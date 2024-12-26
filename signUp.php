<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - EMS</title>
    <link rel="icon" href="Images\logo.PNG" type="image/x-icon">
    <link rel="stylesheet" href="style.css">

</head>

<body>
    <div class="welcome-section">
        <h1>Welcome to XYZ Events</h1>
        <p>
            Please Fill the required information
        </p>
    </div>

    <!-- This is the Sign Up Form! -->
    <div class="auth-container">
        <form action="signUpDB.php" method="POST">
            <input type="text" name="name" placeholder="name" required>
            <input type="email" name="email" placeholder="email" required>
            <input type="text" name="phoneNumber" placeholder="phone number" required>
            <input type="password" name="password" placeholder="password" required>
            <button type="submit">Sign Up</button>
            <div class="checkbox-container">
                <input type="checkbox" id="organizer" name="isOrganizer"> <br>
                <label for="organizer">Sign up as Organizer</label>
            </div>
        </form>
        <a href="index.php">Already have an account? Sign In</a>
    </div>

</body>

</html>