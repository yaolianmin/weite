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
class admin_lt_xuanshang_controller extends siteadmin_controller
{
	
	function set_search(){
		$search_list[]=array("param"=>"look","name"=>'信息状态',"value"=>array("1"=>"已查看","0"=>"未查看"));
		$da_time=array('1'=>'今天','3'=>'最近三天','7'=>'最近七天','15'=>'最近半月','30'=>'最近一个月');
		$re_time=array('1'=>'今天','3'=>'最近三天','7'=>'最近七天','15'=>'最近半月','30'=>'最近一个月');
		$search_list[]=array("param"=>"end","name"=>'推荐时间',"value"=>$da_time);
		$search_list[]=array("param"=>"reply","name"=>'回复时间',"value"=>$re_time);
		$this->yunset("search_list",$search_list);
	}
	function index_action()
	{
		$this->set_search();
		include(PLUS_PATH."user.cache.php");
		include(PLUS_PATH."com.cache.php");
		$where = "1";
		if(trim($_GET['keyword'])!="")
		{
			if($_GET['type']=="1" || $_GET['type']=="4")
			{
				$infouid = $this->obj->DB_select_all("member","`username` like '%".trim($_GET['keyword'])."%'","`uid`");
				if(is_array($infouid))
				{
					foreach($infouid as $k=>$v)
					{
						$info_uids[] = $v['uid'];
					}
					$uids = @implode(",",$info_uids);
				}
				if ($_GET['type']=="1")
				{
					$where.=" and `uid` in (".$uids.")";
				}else{
					$where.=" and `job_uid` in (".$uids.")";
				}
			}
			$urlarr['type']=$_GET['type'];
			$urlarr['keyword']=$_GET['keyword'];
		}
		if($_GET['end']){
			if($_GET['end']=='1'){
				$where.=" and `datetime` >= '".strtotime(date("Y-m-d 00:00:00"))."'";
			}else{
				$where.=" and `datetime` >= '".strtotime('-'.(int)$_GET['end'].'day')."'";
			}
			$urlarr['end']=$_GET['end'];
		}
		if($_GET['reply']){
			if($_GET['reply']=='1'){
				$where.=" and `reply_time` >= '".strtotime(date("Y-m-d 00:00:00"))."'";
			}else{
				$where.=" and `reply_time` >= '".strtotime('-'.(int)$_GET['reply'].'day')."'";
			}
			$urlarr['reply']=$_GET['reply'];
		}
        if($_GET['look']=='1'){
       	   $where.=" and `status`='1'";
        }elseif ($_GET['look']=='0'){
       	   $where.=" and  `status`='0'";
        }

        if($_GET['status']=="1")
        {
			$where = "`edate`>'".time()."'";
		}elseif($_GET['status']=="2"){
			$where = "`edate`<'".time()."'";
		}elseif($_GET['status']=="3"){
			$where = "`status`='3'";
		}elseif($_GET['status']=="4"){
			$where = "`status`='0'";
		}
		if($_GET['order'])
		{
			$where.=" order by ".$_GET['t']." ".$_GET['order'];
			$urlarr['order']=$_GET['order'];
			$urlarr['t']=$_GET['t'];
		}else{
			$where.=" order by id desc";
		}
		$urlarr["page"]="{{page}}";
		$pageurl=Url($_GET['m'],$urlarr,'admin');
        $M=$this->MODEL();
		$PageInfo=$M->get_page("rebates",$where,$pageurl,$this->config['sy_listnum']);
        $this->yunset($PageInfo);
        $list=$PageInfo['rows'];
		if(is_array($list))
		{
			foreach($list as $v)
			{
				$uid[]=$v['job_uid'];
				$uid[]=$v['uid'];
			}
		    $statis =$this->obj->DB_select_all("member","`uid` in (".@implode(",",$uid).")","`username`,`uid`");
			foreach($list as $key=>$value)
			{
				foreach($statis as $k=>$v)
				{
					if($value['job_uid']==$v['uid'])
					{
						  $list[$key]['name'] = $v['username'];
					}
					if($value['uid']==$v['uid'])
					{
						  $list[$key]['username'] = $v['username'];
					}
				}
			}
		}
		$this->yunset("get_type", $_GET['type']);
		$this->yunset("list",$list);
		$this->siteadmin_tpl(array('admin_lt_xuanshang'));
	}

	function del_action(){
		$this->check_token();
		
	    if($_GET['del']){
	    	$del=$_GET['del'];
	    	if($del){
	    		if(is_array($del)){
					$this->obj->DB_delete_all("rebates","`id` in(".@implode(',',$del).")","");
		    	}else{
		    		$this->obj->DB_delete_all("rebates","`id`='".$del."'");
		    	}
	    		$this->layer_msg( "猎头悬赏(ID:".@implode(',',$del).")删除成功！",9,1,$_SERVER['HTTP_REFERER']);
	    	}else{
				$this->layer_msg( "请选择您要删除的信息！",8,1,$_SERVER['HTTP_REFERER']);
	    	}
	    }
		
	    if(isset($_GET['id'])){
			$result=$this->obj->DB_delete_all("rebates","`id`='".$_GET['id']."'" );
 			isset($result)?$this->layer_msg('猎头悬赏(ID:'.$_GET['id'].')删除成功！',9,0,$_SERVER['HTTP_REFERER']):$this->layer_msg('删除失败！',8,0,$_SERVER['HTTP_REFERER']);
		}else{
			$this->layer_msg('非法操作！',8);
		}
	}

}
?>