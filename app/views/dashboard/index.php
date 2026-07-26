<?php

ob_start();

?>

<h1>

Welcome to Plantliotecha

</h1>

<div class="dashboard-grid">

    <div class="card">

        <h2>Total Plants</h2>

        <h1>0</h1>

    </div>

    <div class="card">

        <h2>Tasks Due</h2>

        <h1>0</h1>

    </div>

    <div class="card">

        <h2>Badges</h2>

        <h1>0</h1>

    </div>

</div>

<div class="card">

    <h2>Recent Activity</h2>

    <p>No activity yet.</p>

</div>

<?php

$content = ob_get_clean();

include "../app/Views/layouts/main.php";