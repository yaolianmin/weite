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
class admin_job_controller extends adminCommon{
	function index_action(){
 		$position=$this->obj->DB_select_all("job_class","`keyid`='0' order by sort asc");
		$this->yunset("position",$position);
		$this->yuntpl(array('admin/admin_job'));
	}
	function classadd_action(){
	    $this->yunset($this->MODEL('cache')->GetCache(array('job')));
		$position=$this->obj->DB_select_all("job_class","`keyid`='0' order by sort asc");
		$this->yunset("position",$position);
		if($_GET['id']){
			$info=$this->obj->DB_select_once("job_class","`id`='".$_GET['id']."'");
			$this->yunset("info",$info);
			$job=$this->obj->DB_select_once("job_class","`id`='".$info['keyid']."'");
			$class2=$this->obj->DB_select_all("job_class","`keyid`='".$job['keyid']."' order by sort asc");
			$this->yunset("class2",$class2);
			$this->yunset("job",$job);
		}
		$this->yuntpl(array('admin/admin_job_classadd'));
	}
	
	function get_class_action(){
		if($_POST['nid']){
			$typeclass=$this->obj->DB_select_all("job_class","keyid='".intval($_POST['nid'])."' order by `sort`","`id`,`name`,`keyid`");

			if(isset($_POST['use_layui'])){ 
				if($typeclass[0]){
					$html = "<option value=''>请选择</option>";
					foreach($typeclass as $v){
						$html.="<option value='{$v['id']}'>{$v['name']}</option>";
					}
					echo $html;exit;
				}else{
					echo $html="<option value=''>该分类下暂无子类！</option>";exit;
				}
			}

			if($typeclass[0]){
				$html='';
				foreach($typeclass as $v){
					$html.="<div class=\"yun_admin_select_box_list\"><a href=\"javascript:;\" onClick=\"select_new('keyid','".$v['id']."','".$v['name']."')\">".$v['name']."</a></div>";
				}
				echo $html;exit;
			}else{
				echo $html="<div class=\"yun_admin_select_box_list\">该分类下暂无子类！</div>";exit;
			}
		}
	}
	
	function save_action(){
		if($_POST['submit']){
			$where="`name`='".trim($_POST['position'])."'";
			if(intval($_POST['id'])){
				$where.=" and `id`<>'".intval($_POST['id'])."'";
			}
			if(intval($_POST['nid'])){
				$where.=" and `keyid`='".intval($_POST['nid'])."'";
			} 
			$info=$this->obj->DB_select_once("job_class",$where); 
			if($info['id']){
				$this->ACT_layer_msg("该类别已存在！",8,$_SERVER['HTTP_REFERER']);
			}else{
				if($_POST['keyid']!=""){
					$value.="`keyid`='".$_POST['keyid']."',";
				}elseif($_POST['nid']!=""){
					$value.="`keyid`='".$_POST['nid']."',";
				}
				$value.="`name`='".$_POST['position']."',";
				$value.="`sort`='".$_POST['sort']."',";
				$content=str_replace(array("&amp;","background-color:#ffffff","background-color:#fff","white-space:nowrap;"),array("&",'','',''),$_POST['content']);
				$value.="`content`='".$content."'";
				if($_POST['id']){
					$nid=$this->obj->DB_update_all("job_class",$value,"`id`='".$_POST['id']."'");
					$msg="更新";
				}else{
					$nid=$this->obj->DB_insert_once("job_class",$value);
					$msg="添加";
				}
				$this->cache_action();
				$nid?$this->ACT_layer_msg($msg."成功！",9,"index.php?m=admin_job",2,1):$this->ACT_layer_msg($msg."失败！",8,"index.php?m=admin_job");
			}
		}
	}
	function ajaxjob_action(){
		extract($_GET);
		if($id!=""){
		    $jobs=$this->obj->DB_select_all("job_class","`keyid`=$id");
			if(is_array($jobs)){
				$html .= "<select name='keyid'>";
				if($ajax=="1"){
					$html .= "<option value=\"\">==请选择==</option>";
				}
				foreach($jobs as $key=>$v){
					$html .= "<option value='".$v['id']."'>".$v['name']."</option>";
				}
				$html .= "<select>";
				echo $html;
			 	die;
			}die;
		}
	}
 	function up_action(){
 		if((int)$_GET['id']){
			$id=(int)$_GET['id'];
			$onejob=$this->obj->DB_select_once("job_class","`id`='".$_GET['id']."'");
			$twojob=$this->obj->DB_select_all("job_class","`keyid`='".$_GET['id']."' order by sort asc","id,sort");
			if(is_array($twojob)){
				foreach($twojob as $key=>$v){
					$val[]=$v['id'];
					$root_arr = @implode(",",$val);
				}
			}
			$jobs=$this->obj->DB_select_all("job_class","`keyid`='".$_GET['id']."' or `keyid` in ($root_arr) order by sort asc");
			$a=0;
			if(is_array($jobs)){
				foreach($jobs as $key=>$v){
					if($v['keyid']==$id){
						$twojob[$a]['id']=$v['id'];
						$twojob[$a]['sort']=$v['sort'];
						$twojob[$a]['name']=$v['name'];
						$a++;
					}else{
						$threejob[$v['keyid']][]=$v;
					}
				}
			}
			$this->yunset("id",$id);
			$this->yunset("onejob",$onejob);
			$this->yunset("twojob",$twojob);
			$this->yunset("threejob",$threejob);
		}
		$position=$this->obj->DB_select_all("job_class","`keyid`='0'");
		$this->yunset("position",$position);
		$this->yuntpl(array('admin/admin_job'));
	}
 	function upp_action(){

		if($_POST['update']){
			if(!empty($_POST['position'])){
				$value="`name`='".$_POST['position']."',`sort`='".$_POST['sort']."'";
				$where="`id`='".$_POST['id']."'";
				$up=$this->obj->DB_update_all("job_class",$value,$where);
				$this->cache_action();
 		        $up?$this->ACT_layer_msg("职位类别(ID:".$_POST['id'].")更新成功！",9,$_SERVER['HTTP_REFERER'],2,1):$this->ACT_layer_msg("添加失败！",8,$_SERVER['HTTP_REFERER']);
			}else{
				$this->ACT_layer_msg("请正确填写你要更新的职位！",8,$_SERVER['HTTP_REFERER']);
			}
		}
		$this->yuntpl(array('admin/admin_job'));
	}
 	function del_action(){
		if((int)$_GET['delid']){
			$this->check_token();
			$ids=$this->sonclass(array(intval($_GET['delid'])));
			$layer_type='0';
		}else if($_POST['del']){ 
			$ids=$this->sonclass($_POST['del']);
			$layer_type='1';
		}
		$id=$this->obj->DB_delete_all("job_class","`id` in (".@implode(',',$ids).")","");
		$this->cache_action();
		isset($id)?$this->layer_msg('删除成功！',9,$layer_type,$_SERVER['HTTP_REFERER']):$this->layer_msg('删除失败！',8,$layer_type,$_SERVER['HTTP_REFERER']);
	}
	function sonclass($id){
		$class=$this->obj->DB_select_all("job_class","`keyid` in(".@implode(',',$id).")","id");
		if($class&&is_array($class)){
			foreach($class as $val){
				$ids[]=$val['id'];
			}
		}
		$cl=$this->obj->DB_select_all("job_class","`keyid` in(".@implode(',',$ids).")","id");
		if($cl){
			foreach($cl as $v){
				$ids[]=$v['id'];
			}
		}
		if($ids&&is_array($ids)){
			$ids=array_merge($id,$ids);
		}else{
			$ids=$id;
		}

		return $ids;
	}
	function sclass_action(){
		include(PLUS_PATH."job.cache.php");
		if(is_array($job_type[$_GET['pid']])){
			$html="<option value=\"\">--请选择--</option>";
			foreach($job_type[$_GET['pid']] as $val){
				$html.="<option value=\"".$val."\">".$job_name[$val]."</option>";
			}
		}else{
			$html="<option value=\"\">--暂无子类--</option>";
		}
		echo $html;die;
	}
	function cache_action(){
		include(LIB_PATH."cache.class.php");
		$cacheclass= new cache(PLUS_PATH,$this->obj);
		$makecache=$cacheclass->job_cache("job.cache.php");
	}
	function move_action(){
		extract($_GET);
		if($keyid==""){
			$move = $this->obj->DB_update_all("job_class","`keyid`=$nid","`id`=$pid");
		}else{
			$move = $this->obj->DB_update_all("job_class","`keyid`=$keyid","`id`=$pid");
		}
 		$move?$this->ACT_layer_msg("职位类别(ID:".$pid.")移动成功！",9,$_SERVER['HTTP_REFERER'],2,1):$this->ACT_layer_msg("移动失败！",8,$_SERVER['HTTP_REFERER']);
	}
	function ajax_action()
	{
		if($_POST['sort']) 
		{
			$this->obj->DB_update_all("job_class","`sort`='".$_POST['sort']."'","`id`='".$_POST['id']."'");
			$this->MODEL('log')->admin_log("修改职位类别(ID:".$_POST['id'].")的排序");
		}
		if($_POST['name']) 
		{
			$this->obj->DB_update_all("job_class","`name`='".$_POST['name']."'","`id`='".$_POST['id']."'");
			$this->MODEL('log')->admin_log("修改职位类别(ID:".$_POST['id'].")的名称");
		}
		$this->cache_action();echo '1';die;
	}
}

?>