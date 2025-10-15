<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../assets/css/reset.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100..900&display=swap" rel="stylesheet">
    <title>楽曲登録 -旅行提案アプリ-</title>
</head>
<body>
    <main>
        <sction class="sm">
            <div class="header">
                <?php include '../assets/include/header.php'?>
            </div>
            <div class="page-header">
                <h1>楽曲登録</h1>
                <p style="text-align:center;">楽曲を登録します。必要な情報を入力してください。</p>
            </div>
            <div class="page-contents">
                <from action="check.php" class="basic-form">
                    <div class="basic-form-box">
                        <p class="input-name">曲名</p>
                        <input class="basic-form-input" name="user_name" type="text">
                    </div><!--basic-form-box-->

                    <div class="basic-form-box">
                        <p class="input-name">アーティスト名</p>
                        <input class="basic-form-input" name="email" type="text">
                    </div><!--basic-form-box-->

                    <div class="basic-form-box">
                        <p class="input-name">ゆかりの地域</p>
                        <input class="basic-form-input" name="password" type="text" placeholder="例：東京都渋谷区">
                    </div><!--basic-form-box-->

                     <div class="basic-form-box">
                        <p class="input-name">楽曲リンク</p>
                        <input class="basic-form-input" name="password" type="text" placeholder="例：Youtube">
                    </div><!--basic-form-box-->
                </from><!--basic-form-->

                <a href="" class="basic-btn blue-btn">新規登録</a>
                
            </div>
        </sction>
    </main>
    <div class="menu-bar-area">
        <?php include '../assets/include/menu-bar.php'?>
    </div>
</body>
</html>