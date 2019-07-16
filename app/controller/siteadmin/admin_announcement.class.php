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
class admin_announcement_controller extends siteadmin_controller
{
	function set_search(){
		$ad_time=array('1'=>'今天','3'=>'最近三天','7'=>'最近七天','15'=>'最近半月','30'=>'最近一个月');
		$search_list[]=array("param"=>"end","name"=>'发布时间',"value"=>$ad_time);
		$this->yunset("search_list",$search_list);
	}
	function index_action(){
		$this->set_search();
		
		$where="1";
		if(trim($_GET['keyword'])){
			$where.=" and `title` like '%".trim($_GET['keyword'])."%'";
			$urlarr['keyword']=trim($_GET['keyword']);
		}
		if((int)$_GET['end']){
			if((int)$_GET['end']=='1'){
				$where.=" and `datetime` >= '".strtotime(date("Y-m-d 00:00:00"))."'";
			}else{
				$where.=" and `datetime` >= '".strtotime('-'.(int)$_GET['end'].'day')."'";
			}
			$urlarr['end']=(int)$_GET['end'];
		}
		if($_GET['order'])
		{
			$where.=" order by ".$_GET['t']." ".$_GET['order'];
			$urlarr['order']=$_GET['order'];
			$urlarr['t']=$_GET['t'];
		}else{
			$where.=" order by id desc";
		}
		$urlarr['page']="{{page}}";
		$pageurl=Url($_GET['m'],$urlarr,'admin');
        $M=$this->MODEL();
		$announcement=$M->get_page("admin_announcement",$where,$pageurl,$this->config['sy_listnum'],'*','announcement');
		$this->yunset($announcement);
		$this->yuntpl(array('siteadmin/admin_announcement_list'));
	}

	
	function add_action()
	{
		if((int)$_GET['id'])
		{
			$M=$this->MODEL("announcement");
			$announcement = $M->GetAnnouncementOne(array("id"=>(int)$_GET['id']));
			$announcement['content']=str_replace("&","&amp;",$announcement['content']);
			$this->yunset("announcement",$announcement);
		}
        $this->yuntpl(array('siteadmin/admin_announcement_add'));
	}

	
	function save_action()
	{
		if($_POST['submit'])
		{
			$data['title']=$_POST['title'];
			$data['datetime']=time();
			$data['keyword']=$_POST['keyword'];
			$data['description']=$_POST['description'];
			$data['content']=str_replace("&amp;","&",$_POST['content']));
			$M=$this->MODEL("announcement");
			if($_POST['id']){
				$nbid=$M->UpdateAnnouncement($data,array("id"=>$_POST['id']));
				$msg="更新";
			}else{
				$nbid=$M->AddAnnouncement($data);
				$msg="添加";
			}
			isset($nbid)?$this->ACT_layer_msg("公告(ID:".$_POST['id'].")".$msg."成功！",9,"index.php?m=admin_announcement",2,1):$this->ACT_layer_msg($msg."失败！",8,$_SERVER['HTTP_REFERER'],2,1);
		}
	}
	
	function del_action()
	{
		$this->check_token();
	    if($_GET['del'])
	    {
			$M=$this->MODEL("announcement");
			if(is_array($_GET['del'])){
				$del=pylode(',',$_GET['del']);
				$layer_status=1;
			}else{
				$del=(int)$_GET['del'];
				$layer_status=0;
			}
			$M->DeleteAnnouncement(array("`id` in (".$del.")"));
			$this->layer_msg('公告(ID:'.$del.')删除成功！',9,$layer_status,$_SERVER['HTTP_REFERER']);
	    }
	}
}
?>