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
class comnews_controller extends siteadmin_controller{
	
	function set_search(){
		$search_list[]=array("param"=>"status","name"=>'审核状态',"value"=>array("1"=>"已审核","3"=>"未审核","2"=>"未通过"));
		$lo_time=array('1'=>'今天','3'=>'最近三天','7'=>'最近七天','15'=>'最近半月','30'=>'最近一个月');
		$search_list[]=array("param"=>"time","name"=>'发布时间',"value"=>$lo_time);
		$this->yunset("search_list",$search_list);
	}
	function index_action(){
		$this->set_search(); 
		$where='1';
		if(trim($_GET['keyword'])){
			if($_GET['type']){
				if($_GET['type']=="1"){
					$UserinfoM=$this->MODEL('userinfo');
					$com=$UserinfoM->GetUserinfoList(array("`name` like '%".trim($_GET['keyword'])."%'"),array('usertype'=>2,"field"=>"uid,name"));
					$uid=array();
					foreach($com as $val){
						$uid[]=$val['uid'];
					}
					$where.=" and `uid` in(".pylode(',',$uid).")"; 
				}else{
					$where.=" and `title` like '%".trim($_GET['keyword'])."%'";
				}
				$urlarr['type']=$_GET['type'];
			}
			$urlarr['keyword']=$_GET['keyword'];
		}
		if($_GET['status']){
			if($_GET['status']=="3"){
				$where.=" and `status`='0'";
			}else{
				$where.=" and `status`='".$_GET['status']."'";
			}
			$urlarr['status']=$_GET['status'];
		} 
		if($_GET['time']){
			if($_GET['time']=='1'){
				$where.=" and `ctime` >= '".strtotime(date("Y-m-d 00:00:00"))."'";
			}else{
				$where.=" and `ctime` >= '".strtotime('-'.(int)$_GET['time'].'day')."'";
			}
			$urlarr['time']=$_GET['time'];
		} 
		if($_GET['order'])
		{
			$where.=" order by `".$_GET['t']."` ".$_GET['order'];
			$urlarr['order']=$_GET['order'];
			$urlarr['t']=$_GET['t'];
		}else{
			$where.=" order by `id` desc";
		}
		 
		$urlarr['page']="{{page}}";
		$pageurl=Url($_GET['m'],$urlarr,'admin');
        $M=$this->MODEL(); 
		$rows=$M->get_page("company_news",$where,$pageurl,$this->config['sy_listnum']); 
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
		$this->siteadmin_tpl(array('admin_comnews'));
	}
	function statusbody_action(){
		$CompanyM=$this->MODEL('company');
		$userinfo =$CompanyM->GetNewsOne(array("id"=>(int)$_GET['id']),array("field"=>"statusbody")); 
		echo $userinfo['statusbody'];die;
	}
	function status_action(){
		extract($_POST);
		$id = @explode(",",$pid);
		
		if(is_array($id)){
			$CompanyM=$this->MODEL('company');
			foreach($id as $value){
				$idlist[] = $value;
			}
			$aid = @implode(",",$idlist);
			$id=$CompanyM->UpdateComNews(array("status"=>$status,"statusbody"=>$statusbody),array("`id` IN ($aid)")); 
 			$id?$this->ACT_layer_msg("审核(ID:".$aid.")设置成功！",9,$_SERVER['HTTP_REFERER'],2,1):$this->ACT_layer_msg("设置失败！",8,$_SERVER['HTTP_REFERER']);
		}else{
			$this->ACT_layer_msg("非法操作！",8,$_SERVER['HTTP_REFERER']);
		}
	}
	function del_action(){
		$this->check_token();
		$CompanyM=$this->MODEL('company');
	    if($_GET['del']){
	    	$del=$_GET['del'];
	    	if($del){
				foreach($del as $v){
					$this->del_com($v,$CompanyM);
				}
	    		$this->layer_msg( "新闻(ID:".@implode(',',$del).")删除成功！",9,1,$_SERVER['HTTP_REFERER']);
	    	}else{
	    		$this->layer_msg( "请选择您要删除的信息！",8,1,$_SERVER['HTTP_REFERER']);
	    	}
	    }

	    if(isset($_GET['id'])){
			$result=$this->del_com($_GET['id'],$CompanyM);
			$result?$this->layer_msg('新闻(ID:'.@implode(',',$_GET['id']).')删除成功！',9):$this->layer_msg('删除失败！',8);
		}else{
			$this->layer_msg('非法操作！',8);
		}
	}
	function del_com($id,$CompanyM){
		$id_arr = @explode("-",$id);
		if($id_arr[0]){
			$result=$CompanyM->DeleteComNews(array("id"=>$id_arr[0])); 
		}
		return $result;
	}
}
?>