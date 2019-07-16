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
class part_controller extends common{
	function list_action(){
        $time=time();
		$where = "`deadline`>'".$time."' and `state`='1'";
		$page=intval($_POST['page']);
		$cycle=$_POST['cycle'];
		$provinceid=$_POST['provinceid'];
		$cityid=$_POST['cityid'];
		$three_cityid=$_POST['three_cityid'];
		$uid=$_POST['uid'];
		$type=$_POST['type'];
		$keyword=$_POST['keyword']; 
		$limit=!$limit?10:$limit; 
		if($uid){
			$where.=" and `uid`='".$uid."'";
		}
		if($provinceid){
			$where.=" and `provinceid`='".$provinceid."'";
		}
		if($cityid){
			$where.=" and `cityid`='".$cityid."'";
		}
		if($three_cityid){
			$where.=" and `three_cityid`='".$three_cityid."'";
		} 
		if($cycle){
			$where.=" and `billing_cycle`='".$cycle."'";
		} 
		if($type){
			$where.=" and `type`='".$type."'";
		} 
		if($keyword){
			$where.=" and `name` like '%".$keyword."%'";
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
		$rows=$this->obj->DB_select_all("partjob",$where);
		if(is_array($rows)){
			include(PLUS_PATH."city.cache.php");
			include(PLUS_PATH."part.cache.php");
			$list=$comuids=array();
			foreach($rows as $v){
				$comuids[]=$v['uid'];
			}
			$com=$this->obj->DB_select_all("company","`uid` in(".pylode(',',$comuids).")","`uid`,`shortname`");
				
			foreach($rows as $key=>$va){ 	
				$list[$key]['id']		=$va['id']; 
				$list[$key]['salary']		=$va['salary'];
				$list[$key]['salary_type']		=$va['salary_type'];
				$list[$key]['billing_cycle']		=$va['billing_cycle'];
				$list[$key]['type']		=$va['type'];
				$list[$key]['deadline']		=$va['deadline'];
				$list[$key]['lastupdate']		=$va['lastupdate'];
				$list[$key]['provinceid']		=$city_name[$va['provinceid']];
				$list[$key]['cityid']		=$city_name[$va['cityid']];
				$list[$key]['three_cityid']		=$city_name[$va['three_cityid']];
				$list[$key]['name']		=$va['name'];
				if($com){
					foreach($com as $val){
						if($val['uid']==$va['uid']&&$val['shortname']){
							$list[$key]['com_name']	 =mb_substr($val['shortname'], 0,14,'utf-8');
						}else{
							$list[$key]['com_name']	 =mb_substr($va['com_name'], 0,14,'utf-8');
						}
					}
				}else{
					$list[$key]['com_name']		=$va['com_name'];
				}
				
				
				$list[$key]['x']		=$va['x']; 
				$list[$key]['y']		=$va['y'];  
			}   
			$data['list']=count($list)?$list:array();
			$data['error']=1;
		}else{
			$data['error']=2;
		}
		echo json_encode($data);die;
	}
	function getclass_action(){
		$rows=$this->obj->DB_select_all("partclass","1 order by `sort` asc");
		if($rows&&is_array($rows)){
			$parent=array();
			foreach($rows as $val){
				if($val['keyid']=='0'){
					$parent[$val['id']]=array('id'=>$val['id'],'name'=>$val['name'],'variable'=>$val['variable']);
				} 
			}
			foreach($rows as $val){
				foreach($parent as $key=>$v){
					if($val['keyid']==$v['id']){
						$parent[$key]['son'][]=array('id'=>$val['id'],'name'=>$val['name']);
					}
				}
			}
		}
		echo json_encode($parent);die;
	}
	function show_action(){
		$id=(int)$_POST['id'];
		$usertype=(int)$_POST['usertype'];
		$uid=(int)$_POST['uid'];
		$sy_part_web=(int)$this->config['sy_part_web'];
		if($sy_part_web=='2'){
			$data['error']=4;
			echo json_encode($data);die;
		}
		if(!$id){
			$data['error']=3;
			echo json_encode($data);die;
		}
		$row=$this->obj->DB_select_once("partjob","`id`='".$id."'");
		if($uid!=$row['uid']){
			if($row['state']!='1'||$row['deadline']<time()){
				unset($row);
			}
		} 
		if(is_array($row)&&$row){ 
			include(PLUS_PATH."city.cache.php");
			$this->obj->DB_update_all("partjob","`id`='".$id."' and `state`='1' and `deadline`>'".time()."'");
			$list=array();  
			if($usertype=="1"){
				$apply=$this->obj->DB_select_once("part_apply","`uid`='".$uid."' and `jobid`='".$id."'");
				if($apply['id']){
					$list['apply']=1;
				}else{
					$list['apply']=0;
				}
				$collect=$this->obj->DB_select_once("part_collect","`uid`='".$uid."' and `jobid`='".$id."'");
				if($collect['id']){
					$list['collect']=1;
				}else{
					$list['collect']=0;
				}
			}
			$list['id']		=$row['id'];
			$list['comid']	=$row['uid'];
			$list['cityid']	=$row['cityid'];
			$list['hits']	=$row['hits'];
			$list['lastupdate']	=$row['lastupdate'];
			$list['salary']	=$row['salary'];
			$list['salary_type']	=$row['salary_type'];
			$list['type']	=$row['type'];
			$list['number']	=$row['number'];
			$list['sex']	=$row['sex'];
			$list['sdate']	=$row['sdate'];
			$list['edate']	=$row['edate'];
			$list['deadline']	=$row['deadline'];
			$list['x']	=$row['x'];
			$list['y']	=$row['y'];
			$list['name']	=$row['name'];
			$com=$this->obj->DB_select_once("company","`uid`='".$uid."'","`uid`,`shortname`");
			if($com['shortname']){
				$list['com_name']	=$com['shortname'];
			}else{
				$list['com_name']	=$row['com_name'];
			}
			$list['worktime']	=$row['worktime'];
			$list['billing_cycle']	=$row['billing_cycle'];
			$list['address']	=$row['address'];
			$list['content']	=$row['content'];
			$list['linkman']	=$row['linkman'];
			$list['linktel']	=$row['linktel'];
			$list['provinceid']	=$row['provinceid']; 
			$list['cityid']	=$row['cityid']; 
			$list['three_cityid']	=$row['three_cityid']; 
			
			 
				
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

	function apply_action(){
		$_POST = $this->CheckAppUser(); 
		$com_resume_partapply=(int)$_POST['com_resume_partapply'];
		$usertype=(int)$_POST['usertype'];
		$jobid=(int)$_POST['jobid'];
		$uid=(int)$_POST['uid'];
		$user=$this->obj->DB_select_once("member","`uid`='".$uid."'",'usertype');
		if($user['usertype']!="1"){
			$data['error']=6;
			echo json_encode($data);die;
		}
		if($com_resume_partapply=='1'){
			$arr=$this->obj->DB_select_once("resume_expect","`uid`='".$uid."'",'id');
			if($arr['id']<1){
				$data['error']=3;
				echo json_encode($data);die;
			}
		}
		
		$jobinfo=$this->obj->DB_select_once("partjob","`id`='".$jobid."'","deadline,uid,name");
		if($jobinfo['deadline']<time()&&$jobinfo['deadline']>1){
			$data['error']=4;
			echo json_encode($data);die;
		}
		$row=$this->obj->DB_select_once("part_apply","`jobid`='".$jobid."' and `uid`='".$uid."'","id");
		if($row['id']){
			$data['error']=5;
			echo json_encode($data);die;
		}
		$appdata=array(
			'uid'=>$uid,
			'jobid'=>$jobid,
			'comid'=>$jobinfo['uid'],
			'ctime'=>time()
		);
		$nid=$this->obj->insert_into("part_apply",$appdata);
		if($nid){
			$info=$this->obj->DB_select_once("member","`uid`='".$uid."'");
			$fdata=$this->forsend(array("uid"=>$uid,"usertype"=>1));
			$sdata['type']="apply";
			$sdata['name']=$fdata['name'];
			$sdata['uid']=$info['uid'];
			$sdata['username']=$info['username'];
			$sdata['email']=$info['email'];
			$sdata['moblie']=$info['moblie'];
			$sdata['jobname']=$jobinfo['jobname'];

      $notice = $this->MODEL('notice');
      $notice->sendEmailType($sdata);
      $notice->sendSMSType($sdata);
			$this->obj->member_log("申请职位 ".$jobinfo['jobname']);
			$data['error']=1;
		}else{
			$data['error']=2;
		}
		echo json_encode($data);die;
	}
	function fav_action(){
		$usertype=intval($_POST['usertype']);
		$uid=intval($_POST['uid']);
		if($usertype!=1){
			$data['error']=4;
			echo json_encode($data);die;
		}else{
			/**验证权限**/
			$_POST = $this->CheckAppUser();
			/**验证权限结束**/
			
			$row=$this->obj->DB_select_once("part_collect","`uid`='".$uid."' and `jobid`='".(int)$_POST['jobid']."'");
			if(!empty($row)){
				$data['error']=5;
				echo json_encode($data);die;
			}else{
				$value=array(
					'uid'=>$uid,
					'jobid'=>(int)$_POST['jobid'],
					'comid'=>(int)$_POST['comid'],
					'ctime'=>time()
				); 
				$nid=$this->obj->insert_into("part_collect",$value); 
				if($nid){
					$this->obj->member_log("收藏兼职 ".$value['job_name']);
					$data['error']=1;
				}else{
					$data['error']=2;
				}
				echo json_encode($data);die;
			}
		} 
	}
	function favlist_action(){
		$uid=intval($_POST['uid']); 
		$this->CheckAppUser();
		$limit=!$limit?10:$limit; 
		$where="`uid`='".$uid."'  order by `id` desc"; 
		if($page){
			$pagenav=($page-1)*$limit;
			$where.=" limit $pagenav,$limit";
		}else{
			$where.=" limit $limit";
		}
		$rows=$this->obj->DB_select_all("part_collect",$where);
		if($rows&&is_array($rows)){
			$comid=$jobids=array();
			foreach($rows as $val){ 
				$jobids[]=$val['jobid'];
			}
			$joblist=$this->obj->DB_select_all("partjob","`id` in(".pylode(',',$jobids).")","`id`,`name`,`cityid`,`com_name`,`salary`,`salary_type`,`provinceid`,`three_cityid`,`state`,`x`,`y`"); 
			include(PLUS_PATH."city.cache.php");
			include(PLUS_PATH."com.cache.php");
			include(PLUS_PATH."part.cache.php");
			foreach($rows as $key=>$val){
				foreach($joblist as $v){
					if($val['jobid']==$v['id']){
						$rows[$key]['x']=$v['x'];
						$rows[$key]['y']=$v['y'];
						$rows[$key]['state']=$v['state'];
						$rows[$key]['salary']=$v['salary'];
						$rows[$key]['salary_type']=$partclass_name[$v['salary_type']];
						$rows[$key]['job_name']=$v['name'];
						$rows[$key]['city']=$city_name[$v['cityid']];
						$rows[$key]['province']=$city_name[$v['provinceid']];
						$rows[$key]['three_city']=$city_name[$v['three_cityid']];
						$rows[$key]['com_name']=$v['com_name'];
					}
				}
			} 
			$data['list']=$rows;
			$data['error']=1;
		}else{
			$data['error']=2;
		}
		echo json_encode($data);die; 
	}
	function delfavpart_action(){
		$usertype=intval($_POST['usertype']);
		$id=intval($_POST['id']);
		$uid=intval($_POST['uid']);
		if($usertype!=1){
			$data['error']=2;
			echo json_encode($data);die;
		}else{
			$_POST = $this->CheckAppUser();
			$this->obj->DB_delete_all("part_collect","`uid`='".$uid."' and `id`='".$id."'");
			$data['error']=1;
			echo json_encode($data);die;
		}
	}
	function applylist_action(){
		$uid=intval($_POST['uid']); 
		$usertype=intval($_POST['usertype']); 
		$this->CheckAppUser();
		$limit=!$limit?10:$limit; 
		if($usertype=='1'){
			$where="`uid`='".$uid."'  order by `id` desc"; 
		}else{
			$where="`comid`='".$uid."'  order by `id` desc"; 
		} 
		if($page){
			$pagenav=($page-1)*$limit;
			$where.=" limit $pagenav,$limit";
		}else{
			$where.=" limit $limit";
		}
		$rows=$this->obj->DB_select_all("part_apply",$where);
		if($rows&&is_array($rows)){ 
			include PLUS_PATH."/city.cache.php";
			include PLUS_PATH."/user.cache.php";
			$jobids=$uids=array();
			foreach($rows as $val){
				$jobids[]=$val['jobid'];
				$uids[]=$val['uid'];
			}
			$joblist=$this->obj->DB_select_all("partjob","`id` in(".pylode(',',$jobids).")","`id`,`name`,`cityid`,`com_name`,`x`,`y`");
			$resume=$this->obj->DB_select_all("resume","`uid` in(".pylode(',',$uids).")","`uid`,`name`,`sex`,`edu`,`birthday`,`telphone`");
			foreach($rows as $key=>$val){
				foreach($joblist as $v){
					if($val['jobid']==$v['id']){
						$rows[$key]['x']=$v['x'];
						$rows[$key]['y']=$v['y'];
						$rows[$key]['job_name']=$v['name'];
						$rows[$key]['city']=$city_name[$v['cityid']];
						$rows[$key]['com_name']=$v['com_name']; 
					}
				}
				foreach($resume as $v){
					if($val['uid']==$v['uid']){
						if($v['birthday']){
							$birthday=@explode('-',$v['birthday']);
							$rows[$key]['age']=date("Y")-$birthday[0];
						}
						$rows[$key]['telphone']=$v['telphone'];
						$rows[$key]['name']=$v['name'];
						$rows[$key]['edu']=$userclass_name[$v['edu']];
						$rows[$key]['sex']=$v['sex'];
					}
				}
			} 
			$data['list']=$rows;
			$data['error']=1;
		}else{
			$data['error']=2;
		}
		echo json_encode($data);die; 
	}
	function delapply_action(){
		$usertype=intval($_POST['usertype']);
		$id=intval($_POST['id']);
		$uid=intval($_POST['uid']);  
		$this->CheckAppUser();
		if($usertype=='1'){
			$this->obj->DB_delete_all("part_apply","`uid`='".$uid."' and `id`='".$id."'");
		}else{
			$this->obj->DB_delete_all("part_apply","`comid`='".$uid."' and `id`='".$id."'");
		} 
		$data['error']=1;
		echo json_encode($data);die; 
	}
	function delpart_action(){
		$usertype=intval($_POST['usertype']);
		$id=intval($_POST['id']);
		$uid=intval($_POST['uid']);
		if($usertype=='2'){
			$this->CheckAppUser(); 
			$this->obj->DB_delete_all("partjob","`uid`='".$uid."' and `id`='".$id."'"); 
			$data['error']=1;
		}else{
			$data['error']=2;
		} 
		echo json_encode($data);die; 
	}
	function partlist_action(){
		$this->CheckAppUser(); 
		$uid=intval($_POST['uid']); 
		$limit=!$limit?10:$limit; 
		$where="`uid`='".$uid."'  order by `id` desc"; 
		if($page){
			$pagenav=($page-1)*$limit;
			$where.=" limit $pagenav,$limit";
		}else{
			$where.=" limit $limit";
		}

		$rows=$this->obj->DB_select_all("partjob",$where);
		if($rows&&is_array($rows)){ 
			$list=$jobids=array();
			foreach($rows as $v){
				$jobids[]=$v['id'];
			}
			$applys=$this->obj->DB_select_all("part_apply","`jobid` in(".pylode(',',$jobids).") group by `jobid` desc","count(id) as num,jobid");
			foreach($rows as $k=>$v){
				$list[$k]['applynum']=0;
				foreach($applys as $val){
					if($val['jobid']==$v['id']){
						$list[$k]['applynum']=$val['num'];
					}
				} 
				$list[$k]['id']=$v['id'];
				$list[$k]['state']=$v['state'];
				$list[$k]['hits']=$v['hits']; 
				$list[$k]['edate']=$v['edate'];
				$list[$k]['lastupdate']=$v['lastupdate'];
				$list[$k]['deadline']=$v['deadline'];
				
				$list[$k]['x']=$v['x'];
				$list[$k]['y']=$v['y'];
				  
				$list[$k]['name']=$v['name'];
				$list[$k]['edate']=$v['edate'];
			}
			$data['list']=$list;
			$data['error']=1;
		}else{
			$data['error']=2;
		}
		echo json_encode($data);die; 
	}
	
	function partedit_action(){
		$id=intval($_POST['id']);
		$uid=intval($_POST['uid']);
		$this->CheckAppUser(); 
		if($id){
			$row=$this->obj->DB_select_once("partjob","`uid`='".$uid."' and `id`='".$id."'");
			$row['name']=$row['name'];
			$row['address']=$row['address'];
			$row['content']=$row['content'];
			$row['linkman']=$row['linkman'];
			$row['com_name']=$row['com_name'];
			
			$data['list']=$row;
			$data['error']=1;
		}else{
			$data['error']=2;
		}
		echo json_encode($data);die; 
	}
	function partsave_action(){ 
		if(!$_POST['name']||!$_POST['type']||!$_POST['number']||!$_POST['address']||!$_POST['worktime']||!$_POST['linkman']||!$_POST['linktel']){
			$arr['error']='3';
			echo json_encode($arr);die;
		} 

		$this->CheckAppUser(); 
		$uid=intval($_POST['uid']);
		$id=intval($_POST['id']);
		
		$data=array(
			'name'=>$this->stringfilter($_POST['name']),
			'type'=>intval($_POST['type']),
			'sdate'=>strtotime($_POST['sdate']),
			'edate'=>strtotime($_POST['edate']),
			'deadline'=>strtotime($_POST['deadline']),
			'number'=>intval($_POST['number']),
			'sex'=>intval($_POST['sex']),
			'salary'=>intval($_POST['salary']),
			'salary_type'=>intval($_POST['salary_type']),
			'billing_cycle'=>intval($_POST['billing_cycle']),
			'provinceid'=>intval($_POST['provinceid']),
			'cityid'=>intval($_POST['cityid']),
			'three_cityid'=>intval($_POST['three_cityid']),
			'worktime'=>$this->stringfilter($_POST['worktime']),
			'address'=>$this->stringfilter($_POST['address']),
			'x'=>$this->stringfilter($_POST['x']),
			'y'=>$this->stringfilter($_POST['y']),
			'content'=>$this->stringfilter($_POST['content']),
			'linkman'=>$this->stringfilter($_POST['linkman']),
			'linktel'=>$this->stringfilter($_POST['linktel']),
			'state'=>$this->config['com_partjob_status'],
			'lastupdate'=>time()
		);
		
		if($id){			
			$info=$this->obj->DB_select_once("partjob","`uid`='".$uid."' and `id`='".$id."'","`id`");
		} 
		if($info['id']){
			$nid=$this->obj->update_once("partjob",$data,array("id"=>$info['id']));
		}else{
			$company=$this->obj->DB_select_once("company","`uid`='".$uid."'","`name`,`did`");
			$data['com_name']=$company['name'];
			$data['did']=$company['did'];
			$data['uid']=$uid;
			$data['addtime']=time();
			$nid=$this->obj->insert_into("partjob",$data);
		}
		if($nid){
			$arr['id']=$nid;
			if($this->config['com_partjob_status']=="1"){
				$arr['error']='1';
			}else{
				$arr['error']='4';
			}
		}else{
			$arr['error']='2';
		}
		echo json_encode($arr);die;
	}
}
?>