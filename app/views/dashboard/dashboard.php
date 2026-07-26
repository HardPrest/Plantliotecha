<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<title>Dashboard</title>

<link rel="stylesheet" href="assets/css/app.css">

</head>

<body>

<div class="wrapper">

    <aside class="sidebar">

        <h2>🌿 Plantliotecha</h2>

        <nav>

            <a href="#">Dashboard</a>

            <a href="#">My Plants</a>

            <a href="#">Calendar</a>

            <a href="#">Friends</a>

            <a href="#">Community</a>

            <a href="#">Badges</a>

            <a href="logout.php">Logout</a>

        </nav>

    </aside>

    <main class="content">

        <h1>

            Welcome,
            <?= htmlspecialchars($username) ?>

        </h1>

        <br>

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

    </main>

</div>

</body>

</html>