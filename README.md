百度贴吧聊天机器人
=========================
百度贴吧机器人，可回复@和楼中楼对话
-------------------------
##开发中，请勿下载！<br />
### 使用方法
 1.安装前打开login.php输入BDUSS和图灵apikey。<br />
 2.访问你上传的网址，按提示操作。<br />
 3.设置计划任务或者监控到cron.php<br />
### 注意
 本程序需要用到protobuf php扩展<br />
 可在<a href="https://github.com/google/protobuf/releases">这里</a>下载php版的源码编译安装<br />
 此外，如果你没有安装php-dev，你还需要下载php的源码包<br />
```SHELLL
tar zxvf protobuf-x.x.x.tgz
cd protobuf-x.x.x
./configure --prefix=../Protobuf
make && make install
cd php/ext/google/protobuf
gcc -o protobuf.so *.c -shared -fPIC -I. -I/path/to/php/source/main -I/path/to/php/source -I/path/to/php/source/Zend -I/path/to/php/source/TSRM -I../Protobuf/include/google/protobuf -L../Protobuf/lib
```
 然后将protobuf.so加载到php.ini中<br />
 如果您的主机不支持php扩展，也可以使用本程序，但无法及时收到回复并做出反应.
 
## 大家试试在百度贴吧 @吧专用饮水机
 多艾特就可以帮我多测试多改进。<br />
-------------------------
 暂缓更新了
