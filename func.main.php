<?php
//    定义自定义函数
//  获取贴吧的fid代号
function getfid($kw){
	$re=json_decode(xCurl('http://tieba.baidu.com/f/commit/share/fnameShareApi?ie=utf-8&fname='.urlencode($kw)),true);
	return $re['data']['fid'];
};
/*
// 获得特长的回复。
function getfloor($tid,$post_id){
	$postdata = array(
		'kz='.$tid,
		'spid='.$post_id,
	);
	$postdata=getsign($postdata);
	$re=xCurl('http://c.tieba.baidu.com/c/f/pb/floor','ca=open',$postdata,array('Content-Type: application/x-www-form-urlencoded'));
	echo $re;
	return $re['post']['id'];
};*/

// 图灵接口
function robotreply($apikey,$content,$userid){
	$postdata = 'key='.$apikey.'&info='.$content.'&userid='.$userid;
	$re = json_decode(xCurl('http://www.tuling123.com/openapi/api',null,$postdata),true);
	switch ($re['code']){
		case 100000:
			$content = $re['text'];
			break;
		case 200000:
			$content = $re['text'].$re['url'];
			break;
		case 302000:
			$content = $re['text'];
			for ($counts = 0;$counts < count($re['list']);$counts++)
			$content .= "\n【".$re['list'][$counts]['article'].'】：（'.$re['list'][$counts]['source'].')【图片：'.$re['list'][$counts]['icon'].'】【链接：'.$re['list'][$counts]['detailurl'].'】；';
			break;
		case 308000:
			$content = $re['text'].'。';
			for ($counts = 0;$counts < count($re['list']);$counts++)
			$content .= "\n列表".($counts + 1).'：【'.$re['list'][$counts]['name'].'】：（原料：'.$re['list'][$counts]['info'].'）【图片：'.$re['list'][$counts]['icon'].'】【链接：'.$re['list'][$counts]['detailurl'].'】；';
			break;
		case 40004:
			$content = '今天累了，明天再聊吧';
			break;
		default:
			$content = $re['text'];
	};
	//echo $content;
	return $content;
};
?>