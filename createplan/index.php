<?php
    session_start();    
    
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../assets/css/reset.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
     <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100..900&display=swap" rel="stylesheet">
    <title>旅程作成 -旅行提案アプリ-</title>
</head>
<style>
.plan-create-btn{
    width: 100%;
    height: px;
    background-color: #94A5B4;
    /* position: fixed;
    bottom: 80px; */
    box-shadow: rgba(17, 17, 26, 0.1) 0px 1px 0px, rgba(17, 17, 26, 0.1) 0px 8px 24px, rgba(17, 17, 26, 0.1) 0px 16px 48px;
}

.fade-in {
    opacity: 0;
    transform: translateY(30px);
    transition: opacity 0.8s ease, transform 0.8s ease;
}

.fade-in.visible {
    opacity: 1;
    transform: translateY(0);
}

.load-screen{
    background-color: #ffffffff;
    width: 100%;
    height: 100%;
    position: fixed;
    top: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    display: none;
    text-align: center;
}

.load-screen p{
    font-size: 20px;
    font-weight: bold;
}
</style>
<body>
    <main>
        <sction class="sm">
            <div class="header">
                <?php include '../assets/include/header.php'?>
            </div>
            <div class="page-header">
                <h1>旅程の新規作成</h1>
                <p>あなたに合った旅程を提案します。旅行先、日程、同行者を教えてください</p>
            </div>
            <div class="page-contents">
                <form action="../createplan-complete/index.php" method="POST">
                    <div class="question-card">
                        <div class="question-title">
                            <div class="question-title-logo">
                                <img class="" src="../assets/img/mappin3d.png">
                            </div>
                            <h2>行き先は？</h2>
                        </div>
                        <select class="question-pref select-style" name="destination_prefecture">
                            <option value="" default>選択してください</option>
                            <option value="北海道">北海道</option>
                            <option value="青森県">青森県</option>
                            <option value="岩手県">岩手県</option>
                            <option value="宮城県">宮城県</option>
                            <option value="秋田県">秋田県</option>
                            <option value="山形県">山形県</option>
                            <option value="福島県">福島県</option>
                            <option value="茨城県">茨城県</option>
                            <option value="栃木県">栃木県</option>
                            <option value="群馬県">群馬県</option>
                            <option value="埼玉県">埼玉県</option>
                            <option value="千葉県">千葉県</option>
                            <option value="東京都">東京都</option>
                            <option value="神奈川県">神奈川県</option>
                            <option value="新潟県">新潟県</option>
                            <option value="富山県">富山県</option>
                            <option value="石川県">石川県</option>
                            <option value="福井県">福井県</option>
                            <option value="山梨県">山梨県</option>
                            <option value="長野県">長野県</option>
                            <option value="岐阜県">岐阜県</option>
                            <option value="静岡県">静岡県</option>
                            <option value="愛知県">愛知県</option>
                            <option value="三重県">三重県</option>
                            <option value="滋賀県">滋賀県</option>
                            <option value="京都府">京都府</option>
                            <option value="大阪府">大阪府</option>
                            <option value="兵庫県">兵庫県</option>
                            <option value="奈良県">奈良県</option>
                            <option value="和歌山県">和歌山県</option>
                            <option value="鳥取県">鳥取県</option>
                            <option value="島根県">島根県</option>
                            <option value="岡山県">岡山県</option>
                            <option value="広島県">広島県</option>
                            <option value="山口県">山口県</option>
                            <option value="徳島県">徳島県</option>
                            <option value="香川県">香川県</option>
                            <option value="愛媛県">愛媛県</option>
                            <option value="高知県">高知県</option>
                            <option value="福岡県">福岡県</option>
                            <option value="佐賀県">佐賀県</option>
                            <option value="長崎県">長崎県</option>
                            <option value="熊本県">熊本県</option>
                            <option value="大分県">大分県</option>
                            <option value="宮崎県">宮崎県</option>
                            <option value="鹿児島県">鹿児島県</option>
                            <option value="沖縄県">沖縄県</option>
                        </select>

                        <form class="popularity-spot" method="GET" action="../createplan-complete/">
                        <p style="font-size: 12px; color: #666666; padding: 10px 0;">人気の旅行先からはじめる</p>
                        <label class="pref-select-btn">
                            <input type="submit" name="popularity" value="京都" hidden>
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
                    <div class="question-card">
                        <div class="question-title">
                            <div class="question-title-logo">
                                <img class="" src="../assets/img/door_3d.png">
                            </div>
                            <h2>出発地は？</h2>
                        </div>
                        <select class="question-pref select-style" name="departure_prefecture">
                            <option value="" default>選択してください</option>
                            <option value="北海道">北海道</option>
                            <option value="青森県">青森県</option>
                            <option value="岩手県">岩手県</option>
                            <option value="宮城県">宮城県</option>
                            <option value="秋田県">秋田県</option>
                            <option value="山形県">山形県</option>
                            <option value="福島県">福島県</option>
                            <option value="茨城県">茨城県</option>
                            <option value="栃木県">栃木県</option>
                            <option value="群馬県">群馬県</option>
                            <option value="埼玉県">埼玉県</option>
                            <option value="千葉県">千葉県</option>
                            <option value="東京都">東京都</option>
                            <option value="神奈川県">神奈川県</option>
                            <option value="新潟県">新潟県</option>
                            <option value="富山県">富山県</option>
                            <option value="石川県">石川県</option>
                            <option value="福井県">福井県</option>
                            <option value="山梨県">山梨県</option>
                            <option value="長野県">長野県</option>
                            <option value="岐阜県">岐阜県</option>
                            <option value="静岡県">静岡県</option>
                            <option value="愛知県">愛知県</option>
                            <option value="三重県">三重県</option>
                            <option value="滋賀県">滋賀県</option>
                            <option value="京都府">京都府</option>
                            <option value="大阪府">大阪府</option>
                            <option value="兵庫県">兵庫県</option>
                            <option value="奈良県">奈良県</option>
                            <option value="和歌山県">和歌山県</option>
                            <option value="鳥取県">鳥取県</option>
                            <option value="島根県">島根県</option>
                            <option value="岡山県">岡山県</option>
                            <option value="広島県">広島県</option>
                            <option value="山口県">山口県</option>
                            <option value="徳島県">徳島県</option>
                            <option value="香川県">香川県</option>
                            <option value="愛媛県">愛媛県</option>
                            <option value="高知県">高知県</option>
                            <option value="福岡県">福岡県</option>
                            <option value="佐賀県">佐賀県</option>
                            <option value="長崎県">長崎県</option>
                            <option value="熊本県">熊本県</option>
                            <option value="大分県">大分県</option>
                            <option value="宮崎県">宮崎県</option>
                            <option value="鹿児島県">鹿児島県</option>
                            <option value="沖縄県">沖縄県</option>
                        </select>
                    </div>
                    <div class="question-card">
                        <div class="question-title">
                            <div class="question-title-logo">
                                <img class="" src="../assets/img/hand.png">
                            </div>
                            <h2>誰と行きますか？</h2>
                            <select class="select-style" name="companion">
                                <option value="1人">1人</option>
                                <option value="2人">2人</option>
                            </select>
                        </div>
                    </div>
                    <div class="question-card">
                        <div class="question-title">
                            <div class="question-title-logo">
                                <img class="" src="../assets/img/calendar_3d.png">
                            </div>
                            <h2>滞在期間</h2>
                        </div>
                        <div class="date-input">
                            <input type="date" name="trip_start" class="date-style">
                            <span class="material-symbols-rounded">arrow_forward</span>
                            <input type="date" name="trip_end" class="date-style">
                        </div>
                    </div>
                    <div class="question-card">
                        <div class="question-title">
                            <div class="question-title-logo">
                                <img class="" src="../assets/img/automobile_3d.png">
                            </div>
                            <h2>移動手段</h2>
                            <p style="font-size: 10px; color:#333;">※観光地での移動手段</p>
                        </div>
                        <select class="select-style" name="move">
                            <option value="公共交通">交通交通</option>
                            <option value="車">車</option>
                        </select>
                    </div>
                    <div class="question-card">
                        <div class="question-title">
                            <div class="question-title-logo">
                                <img class="" src="../assets/img/mappin3d.png">
                            </div>
                            <h2>経由したい場所</h2>
                        </div>
                        <input type="text" name="waypoint" class="basic-form-input" placeholder="地域、名所を入力">
                    </div>
                    <div class="question-card">
                        <div class="question-title">
                            <div class="question-title-logo">
                                <img class="" src="../assets/img/mappin3d.png">
                            </div>
                            <h2>特別なリクエスト</h2>
                        </div>
                        <input type="text" name="special_requests" class="basic-form-input" placeholder="地域、名所を入力">
                    </div>
                    <button class="basic-btn plan-create-btn" onclick="click_load()">この条件で作成</button>
                </form>
            </div>
        </sction>
    </main>
    <div class="menu-bar-area">
        <?php include '../assets/include/menu-bar.php'?>
    </div>
    <div class="load-screen" id="load_screen">
        <div style="text-align: center;">
            <img src="../assets/img/load.gif" width="80%">
            <p>あなたにぴったりの</nav><br>旅程を作成しています！</p>
        </div>
    </div>
</body>
<script>

const fadeInElements = document.querySelectorAll('.question-card');

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('visible');
            observer.unobserve(entry.target);
        }
    });
}, {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
});

fadeInElements.forEach(element => {
    element.classList.add('fade-in');
    observer.observe(element);
});

function click_load(){
   const load_screen = document.getElementById('load_screen');
   load_screen.style.display = 'flex'
}

</script>

</html>