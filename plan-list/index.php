<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
    //DB接続情報
    $host = 'mysql326.phy.lolipop.lan';
	$dbname = 'LAA1682282-sd3d4g';
    $user = 'LAA1682282';
    $pass = 'Passsd3d';

    try{
        //DB接続
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        //trip_idがNULL or 空でないデータを昇順で取得
        $sql = "SELECT * FROM trip WHERE trip_id IS NOT NULL AND trip_id <> '' ORDER BY trip_id ASC";
        $stmt = $pdo->query($sql);
        $trips = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }catch(PDOException $e){
        die("データベースエラー: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
    }
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../assets/css/reset.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/trip.css">
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
                <h1>旅程一覧</h1>
                <p>過去に生成した旅程の一覧です</p>
            </div>
            <div class="page-contents">
                </a><!--plan-card-->
                <!-- $tripsが空かどうか -->
                <?php if(count($trips) > 0): ?>
                    <?php foreach($trips as $trip): ?>
                    <!-- リンクが決まり次第加筆してください -->
                    <a href="(画面へのリンク)?trip_id=<?= htmlspecialchars($trip['trip_id']) ?>"
                        class="plan-card main-card" 
                        style="background-image: url(../assets/img/spot_img/1.jpg);">

                        <div class="plan-card-detail">
                            <h2><?= htmlspecialchars($trip['trip_name']) ?></h2>
                            <?php if (!empty($trip['trip_start']) && !empty($trip['trip_end'])): ?>
                                <p>
                                    <?= date('Y/m/d', strtotime($trip['trip_start'])) ?> ～ 
                                    <?= date('Y/m/d', strtotime($trip['trip_end'])) ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </a>
                    <?php endforeach; ?>
                <?php else: ?><!-- 旅程が空だった場合は以下を表示 -->
                    <p>旅程がありません。</p>
                <?php endif; ?>

                        <!-- 元々のコードは一応残しておきます
                        <div class="plan-card-detail">
                            <div>
                                <h2>北海道旅行</h2>
                                <p>2025/10/10 ~ 2025/10/12</p>
                            </div>
                        </div>
                        <div class="delete-btn"><span class="material-symbols-rounded">delete</span></div>
                    </a>--plan-card--
                    <a href="" class="plan-card side-card" style="background-image: url(../assets/img/spot_img/40.jpg);">
                        <div class="plan-card-detail">
                            <div>
                                <h2>福岡旅行</h2>
                                <p>2025/10/10 ~ 2025/10/12</p>
                            </div>
                        </div>
                        <div class="delete-btn"><span class="material-symbols-rounded">delete</span></div>
                    </a>--plan-card--
                    -->

                    <div class="empty-state"><span class="material-symbols-rounded">event_busy</span></div>
                    <p>旅程一覧がありません</p>

            </div>
        </sction>
    </main>
    <div class="menu-bar-area">
        <?php include '../assets/include/menu-bar.php'?>
    </div>
</body>

</html>