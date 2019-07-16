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
class public_controller extends common{
	function config_action(){
		if(is_array($this->config)){
			foreach($this->config as $va=>$k){
				$rows[$va]=$k;
				
				$rows['sy_iosMemberUpdateUrl']='http://www.phpyun.com/';
				$rows['sy_iosCompanyUpdateUrl']='http://www.phpyun.com/';
				$rows['sy_iosAdminUpdateUrl']='http://www.phpyun.com/';
			}
		}
		$data[data]=$rows;
		echo json_encode($data);die;
	}
	function city_action(){
		include(PLUS_PATH."city.cache.php");
		$data[city_index]=$city_index;
		$data[city_type]=$city_type;
		if(is_array($city_name)){
			foreach($city_name as $va=>$k){
				$rows[$va]=$k;
			}
		}
		$data[city_name]=$rows;
		echo json_encode($data);die;
	}
	
	function job_action(){
		include(PLUS_PATH."job.cache.php");
		$data[job_index]=$job_index;
		$data[job_type]=$job_type;
		if(is_array($job_name)){
			foreach($job_name as $va=>$k){
				$rows[$va]=$k;
			}
		}
		$data[job_name]=$rows;
		echo json_encode($data);die;
	}
	function industry_action(){
		include(PLUS_PATH."industry.cache.php");
		$data[industry_index]=$industry_index;  
		if(is_array($industry_name)){
			foreach($industry_name as $va=>$k){
				$rows[$va]=$k;
			}
		}  
		$data[industry_name]=$rows; 
		echo json_encode($data);die;
	}
	function parts_action(){
		include(PLUS_PATH."part.cache.php");
		$data[partdata]=$partdata;
		if(is_array($partclass_name)){
			foreach($partclass_name as $va=>$k){
				$rows[$va]=$k;
			}
		}  
		$data[partclass_name]=$rows;
		echo json_encode($data);die;
	}
	function advert_action(){

		if($_GET['type']=='1'){		
			include_once(PLUS_PATH."pimg_cache.php");
			$list=$ad_label[44];
		}else if($_GET['type']=='2'){	
			include_once(PLUS_PATH."pimg_cache.php");
			$list=$ad_label[45];
		}else if($_GET['type']=='3'){			
			include_once(PLUS_PATH."pimg_cache.php");
			$list=$ad_label[45];
		}else if($_GET['type']=='4'){			
			include_once(PLUS_PATH."pimg_cache.php");
			$list=$ad_label[45];
		}else{
			echo json_encode(array('list'=>array()));die;
		}
		$i=0;
		if(is_array($list)){
			foreach($list as $key=>$val){
				$data['list'][$i]['pic']=$val['pic'];
				$i++;
			}
		}
		echo json_encode($data);die;
	}
	function guideImage_action(){

		if($_GET['type']=='iosmemberguide'){		
			include_once(PLUS_PATH."pimg_cache.php");
			$list=$ad_label[44];
		}else if($_GET['type']=='ioscompanyguide'){		
			include_once(PLUS_PATH."pimg_cache.php");
			$list=$ad_label[45];
		}else if($_GET['type']=='iosmemberhelp'){		
			include_once(PLUS_PATH."pimg_cache.php");
			$list=$ad_label[53];
		}else if($_GET['type']=='ioscompanyhelp'){		
			include_once(PLUS_PATH."pimg_cache.php");
			$list=$ad_label[54];
		}else{
			echo json_encode(array('list'=>array()));die;
		}		
		sort($list);

		if(intval($_GET['FSystenVersion'])>=7){
			if(intval($_GET['DeviceResolutionHeight'])>960){
				$backgroundColorArr=array('f0eacc','ccf0e2','d1eaff','a8d9db');
				$topArr=array('20','20','20','20');
				$leftArr=array('0','0','0','0');
				$widthArr=array('320','320','320','320');
				$heightArr=array('460','460','460','460');
				$skipButtonTopArr=array('400','400','400','400');
				$skipButtonLeftArr=array('100','100','100','100');
				$skipButtonWidthArr=array('120','120','120','120');
				$skipButtonHeightArr=array('40','40','40','40');
			}else{
				$backgroundColorArr=array('f0eacc','ccf0e2','d1eaff','a8d9db');
				$topArr=array('20','20','20','20');
				$leftArr=array('0','0','0','0');
				$widthArr=array('320','320','320','320');
				$heightArr=array('460','460','460','460');
				$skipButtonTopArr=array('400','400','400','400');
				$skipButtonLeftArr=array('100','100','100','100');
				$skipButtonWidthArr=array('120','120','120','120');
				$skipButtonHeightArr=array('40','40','40','40');
			}			
		}else{
			if(intval($_GET['DeviceResolutionHeight'])>960){
				$backgroundColorArr=array('f0eacc','ccf0e2','d1eaff','a8d9db');
				$topArr=array('20','20','20','20');
				$leftArr=array('0','0','0','0');
				$widthArr=array('320','320','320','320');
				$heightArr=array('460','460','460','460');
				$skipButtonTopArr=array('400','400','400','400');
				$skipButtonLeftArr=array('100','100','100','100');
				$skipButtonWidthArr=array('120','120','120','120');
				$skipButtonHeightArr=array('40','40','40','40');
			}else{
				$backgroundColorArr=array('f0eacc','ccf0e2','d1eaff','a8d9db');
				$topArr=array('0','0','0','0');
				$leftArr=array('0','0','0','0');
				$widthArr=array('320','320','320','320');
				$heightArr=array('460','460','460','460');
				$skipButtonTopArr=array('360','360','360','360');
				$skipButtonLeftArr=array('100','100','100','100');
				$skipButtonWidthArr=array('120','120','120','120');
				$skipButtonHeightArr=array('40','40','40','40');
			}
		}
		$i=count($list)-1;$index=0;
		if(is_array($list)){
			foreach($list as $key=>$val){
				$data['list'][$index]['pic']=$val['pic'];
				$data['list'][$index]['width']=$widthArr[$i];
				$data['list'][$index]['height']=$heightArr[$i];
				$data['list'][$index]['top']=$topArr[$i];
				$data['list'][$index]['left']=$leftArr[$i];
				$data['list'][$index]['backgroundColor']=$backgroundColorArr[$i];
				$data['list'][$index]['skipButtonTop']=$skipButtonTopArr[$i];
				$data['list'][$index]['skipButtonLeft']=$skipButtonLeftArr[$i];
				$data['list'][$index]['skipButtonWidth']=$skipButtonWidthArr[$i];
				$data['list'][$index]['skipButtonHeight']=$skipButtonHeightArr[$i];
				$i--;
				$index++;
			}
		}
		echo json_encode($data);die;
	}	
	function faqlist_action(){

		if($_GET['type']=='1'){		
			$where="1";
			$nid=17;
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
					$list[$key]['newsphoto']=$nid_row[$va['newsphoto']];
					$list[$key]['s_thumb']	=$nid_row[$va['s_thumb']];
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
		}else if($_GET['type']=='2'){		
			include_once(PLUS_PATH."pimg_cache.php");
			$list=$ad_label[45];
		}else if($_GET['type']=='3'){		
			include_once(PLUS_PATH."pimg_cache.php");
			$list=$ad_label[44];
		}else if($_GET['type']=='4'){	
			include_once(PLUS_PATH."pimg_cache.php");
			$list=$ad_label[45];
		}else{
			echo json_encode(array('list'=>array()));die;
		}
		$i=0;
		if(is_array($list)){
			foreach($list as $key=>$val){
				$data['list'][$i]['pic']=$val['pic'];
				$i++;
			}
		}
		echo json_encode($data);die;
	}
	function user_action(){
		include_once(PLUS_PATH."user.cache.php");
		$data[userdata]=$userdata;
		if(is_array($userclass_name)){
			foreach($userclass_name as $va=>$k){
				$rows[$va]=$k;
			}
		}
		$data[userclass_name]=$rows;
		echo json_encode($data);die;
	}
	function com_action(){
		include_once(PLUS_PATH."com.cache.php");
		$data[comdata]=$comdata;
		if(is_array($comclass_name)){
			foreach($comclass_name as $va=>$k){
				$rows[$va]=$k;
			}
		}
		$data[comclass_name]=$rows;
		echo json_encode($data);die;
	}
	function lt_action(){
		include_once(PLUS_PATH."lt.cache.php");
		$data[ltdata]=$ltdata;
		if(is_array($ltclass_name)){
			foreach($ltclass_name as $va=>$k){
				$rows[$va]=$k;
			}
		}
		$data[ltclass_name]=$rows;
		echo json_encode($data);die;
	}
	
	function getdescription_action(){
		$id=(int)$_POST['id'];
		$typeid=(int)$_POST['typeid'];
		if((!$id)&&(!$typeid)){
			$data['error']=3;
			echo json_encode($data);die;
		}
		if($typeid=='1'){
			$id='9';
		}else if($typeid=='2'){
			$id='21';
		}else if($typeid=='3'){
			$id='9';
		}else if($typeid=='4'){
			$id='21';
		}
		$rows=$this->obj->DB_select_all("description","id='".$id."'","*");
		$row=$rows[0];
		if(is_array($row)){
			$list['id']			=$row['id'];
			$list['title']		=$row['title'];
			$list['name']	=$row['name'];
			$list['keyword']	=$row['keyword'];
			$list['body']		=html_entity_decode($row['content'],ENT_QUOTES);
			$data['list']		=$list;
			$data['error']		=1;
		}else{
			$data['error']=2;
		}
		echo json_encode($data);die;
	}
}
?>