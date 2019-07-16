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
class admin_user_controller extends siteadmin_controller{
	function index_action(){ 
		$urlarr['page']="{{page}}";
		$pageurl=Url($_GET['m'],$urlarr,'admin');
        $M=$this->MODEL();
		$rows=$M->get_page("admin_user","`did`='".$this->config['did']."' order by uid desc",$pageurl,$this->config['sy_listnum']); 
		$adminusergroup=$this->obj->DB_select_all("admin_user_group","1 order by id desc");
		if(is_array($adminusergroup)){
			foreach($adminusergroup as $v){
				$groupname[$v['id']]=$v['group_name'];
			}
		}
		$this->yunset("groupname",$groupname);
		$this->yunset($rows);
		$this->yunset("adminusergroup",$adminusergroup);
		$this->siteadmin_tpl(array('admin_user_list'));
	}
	function add_action(){
		if(isset($_GET['uid'])){
			$adminuser=$this->obj->DB_select_once("admin_user","`uid`='".$_GET['uid']."'");
			$this->yunset("adminuser",$adminuser);
		}
		$user_group=$this->obj->DB_select_all("admin_user_group","`did`='".$this->config['did']."' order by `id` desc");
		$this->yunset("user_group",$user_group);
		$this->siteadmin_tpl(array('admin_user_add'));
	}
	function myuser_action(){
		$where="`uid`='".$_SESSION['auid']."'";
		$adminuser=$this->obj->DB_select_once("admin_user",$where);
		$this->yunset("adminuser",$adminuser);
		$user_group=$this->obj->DB_select_once("admin_user_group","`id`='".$adminuser['m_id']."'");
		$this->yunset("user_group",$user_group);
		$this->siteadmin_tpl(array('admin_myuser'));
	}
	function pass_action(){
		if($_POST['useradd']){
			$_POST['oldpass']=trim($_POST['oldpass']);
			$_POST['password']=trim($_POST['password']);
			$where="`uid`='".$_SESSION['auid']."' and `did`='".$this->config['did']."'";
			$row=$this->obj->DB_select_once("admin_user",$where);
			if($_POST['oldpass']==''||$_POST['password']==''){
				$this->ACT_layer_msg("原始密码、新密码均不能为空！",8,$_SERVER['HTTP_REFERER']);
			}
			if($_POST['oldpass']==$_POST['password']){
				$this->ACT_layer_msg("新密码和原始密码一致，不需要修改！",8,$_SERVER['HTTP_REFERER']);
			}
			if(md5($_POST['oldpass'])!=$row['password']){
				$this->ACT_layer_msg("原始密码不正确！",8,$_SERVER['HTTP_REFERER']);
			}
			if($_POST['password']!=$_POST['okpassword']){
				$this->ACT_layer_msg("新密码两次输入不一致！",8,$_SERVER['HTTP_REFERER']);
			}
			$value="`password`='".md5($_POST['password'])."'";
			$nbid=$this->obj->DB_update_all("admin_user",$value,$where);
			unset($_SESSION['authcode']);
			unset($_SESSION['auid']);
			unset($_SESSION['ausername']);
			unset($_SESSION['ashell']);
			$this->ACT_layer_msg("管理员(ID:".$row['uid']."帐号".$row['username'].")密码修改成功,请重新登录！",9,$_SERVER['HTTP_REFERER'],2,1);
		}

		$this->siteadmin_tpl(array('admin_mypass'));
	}
	function group_action(){ 
		$adminusergroup=$this->obj->DB_select_all("admin_user_group","`did`='".$this->config['did']."' order by id desc");
		$num=$this->obj->DB_select_all("admin_user","`did`='".$this->config['did']."' group by m_id desc","`m_id`,count(uid) as num");
		foreach($adminusergroup as $key=>$val){ 
			$adminusergroup[$key]['num']=0;
			foreach($num as $v){
				if($val['id']==$v['m_id']){
					$adminusergroup[$key]['num']=$v['num'];
				}
			}
		}
		$this->yunset("adminusergroup",$adminusergroup);
		$this->siteadmin_tpl(array('admin_group_list'));
	}
	function addgroup_action(){
		if((int)$_GET['id']){
			$where="`id`='".$_GET['id']."' and `did`='".$this->config['did']."'";
			$admingroup=$this->obj->DB_select_once("admin_user_group",$where);
			$this->yunset("admin_group",$admingroup);
			$this->yunset("power",unserialize($admingroup[2]));
		}
		$nav_user=$this->obj->DB_select_alls("admin_user","admin_user_group","a.`m_id`=b.`id` and a.`uid`='".$_SESSION['auid']."' and b.`did`='".$this->config['did']."'");
		$menurows=$this->obj->DB_select_all("admin_navigation","`display`<>1 and `dids`='1' order by `sort` desc");
		$i=0;$j=0;$a=0;$b=0;
		if(is_array($menurows)){
			foreach($menurows as $key=>$v){
				if($v['keyid']==0){
					$navigation[$i]['id']=$v['id'];
					$navigation[$i]['name']=$v['name'];
					$i++;
				}
				if($v['menu']==2){
					$menu[$j]['id']=$v['id'];
					$menu[$j]['name']=$v['name'];
					$menu[$j]['url']=$v['url'];
					$j++;
				}
			}
		}
		if(is_array($navigation)){
			foreach($navigation as $va){
				if(is_array($menurows)){
					foreach($menurows as $key=>$v){
						if($v['keyid']==$va['id']){
							if(!is_array($one_menu[$va['id']]))$a=0;
							$one_menu[$va['id']][$a]['id']=$v['id'];
							$one_menu[$va['id']][$a]['name']=$v['name'];
							$a++;
							foreach($menurows as $key=>$vaa){
								if($vaa['keyid']==$v['id']){
									if(!is_array($two_menu[$v['id']]))$b=0;
									$two_menu[$v['id']][$b]['id']=$vaa['id'];
									$two_menu[$v['id']][$b]['name']=$vaa['name'];
									$two_menu[$v['id']][$b]['url']=$vaa['url'];
									$b++;
								}
							}
						}
					}
				}
			}
		}
		$power=unserialize($nav_user[0]['group_power']);
		$this->yunset("one_menu",$one_menu);
		$this->yunset("two_menu",$two_menu);
		$this->yunset("navigation",$navigation);
		$this->siteadmin_tpl(array('admin_group'));
	}
	function savagroup_action(){
		extract($_POST);
		$value="`group_name`='".$group_name."',`group_power`='".serialize($power)."'"; 
		if(!$groupid){ 
			$value.=",`did`='".$this->config['did']."',`group_type`='1'";
			$id=$this->obj->DB_insert_once("admin_user_group",$value);
			isset($id)?$this->ACT_layer_msg( "用户组(ID：".$id.")添加成功！",9,$_SERVER['HTTP_REFERER'],2,1):$this->ACT_layer_msg( "用户组添加失败！",9,$_SERVER['HTTP_REFERER']);
		}else{ 
			$result=$this->obj->DB_update_all("admin_user_group", $value,"`id`='".$groupid."' and `did`='".$this->config['did']."'");
			isset($result)?$this->ACT_layer_msg( "用户组(ID：".$groupid.")修改成功！",9,$_SERVER['HTTP_REFERER'],2,1):$this->ACT_layer_msg( "修改失败！",8,$_SERVER['HTTP_REFERER']);
		}
	}
	function save_action(){
		if(isset($_POST['useradd'])){
			if(!empty($_POST['username'])&&!empty($_POST['name'])){
				$user=$this->obj->DB_select_once("admin_user","`username`='".trim($_POST['username'])."'");
				if($user['uid']&&$_POST[uid]!=$user['uid']){
					$this->ACT_layer_msg( "用户名已存在！",8);
				}
				$value="`m_id`='".$_POST['m_id']."',`username`='".$_POST['username']."',`name`='".$_POST['name']."',`isdid`='2'";
				if($_POST['password']){
					$value.=",`password`='".md5($_POST['password'])."'";
				} 
				if(!$_POST[uid]){
					$value.=",`did`='".$this->config['did']."'";
				 	$nbid=$this->obj->DB_insert_once("admin_user",$value);
					$name="管理员（ID:".$nbid."）添加";
				 }else{
				 	$nbid=$this->obj->DB_update_all("admin_user",$value,"`uid`='".$_POST['uid']."' ");
				 	if($_POST['uid']==$_SESSION['auid']){
						unset($_SESSION['authcode']);
						unset($_SESSION['auid']);
						unset($_SESSION['ausername']);
						unset($_SESSION['ashell']);
						$this->ACT_layer_msg( "管理员(ID:".$_POST['uid'].")修改成功,请重新登录！",9,$_SERVER['HTTP_REFERER'],2,1);
				 	}
				 	$name="管理员(ID:".$_POST['uid'].")更新";
				 }
				isset($nbid)?$this->ACT_layer_msg($name."成功！",9,"index.php?m=admin_user",2,1):$this->ACT_layer_msg($name."失败！",8,"index.php?m=admin_user");
			}else{
				$this->ACT_layer_msg( "请填写完整！",8,$_SERVER['HTTP_REFERER']);
			}
		}
	}
	function deluser_action(){
		$this->check_token();
		if(isset($_GET['uid'])){
			$where="`uid`='".$_GET['uid']."' and `did`='".$this->config['did']."'";
			$result=$this->obj->DB_delete_all("admin_user", $where);
			isset($result)?$this->layer_msg('管理员（ID:'.$_GET['uid'].'）删除成功！',9):$this->layer_msg('删除失败！',8);
		}else{
			$this->layer_msg('非法操作！',8);
		}
	}
	function delgroup_action(){
		$this->check_token();
		if(isset($_GET['id'])){
			$where="`id`='".$_GET['id']."' and `did`='".$this->config['did']."'";
			$result=$this->obj->DB_delete_all("admin_user_group",$where);
			isset($result)?$this->layer_msg('用户组（ID：'.$_GET['id'].'）删除成功！',9):$this->layer_msg('删除失败！',8);
		}else{
			$this->layer_msg('非法操作！',8);
		}
	}
}

?>