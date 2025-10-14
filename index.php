<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="assets/css/reset.css">
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="assets/css/index.css">
     <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100..900&display=swap" rel="stylesheet">
    <title>ホーム -旅行提案アプリ-</title>
</head>
<style>
    .header{
        width: calc(100% - 30px);
        height: 45px;
        position: fixed;
        top: 15px;
    }

    .page-contents{
        width: 100%;
        margin-top: 60px;
    }
</style>
<body>
    <main>
        <sction class="sm">
            <div class="header">
                <?php include 'assets/include/header.php'?>
            </div>
            <div class="page-contents">
                <div class="hero-plan-list">
                    <a href="" class="plan-card side-card" style="background-image: url(assets/img/spot_img/40.jpg);">
                        <div class="plan-card-detail">
                            <div>
                                <p>2025/10/10 ~ 2025/10/12</p>
                                <h2>福岡旅行</h2>
                            </div>
                        </div>
                    </a><!--plan-card-->
                    <a href="" class="plan-card main-card" style="background-image: url(assets/img/spot_img/1.jpg);">
                        <div class="plan-card-detail">
                            <div>
                                <p>2025/10/10 ~ 2025/10/12</p>
                                <h2>北海道旅行</h2>
                            </div>
                        </div>
                    </a><!--plan-card-->
                    <a href="" class="plan-card side-card" style="background-image: url(assets/img/spot_img/40.jpg);">
                        <div class="plan-card-detail">
                            <div>
                                <p>2025/10/10 ~ 2025/10/12</p>
                                <h2>福岡旅行</h2>
                            </div>
                        </div>
                    </a><!--plan-card-->
                </div>
                <div class="hero-music-list">
                    <a href="" class="music-card side-card" style="background-image: url(assets/img/spot_img/40.jpg);">
                        <div class="music-card-detail">
                            <div>
                                <p>2025/10/10 ~ 2025/10/12</p>
                                <h2>福岡旅行</h2>
                            </div>
                        </div>
                    </a><!--plan-card-->
                </div>
            </div>
        </sction>
    </main>
    <div class="menu-bar-area">
        <?php include 'assets/include/menu-bar.php'?>
    </div>
</body>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const planList = document.querySelector('.hero-plan-list');
    const mainCard = document.querySelector('.main-card');
    
    if (planList && mainCard) {
        // 真ん中のカードを中央に配置
        const scrollPosition = mainCard.offsetLeft - (planList.offsetWidth / 2) + (mainCard.offsetWidth / 2);
        planList.scrollLeft = scrollPosition;
    }
});
</script>
</html>