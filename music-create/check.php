<?php
    session_start();

    // ログインしていなければリダイレクト
    if (!isset($_SESSION['user_id'])) {
        header('Location: ../signin/index.php');
        exit;
    }

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    
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
    $area   = $_POST['area'] ?? '';  // ← ★ pref_idではなくpref_nameが送られる
    $link        = $_POST['link'] ?? '';
    $user_id     = $_SESSION['user_id'];
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

         // アップロード先
        $upload_dir = __DIR__ . '/../assets/img/music_img/';

        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        // ファイル名をユニーク化
        $new_filename = uniqid('song_', true) . '.' . $extension;
        $upload_file  = $upload_dir . $new_filename;

        // ファイル移動
        if (move_uploaded_file($tmp_name, $upload_file)) {
            // DBには相対パスで保存（Web公開用）
            $image_path = '../assets/img/music_img/' .$new_filename;
        } else {
            exit('❌ 画像のアップロードに失敗しました。');
        }
        } else {
            exit('❌ 画像が選択されていません。');
        }

    $area_map = [
    '北日本' => 1,
    '東日本' => 2,
    '西日本' => 3,
    '南日本' => 4,
    ];

    if (isset($area_map[$area])) {
        $area_id = $area_map[$area];
    } else {
        // 未選択などエラー処理
        exit('❌ ゆかりの地域を選択してください。<a href="index.php">戻る</a>');
    }

    // ------------------------------
    // 🔍 重複チェック
    // ------------------------------
    $check_sql = "SELECT song_id, song_name, singer_name FROM song2 
                  WHERE song_name = :song_name AND singer_name = :singer_name";
    $check_stmt = $pdo->prepare($check_sql);
    $check_stmt->bindValue(':song_name', $song_name, PDO::PARAM_STR);
    $check_stmt->bindValue(':singer_name', $singer_name, PDO::PARAM_STR);
    $check_stmt->execute();

    $existing = $check_stmt->fetch(PDO::FETCH_ASSOC);

    if ($existing) {
        // 既に登録されている → 詳細ページへ誘導
        $song_id = $existing['song_id'];

        echo '
        <html>
        <head>
            <meta charset="UTF-8">
            <style>
                body {
                    margin: 0;
                    font-family: "Helvetica", "Arial", sans-serif;
                    background: linear-gradient(to bottom, #fff4e6, #d9ecff);
                    height: 100vh;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                }
                .card {
                    background: white;
                    padding: 40px 55px;
                    border-radius: 16px;
                    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
                    text-align: center;
                    max-width: 380px;
                }
                h2 {
                    color: #d9534f;
                    margin-bottom: 25px;
                    font-size: 22px;
                    line-height: 1.5;
                }
                .label {
                    font-weight: bold;
                    margin-top: 10px;
                    font-size: 17px;
                }
                .value {
                    font-size: 18px;
                    margin-bottom: 15px;
                }
                a {
                    display: block;
                    margin-top: 20px;
                    color: #3b6cff;
                    text-decoration: none;
                    font-size: 15px;
                }
                a:hover {
                    text-decoration: underline;
                }
            </style>
        </head>
        <body>
            <div class="card">
                <h2>この楽曲は既に登録されています。</h2>

                <div class="label">曲名：</div>
                <div class="value">' . htmlspecialchars($existing["song_name"]) . '</div>

                <div class="label">アーティスト：</div>
                <div class="value">' . htmlspecialchars($existing["singer_name"]) . '</div>

                <a href="../music-detail/index.php?song_id=' . $song_id . '">登録済み楽曲の詳細を見る</a>
                <a href="../music-rank/index.php">一覧へ戻る</a>
            </div>
        </body>
        </html>
        ';
        exit; // これ以上実行しない
    }

    // ------------------------------
    // DB登録処理
    // ------------------------------
    $sql = "INSERT INTO song2 (song_name, singer_name, good, link, area_id, image_path)
            VALUES (:song_name, :singer_name, 0 , :link, :area_id, :image_path)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':song_name', $song_name, PDO::PARAM_STR);
    $stmt->bindValue(':singer_name', $singer_name, PDO::PARAM_STR);
    $stmt->bindValue(':link', $link, PDO::PARAM_STR);
    $stmt->bindValue(':area_id', $area_id, PDO::PARAM_INT);
    $stmt->bindValue(':image_path', $image_path, PDO::PARAM_STR);
    $stmt->execute();

    echo '
    <html>
    <head>
        <meta charset="UTF-8">
        <style>
            body {
                margin: 0;
                font-family: "Helvetica", "Arial", sans-serif;
                background: linear-gradient(to bottom, #fff4e6, #d9ecff);
                height: 100vh;
                display: flex;
                justify-content: center;
                align-items: center;
            }
            .card {
                background: white;
                padding: 40px 60px;
                border-radius: 16px;
                box-shadow: 0 4px 20px rgba(0,0,0,0.1);
                text-align: center;
            }
            h2 {
                color: #ff4b4b;
                font-size: 24px;
                margin-bottom: 25px;
            }
            a {
                display: block;
                margin-top: 20px;
                color: #3b6cff;
                text-decoration: none;
                font-size: 15px;
            }
            a:hover {
                text-decoration: underline;
            }
        </style>
    </head>
    <body>

    <div class="card">
        <h2>登録完了！</h2>
        <a href="../music-rank/index.php">戻る</a>
    </div>

    </body>
    </html>
    ';

} catch (PDOException $e) {
    echo 'データベースエラー: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES);
     echo '<p>エラー内容: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '</p>';
}
?>
</body>
</html>
