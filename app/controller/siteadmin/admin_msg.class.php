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
class admin_msg_controller extends siteadmin_controller
{
	
	function set_search(){
		$search_list[]=array("param"=>"job","name"=>'职位类型',"value"=>array("1"=>"普通","2"=>"高级","3"=>"猎头"));
		$lo_time=array('1'=>'今天','3'=>'最近三天','7'=>'最近七天','15'=>'最近半月','30'=>'最近一个月');
		$search_list[]=array("param"=>"zx","name"=>'咨询时间',"value"=>$lo_time);
		$f_time=array('1'=>'今天','3'=>'最近三天','7'=>'最近七天','15'=>'最近半月','30'=>'最近一个月');
		$search_list[]=array("param"=>"hf","name"=>'回复时间',"value"=>$f_time);
		$this->yunset("search_list",$search_list);
	}
	function index_action()
	{
		$this->set_search();
		$where=1;
		if(trim($_GET['keyword'])!="")
		{
			if($_GET['type']=="1")
			{
				$where.=" and `username` LIKE '%".trim($_GET['keyword'])."%'";
			}elseif($_GET['type']=="2"){
				$where.=" and `job_name` LIKE '%".trim($_GET['keyword'])."%'";
			}elseif($_GET['type']=="3"){
				$where.=" and `com_name` LIKE '%".trim($_GET['keyword'])."%'";
			}elseif ($_GET['type']=="4"){
			    $where.=" and `content` LIKE '%".trim($_GET['keyword'])."%'";
			}elseif ($_GET['type']=="5"){
			    $where.=" and `reply` LIKE '%".trim($_GET['keyword'])."%'";
			}
			$page_url['keyword']=$_GET['keyword'];
			$page_url['type']=$_GET['type'];
		}
		if($_GET['zx']){
			if($_GET['zx']=='1'){
				$where.=" and `datetime` >= '".strtotime(date("Y-m-d 00:00:00"))."'";
			}else{
				$where.=" and `datetime` >= '".strtotime('-'.(int)$_GET['zx'].'day')."'";
			}
			$urlarr['zx']=$_GET['zx'];
		}
		if($_GET['hf']){
			if($_GET['hf']=='1'){
				$where.=" and `reply_time` >= '".strtotime(date("Y-m-d 00:00:00"))."'";
			}else{
				$where.=" and `reply_time` >= '".strtotime('-'.(int)$_GET['hf'].'day')."'";
			}
			$urlarr['hf']=$_GET['hf'];
		}
		if($_GET['job'])
		{
			$where.=" and `type`='".$_GET['job']."'";
			$page_url['job']=$_GET['job'];
		} 
		if($_GET['order'])
		{
			$order=$_GET['order'];
		}else{
			$order="desc";
		}
		$page_url['order']=$_GET['order'];
		$page_url['page']="{{page}}";
		$pageurl=Url($_GET['m'],$page_url,'admin');
        $M=$this->MODEL();
		$rows = $M->get_page("msg",$where." ORDER BY `id` ".$order,$pageurl,$this->config['sy_listnum']);
		$this->yunset($rows);
		$this->yunset("get_type", $_GET);
		$this->siteadmin_tpl(array('admin_msg'));
	} 
	function del_action(){
		$this->check_token();
		$CompanyM=$this->MODEL('company');
	    if($_GET['del']){
	    	$del=$_GET['del'];
	    	if(is_array($del)){
				$CompanyM->DeleteMsg(array("`id` in(".@implode(',',$del).")")); 
	    		$this->layer_msg( "求职咨询(ID:".@implode(',',$del).")删除成功！",9,1,$_SERVER['HTTP_REFERER']);
	    	}else{
				$this->layer_msg( "请选择您要删除的信息！",8,1,$_SERVER['HTTP_REFERER']);
	    	}
	    } 
	    if(isset($_GET['id'])){
			$result=$CompanyM->DeleteMsg(array("id"=>$_GET['id'])); 
			isset($result)?$this->layer_msg('求职咨询(ID:'.$_GET['id'].')删除成功！',9,0,$_SERVER['HTTP_REFERER']):$this->layer_msg('删除失败！',8,0,$_SERVER['HTTP_REFERER']);
		}else{
			$this->ACT_layer_msg("非法操作！",8,$_SERVER['HTTP_REFERER']);
		}
	}
}