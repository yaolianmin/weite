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
class warning_controller extends adminCommon{
	function index_action()
	{
		$where = "1";
		if(($_GET['date'])&&$_GET['time']<1){
			$times=@explode('~',$_GET['date']);
			$where.=" and `ctime` >= '".strtotime($times[0]." 00:00:00")."' and `ctime`<'".strtotime($times[1]." 23:59:59")."'";
			$urlarr['date']=$_GET['date'];
		}
		$urlarr["page"]="{{page}}";
		$pageurl=Url($_GET['m'],$urlarr,'admin');
		$list=$this->get_page("warning",$where." order by `id` desc",$pageurl,$this->config["sy_listnum"]);
		if(is_array($list))
		{
			foreach($list as $v)
			{
				$uid[]=$v['uid'];
			}
			$member=$this->obj->DB_select_all("member","`uid` in (".@implode(",",$uid).")","`uid`,`username`");
			foreach($list as $k=>$v)
			{
				foreach($member as $val)
				{
					if($v['uid']==$val['uid'])
					{
						$list[$k]['username']=$val['username'];
					}
				}
			}
		}
		$this->yunset("list",$list);
		$this->yuntpl(array('admin/admin_warning'));
	}
	function config_action() 
	{
 		if($_POST['config'])
 		{
			unset($_POST['config']);
		    foreach($_POST as $key=>$v)
		    {
		    	$config=$this->obj->DB_select_num("admin_config","`name`='$key'");
			   	if($config==false){
					$this->obj->DB_insert_once("admin_config","`name`='$key',`config`='".$v."'");
			  	}else{
					$this->obj->DB_update_all("admin_config","`config`='".$v."'","`name`='$key'");
				}
			 }
			$this->web_config();
			$this->ACT_layer_msg("预警配置修改成功！",9,1,2,1);
		}
		$this->yuntpl(array('admin/admin_warning_config'));
	}
	function del_action()
	{
		$this->check_token();
		 
	    if($_GET['del'])
	    {
	    	$del=$_GET['del'];
	    	if(is_array($del))
	    	{
				$del=@implode(",",$del);
				$layer_status=1;
	    	}else{
	    		$layer_status=0;
	    	}
			$this->obj->DB_delete_all("warning","`id` in (".$del.")","");
    		$this->layer_msg("预警信息删除(ID:".@implode(',',$del).")成功！",9,$layer_status,$_SERVER['HTTP_REFERER']);
    	}else{
			$this->layer_msg("请选择您要删除的信息！",8,1,$_SERVER['HTTP_REFERER']);
    	}
	}

}
?>