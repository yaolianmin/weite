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
class down_controller extends siteadmin_controller{ 
	function set_search(){
		$lo_time=array('1'=>'今天','3'=>'最近三天','7'=>'最近七天','15'=>'最近半月','30'=>'最近一个月');
		$search_list[]=array('param'=>'time','name'=>'下载时间','value'=>$lo_time);
		$this->yunset('search_list',$search_list);
	}
	function index_action(){
		$where = "1";
		$this->set_search();
        $UserinfoM=$this->MODEL('userinfo');
        $ResumeM=$this->MODEL('resume');
		if(trim($_GET['keyword'])){
			if($_GET['type']=="1"){
				$info=$UserinfoM->GetMemberList(array("`username` like '%".trim($_GET['keyword'])."%'"),array('field'=>"`uid`",'special'=>'member'));
				if(is_array($info)){
					foreach ($info as $v){
						$comid[]=$v['uid'];
					}
				}
				$where.=" and `comid` in (".@implode(",",$comid).")";
			}elseif ($_GET['type']=="2"){
				$info=$UserinfoM->GetUserinfoList(array("`name` like '%".trim($_GET['keyword'])."%'"),array('field'=>"`uid`",'usertype'=>2,'special'=>'company'));
				if(is_array($info)){
					foreach ($info as $v){
						$comid[]=$v['uid'];
					}
				}
				$where.=" and `comid` in (".@implode(",",$comid).")";
			}elseif ($_GET['type']=="3"){
				$info=$UserinfoM->GetMemberList(array("`username` like '%".trim($_GET['keyword'])."%'"),array('field'=>"`uid`",'special'=>'member'));
				if(is_array($info)){
					foreach ($info as $v){
						$uid[]=$v['uid'];
					}
				}
				$where.=" and `uid` in (".@implode(",",$uid).")";
			}elseif ($_GET['type']=="4"){
				$info=$ResumeM->GetResumeExpectList(array("`name` like '%".trim($_GET['keyword'])."%'"),array('field'=>"`id`",'special'=>'resume'));
				if(is_array($info)){
					foreach ($info as $v){
						$eid[]=$v['id'];
					}
				}
				$where.=" and `eid` in (".@implode(",",$eid).")";
			}
			$urlarr['type']=$_GET['type'];
			$urlarr['keyword']=$_GET['keyword'];
		}
		if($_GET['time']){
			if($_GET['time']=='1'){
				$where.=" and `downtime` >= '".strtotime(date("Y-m-d 00:00:00"))."'";
			}else{
				$where.=" and `downtime` >= '".strtotime('-'.(int)$_GET['time'].'day')."'";
			}
			$urlarr['time']=$_GET['time'];
		}
		if($_GET['sdate']){
			$sdate=strtotime($_GET['sdate']);
			$where.=" and `downtime`>'$sdate'";
		}
		if($_GET['edate'])
		{
			$edate=strtotime($_GET['edate']);
			$where.=" and `downtime`<'$edate'";
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
		$list=$this->get_page('down_resume',$where,$pageurl,$this->config['sy_listnum']);
		if(is_array($list)){
			foreach($list as $v){
				$eid[]=$v['eid'];
				$uid[]=$v['uid'];
				$uid[]=$v['comid'];
				$comid[]=$v['comid'];
			}
			$resume=$ResumeM->GetResumeExpectList(array("`id` in (".@implode(",",$eid).")"),array('field'=>"name,id","special"=>'resume_expect'));
			$member=$UserinfoM->GetMemberList(array("`uid` in (".@implode(",",$uid).")"),array('field'=>"username,uid,usertype","special"=>'member'));
			$company=$UserinfoM->GetUserinfoList(array("`uid` in (".@implode(",",$comid).")"),array('field'=>"name,uid",'usertype'=>2,"special"=>'company'));
			
			
			$lt=$UserinfoM->GetUserinfoList(array("`uid` in (".@implode(",",$comid).")"),array('field'=>"realname,uid",'usertype'=>3,"special"=>'lt_info'));
			foreach($list as $k=>$v){
				foreach($company as $val){
					if($v['comid']==$val['uid']){
						$list[$k]['com_name']=$val['name'];
					}
				}
				foreach($statis as $val){
					if($v['comid']==$val['uid']){
						$list[$k]['rating']=$val['rating'];
					}
				}
				foreach($lt_statis as $val){
					if($v['comid']==$val['uid']){
						$list[$k]['rating']=$val['rating'];
					}
				}
				foreach($lt as $val){
					if($v['comid']==$val['uid']){
						$list[$k]['com_name']=$val['realname'];
					}
				}
				foreach($resume as $val){
					if($v['eid']==$val['id']){
						$list[$k]['resume']=$val['name'];
					}
				}
				foreach($member as $val){
					if($v['uid']==$val['uid']){
						$list[$k]['username']=$val['username'];
					}
					if($v['comid']==$val['uid']){
						$list[$k]['com_username']=$val['username'];
						$list[$k]['usertype']=$val['usertype'];
					}
				}
			}
		}
		$this->yunset('list',$list);
		$this->siteadmin_tpl(array('down'));
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
			$this->MODEL('resume')->DeleteDownResume(array("`id` in (".$del.")"));
			$this->layer_msg( "下载记录(ID:".$del.")删除成功！",9,$layer_status,$_SERVER['HTTP_REFERER']);
	   }else{
			$this->layer_msg( "请选择您要删除的信息！",8,1,$_SERVER['HTTP_REFERER']);
    	}
	}
}
?>