<?php
/* *
* $Author ：PHPYUN开发团队
*
* 官网: http://www.phpyun.com
*
* 版权所有 2009-2018 宿迁鑫潮信息技术有限公司，并保留所有权利。
*
* 软件声明：未经授权前提下，不得用于商业运营、二次开发以及任何形式的再次发布。
*/
class index_controller extends common
{
	function actMsg($msgUrl,$msg,$status=8){

		
			
		
			$this->ACT_msg($msgUrl, $msg,$status);
		
	}
	function getMsgUrl(){
		if(isMobileUser()){
			if($this->config['sy_wapdomain']!=""){
				if(strpos($this->config['sy_wapdomain'],$this->protocol)===false)
				{
					$msgUrl = $this->protocol.$this->config['sy_wapdomain'];
				}else{
					$msgUrl = $this->config['sy_wapdomain'];
				}

			}else{
				$msgUrl = $this->config['sy_weburl'].'/wap/';
			}
		}else{
			$msgUrl = $this->config['sy_weburl'];
		}
		return $msgUrl;
	}
	function index_action(){
		$code = $_GET['code'];
		$msgUrl = $this->getMsgUrl();
		if($_GET['login']!="1"){
			if($this->uid!=""&&$this->username!="" && empty($code)){
				$this->actMsg($msgUrl,"您已经登录了！");
			}
		}
		if($this->config['sy_qqlogin']!="1"){
			$this->actMsg($msgUrl,"对不起，QQ绑定已关闭！");
		}
		$this->seo('qqlogin');
	    $app_id = $this->config['sy_qqappid'];
	    $app_secret = $this->config['sy_qqappkey'];
	    $my_url = $this->config['sy_weburl']."/qqlogin.php";

		session_start();
	    if(empty($code)){
		     $_SESSION['state'] = md5(uniqid(rand(), TRUE));
		     $dialog_url = "https://graph.qq.com/oauth2.0/authorize?response_type=code&client_id="
		        . $app_id . "&redirect_uri=" . urlencode($my_url) . "&state="
		        . $_SESSION['state'];
		     echo("<script> top.location.href='" . $dialog_url . "'</script>");
	    }
	 	if($_GET['state'] == $_SESSION['state']){
		     $token_url = "https://graph.qq.com/oauth2.0/token?grant_type=authorization_code&"
		     . "client_id=" . $app_id . "&redirect_uri=" . urlencode($my_url)
		     . "&client_secret=" . $app_secret . "&code=" . $code;
			 if(!function_exists('curl_init')) {

				echo "请开启CURL函数，否则将无法进行下一步操作！";
				die;
			 }
			 $ch = curl_init();
			 curl_setopt($ch, CURLOPT_URL,$token_url);
			 curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  2);
			 curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
			 curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			 $response=curl_exec ($ch);
			 curl_close ($ch);
		     if (strpos($response, "callback") !== false){
		        $lpos = strpos($response, "(");
		        $rpos = strrpos($response, ")");
		        $response  = substr($response, $lpos + 1, $rpos - $lpos -1);
		        $msg = json_decode($response);
		        if (isset($msg->error)){
		           echo "<h3>error:</h3>" . $msg->error;
		           echo "<h3>msg  :</h3>" . $msg->error_description;
		           exit;
		        }
		     }
		     $params = array();
		     parse_str($response, $params);
		     $graph_url = "https://graph.qq.com/oauth2.0/me?access_token=".$params['access_token'];
			 $ch = curl_init();
			 curl_setopt($ch, CURLOPT_URL,$graph_url);
			 curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  2);
			 curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
			 curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			 $str=curl_exec ($ch);
			 curl_close ($ch);
		     if (strpos($str, "callback") !== false){
		        $lpos = strpos($str, "(");
		        $rpos = strrpos($str, ")");
		        $str  = substr($str, $lpos + 1, $rpos - $lpos -1);
		     }
		     $user = json_decode($str);
		     if (isset($user->error)){
		        echo "<h3>error:</h3>" . $user->error;
		        echo "<h3>msg  :</h3>" . $user->error_description;
		        exit;
		     }

	     if($user->openid!=""){
			$userinfo = $this->obj->DB_select_once("member","`qqid`='$user->openid'");
			if(is_array($userinfo)){
				$this->obj->DB_update_all("member","`login_date`='".time()."'","`uid`='".$userinfo[uid]."'");
				$logtime=date("Ymd",$userinfo['login_date']);
				$nowtime=date("Ymd",time());
				if($logtime!=$nowtime){
					$this->MODEL('integral')->get_integral_action($userinfo['uid'],"integral_login","会员登录");
				} 
				if($this->config['sy_uc_type']=="uc_center"){
					$this->uc_open();
					$user = uc_get_user($userinfo['username']);
					$ucsynlogin=uc_user_synlogin($user[0]);
					$this->cookie->add_cookie($userinfo['uid'],$userinfo['username'],$userinfo['salt'],$userinfo['email'],$userinfo['password'],$userinfo['usertype'],1,$userinfo['did']);
					$this->actMsg($msgUrl,"登录成功！".$ucsynlogin);
				}else{
					$this->cookie->unset_cookie();
					$this->cookie->add_cookie($userinfo['uid'],$userinfo['username'],$userinfo['salt'],$userinfo['email'],$userinfo['password'],$userinfo['usertype'],1,$userinfo['did']);
					$this->actMsg($msgUrl,"登录成功！");
				}
			}else{
				$_SESSION['qq']["openid"] = $user->openid;
				$_SESSION['qq']["tooken"] = $params['access_token'];
				$_SESSION['qq']["logininfo"] = "您已登录QQ，请绑定您的帐户！";
				if($this->uid){
					$this->obj->DB_update_all("member","`qqid`=''","`qqid`='".$_SESSION['qq']["openid"]."'");
					$this->obj->DB_update_all("member","`qqid`='".$_SESSION['qq']["openid"]."'","`uid`='".$this->uid."'");

					$this->actMsg($msgUrl."/member/index.php?c=binding","QQ 登录绑定成功！");

				}else{

					$GetUrl = "https://graph.qq.com/user/get_user_info?oauth_consumer_key=".$this->config['sy_qqappid']."&access_token=".$_SESSION['qq']['tooken']."&openid=".$_SESSION['qq']['openid']."&format=json";
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,$GetUrl);
					curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  2);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
					$str=curl_exec ($ch);
					curl_close ($ch);
					$user = json_decode($str,true);

					 

					if($user['nickname']){
						$_SESSION['qq']['nickname'] = $user['nickname'];
						$_SESSION['qq']['pic'] = $user['figureurl_qq_2'];
					}else{
						
						$this->actMsg($msgUrl,"用户信息获取失败，请重新登录QQ！");
					}

					header("location:".Url("qqconnect",array("c"=>"qqbind")));

				}
			}
	     }
	  }else{
		  echo("The state does not match. You may be a victim of CSRF.");
	  }
	}
	function qqbind_action(){
		session_start();
		if($_POST){
			
			
		
			if($_SESSION['qq']['openid']){
				$bindInfo['qqid'] =$_SESSION['qq']['openid'];
				$Member=$this->MODEL("userinfo");
				$return  = $Member->bindUser($_POST['username'],$_POST['password'],$bindInfo);
				if($return['error']=='1'){
					
					$userinfo = $return['info'];
					$this->cookie->add_cookie($userinfo['uid'],$userinfo['username'],$userinfo['salt'],$userinfo['email'],$userinfo['password'],$userinfo['usertype'],1,$userinfo['did']);
					$bind['url'] = $this->config['sy_weburl'].'/member/';
				}else{
					$bind['msg'] =$return['msg'];
				}
		
			}else{
			
				$bind['msg'] = 'QQ登录信息已失效，请重新登录！';
			}
			echo json_encode($bind);
		}else{
			$this->yunset('qqlogin');
			$this->yun_tpl(array('index'));
		}
		
		
		
	}
    function cert_action(){
    	$id=$_GET['id'];
		$arr=@explode("|",base64_decode($id));
		$arr[0] = intval($arr[0]);
		$arr[1] = intval($arr[1]);
		if($id && is_array($arr) && $arr[0] && $arr[2]==$this->config['coding']){
			$row=$this->obj->DB_select_once("company_cert","`uid`='".$arr[0]."' and `check2`='".$arr[1]."'");
			if(is_array($row)){
				
				$this->obj->DB_update_all("member","`email`=''","`email`='".$row['check']."'");
				$this->obj->DB_update_all("resume","`email_status`='0',`email`=''","`email`='".$row['check']."'");
				$this->obj->DB_update_all("company","`email_status`='0',`linkmail`=''","`linkmail`='".$row['check']."'");
				$this->obj->DB_update_all("lt_info","`email_status`='0',`email`=''","`email`='".$row['check']."'");
				
				
				$this->obj->DB_update_all("member","`email`='".$row['check']."'","`uid`='".$arr[0]."'");
				$id=$this->obj->DB_update_all("company_cert","`status`='1'","`uid`='".$arr[0]."' and `check2`='".$arr[1]."'");
				$user=$this->obj->DB_select_once("member","`uid`='".$arr[0]."'","usertype");
				if($user['usertype']=="3"){
					if($id){
						$this->obj->DB_update_all("lt_info","`email_status`='1',`email`='".$row['check']."'","`uid`='".$arr[0]."'");
					}
				}elseif($user['usertype']=="2"){
					if($id){
						$this->obj->DB_update_all("company","`email_status`='1',`linkmail`='".$row['check']."'","`uid`='".$arr[0]."'");
					}
				}elseif($user['usertype']=="1"){
					if($id){
						$this->obj->DB_update_all("resume","`email_status`='1',`email`='".$row['check']."'","`uid`='".$arr[0]."'");
					}
				}
				$pay=$this->obj->DB_select_once("company_pay","`pay_remark`='邮箱认证' and `com_id`='".$arr[0]."'");
				if(empty($pay)){
					$this->MODEL('integral')->get_integral_action($arr[0],"integral_emailcert","邮箱认证");
				}

				if($id){
					$UA = strtoupper($_SERVER['HTTP_USER_AGENT']);
					if(strpos($UA, 'WINDOWS NT') !== false){
						header("location:".$this->config['sy_weburl']."/index.php?m=register&c=ok&type=4");
					}else{
						$this->ACT_msg(Url('wap'),"认证成功！",8);
					}
				}else{
					$this->ACT_msg($this->config['sy_weburl'],"认证失败，联系管理员认证！",8);
				}
			}else{
				$this->ACT_msg($_SERVER['HTTP_REFERER'],"认证失败，请检查来路！",8);
			}
		}else{
			$this->ACT_msg($_SERVER['HTTP_REFERER'],"非法操作！",8);
		}
    }

	function mcert_action(){
    	$id=$_GET['id'];
		$arr=@explode("|",base64_decode($id));

		$arr[0] = intval($arr[0]);

		if($id && is_array($arr) && $arr[0] && $arr[2]==$this->config['coding']){
			$nid=$this->obj->DB_update_all("resume","`email_status`='1'","`uid`='".$arr[0]."'");
			$this->obj->DB_update_all("member","`email_status`='1'","`uid`='".$arr[0]."'");
			$nid?$this->ACT_msg(Url('login'),"激活成功，请登录！",9):$this->ACT_msg($this->config['sy_weburl'],"激活失败，联系管理员认证！",8);
		}else{
			$this->ACT_msg($_SERVER['HTTP_REFERER'],"非法操作！",8);
		}
    }
	function JsonArray($array){
		if(is_object($array)){
		   $array = (array)$array;
		}
	     if(is_array($array)){
           foreach($array as $key=>$value){
           	 $array[$key] = $this->JsonArray($value);
           }
	     }
     return $array;
    }

	function checkuser($username,$name){
		$user = $this->obj->DB_select_once("member","`username`='".$username."'","`uid`");
		if($user['uid']){
			$name.="_".rand(1000,9999);
			return $this->checkuser($name,$username);
		}else{
			return $username;
		}
	}
}

