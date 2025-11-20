<?php
    session_start(); 
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);   
    if(isset($_SESSION['user_id'])){
        $user_id = $_SESSION['user_id'];
        $user_name = $_SESSION['user_name'];
    }

    // plan_idのチェック
    if (!isset($_GET['plan_id']) || empty($_GET['plan_id'])) {
        die("旅程IDが指定されていません。");
    }


    $plan_id = $_GET['plan_id'];

    //DB接続情報
    $host = 'mysql326.phy.lolipop.lan';
    $dbname = 'LAA1682282-sd3d4g';
    $user = 'LAA1682282';
    $pass = 'Passsd3d';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // 旅程データ取得
        $sql = "SELECT * FROM `trip` WHERE `trip_id` = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$plan_id]);
        $trips = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // データが存在するかチェック
        if (empty($trips)) {
            die("旅程データの解析に失敗しました。指定された旅程が見つかりません。(ID: " . htmlspecialchars($plan_id) . ")");
        }

        // 最初の要素を取得（trip_idで検索するので通常1件）
        $trip_info = $trips[0];

        // 旅程詳細データ取得
        $parts_sql = "SELECT * FROM `trip_info` WHERE `trip_id` = ? ORDER BY `trip_info`.`segment_id` ASC";
        $stmt = $pdo->prepare($parts_sql);
        $stmt->execute([$plan_id]);
        $parts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        die("データベースエラー: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
    }

        //memoが送られているかどうか
    if (isset($_POST['memo_add'])) {
        $memo = $_POST['memo_add'];
        $sql_segment_id = $_POST['segment_id'];

        $memo_sql = "UPDATE trip_info SET memo = :memo WHERE segment_id = $sql_segment_id";
        $stmt = $pdo->prepare($memo_sql);
        $stmt->bindValue(':memo', $memo, PDO::PARAM_STR);
        $stmt->execute();

        header("Location: " . $_SERVER['REQUEST_URI']);
        exit();
        
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
    <link rel="stylesheet" type="text/css" href="../assets/css/plan.css">
    <title>旅程 -旅行提案アプリ-</title>
</head>

<body>
    <main>
        <sction class="sm" style="position: relative;">
            <div class="header">
                <?php include '../assets/include/header.php'?>
            </div>
            <div class="plan-hero" style="background-image: url(../assets/img/spot_img/<?=htmlspecialchars($trip_info['pref_id'])?>.png);">
                <div class="trip-title">
                    <!-- <div class="for-user">
                        <p><?= htmlspecialchars($user_name) ?>さんにぴったりの旅程を作成しました。</p>
                    </div> -->
                    <div>
                        <h1><?=htmlspecialchars($trip_info['trip_name'])?></h1>
                        <h5><?=htmlspecialchars($trip_info['trip_start'])?>～<?=htmlspecialchars($trip_info['trip_end'])?></h5>
                        <p><?=htmlspecialchars($trip_info['trip_overview'])?></p>
                    </div>
                </div>
            </div>
            <div class="page-contents">
                <div class="plan-tree">
                    <?php
                        foreach($parts as $parts_tree){
                            $segment_id = $parts_tree['segment_id'];
                            $segment_type = $parts_tree['segment_type'];
                            $segment_name = $parts_tree['segment_name'];
                            $time = $parts_tree['start_time'];
                            $start_time = date("H:i", strtotime($time));
                            $segment_info = $parts_tree['segment_info'];
                            $memo = $parts_tree['memo'];
                        
                            if($segment_type == 1){
                                //移動アイコン
                                switch($parts_tree['segment_info']):
                                case 'plane':
                                    $segment_icon_name = 'travel';
                                    break;
                                case 'train':
                                    $segment_icon_name = 'train';
                                    break;
                                default:
                                    $segment_icon_name = 'directions_car';
                                endswitch;
                                //move
                                echo "
                                    <div class='tree-move'>
                                        <div class='move-line'></div>
                                        <div class='move-info'>
                                            <div class='move-detail'>
                                                <span class='move-icon material-symbols-rounded'>{$segment_icon_name}</span>
                                                <p>" . htmlspecialchars($segment_name) . "</p>
                                            </div>
                                            <button class='move-music-btn' onClick='play_music()'><span class='material-symbols-rounded'>music_note</span><p>音楽を再生</p></button>
                                        </div>
                                    </div><!--move-->
                                ";
                            }else if($segment_type == 2){

                                switch($parts_tree['segment_info']):
                                case 'station':
                                    $segment_icon_name = 'bus_railway';
                                    break;
                                case 'airport_takeoff':
                                    $segment_icon_name = 'flight_takeoff';
                                    break;
                                case 'airport_land':
                                    $segment_icon_name = 'flight_land';
                                    break;
                                case 'hotel':
                                    $segment_icon_name = 'hotel';
                                    break;
                                default:
                                    $segment_icon_name = 'location_on';
                                endswitch;
                                //point
                                if($segment_info == 'tourist'){
                                    $segment_detail = $parts_tree['segment_detail'];
                                    echo "
                                    <div class='tree-point'>
                                        <div class='point-card'>
                                            <div class='point-info'>
                                                <div class='point_tourist'>
                                                    <div class='tourist-info'>
                                                        <div class='tourist-img'><span class='material-symbols-rounded'>image</span></div>
                                                        <div class='tourist-name'>
                                                            <h5 class='point-card-time'>{$start_time}</h5>
                                                            <h4 class='point-card-name-tourist'>" . htmlspecialchars($segment_name) . "</h4>
                                                            
                                                        </div>
                                                    </div>
                                                    <div class='tourist-detail'>
                                                        <p>" . htmlspecialchars($segment_detail) . "</p>
                                                    </div>
                                                    <p>{$memo}</p>
                                                    <form action='#' method='POST'><input type='hidden' name='edit_segment_id' value='{$segment_id}'><button class='plan-edit-btn plan-edit-btn-tourist'><span class='material-symbols-rounded'>edit_note</span></button></form>
                                                </div>
                                                                                            ";
                                                    if(isset($memo)){
                                                        echo"
                                                        <div class='segment-memo'>
                                                            <p>メモ：{$memo}</p>
                                                        </div>
                                                        ";
                                                    }
                                echo "
                                            </div>
                                        </div>
                                    </div><!--point-->
                                    ";  
                                }else{
                                    echo "
                                    <div class='tree-point'>
                                        <div class='point-card'>
                                            <div class='point-info'>
                                                <div class='point-detail'>
                                                    <div>
                                                        <span class='point-icon material-symbols-rounded' style='color:#666;'>{$segment_icon_name}</span>
                                                        <div class='point-name'>
                                                            <h5 class='point-card-time' style='margin: 0 10px;'>{$start_time} </h5>
                                                            <h5 class='point-card-name'>" . htmlspecialchars($segment_name) . "</h5>
                                                        </div>
                                                    </div>
                                                <form action='#' method='POST'><input type='hidden' name='edit_segment_id' value='{$segment_id}'><button class='plan-edit-btn plan-edit-btn-tourist'><span class='material-symbols-rounded'>edit_note</span></button></form>
                                            </div>
                                            ";
                                                    if(isset($memo)){
                                                        echo"
                                                        <div class='segment-memo'>
                                                            <p>メモ：{$memo}</p>
                                                        </div>
                                                        ";
                                                    }
                                echo "
                                        </div>
                                    </div><!--point-->
                                    "; 
                                }

                            }
                        }
                    ?>
                </div>
                <div class="planpage-music-list">
                    <div class="planpage-music-list-title">
                        <span class='point-icon material-symbols-rounded'>queue_music</span>
                        <p><?=htmlspecialchars($trip_info['trip_name'])?>のプレイリスト</p>
                    </div>
                    <?php
                        $sql = "SELECT * FROM `song` WHERE `trip_id` = ? ORDER BY `trip_id` DESC";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute([$plan_id]);
                        $music = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        foreach($music as $row){
                            $singer_name = htmlspecialchars($row['singer_name']);
                            $song_name = htmlspecialchars($row['song_name']);
                            $music_url = htmlspecialchars($row['link']);
                            echo <<<HTML
                                <div class="planpage-music-list-card">
                                    <div>
                                        <p class="music-name">{$song_name}</p>
                                        <p class="music-aname">{$singer_name}</p>
                                    </div>
                                    <div>
                                        <button><span class='music-list-icon material-symbols-rounded'>favorite</span></button>
                                        <button onclick="location.href='$music_url'"   class="music-play-btn"    data-url="{$music_url}">
                                            <span class='music-list-icon material-symbols-rounded' style="color: #7968FF;">play_circle</span>
                                        </button>
                                    </div>
                                </div><!--music-list-->
                            HTML;
                        }
                    ?>
                </div>
                <div class="plan-feedback">
                    <?php 
                    if(isset($_POST['destination_prefecture'])){?>
                        <div class="feedback-title">
                            <h3>提案された旅程はいかがでしたか？</h3>
                            <p>「良くない」「非常に悪い」選択すると提案は<br>要望に沿って再生成されます</p>
                        </div>
                        <form id="feedback_form"  method="POST">
                            <!-- 隠しフィールドで元のデータを保持 -->
                             <?php
                            $destination_prefecture = $_POST['destination_prefecture'] ?? '';
                            $departure_prefecture = $_POST['departure_prefecture'] ?? '';
                            $companion = $_POST['companion'] ?? '';
                            $trip_start = $_POST['trip_start'] ?? '';
                            $trip_end = $_POST['trip_end'] ?? '';
                            $move = $_POST['move'] ?? '';
                            $special_requests = $_POST['special_requests'] ?? '';
                            $waypoint = empty($_POST['waypoint']) ? 'なし' : $_POST['waypoint'];
                            echo "テスト都道府県$destination_prefecture";
                            ?>
                            <input type="hidden" name="destination_prefecture" value="<?=htmlspecialchars($destination_prefecture)?>">
                            <input type="hidden" name="departure_prefecture" value="<?=htmlspecialchars($departure_prefecture)?>">
                            <input type="hidden" name="companion" value="<?=htmlspecialchars($companion)?>">
                            <input type="hidden" name="trip_start" value="<?=htmlspecialchars($trip_start)?>">
                            <input type="hidden" name="trip_end" value="<?=htmlspecialchars($trip_end)?>">
                            <input type="hidden" name="move" value="<?=htmlspecialchars($move)?>">
                            <input type="hidden" name="waypoint" value="<?=htmlspecialchars($waypoint)?>">
                            <div class="feedback-btn-list">
                                <input type="radio" name="feedback"  id="option1" value="1" class="feedback-radio" style="display: none;">
                                <label class="feedback-level level-good" for="option1">
                                    <div><span class='point-icon material-symbols-rounded'>mood</span><p>良い</p></div>
                                </label>

                                <input type="radio" name="feedback" id="option2" value="2" class="feedback-radio" style="display: none;">
                                <label class="feedback-level level-bad" for="option2">
                                    <div><span class='point-icon material-symbols-rounded'>sentiment_dissatisfied</span><p>良くない</p></div>
                                </label>

                                <input type="radio" name="feedback" id="option3" value="3" class="feedback-radio" style="display: none;">
                                <label class="feedback-level level-verybad" for="option3">
                                    <div><span class='point-icon material-symbols-rounded'>sentiment_extremely_dissatisfied</span><p>非常に悪い</p></div>
                                </label>
                            </div>
                            <input type="text" name="special_requests" class="feedback-text" placeholder="改善してほしい箇所、要望を具体的に入力してください" value="<?= htmlspecialchars($special_requests) ?>">
                            <button type="submit" class="basic-btn blue-btn" id="submitBtn">再生成</button>
                        </form>
                    <?php }elseif(isset($_POST['test'])){
                        echo "エラー";
                    }else{
                        echo "フィードバック済";
                    } ?>
                </div>
<script>
    const feedbackRadios = document.querySelectorAll('.feedback-radio');
    const submitBtn = document.getElementById('submitBtn');
    const specialRequests = document.querySelector('.feedback-text');  
    const feedback_form = document.getElementById('feedback_form');    

    feedbackRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value == '1') {
                submitBtn.innerText = '保存';
                specialRequests.disabled = true;
                specialRequests.placeholder = '保存するため入力は不要です';
                specialRequests.style.backgroundColor = '#f0f0f0';
                feedback_form.action = "index.php?plan_id=<?= htmlspecialchars($plan_id) ?>";
            } else {
                submitBtn.textContent = '再生成';
                specialRequests.disabled = false;
                specialRequests.placeholder = '改善してほしい箇所、要望を具体的に入力してください';
                specialRequests.style.backgroundColor = '#fff';
                feedback_form.action = "../createplan-complete/";
            }
        });
    });
</script>

            </div>
        </sction>

    </main>
    <div class="menu-bar-area">
        <?php include '../assets/include/menu-bar.php'?>
    </div>
<?php
if (isset($_POST['edit_segment_id'])) {
    $edit_segment_id = $_POST['edit_segment_id'];

    $sql = "SELECT * FROM `trip_info` WHERE `segment_id` = ? AND `trip_id` = ? ORDER BY `segment_id` ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$edit_segment_id, $plan_id]);
    $segment = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($segment)) {
        echo "<!-- 編集対象のセグメントが見つかりません -->";
    } else {
        $edit_segment_name = $segment[0]['segment_name'];
    ?>
    <!-- HTML部分ここから -->
    <div class='modal-outline memo-modal' id='modal_outline'>
        <div class='modal-area'>
            <button onClick='modal_close()' class='model-close-btn'>
                <span class='material-symbols-rounded'>close</span>
            </button>
            <div class='edit-modal-title'>
                <span class='material-symbols-rounded'>edit_note</span>
                <h3>選択された箇所の<br>メモを編集します</h3>
            </div>

            <h5 class="plan-edit-">選択行程</h5>

            <?php foreach ($segment as $parts_tree): 
                $segment_id = $parts_tree['segment_id'];
                $segment_type = $parts_tree['segment_type'];
                $segment_name = $parts_tree['segment_name'];
                $time = $parts_tree['start_time'];
                $start_time = date("H:i", strtotime($time));
                $segment_info = $parts_tree['segment_info'];
            ?>

                <?php if ($segment_type == 1): ?>
                    <?php
                    //移動アイコン
                    switch ($segment_info):
                        case 'plane':
                            $segment_icon_name = 'travel';
                            break;
                        case 'train':
                            $segment_icon_name = 'train';
                            break;
                        default:
                            $segment_icon_name = 'directions_car';
                    endswitch;
                    ?>
                    <div class='tree-move'>
                        <div class='move-line'></div>
                        <div class='move-info'>
                            <div class='move-detail'>
                                <span class='move-icon material-symbols-rounded'><?= $segment_icon_name ?></span>
                                <p><?= htmlspecialchars($segment_name) ?></p>
                            </div>
                            <button class='move-music-btn'>
                                <span class='material-symbols-rounded'>music_note</span>
                                <p>音楽を再生</p>
                            </button>
                        </div>
                    </div>

                <?php elseif ($segment_type == 2): ?>
                    <?php
                    switch ($segment_info):
                        case 'station':
                            $segment_icon_name = 'bus_railway';
                            break;
                        case 'airport_takeoff':
                            $segment_icon_name = 'flight_takeoff';
                            break;
                        case 'airport_land':
                            $segment_icon_name = 'flight_land';
                            break;
                        case 'hotel':
                            $segment_icon_name = 'hotel';
                            break;
                        default:
                            $segment_icon_name = 'location_on';
                    endswitch;
                    ?>

                    <?php if ($segment_info == 'tourist'): ?>
                        <?php $segment_detail = $parts_tree['segment_detail']; ?>
                        <div class='tree-point'>
                            <div class='point-card'>
                                <div class='point-info'>
                                    <div class='point_tourist'>
                                        <div class='tourist-info'>
                                            <div class='tourist-img'>
                                                <span class='material-symbols-rounded'>image</span>
                                            </div>
                                            <div class='tourist-name'>
                                                <h5 class='point-card-time'><?= $start_time ?></h5>
                                                <h4 class='point-card-name-tourist'><?= htmlspecialchars($segment_name) ?></h4>
                                            </div>
                                        </div>
                                        <div class='tourist-detail'>
                                            <p><?= htmlspecialchars($segment_detail) ?></p>
                                        </div>
                                        <form action='#' method='POST'>
                                            <input type='hidden' name='edit_segment_id' value='<?= $segment_id ?>'>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php else: ?>
                        <div class='tree-point'>
                            <div class='point-card'>
                                <div class='point-info'>
                                    <div class='point-detail'>
                                        <div>
                                            <span class='point-icon material-symbols-rounded' style='color:#666;'><?= $segment_icon_name ?></span>
                                            <div class='point-name'>
                                                <h5 class='point-card-time' style='margin: 0 10px;'><?= $start_time ?></h5>
                                                <h5 class='point-card-name'><?= htmlspecialchars($segment_name) ?></h5>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endforeach; ?>

            <form action="index.php?plan_id=<?= $plan_id ?>" method="POST">
                <input type="text" name="memo_add" class="memo-add-form" placeholder="メモを入力">
                <input type="hidden" name="segment_id" value="<?= $edit_segment_id ?>">
                <button class="memo-add-btn" type="submit">追加</button>
            </form>
        </div>
    </div>
<?php
    }
}
?>

<!-- モーダル -->
<div class='modal-outline music-play' id='music_modal_outline' style="display:none;">
  <div class='modal-area music-modal-area'>
    <button onClick='music_modal_close()' class='model-close-btn play-model-close-btn'>
      <span class='material-symbols-rounded'>close</span>
    </button>

    <iframe class="player" id="player" width="560" height="315"
      src="" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>

    <div class="musicplayer-content">
        <div class="musicplayer-img"></div>
        <div class="music-info">
            <h2></h2>s
            <h3></h3>
        </div>
    </div>
    <div class="musicplayer-control">
        <button class="musicplayer-btn" onclick="playVideo()">
            <span class='material-symbols-rounded'>play_arrow</span>
        </button>
        <button class="musicplayer-btn" onclick="pauseVideo()">
            <span class='material-symbols-rounded'>pause</span>
        </button>
    </div>
  </div>
</div>

<script src="https://www.youtube.com/iframe_api"></script>
<script>
let player; // グローバル変数として宣言

function onYouTubeIframeAPIReady() {
    player = new YT.Player('player', {
        height: '315',
        width: '560',
        events: {
            'onReady': () => console.log('YouTubeプレイヤー準備完了')
        }
    });
}

// PHP の $music 配列を JS に渡す
const musicList = <?= json_encode($music, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;

function play_music() {
    // ランダム曲を選択
    const number = Math.floor(Math.random() * musicList.length);
    const song = musicList[number];
    
    // モーダル表示
    const music_modal = document.getElementById('music_modal_outline');
    music_modal.style.display = 'block';
    
    // 曲情報更新
    const musicImg = document.querySelector('.musicplayer-img');
    // 画像がある場合は表示、ない場合はプレースホルダー
    if (song.image_path && song.image_path !== '') {
        musicImg.innerHTML = `<img src="${song.image_path}" alt="${song.song_name}">`;
    } else {
        // 画像がない場合はアイコンを表示
        musicImg.innerHTML = `<span class='material-symbols-rounded'>music_note</span>`;
    }
    document.querySelector('.music-info h2').textContent = song.song_name || '';
    document.querySelector('.music-info h3').textContent = song.singer_name || '';
    
    // YouTube URLを埋め込み形式に変換
    let embedUrl = song.link;
    
    if (song.link.includes('watch?v=')) {
        // 通常のYouTube URL (例: https://www.youtube.com/watch?v=VIDEO_ID)
        const videoId = song.link.split('watch?v=')[1].split('&')[0];
        embedUrl = `https://www.youtube.com/embed/${videoId}?enablejsapi=1`;
    } else if (song.link.includes('youtu.be/')) {
        // 短縮URL (例: https://youtu.be/VIDEO_ID)
        const videoId = song.link.split('youtu.be/')[1].split('?')[0];
        embedUrl = `https://www.youtube.com/embed/${videoId}?enablejsapi=1`;
    } else if (!song.link.includes('enablejsapi=1')) {
        // すでに埋め込み形式だがAPIパラメータがない場合
        embedUrl = song.link + (song.link.includes('?') ? '&' : '?') + 'enablejsapi=1';
    }
    
    // iframeのsrcを設定
    const iframe = document.getElementById('player');
    iframe.src = embedUrl;
}

function music_modal_close() {
    const music_modal = document.getElementById('music_modal_outline');
    music_modal.style.display = 'none';
    
    // 動画を停止してiframeをクリア
    if (player && typeof player.stopVideo === 'function') {
        player.stopVideo();
    }
    
    const iframe = document.getElementById('player');
    iframe.src = '';
}

function playVideo() {
    if (player && typeof player.playVideo === 'function') {
        player.playVideo();
    }
}

function pauseVideo() {
    if (player && typeof player.pauseVideo === 'function') {
        player.pauseVideo();
    }
}

function modal_close(){
    const modal = document.getElementById('modal_outline');
    modal.style.display = 'none';
}
</script>

</html>