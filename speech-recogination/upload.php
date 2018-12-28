<?php
spl_autoload_register(function ($class) {
	include("./SDK/{$class}.php");
});

//请在此填入AppID与AppKey
$app_id = '21103862**';
$app_key = '7kywTjWd30fl51**';

Configer::setAppInfo($app_id, $app_key);

function saveData($data) {
	$dirname = 'mp3';
	if(!is_dir($dirname)){
		$res = mkdir($dirname,0777); 
	}
	$decodedData = base64_decode($data);
	// print out the raw data, 
	//echo ($decodedData);
	$filename = 'mp3_' . date( 'Y-m-d-H-i-s' ) .'.mp3';
	// write the data out to the file
	$fp = fopen($dirname . '/'.$filename, 'wb');
	fwrite($fp, $decodedData);
	fclose($fp);
}

// pull the raw binary data from the POST array
$data = substr($_POST['data'], strpos($_POST['data'], ",") + 1);

// 开启本地测试
// saveData($data);
// $data = file_get_contents('./mp3/mp3_2018-12-28-05-43-21.mp3');

$params = array(
    'speech'    => $data,
    'format'    => 8,     //MP3
    'rate'      => 16000, //16KHz
    'bits'      => 16,    //16位采样
    'speech_id' => "{$app_id}_" . md5(time()),
);
$response = API::wxasrs($params);
$response = json_decode($response);
var_dump($response);
if (!empty($response->data->speech_text)) {
	$stream = fopen("log.txt", "a+");
	$size = strlen($speech_data);
	fwrite($stream, "[{$size}] {$response->data->speech_text} \r\n");
}
?>
