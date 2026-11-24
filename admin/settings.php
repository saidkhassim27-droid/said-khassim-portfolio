
<?php
session_start();
require_once "../config.php";

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

if (empty($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!hash_equals($_SESSION["csrf_token"], $_POST["csrf_token"] ?? "")) {
        $message = "Invalid request. Refresh and try again.";
    } else {
        $settings = [
            "site_title" => trim($_POST["site_title"] ?? ""),
            "site_description" => trim($_POST["site_description"] ?? ""),
            "contact_email" => trim($_POST["contact_email"] ?? ""),
            "contact_phone" => trim($_POST["contact_phone"] ?? ""),
            "site_location" => trim($_POST["site_location"] ?? ""),
            "whatsapp" => trim($_POST["whatsapp"] ?? ""),
            "linkedin" => trim($_POST["linkedin"] ?? ""),
            "github" => trim($_POST["github"] ?? "")
        ];

        $stmt = $conn->prepare(
            "INSERT INTO site_settings (setting_name, setting_value)
             VALUES (?, ?)
             ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)"
        );

        foreach ($settings as $key => $value) {
            $stmt->execute([$key, $value]);
        }

        $message = "Website settings saved successfully!";
    }
}

$rows = $conn->query("SELECT setting_name, setting_value FROM site_settings")->fetchAll(PDO::FETCH_KEY_PAIR);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Website Settings</title>
<style>
body{font-family:Arial;background:#f3f5f8;padding:20px}
.container{max-width:850px;margin:auto;background:white;padding:25px;border-radius:12px}
h1,h2{color:#071a35}
label{display:block;margin-top:14px;font-weight:bold}
input,textarea{width:100%;padding:12px;margin-top:7px;box-sizing:border-box;border:1px solid #ccc;border-radius:6px}
button{background:#071a35;color:white;padding:13px 22px;border:0;border-radius:6px;margin-top:20px;cursor:pointer}
a{color:#183c65}
.success{color:green}
</style>
</head>
<body>
<div class="container">

<h1>Website Settings</h1>
<p><a href="dashboard.php">← Back to Dashboard</a></p>

<p class="success"><?= htmlspecialchars($message) ?></p>

<form method="POST">
<input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION["csrf_token"]) ?>">

<h2>General Information</h2>

<label>Website Title</label>
<input name="site_title" value="<?= htmlspecialchars($rows["site_title"] ?? "SAID KHASSIM - ICT PROFESSIONAL") ?>">

<label>Website Description</label>
<textarea name="site_description"><?= htmlspecialchars($rows["site_description"] ?? "") ?></textarea>

<h2>Contact Information</h2>

<label>Contact Email</label>
<input type="email" name="contact_email" value="<?= htmlspecialchars($rows["contact_email"] ?? "") ?>">

<label>Contact Phone</label>
<input name="contact_phone" value="<?= htmlspecialchars($rows["contact_phone"] ?? "") ?>">

<label>Location</label>
<input name="site_location" value="<?= htmlspecialchars($rows["site_location"] ?? "") ?>">

<h2>Social Media Links</h2>

<label>WhatsApp Link</label>
<input name="whatsapp" value="<?= htmlspecialchars($rows["whatsapp"] ?? "") ?>">

<label>LinkedIn Link</label>
<input name="linkedin" value="<?= htmlspecialchars($rows["linkedin"] ?? "") ?>">

<label>GitHub Link</label>
<input name="github" value="<?= htmlspecialchars($rows["github"] ?? "") ?>">

<button type="submit">Save Website Settings</button>
</form>

</div>
</body>
</html>
