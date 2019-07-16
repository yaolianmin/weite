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
class admin_lt_pic_controller extends adminCommon{

	function index_action(){
		$where="`photo`<>'' ";
		if(trim($_GET['keyword'])){
			if($_GET['type']=='2'){
				$where.=" AND `uid` like '%" .trim($_GET['keyword']). "%' ";
			}elseif($_GET['type']=='3'){
				$where.= "  AND `realname` like '%".trim($_GET['keyword'])."%' ";
			}else{
				$where.= "  AND `com_name` like '%".trim($_GET['keyword'])."%' ";
			}
			$urlarr['type']=$_GET['type'];
			$urlarr['keyword']=$_GET['keyword'];
		}
		$where.=" order by `uid` desc";
		$urlarr['page']="{{page}}";
		$pageurl=Url('admin_lt_pic',$urlarr,'admin');
		$rows=$this->get_page("lt_info",$where,$pageurl,"15");
		if(is_array($rows)){
			foreach($rows as $key=>$value){
				if(strpos($value['photo'],'http')===false&&file_exists(str_replace("./",APP_PATH."/",$value['photo']))){
					$rows[$key]['photo'] = str_replace("./",$this->config['sy_weburl']."/",$value['photo']);
				}else{
					$rows[$key]['photo'] = $this->config['sy_weburl']."/".$this->config['sy_lt_icon'];
					
					
				}
			}
		}
		$this->yunset("rows",$rows);
		$this->yuntpl(array('admin/admin_lt_pic'));
	}

	function uploadsave_action(){
		$_POST=$this->post_trim($_POST);
		extract($_POST);
		$id=$_POST['id'];
		$UploadM = $this->MODEL('upload');
		if($update){
			if($type=='photo'){
				$row=$this->obj->DB_select_once("lt_info","`uid`='".$id."'","photo");
				if($_FILES['file']['tmp_name']){
					$upload=$UploadM->Upload_pic("../data/upload/lietou/",false);
					$pic=$upload->picture($_FILES['file']);
					$picmsg=$UploadM->picmsg($pic,$_SERVER['HTTP_REFERER']);
					if($picmsg['status'] == $pic){
						$this->ACT_layer_msg($picmsg['msg'],8);
					}
					$data['photo']=$data['photo_big'] = str_replace("../data","./data",$pic);
					if($row['photo']){
						unlink_pic(str_replace("./",APP_PATH."/",$row['photo']));
					}
				}
				$where['uid']=$id;
				$nbid=$this->obj->update_once("lt_info",$data,$where);
				isset($nbid)?$this->ACT_layer_msg("猎头头像(ID:".$id.")修改成功！",9,$_SERVER['HTTP_REFERER'],2,1):$this->ACT_layer_msg("修改失败！",8,$_SERVER['HTTP_REFERER']);
			}
		}
	}
	
	function del_action(){
		if($_GET['delid']){
			$this->check_token();
			if($_GET['type']=='photo'){
				$row=$this->obj->DB_select_once("lt_info","`uid`='".$_GET['delid']."'","`photo`");
				unlink_pic(str_replace("./",APP_PATH."/",$row['photo']));
				$delid=$this->obj->DB_update_all("lt_info","`photo`='',`photo_big`=''","`uid`='".$_GET['delid']."'");
				$delid?$this->layer_msg("猎头头像(ID:".$_GET['delid'].")删除成功！",9,0,$_SERVER['HTTP_REFERER']):$this->layer_msg('删除失败！',8,0,$_SERVER['HTTP_REFERER']);
			}
		}

		if(is_array($_GET['del'])){
			$this->check_token();
			if($_GET['type']=='photo'){
				$uids = @implode(",",$_GET['del']);
				$row=$this->obj->DB_select_all("lt_info","`uid` in (".$uids.") and `photo`!=''","photo");
				if(is_array($row)){
					foreach($row as $v){
						unlink_pic(str_replace("./",APP_PATH."/",$v['photo']));
					}
				}
				$delid=$this->obj->DB_update_all("lt_info","`photo`=''","`uid` in (".$uids.")");
				$delid?$this->layer_msg("猎头头像(ID:".$uids.")删除成功！",9,1,$_SERVER['HTTP_REFERER']):$this->layer_msg('删除失败！',8,1,$_SERVER['HTTP_REFERER']);
			}
		}else{
			$this->layer_msg( "请选择您要删除的图片！",8,1);
		}
	}
}
?>