<?php
session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

require_once "../config.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $current = $_POST["current_password"] ?? "";
    $new = $_POST["new_password"] ?? "";
    $confirm = $_POST["confirm_password"] ?? "";

    $stmt = $conn->prepare(
        "SELECT password FROM admins WHERE id = ?"
    );
    $stmt->execute([$_SESSION["admin_id"]]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$admin || !password_verify($current, $admin["password"])) {
        $message = "Current password is incorrect.";
    } elseif (strlen($new) < 12) {
        $message = "New password must have at least 12 characters.";
    } elseif ($new !== $confirm) {
        $message = "New passwords do not match.";
    } else {
        $hashed = password_hash($new, PASSWORD_DEFAULT);

        $update = $conn->prepare(
            "UPDATE admins SET password = ? WHERE id = ?"
        );
        $update->execute([$hashed, $_SESSION["admin_id"]]);

        $message = "Password changed successfully!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Change Admin Password</title>
<style>
body {
    font-family: Arial, sans-serif;
    background: #f3f5f8;
    padding: 30px;
}
.container {
    max-width: 450px;
    margin: 40px auto;
    background: white;
    padding: 30px;
    border-radius: 10px;
}
h2 { color: #071a35; }
input {
    width: 100%;
    padding: 12px;
    margin: 10px 0 18px;
    box-sizing: border-box;
}
button {
    background: #071a35;
    color: white;
    padding: 13px;
    border: none;
    width: 100%;
    cursor: pointer;
}
.message { color: #176b35; }
</style>
</head>
<body>

<div class="container">
    <h2>Change Your Password</h2>

    <?php if ($message): ?>
        <p class="message">
            <?= htmlspecialchars($message) ?>
        </p>
    <?php endif; ?>

    <form method="POST">
        <label>Current Password</label>
        <input type="password" name="current_password" required>

        <label>New Password</label>
        <input type="password" name="new_password" minlength="12" required>

        <label>Confirm New Password</label>
        <input type="password" name="confirm_password" minlength="12" required>

        <button type="submit">Update Password</button>
    </form>

    <p><a href="dashboard.php">Back to Dashboard</a></p>
</div>

</body>
</html>