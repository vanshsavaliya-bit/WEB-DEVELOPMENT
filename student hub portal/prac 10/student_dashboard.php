
<?php
require_once __DIR__ . "/auth.php";

if ($_SESSION["role"] !== "student") {
    header("Location: admin_dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
</head>
<body>
    <h1>Student Dashboard</h1>

    <p>Welcome,
        <?= htmlspecialchars($_SESSION["name"], ENT_QUOTES, "UTF-8") ?>!
    </p>

    <p>Role: Student</p>
    <p>You have successfully logged in as a student.</p>

    <form method="POST" action="logout.php">
        <input type="hidden" name="csrf_token"
               value="<?= htmlspecialchars($_SESSION["csrf_token"], ENT_QUOTES, "UTF-8") ?>">
        <button type="submit">Logout</button>
    </form>
</body>
</html>
