
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
            $name = trim($_POST["skill_name"] ?? "");
            $category = trim($_POST["category"] ?? "");
            $description = trim($_POST["description"] ?? "");

            if ($name !== "") {
                $stmt = $conn->prepare(
                    "INSERT INTO skills (skill_name, category, description) VALUES (?, ?, ?)"
                );
                $stmt->execute([$name, $category, $description]);
                $message = "Skill added successfully!";
            }
        }

       if ($action === "edit") {
    $id = (int)($_POST["id"] ?? 0);
    $name = trim($_POST["skill_name"] ?? "");
    $category = trim($_POST["category"] ?? "");
    $description = trim($_POST["description"] ?? "");

    if ($id > 0 && $name !== "") {
        $stmt = $conn->prepare(
            "UPDATE skills SET skill_name = ?, category = ?, description = ? WHERE id = ?"
        );
        $stmt->execute([$name, $category, $description, $id]);
        $message = "Skill updated successfully!";
    }
}
        if ($action === "delete") {
            $id = (int)($_POST["id"] ?? 0);
            $stmt = $conn->prepare("DELETE FROM skills WHERE id = ?");
            $stmt->execute([$id]);
            $message = "Skill deleted.";
        }
    }
}

$skills = $conn->query("SELECT * FROM skills ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Manage Skills</title>
<style>
body{font-family:Arial;background:#f3f5f8;margin:0;padding:20px}
.container{max-width:850px;margin:20px auto;background:white;padding:25px;border-radius:12px}
h1,h2{color:#071a35}
label{display:block;margin-top:14px;font-weight:bold}
input,textarea{width:100%;padding:12px;margin-top:6px;box-sizing:border-box;border:1px solid #ccc;border-radius:6px}
button{background:#071a35;color:white;padding:11px 18px;border:0;border-radius:6px;margin-top:12px;cursor:pointer}
.delete{background:#b42318}
.skill{padding:15px;margin-top:12px;border:1px solid #ddd;border-radius:8px}
a{color:#183c65}
</style>
</head>
<body>
<div class="container">
<h1>Manage My Skills</h1>
<p><a href="dashboard.php">← Back to Dashboard</a></p>

<p><?= htmlspecialchars($message) ?></p>

<h2>Add New Skill</h2>
<form method="POST">
<input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION["csrf_token"]) ?>">
<input type="hidden" name="action" value="add">

<label>Skill Name</label>
<input type="text" name="skill_name" required placeholder="e.g. PHP Programming">

<label>Category</label>
<input type="text" name="category" placeholder="e.g. Web Development">

<label>Description</label>
<textarea name="description" placeholder="Describe your skill"></textarea>

<button type="submit">Add Skill</button>
</form>

<h2>Existing Skills</h2>
<?php foreach ($skills as $skill): ?>
<div class="skill">
<strong><?= htmlspecialchars($skill["skill_name"]) ?></strong>
<p><?= htmlspecialchars($skill["category"] ?? "") ?></p>
<p><?= htmlspecialchars($skill["description"] ?? "") ?></p>


<form method="POST">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION["csrf_token"]) ?>">
    <input type="hidden" name="action" value="edit">
    <input type="hidden" name="id" value="<?= (int)$skill["id"] ?>">

    <label>Skill Name</label>
    <input type="text" name="skill_name"
        value="<?= htmlspecialchars($skill["skill_name"]) ?>" required>

    <label>Category</label>
    <input type="text" name="category"
        value="<?= htmlspecialchars($skill["category"] ?? "") ?>">

    <label>Description</label>
    <textarea name="description"><?= htmlspecialchars($skill["description"] ?? "") ?></textarea>

    <button type="submit">Save Changes</button>
</form>

<form method="POST" onsubmit="return confirm('Delete this skill?')">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION["csrf_token"]) ?>">
    <input type="hidden" name="action" value="delete">
    <input type="hidden" name="id" value="<?= (int)$skill["id"] ?>">
    <button class="delete" type="submit">Delete</button>
</form>
</div>
<?php endforeach; ?>
</div>

</body>
</html>
