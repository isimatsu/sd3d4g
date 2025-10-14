<?php
$user_name=$_POST['user_name'];
$email=$_POST['email'];
$password=$_POST['password'];

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
                <h1>登録確認</h1>
                <p style="text-align:center;">以下の内容で登録します。よろしいですか？</p>
            </div>
            <div class="page-contents">
                <from action="#"  style="text-align:center;">
                    <div class="basic-form-box">
                        <p style="display: inline-block;width: 200px;color: #666;">お名前：<?= $user_name ?></p>
                    </div><!--basic-form-box-->

                    <div class="basic-form-box">
                        <p style="display: inline-block;width: 200px;color: #666;">メールアドレス：<?= $email ?></p>
                    </div><!--basic-form-box-->

                </from><!--basic-form-->

                <form action="../signup-complete/index.php" method="post">
                    <input type="hidden" name="user_name" value="<?=$user_name?>">
                    <input type="hidden" name="email" value="<?=$email?>">
                    <input type="hidden" name="password" value="<?=$password?>">
                <a href="../signup-complete/index.php" class="basic-btn blue-btn">登録完了</a>
                </form>
                <p></p>
                <a href="../signup/index.php" class="basic-btn gray-btn">戻る</a>
                
            </div>
        </sction>
    </main>
    <div class="menu-bar-area">
        <?php include '../assets/include/menu-bar.php'?>
    </div>
</body>
</html>