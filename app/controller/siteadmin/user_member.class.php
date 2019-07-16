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
class user_member_controller extends siteadmin_controller{
	
	function set_search(){
		include(CONFIG_PATH."db.data.php");
		$source=$arr_data['source'];
		$search_list[]=array('param'=>'source','name'=>'数据来源','value'=>$source);
		$search_list[]=array('param'=>'lotime','name'=>'最近登录','value'=>array('1'=>'今天','3'=>'最近三天','7'=>'最近七天','15'=>'最近半月','30'=>'最近一个月'));
		$search_list[]=array('param'=>'adtime','name'=>'最近注册','value'=>array('1'=>'今天','3'=>'最近三天','7'=>'最近七天','15'=>'最近半月','30'=>'最近一个月'));
		$search_list[]=array("param"=>"status","name"=>'审核状态',"value"=>array('1'=>'已审核','2'=>'已锁定','3'=>'未通过','4'=>'未审核'));
		$this->yunset('source',$source);
		$this->yunset('search_list',$search_list);
	}
	function index_action(){
		$this->set_search();
        $where="`usertype`='1'";
		if(trim($_GET['keyword'])){
			if((int)$_GET['type']=="1"){
				$where .=" and `username` LIKE '%".trim($_GET['keyword'])."%'";
			}elseif((int)$_GET['type']=="2"){
				$resume = $this->obj->DB_select_all("resume","`name` LIKE '%".trim($_GET['keyword'])."%'","`uid`,`name`","resume");
				foreach($resume as $key=>$value){
					$uids[] = $value['uid'];
				}
				$where .=" and `uid` IN (".@implode(',',$uids).")";

			}elseif($_GET['type']=="3"){
				$where .=" and `email` LIKE '%".trim($_GET['keyword'])."%'";
			}else{
				$where .=" and `moblie` LIKE '%".trim($_GET['keyword'])."%'";
			}
			$urlarr['keyword']=$_GET['keyword'];
			$urlarr['type']=(int)$_GET['type'];
		}
		if($_GET['status']){
			if($_GET['status']=='4'){
				$where.=" and `status`='0'";
			}else if($_GET['status']){
				$where.=" and `status`='".intval($_GET['status'])."'";
			}
			$urlarr['status']=intval($_GET['status']);
		}
		if((int)$_GET['adtime']){
			if((int)$_GET['adtime']=='1'){
				$where .=" and `reg_date`>'".strtotime(date("Y-m-d 00:00:00"))."'";
			}else{
				$where .=" and `reg_date`>'".strtotime('-'.intval($_GET['adtime']).' day')."'";
			}
			$urlarr['adtime']=(int)$_GET['adtime'];
		}
		if((int)$_GET['lotime']){
			if((int)$_GET['lotime']=='1'){
				$where .=" and `login_date`>'".strtotime(date("Y-m-d 00:00:00"))."'";
			}else{
				$where .=" and `login_date`>'".strtotime('-'.intval($_GET['lotime']).' day')."'";
			}
			$urlarr['lotime']=(int)$_GET['lotime'];
		}
		if((int)$_GET['source']){
			$where .=" and `source` = '".(int)$_GET['source']."'";
			$urlarr['source']=(int)$_GET['source'];
		}
		if((int)$_GET['r_uid']){
			$where.=" and `uid`='".(int)$_GET['r_uid']."'";
			$urlarr['r_uid']=(int)$_GET['r_uid'];
		}
		if($_GET['order']){
			$where.=" order by ".$_GET['t']." ".$_GET['order'];
			$urlarr['order']=$_GET['order'];
			$urlarr['t']=$_GET['t'];
		}else{
			$where.=" order by uid desc";
		}
        $urlarr['page']='{{page}}';
		$pageurl=Url($_GET['m'],$urlarr,'admin');
		$userrows=$this->get_page("member",$where,$pageurl,$this->config['sy_listnum'],'*');
		if(is_array($userrows))
		{
			if(empty($resume)){

				foreach($userrows as $key=>$value)
				{
					$uids[] = $value['uid'];
				}
				$Resume=$this->MODEL('resume');
				$resume=$Resume->ResumeAll(array("`uid` IN (".@implode(',',$uids).")"),array("field"=>"`uid`,`name`",'special'=>'resume'));
			}

			foreach($userrows as $key=>$value)
			{
				foreach($resume as $k=>$v)
				{
					if($value['uid'] == $v['uid'])
					{
						$userrows[$key]['name'] = $v['name'];
					}
				}
			}
		}
		$this->yunset("userrows",$userrows);
		$nav_user=$this->get_admin_user_list("admin_user","admin_user_group","a.`m_id`=b.`id` and a.`uid`='".$_SESSION['auid']."'");
		$power=unserialize($nav_user[0]['group_power']);
		if(in_array('141',$power)){
			$this->yunset("email_promiss", '1');
		}
		if(in_array('163',$power)){
			$this->yunset("moblie_promiss", '1');
		}
		$this->siteadmin_tpl(array('admin_member_userlist'));
	}
	function lockinfo_action(){
		$userinfo = $this->MODEL('userinfo')->GetMemberOne(array('uid'=>(int)$_GET['uid']),array('field'=>'`lock_info`'));
		echo trim($userinfo['lock_info']);die;
	}
    
	function status_action(){
		extract($_POST);
        $UserinfoM=$this->MODEL('userinfo');
        $id=$UserinfoM->UpdateMember(array('status'=>$_POST['status'],'lock_info'=>trim($lock_info)),array('uid'=>$uid));
        $UserinfoM->UpdateUserinfo(array("values"=>array('r_status'=>$_POST['status'])),array('uid'=>$uid));
 		if($this->config['sy_email_lock']=='1'){
            $userinfo = $UserinfoM->GetMemberOne(array('uid'=>$uid),array('field'=>"`email`,`uid`,`name`,`usertype`"));
			$data=$this->forsend($userinfo);
      $notice = $this->MODEL('notice');
			$notice->sendEmailType(array("email"=>$userinfo['email'],'uid'=>$data['uid'],'name'=>$data['name'],"certinfo"=>$statusbody,"username"=>$userinfo['name'],"type"=>"lock"));
		}
 		$id?$this->ACT_layer_msg("个人会员锁定(ID:".$uid.")设置成功！",9,$_SERVER['HTTP_REFERER'],2,1):$this->ACT_layer_msg( "设置失败！",8,$_SERVER['HTTP_REFERER']);
	}
	function ckstatus_action(){
		$lock_info = trim($_POST['lock_info']);
		$uid=(int)$_POST['uid'];
 		$id=$this->obj->DB_update_all("member","`status`='".$_POST['status']."',`lock_info`='".$lock_info."'","`uid`='".$uid."'");
 		$this->obj->DB_update_all("resume","`r_status`='".$_POST['status']."'","`uid`='".$uid."' ");

 		if($this->config['sy_email_userstatus']=='1' && $_POST['status']==2){
			$userinfo = $this->obj->DB_select_once("member","`uid`=".$uid,"`email`,`uid`,`username`,`usertype`");
			$data=$this->forsend($userinfo);
      $notice = $this->MODEL('notice');
			$notice->sendEmailType(array("email"=>$userinfo['email'],'uid'=>$data['uid'],'name'=>$data['name'],"certinfo"=>$_POST['statusbody'],"username"=>$userinfo['username'],"type"=>"userstatus"));
		}

 		$id?$this->ACT_layer_msg("个人会员审核(ID:".$uid.")设置成功！",9,$_SERVER['HTTP_REFERER'],2,1):$this->ACT_layer_msg( "设置失败！",8,$_SERVER['HTTP_REFERER']);
	}
	function edit_action(){
		include(CONFIG_PATH."db.data.php");
		unset($arr_data['sex'][3]);
		$this->yunset("arr_data",$arr_data);
        $UserinfoM=$this->MODEL('userinfo');
		if((int)$_GET['id']){
			$com_info = $UserinfoM->GetMemberOne(array('uid'=>(int)$_GET['id']));
			$row=$UserinfoM->GetUserinfoOne(array('uid'=>(int)$_GET['id']),array('usertype'=>1));
            $this->yunset($this->MODEL('cache')->GetCache(array('user','city')));
			$this->yunset(array('row'=>$row,'com_info'=>$com_info,'lasturl'=>$_SERVER['HTTP_REFERER']));
		}
		if($_POST['com_update']){
			$_POST['uid']=(int)$_POST['uid'];
			$mem = $UserinfoM->GetMemberOne(array("uid"=>$_POST['uid']));
			if($mem['username']!=$_POST['username'] && $_POST['username']!=""){
				$num = $UserinfoM->GetMemberNum(array("username"=>$_POST['username']));
				if($num>0){
					$this->ACT_layer_msg("用户名已存在！",8,$_SERVER['HTTP_REFERER'],2,1);
				}else{
					$UserinfoM->UpdateMember(array("username"=>$_POST['username']),array("uid"=>$_POST['uid']));
				}
			}
		$moblienum=$this->obj->DB_select_num("member","moblie='".$_POST['moblie']."' and `uid`<>'".$_POST['uid']."'");
		    $emailnum=$this->obj->DB_select_num("member","email='".$_POST['email']."' and `uid`<>'".$_POST['uid']."'");
		    $idcardnum=$this->obj->DB_select_num("resume","idcard='".$_POST['idcard']."' and `uid`<>'".$_POST['uid']."'");
		    $telhomenum=$this->obj->DB_select_num("resume","telhome='".$_POST['telhome']."' and `uid`<>'".$_POST['uid']."'");
			if($_POST['moblie']&&!CheckMoblie($_POST['moblie'])){
				$this->ACT_layer_msg("手机格式错误！");
			}elseif($_POST['moblie']&&$moblienum){
				$this->ACT_layer_msg("手机已存在！");
			}
			if($_POST['email']&& CheckRegEmail($_POST['email'])==false){
				$this->ACT_layer_msg("邮箱格式错误！");
			}elseif($_POST['email']&&$emailnum){
				$this->ACT_layer_msg("邮箱已存在！");
			}
			if($_POST['idcard']&&!$this->CheckIdCard($_POST['idcard'])){
				$this->ACT_layer_msg("证件号码格式错误！");
			}elseif($_POST['idcard']&&$idcardnum){
				$this->ACT_layer_msg("证件号码已存在！");
			}
			if($_POST['telhome']&& CheckTell($_POST['telhome'])==false){
				$this->ACT_layer_msg("座机格式错误！");
			}elseif($_POST['telhome']&&$telhomenum){
				$this->ACT_layer_msg("座机已存在！");
			}
			$lasturl=str_replace("&amp;","&",$_POST['lasturl']);
			$post['uid']=$_POST['uid'];
			$post['password']=$_POST['password'];
			$post['email']=$_POST['email'];
			$post['moblie']=$_POST['moblie'];
			$post['status']=$_POST['status'];
			$post['address']=$_POST['address'];
			if(trim($post['password'])){
				$nid = $this->uc_edit_pw($post,1,'index.php?m=user_member');
			}
			if($_FILES['wxewm']['tmp_name']){
				$UploadM=$this->MODEL('upload');
				$upload=$UploadM->Upload_pic("../data/upload/user/",false);
				$wxewm=$upload->picture($_FILES['wxewm']);
				$picmsg=$UploadM->picmsg($wxewm,$_SERVER['HTTP_REFERER']);
				if($picmsg['status'] == $wxewm){
					$this->ACT_layer_msg($picmsg['msg'],8);
				}
				$wxewm = str_replace("../data/","./data/",$wxewm);
				$userwx=$this->obj->DB_select_once("resume","`uid`='".$_POST['uid']."'","wxewm");
				if($userwx['wxewm']){
					unlink_pic(".".$userwx['wxewm']);
				}
			}
			$value=array();			
			$value['name']=$_POST['name'];
			$value['sex']=$_POST['sex'];
			$value['living']=$_POST['living'];
			$value['domicile']=$_POST['domicile'];
			$value['r_status']=$_POST['status'];
			$value['birthday']=$_POST['birthday'];
			$value['marriage']=$_POST['marriage'];
			$value['height']=$_POST['height'];
			$value['nationality']=$_POST['nationality'];
			$value['weight']=$_POST['weight'];
			$value['idcard']=$_POST['idcard'];
			$value['exp']=$_POST['exp'];
			$value['email']=$_POST['email'];
			$value['telphone']=$_POST['moblie'];
			$value['telhome']=$_POST['telhome'];
			$value['edu']=$_POST['edu'];
			$value['address']=$_POST['address'];
			$value['homepage']=$_POST['homepage'];
			$value['qq']=$_POST['qq'];
			$value['wxewm']=$_POST['wxewm'];
			$value['description']=$_POST['description'];
			$UserinfoM->UpdateUserinfo(array('values'=>$value,'r_status'=>$_POST['status'],'usertype'=>1),array("`uid`='".$_POST['uid']."'"));
			$UserinfoM->UpdateMember(array("email"=>$_POST['email'],'status'=>$_POST['status'],"moblie"=>$_POST['moblie']),array("`uid`='".$_POST['uid']."'"));

			$ResumeM=$this->MODEL('resume');
			$ResumeM->UpdateResumeExpect(array("edu"=>$_POST['edu'],"exp"=>$_POST['exp'],"uname"=>$_POST['name'],"sex"=>$_POST['sex'],"birthday"=>$_POST['birthday']),array("uid"=>(int)$_POST['uid']));
			$statis = $UserinfoM->GetUserstatisAll(array('uid'=>$_POST['uid']),array('usertype'=>1));
			if(!is_array($statis)){
				$UserinfoM->AddMemberstatis(array('uid'=>$_POST['uid']));
			}
			delfiledir("../data/upload/tel/".$_POST['uid']);
			$this->ACT_layer_msg('个人会员(ID:'.$_POST['uid'].')修改成功',9,$lasturl,2,1);
		}				
		$this->siteadmin_tpl(array('admin_member_useredit'));
	}
	function add_action(){
		if($_POST['submit']){
			extract($_POST);
			$moblienum=$this->obj->DB_select_num("member","moblie='".$moblie."'");
			$emailnum=$this->obj->DB_select_num("member","email='".$email."'");
			if($username==''||mb_strlen($username)<2||mb_strlen($username)>16){
				$msg = '用户名不能为空！';$msg_type=8;
			}elseif($password==""||mb_strlen($password)<6||mb_strlen($password)>20){
				$msg = "密码不能为空！";$msg_type=8;
			}elseif($email==""){
				$msg = "邮箱不能为空！";$msg_type=8;
			}elseif(CheckRegEmail($email)==false){
				$msg="邮箱格式错误！";$msg_type=8;
			}elseif($emailnum){
				$msg="邮箱已存在！";$msg_type=8;
			}elseif($moblie==""){
				$msg = "手机不能为空！";$msg_type=8;
			}elseif(!CheckMoblie($moblie)){
				$msg="手机格式错误！";$msg_type=8;
			}elseif($moblienum){
				$msg="手机已存在！";$msg_type=8;
			}else{
				 $UserinfoM=$this->MODEL('userinfo');
				if($this->config['sy_uc_type']=='uc_center'){
					$this->uc_open();
					$user = uc_get_user($username);
				}else{
					$user = $UserinfoM->GetMemberOne(array("username"=>$username));
				}
				if(is_array($user)){
					$msg = "该会员已经存在！";$msg_type=8;
				}else{
					$time = time();

					$ip = fun_ip_get();
					if($this->config['sy_uc_type']=="uc_center"){
						$uid=uc_user_register($_POST['username'],$_POST['password'],$_POST['email']);
						if($uid<0){
							$msg="uc_center已存在该邮箱！";$msg_type=8;
						}else{
							list($uid,$username,$email,$password,$salt)=uc_get_user($username);
							$value = array('username'=>$username,'password'=>$password,'email'=>$email,'usertype'=>1,'salt'=>$salt,'moblie'=>$moblie,'reg_date'=>$time,'reg_ip'=>$ip);
						}
					}else{
						$salt = substr(uniqid(rand()), -6);
						$pass = md5(md5($password).$salt);
						$value = array('username'=>$username,'password'=>$pass,'email'=>$email,'usertype'=>1,'status'=>$satus,'salt'=>$salt,'moblie'=>$moblie,'reg_date'=>$time,'reg_ip'=>$ip);
					}
					$nid = $UserinfoM->AddMember($value);
					if($nid>0){
                        $UserinfoM->RegisterMember(array('email'=>$email,'telphone'=>$moblie,'username'=>$username),array('uid'=>$nid,'usertype'=>1));
						$msg='个人会员(ID:'.$nid.')添加成功';
						$msg_type=9;
						$this->ACT_layer_msg($msg,$msg_type,'index.php?m=user_member',2,1);
					}
				}
			}
			$this->ACT_layer_msg($msg,$msg_type);
			die;
		}
		$this->siteadmin_tpl(array('admin_member_useradd'));
	}
	function del_action(){
		$this->check_token();
		
	    if($_GET['del'] && !$_GET['send_email'] && !$_GET['send_msg']){
	    	$del=$_GET['del'];
	    	if($del){
				if(is_array($del)){
					$layer_type=1;
					$uids=pylode(',',$del);
				}else{
					$layer_type=0;
					$uids=intval($del);
				}
	    		$this->MODEL('userinfo')->DeleteMember($del);
		    	$this->layer_msg('个人会员(ID:'.$uids.')删除成功！',9,$layer_type,$_SERVER['HTTP_REFERER']);
	    	}else{
				$this->layer_msg('请选择您要删除的会员！',8,0,$_SERVER['HTTP_REFERER']);
	    	}
	    }
	}
	function reset_pw_action(){
		$this->check_token();
		$data['password']='123456';
		$data['uid']=(int)$_GET['uid'];
		$this->uc_edit_pw($data,1,'index.php?m=user_member');
		$this->MODEL('log')->admin_log('会员(ID:'.(int)$_GET['uid'].')重置密码成功');
		echo '1';
	}
	
	function Imitate_action(){
		extract($_GET);
		$user_info = $this->MODEL('userinfo')->GetMemberOne(array('uid'=>(int)$uid));
		$this->cookie->unset_cookie();
		$this->cookie->add_cookie($user_info['uid'],$user_info['username'],$user_info['salt'],$user_info['email'],$user_info['password'],$user_info['usertype'],1,$user_info['did']);
		header('Location: '.$this->config['sy_weburl'].'/member');
	}
}
?>