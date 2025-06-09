<?php
session_start();

// Function to check if the user is logged in
function loginOK() {
    return (isset($_SESSION["loggedin"]) && ($_SESSION["loggedin"] === true));
}

// Redirect to login page if not logged in
if (!loginOK()) {
    header("location: login.php"); // Ensure this points to your customer login page
    exit();
}

// Include database configuration file
require_once "dbconfig.php"; // This should point to your music_store database config

// Establish database connection using MySQLi
$conn = new mysqli($hostname, $dbuser, $dbpass, $database);

// Check connection
if ($conn->connect_error) {
    die("連線失敗: " . $conn->connect_error);
}

// Set character set to UTF-8
$conn->set_charset("utf8mb4");

// Get the album ID from the URL parameter for initial fetching
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$album = null; // Initialize album variable

// Handle POST request for updating album data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = (int)$_POST['id'];
    $title = $_POST['title'];
    $artist_id = (int)$_POST['artist_id']; // Ensure artist_id is an integer
    $release_year = (int)$_POST['release_year'];
    $price = (int)$_POST['price'];
    $cover_url = $_POST['cover_url'];
    $description = $_POST['description'];

    // Prepare the SQL UPDATE statement for the 'albums' table
    $stmt = $conn->prepare("UPDATE albums SET title=?, artist_id=?, release_year=?, price=?, cover_url=?, description=? WHERE id=?");
    
    // 's' for string, 'i' for integer. Check your data types carefully.
    // title (string), artist_id (int), release_year (int), price (int), cover_url (string), description (string), id (int)
    $stmt->bind_param("siiissi", $title, $artist_id, $release_year, $price, $cover_url, $description, $id);

    if ($stmt->execute()) {
        // Redirect to the album list page upon successful update
        header("Location: album_list.php"); // You'll need an album_list.php page
        exit();
    } else {
        echo "更新失敗: " . $stmt->error;
    }
    $stmt->close();
}

// Fetch existing album data for display in the form (for GET requests or after failed POST)
// Join with 'artists' table to get artist name for display
$stmt = $conn->prepare("SELECT
                            a.id,
                            a.title,
                            a.artist_id,
                            art.name AS artist_name, -- Get artist name here
                            a.release_year,
                            a.price,
                            a.cover_url,
                            a.description
                        FROM
                            albums a
                        JOIN
                            artists art ON a.artist_id = art.id
                        WHERE
                            a.id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$album = $result->fetch_assoc();

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>修改專輯</title>
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
        input[type="text"], input[type="number"], input[type="url"], textarea {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            background-color: #007bff;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            margin-top: 15px;
            cursor: pointer;
            width: 100%;
        }
        button:hover {
            background-color: #0056b3;
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
        <h2>修改專輯</h2>
        <?php if ($album): ?>
            <form method="post">
                <input type="hidden" name="id" value="<?php echo $album['id']; ?>">
                
                <label>專輯名稱:</label>
                <input type="text" name="title" value="<?php echo htmlspecialchars($album['title']); ?>" required>
                
                <label>藝術家ID (目前: <?php echo htmlspecialchars($album['artist_name']); ?>):</label>
                <input type="number" name="artist_id" value="<?php echo htmlspecialchars($album['artist_id']); ?>" required>
                
                <label>發行年份:</label>
                <input type="number" name="release_year" value="<?php echo htmlspecialchars($album['release_year']); ?>" required min="1900" max="<?php echo date("Y"); ?>">
                
                <label>定價:</label>
                <input type="number" name="price" value="<?php echo htmlspecialchars($album['price']); ?>" required>
                
                <label>封面圖片URL:</label>
                <input type="url" name="cover_url" value="<?php echo htmlspecialchars($album['cover_url']); ?>" required>
                
                <label>專輯簡介:</label>
                <textarea name="description" rows="4" required><?php echo htmlspecialchars($album['description']); ?></textarea>
                
                <button type="submit">儲存修改</button>
            </form>
        <?php else: ?>
            <p>找不到該專輯。</p>
        <?php endif; ?>
        <a class="back-link" href="album_list.php">返回專輯列表</a>
    </div>
</body>
</html>