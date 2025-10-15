<!DOCTYPE html>
<html lang="ja">
<head>

    <!-- Android Chrome -->
    <meta name="theme-color" content="#9fd3fa">

    <!-- iOS Safari -->
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-capable" content="yes">

    <!-- Windows (Edgeなど) -->
    <meta name="msapplication-navbutton-color" content="#9fd3fa">

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
        max-width: 470px;
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
                    <a href="" class="hero-music-card main-card" style="background-image: url(assets/img/music_img/1.jpg);">
                        <div class="music-card-detail">
                            <div>
                                <h2>花、真っ白</h2>
                                <p>藤井風</p>
                            </div>
                        </div>
                    </a><!--plan-card-->
                    <a href="" class="hero-music-card side-card" style="background-image: url(assets/img/music_img/1.jpg);">
                        <div class="music-card-detail">
                            <div>
                                <h2>花、真っ白</h2>
                                <p>藤井風</p>
                            </div>
                        </div>
                    </a><!--plan-card-->
                    <a href="" class="hero-music-card main-card" style="background-image: url(assets/img/music_img/1.jpg);">
                        <div class="music-card-detail">
                            <div>
                                <h2>花、真っ白</h2>
                                <p>藤井風</p>
                            </div>
                        </div>
                    </a><!--plan-card-->
                </div>
                <div class="new-plan-create-box">
                    <a class="new-plan-create" href="#">
                        <span class="material-symbols-rounded">add_circle</span>
                        旅程を作成
                    </a>
                    <form>
                        <p style="font-size: 12px; color: #666666; padding: 10px 0;">人気の旅行先からはじめる</p>
                        <label class="pref-select-btn">
                            <input type="submit" value="">
                            <div class="pref-icon" style="background-color: #F6F4F2;">
                                <span class="material-symbols-rounded" style="color: #B49994;">landscape_2</span>
                            </div>
                            <div class="pref-detail">
                                <h5>京都</h5>
                                <p style="font-size: 12px; color: #333;">千年の歴史が息づく、雅の都</p>
                            </div>
                        </label><!--pref-select-btn-->
                        <label class="pref-select-btn">
                            <input type="submit" value="">
                            <div class="pref-icon" style="background-color: #F2F6F2;">
                                <span class="material-symbols-rounded" style="color: #94A5B4;">apartment</span>
                            </div>
                            <div class="pref-detail">
                                <h5>東京</h5>
                                <p style="font-size: 12px; color: #333;">世界が集う最先端と伝統の都市</p>
                            </div>
                        </label><!--pref-select-btn-->
                        <label class="pref-select-btn">
                            <input type="submit" value="">
                            <div class="pref-icon" style="background-color: #F2F6F4;">
                                <span class="material-symbols-rounded" style="color: #94B4AB;"">nature</span>
                            </div>
                            <div class="pref-detail">
                                <h5>北海道</h5>
                                <p style="font-size: 12px; color: #333;">大自然と食の宝庫、四季の楽園</p>
                            </div>
                        </label><!--pref-select-btn-->
                    </form>
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
            const scrollPosition = mainCard.offsetLeft - (planList.offsetWidth / 2) + (mainCard.offsetWidth / 2);
            planList.scrollLeft = scrollPosition;
        }
    });
</script>
</html>