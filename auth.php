<?php
$valid_token = 'af7e273a05daa19df42754e13ba90353a983bcf7c0099e5bd0e53dc136d8761b';

// ▼ サーバー名の種別（-MT5 3 など）を削ってブローカー名だけ抽出
function extractBrokerBase($fullBrokerName) {
    return preg_replace('/[-\s].*$/', '', $fullBrokerName); // 例: XMTrading-MT5 3 → XMTrading
}

$allowed_accounts = [
   'XMTrading' => [
       '75286119',
       '72309887',
       '75338588', //例
       '75338588', //ここ以降利用者
       '75338588'
   ],
   'HFMarketsGlobal' => [
       '87450787',
       '87654321', //例
       '87654321', //ここ以降利用者
       '87654321'
   ]
];

// トークン取得
$headers = function_exists('getallheaders') ? getallheaders() : [];
$bearer_token = '';
if (isset($headers['Authorization']) && stripos($headers['Authorization'], 'Bearer ') === 0) {
    $bearer_token = substr($headers['Authorization'], 7);
}
$get_token = isset($_GET['token']) ? trim($_GET['token']) : '';
$token = $bearer_token ?: $get_token;

// トークンチェック
if ($token !== $valid_token) {
    echo 'INVALID_TOKEN';
    exit;
}

// ブローカー・口座チェック
$broker_full = isset($_GET['broker']) ? trim($_GET['broker']) : '';
$account     = isset($_GET['account']) ? trim($_GET['account']) : '';

$broker = extractBrokerBase($broker_full);

if (isset($allowed_accounts[$broker]) && in_array($account, $allowed_accounts[$broker])) {
    echo 'OK';
} else {
    echo 'INVALID_ACCOUNT';
}
