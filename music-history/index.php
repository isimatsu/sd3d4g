<?php
session_start();  
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if(isset($_SESSION['user_id'])){
        $user_id = $_SESSION['user_id'];
        $user_name = $_SESSION['user_name'];
    }

    $host = 'mysql326.phy.lolipop.lan';
	$dbname = 'LAA1682282-sd3d4g';
    $user = 'LAA1682282';
    $pass = 'Passsd3d';
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $sql = "
        SELECT 
        s.song_id,
        s.song_name,
        s.singer_name,
        s.link,
        s.good,
        s.area_id,
        s.song_time,
        s.image_path
        FROM trip t
        JOIN trip_song_connect tc ON t.trip_id = tc.trip_id
        JOIN song2 s ON tc.song_id = s.song_id
        WHERE t.user_id = ?
        AND (t.feedback = 1 OR t.feedback IS NULL)
        ORDER BY s.song_id;";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id]);
    $history_songs = $stmt->fetchAll(PDO::FETCH_ASSOC);



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
                <?php include '../assets/include/header.php'?>
            </div>
            <div class="page-header">
                <a href="../music-rank/index.php">戻る</a>
                <h1>履歴</h1>
            </div>
            <div class="page-contents">
                <?php $rank = 1; foreach ($history_songs as $song): ?>
                <div class="music-card">
                    <div class="music-info">
                                <p style="font-weight: bold; color: <?= $rank_colors[$rank] ?? '#000000' ?>;">#<?= $rank ?></p>
                                    <img class="music-img" src="<?= htmlspecialchars($song['image_path']) ?>">
                                <p><?= htmlspecialchars($song['song_name']) ?></p>
                            </div>
                            <div class="music-action-btn">
                                <a href="<?= htmlspecialchars($song['link']) ?>" target="_blank" rel="noopener">
                                    <span class="music-play material-symbols-rounded">play_circle</span>
                                </a>
                                    <!-- goodボタンの機能は未実装です-->
                                    <span class="music-favorite material-symbols-rounded">favorite</span>
                            </div>
                </div><!--music-card-->
                <?php $rank++; endforeach; ?>
                
                

            </div>
        </sction>
    </main>
    <div class="menu-bar-area">
        <?php include '../assets/include/menu-bar.php'?>
    </div>
</body>

</html>