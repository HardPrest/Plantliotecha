<?php

session_start();

require_once "../config/database.php";

$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm = $_POST["confirm_password"];

    if ($username === "")
        $errors[] = "Username is required.";

    if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        $errors[] = "Valid email required.";

    if (strlen($password) < 8)
        $errors[] = "Password must be at least 8 characters.";

    if ($password !== $confirm)
        $errors[] = "Passwords do not match.";

    // Username already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$username]);

    if ($stmt->fetch())
        $errors[] = "Username already exists.";

    // Email already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->fetch())
        $errors[] = "Email already exists.";

    if (empty($errors)) {

        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("
            INSERT INTO users(username,email,password)
            VALUES(?,?,?)
        ");

        $stmt->execute([$username, $email, $hash]);

        $_SESSION["success"] = "Account created successfully.";

        header("Location: login.php");
        exit;
    }

}

include "../app/Views/auth/register.php";