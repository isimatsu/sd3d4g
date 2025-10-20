<?php
// Gemini APIキーを環境変数または直接設定
$apiKey = 'AIzaSyDAPZGCn6Y5_jWyvb-ceUO4K66DaGltnNE';
$model = 'gemini-2.5-flash';

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
      "segment_name": "移動手段",
      "start_time": "2025-10-20T08:00:00",
      "end_time": "2025-10-20T10:30:00",
      "song_id": "https://www.youtube.com/watch?v=5qap5aO4i9A"
    },
    {
      "segment_type": "point",
      "segment_name": "地点名(観光地など)",
      "start_time": "2025-10-20T11:00:00",
      "end_time": "2025-10-20T13:00:00",
      "song_id": null
    },
    {
      "segment_type": "move",
      "segment_name": "移動手段",
      "start_time": "2025-10-20T13:00:00",
      "end_time": "2025-10-20T14:00:00",
      "song_id": "https://www.youtube.com/watch?v=abcd1234"
    },
    {
      "segment_type": "point",
      "segment_name": "地点名(食事場所など)",
      "start_time": "2025-10-20T14:15:00",
      "end_time": "2025-10-20T15:30:00",
      "song_id": null
    },
    {
      "segment_type": "point",
      "segment_name": "宿泊地",
      "start_time": "2025-10-20T18:00:00",
      "end_time": "2025-10-21T09:00:00",
      "song_id": null
    }
  ],
  "recommended_songs": [
    {
      "title": "Pretender - Official髭男dism",
      "url": "https://www.youtube.com/watch?v=TQ8WlA2GXbk"
    },
    {
      "title": "打上花火 - DAOKO × 米津玄師",
      "url": "https://www.youtube.com/watch?v=-tKVN2mAKRI"
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

// レスポンス処理
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
            }
            h1 {
                color: #333;
                border-bottom: 3px solid #4CAF50;
                padding-bottom: 10px;
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