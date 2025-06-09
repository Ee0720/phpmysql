<?php
session_start();

function loginOK() {
    return (isset($_SESSION["loggedin"]) && ($_SESSION["loggedin"]===true));
}
?>

<?php if (loginOK()) { ?>

    <h1>Welcome, <?= $_SESSION["username"] ?></h1>
    <hr><a class="btn btn-success" href="./logout.php">Logout</a>

<?php } else { ?>

    <h1>Welcome, please sign in or sign up.</h1>
    <hr><a class="btn btn-primary" href="./login.php">Sing In 登入</a> /
    <a class="btn btn-warning"  href="./register.php">Sign Up 註冊</a>

<?php } ?>