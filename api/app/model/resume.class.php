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
class resume_controller extends common{
	function list_action(){
		$where = "`status`<>'2' AND `r_status`<>'2' AND `defaults`=1   and `job_classid`<>'' and `open`='1'";
		$keyword=$_POST['keyword'];
		$page=$_POST['page'];
		$type=$_POST['type'];
		$report=$_POST['report'];
		$edu=$_POST['edu'];
		$limit=$_POST['limit'];
		$order=$_POST['order'];
		$nodata=$_POST['nodata'];
		$job_classids=$_POST['job_classids'];
		$uid=(int)$_POST['uid'];
		$provinceid=(int)$_POST['provinceid'];
		$cityid=(int)$_POST['cityid'];
		$hy=(int)$_POST['hy'];
		$exp=(int)$_POST['exp'];
		$limit=!$limit?10:$limit;
		if($exp){
			$where.=" and `exp`='".$exp."'";
		}else{
			$where.=" AND `exp`>'0'";
		}
		if($report){
			$where.=" and `report`='".$report."'";
		}
		if($type){
			$where.=" and `type`='".$type."'";
		}
		if($edu){
			$where.=" and `edu`='".$edu."'";
		}else{
			$where.="  AND `edu`>'0'";
		}
		if($hy){
			$where.=" and `hy`='".$hy."'";
		}
		if($provinceid){
			$where.=" and `provinceid`='".$provinceid."'";
		}
		if($cityid){
			$where.=" and `cityid`='".$cityid."'";
		}
		if($job_classids){
			$jobids=explode(',',$job_classids);
			if(is_array($jobids))
			{
				foreach($jobids as $value)
				{
					$jobclass[]="FIND_IN_SET('".$value."',job_classid)";
				}
				$where1[]=@implode(" or ",$jobclass);
			}
			$where.=" AND (".@implode(" or ",$where1).")";
			unset($where1);
		}
		
		if($_POST['keyword'])
		{
			include(PLUS_PATH."job.cache.php");

			$where1[]="`uname` LIKE '%".$keyword."%'";
			foreach($job_name as $k=>$v){
				if(strpos($v,$keyword)!==false){
					$jobid[]=$k;
				}
			}
			if(is_array($jobid))
			{
				foreach($jobid as $value)
				{
					$class[]="FIND_IN_SET('".$value."',job_classid)";
				}
				$where1[]=@implode(" or ",$class);
			}
			include(PLUS_PATH."city.cache.php");
			$cityid=array();
			foreach($city_name as $k=>$v)
			{
				if(strpos($v,$keyword)!==false)
				{
					$cityid[]=$k;
				}
			}
			if(!empty($cityid))
			{
				foreach($cityid as $value)
				{
					$class[]= "(provinceid = '".$value."' or cityid = '".$value."')";
				}
				$where1[]=@implode(" or ",$class);
			}
			$where.=" AND (".@implode(" or ",$where1).")";
		}
		if($uid){
			$where.=" and `uid`='".$uid."'";
		}
		if($_POST["height_status"])
		{
			$where .=" AND height_status='".$_POST["height_status"]."'";
		}else{
			$where .=" AND height_status<>'2'";
		}

		if($order=="time asc" || $order=="time desc" ){
			$where.=" order by ".str_replace("time","lastupdate",$order);
		}else if($order=="uid asc"){
			$where.=" order by uid asc";
		}else{
			$where.=" order by uid desc";
		}
		if($page){
			$pagenav=($page-1)*$limit;
			$where.=" limit $pagenav,$limit";
		}else{
			$where.=" limit $limit";
		}
		$select="uid,uname,sex,edu,birthday,provinceid,cityid,three_cityid,minsalary,maxsalary,type,hits,lastupdate,exp,report,job_classid,hy,id,integrity,photo";
		$rows=$this->obj->DB_select_all("resume_expect",$where,$select);
		if(is_array($rows)){
			foreach($rows as $key=>$k){
				if($k['birthday']){
					$birthday=@explode('-',$k['birthday']);
					$list[$key]['age']=date("Y")-$birthday[0];
				}
				$list[$key]['id']			=$k['id'];
				$list[$key]['uid']			=$k['uid'];
				$list[$key]['name']			=$k['uname'];
				$list[$key]['sex']			=$k['sex'];
				$list[$key]['edu']			=$k['edu'];
				$list[$key]['exp']			=$k['exp'];
				$list[$key]['report']		=$k['report'];
				$list[$key]['job_classid']	=$k['job_classid'];
				$list[$key]['provinceid']	=$k['provinceid'];
				$list[$key]['cityid']		=$k['cityid'];
				$list[$key]['three_cityid']	=$k['three_cityid'];
				$list[$key]['minsalary']	=$k['minsalary'];
				$list[$key]['maxsalary']	=$k['maxsalary'];
				$list[$key]['type']			=$k['type'];
				$list[$key]['lastupdate']	=$k['lastupdate'];
				$list[$key]['hits']			=$k['hits'];
				$list[$key]['hy']			=$k['hy'];
				$list[$key]['integrity']	=$k['integrity'];
				$list[$key]['birthday']		=$k['birthday'];
				$list[$key]['photo']		=$k['photo'];
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
			$data['u_name']=$this->config['user_name'];
		}else{
			$data['error']=2;
		}
		echo json_encode($data);die;
	}
	function show_action(){
		$id=(int)$_POST['id'];
		$uid=(int)$_POST['uid'];
		if(!$id && !$uid){
			$data['error']=3;
			echo json_encode($data);die;
		}
		if($uid){
			$where="a.`uid`='".$uid."' and a.`def_job`=b.`id`";
		}else{
			$where="b.`id`='".$id."' and a.`uid`=b.`uid`";
		}
		$rows=$this->obj->DB_select_alls("resume","resume_expect",$where,"a.uid,a.name,a.sex,a.birthday,a.marriage,a.height,a.living,a.domicile,a.nationality,a.weight,a.edu,a.exp,b.provinceid,b.cityid,b.three_cityid,b.minsalary,b.maxsalary,b.type,b.hits,b.lastupdate,b.doc,a.idcard_pic,a.photo,a.resume_photo,a.description,a.address,a.homepage,a.email,a.telphone,a.qq,a.idcard,b.hy,b.job_classid,b.report,b.id,b.integrity,b.jobstatus,a.idcard_status");
		$row=$rows[0];
		if(is_array($row)){
			
			if($_POST['comid'])
			{	
				$this->obj->update_once("userid_job",array("is_browse"=>"2"),array("com_id"=>(int)$_POST['comid'],"eid"=>(int)$_POST['id']));
				$look_resume=$this->obj->DB_select_once("look_resume","`com_id`='".(int)$_POST['comid']."' and `resume_id`='".$row['id']."'");
				if(!empty($look_resume))
				{
					$this->obj->DB_update_all("look_resume","`datetime`='".time()."'","`com_id`='".(int)$_POST['comid']."' and `resume_id`='".$row['id']."'");
				}else{
					$value.="`uid`='".$row['uid']."',";
					$value.="`resume_id`='".$row['id']."',";
					$value.="`com_id`='".(int)$_POST['comid']."',";
					$value.="`datetime`='".time()."'";
					$this->obj->DB_insert_once("look_resume",$value);
				}
			}
			if($row['birthday']){
				$birthday=@explode('-',$row['birthday']);
				$data['info']['age']=date("Y")-$birthday[0];
			}
			$data['info']['id']		=$row['id'];
			$data['info']['homepage']	=$row['homepage'];
			$data['info']['email']		=$row['email'];
			$data['info']['idcard_status']	=$row['idcard_status'];
			$data['info']['telphone']	=$row['telphone'];
			$data['info']['idcard']		=$row['idcard']?$row['idcard']:"";
			$data['info']['uid']		=$row['uid'];
			$data['info']['name']		=$row['name'];
			$data['info']['sex']		=$row['sex'];
			$data['info']['edu']		=$row['edu'];
			$data['info']['exp']		=$row['exp'];
			$data['info']['qq']			=$row['qq'];
			$data['info']['provinceid']	=$row['provinceid'];
			$data['info']['cityid']		=$row['cityid'];
			$data['info']['three_cityid']=$row['three_cityid'];
			$data['info']['minsalary']	=$row['minsalary'];
			$data['info']['maxsalary']	=$row['maxsalary'];
			$data['info']['type']		=$row['type'];
			$data['info']['lastupdate']	=$row['lastupdate'];
			$data['info']['hits']		=$row['hits'];
			$data['info']['hy']		=$row['hy'];
			$data['info']['report']		=$row['report'];
			$data['info']['job_classid']		=$row['job_classid'];
			$data['info']['birthday']	=$row['birthday'];
			$data['info']['marriage']	=$row['marriage'];
			$data['info']['height']		=$row['height'];
			$data['info']['nationality']=$row['nationality'];
			$data['info']['weight']		=$row['weight'];
			$data['info']['living']	=$row['living'];
			$data['info']['domicile']		=$row['domicile'];
			$data['info']['doc']	=$row['doc'];
			$data['info']['idcard_pic']	=$row['idcard_pic'];
			$data['info']['photo']	=$row['photo'];
			$data['info']['resume_photo']	=$row['resume_photo'];
			$data['info']['description']	=$row['description'];
			$data['info']['address']	=$row['address'];
			$data['info']['integrity']	=$row['integrity'];
			$data['info']['jobstatus']	=$row['jobstatus'];
			$ewhere="`eid`='".$row['id']."'";
			if($row['doc']==1){

				$doc = $this->obj->DB_select_once("resume_doc",$ewhere);
				$data['docbody']['id']=$doc['id'];
				$data['docbody']['body']=$doc['doc'];
			}else{
				 
				
				$skill = $this->obj->DB_select_all("resume_skill",$ewhere);
				if(is_array($skill)){
					foreach($skill as $key=>$k){
						$data['skill'][$key]['id']=$k['id'];
						$data['skill'][$key]['name']=$k['name'];
						$data['skill'][$key]['skill']=$k['skill'];
						$data['skill'][$key]['ing']=$k['ing'];
						$data['skill'][$key]['longtime']=$k['longtime'];
						$data['skill'][$key]['pic']=$k['pic'];
					}
				} 
	
			$work = $this->obj->DB_select_all("resume_work",$ewhere);
			if(is_array($work)){
				foreach($work as $key=>$k){
					$data['work'][$key]['id']=$k['id'];
					$data['work'][$key]['name']=$k['name'];
					$data['work'][$key]['sdate']=$k['sdate'];
					$data['work'][$key]['edate']=$k['edate'];
					$data['work'][$key]['department']=$k['department'];
					$data['work'][$key]['title']=$k['title'];
					$data['work'][$key]['content']=$k['content'];
				}
			}
	
			$project = $this->obj->DB_select_all("resume_project",$ewhere);
			if(is_array($project)){
				foreach($project as $key=>$k){
					$data['project'][$key]['id']=$k['id'];
					$data['project'][$key]['name']=$k['name'];
					$data['project'][$key]['sdate']=$k['sdate'];
					$data['project'][$key]['edate']=$k['edate'];
					$data['project'][$key]['sys']=$k['sys'];
					$data['project'][$key]['title']=$k['title'];
					$data['project'][$key]['content']=$k['content'];
				}
			}
	
			$edu = $this->obj->DB_select_all("resume_edu",$ewhere);
			if(is_array($edu)){
				foreach($edu as $key=>$k){
					$data['edu'][$key]['id']=$k['id'];
					$data['edu'][$key]['name']=$k['name'];
					$data['edu'][$key]['sdate']=$k['sdate'];
					$data['edu'][$key]['edate']=$k['edate'];
					$data['edu'][$key]['specialty']=$k['specialty'];
					$data['edu'][$key]['title']=$k['title'];
					$data['edu'][$key]['education']=$k['education'];
					$data['edu'][$key]['content']=$k['content'];
				}
			}
		
			$training = $this->obj->DB_select_all("resume_training",$ewhere);
			if(is_array($training)){
				foreach($training as $key=>$k){
					$data['training'][$key]['id']=$k['id'];
					$data['training'][$key]['name']=$k['name'];
					$data['training'][$key]['sdate']=$k['sdate'];
					$data['training'][$key]['edate']=$k['edate'];
					$data['training'][$key]['title']=$k['title'];
					$data['training'][$key]['content']=$k['content'];
				}
			}
			
			$other = $this->obj->DB_select_all("resume_other",$ewhere);
			if(is_array($other)){
				foreach($other as $key=>$k){
					$data['other'][$key]['id']=$k['id'];
					$data['other'][$key]['name']=$k['name'];
					$data['other'][$key]['content']=$k['content'];
				}
			}
			}
			$data['error']=1;
		}else{
			$data['error']=2;
		}
		if($_POST['comid']){
			$down=$this->obj->DB_select_once("down_resume","`eid`='".$row['id']."' and comid='".$_POST['comid']."'");
			if(!empty($down)){
				$data['look_link']=1;
			}else{
				$data['look_link']=2;
			}
			
			
			$msg=$this->obj->DB_select_once("userid_msg","`uid`='".$row['uid']."' and `fid`='".$_POST['comid']."'");
			if($msg['id']){
				$data['yqms']=1;
			}else{
				$data['yqms']=0;
			}
			$pool=$this->obj->DB_select_once("talent_pool","`uid`='".$row['uid']."' and `cuid`='".$_POST['comid']."'");
			if($pool['id']){
				$data['fav']=1;
			}else{
				$data['fav']=0;
			}
		}else{
			$data['look_link']=2;
			$data['yqms']=0;
			$data['fav']=0;
		}
		echo json_encode($data);die;
	}

	function invite_action(){ 
		if(!$_POST['content']||!$_POST['eid']||!$_POST['uid']||!$_POST['userid']){
			$data['error']=3;
			echo json_encode($data);die;
		}


		$this->CheckAppUser();

		$_POST['fid']=(int)$_POST['uid'];
        $_POST['uid']=(int)$_POST['userid']; 
		$user=$this->obj->DB_select_once("member","`uid`='".$_POST['fid']."'","usertype");
		$expect=$this->obj->DB_select_once("resume","`uid`='".$_POST['uid']."'",'`name`'); 
		$username=$expect['name'];
		
		if($user['usertype']!="2"){
			$data['error']=4;
			echo json_encode($data);die;
		}
		$job=$this->obj->DB_select_once("company_job","`id`='".$_POST['jobid']."'","`name`,`com_name`");
		$sql=array();
		$sql['title']='面试邀请';
		$sql['content']=$this->stringfilter($_POST['content']);
		$sql['uid']=$_POST['uid'];
		$sql['fid']=$_POST['fid'];
		$sql['datetime']=mktime();
		$sql['address']=$_POST['interviewAddress'];
		$sql['intertime']=$_POST['interviewTime'];
		$sql['linkman']=$_POST['linkman'];
		$sql['linktel']=$_POST['linktel'];
		$sql['jobid']=$_POST['jobid'];
		$sql['jobname']=$job['name'];
		$sql['fname']=$job['com_name'];
        $isblackname=$this->obj->DB_select_once("blacklist","`p_uid`='".$_POST['uid']."' and `c_uid`='".$_POST['fid']."' and `usertype`='1'");
        if(is_array($isblackname)){
			$data['error']=2;
			echo json_encode($data);die;
        }
		$umessage = $this->obj->DB_select_once("userid_msg","`uid`='".$_POST['uid']."' AND `fid`='".$_POST['fid']."'");
	
		if(is_array($umessage)){
			$data['error']=6;
		}else{ 
			
			$row=$this->obj->DB_select_once("company_statis","`uid`='".$_POST['fid']."'");
				
			if($row['rating']==0){
	
				if($row['integral']<$this->config['integral_interview'] && $this->config['integral_interview_type']=="2"){ 
					$data['error']=5;
				}else{
					$this->obj->insert_into("userid_msg",$sql);
					if($this->config['integral_interview_type']=="1")
					{
						$auto=true;
					}else{
						$auto=false;
					}
					$this->MODEL('integral')->company_invtal($_POST['fid'],$this->config["integral_interview"],false,"邀请会员面试");
					$this->msg_post($_POST[uid],$_POST['fid'],array('username'=>$username,'jobname'=>$job['name']));
					$state_content = "我刚邀请了人才 <a href=\"".$this->config[sy_weburl]."/index.php?m=resume&id=$_POST[eid]\" target=\"_blank\">".$username."</a> 面试。";
					$this->addstate($state_content,1,$_POST['fid']);
					$data['eid']=$_POST['eid'];
					$data['status']=1;
					$data['error']=1;
					$this->obj->member_log("邀请了人才 ".$username." 面试。");
				}
			}else{
				if($row['vip_etime']>time() || $row['vip_etime']==0){					
					if($row['rating_type']=="1"){
						
						if($row['invite_resume']==0){
						
							if($row['integral']<$this->config['integral_interview'] && $this->config['integral_interview_type']=="2"){
								$data['error']=5;
							}else{
								$this->obj->insert_into("userid_msg",$sql);
								if($this->config['integral_interview_type']=="2"){
									$auto=false;
								}else{
									$auto=true;
								}
								$this->MODEL('integral')->company_invtal($_POST['fid'],$this->config['integral_interview'],$auto,"邀请会员面试");
								$this->msg_post($_POST['uid'],$_POST['fid'],array('username'=>$username,'jobname'=>$job['name']));
								$state_content = "我刚邀请了人才 <a href=\"".$this->config['sy_weburl']."/index.php?m=resume&id=$_POST[eid]\" target=\"_blank\">".$username."</a> 面试。";
								$this->addstate($state_content,1,$_POST['fid']);
								$data['eid']=$_POST['eid'];
								$data['status']=1;
								$data['error']=1;
								$this->obj->member_log("邀请了人才 ".$username." 面试。");
							}
						}else{
							
							$this->obj->insert_into("userid_msg",$sql);
							$this->obj->DB_update_all("company_statis","`invite_resume`=`invite_resume`-1","`uid`='".$_POST['fid']."'");
							$this->msg_post($_POST['uid'],$_POST['fid'],array('username'=>$username,'jobname'=>$job['name']));
							$state_content = "我刚邀请了人才 <a href=\"".$this->config['sy_weburl']."/index.php?m=resume&id=$_POST[eid]\" target=\"_blank\">".$username."</a> 面试。";
							$this->addstate($state_content,1,$_POST['fid']);
							$data['eid']=$_POST['eid'];
							$data['status']=1;
							$data['error']=1;
							$this->obj->member_log("邀请了人才 ".$username." 面试。");
						}
					}else{
						$this->obj->insert_into("userid_msg",$sql);
						$this->msg_post($_POST['uid'],$_POST['fid'],array('username'=>$username,'jobname'=>$job['name']));
						$state_content = "我刚邀请了人才 <a href=\"".$this->config['sy_weburl']."/index.php?m=resume&id=$_POST[eid]\" target=\"_blank\">".$username."</a> 面试。";
						$this->addstate($state_content,1,$_POST['fid']);
						$data['eid']=$_POST['eid'];
						$data['status']=1;
						$data['error']=1;
						$this->obj->member_log("邀请了人才 ".$username." 面试。");
					}
				}else{ 
					if($row['integral']<$this->config['integral_interview'] && $this->config['integral_interview_type']=="2"){
						$data['error']=5;
					}else{
						$this->obj->insert_into("userid_msg",$sql);
						if($this->config['integral_interview_type']=="2")
						{
							$auto=false;
						}else{
							$auto=true;
						}
						$this->MODEL('integral')->company_invtal($_POST['fid'],$this->config['integral_interview'],$auto,"邀请会员面试");
						$this->msg_post($_POST['uid'],$_POST['fid'],array('username'=>$username,'jobname'=>$job['name']));
						$state_content = "我刚邀请了人才 <a href=\"".$this->config['sy_weburl']."/index.php?m=resume&id=$_POST[eid]\" target=\"_blank\">".$username."</a> 面试。";
						$this->addstate($state_content,1,$_POST['fid']);
						$data['eid']=$_POST['eid'];
						$data['status']=1;
						$data['error']=1;
						$this->obj->member_log("邀请了人才 ".$username." 面试。");
					}
				}
			}
		}
		echo json_encode($data);die;
	}

	function msg_post($uid,$comid,$info=array()){
		$com=$this->obj->DB_select_once("company","`uid`='$comid'");
		$uid=$this->obj->DB_select_once("member","`uid`='$uid'","email,moblie");
		$data=array();
		$data['type']="yqms";
		$data['username']=$info['username'];
		$data['jobname']=$info['jobname'];
		$data['company']=$com['name'];
		$data['linkman']=$com['linkman'];
		$data['comtel']=$com['linktel'];
		$data['comemail']=$com['linkmail'];
		$data['email']=$uid['email'];
		$data['moblie']=$uid['moblie'];
		
    $notice = $this->MODEL('notice');
    $notice->sendEmailType($data);
    $notice->sendSMSType($data);
	}
	function down_action()
	{
		if(!$_POST['userid']||!$_POST['eid']||!$_POST['uid'])
		{
			$data['error']=3;
			echo json_encode($data);die;
		}
		$_POST['eid']=(int)$_POST['eid'];
		$_POST['comid']=(int)$_POST['comid'];
		$_POST['uid']=(int)$_POST['uid'];
		$info=$this->obj->DB_select_once("down_resume","`eid`='".$_POST['eid']."' and `comid`='".$_POST['comid']."'");
		if(is_array($info) && !empty($info))
		{
			$data['error']=4;
		}else{

			/**验证权限**/
			$_POST = $this->CheckAppUser();
			/**验证权限结束**/ 
			$value="eid='".$_POST['eid']."',";
			$value.="uid='".$_POST['userid']."',";
			$value.="comid='".$_POST['uid']."',";
			$value.="downtime='".mktime()."'";
			$row=$this->obj->DB_select_once("company_statis","`uid`='".$_POST['uid']."'");
			if($row['vip_etime']>time() || $row['vip_etime']==0){
				if($row['rating_type']=="2"){
					$this->obj->DB_insert_once("down_resume",$value);
					$data['error']=1; 
				}elseif($row['down_resume']>0){
					$this->obj->DB_insert_once("down_resume",$value);
					$this->obj->DB_update_all("company_statis","`down_resume`=`down_resume`-1","`uid`='".$_POST['uid']."'");
					$data['error']=1;
				}else{
					if($this->config['com_integral_online']=="1")
					{
						if($row['integral']<$this->config['integral_down_resume'] && $this->config['integral_down_resume_type']=="2")
						{
							$data['error']=5;
						}else{
							$this->obj->DB_insert_once("down_resume",$value);
							if($this->config['integral_down_resume_type']=="2")
							{
								$auto=false;
							}else{
								$auto=true;
							}
							$this->MODEL('integral')->company_invtal($_POST['uid'],$this->config['integral_down_resume'],$auto,"下载简历");
							$this->obj->member_log("下载简历");
							$data['error']=1;
						}
					}else{
						$data['error']=2;
					}
				}
			}else{
				if($this->config['com_integral_online']=="1")
				{
					if($row['integral']<$this->config['integral_down_resume'] && $this->config['integral_down_resume_type']=="2")
					{
						$data['error']=5;
					}else{
						$this->obj->DB_insert_once("down_resume",$value);
						if($this->config['integral_down_resume_type']=="2")
						{
							$auto=false;
						}else{
							$auto=true;
						}
						$this->MODEL('integral')->company_invtal($_POST['uid'],$this->config['integral_down_resume'],$auto,"下载简历");
						$this->obj->member_log("下载简历");
						$data['error']=1;
					}
				}else{
					$data['error']=2;
				}
			}
		}
		echo json_encode($data);die;
	}
	function sqjob_action(){ 
		$uid=intval($_POST['uid']); 
		$limit=!$limit?10:$limit; 
		$where="`uid`='".$uid."'  order by `id` desc"; 
		if($page){
			$pagenav=($page-1)*$limit;
			$where.=" limit $pagenav,$limit";
		}else{
			$where.=" limit $limit";
		}
		$this->CheckAppUser(); 
		$rows=$this->obj->DB_select_all("userid_job",$where);
		if(is_array($rows)){
			$comid=array();
			foreach($rows as $k=>$v){
				$comid[]=$v['com_id'];
				$rows[$k]['job_name']=$v['job_name'];
			}
			$company=$this->obj->DB_select_all("company","`uid` in (".pylode(",",$comid).")","cityid,uid,name");
			include PLUS_PATH."/city.cache.php";
			foreach($rows as $k=>$v){
				foreach($company as $val){
					if($v['com_id']==$val['uid']){
						$rows[$k]['city']=$city_name[$val['cityid']];
                        $rows[$k]['com_name']=$val['name'];
					}
				}
			}
			$data['error']=1;
			$data['list']=$rows;
		}else{
			$data['error']=2;
		}
		echo json_encode($data);die;
	}
	function myinvite_action(){
		$uid=intval($_POST['uid']); 
		$limit=!$limit?10:$limit;  
		$where="`uid`='".$uid."'  order by `id` desc";  
		if($page){
			$pagenav=($page-1)*$limit;
			$where.=" limit $pagenav,$limit";
		}else{
			$where.=" limit $limit";
		}
		$this->CheckAppUser();  
		$rows=$this->obj->DB_select_all("userid_msg",$where);
		
		if(is_array($rows)&&$rows){
			$user=array();
			foreach($rows as $key=>$val){
				$user[$key]['id']=$val['id'];
				$user[$key]['is_browse']=$val['is_browse'];
				$user[$key]['linktel']=$val['linktel'];
				$user[$key]['linkman']=$val['linkman'];
				$user[$key]['intertime']=$val['intertime'];
				$user[$key]['address']=$val['address'];
				$user[$key]['fname']=$val['fname'];
				$user[$key]['jobname']=$val['jobname'];
			}
			$data['error']=1;
			$data['list']=$user;
		}else{
			$data['error']=2;
		}
		echo json_encode($data);die;
	}
	function inviteinfo_action(){
		$uid=intval($_POST['uid']); 
		$id=intval($_POST['id']); 
		$usertype=intval($_POST['usertype']);  
		$this->CheckAppUser();  
		$row=$this->obj->DB_select_once("userid_msg","`uid`='".$uid."' and `id`='".$id."'");
		if(is_array($row)&&$row){  
			$show=array(
				'jobid'=>$row['jobid'],	
				'fid'=>$row['fid'],
				'is_browse'=>$row['is_browse'],
				'jobname'=>$row['jobname'],
				'fname'=>$row['fname'],
				'intertime'=>$row['intertime'],
				'address'=>$row['address'],
				'linkman'=>$row['linkman'],
				'linktel'=>$row['linktel'],
				'content'=>$row['content']
			);
			$row['title']=$row['title'];
			$row['content']=$row['content'];
			$row['fname']=$row['fname'];
			$row['address']=$row['address'];
			$row['intertime']=$row['intertime'];
			$row['linkman']=$row['linkman'];
			$row['linktel']=$row['linktel'];
			$row['jobname']=$row['jobname'];

			$data['list']=$show;
			$data['error']=1; 
		}else{
			$data['error']=2;
		}
		echo json_encode($data);die;
	}
	function upinvite_action(){
		$uid=intval($_POST['uid']); 
		$id=intval($_POST['id']);  
		$type=intval($_POST['type']);  
		$this->CheckAppUser(); 
		$row=$this->obj->DB_select_once("userid_msg","`uid`='".$uid."' and `id`='".$id."'");
		if($row['id']){
			$this->obj->DB_update_all("userid_msg","`is_browse`='".$type."'","`id`='".$row['id']."'");
			$data['error']=1;
		}else{
			$data['error']=2;
		}
		echo json_encode($data);die;
	}
	
	function deluserjob_action(){
		$usertype=intval($_POST['usertype']);
		$id=intval($_POST['id']);
		$uid=intval($_POST['uid']); 
		if($usertype=='1'||$usertype=='2'){ 
			$this->CheckAppUser();
			if($usertype=='1'){
				$this->obj->DB_delete_all("userid_job","`uid`='".$uid."' and `id`='".$id."'"); 
			}else if($usertype=='2'){
				$this->obj->DB_delete_all("userid_job","`com_id`='".$uid."' and `id`='".$id."'");   
			} 
			$data['error']=1;
		}else{
			$data['error']=2;
		} 		 
		echo json_encode($data);die; 
	}
	function delinvite_action(){
		$usertype=intval($_POST['usertype']);
		$id=intval($_POST['id']);
		$uid=intval($_POST['uid']);
		if($usertype=='1'||$usertype=='2'){
			$this->CheckAppUser(); 
			if($usertype=='1'){
				$this->obj->DB_delete_all("userid_msg","`uid`='".$uid."' and `id`='".$id."'"); 
			}else if($usertype=='2'){
				$this->obj->DB_delete_all("userid_msg","`fid`='".$uid."' and `id`='".$id."'"); 
			} 
			$data['error']=1;
		}else{
			$data['error']=2;
		} 
		echo json_encode($data);die; 
	}
	
	
	function cominvite_action(){
		$uid=intval($_POST['uid']); 
		$limit=!$limit?10:$limit;  
		$where="`fid`='".$uid."'  order by `id` desc";  
		if($page){
			$pagenav=($page-1)*$limit;
			$where.=" limit $pagenav,$limit";
		}else{
			$where.=" limit $limit";
		}
		$this->CheckAppUser(); 
		$rows=$this->obj->DB_select_all("userid_msg",$where);
		if(is_array($rows)){
			foreach($rows as $v){
				$uid[]=$v['uid'];
			}
			$resume=$this->obj->DB_select_all("resume","`uid` in (".pylode(",",$uid).") and `r_status`<>'2'","`uid`,`name`,`exp`,`sex`,`edu`,`defaults` as `eid`");
			foreach($resume as $val){
				$eid[]=$val['eid'];
			}
			$expect=$this->obj->DB_select_all("resume_expect","`id` in (".pylode(",",$eid).")","`salary`,`id`,`job_classid`");
			if(is_array($resume)){
				$user=array();
				include(PLUS_PATH."user.cache.php");
				include(PLUS_PATH."job.cache.php");
				foreach($resume as $key=>$val){
					foreach($expect as $v){
						if($v['id']==$val['eid']){
							$user[$key]['salary']=$userclass_name[$v['salary']];
							if($v['job_classid']!=""){
								$job_classid=@explode(",",$v['job_classid']);
								$user[$key]['jobname']=$job_name[$job_classid[0]];
							}
						}
					}					
					$user[$key]['sex']=$val['sex'];
					$user[$key]['eid']=$val['eid'];
					$user[$key]['name']=$val['name'];
					$user[$key]['exp']=$userclass_name[$val['exp']];
					$user[$key]['edu']=$userclass_name[$val['edu']];
				}  
			} 
			$data['error']=1;
			$data['list']=$user;
		}else{
			$data['error']=2;
		}
		echo json_encode($data);die;
	}
	
	
	
}
?>