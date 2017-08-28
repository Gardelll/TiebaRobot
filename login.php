<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<?php
	date_default_timezone_set('PRC');
	define('SYSTEM_ROOT', dirname(__FILE__).'/');
	//		代码开源，请勿修改版权！



	//    用于生成配置文件
	//    请修改以下信息。
	$bduss = '';//百度Cookie中的BDUSS
	$tulingapi = '';//图灵机器人接口码，在http://tuling123.com获得









	//    以下信息不用更改。
	$re = array('_client_id=' . 'wappc_1483110951554_684','_client_type=' . 2,'_client_version=' . '8.1.0.4','_phone_imei=' . '861110010884802','bdusstoken=' . $bduss . '|null','channel_id=','channel_uid=','from=' . 'baidu_appstore','model=' . 'Kliton F168');
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,'http://c.tieba.baidu.com/c/s/login');
	curl_setopt($ch, CURLOPT_POSTFIELDS,implode('&', $re).'&sign='.md5(implode('', $re).'tiebaclient!!!'));
	curl_setopt($ch, CURLOPT_COOKIE,'ca=open');
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded','Charset: UTF-8','net: 3','User-Agent: bdtb for Android 8.1.0.4','Connection: Keep-Alive','Accept-Encoding: gzip','Host: c.tieba.baidu.com',));
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 50);
	$re = json_decode(gzdecode(curl_exec($ch)),true);
	curl_close($ch);
	if ($re['error_code']) {
		echo $re['error_msg'];
		return 1;
	}else {
		$tbs = $re['anti']['tbs'];
		$bduss = $re['user']['BDUSS'];
		$rbname = $re['user']['name'];
		echo '成功登录“'.$rbname.'”！';
	};
	$re = null;
	$postdata = null;
	$content = "<?php\n\t\$bduss = '$bduss';\n\t\$tbs = '$tbs';\n\t\$rbname = '$rbname';\n\t\$apikey = '$tulingapi';\n?>";
	if(file_put_contents(SYSTEM_ROOT.'config.php',$content) <= 0)
		printf('<p>哎呀，无法写入文件！请手动配置config.php配置信息如下</p><pre>'.htmlspecialchars($content).'</pre>',1);
?>
