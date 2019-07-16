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
class admin_trust_controller extends adminCommon{
	function index_action(){
		$where='1 order by `add_time` desc';
		$urlarr['c']=$_GET['c'];
		$urlarr["page"]="{{page}}";
		$pageurl=Url($_GET['m'],$urlarr,'admin');
		$rows = $this->get_page("user_entrust",$where,$pageurl,$this->config["sy_listnum"]);

		if(is_array($rows)&&$rows){
			$eid=array();
			foreach($rows as $val){
				$eid[]=$val['eid'];
			}
			$resume_expect=$this->obj->DB_select_all("resume_expect","`id` in(".pylode(",",$eid).") ","`id`,`name`");
			foreach($rows as $key=>$value){
				foreach($resume_expect as $val){
					if($value['eid']==$val['id'])
					{
						$rows[$key]['name']=$val['name'];
					}
				}
			}
		}
		$this->yunset("get_info",$_GET);
		$this->yunset("rows",$rows);
		$this->yunset('backurl','index.php?c=user');
		$this->yunset('headertitle','委托简历');
		$this->yuntpl(array('wapadmin/admin_trust'));
	}
	function status_action(){
	    $user_entrust = $this->obj->DB_select_once("user_entrust","`id`='".$_POST['id']."'");
	    if($_POST['status']==2){
	        $user=$this->obj->DB_update_all("resume_expect","`is_entrust`='0'","`uid`='".$user_entrust['uid']."' and `id`='".$user_entrust['eid']."'");
	        if($user['is_entrust']!=0){
	            $this->MODEL('integral')->company_invtal($user_entrust['uid'],$user_entrust['price'],true,"委托简历未接受",true,2,'integral');
	        }
	    }else{
			$this->obj->DB_update_all("resume_expect","`is_entrust`=".$_POST['status'],"`uid`='".$user_entrust['uid']."' and `id`='".$user_entrust['eid']."'");
		}
		$id=$this->obj->DB_update_all("user_entrust","`status`='".$_POST['status']."'","`uid`='".$user_entrust['uid']."' and `id`='".$_POST['id']."'");
		if ($_POST['lasturl']!=''){
		    $lasturl=$this->post_trim($_POST['lasturl']);
		}else{
		    $lasturl=$_SERVER['HTTP_REFERER'];
		}
		if($id){
		    $this->layer_msg('委托简历(ID:'.$_POST['id'].')设置成功！',9,0,$lasturl);
	    }else{
	        $this->layer_msg('设置失败！',8);
	    }
	}
	
	function show_action(){
		$row=$this->obj->DB_select_once('user_entrust','`id`='.intval($_GET['id']).'');
		$expect=$this->obj->DB_select_once('resume_expect','`id`='.intval($_GET['eid']).'','name');
		$this->yunset('row',$row);
		$this->yunset('expect',$expect);
		$lasturl=$_SERVER['HTTP_REFERER'];
		if(strpos($lasturl, 'a=show')===false){
    		if(strpos($lasturl, 'c=admin_trust')!==false){
    		        $_COOKIE['lasturl']=$lasturl;
    		        $this->cookie->setcookie('lasturl',$lasturl,time()+300);
    		    }
		}
		$this->yunset('lasturl',$_COOKIE['lasturl']);
		$this->yunset('headertitle','委托简历设置');
		$this->yuntpl(array('wapadmin/admin_trust_show'));
	}

	function del_action(){
		
		if($_GET["id"]){
		    $eid=$this->obj->DB_select_all("user_entrust","`id`='".intval($_GET['id'])."'","`eid`");
		    $this->obj->DB_update_all("resume_expect","`is_entrust`='0'","`id`='".$eid['eid']."'","resume_expect");
		    $del=$this->obj->DB_delete_all("user_entrust","`id`='".intval($_GET['id'])."'");
		    if($del){
		        $this->layer_msg('委托简历(ID:'.intval($_GET['id']).')删除成功！',9,0,'index.php?c=admin_trust');
		    }else{
		        $this->layer_msg('删除失败！',8);
		    }
		}
	}

}

?>