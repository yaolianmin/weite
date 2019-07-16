<?php

class admin_trust_record_controller extends siteadmin_controller{
	function index_action(){
		$where = "1";
        $UserinfoM=$this->MODEL('userinfo');
        $ResumeM=$this->MODEL('resume');
        $JobM=$this->MODEL('job');
		if(trim($_GET['keyword'])!=""){
			if($_GET['type']=="1"||$_GET['type']==""){
				$resume=$ResumeM->GetResumeExpectList(array("`name` like '%".trim($_GET['keyword'])."%'"),array('field'=>"`id`,`name`",'special'=>'resume_expect'));
				if(is_array($resume)){
					foreach($resume as $v){
						$eid[]=$v['id'];
					}
				}
				$where.=" and `eid` in (".@implode(",",$eid).")";
			}else{
				if($_GET['type']=="2"){
					$jobwhere="`com_name` like '%".trim($_GET['keyword'])."%'";
				}else{
					$jobwhere="`name` like '%".trim($_GET['keyword'])."%'";
				}
				$job=$JobM->GetComjobList(array($jobwhere),array('field'=>"`id`,`name`,`com_name`",'special'=>'company_job'));
				if(is_array($job)){
					foreach($job as $v){
						$jobid[]=$v['id'];
					}
				}
				$where.=" and `jobid` in (".@implode(",",$jobid).")";
			}
			$urlarr['type']=$_GET['type'];
			$urlarr['keyword']=$_GET['keyword'];
		}
		$urlarr["page"]="{{page}}";
		$pageurl=Url($_GET['m'],$urlarr,'admin');
		$list=$this->get_page('user_entrust_record',$where." order by `id` desc",$pageurl,$this->config['sy_listnum']);
		if(is_array($list)){
			foreach($list as $v){
				$eid[]=$v['eid'];
				$jobid[]=$v['jobid'];
			}
			if(trim($_GET['keyword'])!=""){
				if($_GET['type']=="1" || $_GET['type']==""){
					$job=$JobM->GetComjobList(array("`id` in (".@implode(",",$jobid).")"),array("`id`,`name`,`com_name`",'special'=>'company_job'));
				}else{
					$resume=$ResumeM->GetResumeExpectList(array("`id` in (".@implode(",",$eid).")"),array('field'=>"`id`,`name`",'special'=>'resume_expect'));
				}
			}else{
				$resume=$ResumeM->GetResumeExpectList(array("`id` in (".@implode(",",$eid).")"),array('field'=>"`id`,`name`",'special'=>'resume_expect'));
				$job=$JobM->GetComjobList(array("`id` in (".@implode(",",$jobid).")"),array("`id`,`name`,`com_name`",'special'=>'company_job'));
			}
			foreach($list as $k=>$v){
				foreach($resume as $val){
					if($v['eid']==$val['id']){
						$list[$k]['resume_name']=$val['name'];
					}
				}
				foreach($job as $val){
					if($v['jobid']==$val['id']){
						$list[$k]['job_name']=$val['name'];
						$list[$k]['com_name']=$val['com_name'];
					}
				}
			}
		}
		$this->yunset('list',$list);
		$this->siteadmin_tpl(array('admin_trust_record'));
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
	    	$this->MODEL('resume')->DeleteEntrustRecord(array('`id` in ('.$del.')'));
	    	$this->layer_msg( "简历推送(ID:".@implode(',',$del).")删除成功！",9,$layer_status,$_SERVER['HTTP_REFERER']);
	    }else{
	    	$this->ACT_layer_msg("非法操作！",8,$_SERVER['HTTP_REFERER']);
	    }
	}
}
?>