
<?php
require_once "config.php";
$settingsStmt = $conn->query("SELECT * FROM site_settings ORDER BY id DESC LIMIT 1");
$siteSettings = $settingsStmt->fetch(PDO::FETCH_ASSOC);

if (!$siteSettings) {
    $siteSettings = [
        "site_title" => "SAID KHASSIM",
        "site_description" => "ICT Professional & Digital Services",
        "contact_email" => "",
        "contact_phone" => "",
        "site_location" => "",
        "whatsapp" => "",
        "linkedin" => "",
        "github" => ""
    ];
}

$stmt = $conn->query("SELECT * FROM profile ORDER BY id DESC LIMIT 1");
$profile = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$profile) {
    $profile = [
        "full_name" => "Said Khassim",
        "professional_title" => "ICT Professional & Digital Services",
        "biography" => "",
        "profile_image" => "assets/images/said-profile.png"
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo htmlspecialchars($siteSettings['site_description']); ?>">
   <title><?php echo htmlspecialchars($siteSettings["site_title"]); ?> | ICT Professional</title>
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
    <a href="contact.php">Contact</a>
    <a href="request-service.php">Request a Service</a>
</nav>
    </div>
</header>

<section class="hero">
    <div class="container hero-content">
    <div class="hero-image">
    <img src="<?php echo htmlspecialchars($profile["profile_image"]); ?>" alt="Said Khassim">
</div>
<div class="hero-text">
        <p class="welcome">WELCOME TO MY PROFESSIONAL PORTFOLIO</p>
        Hello, I'm <?php echo htmlspecialchars($profile["full_name"]); ?>
        <?php echo htmlspecialchars($profile["professional_title"]); ?>
        <p>
            I am passionate about technology, software development,
            website development and providing digital solutions.
            Explore my work and discover how I can help.
        </p>
        <a class="btn" href="projects.php">View My Projects</a>
        <a class="btn outline" href="contact.php">Contact Me</a>
        </div>
    </div>
</section>

<section class="container intro">
    <h2>What I Do</h2>
    <p>
        I develop digital solutions, create websites, work with databases
        and provide ICT support services.
    </p>
</section>

<footer>
    <h3><?php echo htmlspecialchars(!empty($siteSettings["site_title"]) ? $siteSettings["site_title"] : "SAID KHASSIM"); ?></h3>

    <?php if (!empty($siteSettings["contact_email"])): ?>
        <p>Email:
            <a href="mailto:<?php echo htmlspecialchars($siteSettings["contact_email"]); ?>">
                <?php echo htmlspecialchars($siteSettings["contact_email"]); ?>
            </a>
        </p>
    <?php endif; ?>

    <?php if (!empty($siteSettings["contact_phone"])): ?>
        <p>Phone:
            <?php echo htmlspecialchars($siteSettings["contact_phone"]); ?>
        </p>
    <?php endif; ?>

    <?php if (!empty($siteSettings["site_location"])): ?>
        <p>Location:
            <?php echo htmlspecialchars($siteSettings["site_location"]); ?>
        </p>
    <?php endif; ?>

    <?php if (!empty($siteSettings["linkedin"])): ?>
        <p><a href="<?php echo htmlspecialchars($siteSettings["linkedin"]); ?>" target="_blank">LinkedIn</a></p>
    <?php endif; ?>

    <?php if (!empty($siteSettings["github"])): ?>
        <p><a href="<?php echo htmlspecialchars($siteSettings["github"]); ?>" target="_blank">GitHub</a></p>
    <?php endif; ?>

    <?php if (!empty($siteSettings["whatsapp"])): ?>
    <p>
        <a href="https://wa.me/<?php echo htmlspecialchars(preg_replace('/[^0-9]/', '', $siteSettings["whatsapp"])); ?>" target="_blank">
            Contact me on WhatsApp
        </a>
    </p>
<?php endif; ?>
    <p>&copy; 2026 Said Khassim. All rights reserved.</p>
</footer>

</body>
</html>
