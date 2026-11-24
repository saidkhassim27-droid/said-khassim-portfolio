
<?php
require_once "config.php";

$educationStmt = $conn->query("SELECT * FROM education ORDER BY id DESC");
$education = $educationStmt->fetchAll(PDO::FETCH_ASSOC);

$experienceStmt = $conn->query("SELECT * FROM experience ORDER BY id DESC");
$experience = $experienceStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Education & Experience | Said Khassim</title>
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

    <h1>Education & Experience</h1>

    
<h2>Education</h2>

<?php foreach ($education as $item): ?>
    <div class="education-card">
        <h3><?php echo htmlspecialchars($item["qualification"]); ?></h3>

        <p>
            <strong>Institution:</strong>
            <?php echo htmlspecialchars($item["institution"] ?? ""); ?>
        </p>

        <p>
            <strong>Status:</strong>
            <?php echo htmlspecialchars($item["status"] ?? ""); ?>
        </p>

        <p>
            <?php echo htmlspecialchars($item["description"] ?? ""); ?>
        </p>
    </div>
<?php endforeach; ?>

<h2>Experience</h2>

<?php foreach ($experience as $item): ?>
    <div class="education-card">
        <h3><?php echo htmlspecialchars($item["job_title"]); ?></h3>

        <p>
            <strong>Organisation:</strong>
            <?php echo htmlspecialchars($item["organisation"] ?? ""); ?>
        </p>

        <p>
            <?php echo htmlspecialchars($item["description"] ?? ""); ?>
        </p>
    </div>
<?php endforeach; ?>



</section>

<footer>
    <p>&copy; 2026 Said Khassim. All rights reserved.</p>
</footer>

</body>
</html>
