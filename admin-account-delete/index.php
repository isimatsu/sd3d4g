<?php
session_start();

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../assets/css/reset.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
     <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100..900&display=swap" rel="stylesheet">
    <title>登録確認 -旅行提案アプリ-</title>
</head>
<body>
    <?php
$user_id = $_POST['user_id'];
$trip_count = $_POST['trip_count'];

try {
    $pdo = new PDO(
        'mysql:host=mysql326.phy.lolipop.lan;dbname=LAA1682282-sd3d4g;charset=utf8',
        'LAA1682282',
        'Passsd3d'
    );
} catch (PDOException $e) {
    exit('データベース接続に失敗しました：' . $e->getMessage());
}

// user_id からユーザー情報を取得
$sql = "SELECT user_id, user_name, email FROM user WHERE user_id = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>
    <main>
        <sction class="sm">
            <div class="header">
                <div>

                </div>
                <a href="#" class="header-account">
                    <span class="material-symbols-rounded">account_circle</span>
                    <p>アカウント名</p>
                </a>
            </div>
            <div class="page-header">
                <h1>削除確認</h1>
                <p style="text-align:center;">以下のアカウントを削除します。よろしいですか？</p>
            </div>
            <div class="page-contents">
                <form action="#"  style="text-align:center;">
                    <?php if ($user): ?>
                        <div class="basic-form-box">
                            <p style="display: inline-block;width: 200px;color: #666;">お名前：<?= htmlspecialchars($user['user_name'], ENT_QUOTES, 'UTF-8') ?></p>
                        </div>
                        <div class="basic-form-box">
                            <p style="display: inline-block;color: #666;">メールアドレス：<?= htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8') ?></p>
                        </div>
                        <div class="basic-form-box">
                            <p style="display: inline-block;width: 200px;color: #666;">リクエスト数：<?= $trip_count ?></p>
                        </div>
                    <?php else: ?>
                     <p>該当するユーザーが見つかりませんでした。</p>
                    <?php endif; ?>
                </form><!--basic-form-->

                <form action="delete.php" method="post">
                    <input type="hidden" name="user_id" value="<?=$user_id?>">
                <button class="basic-btn blue-btn">削除</a>
                </form>
                <p></p>
                <form action="../admin/index.php" method="post">
                <button class="basic-btn gray-btn">戻る</a>
                </form>
        
            </div>
        </sction>
    </main>

    <div class="menu-bar-area">
        <?php include '../assets/include/menu-bar.php'?>
    </div>
</body>
</html>