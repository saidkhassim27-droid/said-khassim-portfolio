
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
    $submittedToken = $_POST["csrf_token"] ?? "";

if (!hash_equals($_SESSION["csrf_token"], $submittedToken)) {
        $message = "Invalid request. Refresh and try again.";
    } else {
        $action = $_POST["action"] ?? "";

        if ($action === "add") {

    $name = trim($_POST["certificate_name"] ?? "");
    $organisation = trim($_POST["issuing_organisation"] ?? "");
    $date = !empty($_POST["issue_date"]) ? $_POST["issue_date"] : null;

    $certificateFile = "";

    if ($name === "") {
        $message = "Please enter the certificate name.";
    } else {

        if (
            isset($_FILES["certificate_file"]) &&
            $_FILES["certificate_file"]["error"] !== UPLOAD_ERR_NO_FILE
        ) {

            if ($_FILES["certificate_file"]["error"] !== UPLOAD_ERR_OK) {
                $message = "Certificate upload failed.";

            } elseif ($_FILES["certificate_file"]["size"] > 2097152) {
                $message = "File must not exceed 2MB.";

            } else {

                $allowedTypes = [
                    "image/jpeg" => "jpg",
                    "image/png" => "png",
                    "image/webp" => "webp",
                    "application/pdf" => "pdf"
                ];

                $finfo = new finfo(FILEINFO_MIME_TYPE);
                $fileType = $finfo->file($_FILES["certificate_file"]["tmp_name"]);

                if (!isset($allowedTypes[$fileType])) {
                    $message = "Only JPG, PNG, WEBP or PDF files are allowed.";

                } else {

                    $uploadDir = "../uploads/";

                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }

                    $fileName = bin2hex(random_bytes(16)) . "." . $allowedTypes[$fileType];

                    if (move_uploaded_file(
                        $_FILES["certificate_file"]["tmp_name"],
                        $uploadDir . $fileName
                    )) {
                        $certificateFile = "uploads/" . $fileName;
                    } else {
                        $message = "Could not save the certificate file.";
                    }
                }
            }
        }

        if ($message === "") {

            $stmt = $conn->prepare(
                "INSERT INTO certificates
                (certificate_name, issuing_organisation, issue_date, certificate_file)
                VALUES (?, ?, ?, ?)"
            );

            $stmt->execute([
                $name,
                $organisation,
                $date,
                $certificateFile
            ]);

            $message = "Certificate record added successfully!";
        }
    }
}
            

if ($action === "edit") {
    $id = (int)($_POST["id"] ?? 0);
    $name = trim($_POST["certificate_name"] ?? "");
    $organisation = trim($_POST["issuing_organisation"] ?? "");
    $issue_date = $_POST["issue_date"] ?? null;

    if ($id > 0 && $name !== "") {
        $stmt = $conn->prepare(
            "UPDATE certificates
             SET certificate_name = ?, issuing_organisation = ?, issue_date = ?
             WHERE id = ?"
        );

        $stmt->execute([
            $name,
            $organisation,
            $issue_date ?: null,
            $id
        ]);

        $message = "Certificate updated successfully!";
    }
}

       
if ($action === "delete") {
            $stmt = $conn->prepare("DELETE FROM certificates WHERE id = ?");
            $stmt->execute([(int)($_POST["id"] ?? 0)]);
            $message = "Certificate record deleted.";
        }
    }
}

$certificates = $conn->query("SELECT * FROM certificates ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Certificates Manager</title>
<style>
body{font-family:Arial;background:#f3f5f8;padding:20px}
.container{max-width:850px;margin:auto;background:white;padding:25px;border-radius:12px}
h1,h2{color:#071a35}
label{display:block;margin-top:14px;font-weight:bold}
input{width:100%;padding:12px;margin-top:7px;box-sizing:border-box;border:1px solid #ccc;border-radius:6px}
button{background:#071a35;color:white;padding:12px 20px;border:0;border-radius:6px;margin-top:12px;cursor:pointer}
.delete{background:#b42318}
.record{padding:15px;border:1px solid #ddd;border-radius:8px;margin-top:12px}
a{color:#183c65}
</style>
</head>
<body>
<div class="container">
<h1>Manage My Certificates</h1>
<p><a href="dashboard.php">← Back to Dashboard</a></p>

<p><?= htmlspecialchars($message) ?></p>

<h2>Add New Certificate</h2>
<form method="POST" enctype="multipart/form-data">
<input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION["csrf_token"]) ?>">
<input type="hidden" name="action" value="add">

<label>Certificate Name</label>
<input type="text" name="certificate_name" required placeholder="Enter certificate name">

<label>Issuing Organisation</label>
<input type="text" name="issuing_organisation" placeholder="Enter organisation">

<label>Issue Date</label>
<input type="date" name="issue_date">
<label>Upload Certificate Picture:</label>
<input type="file" name="certificate_file" accept=".jpg,.jpeg,.png,.webp,application/pdf">

<button type="submit">Add Certificate</button>
</form>

<h2>Existing Certificates</h2>
<?php foreach ($certificates as $certificate): ?>
<div class="record">
<strong><?= htmlspecialchars($certificate["certificate_name"]) ?></strong>
<p><?= htmlspecialchars($certificate["issuing_organisation"] ?? "") ?></p>
<p><?= htmlspecialchars($certificate["issue_date"] ?? "") ?></p>


<form method="POST">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION["csrf_token"]) ?>">
    <input type="hidden" name="action" value="edit">
    <input type="hidden" name="id" value="<?= (int)$certificate["id"] ?>">

    <label>Certificate Name</label>
    <input type="text" name="certificate_name"
        value="<?= htmlspecialchars($certificate["certificate_name"]) ?>" required>

    <label>Issuing Organisation</label>
    <input type="text" name="issuing_organisation"
        value="<?= htmlspecialchars($certificate["issuing_organisation"] ?? "") ?>">

    <label>Issue Date</label>
    <input type="date" name="issue_date"
        value="<?= htmlspecialchars($certificate["issue_date"] ?? "") ?>">

    <button type="submit">Save Changes</button>
</form>

<form method="POST" onsubmit="return confirm('Delete this certificate?')">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION["csrf_token"]) ?>">
    <input type="hidden" name="action" value="delete">
    <input type="hidden" name="id" value="<?= (int)$certificate["id"] ?>">
    <button class="delete" type="submit">Delete</button>
</form>

</div>
<?php endforeach; ?>
</div>
</body>
</html>
