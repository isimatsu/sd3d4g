<?php
    session_start();
    
    // GETパラメータから人気の旅行先を取得
    $popularity = isset($_GET['popularity']) ? $_GET['popularity'] : '';
    
    // 都道府県名を正規化（「京都」→「京都府」など）
    $destination_value = '';
    if ($popularity) {
        switch ($popularity) {
            case '京都':
                $destination_value = '京都府';
                break;
            case '東京':
                $destination_value = '東京都';
                break;
            case '北海道':
                $destination_value = '北海道';
                break;
            default:
                $destination_value = $popularity;
        }
    }
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
    z-index: 9999;
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
                <form action="../createplan-complete/index.php" method="POST" id="planForm">
                    <div class="question-card">
                        <div class="question-title">
                            <div class="question-title-logo">
                                <img class="" src="../assets/img/mappin3d.png">
                            </div>
                            <h2>行き先は?</h2>
                        </div>
                        <select class="question-pref select-style" name="destination_prefecture" required>
                            <option value="">選択してください</option>
                            <option value="北海道" <?php echo ($destination_value == '北海道') ? 'selected' : ''; ?>>北海道</option>
                            <option value="青森県" <?php echo ($destination_value == '青森県') ? 'selected' : ''; ?>>青森県</option>
                            <option value="岩手県" <?php echo ($destination_value == '岩手県') ? 'selected' : ''; ?>>岩手県</option>
                            <option value="宮城県" <?php echo ($destination_value == '宮城県') ? 'selected' : ''; ?>>宮城県</option>
                            <option value="秋田県" <?php echo ($destination_value == '秋田県') ? 'selected' : ''; ?>>秋田県</option>
                            <option value="山形県" <?php echo ($destination_value == '山形県') ? 'selected' : ''; ?>>山形県</option>
                            <option value="福島県" <?php echo ($destination_value == '福島県') ? 'selected' : ''; ?>>福島県</option>
                            <option value="茨城県" <?php echo ($destination_value == '茨城県') ? 'selected' : ''; ?>>茨城県</option>
                            <option value="栃木県" <?php echo ($destination_value == '栃木県') ? 'selected' : ''; ?>>栃木県</option>
                            <option value="群馬県" <?php echo ($destination_value == '群馬県') ? 'selected' : ''; ?>>群馬県</option>
                            <option value="埼玉県" <?php echo ($destination_value == '埼玉県') ? 'selected' : ''; ?>>埼玉県</option>
                            <option value="千葉県" <?php echo ($destination_value == '千葉県') ? 'selected' : ''; ?>>千葉県</option>
                            <option value="東京都" <?php echo ($destination_value == '東京都') ? 'selected' : ''; ?>>東京都</option>
                            <option value="神奈川県" <?php echo ($destination_value == '神奈川県') ? 'selected' : ''; ?>>神奈川県</option>
                            <option value="新潟県" <?php echo ($destination_value == '新潟県') ? 'selected' : ''; ?>>新潟県</option>
                            <option value="富山県" <?php echo ($destination_value == '富山県') ? 'selected' : ''; ?>>富山県</option>
                            <option value="石川県" <?php echo ($destination_value == '石川県') ? 'selected' : ''; ?>>石川県</option>
                            <option value="福井県" <?php echo ($destination_value == '福井県') ? 'selected' : ''; ?>>福井県</option>
                            <option value="山梨県" <?php echo ($destination_value == '山梨県') ? 'selected' : ''; ?>>山梨県</option>
                            <option value="長野県" <?php echo ($destination_value == '長野県') ? 'selected' : ''; ?>>長野県</option>
                            <option value="岐阜県" <?php echo ($destination_value == '岐阜県') ? 'selected' : ''; ?>>岐阜県</option>
                            <option value="静岡県" <?php echo ($destination_value == '静岡県') ? 'selected' : ''; ?>>静岡県</option>
                            <option value="愛知県" <?php echo ($destination_value == '愛知県') ? 'selected' : ''; ?>>愛知県</option>
                            <option value="三重県" <?php echo ($destination_value == '三重県') ? 'selected' : ''; ?>>三重県</option>
                            <option value="滋賀県" <?php echo ($destination_value == '滋賀県') ? 'selected' : ''; ?>>滋賀県</option>
                            <option value="京都府" <?php echo ($destination_value == '京都府') ? 'selected' : ''; ?>>京都府</option>
                            <option value="大阪府" <?php echo ($destination_value == '大阪府') ? 'selected' : ''; ?>>大阪府</option>
                            <option value="兵庫県" <?php echo ($destination_value == '兵庫県') ? 'selected' : ''; ?>>兵庫県</option>
                            <option value="奈良県" <?php echo ($destination_value == '奈良県') ? 'selected' : ''; ?>>奈良県</option>
                            <option value="和歌山県" <?php echo ($destination_value == '和歌山県') ? 'selected' : ''; ?>>和歌山県</option>
                            <option value="鳥取県" <?php echo ($destination_value == '鳥取県') ? 'selected' : ''; ?>>鳥取県</option>
                            <option value="島根県" <?php echo ($destination_value == '島根県') ? 'selected' : ''; ?>>島根県</option>
                            <option value="岡山県" <?php echo ($destination_value == '岡山県') ? 'selected' : ''; ?>>岡山県</option>
                            <option value="広島県" <?php echo ($destination_value == '広島県') ? 'selected' : ''; ?>>広島県</option>
                            <option value="山口県" <?php echo ($destination_value == '山口県') ? 'selected' : ''; ?>>山口県</option>
                            <option value="徳島県" <?php echo ($destination_value == '徳島県') ? 'selected' : ''; ?>>徳島県</option>
                            <option value="香川県" <?php echo ($destination_value == '香川県') ? 'selected' : ''; ?>>香川県</option>
                            <option value="愛媛県" <?php echo ($destination_value == '愛媛県') ? 'selected' : ''; ?>>愛媛県</option>
                            <option value="高知県" <?php echo ($destination_value == '高知県') ? 'selected' : ''; ?>>高知県</option>
                            <option value="福岡県" <?php echo ($destination_value == '福岡県') ? 'selected' : ''; ?>>福岡県</option>
                            <option value="佐賀県" <?php echo ($destination_value == '佐賀県') ? 'selected' : ''; ?>>佐賀県</option>
                            <option value="長崎県" <?php echo ($destination_value == '長崎県') ? 'selected' : ''; ?>>長崎県</option>
                            <option value="熊本県" <?php echo ($destination_value == '熊本県') ? 'selected' : ''; ?>>熊本県</option>
                            <option value="大分県" <?php echo ($destination_value == '大分県') ? 'selected' : ''; ?>>大分県</option>
                            <option value="宮崎県" <?php echo ($destination_value == '宮崎県') ? 'selected' : ''; ?>>宮崎県</option>
                            <option value="鹿児島県" <?php echo ($destination_value == '鹿児島県') ? 'selected' : ''; ?>>鹿児島県</option>
                            <option value="沖縄県" <?php echo ($destination_value == '沖縄県') ? 'selected' : ''; ?>>沖縄県</option>
                        </select>

                        <div class="popularity-spot">
                            <p style="font-size: 12px; color: #666666; padding: 10px 0;">人気の旅行先からはじめる</p>
                            <label class="pref-select-btn" onclick="setDestination('京都府')">
                                <div class="pref-icon" style="background-color: #F6F4F2;">
                                    <span class="material-symbols-rounded" style="color: #B49994;">landscape_2</span>
                                </div>
                                <div class="pref-detail">
                                    <h5>京都</h5>
                                    <p style="font-size: 12px; color: #333;">千年の歴史が息づく、雅の都</p>
                                </div>
                            </label><!--pref-select-btn-->
                            <label class="pref-select-btn" onclick="setDestination('東京都')">
                                <div class="pref-icon" style="background-color: #F2F6F2;">
                                    <span class="material-symbols-rounded" style="color: #94A5B4;">apartment</span>
                                </div>
                                <div class="pref-detail">
                                    <h5>東京</h5>
                                    <p style="font-size: 12px; color: #333;">世界が集う最先端と伝統の都市</p>
                                </div>
                            </label><!--pref-select-btn-->
                            <label class="pref-select-btn" onclick="setDestination('北海道')">
                                <div class="pref-icon" style="background-color: #F2F6F4;">
                                    <span class="material-symbols-rounded" style="color: #94B4AB;">nature</span>
                                </div>
                                <div class="pref-detail">
                                    <h5>北海道</h5>
                                    <p style="font-size: 12px; color: #333;">大自然と食の宝庫、四季の楽園</p>
                                </div>
                            </label><!--pref-select-btn-->
                        </div>
                    </div>
                    <div class="question-card">
                        <div class="question-title">
                            <div class="question-title-logo">
                                <img class="" src="../assets/img/door_3d.png">
                            </div>
                            <h2>出発地は?</h2>
                        </div>
                        <select class="question-pref select-style" name="departure_prefecture" required>
                            <option value="">選択してください</option>
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
                            <h2>誰と行きますか?</h2>
                            <select class="select-style" name="companion">
                                <option value="1人">1人</option>
                                <option value="家族">家族</option>
                                <option value="友人">友人</option>
                                <option value="恋人">恋人</option>
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
                            <input type="date" name="trip_start" class="date-style" required>
                            <span class="material-symbols-rounded">arrow_forward</span>
                            <input type="date" name="trip_end" class="date-style" required>
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
                            <option value="公共交通">公共交通</option>
                            <option value="車">車</option>
                        </select>
                    </div>
                    <div class="question-card">
                        <div class="question-title">
                            <div class="question-title-logo">
                                <img class="" src="../assets/img/dol.png">
                            </div>
                            <h2>予算</h2>
                            <select class="select-style" name="companion">
                                <option value="1~2万円">1~2万円</option>
                                <option value="~3万円">~3万円</option>
                                <option value="~4万円">~4万円</option>
                                <option value="~5万円" selected>~5万円</option>
                                <option value="~6万円">~6万円</option>
                                <option value="~7万円">~7万円</option>
                                <option value="~10万円">~10万円</option>
                                <option value="~15万円">~15万円</option>
                                <option value="~20万円">~20万円</option>
                                <option value="上限なし">上限なし</option>
                            </select>
                        </div>
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
                                <img class="" src="../assets/img/request.png">
                            </div>
                            <h2>特別なリクエスト</h2>
                        </div>
                        <input type="text" name="special_requests" class="basic-form-input" placeholder="リクエストを入力">
                    </div>
                    <button type="button" class="basic-btn plan-create-btn" onclick="submitForm()">この条件で作成</button>
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
            <p>あなたにぴったりの<br>旅程を作成しています!</p>
        </div>
    </div>
</body>
<script>
console.log('=== スクリプト読み込み完了 ===');

// PHPから受け取った初期値
const initialDestination = '<?php echo $destination_value; ?>';
if (initialDestination) {
    console.log('初期値設定:', initialDestination);
    // ページ読み込み時にハイライト表示
    const select = document.querySelector('select[name="destination_prefecture"]');
    select.style.backgroundColor = '#e8f5e9';
    setTimeout(() => {
        select.style.backgroundColor = '';
    }, 1500);
}

// 人気の旅行先を選択する関数
function setDestination(prefecture) {
    console.log('選択された都道府県:', prefecture);
    const select = document.querySelector('select[name="destination_prefecture"]');
    select.value = prefecture;
    // 選択後にハイライト表示
    select.style.backgroundColor = '#e8f5e9';
    setTimeout(() => {
        select.style.backgroundColor = '';
    }, 1000);
}

// フォーム送信関数
function submitForm() {
    console.log('=== フォーム送信開始 ===');
    
    const form = document.getElementById('planForm');
    
    // 入力値を取得
    const destination = form.querySelector('[name="destination_prefecture"]').value;
    const departure = form.querySelector('[name="departure_prefecture"]').value;
    const tripStart = form.querySelector('[name="trip_start"]').value;
    const tripEnd = form.querySelector('[name="trip_end"]').value;
    
    console.log('入力値:', {
        destination: destination,
        departure: departure,
        tripStart: tripStart,
        tripEnd: tripEnd
    });
    
    // バリデーション
    if (!destination) {
        alert('行き先を選択してください');
        console.log('バリデーションエラー: 行き先未選択');
        return;
    }
    if (!departure) {
        alert('出発地を選択してください');
        console.log('バリデーションエラー: 出発地未選択');
        return;
    }
    if (!tripStart || !tripEnd) {
        alert('滞在期間を入力してください');
        console.log('バリデーションエラー: 日付未入力');
        return;
    }
    
    // 日付の妥当性チェック
    if (new Date(tripStart) >= new Date(tripEnd)) {
        alert('出発日は帰着日より前の日付を選択してください');
        console.log('バリデーションエラー: 日付が不正');
        return;
    }
    
    console.log('バリデーション通過');
    
    // ローディング画面を表示
    const loadScreen = document.getElementById('load_screen');
    loadScreen.style.display = 'flex';
    console.log('ローディング画面表示');
    
    // フォーム送信
    console.log('フォーム送信実行');
    form.submit();
}

// フェードインアニメーション
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

console.log('=== イベントリスナー登録完了 ===');
</script>

</html>