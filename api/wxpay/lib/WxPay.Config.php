<?php
include(dirname(dirname(dirname(dirname(__FILE__))))."/data/api/wxpay/wxpay_data.php");

define('WXAPPID',$wxpaydata['sy_wxpayappid']);
define('WXMCHID',$wxpaydata['sy_wxpaymchid']);
define('WXKEY',$wxpaydata['sy_wxpaykey']);
define('WXAPPSECRET',$wxpaydata['sy_wxappsecret']);

define('WXXCXAPPID',$wxpaydata['sy_xcxappid']);
define('WXXCXSECRET',$wxpaydata['sy_xcxsecret']);

class WxPayConfig
{

	const APPID = WXAPPID;
	const MCHID = WXMCHID;
	const KEY   = WXKEY;
	const APPSECRET = WXAPPSECRET;
	const XCXAPPID = WXXCXAPPID;
	const XCXSECRET = WXXCXSECRET;
	const SSLCERT_PATH = '../cert/apiclient_cert.pem';
	const SSLKEY_PATH = '../cert/apiclient_key.pem';
	
	const CURL_PROXY_HOST = "0.0.0.0";
	const CURL_PROXY_PORT = 0;
	
	const REPORT_LEVENL = 1;
}
