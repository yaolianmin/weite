<?php
/*
* $Author ：PHPYUN开发团队
*
* 官网: http://www.phpyun.com
*
* 版权所有 2009-2018 宿迁鑫潮信息技术有限公司，并保留所有权利。
*
* 软件声明：未经授权前提下，不得用于商业运营、二次开发以及任何形式的再次发布。
 */

error_reporting(0);

require_once ("classes/PayRequestHandler.class.php");

require_once(dirname(dirname(dirname(__FILE__)))."/data/api/tenpay/tenpay_data.php");
require_once(dirname(dirname(dirname(__FILE__)))."/config/db.config.php");
require_once(dirname(dirname(dirname(__FILE__)))."/config/db.safety.php");
require_once(dirname(dirname(dirname(__FILE__)))."/app/include/mysql.class.php");
require_once(dirname(dirname(dirname(__FILE__)))."/data/plus/config.php");

$db = new mysql($db_config['dbhost'], $db_config['dbuser'], $db_config['dbpass'], $db_config['dbname'], ALL_PS, $db_config['charset']);
if(!is_numeric($_POST['dingdan']))
{
	die;
}

$_COOKIE['uid']=(int)$_COOKIE['uid'];
$_POST['is_invoice']=(int)$_POST['is_invoice'];
$_POST['coupon']=(int)$_POST['coupon'];
$invoice_title=trim($_POST['invoice_title']);
$member_sql=$db->query("SELECT * FROM `".$db_config["def"]."member` WHERE `uid`='".$_COOKIE['uid']."' limit 1");
$member=$db->fetch_array($member_sql);
if($member['usertype'] != $_COOKIE['usertype']||md5($member['username'].$member['password'].$member['salt'])!=$_COOKIE['shell']){
	echo '登录信息验证错误，请重新登录！';die;
}
$sql=$db->query("select * from `".$db_config["def"]."company_order` where `order_id`='$_POST[dingdan]' AND `order_price`>=0");
$row=$db->fetch_array($sql);
if(!$row['uid'] || $row['uid']!=$_COOKIE['uid'])
{
	die;
}
if($_POST['coupon'] && $row['coupon']==""){
	$where="`uid`='".$_COOKIE['uid']."' and `id`='".$_POST['coupon']."' and `validity`>'".time()."'  and `status`='1' and `coupon_scope`<='".$row['order_price']."'";
	$cousql=$db->query("select * from `".$db_config["def"]."coupon_list` where ".$where);
	$coupon=$db->fetch_array($cousql);
	$row['order_price']=sprintf("%.2f", $row['order_price']-$coupon['coupon_amount']);
	if($row['order_price']<0){$row['order_price']='0.01';}
	if($coupon['id']&&$row['coupon']<'1'){
		$db->query("update `".$db_config[def]."coupon_list` set `status`='2',`xf_time`='".time()."' where `id`='".$coupon['id']."'");
		$db->query("update `".$db_config[def]."company_order` set `coupon`='".$_POST['coupon']."',`order_price`='".$row['order_price']."' where `id`='".$row['id']."'");
	}
}


if($invoice_title){
	$up_order=$db->query("update `".$db_config["def"]."company_order` set `is_invoice`='".$_POST['is_invoice']."',`order_bank`='bank' where `order_id`='".$row['order_id']."'");
	$db->fetch_array($up_order);
}
$bargainor_id = $tenpaydata[sy_tenpayid];

$key = $tenpaydata[sy_tenpaycode];

$return_url = $tenpaydata[sy_weburl]."/api/tenpay/return_url.php";

$strDate = date("Ymd");
$strTime = date("His");

$randNum = rand(1000, 9999);

$attach=$_POST[pay_type];

$strReq = $strTime . $randNum;

$sp_billno = $_POST[dingdan];

$transaction_id =trim($bargainor_id.$strDate.$strReq);

$total_fee = $row[order_price]*100;

$desc = "订单号：" . $transaction_id;

$reqHandler = new PayRequestHandler();
$reqHandler->init();
$reqHandler->setKey($key);
$reqHandler->setParameter("bargainor_id", $bargainor_id);			
$reqHandler->setParameter("transaction_id", $transaction_id);		
$reqHandler->setParameter("sp_billno", $sp_billno);					
$reqHandler->setParameter("total_fee", $total_fee);					
$reqHandler->setParameter("return_url", $return_url);				
$reqHandler->setParameter("desc", "订单号：" . $transaction_id);	    
$reqHandler->setParameter("attach", $attach);			        	

$reqUrl = $reqHandler->getRequestURL();



Header("Location:$reqUrl");
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>财付通即时到帐程序</title>
</head>
<body>
<script>//location.href='<?php echo $reqUrl;?>';</script>
</body>
</html>
