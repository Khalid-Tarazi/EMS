<?php
session_start();
include ("config.php");

if (!isset($_SESSION["organizerEmail"])) {
    header("location: index.php");
    exit();
}

$organizerEmail = $_SESSION["organizerEmail"];

// Fetch organizer's ID based on the logged-in email
$sqlOrganizerID = "SELECT organizerID FROM organizer WHERE email = '$organizerEmail'";
$resultOrganizerID = mysqli_query($conn, $sqlOrganizerID);

if ($resultOrganizerID) {
    $rowOrganizerID = mysqli_fetch_assoc($resultOrganizerID);
    $organizerID = $rowOrganizerID['organizerID'];

    // Query to fetch events associated with the logged-in organizerID
    $sql = "SELECT e.eventID, e.eventName, e.eventDate, e.location, e.capacity, e.description
            FROM event e
            INNER JOIN event eo ON e.eventID = eo.eventID
            WHERE eo.organizerID = $organizerID";

    $result = mysqli_query($conn, $sql);
} else {
    echo "Error fetching organizer ID: " . mysqli_error($conn);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events List</title>
    <link rel="icon" href="Images\logo.PNG" type="image/x-icon">
    <link rel="stylesheet" href="displayEvents.css">
    <link rel="stylesheet" href="NavBar/Navbar.css">
</head>

<body>
    <?php include 'NavBar/organizerNav.php'; ?>

    <h2>The Events List</h2>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Date</th>
                <th>Location</th>
                <th>Capacity</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td data-label='Name'>" . htmlspecialchars($row["eventName"]) . "</td>";
                    echo "<td data-label='Date'>" . htmlspecialchars($row["eventDate"]) . "</td>";
                    echo "<td data-label='Location'>" . htmlspecialchars($row["location"]) . "</td>";
                    echo "<td data-label='Capacity'>" . htmlspecialchars($row["capacity"]) . "</td>";
                    echo "<td data-label='Description'>" . htmlspecialchars($row["description"]) . "</td>";
                    echo "<td data-label='Actions'>
                            <a href='updateEvent.php?eventID=" . $row["eventID"] . "'>Update</a> | 
                            <a href='deleteEvent.php?eventID=" . $row["eventID"] . "' onclick='return confirm(\"Are you sure you want to delete this event?\");'>Delete</a>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No events found</td></tr>";
            }
            ?>
        </tbody>
    </table>
    <br><br><br>
    <br><br><br>
    <br><br><br>
    <br><br><br>
    <br><br><br>
    <br><br><br>
    <br><br><br>
    <?php include 'Footer/footer.php'; ?>
    <script src="footer.js"></script>

</body>

</html>