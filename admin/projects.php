
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
            $name = trim($_POST["project_name"] ?? "");
            $description = trim($_POST["description"] ?? "");
            $technologies = trim($_POST["technologies"] ?? "");
            $status = trim($_POST["project_status"] ?? "In Progress");

            if ($name !== "") {
                $stmt = $conn->prepare(
                    "INSERT INTO projects (project_name, description, technologies, project_status) VALUES (?, ?, ?, ?)"
                );
                $stmt->execute([$name, $description, $technologies, $status]);
                $message = "Project added successfully!";
            }
        }

       
if ($action === "edit") {
    $id = (int)($_POST["id"] ?? 0);
    $name = trim($_POST["project_name"] ?? "");
    $description = trim($_POST["description"] ?? "");
    $technologies = trim($_POST["technologies"] ?? "");
    $status = trim($_POST["project_status"] ?? "");

    if ($id > 0 && $name !== "") {
        $stmt = $conn->prepare(
            "UPDATE projects SET project_name = ?, description = ?, technologies = ?, project_status = ? WHERE id = ?"
        );
        $stmt->execute([$name, $description, $technologies, $status, $id]);
        $message = "Project updated successfully!";
    }
}

        if ($action === "delete") {
            $stmt = $conn->prepare("DELETE FROM projects WHERE id = ?");
            $stmt->execute([(int)($_POST["id"] ?? 0)]);
            $message = "Project deleted.";
        }
    }
}

$projects = $conn->query("SELECT * FROM projects ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Projects Manager</title>
<style>
body{font-family:Arial;background:#f3f5f8;padding:20px}
.container{max-width:900px;margin:auto;background:white;padding:25px;border-radius:12px}
h1,h2{color:#071a35}
label{display:block;margin-top:14px;font-weight:bold}
input,textarea,select{width:100%;padding:12px;margin-top:7px;box-sizing:border-box;border:1px solid #ccc;border-radius:6px}
textarea{min-height:100px}
button{background:#071a35;color:white;padding:12px 20px;border:0;border-radius:6px;margin-top:12px;cursor:pointer}
.delete{background:#b42318}
.project{padding:15px;border:1px solid #ddd;border-radius:8px;margin-top:12px}
a{color:#183c65}
</style>
</head>
<body>
<div class="container">
<h1>Manage My Projects</h1>
<p><a href="dashboard.php">← Back to Dashboard</a></p>

<p><?= htmlspecialchars($message) ?></p>

<h2>Add New Project</h2>
<form method="POST">
<input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION["csrf_token"]) ?>">
<input type="hidden" name="action" value="add">

<label>Project Name</label>
<input type="text" name="project_name" required placeholder="e.g. Library Management System">

<label>Project Description</label>
<textarea name="description" placeholder="Explain what the project does"></textarea>

<label>Technologies Used</label>
<input type="text" name="technologies" placeholder="PHP, MySQL, HTML, CSS">

<label>Project Status</label>
<select name="project_status">
<option>In Progress</option>
<option>Completed</option>
<option>Planning</option>
<option>Academic Project</option>
</select>

<button type="submit">Add Project</button>
</form>

<h2>Existing Projects</h2>
<?php foreach ($projects as $project): ?>
<div class="project">
<strong><?= htmlspecialchars($project["project_name"]) ?></strong>
<p><?= htmlspecialchars($project["description"] ?? "") ?></p>
<p><b>Technologies:</b> <?= htmlspecialchars($project["technologies"] ?? "") ?></p>
<p><b>Status:</b> <?= htmlspecialchars($project["project_status"] ?? "") ?></p>


<form method="POST">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION["csrf_token"]) ?>">
    <input type="hidden" name="action" value="edit">
    <input type="hidden" name="id" value="<?= (int)$project["id"] ?>">

    <label>Project Name</label>
    <input type="text" name="project_name"
        value="<?= htmlspecialchars($project["project_name"]) ?>" required>

    <label>Description</label>
    <textarea name="description"><?= htmlspecialchars($project["description"] ?? "") ?></textarea>

    <label>Technologies</label>
    <input type="text" name="technologies"
        value="<?= htmlspecialchars($project["technologies"] ?? "") ?>">

    <label>Project Status</label>
    <input type="text" name="project_status"
        value="<?= htmlspecialchars($project["project_status"] ?? "") ?>">

    <button type="submit">Save Changes</button>
</form>

<form method="POST" onsubmit="return confirm('Delete this project?')">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION["csrf_token"]) ?>">
    <input type="hidden" name="action" value="delete">
    <input type="hidden" name="id" value="<?= (int)$project["id"] ?>">
    <button class="delete" type="submit">Delete</button>
</form>
</div>

<?php endforeach; ?>
</div>
</body>
</html>
