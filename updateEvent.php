<?php
include ("config.php");

$eventID = $_GET['eventID'];
$sql = "SELECT * FROM event WHERE eventID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $eventID);
$stmt->execute();
$result = $stmt->get_result();
$event = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $eventName = $_POST['eventName'];
    $eventDate = $_POST['eventDate'];
    $location = $_POST['location'];
    $capacity = $_POST['capacity'];
    $description = $_POST['description'];

    // Check for conflicts before updating
    $checkSql = "SELECT * FROM event WHERE eventDate = ? AND eventID != ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("si", $eventDate, $eventID);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {   //there is conflict
        echo "<script>
                alert('Error: Event with the same date already exists. Please choose a different date.');
                window.history.back();
              </script>";
    } else {
        $sql = "UPDATE event SET eventName=?, eventDate=?, location=?, capacity=?, description=? WHERE eventID=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssisi", $eventName, $eventDate, $location, $capacity, $description, $eventID);

        if ($stmt->execute()) {
            echo "<script>
                    alert('Event updated successfully');
                    window.location.href = 'displayEvents.php';
                  </script>";
        } else {
            echo "Error updating event: " . $stmt->error;
        }

        $stmt->close();
    }
    $checkStmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Event</title>
    <link rel="icon" href="Images\logo.PNG" type="image/x-icon">
    <link rel="stylesheet" href="updateEvent.css">
    <link rel="stylesheet" href="NavBar/Navbar.css">
</head>

<body>

    <?php include 'NavBar\organizerNav.php'; ?>

    <h2>Update Event</h2>
    <form action="updateEvent.php?eventID=<?php echo $eventID; ?>" method="POST">
        <label for="eventName">Event Name:</label>
        <input type="text" id="eventName" name="eventName" value="<?php echo htmlspecialchars($event['eventName']); ?>"
            required><br><br>

        <label for="eventDate">Event Date:</label>
        <input type="date" id="eventDate" name="eventDate" value="<?php echo htmlspecialchars($event['eventDate']); ?>"
            required><br><br>

        <label for="location">Location:</label>
        <input type="text" id="location" name="location" value="<?php echo htmlspecialchars($event['location']); ?>"
            required><br><br>

        <label for="capacity">Capacity:</label>
        <input type="number" id="capacity" name="capacity" value="<?php echo htmlspecialchars($event['capacity']); ?>"
            required><br><br>

        <label for="description">Description:</label>
        <textarea id="description" name="description"
            required><?php echo htmlspecialchars($event['description']); ?></textarea><br><br>

        <input type="submit" value="Update Event">
    </form>
    <br><br>
    <br><br>
    <br><br>
    <?php include 'Footer/footer.php'; ?>
    <script src="footer.js"></script>

</body>

</html>