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
class lt_talent_controller extends adminCommon{
 
	function set_search(){
		include PLUS_PATH."/user.cache.php";
     
		$this->yunset("search_list",$search_list);
	}
	function index_action(){
		 
		 
		$where="1 ";
		
		if($_GET['news_search']!=""){
			if (trim($_GET['keyword'])!=''){
				 if ($_GET['searchrname']=='2'){
				    $where.=" and `name` like '%".trim($_GET['keyword'])."%'";
			     }elseif ($_GET['searchrname']=='3'){
				    $where.=" and `jobname` like '%".trim($_GET['keyword'])."%'";
			     }elseif ($_GET['searchrname']=='1'){
				     $meminfo=$this->obj->DB_select_all("member","`usertype`='3' AND `username` like '%".trim($_GET['keyword'])."%'","`uid`");
					  if (is_array($meminfo)){
					         foreach ($meminfo as $k=>$v){
						     $memuids[]=$v['uid'];
					     }
					  $mems=@implode(",",$memuids);
				    }
				   $where.=" and `uid` in (".$mems.")";
			      }
			      $urlarr['keyword']=$_GET['keyword'];
			      $urlarr['searchrname']=$_GET['searchrname'];
			}
			$urlarr['news_search']=$_GET['news_search'];
		}
		
		 
		if($_GET['order']){
			if($_GET['t']=="time"){
				$where.=" order by `status_time` ".$_GET['order'];
			}else{
				$where.=" order by ".$_GET['t']." ".$_GET['order'];
			}
			$urlarr['order']=$_GET['order'];
			$urlarr['t']=$_GET['t'];
		}else{
			$where.=" order by `id` desc";
		}
		$urlarr['page']="{{page}}";
		$pageurl=Url($_GET['m'],$urlarr,'admin');
		$rows=$this->get_page("lt_talent",$where,$pageurl,$this->config['sy_listnum']);
		include PLUS_PATH."/user.cache.php";
		include PLUS_PATH."/city.cache.php";
		include PLUS_PATH."/industry.cache.php";
	 
		if(is_array($rows)){
			foreach($rows as $key=>$value){
				$id[] = $value['id'];
				$uid[] = $value['uid'];
			}
			if(empty($meminfo)){
				$meminfo = $this->obj->DB_select_all('member',"`uid` IN (".implode(',',$uid).")");
			}
			$rewardList = $this->obj->DB_select_all('company_job_rewardlist',"`eid` IN (".pylode(',',$id).") AND `status` NOT IN ('18','19','20','21','23','26','27','28','29')");
			
			if(is_array($rewardList)){ 
				foreach($rewardList as $key=>$value){
					$rewardStatusId[] = $value['eid'];
				}
				
				foreach($meminfo as $key=>$value){
					$minfo[$value['uid']] = $value['username'];
				}
				foreach($rows as $key=>$value){
					if(in_array($value['id'],$rewardStatusId)){
						$rows[$key]['rewardstatus'] = '1';
					}
					$rows[$key]['user'] = $minfo[$value['uid']];
				}
				
			}
			
		}
		$this->yunset($this->MODEL('cache')->GetCache(array('city','user')));
		$this->yunset("get_type", $_GET);
		$this->yunset("rows",$rows);
		$this->yuntpl(array('admin/admin_lt_talent'));
	}
	function show_action(){
		if($_GET['id']){
			
			$talentM = $this->MODEL('talent');
			$Info = $talentM->getTalent($_GET['uid'],$_GET['id'],'1');
			$this->yunset("Info", $Info);
			$this->yuntpl(array('admin/admin_lt_talent_show'));
		}
	}
	function status_action()
	{
		if($_POST['pid'])
		{
			if(!$_POST['status']){
				$_POST['status'] = 0;
			}
			$this->obj->DB_update_all("lt_talent","`status`='".$_POST['status']."'","`id` IN (".$_POST['pid'].")");
			$this->MODEL('log')->admin_log("简历库(ID:".$_POST['pid'].")审核设置成功");
			$this->ACT_layer_msg("审核设置成功！",9,$_SERVER['HTTP_REFERER']);
		}else{
			$this->ACT_layer_msg("审核设置失败！",8,$_SERVER['HTTP_REFERER']);
		}
	}
	function recommend_action(){
		$nid=$this->obj->DB_update_all("resume_expect","`rec`='".$_GET['rec']."'","`id`='".$_GET['id']."'");
		$this->MODEL('log')->admin_log("高级人才(ID:".$_GET['id'].")推荐成功");
		echo $nid?1:0;die;
	}

	function del_action(){
		$this->check_token();
	 
	    if($_GET['del']){
	    	$del=$_GET['del'];
	    	if($del){
	    		if(is_array($del)){
					$this->obj->DB_delete_all("lt_talent","`id` in(".@implode(',',$del).")");
				 
					$this->obj->DB_delete_all("company_job_rewardlist","`eid` in(".@implode(',',$del).")");
					$this->obj->DB_delete_all("company_job_rewardlog","`eid` in(".@implode(',',$del).")");
					$del=@implode(',',$del);
					$layer_type='1';
		    	}else{
		    		$this->obj->DB_delete_all("lt_talent","`id`='".$_GET['del']."'");
					$this->obj->DB_delete_all("company_job_rewardlist","`eid`='".$_GET['del']."'");
					$this->obj->DB_delete_all("company_job_rewardlog","`eid`='".$_GET['del']."'");
					$layer_type='0';
		    	}
				$this->layer_msg('猎头简历库简历(ID:'.$del.')删除成功！',9,$layer_type,$_SERVER['HTTP_REFERER']);
	    	}else{
				$this->layer_msg('请选择您要删除的猎头简历库简历！',8,1,$_SERVER['HTTP_REFERER']);
	    	}
	    }
	}
}
?>