<?php
session_start();

// Include database configuration file
require_once "dbconfig.php"; // This should point to your music_store database config

// Function to check if the user is logged in
function loginOK() {
    return (isset($_SESSION["loggedin"]) && ($_SESSION["loggedin"] === true));
}

// Establish database connection using MySQLi
$conn = new mysqli($hostname, $dbuser, $dbpass, $database);

// Check connection
if ($conn->connect_error) {
    die("連線失敗: " . $conn->connect_error);
}

// Set character set to UTF-8
$conn->set_charset("utf8mb4");

// Handle album deletion
if (isset($_GET['delete'])) {
    if (loginOK()) { // Only allow deletion if logged in
        $id = (int)$_GET['delete'];
        // Delete related tracks first due to foreign key constraints if you don't use ON DELETE CASCADE
        // Or, if 'ON DELETE CASCADE' is set on your foreign key, deleting the album will automatically delete its tracks.
        // For simplicity, we'll assume ON DELETE CASCADE or handle it only for the album.
        
        $stmt = $conn->prepare("DELETE FROM albums WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        header("Location: album_list.php");
        exit();
    } else {
        echo "<script>alert('請先登入才能執行刪除操作！'); window.location.href='album_list.php';</script>";
        exit();
    }
}

// Retrieve album list with artist names
$sql = "SELECT 
            a.id, 
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
        ORDER BY a.id ASC"; // Order by ID for consistent display

$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>專輯管理</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css">
    <style>
        body { margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; vertical-align: middle; }
        th { background-color: #f2f2f2; color: #333; font-weight: bold; }
        h1 { text-align: center; margin-bottom: 30px; color: #007bff; }
        .album-cover-thumb { width: 50px; height: 50px; object-fit: cover; border-radius: 4px; }
        .action-links a { margin-right: 8px; text-decoration: none; }
        .top-section { margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; }
        .login-info { display: flex; align-items: center; gap: 10px; }
    </style>
</head>
<body>
    <h1>專輯管理</h1>

    <div class="top-section">
        <div class="login-info">
            <?php if (loginOK()) { ?>
                <a class="btn btn-danger btn-sm" href="#" id="logout">登出</a>
                <span class="badge bg-primary">管理者: <?= htmlspecialchars($_SESSION["customer_name"]); ?></span>
            <?php } else { ?>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#loginModal">
                    登入管理
                </button>
            <?php } ?>
        </div>
        <?php if (loginOK()) { ?>
            <a href="add_album.php" class="btn btn-success btn-sm">新增專輯</a>
        <?php } ?>
    </div>

    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>專輯封面</th>
                <th>專輯名稱</th>
                <th>藝術家</th>
                <th>發行年份</th>
                <th>定價</th>
                <th>簡介</th>
                <th class="text-center">操作</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row["id"]; ?></td>
                    <td>
                        <?php if (!empty($row['cover_url'])): ?>
                            <img src="<?php echo htmlspecialchars($row['cover_url']); ?>" alt="Cover" class="album-cover-thumb">
                        <?php else: ?>
                            無封面
                        <?php endif; ?>
                    </td>
                    <td><?php echo htmlspecialchars($row["title"]); ?></td>
                    <td><?php echo htmlspecialchars($row["artist_name"]); ?></td>
                    <td><?php echo $row["release_year"]; ?></td>
                    <td><?php echo $row["price"]; ?> 元</td>
                    <td><?php echo nl2br(htmlspecialchars(mb_strimwidth($row["description"], 0, 100, '...'))); ?></td> <td class="action-links text-center">
                        <a href="view_album.php?id=<?php echo $row['id']; ?>" class="btn btn-info btn-sm">查看</a>
                        <?php if (loginOK()) { ?>
                            <a href="edit_album.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">修改</a>
                            <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('確定要刪除專輯「<?php echo htmlspecialchars($row['title']); ?>」嗎？這將無法復原！');">刪除</a>
                        <?php } ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8" class="text-center">目前沒有任何專輯。</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

<div class="modal fade" id="loginModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loginModalLabel">登入管理</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="#" method="post">
                    <div class="form-floating m-1">
                        <input type="email" class="form-control" name="email" id="email" placeholder="Email Address" required="required">
                        <label for="email">Email Address</label>
                    </div>
                    <div class="form-floating m-1">
                        <input type="password" class="form-control" name="password" id="password" placeholder="Password" required="required">
                        <label for="password">Password</label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="login_button">登入系統</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function () {

    // Execute login authentication
    $('#login_button').click(function () {
        var email = $('#email').val(); // Changed from username to email
        var password = $('#password').val(); // Changed from userpass to password

        if (email != '' && password != '') {
            $.ajax({
                url: "customer_auth.php", // Changed to the new authentication script
                method: "POST",
                data: {
                    "action": "login",
                    "email": email, // Changed to email
                    "password": password // Changed to password
                },
                success: function (data) {
                    if (data.trim() == 'Yes') { // Use .trim() to remove potential whitespace
                        location.reload();
                        alert("成功登入系統...");
                    } else {
                        alert('帳密無法使用！');
                    }
                },
                error: function (xhr, status, error) { // Enhanced error handling
                    console.error("AJAX error:", status, error);
                    alert('登入失敗，請稍後再試。');
                }
            });
        } else {
            alert("Email 和密碼都必須填寫！");
        }
    });

    // Execute logout
    $('#logout').click(function (e) {
        e.preventDefault(); // Prevent default link behavior
        $.ajax({
            url: "customer_auth.php", // Changed to the new authentication script
            method: "POST",
            data: {
                "action": "logout",
            },
            success: function () {
                location.reload();
                alert("您已登出本系統...");
            },
            error: function (xhr, status, error) { // Enhanced error handling
                console.error("AJAX error:", status, error);
                alert('登出失敗，請稍後再試。');
            }
        });
    });
});
</script>
</body>
</html>