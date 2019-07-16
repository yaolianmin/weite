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
class news_controller extends company
{
	function index_action()
	{
		$this->public_action();
		$this->company_satic();
		$where="`uid`='".$this->uid."'";
		if(trim($_GET['keyword']))
		{
			$urlarr['keyword']=trim($_GET['keyword']);
			$where.=" and `title` like '%".trim($_GET['keyword'])."%'";
		}
		$urlarr['c']="news";
		$urlarr["page"]="{{page}}";
		$pageurl=Url('member',$urlarr);
		$this->get_page("company_news",$where,$pageurl,"10","`title`,`id`,`status`,`ctime`,`statusbody`");
		$this->yunset("js_def",2);
		$this->com_tpl("news");
	}
	function add_action(){
		$this->public_action();
		$this->yunset("js_def",2);
		$this->com_tpl("addnews");
	}
	function edit_action(){
		$this->public_action();
		$editrow=$this->obj->DB_select_once("company_news","`id`='".(int)$_GET['id']."' and `uid`='".$this->uid."'");
		$this->yunset("editrow",$editrow);
		$this->yunset("js_def",2);
		$this->com_tpl("addnews");
	}
	function save_action(){
		if($_POST['action']=="save"){
			$sql['title']=$_POST['title'];
			$body = str_replace("&amp;","&",$_POST['body']);
			$title=trim($sql['title']);
			if($title=="" || $body=="")
			{
 				$this->ACT_layer_msg("新闻标题内容不能为空！",2,$_SERVER['HTTP_REFERER']);
			}
			$sql['body']=$body;
			if(!$_POST['id'])
			{
				$sql['uid']=$this->uid;
				$sql['did']=$this->userdid;
				$sql['ctime']=mktime();
				$oid=$this->obj->insert_into("company_news",$sql);
				$msg="添加";
			}else{
				$where['uid']=$this->uid;
				$where['id']=(int)$_POST['id'];
				$sql['status']='0';
				$oid=$this->obj->update_once("company_news",$sql,$where);
				$msg="修改";
			}
			if($oid)
			{
				$this->obj->member_log($msg."企业新闻");
				$this->ACT_layer_msg("操作成功！",9,"index.php?c=news");
			}else{
				$this->ACT_layer_msg("操作失败，请稍后再试！",8,"index.php?c=news");
			}
		}
	}
	function del_action(){
		if($_POST['delid'] || $_GET['id']){
			if(is_array($_POST['delid']))
			{
				$delid=pylode(",",$_POST['delid']);
				$layer_type='1';
			}else{
				$delid=(int)$_GET['id'];
				$layer_type='0';
			}
			$oid=$this->obj->DB_delete_all("company_news","`id` in (".$delid.") and `uid`='".$this->uid."'","");
			if($oid)
			{
				$this->obj->member_log("删除企业新闻");
				$this->layer_msg('删除成功！',9,$layer_type,$_SERVER['HTTP_REFERER']);
			}else{
				$this->layer_msg('删除失败！',8,$layer_type,$_SERVER['HTTP_REFERER']);
			}
		}else{
			$this->ACT_layer_msg("请选择您要删除的新闻！",8,$_SERVER['HTTP_REFERER']);
		}
	}
	function show_action(){
		if($_POST['id']){
			$row=$this->obj->DB_select_once("company_news","id='".$_POST['id']."'","statusbody");
			$data['statusbody']=$row['statusbody'];
			echo json_encode($data);die;
		}
	}
}
?>