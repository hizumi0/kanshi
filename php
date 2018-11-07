<?php
2
$url = 'http://xxxxxx.ethosdistro.com/'; //ethOSステータスパネルURL
3
$email_to = 'test@example.com'; //メール通知先アドレス
4
$email_from = 'ethos@hyperbanana.net'; //送信元アドレス
5
 
6
/* JSONデータ取得 */
7
$json = file_get_contents($url . '?json=yes');
8
 
9
/* 文字化け防止(UTF8に変換) */
10
$json = mb_convert_encoding($json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
11
 
12
/* JSONデータを連想配列にする */
13
$arr = json_decode($json,true);
14
 
15
$alive_gpus = $arr['alive_gpus'];
16
$total_gpus = $arr['total_gpus'];
17
$alive_rigs = $arr['alive_rigs'];
18
$total_rigs = $arr['total_rigs'];
19
$total_hash = $arr['total_hash'];
20
 
21
echo 'alive_gpus:' . $alive_gpus . '<br>';
22
echo 'total_gpus:' . $total_gpus . '<br>';
23
echo 'alive_rigs:' . $alive_rigs . '<br>';
24
echo 'total_rigs:' . $total_rigs . '<br>';
25
echo 'total_hash:' . $total_hash . '<br>';
26
echo '<br>';
27
 
28
for ($i = 0; $i < $total_rigs; $i++) {
29
 $rig_name = key(array_slice($arr['rigs'], $i, 1, true));
30
 $rig_worker = $arr['rigs'][$rig_name]['rack_loc'];
31
 $rig_condition = $arr['rigs'][$rig_name]['condition'];
32
 
33
 echo $rig_worker . '[' . $rig_name . ']: ' . $rig_condition;
34
 if ($rig_condition == 'mining' || $rig_condition =='throttle' || $rig_condition =='unreachable' ){
35
 //echo ' RIG異常なし';
36
 echo '<br>';
37
 } else {
38
 //echo ' RIG異常あり';
39
 echo '<br>';
40
 
41
 //メール通知
42
 mb_language("Japanese");
43
 mb_internal_encoding("UTF-8");
44
  
45
 $to = $email_to;
46
 $subject = 'ethOS RIGダウン通知';
47
 $message = $rig_worker . 'の状態が変化しました。' . "\r\n" . '状態：' . $rig_condition . "\r\n" . $url;
48
 $headers = 'From: ' . $email_from . "\r\n";
49
 mb_send_mail($to, $subject, $message, $headers);
50
 }
51
}
52
?>
