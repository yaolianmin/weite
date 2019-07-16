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
class admin_tiny_controller extends adminCommon{
	function index_action(){  
	    $where='1  order by id desc';
		$urlarr['c']=$_GET['c'];
		$urlarr['page']="{{page}}";
		$pageurl=Url($_GET['m'],$urlarr,'admin');     
		$rows=$this->get_page("resume_tiny",$where,$pageurl,$this->config['sy_listnum']);
        include PLUS_PATH."/user.cache.php";
		if(is_array($rows)){
			foreach($rows as $k=>$v){				
				$rows[$k]['exp']=$userclass_name[$v['exp']];
			}
		}
		$this->yunset("rows",$rows);	
		$this->yunset('backurl','index.php?c=user');
		$this->yunset("headertitle","普工简历");	
		$this->yuntpl(array('wapadmin/admin_tiny'));
	}
	function show_action(){
		include(CONFIG_PATH."db.data.php");	
		unset($arr_data['sex'][3]);	
		$this->yunset("arr_data",$arr_data);
		
		$row=$this->obj->DB_select_once("resume_tiny","`id`='".$_GET['id']."'");
		$this->yunset($this->MODEL('cache')->GetCache(array('user')));
		$lasturl=$_SERVER['HTTP_REFERER'];
		if(strpos($lasturl, 'a=show')===false){
		    if(strpos($lasturl, 'c=admin_tiny')!==false){
		        $_COOKIE['lasturl']=$lasturl;
		        $this->cookie->setcookie('lasturl',$lasturl,time()+300);
		    }
		}
		$this->yunset('lasturl',$_COOKIE['lasturl']);
		$row['sex']=$arr_data['sex'][$row['sex']];
		$this->yunset("row",$row);		
		$this->yunset('headertitle','普工简历详情');
		$this->yuntpl(array('wapadmin/admin_tiny_show'));
	}
	function status_action(){
	    if($_POST['id']){
	        $status=$this->obj->DB_update_all("resume_tiny","`status`='".intval($_POST['status'])."'","`id`='".$_POST['id']."'");
	        if ($_POST['lasturl']!=''){
	            $lasturl=$this->post_trim($_POST['lasturl']);
	        }else{
	            $lasturl=$_SERVER['HTTP_REFERER'];
	        }
	        if($status){
	            $this->layer_msg('普工简历(ID:'.$_POST['id'].')审核设置成功',9,0,$lasturl);
	        }else{
	            $this->layer_msg('设置失败！',8);
	        }
	    }
	}
	function del_action(){
	    
	    if($_GET["id"]){
	        $del=$this->obj->DB_delete_all("resume_tiny","`id`='".intval($_GET["id"])."'");
	        if($del){
	            $this->layer_msg('普工简历(ID:'.intval($_GET["id"]).')删除成功！',9,0,'index.php?c=admin_tiny');
	        }else{
	            $this->layer_msg('删除失败！',8);
	        }
	    }
	}
}
?>