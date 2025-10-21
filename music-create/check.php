<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>登録処理</title>
</head>
<body>
<?php
try {
    // DB接続
    $pdo=new PDO(
	'mysql:host=mysql326.phy.lolipop.lan;
            dbname=LAA1682282-sd3d4g;charset=utf8',
                'LAA1682282',
                'Passsd3d');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // POSTデータ受け取り
    $song_name   = $_POST['song_name'] ?? '';
    $singer_name = $_POST['singer_name'] ?? '';
    $pref_name   = $_POST['pref'] ?? '';  // ← ★ pref_idではなくpref_nameが送られる
    $link        = $_POST['link'] ?? '';
    $image_path  = ''; // ← 初期化（null禁止）

    // ------------------------------
    // 画像アップロード処理
    // ------------------------------

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $tmp_name = $_FILES['image']['tmp_name'];
        $filename = basename($_FILES['image']['name']);
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($extension, $allowed)) {
            exit('対応していないファイル形式です。<a href="index.php">戻る</a>');
        }

         // アップロード先（music-createの1つ上にあるuploadsフォルダ）
        $upload_dir = __DIR__ . '/../uploads/';

        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        // ファイル名をユニーク化
        $new_filename = uniqid('song_', true) . '.' . $extension;
        $upload_file  = $upload_dir . $new_filename;

        // ファイル移動
        if (move_uploaded_file($tmp_name, $upload_file)) {
            // DBには相対パスで保存（Web公開用）
            $image_path = $upload_file;
        } else {
            exit('❌ 画像のアップロードに失敗しました。');
        }
    } else {
        exit('❌ 画像が選択されていません。');
    }

    // ------------------------------
    // 都道府県名からpref_idを取得
    // ------------------------------
    $stmt = $pdo->prepare('SELECT pref_id FROM pref WHERE pref_name = ? LIMIT 1');
    $stmt->execute([$pref_name]);
    $pref = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($pref) {
    $pref_id = $pref['pref_id'];
    } else {
    $pref_id = null; // 不明な都道府県名の場合
    }

    // ------------------------------
    // DB登録処理
    // ------------------------------
    $sql = "INSERT INTO song (song_name, singer_name, pref_id, link, image_path, good)
            VALUES (:song_name, :singer_name, :pref_id, :link, :image_path, 0)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':song_name', $song_name, PDO::PARAM_STR);
    $stmt->bindValue(':singer_name', $singer_name, PDO::PARAM_STR);
    $stmt->bindValue(':pref_id', $pref_id, PDO::PARAM_INT);
    $stmt->bindValue(':link', $link, PDO::PARAM_STR);
    $stmt->bindValue(':image_path', $image_path, PDO::PARAM_STR);
    $stmt->execute();

    echo "<p>✅ 楽曲を登録しました！</p>";
    echo '<a href="../music-rank/index.php">戻る</a>';

} catch (PDOException $e) {
    echo 'データベースエラー: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES);
     echo '<p>エラー内容: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '</p>';
}
?>
</body>
</html>
