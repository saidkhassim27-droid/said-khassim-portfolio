
<?php
require_once "config.php";

$stmt = $conn->query("SELECT * FROM certificates ORDER BY id DESC");
$certificates = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificates | Said Khassim</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<header>
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

<section class="projects-section">
    <h1>Certificates & Recognition</h1>

    <p>Professional recognition and leadership achievements.</p>

    <div class="projects-grid">

        <?php if (count($certificates) > 0): ?>

            <?php foreach ($certificates as $certificate): ?>

                <div class="project-card">

                    <h2>
                        <?php echo htmlspecialchars($certificate["certificate_name"]); ?>
                    </h2>

                    <p>
                        <strong>Issuing Organisation:</strong>
                        <?php echo htmlspecialchars($certificate["issuing_organisation"] ?? ""); ?>
                    </p>

                    <?php if (!empty($certificate["issue_date"])): ?>
                        <p>
                            <strong>Date Issued:</strong>
                            <?php echo htmlspecialchars($certificate["issue_date"]); ?>
                        </p>
                    <?php endif; ?>

                    <?php if (!empty($certificate["certificate_file"])): ?>
                        <p>
                            <a href="<?php echo htmlspecialchars($certificate["certificate_file"]); ?>" target="_blank" rel="noopener">
                                View Certificate
                            </a>
                        </p>
                    <?php endif; ?>

                </div>

            <?php endforeach; ?>

        <?php else: ?>

            <p>Certificates and recognition will be displayed here soon.</p>

        <?php endif; ?>

    </div>
</section>

<footer>
    <p>&copy; <?php echo date("Y"); ?> Said Khassim. All Rights Reserved.</p>
</footer>

</body>
</html>
