<?php
// Initialize the session
session_start();

// Unset all of the session variables
$_SESSION = array();

// Destroy the session.
session_destroy();

// Include config file to get the main page URL
require_once "dbconfig.php";

// Redirect to the main page after 2 seconds
echo "使用者已登出，轉入首頁...";
header("Refresh: 2; URL = manage_movies.php" . $main); // 使用 dbconfig.php 中設定的 $main 變數
exit(); // 確保在重定向後停止執行腳本
?>