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

$conn = null; // 初始化連線變數
$movies = []; // 初始化電影列表

try {
    // 使用 PDO 連線到 MySQL 資料庫
    $conn = new PDO("mysql:host=$hostname;dbname=$database;charset=UTF8", $dbuser, $dbpass);

    // 設定 PDO 錯誤處理模式為例外
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 刪除電影處理
    if (isset($_GET['delete'])) {
        $id = filter_var($_GET['delete'], FILTER_SANITIZE_NUMBER_INT);
        $stmt = $conn->prepare("DELETE FROM movie WHERE id = ?");
        $stmt->execute([$id]);
        
        // 刪除成功後導向電影列表頁面
        header("Location: manage_movies.php");
        exit();
    }

    // 取得電影列表
    $stmt = $conn->query("SELECT * FROM movie");
    $movies = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // 如果連線或操作失敗，顯示錯誤訊息
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
    <title>電影管理</title>
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
        .action-links a {
            margin-right: 8px; /* 動作連結之間的間距 */
            color: #3b82f6;
            text-decoration: none;
            transition: color 0.2s ease-in-out;
        }
        .action-links a:hover {
            color: #2563eb;
            text-decoration: underline;
        }
        .action-links .delete-link {
            color: #ef4444; /* 紅色刪除連結 */
        }
        .action-links .delete-link:hover {
            color: #dc2626;
        }

        /* Modal Styles (basic for custom implementation) */
        .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1000; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
            justify-content: center; /* Center horizontally */
            align-items: center; /* Center vertically */
        }
        .modal-content {
            background-color: #fefefe;
            margin: auto;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            width: 90%;
            max-width: 400px;
            text-align: center;
        }
        .close-button {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close-button:hover,
        .close-button:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
        .modal-buttons {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 20px;
        }
    </style>
</head>
<body class="p-8">
    <div class="container mx-auto bg-white rounded-lg shadow-lg p-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">電影管理</h1>

        <p class="mb-4 flex justify-between items-center">
            <?php if (loginOK()) { ?>
                <span class="text-gray-700">管理者: <span class="font-semibold text-indigo-700"><?php echo htmlspecialchars($_SESSION["username"]); ?></span></span>
                <a class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-lg transition ease-in-out duration-150" href="#" id="logout">登出</a>
            <?php } else { ?>
                <!-- Login button is managed by JS in action.php, removed from here as per original structure. -->
            <?php } ?> 
        </p>

        <div class="overflow-x-auto rounded-lg shadow-md">
            <table class="min-w-full bg-white">
                <thead>
                    <tr>
                        <th class="py-3 px-6 text-xs uppercase tracking-wider rounded-tl-lg">ID</th>
                        <th class="py-3 px-6 text-xs uppercase tracking-wider">電影名稱</th>
                        <th class="py-3 px-6 text-xs uppercase tracking-wider">發行年份</th>
                        <th class="py-3 px-6 text-xs uppercase tracking-wider">導演</th>
                        <th class="py-3 px-6 text-xs uppercase tracking-wider">類型</th>
                        <th class="py-3 px-6 text-xs uppercase tracking-wider">首映日期</th>
                        <th class="py-3 px-6 text-xs uppercase tracking-wider">內容簡介</th>
                        <th class="py-3 px-6 text-xs uppercase tracking-wider rounded-tr-lg">
                            操作
                            <?php if (loginOK()) { ?>
                                <a href="add_movie.php" class="ml-4 bg-green-500 hover:bg-green-600 text-white font-bold py-1 px-3 rounded-md transition ease-in-out duration-150 text-sm">新增</a>
                            <?php } ?> 
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($movies)): ?>
                        <?php foreach ($movies as $movie): ?>
                        <tr>
                            <td class="py-4 px-6 text-sm text-gray-700"><?php echo htmlspecialchars($movie["id"]); ?></td>
                            <td class="py-4 px-6 text-sm text-gray-700"><?php echo htmlspecialchars($movie["title"]); ?></td>
                            <td class="py-4 px-6 text-sm text-gray-700"><?php echo htmlspecialchars($movie["year"]); ?></td>
                            <td class="py-4 px-6 text-sm text-gray-700"><?php echo htmlspecialchars($movie["director"]); ?></td>
                            <td class="py-4 px-6 text-sm text-gray-700"><?php echo htmlspecialchars($movie["mtype"]); ?></td>
                            <td class="py-4 px-6 text-sm text-gray-700"><?php echo htmlspecialchars($movie["mdate"]); ?></td>
                            <td class="py-4 px-6 text-sm text-gray-700"><?php echo nl2br(htmlspecialchars($movie["content"])); ?></td>
                            <td class="py-4 px-6 text-sm text-gray-700 action-links">
                                <a href="movie_details.php?id=<?php echo htmlspecialchars($movie['id']); ?>" class="text-blue-500 hover:text-blue-700">查看</a>
                                <?php if (loginOK()) { ?>
                                    <a href="edit_movie.php?id=<?php echo htmlspecialchars($movie['id']); ?>" class="text-indigo-500 hover:text-indigo-700">修改</a>
                                    <!-- Custom delete confirmation trigger -->
                                    <a href="#" class="delete-link text-red-500 hover:text-red-700" data-movie-id="<?php echo htmlspecialchars($movie['id']); ?>">刪除</a>
                                <?php } ?> 
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="py-4 px-6 text-center text-gray-600">目前沒有電影資料。</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Custom Delete Confirmation Modal -->
    <div id="deleteConfirmationModal" class="modal">
        <div class="modal-content">
            <span class="close-button">&times;</span>
            <p class="text-lg font-semibold mb-4">確定要刪除這部電影嗎？</p>
            <div class="modal-buttons">
                <button id="confirmDeleteBtn" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-lg transition ease-in-out duration-150">
                    確定刪除
                </button>
                <button id="cancelDeleteBtn" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg transition ease-in-out duration-150">
                    取消
                </button>
            </div>
            <input type="hidden" id="movieToDeleteId">
        </div>
    </div>

    <!-- Login Modal (re-styled with Tailwind) - assumes action.php handles login via AJAX -->
    <div id="loginModal" class="modal">
        <div class="modal-content">
            <span class="close-button" id="closeLoginModal">&times;</span>
            <h5 class="text-xl font-semibold mb-6">登入管理</h5>
            <form action="#" method="post">
                <div class="mb-4">
                    <label for="username" class="sr-only">使用者名稱</label>
                    <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" name="username" id="username" placeholder="使用者名稱" required>
                </div>
                <div class="mb-4">
                    <label for="userpass" class="sr-only">密碼</label>
                    <input type="password" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" name="userpass" id="userpass" placeholder="密碼" required>
                </div>
            </form>
            <button type="button" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg transition ease-in-out duration-150 w-full" id="login_button">登入系統</button>
        </div>
    </div>


<!-- 透過 CDN 載入 jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function () {
    // Custom Delete Modal Logic
    var deleteModal = $('#deleteConfirmationModal');
    var confirmDeleteBtn = $('#confirmDeleteBtn');
    var cancelDeleteBtn = $('#cancelDeleteBtn');
    var movieIdToDeleteInput = $('#movieToDeleteId');
    var closeDeleteButton = deleteModal.find('.close-button');

    $('.delete-link').on('click', function(e) {
        e.preventDefault(); // 阻止默認連結行為
        var movieId = $(this).data('movie-id');
        movieIdToDeleteInput.val(movieId); // 將電影 ID 存入隱藏輸入框
        deleteModal.css('display', 'flex'); // 顯示刪除確認視窗
    });

    confirmDeleteBtn.on('click', function() {
        var id = movieIdToDeleteInput.val();
        window.location.href = 'manage_movies.php?delete=' + id; // 重定向以執行刪除
    });

    cancelDeleteBtn.on('click', function() {
        deleteModal.css('display', 'none'); // 隱藏刪除確認視窗
    });

    closeDeleteButton.on('click', function() {
        deleteModal.css('display', 'none'); // 隱藏刪除確認視窗
    });

    // 點擊視窗外部區域也關閉視窗
    $(window).on('click', function(event) {
        if ($(event.target).is(deleteModal)) {
            deleteModal.css('display', 'none');
        }
        if ($(event.target).is($('#loginModal'))) {
            $('#loginModal').css('display', 'none');
        }
    });

    // Login Modal Logic (re-added for completeness, assuming original action.php still handles it)
    var loginModal = $('#loginModal');
    var closeLoginModalButton = $('#closeLoginModal');

    // Show login modal when login button is clicked (if it exists, though not in this specific PHP)
    // You would typically have a dedicated login button somewhere else that triggers this modal
    // For now, I'll add a placeholder to demonstrate
    $('body').on('click', '[data-bs-target="#loginModal"]', function() { // Re-using original Bootstrap trigger if it exists
        loginModal.css('display', 'flex');
    });

    closeLoginModalButton.on('click', function() {
        loginModal.css('display', 'none');
    });

    // 執行登入認證 (從你提供的原始 JS 程式碼複製)
    $('#login_button').click(function () {
        var username = $('#username').val();
        var userpass = $('#userpass').val();

        if (username !== '' && userpass !== '') {
            $.ajax({
                url: "action.php",
                method: "POST",
                data: {
                    "action": "login",
                    "username": username,
                    "userpass": userpass
                },
                success: function (data) {
                    if (data === 'Yes') {
                        location.reload();
                        // alert("成功登入系統..."); // Replaced with console log or custom message
                        console.log("成功登入系統...");
                    } else {
                        // alert('帳密無法使用!'); // Replaced with console log or custom message
                        console.log('帳密無法使用!');
                        alert('帳密無法使用!'); // Using alert for now as no custom UI for this specific alert
                    }
                },
                error: function (xhr, status, error) {
                    console.error('登入失敗:', error);
                    // alert('無法登入'); // Replaced with console log or custom message
                    alert('無法登入'); // Using alert for now as no custom UI for this specific alert
                }
            });
        } else {
            alert("兩個欄位都要填寫!"); // Using alert for now as no custom UI for this specific alert
        }
    });

    // 執行登出 (從你提供的原始 JS 程式碼複製)
    $('#logout').click(function () {
        $.ajax({
            url: "action.php",
            method: "POST",
            data: {
                "action": "logout",
            },
            success: function () {
                location.reload();
                // alert("您已登出本系統..."); // Replaced with console log or custom message
                console.log("您已登出本系統...");
            }
        });
    });
});
</script>
</body>
</html>
