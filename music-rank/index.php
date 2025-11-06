<?php
    session_start();
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    //DB接続情報
    $host = 'mysql326.phy.lolipop.lan';
	$dbname = 'LAA1682282-sd3d4g';
    $user = 'LAA1682282';
    $pass = 'Passsd3d';

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

        //URLからpref_id取得
        $pref_id = isset($_GET['pref_id']) ? intval($_GET['pref_id']) : null;

        // 都道府県リスト取得（prefテーブルがある前提）
        $pref_sql = "SELECT pref_id, pref_name FROM pref ORDER BY pref_id ASC";
        $pref_stmt = $pdo->query($pref_sql);
        $prefs = $pref_stmt->fetchAll(PDO::FETCH_ASSOC);
        //全国ランキング取得
        $national_sql = "SELECT * FROM song ORDER BY good DESC LIMIT 3";
        $national_stmt = $pdo->query($national_sql);
        $song_national = $stmt_national->fetchAll(PDO::FETCH_ASSOC);

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

            <!-- ▼ 県選択ドロップダウン（タイトル位置に配置） -->
            <h1>
                <label>県を選択：</label>
                <select name="pref_id" onchange="location.href='?pref_id=' + this.value">
                    <option value="">選択してください</option>
                <?php foreach($prefs as $pref): ?>
                    <option value="<?= $pref['pref_id'] ?>"
                        <?= ($pref_id === (int)$pref['pref_id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($pref['pref_name']) ?>
                    </option>
                <?php endforeach; ?>
                </select>
            </h1>

            <?php if ($pref_id !== null): ?>
            
            <?php
            // 曲データ取得
            $sql = "SELECT * FROM song WHERE pref_id = :pref_id ORDER BY good DESC LIMIT 3";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':pref_id', $pref_id, PDO::PARAM_INT);
            $stmt->execute();
            $song_pref = $stmt->fetchAll();

            // 順位カラー
            $rank_colors = [
                1 => "#E6B422",
                2 => "#b5b5b4ff",
                3 => "#b87333"
            ];
            ?>

            <?php if (empty($song_pref)): ?>
                <p>この県には登録された曲がありません。</p>
            <?php else: ?>
                <?php $rank = 1; foreach ($song_pref as $song): ?>
                    <div class="music-card">
                        <div class="music-info">
                            <p style="font-weight: bold; color: <?= $rank_colors[$rank] ?>;">#<?= $rank ?></p>
                                <img class="music-img" src="<?= htmlspecialchars($song['image_path']) ?>">
                            <p><?= htmlspecialchars($song['song_name']) ?></p>
                        </div>
                        <div class="music-action-btn">
                            <a href="<?= htmlspecialchars($song['link']) ?>">
                                <span class="music-play material-symbols-rounded">play_circle</span>
                            </a>
                               <span class="music-favorite material-symbols-rounded">favorite</span>
                        </div>
                    </div><!--music-card-->
                <?php $rank++; endforeach; ?>
            <?php endif; ?>
            <?php endif; ?>
            <!--
            <div class="page-header">
                <h1>○○県のランキング</h1>
                 <div class="music-card">
                    <div class="music-info">
                        <p style="font-weight: bold; color: #E6B422 ;">#1</p>
                        <img class="music-img" src="../assets/img/music_tmp_img.jpg">
                        <p>曲名がはいる</p>
                    </div>
                    <div class="music-action-btn">
                        <span class="music-play material-symbols-rounded">play_circle</span>
                        <span class="music-favorite material-symbols-rounded">favorite</span>
                    </div>
                </div>
                -->
                <!--music-card-->
                <!--
                <div class="music-card">
                    <div class="music-info">
                        <p style="font-weight: bold; color: #b5b5b4ff ;">#2</p>
                        <img class="music-img" src="../assets/img/music_tmp_img.jpg">
                        <p>曲名がはいる</p>
                    </div>
                    <div class="music-action-btn">
                        <span class="music-play material-symbols-rounded">play_circle</span>
                        <span class="music-favorite material-symbols-rounded">favorite</span>
                    </div>
                </div>
                -->
                <!--music-card-->
                <!--
                <div class="music-card">
                    <div class="music-info">
                        <p style="font-weight: bold; color: #b87333 ;">#3</p>
                        <img class="music-img" src="../assets/img/music_tmp_img.jpg">
                        <p>曲名がはいる</p>
                    </div>
                    <div class="music-action-btn">
                        <span class="music-play material-symbols-rounded">play_circle</span>
                        <span class="music-favorite material-symbols-rounded">favorite</span>
                    </div>
                </div>
                -->
                <!--music-card-->
                 <a class="all" href="../music-pref/index.php">すべて表示</a>
                
                 <h1>全国のランキング</h1>
                    <?php $rank = 1; foreach ($national_songs as $song): ?>
                        <div class="music-card">
                            <div class="music-info">
                                <p style="font-weight: bold; color: <?= $rank_colors[$rank] ?>;">#<?= $rank ?></p>
                                    <img class="music-img" src="<?= htmlspecialchars($song['image_path']) ?>">
                                <p><?= htmlspecialchars($song['song_name']) ?></p>
                            </div>
                            <div class="music-action-btn">
                                <a href="<?= htmlspecialchars($song['link']) ?>">
                                    <span class="music-play material-symbols-rounded">play_circle</span>
                                </a>
                                    <span class="music-favorite material-symbols-rounded">favorite</span>
                            </div>
                        </div><!--music-card-->
                    <?php $rank++; endforeach; ?>

                 <!--
                 <div class="music-card">
                    <div class="music-info">
                        <p style="font-weight: bold; color: #E6B422 ;">#1</p>
                        <img class="music-img" src="../assets/img/music_tmp_img.jpg">
                        <p>曲名がはいる</p>
                    </div>
                    <div class="music-action-btn">
                        <span class="music-play material-symbols-rounded">play_circle</span>
                        <span class="music-favorite material-symbols-rounded">favorite</span>
                    </div>
                </div>
                <div class="music-card">
                    <div class="music-info">
                        <p style="font-weight: bold; color: #b5b5b4ff ;">#2</p>
                        <img class="music-img" src="../assets/img/music_tmp_img.jpg">
                        <p>曲名がはいる</p>
                    </div>
                    <div class="music-action-btn">
                        <span class="music-play material-symbols-rounded">play_circle</span>
                        <span class="music-favorite material-symbols-rounded">favorite</span>
                    </div>
                </div>
                <div class="music-card">
                    <div class="music-info">
                        <p style="font-weight: bold; color: #b87333 ;">#3</p>
                        <img class="music-img" src="../assets/img/music_tmp_img.jpg">
                        <p>曲名がはいる</p>
                    </div>
                    <div class="music-action-btn">
                        <span class="music-play material-symbols-rounded">play_circle</span>
                        <span class="music-favorite material-symbols-rounded">favorite</span>
                    </div>
                </div>
                -->
                <a class="all" href="../music-japan/index.php">すべて表示</a>

                 <h1>履歴</h1>
                 <div class="music-card">
                    <div class="music-info">
                        <p style="font-weight: bold; color: #E6B422 ;">#1</p>
                        <img class="music-img" src="../assets/img/music_tmp_img.jpg">
                        <p>曲名がはいる</p>
                    </div>
                    <div class="music-action-btn">
                        <span class="music-play material-symbols-rounded">play_circle</span>
                        <span class="music-favorite material-symbols-rounded">favorite</span>
                    </div>
                </div><!--music-card-->
                <div class="music-card">
                    <div class="music-info">
                        <p style="font-weight: bold; color: #b5b5b4ff ;">#2</p>
                        <img class="music-img" src="../assets/img/music_tmp_img.jpg">
                        <p>曲名がはいる</p>
                    </div>
                    <div class="music-action-btn">
                        <span class="music-play material-symbols-rounded">play_circle</span>
                        <span class="music-favorite material-symbols-rounded">favorite</span>
                    </div>
                </div><!--music-card-->
                <div class="music-card">
                    <div class="music-info">
                        <p style="font-weight: bold; color: #b87333 ;">#3</p>
                        <img class="music-img" src="../assets/img/music_tmp_img.jpg">
                        <p>曲名がはいる</p>
                    </div>
                    <div class="music-action-btn">
                        <span class="music-play material-symbols-rounded">play_circle</span>
                        <span class="music-favorite material-symbols-rounded">favorite</span>
                    </div>
                </div><!--music-card-->
                 <a class="all" href="../music-history/index.php">すべて表示</a>

            </div>
            <div class="page-contents">

               

                


            </div>
        </sction>
    </main>
    <div class="menu-bar-area">
        <?php include '../assets/include/menu-bar.php'?>
    </div>
</body>

</html>