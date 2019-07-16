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
class uppic_controller extends lietou
{
	
	function index_action(){
		$row = $this->obj->DB_select_once("lt_info","`uid`='".$this->uid."'");
		$this->yunset("spic",$row['photo']);
		$this->yunset("bpic",$row['photo_big']);
		$this->public_action();
		$this->yunset("class",8);
		$this->lietou_tpl('uppic');
	}
	
	function uppath(){
		$upload_path = "../data/upload/lietou/";
		return $upload_path;
	}

	function ajaxfileupload_action(){
		$UploadM=$this->MODEL('upload');
		if($_FILES['image']['tmp_name']){
			$upload=$UploadM->Upload_pic("../data/upload/lietou",false,$this->config['user_pickb']);
			$pictures=$upload->picture($_FILES['image']);
			$picMsg = $UploadM->picmsg($pictures,$_SERVER['HTTP_REFERER'],"ajax");
			if($picMsg){
				$imginfo=$this->getInfo($pictures);
				if($imginfo['width']<100 || $imginfo['height']<100){
					unlink_pic($pictures);
					$res['s_thumb'] = '上传头像尺寸太小';
				}else{
					$s_thumb=$upload->makeThumb($pictures,300,300,'_S_');
					unlink_pic($pictures);
					$res["url"] = $s_thumb;
				}
				echo json_encode($res);
			}
		}else{ 
			$res["s_thumb"] = '请选择上传图片';
			echo json_encode($res);
		}
	}

	function getInfo($file){ 
		$data=getimagesize($file);
		$imageInfo["width"]	= $data[0];
		$imageInfo["height"]= $data[1];
		$imageInfo["type"]	= $data[2];
		$imageInfo["name"]	= basename($file);
		$imageInfo["size"]  = filesize($file);
		return $imageInfo;
	}
	
	function savethumb_action(){
		$IntegralM=$this->MODEL('integral');
		$upload_path = $this->uppath();
		$upload_path=ltrim($upload_path,'.');
		if(stripos(trim($_POST['img1']),$upload_path)===false || stripos(trim($_POST['img2']),$upload_path)===false){
			$this->ACT_layer_msg("非法操作！",8,$_SERVER['HTTP_REFERER']);
		}
		include(LIB_PATH."sizer.class.php");
		$sizer=new Sizer("../data/upload/lietou/".date('Ymd').'/');
		$t_name = $sizer->sizeIt();
		$ltInfo=$this->obj->DB_select_once("lt_info","`uid`='".$this->uid."'","`photo`,`photo_big`");

		if($ltInfo['photo']){
			unlink_pic('.'.$ltInfo['photo']);
		}else{
			$IntegralM->get_integral_action($this->uid,"integral_avatar","上传头像");
		}
		if($ltInfo['photo_big']){
			unlink_pic('.'.$ltInfo['photo_big']); 
		}
		
		
		$ref=$this->obj->update_once("lt_info",
										array(	'photo_big'=>str_replace("../data/upload/lietou/","./data/upload/lietou/",$t_name[1]),
												'photo'=>str_replace("../data/upload/lietou/","./data/upload/lietou/",$t_name[2])),
										array('uid'=>$this->uid));
		if($ref){$this->obj->member_log("上传头像");echo 1;}else{echo 2;}
	} 

}
?>