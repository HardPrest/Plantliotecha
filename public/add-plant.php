<?php

session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

require_once "../config/database.php";

$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nickname          = trim($_POST["nickname"]);
    $species           = trim($_POST["species"]);
    $scientific_name   = trim($_POST["scientific_name"]);
    $acquired_date     = !empty($_POST["acquired_date"]) ? $_POST["acquired_date"] : null;
    $location          = trim($_POST["location"]);
    $pot_size          = trim($_POST["pot_size"]);
    $soil_type         = trim($_POST["soil_type"]);
    $sunlight          = trim($_POST["sunlight"]);
    $notes             = trim($_POST["notes"]);

    if ($species === "") {
        $errors[] = "Species is required.";
    }

    if (empty($errors)) {

        $stmt = $pdo->prepare("
            INSERT INTO plants
            (
                user_id,
                nickname,
                species,
                scientific_name,
                acquired_date,
                location,
                pot_size,
                soil_type,
                sunlight,
                notes
            )
            VALUES
            (
                ?,?,?,?,?,?,?,?,?,?
            )
        ");

        $stmt->execute([
            $_SESSION["user_id"],
            $nickname,
            $species,
            $scientific_name,
            $acquired_date,
            $location,
            $pot_size,
            $soil_type,
            $sunlight,
            $notes
        ]);

        header("Location: plants.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>Add Plant</title>

    <link rel="stylesheet" href="assets/css/app.css">

</head>

<body>

<div class="container">

    <h1>Add Plant</h1>

    <?php if (!empty($errors)): ?>

        <div class="alert danger">

            <ul>

                <?php foreach ($errors as $error): ?>

                    <li><?= htmlspecialchars($error) ?></li>

                <?php endforeach; ?>

            </ul>

        </div>

    <?php endif; ?>

    <form method="POST">

        <label>Nickname</label>

        <input
            type="text"
            name="nickname"
            value="<?= htmlspecialchars($_POST["nickname"] ?? "") ?>">

        <label>Species *</label>

        <input
            type="text"
            name="species"
            required
            value="<?= htmlspecialchars($_POST["species"] ?? "") ?>">

        <label>Scientific Name</label>

        <input
            type="text"
            name="scientific_name"
            value="<?= htmlspecialchars($_POST["scientific_name"] ?? "") ?>">

        <label>Acquired Date</label>

        <input
            type="date"
            name="acquired_date"
            value="<?= htmlspecialchars($_POST["acquired_date"] ?? "") ?>">

        <label>Location</label>

        <input
            type="text"
            name="location"
            value="<?= htmlspecialchars($_POST["location"] ?? "") ?>">

        <label>Pot Size</label>

        <input
            type="text"
            name="pot_size"
            value="<?= htmlspecialchars($_POST["pot_size"] ?? "") ?>">

        <label>Soil Type</label>

        <input
            type="text"
            name="soil_type"
            value="<?= htmlspecialchars($_POST["soil_type"] ?? "") ?>">

        <label>Sunlight</label>

        <input
            type="text"
            name="sunlight"
            value="<?= htmlspecialchars($_POST["sunlight"] ?? "") ?>">

        <label>Notes</label>

        <textarea
            name="notes"
            rows="6"><?= htmlspecialchars($_POST["notes"] ?? "") ?></textarea>

        <br><br>

        <button
            class="button"
            type="submit">

            Save Plant

        </button>

    </form>

</div>

</body>

</html>