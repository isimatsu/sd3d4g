<?php
session_start();  
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
    //DB接続情報
    $host = 'mysql326.phy.lolipop.lan';
	$dbname = 'LAA1682282-sd3d4g';
    $user = 'LAA1682282';
    $pass = 'Passsd3d';
try {
    //DB接続
        $pdo = new PDO(
            "mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass,
            [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]
        );

    if (!empty($_POST['trip_id'])) {
        $trip_id = (int)$_POST['trip_id'];

        //song テーブルの該当データの trip_id を NULL にする
        $stmt = $pdo->prepare("UPDATE song SET trip_id = NULL WHERE trip_id = ?");
        $stmt->execute([$trip_id]);

        //trip_info の関連データを削除
        $stmt = $pdo->prepare("DELETE FROM trip_info WHERE trip_id = ?");
        $stmt->execute([$trip_id]);

        //trip テーブルから該当レコードを削除
        $stmt = $pdo->prepare("DELETE FROM trip WHERE trip_id = ?");
        $stmt->execute([$trip_id]);

        //削除完了画面へリダイレクト
        header("Location: index.php");
        exit;
    } else {
        die("不正なアクセスです。");
    }

} catch (PDOException $e) {
    die("データベースエラー: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
}
?>