
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

        if ($action === "add_education") {
            $qualification = trim($_POST["qualification"] ?? "");
            $institution = trim($_POST["institution"] ?? "");
            $start = $_POST["start_date"] ?: null;
            $end = $_POST["end_date"] ?: null;
            $status = trim($_POST["status"] ?? "");
            $description = trim($_POST["description"] ?? "");

            if ($qualification !== "") {
                $stmt = $conn->prepare(
                    "INSERT INTO education (qualification, institution, start_date, end_date, status, description) VALUES (?, ?, ?, ?, ?, ?)"
                );
                $stmt->execute([$qualification, $institution, $start, $end, $status, $description]);
                $message = "Education record added!";
            }
        }

        if ($action === "add_experience") {
            $job = trim($_POST["job_title"] ?? "");
            $organisation = trim($_POST["organisation"] ?? "");
            $start = $_POST["start_date"] ?: null;
            $end = $_POST["end_date"] ?: null;
            $description = trim($_POST["description"] ?? "");

            if ($job !== "") {
                $stmt = $conn->prepare(
                    "INSERT INTO experience (job_title, organisation, start_date, end_date, description) VALUES (?, ?, ?, ?, ?)"
                );
                $stmt->execute([$job, $organisation, $start, $end, $description]);
                $message = "Experience record added!";
            }
        }

       
if ($action === "edit_education") {
    $id = (int)($_POST["id"] ?? 0);
    $qualification = trim($_POST["qualification"] ?? "");
    $institution = trim($_POST["institution"] ?? "");
    $start_date = $_POST["start_date"] ?? null;
    $end_date = $_POST["end_date"] ?? null;
    $status = trim($_POST["status"] ?? "");
    $description = trim($_POST["description"] ?? "");

    if ($id > 0 && $qualification !== "") {
        $stmt = $conn->prepare(
            "UPDATE education SET qualification = ?, institution = ?, start_date = ?, end_date = ?, status = ?, description = ? WHERE id = ?"
        );
        $stmt->execute([
            $qualification, $institution,
            $start_date ?: null, $end_date ?: null,
            $status, $description, $id
        ]);
        $message = "Education record updated successfully!";
    }
}

if ($action === "edit_experience") {
    $id = (int)($_POST["id"] ?? 0);
    $job_title = trim($_POST["job_title"] ?? "");
    $organisation = trim($_POST["organisation"] ?? "");
    $description = trim($_POST["description"] ?? "");

    if ($id > 0 && $job_title !== "") {
        $stmt = $conn->prepare(
            "UPDATE experience SET job_title = ?, organisation = ?, description = ? WHERE id = ?"
        );
        $stmt->execute([
            $job_title, $organisation, $description, $id
        ]);
        $message = "Experience record updated successfully!";
    }
}

        if ($action === "delete_education") {
            $stmt = $conn->prepare("DELETE FROM education WHERE id = ?");
            $stmt->execute([(int)($_POST["id"] ?? 0)]);
            $message = "Education record deleted.";
        }

        if ($action === "delete_experience") {
            $stmt = $conn->prepare("DELETE FROM experience WHERE id = ?");
            $stmt->execute([(int)($_POST["id"] ?? 0)]);
            $message = "Experience record deleted.";
        }
    }
}

$education = $conn->query("SELECT * FROM education ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
$experience = $conn->query("SELECT * FROM experience ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Education and Experience Manager</title>
<style>
body{font-family:Arial;background:#f3f5f8;padding:20px}
.container{max-width:900px;margin:auto;background:white;padding:25px;border-radius:12px}
h1,h2{color:#071a35}
label{display:block;margin-top:12px;font-weight:bold}
input,textarea,select{width:100%;padding:11px;margin-top:6px;box-sizing:border-box;border:1px solid #ccc;border-radius:6px}
button{background:#071a35;color:white;padding:11px 18px;border:0;border-radius:6px;margin-top:12px}
.delete{background:#b42318}
.record{padding:15px;border:1px solid #ddd;border-radius:8px;margin-top:12px}
a{color:#183c65}
</style>
</head>
<body>
<div class="container">
<h1>Education & Experience Manager</h1>
<p><a href="dashboard.php">← Back to Dashboard</a></p>
<p><?= htmlspecialchars($message) ?></p>

<h2>Add Education</h2>
<form method="POST">
<input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION["csrf_token"]) ?>">
<input type="hidden" name="action" value="add_education">

<label>Qualification</label>
<input name="qualification" required placeholder="Diploma in ICT">

<label>Institution</label>
<input name="institution" placeholder="Lunglunga Technical and Vocational College">

<label>Start Date</label>
<input type="date" name="start_date">

<label>End Date</label>
<input type="date" name="end_date">

<label>Status</label>
<select name="status">
<option value="Currently Pursuing">Currently Pursuing</option>
<option value="Completed">Completed</option>
</select>

<label>Description</label>
<textarea name="description"></textarea>
<button type="submit">Add Education</button>
</form>

<h2>Existing Education</h2>
<?php foreach ($education as $item): ?>
<div class="record">
<strong><?= htmlspecialchars($item["qualification"]) ?></strong>
<p><?= htmlspecialchars($item["institution"] ?? "") ?></p>
<p><?= htmlspecialchars($item["status"] ?? "") ?></p>

<form method="POST">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION["csrf_token"]) ?>">
    <input type="hidden" name="action" value="edit_education">
    <input type="hidden" name="id" value="<?= (int)$item["id"] ?>">

    <label>Qualification</label>
    <input type="text" name="qualification"
        value="<?= htmlspecialchars($item["qualification"]) ?>" required>

    <label>Institution</label>
    <input type="text" name="institution"
        value="<?= htmlspecialchars($item["institution"] ?? "") ?>">

    <label>Start Date</label>
    <input type="date" name="start_date"
        value="<?= htmlspecialchars($item["start_date"] ?? "") ?>">

    <label>End Date</label>
    <input type="date" name="end_date"
        value="<?= htmlspecialchars($item["end_date"] ?? "") ?>">

    <label>Status</label>
    <input type="text" name="status"
        value="<?= htmlspecialchars($item["status"] ?? "") ?>">

    <label>Description</label>
    <textarea name="description"><?= htmlspecialchars($item["description"] ?? "") ?></textarea>

    <button type="submit">Save Changes</button>
</form>


<form method="POST">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION["csrf_token"]) ?>">
    <input type="hidden" name="action" value="edit_education">
    <input type="hidden" name="id" value="<?= (int)$item["id"] ?>">

    <label>Qualification</label>
    <input type="text" name="qualification"
        value="<?= htmlspecialchars($item["qualification"]) ?>" required>

    <label>Institution</label>
    <input type="text" name="institution"
        value="<?= htmlspecialchars($item["institution"] ?? "") ?>">

    <label>Start Date</label>
    <input type="date" name="start_date"
        value="<?= htmlspecialchars($item["start_date"] ?? "") ?>">

    <label>End Date</label>
    <input type="date" name="end_date"
        value="<?= htmlspecialchars($item["end_date"] ?? "") ?>">

    <label>Status</label>
    <input type="text" name="status"
        value="<?= htmlspecialchars($item["status"] ?? "") ?>">

    <label>Description</label>
    <textarea name="description"><?= htmlspecialchars($item["description"] ?? "") ?></textarea>

    <button type="submit">Save Changes</button>
</form>

<form method="POST" onsubmit="return confirm('Delete this education record?')">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION["csrf_token"]) ?>">
    <input type="hidden" name="action" value="delete_education">
    <input type="hidden" name="id" value="<?= (int)$item["id"] ?>">
    <button class="delete" type="submit">Delete</button>
</form>

</div>

<?php endforeach; ?>

<hr>
<h2>Add Experience</h2>
<form method="POST">
<input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION["csrf_token"]) ?>">
<input type="hidden" name="action" value="add_experience">

<label>Job Title / Role</label>
<input name="job_title" required placeholder="College Student Council President">

<label>Organisation</label>
<input name="organisation" placeholder="Lunglunga Technical and Vocational College">

<label>Start Date</label>
<input type="date" name="start_date">

<label>End Date</label>
<input type="date" name="end_date">

<label>Description</label>
<textarea name="description"></textarea>
<button type="submit">Add Experience</button>
</form>

<h2>Existing Experience</h2>
<?php foreach ($experience as $item): ?>
<div class="record">
<strong><?= htmlspecialchars($item["job_title"]) ?></strong>
<p><?= htmlspecialchars($item["organisation"] ?? "") ?></p>
<p><?= htmlspecialchars($item["description"] ?? "") ?></p>

<form method="POST">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION["csrf_token"]) ?>">
    <input type="hidden" name="action" value="edit_experience">
    <input type="hidden" name="id" value="<?= (int)$item["id"] ?>">

    <label>Job Title</label>
    <input type="text" name="job_title"
        value="<?= htmlspecialchars($item["job_title"]) ?>" required>

    <label>Organisation</label>
    <input type="text" name="organisation"
        value="<?= htmlspecialchars($item["organisation"] ?? "") ?>">

    <label>Description</label>
    <textarea name="description"><?= htmlspecialchars($item["description"] ?? "") ?></textarea>

    <button type="submit">Save Changes</button>
</form>

<form method="POST" onsubmit="return confirm('Delete this experience record?')">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION["csrf_token"]) ?>">
    <input type="hidden" name="action" value="delete_experience">
    <input type="hidden" name="id" value="<?= (int)$item["id"] ?>">
    <button class="delete" type="submit">Delete</button>
</form>

</div>
<?php endforeach; ?>
</div>
</body>
</html>
