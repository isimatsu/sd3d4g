<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$pdo=new PDO('mysql:host=mysql326.phy.lolipop.lan;
            dbname=LAA1682282-sd3d4g;charset=utf8',
                'LAA1682282',
                'Passsd3d');

$sql=$pdo->prepare('SELECT * FROM user WHERE ')
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
                <from action="#" class="basic-form">
                    <div class="basic-form-box">
                        <p class="input-name">お名前</p>
                        <input class="basic-form-input" type="text" placeholder="○○　○○">
                    </div><!--basic-form-box-->
                    <div class="basic-form-box">
                        <p class="input-name">メールアドレス</p>
                        <input class="basic-form-input" type="text" placeholder="aso@asojuku.ac.jp">
                    </div><!--basic-form-box-->
                     <div class="basic-form-box">
                        <p class="input-name">パスワード</p>
                        <input class="basic-form-input" type="text">
                    </div><!--basic-form-box-->
                </from><!--basic-form-->
                <a href="" class="basic-btn blue-btn">変更</a>
                <a href="" class="basic-btn gray-btn">ログアウト</a>

                

            </div>
        </sction>
    </main>
    <div class="menu-bar-area">
        <?php include '../assets/include/menu-bar.php'?>
    </div>
</body>

</html>
