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
    <title>ログイン -旅行提案アプリ-</title>
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
                <p>以下の内容で登録します。よろしいですか？</p>
            </div>
            <div class="page-contents">
                <from action="#" class="basic-form">
                    <div class="basic-form-box">
                        <p class="input-name">ここに入力名</p>
                        <input class="basic-form-input" type="text" placeholder="例：ここのプレースホルダです。">
                    </div><!--basic-form-box-->
                    <div class="basic-form-box">
                        <p class="input-name">ここに入力名</p>
                        <input class="basic-form-input" type="text" placeholder="例：ここのプレースホルダです。">
                    </div><!--basic-form-box-->
                </from><!--basic-form-->

                <a href="" class="basic-btn blue-btn">登録完了</a>
                <p></p>
                <a href="" class="basic-btn gray-btn">戻る</a>
                
            </div>
        </sction>
    </main>
    <div class="menu-bar-area">
        <?php include '../assets/include/menu-bar.php'?>
    </div>
</body>
</html>