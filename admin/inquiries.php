
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
        $action = $_POST["action"] ?? "";
        $id = (int)($_POST["id"] ?? 0);

        if ($action === "status") {
            $status = $_POST["status"] ?? "New";

            if (in_array($status, ["New", "Read", "Replied"], true)) {
                $stmt = $conn->prepare(
                    "UPDATE inquiries SET status = ? WHERE id = ?"
                );
                $stmt->execute([$status, $id]);
                $message = "Enquiry status updated.";
            }
        }

        if ($action === "delete") {
            $stmt = $conn->prepare("DELETE FROM inquiries WHERE id = ?");
            $stmt->execute([$id]);
            $message = "Enquiry deleted.";
        }
    }
}

$inquiries = $conn->query(
    "SELECT * FROM inquiries ORDER BY created_at DESC"
)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Visitor Enquiries</title>
<style>
body{font-family:Arial;background:#f3f5f8;padding:20px}
.container{max-width:950px;margin:auto;background:white;padding:25px;border-radius:12px}
h1,h2{color:#071a35}
.enquiry{padding:18px;border:1px solid #ddd;border-radius:9px;margin-top:15px}
label{font-weight:bold;display:block;margin-top:12px}
select{padding:10px;border:1px solid #ccc;border-radius:6px}
button{background:#071a35;color:white;padding:10px 16px;border:0;border-radius:6px;margin-top:10px;cursor:pointer}
.delete{background:#b42318}
a{color:#183c65}
.message{color:green}
</style>
</head>
<body>
<div class="container">

<h1>Visitor Enquiries Manager</h1>
<p><a href="dashboard.php">← Back to Dashboard</a></p>

<p class="message"><?= htmlspecialchars($message) ?></p>

<h2>All Visitor Messages</h2>

<?php if (empty($inquiries)): ?>
<p>No visitor enquiries have been received yet.</p>
<?php endif; ?>

<?php foreach ($inquiries as $item): ?>
<div class="enquiry">

<h3><?= htmlspecialchars($item["visitor_name"]) ?></h3>

<p><b>Email:</b> <?= htmlspecialchars($item["visitor_email"]) ?></p>
<p><b>Service:</b> <?= htmlspecialchars($item["service"] ?? "") ?></p>
<p><b>Message:</b></p>
<p><?= nl2br(htmlspecialchars($item["message"])) ?></p>
<p><b>Date:</b> <?= htmlspecialchars($item["created_at"]) ?></p>
<p><b>Current Status:</b> <?= htmlspecialchars($item["status"]) ?></p>

<form method="POST">
<input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION["csrf_token"]) ?>">
<input type="hidden" name="action" value="status">
<input type="hidden" name="id" value="<?= (int)$item["id"] ?>">

<label>Update Status</label>
<select name="status">
<option value="New" <?= $item["status"] === "New" ? "selected" : "" ?>>New</option>
<option value="Read" <?= $item["status"] === "Read" ? "selected" : "" ?>>Read</option>
<option value="Replied" <?= $item["status"] === "Replied" ? "selected" : "" ?>>Replied</option>
</select>
<button type="submit">Update Status</button>
</form>

<form method="POST" onsubmit="return confirm('Delete this enquiry?')">
<input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION["csrf_token"]) ?>">
<input type="hidden" name="action" value="delete">
<input type="hidden" name="id" value="<?= (int)$item["id"] ?>">
<button class="delete" type="submit">Delete Enquiry</button>
</form>

</div>
<?php endforeach; ?>

</div>
</body>
</html>
