<?php
include("config.php");

$eventID = $_GET['eventID'];

$sql = "DELETE FROM event WHERE eventID=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $eventID);

if ($stmt->execute()) {
    echo "Event deleted successfully";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();

header("Location: displayEvents.php");
?>
