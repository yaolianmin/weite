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
class lthy_class_controller extends adminCommon{
	function index_action(){
		 
		$position=$this->obj->DB_select_all("lthy_class","`keyid`='0' order by sort asc");
		$this->yunset("position",$position);
		$this->yuntpl(array('admin/admin_lthy'));
	}
	 
	function save_action(){
		$_POST=$this->post_trim($_POST);
	    $position=explode('-',$_POST['name']);
		foreach ($position as $val){
			if($val){
				$name[]=$val;
			}
		}
		$lthy_class=$this->obj->DB_select_all("lthy_class","`name` in ('".implode("','", $name)."')");
		if(empty($lthy_class)){
            foreach ($name as $key=>$val){
                if($_POST['ctype']=='1'){ 
                    $value="`name`='".$val."'";
                }else{
                    $value="`name`='".$val."',`keyid`='".intval($_POST['nid'])."'";
                }
                $add=$this->obj->DB_insert_once("lthy_class",$value);
            }
			$this->cache_action();
			$add?$msg=2:$msg=3;
			$this->MODEL('log')->admin_log("猎头行业分类(ID:".$add.")添加成功");
		}else{
			$msg=1;
		}
		echo $msg;die;
	}
 
	function up_action(){
		 
		if((int)$_GET["id"]){
			$id=(int)$_GET["id"];
			$onejob=$this->obj->DB_select_once("lthy_class","`id`='".$_GET["id"]."'");
			$twojob=$this->obj->DB_select_all("lthy_class","`keyid`='".$_GET["id"]."' order by sort asc");
			$this->yunset("onejob",$onejob);
			$this->yunset("twojob",$twojob);
			$this->yunset("id",$id);
		}
		$position=$this->obj->DB_select_all("lthy_class","`keyid`='0'");
		$this->yunset("position",$position);
		$this->yuntpl(array('admin/admin_lthy'));
	}
	 
	function del_action(){
		if($_GET['delid']){
			$this->check_token();
			$id=$this->obj->DB_delete_all("lthy_class","`id`='".$_GET['delid']."' or `keyid`='".$_GET['delid']."'","");
			$this->cache_action();
		    isset($id)?$this->layer_msg('猎头行业分类删除成功！',9,0,$_SERVER['HTTP_REFERER']):$this->layer_msg('删除失败！',8,0,$_SERVER['HTTP_REFERER']);
		}
		if($_POST['del']){ 
			$del=@implode(",",$_POST['del']);
			$id=$this->obj->DB_delete_all("lthy_class","`id` in (".$del.") or `keyid` in (".$del.")","");
			$this->cache_action();
			isset($id)?$this->layer_msg('猎头行业分类删除成功！',9,1,$_SERVER['HTTP_REFERER']):$this->layer_msg('删除失败！',8,1,$_SERVER['HTTP_REFERER']);
		}
	}
	function ajax_action(){
		if($_POST['sort']){ 
			$this->obj->DB_update_all("lthy_class","`sort`='".$_POST['sort']."'","`id`='".$_POST['id']."'");
			$this->MODEL('log')->admin_log("猎头行业分类(ID:".$_POST['id'].")排序修改成功");
		}
		if($_POST['name']){ 
			$this->obj->DB_update_all("lthy_class","`name`='".$_POST['name']."'","`id`='".$_POST['id']."'");
			$this->MODEL('log')->admin_log("猎头行业分类(ID:".$_POST['id'].")类别修改成功");
		}
		$this->cache_action();echo '1';die;
	}
	function cache_action(){
		include(LIB_PATH."cache.class.php");
		$cacheclass= new cache(PLUS_PATH,$this->obj);
		$makecache=$cacheclass->lthy_cache("lthy.cache.php");
	}
}

?>