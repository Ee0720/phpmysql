<?php
$servername = "localhost"; // 您的資料庫伺服器名稱
$username = "root"; // 您的資料庫使用者名稱
$password = ""; // 您的資料庫密碼
$dbname = "schooi";   // 您的資料庫名稱

$message = "";
$action = isset($_GET['action']) ? $_GET['action'] : 'list';
$id = isset($_GET['id']) && is_numeric($_GET['id']) ? $_GET['id'] : 0;

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("資料庫連線失敗: " . $e->getMessage());
}

// 處理新增資料
if ($action == 'add' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $year = $_POST['year'];
    $director = $_POST['director'];
    $mtype = $_POST['mtype'];
    $mdate = $_POST['mdate'];
    $content = $_POST['content'];

    $sql = "INSERT INTO movie (title, year, director, mtype, mdate, content) VALUES (:title, :year, :director, :mtype, :mdate, :content)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':year', $year, PDO::PARAM_INT);
    $stmt->bindParam(':director', $director);
    $stmt->bindParam(':mtype', $mtype);
    $stmt->bindParam(':mdate', $mdate);
    $stmt->bindParam(':content', $content);

    if ($stmt->execute()) {
        $message = "電影新增成功！";
        $action = 'list'; // 返回列表
    } else {
        $message = "電影新增失敗。";
    }
}

// 處理修改資料
if ($action == 'edit' && $_SERVER['REQUEST_METHOD'] == 'POST' && $id > 0) {
    $title = $_POST['title'];
    $year = $_POST['year'];
    $director = $_POST['director'];
    $mtype = $_POST['mtype'];
    $mdate = $_POST['mdate'];
    $content = $_POST['content'];

    $sql = "UPDATE movie SET title = :title, year = :year, director = :director, mtype = :mtype, mdate = :mdate, content = :content WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':year', $year, PDO::PARAM_INT);
    $stmt->bindParam(':director', $director);
    $stmt->bindParam(':mtype', $mtype);
    $stmt->bindParam(':mdate', $mdate);
    $stmt->bindParam(':content', $content);

    if ($stmt->execute()) {
        $message = "電影資料更新成功！";
        $action = 'view'; // 返回查看
    } else {
        $message = "電影資料更新失敗。";
    }
}

// 處理刪除資料
if ($action == 'delete' && $id > 0) {
    $sql = "DELETE FROM movie WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        $message = "電影資料刪除成功！";
    } else {
        $message = "電影資料刪除失敗。";
    }
    $action = 'list'; // 返回列表
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>電影管理</title>
    <style>
        body { font-family: sans-serif; margin: 20px; }
        h1, h2 { text-align: center; }
        table { border-collapse: collapse; width: 80%; margin: 20px auto; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .pagination { text-align: center; margin-top: 10px; }
        .pagination a { display: inline-block; padding: 5px 10px; margin: 0 5px; border: 1px solid #ccc; text-decoration: none; }
        .pagination .current { background-color: #007bff; color: white; }
        .container { width: 60%; margin: 20px auto; padding: 20px; border: 1px solid #ccc; }
        label { display: block; margin-bottom: 5px; }
        input[type="text"], input[type="number"], input[type="date"], textarea { width: 100%; padding: 8px; margin-bottom: 10px; border: 1px solid #ddd; box-sizing: border-box; }
        button { padding: 10px 15px; background-color: #007bff; color: white; border: none; cursor: pointer; }
        .message { margin-top: 10px; font-weight: bold; }
        .success { color: green; }
        .error { color: red; }
        .back-link { display: block; margin-top: 10px; }
    </style>
</head>
<body>

    <h1>電影管理</h1>

    <?php if ($message): ?>
        <p class="message <?php echo (strpos($message, '成功') !== false) ? 'success' : 'error'; ?>"><?php echo $message; ?></p>
    <?php endif; ?>

    <?php if ($action == 'list'): ?>
        <h2>電影列表</h2>
        <p><a href="?action=add">新增電影</a></p>
        <?php
        $limit = 10;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $start = ($page - 1) * $limit;

        $sql = "SELECT COUNT(*) AS total FROM movie";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $total_rows = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        $total_pages = ceil($total_rows / $limit);

        $sql = "SELECT id, title, year, director FROM movie LIMIT :start, :limit";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':start', $start, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        $movies = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>電影名稱</th>
                    <th>發行年份</th>
                    <th>導演</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($movies) > 0): ?>
                    <?php foreach ($movies as $movie): ?>
                        <tr>
                            <td><?php echo $movie['id']; ?></td>
                            <td><?php echo htmlspecialchars($movie['title']); ?></td>
                            <td><?php echo $movie['year']; ?></td>
                            <td><?php echo htmlspecialchars($movie['director']); ?></td>
                            <td>
                                <a href="?action=view&id=<?php echo $movie['id']; ?>">查看</a> |
                                <a href="?action=edit&id=<?php echo $movie['id']; ?>">編輯</a> |
                                <a href="?action=delete&id=<?php echo $movie['id']; ?>" onclick="return confirm('確定要刪除這筆資料嗎？');">刪除</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5">沒有資料。</td></tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="pagination">
            <?php if ($total_pages > 1): ?>
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="?action=list&page=<?php echo $i; ?>" class="<?php echo ($i == $page) ? 'current' : ''; ?>"><?php echo $i; ?></a>
                <?php endfor; ?>
            <?php endif; ?>
        </div>

    <?php elseif ($action == 'view' && $id > 0): ?>
        <h2>電影詳細資料</h2>
        <?php
        $sql = "SELECT * FROM movie WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $movie = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($movie): ?>
            <p><strong>ID:</strong> <?php echo $movie['id']; ?></p>
            <p><strong>電影名稱:</strong> <?php echo htmlspecialchars($movie['title']); ?></p>
            <p><strong>發行年份:</strong> <?php echo $movie['year']; ?></p>
            <p><strong>導演:</strong> <?php echo htmlspecialchars($movie['director']); ?></p>
            <p><strong>類型:</strong> <?php echo htmlspecialchars($movie['mtype']); ?></p>
            <p><strong>首映日期:</strong> <?php echo $movie['mdate']; ?></p>
            <p><strong>內容簡介:</strong><br><?php echo nl2br(htmlspecialchars($movie['content'])); ?></p>
            <p><a href="?action=list" class="back-link">返回列表</a></p>
        <?php else: ?>
            <p>找不到該筆資料。</p>
            <p><a href="?action=list" class="back-link">返回列表</a></p>
        <?php endif; ?>

    <?php elseif ($action == 'add'): ?>
        <h2>新增電影</h2>
        <div class="container">
            <form method="post" action="?action=add">
                <label for="title">電影名稱:</label>
                <input type="text" name="title" id="title" required>

                <label for="year">發行年份:</label>
                <input type="number" name="year" id="year" required>

                <label for="director">導演:</label>
                <input type="text" name="director" id="director" required>

                <label for="mtype">類型:</label>
                <input type="text" name="mtype" id="mtype" required>

                <label for="mdate">首映日期:</label>
                <input type="date" name="mdate" id="mdate" required>

                <label for="content">內容簡介:</label>
                <textarea name="content" id="content" rows="5" required></textarea>

                <button type="submit">新增</button>
                <p><a href="?action=list" class="back-link">返回列表</a></p>
            </form>
        </div>

    <?php elseif ($action == 'edit' && $id > 0): ?>
        <h2>編輯電影</h2>
        <?php
        $sql = "SELECT * FROM movie WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $movie = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($movie): ?>
            <div class="container">
                <form method="post" action="?action=edit&id=<?php echo $id; ?>">
                    <label for="title">電影名稱:</label>
                    <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($movie['title']); ?>" required>

                    <label for="year">發行年份:</label>
                    <input type="number" name="year" id="year" value="<?php echo $movie['year']; ?>" required>

                    <label for="director">導演:</label>
                    <input type="text" name="director" id="director" value="<?php echo htmlspecialchars($movie['director']); ?>" required>

                    <label for="mtype">類型:</label>
                    <input type="text" name="mtype" id="mtype" value="<?php echo htmlspecialchars($movie['mtype']); ?>" required>

                    <label for="mdate">首映日期:</label>
                    <input type="date" name="mdate" id="mdate" value="<?php echo $movie['mdate']; ?>" required>

                    <label for="content">內容簡介:</label>
                    <textarea name="content" id="content" rows="5" required><?php echo htmlspecialchars($movie['content']); ?></textarea>

                    <button type="submit">儲存</button>
                    <p><a href="?action=view&id=<?php echo $id; ?>" class="back-link">返回查看</a> | <a href="?action=list" class="back-link">返回列表</a></p>
                </form>
            </div>
        <?php else: ?>
            <p>找不到該筆資料。</p>
            <p><a href="?action=list" class="back-link">返回列表</a></p>
        <?php endif; ?>

    <?php else: ?>
        <p>無效的操作。</p>
    <?php endif; ?>

    <?php $conn = null; ?>

</body>
</html>