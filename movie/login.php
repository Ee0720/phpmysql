<?php
// Initialize the session
session_start();

// Include config file (確保 dbconfig.php 存在並包含資料庫連線設定)
require_once "dbconfig.php";

// Check if the user is already logged in, if yes then redirect him to the main page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: " . $main); // 使用 $main 變數進行跳轉
    exit;
}

// Define variables and initialize with empty values
$username = $userpass = "";
$username_err = $userpass_err = $login_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    $conn = null; // 初始化連線變數
    try {
        // 使用 PDO 連線到 MySQL 資料庫
        $conn = new PDO("mysql:host=$hostname;dbname=$database;charset=UTF8", $dbuser, $dbpass);
        // 設定錯誤處理模式為例外
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Check if username is empty
        if(empty(trim($_POST["username"]))){
            $username_err = "請輸入使用者名稱。";
        } else{
            $username = trim($_POST["username"]);
        }

        // Check if password is empty
        if(empty(trim($_POST["userpass"]))){
            $userpass_err = "請輸入密碼。";
        } else{
            $userpass = trim($_POST["userpass"]);
        }

        // Validate credentials
        if(empty($username_err) && empty($userpass_err)){
            // Prepare a select statement
            $sql = "SELECT id, username, userpass FROM users WHERE username = :username"; // 假設使用者資料儲存在 'users' 表中

            if($stmt = $conn->prepare($sql)){
                // Bind variables to the prepared statement as parameters
                $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);

                // Set parameters
                $param_username = $username; // Use the trimmed username

                // Attempt to execute the prepared statement
                if($stmt->execute()){
                    // Check if username exists, if yes then verify password
                    if($stmt->rowCount() == 1){
                        if($row = $stmt->fetch()){
                            $id = $row["id"];
                            $username = $row["username"];
                            $hashed_password = $row["userpass"];

                            if(password_verify($userpass, $hashed_password)){
                                // Password is correct, so start a new session (if not already started)
                                if (session_status() == PHP_SESSION_NONE) {
                                    session_start();
                                }

                                // Store data in session variables
                                $_SESSION["loggedin"] = true;
                                $_SESSION["id"] = $id;
                                $_SESSION["username"] = $username;

                                // Redirect user to main page
                                header("location: " . $main);
                                exit(); // Ensure no further code is executed after redirection
                            } else{
                                // Password is not valid, display a generic error message
                                $login_err = "使用者名稱或密碼無效。";
                            }
                        }
                    } else{
                        // Username doesn't exist, display a generic error message
                        $login_err = "使用者名稱或密碼無效。";
                    }
                } else{
                    echo "Oops! 發生了一些錯誤，請稍後再試。";
                }
                // No need to unset $stmt for PDO, it's handled by garbage collection or explicit nulling
            }
        }
    } catch (PDOException $e) {
        die("資料庫連線或操作失敗: " . $e->getMessage());
    } finally {
        // Close connection
        $conn = null;
    }
}
?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- 引入 Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <title>登入</title>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .container {
            max-width: 400px;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
            width: 100%; /* Make it responsive */
        }
        .form-group label {
            font-weight: bold;
            display: block;
            margin-bottom: 8px;
            color: #333;
        }
        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            box-sizing: border-box; /* Ensure padding doesn't increase width */
            font-size: 1rem;
        }
        .form-control:focus {
            border-color: #3b82f6;
            outline: none;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.25);
        }
        .is-invalid {
            border-color: #ef4444; /* Red for invalid input */
        }
        .invalid-feedback {
            color: #ef4444;
            font-size: 0.875rem;
            margin-top: 4px;
            display: block;
        }
        .alert-danger {
            background-color: #fee2e2;
            color: #b91c1c;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: 500;
        }
        .btn-primary {
            background-color: #3b82f6;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 6px;
            margin-top: 25px;
            cursor: pointer;
            width: 100%;
            font-size: 1.1rem;
            transition: background-color 0.2s ease-in-out;
        }
        .btn-primary:hover {
            background-color: #2563eb;
        }
        .link-text {
            color: #3b82f6;
            text-decoration: none;
            font-weight: 500;
        }
        .link-text:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-3xl font-bold text-gray-800 mb-6 text-center">登入</h2>
        <p class="text-center text-gray-600 mb-8">請輸入您的憑證以登入。</p>

        <?php
        if(!empty($login_err)){
            echo '<div class="alert-danger">' . htmlspecialchars($login_err) . '</div>';
        }
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="mb-4">
                <label for="username">使用者名稱</label>
                <input type="text" name="username" id="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($username); ?>">
                <span class="invalid-feedback"><?php echo htmlspecialchars($username_err); ?></span>
            </div>

            <div class="mb-6">
                <label for="userpass">密碼</label>
                <input type="password" name="userpass" id="userpass" class="form-control <?php echo (!empty($userpass_err)) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo htmlspecialchars($userpass_err); ?></span>
            </div>

            <div class="mb-4">
                <input type="submit" class="btn-primary" value="登入">
            </div>

            <p class="text-center text-gray-600">還沒有帳號嗎？ <a href="register.php" class="link-text">立即註冊</a>。</p>
        </form>
    </div>
</body>
</html>
