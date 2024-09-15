<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// データベース接続
$dsn = "mysql:dbname=staffschedule;host=localhost";
$user = "c2p31058";
$pass = "K03s09t21";
$conn = new PDO($dsn, $user, $pass);

// $html 変数を初期化
$html = "";

// データ追加処理
if (isset($_POST["date"], $_POST["event"], $_POST["location"], $_POST["remarks"])) {
    $arr = array($_POST["date"], $_POST["event"], $_POST["location"], $_POST["remarks"]);
    $sql = "INSERT INTO `イベント概要` (日付, イベント, 場所, 備考) VALUES (?, ?, ?, ?)";
    $st = $conn->prepare($sql);
    if ($st->execute($arr) === false) {
        echo "<p>データの追加に失敗しました</p>";
    } else {
        echo "<p>データを追加しました</p>";
        echo "<script>window.location.href = 'staffschedule.php';</script>"; // 成功後にページをリロード
        exit();
    }
}

// データ削除処理
if (isset($_POST["delete_date"])) {
    $arr = array($_POST["delete_date"]);
    $sql = "DELETE FROM `イベント概要` WHERE 日付=?";
    $st = $conn->prepare($sql);
    $st->execute($arr);
    echo "<script>window.location.href = 'staffschedule.php';</script>"; // 削除後にページをリロード
    exit();
}

// データ表示と削除フォームの生成
$sql = "SELECT * FROM `イベント概要`";
$st = $conn->prepare($sql);
$st->execute();
$html = "<table border='1'><tr><th>日付</th><th>イベント</th><th>場所</th><th>備考</th><th>操作</th></tr>";
while ($row = $st->fetch(PDO::FETCH_ASSOC)) {
    $html .= "<tr>";
    foreach ($row as $key => $value) {
        $html .= "<td>{$value}</td>";
    }
    $html .= "<td>";
    $html .= "<form method='post' action='update.php'>";
    $html .= "<input type='hidden' name='date' value='{$row["日付"]}'>";
    $html .= "<input type='submit' value='更新' class='update-button'>";
    $html .= "</form>";
    $html .= "<form method='post' action=''>";
    $html .= "<input type='hidden' name='delete_date' value='{$row["日付"]}'>";
    $html .= "<input type='submit' value='削除' class='delete-button'>";
    $html .= "</form>";
    $html .= "</td>";
    $html .= "</tr>";
}
$html .= "</table>";
?>

<!doctype html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>開催予定イベント管理画面</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: Arial, Helvetica, sans-serif;
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .schedule-container {
            width: 100%;
            max-width: 1200px;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            position: relative; /* ポジションを相対に設定 */
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid black;
            padding: 16px;
            text-align: center;
            font-size: 18px;
        }

        th {
            background-color: #f2f2f2;
            font-size: 20px;
        }

        .delete-button {
            background-color: #e74c3c;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
        }

        .delete-button:hover {
            background-color: #c0392b;
        }

        .update-button {
            background-color: #3498db;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
        }

        .update-button:hover {
            background-color: #2980b9;
        }

        textarea {
            width: 100%;
            height: 100px;
            resize: none;
            font-size: 16px;
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        /* マップ画面へのリンクのスタイル */
        .map-link {
            position: absolute;
            top: 20px;
            right: 20px;
            background-color: #2ecc71;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
        }

        .map-link:hover {
            background-color: #27ae60;
        }

    </style>
</head>
<body>
    <div class="schedule-container">
        <h1>開催予定イベント管理画面</h1>
        <hr>
        
        <!-- データ追加フォーム -->
        <form method="post" action="">
            日付：<input name="date" type="date" required><br>
            イベント：<input name="event" required><br>
            場所：<input name="location" required><br>
            備考：<textarea name="remarks"></textarea><br>
            <br>
            <input type="submit" value="追加">
        </form>

        <!-- データ表示テーブル -->
        <?php echo $html; ?>

        <!-- マップ画面へ戻るリンク -->
        <a href="staffmap.html" class="map-link">マップ画面へ戻る</a>
    </div>
</body>
</html>
