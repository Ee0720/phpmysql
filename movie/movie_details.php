<?php
session_start();

// 引入資料庫設定檔
require_once "dbconfig.php";

// 檢查使用者是否已登入
function loginOK() {
    return (isset($_SESSION["loggedin"]) && ($_SESSION["loggedin"] === true));
}

// 如果未登入，則導向登入頁面
if (!loginOK()) { 
    header("location: login.php");
    exit(); // 確保在重定向後停止執行腳本
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0; // 從 URL 參數獲取電影 ID

$movie = null; // 初始化電影變數

// 嘗試建立資料庫連線並獲取電影資料
try {
    // 使用 PDO 連線到 MySQL 資料庫
    $conn = new PDO("mysql:host=$hostname;dbname=$database;charset=UTF8", $dbuser, $dbpass);

    // 設定 PDO 錯誤處理模式為例外
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 準備 SQL 查詢語句，根據 ID 選取單部電影
    $stmt = $conn->prepare("SELECT * FROM movie WHERE id = ?");
    
    // 綁定 ID 參數
    $stmt->execute([$id]);
    
    // 獲取查詢結果
    $movie = $stmt->fetch(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // 如果連線或查詢失敗，顯示錯誤訊息
    die("資料庫連線或查詢失敗: " . $e->getMessage());
} finally {
    // 關閉資料庫連線
    $conn = null;
}
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>電影詳細資訊</title>
    <!-- 引入 Tailwind CSS CDN，用於快速排版和美化 -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* 自訂樣式 */
        body {
            font-family: 'Inter', sans-serif; /* 設定字體 */
            background-color: #f0f2f5; /* 輕微的背景色 */
        }
        .container {
            max-width: 700px; /* 稍微寬一點的容器 */
            background: white;
            padding: 30px; /* 增加內邊距 */
            border-radius: 10px; /* 更圓潤的圓角 */
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1); /* 更明顯的陰影 */
            margin: 40px auto; /* 增加上下外邊距 */
        }
        h2 {
            text-align: center;
            color: #333; /* 深色標題文字 */
            font-size: 2.25rem; /* 更大的標題字體 */
            font-weight: bold;
            margin-bottom: 25px; /* 增加標題下外邊距 */
        }
        .info {
            margin-bottom: 15px;
            padding: 10px;
            border-bottom: 1px solid #e5e7eb; /* 淺灰色底部邊框 */
            display: flex; /* 使用 flexbox 讓標籤和內容對齊 */
            align-items: flex-start; /* 頂部對齊 */
        }
        .info:last-child {
            border-bottom: none; /* 最後一個項目沒有底部邊框 */
        }
        .info label {
            font-weight: bold;
            min-width: 100px; /* 最小寬度，確保標籤對齊 */
            color: #4a5568; /* 深灰色標籤文字 */
            margin-right: 15px; /* 標籤和內容之間的間距 */
        }
        .info span {
            flex-grow: 1; /* 讓內容佔據剩餘空間 */
            color: #6b7280; /* 淺灰色內容文字 */
            line-height: 1.6; /* 行高 */
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 30px; /* 增加連結上外邊距 */
            color: #3b82f6; /* 藍色連結文字 */
            text-decoration: none;
            font-weight: 500;
            padding: 10px 0;
            border: 1px solid #3b82f6;
            border-radius: 6px;
            transition: background-color 0.2s ease-in-out, color 0.2s ease-in-out;
        }
        .back-link:hover {
            background-color: #3b82f6;
            color: white;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if ($movie): ?>
            <h2><?php echo htmlspecialchars($movie['title']); ?></h2>
            <div class="info"><label>發行年份:</label> <span><?php echo htmlspecialchars($movie['year']); ?></span></div>
            <div class="info"><label>導演:</label> <span><?php echo htmlspecialchars($movie['director']); ?></span></div>
            <div class="info"><label>類型:</label> <span><?php echo htmlspecialchars($movie['mtype']); ?></span></div>
            <div class="info"><label>首映日期:</label> <span><?php echo htmlspecialchars($movie['mdate']); ?></span></div>
            <div class="info"><label>內容簡介:</label> <span><?php echo nl2br(htmlspecialchars($movie['content'])); ?></span></div>
        <?php else: ?>
            <p class="text-center text-red-500 text-lg font-semibold">找不到該電影。</p>
        <?php endif; ?>
        <a class="back-link" href="manage_movies.php">返回電影列表</a>
    </div>
</body>
</html>
