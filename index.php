<html>
  <head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>说明</title>
  </head>
  <body>
	<h1>第一步：</h1>
	<a>请在login.php中填写(手动打开填写!)信息并</a><a href="./login.php">运行一遍</a><a>如果您的主机不可写，请。。。自己想办法</a><br />
	<a>如果提示成功登录，请运行<a href="./cron.php">计划任务</a><a>如果没有错误提示，则进入下一步。</a>
	  <h1>第二步：</h1>
	  <a>添加一个计划任务到.</a><?php echo $_SERVER['REQUEST_URI'];?><a>cron.php</a><br />
	  <a>每次任务至少间隔一分钟。</a>
	  <h1>第三步：</h1>
	  <a>尽情享受~</a>
  </body>
</html>
