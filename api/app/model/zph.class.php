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
class zph_controller extends common{
	function list_action(){ 
		$where="UNIX_TIMESTAMP(`starttime`)<'".time()."' AND UNIX_TIMESTAMP(`endtime`)>'".time()."'"; 
		$page=$_POST['page'];	
		$limit=$_POST['limit'];
		$limit=!$limit?10:$limit; 	
		$where.=" order by id desc"; 
		if($page){
			$pagenav=($page-1)*$limit;
			$where.=" limit $pagenav,$limit";
		}else{
			$where.=" limit $limit";
		}
		$rows=$this->obj->DB_select_all("zhaopinhui",$where);
		if(is_array($rows)){
			foreach($rows as $key=>$va){
				$list[$key]['id']		=$va['id'];
				$list[$key]['title']	=$va['title'];
				$list[$key]['stime']	=strtotime($va['starttime'])-time();
				$list[$key]['etime']	=$va['etime'];
				$list[$key]['starttime']	=$va['starttime'];
				$list[$key]['endtime']	=$va['endtime'];
				$list[$key]['address']	=$va['address'];
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
		$row=$this->obj->DB_select_once("zhaopinhui","`id`='".$id."'");
		if(is_array($row)){ 
			$list=array();
			$list['id']		=$row['id'];
			$list['stime']	=strtotime($row['starttime'])-time(); 
			$list['etime']	=strtotime($row['endtime'])-time(); 
			$list['title']	=$row['title'];
			$list['starttime']	=$row['starttime'];
			$list['endtime']	=$row['endtime'];
			$list['organizers']	=$row['organizers'];
			$list['address']	=$row['address'];
			$list['phone']	=$row['phone'];
			$list['user']	=$row['user'];
			$list['traffic']	=$row['traffic'];
			$list['body']	=$row['body'];
			$list['media']	=$row['media'];
			$list['packages']	=$row['packages'];
			$list['booth']	=$row['booth'];
			$list['participate']	=$row['participate'];
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

	function com_action(){
		$id=(int)$_POST['id'];
		if(!$id){
			$data['error']=3;
			echo json_encode($data);die;
		}
		$row=$this->obj->DB_select_once("zhaopinhui","`id`='".$id."'","starttime,endtime,title,body");
		if(is_array($row)){ 
			$where="`zid`='".$id."' and `status`='1'"; 
			$page=$_POST['page'];	
			$limit=$_POST['limit'];
			$limit=!$limit?10:$limit; 	
			$where.=" order by id desc"; 
			if($page){
				$pagenav=($page-1)*$limit;
				$where.=" limit $pagenav,$limit";
			}else{
				$where.=" limit $limit";
			}
			$rows=$this->obj->DB_select_all("zhaopinhui_com",$where);
			if(is_array($rows)&&$rows){
				$uid=$bid=$jobid=array();
				foreach($rows as $k=>$v){
					$rows[$k][$v]=$v;
					$uid[]=$v['uid'];
					$bid[]=$v['bid'];
					$jobid[]=$v['jobid'];
				}
				$com=$this->obj->DB_select_all("company","uid in(".@implode(",",$uid).")","`uid`,`name`"); 
				$bidspace=$this->obj->DB_select_all("zhaopinhui_space","id in(".@implode(",",$bid).")","`id`,`name`");  
				$jobs=$this->obj->DB_select_all("company_job","id in(".@implode(",",$jobid).")  and `status`<>'1' and `r_status`='1'","`id`,`uid`,`name`"); 
				foreach($rows as $key=>$v){
					$jobname=array();
					foreach($com as $val){
						if($v['uid']==$val['uid']){
							if($val['shortname']){
								$rows[$key]['comname']=$val['shortname'];
							}else{
								$rows[$key]['comname']=$val['name'];
							}
						}
					}
					foreach($bidspace as $val){
						if($v['bid']==$val['id']){
							$rows[$key]['bidname']=$val['name'];
						}
					} 
					foreach($jobs as $val){
						if($v['uid']==$val['uid']){
							$jobname[]=$val['name'];
							$rows[$key]['job'][]=array(
								'id'=>$val['id'],
								'uid'=>$val['uid'],
								'name'=>$val['name']
							);
						}
					} 
					$rows[$key]['jobname']=@implode(',',$jobname);
				}
			}
			$data['list']=count($rows)?$rows:array();
			$data['error']=1;
		}else{
			$data['error']=2;
		}
		echo json_encode($data);die;
	}
}
?>