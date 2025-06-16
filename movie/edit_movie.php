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

// 嘗試建立資料庫連線
try {
    // 使用 PDO 連線到 MySQL 資料庫
    $conn = new PDO("mysql:host=$hostname;dbname=$database;charset=UTF8", $dbuser, $dbpass);

    // 設定 PDO 錯誤處理模式為例外
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 處理表單提交 (更新電影資料)
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // 獲取並過濾表單輸入
        $id_to_update = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
        $title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
        $year = filter_var($_POST['year'], FILTER_SANITIZE_NUMBER_INT);
        $director = filter_var($_POST['director'], FILTER_SANITIZE_STRING);
        $mtype = filter_var($_POST['mtype'], FILTER_SANITIZE_STRING);
        $mdate = filter_var($_POST['mdate'], FILTER_SANITIZE_STRING);
        $content = filter_var($_POST['content'], FILTER_SANITIZE_STRING);

        // 準備 SQL UPDATE 語句
        $stmt = $conn->prepare("UPDATE movie SET title=?, year=?, director=?, mtype=?, mdate=?, content=? WHERE id=?");
        
        // 綁定參數並執行
        $stmt->execute([$title, $year, $director, $mtype, $mdate, $content, $id_to_update]);

        // 更新成功後導向電影列表頁面
        header("Location: display_movies.php"); // 假設電影列表頁為 display_movies.php
        exit();
    }

    // 從資料庫獲取要編輯的電影資料 (用於顯示在表單中)
    $stmt = $conn->prepare("SELECT * FROM movie WHERE id = ?");
    $stmt->execute([$id]);
    $movie = $stmt->fetch(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // 如果連線或查詢失敗，顯示錯誤訊息
    die("資料庫連線或操作失敗: " . $e->getMessage());
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
    <title>修改電影</title>
    <!-- 引入 Tailwind CSS CDN，用於快速排版和美化 -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* 自訂樣式 */
        body {
            font-family: 'Inter', sans-serif; /* 設定字體 */
            background-color: #f0f2f5; /* 輕微的背景色 */
        }
        .container {
            max-width: 600px; /* 稍微寬一點的容器 */
            background: white;
            padding: 30px; /* 增加內邊距 */
            border-radius: 10px; /* 更圓潤的圓角 */
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1); /* 更明顯的陰影 */
            margin: 40px auto; /* 增加上下外邊距 */
        }
        label {
            font-weight: bold;
            display: block;
            margin-top: 15px; /* 增加間距 */
            color: #333; /* 深色標籤文字 */
        }
        input, textarea {
            width: 100%;
            padding: 10px; /* 增加輸入框內邊距 */
            margin-top: 8px; /* 增加間距 */
            border: 1px solid #d1d5db; /* 淺色邊框 */
            border-radius: 6px; /* 圓潤的邊框 */
            box-sizing: border-box; /* 確保 padding 不增加寬度 */
            font-size: 1rem; /* 確保字體大小適中 */
        }
        input:focus, textarea:focus {
            border-color: #3b82f6; /* 焦點時的藍色邊框 */
            outline: none; /* 移除預設外框 */
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.25); /* 焦點時的陰影 */
        }
        button {
            background-color: #3b82f6; /* 鮮豔的藍色 */
            color: white;
            padding: 12px 20px; /* 更大的按鈕 */
            border: none;
            border-radius: 6px;
            margin-top: 25px; /* 增加按鈕上外邊距 */
            cursor: pointer;
            width: 100%;
            font-size: 1.1rem; /* 更大的字體 */
            transition: background-color 0.2s ease-in-out; /* 平滑過渡效果 */
        }
        button:hover {
            background-color: #2563eb; /* 滑鼠懸停時的深藍色 */
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px; /* 增加連結上外邊距 */
            color: #3b82f6; /* 藍色連結文字 */
            text-decoration: none;
            font-weight: 500;
        }
        .back-link:hover {
            text-decoration: underline; /* 滑鼠懸停時底線 */
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">修改電影</h2>
        <?php if ($movie): ?>
            <form method="post">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($movie['id']); ?>">
                
                <label for="title">電影名稱:</label>
                <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($movie['title']); ?>" required class="focus:border-blue-500 focus:ring focus:ring-blue-200">
                
                <label for="year">發行年份:</label>
                <input type="number" id="year" name="year" value="<?php echo htmlspecialchars($movie['year']); ?>" required min="1800" max="2100" class="focus:border-blue-500 focus:ring focus:ring-blue-200">
                
                <label for="director">導演:</label>
                <input type="text" id="director" name="director" value="<?php echo htmlspecialchars($movie['director']); ?>" required class="focus:border-blue-500 focus:ring focus:ring-blue-200">
                
                <label for="mtype">類型:</label>
                <input type="text" id="mtype" name="mtype" value="<?php echo htmlspecialchars($movie['mtype']); ?>" required class="focus:border-blue-500 focus:ring focus:ring-blue-200">
                
                <label for="mdate">首映日期:</label>
                <input type="date" id="mdate" name="mdate" value="<?php echo htmlspecialchars($movie['mdate']); ?>" required class="focus:border-blue-500 focus:ring focus:ring-blue-200">
                
                <label for="content">內容簡介:</label>
                <textarea id="content" name="content" rows="6" required class="focus:border-blue-500 focus:ring focus:ring-blue-200"><?php echo htmlspecialchars($movie['content']); ?></textarea>
                
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded transition ease-in-out duration-150">儲存變更</button>
            </form>
        <?php else: ?>
            <p class="text-center text-red-500 text-lg font-semibold">找不到該電影。</p>
        <?php endif; ?>
        <a class="back-link" href="display_movies.php">返回電影列表</a>
    </div>
</body>
</html>
