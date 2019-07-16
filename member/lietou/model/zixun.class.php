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
class zixun_controller extends lietou
{
	function index_action()
	{
		$this->obj->DB_update_all("msg","`com_remind_status`='1'","`job_uid`='".$this->uid."' and `com_remind_status`='0'");
		$urlarr=array("c"=>"zixun","page"=>"{{page}}");
		$pageurl=Url('member',$urlarr);
		$rows=$this->get_page("msg","`job_uid`='".$this->uid."' order by id desc",$pageurl,"10");
     	$this->yunset("rows",$rows);
		$this->public_action();
	    $this->yunset("class",24);
		$this->lietou_tpl('zixun');
	}
	function del_action(){
		
		if($_POST['delid'] || $_GET['id']){
 			if($_POST['delid']){
 				$delid=pylode(',',$_POST['delid']);
 				$layer_status=1;
 			}else{
 				$delid=(int)$_GET['id'];
 				$layer_status=0;
 			}
			$nid = $this->obj->DB_delete_all("msg","`id` in (".$delid.") and `job_uid`='".$this->uid."'","");
			if($nid)
			{				
				$this->obj->member_log("删除求职咨询");
				$this->layer_msg('删除成功！',9,$layer_status,"index.php?c=zixun");
			}else{
				$this->layer_msg('删除失败！',8,$layer_status,"index.php?c=zixun");
			}
		}		
	}
	function sreplys_action(){
		if($_POST['submit']){
			$data['reply']=$_POST['reply'];
			$data['reply_time']=time();
			$where['id']=(int)$_POST['id'];
			$where['job_uid']=$this->uid;
			$this->obj->update_once("msg",$data,$where);
			$this->obj->member_log("回复求职咨询");
 			
			$this->ACT_layer_msg("回复成功！",9,"index.php?c=zixun");
		}
	}
	function replys_action(){
		$reply=$this->obj->DB_select_once("msg","`id`='".(int)$_GET['id']."'","content,reply");
		$this->yunset("reply",$reply);
		$this->yunset("id",$_GET['id']);
		$this->public_action();
		$this->yunset("class",41);
		$this->lietou_tpl('replyss');
	}
}
?>