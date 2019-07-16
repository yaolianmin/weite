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
class save_avatar_controller extends lietou{
	function index_action(){
		$IntegralM=$this->MODEL('integral');
		@header("Expires: 0");
		@header("Cache-Control: private, post-check=0, pre-check=0, max-age=0", FALSE);
		@header("Pragma: no-cache");
		$type = isset($_GET['type'])?trim($_GET['type']):'small';
		$pic_id = trim($_GET['photoId']);
		$new_avatar_path = 'upload/lietou/lietou_'.$type.'/'.$pic_id;
		$len = file_put_contents(APP_PATH.$new_avatar_path,file_get_contents("php://input"));
		$avtar_img = imagecreatefromjpeg(APP_PATH.$new_avatar_path);
		imagejpeg($avtar_img,APP_PATH.$new_avatar_path,80);
		$d['data']['urls'][0] ="../".$new_avatar_path;
		$d['status'] = 1;
		$d['statusText'] = '上传成功!';
		$row = $this->obj->DB_select_once("lt_info","`uid`='".$this->uid."'");
		if($type=="small"){
			if($row['photo']!="")
			{
				unlink_pic($row['photo']);
			}else{
				$IntegralM->get_integral_action($this->uid,"integral_avatar","上传头像");
			}
			$this->obj->update_once("lt_info",array("photo"=>"./".$new_avatar_path),array("uid"=>$this->uid));
			$this->obj->member_log("上传头像");
		}else{
			unlink_pic($row['photo_big']);
			$this->obj->update_once("lt_info",array("photo_big"=>"./".$new_avatar_path),array("uid"=>$this->uid));
		}
		$msg = json_encode($d);
		echo $msg;
	}
}
?>