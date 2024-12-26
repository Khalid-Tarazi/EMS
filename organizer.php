<?php
session_start();
include ("config.php");

if (!isset($_SESSION["organizerEmail"])) {
    header("location: index.php"); 
    exit();
}

$organizerEmail = $_SESSION["organizerEmail"];

// Fetch organizerID based on organizerEmail
$stmtOrganizerID = $conn->prepare("SELECT organizerID FROM organizer WHERE email = ?");
$stmtOrganizerID->bind_param("s", $organizerEmail);
$stmtOrganizerID->execute();
$resultOrganizerID = $stmtOrganizerID->get_result();

if ($resultOrganizerID->num_rows > 0) {
    $rowOrganizerID = $resultOrganizerID->fetch_assoc();
    $organizerID = $rowOrganizerID["organizerID"];
} else {
    
    echo "Error: Organizer ID not found for email $organizerEmail";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {     // Process form submission to create a new event
    $name = $_POST['name'];
    $date = $_POST['date'];
    $location = $_POST['location'];
    $capacity = $_POST['capacity'];
    $description = $_POST['description'];

    $checkSql = "SELECT * FROM event WHERE eventDate = ?";      // Check for date conflicts before inserting
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("s", $date);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {   //same date?
        echo "<script>
                alert('Error: Event with the same date already exists. Please choose a different date.');
                window.history.back();
              </script>";
    } else {
        // Insert the new event into the database
        $sql = "INSERT INTO event (eventName, eventDate, location, capacity, description, organizerID) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("sssisi", $name, $date, $location, $capacity, $description, $organizerID);
            if ($stmt->execute()) {
                echo "<script>
                        alert('New event created successfully');
                        window.location.href = 'displayEvents.php';
                      </script>";
            } else {
                echo "Error: " . $stmt->error;
            }
        } else {
            echo "Error preparing statement: " . $conn->error;
        }
    }
    $checkStmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Event</title>
    <link rel="icon" href="Images\logo.PNG" type="image/x-icon">
    <link rel="stylesheet" href="organizer.css">
    <link rel="stylesheet" href="NavBar/Navbar.css">
</head>

<body>
    <?php include 'NavBar\organizerNav.php'; ?>

    <h2>Create a New Event</h2>
    <form action="organizer.php" method="POST">
        <label for="name">Event Name:</label>
        <input type="text" id="name" name="name" required><br>

        <label for="date">Event Date:</label>
        <input type="date" id="date" name="date" required><br>

        <label for="location">Location:</label>
        <input type="text" id="location" name="location" required><br>

        <label for="capacity">Capacity:</label>
        <input type="number" id="capacity" name="capacity" required><br>

        <label for="description">Description:</label>
        <textarea id="description" name="description" required></textarea><br>

        <input type="submit" value="Create Event">
    </form>
    <br><br><br>
    <br><br><br>

    <?php include 'Footer/footer.php'; ?>
    <script src="footer.js"></script>
</body>

</html>
