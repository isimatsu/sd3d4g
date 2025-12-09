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
    if ($user_id) {
            $sql_history = "
            SELECT DISTINCT
                s.song_id,
                s.song_name,
                s.singer_name,
                s.link,
                s.good AS good_count,  /* ←ここを変更しました */
                s.area_id,
                s.song_time,
                s.image_path,
                /* (SELECT COUNT(*) ... ) の行は削除しました */
                EXISTS(SELECT 1 FROM good WHERE song_id = s.song_id 
                    AND user_id = :userid) AS is_good
            FROM trip t
            JOIN trip_song_connect tc ON t.trip_id = tc.trip_id
            JOIN song2 s ON tc.song_id = s.song_id
            WHERE t.user_id = :userid
            AND (t.feedback = 1 OR t.feedback IS NULL)
            ORDER BY s.song_id";

        // 画像判定関数
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

        // resolveImagePath（一覧画面と同じ）
        function resolveImagePath($song) {

        if (!empty($song['image_path'])) {
            $rawPath = trim($song['image_path']);

            // 外部URL？
            if (preg_match('/^https?:\/\//', $rawPath)) {
                if (is_valid_image_url($rawPath)) return $rawPath;
            }

            // "../" を削除（DB保管時の相対パス対策）
            $clean = preg_replace('/^\.+\//', '', $rawPath);

            // Webパスに統一
            $local = "/sd3d4g/" . $clean;

            // 実在チェック
            if (file_exists($_SERVER['DOCUMENT_ROOT'] . $local)) {
                return $local;
            }
        }
        // 汎用画像
        return "/sd3d4g/assets/img/music_img/汎用画像.jpg";
    }

            $stmt_history = $pdo->prepare($sql_history);
            $stmt_history->bindValue(':userid', $user_id, PDO::PARAM_INT);
            $stmt_history->execute();
            $history_songs = $stmt_history->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $history_songs = [];
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
                <?php include '../assets/include/header.php'?>
            </div>
            <a href="../music-rank/index.php">戻る</a>
            <div class="page-header">
                <h1>履歴</h1>
            </div>
            <div class="page-contents">
                <?php $rank = 1; foreach ($history_songs as $song): ?>
                    <?php $imagePath = resolveImagePath($song); ?>
                <div class="music-card">
                    <a href="../music-detail/?song_id=<?= $song['song_id'] ?>" class="music-info">
                                <p style="font-weight: bold; color: <?= $rank_colors[$rank] ?? '#000000' ?>;">#<?= $rank ?></p>
                                    <img class="music-img" src="<?= htmlspecialchars($imagePath, ENT_QUOTES, 'UTF-8') ?>">
                                <p><?= htmlspecialchars($song['song_name']) ?></p>
                    </a>
                    <div class="music-action-btn">
                        <a href="<?= htmlspecialchars($song['link']) ?>" target="_blank" rel="noopener">
                            <span class="music-play material-symbols-rounded">play_circle</span>
                        </a>
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
                    </div>
                </div><!--music-card-->
                <?php $rank++; endforeach; ?>
                
                

            </div>
        </sction>
    </main>
    <div class="menu-bar-area">
        <?php include '../assets/include/menu-bar.php'?>
    </div>

        <!-- goods.phpにリクエストを送信するjs -->
    <script>
        // document.querySelectorAll(".music-favorite")... のブロックは削除し、以下の関数のみにします

        function plusGood(song_id) {
            // ID重複に対応するため、getElementByIdではなくquerySelectorAllですべて取得する
            // [id="..."] という書き方をすることで、重複IDもすべて取得できます
            const allBtns = document.querySelectorAll(`[id="song_favoritebtn_${song_id}"]`);
            const allCounts = document.querySelectorAll(`[id="good-count-${song_id}"]`);

            if (allBtns.length === 0) return;

            // 現在の状態を判定（どれか1つのボタンの状態を見ればOK）
            const firstBtn = allBtns[0];
            const good_print = allCounts[0];
            
            let current = parseInt(good_print.innerText);
            const isAlreadyGood = firstBtn.classList.contains('music-favorite-after');

            let newCount;
            let addClass;

            // --- 1. 計算と状態決定 ---
            if (isAlreadyGood) {
                // いいね解除
                newCount = current - 1;
                addClass = false;
            } else {
                // いいね登録
                newCount = current + 1;
                addClass = true;
            }

            // --- 2. ページ内の該当するすべてのボタンと数字を一括更新 ---
            // これにより、ランキングと履歴で同じ曲があっても両方同時に変わります
            allBtns.forEach(btn => {
                if (addClass) {
                    btn.classList.add('music-favorite-after', 'after-favorite-btn');
                    btn.dataset.clicked = "true";
                } else {
                    btn.classList.remove('music-favorite-after', 'after-favorite-btn');
                    btn.dataset.clicked = "false";
                }
            });

            allCounts.forEach(span => {
                span.innerText = newCount;
            });

            // --- 3. サーバーへ送信 ---
            console.log('goods実行: ' + song_id);
            fetch("../good/goods.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: "song_id=" + song_id
            })
            .then(res => res.json())
            .then(data => {
                // サーバーから返ってきた正確な値で再度更新（念のため）
                if (data.good_count !== undefined) {
                    allCounts.forEach(span => {
                        span.innerText = data.good_count;
                    });
                }
                
                // ステータスの同期
                allBtns.forEach(btn => {
                    if(data.status === "gooded") {
                        btn.classList.add("gooded", "music-favorite-after", "after-favorite-btn");
                    } else {
                        btn.classList.remove("gooded", "music-favorite-after", "after-favorite-btn");
                    }
                });
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
</body>

</html>