<?php
session_start();

// DB接続
$pdo = new PDO(
    'mysql:host=mysql326.phy.lolipop.lan;
    dbname=LAA1682282-sd3d4g;charset=utf8',
    'LAA1682282',
    'Passsd3d'
);

// ----------------------------------------------------
// 画像判定関数（一覧画面と同じもの）
// ----------------------------------------------------
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

// ----------------------------------------------------
// resolveImagePath（一覧画面と同じ）
// ----------------------------------------------------
function resolveImagePath($song) {


    // 2. 外部URL（存在チェック）
    if (!empty($song['image_path'])) {
        $url = trim($song['image_path']);
        if (is_valid_image_url($url)) return $url;
    }

    // 3. pref_id → spot_img
    $prefId = (int)$song['pref_id'];
    $spot = "/sd3d4g/assets/img/spot_img/{$prefId}.png";
    if ($prefId > 0 && file_exists($_SERVER['DOCUMENT_ROOT'] . $spot)) {
        return $spot;
    }

    // 4. 汎用画像
    return "/assets/img/music_img/汎用画像.jpg";
}

// --- song_id を GET から取得 ---
if (!isset($_GET['song_id']) || !ctype_digit($_GET['song_id'])) {
    echo "曲IDが指定されていません。";
    exit;
}

$song_id = (int)$_GET['song_id'];

// --- 曲データ取得 ---
$sql = "SELECT song_name, singer_name, pref_id, link, good, image_path 
        FROM song WHERE song_id = :id LIMIT 1";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $song_id, PDO::PARAM_INT);
$stmt->execute();
$song = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$song) {
    echo "指定された曲が見つかりません。";
    exit;
}

// --- 曲データを変数へセット ---
$imagePath   = resolveImagePath($song);
$songName    = htmlspecialchars($song['song_name'], ENT_QUOTES, 'UTF-8');
$singerName  = htmlspecialchars($song['singer_name'], ENT_QUOTES, 'UTF-8');
$pref_id     = (int)($song['pref_id'] ?? 0);
$link        = htmlspecialchars($song['link'] ?? '', ENT_QUOTES, 'UTF-8');
$good        = (int)($song['good'] ?? 0);

// --- 都道府県名取得 ---
$stmt = $pdo->prepare("SELECT pref_name FROM pref WHERE pref_id = ? LIMIT 1");
$stmt->execute([$pref_id]);
$pref = $stmt->fetch(PDO::FETCH_ASSOC);
$pref_name = $pref ? $pref['pref_name'] : '（不明）';

// --- 画像パスの調整 ---
if (!empty($imagePath)) {
    if (preg_match('/^https?:\/\//', $imagePath)) {
        // 外部URLならそのまま
        $imageSrc = $imagePath;
    } else {
        // ローカルファイル
        $imageSrc = '../' . ltrim($imagePath, '/');
    }
} else {
    $imageSrc = null;
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
            <div class="page-header">
                <h1>楽曲詳細</h1>
            </div>
        <div class="music-detail-box">
        <?php if ($imagePath): ?>
            <img src="<?= htmlspecialchars($imagePath, ENT_QUOTES, 'UTF-8') ?>" alt="画像">
        <?php else: ?>
            <p>画像が見つかりませんでした。</p>
        <?php endif; ?>
        
        <br>
        <div class="basic-form-box">
            <p class="input-name">曲名</span></p>
            <p><?= $songName ?></p>
        </div>
        <div class="basic-form-box">
            <p class="input-name">アーティスト名</span></p>
            <p><?= $singerName ?></p>
        </div>
        <div class="basic-form-box">
            <p class="input-name">ゆかりの地域</span></p>
            <p><?= $pref_name ?></p>
        </div>
        <div class="basic-form-box">
            <p class="input-name">楽曲リンク</span></p>
            <a href="<?= $link ?>" target="_blank" rel="noopener noreferrer" style="color:#007bff; text-decoration:underline;">
                <?= $link ?>
            </a>
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
</body>
</html>