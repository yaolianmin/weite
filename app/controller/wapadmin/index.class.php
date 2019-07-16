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
class index_controller extends adminCommon{
	function index_action(){ 
		if($_POST['login_sub']){			
			if($_POST['username'] && $_POST['password']){			
				$islogin = $this->wapadmin_get_user_login($_POST['username'], $_POST['password']);
				if(!$islogin){

					$data['msg']='用户名或密码错误！';
					$data['url']='';
					$this->yunset("layer",$data);
					$this->yuntpl(array('wapadmin/login'));
				}else{
					header("location:index.php");
					
				}
				
			}else{
				$data['msg']='请完整输入账户名、密码！';
				$data['url']='';
				$this->yunset("layer",$data);
				$this->yuntpl(array('wapadmin/login'));
			}
		}else{

			if($_SESSION['wuid']){
				
			
			$today=strtotime(date("Y-m-d"));
			$yesterday=$today-86400;
			$list['com_num_now']=$this->obj->DB_select_num("member","usertype='2' and reg_date>'".$today."'");
			$list['com_num']=$this->obj->DB_select_num("member","usertype='2' and reg_date<'".$today."' and reg_date>'".$yesterday."'");
			$list['job_num_now']=$this->obj->DB_select_num("company_job","sdate>'".$today."'");
			$list['job_num']=$this->obj->DB_select_num("company_job","sdate<'".$today."' and sdate>'".$yesterday."'");
			$list['user_num_now']=$this->obj->DB_select_num("member","usertype='1' and reg_date>'".$today."'");
			$list['user_num']=$this->obj->DB_select_num("member","usertype='1' and reg_date<'".$today."' and reg_date>'".$yesterday."'");
			$list['resume_num_now']=$this->obj->DB_select_num("resume_expect","ctime>'".$today."'");
			$list['resume_num']=$this->obj->DB_select_num("resume_expect","ctime<'".$today."' and ctime>'".$yesterday."'");
			
			$list['user_num_dsh']=$this->obj->DB_select_num("member","usertype='1' and `status`='0'");
			$list['com_num_dsh']=$this->obj->DB_select_num("member","usertype='2' and `status`='0'");
			$list['job_num_dsh']=$this->obj->DB_select_num("company_job","state='0'");
			$list['link_num_dsh']=$this->obj->DB_select_num("admin_link","link_state='0'");
			$list['order_num_dsh']=$this->obj->DB_select_num("company_order","`order_state`='3'");
			$list['comcert_num_dsh']=$this->obj->DB_select_num("company_cert","`type`='3' and `status`='0'");
			$list['usercert_num_dsh']=$this->obj->DB_select_num("resume","`idcard_pic`<>'' and `idcard_status`='0'");
			
			$this->yunset("list",$list);

			
				
				
				$this->yuntpl(array('wapadmin/index'));
			}else{
				$this->yuntpl(array('wapadmin/login'));
			}
		}
	}
	function logout_action(){
		unset($_SESSION['authcode']);
		unset($_SESSION['wuid']);
		unset($_SESSION['wusername']);
		unset($_SESSION['wshell']);
		unset($_SESSION['md']);
		unset($_SESSION['tooken']);
		unset($_SESSION['xsstooken']);
		$this->layer_msg("您已成功退出！",9,0,"index.php");
	}
}
?>