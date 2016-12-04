<?php
header("Content-Type: text/html;charset=utf8");
date_default_timezone_set('PRC');
function fetch($url,$cookie=null,$postdata=null,$header=array()){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url);
    if (!is_null($postdata)) curl_setopt($ch, CURLOPT_POSTFIELDS,$postdata);
    if (!is_null($cookie)) curl_setopt($ch, CURLOPT_COOKIE,$cookie);
    if (!empty($header)) curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_TIMEOUT, 50);
    $re = curl_exec($ch);
    curl_close($ch);
    return $re;
};
function getsign($data){
    $data=implode('&', $data).'&sign='.md5(implode('', $data).'tiebaclient!!!');
    return $data;
};
$tieba_header = array(
    'Content-Type: application/x-www-form-urlencoded',
    'Charset: UTF-8',
    'net: 3',
    'User-Agent: bdtb for Android 8.0.0.3',
    'Connection: Keep-Alive',
    'Accept-Encoding: gzip',
    'Host: c.tieba.baidu.com',
    );
$firefox_header = array(
    'Host: tieba.baidu.com',
    'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:50.0) Gecko/20100101 Firefox/50.0',
    'Accept: */*',
    'Accept-Language: zh-CN,zh;q=0.8,en-US;q=0.5,en;q=0.3',
    'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
    'Referer: http://tieba.baidu.com/',
    'Connection: keep-alive',
);
$bduss = ''/*请自行修改！！！*/;
$postdata = array (
    'BDUSS='.$bduss,
    '_client_id=wappc_1480754308621_418',
    '_client_type=2',
    '_client_version=8.0.0.3',
    '_phone_imei=861110010884802',
    'from=' . 'baidu_appstore',
    'model=' . 'Kliton F168',
    'timestamp=' . time() . '760',
);
$postdata=getsign($postdata);
$re=json_decode(gzdecode(fetch('http://c.tieba.baidu.com/c/u/feed/replyme','ca=open',$postdata,$tieba_header)),true);
for ($times = 0 ; $times < $re['message']['replyme'] ; $times ++)
{
//echo json_encode($re['reply_list'][$times],true).'<br />';//显示回复的详细情况
echo $re['reply_list'][$times]['replyer']['name'].'：'.$re['reply_list'][$times]['content'].'<br />';
//if ($times == (count($re['reply_list']) - 1))
//    break;
};
//if (! $re['page']['has_more'])
//    break;
?>
