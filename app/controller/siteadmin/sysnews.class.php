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
class sysnews_controller extends siteadmin_controller
{
	function set_search(){
		$ad_time=array('1'=>'今天','3'=>'最近三天','7'=>'最近七天','15'=>'最近半月','30'=>'最近一个月');
		$search_list[]=array("param"=>"end","name"=>'发布时间',"value"=>$ad_time);
		$this->yunset("search_list",$search_list);
	}
	function index_action()
	{
		$this->set_search();
		$where = "1";
		if(trim($_GET['keyword']))
		{
			if((int)$_GET['type']=="1"){
				$where.=" AND `username` like '%".trim($_GET['keyword'])."%'";
			}else{
				$where.=" AND `content` like '%".trim($_GET['keyword'])."%'";
			}
			$urlarr['keyword']=$_GET['keyword'];
			$urlarr['type']=(int)$_GET['type'];
		}
		if((int)$_GET['end']){
			if((int)$_GET['end']=='1'){
				$where.=" and `ctime` >= '".strtotime(date("Y-m-d 00:00:00"))."'";
			}else{
				$where.=" and `ctime` >= '".strtotime('-'.(int)$_GET['end'].'day')."'";
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
        $urlarr['status']=$_GET['status'];
        $urlarr['order']=$_GET['order'];
		$urlarr['page']="{{page}}";
		$pageurl=Url($_GET['m'],$urlarr,'admin');
		$rows=$this->get_page("sysmsg",$where,$pageurl,$this->config['sy_listnum']);
		$this->siteadmin_tpl(array('sysnews'));
	}
   function add_action()
   {
		if($_POST['submit'])
		{
			if($_POST['content']=="")
			{
				$this->ACT_layer_msg("请填写内容！",8,"index.php?m=sysnews&c=add");
			}
			if($_POST['all']=="5")
			{
				$userarr=@explode(",",$_POST['userarr']);
				foreach($userarr as $v)
				{
					$where[]=" `username`='".$v."'";
				}
				$where=@implode(" or ",$where);
			}else{
				$where="`usertype`='".$_POST['all']."'";
			}
			$member=$this->obj->DB_select_all("member",$where,"`uid`,`username`");
			if(!empty($member))
			{
				$data['content']=$_POST['content'];
				$data['ctime']=time();
				foreach($member as $v)
				{
					$data['fa_uid']=$v['uid'];
					$data['username']=$v['username'];
					$this->obj->insert_into("sysmsg",$data);
				}
				$this->ACT_layer_msg("系统消息发送(ID:$nid)成功！",9,$_SERVER['HTTP_REFERER'],2,1);
			}else{
				$this->ACT_layer_msg("用户不存在！",8,"index.php?m=sysnews&c=add");
			}
		}
		$this->siteadmin_tpl(array('sysnews_add'));
	}
	function del_action()
	{
		$this->check_token();
	    if($_GET['del'])
	    {
	    	$del=$_GET['del'];
	    	if($_GET['del'])
	    	{
	    		if(is_array($_GET['del']))
	    		{
					$del=@implode(',',$_GET['del']);
					$layer_type='1';
		    	}else{
					$layer_type='0';
		    	}
		    	$this->obj->DB_delete_all("sysmsg","`id` in (".$del.")","");
	    		$this->layer_msg("系统消息(ID:".$del.")删除成功！",9,$layer_type,$_SERVER['HTTP_REFERER']);
	    	}else{
	    		$this->layer_msg("请选择您要删除的信息！",8,1,$_SERVER['HTTP_REFERER']);
	    	}
	    }
	}
}