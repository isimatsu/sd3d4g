<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$user_name=$_POST['user_name'];
$email=$_POST['email'];
$password=$_POST['password'];

$pdo=new PDO('mysql:host=mysql326.phy.lolipop.lan;
            dbname=LAA1682282-sd3d4g;charset=utf8',
                'LAA1682282',
                'Passsd3d');

$sql=$pdo->prepare('INSERT INTO user(user_name,email,password)value(?,?,?)');
$sql->execute([$user_name,$email,$password]);
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
    <title>アカウント作成完了 -旅行提案アプリ-</title>
</head>
<body>
    <main>
        <sction class="sm">
            <div class="header">
                <?php include '../assets/include/header.php'?>
            </div>
            <div class="page-contents">
                <div class="page-center-content">
                    <div class="welcome">
                        <img src="../assets/img/ariflight.png">
                        <h1>ようこそ</h1>
                        <p>アカウント登録が完了しました。これからあなたにぴったりの旅をご提案します。</p>
                        <a href="../signin/index.php" class="basic-btn blue-btn">はじめる</a>
                    </div>
                </div>
            </div>
        </sction>
    </main>
</body>
</html>