<?php
if(!function_exists('curl_version')) {
	die("You need to enable the cURL extension to use Licensify.");
}

class licensify {
	/*
		Licensing configuration
		Do not tamper with the values below if you don't know what you're doing.
	*/
	private $public_key = 'n3zjyNniASG1p04wdemRxKxA9KTPRfjU';
	private $product_id = '42';
	private $crypt = 'ULrGaLepQQuWgH9spBIv';
		
	/*
		Licensing configuration <end>
		DO NOT EDIT BELOW.
	*/

	private $debug = false;

	function __construct() {
		if(isset($_POST['licensify_validate'])) {
			# process validation
			$this->call($this->debug, array('licensify_secret_code' => $_POST['licensify_secret_code']));
		}
	}

	public function validate() {
		if(!$this->getLocal($this->debug))
		$this->call($this->debug);
	}

	private function getLocal($debug) {
		$dir = dirname(__FILE__);
		if(!file_exists($dir . '/s.key'))
			return false;

		chmod($dir . '/s.key', 0755);

		$data = file_get_contents($dir . '/s.key');
		if($data) {
			$res = json_decode($this->enc('decrypt', $data));

			if($res->next_check < time() || $res->product != $this->product_id) {
				return false;
			} else {
				return true;
			}
		}
		return false;
	}

	private function hasType($type) {
		$types = array_filter(explode(';', $this->type));
		if(in_array($type, $types))
			return true;
	}

	private function call($debug, $params_extra = null) {

		$params = array(
			'product_id'		=>	$this->product_id,
			'public_key'		=>	$this->public_key,
			'domain'			=>	$_SERVER["SERVER_NAME"],
			'ip'				=>	$_SERVER['SERVER_ADDR'],
			'licensify_root'	=>	dirname(__FILE__),
			'licensify_filename'=>  __FILE__,	
		);

		if($params_extra != null) {
			foreach($params_extra as $key => $val) {
				$params[$key] = $val;
			}
		}

		$curl = curl_init();


		$url = 'https://licensify.com/validate/';
		$url .= '?' . http_build_query($params);

		curl_setopt_array($curl, array(
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL	=>	$url
		));
		
		$resp = curl_exec($curl);

		# debug
		if(!$resp = curl_exec($curl)) {
			die("err:" . curl_error($curl));
		}
			
		# handle
		$this->handle(json_decode($resp), $debug);
		curl_close($curl);
	}

	/*
		Handle response data from license server
	*/
	private function handle($resp, $debug) {

		if($debug)
			$this->pre($resp);

		# check for false product
		if(isset($resp->error)) {
			die("Licensify error: " . $resp->error);
		}

		# check valid response
		if($resp->status == 'valid') {
			# store local
			$this->write($resp);
		} else {
			# output
			echo $resp->output;
			# check for clear
			if(isset($resp->clear)) {
				echo $resp->clear;
			}

			die;
		}
	}

	/* 
		Write to local 
	*/
	private function write($data) {
		# make sure writeable
		# config for local key name
		$key_file = 's.key';
		$dir = dirname(__FILE__);
		chmod($dir, 0755);

		$f = fopen($key_file, 'w');
		fwrite($f, $this->enc('encrypt', json_encode($data->details->local)));	
	}

	/* 
		Enc
	*/
	private function enc($mode = 'encrypt', $string) {
		if($mode == 'encrypt') {
			$r = '';
               for($i = 1; $i<= strlen($string);$i++) {
               $char=substr($string,$i-1,1);
               $keychar=substr($this->crypt,($i % strlen($this->crypt))-1,1);
               $char=chr(ord($char)+ord($keychar));
               $r.=$char;
               } 
               return $r;				
		} else {
			$r = '';
               for($i=1;$i<=strlen($string);$i++) {
                   $char=substr($string,$i-1,1);
                   $keychar=substr($this->crypt,($i % strlen($this->crypt))-1,1);
                   $char=chr(ord($char)-ord($keychar));
                   $r.=$char;
               }
               return $r;				
		}
	}

/*
	End of Licensify Valdation Class
*/
}
?>