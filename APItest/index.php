<?php
// ヘッダーを設定して、ブラウザがデータを即時表示するように指示します
header('Content-Type: text/plain; charset=UTF-8');
header('Cache-Control: no-cache');
header('Connection: keep-alive');
header('X-Accel-Buffering: no'); // Nginxなどのプロキシでのバッファリングを無効化（可能な場合）

// 長時間の処理を許可し、PHPの出力をバッファリングしないように設定します
set_time_limit(0);
ini_set('output_buffering', 'off');
ini_set('zlib.output_compression', 'off');

// すべての既存の出力バッファをクリアし、フラッシュします
while (ob_get_level() > 0) {
    ob_end_clean();
}
// 一部のWebサーバー/ブラウザでストリーミングを開始するためのpadding
echo str_repeat(' ', 4096) . "\n";
flush();

/**
 * Gemini APIを呼び出し、ストリーミングで応答を処理し、画面に出力します。
 */
function generate() {
    // 1. APIキーの取得
    // 環境変数 GEMINI_API_KEY が設定されている必要があります。（Webサーバーの設定に依存します）
    $apiKey = getenv("AIzaSyDAPZGCn6Y5_jWyvb-ceUO4K66DaGltnNE");
    if (!$apiKey) {
        // HTMLに出力するため、エラーメッセージもテキストで出力
        echo "エラー: 環境変数 GEMINI_API_KEY が設定されていません。Webサーバーの設定を確認してください。\n";
        flush();
        return;
    }

    // 2. モデルとストリーミングエンドポイントの設定
    $model = "gemini-2.5-flash";
    $url = "https://generativelanguage.googleapis.com/v1/models/{$model}:generateContent?key={$apiKey}&alt=sse";

    // 3. システムインストラクションの定義 (中身は元のPythonコードの通り)
    $systemInstruction = <<<EOT
あなたは旅程を提案するAIです。以下の条件に沿って旅程を提案し【出力フォーマット】に沿った出力を行ってください。また、旅行と目的地に相性の良い曲や歌を2～5件ほど提案してください

【出力フォーマット】

[出力項目]
・旅行のタイトル（旅程に沿ったタイトル）
・旅行の概要（旅行の見どころ、条件に基づき工夫した点を含める：200文字程度）
・旅程JSON(itinerary)
　→itinerary について
	segment_type は「move」か「point」
　　segment_info は移動は「plane」「train」「boat」「car」「bus」それ以外は「move」、地点は「tourist」「station」「airport」それ以外は「point」
　　segment_name は行動の内容（移動なら区間・方法、ポイントなら具体的な目的地名）
　　start_time は移動開始や滞在開始時間、end_time は出発時刻など
　　移動のパーツには song_id を入れる（地点には不要）。選曲は目的地や旅行に合った雰囲気の曲を選んでください。
　　song_id は必ず YouTube の URL を挿入してください。
・おすすめの曲（移動パーツに挿入した曲のタイトルとURL一覧、URLは間違えなく再生できるものだけを掲載してください。大文字、小文字の違いが多いので注意してください。）

[出力形式（旅程JSON）]
出力はJSONのみとし、説明文や補足は一切出力しないでください。

{
  \"tripTitle\": \"ここに旅行のタイトル\",
  \"trip_overview\": \"旅行のみどころ\",
  \"itinerary\": [
    {
      \"segment_type\": \"move\",
      \"segment_name\": \"移動手段\",
      \"start_time\": \"2025-10-20T08:00:00\",
      \"end_time\": \"2025-10-20T10:30:00\",
      \"song_id\": \"https://www.youtube.com/watch?v=5qap5aO4i9A\"
    },
    {
      \"segment_type\": \"point\",
      \"segment_name\": \"地点名（観光地など）\",
      \"start_time\": \"2025-10-20T11:00:00\",
      \"end_time\": \"2025-10-20T13:00:00\",
      \"song_id\": null
    },
    {
      \"segment_type\": \"move\",
      \"segment_name\": \"移動手段\",
      \"start_time\": \"2025-10-20T13:00:00\",
      \"end_time\": \"2025-10-20T14:00:00\",
      \"song_id\": \"https://www.youtube.com/watch?v=abcd1234\"
    },
    {
      \"segment_type\": \"point\",
      \"segment_name\": \"地点名（食事場所など）\",
      \"start_time\": \"2025-10-20T14:15:00\",
      \"end_time\": \"2025-10-20T15:30:00\",
      \"song_id\": null
    },
    {
      \"segment_type\": \"point\",
      \"segment_name\": \"宿泊地\",
      \"start_time\": \"2025-10-20T18:00:00\",
      \"end_time\": \"2025-10-21T09:00:00\",
      \"song_id\": null
    }
  ],
  \"recommended_songs\": [
    {
      \"title\": \"Pretender - Official髭男dism\",
      \"url\": \"https://www.youtube.com/watch?v=TQ8WlA2GXbk\"
    },
    {
      \"title\": \"打上花火 - DAOKO × 米津玄師\",
      \"url\": \"https://www.youtube.com/watch?v=-tKVN2mAKRI\"
    }
  ]
}
EOT;

    // 4. リクエストボディの構築
    $requestBody = [
        "contents" => [
            [
                "role" => "user",
                "parts" => [
                    // NOTE: ここにユーザーの入力を動的に挿入する必要があります
                    ["text" => "INSERT_INPUT_HERE"], 
                ],
            ],
        ],
        "config" => [
            "temperature" => 0.3,
            "tools" => [
                [
                    "googleSearch" => (object)[], 
                ],
            ],
            "systemInstruction" => $systemInstruction,
            "thinkingConfig" => [
                "thinkingBudget" => -1,
            ],
        ],
    ];

    $jsonBody = json_encode($requestBody);

    // 5. cURLセッションの初期化とオプション設定
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonBody);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
    ]);
    // CURLOPT_RETURNTRANSFER を false に設定し、CURLOPT_WRITEFUNCTIONでストリーミング出力
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);

    // 6. ストリーミングレスポンスを処理する関数 (画面出力部分)
    curl_setopt($ch, CURLOPT_WRITEFUNCTION, function($ch, $data) {
        $lines = explode("\n", $data);
        foreach ($lines as $line) {
            // Server-Sent Events (SSE) 形式の "data: " プレフィックスを処理
            if (strpos($line, 'data: ') === 0) {
                $jsonChunk = substr($line, 6);
                
                $response = json_decode($jsonChunk, true);
                if (isset($response['candidates'][0]['content']['parts'][0]['text'])) {
                    // テキストを画面に出力
                    echo $response['candidates'][0]['content']['parts'][0]['text'];
                    // バッファをフラッシュして即座にブラウザに表示させる
                    flush(); 
                }
            }
        }
        return strlen($data); // cURLに受信バイト数を報告
    });

    // 7. cURLの実行
    $success = curl_exec($ch);

    // エラーチェック
    if ($success === false) {
        echo "\n\n--- cURL Error ---\n";
        echo "CURL/API接続エラー: " . curl_error($ch) . "\n";
        flush();
    }

    // cURLセッションの終了
    curl_close($ch);
    
    echo "\n";
    flush();
}

// 実行
generate();
?>