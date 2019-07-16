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
class tongji_controller extends adminCommon{ 
	function index_action(){ 
		$today=strtotime(date("Y-m-d 00:00:00"));
		$yesterday=strtotime(date("Y-m-d 00:00:00"))-86400;
		
		$com['today']=$this->obj->DB_select_num("member","`usertype`='2' and `reg_date`>='".$today."'","uid");  
		$com['yesterday']=$this->obj->DB_select_num("member","`usertype`='2' and `reg_date`>='".$yesterday."' and `login_date`<'".$yesterday."'","uid");  
		
		$job['today']=$this->obj->DB_select_num("company_job","`sdate`>='".$today."'","id");  
		$job['yesterday']=$this->obj->DB_select_num("company_job","`sdate`>='".$yesterday."' and `sdate`<'".$today."'","id");  
		
		$user['today']=$this->obj->DB_select_num("member","`usertype`='1' and `reg_date`>='".$today."'","uid");  
		$user['yesterday']=$this->obj->DB_select_num("member","`usertype`='1' and `reg_date`>='".$yesterday."' and `login_date`<'".$today."'","uid"); 
		
		$resume['today']=$this->obj->DB_select_num("resume_expect","`ctime`>='".$today."'","id");  
		$resume['yesterday']=$this->obj->DB_select_num("resume_expect","`ctime`>='".$yesterday."' and `ctime`<'".$today."'","id"); 
		
		
	
		
		
		$check['job']=$this->obj->DB_select_num("company_job","`state`='0'","id"); 
		$check['link']=$this->obj->DB_select_num("admin_link","`link_state`='0'","id"); 
		$check['order']=$this->obj->DB_select_num("company_order","`order_state`='3'","id"); 
		$check['com']=$this->obj->DB_select_num("company_cert","`type`='3' and `status`='0'","uid"); 
		$check['user']=$this->obj->DB_select_num("resume","`idcard_status`='0' and `idcard_pic`<>''","uid"); 
		$this->yunset("resume",$resume); 
		$this->yunset("user",$user); 
		$this->yunset("com",$com); 
		$this->yunset("job",$job); 
		$this->yunset("check",$check); 
		$this->yunset("headertitle","统计管理");
		$this->yuntpl(array('wapadmin/admin_tongji'));
	} 
}

?>