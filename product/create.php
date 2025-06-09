<?php
session_start();

function loginOK() {
    return (isset($_SESSION["loggedin"]) && ($_SESSION["loggedin"]===true));
}

if (!loginOK()) {
    header("location: login.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "school");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pname = $_POST['pname'];
    $pspec = $_POST['pspec'];
    $price = (int)$_POST['price'];
    $pdate = $_POST['pdate'];
    $content = $_POST['content'];

    $stmt = $conn->prepare("INSERT INTO product (pname, pspec, price, pdate, content) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssiss", $pname, $pspec, $price, $pdate, $content);
    $stmt->execute();
    header("Location: index.php");
}

include 'header.php';

// Get today's date in YYYY-MM-DD format
$today = date('Y-m-d');
?>

<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <h3 class="mb-0">新增產品</h3>
    </div>
    <div class="card-body">
        <form method="post">
            <div class="mb-3">
                <label class="form-label">產品名稱</label>
                <input name="pname" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">產品規格</label>
                <input name="pspec" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">定價</label>
                <input name="price" type="number" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">製造日期</label>
                <input name="pdate" type="date" class="form-control" value="<?= $today ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">內容說明</label>
                <textarea name="content" rows="5" class="form-control" required></textarea>
            </div>
            <div class="d-flex justify-content-between">
                <button class="btn btn-primary">儲存</button>
                <a href="index.php" class="btn btn-secondary">取消</a>
            </div>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>
