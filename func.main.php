<?php
//    定义自定义函数
//  获取贴吧的fid代号顺便签到
function getfid($bduss,$tbs,$kw){
	$re=json_decode(xCurl('http://tieba.baidu.com/sign/info?kw='.urlencode($kw).'&ie=utf-8','BDUSS='.$bduss),true);
	if (! $re['data']['user_info']['in_sign_in']) {
		xCurl('http://c.tieba.baidu.com/c/c/forum/like','ca=open','BDUSS='.urlencode($bduss).'&fid='.$re['data']['forum_info']['forum_info']['forum_id'].'&kw='.urlencode($kw).'&sign='.md5('BDUSS='.$bduss.'fid='.$re['data']['forum_info']['forum_info']['forum_id'].'kw='.$kw.'tbs='.$tbs.'tiebaclient!!!').'&tbs='.$tbs,array('Content-Type: application/x-www-form-urlencoded'));
		xCurl('http://c.tieba.baidu.com/c/c/forum/sign','ca=open','BDUSS='.urlencode($bduss).'&fid='.$re['data']['forum_info']['forum_info']['forum_id'].'&kw='.urlencode($kw).'&sign='.md5('BDUSS='.$bduss.'fid='.$re['data']['forum_info']['forum_info']['forum_id'].'kw='.$kw.'tbs='.$tbs.'tiebaclient!!!').'&tbs='.$tbs,array('Content-Type: application/x-www-form-urlencoded'));
	};
	return $re['data']['forum_info']['forum_info']['forum_id'];
};
/*
// 获得特长的回复。
function getfloor($tid,$post_id){
	$postdata = array(
		'kz='.$tid,
		'spid='.$post_id,
	);
	$postdata=signpd($postdata);
	$re=xCurl('http://c.tieba.baidu.com/c/f/pb/floor','ca=open',$postdata,array('Content-Type: application/x-www-form-urlencoded'));
	echo $re;
	return $re['post']['id'];
};*/
// 回复楼中楼
function sendfloor($bduss,$tbs,$fid,$tid,$pid,$kw,$content) {
	$postdata = implode('&',array (
		'BDUSS=' . urlencode($bduss),
		'_client_id=' . 'wappc_1480754308621_418',
		'_client_type=2',
		'_client_version=' . '8.0.0.3',
		'_phone_imei=861110010884802',
		'anonymous=1',
		'content=' . urlencode($content),
		'fid=' . $fid,
		'floor_num=0',
		'from=' . urlencode('baidu_appstore'),
		'is_ad=0',
		'is_addition=0',
		'is_giftpost=0',
		'is_twzhibo_thread=0',
		'kw=' . urlencode($kw),
		'model=' . urlencode('Kliton F168'),
		'new_vcode=1',
		'quote_id=' . $pid,
		'reply_uid=' . null,
		'repostid=' . $pid,
		'sign=' . md5('BDUSS='.$bduss.'_client_id=wappc_1480754308621_418'.'_client_type=2'.'_client_version=8.0.0.3'.'_phone_imei=861110010884802'.'anonymous=1'.'content='.$content.'fid='.$fid.'floor_num=0'.'from=baidu_appstore'.'is_ad=0'.'is_addition=0'.'is_giftpost=0'.'is_twzhibo_thread=0'.'kw='.$kw.'model=Kliton F168'.'new_vcode=1'.'quote_id='.$pid.'reply_uid='.null.'repostid='.$pid.'tbs='.$tbs.'tid='.$tid.'timestamp='.time().'987'.'vcode_tag=12'.'tiebaclient!!!'),
		'tbs=' . $tbs,
		'tid=' . $tid,
		'timestamp=' . time() . '987',
		'vcode_tag=' . '12'
	)
	);
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,'http://c.tieba.baidu.com/c/c/post/add');
	curl_setopt($ch, CURLOPT_POSTFIELDS,$postdata);
	curl_setopt($ch, CURLOPT_COOKIE,'ca=open');
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded','Charset: UTF-8','net: 3','User-Agent: bdtb for Android 8.0.0.3','Connection: Keep-Alive','Accept-Encoding: gzip','Host: c.tieba.baidu.com',));
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 50);
	$re = json_decode(gzdecode(curl_exec($ch)),true);
	curl_close($ch);
	return $re;
};
// 图灵接口
function robotreply($apikey,$content,$userid){
	$postdata = 'key='.$apikey.'&info='.$content.'&userid='.$userid;
	$re = json_decode(xCurl('http://www.tuling123.com/openapi/api',null,$postdata),true);
	switch ($re['code']){
		case 100000:
			$content = $re['text'];
			break;
		case 200000:
			$content = $re['text'].'：'.$re['url'];
			break;
		case 302000:
			$content = $re['text'];
			for ($counts = 0;$counts < count($re['list']);$counts++)
			$content .= "\n【".$re['list'][$counts]['article'].'】：（'.$re['list'][$counts]['source'].')【图片：'.$re['list'][$counts]['icon'].'】【链接：'.$re['list'][$counts]['detailurl'].'】。';
			break;
		case 308000:
			$content = $re['text'].'。';
			for ($counts = 0;$counts < count($re['list']);$counts++)
			$content .= "\n列表".($counts + 1).'：【'.$re['list'][$counts]['name'].'】：（原料：'.$re['list'][$counts]['info'].'）【图片：'.$re['list'][$counts]['icon'].'】【链接：'.$re['list'][$counts]['detailurl'].'】；';
			break;
		case 40004:
			$content = '今天累了，明天再聊吧#(乖)';
			break;
		default:
			$content = $re['text'];
	};
	return $content;
};
?>