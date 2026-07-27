<?php

session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

require_once "../config/database.php";
$theme = $_SESSION["theme"] ?? "light";

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    die("Invalid plant.");
}

$stmt = $pdo->prepare("
    SELECT *
    FROM plants
    WHERE id = ?
    AND user_id = ?
    LIMIT 1
");

$stmt->execute([
    $_GET["id"],
    $_SESSION["user_id"]
]);

$plant = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$plant) {
    die("Plant not found.");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<title><?= htmlspecialchars($plant["nickname"] ?: $plant["species"]) ?></title>

<link rel="stylesheet" href="assets/css/app.css">
<link rel="stylesheet" href="assets/css/theme.css">

</head>

<body data-theme="<?= htmlspecialchars($theme) ?>">

<div class="container">

<h1>

<?= htmlspecialchars($plant["nickname"] ?: $plant["species"]) ?>

</h1>

<table>

<tr>
    <th>Species</th>
    <td><?= htmlspecialchars($plant["species"]) ?></td>
</tr>

<tr>
    <th>Scientific Name</th>
    <td><?= htmlspecialchars($plant["scientific_name"]) ?></td>
</tr>

<tr>
    <th>Acquired</th>
    <td><?= htmlspecialchars($plant["acquired_date"]) ?></td>
</tr>

<tr>
    <th>Location</th>
    <td><?= htmlspecialchars($plant["location"]) ?></td>
</tr>

<tr>
    <th>Pot Size</th>
    <td><?= htmlspecialchars($plant["pot_size"]) ?></td>
</tr>

<tr>
    <th>Soil</th>
    <td><?= htmlspecialchars($plant["soil_type"]) ?></td>
</tr>

<tr>
    <th>Sunlight</th>
    <td><?= htmlspecialchars($plant["sunlight"]) ?></td>
</tr>

<tr>
    <th>Notes</th>
    <td><?= nl2br(htmlspecialchars($plant["notes"])) ?></td>
</tr>

</table>

<br>

<a class="button" href="edit-plant.php?id=<?= $plant["id"] ?>">

Edit Plant

</a>

<a
class="button"
href="delete-plant.php?id=<?= $plant["id"] ?>"
onclick="return confirm('Delete this plant?');">

Delete Plant

</a>

</div>

</body>

</html>
