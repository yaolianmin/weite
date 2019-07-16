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
class email_controller extends adminCommon{
	function index_action(){ 
	    $birthday=$this->obj->DB_select_once("email_msg","`title`='生日提醒' order by `ctime` desc","`ctime`");
	    $anniversary=$this->obj->DB_select_once("email_msg","`title`='网站周年' order by `ctime` desc","`ctime`");
	    $todaydue=$this->obj->DB_select_once("email_msg","`title` like '%会员套餐还有1天将过期%'  order by `ctime` desc","`ctime`");
	    $sevendue=$this->obj->DB_select_once("email_msg","`title` like '%会员套餐还有%' and `title` like '%天将过期%'  order by `ctime` desc","`ctime`");
	    $useradd=$this->obj->DB_select_once("email_msg","`title` like '%未发布简历%'  order by `ctime` desc","`ctime`");
	    $userup=$this->obj->DB_select_once("email_msg","`title` like '%今天简历刷新%'  order by `ctime` desc","`ctime`");
	    $addjob=$this->obj->DB_select_once("email_msg","`title` like '%未发布职位%'  order by `ctime` desc","`ctime`");
	    $upjob=$this->obj->DB_select_once("email_msg","`title` like '%未刷新职位%'  order by `ctime` desc","`ctime`");

		if(date('Y-m-d',$birthday['ctime'])==date('Y-m-d')){
	        $birthday['disabled']='1';
	    }
	    if(date('Y-m-d',$anniversary['ctime'])==date('Y-m-d')){
	        $anniversary['disabled']='1';
	    }
	    if(date('Y-m-d',$todaydue['ctime'])==date('Y-m-d')){
	        $todaydue['disabled']='1';
	    }
	    if(date('Y-m-d',$sevendue['ctime'])==date('Y-m-d')){
	        $sevendue['disabled']='1';
	    }
	    if(date('Y-m-d',$useradd['ctime'])==date('Y-m-d')){
	        $useradd['disabled']='1';
	    }
	    if(date('Y-m-d',$userup['ctime'])==date('Y-m-d')){
	        $userup['disabled']='1';
	    }
	    if(date('Y-m-d',$addjob['ctime'])==date('Y-m-d')){
	        $addjob['disabled']='1';
	    }
	    if(date('Y-m-d',$upjob['ctime'])==date('Y-m-d')){
	        $upjob['disabled']='1';
	    }
	    $this->yunset(array('birthday'=>$birthday,'anniversary'=>$anniversary,'todaydue'=>$todaydue,'sevendue'=>$sevendue,'useradd'=>$useradd,'userup'=>$userup,'addjob'=>$addjob,'upjob'=>$upjob));
		$this->yuntpl(array('admin/admin_tuiguang'));
	}
	function msgtg_action(){ 
	    $birthday=$this->obj->DB_select_once("moblie_msg","`content` like '%生日%' order by `ctime` desc","`ctime`");
	    $anniversary=$this->obj->DB_select_once("moblie_msg","`content` like '%周年庆%' order by `ctime` desc","`ctime`");
	    $todaydue=$this->obj->DB_select_once("moblie_msg","`content` like '%将于1天后到期%'  order by `ctime` desc","`ctime`");
	    $sevendue=$this->obj->DB_select_once("moblie_msg","`content` like '%将于7天后到期%'  order by `ctime` desc","`ctime`");
	    $useradd=$this->obj->DB_select_once("moblie_msg","`content` like '%未发布简历%'  order by `ctime` desc","`ctime`");
	    $userup=$this->obj->DB_select_once("moblie_msg","`content` like '%未刷新简历%'  order by `ctime` desc","`ctime`");
	    $addjob=$this->obj->DB_select_once("moblie_msg","`content` like '%未发布职位%'  order by `ctime` desc","`ctime`");
	    $upjob=$this->obj->DB_select_once("moblie_msg","`content` like '%未刷新职位%'  order by `ctime` desc","`ctime`");
	    if(date('Y-m-d',$birthday['ctime'])==date('Y-m-d')){
	        $birthday['disabled']='1';
	    }
	    if(date('Y-m-d',$anniversary['ctime'])==date('Y-m-d')){
	        $anniversary['disabled']='1';
	    }
	    if(date('Y-m-d',$todaydue['ctime'])==date('Y-m-d')){
	        $todaydue['disabled']='1';
	    }
	    if(date('Y-m-d',$sevendue['ctime'])==date('Y-m-d')){
	        $sevendue['disabled']='1';
	    }
	    if(date('Y-m-d',$useradd['ctime'])==date('Y-m-d')){
	        $useradd['disabled']='1';
	    }
	    if(date('Y-m-d',$userup['ctime'])==date('Y-m-d')){
	        $userup['disabled']='1';
	    }
	    if(date('Y-m-d',$addjob['ctime'])==date('Y-m-d')){
	        $addjob['disabled']='1';
	    }
	    if(date('Y-m-d',$upjob['ctime'])==date('Y-m-d')){
	        $upjob['disabled']='1';
	    }
	    $this->yunset(array('birthday'=>$birthday,'anniversary'=>$anniversary,'todaydue'=>$todaydue,'useradd'=>$useradd,'userup'=>$userup,'addjob'=>$addjob,'upjob'=>$upjob));
		$this->yuntpl(array('admin/admin_msgtg'));
	}
	function email_action(){  
		$this->yuntpl(array('admin/admin_send_email'));  
	} 
	function msg_action(){
		$this->yuntpl(array('admin/information'));
	}
	
	function tgresume_action(){
	    $this->yuntpl(array('admin/admin_tgresume'));
	}
	function tgjob_action(){
	    $this->yuntpl(array('admin/admin_tgjob'));
	}

	function send_action(){ 
		extract($_POST);
		if($email_title==''||$content==''){
			$this->ACT_layer_msg("邮件标题均不能为空！",8,$_SERVER['HTTP_REFERER']);
		} 
		$emailarr=$user=$com=$lt=$userinfo=array();
		if(@in_array(1,$all)){
			$userrows=$this->obj->DB_select_all("member","`usertype`='1'","email,`uid`,`usertype`");
		}
		if(@in_array(2,$all)){
			$userrows=$this->obj->DB_select_all("member","`usertype`='2'","email,`uid`,`usertype`");
		}
		if(@in_array(4,$all)){
			$userrows=$this->obj->DB_select_all("member","`usertype`='3' limit 5","email,`uid`,`usertype`");			
		}
		if(@in_array(3,$all)){
			$email_user=@explode(',',$_POST['email_user']); 
			$email_user=array_filter($email_user);
			foreach($email_user as $v){
			    if(CheckRegEmail($v)){
			        $earr[]=$v;
			    }
			}
			$userrows=$this->obj->DB_select_all("member","`email` in('".@implode("','",$email_user)."')","email,`uid`,`usertype`");  
		}  
		if(is_array($userrows)&&$userrows){
			foreach($userrows as $v){
				if($v['usertype']=='1'){$user[]=$v['uid'];}
				if($v['usertype']=='2'){$com[]=$v['uid'];}
				if($v['usertype']=='3'){$lt[]=$v['uid'];}
				$emailarr[$v['uid']]=$v["email"];
			}
			if($user&&is_array($user)){
				$resume=$this->obj->DB_select_all("resume","`uid` in(".@implode(',',$user).")","`name`,`uid`");
				foreach($resume as $val){
					$userinfo[$val['uid']]['name']=$val['name'];
				}
			}
			if($com&&is_array($com)){
				$company=$this->obj->DB_select_all("company","`uid` in(".@implode(',',$com).")","`name`,`uid`");
				foreach($company as $val){
					$userinfo[$val['uid']]['name']=$val['name'];
				}
			}
			if($lt&&is_array($lt)){
				$lt_info=$this->obj->DB_select_all("lt_info","`uid` in(".@implode(',',$lt).")","`realname`,`uid`");
				foreach($lt_info as $val){
					$userinfo[$val['uid']]['name']=$val['realname'];
				}
			} 
		} 
		if(!count($emailarr)){ 
			$this->ACT_layer_msg("没有符合条件的邮箱，请先检查！",8);
		}
		set_time_limit(10000);

		$emailid=$this->send_email($emailarr,$email_title,$content,true,$userinfo); 
	}
	function save_action(){
		extract($_POST);
		if(trim($content)==''){
			$this->ACT_layer_msg("请输入短信内容！",8,$_SERVER['HTTP_REFERER']);
		}
		if($userarr=='' && $all!='4'){
			$this->ACT_layer_msg("手机号码不能为空！",8,$_SERVER['HTTP_REFERER']);
		}
		$uidarr=array();
		if($all==4){
			$mobliesarr=@explode(',',$userarr);
			$userrows=$this->obj->DB_select_all("member","`moblie` in(".$userarr.")","`moblie`,`uid`,`usertype`");		 
			$moblies=array();
			foreach($userrows as $v){
				$moblies[]=$v['moblie'];
			}  
		}else{
			$userrows=$this->obj->DB_select_all("member","`usertype`='".$all."'","`moblie`,`uid`,`usertype`");
		}
		if(is_array($userrows)&&$userrows){
			$user=$com=$lt=$userinfo=array();
			foreach($userrows as $v){
				if($v['usertype']=='1'){$user[]=$v['uid'];}
				if($v['usertype']=='2'){$com[]=$v['uid'];}
				if($v['usertype']=='3'){$lt[]=$v['uid'];}
				$uidarr[$v['uid']]=$v["moblie"];
			}
			if($user&&is_array($user)){
				$resume=$this->obj->DB_select_all("resume","`uid` in(".@implode(',',$user).")","`name`,`uid`");
				foreach($resume as $val){
					$userinfo[$val['uid']]=$val['name'];
				}
			}
			if($com&&is_array($com)){
				$company=$this->obj->DB_select_all("company","`uid` in(".@implode(',',$com).")","`name`,`uid`");
				foreach($company as $val){
					$userinfo[$val['uid']]=$val['name'];
				}
			}
			if($lt&&is_array($lt)){
				$lt_info=$this->obj->DB_select_all("lt_info","`uid` in(".@implode(',',$lt).")","`realname`,`uid`");
				foreach($lt_info as $val){
					$userinfo[$val['uid']]=$val['realname'];
				}
			}
		}
		if($all==4){
			foreach($mobliesarr as $v){
				if(in_array($v,$moblies)==false&&CheckMoblie($v)){
					$uidarr[]=$v;
				}
			}
		}
		if(is_array($uidarr)&&$uidarr){
			if($this->config["sy_msguser"]=="" || $this->config["sy_msgpw"]=="" || $this->config["sy_msgkey"]==""||$this->config['sy_msg_isopen']!='1'){
				$this->ACT_layer_msg("还没有配置短信！",8,$_SERVER['HTTP_REFERER']);
			}
			$notice = $this->MODEL('notice');
			$result = '没有待发送的短信';
			foreach($uidarr as $key=>$v){
				if($userinfo[$key]==''){
					$key='';
				}
				$msguser=$this->config["sy_msguser"];
				$msgpw=$this->config["sy_msgpw"];
				$msgkey=$this->config["sy_msgkey"];
				$result = $notice->sendSMS(array('mobile' => $v, 'content' => $content,
					'uid' => $key, 'name' => $userinfo[$key]
				));
				$result = $result['msg'];
			}
		}
		$this->ACT_layer_msg($result,14,$_SERVER['HTTP_REFERER'],2,1);
	}
	function getinfos($userrows){
		foreach($userrows as $v){
			if($v['usrtype']=='1'){
				$user[]=$v['uid'];
			}else if($v['usrtype']=='2'){
				$com[]=$v['uid'];
			}else if($v['usrtype']=='3'){
				$lt[]=$v['uid'];
			}
		}
		if($user&&$user){
			$resume=$this->obj->DB_select_all("resume","`uid` in(".pylode(',',$user).")","`uid`,`name`"); 
		}
		if($com&&$com){
			$company=$this->obj->DB_select_all("company","`uid` in(".pylode(',',$com).")","`uid`,`name`"); 
		}
		if($lt&&$lt){
			$ltinfo=$this->obj->DB_select_all("lt_info","`uid` in(".pylode(',',$lt).") and `realname`<>''","`uid`,`realname` as `name`"); 
		}
		foreach($userrows as $k=>$v){
			foreach($resume as $val){
				if($v['uid']==$val['uid']){
					$userrows[$k]['name']=$v['name'];
				}
			}
			foreach($company as $val){
				if($v['uid']==$val['uid']){
					$userrows[$k]['name']=$v['name'];
				}
			}
			foreach($ltinfo as $val){
				if($v['uid']==$val['uid']){
					$userrows[$k]['name']=$v['name'];
				}
			} 
		}
		return $userrows;
	}
	function sendcom_action(){
		$data=array();
		$emailtype=intval($_POST['emailtype']);
		$emailday=intval($_POST['emailday']);
		$page=intval($_POST['page']);
		$limit=100;
		if($page<1){$page=1;}	
		if($emailtype){ 
			if($emailday){
				$time=strtotime("-".$emailday." day");
			}else{
				$time=strtotime("-7 day");
			} 
			$ststrsql=($page-1)*$limit;
			if($emailtype=='2'){
				if($this->config['sy_email_viped']!='1'){
					$data['msg']='请先开启会员服务到期提醒！'; 
					$data['stype']=2;  
				}else{
					$allnum=$this->obj->DB_select_num("company_statis","`vip_etime`<'".$time."'","`uid`");
					$allpage=ceil($allnum/$limit); 
					if($allpage>$page){ 
						$rating=$this->obj->DB_select_all("company_statis","`vip_etime`<'".$time."' limit $ststrsql,$limit","`uid`,`vip_etime`,`rating_name`"); 
						if($rating&&is_array($rating)){
							$uids=array();
							foreach($rating as $v){
								$uids[]=$v['uid'];
							} 
							$member=$this->obj->DB_select_all("member","`usertype`='2' and `email`<>'' and `uids` in(".pylode(',',$uids).")","`uid`,`email`,`login_date`");
						} 
					}else{
						$data['msg']='发送成功！'; 
						$data['stype']=2;  
					}
				} 
				 
			}else if($emailtype=='1'){ 
				if($this->config['sy_email_comwdl']!='1'){
					$data['msg']='请先开启未登录提醒！'; 
					$data['stype']=2;  
				}else{
					$allnum=$this->obj->DB_select_num("member","`usertype`='2' and `email`<>'' and `login_date`<'".$time."'","`uid`");
					
					$allpage=ceil($allnum/$limit);  
					if($allpage>$page){
						$member=$this->obj->DB_select_all("member","`usertype`='2' and `email`<>'' and `login_date`<'".$time."' limit $ststrsql,$limit","`uid`,`email`"); 
					}else{
						$data['msg']='发送成功！'; 
						$data['stype']=2;  
					}
				}  
			}
			 
			if($data['stype']==''){ 
				if($member&&is_array($member)){ 
					$uids=$com=$ratinginfo=$ratingdate=array();
					foreach($member as $v){
						$uids[]=$v['uid'];
					}
					$company=$this->obj->DB_select_all("company","`uid` in(".pylode(',',$uids).")","`uid`,`name`");
					foreach($company as $v){
						$com[$v['uid']]=$v['name'];
					}
					$notice = $this->MODEL('notice');
					if($emailtype=='1'){
						foreach($member as $v){ 
							$notice->sendEmailType(array("email"=>$v['email'],"uid"=>$v['uid'],"name"=>$com[$v['uid']],"date"=>date("Y-m-d H:i:s",$v['login_date']),"type"=>"comwdl"));
						}
					}elseif($emailtype=='2'){
						foreach($rating as $v){
							$ratinginfo[$v['uid']]=$v['rating_name'];
							$ratingdate[$v['uid']]=date("Y-m-d H:i:s",$v['vip_etime']);
						}
						foreach($member as $v){ 
							$notice->sendEmailType(array("email"=>$v['email'],"uid"=>$v['uid'],"name"=>$com[$v['uid']],"retingname"=>$ratinginfo[$v['uid']],'date'=>$ratingdate[$v['uid']],"type"=>"viped"));
						}
					}  
					$data['msg']='正在发送第'.$ststrsql.'至'.($ststrsql+$limit).'封邮件！'; 
					$data['stype']=1;  
				}else{
					$data['msg']='未找到合适企业！'; 
					$data['stype']=2; 
				}
			}
		}else{
			$data['msg']='非法操作！'; 
			$data['stype']=2;
		}
		$data['page']=$page+1;
		echo json_encode($data);die;
	}
	 
	function senduser_action(){
		$emailtype=intval($_POST['emailtype']);
		$emailday=intval($_POST['emailday']);
		$page=intval($_POST['page']);
		$limit=100;
		if($page<1){$page=1;}	
		if($emailtype){ 
			if($emailday){
				$time=strtotime("-".$emailday." day");
			}else{
				$time=strtotime("-7 day");
			} 
			$ststrsql=($page-1)*$limit;
			if($emailtype=='2'){
				if($this->config['sy_email_userup']!='1'){
					$data['msg']='请先开启未更新提醒！';  
					$data['stype']=2;
				}else{ 
					$allnum=$this->obj->DB_select_num("resume_expect","`lastupdate`<'".$time."' and `defaults`='1' and `status`<>'2' and `r_status`<>'2' and `job_classid`<>'' and `open`='1' group by `uid` ","`uid`");
					$allpage=ceil($allnum/$limit); 
					if($allpage>$page){ 
						$expect=$this->obj->DB_select_all("resume_expect","`lastupdate`<'".$time."' and `defaults`='1' and `status`<>'2' and `r_status`<>'2' and `job_classid`<>'' and `open`='1' group by `uid`  limit $ststrsql,$limit","`uid`,`lastupdate`");
						if($expect&&is_array($expect)){
							$uids=$lasttime=array();
							foreach($expect as $v){
								$uids[]=$v['uid'];
								$lasttime[$v['uid']]=date("Y-m-d H:i:s",$v['lastupdate']);
							}
							$member=$this->obj->DB_select_all("member","`usertype`='1' and `email`<>'' and `uids` in(".pylode(',',$uids).")","`uid`,`email`,`username`");
						} 
					}else{
						$data['msg']='发送成功！'; 
						$data['stype']=2;  
					}
				}
			}else if($emailtype=='1'){
				if($this->config['sy_email_userwdl']!='1'){
					$data['msg']='请先开启未登录提醒！';  
					$data['stype']=2; 
				}else{ 
					$allnum=$this->obj->DB_select_num("member","`usertype`='1' and `email`<>'' and `login_date`<'".$time."' ","`uid`");
					$allpage=ceil($allnum/$limit); 
					
					if($allpage>$page){ 
						$member=$this->obj->DB_select_all("member","`usertype`='1' and `email`<>'' and `login_date`<'".$time."' limit $ststrsql,$limit","`uid`,`email`");
					}else{
						$data['msg']='发送成功！'; 
						$data['stype']=2;  
					}
				} 
			} 
			if($data['stype']==''){ 
				if($member&&is_array($member)){
					$emailarr=$userinfo=$uids=array(); 
					$notice = $this->MODEL('notice');
					if($emailtype=='1'){
						foreach($member as $v){ 
							$notice->sendEmailType(array("email"=>$v['email'],"uid"=>$v['uid'],"username"=>$v['username'],"date"=>date("Y-m-d H:i:s",$v['login_date']),"type"=>"userwdl"));
						}
					}else{
						foreach($member as $v){ 
							$notice->sendEmailType(array("email"=>$v['email'],"uid"=>$v['uid'],"name"=>$v['username'],'date'=>$lasttime[$v['uid']],"type"=>"userup"));
						}
					} 
					$data['msg']='正在发送第'.$ststrsql.'至'.($ststrsql+$limit).'封邮件！'; 
					$data['stype']=1;  
				}else{
					$data['msg']='未找到合适用户！';
					$data['stype']=2;		
				}
			}
		}else{
			$data['msg']='非法操作！';
			$data['stype']=2;			
		}
		$data['page']=$page+1;
		echo json_encode($data);die;
	}
	
	function getBirthday_action(){
	    $todaydue=$this->obj->DB_select_all("company_statis","`vip_etime`>'".time()."' and `vip_etime`<'".strtotime('+1 day')."'","uid");
	    foreach ($todaydue as $v){
	        $todayuid[]=$v['uid'];
	    }
	    $sevendue=$this->obj->DB_select_all("company_statis","`vip_etime`>'".time()."' and `vip_etime`<'".strtotime('+7 day')."'","uid");
	    foreach ($sevendue as $v){
	        $sevenuid[]=$v['uid'];
	    }
	    $regs=$this->obj->DB_select_all('member',"`reg_date`>'".strtotime('-7 day')."' and `usertype`='1'","uid");
	    foreach($regs as $k=>$v){
	        $uids[]=$v['uid'];
	    }
	    $upjob=$this->obj->DB_select_all("company_job","`edate`>'".time()."' and `lastupdate`<'".strtotime('-7 day')."' and `r_status`<>'2' group by `uid` order by `lastupdate` desc","`uid`");
	    foreach ($upjob as $v){
	        $upuid[]=$v['uid'];
	    }
	    if ($_GET['type']=='email'){
	        $num['birthday_e']   =$this->obj->DB_select_num("resume","date_format( birthday, '%m%d' )  = date_format( now( ) , '%m%d' )  and `email`<>''");
	        $num['anniversary_e']=$this->obj->DB_select_num("member","`email`<>''and `status`=1");
	        $num['todaydue_e']   =$this->obj->DB_select_num("company","`linkmail`<>'' and `r_status`<>'2' and `uid` in ('".pylode("','", $todayuid)."')");
	        $num['sevendue_e']   =$this->obj->DB_select_num("company","`linkmail`<>'' and `r_status`<>'2' and `uid` in ('".pylode("','", $sevenuid)."')");
	        $num['useradd_e']    =$this->obj->DB_select_num("resume","`r_status`<>'2' and def_job='0' and resumetime is null and `email`<>'' and `uid` in ('".pylode("','", $uids)."')");
	        $num['userup_e']     =$this->obj->DB_select_num("resume","`def_job`>0 and `r_status`<>'2' and `email`<>'' and `lastupdate`<'".strtotime('-7 day')."'");
	        $num['addjob_e']     =$this->obj->DB_select_num("company","`jobtime`>'1' and `jobtime`<'".strtotime('-7 day')."' and `r_status`<>'2' and `linkmail`<>''");
	        $num['upjob_e']     =$this->obj->DB_select_num("company","`linkmail`<>'' and `r_status`<>'2' and `uid` in ('".pylode("','", $upuid)."')");
	    }else{
	        $num['birthday_m']   =$this->obj->DB_select_num("resume","date_format( birthday, '%m%d' )  = date_format( now( ) , '%m%d' )  and `telphone`<>''");
	        $num['anniversary_m']=$this->obj->DB_select_num("member","`moblie`<>'' and `status`=1");
	        $num['todaydue_m']   =$this->obj->DB_select_num("company","`linktel`<>'' `r_status`<>'2' and `uid` in ('".pylode("','", $todayuid)."')");
	        $num['sevendue_m']     =$this->obj->DB_select_num("company","`linktel`<>'' `r_status`<>'2' and `uid` in ('".pylode("','", $sevenuid)."')");
	        $num['useradd_m']    =$this->obj->DB_select_num("resume","`r_status`<>'2' and def_job='0' and resumetime is null and `telphone`<>'' and `uid` in ('".pylode("','", $uids)."')");
	        $num['userup_e']     =$this->obj->DB_select_num("resume","`def_job`>0 and `r_status`<>'2' and `email`<>'' and `lastupdate`<'".strtotime('-7 day')."'");
	        $num['userup_m']     =$this->obj->DB_select_num("resume","`def_job`>0 and `r_status`<>'2' and `telphone`<>'' and `lastupdate`<'".strtotime('-7 day')."'");
	        $num['addjob_m']     =$this->obj->DB_select_num("company","`jobtime`>'1' and `jobtime`<'".strtotime('-7 day')."' and `r_status`<>'2' and `linktel`<>''");
	        $num['upjob_m']     =$this->obj->DB_select_num("company","`linktel`<>'' and `r_status`<>'2' and `uid` in ('".pylode("','", $upuid)."')");
	    }
	    echo json_encode($num);die;
	}

	function sendPromotion_action(){
	    $type=intval($_POST['type']);
	    $emailtype=intval($_POST['emailtype']);
	    $emailtpl=intval($_POST['emailtpl']);
	    $dayslimit=intval($_POST['dayslimit']);
		$sort=intval($_POST['sort']);
	    if($sort){
	        if($this->config['sy_email_set']!="1"){
	            $arr['status']=0;
	            $arr['msg']='还没有配置邮箱，请联系管理员！';
	            
	            echo json_encode($arr);die;
	        }
	    }else{
	        if($this->config["sy_msguser"]=="" || $this->config["sy_msgpw"]=="" || $this->config["sy_msgkey"]==""||$this->config['sy_msg_isopen']!='1'){
	            $arr['status']=0;
	            $arr['msg']='还没有配置短信，请联系管理员！';
	            
	            echo json_encode($arr);die;
	        }
	    }
	    $emailarr=$user=$com=$lt=$userinfo=array();
	    $members=$users=$companys=$uids=$useremail=$comemail=$ltemail=$tpls=array();
	    if($emailtype=='1'){
	        if($type=='1'){
	            if($sort){
	                $users=$this->obj->DB_select_all("resume","date_format( birthday, '%m%d' )  = date_format( now( ) , '%m%d' )  and `email`<>''","`email`,`uid`,`birthday`,`name`");
	            }else{
	                $users=$this->obj->DB_select_all("resume","date_format( birthday, '%m%d' )  = date_format( now( ) , '%m%d' )  and `telphone`<>''","`uid`,`birthday`,`name`,`telphone` as moblie");
	            }

				
	            if($users&&is_array($users)){
	                foreach($users as $k=>$v){
	                    $userinfo[$v['uid']]=$v;
	                }
	            }
	        }
	    }else if($emailtype=='2'){
	        if($type=='2'){
	            if($sort){
	                $members=$this->obj->DB_select_all("member","`email`<>'' and `status`='1'","`uid`,`username`,`email`");
	            }else{
	                $members=$this->obj->DB_select_all("member","`moblie`<>'' and `status`='1'","`uid`,`username`,`moblie`");
	            }
	            if($members&&is_array($members)){
	                foreach($members as $k=>$v){
	                    $userinfo[$v['uid']]=$v;
	                }
	            }
	        }
	    }else if($emailtype=='3'){
	        if($this->config['sy_email_viped']=='2'){
	            $arr['status']=0;
	            $arr['msg']='请先开启会员到期提醒！';
	            
	            echo json_encode($arr);die;
	        }
	        $comstatis=$this->obj->DB_select_all("company_statis","`vip_etime`>'".time()."' and `vip_etime`<'".strtotime('+'.$dayslimit.' day')."'","`uid`,`vip_etime`,`rating_name`");
	         if(is_array($comstatis)){
				foreach($comstatis as $key=>$value){
					$uid[] = $value['uid'];
				}
				 if($sort){
					$companys=$this->obj->DB_select_all("company","`uid` IN (".@implode(',',$uid).") AND `linkmail`<>'' and `name`<>''","`uid`,`name`,`linkmail` as email");
				}else{
					$companys=$this->obj->DB_select_all("company","`uid` IN (".@implode(',',$uid).") AND `linktel`<>'' and `name`<>''","`uid`,`name`,`linktel` as moblie");
				}
				foreach($companys as $key=>$value){
					foreach ($comstatis as $k=>$v){
						if($value['uid']==$v['uid']){
							$companys[$key]['vip_etime']=$v['vip_etime'];
							$companys[$key]['rating_name']=$v['rating_name'];
						}
					}
				}
			}
	        if($companys&&is_array($companys)){
	            foreach($companys as $k=>$v){
					$v['day'] = $dayslimit;
	                $userinfo[$v['uid']]=$v;
	            }
	        }
	    }else if($emailtype=='4'){
	        if($type=='1'){
	            $regs=$this->obj->DB_select_all('member',"`reg_date`>'".strtotime('-'.$dayslimit.' day')."' and `usertype`='1'","uid,reg_date,username");
	            $regdate=$user=array();
				foreach($regs as $k=>$v){
	                $uids[]=$v['uid'];
					$regdate[$v['uid']] = $v['reg_date'];
					$user[$v['uid']] = $v['username'];
	            }
	            if($sort){
	                $users=$this->obj->DB_select_all("resume","`r_status`<>'2' and def_job='0' and resumetime is null and `email`<>'' and `uid` in ('".pylode("','", $uids)."')","`uid`,`name`,`email`,`resumetime`");
	            }else{
	                $users=$this->obj->DB_select_all("resume","`r_status`<>'2' and def_job='0' and resumetime is null and `telphone` and `uid` in ('".pylode("','", $uids)."')","`uid`,`name`,`resumetime`,`telphone` as `moblie`");
	            }
	            foreach($users as $k=>$v){
					if($v['name']){
						
						$v['name'] = $user[$v['uid']];
					}
	                $userinfo[$v['uid']]=$v;
	                $userinfo[$v['uid']]['day']=$dayslimit;
					$userinfo[$v['uid']]['reg_date']=$regdate[$v['uid']];
	            }
	        }
	    }else if($emailtype=='5'){
	        if($type=='1'){
	            if($sort){
	                $resumes=$this->obj->DB_select_all("resume","`def_job`>0 and `r_status`<>'2' and `email`<>'' and `lastupdate`<'".strtotime('-7 day')."'","distinct `uid`,`name`,`email`,`lastupdate`");
	            }else{
	                $resumes=$this->obj->DB_select_all("resume","`def_job`>0 and `r_status`<>'2' and `telphone`<>'' and `lastupdate`<'".strtotime('-7 day')."'","distinct `uid`,`name`,`lastupdate`,`telphone` as `moblie`");
	            }
	            foreach($resumes as $k=>$v){
	                $userinfo[$v['uid']]=$v;
	                $userinfo[$v['uid']]['day']=$dayslimit;
	            }
	        }
	    }else if($emailtype=='6'){
	        if($type=='2'){
	            if($sort){
	                 $companys=$this->obj->DB_select_all("company","`jobtime`>'1' and `jobtime`<'".strtotime('-7 day')."' and `r_status`<>'2' and `linkmail`<>''","`uid`,`name`,`jobtime`,`linkmail` as `email`,`linktel` as `moblie`");
	            }else{
	                 $companys=$this->obj->DB_select_all("company","`jobtime`>'1' and `jobtime`<'".strtotime('-7 day')."' and `r_status`<>'2' and `linktel`<>''","`uid`,`name`,`jobtime`,`linktel` as `moblie`");
	            }
	            foreach($companys as $k=>$v){
	                $userinfo[$v['uid']]=$v;
	                $userinfo[$v['uid']]['day']=$dayslimit;
	            }
	        }
	    }else if($emailtype=='7'){
	        if($type=='2'){
	            $comjobs=$this->obj->DB_select_all("company_job","`edate`>'".time()."' and `lastupdate`<'".strtotime('-7 day')."' and `r_status`<>'2' group by `uid` order by `lastupdate` desc","`uid`,`lastupdate`");
	            foreach($comjobs as $k=>$v){
	                $comids[]=$v['uid'];
	            }
	            if($sort){
	                $companys=$this->obj->DB_select_all("company","`linkmail`<>'' and `name`<>'' and `uid` in ('".pylode("','", $comids)."')","`uid`,`name`,`linkmail` as `email`");
	            }else{
	                $companys=$this->obj->DB_select_all("company","`linktel`<>'' and `name`<>'' and `uid` in ('".pylode("','", $comids)."')","`uid`,`name`,`linktel` as `moblie`");
	            }
	            foreach($companys as $k=>$v){
	                foreach ($comjobs as $val){
	                    if ($v['uid']==$val['uid']){
	                        $companys[$k]['lastupdate']=$val['lastupdate'];
	                    }
	                }
	            }
	            foreach($companys as $k=>$v){
	                $userinfo[$v['uid']]=$v;
	                $userinfo[$v['uid']]['day']=$dayslimit;
	            }
	        }
	    }
		
	    if($emailtpl=='1'){
	        $useremail=array_unique($useremail);
	        $comemail=array_unique($comemail);
	        $ltemail=array_unique($ltemail);
	        set_time_limit(1000);
	        if(count($userinfo)>500){
	            $arr['status']=0;
	            $arr['msg']='数量过多，第三方发送服务器将会影响，部分邮件无法发送。建议找专业的群发软件！';
	            
	            echo json_encode($arr);die;
	        }
		}
		foreach($userinfo as $key=>$value){
			$data[] = $this->shjobmsg($value,$emailtype,$sort);
		}
		
		if($data!=""){
			$ok = 0;
			$notOk = 0;
			$errorMsg = '';
			$notice = $this->MODEL('notice');
			foreach($data as $key=>$value){
				if($value['email']){
				  	$retval = $notice->sendEmailType($value);
				}
				if($value['moblie']){
					$retval = $notice->sendSMSType($value);
				}
				
				if($retval['status'] == -1){
					$notOk ++;	
					$errorMsg = ",失败原因：{$retval['msg']}";
				}
				else{
					$ok ++;
				}
			}
		}
	
		$arr['status']= $notOk == 0 ? 1 : 0;
	    $arr['msg']= "发送成功:{$ok},失败:{$notOk}{$errorMsg}";
	    
	    echo json_encode($arr);die;
	}
	
	function shjobmsg($info,$type,$sort){
	    $data=array();
	    $tpltype=array(
	        '1'=>'birthday',
	        '2'=>'webbirthday',
	        '3'=>'viped',
	        '4'=>'useradd',
	        '5'=>'userup',
	        '6'=>'addjob',
	        '7'=>'upjob'
	    );
	    $data['type']=$tpltype[$type];
	    if($data['type']!=""){
	        if($type=='1'){
                $data['uid']=$info['uid'];
                $data['name']=$info['name'];
                if($sort){
                    $data['email']=$info['email'];
                }else{
                    $data['moblie']=$info['moblie'];
                }
                $data['username']=$info['name'];
                $data['date']=$info['birthday'];
                $data['year']=date("Y")-date("Y",strtotime($info['birthday']));
	        }elseif ($type=='2'){
	            $data['uid']=$info['uid'];
	            $data['name']=$info['username'];
	            $data['username']=$info['username'];
	            if($sort){
	                $data['email']=$info['email'];
	            }else{
	                $data['moblie']=$info['moblie'];
	            }
	        }elseif ($type=='3'){
	             
	            $data['uid']=$info['uid'];
	            $data['name']=$info['name'];
	            if($sort){
	                $data['email']=$info['email'];
	            }else{
	                $data['moblie']=$info['moblie'];
	            }
	            $data['retingname']=$info['rating_name'];
				$data['date']=date("Y-m-d",$info['vip_etime']); 
				$data['day'] = floor(($info['vip_etime'] - time()) / (60 * 60 * 24)); 
	        }elseif ($type=='4'){
	            if($info['name']==''){
	                
	                $data['username']=$userarr['username'];
	            }else{
	                $data['username']=$info['name'];
	            }
	            $data['uid']=$info['uid'];
	            $data['name']=$info['name'];
	            if($sort){
                    $data['email']=$info['email'];
                }else{
                    $data['moblie']=$info['moblie'];
                }
	            $data['date']=date("Y-m-d",$info['reg_date']);
	            $data['day']=$info['day'];
	        }elseif ($type=='5'){
	            if($info['name']==''){
	                $userarr=$this->obj->DB_select_once("member","`uid`='".$info['uid']."'","`username`");
	                $data['username']=$userarr['username'];
	            }else{
	                $data['username']=$info['name'];
	            }
	            $data['uid']=$info['uid'];
	            $data['name']=$info['name'];
	            if($sort){
                    $data['email']=$info['email'];
                }else{
                    $data['moblie']=$info['moblie'];
                }
	            $data['date']=date("Y-m-d",$info['lastupdate']);
	            $data['day']=$info['day'];
	        }elseif ($type=='6'){
	            $data['uid']=$info['uid'];
	            $data['name']=$info['name'];
	            if($sort){
                    $data['email']=$info['email'];
                }else{
                    $data['moblie']=$info['moblie'];
                }
	            if($info['name']){
	                $data['username']=$info['name'];
	            }else{
	                $data['username']=$info['username'];
	            }
	            $data['date']=date("Y-m-d",$info['jobtime']);
	            $data['day']=$info['day'];
	        }elseif ($type=='7'){
	            $data['uid']=$info['uid'];
	            $data['name']=$info['name'];
	            if($sort){
                    $data['email']=$info['email'];
                }else{
                    $data['moblie']=$info['moblie'];
                }
	            if($info['name']){
	                $data['username']=$info['name'];
	            }else{
	                $data['username']=$info['username'];
	            }
	            $data['date']=date("Y-m-d",$info['lastupdate']);
	            $data['day']=$info['day'];
	        }
	        return $data;
	    }
	}
	function msgsave_action(){
		extract($_POST);
		if(trim($content)==''){
			$this->ACT_layer_msg("请输入短信内容！",8,$_SERVER['HTTP_REFERER']);
		}
		if($userarr=='' && $all=='4'){
			$this->ACT_layer_msg("手机号码不能为空！",8,$_SERVER['HTTP_REFERER']);
		}
		$uidarr=array();
		if($all==4){
			$mobliesarr=@explode(',',$userarr);
			$userrows=$this->obj->DB_select_all("member","`moblie` in(".$userarr.")","`moblie`,`uid`,`usertype`");		 
			$moblies=array();
			foreach($userrows as $v){
				$moblies[]=$v['moblie'];
			}  
		}else{
			$userrows=$this->obj->DB_select_all("member","`usertype`='".$all."'","`moblie`,`uid`,`usertype`");
		}
		if(is_array($userrows)&&$userrows){
			$user=$com=$lt=$userinfo=array();
			foreach($userrows as $v){
				if($v['usertype']=='1'){$user[]=$v['uid'];}
				if($v['usertype']=='2'){$com[]=$v['uid'];}
				if($v['usertype']=='3'){$lt[]=$v['uid'];}
				$uidarr[$v['uid']]=$v["moblie"];
			}
			if($user&&is_array($user)){
				$resume=$this->obj->DB_select_all("resume","`uid` in(".@implode(',',$user).")","`name`,`uid`");
				foreach($resume as $val){
					$userinfo[$val['uid']]=$val['name'];
				}
			}
			if($com&&is_array($com)){
				$company=$this->obj->DB_select_all("company","`uid` in(".@implode(',',$com).")","`name`,`uid`");
				foreach($company as $val){
					$userinfo[$val['uid']]=$val['name'];
				}
			}
			if($lt&&is_array($lt)){
				$lt_info=$this->obj->DB_select_all("lt_info","`uid` in(".@implode(',',$lt).")","`realname`,`uid`");
				foreach($lt_info as $val){
					$userinfo[$val['uid']]=$val['realname'];
				}
			}
		}
		if($all==4){
			foreach($mobliesarr as $v){
				if(in_array($v,$moblies)==false&&CheckMoblie($v)){
					$uidarr[]=$v;
				}
			}
		}
		if(is_array($uidarr)&&$uidarr){
			if($this->config["sy_msguser"]=="" || $this->config["sy_msgpw"]=="" || $this->config["sy_msgkey"]==""||$this->config['sy_msg_isopen']!='1'){
				$this->ACT_layer_msg("还没有配置短信！",8,$_SERVER['HTTP_REFERER']);
			}
			$notice = $this->MODEL('notice');
			$result = '没有待发送的短信';
			foreach($uidarr as $key=>$v){
				if($userinfo[$key]==''){
					$key='';
				}
				$msguser=$this->config["sy_msguser"];
				$msgpw=$this->config["sy_msgpw"];
				$msgkey=$this->config["sy_msgkey"];
				$result = $notice->sendSMS(array('mobile' => $v, 'content' => $content,
					'uid' => $key, 'name' => $userinfo[$key]
				));
				$result = $result['msg'];
			}
		}
		$this->ACT_layer_msg($result,14,$_SERVER['HTTP_REFERER'],2,1);
	}
	function send_email($email=array(),$emailtitle="",$emailcoment="",$emailalert=false,$userinfo=array(),$other=array()){
		
    $notice = $this->MODEL('notice');        
		$sendok=0;$sendno=0;
		if(is_array($email)){
			if($other['batch']=='1'){
				$emailData['email'] = @implode(',',$email);
				$emailData['subject'] = $emailtitle;
				$emailData['content'] = stripslashes($emailcoment);
				$sendid = $notice->sendEmail($emailData);

			}else{ 
				foreach($email as $key=>$v){
					if($emailcoment==''&&$userinfo['tpl']){
						$data=array(
							'username'=>$userinfo[$key]['name'],
							'date'=>$userinfo[$key]['date'],
							'year'=>$userinfo[$key]['year']
						);
						$emailcoment=$notice->_tpl($userinfo['tpl']['content'],$data);
					}
					if($emailtitle==''&&$userinfo['tpl']){
						$data=array(
							'username'=>$userinfo[$key]['name'],
							'date'=>$userinfo[$key]['date'],
							'year'=>$userinfo[$key]['year']
						);
						$emailtitle=$notice->_tpl($userinfo['tpl']['title'],$data);
					} 
					
					$emailData['email'] = $v;
					$emailData['subject'] = $emailtitle;
					$emailData['content'] = stripslashes($emailcoment);

					$emailData['uid'] = $key;
					$emailData['name'] = $userinfo[$key]['name'];
					$emailData['cuid'] = $userinfo[$key]['cuid'];
					$emailData['cname'] = $userinfo[$key]['cuid'];

					$sendid = $notice->sendEmail($emailData);

					if($sendid['status'] != -1){
						$state=1;
						$sendok++;
					}else{
						$state=0;
						$sendno++;
					}
				}
			}
		}
		if($emailalert){
			$this->ACT_layer_msg($sendok."位发送成功，".$sendno."位发送失败！",1,$_SERVER['HTTP_REFERER']);
		}else{
			return $sendok;
		}
	}
	function getcom_action(){
		$com=(int)$_POST['com'];
	    if ($com==1){ 
	        $row = $this->obj->DB_select_all('company_statis',"vip_etime>".time()."", 'uid');
	    }elseif ($com==2){ 
			$row = $this->obj->DB_select_all("company_job",
				"`edate`>'".time()."' and `lastupdate`>'".strtotime('-7 day')."' and `r_status`<>'2'","`uid`");
	    }elseif ($com==3){  
	        $row = $this->obj->DB_select_num('member',"reg_date>".strtotime('-3 day')." and usertype=2", 'uid');
		}
		
		$uids = array();
		foreach($row as $r){
			$uids [] = $r['uid'];
		}
		$uids = array_unique($uids);
		$num = 0;
		if(count($uids) > 0){
			$where = "uid in (" . implode(',', $uids) . ") and `name`<>'' ";
			if($_POST['msgType'] == 1){
				$where .= " and linktel <> ''";
			}
			else{
				$where .= " and linkmail <> ''";
			}
			$num = $this->obj->DB_select_num("company", $where);
		}
	    echo $num;die;
	}
	function getuser_action(){
	    $user=(int)$_POST['user'];
	    if ($user==1){
	        $num=$this->obj->DB_select_num('resume_expect',"lastupdate>".strtotime('-7 day')." and status<>2 and r_status<>2 and job_classid<>'' and open=1 and defaults=1");
	    }elseif ($user==2){
	        $num=$this->obj->DB_select_num('member',"reg_date>".strtotime('-3 day')." and usertype=1");
	    }
	    echo $num;die;
	}
	function getjob_action(){
	    $job=(int)$_POST['job'];
	    if ($job==2){
	        $num=$this->obj->DB_select_num('company_job',"rec_time>".time()." and `state`=1 ");
	    }elseif ($job==3){ 
	        $num=$this->obj->DB_select_num('company_job',"urgent_time>".time()." and `state`=1");
	    }
	    echo $num;die;
	}
	function gsresume($resume,$com,$sendnum,$num){
        $company=$this->getsendcom($com, $sendnum);
	    if($company&&is_array($company)){
	        foreach ($company as $v){
	            $hyid[$v['uid']]['hy']=$v['hy'];
	            $hyid[$v['uid']]['cityid']=$v['cityid'];
	        }
	    }
	    if($hyid&&is_array($hyid)){
	        include(CONFIG_PATH."db.data.php");
	        include PLUS_PATH."/user.cache.php";
	        foreach ($hyid as $k=>$v){
	            if ($resume==1){ 
	                $expect=$this->obj->DB_select_all('resume_expect',"height_status=2 and hy=".$v['hy']." and cityid=".$v['cityid']." and status<>2 and r_status<>2 and job_classid<>'' and open=1 and defaults=1 order by lastupdate desc limit ".$num."");
	            }elseif ($resume==2){ 
	                $expect=$this->obj->DB_select_all('resume_expect',"hy=".$v['hy']." and cityid=".$v['cityid']." and status<>2 and r_status<>2 and job_classid<>'' and open=1 and defaults=1 order by lastupdate desc limit ".$num."");
	            }elseif ($resume==3){ 
	                $expect=$this->obj->DB_select_all('resume_expect',"hy=".$v['hy']." and cityid=".$v['cityid']." and whour>12 and exp>18 and status<>2 and r_status<>2 and job_classid<>'' and open=1 and defaults=1 order by lastupdate desc limit ".$num."");
	            }
	            if($expect&&is_array($expect)){
	                $html='<table width="800"border="0" style="border:1px solid #ddd" cellpadding="5" cellspacing="0" id="rtable">';
	                $html.='<tr ><td colspan="6"><div style="float:left;padding:10px;"><img src="'.$this->config['sy_weburl'].'/'.$this->config['sy_logo'].'"></div><div style="float:left; padding-left:100px; line-height: 100px;"> 网站联系电话:'.$this->config['sy_freewebtel'].'</div> <div style=" float:right; padding-right:10px;    text-align: center;"><div><img src="'.$this->config['sy_weburl'].'/'.$this->config['sy_wx_qcode'].'" width="80"height="80"></div>微信公众号二维码</div></td></tr>';
	                $html.='<tr style="background:#f8f8f8; font-weight:bold"><td height="26">姓名</td><td>年龄</td><td>学历</td><td>工作经验</td> <td>性别</td> <td width="80" align="center">操作</td></tr>';
	                foreach ($expect as $v){
	                    $a=date('Y',strtotime($v['birthday']));
	                    $age=date("Y")-$a;
	                    if ($v['height_status']==2){
	                        $url=Url('resume',array('c'=>'show','id'=>$v['id'],'type'=>2));
	                    }else{
	                        $url=Url('resume',array('c'=>'show','id'=>$v['id']));
	                    }
	                    $html.='<tr><td height="26" style="border-bottom:1px solid #ddd"><b><font color="#0033FF">'.$v['uname'].'</font></b></td><td style="border-bottom:1px solid #ddd">'.$age.'</td><td style="border-bottom:1px solid #ddd">'.$userclass_name[$v['edu']].'</td><td style="border-bottom:1px solid #ddd">'.$userclass_name[$v['exp']].'</td> <td style="border-bottom:1px solid #ddd">'.$arr_data['sex'][$v['sex']].'</td><td align="center" style="border-bottom:1px solid #ddd"><a href="'.$url.'" style="background:#f60; padding:2px 14px;color:#fff; display:inline-block">查看</a></td></tr>';
	                }
	                $html.='</table>';
	                $table[$k]=$html;
	            }
	        }
	        foreach ($company as $k=>$v){
	            foreach ($table as $key=>$val){
	                if ($v['uid']==$key){
	                    $company[$k]['html']=$val;
	                }
	            }
	        }
	        return $company;
	    }
	}
	function getsendcom($com,$sendnum,$type=1){
	    if ($com==1){ 
	        $scom=$this->obj->DB_select_all('company_statis',"vip_etime>".time()."","uid");
	    }elseif ($com==2){  
	        $scom=$this->obj->DB_select_all("company_job","`edate`>'".time()."' and `lastupdate`>'".strtotime('-7 day')."' and `r_status`<>'2'","distinct `uid`");
	    }elseif ($com==3){  
	        $scom=$this->obj->DB_select_all('member',"reg_date>".strtotime('-3 day')." and usertype=2","uid");
	    }
	    if($scom&&is_array($scom)){
	        foreach ($scom as $v){
	            $comid[]=$v['uid'];
	        }
	        $limit='';
	        if ($sendnum){
	            $limit="limit ".$sendnum;
	        }
	        if ($type==1){
	            $company=$this->obj->DB_select_all('company',"`linkmail`<>'' and `r_status`<>'2' and `uid` in (".pylode(',', $comid).") order by lastupdate desc ".$limit,"uid,name,hy,linkmail,cityid");
	        }else{
	            $company=$this->obj->DB_select_all('company',"`linktel`<>'' and `r_status`<>'2' and `uid` in (".pylode(',', $comid).") order by lastupdate desc ".$limit,"uid,name,hy,linktel,cityid");
	        }
	    }
	    return $company;
	}
	function sendresume_action(){
	    $type=(int)$_POST['stype'];
	    if($type==1){
	        $company=$this->gsresume($_POST['resume'], $_POST['com'], $_POST['sendnum'], $_POST['num']);
	        $notice = $this->MODEL('notice');
	        $sendok=0;$sendno=0;
	        if($company&&is_array($company)){
	            foreach($company as $k=>$v){
	                if ($v['html']!=''){
	                    $emailData['email'] = $v['linkmail'];
	                    $emailData['subject'] = $_POST['email_title'];
	                    $emailData['content'] = $v['html'];

	                    $emailData['uid'] = $v['uid'];
	                    $emailData['name'] = $v['name'];
	                    $sendid = $notice->sendEmail($emailData);
	                    if($sendid['status'] != -1){
	                        $sendok++;
	                    }else{
	                        $sendno++;
	                    }
	                }
	            }
	        }
	        $this->ACT_layer_msg($sendok."位发送成功，".$sendno."位发送失败！",1,$_SERVER['HTTP_REFERER']);
	    }else{
	        if($this->config["sy_msguser"]=="" || $this->config["sy_msgpw"]=="" || $this->config["sy_msgkey"]==""||$this->config['sy_msg_isopen']!='1'){
	            $this->ACT_layer_msg("还没有配置短信！",8);
	        }
			$company=$this->getsendcom($_POST['com'], $_POST['sendnum'],2);
			$notice = $this->MODEL('notice');
			$result = '没有符合条件的企业';
			foreach($company as $v){
	            $msguser=$this->config["sy_msguser"];
	            $msgpw=$this->config["sy_msgpw"];
	            $msgkey=$this->config["sy_msgkey"];
				$result = $notice->sendSMS(array('moblie' => $v['linktel'], 'content' => $_POST['content']
				));
				$result = $result['msg'];
	        }
	        $this->ACT_layer_msg($result,14,$_SERVER['HTTP_REFERER'],2,1);
	    }
	}
	function gsjob($job,$user,$sendnum,$num){
	    $resume=$this->getsenduser($user, $sendnum);
	    if($resume&&is_array($resume)){
	        foreach ($resume as $v){
	            $uid[]=$v['uid'];
	        }
	        $expect=$this->obj->DB_select_all('resume_expect',"uid in (".pylode(',', $uid).") and defaults=1","uid,hy,cityid");
	        foreach ($expect as $v){
	            $hyid[$v['uid']]['hy']=$v['hy'];
	            $hyid[$v['uid']]['cityid']=$v['cityid'];
	        }
	    }
	    if($hyid&&is_array($hyid)){
	        include(CONFIG_PATH."db.data.php");
	        include PLUS_PATH."/com.cache.php";
	        include PLUS_PATH."/city.cache.php";
	        foreach ($hyid as $k=>$v){
	            if ($job==1){ 
	                $comjob=$this->obj->DB_select_all('company_job',"hy=".$v['hy']." and cityid=".$v['cityid']." and `state`=1 order by lastupdate desc limit ".$num."");
	            }elseif ($job==2){ 
	                $comjob=$this->obj->DB_select_all('company_job',"hy=".$v['hy']." and cityid=".$v['cityid']." and rec_time>".time()." and `state`=1 order by lastupdate desc limit ".$num."");
	            }elseif ($job==3){ 
	                $comjob=$this->obj->DB_select_all('company_job',"hy=".$v['hy']." and cityid=".$v['cityid']." and urgent_time>".time()." and `state`=1 order by lastupdate desc limit ".$num."");
	            }
	            if($comjob&&is_array($comjob)){
	                $html='<table width="800"border="0" style="border:1px solid #ddd" cellpadding="5" cellspacing="0" id="rtable">';
	                $html.='<tr ><td colspan="6"><div style="float:left;padding:10px;"><img src="'.$this->config['sy_weburl'].'/'.$this->config['sy_logo'].'"></div><div style="float:left; padding-left:100px; line-height: 100px;"> 网站联系电话:'.$this->config['sy_freewebtel'].'</div> <div style=" float:right; padding-right:10px;    text-align: center;"><div><img src="'.$this->config['sy_weburl'].'/'.$this->config['sy_wx_qcode'].'" width="80"height="80"></div>微信公众号二维码</div></td></tr>';
	                $html.='<tr style="background:#f8f8f8; font-weight:bold"><td height="26">职位</td><td>工作地点</td><td>薪资</td><td>学历要求</td><td>工作经验</td> <td>性别</td> <td width="80" align="center">操作</td></tr>';
	                foreach ($comjob as $v){
	                    $url=Url('job',array('c'=>'comapply','id'=>$v['id']));
	                    if ($v['minsalary']&&$v['maxsalary']){
	                        $salary='￥'.$v['minsalary'].'-'.$v['maxsalary'];
	                    }elseif ($v['minsalary']&&!$v['maxsalary']){
	                        $salary='￥'.$v['minsalary'].'起';
	                    }elseif (!$v['minsalary']&&!$v['maxsalary']){
	                        $salary='面议';
	                    }
	                    $html.='<tr><td height="26" style="border-bottom:1px solid #ddd"><b><font color="#0033FF">'.mb_substr($v['name'],"0","12","utf-8").'</font></b></td><td style="border-bottom:1px solid #ddd">'.$city_name[$v['cityid']].'</td><td style="border-bottom:1px solid #ddd">'.$salary.'</td><td style="border-bottom:1px solid #ddd">'.$comclass_name[$v['edu']].'</td><td style="border-bottom:1px solid #ddd">'.$comclass_name[$v['exp']].'</td> <td style="border-bottom:1px solid #ddd">'.$arr_data['sex'][$v['sex']].'</td><td align="center" style="border-bottom:1px solid #ddd"><a href="'.$url.'" style="background:#f60; padding:2px 14px;color:#fff; display:inline-block">查看</a></td></tr>';
	                }
	                $html.='</table>';
	                $table[$k]=$html;
	            }
	        }
	        foreach ($resume as $k=>$v){
	            foreach ($table as $key=>$val){
	                if ($v['uid']==$key){
	                    $resume[$k]['html']=$val;
	                }
	            }
	        }
	        return $resume;
	    }
	}
	function getsenduser($user,$sendnum,$type=1){
	    if ($user==1){
	        $suser=$this->obj->DB_select_all('resume_expect',"lastupdate>".strtotime('-7 day')." and status<>2 and r_status<>2 and job_classid<>'' and open=1 and defaults=1","uid");
	    }elseif ($user==2){ 
	        $suser=$this->obj->DB_select_all('member',"reg_date>".strtotime('-3 day')." and usertype=1","uid");
	    }
	    if($suser&&is_array($suser)){
	        foreach ($suser as $v){
	            $userid[]=$v['uid'];
	        }
	        $limit='';
	        if ($sendnum){
	            $limit="limit ".$sendnum;
	        }
	        if ($type==1){
	            $resume=$this->obj->DB_select_all('resume',"`email`<>'' and `r_status`<>'2' and `uid` in (".pylode(',', $userid).") order by lastupdate desc ".$limit,"uid,name,email");
	        }else{
	            $resume=$this->obj->DB_select_all('resume',"`telphone`<>'' and `r_status`<>'2' and `uid` in (".pylode(',', $userid).") order by lastupdate desc ".$limit,"uid,name,telphone");
	        }
	    }
	    return $resume;
	}
	function sendjob_action(){
	    $type=(int)$_POST['stype'];
	    if($type==1){
	        $resume=$this->gsjob($_POST['job'], $_POST['user'], $_POST['sendnum'], $_POST['num']);
	        $sendok=0;$sendno=0;
	        if($resume&&is_array($resume)){
              $notice = $this->MODEL('notice');
	            foreach($resume as $k=>$v){
	                if ($v['html']!=''){
	                    $emailData['email'] = $v['email'];
	                    $emailData['subject'] = $_POST['email_title'];
	                    $emailData['content'] = $v['html'];

	                    $emailData['uid'] = $v['uid'];
	                    $emailData['name'] = $v['name'];
	                    $sendid = $notice->sendEmail($emailData);
	                    if($sendid['status'] != -1){
	                        $sendok++;
	                    }else{
	                        $sendno++;
	                    }
	                }
	            }
	        }
	        $this->ACT_layer_msg($sendok."位发送成功，".$sendno."位发送失败！",1,$_SERVER['HTTP_REFERER']);
	    }else{
	        if($this->config["sy_msguser"]=="" || $this->config["sy_msgpw"]=="" || $this->config["sy_msgkey"]==""||$this->config['sy_msg_isopen']!='1'){
	            $this->ACT_layer_msg("还没有配置短信！",8);
	        }
			$resume=$this->getsenduser($_POST['user'], $_POST['sendnum'],2);
			$notice = $this->MODEL('notice');
			$result = '没有符合条件的用户';
	        foreach($resume as $v){
	            $msguser=$this->config["sy_msguser"];
	            $msgpw=$this->config["sy_msgpw"];
	            $msgkey=$this->config["sy_msgkey"];
				$result = $notice->sendSMS(array('moblie'=> $v['telphone'],
					'content' => $_POST['content'],
					'uid' => $v['uid'],
					'name' => $v['name']
				));
				$result = $result['msg'];
	        }
	        $this->ACT_layer_msg($result,14,$_SERVER['HTTP_REFERER'],2,1);
	    }
	}
}

?>