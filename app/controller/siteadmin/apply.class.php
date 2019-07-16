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
class apply_controller extends siteadmin_controller
{
	function set_search(){
		$search_list[]=array("param"=>"browse","name"=>'是否查看',"value"=>array("1"=>"未查看","2"=>"已查看"));
		$ad_time=array('1'=>'今天','3'=>'最近三天','7'=>'最近七天','15'=>'最近半月','30'=>'最近一个月');
		$search_list[]=array("param"=>"end","name"=>'申请时间',"value"=>$ad_time);
		$this->yunset("search_list",$search_list);
	}
	function index_action() {
		$where = "1";
		$this->set_search();
		$UserInfoM=$this->MODEL('userinfo');
		$ResumeM=$this->MODEL('resume');

		if(trim($_GET['keyword']))
		{
			if($_GET['type']=="1")
			{
				$where.=" and `job_name` like '%".trim($_GET['keyword'])."%'";
			}elseif ($_GET['type']=="2"){
				$where.=" and `com_name` like '%".trim($_GET['keyword'])."%'";
			}elseif ($_GET['type']=="3"){
				$member=$UserInfoM->GetMemberList(array("`username` like '%".trim($_GET['keyword'])."%'"),array("field"=>"`uid`,`username`","special"=>'member'));
				if(is_array($member)){
					foreach ($member as $v){
						$uid[]=$v['uid'];
					}
				}
				$where.=" and `uid` in (".@implode(",",$uid).")";
			}elseif ($_GET['type']=="4"){
				$expect=$ResumeM->GetResumeExpectList(array("`name` like '%".trim($_GET['keyword'])."%'"),array("field"=>"`uid`,`name`,`id`","special"=>'resume_expect'));
				if(is_array($expect)){
					foreach ($expect as $v){
						$eid[]=$v['uid'];
					}
				}
				$where.=" and `eid` in (".@implode(",",$eid).")";
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
		if($_GET['browse']){
			$where.=" and `is_browse`= '".$_GET['browse']."'";
			$urlarr['browse']=$_GET['browse'];
		}
		if($_GET['sdate'])
		{
			$sdate=strtotime($_GET['sdate']);
			$where.=" and `datetime`>'$sdate'";
		}
		if($_GET['edate'])
		{
			$edate=strtotime($_GET['edate']);
			$where.=" and `datetime`<'$edate'";
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
		$rows=$M->get_page("userid_job",$where,$pageurl,$this->config['sy_listnum']);
		if(is_array($rows['rows'])&&$rows['rows']){
			foreach($rows['rows'] as $v){
				$uid[]=$v['uid'];
				$eid[]=$v['eid'];
			}
			if($_GET['type']!='3'){
				$member=$UserInfoM->GetMemberList(array("`uid` in(".@implode(',',$uid).")"),array("field"=>"`uid`,`username`","special"=>'member'));
			}
			if($_GET['type']!='4'){
				$expect=$ResumeM->GetResumeExpectList(array("`name` like '%".trim($_GET['keyword'])."%'"),array("field"=>"`uid`,`name`,`id`","special"=>'resume_expect'));
			}

			foreach($rows['rows'] as $k=>$v){
				foreach($member as $val){
					if($v['uid']==$val['uid']){
						$rows['rows'][$k]['username']=$val['username'];
					}
				}
				foreach($expect as $val){
					if($v['eid']==$val['id']){
						$rows['rows'][$k]['resume']=$val['name'];
					}
				}
			}
		}
		$this->yunset($rows);
		$this->siteadmin_tpl(array('apply'));
	}
	function del_action(){
		$this->check_token();
	    if($_GET['del']){
			$CompanyM=$this->MODEL('company');
	    	if(is_array($_GET['del'])){
	    		$del=@implode(",",$_GET['del']);
	    		$layer_status=1;
	    	}else{
	    		$del=$_GET['del'];
	    		$layer_status=0;
	    	}
			$CompanyM->DeleteUserJob(array("`id` in (".$del.")"));
			$this->layer_msg( "职位申请记录(ID:".$del.")删除成功！",9,$layer_status,$_SERVER['HTTP_REFERER']);
	   }else{
			$this->layer_msg( "请选择您要删除的信息！",8,1,$_SERVER['HTTP_REFERER']);
    	}
	}
}
?>