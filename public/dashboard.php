<?php

session_start();

if (!isset($_SESSION["user_id"])) {

    header("Location: login.php");
    exit;

}

$username = $_SESSION["username"];

include "../app/Views/dashboard/dashboard.php";