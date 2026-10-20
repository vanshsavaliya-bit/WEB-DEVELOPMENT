
<?php
require_once __DIR__ . "/db.php";

$users = [
    [
        "name" => "Admin User",
        "username" => "admin",
        "email" => "admin@example.com",
        "password" => "Admin@12345",
        "role" => "admin"
    ],
    [
        "name" => "Student User",
        "username" => "student",
        "email" => "student@example.com",
        "password" => "Student@12345",
        "role" => "student"
    ]
];

foreach ($users as $user) {
    $check = $conn->prepare(
        "SELECT id FROM users
         WHERE username = ? OR email = ? LIMIT 1"
    );

    $check->bind_param(
        "ss",
        $user["username"],
        $user["email"]
    );

    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo htmlspecialchars($user["username"]) .
             " already exists. Skipped.<br>";
        $check->close();
        continue;
    }

    $check->close();

    $hash = password_hash(
        $user["password"],
        PASSWORD_DEFAULT
    );

    $stmt = $conn->prepare(
        "INSERT INTO users
        (name, username, email, password, role)
        VALUES (?, ?, ?, ?, ?)"
    );

    $stmt->bind_param(
        "sssss",
        $user["name"],
        $user["username"],
        $user["email"],
        $hash,
        $user["role"]
    );

    if ($stmt->execute()) {
        echo htmlspecialchars($user["username"]) .
             " created successfully.<br>";
    } else {
        echo "Unable to create " .
             htmlspecialchars($user["username"]) . ".<br>";
    }

    $stmt->close();
}

$conn->close();
?>
