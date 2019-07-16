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
class admin_trust_controller extends siteadmin_controller{ 
	function set_search(){
		$search_list[]=array('param'=>'status','name'=>'审核状态','value'=>array('1'=>'已审核','3'=>'未审核','2'=>'未通过'));
		$lo_time=array('1'=>'今天','3'=>'最近三天','7'=>'最近七天','15'=>'最近半月','30'=>'最近一个月');
		$search_list[]=array('param'=>'time','name'=>'发布时间','value'=>$lo_time);
		$this->yunset('search_list',$search_list);
	}
	function index_action(){
        $ResumeM=$this->MODEL('resume');
		$this->set_search();
		$where='1';
		if($_GET["status"] ){
			if($_GET["status"]==3){
				$where.=" and `status`=0";
			}else{
				$where.=" and `status`='".$_GET["status"]."'";
			}
			$urlarr["status"]=$_GET["status"];
		}
		if($_GET['time']){
			if($_GET['time']=='1'){
				$where.=" and `add_time` >= '".strtotime(date("Y-m-d 00:00:00"))."'";
			}else{
				$where.=" and `add_time` >= '".strtotime('-'.(int)$_GET['time'].'day')."'";
			}
			$urlarr['time']=$_GET['time'];
		}
		if($_GET["keyword"]!=""){
			if ($_GET['type']=='1'){
				$where.=" and `username` like '%".trim($_GET["keyword"])."%'";
			}elseif ($_GET['type']=='2'){
				$trustinfo=$ResumeM->GetResumeExpectList(array("`name` like '%".trim($_GET["keyword"])."%'"),array('field'=>"`uid`",'special'=>'resume_expect'));
				if (is_array($trustinfo)){
					foreach ($trustinfo as $val){
						$trustuids[]=$val['uid'];
					}
					$trustuid=@implode(",",$trustuids);
				}
				$where.=" and `uid` in (".$trustuid.") ";
			}
			$urlarr["type"]=$_GET["type"];
			$urlarr["keyword"]=$_GET["keyword"];
		} 
		if($_GET['order']){
			$where.=' order by `'.$_GET['order'].'`';
			$urlarr['order']=$_GET['order'];
		}else{
			$where.=' order by `add_time` ';
		}
		if($_GET['desc']){
			$where.=$_GET['desc'];
			$urlarr['desc']=$_GET['desc'];
		}else{
			$where.=' desc';
		}
		$urlarr['page']='{{page}}';
		$pageurl=Url($_GET['m'],$urlarr,'admin');
		$rows=$this->get_page("user_entrust",$where,$pageurl,$this->config["sy_listnum"]);
		if(is_array($rows)){
			$eid=array();
			foreach($rows as $val){
				$eid[]=$val['eid'];
			}
			$resume_expect=$this->obj->DB_select_all("resume_expect","`id` in(".pylode(",",$eid).")","`id`,`name`","resume_expect");
			foreach($rows as $key=>$value){
				foreach($resume_expect as $val){
					if($value['eid']==$val['id']){
						$rows[$key]['name']=$val['name'];
					}
				}
			}
		}
		$this->yunset(array('get_info'=>$_GET,'rows'=>$rows));
		$this->siteadmin_tpl(array('admin_trust'));
	}
	function status_action(){
		extract($_POST);
        $UserinfoM=$this->MODEL('userinfo');
        $ResumeM=$this->MODEL('resume');
		$user_entrust = $ResumeM->GetUserEntrustOne(array('id'=>$pid));
		if($status=='2'){
			$ResumeM->UpdateResumeExpect(array('is_entrust'=>0),array('uid'=>$user_entrust['uid'],'id'=>$user_entrust['eid']));
			if($user_entrust['0']){
				$UserinfoM->UpdateMemberstatis(array("`integral`=`integral`+'".$user_entrust['price']."'"),array('uid'=>$user_entrust['uid']));
			}
		}else{
			$ResumeM->UpdateResumeExpect(array('is_entrust'=>$status),array('uid'=>$user_entrust['uid'],'id'=>$user_entrust['eid']));
		}
		$id=$ResumeM->UpdateUserEntrust(array('status'=>$status),array('uid'=>$user_entrust['uid'],'id'=>$pid));
 		$id?$this->ACT_layer_msg( "委托简历审核(ID:".$user_entrust['id'].")设置成功！",9,$_SERVER['HTTP_REFERER'],2,1):$this->ACT_layer_msg( "设置失败！",8,$_SERVER['HTTP_REFERER']);
	}
	function recom_action(){
        $UserinfoM=$this->MODEL('userinfo');
        $ResumeM=$this->MODEL('resume');
        $this->yunset($this->MODEL('cache')->GetCache(array('job','com','city')));
		$urlarr['c']='recom';
		$row=$ResumeM->GetResumeExpectOne(array('id'=>$_GET['eid']));
		$user_entrust=$ResumeM->GetUserEntrustOne(array('id'=>$_GET['id']));
		$record=$ResumeM->GetEntrustRecordList(array('eid'=>$_GET['eid']),array('field'=>'`jobid`'));
		$jobid=array();
		if(is_array($record)){ 
			foreach($record as $v){
				$jobid[]=$v['jobid'];
			}
		}
		$where="`state`='1' and `edate`>'".time()."' and `r_status`<>'2'";
		$urlarr['eid']=$_GET['eid'];
		$urlarr['id']=$_GET['id'];
		if($_GET['salary']){
			$where.=" and `salary`='".$_GET['salary']."'";
			$urlarr['salary']=$_GET['salary'];
		}
		if($row['provinceid'] || $row['job_classid']){
			$where.=" and `provinceid`='".$row['provinceid']."' and `job_post` in(".pylode(',', $row['job_classid']).")";
			$urlarr['provinceid']=$_GET['provinceid'];
			$urlarr['job_post']=$_GET['job_post'];
		}
		if(is_array($jobid)){
			$where.=" and `id` not in (".@implode(",",$jobid).")";
		}
		$urlarr["page"]="{{page}}";
		$pageurl=Url('admin_trust',$urlarr,'admin');
		$rows=$UserinfoM->get_page('company_job',$where,$pageurl,$this->config['sy_listnum'],"`uid`,`name`,`hy`,`job1`,`job1_son`,`provinceid`,`cityid`,`salary`,`job_post`,`id`");
		$rows=$rows['rows'];
		if($rows&&is_array($rows)){
			foreach($rows as $val){
				$uids[]=$val['uid'];
			}
			$company=$UserinfoM->GetUserinfoList(array("`uid` in(".@implode(',',$uids).")"),array('field'=>"`uid`,`name`,`linkmail`",'usertype'=>2));
			foreach($rows as $key=>$val){
				foreach($company as $value){
					if($val['uid']==$value['uid']){
						$rows[$key]['bname']=$value['name'];
						$rows[$key]['mail']=$value['linkmail'];
					}
				}
			}
		}
		$this->yunset(array('rows'=>$rows,'row'=>$row));
		$this->siteadmin_tpl(array('admin_trust_recom'));
	}
	function directrecom_action(){
		$smtpusermail =$this->config['sy_smtpemail'];
 		if($_GET['eid']&&$_GET['jobid']){
            $UserinfoM=$this->MODEL('userinfo');
            $ResumeM=$this->MODEL('resume');
            $row=$ResumeM->GetEntrustRecordOne(array('jobid'=>$_GET['jobid'],'eid'=>$_GET['eid']));
			if(!empty($row)){
				$arr['msg']='请勿重复推荐！';
				$arr['type']='8';
			}
			$linkmail=$UserinfoM->GetUserinfoOne(array('uid'=>$_GET['comid']),array('field'=>"`linkmail`,`uid`,`did`",'usertype'=>2));
			
			$contents=file_get_contents($this->config['sy_weburl']."/index.php?m=resume&c=sendresume&id=".$_GET['eid']."&type=charge");
			
			$emailData['email'] = $linkmail['linkmail'];
			$emailData['subject'] = $this->config['sy_webname']."向您推荐了简历！";
			$emailData['content'] = $contents;
			$notice = $this->MODEL('notice');
			$sendid = $notice->sendEmail($emailData);

			if($sendid['status'] != -1){
				$ResumeM->AddEntrustRecord(array('jobid'=>$_GET['jobid'],'eid'=>$_GET['eid'],'comid'=>$_GET['comid'],'ctime'=>time(),'did'=>$linkmail['did']));
				$arr['msg']='邮件发送成功！';
				$arr['type']='9';
			}else{
				$arr['msg']='邮件发送错误 原因：' . $sendid['msg'];
				$arr['type']='8';
			}
			echo json_encode($arr);die;
		}
	}
	function del_action(){
		$this->check_token();
		if(is_array($_GET['del'])){
			$linkid=@implode(',',$_GET['del']);
			$layer_type=1;
		}else if($_GET["id"]){
			$linkid=$_GET["id"];
			$layer_type=0;
		}
        $ResumeM=$this->MODEL('resume');
		$eid=$this->obj->DB_select_all("user_entrust","`id` in (".$linkid.")","`eid`");

		if(is_array($eid)&&$eid){
			foreach($eid as $val){
				$eids[]=$val['eid'];
			}
			$this->obj->DB_update_all("resume_expect","`is_entrust`='0'","`id` in(".@implode(',',$eids).")","resume_expect");
		}
		$del=$ResumeM->DeleteUserEntrust(array("`id` in (".$linkid.")"));
		$del?$this->layer_msg('委托简历(ID:'.$linkid.')删除成功！',9,$layer_type,$_SERVER['HTTP_REFERER']):$this->layer_msg('删除失败！',8,$layer_type,$_SERVER['HTTP_REFERER']);
	}
}
?>