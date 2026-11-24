

<?php
session_start();
require_once "config.php";
$settingsStmt = $conn->query("SELECT * FROM site_settings ORDER BY id DESC LIMIT 1");
$siteSettings = $settingsStmt->fetch(PDO::FETCH_ASSOC);

if (!$siteSettings) {
    $siteSettings = [];
}

if (empty($_SESSION["contact_token"])) {
    $_SESSION["contact_token"] = bin2hex(random_bytes(32));
}

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (
        !isset($_POST["csrf_token"]) ||
        !hash_equals($_SESSION["contact_token"], $_POST["csrf_token"])
    ) {
        $error = "Please refresh the page and try again.";
    } else {
        $name = trim($_POST["name"] ?? "");
        $email = trim($_POST["email"] ?? "");
        $service = trim($_POST["service"] ?? "");
        $message = trim($_POST["message"] ?? "");

        if ($name === "" || !filter_var($email, FILTER_VALIDATE_EMAIL) || $message === "") {
            $error = "Please enter your name, a valid email and your message.";
        } else {
            try {
                $stmt = $conn->prepare(
                    "INSERT INTO inquiries
                    (visitor_name, visitor_email, service, message)
                    VALUES (?, ?, ?, ?)"
                );

                $stmt->execute([$name, $email, $service, $message]);

                $success = "Thank you! Your message has been received.";
                $_SESSION["contact_token"] = bin2hex(random_bytes(32));
            } catch (PDOException $e) {
                $error = "Sorry, your message could not be saved. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact | Said Khassim</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<header>
    <div class="container nav">
        <h2>SAID <span>KHASSIM</span></h2>
        <nav>
    <a href="index.php">Home</a>
    <a href="about.php">About</a>
    <a href="skills.php">Skills</a>
    <a href="services.php">Services</a>
    <a href="projects.php">Projects</a>
    <a href="education.php">Education & Experience</a>
    <a href="certificates.php">Certificates</a>
    <a href="request-service.php">Request a Service</a>
    <a href="contact.php">Contact</a>
</nav>
    </div>
</header>

<section class="container intro contact-section">

    <h1>Get In Touch</h1>

    <p class="contact-intro">
        Have a project, opportunity or question?
        I would be happy to hear from you.
        Send me a message using the form below.
    </p>

    <div class="contact-container">

        <div class="contact-info">
            <h2>Let's Connect</h2>

            <p>
                I welcome enquiries about ICT projects,
                web development, database solutions,
                technical support and other digital services.
            </p>

            <p><strong>Name:</strong> Said Khassim</p>
<p><strong>Professional Area:</strong> ICT & Digital Services</p>

<?php if (!empty($siteSettings["contact_email"])): ?>
    <p><strong>Email:</strong>
        <?php echo htmlspecialchars($siteSettings["contact_email"]); ?>
    </p>
<?php endif; ?>

<?php if (!empty($siteSettings["contact_phone"])): ?>
    <p><strong>Phone:</strong>
        <?php echo htmlspecialchars($siteSettings["contact_phone"]); ?>
    </p>
<?php endif; ?>

<?php if (!empty($siteSettings["site_location"])): ?>
    <p><strong>Location:</strong>
        <?php echo htmlspecialchars($siteSettings["site_location"]); ?>
    </p>
<?php endif; ?>
        </div>

        <div class="contact-form">
            <h2>Send Me a Message</h2>
            <?php if ($success): ?>
    <p style="color: green;">
        <?php echo htmlspecialchars($success); ?>
    </p>
<?php endif; ?>

<?php if ($error): ?>
    <p style="color: red;">
        <?php echo htmlspecialchars($error); ?>
    </p>
<?php endif; ?>

            <form action="" method="POST">
<input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['contact_token']); ?>">

                <label for="name">Your Name</label>
                <input type="text" id="name" name="name"
                       placeholder="Enter your full name" required>

                <label for="email">Email Address</label>
                <input type="email" id="email" name="email"
                       placeholder="Enter your email" required>

                <label for="service">Service Interested In</label>
                <select id="service" name="service" required>
                    <option value="">Select a service</option>
                    <option>Software Development</option>
                    <option>Web Development</option>
                    <option>Database Management</option>
                    <option>Networking</option>
                    <option>IT Support</option>
                    <option>Digital Design</option>
                    <option>Technology Writing</option>
                    <option>Digital Marketing</option>
                    <option>Other Enquiry</option>
                </select>

                <label for="message">Your Message</label>
                <textarea id="message" name="message"
                          rows="6"
                          placeholder="Write your message here..."
                          required></textarea>

                <button type="submit">Send Message</button>

            </form>
        </div>

    </div>

</section>

<footer>
    <p>&copy; 2026 Said Khassim. All rights reserved.</p>
</footer>

</body>
</html>
