<?php


$api_key = htmlentities($_POST['api-key']);
$channel_id = htmlentities($_POST['channel_id']);
//$since_id = htmlentities($_POST['since_id']);
$page_size = htmlentities($_POST['page_size']);
$page = htmlentities($_POST['page']);

function prepare_orders($response) {
    $orders = $response;
//    foreach ($products as $index => $product) {
//        $products[$index] = array_merge([
//            'infoUrl' => '#'
//        ], $product);
//    }
    return $orders;
}

function http_parse_headers($header) {
    $retVal = array();
    $fields = explode("\r\n", preg_replace('/\x0D\x0A[\x09\x20]+/', ' ', $header));
    foreach( $fields as $field ) {
        if( preg_match('/([^:]+): (.+)/m', $field, $match) ) {
            $match[1] = preg_replace_callback('/(?<=^|[\x09\x20\x2D])./e', 'strtoupper("\0")', strtolower(trim($match[1])));
            if( isset($retVal[$match[1]]) ) {
                $retVal[$match[1]] = array($retVal[$match[1]], $match[2]);
            } else {
                $retVal[$match[1]] = trim($match[2]);
            }
        }
    }
    return $retVal;
}

// CURL Request for Channel Name

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.veeqo.com/channels/$channel_id");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);

curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "Content-Type: application/json",
    "x-api-key: $api_key"
));
$channelResponse = curl_exec($ch);

curl_close($ch);


// CURL Request for orders

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "https://api.veeqo.com/orders?channel_ids=".$channel_id."&status=shipped&page_size=".$page_size."&page=".$page);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, TRUE);

curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "Content-Type: application/json",
    "x-api-key: $api_key"
));

$response = curl_exec($ch);
$responseSize = curl_getinfo($ch, CURLINFO_SIZE_DOWNLOAD);
$time = curl_getinfo($ch, CURLINFO_TOTAL_TIME);
$responseCode = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$headers = substr($response, 0, $header_size);
$body = substr($response, $header_size);

$err = curl_error($ch);

curl_close($ch);

$channel = json_decode($channelResponse, true);
$response = json_decode($response, true);
$body = json_decode($body,true);
$headers_arr = http_parse_headers($headers);

echo ("\n\nHeaders: ".$headers);
echo ("\n\nheaders_arr: ".$headers_arr['9']);
//echo ("\n\nBody[0][sellables][0][product_title]: ".$body[0]['sellables'][0]['product_title']);
//echo ("\n\nBody[0][sellables][0][stock_entries][0][warehouse_id]: ".$body[0]['sellables'][0]['stock_entries'][0]['warehouse_id']);
echo ("\n\nX-Total-Count: ".$headers_arr['X-Total-Count']);

$results = [
    'orders' => [],
    'error' => false,
    'time' => $time,
    'responseSize' => $responseSize,
    'responseCode' => $responseCode
];




// Error Handling
// TODO refactor this mess

if ($channel_id == null){
    if ($responseCode == '200'){
        $results = [
            'error' => "No Channel ID",
            'orders' => []
        ];
    } else {
        $results = [
            'error' => "API error: " .$responseCode." ". $body['error_messages'],
            'orders' => []
        ];
    }
} elseif ($err) {
    $results['error'] = "cURL Error #:" . $err ;
} elseif(isset($body['error_messages'])) {
    $results['error'] = "API error: " .$responseCode." ". $body['error_messages'];
} elseif($responseCode != '200'){
    $results['error'] = "API error: " .$responseCode." ". $body['error_messages'];
} else {
    $results['orders'] = prepare_orders($body);
}

return $results;