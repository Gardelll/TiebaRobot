<?php
	//    用于生成配置文件
	//    请修改以下信息。
	$bduss = '请输入';//百度Cookie中的BDUSS
	$tulingapi = '请输入';//图灵机器人接口码，在http://tuling123.com获得









	//    以下信息不用更改。
	include './common.php';
	$re = json_decode(gzdecode(xCurl('http://c.tieba.baidu.com/c/s/login','ca=open',getsign(array('_client_id=' . 'wappc_1480754308621_418','_client_type=' . 2,'_client_version=' . '8.0.0.3','_phone_imei=' . '861110010884802','bdusstoken=' . $bduss . '|null','channel_id=','channel_uid=','from=' . 'baidu_appstore','model=' . 'Redmi Note 3')),$tieba_header)),true);
	if ($re['error_code']) {
		echo $re['error_msg'];
		return 1;
	}else {
		$tbs = $re['anti']['tbs'];
		$bduss = $re['user']['BDUSS'];
		$rbname = $re['user']['name'];
		echo '成功登录'.$rbname.'！';
	};
	$re = null;
	$postdata = null;
	$content = "<?php\n    \$bduss = '$bduss';\n    \$tbs = '$tbs';\n    \$rbname = '$rbname';\n    \$apikey = '$tulingapi';\n?>";
	if(file_put_contents(dirname(__FILE__).'/config.php',$content)<= 0)
		showmsg('<p>哎呀，无法写入文件！请手动配置config.php配置信息如下</p><pre>'.htmlspecialchars($content).'</pre>',1);
?>
