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
class comproduct_controller extends siteadmin_controller
{
	
	function set_search(){
		$search_list[]=array("param"=>"status","name"=>'审核状态',"value"=>array("1"=>"已审核","3"=>"未审核","2"=>"未通过"));
		$ad_time=array('1'=>'今天','3'=>'最近三天','7'=>'最近七天','15'=>'最近半月','30'=>'最近一个月');
		$search_list[]=array("param"=>"end","name"=>'发布时间',"value"=>$ad_time);
		$this->yunset("search_list",$search_list);
	}
	function index_action(){

		$this->set_search(); 
		$where='1'; 
		if(trim($_GET['keyword'])){
			if($_GET['type']=="1"){ 
				$UserinfoM=$this->MODEL('userinfo');
				$com=$UserinfoM->GetUserinfoList(array("`name` like '%".trim($_GET['keyword'])."%'"),array('usertype'=>2,"field"=>"uid,name",'special'=>'1'));
				$uid=array();
				foreach($com as $val){
					$uid[]=$val['uid'];
				}
				$where.=" and `uid` in(".pylode(',',$uid).")";
			}else{
				$where.=" and `title` like '%".trim($_GET['keyword'])."%'";
			}
			$urlarr['type']=$_GET['type'];
			$urlarr['keyword']=$_GET['keyword'];
		}
		if($_GET['status']){
			 if($_GET['status']=='3'){
				$where.=" and `status`='0'";
			 }else{
				$where.=" and `status`='".$_GET['status']."'";
			 }
			 $urlarr['status']=$_GET['status'];
		} 
		if($_GET['end']){
			if($_GET['end']=='1'){
				$where.=" and `ctime` >= '".strtotime(date("Y-m-d 00:00:00"))."'";
			}else{
				$where.=" and `ctime` >= '".strtotime('-'.(int)$_GET['end'].'day')."'";
			}
			$urlarr['end']=$_GET['end'];
		}  
		if($_GET['order']) {
			$where.=" order by ".$_GET['t']." ".$_GET['order'];
			$urlarr['order']=$_GET['order']; 
		}else{
			$where.=" order by id desc";
		} 
		$urlarr['page']="{{page}}";
		$pageurl=Url($_GET['m'],$urlarr,'admin');
        $M=$this->MODEL(); 
		 
		$rows=$M->get_page("company_product",$where,$pageurl,$this->config['sy_listnum']); 
		if($rows['rows']&&is_array($rows['rows'])){
			if(count($uid)==false){  
				$uid=array();
				foreach($rows['rows'] as $val){
					$uid[]=$val['uid'];
				}
				$UserinfoM=$this->MODEL('userinfo');
				$com=$UserinfoM->GetUserinfoList(array("`uid` in(".@implode(',',$uid).")"),array('usertype'=>2,"field"=>"uid,name"));
			}
			foreach($rows['rows'] as $key=>$val){
				foreach($com as $v){
					if($val['uid']==$v['uid']){
						$rows['rows'][$key]['name']=$v['name'];
					}
				} 
			} 
		}
		$this->yunset($rows); 
		$this->yunset("get_type", $_GET);
		$this->siteadmin_tpl(array('admin_comproduct'));
	}
	function statusbody_action(){
		$CompanyM=$this->MODEL('company');
		$userinfo =$CompanyM->GetProductOne(array("id"=>$_GET['id']),array("field"=>"statusbody")); 
		echo $userinfo['statusbody'];die;
	}

	function status_action(){
		extract($_POST);
		$id = @explode(",",$id);
		
		if(is_array($id)){
			$CompanyM=$this->MODEL('company');
			foreach($id as $value){
				$idlist[] = $value;
			}
			$aid = @implode(",",$idlist);
			$id=$CompanyM->UpdateProduct(array("status"=>$status,"statusbody"=>$statusbody),array("`id` IN ($aid)")); 
 			$id?$this->ACT_layer_msg("产品审核(ID:".$aid.")设置成功！",9,$_SERVER['HTTP_REFERER'],2,1):$this->ACT_layer_msg("设置失败！",8,$_SERVER['HTTP_REFERER']);
		}else{
			$this->ACT_layer_msg("非法操作！",8,$_SERVER['HTTP_REFERER']);
		}
	}
	function statuss_action(){
		$CompanyM=$this->MODEL('company');
		$CompanyM->UpdateProduct(array("status"=>$_POST['status']),array("`id` IN (".$_POST['allid'].")"));  
		$this->MODEL('log')->admin_log("企业产品(ID:".$_POST['allid'].")审核成功");
		echo $_POST['status'];die;
	}
	function del_action(){
		$this->check_token();
		$CompanyM=$this->MODEL('company');
	    if($_GET['del']){
	    	$del=$_GET['del'];
	    	if($del){ 
	    		if(is_array($del)){
			    	foreach($del as $v){
			    	    $this->del_com($v,$CompanyM);
			    	}
					$del_id=@implode(',',$del);
		    	}else{
		    		$this->del_com($del,$CompanyM);
					$del_id=$del;
		    	}
				$this->layer_msg('产品(ID:'.$del_id.')删除成功！',9,1,$_SERVER['HTTP_REFERER']);
	    	}else{
	    		$this->layer_msg( "请选择您要删除的信息！",8,1);
	    	}
	    }

	    if(isset($_GET['id'])){
	    	extract($_GET);
	    	$id_a=explode("-",$id);
			$result=$this->del_com($id,$CompanyM);
			isset($result)?$this->layer_msg('产品(ID:'.$id_a[0].')删除成功！',9,$_SERVER['HTTP_REFERER']):$this->layer_msg('删除失败！',8,$_SERVER['HTTP_REFERER']);
		}else{
			$this->layer_msg('非法操作！',8);
		}
	}

	function del_com($id,$CompanyM){ 
		$id_arr = explode("-",$id);
		if($id_arr[0]){ 
			$product=$CompanyM->GetProductOne(array("id"=>$id_arr[0])); 
 			unlink_pic("../".$product['pic']);
			$result=$CompanyM->DeleteProduct(array("id"=>$product['id'])); 
		}
		return $result;
	}

}
?>