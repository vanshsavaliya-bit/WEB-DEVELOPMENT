
<?php
session_start();
require_once "db.php";

if (empty($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}

$message = "";
$messageType = "";

$name = "";
$username = "";
$email = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"] ?? "");
    $username = trim($_POST["username"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";
    $confirmPassword = $_POST["confirm_password"] ?? "";
    $token = $_POST["csrf_token"] ?? "";

    if (!is_string($token) ||
        !hash_equals($_SESSION["csrf_token"], $token)) {
        $message = "Invalid request. Please refresh the page.";
        $messageType = "error";

    } elseif ($name === "" || $username === "" ||
              $email === "" || $password === "" ||
              $confirmPassword === "") {
        $message = "All fields are required.";
        $messageType = "error";

    } elseif (strlen($name) > 100 ||
              strlen($username) > 50 ||
              strlen($email) > 150) {
        $message = "One or more fields are too long.";
        $messageType = "error";

    } elseif (!preg_match('/^[a-zA-Z0-9_]{3,50}$/', $username)) {
        $message = "Username must be 3-50 characters using letters, numbers, or underscores.";
        $messageType = "error";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Please enter a valid email address.";
        $messageType = "error";

    } elseif (strlen($password) < 8 ||
              strlen($password) > 72) {
        $message = "Password must be 8-72 characters long.";
        $messageType = "error";

    } elseif ($password !== $confirmPassword) {
        $message = "Passwords do not match.";
        $messageType = "error";

    } else {
        // Check duplicate email or username
        $stmt = $conn->prepare(
            "SELECT id FROM users
             WHERE email = ? OR username = ? LIMIT 1"
        );
        $stmt->bind_param("ss", $email, $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $message = "Email or username already exists.";
            $messageType = "error";
            $stmt->close();

        } else {
            $stmt->close();

            // Hash the password before storing it
            $hashedPassword = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            $stmt = $conn->prepare(
                "INSERT INTO users
                 (name, username, email, password)
                 VALUES (?, ?, ?, ?)"
            );
            $stmt->bind_param(
                "ssss",
                $name,
                $username,
                $email,
                $hashedPassword
            );

            if ($stmt->execute()) {
                $message = "Registration successful!";
                $messageType = "success";

                // Clear the form and rotate the CSRF token
                $name = "";
                $username = "";
                $email = "";
                $_SESSION["csrf_token"] =
                    bin2hex(random_bytes(32));
            } else {
                // UNIQUE constraints also protect against
                // simultaneous duplicate registration attempts.
                if ($stmt->errno === 1062) {
                    $message = "Email or username already exists.";
                } else {
                    $message = "Registration failed. Please try again.";
                }
                $messageType = "error";
            }

            $stmt->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Practical 9 - Secure Registration</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f1f5f9;
            margin: 0;
            padding: 30px 15px;
        }

        .container {
            max-width: 430px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 15px #0001;
        }

        h2 {
            text-align: center;
            color: #1e293b;
        }

        label {
            display: block;
            margin-top: 14px;
            font-weight: bold;
        }

        input {
            box-sizing: border-box;
            width: 100%;
            padding: 11px;
            margin-top: 6px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
        }

        button {
            width: 100%;
            padding: 12px;
            margin-top: 22px;
            border: 0;
            border-radius: 6px;
            background: #2563eb;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }

        .success {
            background: #dcfce7;
            color: #166534;
            padding: 12px;
            border-radius: 6px;
        }

        .error {
            background: #fee2e2;
            color: #991b1b;
            padding: 12px;
            border-radius: 6px;
        }

        .hint {
            color: #64748b;
            font-size: 13px;
        }
    </style>
</head>

<body>
<div class="container">
    <h2>Secure User Registration</h2>

    <?php if ($message !== ""): ?>
        <p class="<?= htmlspecialchars($messageType, ENT_QUOTES, 'UTF-8') ?>">
            <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
        </p>
    <?php endif; ?>

    <form method="POST" action="register.php">
        <input type="hidden" name="csrf_token"
               value="<?= htmlspecialchars($_SESSION["csrf_token"], ENT_QUOTES, 'UTF-8') ?>">

        <label for="name">Full Name</label>
        <input type="text" id="name" name="name"
               maxlength="100" required
               value="<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>">

        <label for="username">Username</label>
        <input type="text" id="username" name="username"
               minlength="3" maxlength="50"
               pattern="[a-zA-Z0-9_]{3,50}"
               title="Use 3-50 letters, numbers, or underscores"
               required
               value="<?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8') ?>">

        <label for="email">Email Address</label>
        <input type="email" id="email" name="email"
               maxlength="150" required
               value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>">

        <label for="password">Password</label>
        <input type="password" id="password" name="password"
               minlength="8" maxlength="72" required>
        <p class="hint">Use 8-72 characters.</p>

        <label for="confirm_password">Confirm Password</label>
        <input type="password" id="confirm_password"
               name="confirm_password"
               minlength="8" maxlength="72" required>

        <button type="submit">Register</button>
    </form>
</div>
</body>
</html>
