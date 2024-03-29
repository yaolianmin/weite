<?php
require_once "WxPay.Exception.php";
require_once "WxPay.Config.php";
require_once "WxPay.Data.php";


class WxPayApi
{

	public static function unifiedOrder($inputObj, $timeOut = 6, $type=1)
	{
		$url = "https://api.mch.weixin.qq.com/pay/unifiedorder";
	
		if(!$inputObj->IsOut_trade_noSet()) {
			throw new WxPayException("缺少统一支付接口必填参数out_trade_no！");
		}else if(!$inputObj->IsBodySet()){
			throw new WxPayException("缺少统一支付接口必填参数body！");
		}else if(!$inputObj->IsTotal_feeSet()) {
			throw new WxPayException("缺少统一支付接口必填参数total_fee！");
		}else if(!$inputObj->IsTrade_typeSet()) {
			throw new WxPayException("缺少统一支付接口必填参数trade_type！");
		}
		
		
		if($inputObj->GetTrade_type() == "JSAPI" && !$inputObj->IsOpenidSet()){
			throw new WxPayException("统一支付接口中，缺少必填参数openid！trade_type为JSAPI时，openid为必填参数！");
		}
		if($inputObj->GetTrade_type() == "NATIVE" && !$inputObj->IsProduct_idSet()){
			throw new WxPayException("统一支付接口中，缺少必填参数product_id！trade_type为JSAPI时，product_id为必填参数！");
		}
		

		if(!$inputObj->IsNotify_urlSet()){
			$inputObj->SetNotify_url(WxPayConfig::NOTIFY_URL);
		}
		if ($type==1){
		    $inputObj->SetAppid(WxPayConfig::APPID);
		}else{
		    $inputObj->SetAppid(WxPayConfig::XCXAPPID);
		}
		
		$inputObj->SetMch_id(WxPayConfig::MCHID);
		$inputObj->SetSpbill_create_ip(fun_ip_get());  
		$inputObj->SetNonce_str(self::getNonceStr());
		
		
		$inputObj->SetSign();
		$xml = $inputObj->ToXml();
		
		$startTimeStamp = self::getMillisecond();
		
		$response = self::postXmlCurl($xml, $url, false, $timeOut);

		$result = WxPayResults::Init($response);
		self::reportCostTime($url, $startTimeStamp, $result);
		
		return $result;
	}
	

	public static function orderQuery($inputObj, $timeOut = 6)
	{
		$url = "https://api.mch.weixin.qq.com/pay/orderquery";
	
		if(!$inputObj->IsOut_trade_noSet() && !$inputObj->IsTransaction_idSet()) {
			throw new WxPayException("订单查询接口中，out_trade_no、transaction_id至少填一个！");
		}
		$inputObj->SetAppid(WxPayConfig::APPID);
		$inputObj->SetMch_id(WxPayConfig::MCHID);
		$inputObj->SetNonce_str(self::getNonceStr());
		
		$inputObj->SetSign();
		$xml = $inputObj->ToXml();
		
		$startTimeStamp = self::getMillisecond();
		$response = self::postXmlCurl($xml, $url, false, $timeOut);
		$result = WxPayResults::Init($response);
		self::reportCostTime($url, $startTimeStamp, $result);
		
		return $result;
	}
	

	public static function closeOrder($inputObj, $timeOut = 6)
	{
		$url = "https://api.mch.weixin.qq.com/pay/closeorder";
		
		if(!$inputObj->IsOut_trade_noSet()) {
			throw new WxPayException("订单查询接口中，out_trade_no必填！");
		}
		$inputObj->SetAppid(WxPayConfig::APPID);
		$inputObj->SetMch_id(WxPayConfig::MCHID);
		$inputObj->SetNonce_str(self::getNonceStr());
		
		$inputObj->SetSign();
		$xml = $inputObj->ToXml();
		
		$startTimeStamp = self::getMillisecond();
		$response = self::postXmlCurl($xml, $url, false, $timeOut);
		$result = WxPayResults::Init($response);
		self::reportCostTime($url, $startTimeStamp, $result);
		
		return $result;
	}


	public static function refund($inputObj, $timeOut = 6)
	{
		$url = "https://api.mch.weixin.qq.com/secapi/pay/refund";

		if(!$inputObj->IsOut_trade_noSet() && !$inputObj->IsTransaction_idSet()) {
			throw new WxPayException("退款申请接口中，out_trade_no、transaction_id至少填一个！");
		}else if(!$inputObj->IsOut_refund_noSet()){
			throw new WxPayException("退款申请接口中，缺少必填参数out_refund_no！");
		}else if(!$inputObj->IsTotal_feeSet()){
			throw new WxPayException("退款申请接口中，缺少必填参数total_fee！");
		}else if(!$inputObj->IsRefund_feeSet()){
			throw new WxPayException("退款申请接口中，缺少必填参数refund_fee！");
		}else if(!$inputObj->IsOp_user_idSet()){
			throw new WxPayException("退款申请接口中，缺少必填参数op_user_id！");
		}
		$inputObj->SetAppid(WxPayConfig::APPID);
		$inputObj->SetMch_id(WxPayConfig::MCHID);
		$inputObj->SetNonce_str(self::getNonceStr());
		
		$inputObj->SetSign();
		$xml = $inputObj->ToXml();
		$startTimeStamp = self::getMillisecond();
		$response = self::postXmlCurl($xml, $url, true, $timeOut);
		$result = WxPayResults::Init($response);
		self::reportCostTime($url, $startTimeStamp, $result);
		
		return $result;
	}
	

	public static function refundQuery($inputObj, $timeOut = 6)
	{
		$url = "https://api.mch.weixin.qq.com/pay/refundquery";
	
		if(!$inputObj->IsOut_refund_noSet() &&
			!$inputObj->IsOut_trade_noSet() &&
			!$inputObj->IsTransaction_idSet() &&
			!$inputObj->IsRefund_idSet()) {
			throw new WxPayException("退款查询接口中，out_refund_no、out_trade_no、transaction_id、refund_id四个参数必填一个！");
		}
		$inputObj->SetAppid(WxPayConfig::APPID);
		$inputObj->SetMch_id(WxPayConfig::MCHID);
		$inputObj->SetNonce_str(self::getNonceStr());
		
		$inputObj->SetSign();
		$xml = $inputObj->ToXml();
		
		$startTimeStamp = self::getMillisecond();
		$response = self::postXmlCurl($xml, $url, false, $timeOut);
		$result = WxPayResults::Init($response);
		self::reportCostTime($url, $startTimeStamp, $result);
		
		return $result;
	}
	

	public static function downloadBill($inputObj, $timeOut = 6)
	{
		$url = "https://api.mch.weixin.qq.com/pay/downloadbill";

		if(!$inputObj->IsBill_dateSet()) {
			throw new WxPayException("对账单接口中，缺少必填参数bill_date！");
		}
		$inputObj->SetAppid(WxPayConfig::APPID);
		$inputObj->SetMch_id(WxPayConfig::MCHID);
		$inputObj->SetNonce_str(self::getNonceStr());
		
		$inputObj->SetSign();
		$xml = $inputObj->ToXml();
		
		$response = self::postXmlCurl($xml, $url, false, $timeOut);
		if(substr($response, 0 , 5) == "<xml>"){
			return "";
		}
		return $response;
	}
	

	public static function micropay($inputObj, $timeOut = 10)
	{
		$url = "https://api.mch.weixin.qq.com/pay/micropay";

		if(!$inputObj->IsBodySet()) {
			throw new WxPayException("提交被扫支付API接口中，缺少必填参数body！");
		} else if(!$inputObj->IsOut_trade_noSet()) {
			throw new WxPayException("提交被扫支付API接口中，缺少必填参数out_trade_no！");
		} else if(!$inputObj->IsTotal_feeSet()) {
			throw new WxPayException("提交被扫支付API接口中，缺少必填参数total_fee！");
		} else if(!$inputObj->IsAuth_codeSet()) {
			throw new WxPayException("提交被扫支付API接口中，缺少必填参数auth_code！");
		}
		
		$inputObj->SetSpbill_create_ip($_SERVER['REMOTE_ADDR']);
		$inputObj->SetAppid(WxPayConfig::APPID);
		$inputObj->SetMch_id(WxPayConfig::MCHID);
		$inputObj->SetNonce_str(self::getNonceStr());
		
		$inputObj->SetSign();
		$xml = $inputObj->ToXml();
		
		$startTimeStamp = self::getMillisecond();
		$response = self::postXmlCurl($xml, $url, false, $timeOut);
		$result = WxPayResults::Init($response);
		self::reportCostTime($url, $startTimeStamp, $result);
		
		return $result;
	}
	

	public static function reverse($inputObj, $timeOut = 6)
	{
		$url = "https://api.mch.weixin.qq.com/secapi/pay/reverse";
	
		if(!$inputObj->IsOut_trade_noSet() && !$inputObj->IsTransaction_idSet()) {
			throw new WxPayException("撤销订单API接口中，参数out_trade_no和transaction_id必须填写一个！");
		}
		
		$inputObj->SetAppid(WxPayConfig::APPID);
		$inputObj->SetMch_id(WxPayConfig::MCHID);
		$inputObj->SetNonce_str(self::getNonceStr());
		
		$inputObj->SetSign();
		$xml = $inputObj->ToXml();
		
		$startTimeStamp = self::getMillisecond();
		$response = self::postXmlCurl($xml, $url, true, $timeOut);
		$result = WxPayResults::Init($response);
		self::reportCostTime($url, $startTimeStamp, $result);
		
		return $result;
	}
	

	public static function report($inputObj, $timeOut = 1)
	{
		$url = "https://api.mch.weixin.qq.com/payitil/report";

		if(!$inputObj->IsInterface_urlSet()) {
			throw new WxPayException("接口URL，缺少必填参数interface_url！");
		} if(!$inputObj->IsReturn_codeSet()) {
			throw new WxPayException("返回状态码，缺少必填参数return_code！");
		} if(!$inputObj->IsResult_codeSet()) {
			throw new WxPayException("业务结果，缺少必填参数result_code！");
		} if(!$inputObj->IsUser_ipSet()) {
			throw new WxPayException("访问接口IP，缺少必填参数user_ip！");
		} if(!$inputObj->IsExecute_time_Set()) {
			throw new WxPayException("接口耗时，缺少必填参数execute_time_！");
		}
		$inputObj->SetAppid(WxPayConfig::APPID);
		$inputObj->SetMch_id(WxPayConfig::MCHID);
		$inputObj->SetUser_ip($_SERVER['REMOTE_ADDR']);
		$inputObj->SetTime(date("YmdHis")); 
		$inputObj->SetNonce_str(self::getNonceStr());
		
		$inputObj->SetSign();
		$xml = $inputObj->ToXml();
		
		$startTimeStamp = self::getMillisecond();
		$response = self::postXmlCurl($xml, $url, false, $timeOut);
		return $response;
	}
	

	public static function bizpayurl($inputObj, $timeOut = 6)
	{
		if(!$inputObj->IsProduct_idSet()){
			throw new WxPayException("生成二维码，缺少必填参数product_id！");
		}
		
		$inputObj->SetAppid(WxPayConfig::APPID);
		$inputObj->SetMch_id(WxPayConfig::MCHID);
		$inputObj->SetTime_stamp(time()); 
		$inputObj->SetNonce_str(self::getNonceStr());
		
		$inputObj->SetSign();
		
		return $inputObj->GetValues();
	}
	

	public static function shorturl($inputObj, $timeOut = 6)
	{
		$url = "https://api.mch.weixin.qq.com/tools/shorturl";

		if(!$inputObj->IsLong_urlSet()) {
			throw new WxPayException("需要转换的URL，签名用原串，传输需URL encode！");
		}
		$inputObj->SetAppid(WxPayConfig::APPID);
		$inputObj->SetMch_id(WxPayConfig::MCHID);
		$inputObj->SetNonce_str(self::getNonceStr());
		
		$inputObj->SetSign();
		$xml = $inputObj->ToXml();
		
		$startTimeStamp = self::getMillisecond();
		$response = self::postXmlCurl($xml, $url, false, $timeOut);
		$result = WxPayResults::Init($response);
		self::reportCostTime($url, $startTimeStamp, $result);
		
		return $result;
	}
	

	public static function notify($callback, &$msg)
	{

            $xml = file_get_contents("php://input");  

		try {
			$result = WxPayResults::Init($xml);
		} catch (WxPayException $e){
			$msg = $e->errorMessage();
			return false;
		}
		
		return call_user_func($callback, $result);
	}
	

	public static function getNonceStr($length = 32) 
	{
		$chars = "abcdefghijklmnopqrstuvwxyz0123456789";  
		$str ="";
		for ( $i = 0; $i < $length; $i++ )  {  
			$str .= substr($chars, mt_rand(0, strlen($chars)-1), 1);  
		} 
		return $str;
	}
	

	public static function replyNotify($xml)
	{
		echo $xml;
	}
	

	private static function reportCostTime($url, $startTimeStamp, $data)
	{
		
		if(WxPayConfig::REPORT_LEVENL == 0){
			return;
		} 
		
		if(WxPayConfig::REPORT_LEVENL == 1 &&
			 array_key_exists("return_code", $data) &&
			 $data["return_code"] == "SUCCESS" &&
			 array_key_exists("result_code", $data) &&
			 $data["result_code"] == "SUCCESS")
		 {
		 	return;
		 }
		 
		
		$endTimeStamp = self::getMillisecond();
		$objInput = new WxPayReport();
		$objInput->SetInterface_url($url);
		$objInput->SetExecute_time_($endTimeStamp - $startTimeStamp);
		
		if(array_key_exists("return_code", $data)){
			$objInput->SetReturn_code($data["return_code"]);
		}
		
		if(array_key_exists("return_msg", $data)){
			$objInput->SetReturn_msg($data["return_msg"]);
		}

		if(array_key_exists("result_code", $data)){
			$objInput->SetResult_code($data["result_code"]);
		}

		if(array_key_exists("err_code", $data)){
			$objInput->SetErr_code($data["err_code"]);
		}
	
		if(array_key_exists("err_code_des", $data)){
			$objInput->SetErr_code_des($data["err_code_des"]);
		}
		if(array_key_exists("out_trade_no", $data)){
			$objInput->SetOut_trade_no($data["out_trade_no"]);
		}
		if(array_key_exists("device_info", $data)){
			$objInput->SetDevice_info($data["device_info"]);
		}
		
		try{
			self::report($objInput);
		} catch (WxPayException $e){
		}
	}

	private static function postXmlCurl($xml, $url, $useCert = false, $second = 30)
	{		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_TIMEOUT, $second);
		
		if(WxPayConfig::CURL_PROXY_HOST != "0.0.0.0" 
			&& WxPayConfig::CURL_PROXY_PORT != 0){
			curl_setopt($ch,CURLOPT_PROXY, WxPayConfig::CURL_PROXY_HOST);
			curl_setopt($ch,CURLOPT_PROXYPORT, WxPayConfig::CURL_PROXY_PORT);
		}
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
		curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		
		if($useCert == true){
			curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
			curl_setopt($ch,CURLOPT_SSLCERT, WxPayConfig::SSLCERT_PATH);
			curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
			curl_setopt($ch,CURLOPT_SSLKEY, WxPayConfig::SSLKEY_PATH);
		}
		
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
		
		$data = curl_exec($ch);
		
		if($data){
			curl_close($ch);
			return $data;
		} else { 
			$error = curl_errno($ch);
			
			curl_close($ch);


		}
	}
	
	private static function getMillisecond()
	{
		$time = explode ( " ", microtime () );
		$time = $time[1] . ($time[0] * 1000);
		$time2 = explode( ".", $time );
		$time = $time2[0];
		return $time;
	}
}

