
<?php
require_once __DIR__ . "/config.php";
require_once __DIR__ . "/db.php";

if (isset($_SESSION["user_id"])) {
    if ($_SESSION["role"] === "admin") {
        header("Location: admin_dashboard.php");
    } else {
        header("Location: student_dashboard.php");
    }
    exit;
}

if (empty($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";
    $token = $_POST["csrf_token"] ?? "";

    if (!is_string($token) ||
        !hash_equals($_SESSION["csrf_token"], $token)) {
        $error = "Invalid request. Please refresh the page.";
    } elseif ($username === "" || $password === "") {
        $error = "Enter username and password.";
    } else {
        $stmt = $conn->prepare(
            "SELECT id, name, username, password, role
             FROM users
             WHERE username = ? OR email = ?
             LIMIT 1"
        );

        $stmt->bind_param("ss", $username, $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result(
                $id,
                $name,
                $dbUsername,
                $passwordHash,
                $role
            );
            $stmt->fetch();

            if (
                password_verify($password, $passwordHash) &&
                in_array($role, ["admin", "student"], true)
            ) {
                session_regenerate_id(true);

                $_SESSION["user_id"] = $id;
                $_SESSION["name"] = $name;
                $_SESSION["username"] = $dbUsername;
                $_SESSION["role"] = $role;
                $_SESSION["last_activity"] = time();
                $_SESSION["csrf_token"] =
                    bin2hex(random_bytes(32));

                $update = $conn->prepare(
                    "UPDATE users
                     SET last_login = NOW()
                     WHERE id = ?"
                );
                $update->bind_param("i", $id);
                $update->execute();
                $update->close();

                $stmt->close();
                $conn->close();

                if ($role === "admin") {
                    header("Location: admin_dashboard.php");
                } else {
                    header("Location: student_dashboard.php");
                }
                exit;
            }
        }

        $error = "Invalid username/email or password.";
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Practical 10 - Secure Login</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #eef2f7;
            padding: 30px 15px;
        }

        .box {
            max-width: 400px;
            margin: 40px auto;
            padding: 25px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 15px #0001;
        }

        h2 {
            text-align: center;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }

        input {
            box-sizing: border-box;
            width: 100%;
            padding: 12px;
            margin-top: 7px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        button {
            width: 100%;
            padding: 12px;
            margin-top: 22px;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        .error {
            background: #fee2e2;
            color: #991b1b;
            padding: 12px;
            border-radius: 6px;
        }

        .success {
            background: #dcfce7;
            color: #166534;
            padding: 12px;
            border-radius: 6px;
        }
    </style>
</head>
<body>
<div class="box">
    <h2>Secure Login</h2>

    <?php if (isset($_GET["timeout"])): ?>
        <p class="error">Session expired. Please log in again.</p>
    <?php endif; ?>

    <?php if (isset($_GET["logout"])): ?>
        <p class="success">You have logged out successfully.</p>
    <?php endif; ?>

    <?php if ($error !== ""): ?>
        <p class="error">
            <?= htmlspecialchars($error, ENT_QUOTES, "UTF-8") ?>
        </p>
    <?php endif; ?>

    <form method="POST" action="login.php">
        <input type="hidden" name="csrf_token"
               value="<?= htmlspecialchars($_SESSION["csrf_token"], ENT_QUOTES, "UTF-8") ?>">

        <label for="username">Username or Email</label>
        <input type="text" id="username" name="username"
               autocomplete="username" required>

        <label for="password">Password</label>
        <input type="password" id="password" name="password"
               autocomplete="current-password" required>

        <button type="submit">Login</button>
    </form>
</div>
</body>
</html>
