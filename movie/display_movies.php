<?php
// 引入資料庫設定檔
require_once "dbconfig.php";

// 嘗試建立資料庫連線
try {
    // 使用 PDO 連線到 MySQL 資料庫
    $conn = new PDO("mysql:host=$hostname;dbname=$database;charset=UTF8", $dbuser, $dbpass);

    // 設定 PDO 錯誤處理模式為例外，這會讓 PDO 在發生錯誤時拋出例外
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 準備 SQL 查詢語句，選取所有電影資料
    $stmt = $conn->prepare("SELECT * FROM `movie`");
    // 執行查詢
    $stmt->execute();

    // 獲取所有查詢結果
    $movies = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
    <title>電影列表</title>
    <!-- 引入 Tailwind CSS CDN，用於快速排版和美化 -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* 自訂樣式 */
        body {
            font-family: 'Inter', sans-serif; /* 設定字體 */
            background-color: #f0f2f5; /* 輕微的背景色 */
        }
        table {
            width: 100%;
            border-collapse: collapse; /* 合併邊框 */
        }
        th, td {
            padding: 12px 15px; /* 內邊距 */
            text-align: left; /* 文字靠左對齊 */
            border-bottom: 1px solid #e2e8f0; /* 底部邊框 */
        }
        th {
            background-color: #4a5568; /* 表頭背景色 */
            color: #ffffff; /* 表頭文字顏色 */
            font-weight: bold; /* 字體加粗 */
        }
        tr:nth-child(even) {
            background-color: #f7fafc; /* 隔行變色 */
        }
        tr:hover {
            background-color: #edf2f7; /* 滑鼠懸停效果 */
        }
    </style>
</head>
<body class="p-8">
    <div class="container mx-auto bg-white rounded-lg shadow-lg p-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">電影列表</h1>

        <?php if (!empty($movies)): ?>
            <div class="overflow-x-auto rounded-lg">
                <table class="min-w-full bg-white">
                    <thead>
                        <tr>
                            <th class="py-3 px-6 text-xs uppercase tracking-wider rounded-tl-lg">ID</th>
                            <th class="py-3 px-6 text-xs uppercase tracking-wider">電影名稱</th>
                            <th class="py-3 px-6 text-xs uppercase tracking-wider">發行年份</th>
                            <th class="py-3 px-6 text-xs uppercase tracking-wider">導演</th>
                            <th class="py-3 px-6 text-xs uppercase tracking-wider">類型</th>
                            <th class="py-3 px-6 text-xs uppercase tracking-wider">首映日期</th>
                            <th class="py-3 px-6 text-xs uppercase tracking-wider rounded-tr-lg">內容簡介</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($movies as $movie): ?>
                            <tr>
                                <td class="py-4 px-6 text-sm text-gray-700"><?php echo htmlspecialchars($movie['id']); ?></td>
                                <td class="py-4 px-6 text-sm text-gray-700"><?php echo htmlspecialchars($movie['title']); ?></td>
                                <td class="py-4 px-6 text-sm text-gray-700"><?php echo htmlspecialchars($movie['year']); ?></td>
                                <td class="py-4 px-6 text-sm text-gray-700"><?php echo htmlspecialchars($movie['director']); ?></td>
                                <td class="py-4 px-6 text-sm text-gray-700"><?php echo htmlspecialchars($movie['mtype']); ?></td>
                                <td class="py-4 px-6 text-sm text-gray-700"><?php echo htmlspecialchars($movie['mdate']); ?></td>
                                <td class="py-4 px-6 text-sm text-gray-700"><?php echo htmlspecialchars($movie['content']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="text-center text-gray-600">目前沒有電影資料。</p>
        <?php endif; ?>
    </div>
</body>
</html>
