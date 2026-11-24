<?php
require_once "config.php";

$stmt = $conn->query("SELECT * FROM projects ORDER BY id DESC");
$projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Projects | Said Khassim</title>
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

    <h1>My Projects & Work Samples</h1>

    <p>
        A collection of academic projects, practical work
        and technology-related activities that demonstrate
        my learning and development in ICT.
    </p>

    <div class="projects-grid">

    <?php foreach ($projects as $project): ?>

        <div class="project-card">

            <h2>
                <?php echo htmlspecialchars($project["project_name"]); ?>
            </h2>

            <p>
                <?php echo htmlspecialchars($project["description"] ?? ""); ?>
            </p>

            <p>
                <strong>Technologies:</strong>
                <?php echo htmlspecialchars($project["technologies"] ?? ""); ?>
            </p>

            <p>
                <strong>Status:</strong>
                <?php echo htmlspecialchars($project["project_status"] ?? ""); ?>
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
