<?php

session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

require_once "../config/database.php";

$id = $_GET["id"] ?? 0;

$stmt = $pdo->prepare("
    SELECT *
    FROM plants
    WHERE id=?
    AND user_id=?
");

$stmt->execute([$id, $_SESSION["user_id"]]);

$plant = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$plant) {
    die("Plant not found.");
}

$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nickname = trim($_POST["nickname"]);
    $species = trim($_POST["species"]);
    $scientific_name = trim($_POST["scientific_name"]);
    $acquired_date = $_POST["acquired_date"] ?: null;
    $location = trim($_POST["location"]);
    $pot_size = trim($_POST["pot_size"]);
    $soil_type = trim($_POST["soil_type"]);
    $sunlight = trim($_POST["sunlight"]);
    $notes = trim($_POST["notes"]);

    if ($species == "") {
        $errors[] = "Species is required.";
    }

    if (empty($errors)) {

        $stmt = $pdo->prepare("
            UPDATE plants
            SET
                nickname=?,
                species=?,
                scientific_name=?,
                acquired_date=?,
                location=?,
                pot_size=?,
                soil_type=?,
                sunlight=?,
                notes=?
            WHERE id=?
            AND user_id=?
        ");

        $stmt->execute([
            $nickname,
            $species,
            $scientific_name,
            $acquired_date,
            $location,
            $pot_size,
            $soil_type,
            $sunlight,
            $notes,
            $id,
            $_SESSION["user_id"]
        ]);

        header("Location: plant.php?id=".$id);
        exit;

    }

}
?>
<!doctype html>
<html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Edit Plant | Plantliotecha</title><link rel="stylesheet" href="assets/css/app.css"></head><body><main class="container">
<p><a href="plant.php?id=<?= (int) $id ?>">&larr; Back to plant</a></p>
<h1>Edit <?= htmlspecialchars($plant["nickname"] ?: $plant["species"]) ?></h1>
<?php if (!empty($errors)): ?><div class="alert danger"><?= htmlspecialchars($errors[0]) ?></div><?php endif; ?>
<form method="POST">

        <label>Nickname</label>

        <input
            type="text"
            name="nickname"
            value="<?= htmlspecialchars($_POST["nickname"] ?? $plant["nickname"]) ?>">

        <label>Species *</label>

        <input
            type="text"
            name="species"
            required
            value="<?= htmlspecialchars($_POST["species"] ?? $plant["species"]) ?>">

        <label>Scientific Name</label>

        <input
            type="text"
            name="scientific_name"
            value="<?= htmlspecialchars($_POST["scientific_name"] ?? $plant["scientific_name"]) ?>">

        <label>Acquired Date</label>

        <input
            type="date"
            name="acquired_date"
            value="<?= htmlspecialchars($_POST["acquired_date"] ?? $plant["acquired_date"]) ?>">

        <label>Location</label>

        <input
            type="text"
            name="location"
            value="<?= htmlspecialchars($_POST["location"] ?? $plant["location"]) ?>">

        <label>Pot Size</label>

        <input
            type="text"
            name="pot_size"
            value="<?= htmlspecialchars($_POST["pot_size"] ?? $plant["pot_size"]) ?>">

        <label>Soil Type</label>

        <input
            type="text"
            name="soil_type"
            value="<?= htmlspecialchars($_POST["soil_type"] ?? $plant["soil_type"]) ?>">

        <label>Sunlight</label>

        <input
            type="text"
            name="sunlight"
            value="<?= htmlspecialchars($_POST["sunlight"] ?? $plant["sunlight"]) ?>">

        <label>Notes</label>

        <textarea
            name="notes"
            rows="6"><?= htmlspecialchars($_POST["notes"] ?? $plant["notes"]) ?></textarea>

        <br><br>

        <button
            class="button"
            type="submit">

            Save Plant

        </button>

    </form>
</main></body></html>
