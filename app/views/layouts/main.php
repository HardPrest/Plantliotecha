<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Plantliotecha</title>

    <link rel="stylesheet" href="/Plantliotecha/public/assets/css/base/variables.css">
    <link rel="stylesheet" href="/Plantliotecha/public/assets/css/base/reset.css">

    <link rel="stylesheet" href="/Plantliotecha/public/assets/css/layout/sidebar.css">
    <link rel="stylesheet" href="/Plantliotecha/public/assets/css/layout/topbar.css">

    <link rel="stylesheet" href="/Plantliotecha/public/assets/css/components/cards.css">
    <link rel="stylesheet" href="/Plantliotecha/public/assets/css/components/buttons.css">

    <link rel="stylesheet" href="/Plantliotecha/public/assets/css/pages/dashboard.css">

</head>

<body>

<?php include "../app/Views/partials/sidebar.php"; ?>

<div class="main">

    <?php include "../app/Views/partials/topbar.php"; ?>

    <main class="content">

        <?= $content ?>

    </main>

</div>

</body>

</html>