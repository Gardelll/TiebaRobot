<?php
  /*
  *百度贴吧自动回帖PHP版
  *作者:    孙新
  *email:  sunxinao@hotmail.com
  *懒得整理。先这样好咯(눈_눈)
  *2016年12月17日 星期六
  */

include './config.php';
if (!isset($bduss))
	header('location: ./login.php');
include './common.php';
include './func.main.php';

// 回复 "回复我的" 部分
$postdata = getsign(array (
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
for ($times = 0 ; $times < $re['message']['replyme'] ; $times ++) {
echo "\n".$re['reply_list'][$times]['replyer']['name'] . '说：' . $re['reply_list'][$times]['content'] . "\n";

// 自动关注该吧
//$postdata = 'fid='.getfid($re['reply_list'][$times]['fname']).'&fname='.$re['reply_list'][$times]['fname'].'&uid='.urlencode($bduss).'&ie=gbk&tbs='.$tbs;
//xCurl('http://tieba.baidu.com/f/like/commit/add','BDUSS='.$bduss,$postdata);
//$postdata = null;

// 用图灵接口调用自动回复,详见http://tuling123.com
$content = robotreply($apikey,preg_replace("/@{$rbname}\s*?|回复(\s|@)*?{$rbname}\s*?(:|：)/i",'',$re['reply_list'][$times]['content']),$re['reply_list'][$times]['replyer']['id']);

// 获取quoteid代号
if ($re['reply_list'][$times]['is_floor'] == 1)
	$pid = $re['reply_list'][$times]['quote_pid'];
	else
	$pid = $re['reply_list'][$times]['post_id'];

// 回复
replyreply:
$postdata = getsign(array (
        'BDUSS=' . $bduss,
        '_client_id=' . 'wappc_1480754308621_418',
        '_client_type=' . '2',
        '_client_version=' . '8.0.0.3',
        '_phone_imei=' . '861110010884802',
        'anonymous=' . '1',
        'barrage_time=' . '0',
        'content=' . '回复 ' . $re['reply_list'][$times]['replyer']['name'] . ' :' . $content,
        'fid=' . getfid($re['reply_list'][$times]['fname']),
        'from=' . 'baidu_appstore',
        'is_ad=' . '0',
        'is_barrage=' . '0',
        'kw=' . $re['reply_list'][$times]['fname'],
        'model=' . 'Kliton F168',
        'new_vcode=' . '1',
        'quote_id=' . $pid,
        'reply_uid=' . 'null',
        'tbs=' . $tbs,
        'tid=' . $re['reply_list'][$times]['thread_id'],
        'timestamp=' . time() . '465',
        'vcode_tag=' . '12'
    ));
	$return = json_decode(gzdecode(xCurl('http://c.tieba.baidu.com/c/c/post/add','ca=open',$postdata,$tieba_header)),true);
switch ($return['error_code']) {
	case 0:
		echo $rbname.'回复：'.$content."\n";
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
$postdata = getsign(array (
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

// 自动关注该吧
//$postdata = 'fid='.getfid($re['at_list'][$times]['fname']).'&fname='.$re['at_list'][$times]['fname'].'&uid='.urlencode($bduss).'&ie=gbk&tbs='.$tbs;
//xCurl('http://tieba.baidu.com/f/like/commit/add','BDUSS='.$bduss,$postdata);
//$postdata = null;

// 用图灵接口调用自动回复,详见http://tuling123.com
$content = robotreply($apikey,preg_replace("/@{$rbname}\s*?|回复(\s|@)*?{$rbname}\s*?(:|：)/i",'',$re['at_list'][$times]['content']),$re['at_list'][$times]['replyer']['id']);

// 获取quoteid代号
if ($re['at_list'][$times]['is_floor'] == 1)
	$pid = $re['at_list'][$times]['quote_pid'];
	else
	$pid = $re['at_list'][$times]['post_id'];

// 回复
replyat:
$postdata = getsign(array (
        'BDUSS=' . $bduss,
        '_client_id=' . 'wappc_1480754308621_418',
        '_client_type=' . '2',
        '_client_version=' . '8.0.0.3',
        '_phone_imei=' . '861110010884802',
        'anonymous=' . '1',
        'barrage_time=' . '0',
        'content=' . '回复 ' . $re['at_list'][$times]['replyer']['name'] . ' :' . $content,
        'fid=' . getfid($re['at_list'][$times]['fname']),
        'from=' . 'baidu_appstore',
        'is_ad=' . '0',
        'is_barrage=' . '0',
        'kw=' . $re['at_list'][$times]['fname'],
        'model=' . 'Kliton F168',
        'new_vcode=' . '1',
        'quote_id=' . $pid,
        'reply_uid=' . 'null',
        'tbs=' . $tbs,
        'tid=' . $re['at_list'][$times]['thread_id'],
        'timestamp=' . time() . '437',
		'vcode_tag=' . '12'
	));
$return = json_decode(gzdecode(xCurl('http://c.tieba.baidu.com/c/c/post/add','ca=open',$postdata,$tieba_header)),true);
switch ($return['error_code']) {
	case 0:
		echo $rbname.'回复：'.$content."\n";
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
//echo "\n".'Success!';
?>
