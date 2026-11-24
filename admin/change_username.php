
<?php
session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

require_once "../config.php";

$message = "";

if (empty($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";
    $token = $_POST["csrf_token"] ?? "";

    if (!hash_equals($_SESSION["csrf_token"], $token)) {
        $message = "Invalid request. Please try again.";
    } elseif (!preg_match('/^[a-zA-Z0-9_]{4,30}$/', $username)) {
        $message = "Use 4-30 letters, numbers or underscores.";
    } else {
        $stmt = $conn->prepare(
            "SELECT password FROM admins WHERE id = ?"
        );
        $stmt->execute([$_SESSION["admin_id"]]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$admin || !password_verify($password, $admin["password"])) {
            $message = "Current password is incorrect.";
        } else {
            try {
                $update = $conn->prepare(
                    "UPDATE admins SET username = ? WHERE id = ?"
                );
                $update->execute([$username, $_SESSION["admin_id"]]);

                $_SESSION["admin_username"] = $username;
                $message = "Username changed successfully!";
            } catch (PDOException $e) {
                $message = "That username may already be in use.";
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
<title>Change Username | Said Khassim</title>
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
</style>
</head>
<body>
<div class="container">
    <h2>Change Administrator Username</h2>

    <?php if ($message): ?>
        <p><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <form method="POST">
        <input type="hidden" name="csrf_token"
               value="<?= htmlspecialchars($_SESSION["csrf_token"]) ?>">

        <label>New Username</label>
        <input type="text" name="username"
               value="SaidKhassim" required minlength="4" maxlength="30">

        <label>Confirm with Your Current Password</label>
        <input type="password" name="password" required>

        <button type="submit">Update Username</button>
    </form>

    <p><a href="dashboard.php">Back to Dashboard</a></p>
</div>
</body>
</html>
