<?php 
session_start();    
    if(isset($_SESSION['user_id'])){
        $user_id = $_SESSION['user_id'];
        $user_name = $_SESSION['user_name'];
    }
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../assets/css/reset.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/admin.css">
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
                <h1>管理者画面</h1>
            </div>
            <div class="page-contents">
                <div class="user-list">
      <div class="user-row header">
        <span>アカウント名</span>
        <span>リクエスト数</span>
        <span></span>
      </div>
<?php
    // DB接続
    $pdo=new PDO(
	    'mysql:host=mysql326.phy.lolipop.lan;
            dbname=LAA1682282-sd3d4g;charset=utf8',
              'LAA1682282',
              'Passsd3d');

      $sql = "
        SELECT 
            u.user_id,
            u.user_name,
            COUNT(t.trip_id) AS trip_count
        FROM user u
        LEFT JOIN trip t ON u.user_id = t.user_id
        GROUP BY u.user_id, u.user_name
        ORDER BY u.user_id ASC
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


  <?php foreach ($users as $user): ?>
            <div class="user-row">
                <span><?= htmlspecialchars($user['user_name'], ENT_QUOTES, 'UTF-8') ?></span>
                <span><?= (int)$user['trip_count'] ?></span>
            <form action='../admin-account-delete/index.php' method='post'>
                <input type="hidden" name="user_id" value="<?=$user['user_id']?>">
                <input type="hidden" name="trip_count" value="<?= (int)$user['trip_count'] ?>">
                <button type="submit" class="delete">削除</button>
            </form>
        </div>
  <?php endforeach; ?>
            
    </div>
    <div class="dots">
  <span class="dot"></span>
  <span class="dot"></span>
  <span class="dot"></span>
</div>

            </div>
        </sction>
    </main>
    <div class="menu-bar-area">
        <?php include '../assets/include/menu-bar.php'?>
    </div>
</body>

</html>