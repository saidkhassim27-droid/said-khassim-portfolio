
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
$error = "";

$stmt = $conn->query("SELECT * FROM profile ORDER BY id ASC LIMIT 1");
$profile = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!hash_equals($_SESSION["csrf_token"], $_POST["csrf_token"] ?? "")) {
        $error = "Invalid request. Please refresh and try again.";
    } else {
        $name = trim($_POST["full_name"] ?? "");
        $title = trim($_POST["professional_title"] ?? "");
        $bio = trim($_POST["biography"] ?? "");
        $email = trim($_POST["email"] ?? "");
        $phone = trim($_POST["phone"] ?? "");
        $location = trim($_POST["location"] ?? "");
        $image = $profile["profile_image"] ?? "";

        if ($name === "") {
            $error = "Full name is required.";
        } elseif (!empty($_FILES["profile_image"]["name"])) {
            $file = $_FILES["profile_image"];

            if ($file["error"] !== UPLOAD_ERR_OK || $file["size"] > 2 * 1024 * 1024) {
                $error = "Image upload failed or file is larger than 2MB.";
            } else {
                $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file["tmp_name"]);
                $allowed = [
                    "image/jpeg" => "jpg",
                    "image/png" => "png",
                    "image/webp" => "webp"
                ];

                if (!isset($allowed[$mime])) {
                    $error = "Only JPG, PNG and WEBP images are allowed.";
                } else {
                    $filename = bin2hex(random_bytes(16)) . "." . $allowed[$mime];
                    $destination = "../uploads/" . $filename;

                    if (!move_uploaded_file($file["tmp_name"], $destination)) {
                        $error = "Could not save the uploaded image.";
                    } else {
                        $image = "uploads/" . $filename;
                    }
                }
            }
        }

        if ($error === "") {
            if ($profile) {
                $sql = "UPDATE profile SET full_name=?, professional_title=?, biography=?, profile_image=?, email=?, phone=?, location=? WHERE id=?";
                $save = $conn->prepare($sql);
                $save->execute([$name, $title, $bio, $image, $email, $phone, $location, $profile["id"]]);
            } else {
                $sql = "INSERT INTO profile (full_name, professional_title, biography, profile_image, email, phone, location) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $save = $conn->prepare($sql);
                $save->execute([$name, $title, $bio, $image, $email, $phone, $location]);
            }

            $message = "Profile saved successfully!";
            $stmt = $conn->query("SELECT * FROM profile ORDER BY id ASC LIMIT 1");
            $profile = $stmt->fetch(PDO::FETCH_ASSOC);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Profile</title>
<link rel="stylesheet" href="../assets/css/style.css">
<style>
body { background:#f3f5f8; font-family:Arial,sans-serif; }
.manager { max-width:800px; margin:35px auto; padding:25px; background:white; border-radius:12px; }
.manager h1 { color:#071a35; }
.manager label { display:block; margin-top:15px; font-weight:bold; }
.manager input,.manager textarea { width:100%; padding:12px; margin-top:7px; border:1px solid #ccc; border-radius:6px; box-sizing:border-box; }
.manager textarea { min-height:120px; }
.manager button { margin-top:20px; padding:13px 25px; background:#071a35; color:white; border:0; border-radius:6px; cursor:pointer; }
.manager a { color:#183c65; }
.success { color:green; }
.error { color:#b00020; }
</style>
</head>
<body>
<div class="manager">
<h1>Manage My Profile</h1>
<p><a href="dashboard.php">← Back to Dashboard</a></p>

<?php if ($message): ?><p class="success"><?= htmlspecialchars($message) ?></p><?php endif; ?>
<?php if ($error): ?><p class="error"><?= htmlspecialchars($error) ?></p><?php endif; ?>

<form method="POST" enctype="multipart/form-data">
<input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION["csrf_token"]) ?>">

<label>Full Name</label>
<input type="text" name="full_name" required value="<?= htmlspecialchars($profile["full_name"] ?? "Said Khassim") ?>">

<label>Professional Title</label>
<input type="text" name="professional_title" value="<?= htmlspecialchars($profile["professional_title"] ?? "ICT Professional & Digital Services") ?>">

<label>Biography</label>
<textarea name="biography"><?= htmlspecialchars($profile["biography"] ?? "") ?></textarea>

<label>Email</label>
<input type="email" name="email" value="<?= htmlspecialchars($profile["email"] ?? "") ?>">

<label>Phone</label>
<input type="text" name="phone" value="<?= htmlspecialchars($profile["phone"] ?? "") ?>">

<label>Location</label>
<input type="text" name="location" value="<?= htmlspecialchars($profile["location"] ?? "") ?>">

<label>Profile Photo (JPG, PNG or WEBP, max 2MB)</label>
<input type="file" name="profile_image" accept=".jpg,.jpeg,.png,.webp">

<?php if (!empty($profile["profile_image"])): ?>
<p>Current photo:</p>
<img src="../<?= htmlspecialchars($profile["profile_image"]) ?>" width="120" alt="Current profile photo">
<?php endif; ?>

<button type="submit">Save Profile</button>
</form>
</div>
</body>
</html>
