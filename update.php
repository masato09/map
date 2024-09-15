<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// データベース接続
$dsn = "mysql:dbname=staffschedule;host=localhost";
$user = "c2p31058";
$pass = "K03s09t21";
$conn = new PDO($dsn, $user, $pass);

// データ取得処理
if (isset($_POST["date"])) {
    $date = $_POST["date"];
    $sql = "SELECT * FROM `イベント概要` WHERE 日付=?";
    $st = $conn->prepare($sql);
    $st->execute([$date]);
    $event = $st->fetch(PDO::FETCH_ASSOC);
}

// データ更新処理
if (isset($_POST["update_date"], $_POST["update_event"], $_POST["update_location"], $_POST["update_remarks"])) {
    $arr = array($_POST["update_event"], $_POST["update_location"], $_POST["update_remarks"], $_POST["update_date"]);
    $sql = "UPDATE `イベント概要` SET イベント=?, 場所=?, 備考=? WHERE 日付=?";
    $st = $conn->prepare($sql);
    if ($st->execute($arr) === false) {
        echo "<p>データの更新に失敗しました</p>";
    } else {
        echo "<p>データを更新しました</p>";
        echo "<script>window.location.href = 'staffschedule.php';</script>"; // 成功後にページをリロード
        exit();
    }
}
?>

<!doctype html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>更新ページ</title>
    <style>
        textarea {
            width: 100%;
            height: 100px;
            resize: none;
            font-size: 16px;
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }
    </style>
</head>
<body>
<h1>更新ページ</h1>
<hr>

<!-- データ更新フォーム -->
<form method="post" action="">
    日付：<input name="update_date" type="date" value="<?php echo htmlspecialchars($event['日付']); ?>" readonly><br>
    イベント：<input name="update_event" value="<?php echo htmlspecialchars($event['イベント']); ?>" required><br>
    場所：<input name="update_location" value="<?php echo htmlspecialchars($event['場所']); ?>" required><br>
    備考：<textarea name="update_remarks"><?php echo htmlspecialchars($event['備考']); ?></textarea><br>
    <br>
    <input type="submit" value="更新">
</form>

</body>
</html>
