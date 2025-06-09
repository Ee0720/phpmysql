<?php
session_start();

// Ensure this file exists and contains your database connection details
require_once "dbconfig.php";

try {
    $conn = new PDO("mysql:host=$hostname;dbname=$database;charset=UTF8", $dbuser, $dbpass);
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

$action = $_POST["action"] ?? ''; // Use null coalescing operator for safer access

if ($action == "login") {
    $email = $_POST["email"]; // Assuming email as the login identifier
    $password = $_POST["password"]; // The password entered by the user

    // Prepare a statement to select the customer based on their email
    $stmt = $conn->prepare("SELECT `id`, `name`, `email`, `password` FROM `customers` WHERE `email` = :email;");
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();

    // If an account with the provided email exists
    if ($stmt->rowCount() == 1) {
        $customer = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verify the provided password against the hashed password in the database
        if (password_verify($password, $customer["password"])) {
            // Store data in session variables
            $_SESSION["loggedin"] = true;
            $_SESSION["customer_id"] = $customer["id"];
            $_SESSION["customer_name"] = $customer["name"];
            $_SESSION["customer_email"] = $customer["email"];

            echo "Yes"; // Login successful
        } else {
            echo "No"; // Incorrect password
        }
    } else {
        echo "No"; // Account not found
    }
} elseif ($action == "logout") {
    // Unset all session variables specific to the customer login
    unset($_SESSION["loggedin"]);
    unset($_SESSION["customer_id"]);
    unset($_SESSION["customer_name"]);
    unset($_SESSION["customer_email"]);
    session_destroy(); // Destroy the entire session

    // Optionally, redirect to a login page or home page after logout
    // header("Location: login.php");
    // exit();
}

// Close connection (optional, as PHP automatically closes connections at script end)
$conn = null;
?>