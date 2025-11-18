<?php
session_start(); 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


// タイムアウトを延長
set_time_limit(300); // 5分

// ログファイルに出力（デバッグ用）
function debugLog($message) {
    error_log(date('[Y-m-d H:i:s] ') . $message);
}

debugLog("=== 処理開始 ===");

// Gemini APIキーを環境変数または直接設定
$apiKey = 'AIzaSyDAPZGCn6Y5_jWyvb-ceUO4K66DaGltnNE';
$model = 'gemini-2.5-flash';

// データベース接続設定
$host = 'mysql326.phy.lolipop.lan';
$dbname = 'LAA1682282-sd3d4g';
$username = 'LAA1682282';
$password = 'Passsd3d';

//入力情報受け取り
if(isset($_POST['seiti'])){
    $seiti = $_POST['seiti'] ?? '';
    echo $seiti;
}

$destination_prefecture = $_POST['destination_prefecture'] ?? '';
$departure_prefecture = $_POST['departure_prefecture'] ?? '';
$companion = $_POST['companion'] ?? '';
$trip_start = $_POST['trip_start'] ?? '';
$trip_end = $_POST['trip_end'] ?? '';
$move = $_POST['move'] ?? '';
$budget = $_POST['budget'] ?? '上限なし';
$special_requests = $_POST['special_requests'] ?? '';
$waypoint = empty($_POST['waypoint']) ? 'なし' : $_POST['waypoint'];



// システムプロンプト
$systemInstruction = <<<'EOT'
あなたは旅程を提案するAIです。以下の条件に沿って旅程を提案し【出力フォーマット】に沿った出力を行ってください。また、旅行と目的地に相性の良い曲や歌を2～5件ほど提案してください
【出力フォーマット】
[出力項目]
・旅行のタイトル(旅程に沿ったタイトル,10文字程度)
・旅行の概要(旅行の見どころ、条件に基づき工夫した点を含める:70文字程度)
・旅程JSON(itinerary)
　→itinerary について
	segment_type は「move」か「point」
　　segment_info は移動は「plane」「train」「boat」「car」「bus」「walking」それ以外は「move」、地点は「tourist」「station」「airport_takeoff」「airport_land」「hotel」それ以外は「point」
　　segment_name は行動の内容(移動なら区間・方法、ポイントなら具体的な目的地名)
	segment_detailは「point」の「tourist」にのみ観光地の見どころなどを100文字程度で出力してください。
　　start_time は移動開始や滞在開始時間、end_time は出発時刻など
　　移動のパーツには song_id を入れる(地点には不要)。選曲は目的地や旅行に合った雰囲気の曲を選んでください。
　　song_id は必ず YouTube の URL を挿入してください。
・旅程は必ずpoint→move→pointの順、point、moveの比率は1:1が理想（目安）。はじめは（出発地）必ずpointから始めます。出発地は入力項目の「出発地」から（出発地が大雑把な場合その周辺の代表地点を採用すること
・入力項目「移動手段」について移動手段は「車」「公共交通」がありますが。あくまでも旅行先での移動手段であって出発地から目的地が離れている場合は飛行機や新幹線の提案を優先してください。
・特別なリクエストはユーザーが自由に入力できる条件です。その指示に従って提案してください。
・「旅行予算」が設定されている場合、それを超えないようなプランを作成してください。
・おすすめの曲(移動パーツに挿入した曲のタイトルとURL一覧、URLは間違えなく再生できるものだけを掲載してください。大文字、小文字の違いが多いので注意してください。)
[出力形式(旅程JSON)]
出力はJSONのみとし、説明文や補足は一切出力しないでください。
{
  "tripTitle": "ここに旅行のタイトル",
  "trip_overview": "旅行のみどころ",
  "itinerary": [
    {
      "segment_type": "move",
      "segment_info": "plane",
      "segment_name": "移動手段",
　  "segment_detail": null,
      "start_time": "2025-10-20T08:00:00",
      "end_time": "2025-10-20T10:30:00",
      "song_id": "https://www.youtube.com/watch?v=5qap5aO4i9A"
    },
    {
      "segment_type": "point",
      "segment_info": "tourist",
      "segment_name": "地点名(観光地など)",
      "segment_detail": "（見どころを100文字程度で）",
      "start_time": "2025-10-20T11:00:00",
      "end_time": "2025-10-20T13:00:00",
      "song_id": null
    }
  ],
  "recommended_songs": [
    {
      "title": "Pretender - Official髭男dism",
      "url": "https://www.youtube.com/watch?v=TQ8WlA2GXbk"
    }
  ]
}
EOT;

// ユーザー入力
$userInput = "
「入力項目」
・出発地：$departure_prefecture
・目的地：$destination_prefecture
・人数：$companion
・出発日：$trip_start
・終了日：$trip_end
・移動手段：$move
・旅行予算：$budget
・絶対に経由する場所：$waypoint
・特別なリクエスト：$special_requests";

// リクエストボディの作成
$requestBody = [
    'contents' => [
        [
            'role' => 'user',
            'parts' => [
                ['text' => $userInput]
            ]
        ]
    ],
    'systemInstruction' => [
        'parts' => [
            ['text' => $systemInstruction]
        ]
    ],
    'generationConfig' => [
        'temperature' => 0.3,
    ],
    'tools' => [
        [
            'googleSearch' => new stdClass()
        ]
    ]
];

debugLog("=== API呼び出し開始 ===");

// API エンドポイント
$url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";

// cURLでリクエスト送信
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 120); // 2分のタイムアウト
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30); // 接続タイムアウト30秒
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestBody));

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

// cURLエラーチェック
if (curl_errno($ch)) {
    $error = curl_error($ch);
    curl_close($ch);
    debugLog("cURLエラー: $error");
    die("通信エラーが発生しました: $error<br><a href='../createplan/'>戻る</a>");
}

curl_close($ch);

debugLog("API応答受信 (HTTP: $httpCode, サイズ: " . strlen($response) . "bytes)");

// YouTube URLから動画IDを抽出する関数
function extractYoutubeId($url) {
    if (empty($url)) return null;
    $parsed = parse_url($url);
    if (isset($parsed['query'])) {
        parse_str($parsed['query'], $params);
        return $params['v'] ?? null;
    }
    return null;
}

// 曲名とアーティスト名を分割する関数
function parseSongTitle($title) {
    $parts = explode(' - ', $title, 2);
    return [
        'song_name' => trim($parts[0] ?? $title),
        'singer_name' => trim($parts[1] ?? '不明')
    ];
}

// レスポンス処理
$dbSaveResult = '';
$tripId = null;
$tripData = null;

if ($httpCode === 200) {
    debugLog("=== JSON抽出開始 ===");
    
    $responseData = json_decode($response, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        debugLog("JSONデコードエラー: " . json_last_error_msg());
        die("APIレスポンスの解析に失敗しました<br><a href='../createplan/'>戻る</a>");
    }
    
    // テキスト抽出
    $resultText = '';
    if (isset($responseData['candidates'][0]['content']['parts'])) {
        foreach ($responseData['candidates'][0]['content']['parts'] as $part) {
            if (isset($part['text'])) {
                $resultText .= $part['text'];
            }
        }
    }
    
    debugLog("抽出テキスト長: " . strlen($resultText));
    
    // JSONを抽出
    $jsonText = $resultText;
    if (preg_match('/```json\s*(.*?)\s*```/s', $resultText, $matches)) {
        $jsonText = $matches[1];
        debugLog("JSONコードブロックを検出");
    } elseif (preg_match('/```\s*(.*?)\s*```/s', $resultText, $matches)) {
        $jsonText = $matches[1];
        debugLog("コードブロックを検出");
    }
    
    // JSONをパース
    $tripData = json_decode($jsonText, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        debugLog("旅程JSONパースエラー: " . json_last_error_msg());
        debugLog("JSON内容(最初の500文字): " . substr($jsonText, 0, 500));
        die("旅程データの解析に失敗しました<br><a href='../createplan/'>戻る</a>");
    }
    
    debugLog("JSON解析成功");
    
    // データベースに保存
    if ($tripData && isset($tripData['itinerary'])) {
        debugLog("=== DB保存開始 ===");
        
        try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            debugLog("DB接続成功");
            
            // トランザクション開始
            $pdo->beginTransaction();

            
            $prefstmt=$pdo->prepare("SELECT pref_id FROM pref WHERE pref_name=?");
            $prefstmt->execute([$destination_prefecture]);
            $row=$prefstmt->fetch(PDO::FETCH_ASSOC);

            echo "DEBUG row = ";
            var_dump($row);
            echo "<br>";

            if ($row && isset($row['pref_id'])) {
                $pref_id = $row['pref_id'];
            } else {
                echo"❌ エラー: 都道府県 '{$destination_prefecture}' が pref テーブルに存在しません。";
                exit;
            }

            
            // 1. tripテーブルにデータを挿入
            $tripInsertSql = "INSERT INTO trip (trip_name, trip_overview, trip_start, trip_end, user_id, pref_id) 
                              VALUES (:trip_name, :trip_overview, :trip_start, :trip_end, :user_id, :pref_id)";
            $tripStmt = $pdo->prepare($tripInsertSql);
            $tripStmt->execute([
                ':trip_name' => $tripData['tripTitle'],
                ':trip_overview' => $tripData['trip_overview'],
                ':trip_start' => $trip_start,
                ':trip_end' => $trip_end,
                ':user_id' => $_SESSION['user_id'],
                ':pref_id' => $pref_id
            ]);
            
            $tripId = $pdo->lastInsertId();
            debugLog("Trip挿入完了 (ID: $tripId)");
            
            // 2. songテーブルに楽曲を挿入
            $songMap = [];
            
            if (isset($tripData['recommended_songs'])) {
                $songInsertSql = "INSERT INTO song (song_name, singer_name, link, user_id, trip_id, pref_id, song_time, image_path) 
                                  VALUES (:song_name, :singer_name, :link, :user_id, :trip_id, :pref_id, :song_time, :image_path)";
                $songStmt = $pdo->prepare($songInsertSql);
                
                foreach ($tripData['recommended_songs'] as $song) {
                    $parsed = parseSongTitle($song['title']);
                    $youtubeId = extractYoutubeId($song['url']);
                    
                    $songStmt->execute([
                        ':song_name' => $parsed['song_name'],
                        ':singer_name' => $parsed['singer_name'],
                        ':link' => $song['url'],
                        ':user_id' => $_SESSION['user_id'] ?? 11,
                        ':trip_id' => $tripId,
                        ':pref_id' => $pref_id,
                        ':song_time' => 0,
                        ':image_path' => $youtubeId ? "https://img.youtube.com/vi/{$youtubeId}/hqdefault.jpg" : ''
                    ]);
                    
                    $songMap[$song['url']] = $pdo->lastInsertId();
                }
                
                debugLog("楽曲挿入完了 (" . count($songMap) . "件)");
            }
            
            // 3. ダミー楽曲を確認・作成
            $dummySongId = null;
            $checkDummySql = "SELECT song_id FROM song WHERE song_name = '楽曲なし' AND trip_id = :trip_id LIMIT 1";
            $checkStmt = $pdo->prepare($checkDummySql);
            $checkStmt->execute([':trip_id' => $tripId]);
            $dummyResult = $checkStmt->fetch();
            
            if ($dummyResult) {
                $dummySongId = $dummyResult['song_id'];
                debugLog("既存のダミー楽曲を使用 (ID: $dummySongId)");
            } else {
                $dummySongSql = "INSERT INTO song (song_name, singer_name, link, user_id, trip_id, pref_id, song_time, image_path) 
                                 VALUES ('楽曲なし', '不明', '', :user_id, :trip_id, :pref_id, 0, '')";
                $dummySongStmt = $pdo->prepare($dummySongSql);
                $dummySongStmt->execute([
                    ':user_id' => $_SESSION['user_id'] ?? 11,
                    ':trip_id' => $tripId,
                    ':pref_id' => $pref_id
                ]);
                $dummySongId = $pdo->lastInsertId();
                debugLog("ダミー楽曲を作成 (ID: $dummySongId)");
            }
            
            // 4. trip_infoテーブルにセグメントデータを挿入
            $segmentInsertSql = "INSERT INTO trip_info 
                                 (trip_id, segment_type, segment_info, segment_name, segment_detail, start_time, end_time, song_id) 
                                 VALUES (:trip_id, :segment_type, :segment_info, :segment_name, :segment_detail, :start_time, :end_time, :song_id)";
            $segmentStmt = $pdo->prepare($segmentInsertSql);
            
            $segmentTypeMap = [
                'move' => 1,
                'point' => 2
            ];
            
            $segmentCount = 0;
            foreach ($tripData['itinerary'] as $index => $segment) {
                $songIdDb = $dummySongId;
                
                if ($segment['segment_type'] === 'move' && !empty($segment['song_id'])) {
                    $songIdDb = $songMap[$segment['song_id']] ?? $dummySongId;
                }
                
                $segmentStmt->execute([
                    ':trip_id' => $tripId,
                    ':segment_type' => $segmentTypeMap[$segment['segment_type']] ?? 2,
                    ':segment_info' => $segment['segment_info'],
                    ':segment_name' => $segment['segment_name'],
                    ':segment_detail' => $segment['segment_detail'] ?? null,
                    ':start_time' => date('H:i:s', strtotime($segment['start_time'])),
                    ':end_time' => date('H:i:s', strtotime($segment['end_time'])),
                    ':song_id' => $songIdDb
                ]);
                $segmentCount++;
            }
            
            debugLog("セグメント挿入完了 ($segmentCount 件)");
            
            // コミット
            $pdo->commit();
            debugLog("=== DB保存完了 ===");
            
            $dbSaveResult = "✅ データベースに保存完了！";
            
        } catch (PDOException $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            debugLog("DB保存エラー: " . $e->getMessage());
            debugLog("スタックトレース: " . $e->getTraceAsString());
            $dbSaveResult = "❌ DB保存エラー: " . $e->getMessage();
            die($dbSaveResult . "<br><a href='../createplan/'>戻る</a>");
        }
    } else {
        debugLog("旅程データが不正: " . print_r($tripData, true));
        $dbSaveResult = "⚠️ JSONパースに失敗しました";
        die($dbSaveResult . "<br><a href='../createplan/'>戻る</a>");
    }
    
    debugLog("=== HTML出力開始 ===");
    
    // HTML表示
    ?>
    <!DOCTYPE html>
    <html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="../assets/css/reset.css">
        <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
        <link rel="stylesheet" type="text/css" href="../assets/css/index.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100..900&display=swap" rel="stylesheet">
        <title>旅程作成完了 -旅行提案アプリ-</title>
    </head>
    <style>
        .complete{
            display: block;
        }
        .complete-card{
            display: flex;
            justify-content: center;
            text-align: center;
            margin-bottom: 20px;
        }
        .plan-card{
            width: 200px;
        }
        .complete-mess{
            font-size: 25px;
            font-weight: bold;
            margin-bottom: 20px;
        }
    </style>
    <body>
        <main>
            <section class="sm">
                <div class="page-contents">
                    <div class="page-center-content">
                        <div class="complete">
                            <p class="complete-mess">旅程が完成しました</p>
                            <div class="complete-card">
                                <a href="../plan-list/?plan_id=<?= $tripId ?>" class="plan-card main-card" style="background-image: url(../assets/img/spot_img/<?= $pref_id ?>.png);">
                                    <div class="plan-card-detail">
                                        <div>
                                            <p><?= htmlspecialchars($trip_start) ?> ~ <?= htmlspecialchars($trip_end) ?></p>
                                            <h2><?= htmlspecialchars($tripData['tripTitle'] ?? 'タイトル未設定') ?></h2>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <form action="../plan/?plan_id=<?= $tripId ?>" method="post">
                                <input type="hidden" name="test" value="<?php $tripId?>">
                                <input type="hidden" name="destination_prefecture" value="<?= $destination_prefecture?>">
                                <input type="hidden" name="departure_prefecture" value="<?= $departure_prefecture?>">
                                <input type="hidden" name="companion" value="<?= $companion?>">
                                <input type="hidden" name="trip_start" value="<?= $trip_start?>">
                                <input type="hidden" name="trip_end" value="<?= $trip_end?>">
                                <input type="hidden" name="move" value="<?= $move?>">
                                <input type="hidden" name="special_requests" value="<?= $special_requests?>">
                                <input type="hidden" name="waypoint" value="<?= $waypoint?>">
                                <button class="basic-btn blue-btn">さっそく確認する</button>
                            </form>
                            

                        </div>
                    </div>
                </div>
            </section>
        </main>
    </body>
    </html>
    <?php
    debugLog("=== 処理完了 ===");
} else {
    debugLog("APIエラー HTTP: $httpCode");
    debugLog("レスポンス: " . substr($response, 0, 500));
    echo "<!DOCTYPE html><html><body>";
    echo "<h1>エラーが発生しました</h1>";
    echo "<p>HTTPコード: {$httpCode}</p>";
    echo "<h3>APIからのレスポンス:</h3>";
    echo "<pre>" . htmlspecialchars($response, ENT_QUOTES, 'UTF-8') . "</pre>";
    echo "<a href='../createplan/' style='display:inline-block; margin-top:20px; padding:10px 20px; background-color:#007bff; color:white; text-decoration:none; border-radius:5px;'>戻る</a>";
    echo "</body></html>";
}
?>