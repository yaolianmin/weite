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
class zph_controller extends common{  
	function zph_tpl($tpl){
		$this->yuntpl(array($this->config['style'].'/zph/'.$tpl));
	}
	function Zphpublic_action(){
		if($this->config['sy_zph_web']=="2"){
			header("location:".Url('error'));
		}
	}
}
?>