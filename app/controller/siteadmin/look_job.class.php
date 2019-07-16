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
class look_job_controller extends siteadmin_controller{
	function set_search(){ 
		$ad_time=array('1'=>'今天','3'=>'最近三天','7'=>'最近七天','15'=>'最近半月','30'=>'最近一个月');
		$search_list[]=array("param"=>"end","name"=>'浏览时间',"value"=>$ad_time);
		$this->yunset("search_list",$search_list);
	}
	function index_action(){
		$this->set_search();
		$where = "1";
		$UserInfoM=$this->MODEL('userinfo');
		$JobM=$this->MODEL('job');
		if((int)$_GET['end']){
			if((int)$_GET['end']=='1'){
				$where.=" and `datetime` >= '".strtotime(date("Y-m-d 00:00:00"))."'";
			}else{
				$where.=" and `datetime` >= '".strtotime('-'.(int)$_GET['end'].'day')."'";
			}
			$urlarr['end']=(int)$_GET['end'];
		}
		if(trim($_GET['keyword'])){
			if($_GET['type']=="1"){
				$member=$UserInfoM->GetMemberList(array("`username` like '%".trim($_GET['keyword'])."%'","usertype"=>'1'),array("field"=>"`uid`,`username`"));
				if(is_array($member)){
					foreach($member as $v){
						$uid[]=$v['uid'];
					}
				}
				$where.=" and `uid` in (".@implode(",",$uid).")";
			}else{
				$jobid=$comid=array();
				if($_GET['type']=="2"){
					$job=$JobM->GetComjobList(array("`name` like '%".trim($_GET['keyword'])."%'"),array("field"=>"name,uid,com_name,id",'special'=>'1'));
					if(is_array($job)){
						foreach($job as $v){
							$jobid[]=$v['id'];
						}
					}
					 
					$where.=" and `jobid` in (".@implode(",",$jobid).")";
				}elseif($_GET['type']=="3"){
					$job=$JobM->GetComjobList(array("`com_name` like '%".trim($_GET['keyword'])."%'"),array("field"=>"name,uid,com_name,id"));
					if(is_array($job)){
						foreach($job as $v){
							$comid[]=$v['uid'];
						}
					}
					$where.=" and `com_id` in (".@implode(",",$comid).")";
				}

			}
			$urlarr['type']=$_GET['type'];
			$urlarr['keyword']=$_GET['keyword'];
		}
		if($_GET['sdate']){
			$sdate=strtotime($_GET['sdate']);
			$where.=" and `datetime`>'$sdate'";
			$urlarr['sdate']=$_GET['sdate'];
		}
		if($_GET['edate']){
			$edate=strtotime($_GET['edate']);
			$where.=" and `datetime`<'$edate'";
			$urlarr['edate']=$_GET['edate'];
		}
		if($_GET['order']){
			$where.=" order by ".$_GET['t']." ".$_GET['order'];
			$urlarr['order']=$_GET['order'];
			$urlarr['t']=$_GET['t'];
		}else{
			$where.=" order by id desc";
		}
		$urlarr["page"]="{{page}}";
		$pageurl=Url($_GET['m'],$urlarr,'admin');
		$M=$this->MODEL();
		$rows=$M->get_page("look_job",$where,$pageurl,$this->config['sy_listnum']);
		if(is_array($rows['rows'])&&$rows['rows']){
			$uids=$jobid=array();
			foreach($rows['rows'] as $v){
				if(in_array($v['uid'],$uids)==false){$uids[]=$v['uid'];}
				if(in_array($v['jobid'],$jobid)==false){$jobid[]=$v['jobid'];} 
			}
			if($_GET['type']!="1" || !trim($_GET['keyword'])){
				$member=$UserInfoM->GetMemberList(array("`uid` in (".@implode(",",$uids).")"),array("field"=>"`uid`,`username`","special"=>'member'));
			}
			if(($_GET['type']!="2" && $_GET['type']!="3") || !trim($_GET['keyword'])){ 
				$job=$JobM->GetComjobList(array("`id` in (".@implode(",",$jobid).")"),array("field"=>"`name`,`uid`,`com_name`,`id`","special"=>'look_job')); 
			}
			foreach($rows['rows'] as $k=>$v){
				foreach($member as $val){
					if($v['uid']==$val['uid']){
						$rows['rows'][$k]['username']=$val['username'];
					}
				}
				foreach($job as $val){
					if($v['jobid']==$val['id']){
						$rows['rows'][$k]['job_name']=$val['name'];
						$rows['rows'][$k]['com_name']=$val['com_name'];
					}
				}
			}
		}
		$this->yunset($rows);
		$this->siteadmin_tpl(array('look_job'));
	}
	function del_action(){
		$this->check_token();
	    if($_GET['del']){
	    	if(is_array($_GET['del'])){
	    		$del=@implode(",",$_GET['del']);
	    		$layer_status=1;
	    	}else{
	    		$del=$_GET['del'];
	    		$layer_status=0;
	    	}
			$this->obj->DB_delete_all("look_job","`id` in (".$del.")","");
			$this->layer_msg( "职位浏览记录(ID:".$del.")删除成功！",9,$layer_status,$_SERVER['HTTP_REFERER']);
	   }else{
			$this->layer_msg( "请选择您要删除的信息！",8,1,$_SERVER['HTTP_REFERER']);
    	}
	}
}
?>