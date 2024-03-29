<?php
ini_set('date.timezone','Asia/Shanghai');
error_reporting(E_ERROR);

require_once "lib/WxPay.Api.php";
require_once 'lib/WxPay.Notify.php';



class PayNotifyCallBack extends WxPayNotify
{
	
	public function Queryorder($transaction_id)
	{
		$input = new WxPayOrderQuery();
		$input->SetTransaction_id($transaction_id);
		$result = WxPayApi::orderQuery($input);
		if(array_key_exists("return_code", $result)
			&& array_key_exists("result_code", $result)
			&& $result["return_code"] == "SUCCESS"
			&& $result["result_code"] == "SUCCESS")
		{
			return $result["out_trade_no"];
		}
		return false;
	}
	

	public function NotifyProcess($data, &$msg)
	{
		$notfiyOutput = array();
		
		if(!array_key_exists("transaction_id", $data)){
			$msg = "输入参数不正确";
			return false;
		}
	
		$dingdan = $this->Queryorder($data["transaction_id"]);
		if(!$dingdan){
			$msg = "订单查询失败";
			return false;
		}else{
			require_once(dirname(dirname(dirname(__FILE__)))."/global.php");
			require_once(APP_PATH.'app/public/common.php');
			require_once(LIB_PATH.'ApiPay.class.php');

			$wxPay = new apipay($phpyun,$db,$db_config['def'],'index');
			
			$wxPay->payAll($dingdan,$total_fee,'wxpay');
		}
		return true;
	}
}

$notify = new PayNotifyCallBack();
$notify->Handle(false);
