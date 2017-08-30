<?php
class TiebaRobot {

	//  获取贴吧的fid代号顺便签到
	public static function getFid($kw){
		$re = json_decode(((new HttpUtil('http://tieba.baidu.com/sign/info'))
			->addPostData('kw', $kw)
			->addPostData('ie', 'utf-8')
			->addCookie('BDUSS', RB_BDUSS)
			->build()
			->getPage()), true);
		if (! $re['data']['user_info']['is_sign_in']) {
			(new HttpUtil('http://c.tieba.baidu.com/c/c/forum/like'))
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
				->addPostData('tbs', RB_TBS)
				->addPostData('BDUSS', RB_BDUSS)
				->addPostData('fid', $re['data']['forum_info']['forum_info']['forum_id'])
				->addPostData('kw', $kw)
				->addCookie('ca', 'open')
				->signTiebaClient()
				->build()
				->postPage();
			(new HttpUtil('http://c.tieba.baidu.com/c/c/forum/sign'))
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
				->addPostData('tbs', RB_TBS)
				->addPostData('BDUSS', RB_BDUSS)
				->addPostData('fid', $re['data']['forum_info']['forum_info']['forum_id'])
				->addPostData('kw', $kw)
				->addCookie('ca', 'open')
				->signTiebaClient()
				->build()
				->postPage();
		}
		return $re['data']['forum_info']['forum_info']['forum_id'];
	}

	// 回复
	public static function sendReply($fid, $tid, $pid, $to, $spid, $kw, $content) {
		$re = (new HttpUtil('http://c.tieba.baidu.com/c/c/post/add'))
			->addHeader('Charset: UTF-8')
			->addHeader('net: 3')
			->addHeader('User-Agent: bdtb for Android ' . TIEBA_VERSION)
			->addHeader('Connection: Keep-Alive')
			->addHeader('Accept-Encoding: gzip')
			->addPostData('_client_type', '2')
			->addPostData('_client_id', TIEBA_WAPPC)
				->addPostData('_client_version', TIEBA_VERSION)
			->addPostData('_phone_imei', '861110010884802')
			->addPostData('anonymous', '1')
			->addPostData('can_no_forum', '0')
			->addPostData('floor_num', '0')
			->addPostData('from', 'baidu_appstore')
			->addPostData('is_addition', '0')
			->addPostData('is_feedback', '0')
			->addPostData('is_giftpost', '0')
			->addPostData('is_twzhibo_thread', '0')
			->addPostData('new_vcode', '1')
			->addPostData('v_fid', 'null')
			->addPostData('v_fname', 'null')
			->addPostData('model', 'Kliton F168')
			->addPostData('tbs', RB_TBS)
			->addPostData('kw', $kw)
			->addPostData('fid', $fid)
			->addPostData('tid', $tid)
			->addPostData('quote_id', $pid)
			->addPostData('reply_uid', $to)
			->addPostData('content', $content)
			->addPostData('repostid', $spid)
			->addPostData('timestamp', self::mtime())
			->addPostData('vcode_tag', '12')
			->addPostData('is_ad', '0')
			->addPostData('BDUSS', RB_BDUSS)
			->addCookie('ca', 'open')
			->signTiebaClient()
			->build()
			->postPage();
		$re = json_decode(gzdecode($re), true);
		return $re;
	}

	public static function follow($portrait){
		(new HttpUtil('http://c.tieba.baidu.com/c/c/forum/sign'))
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
			->addPostData('tbs', RB_TBS)
			->addPostData('BDUSS', RB_BDUSS)
			->addPostData('portrait', $portrait)
			->addCookie('ca', 'open')
			->signTiebaClient()
			->build()
			->postPage();
	}

	//获取回复或艾特
	public static function getFeed($type){
		$re = (new HttpUtil('http://c.tieba.baidu.com/c/u/feed/'.$type.'me'))
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
			->addPostData('BDUSS', RB_BDUSS)
			->addPostData('pn', '1')
			->addCookie('ca', 'open')
			->signTiebaClient()
			->build()
			->postPage();
		return json_decode(gzdecode($re), true);
	}

	public static function getReply() {
		return self::getFeed('reply')['reply_list'];
	}

	public static function getAt() {
		return self::getFeed('at')['at_list'];
	}

	public static function getFeedCount() {
		$re = (new HttpUtil('http://c.tieba.baidu.com/c/s/msg'))
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
			->addPostData('BDUSS', RB_BDUSS)
			->addCookie('ca', 'open')
			->signTiebaClient()
			->build()
			->postPage();
		$re = json_decode(gzdecode($re), true);
		return array($re['message']['replyme'], $re['message']['atme']);
	}

	// 图灵接口
	public static function robotReply($content, $userid){
		$content = trim($content," \n");
		if (empty($content)) $content = '打个招呼';
		$re = json_decode((new HttpUtil('http://www.tuling123.com/openapi/api'))
			->addPostData('key', RB_APIKEY)
			->addPostData('info', $content)
			->build()
			->postPage(),true);
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
	}

	//13位毫秒级时间戳
	public static function mtime() {
		list($t1, $t2) = explode(' ', microtime());
		return $t2 . ceil( ($t1 * 1000) );
	}
}
?>
