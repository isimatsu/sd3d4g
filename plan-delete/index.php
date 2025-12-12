<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../assets/css/reset.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/trip.css">
     <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100..900&display=swap" rel="stylesheet">
    <title>pagename -旅行提案アプリ-</title>
</head>
<body>
    <main>
        <sction class="sm">
            <div class="header">
                <?php include '../assets/include/header.php'?>
            </div>
             <div class="page-header">
                <h1>旅程一覧</h1>
                <p>過去に生成した旅程の一覧です</p>
            </div>
            <div class="empty-state">
                <span class="material-symbols-rounded" style="font-size: 60px;">delete</span>
                <p>旅程一覧を削除しました</p>
                <!-- aタグのcssが優先されてボタンのcssを適用できなかったので直接書いてます -->
                    <a href="../plan-list/index.php" 
                    style="display: block;margin: 10px 0;width: 100%;height: 55px;border-radius: 20px;
                            color: #fff;text-decoration: none;display: flex;justify-content: center;
                            align-items: center;background-color: #0084FF;"
                    >旅程一覧に戻る</a>
            </div>
        </sction>
    </main>
    <div class="menu-bar-area">
        <?php include '../assets/include/menu-bar.php'?>
    </div>
</body>

</html>