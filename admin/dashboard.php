<?php
session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

require_once "../config.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Said Khassim</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f3f5f8;
            color: #333;
        }

        header {
            background: #071a35;
            color: white;
            padding: 22px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
        }

        header h2 {
            margin: 0;
            color: #e0bd67;
        }

        header a {
            color: white;
            text-decoration: none;
            background: #b99142;
            padding: 10px 18px;
            border-radius: 5px;
        }

        .container {
            max-width: 1150px;
            margin: 35px auto;
            padding: 20px;
        }

        .welcome {
            background: white;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 25px;
            border-left: 5px solid #c6a04d;
        }

        .welcome h1 {
            color: #071a35;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.07);
            border-top: 4px solid #c6a04d;
        }

        .card h3 {
            color: #071a35;
        }

        .card a {
            display: inline-block;
            margin-top: 10px;
            color: #9b752c;
            font-weight: bold;
            text-decoration: none;
        }

        @media (max-width: 600px) {
            .container {
                margin: 15px auto;
            }
        }
    </style>
</head>

<body>

<header>
    <h2>SAID KHASSIM ADMIN</h2>
    <a href="logout.php">Logout</a>
</header>

<div class="container">

    <div class="welcome">
        <h1>Welcome to Your Dashboard</h1>

        <p>
            Hello,
            <?= htmlspecialchars($_SESSION["admin_username"]) ?>!
        </p>

        <p>
            Manage your professional portfolio from this administration area.
        </p>
    </div>

    <div class="dashboard-grid">

        <div class="card">
            <h3>My Profile</h3>
            <p>Manage your name, biography and profile photo.</p>
            <a href="profile.php">Manage Profile →</a>
        </div>

        <div class="card">
            <h3>Skills</h3>
            <p>Add, edit and update your ICT skills.</p>
            <a href="skills.php">Manage Skills →</a>
        </div>

        <div class="card">
            <h3>Education & Experience</h3>
            <p>Update your qualifications and experience.</p>
            <a href="education.php">Manage Education →</a>
        </div>

        <div class="card">
            <h3>Services</h3>
            <p>Manage the services you offer.</p>
            <a href="services.php">Manage Services →</a>
        </div>

        <div class="card">
            <h3>Projects</h3>
            <p>Add and update your ICT projects.</p>
            <a href="projects.php">Manage Projects →</a>
        </div>

        <div class="card">
            <h3>Certificates</h3>
            <p>Upload and manage your certificates.</p>
            <a href="certificates.php">Manage Certificates →</a>
        </div>

        <div class="card">
            <h3>Visitor Enquiries</h3>
            <p>View messages sent through your website.</p>
            <a href="inquiries.php">View Enquiries →</a>
        </div>

        <div class="card">
            <h3>Website Settings</h3>
            <p>Manage your website contact information.</p>
            <a href="settings.php">Manage Settings →</a>
        </div>

    </div>

</div>

</body>
</html>