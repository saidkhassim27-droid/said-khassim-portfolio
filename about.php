
<?php
require_once "config.php";

$stmt = $conn->query("SELECT * FROM profile ORDER BY id DESC LIMIT 1");
$profile = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$profile) {
    $profile = [
        "full_name" => "Said Khassim",
        "professional_title" => "ICT Professional & Digital Services",
        "biography" => "",
        "profile_image" => "assets/images/said-about.png"
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Me | Said Khassim</title>
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
    <h1>About Me</h1>
    <div class="about-content">
        <div class="about-image">
    <img src="<?php echo htmlspecialchars($profile['profile_image']); ?>" alt="Said Khassim">
</div>

    <h2>Who I Am</h2>

    <p>
    <?php echo nl2br(htmlspecialchars($profile["biography"])); ?>
</p>

    <p>
        I am currently pursuing a Diploma in Information
        and Communication Technology (ICT). I enjoy learning
        new technologies, solving problems and developing
        practical solutions.
    </p>

    <h2>My Education</h2>

    <p>
        Diploma in Information and Communication Technology (ICT)
        – Currently pursuing.
    </p>

    <h2>Leadership Experience</h2>

    <p>
        I have gained leadership experience through student
        leadership roles in primary school, secondary school
        and college. These experiences have helped me develop
        communication, teamwork and organisational skills.
    </p>

    <h2>My Career Goals</h2>

    <p>
        My goal is to grow as an ICT professional, develop
        useful digital solutions and contribute positively
        to organisations and communities through technology.
    </p>

</div>
</section>

<footer>
    <p>&copy; 2026 Said Khassim. All rights reserved.</p>
</footer>

</body>
</html>
