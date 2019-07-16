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
class cron_controller extends adminCommon{
	function public_act(){
		 
		$arrweek[]="周一";
		$arrweek[]="周二";
		$arrweek[]="周三";
		$arrweek[]="周四";
		$arrweek[]="周五";
		$arrweek[]="周六";
		$arrweek[]="周日";
		 
		for($i=1;$i<=31;$i++){
			$montharr[]=$i."日";
		}
		 
		for($i=0;$i<=23;$i++){
			$hourarr[]=$i."时";
		}
		$this->yunset("hourarr",$hourarr);
		$this->yunset("montharr",$montharr);
		$this->yunset("arrweek",$arrweek);
	}
	function index_action(){
		$rows=$this->obj->DB_select_all("cron");
		$this->yunset("rows",$rows);
		$this->yuntpl(array('admin/admin_cron_list'));
	}
	function add_action(){
		$this->public_act();
		if($_GET["id"]){
			$row=$this->obj->DB_select_once("cron","`id`='".$_GET["id"]."'");
			$this->yunset("row",$row);
		}
		$this->yuntpl(array('admin/admin_cron_add'));
	}
	function save_action(){

		 
		if($_POST['msgconfig']){
			$id=$_POST["id"];
			unset($_POST["id"]);
			unset($_POST["msgconfig"]);
			$CronM=$this->MODEL('cron');
			$_POST['nexttime']  = strtotime($CronM->nextexe($_POST));

			if($_POST['dir'])
			{
				$dirArr = explode('.',$_POST['dir']);
				
				if($dirArr[0]=='' || strpos($dirArr[0],'/')!==false || end($dirArr)!='php'){
					$this->ACT_layer_msg("无效的执行文件！",8);
				}else{
					$_POST['dir'] = $dirArr[0].'.php';
					
				}
				
				
			}else{
				$this->ACT_layer_msg("请填写计划任务执行文件！",8);
			}
			if(!$id){
				$_POST["ctime"]=time();
				$nbid=$this->obj->insert_into("cron",$_POST);
				$alert="计划任务(id:".$nbid.")添加成功！";
				$this->croncache();

			}else{

				$nbid=$this->obj->update_once("cron",$_POST,array('id'=>$id));
				$alert="计划任务(id:".$id.")修改成功！";
				$this->croncache();
			}

 			isset($nbid)?$this->ACT_layer_msg($alert,9,"index.php?m=cron"):$this->ACT_layer_msg("添加失败！",8,"index.php?m=cron");

		}
	}
	function del_action(){
		$this->check_token();
		if($_GET["id"]){

			$ad=$this->obj->DB_delete_all("cron","`id`='".$_GET["id"]."'");
			$this->croncache();
			$this->layer_msg('计划任务(ID:'.$_GET["id"].')删除成功！',9,0,"index.php?m=cron");
		}


	}
	 
	function croncache(){
		include(LIB_PATH."cache.class.php");
		$cacheclass= new cache(PLUS_PATH,$this->obj);
		$cacheclass->cron_cache("cron.cache.php");
	}

	function run_action(){
		if($_GET['id'])
		{
			include PLUS_PATH.'cron.cache.php';
			$CronM=$this->MODEL('cron');
			$CronM->excron($cron,$_GET['id']);
		}

		$this->layer_msg('计划任务(ID:'.$_GET["id"].')执行成功！',9,0,"index.php?m=cron");
	}


}

?>