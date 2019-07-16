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
class index_controller extends common{
	function index_action(){
		if($this->uid != "" && $this->username != ""){
			if($_GET['type']=="out"){       
				if($this->config['sy_uc_type']=="uc_center"){
					$M=$this->MODEL();
					$M->uc_open();
					$logout = uc_user_synlogout();
				}elseif($this->config['sy_pw_type']){
					include(APP_PATH."/api/pw_api/pw_client_class_phpapp.php");
					$username=$_SESSION['username'];
					$pw=new PwClientAPI($username,"","");
					$logout=$pw->logout();
					$this->cookie->unset_cookie();
				}else{
					$this->cookie->unset_cookie();
				}
			}else{
				$this->ACT_msg($this->config['sy_weburl']."/member", "您已经登录了！");
			}
		}
		if($_GET['backurl']=='1'){
			$this->cookie->setCookie("backurl",$_SERVER['HTTP_REFERER'],time()+60);
		}
		if(!$_GET['usertype']){
			$_GET['usertype']=1;
		}
    $this->yunset("cookie", $_COOKIE['checkurl']);
		$this->yunset("usertype",(int)$_GET['usertype']);
		$this->yunset("loginname",$_COOKIE['loginname']);
		$this->seo("login");
		$this->yun_tpl(array('index'));
	}
	
	function loginsave_action(){
		$username=$_POST['username'];
		if($this->uid > 0 && $this->username!=""){
			if($this->usertype=='1'){
				$this->layer_msg('您现在是个人会员登录状态!');
			}elseif($this->usertype=='2'){
				$this->layer_msg('您现在是企业会员登录状态!');
			}elseif($this->usertype=='3'){
				$this->layer_msg('您现在是猎头会员登录状态!');
			}
		}
		
		if($_POST['path']!="index" && !$_POST['act_login']){
			if(strstr($this->config['code_web'],'前台登录')){
		    session_start();
		    if(!gtauthcode($this->config,'',$this->config['code_kind'])){
			    if ($this->config['code_kind']=='3'){
						$this->layer_msg('请点击按钮进行验证!');
			    }else{
			      $this->layer_msg('验证码错误!');
			    }
			  }
			}
		}
		
		if($this->config['sy_msg_isopen'] && $this->config['sy_msg_login'] && $_POST['act_login']){
			if(!CheckMoblie($username)){
				$this->layer_msg('手机号码不正确!');
			}
			if(!is_array($this->FetchMoblie($username))){
				$this->layer_msg('手机号码不存在!');
			}
			$where=array("moblie"=> $username);
		}else {
		 	if(CheckRegUser($username)==false && CheckRegEmail($username)==false && ($username!="")){
				$this->layer_msg('用户名包含特殊字符或为空!');
			}
			$where=array("username"=> $username);
		}
		
		$Member=$this->MODEL("userinfo");

		if($this->config['sy_uc_type']=="uc_center"  && !$_POST['act_login']){
			$ucinfo = $this->uc_open();
			

				$uname = $username;
			

			list($uid, $uname, $password, $email) = uc_user_login($uname, $_POST['password']);
			if($uid=='-1'){
				$user = $Member->GetMemberOne(array("username"=>$username),array("field"=>"username,email,uid,password,salt"));
				$pass = md5(md5($_POST['password']).$user['salt']);
				if($pass==$user['password']){
					$uid = $user['uid'];
					$uid = uc_user_register($user['username'],$_POST['password'],$user['email']);
					$uc_user_register_msg_arr = array( 
								 '-1' => '当前用户名不合法',
					       '-2' => '当前用户名包含论坛禁止的词语',
					       '-4' => '当前用户 Email 格式不符合论坛规则！',
					       '-5' => '当前用户 Email 论坛不允许注册！',
					       '-6' => '当前用户 Email 与论坛其他用户重复！'
					      );
					if(array_key_exists($uid,$uc_user_register_msg_arr)){
						$this->layer_msg($uc_user_register_msg_arr[$uid],9,0,'',2);
					}
					list($uid, $username, $password, $email) = uc_user_login($uname, $_POST['password']);
				}else{
					$this->layer_msg('账户或密码错误!');
				}
			}
			if($uid > 0) {
				$ucsynlogin=uc_user_synlogin($uid);
				$msg =  '登录成功！';
				$user = $Member->GetMemberOne(array("username"=>$username),array("field"=>"`uid`,`usertype`,`email_status`,`status`"));
				
				if(!empty($user)){
					
					if (session_id() == ""){session_start();}
					if($_SESSION['qq']['openid']){
						$Member->UpdateMember(array("qqid"=>$_SESSION['qq']['openid']),array("username"=>$username));
						unset($_SESSION['qq']);
					}
					if($_SESSION['wx']['openid']){
						$udate = array('wxid'=>$_SESSION['wx']['openid']);
						if($_SESSION['wx']['unionid']){
							$udate['unionid']  = $_SESSION['wx']['unionid'];
						}
						$Member->UpdateMember($udate,array("username"=>$username));
						unset($_SESSION['wx']);
					}
					if($_SESSION['sina']['openid']){
						$Member->UpdateMember(array("sinaid"=>$_SESSION['sina']['openid']),array("username"=>$username));
						unset($_SESSION['sina']);
					}
					if(!$user['usertype']){
						$this->cookie->unset_cookie();
						$this->cookie->setcookie("username",$username,time()+3600);
						$this->cookie->setcookie("password",$_POST['password'],time()+3600);
						$this->layer_msg($ucsynlogin,9,0,Url("login",array("c"=>"utype"),"1"),2,3);
					}
					
					if($user['status']=="2"){
						$this->layer_msg("您的账号已被锁定!",9,0,Url("register",array("c"=>"ok","type"=>2),"1"),2);
					}
					
					if($user['usertype']=="1" && $this->config['user_state']!="1" && $user['status']!="1"){
						$this->layer_msg("您还没有通过审核!",9,0,Url("register",array("c"=>"ok","type"=>3),"1"),2,0);
					}
					if($user['usertype']=="2" && $this->config['com_status']!="1" && $user['status']!="1"){
						$this->layer_msg("您还没有通过审核!",9,0,Url("register",array("c"=>"ok","type"=>3),"1"),2,1);
					}
					if($user['usertype']=="3" && $this->config['lt_status']!="1" && $user['status']!="1"){
						$this->layer_msg("您还没有通过审核!",9,0,Url("register",array("c"=>"ok","type"=>3),"1"),2,1);
					}
					if($user['usertype']=="4" && $this->config['px_status']!="1" && $user['status']!="1"){
						$this->layer_msg("您还没有通过审核!",9,0,Url("register",array("c"=>"ok","type"=>3),"1"),2,1);
					}
					
					if($this->config['user_status']=="1"){
						if($user['usertype']=='1'){
							$Resume=$this->MODEL("resume");
							$info=$Resume->SelectResumeOne(array("uid"=>$user['uid']),"`name`,`email_status`,`birthday`");
							if($info['email_status']!="1"){
							    $this->layer_msg('您的账户还未激活，请先激活!',9,0,Url("activate",'',"1"),2);
							}
						}
					}
					
					if($_POST['loginname']){
						$this->cookie->setcookie("loginname",$username,time()+8640000);
					}
					
					if($user['usertype']=="2"){$this->autoupjob($user['uid'],$user['usertype']);}
					
				}else{
					$this->cookie->unset_cookie();
					$this->cookie->setcookie("username",$username,time()+3600);
					$this->cookie->setcookie("password",$_POST['password'],time()+3600);
					$this->layer_msg($ucsynlogin,9,0,Url("login",array("c"=>"utype"),"1"),2,3);
				}
				$this->layer_msg($ucsynlogin,9,0,$this->config['sy_weburl']."/member",2,2);

			} elseif($uid == -2) {
				$msg =  '密码错误';
			} elseif($uid == -3)  {
				$msg = '论坛安全提问错误!';
			}else{
				$msg = '登录失败!';
			}
			$this->layer_msg($msg);
		}else{
			$user = $Member->GetMemberOne($where,array("field"=>"`pw_repeat`,`pwuid`,`uid`,`username`,`salt`,`email`,`password`,`usertype`,`status`,`email_status`,`did`,`login_date`"));
			if(is_array($user)){
				if($this->config['sy_pw_type']=="pw_center"){
					if($user['pw_repeat']!="1"){
						include(APP_PATH."/api/pw_api/pw_client_class_phpapp.php");
						$pw=new PwClientAPI($username,$_POST['password'],"");
						$pwuser =$pw->user_login();
						if($pwuser['uid']>0){
							if(empty($user)){
								
								$user = $this->newuser($Member,$pwuser['username'],$pwuser['password'],$pwuser['email'],$user['usertype'],$pwuser['uid'],$qqid);
							}else if($pwuser['uid']==$user['pwuid']){
								$pwrows=$pw->login($pwuser['uid']);
								$this->cookie->add_cookie($user['uid'],$user['username'],$user['salt'],$user['email'],$user['password'],$user['usertype'],$_POST['loginname'],$user['did']);
								$logtime=date("Ymd",$user['login_date']);
								$nowtime=date("Ymd",time());
								if($logtime!=$nowtime){
									$this->MODEL('integral')->get_integral_action($user['uid'],"integral_login","会员登录");
								} 
								$this->layer_msg('登录成功!',9,0,Url("login",array("c"=>"utype"),"1"),2,2);
							}else{
								$Member->UpdateMember(array("pw_repeat"=>"1"),array("uid"=>$user['uid']));
							}
						}
					}
				} elseif ($this->config['sy_msg_isopen'] && $this->config['sy_msg_login'] && $_POST['act_login']) {
					$cert_validity = 1800;		
					$cert_arr = $this->obj->DB_select_once("company_cert","`uid`='".$user['uid']."' and type='2' order by id desc");
					if (is_array($cert_arr)) {
						if((time()-$cert_arr['ctime']) <= $cert_validity){
						 	$res = $_POST['password'] == $cert_arr['check2'] ? true : false;
						 	if($res == false){
						 		$this->layer_msg('短信验证码错误！'); 
						 	}
						}else {
						 	$this->layer_msg('验证码验证超时，请重新点击发送验证码！'); 
						}
					}else {
					 	$this->layer_msg('验证码发送不成功，请重新点击发送短信验证码！'); 
					}
				}else{
					$res = md5(md5($_POST['password']).$user['salt']) == $user['password'] ? true : false;
				}
				
				if($res){
					
					if($user['status']=="2"){
						$this->layer_msg('您的账号已被锁定!',9,0,Url("register",array("c"=>"ok","type"=>2),"1"),2);
					}
					
					if($user['usertype']=='1'){
						$Resume=$this->MODEL("resume");
						$info=$Resume->SelectResumeOne(array("uid"=>$user['uid']),"`name`,`email_status`,`birthday`");
						if($this->config['user_status']=="1" && $info['email_status']!="1"){
							$this->layer_msg('您的账户还未激活，请先激活!',9,0,Url("activate",'',"1"),2);
						}

						if($this->config['user_state']!="1" && $user['status']!="1"){
							$this->layer_msg("您还没有通过审核!",9,0,Url("register",array("c"=>"ok","type"=>3),"1"),2,0);
						}
					}elseif($user['usertype']=='2'){
						if($this->config['com_status']!="1" && $user['status']!="1"){
							
							
						}
						$Company=$this->MODEL("company");
						$info=$Company->GetCompanyInfo(array("uid"=>$user['uid']),array("field"=>'name'));
						$this->autoupjob($user['uid'],$user['usertype']);
					}elseif($user['usertype']=='3'){
						if($this->config['lt_status']!="1" && $user['status']!="1"){
							$this->layer_msg('您还没有通过审核!',9,0,Url("register",array("c"=>"ok","type"=>3),"1"),2,1);
						}
						$Lietou=$this->MODEL("lietou");
						$info=$Lietou->GetLtinfo(array("uid"=>$user['uid']),array("field"=>"`realname` as `name`"));
					}elseif($user['usertype']=='4'){
						if($this->config['px_status']!="1" && $user['status']!="1"){
							$this->layer_msg('您还没有通过审核!',9,0,Url("register",array("c"=>"ok","type"=>3),"1"),2,1);
						}
						$Train=$this->MODEL("train");
						$info=$Train->GetTrainInfo(array("uid"=>$user['uid']),array("field"=>"`name`"));
					}
					
					
					if($qqid){
						$Member->UpdateMember(array("qqid"=>$qqid,"username"=>$username),array("uid"=>$user['uid']));
					}
					if (session_id() == ""){session_start();}
					if($_SESSION['qq']['openid']){
						$Member->UpdateMember(array("qqid"=>$_SESSION['qq']['openid']),array("username"=>$username));
						unset($_SESSION['qq']);
					}
					
					if($_SESSION['wx']['openid']){
						$udate = array('wxid'=>$_SESSION['wx']['openid']);
						if($_SESSION['wx']['unionid']){
							$udate['unionid']  = $_SESSION['wx']['unionid'];
						}
						$Member->UpdateMember($udate,array("username"=>$username));
						unset($_SESSION['wx']);
					}
					
					if($_SESSION['sina']['openid']){
						$Member->UpdateMember(array("sinaid"=>$_SESSION['sina']['openid']),array("username"=>$username));
						unset($_SESSION['sina']);
					}
					
					$ip = fun_ip_get();
					$Member->UpdateMember(array("login_ip"=>$ip,"login_date"=> time(),"`login_hits`=`login_hits`+1"),array("uid"=>$user['uid']));
					
					$state_content = "登录成功";
					$this->obj->DB_insert_once("login_log","`uid`='".$user['uid']."',`content`='".$state_content."',`ip`='".$ip."',`usertype`='".$user['usertype']."',`ctime`='".time()."'");
					
					$this->cookie->unset_cookie();
					$this->cookie->add_cookie($user['uid'],$user['username'],$user['salt'],$user['email'],$user['password'],$user['usertype'],$_POST['loginname'],$user['did']);
					
					$logtime=date("Ymd",$user['login_date']);
					$nowtime=date("Ymd",time());
					if($logtime!=$nowtime){
						$this->MODEL('integral')->get_integral_action($user['uid'],"integral_login","会员登录");
					}
					$this->obj->DB_update_all('member',"`login_date` = ".time(),"`uid`=".$user['uid']);
					
					
					
						
						$this->layer_msg('登录成功',9,0,$this->config['sy_weburl']."/member/index.php",2,1);
					
				}else{
					$this->layer_msg('密码不正确!');
				}
			}else{
				$this->layer_msg('该用户不存在!');
			}
		}
			
	}
	
	function sendmsg_action(){
		if(!$this->config['sy_msg_isopen'] || !$this->config['sy_msg_login']){
			$this->layer_msg('网站未开启短信验证登录服务!');
		}else{
			$moblie=$_POST['moblie'];

			if(strpos($this->config['code_web'],'前台登录')!==false){
			    session_start();
			    if ($this->config['code_kind']==3){
					 
					if(!gtauthcode($this->config)){
						 $this->layer_msg('极验证未通过');
					}
			    }else{
        	    if(md5(strtolower(trim($_POST['code'])))!=$_SESSION['authcode'] || trim($_POST['code'])==''){
	        			$this->layer_msg('验证码错误');
	        		}
			    }
			}

			$res = $this->send_autocode($moblie);
			if($res == 5){
				$this->layer_msg('手机号有误!');
			}elseif ($res == 1) {
				$this->layer_msg('该手机号超过发送条数!');
			}elseif ($res == 2) {
				$this->layer_msg('该IP超过一天发送条数!');
			}elseif ($res == 3) {
				$this->layer_msg('手机用户不存在!');
			}elseif ($res == 4) {
				$this->layer_msg('未开启短信发送功能!');
			}elseif ($res == 6) {
				$this->layer_msg('验证码重复发送，请稍后!');
			}elseif($res == '发送成功!'){
				$this->layer_msg('发送成功!',9,0,'',2,1);
			}else{
				$this->layer_msg($res);
			}
		}
	}
	
	function newuser($Member,$username,$password,$email,$usertype,$pwuid,$qqid=''){
		$salt = substr(uniqid(rand()), -6);
		$pass = md5($password.$salt);
		$mdata['username']=$username;
		$mdata['password']=$pass;
		$mdata['email']=$email;
		$mdata['usertype']=$usertype;
		$mdata['status']=$this->config['user_status'];
		$mdata['salt']=$salt;
		$mdata['reg_date']=time();
		$mdata['reg_ip']=fun_ip_get();
		$mdata['pwuid']=$pwuid;
		$Member->AddMember($mdata);
		$this->cookie->unset_cookie();
		$new_info = $Member->GetMemberOne(array("username"=>$username));
		$userid = $new_info['uid'];
		if($this->config['sy_pw_type']=="pw_center"){
			$Member->UpdateMember(array("pwuid"=>$pwuid),array("uid"=>$userid));
		}
		$this->cookie->add_cookie($userid,$username,$salt,$email,$pass,$usertype,1,$this->config['did']);
		if($usertype=="1"){
			$table = "member_statis";
			$table2 = "resume";
			$data['uid']=$userid;
			$data2['uid']=$userid;
			$data2['email']=$email;
		}elseif($usertype=="2"){
			$table = "company_statis";
			$table2 = "company";
			$data=$Member->FetchRatingInfo(array("uid"=>$userid));
			$data2['uid']=$userid;
			$data2['linkmail']=$email;
		}
		if($qqid){
			$Member->UpdateMember(array("qqid"=>$qqid),array("uid"=>$userid));
		}
		$Member->InsertReg($table,$data);
		$Member->InsertReg($table2,$data2);
		return $new_info;
	}
	
	function rest_action(){
		$this->cookie->unset_cookie();
		$url = Url("login",array("usertype"=>"1"),"1");
		header("Location: ".$url);
	}
	
	function autoupjob($uid,$usertype){
		if($usertype=='2'){
			$Job=$this->Model("job");
			$Job->UpdateComjob(array("lastupdate"=>time()),array("`uid`='".$uid."' AND `autotype`='2' AND `autotime`>'".time()."'"));
		}
	}
	
	function utype_action(){
		if($this->uid)
		{
			header("Location:".$this->config['sy_weburl']."/member");
		}
		$this->seo("login");
		$this->yun_tpl(array('utype'));
	}

	function setutype_action(){   
		
		if($_COOKIE['username'] && $_COOKIE['password'] && (CheckRegUser($_COOKIE['username']) OR CheckRegEmail($_COOKIE['username'])==false))
		{
			
			$Member=$this->MODEL("userinfo");
			$user = $Member->GetMemberOne(array("username"=>$_COOKIE['username']),array("field"=>"uid,username,password,salt,usertype,did"));
		
			$pass = md5(md5($_COOKIE['password']).$user['salt']);
			$userid = $user['uid'];

			if(!$user['usertype'])
			{
				if($pass==$user['password'] && $user['password']!='')
				{
					$usertype = (int)$_GET['usertype'];
					if($usertype=='1')
					{
						$table = "member_statis";
						$table2 = "resume";
						$data1=array("uid"=>$userid);
						$data2['uid']=$userid;

					}elseif($usertype=='2')
					{

						$table = "company_statis";
						$table2 = "company";
						$data1=$Member->FetchRatingInfo(array("uid"=>$userid));
						$data2['uid']=$userid;
						$data1['did']=$user['did'];

					}elseif($usertype=='3')
					{
						$table1 = 'lt_statis';
						$table2 = 'lt_info';

						$id =$this->config['lt_rating'];
						$row = $Member->GetRatinginfoOne(array('id'=>$id));
						$data1=array('rating'=>$id,'integral'=>$this->config['integral_reg'],'rating_name'=>$row['name'],'rating_type'=>$row['type'],'lt_job_num'=>$row['lt_job_num'],'lt_down_resume'=>$row['lt_resume'],'lt_editjob_num'=>$row['lt_editjob_num'],'lt_breakjob_num'=>$row['lt_breakjob_num']);
						if($row['service_time']>0){
							$time=time()+86400*$row['service_time'];
						}else{
							$time=0;
						}
						$data1['vip_etime']=$time;
						$data2['uid']=$userid;
						$data2['did']=$user['did'];

					}
					$Member->UpdateMember(array("usertype"=>$usertype),array("uid"=>$userid));
					$Member->InsertReg($table,$data1);
					$Member->InsertReg($table2,$data2);
					$this->cookie->add_cookie($userid,$user['username'],$user['salt'],$user['email'],$user['password'],$usertype,1,$user['did']);
					header("Location:".$this->config['sy_weburl'].'/member');
				}else{
					$this->cookie->unset_cookie();
					echo "激活失败";
				}
			}else{
				$this->cookie->unset_cookie();
				echo "激活失败";
			}


		}else{
			header("Location:".Url('index'));
		}
	}
	
	function wxlogin_action(){
		$WxM=$this->MODEL('weixin');
		$qrcode = $WxM->applyWxQrcode($_COOKIE['wxloginid']);
		if(!$qrcode){
			echo 0;
		}else{
			echo $qrcode;
		}
	}
	
	function getwxloginstatus_action(){
	
		if($_COOKIE['wxloginid']){
			
			$WxM=$this->MODEL('weixin');
			$user = $WxM->getWxLoginStatus($_COOKIE['wxloginid']);
			if(!empty($user)){
				if($user['status']=="2"){
					$this->layer_msg('您的账号已被锁定',9,0,Url("register",array("c"=>"ok","type"=>2),"1"),2);
				}
				if($user['usertype']=="2" && $this->config['com_status']!="1" && $user['status']!="1"){
					$this->layer_msg('您还没有通过审核',9,0,Url("register",array("c"=>"ok","type"=>3),"1"),2,1);
				}
				if($user['usertype']=="3" && $this->config['lt_status']!="1" && $user['status']!="1"){
					$this->layer_msg('您还没有通过审核!',9,0,Url("register",array("c"=>"ok","type"=>3),"1"),2,1);
				}
				if($user['usertype']=="4" && $this->config['px_status']!="1" && $user['status']!="1"){
					$this->layer_msg('您还没有通过审核!',9,0,Url("register",array("c"=>"ok","type"=>3),"1"),2,1);
				}
				if($this->config['user_status']=="1" && $user['usertype']=="1"&&$user['email_status']!="1"){
					$this->layer_msg('您的账户还未激活，请先激活！',9,0,Url("activate",'',"1"),2);
				}
				$this->cookie->add_cookie($user['uid'],$user['username'],$user['salt'],$user['email'],$user['password'],$user['usertype'],1,$user['did']);
				$logtime=date("Ymd",$user['login_date']);
				$nowtime=date("Ymd",time());
				if($logtime!=$nowtime){
				    $this->MODEL('integral')->get_integral_action($user['uid'],"integral_login","会员登录");
				} 

				$this->layer_msg('',9,0,Url("member"));
			}else{
				$this->layer_msg('');
			}
		}else{
			$this->layer_msg('');
		}
	}
}




