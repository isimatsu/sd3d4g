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

    // pref_id → spot_img（存在すれば）
    $prefId = (int)$song['pref_id'];
    $spot = "/sd3d4g/assets/img/spot_img/{$prefId}.png";

    if ($prefId > 0 && file_exists($_SERVER['DOCUMENT_ROOT'] . $spot)) {
        return $spot;
    }

    // 汎用画像
    return "/sd3d4g/assets/img/music_img/汎用画像.jpg";
}

// ----------------------------------------------------
// YouTube 正規化 / 埋め込み判定関数（追加）
// ----------------------------------------------------

// YouTube の動画IDを抽出（11文字）。見つかなければ null を返す
function extractYoutubeId(string $url): ?string {
    // いくつかのパターンに対応
    $patterns = [
        '/youtube\.com\/.*[?&]v=([a-zA-Z0-9_-]{11})/i',   // https://www.youtube.com/watch?v=ID
        '/youtu\.be\/([a-zA-Z0-9_-]{11})/i',             // https://youtu.be/ID
        '/youtube\.com\/embed\/([a-zA-Z0-9_-]{11})/i',   // embed/ID
        '/youtube\.com\/v\/([a-zA-Z0-9_-]{11})/i',
    ];

    foreach ($patterns as $pat) {
        if (preg_match($pat, $url, $m)) {
            return $m[1];
        }
    }

    // まれにパラメータだけ渡される場合（v=... がエンコード等で含まれる）
    if (preg_match('/v=([a-zA-Z0-9_-]{11})/', $url, $m2)) {
        return $m2[1];
    }

    return null;
}

// 動画IDが有効（存在するか）を簡易チェックする
// サムネイルが存在するかで判定（軽量）
// true: 存在する本来の動画／false: 削除・非公開・無効
function youtubeVideoExists(string $videoId, int $timeout = 3): bool {
    $thumb = "https://img.youtube.com/vi/{$videoId}/mqdefault.jpg";
    // getimagesize は URL の取得を行う（allow_url_fopen が有効なら使える）
    // ここでは curl で HEAD を返す方法で確認する（より互換）
    $ch = curl_init($thumb);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return ($httpCode >= 200 && $httpCode < 400);
}

function normalizeYoutubeUrl(string $url): string {
    $id = extractYoutubeId($url);
    return $id ? "https://www.youtube.com/watch?v={$id}" : $url;
}

// ✅ レスポンシブ埋め込み生成（修正版）
function getYoutubeEmbedHtml(string $url): string {
    $id = extractYoutubeId($url);
    if (!$id || !youtubeVideoExists($id)) {
        return '';
    }

    $src = "https://www.youtube.com/embed/{$id}?rel=0&showinfo=0";

    return "
    <div class=\"youtube-container\">
        <iframe src=\"" . htmlspecialchars($src, ENT_QUOTES, 'UTF-8') . "\" 
            frameborder=\"0\" 
            allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture\" 
            allowfullscreen>
        </iframe>
    </div>";
}

// --- song_id を GET から取得 ---
if (!isset($_GET['song_id']) || !ctype_digit($_GET['song_id'])) {
    echo "曲IDが指定されていません。";
    exit;
}

$song_id = (int)$_GET['song_id'];

// --- 曲データ取得 ---
$sql = "SELECT * FROM `song2` WHERE `song_id` = ?";
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
// normalizeYoutubeUrl で再生可能な形式へ整形（ただし非YouTubeはそのまま）
$linkRaw     = $song['link'] ?? '';
$linkNormalized = $linkRaw ? normalizeYoutubeUrl($linkRaw) : '';
$good        = (int)($song['good'] ?? 0);

// --- 都道府県名取得 ---
$stmt = $pdo->prepare("SELECT pref_name FROM pref WHERE pref_id = ? LIMIT 1");
$stmt->execute([$pref_id]);
$pref = $stmt->fetch(PDO::FETCH_ASSOC);
$pref_name = $pref ? $pref['pref_name'] : '（不明）';

// --- 埋め込みHTMLの生成（YouTubeのみ） ---
$embedHtml = '';
if (!empty($linkNormalized)) {
    $embedHtml = getYoutubeEmbedHtml($linkNormalized, 560, 315);
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
            <img src="<?= htmlspecialchars($imagePath, ENT_QUOTES, 'UTF-8') ?>" alt="画像">
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
            <?php if (!empty($embedHtml)): ?>
            <?= $embedHtml ?>
            <p style="font-size:12px;color:#666;">
                <a href="<?= htmlspecialchars($linkNormalized, ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener noreferrer">
                    動画をYouTubeで開く
                </a>
            </p>
        <?php elseif (!empty($linkNormalized)): ?>
            <p>
                <a href="<?= htmlspecialchars($linkNormalized, ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener noreferrer" style="color:#007bff; text-decoration:underline;">
                    <?= htmlspecialchars($linkNormalized, ENT_QUOTES, 'UTF-8') ?>
                </a>
            </p>
        <?php else: ?>
            <p>リンクなし</p>
        <?php endif; ?>

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