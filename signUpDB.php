<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="attendee.css">
</head>

<body>
    <?php
    include 'config.php';

    $toastMessage = '';
    $isSuccess = false;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["isOrganizer"])) {

            $organizerName = $_POST["name"];
            $organizerEmail = $_POST["email"];
            $organizerPhoneNumber = $_POST["phoneNumber"];
            $organizerPassword = $_POST["password"];

            $sql = "INSERT INTO organizer (name, email, phoneNumber, password) 
                    VALUES ('$organizerName', '$organizerEmail', '$organizerPhoneNumber', '$organizerPassword')";
            $result = mysqli_query($conn, $sql);

            if ($result) {
                $toastMessage = 'Organizer registered successfully.';
                $isSuccess = true;
            } else {
                $toastMessage = 'Error registering organizer: ' . mysqli_error($conn);
                $isSuccess = false;
            }
        } else {

            $attendeeName = $_POST["name"];
            $attendeeEmail = $_POST["email"];
            $attendeePhoneNumber = $_POST["phoneNumber"];
            $attendeePassword = $_POST["password"];

            $sql = "INSERT INTO attendee (name, email, phoneNumber, password) 
                    VALUES ('$attendeeName', '$attendeeEmail', '$attendeePhoneNumber', '$attendeePassword')";
            $result = mysqli_query($conn, $sql);

            if ($result) {
                $toastMessage = 'Attendee registered successfully.';
                $isSuccess = true;
            } else {
                $toastMessage = 'Error registering attendee: ' . mysqli_error($conn);
                $isSuccess = false;
            }
        }
    }
    ?>

    <?php if ($toastMessage): ?>
        <div id="toast-message" data-message="<?php echo htmlspecialchars($toastMessage); ?>"
            data-success="<?php echo $isSuccess ? 'true' : 'false'; ?>"></div>
    <?php endif; ?>

    <script src="signUpAlert.js"></script>
</body>

</html>