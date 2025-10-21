<?php
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

// システムプロンプト
$systemInstruction = <<<'EOT'
あなたは旅程を提案するAIです。以下の条件に沿って旅程を提案し【出力フォーマット】に沿った出力を行ってください。また、旅行と目的地に相性の良い曲や歌を2～5件ほど提案してください
【出力フォーマット】
[出力項目]
・旅行のタイトル(旅程に沿ったタイトル)
・旅行の概要(旅行の見どころ、条件に基づき工夫した点を含める:200文字程度)
・旅程JSON(itinerary)
　→itinerary について
	segment_type は「move」か「point」
　　segment_info は移動は「plane」「train」「boat」「car」「bus」それ以外は「move」、地点は「tourist」「station」「airport」それ以外は「point」
　　segment_name は行動の内容(移動なら区間・方法、ポイントなら具体的な目的地名)
　　start_time は移動開始や滞在開始時間、end_time は出発時刻など
　　移動のパーツには song_id を入れる(地点には不要)。選曲は目的地や旅行に合った雰囲気の曲を選んでください。
　　song_id は必ず YouTube の URL を挿入してください。
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
      "start_time": "2025-10-20T08:00:00",
      "end_time": "2025-10-20T10:30:00",
      "song_id": "https://www.youtube.com/watch?v=5qap5aO4i9A"
    },
    {
      "segment_type": "point",
      "segment_info": "tourist",
      "segment_name": "地点名(観光地など)",
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
・出発地：福岡
・目的地：北海道
・人数：2人
・日程：3日間
・移動手段：公共交通
・絶対に経由する場所：なし";

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
            $tripInsertSql = "INSERT INTO trip (trip_name, trip_overview, /*trip_days,*/ user_id, pref_id) 
                              VALUES (:trip_name, :trip_overview, /*:trip_days,*/ :user_id, :pref_id)";
            $tripStmt = $pdo->prepare($tripInsertSql);
            //trip_daysをtrip_start,endに変更したため一旦コメントアウト
            $tripStmt->execute([
                ':trip_name' => $tripData['tripTitle'],
                ':trip_overview' => $tripData['trip_overview'],
                //':trip_days' => $tripDays . '日間',
                ':user_id' => 11, // テストユーザーID
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
                                 (trip_id, segment_type, segment_info, segment_name, start_time, end_time, song_id) 
                                 VALUES (:trip_id, :segment_type, :segment_info, :segment_name, :start_time, :end_time, :song_id)";
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
        <title>旅程提案結果</title>
        <style>
            body {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                max-width: 1200px;
                margin: 0 auto;
                padding: 20px;
                background-color: #f5f5f5;
            }
            .container {
                background: white;
                padding: 30px;
                border-radius: 8px;
                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                margin-bottom: 20px;
            }
            h1 {
                color: #333;
                border-bottom: 3px solid #4CAF50;
                padding-bottom: 10px;
            }
            .status {
                padding: 15px;
                border-radius: 5px;
                margin-bottom: 20px;
                font-weight: bold;
            }
            .status.success {
                background-color: #d4edda;
                color: #155724;
                border: 1px solid #c3e6cb;
            }
            .status.error {
                background-color: #f8d7da;
                color: #721c24;
                border: 1px solid #f5c6cb;
            }
            pre {
                background-color: #f8f8f8;
                border: 1px solid #ddd;
                border-radius: 4px;
                padding: 15px;
                overflow-x: auto;
                white-space: pre-wrap;
                word-wrap: break-word;
            }
            .json-result {
                font-family: 'Courier New', monospace;
                font-size: 14px;
                line-height: 1.6;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>🗾 旅程提案結果</h1>
            
            <?php if ($dbSaveResult): ?>
            <div class="status <?php echo strpos($dbSaveResult, '✅') !== false ? 'success' : 'error'; ?>">
                <?php echo htmlspecialchars($dbSaveResult, ENT_QUOTES, 'UTF-8'); ?>
            </div>
            <?php endif; ?>
            
            <div class="json-result">
                <pre><?php echo htmlspecialchars($resultText, ENT_QUOTES, 'UTF-8'); ?></pre>
            </div>
        </div>
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