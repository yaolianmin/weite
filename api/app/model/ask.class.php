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
class ask_controller extends common{
	function list_action(){ 
		$where="1"; 
		$uid=$_POST['uid'];	
		$hot=$_POST['hot'];	
		$keyword=$_POST['keyword'];	
		$page=$_POST['page'];	
		$limit=$_POST['limit'];
		$limit=!$limit?10:$limit; 
		if($hot){
			$time=strtotime("-".$hot." day");
			$where.=" and `add_time`>'".$time."'";
		}
		if($keyword){
			$where.=" and `title` like '%".$keyword."%'";
		}
		$where.=" order by `add_time` desc"; 
		if($page){
			$pagenav=($page-1)*$limit;
			$where.=" limit $pagenav,$limit";
		}else{
			$where.=" limit $limit";
		}
		$rows=$this->obj->DB_select_all("question",$where);
		if(is_array($rows)){
			$uids=array();
			foreach($rows as $v){
				if(in_array($v['uid'],$uids)==false){
					$uids[]=$v['uid'];
				}
			}
			if($uid){
				$attention=$this->obj->DB_select_once("attention","`uid`='".$uid."' and `type`='1'","`ids`");
				$atnids=explode(',',$attention['ids']);
			}
			
			
			$list=array();
			foreach($rows as $key=>$va){
				
				if(in_array($va['id'],$atnids)){
					$list[$key]['atn']=1;
				}
				$list[$key]['id']		=$va['id'];
				$list[$key]['cid']		=$va['cid'];
				$list[$key]['uid']		=$va['uid'];
				$list[$key]['atnnum']		=$va['atnnum'];
				$list[$key]['visit']		=$va['visit'];
				$list[$key]['add_time']		=$va['add_time'];
				$list[$key]['is_recom']		=$va['is_recom'];
				$list[$key]['lastupdate']		=$va['lastupdate'];
				$list[$key]['answer_num']		=$va['answer_num'];
				$list[$key]['title']	= $va['title']; 
				$list[$key]['content']	= $va['content']; 
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
		$row=$this->obj->DB_select_once("question","`id`='".$id."'");
		if(is_array($row)){ 
			$uid=$_POST['uid'];	 
			$page=$_POST['page'];	
			$limit=$_POST['limit'];
			$limit=!$limit?10:$limit;  
			$where="`qid`='".$id."' order by `support` desc"; 
			if($page){
				$pagenav=($page-1)*$limit;
				$where.=" limit $pagenav,$limit";
			}else{
				$where.=" limit $limit";
			}
			$rows=$this->obj->DB_select_all("answer",$where);
			if($rows&&is_array($rows)){ 
				$uids=array($row['uid']);
				foreach($rows as $v){
					if(in_array($v['uid'],$uids)==false){
						$uids[]=$v['uid'];
					}
				}
				
			}
			if($uid){
				$attention=$this->obj->DB_select_once("attention","`uid`='".$uid."' and `type`='1'","`ids`");
				$atnids=explode(',',$attention['ids']);
			} 
			foreach($friend as $v){
				if($v['uid']==$row['uid']){
					$row['nickname']= $v['nickname']; 
					$row['description']= $v['description']; 
					$row['pic']=$v['pic']; 
				}
			}
			if(in_array($row['id'],$atnids)){
				$row['atn']=1;
			} 
			
			foreach($row as $k=>$v){
				if(is_array($v)){
					foreach($v as $key=>$val){
						$list[$k][$key]=isset($val)?$val:'';
					}
				}else{
					$row[$k]=isset($v)?$v:'';
				}
			}
			$data['answer']=$rows;
			$data['list']=count($row)?$row:array();
			$data['error']=1;
		}else{
			$data['error']=2;
		}
		echo json_encode($data);die;
	} 
}
?>