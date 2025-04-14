<?php
// 資料庫連線設定
$servername = "localhost"; // 伺服器名稱
$username = "root"; // 資料庫使用者名稱
$password = ""; // 資料庫密碼
$dbname = "books"; // 資料庫名稱

// 建立資料庫連線
$conn = new mysqli($servername, $username, $password, $dbname);

// 檢查連線是否成功
if ($conn->connect_error) {
    die("連線失敗: " . $conn->connect_error);
}

// 設定每頁顯示的筆數
$recordsPerPage = 10;

// 取得目前頁碼
if (isset($_GET["page"])) {
    $currentPage = $_GET["page"];
} else {
    $currentPage = 1;
}

// 計算資料起始筆數
$startRecord = ($currentPage - 1) * $recordsPerPage;

// 查詢資料總筆數
$sqlTotalRecords = "SELECT COUNT(*) AS total FROM `books`";
$resultTotalRecords = $conn->query($sqlTotalRecords);
$rowTotalRecords = $resultTotalRecords->fetch_assoc();
$totalRecords = $rowTotalRecords["total"];

// 計算總頁數
$totalPages = ceil($totalRecords / $recordsPerPage);

// 查詢分頁資料 (只取出部分欄位用於列表)
$sql = "SELECT `id`, `pname`, `pspec`, `price`, `pdate` FROM `books` LIMIT $startRecord, $recordsPerPage";
$result = $conn->query($sql);

// 處理新增資料的請求
if (isset($_POST["add_submit"])) {
    $pname = $conn->real_escape_string($_POST["pname"]);
    $pspec = $conn->real_escape_string($_POST["pspec"]);
    $price = intval($_POST["price"]);
    $pdate = $conn->real_escape_string($_POST["pdate"]);
    $content = $conn->real_escape_string($_POST["content"]);

    $sqlInsert = "INSERT INTO `books` (`pname`, `pspec`, `price`, `pdate`, `content`) VALUES ('$pname', '$pspec', $price, '$pdate', '$content')";

    if ($conn->query($sqlInsert) === TRUE) {
        echo "<script>alert('資料新增成功'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('資料新增失敗: " . $conn->error . "');</script>";
    }
}

// 處理修改資料的請求
if (isset($_POST["edit_submit"])) {
    $id = intval($_POST["id"]);
    $pname = $conn->real_escape_string($_POST["pname"]);
    $pspec = $conn->real_escape_string($_POST["pspec"]);
    $price = intval($_POST["price"]);
    $pdate = $conn->real_escape_string($_POST["pdate"]);
    $content = $conn->real_escape_string($_POST["content"]);

    $sqlUpdate = "UPDATE `books` SET `pname`='$pname', `pspec`='$pspec', `price`=$price, `pdate`='$pdate', `content`='$content' WHERE `id`=$id";

    if ($conn->query($sqlUpdate) === TRUE) {
        echo "<script>alert('資料修改成功'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('資料修改失敗: " . $conn->error . "');</script>";
    }
}

// 處理刪除資料的請求
if (isset($_GET["delete_id"])) {
    $deleteId = intval($_GET["delete_id"]);
    $sqlDelete = "DELETE FROM `books` WHERE `id`=$deleteId";

    if ($conn->query($sqlDelete) === TRUE) {
        echo "<script>alert('資料刪除成功'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('資料刪除失敗: " . $conn->error . "');</script>";
    }
}

// 取得要編輯的資料
$editData = null;
if (isset($_GET["edit_id"])) {
    $editId = intval($_GET["edit_id"]);
    $sqlEdit = "SELECT * FROM `books` WHERE `id`=$editId";
    $resultEdit = $conn->query($sqlEdit);
    if ($resultEdit->num_rows > 0) {
        $editData = $resultEdit->fetch_assoc();
    } else {
        echo "<script>alert('找不到要編輯的資料'); window.location.href='index.php';</script>";
        exit();
    }
}

// 取得要顯示詳細內容的資料
$detailData = null;
if (isset($_GET["detail_id"])) {
    $detailId = intval($_GET["detail_id"]);
    $sqlDetail = "SELECT * FROM `books` WHERE `id`=$detailId";
    $resultDetail = $conn->query($sqlDetail);
    if ($resultDetail->num_rows > 0) {
        $detailData = $resultDetail->fetch_assoc();
    } else {
        echo "<script>alert('找不到詳細資料'); window.location.href='index.php';</script>";
        exit();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>書籍資料管理</title>
    <style>
        table {
            border-collapse: collapse;
            width: 80%;
            margin: 20px auto;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .pagination {
            text-align: center;
            margin-top: 20px;
        }
        .pagination a {
            display: inline-block;
            padding: 5px 10px;
            margin: 0 5px;
            border: 1px solid #ccc;
            text-decoration: none;
        }
        .pagination a.active {
            background-color: #4CAF50;
            color: white;
        }
        .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
        }
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto; /* 15% from the top and centered */
            padding: 20px;
            border: 1px solid #888;
            width: 80%; /* Could be more or less, depending on screen size */
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
        .form-group {
            margin-bottom: 10px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-group input[type="text"],
        .form-group input[type="number"],
        .form-group input[type="date"],
        .form-group textarea {
            width: calc(100% solid #ccc;
            box-sizing: border-box;
        }
        .form-group textarea {
            height: 100px;
        }
        .btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            text-decoration: none;
        }
        .btn-edit {
            background-color: #008CBA;
        }
        .btn-delete {
            background-color: #f44336;
            color: white;
        }
        .btn-detail {
            background-color: #ff9800;
            color: white;
        }
        .btn-cancel {
            background-color: #ccc;
            color: black;
        }
        .detail-modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 60%;
        }
        .detail-modal-content h3 {
            margin-top: 0;
        }
        .detail-modal-content p {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

    <h2>書籍資料列表</h2>

    <button id="addBtn" class="btn">新增書籍</button>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>產品名稱</th>
                <th>產品規格</th>
                <th>產品定價</th>
                <th>製作日期</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["id"] . "</td>";
                    echo "<td>" . $row["pname"] . "</td>";
                    echo "<td>" . $row["pspec"] . "</td>";
                    echo "<td>" . $row["price"] . "</td>";
                    echo "<td>" . $row["pdate"] . "</td>";
                    echo "<td>";
                    echo "<a href='index.php?detail_id=" . $row["id"] . "' class='btn btn-detail'>詳細</a> ";
                    echo "<a href='index.php?edit_id=" . $row["id"] . "' class='btn btn-edit'>編輯</a> ";
                    echo "<a href='index.php?delete_id=" . $row["id"] . "' class='btn btn-delete' onclick='return confirm(\"確定要刪除這筆資料嗎？\");'>刪除</a>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>查無資料</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <div class="pagination">
        <?php
        if ($totalPages > 1) {
            for ($i = 1; $i <= $totalPages; $i++) {
                $activeClass = ($i == $currentPage) ? "active" : "";
                echo "<a href='index.php?page=" . $i . "' class='" . $activeClass . "'>" . $i . "</a>";
            }
        }
        ?>
    </div>

    <div id="addModal" class="modal">
        <div class="modal-content">
            <span class="close" id="closeAddModal">&times;</span>
            <h3>新增書籍資料</h3>
            <form method="post" action="index.php">
                <div class="form-group">
                    <label for="pname">產品名稱:</label>
                    <input type="text" id="pname_add" name="pname" required>
                </div>
                <div class="form-group">
                    <label for="pspec">產品規格:</label>
                    <input type="text" id="pspec_add" name="pspec" required>
                </div>
                <div class="form-group">
                    <label for="price">產品定價:</label>
                    <input type="number" id="price_add" name="price" required>
                </div>
                <div class="form-group">
                    <label for="pdate">製作日期:</label>
                    <input type="date" id="pdate_add" name="pdate" required>
                </div>
                <div class="form-group">
                    <label for="content">內容說明:</label>
                    <textarea id="content_add" name="content" required></textarea>
                </div>
                <button type="submit" class="btn" name="add_submit">新增</button>
            </form>
        </div>
    </div>

    <div id="editModal" class="modal" style="<?php echo ($editData !== null) ? 'display:block;' : 'display:none;'; ?>">
        <div class="modal-content">
            <span class="close" id="closeEditModal">&times;</span>
            <h3>編輯書籍資料</h3>
            <form method="post" action="index.php">
                <input type="hidden" name="id" value="<?php echo ($editData !== null) ? $editData['id'] : ''; ?>">
                <div class="form-group">
                    <label for="pname">產品名稱:</label>
                    <input type="text" id="pname_edit" name="pname" value="<?php echo ($editData !== null) ? $editData['pname'] : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label for="pspec">產品規格:</label>
                    <input type="text" id="pspec_edit" name="pspec" value="<?php echo ($editData !== null) ? $editData['pspec'] : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label for="price">產品定價:</label>
                    <input type="number" id="price_edit" name="price" value="<?php echo ($editData !== null) ? $editData['price'] : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label for="pdate">製作日期:</label>
                    <input type="date" id="pdate_edit" name="pdate" value="<?php echo ($editData !== null) ? $editData['pdate'] : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label for="content">內容說明:</label>
                    <textarea id="content_edit" name="content" required><?php echo ($editData !== null) ? $editData['content'] : ''; ?></textarea>
                </div>
                <button type="submit" class="btn" name="edit_submit">儲存</button>
                <a href="index.php" class="btn btn-cancel">取消</a>
            </form>
        </div>
    </div>

    <div id="detailModal" class="modal" style="<?php echo ($detailData !== null) ? 'display:block;' : 'display:none;'; ?>">
        <div class="detail-modal-content">
            <span class="close" id="closeDetailModal">&times;</span>
            <h3>書籍詳細內容</h3>
            <?php if ($detailData !== null): ?>
                <p><strong>ID:</strong> <?php echo $detailData['id']; ?></p>
                <p><strong>產品名稱:</strong> <?php echo $detailData['pname']; ?></p>
                <p><strong>產品規格:</strong> <?php echo $detailData['pspec']; ?></p>
                <p><strong>產品定價:</strong> <?php echo $detailData['price']; ?></p>
                <p><strong>製作日期:</strong> <?php echo $detailData['pdate']; ?></p>
                <p><strong>內容說明:</strong><br><?php echo nl2br($detailData['content']); ?></p>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Get the modals
        var addModal = document.getElementById("addModal");
        var editModal = document.getElementById("editModal");
        var detailModal = document.getElementById("detailModal");

        // Get the buttons that open the modals
        var addBtn = document.getElementById("addBtn");

        // Get the close buttons
        var closeAddModal = document.getElementById("closeAddModal");
        var closeEditModal = document.getElementById("closeEditModal");
        var closeDetailModal = document.getElementById("closeDetailModal");

        // Event listeners to open the add modal
        addBtn.addEventListener('click', function() {
            addModal.style.display = "block";
        });

        // Event listeners to close the modals
        closeAddModal.addEventListener('click', function() {
            addModal.style.display = "none";
        });

        closeEditModal.addEventListener('click', function() {
            editModal.style.display = "none";
            // 清除 edit_id 參數
            window.history.replaceState({}, document.title, window.location.pathname);
        });

        closeDetailModal.addEventListener('click', function() {
            detailModal.style.display = "none";
            // 清除 detail_id 參數
            window.history.replaceState({}, document.title, window.location.pathname);
        });

        // Close modal when clicking outside
        window.addEventListener('click', function(event) {
            if (event.target == addModal) {
                addModal.style.display = "none";
            }
            if (event.target == editModal) {
                editModal.style.display = "none";
                // 清除 edit_id 參數
                window.history.replaceState({}, document.title, window.location.pathname);
            }
            if (event.target == detailModal) {
                detailModal.style.display = "none";
                // 清除 detail_id 參數
                window.history.replaceState({}, document.title, window.location.pathname);
            }
        });

        // 如果有 edit_id 參數，則自動開啟編輯 Modal
        <?php if (isset($_GET["edit_id"])): ?>
            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('editModal').style.display = 'block';
            });
        <?php endif; ?>

        // 如果有 detail_id 參數，則自動開啟詳細內容 Modal
        <?php if (isset($_GET["detail_id"])): ?>
            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('detailModal').style.display = 'block';
            });
        <?php endif; ?>
    </script>

</body>
</html>