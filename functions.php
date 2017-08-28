<?php
	if (!defined('IS_GARDEL')) exit();
//    定义自定义函数

//  获取贴吧的fid代号顺便签到
	function getfid($bduss,$tbs,$kw){
		$re=json_decode(xCurl('http://tieba.baidu.com/sign/info?kw='.urlencode($kw).'&ie=utf-8','BDUSS='.$bduss),true);
		if (! $re['data']['user_info']['is_sign_in']) {
			xCurl('http://c.tieba.baidu.com/c/c/forum/like','ca=open;','BDUSS='.urlencode($bduss).'&fid='.$re['data']['forum_info']['forum_info']['forum_id'].'&kw='.urlencode($kw).'&sign='.md5('BDUSS='.$bduss.'fid='.$re['data']['forum_info']['forum_info']['forum_id'].'kw='.$kw.'tbs='.$tbs.'tiebaclient!!!').'&tbs='.$tbs,array('Content-Type: application/x-www-form-urlencoded'));
			xCurl('http://c.tieba.baidu.com/c/c/forum/sign','ca=open;','BDUSS='.urlencode($bduss).'&fid='.$re['data']['forum_info']['forum_info']['forum_id'].'&kw='.urlencode($kw).'&sign='.md5('BDUSS='.$bduss.'fid='.$re['data']['forum_info']['forum_info']['forum_id'].'kw='.$kw.'tbs='.$tbs.'tiebaclient!!!').'&tbs='.$tbs,array('Content-Type: application/x-www-form-urlencoded'));
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
		$re=xCurl('http://c.tieba.baidu.com/c/f/pb/floor','ca=open;',$postdata,array('Content-Type: application/x-www-form-urlencoded'));
		echo $re;
		return $re['post']['id'];
	};*/
// 回复
	function send_firefox($bduss,$tbs,$fid,$tid,$pid,$spid,$kw,$content) {
		$postdata='ie=utf-8&kw='.urlencode($kw).'&fid='.$fid.'&tid='.$tid.'&quote_id='.$pid.'&rich_text=1&tbs='.$tbs.'&content='.urlencode($content).'&lp_type=0&lp_sub_type=0&new_vcode=1&tag=11&repostid='.$spid.'&anonymous=0';
		$re = json_decode(gzdecode(xCurl('http://tieba.baidu.com/f/commit/post/add','BDUSS='.$bduss,$postdata,array('Accept-Language: en-US,en;q=0.5','Accept-Encoding: gzip','Content-Type: application/x-www-form-urlencoded; charset=UTF-8','X-Requested-With: XMLHttpRequest','Referer: http://tieba.baidu.com/p/'.$tid,'Connection: keep-alive'))),true);
		return $re;
	};
	function send_client($bduss,$tbs,$fid,$tid,$pid,$spid,$kw,$content) {
		$ts = time().'487';
		$postdata = implode('&',array (
			'BDUSS=' . urlencode($bduss),
			'_client_id=' . 'wappc_1483110951554_684',
			'_client_type=2',//1=IPhone,2=Android,3=WindowsPhone,4=Windows8
			'_client_version=' . '8.1.0.4',
			'_phone_imei=861110010884802',
			'anonymous=1',
			'barrage_time=0',
			'content=' . urlencode($content),
			'fid=' . $fid,
			'floor_num=0',
			'from=baidu_appstore',
			'is_ad=0',
			'is_addition=0',
			'is_barrage=0',
			'is_giftpost=0',
			'is_twzhibo_thread=0',
			'kw=' . urlencode($kw),
			'model=' . urlencode('Kliton F168'),
			'new_vcode=1',
			'quote_id=' . $pid,
			'reply_uid=' . 'null',
			'repostid=' . $spid,
			'sign=' . md5('BDUSS='.$bduss.'_client_id=wappc_1483110951554_684'.'_client_type=2'.'_client_version=8.1.0.4'.'_phone_imei=861110010884802'.'anonymous=1'.'barrage_time=0'.'content='.$content.'fid='.$fid.'floor_num=0'.'from=baidu_appstore'.'is_ad=0'.'is_addition=0'.'is_barrage=0'.'is_giftpost=0'.'is_twzhibo_thread=0'.'kw='.$kw.'model=Kliton F168'.'new_vcode=1'.'quote_id='.$pid.'reply_uid=null'.'repostid='.$spid.'tbs='.$tbs.'tid='.$tid.'timestamp='.$ts.'vcode_tag=12'.'tiebaclient!!!'),
			'tbs=' . $tbs,
			'tid=' . $tid,
			'timestamp=' . $ts,
			'vcode_tag=' . '12'
		));
		$re = json_decode(gzdecode(xCurl('http://c.tieba.baidu.com/c/c/post/add','ca=open;',$postdata,array('Content-Type: application/x-www-form-urlencoded','Charset: UTF-8','net: 3','User-Agent: bdtb for Android 8.1.0.4','Connection: Keep-Alive','Accept-Encoding: gzip','Host: c.tieba.baidu.com'))),true);
		return $re;
	};
	function follow($bduss,$tbs,$portrait){
		$re = json_decode(gzdecode(xCurl('http://c.tieba.baidu.com/c/c/user/follow','ca=open;','BDUSS='.urlencode($bduss).'&_client_version=8.1.0.4'.'&portrait='.urlencode($portrait).'&sign='.md5('BDUSS='.$bduss.'_client_version=8.1.0.4'.'portrait='.$portrait.'tbs='.$tbs.'tiebaclient!!!').'&tbs='.$tbs,array('Content-Type: application/x-www-form-urlencoded','Charset: UTF-8','net: 3','User-Agent: bdtb for Android 8.1.0.4','Connection: Keep-Alive','Accept-Encoding: gzip','Host: c.tieba.baidu.com'))),true);
		return $re;
	};
	function getfeed($bduss,$type){
		$re = json_decode(gzdecode(xCurl('http://c.tieba.baidu.com/c/u/feed/'.$type.'me','ca=open;','BDUSS='.urlencode($bduss).'&_client_version=8.1.0.4'.'&sign='.md5('BDUSS='.$bduss.'_client_version=8.1.0.4'.'tiebaclient!!!'),array('Content-Type: application/x-www-form-urlencoded','Charset: UTF-8','net: 3','User-Agent: bdtb for Android 8.1.0.4','Connection: Keep-Alive','Accept-Encoding: gzip'))),true);
		return $re;
	};
// 图灵接口
	function robotreply($apikey,$content,$userid){
		$content = trim($content," \n");
		if (empty($content)) $content = '打个招呼';
		$re = json_decode(xCurl('http://www.tuling123.com/openapi/api',null,'key='.$apikey.'&info='.$content.'&userid='.$userid),true);
		switch ($re['code']){
			case 100000:
				$content = $re['text'];
				break;
			case 200000:
				$content = $re['text'].'：'.$re['url'];
				break;
			case 302000:
				$content = $re['text'];
				foreach ($re['list'] as $list)
					$content .= "\n【".$list['article'].'】：（'.$list['source'].')【图片：'.$list['icon'].'】【链接：'.$list['detailurl'].'】。';
				break;
			case 308000:
				$content = $re['text'].'。';
				foreach ($re['list'] as $list)
					$content .= "\n列表".'【'.$list['name'].'】：（原料：'.$list['info'].'）【图片：'.$list['icon'].'】【链接：'.$list['detailurl'].'】；';
				break;
			case 40002:
				$content = '亲，叫我干嘛';
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
