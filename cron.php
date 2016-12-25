<?php
  /*
  *百度贴吧自动回帖PHP版
  *作者:    孙新
  *email:  sunxinao@hotmail.com
  *懒得整理。先这样好咯(눈_눈)
  *2016年12月24日 星期六
  */

include './config.php';
if (!isset($bduss))
	header('location: ./login.php');
include './common.php';
include './func.main.php';

// 回复 "回复我的" 部分
$postdata = signpd(array (
	'BDUSS='.$bduss,
	'_client_id=wappc_1480754308621_418',
	'_client_type=2',
	'_client_version=8.0.0.3',
	'_phone_imei=861110010884802',
	'from=' . 'baidu_appstore',
	'model=' . 'Kliton F168',
	'timestamp=' . time() . '760',
));
$re = json_decode(gzdecode(xCurl('http://c.tieba.baidu.com/c/u/feed/replyme','ca=open',$postdata,$tieba_header)),true);
$postdata = null;
//file_put_contents('./log.txt',json_encode($re,1));
for ($times = 0 ; $times < $re['message']['replyme'] ; $times ++) {
echo "\n".$re['reply_list'][$times]['replyer']['name'] . '说：' . $re['reply_list'][$times]['content'] . "\n";

// 用图灵接口调用自动回复,详见http://tuling123.com
$content = robotreply($apikey,preg_replace("/@{$rbname}\s*?|回复(\s|@)*?{$rbname}\s*?(:|：)/i",'',$re['reply_list'][$times]['content']),$re['reply_list'][$times]['replyer']['id']);

// 获取quoteid;fidid代号
$fid = getfid($bduss,$tbs,$re['reply_list'][$times]['fname']);
if ($re['reply_list'][$times]['is_floor'] == 1)
	$pid = $re['reply_list'][$times]['quote_pid'];
	else
	$pid = $re['reply_list'][$times]['post_id'];

// 回复
replyreply:
	$return = sendfloor($bduss,$tbs,$fid,$re['reply_list'][$times]['thread_id'],$pid,$re['reply_list'][$times]['fname'],'回复 ' . $re['reply_list'][$times]['replyer']['name'] . ' :' . $content);
switch ($return['error_code']) {
	case 0:
		echo $rbname.'回复：'.$content."\n";
		break;
	case 1:
		include '.login.php';
		return 0;
		break;
	case 4:
	case 220034:
		sleep(5);
		xCurl('http://tieba.baidu.com/f/commit/post/add','BDUSS='.$bduss,'ie=utf-8&kw='.urlencode($re['reply_list'][$times]['fname']).'&fid='.$fid.'&tid='.$re['reply_list'][$times]['thread_id'].'&vcode_md5=&floor_num=0&quote_id='.$pid.'&rich_text=1&tbs='.$tbs.'&content='.urlencode('回复 '.$re['reply_list'][$times]['replyer']['name'].' :'.$content).'&mouse_pwd=60%2C58%2C60%2C38%2C60%2C50%2C59%2C59%2C3%2C59%2C38%2C58%2C38%2C59%2C38%2C58%2C3%2C63%2C62%2C57%2C51%2C63%2C51%2C3%2C59%2C57%2C60%2C60%2C38%2C61%2C60%2C50%2C'.time().'4100&mouse_pwd_t='.time().'410&mouse_pwd_isclick=0__type__=reply',$firefox_header);
		break;
	case 230902:
		$pid = '';
		$content .= ' @'.$re['reply_list'][$times]['replyer']['name'];
		goto replyreply;
		break;
	case 110001:
		echo $return['error_msg']."\n";
		break;
	default:
		echo $return['error_code'].':'.$return['error_msg']."\n";
};
$return = null;
$postdata = null;
};
$re = null;

//回复 "@提到我的" 部分
$postdata = signpd(array (
	'BDUSS='.$bduss,
	'_client_id=wappc_1480754308621_418',
	'_client_type=2',
	'_client_version=8.0.0.3',
	'_phone_imei=861110010884802',
	'from=' . 'baidu_appstore',
	'model=' . 'Kliton F168',
	'timestamp=' . time() . '760',
));
$re=json_decode(gzdecode(xCurl('http://c.tieba.baidu.com/c/u/feed/atme','ca=open',$postdata,$tieba_header)),1);
$postdata = null;
for ($times = 0 ; $times < $re['message']['atme'] ; $times ++) {
echo $re['at_list'][$times]['replyer']['name'] . '艾特：' . $re['at_list'][$times]['content'] . "\n";

// 用图灵接口调用自动回复,详见http://tuling123.com
$content = robotreply($apikey,preg_replace("/@{$rbname}\s*?|回复(\s|@)*?{$rbname}\s*?(:|：)/i",'',$re['at_list'][$times]['content']),$re['at_list'][$times]['replyer']['id']);

// 获取quoteid;fidid代号
$fid = getfid($bduss,$tbs,$re['at_list'][$times]['fname']);
if ($re['at_list'][$times]['is_floor'] == 1)
	$pid = $re['at_list'][$times]['quote_pid'];
	else
	$pid = $re['at_list'][$times]['post_id'];

// 回复
replyat:
	$return = sendfloor($bduss,$tbs,$fid,$re['at_list'][$times]['thread_id'],$pid,$re['at_list'][$times]['fname'],'回复 ' . $re['at_list'][$times]['replyer']['name'] . ' :' . $content);
switch ($return['error_code']) {
	case 0:
		echo $rbname.'回复：'.$content."\n";
		break;
	case 1:
		include '.login.php';
		return 0;
		break;
	case 4:
	case 220034:
		sleep(5);
		xCurl('http://tieba.baidu.com/f/commit/post/add','BDUSS='.$bduss,'ie=utf-8&kw='.urlencode($re['at_list'][$times]['fname']).'&fid='.$fid.'&tid='.$re['at_list'][$times]['thread_id'].'&vcode_md5=&floor_num=0&quote_id='.$pid.'&rich_text=1&tbs='.$tbs.'&content='.urlencode('回复 '.$re['at_list'][$times]['replyer']['name'].' :'.$content).'&mouse_pwd=60%2C58%2C60%2C38%2C60%2C50%2C59%2C59%2C3%2C59%2C38%2C58%2C38%2C59%2C38%2C58%2C3%2C63%2C62%2C57%2C51%2C63%2C51%2C3%2C59%2C57%2C60%2C60%2C38%2C61%2C60%2C50%2C'.time().'4100&mouse_pwd_t='.time().'410&mouse_pwd_isclick=0__type__=reply',$firefox_header);
		break;
	case 230902:
		$pid = '';
		$content .= ' @'.$re['at_list'][$times]['replyer']['name'];
		goto replyat;
		break;
	case 110001:
		echo $return['error_msg']."\n";
		break;
	default:
		echo $return['error_code'].':'.$return['error_msg']."\n";
};
$return = null;
$postdata = null;
};
?>
