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
class news_controller extends common{
	function list_action(){
		$where="1";
		$nid=(int)$_POST['nid'];
		$sdate=$_POST['sdate'];
		$edate=$_POST['edate'];
		$keyword=$_POST['keyword'];
		$describe=$_POST['describe'];
		$page=$_POST['page'];
		$limit=$_POST['limit'];
		$order=$_POST['order'];
		$nodata=$_POST['nodata'];
		$limit=!$limit?10:$limit;
		if($nid){
			$where.=" and `nid`='".$nid."'";
		}
		if($sdate){
			$where.=" and `datetime`>'".strtotime($sdate)."'";
		}
		if($edate){
			$where.=" and `datetime`<'".strtotime($edate)."'";
		}
		if($describe){
			$where.=" and `describe`='".$describe."'";
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
		$rows=$this->obj->DB_select_all("news_base",$where);
		if(is_array($rows)){
			foreach($rows as $va){$nid_arr[]=$va['nid'];}
			$rows_group=$this->obj->DB_select_all("news_group","id in (".pylode(',',$nid_arr).")");
			foreach($rows_group as $v){$nid_row[$v['id']]=$v['name'];}
			foreach($rows as $key=>$va){
				$list[$key]['id']		=$va['id'];
				$list[$key]['title']	=$va['title'];
				$list[$key]['nid']		=$va['nid'];
				$list[$key]['nidname']	=$nid_row[$va['nid']];
				$list[$key]['keyword']	=$va['keyword'];
				$list[$key]['author']	=$nid_row[$va['author']];
				$list[$key]['datetime']	=$va['datetime'];
				$list[$key]['hits']		=$va['hits'];
				$list[$key]['describe']	=$va['describe'];
				$list[$key]['description']=$va['description'];
				$list[$key]['newsphoto']=$va['newsphoto'];
				$list[$key]['s_thumb']	=$va['s_thumb'];
				$list[$key]['source']	=$nid_row[$va['source']];
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
		$rows=$this->obj->DB_select_alls("news_base","news_group","a.nid=b.id and a.id='".$id."'","a.*,b.name as groupname");
		$row=$rows[0];
		if(is_array($row)){
			$this->obj->DB_update_all("news_base","`hits`=`hits`+1","`id`='".$id."'");
			$cont=$this->obj->DB_select_once("news_content","`nbid`='$id'");
			$list['id']			=$row['id'];
			$list['title']		=$row['title'];
			$list['nid']		=$row['nid'];
			$list['nidname']	=$row['groupname'];
			$list['keyword']	=$row['keyword'];
			$list['author']		=$row['author']?$row['author']:$this->config['sy_webname'];
			$list['datetime']	=$row['datetime'];
			$list['hits']		=$row['hits'];
			$list['describe']	=$row['describe'];
			$list['description']=$row['description'];
			$list['newsphoto']	=$row['newsphoto'];
			$list['s_thumb']	=$row['s_thumb'];
			$list['source']		=$row['source']?$row['source']:$this->config['sy_webname'];
			$list['body']		=html_entity_decode(str_replace("/data/upload/kindeditor/image/",$this->config['sy_weburl']."/data/upload/kindeditor/image/",$cont['content']),ENT_QUOTES);
			$data['list']		=$list;
			$data['error']		=1;
		}else{
			$data['error']=2;
		}
		echo json_encode($data);die;
	}
	function class_action(){
		$id=(int)$_POST['id'];
		$keyid=(int)$_POST['keyid'];
		$order=$_POST['order'];
		$where="1";
		if($id){
			$where.=" and `id`='".$id."'";
		}else{
			if($keyid){
				$where.=" and `keyid`='".$keyid."'";
			}else{
				$where.=" and `keyid`='0'";
			}
		}
		if($order){
			$where.=" order by ".$order;
		}else{
			$where.=" order by sort asc";
		}
		$rows=$this->obj->DB_select_all("news_group",$where);

		if(is_array($rows)){
			foreach($rows as $key=>$va){
				$list[$key]['id']		=$va['id'];
				$list[$key]['name']		=$va['name'];
				$list[$key]['keyid']	=$va['keyid'];
				$list[$key]['sort']		=$va['sort'];
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


}
?>