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
class message_controller extends company
{
	function index_action()
	{
		if($_POST['add_message'])
		{
			$data['content']=trim($_POST['content']);
			$data['uid']=$this->uid;
			$data['username']=$this->username;
			$data['ctime']=mktime();
			$nid=$this->obj->insert_into("message",$data);
			if($nid)
			{
				$this->obj->member_log("反馈问题");
				$this->ACT_layer_msg("提交成功！",9,"index.php?c=seemessage");
			}else{
				$this->ACT_layer_msg("提交失败！",8,"index.php?c=seemessage");
			}
		}
		$this->public_action();
		$this->yunset("js_def",7);
		$this->com_tpl('message');
	}
}
?>