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

    if (!isset($_SESSION['user_id'])) {
        echo json_encode(["status" => "error", "message" => "ログインが必要です"]);
        exit;
    }

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

        $user_id = $_SESSION['user_id'];
        $song_id = intval($_POST['song_id']);

        $sql = "SELECT 1 FROM good WHERE user_id = :uid AND song_id = :sid";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':uid' => $user_id, ':sid' => $song_id]);
        $exists = $stmt->fetch();

        if ($exists) {
            // good解除
            $pdo->prepare("DELETE FROM good WHERE user_id = :uid AND song_id = :sid")
                ->execute([':uid' => $user_id, ':sid' => $song_id]);

            // songテーブルのgoodカウントを減らす
            $pdo->prepare("UPDATE song SET good = good - 1 WHERE song_id = :sid")
                ->execute([':sid' => $song_id]);

            $status = "ungooded";
        } else {
            // good登録
            $pdo->prepare("INSERT INTO good(user_id, song_id) VALUES(:uid, :sid)")
                ->execute([':uid' => $user_id, ':sid' => $song_id]);

            // songテーブルのgoodカウントを増やす
            $pdo->prepare("UPDATE song SET good = good + 1 WHERE song_id = :sid")
                ->execute([':sid' => $song_id]);

            $status = "gooded";
        }

        // 最新のgood数を取得
        $count_stmt = $pdo->prepare("SELECT good FROM song WHERE song_id = :sid");
        $count_stmt->execute([':sid' => $song_id]);
        $good_count = $count_stmt->fetchColumn();

        echo json_encode([
            "status" => $status,
        ]);
?>