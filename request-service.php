<?php
session_start();
require_once "config.php";

if (empty($_SESSION["request_token"])) {
    $_SESSION["request_token"] = bin2hex(random_bytes(32));
}

$success = "";
$error = "";

$services = [
    "Software Development",
    "Web Development",
    "Database Management",
    "Networking",
    "IT Support",
    "Digital Design",
    "Technology Writing",
    "Digital Marketing",
    "Other Enquiry"
];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $token = $_POST["csrf_token"] ?? "";

    if (!hash_equals($_SESSION["request_token"], $token)) {
        $error = "Invalid request. Please refresh the page.";

    } else {

        $name = trim($_POST["name"] ?? "");
        $email = trim($_POST["email"] ?? "");
        $service = trim($_POST["service"] ?? "");
        $message = trim($_POST["message"] ?? "");

        if (
            $name === "" ||
            !filter_var($email, FILTER_VALIDATE_EMAIL) ||
            !in_array($service, $services, true) ||
            $message === ""
        ) {
            $error = "Please complete all fields correctly.";

        } else {

            try {
                $stmt = $conn->prepare(
                    "INSERT INTO inquiries
                    (visitor_name, visitor_email, service, message)
                    VALUES (?, ?, ?, ?)"
                );

                $stmt->execute([
                    $name,
                    $email,
                    $service,
                    $message
                ]);

                $success = "Your service request has been received successfully!";
                $_SESSION["request_token"] = bin2hex(random_bytes(32));

            } catch (PDOException $e) {
                $error = "Your request could not be submitted. Please try again.";
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
<title>Request a Service | Said Khassim</title>

<style>
* {
    box-sizing: border-box;
}

body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #f3f5f8;
    color: #17243a;
}

header {
    background: #071a35;
    padding: 20px;
    text-align: center;
}

header h2 {
    color: #ffffff;
    margin: 0 0 15px;
}

nav {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 18px;
}

nav a {
    color: white;
    text-decoration: none;
    font-weight: bold;
}

nav a:hover {
    color: #d4af37;
}

.container {
    max-width: 750px;
    margin: 40px auto;
    padding: 30px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
}

h1 {
    color: #071a35;
    text-align: center;
}

.intro {
    text-align: center;
    line-height: 1.7;
}

label {
    display: block;
    margin-top: 18px;
    font-weight: bold;
}

input, select, textarea {
    width: 100%;
    padding: 13px;
    margin-top: 8px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 15px;
}

textarea {
    min-height: 130px;
    resize: vertical;
}

button {
    width: 100%;
    margin-top: 22px;
    padding: 14px;
    background: #071a35;
    color: white;
    border: none;
    border-radius: 6px;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
}

button:hover {
    background: #183c65;
}

.success {
    background: #e5f7e9;
    color: #176b2c;
    padding: 14px;
    border-radius: 6px;
}

.error {
    background: #fde8e8;
    color: #a21d1d;
    padding: 14px;
    border-radius: 6px;
}

footer {
    background: #071a35;
    color: white;
    text-align: center;
    padding: 20px;
    margin-top: 40px;
}

@media (max-width: 600px) {
    .container {
        margin: 20px 12px;
        padding: 20px;
    }
}
</style>
</head>

<body>

<header>
    <h2>SAID KHASSIM</h2>

    <nav>
        <a href="index.php">Home</a>
        <a href="about.php">About</a>
        <a href="skills.php">Skills</a>
        <a href="services.php">Services</a>
        <a href="projects.php">Projects</a>
        <a href="education.php">Education</a>
        <a href="certificates.php">Certificates</a>
        <a href="contact.php">Contact</a>
    </nav>
</header>

<div class="container">

    <h1>Request a Service</h1>

    <p class="intro">
        Tell me what ICT or digital service you need.
        Complete the form below and submit your request.
    </p>

    <?php if ($success !== ""): ?>
        <p class="success">
            <?php echo htmlspecialchars($success); ?>
        </p>
    <?php endif; ?>

    <?php if ($error !== ""): ?>
        <p class="error">
            <?php echo htmlspecialchars($error); ?>
        </p>
    <?php endif; ?>

    <form method="POST">

        <input type="hidden" name="csrf_token"
        value="<?php echo htmlspecialchars($_SESSION["request_token"]); ?>">

        <label>Your Full Name</label>
        <input type="text" name="name" required>

        <label>Email Address</label>
        <input type="email" name="email" required>

        <label>Select a Service</label>
        <select name="service" required>
            <option value="">Choose a service</option>

            <?php foreach ($services as $service): ?>
                <option value="<?php echo htmlspecialchars($service); ?>">
                    <?php echo htmlspecialchars($service); ?>
                </option>
            <?php endforeach; ?>

        </select>

        <label>Describe What You Need</label>
        <textarea name="message"
        placeholder="Explain the service you are requesting..."
        required></textarea>

        <button type="submit">Submit Service Request</button>

    </form>

</div>

<footer>
    <p>SAID KHASSIM</p>
    <p>&copy; 2026 Said Khassim. All rights reserved.</p>
</footer>

</body>
</html>