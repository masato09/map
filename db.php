<?php
$servername = "localhost";
$username = "c2p31058";
$password = "K03s09t21";
$dbname = "staffschedule";

// データベース接続
$conn = new mysqli($servername, $username, $password, $dbname);

// 接続確認
if ($conn->connect_error) {
    die("接続失敗: " . $conn->connect_error);
}
?>
