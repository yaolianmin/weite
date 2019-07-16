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
class com_controller extends common{
	function reg_action(){ 
		if(!$_POST['username'] || !$_POST['password']  ||!$_POST['moblie'] )
		{
			$data['error']=2;
			echo json_encode($data);die;
		}
		
		if(!CheckMoblie($_POST['moblie']))
		{
			$data['error']=3;
			echo json_encode($data);die;
		}
		$_POST['username']=$this->stringfilter($_POST['username']);
		$nid = $this->obj->DB_select_once("member","`username`='".$_POST['username']."' or `molie`='".$_POST['moblie']."'");
		if(is_array($nid))
		{
			$data['error']=4;
			echo json_encode($data);die;
		}
		if($this->config[sy_uc_type]=="uc_center")
		{
			$this->uc_open();
			$uid=uc_user_register($_POST['username'],$_POST['password'],$_POST['email']);
			if($uid<=0)
			{
				$data['error']=6;
				echo json_encode($data);die;
			}else{
				list($uid,$username,$password,$email,$salt)=uc_user_login($_POST['username'],$_POST['password']);
				$pass = md5(md5($_POST['password']).$salt);
				$ucsynlogin=uc_user_synlogin($uid);
			}
		}elseif($this->config[sy_pw_type]=="pw_center"){
			include(APP_PATH."/api/pw_api/pw_client_class_phpapp.php");
			$username=$username;
			$password=$_POST['password'];
			$email=$_POST['email'];
			$pw=new PwClientAPI($username,$password,$email);
			$pwuid=$pw->register();
			$salt = substr(uniqid(rand()), -6);
			$pass = md5(md5($password).$salt);
		}else{
			$salt = substr(uniqid(rand()), -6);
			$pass = md5(md5($_POST['password']).$salt);
		}
		$ip = fun_ip_get();
		$time = time();
		$arr=array();
		$arr['username']=$_POST['username'];
		$arr['password']=$pass;
		$arr['moblie']=$_POST['moblie'];
		$arr['email']=$_POST['email'];
		$arr['usertype']=2;
		$arr['status']=$this->config['com_status'];
		$arr['salt']=$salt;
		$arr['reg_date']=$time;
		$arr['reg_ip']=$ip;
		$arr['source']=3;
		
		$userid=$this->obj->insert_into("member",$arr);
		if($userid)
		{
			$IntegralM=$this->MODEL('integral');
			if($this->config['sy_pw_type']=="pw_center")
			{
				$this->obj->update_once("member",array("pwuid"=>$pwuid),array("uid"=>$userid));
			}
			$ratingdata = "`uid`='".$userid."',";
			$ratingM = $this->MODEL('rating');
					
			$ratingdata.=$ratingM->rating_info();

			$this->obj->DB_insert_once("company_statis",$ratingdata);
			$data2['uid']=$userid;
			$data2['linkmail']=$_POST['email'];
			$data2['name']=$this->stringfilter($_POST['unit_name']);
			$data2['linktel']=$_POST['moblie'];
			$data2['address']=$this->stringfilter($_POST['address']);
			$this->obj->insert_into("company",$data2);
			$sql=array();
			$sql['uid']=$userid;
			$sql['nickname']=$_POST['username'];
			$sql['usertype']=2;
			if($this->config['integral_reg']>0){
				
				$IntegralM->company_invtal($userid,$this->config['integral_reg'],true,"注册赠送",true,2,'integral',23); 
			}
			$IntegralM->get_integral_action($userid,"integral_login","会员登录");
			if($this->config['com_status']!="1"){
				$data['error']=5;
				echo json_encode($data);die;
			}else{
				$data['uid']=$userid;
				$data['username']=$_POST['username'];
				$data['usertype']=2;
				$data['error']=1;
				echo json_encode($data);die;
			}
		}else{
			$data['error']=7;
			echo json_encode($data);die;
		}
	}
	function send_action(){
		$username=trim($_POST['username']);
		$info = $this->obj->DB_select_once("member","`username`='".$username."' or `moblie`='".$username."'","`moblie`,`username`,`uid`,`usertype`,`did`"); 
        if(!$info['uid']){ 
			$sendcode = rand(100000,999999);
			if(!$this->config["sy_msguser"] || !$this->config["sy_msgpw"] || !$this->config["sy_msgkey"]||$this->config['sy_msg_isopen']!='1'){
				$data['error']=6;
				echo json_encode($data);die;  
            }else{
	            $moblieCode = $this->obj->DB_select_once('company_cert',"`check`='".$_POST['username']."'");
				if((time()-$moblieCode['ctime'])<90){
					$data['error']=5;
					echo json_encode($data);die;  
				}
	            $num=$this->obj->DB_select_num("moblie_msg","`moblie`='".$_POST['username']."' and `ctime`>'".strtotime(date("Y-m-d"))."'");
				if($num>=$this->config['moblie_msgnum']){
					$data['error']=4;
					echo json_encode($data);die;  
				}
				$ip=fun_ip_get();
				$ipnum=$this->obj->DB_select_num("moblie_msg","`ip`='".$ip."' and `ctime`>'".strtotime(date("Y-m-d"))."'");
				if($ipnum>=$this->config['ip_msgnum']){
					$data['error']=3;
					echo json_encode($data);die;  
				}

        $notice = $this->MODEL('notice');
				$result = $notice->sendSMSType(array("moblie"=>$_POST['username'],"code"=>$sendcode,"type"=>'regcode'));
	            
				if($result['status'] != -1){
					$datas['did']=$this->config['did'];
					$datas['uid']='0';
					$datas['type']='2';
					$datas['status']='0';
					$datas['step']='1';
					$datas['check']=$_POST['moblie'];
					$datas['check2']=$randstr;
					$datas['ctime']=time();
					$datas['statusbody']='手机注册验证码';
					if(is_array($moblieCode) && !empty($moblieCode)){
						$this->obj->update_once("company_cert",$datas,"`check`='".$_POST['moblie']."'");
					}
				}
      }  
			
			$data['sendcode']=$sendcode; 
			$data['error']=1; 
			
			echo json_encode($data);die;
        }else{
			$data['error']=2;
			echo json_encode($data);die;
        }
	}
	function login_action(){
		if(!$_POST['username'] || !$_POST['password']){
			$data['error']=2;
			echo json_encode($data);die;
		}
		$_POST['username']=$this->stringfilter($_POST['username']);
		$row = $this->obj->DB_select_once("member","`username`='".$_POST['username']."' and `usertype`='2'");
		if(!is_array($row)){
			$data['error']=3;
			echo json_encode($data);die;
		}elseif($row['status']==0){
			$data['error']=5;
			echo json_encode($data);die;
		}elseif($row['status']==2){
			$data['error']=6;
			echo json_encode($data);die;
		}
		$pass = md5(md5($_POST['password']).$row['salt']);
		if($pass!=$row['password']){
			$data['error']=4;
			echo json_encode($data);die;
		}else{
			$ip = fun_ip_get();
			$this->obj->DB_update_all("member","`login_ip`='".$ip."',`login_date`='".time()."',`login_hits`=`login_hits`+1","`uid`='".$row['uid']."'");
			$logtime=date("Ymd",$row['login_date']);
			$nowtime=date("Ymd",time());
			if($logtime!=$nowtime){
				$this->MODEL('integral')->get_integral_action($row['uid'],"integral_login","会员登录");
			} 
			$data['uid']=$row['uid'];
			$data['username']=$row['username'];
			$data['usertype']=2;
			$data['error']=1;
			echo json_encode($data);die;
		}
	}
	function getuser_action(){
		$uid=(int)$_POST['uid'];
		if($_POST['uids']){
			$uid_array=explode(",",$_POST['uids']);
			$uid=pylode(",",$uid_array);
		} 
		$row = $this->obj->DB_select_alls("member","company_statis","a.`uid`=b.`uid` and a.`uid` in (".$uid.")");
		
		if(!is_array($row) && $_POST['uid']){
			$data['error']=2;
			echo json_encode($data);die;
		}
		$row_status0 = $this->obj->DB_select_all("company_job","`uid` in (".$uid.") and `state`='0' group by `uid`","count(`id`) as status_num,`uid`");
		$row_status1 = $this->obj->DB_select_all("company_job","`uid` in (".$uid.") and `state`='1' group by `uid`","count(`id`) as status_num,`uid`");
		$row_status3 = $this->obj->DB_select_all("company_job","`uid` in (".$uid.") and `state`='3' group by `uid`","count(`id`) as status_num,`uid`");
		$row_resume_num=$this->obj->DB_select_all("down_resume","`comid` in (".$uid.") group by `comid`","count(`id`) as down_resume_num,`comid` as uid");
		$row_talent_num=$this->obj->DB_select_all("talent_pool","`cuid` in (".$uid.") group by `cuid`","count(`id`) as talent_pool_num,`cuid` as uid");
		$row_invite_num=$this->obj->DB_select_all("userid_msg","`fid` in (".$uid.") group by `fid`","count(`id`) as invite_num,`fid` as uid");
		$userid_job=$this->obj->DB_select_all("userid_job","`com_id` in (".$uid.") group by `com_id`","count(`id`) as num,`com_id` as uid");
		$rowInfo = $this->obj->DB_select_all("company","`uid` in (".$uid.")",'`logo`,`uid`');
		foreach($row as $k){
			foreach($rowInfo as $key=>$val){
				if($k['uid']==$val['uid']){
					$data[$k['uid']]['logo']			=$val['logo']?$val['logo']:'';
				}
			}
			$data[$k['uid']]['status0']			='0';
			$data[$k['uid']]['status1']			='0';
			$data[$k['uid']]['status3']			='0';
			$data[$k['uid']]['down_resume']			='0';
			$data[$k['uid']]['talent_num']			='0';
			$data[$k['uid']]['invite_num']			='0';
			foreach($row_status0 as $key=>$val){
				if($k['uid']==$val['uid']){
					$data[$k['uid']]['status0']			=$val['status_num']?$val['status_num']:'0';
					break;
				}
			}
			foreach($row_status1 as $key=>$val){
				if($k['uid']==$val['uid']){
					$data[$k['uid']]['status1']			=$val['status_num']?$val['status_num']:'0';
					break;
				}
			}
			foreach($row_resume_num as $key=>$val){
				if($k['uid']==$val['uid']){
					$data[$k['uid']]['down_resume']			=$val['down_resume_num']?$val['down_resume_num']:'0';
					break;
				}
			}
			foreach($userid_job as $key=>$val){
				if($k['uid']==$val['uid']){
					$data[$k['uid']]['userid_job']			=$val['num']?$val['num']:'0';
					break;
				}
			}
			foreach($row_talent_num as $key=>$val){
				if($k['uid']==$val['uid']){
					$data[$k['uid']]['talent_num']			=$val['talent_pool_num']?$val['talent_pool_num']:'0';
					break;
				}
			}
			foreach($row_invite_num as $key=>$val){
				if($k['uid']==$val['uid']){
					$data[$k['uid']]['invite_num']			=$val['invite_num']?$val['invite_num']:'0';
					break;
				}
			}
			$data[$k['uid']]['uid']			=$k['uid'];
			$data[$k['uid']]['username']		=$k['username'];
			$data[$k['uid']]['usertype']		=$k['usertype'];
			$data[$k['uid']]['email']			=$k['email'];
			$data[$k['uid']]['moblie']			=$k['moblie'];
			$data[$k['uid']]['reg_ip']			=$k['reg_ip'];
			$data[$k['uid']]['reg_date']		=$k['reg_date'];
			$data[$k['uid']]['login_ip']		=$k['login_ip'];
			$data[$k['uid']]['login_date']	=$k['login_date'];
			$data[$k['uid']]['login_hits']	=$k['login_hits'];
			$data[$k['uid']]['pay']			=$k['pay'];
			$data[$k['uid']]['integral']		=$k['integral'];
			$data[$k['uid']]['job']			=$k['job'];

			$data[$k['uid']]['rating']			=$k['rating'];
			$data[$k['uid']]['rating_name']	=$k['rating_name'];

			$data[$k['uid']]['invite_resume']=$k['invite_resume'];
			$data[$k['uid']]['job_num']		=$k['job_num'];
			$data[$k['uid']]['editjob_num']	=$k['editjob_num'];
			$data[$k['uid']]['breakjob_num'] =$k['breakjob_num'];
			$data[$k['uid']]['vip_etime']		=$k['vip_etime'];
			$data[$k['uid']]['msg_num']		=$k['msg_num'];
	
		}
		foreach($data as $k=>$v){
			if(is_array($v)){
				foreach($v as $key=>$val){
					$data[$k][$key]=isset($val)?$val:'';
				}
			}else{
				$data[$k]=isset($v)?$v:'';
			}
		}
		$data['error']=1;
		echo json_encode($data);die;
	}
	function getinfo_action(){
		$uid=(int)$_POST['uid'];
		if($_POST['uids']){
			$uid_array=explode(",",$_POST['uids']);
			$uid=pylode(",",$uid_array);
		}
		$row = $this->obj->DB_select_all("company","`uid` in (".$uid.")");
		if((!is_array($row) || empty($row)) && $_POST['uid']){
			$data['error']=2;
			echo json_encode($data);die;
		}
		$com = $this->obj->DB_select_all("member","`uid` in (".$uid.")");
		if((!is_array($com) || empty($com)) && $_POST['uid']){
			$data['error']=2;
			echo json_encode($data);die;
		}
		$userid_job=$this->obj->DB_select_all("userid_job","`com_id` in (".$uid.") group by `com_id`","count(`id`) as num,`com_id` as uid");
		$job_num = $this->obj->DB_select_all("company_job","`uid` in (".$uid.") and `state`='1' and `edate`>'".time()."' group by `uid`","count(`id`) as num,`uid`");
		$row_talent_num=$this->obj->DB_select_all("talent_pool","`cuid` in (".$uid.") group by `cuid`","count(`id`) as num,`cuid` as uid");
		$data=array();
		foreach($row as $k){
			foreach($userid_job as $v){
				if($k['uid']==$v['uid']){
					$data[$k['uid']]['userid_job']	=$v['num'];
				}
			}
			foreach($job_num as $v){
				if($k['uid']==$v['uid']){
					$data[$k['uid']]['job_num']	=$v['num'];
				}
			}
			foreach($row_talent_num as $v){
				if($k['uid']==$v['uid']){
					$data[$k['uid']]['talent_num']	=$v['num'];
				}
			}
			foreach($com as $v){
				if($k['uid']==$v['uid']){
					$data[$k['uid']]['username']	=$v['username'];
				}
			}
			$data[$k['uid']]['uid']			=$k['uid'];
			$data[$k['uid']]['name']		=$k['name'];
			$data[$k['uid']]['hy']			=$k['hy'];
			$data[$k['uid']]['pr']			=$k['pr'];
			$data[$k['uid']]['provinceid']	=$k['provinceid'];
			$data[$k['uid']]['cityid']		=$k['cityid'];
			$data[$k['uid']]['mun']			=$k['mun'];
			$data[$k['uid']]['sdate']		=$k['sdate'];
			$data[$k['uid']]['money']		=$k['money'];
			$data[$k['uid']]['content']		=strip_tags($k['content']);
			$data[$k['uid']]['zip']			=$k['zip'];
			
			$data[$k['uid']]['com_login_link']		=$this->config['com_login_link'];
			$data[$k['uid']]['address']		=$k['address'];
			$data[$k['uid']]['linkman']		=$k['linkman'];
			$data[$k['uid']]['linkphone']	=$k['linkphone'];
			$data[$k['uid']]['linktel']		=$k['linktel'];
			$data[$k['uid']]['linkjob']		=$k['linkjob'];
			$data[$k['uid']]['linkqq']		=$k['linkqq'];			
			$data[$k['uid']]['linkmail']	=$k['linkmail'];
			$data[$k['uid']]['x']			=$k['x'];
			$data[$k['uid']]['y']			=$k['y'];
			$data[$k['uid']]['logo']		=$k['logo'];
			$data[$k['uid']]['payd']		=$k['payd'];
			$data[$k['uid']]['lastupdate']	=$k['lastupdate'];
			$data[$k['uid']]['jobtime']		=$k['jobtime'];
			$data[$k['uid']]['r_status']	=$k['r_status'];
			$data[$k['uid']]['firmpic']		=$k['firmpic'];
			$data[$k['uid']]['rec']			=$k['rec'];
			$data[$k['uid']]['hits']		=$k['hits'];
			$data[$k['uid']]['ant_num']		=$k['ant_num'];
			$data[$k['uid']]['pl_time']		=$k['pl_time'];
			$data[$k['uid']]['pl_status']	=$k['pl_status'];
			$data[$k['uid']]['infostatus']	=$k['infostatus'];
			$data[$k['uid']]['order']		=$k['order'];
			$data[$k['uid']]['admin_remark']=$k['admin_remark'];
			$data[$k['uid']]['email_dy']	=$k['email_dy'];
			$data[$k['uid']]['msg_dy']		=$k['msg_dy'];
			$data[$k['uid']]['website']		=$k['website'];
			$data[$k['uid']]['busstops']	=$k['busstops'];
		}
		foreach($data as $k=>$v){
			if(is_array($v)){
				foreach($v as $key=>$val){
					$data[$k][$key]=isset($val)?$val:'';
				}
			}else{
				$data[$k]=isset($v)?$v:'';
			}
		}
		$data['error']=1;
		echo json_encode($data);die;
	}
	function jobadd_action(){
		if(!$_POST['name']||!$_POST['job1']||!$_POST['job1_son']||!$_POST['job_post']||!$_POST['provinceid']||!$_POST['cityid']||!$_POST['description']||!$_POST['edate']||!$_POST['minsalary']||!$_POST['uid']){
			$data['error']=3;
			echo json_encode($data);die;
		}
		if($_POST['maxsalary'] && (int)$_POST['minsalary']>(int)$_POST['maxsalary']){
			$data['error']=7;
			echo json_encode($data);die;
		}
		$_POST['edate']=strtotime($_POST['edate']);
		$id= intval($_POST['id']);
		$uid=intval($_POST['uid']);
		unset($_POST['id']);
		$_POST['name']=$this->stringfilter($_POST['name']);
		$_POST['description']=$this->stringfilter($_POST['description']);
		$_POST['uid']=$_POST['uid'];
		$_POST['lastupdate']=mktime();
		$_POST['state']=$this->config['com_job_status'];
		$_POST['description'] = str_replace(array("&amp;","background-color:#ffffff","background-color:#fff"),array("&",'',''),html_entity_decode($_POST['description'],ENT_QUOTES));

		if($id){
			if($_POST['state']=="1" || $_POST['state']=="2"){
				$this->get_com(2,$uid);
			}
			$where['id']=$id;
			$where['uid']=$uid;
			unset($_POST['uid']);
			$rows=$this->obj->DB_select_once("company_job","`id`='".$id."'");
			$nid=$this->obj->update_once("company_job",$_POST,$where);
			if($nid){
				$this->obj->update_once("company",array("jobtime"=>(int)$_POST['lastupdate']),array("uid"=>$uid));
				if($this->config['com_job_status']=="0"){
					$this->obj->member_log("更新职位 ".$_POST['name']);
					$data['error']=4;
				}else{
					$data['error']=5;
				}
				echo json_encode($data);die;
			}else{
				$data['error']=6;
				echo json_encode($data);die;
			}
		}else{
			$sta=$this->obj->DB_select_once("company_statis","`uid`='".$uid."'");
			if(!empty($sta)&&$sta){
				$job_num = $sta['job_num'];
				if($job_num==0){
					$data['error']=1;
					echo json_encode($data);die;
				}
			}
			$statis=$this->obj->DB_select_alls("company","company_statis","a.`uid`='".$uid."' and a.`uid`=b.`uid`");
			$_POST['com_name']=$statis[0]['name'];
			$_POST['com_logo']=$statis[0]['logo'];
			$_POST['com_provinceid']=$statis[0]['provinceid'];
			$_POST['pr']=$statis[0]['pr'];
			$_POST['mun']=$statis[0]['mun'];
			$_POST['rating']=$statis[0]['rating'];
			$_POST['sdate']=time();
			$_POST['source']=3;
			$this->get_com(1,$uid);
			$nid=$this->obj->insert_into("company_job",$_POST);
			$name="添加";
			if($nid){
				if($this->config['com_job_status']=="1"){
					$this->send_dingyue($nid,2);
				}
				$this->obj->update_once("company",array("jobtime"=>$_POST['lastupdate']),array("uid"=>$uid));
				$state_content = "发布了新职位 <a href=\"".$this->config['sy_weburl']."/index.php?m=com&c=comapply&id=$nid\" target=\"_blank\">".$_POST['name']."</a>。";
				$this->addstate($state_content);
				if($this->config['com_job_status']=="0"){
					$this->obj->member_log("发布了新职位 ".$_POST['name']);
					$data['error']=4;
				}else{
					$data['error']=5;
				}
				echo json_encode($data);die;
			}else{
				$data['error']=6;
				echo json_encode($data);die;
			}
		}
	}
	function saveinfo_action(){
		if(!$_POST['name']||!$_POST['hy']||!$_POST['pr']||!$_POST['provinceid']||!$_POST['cityid']||!$_POST['mun']||!$_POST['address']||!$_POST['linkphone']||!$_POST['content']||!$_POST['uid']){
			$data['error']=3;
			echo json_encode($data);die;
		}else{

			$cert_email = $this->obj->DB_select_once("company_cert","`uid`='".$_POST['uid']."' and `type`='1'");
			if(is_array($cert_email)){
				if($cert_email['check'] != $_POST['linkmail']){
					$_POST['email_status'] = "0";
					$this->obj->DB_delete_all("company_cert","`id`='".$cert_email['id']."'");
				}
			}

			$cert_tel = $this->obj->DB_select_once("company_cert","`uid`='".$_POST['uid']."' and `type`='2'");
			if(is_array($cert_tel)){
				if($cert_tel['check'] != $_POST['linktel']){
					$_POST['moblie_status'] = "0";
					$this->obj->DB_delete_all("company_cert","`id`='".$cert_tel['id']."'");
				}
			}
			$_POST['content']=$this->stringfilter($_POST['content']);
			$_POST['name']=$this->stringfilter($_POST['name']);
			$_POST['address']=$this->stringfilter($_POST['address']);
			$_POST['linkman']=$this->stringfilter($_POST['linkman']);
			$_POST['linkjob']=$this->stringfilter($_POST['linkjob']);
			$where['uid']=(int)$_POST['uid'];
			$_POST['content'] = str_replace(array("&amp;","background-color:#ffffff","background-color:#fff"),array("&",'',''),html_entity_decode($_POST['content'],ENT_QUOTES));
			$_POST['lastupdate']=mktime();
			$nid=$this->obj->update_once("company",$_POST,$where);
			if($nid){
				$sql2['com_name']=$_POST['name'];
				$sql2['pr']=$_POST['pr'];
				$sql2['mun']=$_POST['mun'];
				$sql2['com_provinceid']=$_POST['provinceid'];
				$this->obj->update_once("company_job",$sql2,$where);
				$sql['com_name']=$_POST['name'];
				$this->obj->update_once("fav_job",$sql,array("com_id"=>(int)$_POST['uid']));
				$this->obj->update_once("userid_job",$sql,array("com_id"=>(int)$_POST['uid']));
				$this->obj->update_once("report",array("r_name"=>$_POST['name']),array("c_uid"=>(int)$_POST['uid']));
				$this->obj->update_once("blacklist",$sql,array("c_uid"=>(int)$_POST['uid']));
				$this->obj->update_once("msg",$sql,array("job_uid"=>(int)$_POST['uid']));
				$this->obj->member_log("保存企业基本信息");
				$data['error']=1;
				echo json_encode($data);die;
			}else{
				$data['error']=2;
				echo json_encode($data);die;
			}
		}
	}
	function savepw_action(){
		if(!$_POST['oldpassword']||!$_POST['password']||!$_POST['uid']){
			$data['error']=3;
			echo json_encode($data);die;
		}else{
			$info = $this->obj->DB_select_once("member","`uid`='".(int)$_POST['uid']."'");
			if(is_array($info))
			{
				$oldpass = md5(md5($_POST['oldpassword']).$info['salt']);
				if($info['password']!=$oldpass)
				{
					$data['error']=2;
					echo json_encode($data);die;
				}
				if($this->config['sy_uc_type']=="uc_center" && $info['name_repeat']!="1")
				{
					$this->uc_open();
					$ucresult= uc_user_edit($info['username'], $_POST['oldpassword'], $_POST['password'], "","1");
					if($ucresult == -1) {
						$data['error']=2;
						echo json_encode($data);die;
					}
				}else{
					$salt = substr(uniqid(rand()), -6);
					$pass2 = md5(md5($_POST['password']).$salt);
					$sql['password']=$pass2;
					$sql['salt']=$salt;
					$where['uid']=(int)$_POST['uid'];
					$this->obj->update_once("member",$sql,$where);
				}
				$this->obj->member_log("修改密码");
				$data['error']=1;
				echo json_encode($data);die;
			}
		}
	}
	function savemap_action(){
		if(!$_POST['xvalue']||!$_POST['yvalue']||!$_POST['uid']){
			$data['error']=3;
		}else{
			$sql['x']=(float)$_POST['xvalue'];
			$sql['y']=(float)$_POST['yvalue'];
			$where['uid']=(int)$_POST['uid'];
			$oid=$this->obj->update_once("company",$sql,$where);
			if($oid){
				$this->obj->member_log("设置地图");
				$data['error']=1;
			}else{
				$data['error']=2;
			}
		}
		echo json_encode($data);die;
	}
	function paylist_action(){
		$where=1;
		$sdate=$_POST['sdate'];
		$edate=$_POST['edate'];
		$page=$_POST['page'];
		$limit=$_POST['limit'];
		$order=$_POST['order'];
		$limit=!$limit?10:$limit;
		if($_POST['uid']){
			$where.=" and `com_id`='".(int)$_POST['uid']."'";
		}
		if($sdate){
			$where.=" and `pay_time`>'".strtotime($sdate)."'";
		}
		if($edate){
			$where.=" and `pay_time`<'".strtotime($edate)."'";
		}
		if($order){
			$where.=" order by ".$order;
		}else{
			$where.=" order by id desc";
		}
		if($page){
			$pagenav=($page-1)*$limit;
			$where.=" limit $pagenav,$limit";
		}else{
			$where.=" limit $limit";
		}
		$rows=$this->obj->DB_select_all("company_pay",$where);
		if(is_array($rows) && !empty($rows)){
			foreach($rows as $key=>$va){
				$list[$key]['id']			=$va['id'];
				$list[$key]['order_id']	=$va['order_id'];
				$list[$key]['order_price']	=$va['order_price'];
				$list[$key]['pay_time']	=$va['pay_time'];
				$list[$key]['pay_state']	=$va['pay_state'];
				$list[$key]['com_id']		= $va['com_id'];
				$list[$key]['pay_remark']	= $va['pay_remark'];
				$list[$key]['type']			= $va['type'] ;
			}
			foreach($list as $k=>$v){
				if(is_array($v)){
					foreach($v as $key=>$val){
						$list[$k][$key]=isset($val)?$val:'';
					}
				}else{
					$list[$k]=isset($v)?$v:'';
				}
			}
			$data['list']=count($list)?$list:array();
			$data['error']=1;
		}else{
			$data['error']=2;
		}
		echo json_encode($data);die;
	}
	function hrlist_action(){
		$where="`com_id`='".(int)$_POST['uid']."'";
		$page=$_POST['page'];
		$limit=$_POST['limit'];
		$order=$_POST['order'];
		$keyword=$this->stringfilter($_POST['keyword']);
		$limit=!$limit?10:$limit; 
		if($keyword){
			$rows=$this->obj->DB_select_all("resume","`name` like '%".$keyword."%'","uid,`sex`");
			if(is_array($rows) && !empty($rows)){
				foreach($rows as $v){
					$uidarr[]=$v['uid'];
				}
			}
			$where.=" and uid in (".pylode(',',$uidarr).")";
		}
		$total=$this->obj->DB_select_num("userid_job",$where);
		if($order){
			$where.=" order by ".$order;
		}else{
			$where.=" order by id desc";
		}
		if($page){
			$pagenav=($page-1)*$limit;
			$where.=" limit $pagenav,$limit";
		}else{
			$where.=" limit $limit";
		}
		$rows=$this->obj->DB_select_all("userid_job",$where);
		if(is_array($rows) && !empty($rows)){
			$eid=array();
			foreach($rows as $v){
				$uid[]=$v['uid'];
				if(in_array($v['eid'],$eid)==false){
					$eid[]=$v['eid'];
				}
			}
			$userrows=$this->obj->DB_select_all("resume","`uid` in (".pylode(",",$uid).")","uid,name,sex,exp,edu");
			$resume=$this->obj->DB_select_all("resume_expect","`id` in (".pylode(",",$eid).")","id,job_classid");
			include(PLUS_PATH."user.cache.php");
			include(PLUS_PATH."job.cache.php");
			foreach($rows as $key=>$va){
				foreach($userrows as $val){
					if($va[uid]==$val[uid]){
						$list[$key]['name']= $val['name'];
						$list[$key]['sex']=$val['sex'];
						$list[$key]['exp']= $userclass_name[$val['exp']];
						$list[$key]['edu']= $userclass_name[$val['edu']];
					}
				}
				foreach($resume as $v){
					$jobname=array();
					if($v['id']==$va['eid']){
						$jobclassid=@explode(',',$v['job_classid']);
						foreach($jobclassid  as $jval){
							$jobname[]=$job_name[$jval];
						} 
						$list[$key]['jobname']= @implode(',',$jobname);
					}
				}
				$list[$key]['id']			=$va['id'];
				$list[$key]['uid']			= $va['uid'];
				$list[$key]['job_id']		= $va['job_id'];
				$list[$key]['job_name']	= $va['job_name'];
				$list[$key]['com_name']	= $va['com_name'];
				$list[$key]['com_id']		= $va['com_id'];
				$list[$key]['eid']			= $va['eid'];
				$list[$key]['type']			= $va['type'];
				$list[$key]['datetime']	= $va['datetime'];
				$list[$key]['is_browse']	= $va['is_browse'];
			}
			foreach($list as $k=>$v){
				if(is_array($v)){
					foreach($v as $key=>$val){
						$list[$k][$key]=isset($val)?$val:'';
					}
				}else{
					$list[$k]=isset($v)?$v:'';
				}
			}
			$data['list']=count($list)?$list:array();
			$data['total']=$total;
			$data['error']=1;
		}else{
			$data['error']=2;
		}
		echo json_encode($data);die;
	}
	function downlist_action(){
		$where=1;
		$page=$_POST['page'];
		$limit=$_POST['limit'];
		$order=$_POST['order'];
		$keyword=$this->stringfilter($_POST['keyword']);
		$limit=!$limit?10:$limit;
		if($_POST['uid']){
			$where.=" and `comid`='".(int)$_POST['uid']."'";
		}
		if($keyword){
			$rows=$this->obj->DB_select_all("resume","`name` like '%".$keyword."%'","uid");
			if(is_array($rows) && !empty($rows)){
				foreach($rows as $v){
					$uidarr[]=$v['uid'];
				}
			}
			$where.=" and uid in (".pylode(',',$uidarr).")";
		}
		$total=$this->obj->DB_select_num("down_resume",$where);
		if($order){
			$where.=" order by ".$order;
		}else{
			$where.=" order by id desc";
		}
		if($page){
			$pagenav=($page-1)*$limit;
			$where.=" limit $pagenav,$limit";
		}else{
			$where.=" limit $limit";
		}
		$rows=$this->obj->DB_select_all("down_resume",$where);
		if(is_array($rows) && !empty($rows)){
			foreach($rows as $v){
				$uid[]=$v['uid'];
			}
			$userrows=$this->obj->DB_select_all("resume","`uid` in (".pylode(",",$uid).")","`uid`,`name`,`sex`,`edu`");
			foreach($rows as $key=>$va){
				foreach($userrows as $val){
					if($va[uid]==$val[uid]){
						$list[$key]['name']	= $val['name'];
						$list[$key]['sex']	=$val['sex'];
						$list[$key]['edu']	= $val['edu'];
					}
				}
				$list[$key]['id']			=$va['id'];
				$list[$key]['uid']			=$va['uid'];
				$list[$key]['eid']			= $va['eid'];
				$list[$key]['comid']		= $va['comid'];
				$list[$key]['downtime']	= $va['downtime'];
			}
			foreach($list as $k=>$v){
				if(is_array($v)){
					foreach($v as $key=>$val){
						$list[$k][$key]=isset($val)?$val:'';
					}
				}else{
					$list[$k]=isset($v)?$v:'';
				}
			}
			$data['list']=count($list)?$list:array();
			$data['total']=$total;
			$data['error']=1;
		}else{
			$data['error']=2;
		}
		echo json_encode($data);die;
	}
	function savetalentpool_action(){
		$this->CheckAppUser();
		if(intval($_POST['usertype'])!="2"){
			$data['error']=3;
		}else{
			$row=$this->obj->DB_select_once("talent_pool","`eid`='".(int)$_POST['eid']."' and `cuid`='".intval($_POST['uid'])."'");
			if(empty($row)){				
				$value="`eid`='".(int)$_POST['eid']."',";
				$value.="`uid`='".(int)$_POST['userid']."',";
				$value.="`remark`='". $_POST['remark']."',";
				$value.="`cuid`='".intval($_POST['uid'])."',";
				$value.="`ctime`='".time()."'";
				$this->obj->DB_insert_once("talent_pool",$value); 
				$data['error']=1;
			}else{
				$data['error']=2;
			}
		}
		echo json_encode($data);die;
	}
	function talentpoollist_action(){
		$where="`cuid`='".(int)$_POST['uid']."'";
		$page=$_POST['page'];
		$limit=$_POST['limit'];
		$order=$_POST['order'];
		$keyword=$this->stringfilter($_POST['keyword']);
		$limit=!$limit?10:$limit; 
		if($keyword){
			$rows=$this->obj->DB_select_all("resume","`name` like '%".$keyword."%'","`uid`");
			if(is_array($rows) && !empty($rows)){
				foreach($rows as $v){
					$uidarr[]=$v['uid'];
				}
			}
			$where.=" and `uid` in (".@implode(',',$uidarr).")";
		}
		$total=$this->obj->DB_select_num("talent_pool",$where);
		if($order){
			$where.=" order by ".$order;
		}else{
			$where.=" order by id desc";
		}
		if($page){
			$pagenav=($page-1)*$limit;
			$where.=" limit $pagenav,$limit";
		}else{
			$where.=" limit $limit";
		}
		$rows=$this->obj->DB_select_all("talent_pool",$where);
		if(is_array($rows) && !empty($rows)){
			foreach($rows as $v){
				$uid[]=$v['uid'];
			}
			$userrows=$this->obj->DB_select_all("resume","`uid` in (".pylode(",",$uid).")","`uid`,`name`,`sex`,`edu`,`exp`,`def_job`");
			$eids=array();
			foreach($userrows as $v){
				if(in_array($v['def_job'],$eids)==false){
					$eids[]=$v['def_job'];
				}
			}
			$expect=$this->obj->DB_select_all("resume_expect","`id` in (".pylode(",",$eids).")","`id`,`uid`,`job_classid`");
			include(PLUS_PATH."job.cache.php");
			if($expect&&is_array($expect)){
				foreach($expect as $k=>$v){
					$job_classid=@explode(',',$v['job_classid']);
					$jobname=array();
					foreach($job_classid as $v){
						$jobname[]=$job_name[$v];
					}
					$expect[$k]['jobname']=implode(',',$jobname);
				}
			}
			$useridmsg=$this->obj->DB_select_all("userid_msg","`fid`='".(int)$_POST['uid']."' and `uid` in (".pylode(",",$uid).")","`uid`");
			$userid=array();
			foreach($useridmsg as $v){
				$userid[]=$v['uid'];
			}
			include(PLUS_PATH."user.cache.php");
			foreach($rows as $key=>$va){
				foreach($userrows as $val){
					if($va[uid]==$val[uid]){
						$list[$key]['name']	= $val['name'];
						$list[$key]['sex']	=$val['sex'];
						$list[$key]['exp']	= $userclass_name[$val['exp']];
						$list[$key]['edu']	= $userclass_name[$val['edu']];
					}
				}
				foreach($expect as $v){
					if($v['uid']==$va['uid']){
						$list[$key]['jobname']= $v['jobname'];
					}
				}
				if(in_array($va['uid'],$userid)){
					$list[$key]['useridmsg']=1;
				}else{
					$list[$key]['useridmsg']=0;
				}
				$list[$key]['id']			=$va['id'];
				$list[$key]['uid']			=$va['uid'];
				$list[$key]['eid']			= $va['eid'];
				$list[$key]['comid']		= $va['cuid'];
				$list[$key]['ctime']	= $va['ctime'];
			}
			foreach($list as $k=>$v){
				if(is_array($v)){
					foreach($v as $key=>$val){
						$list[$k][$key]=isset($val)?$val:'';
					}
				}else{
					$list[$k]=isset($v)?$v:'';
				}
			}
			$data['list']=count($list)?$list:array();
			$data['total']=$total;
			$data['error']=1;
		}else{
			$data['error']=2;
		}
		echo json_encode($data);die;
	}
	function talentpooldel_action(){
		$_POST = $this->CheckAppUser();
		if(!$_POST['uid'] || !$_POST['id']){
			$data['error']=3;
			echo json_encode($data);die;
		}
		$id=$this->obj->DB_delete_all("talent_pool","`cuid`='".(int)$_POST['uid']."' and `id`='".intval($_POST['id'])."'");
		if($id){
			$data['error']=1;
		}else{
			$data['error']=2;
		}
		echo json_encode($data);die;
	}
	function invitelist_action(){
		$where="`fid`='".(int)$_POST['uid']."'";
		$page=$_POST['page'];
		$limit=$_POST['limit'];
		$order=$_POST['order'];
		$keyword=$this->stringfilter($_POST['keyword']);
		$limit=!$limit?10:$limit; 
		if($keyword){
			$rows=$this->obj->DB_select_all("resume","`name` like '%".$keyword."%'","uid");
			if(is_array($rows) && !empty($rows)){
				foreach($rows as $v){
					$uidarr[]=$v['uid'];
				}
				$uidarr=pylode(',',$uidarr);
			}
			$where.=" and uid in (".$uidarr.")";
		}
		$total=$this->obj->DB_select_num("userid_msg",$where);
		if($order){
			$where.=" order by ".$order;
		}else{
			$where.=" order by id desc";
		}
		if($page){
			$pagenav=($page-1)*$limit;
			$where.=" limit $pagenav,$limit";
		}else{
			$where.=" limit $limit";
		}
		$rows=$this->obj->DB_select_all("userid_msg",$where);
		if(is_array($rows) && !empty($rows)){
			foreach($rows as $v){
				$uid[]=$v['uid'];
			}
			$userrows=$this->obj->DB_select_all("resume","`uid` in (".pylode(",",$uid).")","`name`,`sex`,`edu`,`uid`,def_job,`exp`");
			if($userrows&&is_array($userrows)){
				$eid=array();
				foreach($userrows as $v){ 
					$eid[]=$v['def_job'];
				}
				$expect=$this->obj->DB_select_all("resume_expect","`id` in (".pylode(",",$eid).")","`id`,`uid`,`job_classid`");
				include(PLUS_PATH."job.cache.php");
				if($expect&&is_array($expect)){
					foreach($expect as $k=>$v){
						$job_classid=@explode(',',$v['job_classid']);
						$jobname=array();
						foreach($job_classid as $v){
							$jobname[]=$job_name[$v];
						}
						$expect[$k]['jobname']=implode(',',$jobname);
					}
				}
			}
			
			foreach($rows as $key=>$va){
				foreach($userrows as $val){
					if($va[uid]==$val[uid]){
						$list[$key]['name']	= $val['name'];
						$list[$key]['sex']	= $val['sex'];
						$list[$key]['eid']	= $val['def_job'];
						$list[$key]['edu']	= $val['edu'];
						$list[$key]['exp']	= $val['exp'];
					}
				}
				foreach($expect as $v){
					if($v['uid']==$va['uid']){
						$list[$key]['job_classid']= $v['jobname'];
					}
				}
				$list[$key]['id']			=$va['id'];
				$list[$key]['uid']			=$va['uid'];
				$list[$key]['title']		= $va['title'];
				$list[$key]['fid']			= $va['fid'];
				$list[$key]['fname']		= $va['fname'];
				$list[$key]['datetime'] 	= $va['datetime'];
				$list[$key]['is_browse']	= $va['is_browse'];
				$list[$key]['intertime']	= $va['intertime'];
				$list[$key]['content']		= $va['content'];
				$list[$key]['jobname']		= $va['jobname'];

			}
			foreach($list as $k=>$v){
				if(is_array($v)){
					foreach($v as $key=>$val){
						$list[$k][$key]=isset($val)?$val:'';
					}
				}else{
					$list[$k]=isset($v)?$v:'';
				}
			}
			$data['list']=count($list)?$list:array();
			$data['total']=$total;
			$data['error']=1;

		}else{
			$data['error']=2;
		}
		echo json_encode($data);die;
	}
	function messagelist_action(){
		$where=1;
		$page=$_POST['page'];
		$limit=$_POST['limit'];
		$order=$_POST['order'];
		$limit=!$limit?10:$limit;
		if($_POST['uid']){
			$where.=" and (`fa_uid`='".(int)$_POST['uid']."' or `uid`='".(int)$_POST['uid']."') and `keyid`='0' ";
		}

		if($order){
			$where.=" order by ".$order;
		}else{
			$where.=" order by id desc";
		}
		if($page){
			$pagenav=($page-1)*$limit;
			$where.=" limit $pagenav,$limit";
		}else{
			$where.=" limit $limit";
		}
		$rows=$this->obj->DB_select_all("message",$where);
		if(is_array($rows) &&!empty($rows)){
			foreach($rows as $key=>$va){
				$list[$key]['id']			=$va['id'];
				$list[$key]['content']		= $va['content'];
				$list[$key]['username']	= $va['username'];
				$list[$key]['status']		= $va['status'];
				$list[$key]['ctime'] 		= $va['ctime'];
			}
			foreach($list as $k=>$v){
				if(is_array($v)){
					foreach($v as $key=>$val){
						$list[$k][$key]=isset($val)?$val:'';
					}
				}else{
					$list[$k]=isset($v)?$v:'';
				}
			}
			$data['list']=count($list)?$list:array();
			$data['error']=1;
		}else{
			$data['error']=2;
		}
		echo json_encode($data);die;
	}
	function messageadd_action(){
		if(!$_POST['content']){
			$data['error']=3;
		}else{
			$sql['content']=$this->stringfilter($_POST['content']);
			$sql['fa_uid']=(int)$_POST['uid'];
			$sql['username']=$this->stringfilter($_POST['username']);
			$sql['ctime']=time();
			$id=$this->obj->insert_into("message",$sql);
			if($id){
				$data['error']=1;
			}else{
				$data['error']=2;
			}
		}
		echo json_encode($data);die;
	}
	function msglist_action(){
		$where=1;
		$page=$_POST['page'];
		$limit=$_POST['limit'];
		$order=$_POST['order'];
		$limit=!$limit?10:$limit;
		if($_POST['uid']){
			$where.=" and `job_uid`='".(int)$_POST['uid']."' and `del_status`<>'1'";
		}
		if($order){
			$where.=" order by ".$order;
		}else{
			$where.=" order by id desc";
		}
		if($page){
			$pagenav=($page-1)*$limit;
			$where.=" limit $pagenav,$limit";
		}else{
			$where.=" limit $limit";
		}
		$rows=$this->obj->DB_select_all("msg",$where);
		if(is_array($rows) &&!empty($rows)){
			foreach($rows as $key=>$va){
				$list[$key]['id']			=$va['id'];
				$list[$key]['uid']			=$va['uid'];
				$list[$key]['content']		= $va['content'];
				$list[$key]['username']	= $va['username'];
				$list[$key]['jobid']		= $va['jobid'];
				$list[$key]['job_name'] 	= $va['job_name'];
				$list[$key]['datetime'] 	= $va['datetime'];
				$list[$key]['reply'] 		= $va['reply'];
			}
			foreach($list as $k=>$v){
				if(is_array($v)){
					foreach($v as $key=>$val){
						$list[$k][$key]=isset($val)?$val:'';
					}
				}else{
					$list[$k]=isset($v)?$v:'';
				}
			}
			$data['list']=count($list)?$list:array();
			$data['error']=1;
		}else{
			$data['error']=2;
		}
		echo json_encode($data);die;
	}


	function get_com($type,$uid){
		$statis=$this->obj->DB_select_once("company_statis","`uid`='".$uid."'");
		if($statis['rating']){
			if($type==1){
				if(($statis['vip_etime']>time() || $statis['vip_etime']==0) && $statis['job_num']>0)
				{
					$value="`job_num`=`job_num`-1";
				}else{
					if($this->config['com_integral_online']=="1"){
						$this->intergal($type,$statis);
					}else{
						$data['error']=1;
						echo json_encode($data);die;
					}
				}
			}elseif($type==2){
				if(($statis['vip_etime']>time() || $statis['vip_etime']==0) && $statis['editjob_num']>0)
				{
					$value="`editjob_num`=`editjob_num`-1";
				}else{
					if($this->config['com_integral_online']=="1"){
						$this->intergal($type,$statis);
					}else{
						$data['error']=1;
						echo json_encode($data);die;
					}
				}
			}elseif($type==3){
				if(($statis['vip_etime']>time() || $statis['vip_etime']==0) && $statis['breakjob_num']>0)
				{
					$value="`breakjob_num`=`breakjob_num`-1";
				}else{
					if($this->config['com_integral_online']=="1"){
						$this->intergal($type,$statis);
					}else{
						$data['error']=1;
						echo json_encode($data);die;
					}
				}
			}
			if($value){
				$this->obj->DB_update_all("company_statis",$value,"uid='".$uid."'");
			}
		}else{
			$this->intergal($type,$statis,$uid);
		}
	}

	function intergal($type,$statis,$uid){
		if($type==1 && $this->config['integral_job']){
			if($this->config['integral_job_type']=="1")
			{
				$auto=true;
			}else{
				if($statis['integral']<$this->config['integral_job']){
					$data['error']=2;
					echo json_encode($data);die;
				}
				$auto=false;
			}
			$nid=$this->MODEL('integral')->company_invtal($uid,$this->config['integral_job'],$auto,"发布职位");
		}elseif($type==2 && $this->config['integral_jobedit']){
			if($this->config['integral_job_type']=="1")
			{
				$auto=true;
			}else{
				if($statis['integral']<$this->config['integral_jobedit']){
					$data['error']=2;
					echo json_encode($data);die;
				}
				$auto=false;
			}
			$nid=$this->MODEL('integral')->company_invtal($uid,$this->config['integral_jobedit'],$auto,"修改职位");
		}elseif($type==3 && $this->config['integral_jobefresh']){
			if($this->config['integral_job_type']=="1")
			{
				$auto=true;
			}else{
				if($statis['integral']<$this->config['integral_jobefresh']){
					$data['error']=2;
					echo json_encode($data);die;
				}
				$auto=false;
			}
			$nid=$this->MODEL('integral')->company_invtal($uid,$this->config['integral_jobefresh'],$auto,"刷新职位");
		}
	}
	function deljob_action()
	{
		if(!$_POST['ids'] || !$_POST['uid'])
		{
			$data['error']=3;
			echo json_encode($data);die;
		}
		$ids=@explode(",",$_POST['ids']);
		$delid=pylode(",",$ids);
		$uid=(int)$_POST['uid'];
		$nid=$this->obj->DB_delete_all("company_job","`id` in (".$delid.") and `uid`='".$uid."'"," ");
		$newest=$this->obj->DB_select_once("company_job","`uid`='".$uid."' order by lastupdate DESC");
	    $this->obj->update_once("company",array("jobtime"=>$newest['lastupdate']),array("uid"=>$uid));
	    if($nid)
	    {
	    	$this->obj->member_log("删除职位");
	    	$data['error']=1;
	    }else{
	    	$data['error']=2;
	    }
		echo json_encode($data);die;
	}
	function refresh_job_action()
	{
		if(!$_POST['uid'] || !$_POST['id'])
		{
			$data['error']=3;
			echo json_encode($data);die;
		}
		$this->get_com(3,$_POST['uid']);
		$where['id']=(int)$_POST['id'];
		$where['uid']=(int)$_POST['uid'];
		$nid=$this->obj->update_once("company_job",array("lastupdate"=>time()),$where);
		$this->obj->update_once("company",array("jobtime"=>time()),array("uid"=>(int)$_POST['uid']));
	    if($nid)
	    {

	    	$this->obj->member_log("刷新职位");
	    	$data['error']=4;
	    }else{
	    	$data['error']=5;
	    }
	    echo json_encode($data);die;
	}
	function look_resume_action(){ 
		$_POST = $this->CheckAppUser();
		$page=$_POST['page'];
		$limit=$_POST['limit'];
		$limit=!$limit?10:$limit;
		if(!$_POST['uid']){
			$data['error']=3;
			echo json_encode($data);die;
		}
		$where.="`com_id`='".(int)$_POST['uid']."' and `com_status`='0' order by `datetime` desc";
		if($page){
			$pagenav=($page-1)*$limit;
			$where.=" limit $pagenav,$limit";
		}else{
			$where.=" limit $limit";
		}
		$rows=$this->obj->DB_select_all("look_resume",$where);
		if(is_array($rows) && !empty($rows)){
			$resume_id=$uids=array();
			foreach($rows as $v){
				$com_id[] = $v['com_id'];
				if(in_array($v['resume_id'],$resume_id)==false){
					$resume_id[] = $v['resume_id'];
				} 
				if(in_array($v['uid'],$uids)==false){
					$uids[] = $v['uid'];
				} 
			}
			
			$com=$this->obj->DB_select_all("company","`uid` IN (".pylode(",",$com_id).")","uid,name");
			$expect=$this->obj->DB_select_all("resume_expect","`id` in (".pylode(",",$resume_id).")","`uname`,`sex`,`exp`,`edu`,`id`,`uid`,`job_classid`"); 
			if(is_array($expect)){
				include(PLUS_PATH."job.cache.php");
				include(PLUS_PATH."user.cache.php");
				foreach($rows as $key=>$val){
					foreach($expect as $va){ 
						if($val['resume_id']==$va['id']){ 
							$jobname=array(); 
							$jobclassid=@explode(',',$va['job_classid']);
							foreach($jobclassid  as $jval){
								$jobname[]=$job_name[$jval];
							}
							$rows[$key]['jobname']= @implode(',',$jobname);
							  
							$rows[$key]['name']=$va['uname'];
							$rows[$key]['uid']=$va['uid'];
							$rows[$key]['sex']=$va['sex'];
							$rows[$key]['exp']= $userclass_name[$va['exp']];
							$rows[$key]['edu']= $userclass_name[$va['edu']];
						}
					}
				}
			}
			foreach($rows as $key=>$va){
				$list[$key]['id']			=$va['id'];
				$list[$key]['uid']			=$va['uid'];
				$list[$key]['resume_id']	=$va['resume_id'];
				$list[$key]['sex']			=$va['sex'];
				$list[$key]['exp']			=$va['exp'];
				$list[$key]['edu']			=$va['edu'];
				$list[$key]['jobname']			=$va['jobname'];
				$list[$key]['name']	= $va['name'];
				$list[$key]['datetime'] 	= $va['datetime'];
			}
			foreach($list as $k=>$v){
				if(is_array($v)){
					foreach($v as $key=>$val){
						$list[$k][$key]=isset($val)?$val:'';
					}
				}else{
					$list[$k]=isset($v)?$v:'';
				}
			}
			$data['list']=count($list)?$list:array();
			$data['error']=1;
		}else{
			$data['error']=2;
		}
		echo json_encode($data);die;
	}
	function look_resume_del_action()
	{
		$_POST = $this->CheckAppUser();
		if(!$_POST['uid'] || !$_POST['ids']){
			$data['error']=3;
			echo json_encode($data);die;
		}
		$id=$this->obj->DB_update_all("look_resume","`com_status`='1'","`com_id`='".(int)$_POST['uid']."' and `id` in (".$_POST['ids'].")","");
		if($id)
		{
			$data['error']=1;
		}else{
			$data['error']=2;
		}
		echo json_encode($data);die;
	}
	function look_job_action(){	
		$_POST = $this->CheckAppUser();
		$page=$_POST['page'];
		$limit=$_POST['limit'];
		$limit=!$limit?10:$limit;
		if(!$_POST['uid']){
			$data['error']=3;
			echo json_encode($data);die;
		}
		$where.="`com_id`='".(int)$_POST['uid']."' and `com_status`='0' order by `datetime` desc";
		if($page){
			$pagenav=($page-1)*$limit;
			$where.=" limit $pagenav,$limit";
		}else{
			$where.=" limit $limit";
		}
		$rows=$this->obj->DB_select_all("look_job",$where);
		if(is_array($rows) && !empty($rows))
		{
			foreach($rows as $v){
				$uid[]=$v['uid'];
				$jobid[]=$v['jobid'];
			}
			$resume=$this->obj->DB_select_all("resume","`uid` in (".pylode(",",$uid).")","`uid`,`name`,`sex`,`edu`,`exp`,`def_job`");
			$job=$this->obj->DB_select_all("company_job","`id` in (".pylode(",",$jobid).")","`id`,`name`,`sex`,`salary`,`provinceid`,`cityid`,`three_cityid`,`edate`,`state`"); 
			if($resume&&is_array($resume)){
				$eid=array();
				foreach($resume as $v){
					$eid[]=$v['def_job'];
				}
				$expect=$this->obj->DB_select_all("resume_expect","`id` in (".pylode(",",$eid).")","`id`,`uid`,`job_classid`");
				include(PLUS_PATH."job.cache.php");
				if($expect&&is_array($expect)){
					foreach($expect as $k=>$v){
						$job_classid=@explode(',',$v['job_classid']);
						$jobname=array();
						foreach($job_classid as $v){
							$jobname[]=$job_name[$v];
						}
						$expect[$k]['jobname']= @implode(',',$jobname);
					}
				}
			}
			include(PLUS_PATH."city.cache.php");
			include(PLUS_PATH."user.cache.php");
			foreach($rows as $key=>$val){
				foreach($expect as $v){
					if($v['uid']==$val['uid']){
						$rows[$key]['job_classid']=$v['jobname'];
					}
				}
				foreach($resume as $va){ 
					if($val['uid']==$va['uid']){
						$rows[$key]['jobstate']=$va['state'];
						$rows[$key]['jobedate']=$va['edate'];
						$rows[$key]['salary']= $userclass_name[$va['salary']];
						$rows[$key]['exp']= $userclass_name[$va['exp']];
						$rows[$key]['edu']= $userclass_name[$va['edu']];
						$rows[$key]['sex']=$va['sex'];
						$rows[$key]['name']=$va['name'];
						$rows[$key]['city']= $city_name[$va['cityid']];
						$rows[$key]['province']= $city_name[$va['provinceid']];
						$rows[$key]['three_city']= $city_name[$va['three_cityid']];
					}
				}
				foreach($job as $va){
					if($val['jobid']==$va['id']){
						$rows[$key]['jobname']= $va['name'];
					}
				}
			}
			foreach($rows as $key=>$va){
				$list[$key]['salary']			=$va['salary'];
				$list[$key]['id']			=$va['id'];
				$list[$key]['uid']			=$va['uid'];
				$list[$key]['sex']			=$va['sex'];
				$list[$key]['jobname']			=$va['jobname'];
				$list[$key]['city']			=$va['city'];
				$list[$key]['job_classid']			=$va['job_classid'];
				$list[$key]['province']			=$va['province'];
				$list[$key]['three_city']			=$va['three_city'];
				$list[$key]['exp']			=$va['exp'];
				$list[$key]['edu']			=$va['edu'];
				$list[$key]['name']			= $va['name']; 
				$list[$key]['datetime'] 	= $va['datetime'];
			}
			foreach($list as $k=>$v){
				if(is_array($v)){
					foreach($v as $key=>$val){
						$list[$k][$key]=isset($val)?$val:'';
					}
				}else{
					$list[$k]=isset($v)?$v:'';
				}
			}
			$data['list']=count($list)?$list:array();
			$data['error']=1;
		}else{
			$data['error']=2;
		}
		echo json_encode($data);die;
	}
	function look_job_del_action()
	{
		$_POST = $this->CheckAppUser();
		if(!$_POST['uid'] || !$_POST['ids']){
			$data['error']=3;
			echo json_encode($data);die;
		}
		$id=$this->obj->DB_update_all("look_job","`com_status`='1'","`com_id`='".(int)$_POST['uid']."' and `id` in (".$_POST['ids'].")","");
		if($id)
		{
			$data['error']=1;
		}else{
			$data['error']=2;
		}
		echo json_encode($data);die;
	}
}
?>