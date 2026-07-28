<?php

session_start();

if (!isset($_SESSION["user_id"])) {

    header("Location: login.php");
    exit;

}

require_once "../config/database.php";

$stmt = $pdo->prepare("
    SELECT *
    FROM plants
    WHERE user_id = ?
    ORDER BY nickname ASC, species ASC
");

$stmt->execute([$_SESSION["user_id"]]);

$plants = $stmt->fetchAll(PDO::FETCH_ASSOC);
$theme = $_SESSION["theme"] ?? "light";

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<title>My Plants | Plantliotecha</title>

<link rel="stylesheet" href="assets/css/app.css">
<link rel="stylesheet" href="assets/css/theme.css">

</head>

<body data-theme="<?= htmlspecialchars($theme) ?>">

<div class="container">

<p><a href="dashboard.php">Dashboard</a> &middot; <a href="profile.php">Profile & settings</a> &middot; <a href="logout.php">Log out</a></p>

<h1>My Plants</h1>

<p>

<a class="button" href="add-plant.php">

+ Add Plant

</a>

</p>

<?php if (count($plants) === 0): ?>

<p>

You haven't added any plants yet.

</p>

<?php else: ?>

<div class="plant-grid">

<?php foreach ($plants as $plant): ?>

<div class="card">

<h2>

<?= htmlspecialchars(
    $plant["nickname"] ?: $plant["species"]
) ?>

</h2>

<p>

<strong>Species:</strong>

<?= htmlspecialchars($plant["species"]) ?>

</p>

<?php if ($plant["location"]): ?>

<p>

<strong>Location:</strong>

<?= htmlspecialchars($plant["location"]) ?>

</p>

<?php endif; ?>

<a href="plant.php?id=<?= $plant["id"] ?>">

View Plant

</a>

</div>

<?php endforeach; ?>

</div>

<?php endif; ?>

</div>

</body>

</html>
