
<?php
require_once "config.php";

$stmt = $conn->query("SELECT * FROM services ORDER BY id DESC");
$services = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Services | Said Khassim</title>
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

<section class="container intro">
    <h1>My Services</h1>
    <p>Digital and ICT solutions designed to meet different technology needs.</p>

   
<div class="services-grid">

    <?php foreach ($services as $service): ?>
        <div class="service-card">
            <h2><?php echo htmlspecialchars($service["service_name"]); ?></h2>

            <p>
                <?php echo htmlspecialchars($service["description"]); ?>
            </p>
        </div>
    <?php endforeach; ?>

</div>
 
</section>

<footer>
    <p>&copy; 2026 Said Khassim. All rights reserved.</p>
</footer>

</body>
</html>
