<?php
session_start();

// Function to check if the user is logged in
function loginOK() {
    return (isset($_SESSION["loggedin"]) && ($_SESSION["loggedin"] === true));
}

// Redirect to login page if not logged in
if (!loginOK()) {
    header("location: login.php"); // Make sure your login.php is configured for customer login
    exit();
}

// Include database configuration file
require_once "dbconfig.php"; // This should point to your music_store database config

// Establish database connection using MySQLi (as in your original code)
$conn = new mysqli($hostname, $dbuser, $dbpass, $database);

// Check connection
if ($conn->connect_error) {
    die("連線失敗: " . $conn->connect_error);
}

// Set character set to UTF-8
$conn->set_charset("utf8mb4");

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect data from the form
    $title = $_POST['title'];                 // Album title
    $artist_id = $_POST['artist_id'];         // Artist ID (assuming you'll get this from a dropdown or input)
    $release_year = $_POST['release_year'];   // Release year
    $price = $_POST['price'];                 // Album price
    $cover_url = $_POST['cover_url'];         // Album cover URL
    $description = $_POST['description'];     // Album description

    // Prepare and bind the SQL INSERT statement for the 'albums' table
    $stmt = $conn->prepare("INSERT INTO albums (title, artist_id, release_year, price, cover_url, description) VALUES (?, ?, ?, ?, ?, ?)");
    
    // 's' for string, 'i' for integer. Check your data types carefully.
    // title (string), artist_id (int), release_year (int), price (int), cover_url (string), description (string)
    $stmt->bind_param("siiiss", $title, $artist_id, $release_year, $price, $cover_url, $description);

    // Execute the statement
    if ($stmt->execute()) {
        // Redirect to the album list page upon successful addition
        header("Location: album_list.php"); // You'll need an album_list.php page
        exit();
    } else {
        echo "新增專輯失敗: " . $stmt->error;
    }
    
    // Close the statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新增專輯</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 20px;
            background-color: #f9f9f9;
        }
        .container {
            max-width: 500px;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            margin: auto;
        }
        label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
        }
        input, textarea {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            background-color: #28a745;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            margin-top: 15px;
            cursor: pointer;
            width: 100%;
        }
        button:hover {
            background-color: #218838;
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 10px;
            color: #007bff;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>新增專輯</h2>
        <form method="post">
            <label>專輯名稱:</label>
            <input type="text" name="title" required>
            
            <label>藝術家ID (請輸入數字):</label>
            <input type="number" name="artist_id" required>
            
            <label>發行年份:</label>
            <input type="number" name="release_year" required min="1900" max="<?php echo date("Y"); ?>">
            
            <label>定價:</label>
            <input type="number" name="price" required>
            
            <label>封面圖片URL:</label>
            <input type="url" name="cover_url" required>
            
            <label>專輯簡介:</label>
            <textarea name="description" rows="4" required></textarea>
            
            <button type="submit">新增專輯</button>
        </form>
        <a class="back-link" href="album_list.php">返回專輯列表</a>
    </div>
</body>
</html>