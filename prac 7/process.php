<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get and sanitize data
    $name = htmlspecialchars(trim($_POST["name"]));
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    $mobile = htmlspecialchars(trim($_POST["mobile"]));
    $course = htmlspecialchars(trim($_POST["course"]));

    $errors = [];

    // Validation
    if (empty($name)) {
        $errors[] = "Name is required";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email";
    }

    if (!preg_match("/^[0-9]{10}$/", $mobile)) {
        $errors[] = "Mobile must contain 10 digits";
    }

    if (empty($course)) {
        $errors[] = "Course is required";
    }

    // Display errors
    if (!empty($errors)) {

        echo "<h3>Errors:</h3>";

        foreach ($errors as $error) {
            echo "<p>$error</p>";
        }

    } else {

        // Store data in CSV
        $file = fopen("data.csv", "a");

        fputcsv($file, [
            $name,
            $email,
            $mobile,
            $course
        ]);

        fclose($file);

        echo "<h2>Registration Successful!</h2>";
        echo "<p>Data stored successfully.</p>";
    }
}

?>