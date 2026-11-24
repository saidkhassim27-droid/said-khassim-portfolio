
<?php
require_once "config.php";

$stmt = $conn->query("SELECT * FROM skills ORDER BY id DESC");
$skills = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Skills | Said Khassim</title>
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
    <h1>My Skills</h1>
    

<div class="skill-grid">

    <?php foreach ($skills as $skill): ?>
        <div class="skill-card">
            <h2><?php echo htmlspecialchars($skill["skill_name"]); ?></h2>

            <p>
                <?php echo htmlspecialchars($skill["description"] ?? ""); ?>
            </p>

            <small>
                <?php echo htmlspecialchars($skill["category"] ?? ""); ?>
            </small>
        </div>
    <?php endforeach; ?>

</div>


</section>

<footer>
    <p>&copy; 2026 Said Khassim. All rights reserved.</p>
</footer>

</body>
</html>
