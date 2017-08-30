<?php
class HttpUtil {
	private $url;
	private $randomString;
	private $header = array();
	private $cookie = array();
	private $postdata = array();
	private $postdata_raw = '';
	private $protobuf_data;

	public function __construct($url) {
		if (empty($url)) throw new Exception('url不能为空');
		$this->randomString = md5(mt_rand() . microtime()) . '*';
		$this->url = $url;
	}

	public function __destruct() {
		unset($this->url);
		unset($this->randomString);
		unset($this->header);
		unset($this->cookie);
		unset($this->postdata);
		unset($this->postdata_raw);
		unset($this->protobuf_data);
	}

	public function addHeader($data) {
		if (!empty($data)) 
			$this->header[] = $data;
		return $this;
	}

	public function addPostData($key, $val) {
		if (!empty($key))
			$this->postdata[$key] = $val;
		return $this;
	}

	public function addCookie($key, $val) {
		if (!empty($key))
			$this->cookie[$key] = $val;
		return $this;
	}

	public function addProto($data) {
		if (!empty($data) && empty($this->protobuf_data))
			$this->protobuf_data = $data;
		return $this;
	}

	public function signTiebaClient() {
		if (empty($this->postdata)) return $this;
		ksort($this->postdata);
		$buf = '';
		foreach ($this->postdata as $key => $val) 
			$buf .= ($key . '=' . $val);
		$buf .= 'tiebaclient!!!';
		$this->postdata['sign'] = strtoupper(md5($buf));
		return $this;
	}

	public function build() {
		if (empty($this->protobuf_data)) {
			foreach ($this->postdata as $key => $val)
				$this->postdata_raw .= ($key . '=' . urlencode($val) . '&');
				$this->postdata_raw = substr($this->postdata_raw, 0 , strlen($this->postdata_raw) - 1);
		} else {
			foreach ($this->postdata as $key => $val) {
				$this->postdata_raw .= ('--' . $this->randomString . "\r\n" .
					'Content-Disposition: form-data; name="' . $key . "\"\r\n\r\n" . //每个参数都用随机字符串分割，键值后有两个回车换行
					str_replace("\r", '', $val) . "\r\n");
			}
			$this->postdata_raw .= ('--' . $this->randomString . "\r\nContent-Disposition: form-data; name=\"data\"; filename=\"file\"\r\n\r\n" .
				$this->protobuf_data . "\r\n");//添加protobuf文件
			$this->postdata_raw .= ('--' . $this->randomString . "--\r\n");//结尾仍以随机字符串结尾，也可以不要
			$this->addHeader('Content-Type: multipart/form-data; boundary=' . $this->randomString);
			$this->addHeader('x_bd_data_type: protobuf');
		}
		return $this;
	}

	public function getPage() {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->url . '?' . $this->postdata_raw);
		if (!empty($this->cookie)) {
			$coo = '';
			foreach ($this->cookie as $key => $val)
				$coo .= ($key . '=' . $val .'; ');
			curl_setopt($ch, CURLOPT_COOKIE, $coo);
		}
		curl_setopt($ch, CURLOPT_HTTPHEADER, empty($this->header) ? array('User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:50.0) Gecko/20100101 Firefox/50.0','Accept: */*','Accept-Language: zh-CN,zh;q=0.8,en-US;q=0.5,en;q=0.3','Content-Type: application/x-www-form-urlencoded; charset=UTF-8','Referer: http://tieba.baidu.com/','Connection: keep-alive') : $this->header);
		if (strpos($this->url,'https://') === 0) curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 50);
		$re = curl_exec($ch);
		if (! $re) throw (new Exception("无法抓取网页"));
		curl_close($ch);
		return $re;
	}

	public function postPage() {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->url);
		if (!empty($this->cookie)) {
			$coo = '';
			foreach ($this->cookie as $key => $val)
				$coo .= ($key . '=' . $val .'; ');
			curl_setopt($ch, CURLOPT_COOKIE, $coo);
		}
		curl_setopt($ch, CURLOPT_POSTFIELDS, $this->postdata_raw);
		curl_setopt($ch, CURLOPT_HTTPHEADER, empty($this->header) ? array('User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:50.0) Gecko/20100101 Firefox/50.0','Accept: */*','Accept-Language: zh-CN,zh;q=0.8,en-US;q=0.5,en;q=0.3','Content-Type: application/x-www-form-urlencoded; charset=UTF-8','Referer: http://tieba.baidu.com/','Connection: keep-alive') : $this->header);
		if (strpos($this->url,'https://') === 0) curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 50);
		$re = curl_exec($ch);
		if (! $re) throw (new Exception("无法抓取网页"));
		curl_close($ch);
		return $re;
	}
}
