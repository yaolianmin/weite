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
class search_resume_controller extends lietou
{
	function index_action(){
		include(CONFIG_PATH."db.data.php");
		unset($arr_data['sex'][3]);
		$this->yunset("arr_data",$arr_data);
		$this->yunset("getinfo",$_GET);
		$uptime=array(1=>'今天',3=>'最近3天',7=>'最近7天',30=>'最近一个月',90=>'最近三个月');
        $this->yunset("uptime",$uptime);
        $this->yunset('ltstyle',$this->config['sy_weburl'].'/app/template/lietou');
		$this->yunset("type",$_GET['type']);
		$this->yunset($this->MODEL('cache')->GetCache(array('job','user','hy','city')));
		$this->public_action();
		$this->yunset("class",25);
		$this->lietou_tpl('search_resume');
	}
}
?>