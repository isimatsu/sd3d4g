<?php 
if(isset($_POST['out'])){
    session_start();
    session_destroy();
}
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
    <title>pagename -旅行提案アプリ-</title>
</head>
<body>
    <main>
        <sction class="sm">
            <div class="header">
            </div>
            <div class="page-header">
                <h1>ログイン</h1>
                <p>ログインします。アカウント情報を入力してください。<br>
                    またアカウントをお持ちでない方は<a href="../signup/index.php">新規登録</a></p>
            </div>
            <div class="page-contents">

            <form action="../index.php" class="basic-form" method="POST">
                    <div class="basic-form-box">
                        <p class="input-name">メールアドレス</p>
                        <input class="basic-form-input" name="email" type="text" placeholder="aso@asojuku.ac.jp">
                    </div><!--basic-form-box-->
                    <div class="basic-form-box">
                        <p class="input-name">パスワード</p>
                        <input class="basic-form-input" name="password" type="password" >
                    </div><!--basic-form-box-->
                    <button class="basic-btn blue-btn">ログイン</button>
            </form><!--basic-form-->

                <div class="error-message">
                    <p><strong>*メールアドレスかパスワードが違います</strong></p>
                </div>



                
                

                
            </div>
        </sction>
    </main>
    
</body>

</html>