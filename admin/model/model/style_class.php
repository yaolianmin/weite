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
class style{
	function __construct($obj){
		$this->obj = $obj;
	}
 	function model_list_action(){
		$path = TPL_PATH;
		$handle = @opendir($path);

		while($file = @readdir($handle)){
			if($file=="."||$file==".."||$file==".svn"||$file=="member"||$file=="train"||$file=="siteadmin"||$file=="admin"||$file=="company"||$file=="im"||$file=="wap"||$file=="lietou"||$file=="ask"||$file=="wapadmin"||$file=="resume") continue;
			if(is_dir($path.$file)){
				$list[] = $file;
			}
		}
		if(is_array($list)){
			foreach($list as $key=>$value){
				$filepath =$path.$value."/info.txt";
				if(!file_exists($filepath)){
					$fopen = @fopen($filepath,"w+");
					fclose($fopen);
				}
				$size = @filesize($filepath);
				$fp = @fopen($filepath,"r+");

				$text = @fread($fp,$size);
				if($text==""){
					$text= "暂未命名||暂无添加作者信息||".$value."||"."../app/template/".$value."/images/preview.jpg";
					@fwrite($fp,$text);
				}
				@fclose($fp);
				$content = @explode("||",$text);
				$text="";
				$lists[$key]['name'] = $content[0];
				$lists[$key]['author'] = $content[1];
				$lists[$key]['dir'] = $content[2];
				$lists[$key]['img'] = $content[3];

			}
		}
		return $lists;
	}

 	function model_modify_action($dir){
		$path = TPL_PATH.$dir."/info.txt";
		$fp = @fopen($path,r);
		$text = @fread($fp,filesize($path));
		@fclose($fp);
		$content = @explode("||",$text);
		$style_info = array("name"=>$content[0],"author"=>$content[1],"dir"=>$content[2],"img"=>$content[3],);
		return $style_info;

	}

	function model_save_action($arr){

		extract($arr);
		$path = TPL_PATH.$dir."/info.txt";
        if ($img!='' && $img!="../app/template/".$dir."/images/preview.jpg"){
            $file = file_get_contents('.'.$img);
            $preview = file_put_contents(TPL_PATH.$dir."/images/preview.jpg", $file);
            unlink_pic('.'.$img);
        }
		$text = $name."||".$author."||".$dir."||../app/template/".$dir."/images/preview.jpg";
		$fp = @fopen($path,w);
		 
		@fwrite($fp,$text);
		@fclose($fp);

	}

}