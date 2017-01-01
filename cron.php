<?php
  /*
  *百度贴吧自动回帖PHP版
  *作者:    孙新
  *email:  sunxinao@hotmail.com
  *懒得整理。先这样好咯(눈_눈)
  *2016年12月24日 星期六
  */
	header("Content-Type: text/html;charset=utf8");
	date_default_timezone_set('PRC');
	set_time_limit(0);
	define('IS_GARDEL',true);
	define('SYSTEM_ROOT', dirname(__FILE__).'/');
	include (SYSTEM_ROOT.'config.php');
	if (is_null($bduss))
		header('location: login.php');
	include (SYSTEM_ROOT.'functions.php');

// 回复 "回复我的" 部分
	for ($step = 1 ; $step <= 2 ; $step ++) {
		switch ($step) {
			case 1:
				$type = 'reply';
				break;
			case 2:
				$type = 'at';
				break;
		};
	$re_m = getfeed($bduss,$type);
	//file_put_contents('./log_'.$type.'.txt',json_encode($re_m,1));
	for ($times = 0 ; $times < $re_m['message'][$type.'me'] ; $times ++) {	//你可能会想到foreach()，但我觉得这里用for更好一些
		$content = trim(preg_replace("/@{$rbname}\s*?|回复 (\s|@)*?{$rbname}\s*?(:|：)/i",'',$re_m[$type.'_list'][$times]['content'])," \n\…");

// 获取quoteid;fidid代号，并判断是否为长回复。
		$fid = getfid($bduss,$tbs,$re_m[$type.'_list'][$times]['fname']);
		if ($re_m[$type.'_list'][$times]['is_floor'] == 1)
			$pid = $re_m[$type.'_list'][$times]['quote_pid'];
		else
			$pid = $re_m[$type.'_list'][$times]['post_id'];

// 用图灵接口调用自动回复,详见http://tuling123.com
		if ($re_m[$type.'_list'][$times]['replyer']['is_friend'])
			$content = '回复 ' . $re_m[$type.'_list'][$times]['replyer']['name'] . ' :' . robotreply($apikey,$content,$re_m[$type.'_list'][$times]['replyer']['id']) . '#(滑稽)';
		elseif (strstr($content,'爱你一万年')) {
			follow($bduss,$tbs,$re_m[$type.'_list'][$times]['replyer']['portrait']);
			$content = '爱上了我，我就是你的人了。关注我让我们一起愉快地聊天吧！#(太开心)';
		}
		else
			$content = '回复 ' . $re_m[$type.'_list'][$times]['replyer']['name'] . ' :' . '亲，初次见面，无耻求关注#(滑稽)，对我说“爱你一万年”，我就会关注你哦。#(乖)';

// 回复
		reply:
		$re = send_client($bduss,$tbs,$fid,$re_m[$type.'_list'][$times]['thread_id'],$pid,$re_m[$type.'_list'][$times]['fname'],$content);
		switch ($re['error_code']) {
			case 0:
				echo $rbname.'回复：'.$content."\n";
				break;
			case 1:
				echo $re['error_msg']."\n";
				include(SYSTEM_ROOT.'login.php');
				exit();
				break;
			case 4:
			case 220034:
				echo $re['error_msg']."\n";
				sleep(5);
				send_firefox($bduss,$tbs,$fid,$re_m[$type.'_list'][$times]['thread_id'],$pid,$re_m[$type.'_list'][$times]['fname'],$content);
				break;
			case 230902:
				echo $re['error_msg']."\n";
				$pid = null;
				$content = preg_replace("/回复 (\s|@)*?{$re_m[$type.'_list'][$times]['replyer']['name']}\s*?(:|：)/i",'',$content);
				$content .= ' @'.$re_m[$type.'_list'][$times]['replyer']['name'];
				sleep(5);
				goto reply;
				break;
			case 110001:
				echo $re['error_msg']."\n";
				break;
			default:
				echo $re['error_code'].':'.$re['error_msg']."\n";
		};
		unset($re);
	};
	unset($re_m);
	};


?>
