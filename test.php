<?php
require_once './HttpUtil.class.php';
require_once './Tieba.php';
require_once './Tieba_Send.php';
require_once './Tieba_Send_Userinfo.php';
$re = new HttpUtil('http://c.tieba.baidu.com/c/u/feed/replyme')
	->addHeader('Charset: UTF-8')
	->addHeader('net: 3')
	->addHeader('User-Agent: bdtb for Android 8.7.8.7')
	->addHeader('Connection: Keep-Alive')
	->addHeader('Accept-Encoding: gzip')
	->addPostData('stTime', '2')
	->addCookie('ca', 'open')
	->addProto((new Tieba())
		->setSend((new Tieba_Send())
			->setI1(1)
			->setUserinfo((new Tieba_Send_Userinfo())
				->setClientType(2)
				->setClientVersion('8.7.8.7')
				->setClientId('wappc_1503539678648_436')
				->setPhoneImei('869271022089483')
				->setFrom('baidu_appstore')
				->setTimestamp(time() * 1000 + 437)
				->setModel('Redmi Note 3')
				->setBDUSS('lBCaGVtVU1NLVdZRGxtRUZPbDZCdUxxYlNMaW03TENvVjFlVmZST1VGaWNDYjFZSVFBQUFBJCQAAAAAAAAAAAEAAADsvllJU3VuWGluYW9ubzEAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAJx8lVicfJVYW')))
		->serializeToString())
		->signTiebaClient()
		->build()
		->postPage();
echo gzdecode($re);
