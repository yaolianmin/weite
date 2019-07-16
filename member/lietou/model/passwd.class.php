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
class passwd_controller extends lietou
{
	function index_action()
	{
		if($_POST['submit'])
		{
			$info = $this->obj->DB_select_once("member","`uid`='".$this->uid."'","`salt`,`password`");
			if(is_array($info)){
				$oldpass = md5(md5($_POST['oldpassword']).$info['salt']);
				if($info['password']!=$oldpass){
					$this->ACT_layer_msg("原始密码错误！",8,"index.php?c=passwd");
				}
				$salt = substr(uniqid(rand()), -6);
				$pass2 = md5(md5($_POST['password']).$salt);
				$data['password']=$pass2;
				$data['salt']=$salt;
				$this->obj->update_once("member",$data,array("uid"=>$this->uid));
				$this->cookie->unset_cookie();
				$this->obj->member_log("修改密码",8);
				$this->ACT_layer_msg("密码修改成功,请重新登录！",9,$this->config['sy_weburl']."/lietou/");
			}
		}
		$this->public_action();
		$this->yunset("class","6");
		$this->lietou_tpl('passwd');
	}
}
?>