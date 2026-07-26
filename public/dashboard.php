<?php

session_start();

if (!isset($_SESSION["user_id"])) {

    header("Location: login.php");
    exit;

}

$username = $_SESSION["username"];

require_once "../config/database.php";
$count = $pdo->prepare("SELECT COUNT(*) FROM plants WHERE user_id = ?");
$count->execute([$_SESSION["user_id"]]);
$plantCount = (int) $count->fetchColumn();
?>
<!doctype html>
<html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Dashboard | Plantliotecha</title><link rel="stylesheet" href="assets/css/app.css"></head>
<body><main class="container"><p><a href="plants.php">My plants</a> &middot; <a href="logout.php">Log out</a></p><h1>Welcome back, <?= htmlspecialchars($username) ?>.</h1><p style="color:var(--muted);max-width:560px">Your personal plant library is ready whenever you are.</p><p><a class="button" href="add-plant.php">Add a plant</a> <a class="button secondary" href="plants.php">View collection</a></p><section class="plant-grid"><article class="card"><h2><?= $plantCount ?></h2><p><?= $plantCount === 1 ? "plant in your collection" : "plants in your collection" ?></p></article><article class="card"><h2>Keep growing</h2><p>Add each plant's home, care details, and notes so everything stays in one calm place.</p></article></section></main></body></html>
