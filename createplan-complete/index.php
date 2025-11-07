<?php
session_start(); 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Gemini APIキーを環境変数または直接設定
$apiKey = 'AIzaSyDAPZGCn6Y5_jWyvb-ceUO4K66DaGltnNE';
$model = 'gemini-2.5-flash';

// データベース接続設定
$host = 'mysql326.phy.lolipop.lan';
$dbname = 'LAA1682282-sd3d4g';
$username = 'LAA1682282';
$password = 'Passsd3d';

//入力情報受け取り
$destination_prefecture = $_POST['destination_prefecture'];
$departure_prefecture = $_POST['departure_prefecture'];
$companion = $_POST['companion'];
$trip_start = $_POST['trip_start'];
$trip_end = $_POST['trip_end'];
$move = $_POST['move'];
if($_POST['waypoint'] == ''){
    $waypoint = 'なし';
}else{
    $waypoint = $_POST['waypoint'];
}


// システムプロンプト
$systemInstruction = <<<'EOT'
あなたは旅程を提案するAIです。以下の条件に沿って旅程を提案し【出力フォーマット】に沿った出力を行ってください。また、旅行と目的地に相性の良い曲や歌を2～5件ほど提案してください
【出力フォーマット】
[出力項目]
・旅行のタイトル(旅程に沿ったタイトル,10文字程度)
・旅行の概要(旅行の見どころ、条件に基づき工夫した点を含める:200文字程度)
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

// ユーザー入力(ここに旅行の条件を入力)
$userInput = "
「入力項目」
・出発地：$departure_prefecture
・目的地：$destination_prefecture
・人数：$companion
・出発日：$trip_start
・終了日：$trip_end
・移動手段：$move
・絶対に経由する場所：$waypoint";

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

// API エンドポイント
$url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";

// cURLでリクエスト送信
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestBody));

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// YouTube URLから動画IDを抽出する関数
function extractYoutubeId($url) {
    if (empty($url)) return null;
    parse_str(parse_url($url, PHP_URL_QUERY), $params);
    return $params['v'] ?? null;
}

// 曲名とアーティスト名を分割する関数
function parseSongTitle($title) {
    // "曲名 - アーティスト名" の形式を想定
    $parts = explode(' - ', $title, 2);
    return [
        'song_name' => trim($parts[0] ?? $title),
        'singer_name' => trim($parts[1] ?? '不明')
    ];
}

// レスポンス処理
$dbSaveResult = '';
$tripId = null;

if ($httpCode === 200) {
    $responseData = json_decode($response, true);
    
    // テキスト抽出
    $resultText = '';
    if (isset($responseData['candidates'][0]['content']['parts'])) {
        foreach ($responseData['candidates'][0]['content']['parts'] as $part) {
            if (isset($part['text'])) {
                $resultText .= $part['text'];
            }
        }
    }
    
    // JSONを抽出
    $jsonText = $resultText;
    if (preg_match('/```json\s*(.*?)\s*```/s', $resultText, $matches)) {
        $jsonText = $matches[1];
    } elseif (preg_match('/```\s*(.*?)\s*```/s', $resultText, $matches)) {
        $jsonText = $matches[1];
    }
    
    // JSONをパース
    $tripData = json_decode($jsonText, true);
    
    // データベースに保存
    if ($tripData && isset($tripData['itinerary'])) {
        try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // トランザクション開始
            $pdo->beginTransaction();
            
            // 旅行日数を計算
            $startDate = new DateTime($tripData['itinerary'][0]['start_time']);
            $endDate = new DateTime(end($tripData['itinerary'])['end_time']);
            $tripDays = $endDate->diff($startDate)->days + 1;
            
            // 目的地の都道府県IDを取得（ここでは北海道=1と仮定）
            $prefId = 1; // 実際には目的地から動的に取得すべき
            
            // 1. tripテーブルにデータを挿入
            $tripInsertSql = "INSERT INTO trip (trip_name, trip_overview, trip_start, trip_end,user_id, pref_id) 
                              VALUES (:trip_name, :trip_overview, :trip_start, :trip_end, :user_id, :pref_id)";
            $tripStmt = $pdo->prepare($tripInsertSql);
            //trip_daysをtrip_start,endに変更したため一旦コメントアウト
            $tripStmt->execute([
                ':trip_name' => $tripData['tripTitle'],
                ':trip_overview' => $tripData['trip_overview'],
                ':trip_start' => $trip_start,
                ':trip_end' => $trip_end,
                ':user_id' => $_SESSION['user_id'], // テストユーザーID
                ':pref_id' => $prefId
            ]);
            
            $tripId = $pdo->lastInsertId();
            
            // 2. songテーブルに楽曲を挿入してIDを取得
            $songMap = []; // YouTube URL => song_id のマッピング
            
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
                        ':user_id' => 11,
                        ':trip_id' => $tripId,
                        ':pref_id' => $prefId,
                        ':song_time' => 0, // 再生時間は不明なので0
                        ':image_path' => "https://img.youtube.com/vi/{$youtubeId}/hqdefault.jpg"
                    ]);
                    
                    $songMap[$song['url']] = $pdo->lastInsertId();
                }
            }
            
            // 3. まずダミー楽曲を作成（song_id=1にする）
            $checkDummySql = "SELECT song_id FROM song WHERE song_id = 1";
            $checkResult = $pdo->query($checkDummySql)->fetch();
            
            if (!$checkResult) {
                $dummySongSql = "INSERT INTO song (song_id, song_name, singer_name, link, user_id, trip_id, pref_id, song_time, image_path) 
                                 VALUES (1, '楽曲なし', '不明', '', 11, :trip_id, :pref_id, 0, '')";
                $dummySongStmt = $pdo->prepare($dummySongSql);
                $dummySongStmt->execute([
                    ':trip_id' => $tripId,
                    ':pref_id' => $prefId
                ]);
            }
            
            // 4. trip_infoテーブルにセグメントデータを挿入
            $segmentInsertSql = "INSERT INTO trip_info 
                                 (trip_id, segment_type, segment_info, segment_name, segment_detail,start_time, end_time, song_id) 
                                 VALUES (:trip_id, :segment_type, :segment_info, :segment_name, :segment_detail,:start_time, :end_time, :song_id)";
            $segmentStmt = $pdo->prepare($segmentInsertSql);
            
            $segmentTypeMap = [
                'move' => 1,
                'point' => 2
            ];
            
            foreach ($tripData['itinerary'] as $segment) {
                $songIdDb = 1; // デフォルトはダミー楽曲ID
                
                // 移動セグメントで楽曲URLがある場合、対応するsong_idを取得
                if ($segment['segment_type'] === 'move' && !empty($segment['song_id'])) {
                    $songIdDb = $songMap[$segment['song_id']] ?? 1;
                }
                
                $segmentStmt->execute([
                    ':trip_id' => $tripId,
                    ':segment_type' => $segmentTypeMap[$segment['segment_type']] ?? 2,
                    ':segment_info' => $segment['segment_info'],
                    ':segment_name' => $segment['segment_name'],
                    ':segment_detail' => $segment['segment_detail'],
                    ':start_time' => date('H:i:s', strtotime($segment['start_time'])),
                    ':end_time' => date('H:i:s', strtotime($segment['end_time'])),
                    ':song_id' => $songIdDb
                ]);
            }
            
            // コミット
            $pdo->commit();
            
            $dbSaveResult = "✅ データベースに保存完了！ (Trip ID: {$tripId}, セグメント数: " . count($tripData['itinerary']) . ", 楽曲数: " . count($songMap) . ")";
            
        } catch (PDOException $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            $dbSaveResult = "❌ DB保存エラー: " . $e->getMessage();
        }
    } else {
        $dbSaveResult = "⚠️ JSONパースに失敗しました";
    }
    
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
            text-align: center;
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
            <sction class="sm">
                <div class="page-contents">
                    <div class="page-center-content">
                        <div class="complete">
                            <p class="complete-mess">！旅程が完成しました！</p>
                            <div class="complete-card">
                                <a href="" class="plan-card main-card" style="background-image: url(../assets/img/spot_img/1.jpg);">
                                    <div class="plan-card-detail">
                                        <div>
                                            <p><?= $trip_start ?> ~ <?= $trip_end ?></p>
                                            <h2><?= $tripData['tripTitle']?></h2>
                                        </div>
                                    </div>
                                </a><!--plan-card-->
                            </div>
                            <a href="../plan-list/" class="basic-btn blue-btn">さっそく確認する</a>
                        </div>
                    </div>
                </div>
            </sction>
        </main>
    </body>
    </html>
    <?php
} else {
    echo "<!DOCTYPE html><html><body>";
    echo "<h1>エラーが発生しました</h1>";
    echo "<p>HTTPコード: {$httpCode}</p>";
    echo "<pre>" . htmlspecialchars($response, ENT_QUOTES, 'UTF-8') . "</pre>";
    echo "</body></html>";
}
?>