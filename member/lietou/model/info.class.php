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
class info_controller extends lietou{
	function index_action(){
		$row=$this->obj->DB_select_once("lt_info","`uid`='".$this->uid."'");
		if($row['job']!=""){
			$job=@explode(",",$row['job']);
		}else{
			$job=array();
		}
		$this->yunset("job",$job);
		if($row['hy']!=""){
			$hy=@explode(",",$row['hy']);
		}else{
			$hy=array();
		}
		$this->yunset("hy",$hy);
		$this->yunset("row",$row);
		$this->yunset($this->MODEL('cache')->GetCache(array('city','lt','ltjob','lthy')));
		$this->public_action();
		$this->yunset("class","1");
		$this->lietou_tpl('info');
	}
	function save_action(){
		$IntegralM=$this->MODEL('integral');
		$Member=$this->MODEL("userinfo");
		$_POST=$this->post_trim($_POST);
		unset($_POST['submit']);
		$where['uid']=$this->uid;
		$_POST['job'] = pylode(",",$_POST['job']);
		$_POST['hy'] = pylode(",",$_POST['qw_hy']);
		$row=$this->obj->DB_select_once("lt_info","`uid`='".$this->uid."'");
		if($row['moblie_status']==1){
		    unset($_POST['moblie']);
		}else{
		    $moblieNum = $Member->GetMemberNum(array("moblie"=>$_POST['moblie'],"`uid`<>'".$this->uid."'"));
		    if($_POST['moblie']==''){
		        $this->ACT_layer_msg("手机号码不能为空！",8);
		    }elseif($_POST['moblie']&&CheckMoblie($_POST['moblie'])==false){
		        $this->ACT_layer_msg("手机号码格式错误！",8);
		    }elseif($moblieNum>0){
		        $this->ACT_layer_msg("手机号码已存在！",8);
		    }else{
		        $data['moblie']=$_POST['moblie'];
		    }
		}
		if($row['email_status']==1){
		    unset($_POST['email']);
		}else{
		    $emailNum = $Member->GetMemberNum(array("email"=>$_POST['email'],"`uid`<>'".$this->uid."'"));
		    if($_POST['email']&&CheckRegEmail($_POST['email'])==false){
		        $this->ACT_layer_msg("联系邮箱格式错误！",8);
		    }elseif($_POST['email']&&$emailNum>0){
		        $this->ACT_layer_msg("联系邮箱已存在！",8);
		    }else{
		        $data['email']=$_POST['email'];
		    }
		}
		$this->obj->DB_update_all("lt_job","`com_name`='".$_POST['com_name']."'","`uid`=".$this->uid." ");
		$id=$this->obj->update_once("lt_info",$_POST,$where);
		if($id){
		    if(!empty($data)){
		        $this->obj->update_once("member",$data,array("uid"=>$this->uid));
		    }
		    $this->obj->member_log("修改基本信息",7);
		  
		    
		    if($row['com_name']==""){
		        $IntegralM->get_integral_action($this->uid,"integral_userinfo","完善基本资料");
		    }
		    $this->ACT_layer_msg("更新成功！",9,$_SERVER['HTTP_REFERER']);
		}else{
		    $this->ACT_layer_msg("更新失败！",8,$_SERVER['HTTP_REFERER']);
		}
	}
}
?>