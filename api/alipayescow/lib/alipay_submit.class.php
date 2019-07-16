<?php

require_once("alipay_core.function.php");
require_once("alipay_md5.function.php");

class AlipaySubmit {

	var $alipay_config;

	var $alipay_gateway_new = 'https://mapi.alipay.com/gateway.do?';

	function __construct($alipay_config){
		$this->alipay_config = $alipay_config;
	}
    function AlipaySubmit($alipay_config) {
    	$this->__construct($alipay_config);
    }
	

	function buildRequestMysign($para_sort) {

		$prestr = createLinkstring($para_sort);
		
		$mysign = "";
		switch (strtoupper(trim($this->alipay_config['sign_type']))) {
			case "MD5" :
				$mysign = md5Sign($prestr, $this->alipay_config['key']);
				break;
			default :
				$mysign = "";
		}
		
		return $mysign;
	}


	function buildRequestPara($para_temp) {
		
		$para_filter = paraFilter($para_temp);

		
		$para_sort = argSort($para_filter);

		
		$mysign = $this->buildRequestMysign($para_sort);
		

		$para_sort['sign'] = $mysign;
		$para_sort['sign_type'] = strtoupper(trim($this->alipay_config['sign_type']));
		
		return $para_sort;
	}


	function buildRequestParaToString($para_temp) {
		
		$para = $this->buildRequestPara($para_temp);
		

		$request_data = createLinkstringUrlencode($para);
		
		return $request_data;
	}
	

	function buildRequestForm($para_temp, $method, $button_name) {

		$para = $this->buildRequestPara($para_temp);
		
		$sHtml = "<form id='alipaysubmit' name='alipaysubmit' style='display:none;' action='".$this->alipay_gateway_new."_input_charset=".trim(strtolower($this->alipay_config['input_charset']))."' method='".$method."'>";
		while (list ($key, $val) = each ($para)) {
            $sHtml.= "<input type='hidden' name='".$key."' value='".$val."'/>";
        }


        $sHtml = $sHtml."<input type='submit' value='".$button_name."'></form>";
		
		$sHtml = $sHtml."<script>document.forms['alipaysubmit'].submit();</script>";
		
		return $sHtml;
	}
	

	function buildRequestHttp($para_temp) {
		$sResult = '';
		

		$request_data = $this->buildRequestPara($para_temp);

	
		$sResult = getHttpResponsePOST($this->alipay_gateway_new, $this->alipay_config['cacert'],$request_data,trim(strtolower($this->alipay_config['input_charset'])));

		return $sResult;
	}
	

	function buildRequestHttpInFile($para_temp, $file_para_name, $file_name) {
		
		
		$para = $this->buildRequestPara($para_temp);
		$para[$file_para_name] = "@".$file_name;
		
		
		$sResult = getHttpResponsePOST($this->alipay_gateway_new, $this->alipay_config['cacert'],$para,trim(strtolower($this->alipay_config['input_charset'])));

		return $sResult;
	}
	

	function query_timestamp() {
		$url = $this->alipay_gateway_new."service=query_timestamp&partner=".trim(strtolower($this->alipay_config['partner']))."&_input_charset=".trim(strtolower($this->alipay_config['input_charset']));
		$encrypt_key = "";		

		$doc = new DOMDocument();
		$doc->load($url);
		$itemEncrypt_key = $doc->getElementsByTagName( "encrypt_key" );
		$encrypt_key = $itemEncrypt_key->item(0)->nodeValue;
		
		return $encrypt_key;
	}
}
?>