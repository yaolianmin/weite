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
class tiny_controller extends common{
	function list_action(){
		$where="status='1'";
		$sdate=$_POST['sdate'];
		$edate=$_POST['edate'];
		$keyword=$this->stringfilter($_POST['keyword']);
		$page=$_POST['page'];
		$sex=(int)$_POST['sex'];
		$edu=(int)$_POST['edu'];
		$limit=$_POST['limit'];
		$order=$_POST['order'];
		$nodata=$_POST['nodata'];
		$limit=!$limit?10:$limit;
		if($sex){
			$where.=" and `sex`='".$sex."'";
		}
		if($edu){
			$where.=" and `edu`='".$edu."'";
		}
		if($sdate){
			$where.=" and `time`>'".strtotime($sdate)."'";
		}
		if($edate){
			$where.=" and `time`<'".strtotime($edate)."'";
		}
		if($keyword){
			$where.=" and `username` like '%".$keyword."%'";
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
		$rows=$this->obj->DB_select_all("resume_tiny",$where);
		if(is_array($rows)){
			foreach($rows as $key=>$va){
				$list[$key]['id']		=$va['id'];
				$list[$key]['name']		=$va['username'];
				$list[$key]['sex']		=$va['sex'];
				$list[$key]['exp']		=$va['exp'];
				$list[$key]['job']		=$va['job'];
				$list[$key]['mobile']	=$va['mobile'];
				$list[$key]['production']=$va['production'];
				$list[$key]['time']		=$va['time'];
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
		$row=$this->obj->DB_select_once("resume_tiny","`id`='".$id."'");
		if(is_array($row)){
			include(PLUS_PATH."city.cache.php");
			$list=array();
			$list['id']		=$row['id'];
			$list['hits']		=$row['hits'];
			$list['name']	=$row['username'];
			$list['sex']	=$row['sex'];
			$list['exp']	=$row['exp'];
			$list['job']	=$row['job'];
			$list['mobile']	=$row['mobile'];
			$list['production']	=$row['production'];
			$list['time']	=$row['time'];
			$list['user_wjl_link']	=$this->config['user_wjl_link'];
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
		if(!$_POST['name'] || !$_POST['password'] || !$_POST['sex'] || !$_POST['exp'] || !$_POST['mobile'] || !$_POST['production'] || !$_POST['job']){
			$data['error']=3;
			echo json_encode($data);die;
		}
		$sql['username']=$this->stringfilter($_POST['name']);
		$sql['password']=md5($_POST['password']);
		$sql['job']=$this->stringfilter($_POST['job']);
		$sql['production']=$this->stringfilter($_POST['production']);
		$sql['sex']=intval($_POST['sex']);
		$sql['exp']=intval($_POST['exp']);
		$sql['mobile']=$_POST['mobile'];
		$sql['status']=$this->config['user_wjl'];
		$sql['time']=time();
		$nid=$this->obj->insert_into("resume_tiny",$sql);
		if($nid){
			$data['id']=$nid;
			if($this->config['user_wjl']=="1"){
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
		if(!$_POST['name'] || !$_POST['id'] || !$_POST['sex'] || !$_POST['exp'] || !$_POST['mobile'] || !$_POST['production'] || !$_POST['job']){
			$data['error']=3;
			echo json_encode($data);die;
		}
		$sql['username']=$this->stringfilter($_POST['name']);
		if($_POST['password']!=""){
			$sql['password']=md5($_POST['password']);
		}
		$sql['job']=$this->stringfilter($_POST['job']);
		$sql['production']=$this->stringfilter($_POST['production']);
		$sql['sex']=$_POST['sex'];
		$sql['exp']=$_POST['exp'];
		$sql['mobile']=$_POST['mobile'];
		$sql['status']=$this->config['user_wjl'];
		$nid=$this->obj->update_once("resume_tiny",$sql,array("id"=>(int)$_POST['id']));
		if($nid){
			$data['id']=$nid;
			if($this->config['user_wjl']=="1"){
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
		$row=$this->obj->DB_select_once("resume_tiny","`id`='".$id."'","password");
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
		$row=$this->obj->DB_select_once("resume_tiny","`id`='".$id."'","password");
		if(is_array($row)){
			if($row['password']==md5($_POST['password'])){
				$id=$this->obj->DB_delete_all("resume_tiny","`id`='".$id."'");
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
		$row=$this->obj->DB_select_once("resume_tiny","`id`='".$id."'","password");
		if(is_array($row)){
			if($row['password']==md5($_POST['password'])){
				$nid=$this->obj->DB_update_all("resume_tiny","`time`='".mktime()."'","`id`='".$id."'");
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