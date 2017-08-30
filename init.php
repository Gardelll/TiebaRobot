<?php
date_default_timezone_set('PRC');
set_time_limit(0);
define('SYSTEM_ROOT', dirname(__FILE__));
require_once(SYSTEM_ROOT . '/config.php');
/*spl_autoload_register(function ($classname) {
	$filename = SYSTEM_ROOT . '/lib/' . str_replace('\\', '/', $classname) . '.php';
	if (file_exists($filename)) {
		include_once $filename;
	} else {
		die('找不到文件:'.$filename);
	}
});*/
require_once (SYSTEM_ROOT . '/TiebaRobot.php');
error_reporting(E_ALL^E_WARNING^E_NOTICE);
