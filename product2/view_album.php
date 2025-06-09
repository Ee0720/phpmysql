<?php
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

// Get the album ID from the URL, cast to integer for security
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$album = null; // Initialize album variable

if ($id > 0) {
    // Prepare a SQL statement to select album and artist details
    // We'll join the 'albums' and 'artists' tables to show the artist's name instead of just their ID.
    $stmt = $conn->prepare("SELECT
                                a.title,
                                art.name AS artist_name,
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
    $stmt->bind_param("i", $id); // 'i' for integer (ID)
    $stmt->execute();
    $result = $stmt->get_result();
    $album = $result->fetch_assoc(); // Fetch the album data
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
    <title>專輯詳細資訊</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 20px;
            background-color: #f9f9f9;
        }
        .container {
            max-width: 600px;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            margin: auto;
            text-align: center; /* Center content */
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 25px;
        }
        .album-cover {
            width: 200px;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .info {
            margin-bottom: 15px;
            padding: 10px;
            border-bottom: 1px solid #eee;
            text-align: left; /* Align text within info divs */
        }
        .info:last-of-type {
            border-bottom: none; /* No border for the last info block */
        }
        .info label {
            font-weight: bold;
            display: inline-block; /* Make label and value appear on same line */
            width: 120px; /* Give labels a fixed width */
            vertical-align: top; /* Align label to the top for longer content */
            color: #555;
        }
        .info span {
            display: inline-block;
            max-width: calc(100% - 130px); /* Adjust width for content */
            vertical-align: top;
            color: #666;
        }
        .back-link {
            display: inline-block;
            text-align: center;
            margin-top: 30px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .back-link:hover {
            background-color: #0056b3;
        }
        p {
            text-align: center;
            color: #cc0000;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>專輯詳細資訊</h2>
        <?php if ($album): ?>
            <?php if (!empty($album['cover_url'])): ?>
                <img src="<?php echo htmlspecialchars($album['cover_url']); ?>" alt="<?php echo htmlspecialchars($album['title']); ?> 專輯封面" class="album-cover">
            <?php endif; ?>
            <div class="info"><label>專輯名稱:</label> <span><?php echo htmlspecialchars($album['title']); ?></span></div>
            <div class="info"><label>藝術家:</label> <span><?php echo htmlspecialchars($album['artist_name']); ?></span></div>
            <div class="info"><label>發行年份:</label> <span><?php echo $album['release_year']; ?></span></div>
            <div class="info"><label>定價:</label> <span><?php echo $album['price']; ?> 元</span></div>
            <div class="info"><label>專輯簡介:</label> <span><?php echo nl2br(htmlspecialchars($album['description'])); ?></span></div>
        <?php else: ?>
            <p>找不到該專輯。</p>
        <?php endif; ?>
        <a class="back-link" href="album_list.php">返回專輯列表</a>
    </div>
</body>
</html>