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
class admin_lt_rating_controller extends adminCommon{
	function index_action(){
		$where="`category`='2'";
		if($_GET['rating']){
			$where.=" and `id`='".$_GET['rating']."'";
			$urlarr['rating']=$_GET['rating'];
		}
		$where.=" order by `sort`";
		$urlarr['page']="{{page}}";
		$pageurl=Url($_GET['m'],$urlarr,'admin');
		$list=$this->get_page("company_rating",$where,$pageurl,$this->config['sy_listnum']);
 		$this->yunset("list",$list);
		$this->yuntpl(array('admin/admin_lt_rating'));
	}
	function rating_action(){
		if($_GET['id']){
			$row=$this->obj->DB_select_once("company_rating","`id`='".$_GET['id']."'");
			$this->yunset("row",$row);
		}
		$coupon=$this->obj->DB_select_all("coupon");
		$this->yunset("coupon",$coupon);
		$this->yuntpl(array('admin/admin_ltclass_add'));
	}
	function saveclass_action(){
	    $id=$_POST['id'];
	    unset($_POST['useradd']);
	    unset($_POST['id']);
	    if($_POST['time']){
	        $times=@explode('~',$_POST['time']);
	        $_POST['time_start']=strtotime($times[0]." 00:00:00");
	        $_POST['time_end']=strtotime($times[1]." 23:59:59");
	    }else{
	        unset($_POST['time']);
	    }
	    $row=$this->obj->DB_select_once("company_rating","`id`='".$id."'");
	    $_POST['com_pic'] = $this->checksrc($_POST['com_pic'],$row['com_pic']);
	    if(!$id){
	        $nid=$this->obj->insert_into("company_rating",$_POST);
	        $name="猎头会员等级（ID：".$nid."）添加";
	    }else{
	        $where['id']=$id;
	        $nid=$this->obj->update_once("company_rating",$_POST,$where);
	        $name="猎头会员等级（ID：".$id."）更新";
	    }
		$nid?$this->ACT_layer_msg($name."成功！",9,"index.php?m=admin_lt_rating",2,1):$this->ACT_layer_msg($name."失败！",8,"index.php?m=admin_lt_rating");
	}
 
	function opera_action(){
		if ($_POST['display'] && $_POST['id']){
			$nid=$this->obj->update_once("lt_service",array("display"=>$_POST['display']),array("id"=>$_POST['id']));
			if ($nid){
				echo 1;die;
			}else{
				echo 2;die;
			}
		}
	}
	function delrating_action(){
		if($_POST['del']){
			$layer_type='1';
			$id=pylode(',',$_POST['del']);
		}else if($_GET['id']){
			$this->check_token();
			$id=$_GET['id'];
			$layer_type='0';
		}
		$nid=$this->obj->DB_delete_all("company_rating","`id` in(".$id.")","");
		$nid?$this->layer_msg('删除猎头会员等级（ID：(ID:'.$id.')成功！',9,$layer_type,$_SERVER['HTTP_REFERER']):$this->layer_msg('删除失败！',8,$layer_type,$_SERVER['HTTP_REFERER']);  
	}
	function server_action(){
		$list=$this->obj->DB_select_all("lt_service");
		$this->yunset("list",$list);
		$this->yuntpl(array('admin/admin_ltrating'));
	}
	function srating_action(){
		$this->yuntpl(array('admin/admin_ltrating_add'));
	}

 	function list_action(){
		$zzlist=$this->obj->DB_select_all("lt_service");
		$this->yunset("zzlist",$zzlist);
		
		if($_GET['id']){
 			$row=$this->obj->DB_select_once("lt_service","`id`='".$_GET['id']."'");
			$this->yunset("row",$row);
			$list=$this->obj->DB_select_all("lt_service_detail","`type`='".$_GET['id']."' order by `id` asc");
			$this->yunset("list",$list);
		}
		$this->yuntpl(array('admin/admin_ltservice_list'));
	}

	function save_action(){
		if($_POST['useradd']){
			unset($_POST['useradd']);
			$name=$_POST['name'];
			$row=$this->obj->DB_select_all("lt_service","`name`='".$name."'");
			if (!empty($row)){
				$this->ACT_layer_msg("增值包名称已存在！",8,$_SERVER['HTTP_REFERER']);
			}else{
				$nid=$this->obj->insert_into("lt_service",$_POST);
				$name="猎头增值包（ID：".$nid."）添加";
			}
		}
		$nid?$this->ACT_layer_msg($name."成功！<br>请在增值包中添加套餐！",9,"index.php?m=admin_lt_rating&c=edit&id=".$nid,2,1):$this->ACT_layer_msg($name."失败！",8,$_SERVER['HTTP_REFERER']);
	}
	function dels_action(){
		if($_POST['del']){
			$layer_type='1';
			$id=pylode(',',$_POST['del']);
		}else if($_GET['id']){
			$this->check_token();
			$id=$_GET['id'];
			$layer_type='0';
		}
		$nid=$this->obj->DB_delete_all("lt_service","`id` in(".$id.")","");
		$this->obj->DB_delete_all("lt_service_detail","`type`='".$id."'");
		$nid?$this->layer_msg('增值服务包删除(ID:'.$id.')成功！',9,$layer_type,$_SERVER['HTTP_REFERER']):$this->layer_msg('删除失败！',8,$layer_type,$_SERVER['HTTP_REFERER']);
	}
 
	function edit_action(){
		$zzlist=$this->obj->DB_select_all("lt_service");
		$this->yunset("zzlist",$zzlist);
		if ($_GET['id']){
			$row=$this->obj->DB_select_once("lt_service","`id`='".$_GET['id']."'");
			$this->yunset("row",$row);
			$list=$this->obj->DB_select_all("lt_service_detail","`type`='".$_GET['id']."' order by `id` asc");
			$this->yunset("list",$list);
		}
		$this->yuntpl(array('admin/admin_ltservice_add'));
	}

	function edittc_action(){
		$zzlist=$this->obj->DB_select_all("lt_service");
		$this->yunset("zzlist",$zzlist);
		if($_GET['id']){
			$row=$this->obj->DB_select_once("lt_service","`id`='".$_GET['id']."'");
			$this->yunset("row",$row);
			$list=$this->obj->DB_select_all("lt_service_detail","`type`='".$_GET['id']."' order by `id` asc");
			$this->yunset("list",$list);
		}
		if($_GET['tid']){
			$listinfo=$this->obj->DB_select_once("lt_service_detail","`id`='".$_GET['tid']."'");
			$this->yunset("listinfo",$listinfo);
		}
		$this->yuntpl(array('admin/admin_ltservice_add'));
 	}

 
	function saves_action(){
		if($_POST['useradd']){
 			$id=$_POST['type'];
			$_POST['type']=$id;
 			unset($_POST['useradd']);
 			$nid=$this->obj->insert_into("lt_service_detail",$_POST);
			$name="套餐（ID：".$id."）添加";
		}else if($_POST['userupdate']){
			$id=$_POST['type'];
			$tid=$_POST['tid'];
			$_POST['id']=$tid;
			unset($_POST['userupdate']);
			unset($_POST['tid']);
			$nid=$this->obj->update_once("lt_service_detail",$_POST,"`id`='".$tid."'");
			$name="套餐（ID：".$tid."）更新";
		}
		$nid?$this->ACT_layer_msg($name."成功！",9,"index.php?m=admin_lt_rating&c=list&id=".$id,2,1):$this->ACT_layer_msg($name."失败！",8,$_SERVER['HTTP_REFERER']);
	}
 
	function delt_action(){
		if($_POST['del']){
			$layer_type='1';
			$id=pylode(',',$_POST['del']);
		}else if($_GET['id']){
			$this->check_token();
			$id=$_GET['id'];
			$layer_type='0';
		}
		$nid=$this->obj->DB_delete_all("lt_service_detail","`id` in(".$id.")","");
		$nid?$this->layer_msg('套餐删除(ID:'.$id.')成功！',9,$layer_type,$_SERVER['HTTP_REFERER']):$this->layer_msg('删除失败！',8,$layer_type,$_SERVER['HTTP_REFERER']);
	}
}

?>