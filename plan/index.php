<?php
    session_start();    
    if(isset($_SESSION['user_id'])){
        $user_id = $_SESSION['user_id'];
        $user_name = $_SESSION['user_name'];
    }

    //plan_idでDBから引っ張る
    $plan_id = $_GET['plan_id'];

    //DB接続情報
    $host = 'mysql326.phy.lolipop.lan';
	$dbname = 'LAA1682282-sd3d4g';
    $user = 'LAA1682282';
    $pass = 'Passsd3d';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "SELECT * FROM `trip` WHERE `trip_id` = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$plan_id]);
        $trips = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $parts_sql = "SELECT * FROM `trip_info` WHERE `trip_id` = ? ORDER BY `trip_info`.`segment_id` ASC";
        $stmt = $pdo->prepare($parts_sql);
        $stmt->execute([$plan_id]);
        $parts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        die("データベースエラー: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
    }

    
    foreach($trips as $trip_info){
        
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
            <div class="plan-hero" style="background-image: url(../assets/img/spot_img/1.jpg);">
                <div class="trip-title">
                    <!-- <div class="for-user">
                        <p><?= $user_name ?>さんにぴったりの旅程を作成しました。</p>
                    </div> -->
                    <div>
                        <h1><?=$trip_info['trip_name']?></h1>
                        <h5><?=$trip_info['trip_start']?>～<?=$trip_info['trip_end']?></h5>
                    </div>
                </div>
            </div>
            <div class="page-contents">
                <div class="plan-tree">

                    <!-- <div class="tree-move">
                        <div class="move-line"></div>
                        <div class="move-info">
                            <div class="move-detail">
                                <span class="move-icon material-symbols-rounded">travel</span>
                                <p>移動名</p>
                            </div>
                        </div>
                    </div>move -->
                    <!-- <div class="tree-point">
                        <div class="point-card">
                            <div class="point-info">
                                <div class="point-detail">
                                    <span class="move-icon material-symbols-rounded">distance</span>
                                    <div class="point-name">
                                        <h5>time</h5>
                                        <h5>aaaa</h5>
                                        <p>dsdadadsada</p>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>point -->
                    <?php
                        foreach($parts as $parts_tree){
                            $segment_id = $parts_tree['segment_id'];
                            $segment_type = $parts_tree['segment_type'];
                            $segment_name = $parts_tree['segment_name'];
                            $time = $parts_tree['start_time'];
                            $start_time = date("H:i", strtotime($time));
                            $segment_info = $parts_tree['segment_info'];
                        

                    
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
                                                <p>{$segment_name}</p>
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
                                                            <h4 class='point-card-name-tourist'>{$segment_name}</h4>
                                                            
                                                        </div>
                                                    </div>
                                                    <div class='tourist-detail'>
                                                        <p>{$segment_detail}</p>
                                                    </div>
                                                    <form action='#' method='POST'><input type='hidden' name='edit_segment_id' value='{$segment_id}'><button class='plan-edit-btn plan-edit-btn-tourist'><span class='material-symbols-rounded'>edit_location_alt</span></button></form>
                                                </div>
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
                                                            <h5 class='point-card-name'>{$segment_name}</h5>
                                                        </div>
                                                    </div>
                                                <form action='#' method='POST'><input type='hidden' name='edit_segment_id' value='{$segment_id}'><button class='plan-edit-btn plan-edit-btn-tourist'><span class='material-symbols-rounded'>edit_location_alt</span></button></form>
                                            </div>
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
                        <p><?=$trip_info['trip_name']?>のプレイリスト</p>
                    </div>
                    <?php
                        $sql = "SELECT * FROM `song` WHERE `trip_id` = ? ORDER BY `trip_id` DESC";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute([$plan_id]);
                        $music = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        foreach($music as $row){
                            $singer_name = $row['singer_name'];
                            $song_name = $row['song_name'];
                            $music_url = $row['link'];
                            echo <<<HTML
                                <div class="planpage-music-list-card">
                                    <div>
                                        <p class="music-name">{$song_name}</p>
                                        <p class="music-aname">{$singer_name}</p>
                                    </div>
                                    <div>
                                        <button><span class='music-list-icon material-symbols-rounded'>favorite</span></button>
                                        <button class="music-play-btn" data-url="{$music_url}">
                                            <span class='music-list-icon material-symbols-rounded' style="color: #7968FF;">play_circle</span>
                                        </button>
                                    </div>
                                </div><!--music-list-->
                            HTML;
                        }
                    ?>
                </div>
                <div class="plan-feedback">
                    <div class="feedback-title">
                        <h3>提案された旅程はいかがでしたか？</h3>
                        <p>「良くない」「非常に悪い」選択すると提案は<br>要望に沿って再生成されます</p>
                    </div>
                    <form action="#">
                        <div class="feedback-btn-list">
                            <input type="radio" name="feedback" id="option1" class="feedback-radio" style="display: none;">
                            <label class="feedback-level level-good" for="option1">
                                <div><span class='point-icon material-symbols-rounded'>mood</span><p>良い</p></div>
                            </label>

                            <input type="radio" name="feedback" id="option2" class="feedback-radio" style="display: none;">
                            <label class="feedback-level level-bad" for="option2">
                                <div><span class='point-icon material-symbols-rounded'>sentiment_dissatisfied</span><p>良くない</p></div>
                            </label>

                            <input type="radio" name="feedback" id="option3" class="feedback-radio" style="display: none;">
                            <label class="feedback-level level-verybad" for="option3">
                                <div><span class='point-icon material-symbols-rounded'>sentiment_extremely_dissatisfied</span><p>非常に悪い</p></div>
                            </label>
                        </div>
                        <input type="text" name="" class="feedback-text" placeholder="改善してほしい箇所、要望を具体的に入力してください">
                        <button type="submit" class="basic-btn blue-btn">再生成</button>
                    </form>
                </div>
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

    foreach ($segment as $row) {
        $edit_segment_name = $row['segment_name'];
    }
    ?>
    <!-- HTML部分ここから -->
    <div class='modal-outline' id='modal_outline'>
        <div class='modal-area'>
            <button onClick='modal_close()' class='model-close-btn'>
                <span class='material-symbols-rounded'>close</span>
            </button>
            <div class='edit-modal-title'>
                <span class='material-symbols-rounded'>edit_location_alt</span>
                <h3>選択された箇所の<br>旅程を変更します</h3>
            </div>

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

            <form action='#' method='POST'>
                <input type='text' name='update_segment_prompt' class='feedback-text' placeholder='改善してほしい箇所、要望を具体的に入力してください'>
                <button type='submit' class='basic-btn blue-btn'>再生成</button>
            </form>
        </div>
    </div>
<?php
}
?>

<?php
$music_url = 'https://www.youtube.com/watch?v=TQ8WlA2GXbk' ?? ''; 

if (strpos($music_url, 'watch?v=') !== false) {
    $music_embed_url = str_replace('watch?v=', 'embed/', $music_url) . '?enablejsapi=1';
} elseif (strpos($music_url, 'youtu.be/') !== false) {
    $music_embed_url = str_replace('youtu.be/', 'www.youtube.com/embed/', $music_url) . '?enablejsapi=1';
} else {
    $music_embed_url = $music_url;
}
?>


<div class='modal-outline music-play' id='music_modal_outline'>
  <div class='modal-area music-modal-area'>
    <button onClick='music_modal_close()' class='model-close-btn play-model-close-btn'>
      <span class='material-symbols-rounded'>close</span>
    </button>

    <iframe id="player" width="100%" height=""
      src="<?= $music_embed_url ?>"
      frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>

    <button onclick="playVideo()">▶ 再生</button>
    <button onclick="pauseVideo()">⏸ 停止</button>
  </div>
</div>

<script src="https://www.youtube.com/iframe_api"></script>
<script>
  let player;

  function onYouTubeIframeAPIReady() {
    player = new YT.Player('player', {
      events: {
        'onReady': () => console.log('YouTubeプレイヤー準備完了')
      }
    });
  }

  function play_music() {
    const music_modal = document.getElementById('music_modal_outline');
    music_modal.style.display = 'block';
    if (player && typeof player.playVideo === 'function') {
      player.playVideo();
    } else {
      console.log('プレイヤー未準備。');
    }
  }

  function music_modal_close() {
    const modal = document.getElementById('music_modal_outline');
    modal.style.display = 'none';
    if (player && typeof player.pauseVideo === 'function') {
      player.pauseVideo();
    }
  }

  function playVideo() {
    if (player && typeof player.playVideo === 'function') player.playVideo();
  }

  function pauseVideo() {
    if (player && typeof player.pauseVideo === 'function') player.pauseVideo();
  }

  function modal_close(){
    const modal = document.getElementById('modal_outline');
    modal.style.display = 'none';
  }
</script>


</html>