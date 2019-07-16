<?php


require_once("alipay_core.function.php");
require_once("alipay_md5.function.php");

class AlipayNotify {

	var $https_verify_url = 'https://mapi.alipay.com/gateway.do?service=notify_verify&';

	var $http_verify_url = 'http://notify.alipay.com/trade/notify_query.do?';
	var $alipay_config;

	function __construct($alipay_config){
		$this->alipay_config = $alipay_config;
	}
    function AlipayNotify($alipay_config) {
    	$this->__construct($alipay_config);
    }

	function verifyNotify(){
		if(empty($_POST)) {
			return false;
		}
		else {
			
			$isSign = $this->getSignVeryfy($_POST, $_POST["sign"]);
			
			$responseTxt = 'true';
			if (! empty($_POST["notify_id"])) {$responseTxt = $this->getResponse($_POST["notify_id"]);}
			

			if (preg_match("/true$/i",$responseTxt) && $isSign) {
				return true;
			} else {
				return false;
			}
		}
	}
	

	function verifyReturn(){
		if(empty($_GET)) {
			return false;
		}
		else {
			
			$isSign = $this->getSignVeryfy($_GET, $_GET["sign"]);
			
			$responseTxt = 'true';
			if (! empty($_GET["notify_id"])) {$responseTxt = $this->getResponse($_GET["notify_id"]);}
			

			if (preg_match("/true$/i",$responseTxt) && $isSign) {
				return true;
			} else {
				return false;
			}
		}
	}
	

	function getSignVeryfy($para_temp, $sign) {
		
		$para_filter = paraFilter($para_temp);
		

		$para_sort = argSort($para_filter);

		$prestr = createLinkstring($para_sort);
		
		$isSgin = false;
		switch (strtoupper(trim($this->alipay_config['sign_type']))) {
			case "MD5" :
				$isSgin = md5Verify($prestr, $sign, $this->alipay_config['key']);
				break;
			default :
				$isSgin = false;
		}
		
		return $isSgin;
	}


	function getResponse($notify_id) {
		$transport = strtolower(trim($this->alipay_config['transport']));
		$partner = trim($this->alipay_config['partner']);
		$veryfy_url = '';
		if($transport == 'https') {
			$veryfy_url = $this->https_verify_url;
		}
		else {
			$veryfy_url = $this->http_verify_url;
		}
		$veryfy_url = $veryfy_url."partner=" . $partner . "&notify_id=" . $notify_id;
		$responseTxt = getHttpResponseGET($veryfy_url, $this->alipay_config['cacert']);
		
		return $responseTxt;
	}
}
?>
