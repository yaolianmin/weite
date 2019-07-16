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
class entrust_resume_controller extends lietou{
 	function index_action(){
		$where="a.`lt_uid`='".$this->uid."' and a.`uid`=b.`uid` and `height_status`='2'";
		$delwhere="`lt_uid`='".$this->uid."' and `id`='".(int)$_GET['del']."'";
		$this->resume("entrust",$where,$delwhere,"委托来的简历");
		$this->public_action();
		$this->obj->DB_update_all("entrust","`remind_status`='1'","`lt_uid`='".$this->uid."' and `remind_status`='0'");
		$this->yunset("class",18);
		$this->lietou_tpl('entrust_resume');
 	}
	
	function del_action(){
 		if($_POST['delid'] || $_GET['del']){
 			if($_POST['delid']){
 				$delid=pylode(',',$_POST['delid']);
 				$layer_status=1;
 			}else{
 				$delid=(int)$_GET['del'];
 				$layer_status=0;
 			}
			
			$nid=$this->obj->DB_delete_all("entrust","`lt_uid`='".$this->uid."' and `uid` in (".$delid.")","");
			if($nid){
 				$this->obj->member_log("删除委托的简历");
 				$this->layer_msg('删除成功！',9,$layer_status,$_SERVER['HTTP_REFERER']);
 			}else{
 				$this->layer_msg('删除失败！',8,$layer_status,$_SERVER['HTTP_REFERER']);
 			}
 		}
 	}
}
?>