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
class lietou_controller extends wap_controller{
	function waptpl($tpname){
		$this->yuntpl(array('wap/member/lietou/'.$tpname));
	}
	function user_shell(){
		$userinfo=$this->obj->DB_select_once("lt_info","`uid`='".$this->uid."'");
		if($userinfo['realname']==""){			
			$data['msg']='请先完善基本资料！';
		    $data['url']='index.php?c=info';
			$this->yunset("layer",$data);	
		}
	}
	function index_action(){
		$user=$this->obj->DB_select_once("lt_info","`uid`='".$this->uid."'");
		$user=$this->lt_array_action($user);
		if($user['photo_big'] == ""){
			$user['photo_big'] = $this->config['sy_weburl']."/".$this->config['sy_lt_icon'];
		}else{
			$user['photo_big'] = str_replace("./",$this->config['sy_weburl']."/",$user['photo_big']);
		}
		$this->yunset("user",$user);
		$downnum=$this->obj->DB_select_num("down_resume","`comid`='".$this->uid."'");
		$this->yunset("downnum",$downnum);
		$ypnum=$this->obj->DB_select_num("userid_job","`com_id`='".$this->uid."'");
		$this->yunset("ypnum",$ypnum);
		$jobnum=$this->obj->DB_select_num("lt_job","`uid`='".$this->uid."' and `status`='1' and `zp_status`<>'1'");
		$this->yunset("jobnum",$jobnum);
		$this->lt_satic();
		$backurl=Url('wap',array());
		$this->yunset('backurl',$backurl);
		$this->seo("ltindex");
		$this->waptpl('index');
	}

	function info_action(){
		$CacheList=$this->MODEL('cache')->GetCache(array('lt','lthy','ltjob','city'));
		$this->yunset($CacheList);
		$row=$this->obj->DB_select_once("lt_info","`uid`='".$this->uid."'");
		if($row['job']){
			$job=@explode(",",$row['job']);
			foreach ($job as $v){
				$jobname[]=$CacheList['ltjob_name'][$v];
			}
		}
		$jobname=@implode(",",$jobname);
		$this->yunset("jobname",$jobname);
		if($row['hy']){
			$hy=@explode(",",$row['hy']);
			foreach ($hy as $v){
				$hyname[]=$CacheList['lthy_name'][$v];
			}
		}
		$hyname=@implode(",",$hyname);
		$this->yunset("hyname",$hyname);
		$this->yunset("row",$row);
		if($_POST['submit']){
			$_POST=$this->post_trim($_POST);
			unset($_POST['submit']);
			if($_POST['realname']==''){
				$data['msg']='请输入真实姓名！';
			}elseif($_POST['com_name']==''){
				$data['msg']='请输入所在公司！';
			}elseif($_POST['phone']==''){
				$data['msg']='请输入公司座机！';
			}elseif($_POST['phone']&&CheckTell($_POST['phone'])==false){
				$data['msg']='公司座机格式错误！';
			}elseif($_POST['email']&&CheckRegEmail($_POST['email'])==false){
				$data['msg']='联系邮箱格式错误！';
			}elseif($_POST['moblie']==''){
				$data['msg']='请输入手机号码！';
			}elseif($_POST['moblie']&&CheckMoblie($_POST['moblie'])==false){
				$data['msg']='手机号码格式错误！';
			}elseif($_POST['cityid']==''){
				$data['msg']='请输入所在公司！';
			}elseif($_POST['exp']==''){
				$data['msg']='请选择工作经验！';
			}elseif($_POST['title']==''){
				$data['msg']='请选择目前头衔！';
			}elseif($_POST['qw_hy']==''){
				$data['msg']='请选择擅长行业！';
			}elseif($_POST['job']==''){
				$data['msg']='请选择擅长职位！';
			}elseif($_POST['content']==''){
				$data['msg']='请输入顾问介绍！';
			}else{
				$where['uid']=$this->uid;
				$_POST['job'] = pylode(",",$_POST['job']);
				$_POST['hy'] = pylode(",",$_POST['qw_hy']);
				$row=$this->obj->DB_select_once("lt_info","`uid`='".$this->uid."'");
				$Member=$this->MODEL("userinfo");
				if($row['moblie_status']==1){
					unset($_POST['moblie']);
				}else{
					$moblieNum = $Member->GetMemberNum(array("moblie"=>$_POST['moblie'],"`uid`<>'".$this->uid."'"));
					if($_POST['moblie']==''){
						$data['msg']='手机号码不能为空！';
					}elseif(!CheckMoblie($_POST['moblie'])){
						$data['msg']='手机号码格式错误！';
					}elseif($moblieNum>0){
						$data['msg']='手机号码已存在！';
					}else{
						$data1['moblie']=$_POST['moblie'];
					}
				}
				if($row['email_status']==1){
					unset($_POST['email']);
				}else{
					$emailNum = $Member->GetMemberNum(array("email"=>$_POST['email'],"`uid`<>'".$this->uid."'"));
					if($_POST['email']&&CheckRegEmail($_POST['email'])==false){
						$data['msg']='联系邮箱格式错误！';
					}elseif($_POST['email']&&$emailNum>0){
						$data['msg']='联系邮箱已存在！';
					}else{
						$data1['email']=$_POST['email'];
					}
				}
				$this->obj->DB_update_all("lt_job","`com_name`='".$_POST['com_name']."'","`uid`=".$this->uid." ");
				$id=$this->obj->update_once("lt_info",$_POST,$where);
				if($id){
					if(!empty($data1)){
						$this->obj->update_once("member",$data1,array("uid"=>$this->uid));
					}
					$this->obj->member_log("修改基本信息",7);
				
					if($row['com_name']==""){
						$this->MODEL('integral')->get_integral_action($this->uid,"integral_userinfo","完善基本资料");
					}
					$data['msg']='更新成功！';
					$data['url']='index.php?c=info';
				}else{
					$data['msg']='更新失败！';
				}
			}
			$this->yunset("layer",$data);
		}
		$backurl=Url('wap',array(),'member');
		$this->yunset('backurl',$backurl);
		$this->waptpl('info');
	}


	function uppic_action(){
		if($_POST['submit']){
			preg_match('/^(data:\s*image\/(\w+);base64,)/', $_POST['uimage'], $result);
			$uimage=str_replace($result[1], '', str_replace('#','+',$_POST['uimage']));

			if(in_array(strtolower($result[2]),array('jpg','png','gif','jpeg'))){
				$new_file = time().rand(1000,9999).".".$result[2];
			}else{
				$new_file = time().rand(1000,9999).".jpg";
			}

			$im = imagecreatefromstring(base64_decode($uimage));
			if ($im === false) {
				echo 2;die;
			}
			if (!file_exists(DATA_PATH."upload/lietou/".date('Ymd')."/")){
				mkdir(DATA_PATH."upload/lietou/".date('Ymd')."/");
				chmod(DATA_PATH."upload/lietou/".date('Ymd')."/",0777);
		
			}
			$re=file_put_contents(DATA_PATH."upload/lietou/".date('Ymd')."/".$new_file, base64_decode($uimage));
			chmod(DATA_PATH."upload/lietou/".date('Ymd')."/".$new_file,0777);
			if($re){
				$ltInfo=$this->obj->DB_select_once("lt_info","`uid`='".$this->uid."'","`photo`,`photo_big`");
				if($ltInfo['photo']){
					unlink_pic(APP_PATH.$ltInfo['photo']);
				}else{
					$this->MODEL('integral')->get_integral_action($this->uid,"integral_avatar","上传头像");
				}
				if($ltInfo['photo_big']){
					unlink_pic(APP_PATH.$ltInfo['photo_big']);
				}
				$photo="./data/upload/lietou/".date('Ymd')."/".$new_file;
				$ref=$this->obj->DB_update_all("lt_info","`photo_big`='".$photo."',`photo`='".$photo."'","`uid`='".$this->uid."'");
				if($ref){$this->obj->member_log("上传猎头头像");echo 1;die;}else{echo 2;die;}
			}else{
				unlink_pic(APP_PATH."data/upload/lietou/".date('Ymd')."/".$new_file);
				echo 2;die;
			}
		}else{
			$row = $this->obj->DB_select_once("lt_info","`uid`='".$this->uid."'");
		    if(!$row['photo_big'] || !file_exists(str_replace('./',APP_PATH,$row['photo_big']))){
			    $row['photo_big']=$this->config['sy_weburl']."/".$this->config['sy_lt_icon'];
			}else{
			    $row['photo_big']=str_replace("./",$this->config['sy_weburl']."/",$row['photo_big']);
			}
			$this->yunset("row",$row);
		}
		$backurl=Url('wap',array(),'member');
		$this->yunset('backurl',$backurl);
		$this->user_shell();
		$this->waptpl('uppic');
	}

	function pay_action(){
		if($this->config['wxpay']=='1'){
			$paytype['wxpay']='1';
		}
		if($this->config['alipay']=='1' &&  $this->config['alipaytype']=='1'){
			$paytype['alipay']='1';
		}
		$banks=$this->obj->DB_select_all("bank");
		$this->yunset("banks",$banks);
		if($this->config['bank']=='1' &&  $banks){
			$paytype['bank']='1';
		}
		if($paytype){
			if($_POST['usertype']=='price'){
				$id=(int)$_POST['id'];
				if ($id){
					$rows=$this->obj->DB_select_once("company_rating","`service_price`<>'' and `service_time`>'0' and `id`='".$id."' and `display`='1' and `category`=2 order by sort desc","name,time_start,time_end,service_price,yh_price,coupon,id");
					if ($row['time_start']<time() && $rows['time_end']>time()){
						if ($rows['coupon']>0){
							$coupon=$this->obj->DB_select_once("coupon","`id`='".$rows['coupon']."'");
							$this->yunset("coupon",$coupon);
						}
					}
				}
				$this->yunset("rows",$rows);
			}elseif($_GET['id']){
				$order=$this->obj->DB_select_once("company_order","`uid`='".$this->uid."' and `id`='".(int)$_GET['id']."'");
				if(empty($order)){ 
					$this->ACT_msg($_SERVER['HTTP_REFERER'],"订单不存在！"); 
				}elseif($order['order_state']!='1'){ 
					header("Location:index.php?c=paylog"); 
				}else{
					$this->yunset("order",$order);
				}
			}
			$this->yunset("paytype",$paytype);
		}else{
			$data['msg']="暂未开通手机支付，请移步至电脑端充值！";
			$data['url']=$_SERVER['HTTP_REFERER'];
			$this->yunset("layer",$data);
				
		}
		$backurl=Url('wap',array(),'member');
		$this->yunset('backurl',$backurl);
		$this->user_shell();
		$this->waptpl('pay');
	}
	function dingdan_action(){
		if($_POST['price']){
			if($_POST['comvip']){
				$comvip=(int)$_POST['comvip'];
				$ratinginfo =  $this->obj->DB_select_once("company_rating","`id`='".$comvip."'");
				$price = $ratinginfo['service_price'];
				$data['type']='1';
			}elseif($_POST['price_int']){
				if($this->config['integral_min_recharge'] && $_POST['price_int']<$this->config['integral_min_recharge']){
	
					$data['msg']="充值不得低于".$this->config['integral_min_recharge'];
					$data['url']=$_SERVER['HTTP_REFERER'];
					$this->yunset("layer",$data);
					$this->waptpl('pay');exit;
				}
				$price = $_POST['price_int']/$this->config['integral_proportion'];
				$data['type']='2';
			}
			$dingdan=mktime().rand(10000,99999);
			$data['order_id']=$dingdan;
			$data['order_price']=$price;
			$data['order_time']=mktime();
			$data['order_state']="1";
			$data['order_remark']=trim($_POST['remark']);
			$data['uid']=$this->uid;
			$data['rating']=$_POST['comvip'];
			$data['integral']=$_POST['price_int'];
			$id=$this->obj->insert_into("company_order",$data);
			if($id){
				$this->member_log("下单成功,订单ID".$dingdan);
				$_POST['dingdan']=$dingdan;
				$_POST['dingdanname']=$dingdan;
				$_POST['alimoney']=$price;
				$data['msg']="下单成功，请付款！";

				if($_POST['paytype']=='alipay'){
					$url=$this->config['sy_weburl'].'/api/wapalipay/alipayto.php?dingdan='.$dingdan.'&dingdanname='.$dingdanname.'&alimoney='.$price;
					header('Location: '.$url);exit();
				}elseif($_POST['paytype']=='wxpay'){
					$url='index.php?c=wxpay&id='.$id;
					header('Location: '.$url);exit();
				}
			}else{
				$data['msg']="提交失败，请重新提交订单！";
				$data['url']=$_SERVER['HTTP_REFERER'];
			}
		}else{
			$data['msg']="参数不正确，请正确填写！";
			$data['url']=$_SERVER['HTTP_REFERER'];
		}
		$this->yunset("layer",$data);
		$backurl=Url('wap',array(),'member');
		$this->yunset('backurl',$backurl);
		$this->user_shell();
		$this->waptpl('pay');
	}

	function wxpay_action(){
		if($_GET['id']){
			$id = (int)$_GET['id'];
			$order = $this->obj->DB_select_once("company_order","`uid`='".$this->uid."' AND `id`='".$id."'");
			if(!empty($order)){
				require_once(LIB_PATH.'wxOrder.function.php');
				$jsApiParameters = wxWapOrder(array('body'=>'充值','id'=>$order['order_id'],'url'=>$this->config['sy_weburl'],'total_fee'=>$order['order_price']));
				if($jsApiParameters){
					$this->yunset('jsApiParameters',$jsApiParameters);
				}else{
					$data['msg']="参数不正确，请重新支付！";
					$data['url']='index.php?c=mypay';
					$this->yunset("layer",$data);
				}
	
			}else{
				$data['msg']="参数不正确，请正确填写！";
				$data['url']=$_SERVER['HTTP_REFERER'];
				$this->yunset("layer",$data);
			}
	
			$this->yunset('id',(int)$_GET['id']);
			$this->waptpl('wxpay');
		}else{
			$data['msg']="参数不正确，请正确填写！";
			$data['url']=$_SERVER['HTTP_REFERER'];
			$this->yunset("layer",$data);
			$backurl=Url('wap',array(),'member');
		    $this->yunset('backurl',$backurl);
			$this->user_shell();
			$this->waptpl('pay');
		}
	}
	function paybank_action(){
		if($_POST['nextstep']){
			if($_POST['bank_name']==""){
				$data['msg']="请填写汇款银行！";
				$data['url']=$_SERVER['HTTP_REFERER'];
				$this->yunset("layer",$data);
			}elseif($_POST['bank_number']==""){
				$data['msg']="请填写汇入账号！";
				$data['url']=$_SERVER['HTTP_REFERER'];
				$this->yunset("layer",$data);
			}elseif($_POST['bank_price']==""){
				$data['msg']="请填写汇款金额！";
				$data['url']=$_SERVER['HTTP_REFERER'];
				$this->yunset("layer",$data);
			}elseif($_POST['bank_time']==""){
				$data['msg']="请填写汇款时间！";
				$data['url']=$_SERVER['HTTP_REFERER'];
				$this->yunset("layer",$data);
			}
			if(is_uploaded_file($_FILES['order_pic']['tmp_name'])){
				$UploadM=$this->MODEL('upload');
				$upload=$UploadM->Upload_pic("../../data/upload/order/",false,$this->config['com_uppic']);
				$pictures=$upload->picture($_FILES['order_pic']);
				$picmsg = $UploadM->picmsg($pictures,$_SERVER['HTTP_REFERER']);
				if($picmsg['status'] == $pictures){
					$data['msg']=$picmsg['msg'];
					$this->yunset("layer",$data);
				}
				$pictures = str_replace("../../data/upload/order","./data/upload/order",$pictures);
			}
			$id=intval($_GET['id']);
			$orderbank=$_POST['bank_name'].'@%'.$_POST['bank_number'].'@%'.$_POST['bank_price'];
			if($_POST['bank_time']){
				$banktime=strtotime($_POST['bank_time']);
			}else{
				$banktime="";
			}
			if($id){
				$order=$this->obj->DB_select_once("company_order","`id`='".$id."' and `uid`='".$this->uid."'");
				if($order['id']){
					$_POST['coupon']=intval($_POST['coupon']);
					if($_POST['coupon'] && $order['coupon']==""){
						$coupon=$this->obj->DB_select_once("coupon_list","`id`='".$_POST['coupon']."' and `uid`='".$this->uid."' and `validity`>'".time()."' and `coupon_scope`<='".$order['order_price']."' and `status`='1'");
						if($coupon['id']){
							$order_price=$order['order_price']-$coupon['coupon_amount'];
							$this->obj->DB_update_all("company_order","`order_price`='".$order_price."',`coupon`='".$_POST['coupon']."'","`id`='".(int)$_POST['oid']."' and `uid`='".$this->uid."'");
							$this->obj->DB_update_all("coupon_list","`status`='2',`xf_time`='".time()."'","`id`='".$coupon['id']."' and `uid`='".$this->uid."'");
						}
					}
					if(is_uploaded_file($_FILES['order_pic']['tmp_name'])){
						@unlink_pic(str_replace('./', $this->config['sy_weburl'].'/', $order['order_pic']));
					}else{
						$pictures=$order['order_pic'];
					}
					$company_order="`order_type`='bank',`order_state`='3',`order_remark`='".$_POST['remark']."',`order_pic`='".$pictures."',`order_bank`='".$orderbank."',`bank_time`='".$banktime."'";
					if($_POST['is_invoice']=='1'&&$this->config['sy_com_invoice']=='1'){
						$company_order.=",`is_invoice`='".intval($_POST['is_invoice'])."'";
						$this->add_invoice_record($_POST,$order['order_id'],$order['id']);
					}
					$this->obj->DB_update_all("company_order",$company_order,"`order_id`='".$order['order_id']."'");
					$data['msg']="操作成功，请等待管理员审核！";
					$data['url']="index.php?c=paylog";
					$this->yunset("layer",$data);
				}else{
					$data['msg']="非法操作！";
					$data['url']=$_SERVER['HTTP_REFERER'];
					$this->yunset("layer",$data);
				}
			}else{
				if($_POST['price']){
					if($_POST['comvip']){
						$comvip=(int)$_POST['comvip'];
						$ratinginfo =  $this->obj->DB_select_once("company_rating","`id`='".$comvip."'");
						if($ratinginfo['time_start']<time() && $ratinginfo['time_end']>time()){
							$price = $ratinginfo['yh_price'];
						}else{
							$price = $ratinginfo['service_price'];
						}
						$data['type']='1';
	
					}elseif($_POST['comservice']){
						$id=(int)$_POST['comservice'];
						$dkjf=(int)$_POST['dkjf'];
						$price=$_POST['dkprice'];
						$data['type']='5';
					}elseif($_POST['price_int'] || $_POST['money_int']){
						if($_POST['price_int']){
							if($this->config['integral_min_recharge'] && $_POST['price_int']<$this->config['integral_min_recharge']){
								$data['msg']="充值不得低于".$this->config['integral_min_recharge'];
								$data['url']=$_SERVER['HTTP_REFERER'];
								$this->yunset("layer",$data);
								$this->waptpl('pay');exit;
							}
							$price = $_POST['price_int']/$this->config['integral_proportion'];
							$data['type']='2';
						}elseif ($_POST['money_int']){
							if($this->config['money_min_recharge'] && $_POST['money_int']<$this->config['money_min_recharge']){
								$data['msg']="充值不得低于".$this->config['money_min_recharge'];
								$data['url']=$_SERVER['HTTP_REFERER'];
								$this->yunset("layer",$data);
								$this->waptpl('pay');exit;
							}
							$price = $_POST['money_int'];
							$data['type']='4';
						}
					}
					$dingdan=mktime().rand(10000,99999);
					$data['order_id']=$dingdan;
					$data['order_dkjf']=$dkjf;
					$data['order_price']=$price;
					$data['order_time']=mktime();
					$data['order_state']="3";
					$data['order_type']="bank";
					$data['order_remark']=trim($_POST['remark']);
					$data['order_pic']=$pictures;
					$data['order_bank']=$orderbank;
					$data['bank_time']=$banktime;
					$data['uid']=$this->uid;
					$data['rating']=$_POST['comvip']?$_POST['comvip']:$_POST['comservice'];
					$data['integral']=$_POST['price_int'];
	
					$id=$this->obj->insert_into("company_order",$data);
					if($id){
						if($_POST['comservice']){
							$this->MODEL('integral')->company_invtal($this->uid,$dkjf,$auto,"购买增值包",true,2,'integral',11);
						}
						$this->member_log("下单成功,订单ID".$dingdan);
						$data['msg']="操作成功，请等待管理员审核！";
						$data['url']="index.php?c=paylog";
						$this->yunset("layer",$data);
					}else{
						$data['msg']="提交失败，请重新提交订单！";
						$data['url']=$_SERVER['HTTP_REFERER'];
					}
				}else{
					$data['msg']="参数不正确，请正确填写！";
					$data['url']=$_SERVER['HTTP_REFERER'];
				}
			}
		}
		$this->yunset("layer",$data);
	
		$backurl=Url('wap',array(),'member');
		$this->yunset('backurl',$backurl);
		$this->waptpl('pay');
	}

	function passwd_action(){
		$this->rightinfo();
		if($_POST['submit']){
			$member=$this->obj->DB_select_once("member","`uid`='".$this->uid."'");
			$pw=md5(md5($_POST['oldpassword']).$member['salt']);
			if($pw!=$member['password']){
				$data['msg']="旧密码不正确，请重新输入！";
			}else if(mb_strlen($_POST['password1'])<6 || mb_strlen($_POST['password1'])>20){
				$data['msg']="密码长度应在6-20位！";
			}elseif($_POST['password1']!=$_POST['password2']){
				$data['msg']="新密码和确认密码不一致！";
			}elseif($this->config['sy_uc_type']=="uc_center" && $member['name_repeat']!="1"){
				$this->uc_open();
				$ucresult= uc_user_edit($member['username'], $_POST['oldpassword'], $_POST['password1'], "","1");
				if($ucresult == -1){
					$data['msg']="旧密码不正确，请重新输入！";
				}
			}else{
				$salt = substr(uniqid(rand()), -6);
				$pass2 = md5(md5($_POST['password1']).$salt);
				$this->obj->DB_update_all("member","`password`='".$pass2."',`salt`='".$salt."'","`uid`='".$this->uid."'");
				$this->cookie->SetCookie("uid","",time() -286400);
				$this->cookie->SetCookie("username","",time() - 86400);
				$this->cookie->SetCookie("salt","",time() -86400);
				$this->cookie->SetCookie("shell","",time() -86400);
				$this->member_log("修改密码");
				$data['msg']="修改成功，请重新登录！";
				$data['url']=get_url(array('m'=>'wap','c'=>'login'),$this->config);
			}
			$this->waplayer_msg($data['msg'],$data['url']);
		}
		$backurl=Url('wap',array(),'member');
		$this->yunset('backurl',$backurl);
		$this->user_shell();
		$this->waptpl('passwd');
	}

	function job_action(){
		$where = "`uid`='".$this->uid."'";
		  if($_GET['zp_status']==1){
			$where.="and `zp_status`='1'";
			$urlarr['zp_status']=$_GET['zp_status'];
		}else{
            if($_GET['s']==1){
			$where .= " and `status`='1' and `zp_status`='0'";
			$urlarr['s']=$_GET['s'];
            }elseif($_GET["s"]==2){
                $where .= " and `status`='2' and `zp_status`='0'";
                $urlarr['s']=2;
            }elseif($_GET["s"]==3){
                $where .= "  and `status`='3' and `zp_status`='0'";
                $urlarr['s']=3;
            }else{
                $where .= " and `status`='".$_GET['s']."'";
                $urlarr['s']=$_GET['s'];
            } 
        }
		

		$urlarr=array("c"=>"job","s"=>$_GET['s'],"zp_status"=>$_GET['zp_status'],"page"=>"{{page}}");
		$pageurl=Url('wap',$urlarr,'member');
		$joblist=$this->get_page("lt_job",$where." order by lastupdate desc",$pageurl,"10");
        if(is_array($joblist)){
			foreach ($joblist as $k=>$v){
				if($v['minsalary']){
					if($v['maxsalary']){
						$joblist[$k]['msalary']='￥'.$v['minsalary'].'-'.$v['maxsalary'].'万';
					}else{
						$joblist[$k]['msalary']='￥'.$v['minsalary'].'万以上';
					}
				}else{
					$joblist[$k]['msalary']='面议';
				}
			}
		}
		$this->yunset("joblist",$joblist);
		$this->lt_satic();
		$CacheList=$this->MODEL('cache')->GetCache(array('lt','city'));
		$this->yunset($CacheList);
		$this->yunset("s",$_GET['s']);
        $this->yunset("zp_status",$_GET['zp_status']);
		$backurl=Url('wap',array(),'member');
		$this->yunset('backurl',$backurl);
		$this->user_shell();
		$this->waptpl('job');
	}

	function jobdel_action(){
		if($_GET['id']){
			$del=(int)$_GET['id'];
			$did=$this->obj->DB_delete_all("lt_job","`uid`='".$this->uid."' and `id` in (".$del.")","");
			$this->obj->DB_delete_all("fav_job","`job_id` in (".$del.")","");
			$this->obj->DB_delete_all("rebates","`job_id` in (".$del.")","");
			$this->obj->DB_delete_all("userid_job","`job_id` in (".$del.")","");
			if($did){
				$this->obj->member_log("删除猎头职位",1,3);
				$this->waplayer_msg('删除成功！');
			}else{
				$this->waplayer_msg('删除失败！');
			}
		}
	}

	function getserver_action(){
		if($this->config['wxpay']=='1'){
			$paytype['wxpay']='1';
		}
		if($this->config['alipay']=='1' &&  $this->config['alipaytype']=='1'){
			$paytype['alipay']='1';
		}
		if($paytype){
			$this->yunset("paytype",$paytype);
		}
		
		$jobid=intval($_GET['id']);
		$server=intval($_GET['server']);
		
		$statis=$this->obj->DB_select_once("lt_statis","`uid`='".$this->uid."'");
		$this->yunset("statis",$statis);

		$info=$this->obj->DB_select_once("lt_job","`uid`='".$this->uid."' and `id`='".$jobid."'","`id`");
		$this->yunset("info",$info);

		$backurl=Url('wap',array(),'member');
		$this->yunset('backurl',$backurl);
		$this->user_shell();
		$this->waptpl('getserver');
	}

	function getOrder_action(){
		if($_POST){
       		$M=$this->MODEL('compay');
			if ($_POST['server']=='refresh_job'){
				$return = $M->buyLtJobRefresh($_POST);
			} else if ($_POST['server']=='issue_job'){
				$return = $M->buyLtIssueJob($_POST);
			} else if ($_POST['server']=='downresume'){
				$return = $M->buyLtDownresume($_POST);
			} 
			
			if($return['order']['order_id'] && $return['order']['id']){
				$dingdan = $return['order']['order_id'];
				$price = $return['order']['order_price'];
				$id = $return['order']['id'];
				$this->member_log("下单成功,订单ID".$dingdan);
				$_POST['dingdan']=$dingdan;
				$_POST['dingdanname']=$dingdan;
				$_POST['alimoney']=$price;
				$data['msg']="下单成功，请付款！";
				if($_POST['paytype']=='alipay'){
					$url=$this->config['sy_weburl'].'/api/wapalipay/alipayto.php?dingdan='.$dingdan.'&dingdanname='.$dingdan.'&alimoney='.$price;
					header('Location: '.$url);exit();
				}elseif($_POST['paytype']=='wxpay'){
					$url='index.php?c=wxpay&id='.$id;
					header('Location: '.$url);exit();
				}
			}else{
				
				if($return['error']){
					$data['msg']=$return['error'];
				}else{
					$data['msg']="提交失败，请重新提交订单！";
				}
				
				$data['url']=$_SERVER['HTTP_REFERER'];
			}
 		}else{
			$data['msg']="参数不正确，请正确填写！";
			$data['url']=$_SERVER['HTTP_REFERER'];
		}
		$this->yunset("layer",$data);
		$backurl=Url('wap',array(),'member');
		$this->yunset('backurl',$backurl);
		$this->user_shell();
		$this->waptpl('pay');
	}


	function dkzf_action(){
  		if($_POST){
   			$M=$this->MODEL('jfdk');
			if ($_POST['jobid']){
				$return = $M->buyLtJobRefresh($_POST);
			} else if ($_POST['issuejob']){
				$return = $M->buyLtIssueJob($_POST);
			} else if ($_POST['eid']){
				$return = $M->buyLtDownresume($_POST);
			}
			if($return['status']==1){
				echo json_encode(array('error'=>0,'msg'=>$return['msg']));
			}else{
				echo json_encode(array('error'=>1,'msg'=>$return['error'],'url'=>$return['url']));
			}
		}else{
			echo json_encode(array('error'=>1,'msg'=>'参数错误，请重试！'));
		}
	}
	
	function jobset_action(){
		if($_GET['id']){
			$where['id']=(int)$_GET['id'];
			$where['uid']=$this->uid;
			$did=$this->obj->update_once("lt_job",array("zp_status"=>(int)$_GET['status']),$where);
			if($did){
				$this->obj->member_log("设置猎头职位招聘状态");
				$this->waplayer_msg('操作成功！');
			}else{
				$this->waplayer_msg('操作失败！');
			}
		}
		
	}

 	function ajax_refresh_job_action() {
		
		if(!isset($_POST['jobid'])){
			exit;
		}

		$jobid = $_POST['jobid'];
		
		$statis = $this->lt_satic();

		$msg = '';
		
 		$M=$this->MODEL('comtc');
 		$return = $M->ltRefreshJob($_POST);
 		if($return['status']==1){
			$data['msg']=$return['msg'];
			$data['error']=1;
			echo json_encode($data);
			exit;
		}else if($return['status']==2){
			$data['msg']=$return['msg'];
			$data['error']=2;
			echo json_encode($data);
			exit;
		}else{
			$data['msg']=$return['msg'];
			$data['error']=3;
			echo json_encode($data);
 			exit;
		}
		 

		$data['msg'] = $msg;
		echo json_encode($data);
		exit;
	}

	function lt_satic(){
		$statis=$this->obj->DB_select_once("lt_statis","`uid`='".$this->uid."'");

		if($statis['rating']){
			$rating=$this->obj->DB_select_once("company_rating","`id`='".$statis['rating']."' and `category`='2'");
		}
		
		if($statis['vip_etime'] < time()){
			
			if($statis['vip_etime'] > 1){
			
				$nums=0;
			
			}else if($statis['vip_etime'] < '1' && $statis['rating']!="0"){
			
				$nums=1;
			
			}else{
			
				$nums=0;
			
			} 

			if($nums==0){
				if($this->config['com_vip_done']=='0'){ 

					$data['lt_job_num']=$data['lt_breakjob_num']=$data['lt_down_resume']='0';

					$statis['rating_name']=$data['rating_name']="非会员";
					
					$statis['rating_type']=$statis['rating']=$data['rating_type']=$data['rating']="0";  
					
					$statis['vip_stime']=$data['vip_stime']=$statis['vip_etime']=$data['vip_etime']="0"; 
					
					$where['uid']=$this->uid;
					
					$this->obj->update_once("lt_statis",$data,$where);
					
				}elseif ($this->config['com_vip_done']=='1'){ 
					
					$ratingM = $this->MODEL('rating');
					
					$rat_value=$ratingM->ltrating_info();
					
					$this->obj->DB_update_all("lt_statis",$rat_value,"`uid`='".$this->uid."'");
				}
			}
		}
		
		if($statis['vip_etime']>time() || $statis['vip_etime']==0){
			if($statis['rating_type']=="2"){
				$addltjobnum='1';
			}elseif($statis['rating_type']=='1'){
				if($statis['lt_job_num'] > 0){
					$addltjobnum='1';
				}else{
					$addltjobnum='2';
				}
   			}else{
				$addltjobnum='0';
			}
		}else{
  			$addltjobnum='0';
 		}
		
		$statis['integral_format']=number_format($statis['integral']);
		$this->yunset("addltjobnum",$addltjobnum);

		$this->yunset("statis",$statis);
		return $statis;
	}
	 

	function get_com($type){
		$statis=$this->lt_satic();
		if($statis['rating_type']&&$statis['rating']){
			$data=array();
			if($type==1){
				if($statis['rating_type']=='1' && $statis['lt_job_num']>0 && ($statis['vip_etime']<1 || $statis['vip_etime']>=time())){
					$data="`lt_job_num`=`lt_job_num`-1";
				}else{
					return $this->ACT_layer_msg("会员套已用完！",8,"index.php?c=rating");
				}
			}elseif($type==3){
				if($statis['rating_type']=='1' && $statis['lt_breakjob_num']>0 && ($statis['vip_etime']<1 || $statis['vip_etime']>=time())){
					$data="`lt_breakjob_num`=`lt_breakjob_num`-1";
				}else{
					return $this->ACT_layer_msg("会员套已用完！",8,"index.php?c=rating");
				}
				
			}
			if($data){
				$this->obj->DB_update_all("lt_statis",$data,"`uid`='".$this->uid."'");
			}
		}else{
			return $this->ACT_layer_msg("会员已到期！",8,"index.php?c=rating");
		}
	}

	function jobadd_action(){
		include(CONFIG_PATH."db.data.php");		
		$this->yunset("arr_data",$arr_data);
		$CacheList=$this->MODEL('cache')->GetCache(array('lt','lthy','ltjob','city','com','hy'));
		$rows=$this->obj->DB_select_all("company_cert","`uid`='".$this->uid."' group by type order by id desc");
		foreach($rows as $v){
			$row[$v["type"]]=$v;
		}
		$info=$this->obj->DB_select_once("lt_info","`uid`='".$this->uid."'","`com_name`,`email_status`,`moblie_status`,`yyzz_status`");
		if($info['com_name']==''){
			$data['msg']="请先完善基本资料！";
			$data['url']='index.php?c=info';
		}
		$this->rightinfo();
		$msg=array();
		if($this->config['lt_enforce_emailcert']=="1"){
			if($row['1']['status']!="1"){
				$data['msg']="请先完成邮箱认证";
				$data['url']='index.php?c=binding';
			}
		}
		if($this->config['lt_enforce_mobilecert']=="1"){
			if($row['2']['status']!="1"){
				$data['msg']="请先完成手机认证";
				$data['url']='index.php?c=binding';
			}
		}
		if($this->config['lt_enforce_licensecert']=="1"){
			if($row['4']['status']!="1"){
				$data['msg']="请先完成职业资格认证";
				$data['url']='index.php?c=binding';
			}
		}
		if($_GET['id']){
			$row=$this->obj->DB_select_once("lt_job","`id`='".(int)$_GET['id']."' and `uid`='".$this->uid."'");
			$arr_data1=$arr_data['sex'][$row['sex']];		
		    $this->yunset("arr_data1",$arr_data1);
			if($row['id']){
				if($row['constitute']!=""){
					$row['constitute']=@explode(",",$row['constitute']);
				}
				if($row['welfare']!=""){
					$row['welfare']=@explode(",",$row['welfare']);
				}
				if($row['language']!=""){
					$row['language']=@explode(",",$row['language']);
				}
				if($row['job']){
					$job=@explode(",",$row['job']);
					foreach ($job as $v){
						$jobname[]=$CacheList['ltjob_name'][$v];
					}
				}
				$jobname=@implode(",",$jobname);
				$this->yunset("jobname",$jobname);
				if($row['qw_hy']){
					$hy=@explode(",",$row['qw_hy']);
					foreach ($hy as $v){
						$hyname[]=$CacheList['lthy_name'][$v];
					}
				}
				$hyname=@implode(",",$hyname);
				$this->yunset("hyname",$hyname);
				$row['days']= ceil(($row['edate']-$row['sdate'])/86400);
				$this->yunset("row",$row);
			}else{
				$data['msg']='职位不存在！';
				$data['url']='index.php?c=job&s=1';
			}
		}
		if($_POST['submit']){
			$_POST=$this->post_trim($_POST);
			$id=(int)$_POST['id'];
			$info=$this->obj->DB_select_once("lt_statis","`uid`='".$this->uid."'","`integral`");
			$_POST['desc'] = str_replace("&amp;","&",html_entity_decode($_POST['desc'],ENT_QUOTES));
			$data1['com_name']=$_POST['com_name'];
			$data1['pr']=$_POST['pr'];
			$data1['hy']=$_POST['hy'];
			$data1['mun']=$_POST['mun'];
			$data1['desc']=$_POST['desc'];
			$data1['job_name']=$_POST['job_name'];
			$data1['department']=$_POST['department'];
			$data1['edate']=strtotime($_POST['edate']);
			$data1['report']=$_POST['report'];
			$data1['jobone']=$_POST['jobone'];
			$data1['jobtwo']=$_POST['jobtwo'];
			$data1['provinceid']=$_POST['provinceid'];
			$data1['cityid']=$_POST['cityid'];
			$data1['three_cityid']=$_POST['three_cityid'];
			$data1['minsalary']=$_POST['minsalary'];
			$data1['maxsalary']=$_POST['maxsalary'];
			if(!empty($_POST['constitute'])){
				$_POST['constitute'] = pylode(",",$_POST['constitute']);
			}else{
				$_POST['lang'] = "";
			}
			if(!empty($_POST['welfare'])){
				$_POST['welfare'] = pylode(",",$_POST['welfare']);
			}else{
				$_POST['welfare'] = "";
			}
			if(!empty($_POST['language'])){
				$_POST['language'] = pylode(",",$_POST['language']);
			}else{
				$_POST['language'] = "";
			}
			$data1['constitute']=$_POST['constitute'];
			$data1['welfare']=$_POST['welfare'];
			$data1['job_desc']=$_POST['job_desc'];
			$data1['age']=$_POST['age'];
			$data1['sex']=$_POST['sex'];
			$data1['exp']=$_POST['exp'];
			$data1['edu']=$_POST['edu'];
			$data1['language']=$_POST['language'];
			$data1['eligible']=$_POST['eligible'];
			$data1['rebates']=$_POST['rebates'];
			$data1['other']=$_POST['other'];
			$data1['lastupdate']=time();
			$data1['status']=$this->config['lt_job_status'];
			if($_POST['id']){
				$job=$this->obj->DB_select_once("lt_job","`id`='".$_POST['id']."' and `uid`='".$this->uid."'","`status`");
				if($job['status']>'0'){
					$this->get_com(2);
				} 
				$where['uid']=$this->uid;
				$where['id']=$_POST['id'];
				$id=$this->obj->update_once("lt_job",$data1,$where);
				if($id){
					$this->obj->member_log("更新猎头职位",1,2);
					$data['msg']='修改职位成功！';
					if($this->config['lt_job_status']=='1'){
						$data['url']='index.php?c=job&s=1';
					}else{
						$data['url']='index.php?c=job&s=0';
					}
				}else{
					$data['msg']='修改职位失败！';
				}
			}else{
				$data1['uid']=$this->uid;
				$data1['did']=$this->userdid;
				$this->get_com(1);
				$id=$this->obj->insert_into("lt_job",$data1);
				if($id){
					$state_content = "新发布了猎头职位 <a href=\"".$this->config['sy_weburl']."/lietou/index.php?c=jobshow&id=".$id."\" target=\"_blank\">".$_POST['job_name']."</a>。";
					$state['uid']=$this->uid;
					$state['content']=$state_content;
					$state['ctime']=time();
					$state['type']=2;
					$this->obj->insert_into("friend_state",$state);
					$this->obj->member_log("发布猎头职位",1,1);
					$data['msg']='发布职位成功！';
					if($this->config['lt_job_status']=='1'){
						$data['url']='index.php?c=job&s=1';
					}else{
						$data['url']='index.php?c=job&s=0';
					}
				}else{
					$data['msg']='发布职位失败！';
					
				}
			}
		}
		$this->yunset("layer",$data);
		$this->yunset("today",date("Y-m-d"));
		$this->yunset($CacheList);
		$backurl=Url('wap',array(),'member');
		$this->yunset('backurl',$backurl);
		$this->user_shell();
		$this->waptpl('jobadd');
	}

	function binding_action(){
		if($_POST['moblie']){
			$row=$this->obj->DB_select_once("company_cert","`uid`='".$this->uid."' and `check`='".$_POST['moblie']."'");
			if(!empty($row)){
				if($row['check2']!=$_POST['code']){
					echo 3;die;
				}
				$this->obj->DB_update_all("member","`moblie`=''","`moblie`='".$row['check']."'");
				$this->obj->DB_update_all("resume","`moblie_status`='0',`telphone`=''","`telphone`='".$row['check']."'");
				$this->obj->DB_update_all("company","`moblie_status`='0',`moblie`=''","`linktel`='".$row['check']."'");
				$this->obj->DB_update_all("lt_info","`moblie_status`='0',`moblie`=''","`moblie`='".$row['check']."'");

				$this->obj->DB_update_all("member","`moblie`='".$row['check']."'","`uid`='".$this->uid."'");
				$this->obj->DB_update_all("lt_info","`moblie`='".$row['check']."',`moblie_status`='1'","`uid`='".$this->uid."'");
				$this->obj->DB_update_all("company_cert","`status`='1'","`uid`='".$this->uid."' and `check2`='".$_POST['code']."'");
				$this->obj->member_log("手机绑定");
				$pay=$this->obj->DB_select_once("company_pay","`pay_remark`='手机绑定' and `com_id`='".$this->uid."'");
				if(empty($pay)){
					$this->MODEL('integral')->get_integral_action($this->uid,"integral_mobliecert","手机绑定");
				}
				echo 1;die;
			}else{
				echo 2;die;
			}
		}
		if($_GET['type']){
			if($_GET['type']=="moblie"){
				$this->obj->DB_update_all("lt_info","`moblie_status`='0'","`uid`='".$this->uid."'");
			}
			if($_GET['type']=="email"){
				$this->obj->DB_update_all("lt_info","`email_status`='0'","`uid`='".$this->uid."'");
			}
			if($_GET['type']=="qqid"){
				$this->obj->DB_update_all("member","`qqid`=''","`uid`='".$this->uid."'");
			}
			if($_GET['type']=="sinaid"){
				$this->obj->DB_update_all("member","`sinaid`=''","`uid`='".$this->uid."'");
			}
			$this->waplayer_msg('解除绑定成功！');
		}
		$member=$this->obj->DB_select_once("member","`uid`='".$this->uid."'");
		$this->yunset("member",$member);
		if(($member['qqid']!=""||$member['wxid']!=""||$member['unionid']!=""||$member['sinaid']!="") && $member['restname']=="0"){
			$this->yunset("setname",1);
		}
		$lt=$this->obj->DB_select_once("lt_info","`uid`='".$this->uid."'");
		$this->yunset("lt",$lt);
		$cert=$this->obj->DB_select_once("company_cert","`uid`='".$this->uid."' and type='4'");
		$this->yunset("cert",$cert);
		$backurl=Url('wap','','member');
		$this->yunset('backurl',$backurl);
		$this->user_shell();
		$this->waptpl('binding');
	}

	function bindingbox_action(){
		$member=$this->obj->DB_select_once("member","`uid`='".$this->uid."'");
		$this->yunset("member",$member);
		$this->rightinfo();
		$backurl=Url('wap',array('c'=>'binding'),'member');
		$this->yunset('backurl',$backurl);
		$this->user_shell();
		$this->waptpl('bindingbox');
	}

	function ltcert_action(){
		if($_POST['submit']){
			preg_match('/^(data:\s*image\/(\w+);base64,)/', $_POST['uimage'], $result);
			$uimage=str_replace($result[1], '', str_replace('#','+',$_POST['uimage']));

			if(in_array(strtolower($result[2]),array('jpg','png','gif','jpeg'))){
	
				$new_file = time().rand(1000,9999).".".$result[2];
					
			}else{
				$new_file = time().rand(1000,9999).".jpg";
			}

			$im = imagecreatefromstring(base64_decode($uimage));
				
			if ($im === false) {
				echo 2;die;
			}
			if (!file_exists(DATA_PATH."upload/cert/".date('Ymd')."/")){
				mkdir(DATA_PATH."upload/cert/".date('Ymd')."/");
				chmod(DATA_PATH."upload/cert/".date('Ymd')."/",0777);
			}
			$re=file_put_contents(DATA_PATH."upload/cert/".date('Ymd')."/".$new_file, base64_decode($uimage));
			chmod(DATA_PATH."upload/cert/".date('Ymd')."/".$new_file,0777);
			if($re){
				if($this->config['lt_cert_status']=="1"){
					$sql['status']=0;
				}else{
					$sql['status']=1;
					$this->obj->DB_update_all("lt_info","`yyzz_status`='1'","`uid`='".$this->uid."'");
				}
				$photo="./data/upload/cert/".date('Ymd')."/".$new_file;
				$sql['step']=1;
				$sql['check']=$photo;
				$sql['check2']="4";
				$sql['ctime']=mktime();
				$company=$this->obj->DB_select_once("company_cert","`uid`='".$this->uid."' and type='4'","`check`");
				if(is_array($company)){
					unlink_pic(APP_PATH.$company['check']);
					$where['uid']=$this->uid;
					$where['type']='4';
					$this->obj->update_once("company_cert",$sql,$where);
					$this->obj->member_log("更新职业资格证书");
				}else{
					$sql['uid']=$this->uid;
					$sql['did']=$this->userdid;
					$sql['type']='4';
					$this->obj->insert_into("company_cert",$sql);
					$this->obj->member_log("上传职业资格证书");
					if($this->config['lt_cert_status']=="0"){
						$uid=$this->uid;
						$ulen=9-strlen($uid);
						for($a=1;$a<$ulen;$a++){
							$uid="0".$uid;
						}
						$data['rzid']="YLT".$uid;
						$this->obj->update_once("lt_info",$data,array("uid"=>$uid));
						$this->MODEL('integral')->get_integral_action($this->uid,"integral_ltcert","猎头执照认证");
					}
				}
				echo 1;die;
			}else{
				unlink_pic(APP_PATH."data/upload/cert/".date('Ymd')."/".$new_file);
				echo 2;die;
			}
		}else{
			$company=$this->obj->DB_select_once("company_cert","`uid`='".$this->uid."' and `type`='4'","`check`,`status`");
			if($company['check']==""){
				$company['check']='/'.$this->config['sy_unit_icon'];
			}
			$this->yunset("company",$company);
			$backurl=Url('wap',array('c'=>'binding'),'member');
		    $this->yunset('backurl',$backurl);
			$this->user_shell();
			$this->waptpl('ltcert');
		}
	}

	function setname_action(){
		if($_POST['username']){
			$user=$this->obj->DB_select_once("member","`uid`='".$this->uid."'");
			if(($user['qqid']==""&&$user['wxid']==""&&$user['unionid']==""&&$user['sinaid']=="") || $user['restname']=="1"){
				echo "您无权修改账户！";die;
			}
			$username=$_POST['username'];
			$num = $this->obj->DB_select_num("member","`username`='".$username."'");
			if($num>0){
				echo "用户名已存在！";die;
			}
			if($this->config['sy_regname']!=""){
				$regname=@explode(",",$this->config['sy_regname']);
				if(in_array($username,$regname)){
					echo "该用户名禁止使用！";die;
				}
			}
			$salt = substr(uniqid(rand()), -6);
			$password = md5(md5($_POST['password']).$salt);
			$data['password']=$password;
			$data['salt']=$salt;
			$data['username']=$username;
			$data['restname']=1;
			$this->obj->update_once('member',$data,array('uid'=>$this->uid));
			$this->cookie->unset_cookie();
			$this->obj->member_log("修改账户",8);
			echo 1;die;
		}
		$user=$this->obj->DB_select_once("member","`uid`='".$this->uid."'");
		if(($user['qqid']==""&&$user['wxid']==""&&$user['unionid']==""&&$user['sinaid']=="") || $user['restname']=="1"){
			$data['msg']="您无权修改账户！";
			$data['url']='index.php?c=binding';
			$this->yunset("layer",$data);
		}
		$this->rightinfo();
		$backurl=Url('wap',array('c'=>'binding'),'member');
		$this->yunset('backurl',$backurl);
		$this->user_shell();
		$this->waptpl('setname');
	}


	function look_resume_action(){
		$where="a.`com_id`='".$this->uid."' and a.`resume_id`=b.`id`";
		$this->resume("look_resume",$where);
		$backurl=Url('wap',array(),'member');
		$this->yunset('backurl',$backurl);
		$this->user_shell();
		$this->waptpl('look_resume');
	}
	function lookdel_action(){
		if($_GET['del']){
			$delid=(int)$_GET['del'];
			$nid=$this->obj->DB_delete_all("look_resume","`com_id`='".$this->uid."' and `resume_id` in (".$delid.")","");
			if($nid){
				$this->obj->member_log("删除浏览过的简历");
				$this->waplayer_msg('删除成功！');
			}else{
				$this->waplayer_msg('删除失败！');
			}
		}
	}

	function down_resume_action(){
		$where="a.`comid`='".$this->uid."' and a.`eid`=b.`id` and b.`height_status`='2'";
		$this->resume("down_resume",$where);
		$backurl=Url('wap',array(),'member');
		$this->yunset('backurl',$backurl);
		$this->user_shell();
		$this->waptpl('down_resume');
	}
	function downdel_action(){
		if($_GET['del']){
 			$delid=(int)$_GET['del'];
			$nid=$this->obj->DB_delete_all("down_resume","`comid`='".$this->uid."' and `eid` in (".$delid.")","");
 			if($nid){
 				$this->obj->member_log("删除下载的简历");
 				$this->waplayer_msg('删除成功！');
			}else{
				$this->waplayer_msg('删除失败！');
			}
 		}
	}

	function yp_resume_action(){
		$where="a.`com_id`='".$this->uid."' and a.`eid`=b.`id` and `height_status`='2'";
		$this->resume("userid_job",$where);
		$backurl=Url('wap',array(),'member');
		$this->yunset('backurl',$backurl);
		$this->user_shell();
		$this->waptpl('yp_resume');
	}
	function ypdel_action(){
		if($_GET['del']){
 			$delid=(int)$_GET['del'];
			$nid=$this->obj->DB_delete_all("userid_job","`com_id`='".$this->uid."' and `eid` in (".$delid.")","");
 			if($nid){
 				$this->obj->member_log("删除应聘来的简历");
 				$this->waplayer_msg('删除成功！');
			}else{
				$this->waplayer_msg('删除失败！');
			}
 		}
	}

	function entrust_resume_action(){
		$where="a.`lt_uid`='".$this->uid."' and a.`uid`=b.`uid` and `height_status`='2'";
		$delwhere="`lt_uid`='".$this->uid."' and `id`='".(int)$_GET['del']."'";
		$this->resume("entrust",$where,$delwhere,"委托来的简历");
		
		$this->obj->DB_update_all("entrust","`remind_status`='1'","`lt_uid`='".$this->uid."' and `remind_status`='0'");

		$backurl=Url('wap',array(),'member');
		$this->yunset('backurl',$backurl);
		$this->user_shell();
		$this->waptpl('entrust_resume');
	}
	function entrustdel_action(){
		if($_GET['del']){
			$delid=(int)$_GET['del'];	
			$nid=$this->obj->DB_delete_all("entrust","`lt_uid`='".$this->uid."' and `uid` in (".$delid.")","");
			if($nid){
				$this->obj->member_log("删除委托的简历");
				$this->waplayer_msg('删除成功！');
			}else{
				$this->waplayer_msg('删除失败！');
			}
		}
	}

	function reward_list_action(){
		$urlarr['c']='reward_list';
		$urlarr["page"]="{{page}}";
		$pageurl=Url('wap',$urlarr,'member');
		$rows=$this->get_page("change","`uid`='".$this->uid."' order by id desc",$pageurl,"10");
		if(is_array($rows)){
			foreach($rows as $key=>$val){
				$gid[]=$val['gid'];
			}
			$M=$this->MODEL('redeem');
			$gift=$M->GetReward(array('`id` in('.pylode(',', $gid).')'),array('field'=>'id,pic'));
			foreach($rows as $k=>$val){
				foreach ($gift as $v){
					if($val['gid']==$v['id']){
						$rows[$k]['pic']=$v['pic'];
					}
				}
			}
		}
		$statis=$this->obj->DB_select_once("lt_statis","`uid`='".$this->uid."'","integral");
		$statis[integral]=number_format($statis[integral]);
		$this->yunset("statis",$statis);
		$this->yunset('rows',$rows);
		$backurl=Url('wap',array(),'member');
		$this->yunset('backurl',$backurl);
		$this->user_shell();
		$this->waptpl('reward_list');
	}

	function rewarddel_action(){
		if($this->usertype!='3' || $this->uid==''){
			$this->waplayer_msg('登录超时！');
		}else{
			$rows=$this->obj->DB_select_once("change","`uid`='".$this->uid."' and `id`='".(int)$_GET['id']."' ");
			if($rows['id']){
				$this->obj->DB_update_all("reward","`num`=`num`-".$rows['num'].",`stock`=`stock`+".$rows['num']."","`id`='".$rows['gid']."'");
				$this->MODEL('integral')->company_invtal($this->uid,$rows['integral'],true,"取消兑换",true,2,'integral',24);
				$this->obj->DB_delete_all("change","`uid`='".$this->uid."' and `id`='".(int)$_GET['id']."' ");
			}
			$this->obj->member_log("取消兑换");
			$this->waplayer_msg('取消成功！');
		}
	}

	function rating_action(){
		$lt=$this->obj->DB_select_once("lt_info","`uid`='".$this->uid."'");
		$statis=$this->obj->DB_select_once("lt_statis","`uid`='".$this->uid."'");
		$this->yunset("statis",$statis);
		$this->yunset("lt",$lt);
		$row=$this->obj->DB_select_all("company_rating","`category`='2' and `display`='1'and `type`='1' order by `type` asc,`sort` desc");
		if (is_array($row)&&$row){
			foreach ($row as $v){
				$couponid[]=$v['coupon'];
			}
			if(empty($coupon)){
				$coupon=$this->obj->DB_select_all("coupon","`id` in (".@implode(",",$couponid).")","`id`,`name`");
			}
			if (is_array($coupon)){
				foreach ($row as $k=>$v){
					foreach ($coupon as $val){
						if ($v['coupon']==$val['id']){
							$row[$k]['couponnmae']=$val['name'];
						}
					}
				}
			}
		}
		if ($row&&is_array($row)){
			foreach ($row as $k=>$v){
				$rname=array();
				if($v['lt_job_num']>0){$rname[]='猎头发布职位数:'.$v['lt_job_num'].'份';}
				if($v['lt_editjob_num']>0){$rname[]='猎头修改职位数:'.$v['lt_editjob_num'].'份';}
				if($v['lt_breakjob_num']>0){$rname[]='猎头刷新职位数:'.$v['lt_breakjob_num'].'份';}
				if($v['lt_resume']>0){$rname[]='猎头下载简历数:'.$v['lt_resume'].'份';}
				$row[$k]['rname']=@implode('+',$rname);
			}
		}
		
		$backurl=Url('wap',array('c'=>mypay),'member');
		$this->yunset("row",$row);
		$this->yunset("js_def",4);
		$this->yunset('backurl',$backurl);
		$this->user_shell();
		$this->waptpl('lietou_rating');
	}

	function time_action(){
		$lt=$this->obj->DB_select_once("lt_info","`uid`='".$this->uid."'");
		$statis=$this->obj->DB_select_once("lt_statis","`uid`='".$this->uid."'");
		$this->yunset("statis",$statis);
		$this->yunset("lt",$lt);
		$row=$this->obj->DB_select_all("company_rating","`category`='2' and `display`='1'and `type`='2' order by `type` asc,`sort` desc");
		if (is_array($row)&&$row){
			foreach ($row as $v){
				$couponid[]=$v['coupon'];
			}
			if(empty($coupon)){
				$coupon=$this->obj->DB_select_all("coupon","`id` in (".@implode(",",$couponid).")","`id`,`name`");
			}
			if (is_array($coupon)){
				foreach ($row as $k=>$v){
					foreach ($coupon as $val){
						if ($v['coupon']==$val['id']){
							$row[$k]['couponnmae']=$val['name'];
						}
					}
				}
			}
		}
		if ($row&&is_array($row)){
			foreach ($row as $k=>$v){
				$rname=array();
				if($v['lt_job_num']>0){$rname[]='猎头发布职位数:'.$v['lt_job_num'].'份';}
				if($v['lt_editjob_num']>0){$rname[]='猎头修改职位数:'.$v['lt_editjob_num'].'份';}
				if($v['lt_breakjob_num']>0){$rname[]='猎头刷新职位数:'.$v['lt_breakjob_num'].'份';}
				if($v['lt_resume']>0){$rname[]='猎头下载简历数:'.$v['lt_resume'].'份';}
				$row[$k]['rname']=@implode('+',$rname);
			}
		}
		$backurl=Url('wap',array('c'=>mypay),'member');
		$this->yunset("row",$row);
		$this->yunset("js_def",4);
		$this->yunset('backurl',$backurl);
		$this->user_shell();
		$this->waptpl('lietou_time');
	}

	function mypay_action(){
		$statis=$this->obj->DB_select_once("lt_statis","`uid`='".$this->uid."'");
		$statis['integral_format']=number_format($statis['integral']);
		$this->yunset("statis",$statis);
		$backurl=Url('wap',array(),'member');
		$this->yunset('backurl',$backurl);
		$this->user_shell();
		$this->waptpl('mypay');
	}
	function give_rebates_action(){
		$urlarr=array("c"=>"give_rebates","page"=>"{{page}}");
		$pageurl=Url('wap',$urlarr,'member');
		$rows=$this->get_page("rebates","`job_uid`='".$this->uid."' order by id desc",$pageurl,"10");
		if(is_array($rows)){
			foreach($rows as $k=>$v){
				$uid[]=$v['uid'];
				$id[]=$v['id'];
			}
			$uid=pylode(",",$uid);
			$user=$this->obj->DB_select_all("member","`uid` in (".$uid.")","`uid`,`username`");
			$temporary=$this->obj->DB_select_all("temporary_resume","`rid` in (".pylode(",",$id).")","`rid`,`email`");
			foreach($rows as $k=>$v){
				foreach($user as $val){
					if($v['uid']==$val['uid']){
						$rows[$k]['username']=$val['username'];
					}
				}
				foreach($temporary as $val){
					if($v['id']==$val['rid']){
						$rows[$k]['email']=$val['email'];
					}
				}
			}
		}
		$this->yunset("rows",$rows);
		$backurl=Url('wap',array(),'member');
		$this->yunset('backurl',$backurl);
		$this->user_shell();
		$this->waptpl('give_rebates');
	}
	function save_give_rebates_action(){
		if($_POST){
			$data['reply']=$_POST['reply'];
			$data['reply_time']=time();
			$data['status']=1;
			$where['id']=(int)$_POST['id'];
			$where['job_uid']=$this->uid;
			$this->obj->update_once("rebates",$data,$where);
			$this->obj->member_log("回复推荐给我的返利");
			echo 1;die;
		}
	}	
	function rebates_set_action(){
		if($_POST['id']){
			$where['id']=(int)$_POST['id'];
			$where['job_uid']=$this->uid;
			$nid=$this->obj->update_once("rebates",array("status"=>(int)$_POST['status']),$where);
			echo 1;die;
		}
	}
	function my_rebates_action(){
		$urlarr=array("c"=>"my_rebates","page"=>"{{page}}");
		$pageurl=Url('wap',$urlarr,'member');
		$rows=$this->get_page("rebates","`uid`='".$this->uid."' order by id desc",$pageurl,"10");
		if(is_array($rows)){
			foreach($rows as $k=>$v){
				$uids[]=$v['job_id'];
			}
			$job=$this->obj->DB_select_all("lt_job","`id` in(".pylode(',',$uids).")","`id`,`job_name`,`com_name`,`rebates`,`usertype`");
			foreach($rows as $k=>$v){
				foreach($job as $val){
					if($v['job_id']==$val['id']){
						$rows[$k]['job_name']=$val['job_name'];
						$rows[$k]['com_name']=$val['com_name'];
						$rows[$k]['rebates']=$val['rebates'];
						if($val['usertype']==2){
							$rows[$k]['type']=2;
						}else{
							$rows[$k]['type']=3;
						}
					}
				}
			}
		}
		$this->yunset("rows",$rows);
		$backurl=Url('wap',array(),'member');
		$this->yunset('backurl',$backurl);
		$this->user_shell();
		$this->waptpl('my_rebates');
	}
	function delrebate_action(){
		if($_GET['id']){
			$del=(int)$_GET['id'];
			$this->obj->DB_delete_all("temporary_resume","`rid`='".$del."'","");
			if($_GET['type']==1){
				$nid=$this->obj->DB_delete_all("rebates","`job_uid`='".$this->uid."' and `id`='".$del."'","");
			}else{
				$nid=$this->obj->DB_delete_all("rebates","`uid`='".$this->uid."' and `id`='".$del."'","");	
			}
			if($nid){
				if($_GET['type']==1){
					$this->obj->member_log("删除推荐给我的人才");
				}else{
					$this->obj->member_log("删除我推荐的悬赏");
				}
				$this->waplayer_msg('删除成功！');
			}else{
				$this->waplayer_msg('删除失败！');
			}
		}
	}
	function rebateshow_action(){
		if(intval($_GET['id'])){
			$this->obj->DB_update_all("rebates","`status`='1'","`id`='".intval($_GET['id'])."'");
			include(CONFIG_PATH."db.data.php");
			$CacheList=$this->MODEL('cache')->GetCache(array('user','hy','job','city'));
			$rebate=$this->obj->DB_select_once("rebates","`id`='".intval($_GET['id'])."'");
			$resume=$this->obj->DB_select_once("temporary_resume","`rid`='".intval($_GET['id'])."'");
			$resume['sex']=$arr_data['sex'][$resume['sex']];
			if($resume['job_classid']){
				$jobids=@explode(',',$resume['job_classid']);
				foreach($jobids as $val){
					$jobname[]=$CacheList['job_name'][$val];
				}
				$resume['jobname']=@implode('、',$jobname);
			}
			if($CacheList['city_name'][$resume['three_cityid']]){
				$resume['city']=$CacheList['city_name'][$resume['provinceid']].'-'.$CacheList['city_name'][$resume['cityid']].'-'.$CacheList['city_name'][$resume['three_cityid']];
			}elseif($CacheList['city_name'][$resume['cityid']]){
				$resume['city']=$CacheList['city_name'][$resume['provinceid']].'-'.$CacheList['city_name'][$resume['cityid']];
			}elseif($CacheList['city_name'][$resume['provinceid']]){
				$resume['city']=$CacheList['city_name'][$resume['provinceid']];
			}
			
			if($resume['minsalary']){
				if($resume['maxsalary']){
					$resume['rsalary']='￥'.$resume['minsalary'].'-'.$resume['maxsalary'].'万/年';
				}else{
					$resume['rsalary']='￥'.$resume['minsalary'].'万/年以上';
				}
			}else{
				$resume['rsalary']='面议';
			}
		}
		$this->yunset($CacheList);
		$this->yunset("rebate",$rebate);
		$this->yunset("resume",$resume);
		$backurl=Url('wap',array(),'member');
		$this->yunset('backurl',$backurl);
		$this->user_shell();
		$this->waptpl('rebateshow');
	}
	function paylog_action(){
		include(CONFIG_PATH."db.data.php");
		$this->yunset("arr_data",$arr_data);
		$urlarr=array("c"=>"paylog","page"=>"{{page}}");
		$pageurl=Url('wap',$urlarr,'member');
		$where="`uid`='".$this->uid."' and `order_price`> 0  order by order_time desc";
		$rows=$this->get_page("company_order",$where,$pageurl,"10");
		if($rows&&is_array($rows)){
			foreach($rows as $key=>$val){
				$rows[$key]['sname']=$arr_data['paystate'][$val['order_state']];
				$rows[$key]['type']=$arr_data['pay'][$val['order_type']];
			}
		}
		$this->yunset("rows",$rows);
		$this->yunset('backurl','index.php');
		$this->user_shell();
		$this->waptpl('paylog');
	}
	function delpaylog_action(){
		if($this->usertype!='3' || $this->uid==''){
			$this->waplayer_msg('登录超时！');
		}else{
			$oid=$this->obj->DB_select_once("company_order","`uid`='".$this->uid."' and `id`='".(int)$_GET['id']."' and `order_state`='1'");
			if(empty($oid)){
				$this->waplayer_msg('订单不存在！');
			}else{
				$this->obj->DB_delete_all("company_order","`id`='".$oid['id']."' and `uid`='".$this->uid."'");
				$this->obj->DB_delete_all("invoice_record","`oid`='".$oid['id']."'  and `uid`='".$this->uid."'");
				$this->waplayer_msg('取消成功！');
			}
		}
	}
	function consume_action(){
		$urlarr=array("c"=>"consume","page"=>"{{page}}");
		$pageurl=Url('wap',$urlarr,'member');
		$where="`com_id`='".$this->uid."' order by pay_time desc";
		$rows = $this->get_page("company_pay",$where,$pageurl,"10");
		if(is_array($rows)){
			foreach($rows as $k=>$v){
				$rows[$k]['pay_time']=date("Y-m-d H:i:s",$v['pay_time']);
				$rows[$k]['order_price']=str_replace(".00","",$rows[$k]['order_price']);
			}
		}
		$this->yunset('backurl','index.php');
		$this->yunset("rows",$rows);
		$this->user_shell();
		$this->waptpl('consume');
	}
	function loglist_action(){
		$userM  = $this->MODEL('userinfo');
		$statis = $userM->GetUserstatisOne(array('uid'=>$this->uid),array('usertype'=>1));

		$urlarr['c']=$_GET['c'];
		$urlarr["page"]="{{page}}";
		$pageurl=Url('wap',$urlarr,'member');
		$rows=$this->get_page("company_job_sharelog","`uid`='".$this->uid."' order by time desc",$pageurl,"10");

		$this->yunset("rows",$rows);
		$statis['packpay'] = sprintf("%.2f", $statis['packpay']);
		$statis['freeze'] = sprintf("%.2f", $statis['freeze']);
		$this->yunset("statis",$statis);
		$this->waptpl('loglist');
	}

	function withdraw_action(){
		
		if($_POST){

			$M			=	$this->MODEL('pack');
			
			 $return	=  $M->withDraw($this->uid,$this->usertype,$_POST['price'],$_POST['real_name']);
				
			 if($return==''){
				$data['msg']='提现成功，请关注微信账户提醒！';
				$data['url']='index.php?c=withdrawlist';
			
				$this->yunset("layer",$data);
				
					
			 }else{
				
				
				 $data['msg']=$return;
				 $data['url']='index.php?c=withdrawlist';
			
				$this->yunset("layer",$data);
				
			 }
			
		}else{
			$userM  = $this->MODEL('userinfo');
			$statis = $userM->GetUserstatisOne(array('uid'=>$this->uid),array('usertype'=>3));
			$statis['packpay'] = sprintf("%.2f", $statis['packpay']);
			$statis['freeze'] = sprintf("%.2f", $statis['freeze']);
			$this->yunset("statis",$statis);
			
		}
		$this->waptpl('withdraw');
	}
	function withdrawlist_action(){
		
		$urlarr["c"]="jobpack";
		$urlarr["act"]="withdrawlist";
		$urlarr["page"]="{{page}}";
		$pageurl=Url('wap',$urlarr,'member');
		$where = "`uid`='".$this->uid."'";
		$rows=$this->get_page("member_withdraw",$where." order by id desc",$pageurl,"10");

		if(is_array($rows)){
			include (APP_PATH."/config/db.data.php");
			foreach($rows as $k=>$v){
				$rows[$k]['order_state_n']=$arr_data['withdrawstate'][$v['order_state']];
			}
		}
		$userM  = $this->MODEL('userinfo');
		$statis = $userM->GetUserstatisOne(array('uid'=>$this->uid),array('usertype'=>3));

		$this->yunset("statis",$statis);
		$this->yunset("rows",$rows);
		$this->waptpl('withdrawlist');
	}
	function rewardlog_action(){	

		$urlarr=array("c"=>"jobpack",'c'=>'rewardlog',"page"=>"{{page}}");
		$where="`uid`='".$this->uid."' ";
		if($_GET['jobid']){
			$where.=" AND `jobid`='".(int)$_GET['jobid']."'";
			$urlarr['jobid']=$_GET['jobid'];
		}
		
		$pageurl=Url('wap',$urlarr,'member');
 
		$rows=$this->get_page("company_job_rewardlist",$where." order by datetime DESC",$pageurl,'10');
		
		if(is_array($rows) && !empty($rows)){
			$jobids=array();
			foreach($rows as $v){
				$jobids[]=$v['jobid'];
				
				if($v['usertype']=='3'){
					$lteid[]=$v['eid'];
				}else{
					$eid[]=$v['eid'];
				}
				$rewardid[] = $v['id'];
			}
			$joblist = $this->obj->DB_select_all("company_job","`id` IN (".@implode(',',$jobids).")");

			include PLUS_PATH."/user.cache.php";
			include PLUS_PATH."/job.cache.php";
			if(!empty($eid)){
				$ulist = $this->obj->DB_select_all("resume_expect","`id` IN (".@implode(',',$eid).")");

			}
			if(!empty($lteid)){
				$ltulist = $this->obj->DB_select_all("lt_talent","`id` IN (".@implode(',',$lteid).")");

			}

			$M			=	$this->MODEL('pack');
			

			$log = $this->obj->DB_select_all("company_job_rewardlog","`rewardid` IN (".@implode(',',$rewardid).") ORDER BY id ASC");
			if(is_array($log)){
				foreach($log as $value){
					$logList[$value['rewardid']][] = $value;
					
				}
			}
			foreach($rows as $k=>$v){

					$rows[$k]['log'] = $M->getStatusInfo($v['id'],1,$v['status'],$logList[$v['id']]);

				
				foreach($joblist as $val){
					if($v['jobid']==$val['id']){
						$rows[$k]['name']=$val['name'];
					}
				}
				if(is_array($ulist)){
					foreach($ulist as $val){
						if($v['eid']==$val['id']){
							$rows[$k]['uname']=$val['uname'];
							$rows[$k]['edu']=$userclass_name[$val['edu']];
							$rows[$k]['exp']=$userclass_name[$val['exp']];
							if($val['job_classid']){
								$class = @explode(',',$val['job_classid']);
								foreach($class as $v){
									$classname[] = $job_name[$v];
								}
								$rows[$k]['jobclass']=@implode(',',$classname);
								unset($classname);
							}
						}
					}
				}
				if(is_array($ltulist)){
					foreach($ltulist as $val){
						if($v['eid']==$val['id']){
							$rows[$k]['uname']=mb_substr($val['name'],0,1,'utf-8').'**';
							$rows[$k]['edu']=$userclass_name[$val['edu']];
							$rows[$k]['exp']=$userclass_name[$val['exp']];
							
							$rows[$k]['jobclass']=$val['jobname'];
								
						}
					}
				}
				
			}
		}
		
		$this->yunset("rows",$rows);
		$this->waptpl('jobrewardlog');
	}
	
	function logstatus_action(){
		if($_POST){
				
			 $M			=	$this->MODEL('pack');
			 $return	=  $M->logStatus((int)$_POST['rewardid'],(int)$_POST['status'],$this->uid,'1',$_POST);
				
			 if($return['error']==''){
				 echo json_encode(array('error'=>'ok'));
					
			 }else{
				 
				 echo json_encode(array('error'=>$return['error']));
			 }
		}

	
	}
	function arb_action(){
		if($_POST){

			if(!$_POST['rewardid']){
				$this->ACT_layer_msg("请选择需要仲裁的赏单！",8,$_SERVER['HTTP_REFERER']);
			}
			if(!$_POST['content']){
				$this->ACT_layer_msg("请填写仲裁原因！",8,$_SERVER['HTTP_REFERER']);
			}else{
				$data['content'] = $_POST['content'];
			}

			
			if (is_uploaded_file($_FILES['arbpic']['tmp_name'])) {
				$UploadM=$this->MODEL('upload');
				$upload=$UploadM->Upload_pic("../data/upload/pack/".$this->uid.'/',false);
				$arbpic=$upload->picture($_FILES['arbpic']);
				
				$picmsg=$UploadM->picmsg($arbpic,$_SERVER['HTTP_REFERER']);
				if($picmsg['status'] == $arbpic){
					$this->ACT_layer_msg($picmsg['msg'],8);
				}
				$arbpic = str_replace("../data/","./data/",$arbpic);
				$data['arbpic'] = $arbpic;
			}
			
			 $M			=	$this->MODEL('pack');

			 $return	=  $M->logStatus((int)$_POST['rewardid'],26,$this->uid,'1',$data);
				
			 if($return['error']==''){
				$data['msg']='仲裁提交成功！';
				$data['url']='index.php?c=rewardlog';
			
				$this->yunset("layer",$data);
					
			 }else{
				 $data['msg']=$return['error'];
				$data['url']='index.php?c=rewardlog';
			
				$this->yunset("layer",$data);
			 }
		}elseif($_GET['rewardid']){
		
			
			
		}
	
		$this->waptpl('jobrewardarb');
	}

	function talent_action(){
		
		$urlarr=array("c"=>"talent","page"=>"{{page}}");
		$pageurl=Url('member',$urlarr);
		$rows=$this->get_page("lt_talent","`uid`='".$this->uid."' order by id desc",$pageurl,"10");

		if(is_array($rows)){
			foreach($rows as $key=>$value){
				$id[] = $value['id'];
			}
			
			$rewardList = $this->obj->DB_select_all('company_job_rewardlist',"`eid` IN (".pylode(',',$id).") AND `status` NOT IN ('18','19','20','21','23','26','27','28','29')");
			if(is_array($rewardList)){ 
				foreach($rewardList as $key=>$value){
					$rewardStatusId[] = $value['eid'];
				}
				foreach($rows as $key=>$value){
					if(in_array($value['id'],$rewardStatusId)){
						$rows[$key]['rewardstatus'] = '1';
					}
				}
			}
			
		}
		$this->yunset("rows",$rows);
		$this->yunset($this->MODEL('cache')->GetCache(array('city','user')));
		$this->waptpl('talent');
	}

	function talentexpect_action(){
		
		$talentM = $this->MODEL('talent');

		if($_GET['id']){
			$expectInfo = $talentM->getTalent($this->uid,$_GET['id']);
			
			$this->yunset("resume",$expectInfo);
			
		}
		$this->yunset($this->MODEL('cache')->GetCache(array('city','user','hy')));
		
		include(CONFIG_PATH."db.data.php");
		unset($arr_data['sex'][3]);
		$this->yunset("arr_data",$arr_data);

		$this->waptpl('talent_expect');
	}
	function savetalentexpect_action(){

		if($_POST){
			
			$talentM = $this->MODEL('talent');
			$return  = $talentM->addTalent($_POST);
			
			echo json_encode($return);
			
		}
	
	}
	function talentdel_action(){
		if($_GET['id']){
			$del=(int)$_GET['id'];
			$this->obj->DB_delete_all("temporary_resume","`rid`='".$del."'","");
			$nid=$this->obj->DB_delete_all("rebates","`id`='".$del."' and `uid`='".$this->uid."'","");
			$this->obj->member_log("删除我推荐的人才");
			$nid?$this->layer_msg('删除成功！',9,0,"index.php?c=my_rebates"):$this->layer_msg('删除失败！',8,0,"index.php?c=my_rebates");
		}
	}

	function telstatus_action(){

		$talentM = $this->MODEL('talent');

		if($_GET['id']){

			$Info = $talentM->getTalent($this->uid,$_GET['id']);
			$this->yunset("Info",$Info);
			$this->waptpl('telstatus');
		}elseif($_POST['id'] && $_POST['linktel'] && $_POST['code']){

			$return  = $talentM->telStatus($_POST['id'],$_POST['linktel'],$_POST['code']);
			if($return['error']=='1'){

				$this->obj->member_log("简历库授权认证");
			}
			echo json_encode($return);
		}
	}
	

	function talentreward_action(){

		$packM = $this->MODEL('pack');
		$job = $packM->getRewardJob((int)$_GET['jobid'],'1');
		$this->yunset('job',$job);

		$urlarr=array("c"=>"talent","page"=>"{{page}}");
		$pageurl=Url('member',$urlarr);
		$rows=$this->get_page("lt_talent","`uid`='".$this->uid."' order by id desc",$pageurl,"10");

		if(is_array($rows)){
			foreach($rows as $key=>$value){
				$id[] = $value['id'];
			}
			
			$rewardList = $this->obj->DB_select_all('company_job_rewardlist',"`eid` IN (".pylode(',',$id).") AND `status` NOT IN ('18','19','20','21','23','26','27','28','29')");
			if(is_array($rewardList)){ 
				foreach($rewardList as $key=>$value){
					$rewardStatusId[] = $value['eid'];
				}
				foreach($rows as $key=>$value){
					if(in_array($value['id'],$rewardStatusId)){
						$rows[$key]['rewardstatus'] = '1';
					}
				}
			}
		}
		$this->yunset("rows",$rows);
		
		$CacheM = $this->MODEL('cache');
		$CacheList=$CacheM->GetCache(array('com'));
        $this->yunset($CacheList);
		$this->yunset($this->MODEL('cache')->GetCache(array('city','user')));
		$this->waptpl('talentreward');
	}
	
	function talentsqjob_action(){	
		
		
		$packM = $this->MODEL('pack');
	
		$return  = $packM->sqRewardJob($_POST['jobid'],$this->uid,$this->usertype,$_POST['eid']);
		
		echo json_encode($return);
	}
	function gotime_action(){
		if($_POST['gotimeid']&&$_POST['day']){
			$this->obj->DB_update_all("lt_job","`edate`=`edate`+".intval($_POST['day'])*24*3600,"`id`=".$_POST['gotimeid']);
			$data['msg']='延期成功！';
			$this->yunset("layer",$data);
			$backurl=Url('wap',array(),'member');
			$this->yunset('backurl',$backurl);
			$_GET['id']=$_POST['gotimeid'];
			$this->waptpl('gotime');	
		}else{
			if($_POST['gotimeid']){
				$_GET['id']=$_POST['gotimeid'];
			}
			$this->waptpl('gotime');
		}
	}
}
?>