<?php
    session_start();
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    //DB接続情報
    $host = 'mysql326.phy.lolipop.lan';
	$dbname = 'LAA1682282-sd3d4g';
    $user = 'LAA1682282';
    $password = 'Passsd3d';

    try {
        $pdo = new PDO(
            "mysql:host=$host;dbname=$dbname;charset=utf8",
            $user,
            $password,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
        }catch(PDOException $e){
            die("データベース接続エラー： " . htmlspecialchars($e->getMessage(),ENT_QUOTES,'UTF-8'));
        }

        //URLからarea_id取得
        $area_id = isset($_GET['area_id']) ? intval($_GET['area_id']) : null;
        if ($area_id === null) exit("エリアが指定されていません");

        //対象エリア名取得
        $areas = [
            1 => "北海道・東北",
            2 => "関東・東海",
            3 => "近畿・中国・四国",
            4 => "九州・沖縄"
        ];

        $area_name = $areas[$area_id] ?? "不明なエリア";

        //曲データ取得
        $sql = "SELECT s.*, 
                (SELECT COUNT(*) FROM good WHERE song_id = s.song_id) AS good_count,
                EXISTS(SELECT 1 FROM good WHERE song_id = s.song_id 
                AND user_id = :userid) AS is_good
                FROM song2 s WHERE s.area_id = :area_id 
                ORDER BY good_count DESC LIMIT 50";
        $stmt = $pdo->prepare($sql);
        $stmt -> bindValue(':userid', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt -> bindValue(":area_id", $area_id, PDO::PARAM_INT);
        $stmt -> execute();
        $area_songs = $stmt->fetchAll();
    
        $rank_colors = [
                1 => "#E6B422",
                2 => "#b5b5b4ff",
                3 => "#b87333"
            ];
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
        <section class="sm">
            <div class="header">
                <?php include '../assets/include/header.php'?>
            </div>

            <div class="page-header">
                <a href="../music-rank/index.php?area_id=<?= $area_id ?>">戻る</a>
                <h1><?= htmlspecialchars($area_name) ?>のランキング</h1>
            </div>
            <?php if (empty($area_songs)): ?>
                <p>表示できる曲がありません。</p>
            <?php else: ?>

            <div class="page-contents">
                <?php $rank = 1; foreach ($area_songs as $song): ?>
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
                            <div class="good-area">
                                <button class="favorite-btn" data-song-id="<?= $song['song_id'] ?>">
                                    <span id="song_favoritebtn_<?= $song['song_id'] ?>"
                                        class="music-favorite material-symbols-rounded <?= $song['is_good'] ? "music-favorite-after" : "" ?>"
                                        data-song-id="<?= $song['song_id'] ?>">
                                            favorite
                                    </span>
                                </button>
                                <span class="good-count" id="good-count-<?= $song['song_id'] ?>">
                                    <?= $song['good_count'] ?>
                                </span>
                            </div>

                        <!--試しで上のに変更中
                            <div class="good-area">
                                <button onclick="plusGood(<?= $song['song_id'] ?>,<?= $song['is_good'] ?>)">
                                    <span id="song_favoritebtn_<?= $song['song_id'] ?>" class="music-favorite material-symbols-rounded <?= $song['is_good'] ? "music-favorite-after" : "" ?>"
                                        data-song-id="<?= $song['song_id'] ?>">
                                            favorite
                                    </span>
                                </button>
                                <span class="good-count" id="good-count-<?= $song['song_id'] ?>">
                                    <?= $song['good_count'] ?>
                                </span>
                            </div>
                        -->
                        </div>
                    </div><!--music-card-->
                <?php $rank++; endforeach; ?>
            <?php endif; ?>
            </div>

        </section>
    </main>
    <div class="menu-bar-area">
        <?php include '../assets/include/menu-bar.php'?>
    </div>

    <!-- goods.phpにリクエストを送信するjs -->
    <script>
        document.querySelectorAll(".music-favorite").forEach(btn => {
            btn.addEventListener("click", function() {
                let songId = this.dataset.songId;
                const icon = document.getElementById(`song_favoritebtn_${songId}`);
                const goodPrint = document.getElementById(`good-count-${songId}`);
                console.log('goods実行')
                fetch("../good/goods.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: "song_id=" + songId
                })
                .then(res => res.json())
                .then(data => {
                    const goodPrint = document.getElementById(`good-count-${songId}`);

                    // DBの最新値を画面に反映
                    if (data.good_count !== undefined) {
                        goodPrint.innerText = data.good_count;
                    }
                    if(data.status === "gooded") {
                        this.classList.add("gooded");
                    } else {
                        this.classList.remove("gooded");
                    }
                });
            });
        });
/*
        function plusGood(song_id) {
            const favorite_btn_print = document.getElementById(`song_favoritebtn_${song_id}`);
            const good_print = document.getElementById(`good-count-${song_id}`);
            
            let current = parseInt(good_print.innerText);
            const isAlreadyGood = favorite_btn_print.classList.contains('music-favorite-after');

            if (isAlreadyGood) {
                const afterGood = current - 1;
                good_print.innerText = afterGood;
                favorite_btn_print.classList.remove('music-favorite-after', 'after-favorite-btn');
                favorite_btn_print.dataset.clicked = "false";
            } else {
                const afterGood = current + 1;
                good_print.innerText = afterGood;
                favorite_btn_print.classList.add('music-favorite-after', 'after-favorite-btn');
                favorite_btn_print.dataset.clicked = "true";
            }
        }
*/
    </script>
</body>

</html>