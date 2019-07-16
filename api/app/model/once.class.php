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
class once_controller extends common{
	function list_action(){
		$where="status='1' and `edate`>'".time()."'";
		$sdate=$_POST['sdate'];
		$edate=$_POST['edate'];
		$keyword=$this->stringfilter($_POST['keyword']);
		$page=$_POST['page'];
		$limit=$_POST['limit'];
		$order=$_POST['order'];
		$nodata=$_POST['nodata'];
		$limit=!$limit?10:$limit;
		if($sdate){
			$where.=" and `ctime`>'".strtotime($sdate)."'";
		}
		if($edate){
			$where.=" and `ctime`<'".strtotime($edate)."'";
		}
		if($keyword){
			$where.=" and `title` like '%".$keyword."%'";
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
			$where.=" order by id desc";
		}
		if($page){
			$pagenav=($page-1)*$limit;
			$where.=" limit $pagenav,$limit";
		}else{
			$where.=" limit $limit";
		}
		$rows=$this->obj->DB_select_all("once_job",$where);
		if(is_array($rows)){
			foreach($rows as $key=>$va){
				$list[$key]['id']			=$va['id'];
				$list[$key]['title']		=$va['title'];
				$list[$key]['provinceid']	=$va['provinceid'];
				$list[$key]['cityid']		=$va['cityid'];
				$list[$key]['three_cityid']	=$va['three_cityid'];
				$list[$key]['require']		=$va['require'];
				
				$list[$key]['phone']		=$va['phone'];
				$list[$key]['linkman']		=$va['linkman'];
				
				$list[$key]['mans']			=$va['mans'];
				$list[$key]['companyname']	=$va['companyname'];
				$list[$key]['address']		=$va['address'];
				$list[$key]['time']			=$va['ctime'];
				$list[$key]['user_wzp_link']=$this->config['user_wzp_link'];
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
	function show_action(){
		$id=(int)$_POST['id'];
		if(!$id){
			$data['error']=3;
			echo json_encode($data);die;
		}
		$row=$this->obj->DB_select_once("once_job","`id`='".$id."' and `edate`>'".time()."'");
		if(is_array($row)){
			$list['id']	=$row['id'];
			$list['title']	=$row['title'];
			$list['hits']	=intval($row['hits']);
			$list['mans']	=$row['mans'];
			$list['require']=$row['require'];
			$list['phone']	=$row['phone'];
			$list['provinceid'] = $row['provinceid'];
			$list['cityid']		=$row['cityid'];
			$list['three_cityid'] = $row['three_cityid'];
			$list['companyname']	=$row['companyname'];
			$list['linkman']=$row['linkman'];
			$list['address']=$row['address'];
			$list['time']	=$row['ctime'];
			$list['edate']	=$row['edate'];
			$list['user_wzp_link']=$this->config['user_wzp_link'];
			$list['days']	=intval((intval($row['edate'])-time())/86400).'';
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

	function add_action(){
		if(!$_POST['title'] || !$_POST['companyname'] || !$_POST['linkman'] || !$_POST['phone'] || !$_POST['require'] || !$_POST['provinceid']|| !$_POST['cityid'] || !$_POST['edate'] ){
			$data['error']=3;
			echo json_encode($data);die;
		} 
		$sql['title']=$this->stringfilter($_POST['title']);
		$sql['companyname']=$this->stringfilter($_POST['companyname']);
		$sql['linkman']=$this->stringfilter($_POST['linkman']);	
		$sql['phone']=$this->stringfilter($_POST['phone']);
		$sql['require']=$this->stringfilter($_POST['require']);
		$sql['provinceid']=(int)$_POST['provinceid'];
		$sql['cityid']=(int)$_POST['cityid'];
		$sql['three_cityid']=(int)$_POST['three_cityid'];
		$sql['ctime']=mktime();
		$sql['edate']=strtotime($_POST['edate']);
		$sql['password']=md5($_POST['password']);
		$sql['status']=$this->config['com_fast_status'];
		$nid=$this->obj->insert_into("once_job",$sql);
		if($nid){
			$data['id']=$nid;
			if($this->config['com_fast_status']=="1"){
				$data['error']=1;
			}else{
				$data['error']=4;
			}
		}else{
			$data['error']=2;
		}
		echo json_encode($data);die;
	}
	function edit_action(){
		if(!$_POST['title'] || !$_POST['id']|| !$_POST['require'] || !$_POST['companyname'] || !$_POST['phone'] || !$_POST['linkman'] || !$_POST['password']){
			$data['error']=3;
			echo json_encode($data);die;
		}
		$row=$this->obj->DB_select_once("once_job","`id`='".(int)$_POST['id']."'","password");

		if($row['password']!=md5($_POST['password'])){
			$data['error']=5;
			echo json_encode($data);die;
		}
		$sql=array();
		$sql['title']=$this->stringfilter($_POST['title']);
		$sql['companyname']=$this->stringfilter($_POST['companyname']);
		$sql['require']=$this->stringfilter($_POST['require']);
		$sql['phone']=$this->stringfilter($_POST['phone']);
		$sql['linkman']=$this->stringfilter($_POST['linkman']);
		$sql['edate']=strtotime($_POST['edate']);
		$sql['status']=$this->config['com_fast_status'];
		$nid=$this->obj->update_once("once_job",$sql,array("id"=>(int)$_POST['id']));
		if($nid){
			$data['id']=$nid;
			if($this->config['com_fast_status']=="1"){
				$data['error']=1;
			}else{
				$data['error']=4;
			}
		}else{
			$data['error']=2;
		}
		echo json_encode($data);die;
	}
	function pass_action(){
		$id=(int)$_POST['id'];
		if(!$_POST['password'] || !$id){
			$data['error']=3;
			echo json_encode($data);die;
		}
		$row=$this->obj->DB_select_once("once_job","`id`='".$id."'","password");
		if(is_array($row)){
			if($row['password']==md5($_POST['password'])){
				$data['error']=1;
			}else{
				$data['error']=2;
			}
		}else{
			$data['error']=4;
		}
		echo json_encode($data);die;
	}
	function del_action(){
		$id=(int)$_POST['id'];
		if(!$_POST['password'] || !$id){
			$data['error']=3;
			echo json_encode($data);die;
		}
		$row=$this->obj->DB_select_once("once_job","`id`='".$id."'","password");
		if(is_array($row)){
			if($row['password']==md5($_POST['password'])){
				$id=$this->obj->DB_delete_all("once_job","`id`='".$id."'");
				$data['error']=1;
			}else{
				$data['error']=2;
			}
		}else{
			$data['error']=4;
		}
		echo json_encode($data);die;
	}

	function editctime_action(){
		$id=(int)$_POST['id'];
		if(!$_POST['password'] || !$id){
			$data['error']=3;
			echo json_encode($data);die;
		}
		$row=$this->obj->DB_select_once("once_job","`id`='".$id."'","password");
		if(is_array($row)){
			if($row['password']==md5($_POST['password'])){
				$nid=$this->obj->DB_update_all("once_job","`ctime`='".mktime()."'","`id`='".$id."'");
				if($nid){
					$data['error']=1;
				}else{
					$data['error']=5;
				}
			}else{
				$data['error']=2;
			}
		}else{
			$data['error']=4;
		}
		echo json_encode($data);die;
	}

}
?>