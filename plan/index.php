<?php
    session_start();    
    if(isset($_SESSION['user_id'])){
        $user_id = $_SESSION['user_id'];
        $user_name = $_SESSION['user_name'];
    }

    //plan_idでDBから引っ張る
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
    <title>旅程 -旅行提案アプリ-</title>
</head>

<body>
    <main>
        <sction class="sm">
            <div class="header">
                <?php include '../assets/include/header.php'?>
            </div>
            <div class="plan-hero">
                
            </div>
            <div class="page-contents">
                <div class="trip-title">
                    <h1>北海道旅行</h1>
                    <h5>～</h5>
                </div>
            </div>
        </sction>
    </main>
    <div class="menu-bar-area">
        <?php include '../assets/include/menu-bar.php'?>
    </div>
</body>

</html>