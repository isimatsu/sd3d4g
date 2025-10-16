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
                <form>
                    <div class="question-card">
                        <div class="question-title">
                            <div class="question-title-logo">
                                <img class="" src="../assets/img/mappin3d.png">
                            </div>
                            <h2>行き先は？</h2>
                        </div>
                        <select class="question-pref select-style" name="prefecture">
                            <option value="0" default>選択してください</option>
                            <option value="1">北海道</option>
                            <option value="2">青森県</option>
                            <option value="3">岩手県</option>
                            <option value="4">宮城県</option>
                            <option value="5">秋田県</option>
                            <option value="6">山形県</option>
                            <option value="7">福島県</option>
                            <option value="8">茨城県</option>
                            <option value="9">栃木県</option>
                            <option value="10">群馬県</option>
                            <option value="11">埼玉県</option>
                            <option value="12">千葉県</option>
                            <option value="13">東京都</option>
                            <option value="14">神奈川県</option>
                            <option value="15">新潟県</option>
                            <option value="16">富山県</option>
                            <option value="17">石川県</option>
                            <option value="18">福井県</option>
                            <option value="19">山梨県</option>
                            <option value="20">長野県</option>
                            <option value="21">岐阜県</option>
                            <option value="22">静岡県</option>
                            <option value="23">愛知県</option>
                            <option value="24">三重県</option>
                            <option value="25">滋賀県</option>
                            <option value="26">京都府</option>
                            <option value="27">大阪府</option>
                            <option value="28">兵庫県</option>
                            <option value="29">奈良県</option>
                            <option value="30">和歌山県</option>
                            <option value="31">鳥取県</option>
                            <option value="32">島根県</option>
                            <option value="33">岡山県</option>
                            <option value="34">広島県</option>
                            <option value="35">山口県</option>
                            <option value="36">徳島県</option>
                            <option value="37">香川県</option>
                            <option value="38">愛媛県</option>
                            <option value="39">高知県</option>
                            <option value="40">福岡県</option>
                            <option value="41">佐賀県</option>
                            <option value="42">長崎県</option>
                            <option value="43">熊本県</option>
                            <option value="44">大分県</option>
                            <option value="45">宮崎県</option>
                            <option value="46">鹿児島県</option>
                            <option value="47">沖縄県</option>
                        </select>
                        <div class="popularity-spot">
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
                        </div>
                    </div>
                    <div class="question-card">
                        <div class="question-title">
                            <div class="question-title-logo">
                                <img class="" src="../assets/img/hand.png">
                            </div>
                            <h2>誰と行きますか？</h2>
                            <select class="select-style">
                                <option value="1人">一人</option>
                            </select>
                        </div>
                    </div>
                    <div class="question-card">
                        <div class="question-title">
                            <div class="question-title-logo">
                                <img class="" src="../assets/img/mappin3d.png">
                            </div>
                            <h2>行き先は？</h2>
                        </div>

                    </div>
                </form>
            </div>
        </sction>
    </main>
    <div class="menu-bar-area">
        <?php include '../assets/include/menu-bar.php'?>
    </div>
</body>
</html>