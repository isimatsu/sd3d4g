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
    <title>pagename -旅行提案アプリ-</title>
</head>
<body>
    <main>
        <sction class="sm">
            <div class="header">
                <?php include '../assets/include/header.php'?>
            </div>
            <div class="page-header">
                <h1>ログイン</h1>
                <p>ログインします。アカウント情報を入力してください。<br>
                    またアカウントをお持ちでない方は<a href="../signup/index.php">新規登録</a></p>
            </div>
            <div class="page-contents">

            <from action="#" class="basic-form">
                    <div class="basic-form-box">
                        <p class="input-address">メールアドレス</p>
                        <input class="basic-form-input" type="text" placeholder="aso@asojuku.ac.jp">
                    </div><!--basic-form-box-->
                    <div class="basic-form-box">
                        <p class="input-password">パスワード</p>
                        <input class="basic-form-input" type="text" >
                    </div><!--basic-form-box-->
                </from><!--basic-form-->

                <div class="error-message">
                    <p><strong>*:メールアドレスかパスワードが違います・</strong></p>
                </div>


                <a href="" class="basic-btn blue-btn">ログイン</a>
                
                

                
            </div>
        </sction>
    </main>
    <div class="menu-bar-area">
        <?php include '../assets/include/menu-bar.php'?>
    </div>
</body>

</html>