<?php
date_default_timezone_set('PRC');

//  定义curl功能
function xCurl($url,$cookie=null,$postdata=null,$header=array()){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$url);
	if (!is_null($postdata)) curl_setopt($ch, CURLOPT_POSTFIELDS,$postdata);
	if (!is_null($cookie)) curl_setopt($ch, CURLOPT_COOKIE,$cookie);
	if (!empty($header)) curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 50);
	$re = curl_exec($ch);
	curl_close($ch);
	return $re;
};
//  获取POSTDATA的sign
function signpd($data){
	$data=implode('&', $data).'&sign='.md5(implode('', $data).'tiebaclient!!!');
	return $data;
};
// 定义字符变量
$tieba_header = array(
	'Content-Type: application/x-www-form-urlencoded',
	'Charset: UTF-8',
	'net: 3',
	'User-Agent: bdtb for Android 8.0.0.3',
	'Connection: Keep-Alive',
	'Accept-Encoding: gzip',
	'Host: c.tieba.baidu.com',
	);
$firefox_header = array(
	'Host: tieba.baidu.com',
	'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:50.0) Gecko/20100101 Firefox/50.0',
	'Accept: */*',
	'Accept-Language: zh-CN,zh;q=0.8,en-US;q=0.5,en;q=0.3',
	'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
	'Referer: http://tieba.baidu.com/',
	'Connection: keep-alive',
	);
?>