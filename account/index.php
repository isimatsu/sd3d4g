<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if (!isset($_SESSION['user_id'])) {
            header('Location: ../signin/index.php');
            exit;
        }
    

    if(isset($_SESSION['user_id'])){
        $user_id = $_SESSION['user_id'];
        $user_name = $_SESSION['user_name'];
    }
$pdo=new PDO('mysql:host=mysql326.phy.lolipop.lan;
            dbname=LAA1682282-sd3d4g;charset=utf8',
                'LAA1682282',
                'Passsd3d');

    if (isset($_POST['update_name'])) {
    $user_name = $_POST['update_name'];    // 更新後のユーザー名
    $email = $_POST['update_email'];       // 更新後のメールアドレス
    $password = $_POST['update_password']; // 更新後のパスワード

    $sql = $pdo->prepare('UPDATE user SET user_name = ?, email = ?, password = ? WHERE user_id = ?');
    $sql->execute([$user_name, $email, $password, $user_id]);

    $_SESSION['user_name'] = $user_name;
}

/*               
if (isset($_POST['account_name'])) {
    echo '更新内容を受け取りました';

    $account_email = $_POST['account_name']; // 既存のメールアドレス
    $user_name = $_POST['update_name'];
    $email = $_POST['update_email'];
    $password = $_POST['update_password'];

    $sql = $pdo->prepare('UPDATE user SET user_name = ?, email = ?, password = ? WHERE email = ?');
    $sql->execute([$user_name, $email, $password, $account_email]);
}
*/



$sql=$pdo->prepare('SELECT * FROM user WHERE user_id=?');
$sql->execute([$user_id]);
foreach($sql as $row){
    $user_name=$row['user_name'];
    $email=$row['email'];
    $password=$row['password'];
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../assets/css/reset.css">

     <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100..900&display=swap" rel="stylesheet">
    <title>pagename -旅行提案アプリ-</title>

    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
</head>
<style>
    .page-header{
        text-align: center;
        color: blue;
        font-size: 10px;
    }
</style>
<body>
    <main>
        <sction class="sm">
            <div class="header">
                <?php include '../assets/include/header.php'?>
            </div>
            <div class="page-header">
                <span class="material-symbols-rounded" style="font-size: 60px;">account_circle</span>
                <h1>アカウント管理</h1>
            </div>
            <div class="page-contents">
                <form action="#" class="basic-form" method="POST">
                    <div class="basic-form-box">
                        <p class="input-name">お名前</p>
                        <input class="basic-form-input" type="text" placeholder="<?= $user_name ?>" name="update_name" required>
                    </div><!--basic-form-box-->
                    <div class="basic-form-box">
                        <p class="input-name">メールアドレス</p>
                        <input class="basic-form-input" type="email" placeholder="<?= $email ?>" name="update_email" required>
                    </div><!--basic-form-box-->
                     <div class="basic-form-box">
                        <p class="input-name">パスワード</p>
                        <input class="basic-form-input" type="password" name="update_password" required>
                    </div><!--basic-form-box-->
                     <button type="submit" class="basic-btn blue-btn">変更</button>
                </form><!--basic-form-->
                <form action="../signin/index.php" method="post">
                    <input type="hidden" name="out" value="1">
                    <button class="basic-btn gray-btn">ログアウト</button>
                </form>
                <!--<a href="../signin/index.php" class="basic-btn gray-btn">ログアウト</a>-->

                

            </div>
        </sction>
    </main>
    <div class="menu-bar-area">
        <?php include '../assets/include/menu-bar.php'?>
    </div>
</body>

</html>
