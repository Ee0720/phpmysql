<?php
// Initialize the session
session_start();

function loginOK() {
    return (isset($_SESSION["loggedin"]) && ($_SESSION["loggedin"]===true));
}

$conn = new mysqli("localhost", "root", "", "school");
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

$total_result = $conn->query("SELECT COUNT(*) AS total FROM product")->fetch_assoc();
$total_pages = ceil($total_result['total'] / $limit);
$result = $conn->query("SELECT * FROM product ORDER BY id DESC LIMIT $offset, $limit");

include 'header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>產品列表</h2>
    <!-- <span><?= $_SESSION["username"]; ?></span>
    <a href="logout.php" class="btn btn-success">登出</a> -->

    <?php if (loginOK()) { ?>
        <span>
        <a href="create.php" class="btn btn-success">➕ 新增產品</a>
        <a href="logout.php" class="btn btn-primary">登出</a>
        </span>
    <?php } else { ?>
        <a href="login.php" class="btn btn-primary">登入管理</a>
    <?php } ?>

</div>

<table class="table table-bordered table-hover bg-white">
    <thead class="table-light">
        <tr>
            <th>ID</th><th>產品名稱</th><th>價格</th><th>製造日期</th><th>操作</th>
        </tr>
    </thead>
    <tbody>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><a href="view.php?id=<?= $row['id'] ?>"><?= htmlspecialchars($row['pname']) ?></a></td>
            <td>$<?= $row['price'] ?></td>
            <td><?= $row['pdate'] ?></td>
            <td>

            <?php if (loginOK()) { ?>
                <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">編輯</a>
                <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('確認刪除?')">刪除</a>
            <?php } ?>
            
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<nav>
  <ul class="pagination">
    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <li class="page-item <?= $i == $page ? 'active' : '' ?>">
            <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
        </li>
    <?php endfor; ?>
  </ul>
</nav>

<?php include 'footer.php'; ?>
