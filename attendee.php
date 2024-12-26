<?php
session_start();
include ("config.php");

// Check if attendee is logged in
if (!isset($_SESSION["attendeeEmail"])) {
    header("location: index.php");
    exit();
}

$attendeeEmail = $_SESSION["attendeeEmail"];

// Retrieve attendeeID based on the logged in email
$sqlAttendeeID = "SELECT attendeeID FROM attendee WHERE email = ?";
$stmtAttendeeID = $conn->prepare($sqlAttendeeID);
$stmtAttendeeID->bind_param("s", $attendeeEmail);
$stmtAttendeeID->execute();
$resultAttendeeID = $stmtAttendeeID->get_result();  //helps prevent SQL injection by separating SQL code from user input.

if ($resultAttendeeID->num_rows > 0) {
    $rowAttendeeID = $resultAttendeeID->fetch_assoc();
    $attendeeID = $rowAttendeeID["attendeeID"];
} else {
    // Handle case where attendeeID retrieval fails
    die("Error: Unable to retrieve attendeeID.");
}

// Initialize toast variables
$toastMessage = '';
$isSuccess = false;

// Process ticket selection and purchase
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['eventId']) && isset($_POST['ticketType'])) {
    $eventId = $_POST['eventId'];
    $ticketType = $_POST['ticketType'];
    $price = ($ticketType === 'vip') ? 40.0 : 20.0;

    // Verify eventID exists in the database
    $sqlCheckEvent = "SELECT * FROM event WHERE eventID = ?";
    $stmtCheckEvent = $conn->prepare($sqlCheckEvent);
    $stmtCheckEvent->bind_param("i", $eventId);
    $stmtCheckEvent->execute();
    $resultCheckEvent = $stmtCheckEvent->get_result();

    if ($resultCheckEvent->num_rows == 0) {
        die("Error: Invalid event selected.");
    }

    // Check if a ticket of the selected type is available for the event
    $sqlCheckTicket = "SELECT * FROM ticket WHERE eventID = ? AND ticketType = ? AND attendeeID IS NULL LIMIT 1";
    $stmtCheckTicket = $conn->prepare($sqlCheckTicket);
    $stmtCheckTicket->bind_param("is", $eventId, $ticketType);
    $stmtCheckTicket->execute();
    $resultCheckTicket = $stmtCheckTicket->get_result();

    if ($resultCheckTicket->num_rows > 0) {
        $ticket = $resultCheckTicket->fetch_assoc();
        $ticketID = $ticket['ticketID'];

        // Update the ticket with attendeeID and price
        $sqlUpdateTicket = "UPDATE ticket SET attendeeID = ?, price = ? WHERE ticketID = ?";
        $stmtUpdateTicket = $conn->prepare($sqlUpdateTicket);
        $stmtUpdateTicket->bind_param("idi", $attendeeID, $price, $ticketID);

        if ($stmtUpdateTicket->execute()) {
            $toastMessage = 'Ticket purchased successfully.';       //alert
            $isSuccess = true;
        } else {
            $toastMessage = 'Error purchasing ticket: ' . $stmtUpdateTicket->error; //alert
            $isSuccess = false;
        }
    } else {
        // Insert a new ticket if no available tickets of the selected type
        $sqlInsertTicket = "INSERT INTO ticket (eventID, ticketType, price, attendeeID) VALUES (?, ?, ?, ?)";
        $stmtInsertTicket = $conn->prepare($sqlInsertTicket);
        $stmtInsertTicket->bind_param("issi", $eventId, $ticketType, $price, $attendeeID);

        if ($stmtInsertTicket->execute()) {
            $toastMessage = 'New ticket purchased successfully.';   //alert
            $isSuccess = true;
        } else {
            $toastMessage = 'Error purchasing new ticket: ' . $stmtInsertTicket->error; //alert
            $isSuccess = false;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Event</title>
    <link rel="stylesheet" href="attendee.css">
    <link rel="stylesheet" href="NavBar/Navbar.css">

</head>

<body>
     <?php if ($toastMessage): ?>    <!-- for event listener in alert.js -->
        <div id="toast-message" data-message="<?php echo htmlspecialchars($toastMessage); ?>"
            data-success="<?php echo $isSuccess ? 'true' : 'false'; ?>">
        </div>
    <?php endif; ?>

    <nav class="navbar">
        <div class="navbar-brand">
            <a href="home.php">XYZ Events</a>
        </div>
        <div class="navbar-buttons">
            <a href="home.php" class="btn">Home</a>
            <a href="attendee.php" class="btn">Select Event</a>
            <a href="index.php" class="btn">Log Out</a>
        </div>
    </nav>

    <div class="container">
        <h2>Select Your Preferred Event</h2>
        <form action="attendee.php" method="POST">
            <label for="eventId">Select Event:</label><br>
            <select name="eventId" id="eventId" required>
                <option value="">Select Event</option>
                <?php
                $sql = "SELECT * FROM event";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value=\"" . $row['eventID'] . "\">" . $row['eventName'] . " - " . $row['eventDate'] . "</option>";
                    }
                } else {
                    echo "<option value=\"\">No events available</option>";
                }
                ?>
            </select><br><br>

            <h2>Select Ticket Type</h2>
            <input type="radio" id="ticketTypeNormal" name="ticketType" value="normal" required>
            <label for="ticketTypeNormal"> Normal ($20.0)</label><br>
            <input type="radio" id="ticketTypeVIP" name="ticketType" value="vip">
            <label for="ticketTypeVIP"> VIP ($40.0)</label><br><br>

            <input type="submit" value="Select Event and Ticket">
        </form>

        <h2>Your Purchased Tickets</h2>
        <?php
        // Fetch attendee's purchased tickets
        $sqlAttendeeTickets = "SELECT * FROM ticket WHERE attendeeID = ?";
        $stmtAttendeeTickets = $conn->prepare($sqlAttendeeTickets);
        $stmtAttendeeTickets->bind_param("i", $attendeeID);
        $stmtAttendeeTickets->execute();
        $resultAttendeeTickets = $stmtAttendeeTickets->get_result();

        if ($resultAttendeeTickets->num_rows > 0) {
            echo "<table border='1'>";
            echo "<tr><th>Ticket ID</th><th>Event ID</th><th>Ticket Type</th><th>Price</th></tr>";
            while ($row = $resultAttendeeTickets->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['ticketID'] . "</td>";
                echo "<td>" . $row['eventID'] . "</td>";
                echo "<td>" . $row['ticketType'] . "</td>";
                echo "<td>$" . $row['price'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No tickets purchased yet.</p>";
        }
        ?>
    </div>

    <?php include 'Footer/footer.php'; ?>
    <script src="Footer/footer.js"></script>
    <script src="alert.js"></script>
</body>

</html>