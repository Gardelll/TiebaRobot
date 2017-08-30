<?php
/**
 * 百度贴吧自动回帖PHP版
 * @author    Gardel
 * @email  sunxinao@hotmail.com
 * @date 2017年8月30日 18:29 星期三
 */
header('Content-Type: text/plain; charset=utf-8');
require_once './init.php';
if (!defined(RB_BDUSS)) {
	header('Location: install.php');
	exit();
}

list($replyme, $atme) = TiebaRobot::getFeedCount();
if ($replyme) {
	do_reply(array_slice(TiebaRobot::getReply(), 0, $replyme));
}
if ($atme) {
	ao_reply(array_slice(TiebaRobot::getAt(), 0, $atme));
}

function do_reply($msgs) {
	for ($i = 0; $i < count($msgs); $i ++) {
		if (!isset($msgs[$i]['content']) || empty($msgs[$i]['content'])) continue;
		$content = trim(preg_replace("/@" . RB_NAME . "\s*?|回复 (\s|@)*?" . RB_NAME . "\s*?(:|：)/i",'',$msgs[$i]['content'])," \n.…");
		$fid = TiebaRobot::getFid($msgs[$i]['fname']);
		$pid = null;$spid = null;
		if ($msgs[$i]['is_floor']) {
			$pid = $msgs[$i]['quote_pid'];
			$spid = $msgs[$i]['post_id'];
		} else {
			$pid = $msgs[$i]['post_id'];
			$spid = $msgs[$i]['post_id'];
		};
		$to = $msgs[$i]['replyer']['id'];
		$replyer = $msgs[$i]['replyer']['name'];

		// 用图灵接口调用自动回复,详见http://tuling123.com
		if ($msgs[$i]['replyer']['is_friend'])
			$content = '回复 ' . $replyer . ' :' . TiebaRobot::robotReply($content, $to) . '#(滑稽)';
		elseif (strstr($content,'爱你一万年') !== false) {
			TiebaRobot::follow($msgs[$i]['replyer']['portrait']);
			if (!($msgs[$i]['replyer']['is_friend'])) $content = '回复 ' . $replyer . ' :' .'下一步：关注我，我们必须成为好朋友才可以一起愉快地聊天#(太开心)';
			else $content = '回复 ' . $replyer . ' :' . '让我们一起愉快地聊天吧#(滑稽)';
		}
		else
			$content = '回复 ' . $replyer . ' :' . '亲，初次见面，无耻求互粉互粉互粉!#(滑稽)，对我说“爱你一万年”，我就会关注你哦。#(乖)';
			if (empty($pid) || empty($spid)) $content = preg_replace("/回复\\s+" . $replyer . "\s+(:|：)/i", ' @' . $replyer .' ', $content);

		// 回复
		if (mb_strlen($content, 'utf_8') >= 5000) $content = mb_strcut($content, 0 , 5000,'utf-8');
		$re = TiebaRobot::sendReply($fid, $msgs[$i]['thread_id'], $pid, $to, $spid, $msgs[$i]['fname'], $content);
		switch ($re['error_code']) {
			case 0:
				echo RB_NAME . '回复：' . $content . "\n";
				break;
			case 1:
				echo $re['error_msg']."\n";
				exit();
				break;
			case 4:
			case 220034:
				echo $re['error_msg']." 十秒后重试\n";
				sleep(10);
				$i--;
				break;
			case 230046:
			case 230902:
				echo $re['error_msg']."\n";
				break;
			case 3250003:
			case 110001:
				echo $re['error_msg']."\n";
				break;
			default:
				echo $re['error_code'].':'.$re['error_msg']."\n";
		}
	}
	unset($re);
}
