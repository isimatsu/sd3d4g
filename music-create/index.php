<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$pdo=new PDO('mysql:host=mysql326.phy.lolipop.lan;
            dbname=LAA1682282-sd3d4g;charset=utf8',
                'LAA1682282',
                'Passsd3d');
    
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($email !== '' && $password !== '') {
        $sql = $pdo->prepare('SELECT * FROM user WHERE email = ? AND password = ?');
        $sql->execute([$email, $password]);

        if ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['user_name'] = $row['user_name'];
            header('Location: index.php');
            exit;
        } else {
            echo 'メールアドレスまたはパスワードが違います。';
            echo '<a href="./signin/index.php">戻る</a>';
            exit;
        }
        } else {
            echo 'メールアドレスとパスワードを入力してください。';
            echo '<a href="./signin/index.php">戻る</a>';
            exit;
        }
        } else {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ../signin/index.php');
            exit;
        }
    }
?>
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
        <section class="sm">
            <div class="header">
                <?php include '../assets/include/header.php'?>
            </div>
            <button id="back-btn" type="button">戻る</button>
            <div class="page-header">
                <h1>楽曲登録</h1>
                <p style="text-align:center;">楽曲を登録します。必要な情報を入力してください。</p>
            </div>
            <div class="page-contents">
                <form action="check.php" method="POST" class="basic-form" enctype="multipart/form-data">
                    <div class="basic-form-box">
                        <p class="input-name">曲名</p>
                        <input class="basic-form-input" name="song_name" type="text">
                    </div><!--basic-form-box-->

                    <div class="basic-form-box">
                        <p class="input-name">アーティスト名</p>
                        <input class="basic-form-input" name="singer_name" type="text">
                    </div><!--basic-form-box-->

                    <div class="basic-form-box">
                        <p class="input-name">ゆかりの地域</p>
                        <select class="question-pref select-style" name="area">
                            <option value="" default>選択してください</option>
                            <option value="北日本">北日本(北海道・東北地方)</option>
                            <option value="東日本">東日本(関東地方・中部地方)</option>
                            <option value="西日本">西日本(近畿地方・中国地方・四国地方)</option>
                            <option value="南日本">南日本(九州地方・沖縄県)</option>
                        </select>
                    </div><!--basic-form-box-->

                    <div class="basic-form-box">
                        <p class="input-name">楽曲リンク</p>
                        <input class="basic-form-input" name="link" type="url" placeholder="例：Youtube">
                    </div>
                    <!-- 画像アップロード-->
                        <p class="input-name">画像ファイル</p>
                        <input name="image" type="file">
                    
                     <button type="submit" class="basic-btn blue-btn">新規登録</button>
                </from><!--basic-form-->

                
            </div>
        </sction>
    </main>
    <div class="menu-bar-area">
        <?php include '../assets/include/menu-bar.php'?>
    </div>
    <script>
        const backBtn = document.getElementById('back-btn');

        backBtn.addEventListener('click', function() {
        window.history.back(); // ブラウザの「戻る」と同じ動作
        // window.history.go(-1); // これでも同じ動作になります
        });
    </script>
</body>
</html>