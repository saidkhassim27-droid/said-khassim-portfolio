
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

        if ($action === "add") {
            $name = trim($_POST["service_name"] ?? "");
            $description = trim($_POST["description"] ?? "");

            if ($name !== "") {
                $stmt = $conn->prepare(
                    "INSERT INTO services (service_name, description) VALUES (?, ?)"
                );
                $stmt->execute([$name, $description]);
                $message = "Service added successfully!";
            }
        }

       
if ($action === "edit") {
    $id = (int)($_POST["id"] ?? 0);
    $name = trim($_POST["service_name"] ?? "");
    $description = trim($_POST["description"] ?? "");

    if ($id > 0 && $name !== "") {
        $stmt = $conn->prepare(
            "UPDATE services SET service_name = ?, description = ? WHERE id = ?"
        );
        $stmt->execute([$name, $description, $id]);
        $message = "Service updated successfully!";
    }
}

        if ($action === "delete") {
            $stmt = $conn->prepare("DELETE FROM services WHERE id = ?");
            $stmt->execute([(int)($_POST["id"] ?? 0)]);
            $message = "Service deleted.";
        }
    }
}

$services = $conn->query("SELECT * FROM services ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Services Manager</title>
<style>
body{font-family:Arial;background:#f3f5f8;padding:20px}
.container{max-width:850px;margin:auto;background:white;padding:25px;border-radius:12px}
h1,h2{color:#071a35}
label{display:block;margin-top:15px;font-weight:bold}
input,textarea{width:100%;padding:12px;margin-top:7px;box-sizing:border-box;border:1px solid #ccc;border-radius:6px}
textarea{min-height:110px}
button{background:#071a35;color:white;padding:12px 20px;border:0;border-radius:6px;margin-top:12px;cursor:pointer}
.delete{background:#b42318}
.service{padding:15px;border:1px solid #ddd;border-radius:8px;margin-top:12px}
a{color:#183c65}
</style>
</head>
<body>
<div class="container">
<h1>Manage My Services</h1>
<p><a href="dashboard.php">← Back to Dashboard</a></p>

<p><?= htmlspecialchars($message) ?></p>

<h2>Add New Service</h2>
<form method="POST">
<input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION["csrf_token"]) ?>">
<input type="hidden" name="action" value="add">

<label>Service Name</label>
<input type="text" name="service_name" required placeholder="e.g. Web Development">

<label>Description</label>
<textarea name="description" placeholder="Describe the service you provide"></textarea>

<button type="submit">Add Service</button>
</form>

<h2>Existing Services</h2>
<?php foreach ($services as $service): ?>
<div class="service">
<strong><?= htmlspecialchars($service["service_name"]) ?></strong>
<p><?= htmlspecialchars($service["description"] ?? "") ?></p>


<form method="POST">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION["csrf_token"]) ?>">
    <input type="hidden" name="action" value="edit">
    <input type="hidden" name="id" value="<?= (int)$service["id"] ?>">

    <label>Service Name</label>
    <input type="text" name="service_name"
        value="<?= htmlspecialchars($service["service_name"]) ?>" required>

    <label>Description</label>
    <textarea name="description" required><?= htmlspecialchars($service["description"] ?? "") ?></textarea>

    <button type="submit">Save Changes</button>
</form>

<form method="POST" onsubmit="return confirm('Delete this service?')">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION["csrf_token"]) ?>">
    <input type="hidden" name="action" value="delete">
    <input type="hidden" name="id" value="<?= (int)$service["id"] ?>">
    <button class="delete" type="submit">Delete</button>
</form>
</div>

<?php endforeach; ?>
</div>
</body>
</html>
