<?php
session_start();

// DB接続
$host = 'mysql326.phy.lolipop.lan';
$dbname = 'LAA1682282-sd3d4g';
$user = 'LAA1682282';
$pass = 'Passsd3d';
try{
    //DB接続
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass,
        [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
    //trip_idがNULL or 空でないデータを昇順で取得
    $sql = "SELECT * FROM `song2` WHERE `song_id` = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$_GET['song_id']]);
    $musics = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach($musics as $music){
        $link = $music['link'];
        $image_path = $music['image_path'];
        $music_name = $music['song_name'];
        $singer_name = $music['singer_name'];
        $area_id = $music['area_id'];
        $good = $music['good'];
    };
}catch(PDOException $e){
    die("データベースエラー: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
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
    <title>楽曲詳細 -旅行提案アプリ-</title>
</head>
<body>
    <main>
        <section class="sm">
            <div class="header">
                <?php include '../assets/include/header.php'?>
            </div>
            <button id="back-btn" type="button">戻る</button>
            <div class="page-header">
                <h1>楽曲詳細</h1>
            </div>
        <div class="music-detail-box">
            <img src="<?= $image_path ?>" alt="画像">
        <br>
        <div class="basic-form-box">
            <p class="input-name">曲名</span></p>
            <p><?= $music_name ?></p>
        </div>
        <div class="basic-form-box">
            <p class="input-name">アーティスト名</span></p>
            <p><?= $singer_name ?></p>
        </div>
        <div class="basic-form-box">
            <p class="input-name">ゆかりの地域</span></p>
            <p><?= $area_id ?></p>
        </div>
        <div class="basic-form-box">
            <p class="input-name">楽曲リンク</span></p>
            <p style="font-size:12px;color:#666;">
                <a href="<?= $link ?>" target="_blank" rel="noopener noreferrer">
                    動画をYouTubeで開く
                </a>
            </p>
        </div>
        <div class="basic-form-box">
            <p class="input-name">いいね</span></p>
            <p><?= $good ?></p>
        <br>
        <br>
        <br>
        </div>
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