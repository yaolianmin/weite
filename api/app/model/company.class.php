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
class company_controller extends common{
	function list_action(){
		$where="`name`<>'' AND `r_status`<>'2'";
		$keyword=$_POST['keyword'];
		$page=$_POST['page'];
		$limit=$_POST['limit'];
		$order=$_POST['order'];
		$nodata=$_POST['nodata'];
		$uid=(int)$_POST['uid'];
		$provinceid=(int)$_POST['provinceid'];
		$cityid=(int)$_POST['cityid'];
		$hy=(int)$_POST['hy'];
		$limit=!$limit?10:$limit;
		if($hy){
			$where.=" and `hy`='".$hy."'";
		}else{
			$where.=" and `hy`<>''";
		} 
		if($provinceid){
			$where.=" and `provinceid`='".$provinceid."'";
		}
		if($cityid){
			$where.=" and `cityid`='".$cityid."'";
		}
		if($keyword){
			$keyword=$this->stringfilter($keyword);
			$where.=" and `name` like '%".$keyword."%'";
		}
		if($uid){
			$where.=" and `uid`='".$uid."'";
		}
		if($_POST['rec'])
		{
			$where.=" and `rec`='".(int)$_POST['rec']."'";
		}
		if($_POST['pr'])
		{
			$where.=" and `pr`='".(int)$_POST['pr']."'";
		}
		if($_POST['mun'])
		{
			$where.=" and `mun`='".(int)$_POST['mun']."'";
		}
		if($_POST['firmpic'])
		{
			$where.=" and `firmpic`<>''";
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
			$where.=" order by uid desc";
		}
		if($page){
			$pagenav=($page-1)*$limit;
			$where.=" limit $pagenav,$limit";
		}else{
			$where.=" limit $limit";
		}
		
		$rows=$this->obj->DB_select_all("company",$where);
		if(is_array($rows)){
			foreach($rows as $key=>$k){
				$list[$key]['uid']			=$k['uid'];
				if($k['shortname']){
					$list[$key]['name']			=$k['shortname'];
				}else{
					$list[$key]['name']			=$k['name'];
				}
				
				$list[$key]['cert']			=$k['cert'];
				$list[$key]['hy']			=$k['hy'];
				$list[$key]['pr']			=$k['pr'];
				$list[$key]['provinceid']	=$k['provinceid'];
				$list[$key]['cityid']		=$k['cityid'];
				$list[$key]['mun']			=$k['mun'];
				$list[$key]['sdate']		=$k['sdate'];
				$list[$key]['money']		=$k['money'];
				$list[$key]['content']		=$k['content'];
				$list[$key]['address']		=$k['address'];
				$list[$key]['zip']			=$k['zip'];
				$list[$key]['linkman']		=$k['linkman'];
				$list[$key]['linkjob']		=$k['linkjob'];
				$list[$key]['linkqq']		=$k['linkqq'];
				$list[$key]['linkphone']	=$k['linkphone'];
				$list[$key]['linktel']		=$k['linktel'];
				$list[$key]['linkmail']		=$k['linkmail'];
				$list[$key]['x']			=$k['x'];
				$list[$key]['y']			=$k['y'];
				$list[$key]['logo']			=$k['logo'];
				$list[$key]['payd']			=$k['payd'];
				$list[$key]['lastupdate']	=$k['lastupdate'];
				$list[$key]['jobtime']		=$k['jobtime'];
				$list[$key]['r_status']		=$k['r_status'];
				$list[$key]['firmpic']		=$this->config['sy_weburl'].str_replace('./','/',$k['firmpic']);
				$list[$key]['rec']			=$k['rec'];
				$list[$key]['hits']			=$k['hits'];
				$list[$key]['ant_num']		=$k['ant_num'];
				$list[$key]['pl_time']		=$k['pl_time'];
				$list[$key]['pl_status']	=$k['pl_status'];
				$list[$key]['order']		=$k['order'];
				$list[$key]['admin_remark']	=$k['admin_remark'];
				$list[$key]['email_dy']		=$k['email_dy'];
				$list[$key]['msg_dy']		=$k['msg_dy'];
				$job_rows=$this->obj->DB_select_all("company_job","`uid`='".$k['uid']."'","`id`,`name`");
				foreach($job_rows as $itemKey=>$item){
					$list[$key]['joblist'][$itemKey]['name']=$item['name'];
					$list[$key]['joblist'][$itemKey]['id']=$item['id'];
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
			$data[error]=1;
		}else{
			$data[error]=2;
		}
		echo json_encode($data);die;
	}
	function hotjoblist_action(){
		$where="a.uid=b.uid";
		$keyword=$_POST['keyword'];
		$page=$_POST['page'];
		$limit=$_POST['limit'];
		$order=$_POST['order'];
		$nodata=$_POST['nodata'];
		$uid=(int)$_POST['uid'];
		$provinceid=(int)$_POST['provinceid'];
		$cityid=(int)$_POST['cityid'];
		$hy=(int)$_POST['hy'];
		$limit=!$limit?10:$limit;
		if($hy){
			$where.=" and b.`hy`='".$hy."'";
		}
		if($provinceid){
			$where.=" and b.`provinceid`='".$provinceid."'";
		}
		if($cityid){
			$where.=" and b.`cityid`='".$cityid."'";
		}
		if($keyword){
			$keyword=$this->stringfilter($keyword);
			$where.=" and b.`name` like '%".$keyword."%'";
		}
		if($uid){
			$where.=" and `uid`='".$uid."'";
		}
		if($_POST['rec'])
		{
			$where.=" and b.`rec`='".(int)$_POST['rec']."'";
		}
		if($_POST['pr'])
		{
			$where.=" and b.`pr`='".(int)$_POST['pr']."'";
		}
		if($_POST['mun'])
		{
			$where.=" and b.`mun`='".(int)$_POST['mun']."'";
		}
		if($_POST['firmpic'])
		{
			$where.=" and b.`firmpic`<>''";
		}
		if($nodata){
			$nodataarr=explode(",",$nodata);
			foreach($nodataarr as $v){
				$where.=" b.and ".$v."<>''";
			}
		}
		if($order){
			$where.=" order by b.".$order;
		}else{
			$where.=" order by b.uid desc";
		}
		if($page){
			$pagenav=($page-1)*$limit;
			$where.=" limit $pagenav,$limit";
		}else{
			$where.=" limit $limit";
		}
		$rows=$this->obj->DB_select_alls("hotjob","company",$where,"a.hot_pic,b.*");
		if(is_array($rows)){
			foreach($rows as $key=>$k){
				$list[$key]['uid']			=$k['uid'];
				if($k['shortname']){
					$list[$key]['name']			=$k['shortname'];
				}else{
					$list[$key]['name']			=$k['name'];
				}
				
				$list[$key]['cert']			=$k['cert'];
				$list[$key]['hy']			=$k['hy'];
				$list[$key]['pr']			=$k['pr'];
				$list[$key]['provinceid']	=$k['provinceid'];
				$list[$key]['cityid']		=$k['cityid'];
				$list[$key]['mun']			=$k['mun'];
				$list[$key]['sdate']		=$k['sdate'];
				$list[$key]['money']		=$k['money'];
				$list[$key]['content']		=$k['content'];
				$list[$key]['address']		=$k['address'];
				$list[$key]['zip']			=$k['zip'];
				$list[$key]['linkman']		=$k['linkman'];
				$list[$key]['linkjob']		=$k['linkjob'];
				$list[$key]['linkqq']		=$k['linkqq'];
				$list[$key]['linkphone']	=$k['linkphone'];
				$list[$key]['linktel']		=$k['linktel'];
				$list[$key]['linkmail']		=$k['linkmail'];
				$list[$key]['x']			=$k['x'];
				$list[$key]['y']			=$k['y'];
				$list[$key]['logo']			=$k['logo'];
				$list[$key]['payd']			=$k['payd'];
				$list[$key]['lastupdate']	=$k['lastupdate'];
				$list[$key]['jobtime']		=$k['jobtime'];
				$list[$key]['r_status']		=$k['r_status'];
				$list[$key]['firmpic']		=$k['firmpic'];
				$list[$key]['rec']			=$k['rec'];
				$list[$key]['hits']			=$k['hits'];
				$list[$key]['ant_num']		=$k['ant_num'];
				$list[$key]['pl_time']		=$k['pl_time'];
				$list[$key]['pl_status']	=$k['pl_status'];
				$list[$key]['order']		=$k['order'];
				$list[$key]['admin_remark']	=$k['admin_remark'];
				$list[$key]['email_dy']		=$k['email_dy'];
				$list[$key]['msg_dy']		=$k['msg_dy'];
				$list[$key]['hot_pic']		=isset($k['hot_pic'])?$this->config['sy_weburl'].'/'.$k['hot_pic']:'';
				$job_rows=$this->obj->DB_select_all("company_job","`uid`='".$k['uid']."'","`id`,`name`");
				foreach($job_rows as $itemKey=>$item){
					$list[$key]['joblist'][$itemKey]['name']=$item['name'];
					$list[$key]['joblist'][$itemKey]['id']=$item['id'];
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
			$data[error]=1;
		}else{
			$data[error]=2;
		}
		echo json_encode($data);die;
	}
}
?>