<?php
$conn = new mysqli("localhost", "root", "", "school");
$id = (int)$_GET['id'];
$result = $conn->query("SELECT * FROM product WHERE id = $id");
$row = $result->fetch_assoc();

include 'header.php';
?>

<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0">產品詳細資料</h4>
    </div>
    <div class="card-body">
        <dl class="row">
            <dt class="col-sm-3">產品名稱</dt>
            <dd class="col-sm-9"><?= htmlspecialchars($row['pname']) ?></dd>

            <dt class="col-sm-3">產品規格</dt>
            <dd class="col-sm-9"><?= htmlspecialchars($row['pspec']) ?></dd>

            <dt class="col-sm-3">價格</dt>
            <dd class="col-sm-9">$<?= number_format($row['price']) ?></dd>

            <dt class="col-sm-3">製造日期</dt>
            <dd class="col-sm-9"><?= $row['pdate'] ?></dd>

            <dt class="col-sm-3">內容說明</dt>
            <dd class="col-sm-9"><pre class="mb-0"><?= htmlspecialchars($row['content']) ?></pre></dd>
        </dl>
    </div>
    <div class="card-footer d-flex justify-content-between">
        <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-warning">✏️ 編輯</a>
        <a href="index.php" class="btn btn-secondary">⬅ 返回列表</a>
    </div>
</div>

<?php include 'footer.php'; ?>
