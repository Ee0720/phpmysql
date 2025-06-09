<?php
// Initialize the session
session_start();

// Include config file - make sure this file defines your database credentials
require_once "dbconfig.php";

// Define the main page to redirect to after successful login
// Make sure this variable is defined in dbconfig.php or here.
// For example: $main = "album_list.php";
$main_page = "album_list.php"; // Assuming album_list.php is your main page

// Check if the user is already logged in, if yes then redirect them to the main page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: " . $main_page);
    exit;
}

// Define variables and initialize with empty values
$email = $password = "";
$email_err = $password_err = $login_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    $conn = null; // Initialize connection to null outside try-catch
    try {
        $conn = new PDO("mysql:host=$hostname;dbname=$database;charset=UTF8", $dbuser, $dbpass);
        // Set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Check if email is empty
        if(empty(trim($_POST["email"]))){
            $email_err = "請輸入電子郵件。"; // Please enter email.
        } else{
            $email = trim($_POST["email"]);
        }

        // Check if password is empty
        if(empty(trim($_POST["password"]))){
            $password_err = "請輸入密碼。"; // Please enter your password.
        } else{
            $password = trim($_POST["password"]);
        }

        // Validate credentials
        if(empty($email_err) && empty($password_err)){

            // Prepare a select statement
            // Select id, name (for session), email, and hashed password from customers table
            $sql = "SELECT id, name, email, password FROM customers WHERE email = :email";

            if($stmt = $conn->prepare($sql)){

                // Bind variables to the prepared statement as parameters
                $stmt->bindParam(":email", $param_email, PDO::PARAM_STR);

                // Set parameters
                $param_email = $email; // Use the trimmed email

                // Attempt to execute the prepared statement
                if($stmt->execute()){

                    // Check if email exists, if yes then verify password
                    if($stmt->rowCount() == 1){

                        if($row = $stmt->fetch(PDO::FETCH_ASSOC)){ // Use FETCH_ASSOC for associative array
                            $id = $row["id"];
                            $customer_name = $row["name"]; // Get customer name
                            $customer_email = $row["email"];
                            $hashed_password = $row["password"]; // The 'password' column from customers table

                            if(password_verify($password, $hashed_password)){
                                // Password is correct, so start a new session (already started at top)

                                // Store data in session variables
                                $_SESSION["loggedin"] = true;
                                $_SESSION["customer_id"] = $id; // Changed to customer_id
                                $_SESSION["customer_name"] = $customer_name; // Store customer name
                                $_SESSION["customer_email"] = $customer_email; // Store customer email

                                // Redirect user to the main page
                                header("location: " . $main_page);
                                exit; // Important: terminate script after redirect
                            } else{
                                // Password is not valid, display a generic error message
                                $login_err = "電子郵件或密碼無效。"; // Invalid email or password.
                            }
                        }
                    } else{
                        // Email doesn't exist, display a generic error message
                        $login_err = "電子郵件或密碼無效。"; // Invalid email or password.
                    }
                } else{
                    echo "Oops! Something went wrong. Please try again later."; // Database execution error
                }
                // Close statement
                unset($stmt);
            }
        }
    } catch (PDOException $e) {
        // Catch any PDO exceptions during connection or query execution
        $login_err = "資料庫錯誤: " . $e->getMessage(); // Database error
        error_log("Login error: " . $e->getMessage()); // Log the error for debugging
    } finally {
        // Close connection in finally block to ensure it's always closed
        if ($conn) {
            $conn = null; // Unset PDO object
        }
    }
}
?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>客戶登入</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f8f9fa;
        }
        .container {
            max-width: 400px;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }
        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
        }
        p {
            text-align: center;
            margin-bottom: 20px;
            color: #666;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            margin-bottom: 5px;
            font-weight: bold;
            display: block;
        }
        .form-control {
            border-radius: 5px;
        }
        .invalid-feedback {
            display: block;
            margin-top: 5px;
        }
        .btn-primary {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            font-size: 1.1rem;
        }
        .alert-danger {
            text-align: center;
        }
        .link-text {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>客戶登入</h2>
        <p>請填寫您的電子郵件和密碼來登入。</p>

        <?php
        if(!empty($login_err)){
            echo '<div class="alert alert-danger">' . $login_err . '</div>';
        }
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

            <div class="form-group">
                <label for="email">電子郵件</label>
                <input type="email" name="email" id="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($email); ?>">
                <span class="invalid-feedback"><?php echo $email_err; ?></span>
            </div>

            <div class="form-group">
                <label for="password">密碼</label>
                <input type="password" name="password" id="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>

            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="登入">
            </div>

            <p class="link-text">還沒有帳號嗎？ <a href="customer_register.php">立即註冊</a>。</p>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>