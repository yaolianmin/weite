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
class job_controller extends common{
	function list_action(){
        $time=time();
		$where = "`edate`>'$time' and `r_status`='1' and `status`='0'";
		$provinceid=(int)$_POST['provinceid'];
		$cityid=(int)$_POST['cityid'];
		$three_cityid=(int)$_POST['three_cityid'];
		$job1=(int)$_POST['job1'];
		$job1_son=(int)$_POST['job1_son'];
		$job_post=(int)$_POST['job_post'];
		$exp=(int)$_POST['exp'];
		$hy=(int)$_POST['hy'];
		$pr=(int)$_POST['pr'];
		$mun=(int)$_POST['mun'];
		$edu=$_POST['edu'];
		
		$jobid=$_POST['jobid'];
		$sdate=$_POST['sdate'];
		$edate=$_POST['edate'];
		$keyword=$this->stringfilter($_POST['keyword']);
		$page=$_POST['page'];
		$limit=$_POST['limit'];
		$order=$_POST['order'];
		$nodata=$_POST['nodata'];
		$uid=(int)$_POST['uid'];
		$state=(int)$_POST['state'];
		$rec = (int)$_POST['rec'];
		
		$limit=!$limit?10:$limit;
		if($_POST['state'])
		{
			if($_POST['state']==2)
			{
				$where.=" and `edate`<'".time()."'";
			}elseif($_POST['state']==4){
				$where.=" and `state`='0'";
			}else{
				$where.=" and `state`='".$_POST['state']."'";
			}
		}
		if($edu){
			$where.=" and `edu`='".$edu."'";
		}
		if($_POST['r_status'])
		{
			$where.=" and `r_status`='1'";
		}
		if($_POST['status'])
		{
			$where.=" and `status`='0'";
		}
		if($rec)
		{
			$where.="and `rec`='".$rec."'";	
		}
		if($hy){
			$where.=" and `hy`='".$hy."'";
		}
		if($pr){
			$where.=" and `pr`='".$pr."'";
		}
		if($mun){
			$where.=" and `mun`='".$mun."'";
		}
		if($exp){
			$where.=" and `exp`='".$exp."'";
		}
		if($provinceid){
			$where.=" and `provinceid`='".$provinceid."'";
		}
		if($cityid){
			$where.=" and `cityid`='".$cityid."'";
		}
		if($jobid){
			$where.=" and `id`<>'".$jobid."'";
		}
		if($three_cityid){
			$where.=" and `three_cityid`='".$three_cityid."'";
		}
		if($job1){
			$where.=" and `job1`='".$job1."'";
		}
		if($job1_son){
			$where.=" and `job1_son`='".$job1_son."'";
		}
		if($job_post){
			$where.=" and `job_post`='".$job_post."'";
		}
		if($sdate){
			$where.=" and `lastupdate`>'".strtotime($sdate)."'";
		}
		if($edate){
			$where.=" and `lastupdate`<'".strtotime($edate)."'";
		}
		if($keyword){
			$where1[]="`name` LIKE '%".$keyword."%'";
			$where1[]="`com_name` LIKE '%".$keyword."%'";
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
		if($nodata){
			$nodataarr=explode(",",$nodata);
			foreach($nodataarr as $v){
				$where.=" and ".$v."<>''";
			}
		}
		if($order){
			$where.=" order by ".$order;
		}else{
			$where.=" order by `lastupdate` desc";
		}
		if($page){
			$pagenav=($page-1)*$limit;
			$where.=" limit $pagenav,$limit";
		}else{
			$where.=" limit $limit";
		}
		$rows=$this->obj->DB_select_all("company_job",$where);
		if(is_array($rows)&&$rows){
			$uids=array();
			$jobids=array();
			foreach($rows as $key=>$va){
				$uids[]=$va['uid'];
				$jobids[]=$va['id'];
				$list[$key]['id']		=$va['id'];
				$list[$key]['name']		=$va['name'];
				$list[$key]['comid']	=$va['uid'];
				$list[$key]['comname']	=$va['com_name'];
				$list[$key]['hy']		=$va['hy'];
				$list[$key]['job1']		=$va['job1'];
				$list[$key]['job1_son']	=$va['job1_son'];
				$list[$key]['job_post']	=$va['job_post'];
				$list[$key]['provinceid']=$va['provinceid'];
				$list[$key]['cityid']	=$va['cityid'];
				$list[$key]['three_cityid']=$va['three_cityid'];
				$list[$key]['minsalary']	=$va['minsalary'];
				$list[$key]['maxsalary']	=$va['maxsalary'];
				$list[$key]['number']	=$va['number'];
				$list[$key]['exp']		=$va['exp'];
				$list[$key]['report']	=$va['report'];
				$list[$key]['edu']		=$va['edu'];
				$list[$key]['state']	=$va['state'];
				$list[$key]['sex']		=$va['sex'];
				$list[$key]['marriage']	=$va['marriage'];
				$list[$key]['description']=$va['description'];
				$list[$key]['xuanshang']=$va['xuanshang'];
				$list[$key]['sdate']	=$va['sdate'];
				$list[$key]['edate']	=$va['edate'];
				$list[$key]['jobhits']	=$va['jobhits'];
				$list[$key]['lastupdate']=$va['lastupdate'];
				$list[$key]['rec']		=$va['rec'];
				$list[$key]['cloudtype']=$va['cloudtype'];
				$list[$key]['statusbody']=$va['statusbody'];
				$list[$key]['age']		=$va['age'];
				$list[$key]['urgent_time']	=$va['urgent_time'];
				$list[$key]['rec_time']	=$va['rec_time'];
			}
			$com_rows=$this->obj->DB_select_all("company","`uid` in (".pylode(',',$uids).")","`uid`,`address`,`shortname`");
			$userid_job=$this->obj->DB_select_all("userid_job","`job_id` in (".pylode(',',$jobids).") group by `job_id`","count(`id`) as num,`job_id`");
			foreach($rows as $key=>$va){
				$list[$key]['userid_job']=0;
				foreach($com_rows as $comKey=>$comVal){
					if($va['uid']==$comVal['uid']){
						$list[$key]['address']=$comVal['address'];
						if($comVal['shortname']){
							$list[$key]['comname']	=$comVal['shortname'];
						}
					}
				}
				foreach($userid_job as $v){
					if($va['id']==$v['job_id']){
						$list[$key]['userid_job']=$v['num'];
					}
				}
			}

			if(isset($_POST['muid'])){
				$fav_rows=$this->obj->DB_select_all("fav_job","`uid` in (".$_POST['muid'].") and `job_id` in (".pylode(',',$jobids).")","`job_id`,`uid`,`com_id`");
				foreach($rows as $key=>$va){
					$list[$key]['is_fav']="2";
					foreach($fav_rows as $comKey=>$comVal){
						if($va['id']==$comVal['job_id']){
							$list[$key]['is_fav']="1";
						}
					}
				}
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

	function hotkey_action(){
		$type = (int)$_POST['type'];
		$where = "`tuijian`='1'";
		if($type){
			$where .=" and `type` =".$type." limit 16";
		}
		$rows = $this->obj->DB_select_all("hot_key",$where);
		if(is_array($rows)&&$rows){
			foreach($rows as $key=>$va){
				$list[$key]['id']		= $va['id'];
				$list[$key]['key_name'] = $va['key_name'];
				$list[$key]['num']		= $va['num'];
				$list[$key]['type']		= $va['type'];
				$list[$key]['size']		= $va['size'];
				$list[$key]['bold']		= $va['bold'];
				$list[$key]['color']	= $va['color'];
				$list[$key]['tuijian']	= $va['tuijian'];
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


	function comshow_action(){
		$id=(int)$_POST['id'];
		$uid=(int)$_POST['uid'];
		$usertype=(int)$_POST['usertype'];
		if(!$id){
			$data['error']=3;
			echo json_encode($data);die;
		}
		$row = $this->obj->DB_select_once("lt_job","`id`='".$id."'");
		if (is_array($row)) {
			$list=array();
			if($usertype=="1"){
				$useridjob=$this->obj->DB_select_once("userid_job","`uid`='".$uid."' and `job_id`='".$id."'");
				if($useridjob['id']){
					$list['useridjob']=1;
				}else{
					$list['useridjob']=0;
				}
			 
				$favjob=$this->obj->DB_select_once("fav_job","`uid`='".$uid."' and `job_id`='".$id."' and `type`='2' "); 
				if($favjob['id']){
					$list['favjob']=1;
				}else{
					$list['favjob']=0;
				}
			}
			$CacheM=$this->MODEL('cache');
			$CacheList=$CacheM->GetCache(array('city','com','hy','lt','ltjob','lthy'));
			$this->yunset($CacheList);
			$ltclass_name=$CacheList['ltclass_name'];
			
			$list['id']			=$row['id'];
			$list['job_name']	=$row['job_name'];
			
			$list['minsalary']	=$row['minsalary'];
			$list['maxsalary']	=$row['maxsalary'];
			
			$list['provinceid']	=$row['provinceid'];
			$list['cityid']		=$row['cityid'];
			$list['three_cityid']	=$row['three_cityid'];
			$list['exp']		=$row['exp'];
			$list['edu']		=$ltclass_name[$row['edu']];
			
			$list['job_desc']	=$row['job_desc'];

			$list['sex']		=$row['sex'];
			
		
			if($row['language']!=""){
				$language=@explode(",",$row['language']);
				foreach($language as $v){
					$language_arr[]=$ltclass_name[$v];
				}
				$row['language']=@implode(",",$language_arr);
				$list['language']	= $row['language'];
			}else{
				$list['language']	= "";
			}
			
			
			$list['department']	=$row['department'];
			$list['report']		=$row['report'];
			
			
			if($row['constitute']!=""){
				$constitute=@explode(",",$row['constitute']);
				foreach($constitute as $v){
					$const[]=$ltclass_name[$v];
				}
				$row['constitute']=@implode(",",$const);
				$list['constitute']	= $row['constitute'];
			}else{
				$list['constitute']	="";
			}
			
			
			if($row['welfare']!=""){
				$welfare=@explode(",",$row['welfare']);
				foreach($welfare as $v){
					$welfare_arr[]=$ltclass_name[$v];
				}
				$row['welfare']=@implode(",",$welfare_arr);
				$list['welfare']	= $row['welfare'];
			}else{
				$list['welfare']	= "";
			}
			
			$list['eligible']	=$row['eligible'];
			$list['desc']		=$row['desc'];
			
			
			$UserInfoM=$this->MODEL('userinfo');
			$com_info=$UserInfoM->GetUserinfoOne(array('uid'=>$row['uid']),array('usertype'=>2));
			if($com_info['logo']==''){
				$com_info['logo']=$this->config['sy_unit_icon'];
			}
			$list['logo']		=$com_info['logo'];			
			$list['comid']		=$row['uid'];
			if($com_info['shortname']){
				$list['com_name']	=$com_info['shortname'];
			}else{
				$list['com_name']	=$row['com_name'];
			}
			
			$list['pr']			=$row['pr'];
			$list['mun']		=$row['mun'];
			$list['hy']			=$row['hy'];
			
			
			$ComJobNum=$this->MODEL('job')->GetComjobNum(array('uid'=>$row['uid'],'r_status'=>1,'status'=>'0','state'=>1));
			$list['jobnum'] 	=$ComJobNum;
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
	function ltshow_action(){
		$id=(int)$_POST['id'];
		$uid=(int)$_POST['uid'];
		$usertype=(int)$_POST['usertype'];
		if(!$id){
			$data['error']=3;
			echo json_encode($data);die;
		}
		$row = $this->obj->DB_select_once("lt_job","`id`='".$id."'");
		if (is_array($row)) {
			$list=array();
			if($usertype=="1"){
				$useridjob=$this->obj->DB_select_once("userid_job","`uid`='".$uid."' and `job_id`='".$id."'");
				if($useridjob['id']){
					$list['useridjob']=1;
				}else{
					$list['useridjob']=0;
				}
			 
				$favjob=$this->obj->DB_select_once("fav_job","`uid`='".$uid."' and `job_id`='".$id."' and `type`='3' "); 
				if($favjob['id']){
					$list['favjob']=1;
				}else{
					$list['favjob']=0;
				}
			}
			$CacheM=$this->MODEL('cache');
			$CacheList=$CacheM->GetCache(array('city','com','hy','lt','ltjob','lthy'));
			$this->yunset($CacheList);
			$ltclass_name=$CacheList['ltclass_name'];
			
			$list['id']			=$row['id'];
			$list['job_name']	=$row['job_name'];
			
			$list['minsalary']	=$row['minsalary'];
			$list['maxsalary']	=$row['maxsalary'];
			
			$list['provinceid']	=$row['provinceid'];
			$list['cityid']		=$row['cityid'];
			$list['three_cityid']	=$row['three_cityid'];
			$list['exp']		=$row['exp'];
			$list['edu']		=$ltclass_name[$row['edu']];
			$list['job_desc']	=$row['job_desc'];
			$list['sex']		=$row['sex'];
			
			
			if($row['language']!=""){
				$language=@explode(",",$row['language']);
				foreach($language as $v){
					$language_arr[]=$ltclass_name[$v];
				}
				$row['language']=@implode(",",$language_arr);
				$list['language']	= $row['language'];
			}else{
				$list['language']	= "";
			}
			
			
			$list['department']	=$row['department'];
			$list['report']		=$row['report'];
			
			
			if($row['constitute']!=""){
				$constitute=@explode(",",$row['constitute']);
				foreach($constitute as $v){
					$const[]=$ltclass_name[$v];
				}
				$row['constitute']=@implode(",",$const);
				$list['constitute']	= $row['constitute'];
			}else{
				$list['constitute']	="";
			}
			
			
			if($row['welfare']!=""){
				$welfare=@explode(",",$row['welfare']);
				foreach($welfare as $v){
					$welfare_arr[]=$ltclass_name[$v];
				}
				$row['welfare']=@implode(",",$welfare_arr);
				$list['welfare']	= $row['welfare'];
			}else{
				$list['welfare']	= "";
			}
			
			$list['eligible']	=$row['eligible'];
			$list['desc']		=$row['desc'];
			
			
			$UserInfoM=$this->MODEL('userinfo');
			$Info=$UserInfoM->GetUserinfoOne(array('uid'=>$row['uid']),array('usertype'=>3));
			if($Info['photo']==''){
				$Info['photo']=$this->config['sy_lt_icon'];
			}
			$list['photo']		=$Info['photo'];			
			$list['lt_name']	=$Info['realname'];
			$list['lt_title']	=$ltclass_name[$Info['title']];
			$LietouJobNum=$this->MODEL('lietou')->GetLietoujobNum(array('uid'=>$row['uid'],"status"=>1,"`edate`>'".time()."' and `zp_status`='0' and `r_status`<>'2'"));
			$list['comid']		=$row['uid'];
			$list['com_name']	=$row['com_name'];
			$list['jobnum']		=$LietouJobNum;
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
	function show_action(){
		$id=(int)$_POST['id'];
		$uid=(int)$_POST['uid'];
		$usertype=(int)$_POST['usertype'];
		if(!$id){
			$data['error']=3;
			echo json_encode($data);die;
		}
		$row=$this->obj->DB_select_once("company_job","`id`='".$id."'");
		if(is_array($row)){
			$com=$this->obj->DB_select_once("company","`uid`='".$row['uid']."'");
			$this->obj->DB_update_all("company_job","`jobhits`=`jobhits`+1","`id`='".$id."'");
			
			$list=array();
			if($usertype=="1"){
				$look_job=$this->obj->DB_select_once("look_job","`uid`='".$uid."' and `jobid`='".$id."'");
				if(!empty($look_job)){
					$this->obj->DB_update_all("look_job","`datetime`='".time()."'","`uid`='".$uid."' and `jobid`='".$id."'");
				}else{
					$value.="`uid`='".$uid."',";
					$value.="`jobid`='".$id."',";
					$value.="`com_id`='".$row['uid']."',";
					$value.="`datetime`='".time()."'";
					$this->obj->DB_insert_once("look_job",$value);
				}
				$useridjob=$this->obj->DB_select_once("userid_job","`uid`='".$uid."' and `job_id`='".$id."'");
				if($useridjob['id']){
					$list['useridjob']=1;
				}else{
					$list['useridjob']=0;
				}
			 
				$favjob=$this->obj->DB_select_once("fav_job","`uid`='".$uid."' and `job_id`='".$id."' and `type`='1' "); 
				if($favjob['id']){
					$list['favjob']=1;
				}else{
					$list['favjob']=0;
				}
			}
			$list['id']		=$row['id'];
			$list['name']	=$row['name'];
			$list['comid']	=$row['uid'];
			if($com['shortname']){
				$list['comname']=$com['shortname'];
			}else{
				$list['comname']=$row['com_name'];
			}
			$list['pr']		=$row['pr'];
			$list['mun']	=$row['mun'];
			$list['hy']		=$row['hy'];
			$list['job1']	=$row['job1'];
			$list['job1_son']=$row['job1_son'];
			$list['job_post']=$row['job_post'];
			$list['provinceid']=$row['provinceid'];
			$list['cityid']	=$row['cityid'];
			$list['three_cityid']=$row['three_cityid'];
			$list['minsalary']	=$row['minsalary'];
			$list['maxsalary']	=$row['maxsalary'];
			$list['number']	=$row['number'];
			$list['exp']	=$row['exp'];
			$list['edu']	=$row['edu'];
			$list['state']	=$row['state'];
			$list['report']	=$row['report'];
			$list['sex']	=$row['sex'];
			$list['marriage']=$row['marriage'];
			$list['description']=$row['description'];
			$list['xuanshang']=$row['xuanshang'];
			$list['sdate']	=$row['sdate'];
			$list['edate']	=$row['edate'];
			$list['jobhits']=$row['jobhits'];
			$list['lastupdate']=$row['lastupdate'];
			$list['rec']	=$row['rec'];
			$list['cloudtype']=$row['cloudtype'];
			$list['statusbody']=$row['statusbody'];
			$list['age']	=$row['age'];
			$list['lang']=$row['lang']?$row['lang']:'';
			$list['welfare']=$row['welfare']?$row['welfare']:'';

			$list['is_link']	=$row['is_link'];
			$list['com_login_link']	=$this->config['com_login_link'];
			
			if($row['link_type']==1){
				$link=$this->obj->DB_select_once("company","`uid`='".$row['uid']."'");
				$list['linktel']	=$link['linktel'];
				$list['linkman']	=$link['linkman'];
			}else{
				$link=$this->obj->DB_select_once("company_job_link","`jobid`='".$row['id']."'");
				$list['linktel']	=$link['link_moblie'];
				$list['linkman']	=$link['link_man'];
			}
			$list['address']=$com['address'];
			$list['linkphone']=$com['linkphone'];
			$list['busstops']=$com['busstops'];

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

	function apply_action()
	{
		$_POST = $this->CheckAppUser();
		if(!$_POST['companyname']||!$_POST['companyuid']||!$_POST['eid']||!$_POST['jobid']||!$_POST['jobname']||!$_POST['uid'])
		{
			$data['error']=3;
			echo json_encode($data);die;
		}
		$jobid=(int)$_POST['jobid'];
		$uid=(int)$_POST['uid'];
		$user=$this->obj->DB_select_once("member","`uid`='".$uid."'");
		if($user['usertype']!="1")
		{
			$data['error']=6;
			echo json_encode($data);die;
		}
		
		$rows=$this->obj->DB_select_all("userid_job","`uid`='".$uid."' and `job_id`='".$jobid."'");
		if(is_array($rows)&&!empty($rows)){
			$data['error']=5;
			echo json_encode($data);die;
		}
		$value['job_id']=$jobid;
		$value['com_name']=$this->stringfilter($_POST['companyname']);
		$value['job_name']=$this->stringfilter($_POST['jobname']);
		$value['com_id']=$_POST['companyuid'];
		$value['uid']=$uid;
		$value['eid']=(int)$_POST['eid'];
		$value['datetime']=mktime();
		$nid=$this->obj->insert_into("userid_job",$value);
		if($nid)
		{
			$this->obj->DB_update_all("company_statis","`sq_job`=`sq_job`+1","`uid`='".$value['com_id']."'");
			$this->obj->DB_update_all("company_statis","`sq_jobnum`=`sq_jobnum`+1","`uid`='".$uid."'");
			$this->sqjobmsg($jobid,$value['com_id']);
			$this->obj->member_log("申请职位 ".$value['job_name']);
			$data['error']=1;
		}else{
			$data['error']=2;
		}
		echo json_encode($data);die;
	}
	function fav_action()
	{
		if(!$_POST['companyname']||!$_POST['companyuid']||!$_POST['jobid']||!$_POST['jobname']||!$_POST['uid'])
		{
			$data['error']=3;
			echo json_encode($data);die;
		}
		/**验证权限**/
		$_POST = $this->CheckAppUser();
		/**验证权限结束**/

		$user=$this->obj->DB_select_once("member","`uid`='".(int)$_POST['uid']."'");
		if($user['usertype']!="1")
		{
			$data['error']=4;
			echo json_encode($data);die;
		}
		$rows=$this->obj->DB_select_all("fav_job","`uid`='".(int)$_POST['uid']."' and `job_id`='".(int)$_POST['jobid']."'");
		if(is_array($rows)&&!empty($rows)){
			$data['error']=5;
			echo json_encode($data);die;
		}
		$value['job_id']=(int)$_POST['jobid'];
		$value['com_name']=$this->stringfilter($_POST['companyname']);
		$value['job_name']=$this->stringfilter($_POST['jobname']);
		if($_POST['type']){
			$value['type']=(int)$_POST['type'];
		}
		$value['com_id']=$_POST['companyuid'];
		$value['uid']=(int)$_POST['uid'];
		$value['datetime']=mktime();
		$nid=$this->obj->insert_into("fav_job",$value);
		if($nid){
			$this->obj->member_log("收藏职位 ".$value['job_name']);
			$data['error']=1;
		}else{
			$data['error']=2;
		}
		echo json_encode($data);die;
	}

	function zixun_action()
	{
		if(!$_POST['uid']||!$_POST['username']||!$_POST['jobid']||!$_POST['job_uid']||!$_POST['content']||!$_POST['com_name']||!$_POST['job_name'])
		{
			$data['error']=3;
			echo json_encode($data);die;
		}
		/**验证权限**/
		$_POST = $this->CheckAppUser();
		/**验证权限结束**/

		$sql['uid']=(int)$_POST['uid'];
		$sql['jobid']=(int)$_POST['jobid'];
		$sql['job_uid']=(int)$_POST['job_uid'];
		$sql['username']=$this->stringfilter($_POST['username']);
		$sql['content']=$this->stringfilter($_POST['content']);
		$sql['com_name']=$this->stringfilter($_POST['com_name']);
		$sql['job_name']=$this->stringfilter($_POST['job_name']);
		$sql['type']=1;
		$sql['datetime']=time();
		$id=$this->obj->insert_into("msg",$sql);
		if($id){
			$this->obj->member_log("发布求职咨询给职位 ".$sql['job_name']);
			$data['error']=1;
		}else{
			$data['error']=2;
		}
		echo json_encode($data);die;
	}
	function reply_zixun_action()
	{
		if(!$_POST['uid']||!$_POST['id']||!$_POST['content'])
		{
			$data['error']=3;
			echo json_encode($data);die;
		}

		/**验证权限**/
		$_POST = $this->CheckAppUser();
		/**验证权限结束**/

		$sql['reply']=$this->stringfilter($_POST['content']);
		$sql['reply_time']=time();
		$where['id']=(int)$_POST['id'];
		$where['job_uid']=(int)$_POST['uid'];
		$id=$this->obj->update_once("msg",$sql,$where);
		if($id){
			$this->obj->member_log("回复求职咨询");
			$data['error']=1;
		}else{
			$data['error']=2;
		}
		echo json_encode($data);die;
	}
	function get_jobnum_action()
	{
		$time = time();
		$where = "`sdate`<'$time' and `edate`>'$time' and `state`='1' and `r_status`<>'2' and `status`<>'1'";
		$num=$this->obj->DB_select_num("company_job",$where);
		$data['num']=$num;
		echo json_encode($data);die;
	}
}
?>