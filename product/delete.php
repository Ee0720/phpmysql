<?php

session_start();

function loginOK() {
    return (isset($_SESSION["loggedin"]) && ($_SESSION["loggedin"]===true));
}

if (!loginOK()) {
    header("location: login.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "school");
$id = (int)$_GET['id'];
$conn->query("DELETE FROM product WHERE id=$id");
header("Location: index.php");
?>