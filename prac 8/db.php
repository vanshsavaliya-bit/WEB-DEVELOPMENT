<?php

$pdo = new PDO(
    "mysql:host=localhost;dbname=studenthub",
    "root",
    ""
);

$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

echo "Database Connected Successfully";

?>