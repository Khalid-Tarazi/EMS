<!DOCTYPE html>
<html lang="en">
<!-- This is the sign-in page -->

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XYZ Events - Login or Sign Up</title>
    <link rel="icon" href="Images\logo.PNG" type="image/x-icon">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>
        <div class="welcome-section">
            <h1>Welcome to XYZ Events</h1>
            <p>Your premier platform for managing and attending the best events in the Middle East. Sign in to access
                your homepage and explore our exciting events!</p>
        </div>
    </header>

    <main>
        <!-- The sign-in form -->
        <div class="auth-container">
            <?php
            session_start();
            include 'config.php';

            // Initialize variables to store errors
            $email_empty = $password_empty = "";
            $login_error = false;

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // Validate and sanitize input
                $email = trim($_POST["email"]);
                $password = trim($_POST["password"]);

                // Check if email and password are empty
                if (empty($email)) {
                    $email_empty = "Please enter your email";
                }
                if (empty($password)) {
                    $password_empty = "Please enter your password";
                }

                // If no errors, proceed with login
                if (empty($email_empty) && empty($password_empty)) {
                    if (isset($_POST["isOrganizer"])) {
                        // Organizer login
                        $sql = "SELECT * FROM organizer WHERE email = ? AND password = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("ss", $email, $password);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if ($result->num_rows > 0) {
                            // Organizer found, set session and redirect to home.php
                            $_SESSION["role"] = "organizer";
                            $_SESSION["organizerEmail"] = $email;
                            header("location: home.php");
                            exit();
                        } else {
                            $login_error = true;
                        }
                    } else {
                        // Attendee login
                        $sql = "SELECT attendeeID FROM attendee WHERE email = ? AND password = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("ss", $email, $password);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if ($result->num_rows > 0) {
                            // Attendee found, set session and redirect to home.php
                            $_SESSION["role"] = "attendee";
                            $_SESSION["attendeeEmail"] = $email;
                            header("location: home.php");
                            exit();
                        } else {
                            $login_error = true;
                        }
                    }
                }
            }
            ?>

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <input type="email" name="email" placeholder="Email" required>
                <span style="color: red"><?php echo $email_empty; ?></span><br>
                <input type="password" name="password" placeholder="Password" required>
                <span style="color: red"><?php echo $password_empty; ?></span><br>

                <button type="submit">Sign In</button>
                <div class="checkbox-container">
                    <input type="checkbox" id="organizer" name="isOrganizer">
                    <label for="organizer">Sign in as Organizer</label>
                </div>
            </form>
            <a href="signUp.php">Don't have an account? Sign Up</a>

            <?php
            
            if ($login_error) {
                echo '<p style="color: red;">Invalid email or password.</p>';
            }
            ?>
        </div>
    </main>
</body>

</html>