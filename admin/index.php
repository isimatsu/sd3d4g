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

      <!-- 繰り返し部分 -->
      <div class="user-row">
        <span>〇〇（アカウント名）</span>
        <span>100</span>
        <a href="#" class="delete">削除</a>
      </div>
      <div class="user-row">
        <span>〇〇（アカウント名）</span>
        <span>100</span>
        <a href="#" class="delete">削除</a>
      </div>
      <div class="user-row">
        <span>〇〇（アカウント名）</span>
        <span>100</span>
        <a href="#" class="delete">削除</a>
      </div>
      <div class="user-row">
        <span>〇〇（アカウント名）</span>
        <span>100</span>
        <a href="#" class="delete">削除</a>
      </div>
      <div class="user-row">
        <span>〇〇（アカウント名）</span>
        <span>100</span>
        <a href="#" class="delete">削除</a>
      </div>
      <div class="user-row">
        <span>〇〇（アカウント名）</span>
        <span>100</span>
        <a href="#" class="delete">削除</a>
      </div>
      <div class="user-row">
        <span>〇〇（アカウント名）</span>
        <span>100</span>
        <a href="#" class="delete">削除</a>
      </div>
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