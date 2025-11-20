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
            header('Location: signin/index.php');
            exit;
        }
    }

//DB接続情報
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
    $sql = "SELECT * FROM trip 
            WHERE user_id = ? 
            AND trip_id IS NOT NULL 
            AND trip_id <> '' 
            ORDER BY trip_id ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$_SESSION['user_id']]);
    $trips = $stmt->fetchAll(PDO::FETCH_ASSOC);
}catch(PDOException $e){
    die("データベースエラー: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
}

    // songテーブルからデータを保存順に取得
    $sql2 = "SELECT s.song_id, s.song_name, s.singer_name, s.image_path, s.pref_id
             FROM song s
             ORDER BY s.song_id DESC
             LIMIT 10";
    $stmt2 = $pdo->prepare($sql2);
    $stmt2->execute();
    $songs = $stmt2->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="ja">
<head>

    <!-- Android Chrome -->
    <meta name="theme-color" content="#9fd3fa">

    <!-- iOS Safari -->
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-capable" content="yes">

    <!-- Windows (Edgeなど) -->
    <meta name="msapplication-navbutton-color" content="#9fd3fa">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="assets/css/reset.css">
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="assets/css/index.css">
     <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100..900&display=swap" rel="stylesheet">
    <title>ホーム -旅行提案アプリ-</title>
</head>
<style>
    .header{
        width: calc(100% - 30px);
        height: 45px;
        position: fixed;
        top: 15px;
        max-width: 470px;
    }

    .page-contents{
        width: 100%;
        margin-top: 60px;
    }
</style>
<body>
    <main>
        <sction class="sm">
            <div class="header">
                <?php include 'assets/include/header.php'?>
            </div>
            <div class="page-contents">
                <div class="hero-plan-list">
                    <?php
                        $print_count = 0;
                        
                        foreach($trips as $row){
                            $trip_id = $row['trip_id'];
                            $trip_start = $row['trip_start'];
                            $trip_end = $row['trip_end'];
                            $trip_name = $row['trip_name'];
                            $pref_id = $row['pref_id'];

                            $print_count = $print_count + 1;
                            // ヒアドキュメントで出力
                            
                            if($print_count <= 3){
                                if($print_count == 2){
                                echo <<<EOT
                                <a href="plan/?plan_id={$trip_id}" class="plan-card main-card" style="background-image: url(assets/img/spot_img/{$pref_id}.png);">
                                    <div class="plan-card-detail">
                                        <div>
                                            <p>{$trip_start} ~ {$trip_end}</p>
                                            <h2>{$trip_name}</h2>
                                        </div>
                                    </div>
                                </a><!--plan-card-->
                                EOT;
                                }else{
                                echo <<<EOT
                                <a href="plan/?plan_id={$trip_id}" class="plan-card side-card" style="background-image: url(assets/img/spot_img/{$pref_id}.png);">
                                    <div class="plan-card-detail">
                                        <div>
                                            <p>{$trip_start} ~ {$trip_end}</p>
                                            <h2>{$trip_name}</h2>
                                        </div>
                                    </div>
                                </a>
                                EOT;
                                }
                            }
                            
                        }
                        if($print_count == 0){
                            echo '<h2>おすすめの旅行地！</h2>';
                            //echo '結果なし';
                            for($i=0;$i<3;$i++){
                            $number=mt_rand(1,47);
                            $sql3="SELECT * FROM pref WHERE pref_id=?";
                            $stmt3=$pdo->prepare($sql3);
                            $stmt3->execute([$number]);
                            foreach($stmt3 as $row){
                                $pref_name=$row['pref_name'];
                            }
                            echo <<<EOT
                            <a href="createplan/?popularity={$pref_name}" class="plan-card main-card" style="background-image: url(assets/img/spot_img/{$number}.png);">
                                    <div class="plan-card-detail">
                                        <div>
                                            <h2>{$pref_name}</h2>
                                        </div>
                                    </div>
                                </a>
                            EOT;
                        }
                    }

                    ?>

                </div>

                <!--音楽スライド機能-->
                <?php
                // helper: URLが有効で画像であるかを確認
                function is_valid_image_url(string $url, int $timeout = 3): bool {
                    if (!filter_var($url, FILTER_VALIDATE_URL)) return false;

                    $ch = curl_init($url);
                    curl_setopt($ch, CURLOPT_NOBODY, true);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
                    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                    curl_exec($ch);

                    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
                    curl_close($ch);

                    if ($httpCode < 200 || $httpCode >= 400) return false;
                    return (stripos($contentType, 'image/') === 0);
                }

                // 画像パス処理を 1 つの関数にまとめた
                function resolveImagePath($song) {
                    // 1. 外部URL
                    if (!empty($song['image_path'])) {
                        $url = trim($song['image_path']);
                        if (is_valid_image_url($url)) {
                            return $url;
                        }
                    }

                    // 2. ローカル music_img
                    if (!empty($song['image_path'])) {
                        $rel = "/sd3d4g/assets/img/music_img/".ltrim($song['image_path'],'/');
                        if (file_exists($_SERVER['DOCUMENT_ROOT'] . $rel)) {
                            return $rel;
                        }
                    }

                    // 3. pref_id から spot_img
                    $prefId = (int)($song['pref_id']);
                    $spot = "/sd3d4g/assets/img/spot_img/".$prefId.".png";
                    if ($prefId > 0 && file_exists($_SERVER['DOCUMENT_ROOT'] . $spot)) {
                        return $spot;
                    }

                    // 4. 汎用
                    return "/assets/img/music_img/汎用画像.jpg";
                }
                ?>

                <div class="hero-music-list-wrapper">
                    <div class="hero-music-list">

                        <!-- 1セット目 -->
                        <?php foreach ($songs as $song): ?>
                            <?php $imgPath = resolveImagePath($song); ?>
                            <a href="#" class="hero-music-card" 
                            style="background-image: url('<?= $imgPath ?>');">
                                <div class="music-card-detail">
                                    <h2><?= htmlspecialchars($song['song_name'], ENT_QUOTES, 'UTF-8') ?></h2>
                                    <p><?= htmlspecialchars($song['singer_name'], ENT_QUOTES, 'UTF-8') ?></p>
                                </div>
                            </a>
                        <?php endforeach; ?>

                        <!-- ★ コピーセット（完全に同じ画像を出力） -->
                        <?php foreach ($songs as $song): ?>
                            <?php $imgPath = resolveImagePath($song); ?>
                            <a href="#" class="hero-music-card" 
                            style="background-image: url('<?= $imgPath ?>');">
                                <div class="music-card-detail">
                                    <h2><?= htmlspecialchars($song['song_name'], ENT_QUOTES, 'UTF-8') ?></h2>
                                    <p><?= htmlspecialchars($song['singer_name'], ENT_QUOTES, 'UTF-8') ?></p>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="new-plan-create-box">
                    <a class="new-plan-create" href="createplan/">
                        <span class="material-symbols-rounded">add_circle</span>
                        旅程を作成
                    </a>
                    <form action="createplan/" method="GET">
                        <div class="popularity-spot">
                            <p style="font-size: 12px; color: #666666; padding: 10px 0;">人気の旅行先からはじめる</p>
                            <button type="submit" name="popularity" value="京都">
                                <div class="pref-select-btn" onclick="setDestination('京都府')">
                                    <div class="pref-icon" style="background-color: #F6F4F2;">
                                        <span class="material-symbols-rounded" style="color: #B49994;">landscape_2</span>
                                    </div>
                                    <div class="pref-detail">
                                        <h5>京都</h5>
                                        <p style="font-size: 12px; color: #333;">千年の歴史が息づく、雅の都</p>
                                    </div>
                                </div><!--pref-select-btn-->
                            </button>
                            <button type="submit" name="popularity" value="東京">
                                <div class="pref-select-btn" onclick="setDestination('東京都')">
                                    <div class="pref-icon" style="background-color: #F2F6F2;">
                                        <span class="material-symbols-rounded" style="color: #94A5B4;">apartment</span>
                                    </div>
                                    <div class="pref-detail">
                                        <h5>東京</h5>
                                        <p style="font-size: 12px; color: #333;">世界が集う最先端と伝統の都市</p>
                                    </div>
                                </div><!--pref-select-btn-->
                            </button>
                            <button type="submit" name="popularity" value="北海道">
                                <div class="pref-select-btn" onclick="setDestination('北海道')">
                                    <div class="pref-icon" style="background-color: #F2F6F4;">
                                        <span class="material-symbols-rounded" style="color: #94B4AB;">nature</span>
                                    </div>
                                    <div class="pref-detail">
                                        <h5>北海道</h5>
                                        <p style="font-size: 12px; color: #333;">大自然と食の宝庫、四季の楽園</p>
                                    </div>
                                </div><!--pref-select-btn-->
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </sction>
    </main>
    <div class="menu-bar-area">
        <?php include 'assets/include/menu-bar.php'?>
    </div>
</body>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const planList = document.querySelector('.hero-plan-list');
        const mainCard = document.querySelector('.main-card');
        
        if (planList && mainCard) {
            const scrollPosition = mainCard.offsetLeft - (planList.offsetWidth / 2) + (mainCard.offsetWidth / 2);
            planList.scrollLeft = scrollPosition;
        }
    });
</script>
</html>