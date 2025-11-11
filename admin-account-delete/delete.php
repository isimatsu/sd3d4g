<?php
session_start();

// POSTで user_id を受け取る
$user_id = $_POST['user_id'] ?? null;

// IDがない場合は処理を中断
if (empty($user_id)) {
    exit('ユーザーIDが指定されていません。');
}

try {
    // DB接続
    $pdo = new PDO(
        'mysql:host=mysql326.phy.lolipop.lan;dbname=LAA1682282-sd3d4g;charset=utf8',
        'LAA1682282',
        'Passsd3d',
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    // 削除処理
    $sql = "DELETE FROM user WHERE user_id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();

} catch (PDOException $e) {
    exit('削除処理に失敗しました：' . $e->getMessage());
}

// 削除後、管理画面トップへ遷移
header('Location: ../admin/index.php');
exit;