<?php

include "db.php";

$name = "Vansh";
$email = "vansh@test.com";

$sql = "INSERT INTO students (name, email)
        VALUES (:name, :email)";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    ":name" => $name,
    ":email" => $email
]);

echo "<h2>Student Added Successfully</h2>";

?>