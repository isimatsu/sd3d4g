session_start();
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
            <div class="page-header">
                <h1>楽曲詳細</h1>
            </div>
            <?php 
            // DB接続
            $pdo=new PDO(
	            'mysql:host=mysql326.phy.lolipop.lan;
                    dbname=LAA1682282-sd3d4g;charset=utf8',
                        'LAA1682282',
                        'Passsd3d');

            // 2. データベースから画像パスを取得
            $sql = "SELECT song_name, singer_name, pref_id, link, good, image_path  FROM song WHERE song_id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':id', 176, PDO::PARAM_INT); // 例: id=1の画像を取得
            $stmt->execute();
            $song = $stmt->fetch(PDO::FETCH_ASSOC);

            // 3. パスを変数に格納
            $imagePath = $song['image_path'];
            $songName    = htmlspecialchars($song['song_name'] ?? '（不明）', ENT_QUOTES, 'UTF-8');
            $singerName  = htmlspecialchars($song['singer_name'] ?? '（不明）', ENT_QUOTES, 'UTF-8');
            $prefName    = htmlspecialchars($song['pref_id'] ?? '（不明）', ENT_QUOTES, 'UTF-8');
            $link        = htmlspecialchars($song['link'] ?? '', ENT_QUOTES, 'UTF-8');
            $good        = (int)($song['good'] ?? 0);
            ?>
        <?php if (!empty($imagePath)): ?>
            <img src="<?= htmlspecialchars($imagePath, ENT_QUOTES, 'UTF-8') ?>" alt="画像">
        <?php else: ?>
            <p>画像が見つかりませんでした。</p>
        <?php endif; ?>

        // ------------------------------
        // pref_idから都道府県名を取得
        // ------------------------------
        $stmt = $pdo->prepare('SELECT pref_name FROM pref WHERE pref_id = ? LIMIT 1');
        $stmt->execute([$pref_id]);
        $pref = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($pref) {
            $pref_name = $pref['pref_name'];
        } else {
            $pref_name = '（不明）';
        }

            <div><p>曲名</p><br><?= $songName ?></div><br>
            <div><p>アーティスト名</p><br><?= $singerName ?></div><br>
            <div><p>ゆかりの地域</p><br><?= $prefName ?></div><br>
            <div><p>楽曲リンク<br><?= $prefName ?></div><br>
            <div><p>いいね</p><br><?= $good ?></div>
        </sction>
    </main>
    <div class="menu-bar-area">
        <?php include '../assets/include/menu-bar.php'?>
    </div>
</body>
</html>