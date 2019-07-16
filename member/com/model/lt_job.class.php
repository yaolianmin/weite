<?php
/* *
* $Author ：PHPYUN开发团队
*
* 官网: http://www.phpyun.com
*
* 版权所有 2009-2018 宿迁鑫潮信息技术有限公司，并保留所有权利。
*
* 软件声明：未经授权前提下，不得用于商业运营、二次开发以及任何形式的再次发布。
*/
class lt_job_controller extends company{
	function index_action(){
		$urlarr=array("c"=>"lt_job","page"=>"{{page}}");
		$pageurl=Url('member',$urlarr);
		$rows=$this->get_page("lt_job","`uid`='".$this->uid."'",$pageurl,"10","`job_name`,`status`,`id`,`lastupdate`,`edate`,`rec`,`rec_time`,`zp_status`,`hits`,`statusbody`");
		if($rows&&is_array($rows)){
			$jobs=array();
			foreach($rows as $key=>$val){ 
				$rows[$key]['num']=0;
				$jobs[]=$val['id'];
			}
			$nums=$this->obj->DB_select_all("userid_job","`job_id` in(".pylode(',',$jobs).") and `com_id`='".$this->uid."'  group by `com_id`","`job_id`,count(id) as num");
			if($nums&&is_array($nums)){ 
				foreach($rows as $key=>$val){
					foreach($nums as $v){
						if($val['id']==$v['job_id']){
							$rows[$key]['num']=$v['num'];
						}
					}
				} 
			}
		}
		$this->company_satic();
		$this->public_action();
		$this->yunset("rows",$rows);
		$this->yunset("js_def",3);
		$this->com_tpl('lt_job');
	}
	function jobset_action(){
		$IntegralM = $this->MODEL('integral');
		if($_GET['status']){
			$where['id']=(int)$_GET['id'];
			$where['uid']=$this->uid;
			if((int)$_GET['status']=='2'){
				$status=0;
			}else{
				$status=1;
			}
			$this->obj->update_once("lt_job",array("zp_status"=>$status),$where);
			$this->obj->member_log("设置猎头职位招聘状态");
			$this->layer_msg('设置成功！',9,0,$_SERVER['HTTP_REFERER']);
		}
		if($_POST['sub_rec']){
			$row=$this->obj->DB_select_once("company_statis","`uid`='".$this->uid."'","integral");
			$job=$this->obj->DB_select_once("lt_job","`id`='".(int)$_POST['recid']."' and `uid`='".$this->uid."'","`rec_time`,`name`");
			$_POST['day']=intval($_POST['day']);
			if($job['rec_time']<time()){
				$time=time()+$_POST['day']*86400;
			}else{
				$time=$job['rec_time']+$_POST['day']*86400;
			}
			$integral=$this->config['lt_recjob']*$_POST['day'];
			if($row['integral']<$integral){
 				$this->ACT_layer_msg("您的".$this->config['integral_pricename']."不足，请充值！",8,"index.php?c=pay");
			}else{

				$IntegralM=$this->MODEL('integral');

				$IntegralM->company_invtal($this->uid,$integral,false,"发布推荐猎头职位",true,2,'integral',12);
				$data['rec']=1;
				$data['rec_time']=$time;
				$where['id']=(int)$_POST['recid'];
				$where['uid']=$this->uid;
				$this->obj->update_once("lt_job",$data,$where);
				$this->obj->member_log("发布推荐猎头职位《".$job['job_name']."》",1,1);
 				$this->ACT_layer_msg("设置成功！",9,$_SERVER['HTTP_REFERER']);
			}
		}
	}
	function add_action(){
		include(CONFIG_PATH."db.data.php");		
		$this->yunset("arr_data",$arr_data);
		$statics = $this->company_satic();

		if( $statics['addltjobnum'] == 2){
			if(intval($statics['integral']) < intval($this->config['integral_lt_job'])){
				$this->ACT_msg($_SERVER['HTTP_REFERER'],"你的".$this->config['integral_pricename']."不够发布猎头职位！",8);
			}
		}

		$this->get_user();
		$this->public_action();
        $this->yunset($this->MODEL('cache')->GetCache(array('lt','com','ltjob','city','lthy')));
		$row['edate']=strtotime("+1 month");
		$this->yunset("row",$row);
		$this->yunset("today",date('Y-m-d',time()));
		$this->yunset("js_def",3);
		$this->company_satic();
		$this->com_tpl('lt_jobadd');
	}
	function edit_action(){
		include(CONFIG_PATH."db.data.php");		
		$this->yunset("arr_data",$arr_data);
		$this->get_user();
		$this->public_action();
		$this->company_satic();
		$this->yunset($this->MODEL('cache')->GetCache(array('lt','com','ltjob','city','lthy')));
		$row=$this->obj->DB_select_once("lt_job","`id`='".(int)$_GET['id']."'");
		
		
		$constitute[] = @explode(',',$row['constitute']);
		if($constitute){
			foreach($constitute as $key=>$val){
				$row['constitute']=$val;
			}
		}
		$welfare[] = @explode(',',$row['welfare']);
		if($welfare){
			foreach($welfare as $key=>$val){
				$row['welfare']=$val;
			}
		}
		$language[] = @explode(',',$row['language']);
		if($language){
			foreach($language as $key=>$val){
				$row['language']=$val;
			}
		}
		 
		$this->yunset("row",$row);
		$this->yunset("today",date("Y-m-d"));
		$this->yunset("js_def",3);
		$this->com_tpl('lt_jobadd');
	}
	function save_action(){
		if($_POST['submit']){
			
			$CacheList=$this->MODEL('cache')->GetCache(array('lt'));
			$_POST['language']=@implode(',',$_POST['language']);
			$_POST['welfare']=@implode(',',$_POST['welfare']);
			$_POST['constitute']=@implode(',',$_POST['constitute']);
			 
			$_POST['edate']=strtotime($_POST['edate']);
			$_POST['lastupdate'] = time();
			$_POST['status'] = $this->config['lt_job_status'];
			$_POST['uid'] = $this->uid;

			$com=$this->obj->DB_select_once("company","`uid`='".$this->uid."'","`name`,`pr`,`hy`,`mun`,`content`,`did`");
			$_POST['did'] = $com['did'];
			$_POST['com_name'] = $com['name'];
			$_POST['pr'] = $com['pr'];
			$_POST['hy'] = $com['hy'];
			$_POST['mun'] = $com['mun'];
			$_POST['desc'] = $com['content'];
			$_POST['usertype'] = "2";
			$id=(int)$_POST['id'];
			unset($_POST['submit']);
			unset($_POST['id']);

			if($_POST['status'] != 0 && $this->usertype == 2){
				$member = $this->obj->DB_select_once("member", "`uid`='{$this->uid}'", "`status`");
				if($member['status'] != 1){
					$_POST['status'] = 0;
				}
			}
			
			if(!$id){
				$this->get_com(4);
				$nid=$this->obj->insert_into("lt_job",$_POST);
				$name="添加猎头职位";
				$type='1';
				if($nid){
					$this->obj->DB_update_all("company","`jobtime`='".time()."'","uid='".$this->uid."'");
					$state_content = "新发布了猎头职位 <a href=\"".$this->config['sy_weburl']."/lietou/index.php?c=jobshow&id=$nid\" target=\"_blank\">".$_POST['job_name']."</a>。";
					$this->addstate($state_content,2);
				}
			}else{
				$job=$this->obj->DB_select_once("lt_job","`id`='".$id."'","status");

				$where['id']=$id;
				$where['uid']=$this->uid;
				$nid=$this->obj->update_once("lt_job",$_POST,$where);
				$name="更新猎头职位";
				$type='2';
				if($nid){
					if($job['status']>"0")
					{
						$this->obj->DB_update_all("company","`jobtime`='".time()."'","uid='".$this->uid."'");
					}
				}
			}
			if($nid)
			{
				$this->obj->member_log($name."《".$_POST['job_name']."》",1,$type);
				$this->ACT_layer_msg($name."成功！",9,"index.php?c=lt_job");
			}else{
				$this->ACT_layer_msg($name."失败！",8,$_SERVER['HTTP_REFERER']);
			}
		}
	}
	function del_action(){
		if($_POST['checkboxid'] || $_GET['id']){
			if(is_array($_POST['checkboxid'])){
				$del=pylode(",",$_POST['checkboxid']);
				$layer_type=1;
			}else{
				$del=(int)$_GET['id'];
				$layer_type=0;
			}
			
			$del=$this->obj->DB_delete_all("lt_job","`uid`='".$this->uid."' and `id` in (".$del.")","");
			$this->obj->DB_delete_all("fav_job","`job_id` in (".$del.")","");
			$this->obj->DB_delete_all("rebates","`job_id` in (".$del.")","");
			$this->obj->DB_delete_all("userid_job","`job_id` in (".$del.")","");
			if($del){
				$this->obj->member_log("删除猎头职位",1,3);
				$this->layer_msg('删除成功！',9,$layer_type,$_SERVER['HTTP_REFERER']);
			}else{
				$this->layer_msg('删除失败！',8,$layer_type,$_SERVER['HTTP_REFERER']);
			}
		}
	}

	function refresh_ltjob_action(){
		if($_POST){
			$M=$this->MODEL('comtc');
 			$return = $M->refresh_ltjob($_POST);
 			if($return['status']==1){
				echo json_encode(array('error'=>1,'msg'=>$return['msg']));
			}else if($return['status']==2){
				echo json_encode(array('error'=>2,'msg'=>$return['msg']));
			}else{
				echo json_encode(array('error'=>3,'msg'=>$return['msg']));
			}
		}else{
			echo json_encode(array('error'=>3,'msg'=>'参数错误，请重试！'));
		}
	}
}
?>