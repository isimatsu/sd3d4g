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
    <title>新規作成 -旅行提案アプリ-</title>
</head>
<body>
    <main>
        <sction class="sm">
            <div class="header">
            </div>
            <div class="page-header">
                <h1>新規作成</h1>
                <p>アカウントを登録します。登録する情報を入力して下さい既にアカウントをお持ちの方は<a href="../signin/">ログイン</a></p>
            </div>
            <div class="page-contents">
                <form action="../signup-check/index.php" class="basic-form" method="post">
                    <div class="basic-form-box">
                        <p class="input-name">お名前</p>
                        <input class="basic-form-input" name="user_name" type="text" placeholder="例：田中太郎">
                    </div><!--basic-form-box-->
                    <div class="basic-form-box">
                        <p class="input-name">メールアドレス</p>
                        <input class="basic-form-input" name="email" type="text" placeholder="例：abc@abc.com">
                    </div><!--basic-form-box-->
                    <div class="basic-form-box">
                        <p class="input-name">パスワード</p>
                        <input class="basic-form-input" name="password" type="text" placeholder="例：8文字以上">
                    </div><!--basic-form-box-->
                     <button class="basic-btn blue-btn">新規登録</a>
                </form><!--basic-form-->

                
            </div>
        </sction>
    </main>
    <div class="menu-bar-area">
        <?php include '../assets/include/menu-bar.php'?>
    </div>
</body>
</html>