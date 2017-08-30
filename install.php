<?php
if (file_exists('./config.php')) {
	header('Location: index.php');
	exit();
}
header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set('PRC');
set_time_limit(0);
define('SYSTEM_ROOT', dirname(__FILE__));
define('TIEBA_VERSION', '8.8.0.1');
define('TIEBA_WAPPC', 'wappc_1503985778299_983');
?>
<html>
<head>
<title>安装</title>
<head>
<body>
<?php
$step = $_REQUEST['step'];
switch($step) {
	case 1:
	default:
		if (function_exists('curl_init') && function_exists('gzdecode')) :
?>
<form method="POST" action="install.php?step=2">
百度贴吧Cookie[BDUSS]:<input type="text" name="install_bduss" placeholder="BDUSS" />
图灵apikey:<input type="text" name="install_apikey" placeholder="APIKey" />
<input type="submit" value="下一步" />
</form>
<?php
		else :
?>
<a style="color:#ffff0000">您的主机未开启curl或gzip！</a>
<?php
		endif;
		break;
	case 2:
		if (empty($_REQUSET['install_bduss']) || empty($_REQUEST['install_apikey'])) break;
		$re = (new HttpUtil('http://c.tieba.baidu.com/c/s/login'))
			->addHeader('Charset: UTF-8')
			->addHeader('net: 3')
			->addHeader('User-Agent: bdtb for Android ' . TIEBA_VERSION)
			->addHeader('Connection: Keep-Alive')
			->addHeader('Accept-Encoding: gzip')
			->addPostData('_client_type', '2')
			->addPostData('_client_id', TIEBA_WAPPC)
			->addPostData('_client_version', TIEBA_VERSION)
			->addPostData('_phone_imei', '861110010884802')
			->addPostData('model', 'Kliton F168')
			->addPostData('channel_id', '')
			->addPostData('channel_uid', '')
			->addPostData('from', 'baidu_appstore')
			->addPostData('bdusstoken', trim($_REQUEST['install_bduss'], "\t\n \"'") . '|null')
			->signTiebaClient()
			->build()
			->postPage();
		$re = json_decode(gzdecode($re), true);
		if ($re['error_code']) {
			echo $re['error_msg'];
			break;
		} else {
			$tbs = $re['anti']['tbs'];
			$bduss = $re['user']['BDUSS'];
			$rbname = $re['user']['name'];
			$tulingapi = trim($_REQUEST['install_apikey'], " \t\n\"'");
			echo '成功登录“' . $rbname . '”！<br />';
			unset($re);
			$content = "<?php\n\tdefine('RB_BDUSS', '{$bduss}');\n\tdefine('RB_TBS', '{$tbs}');\n\tdefine('RB_NAMR', '{$rbname}');\n\tdefine('RB_APIKEY', '{$tulingapi}');\n\tdefine('TIEBA_VERSION', '".TIEBA_VERSION."');\n\tdefine('TIEBA_WAPPC', '".TIEBA_WAPPC."');\n?>";
			if (file_put_contents(SYSTEM_ROOT . '/config.php', $content) <= 0)
				echo '<p>哎呀，无法写入文件！请手动配置config.php配置信息如下<pre>'.htmlspecialchars($content).'</pre></p>';
			echo '<p>请将<a href="cron.php">此链接</a>添加到计划任务中并测试一下，安装就完成了</p>';
			break;
		}
}
?>
</body>
</html>
