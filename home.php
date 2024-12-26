<?php
session_start();

$role = isset($_SESSION['role']) ? $_SESSION['role'] : 'guest';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EMS Home</title>
    <link rel="icon" href="Images\logo.PNG" type="image/x-icon">
    <link rel="stylesheet" href="home.css">
    <link rel="stylesheet" href="NavBar\Navbar.css">
</head>

<body>

    <?php
    if ($role == 'attendee') {
        include 'NavBar\attendeeNav.php';
    } elseif ($role == 'organizer') {
        include 'NavBar\organizerNav.php';
    } else {    //guest
        echo '<nav class="navbar">                          
            <div class="navbar-brand"><a href="">XYZ Events</a></div>
            <div class="navbar-buttons">
                <a href="index.php" class="btn">Log In</a>
            </div>
          </nav>';
    }
    ?>

    <div class="content">
        <h1>Welcome to the Event Management System</h1>
        <?php if ($role == 'attendee'): ?>
            <p>As an attendee, you can browse and select events to participate in.</p>
        <?php elseif ($role == 'organizer'): ?>
            <p>As an organizer, you can create, view, and manage your events.</p>
        <?php else: ?>
            <p>Please log in to access your dashboard.</p>
        <?php endif; ?>

        <section class="company-vision">
            <h2>Our Vision</h2>
            <p>XYZ Events is committed to bringing the most exciting and memorable events to the Middle East. Our vision
                is to be the leading platform for event management, providing seamless experiences for both organizers
                and attendees.</p>
        </section>

        <section class="media">
            <h2>Gallery</h2>
            <div class="gallery">
                <img src="Images\event1.jpg" alt="Event 1">
                <img src="Images\event2.jpg" alt="Event 2">
                <img src="Images\event3.jpeg" alt="Event 3">
            </div>
            <h2>Promo Video</h2>
            <div class="video">
                <video width="560" height="315" controls>
                    <source src="Images\promoVideo.mp4" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            </div>
        </section>

        <section class="features">
            <h2>Features</h2>
            <div class="feature-item">
                <h3>Easy Event Management</h3>
                <p>Create, manage, and track events effortlessly with our intuitive platform.</p>
            </div>
            <div class="feature-item">
                <h3>Seamless Registration</h3>
                <p>Attendees can quickly register and get tickets for their favorite events.</p>
            </div>
            <div class="feature-item">
                <h3>Real-Time Analytics</h3>
                <p>Get insights into event performance and attendee engagement with our analytics tools.</p>
            </div>
        </section>

        <section class="testimonials">
            <h2>Testimonials</h2>
            <div class="testimonial-item">
                <p>"XYZ Events made our annual conference a huge success. The platform is incredibly user-friendly."</p>
                <p>- James Smith, Event Organizer</p>
            </div>
            <div class="testimonial-item">
                <p>"As an attendee, I found it super easy to register and get updates about the event."</p>
                <p>- Eren Yeager, Attendee</p>
            </div>
        </section>

        <section class="upcoming-events">
            <h2>Upcoming Events</h2>
            <ul>
                <li><strong>Consulate Meeting:</strong> July 12, 2024 - Aqaba</li>
                <li><strong>Gaming Event:</strong> August 08, 2024 - Amman</li>
                <li><strong>Music Concert:</strong> August 30, 2024 - Amman</li>
            </ul>
        </section>

    </div>

    <br><br>
    <?php include 'Footer/footer.php'; ?>
    <script src="Footer\footer.js"></script>
</body>

</html>