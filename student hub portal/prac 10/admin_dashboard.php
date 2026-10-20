
<?php
require_once __DIR__ . "/auth.php";

if ($_SESSION["role"] !== "admin") {
    header("Location: student_dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
</head>
<body>
    <h1>Admin Dashboard</h1>

    <p>Welcome,
        <?= htmlspecialchars($_SESSION["name"], ENT_QUOTES, "UTF-8") ?>!
    </p>

    <p>Role: Admin</p>
    <p>You have successfully logged in as an administrator.</p>

    <form method="POST" action="logout.php">
        <input type="hidden" name="csrf_token"
               value="<?= htmlspecialchars($_SESSION["csrf_token"], ENT_QUOTES, "UTF-8") ?>">
        <button type="submit">Logout</button>
    </form>
</body>
</html>
