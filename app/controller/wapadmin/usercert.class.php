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
class usercert_controller extends adminCommon{
	function index_action(){  
		$where="`idcard_pic`<>''";	
		if($_GET['status']){
			if($_GET['status']==1){
				$where.=" and `idcard_status`='1'";
				$urlarr['status']='1';
			}else if($_GET['status']==2){
				$where.=" and `idcard_status`='0'";
				$urlarr['status']='2';
			}
		}		
		$where.="order by idcard_status=0 desc";
		$urlarr['c']=$_GET['c'];
		$urlarr['page']="{{page}}";
		$pageurl=Url($_GET['m'],$urlarr,'admin');
		$rows=$this->get_page("resume",$where,$pageurl,$this->config['sy_listnum']);
		$this->yunset("rows",$rows);
		$this->yunset("backurl", basename($_SERVER['HTTP_REFERER']));
		$this->yunset("headertitle","个人认证");
		$this->yuntpl(array('wapadmin/usercert'));
	}
	function show_action(){  
	    $row = $this->obj->DB_select_once("resume","`uid` = '".$_GET['id']."'");
	    
	    $lasturl=$_SERVER['HTTP_REFERER'];
	    if(strpos($lasturl, 'a=show')===false){
	        if(strpos($lasturl, 'c=usercert')!==false){
	            $this->cookie->setcookie('lasturl',$lasturl,time()+300);
	            $_COOKIE['lasturl']=$lasturl;
	        }
	    }
	    $this->yunset('lasturl',$_COOKIE['lasturl']);
	    
		$this->yunset("row",$row);
		$this->yunset("headertitle","个人认证设置");
		$this->yuntpl(array('wapadmin/usercert_show'));
	}
	
	function idcard_status_action(){
	    if($_POST['id']){
	        $_POST['statusbody']=$this->stringfilter($_POST['statusbody']);
	        $nid=$this->obj->DB_update_all("resume","`idcard_status`='".$_POST['status']."',`statusbody`='".$_POST['statusbody']."'","`uid`='".$_POST['id']."'");
	        if ($_POST['lasturl']!=''){
	            $lasturl=$this->post_trim($_POST['lasturl']);
	        }else{
	            $lasturl=$_SERVER['HTTP_REFERER'];
	        }
	        if($nid){
	            $this->layer_msg('个人认证(ID:'.$_POST['id'].')设置成功！',9,0,$lasturl);
	        }else{
	            $this->layer_msg('设置失败！',8);
	        }
	    }
	}
	
	function del_action(){
		if(is_array($_GET['del'])){
			$linkid=@implode(',',$_GET['del']);			
		}else{
			$linkid=$_GET['id'];			
		}		
	    $cert=$this->obj->DB_select_all("resume","`uid` in ($linkid)","`idcard_pic`");
	    if(is_array($cert)){
	     	foreach($cert as $v){
	     		unlink_pic($v['idcard_pic']);
	     	}
	    }
		$del=$this->obj->DB_update_all("resume","`idcard_pic`='',`idcard_status`='0',`cert_time`='',`statusbody`=''","`uid` in ($linkid)","");
		if($del){
	        $this->layer_msg('个人认证审核(ID:'.$linkid.')删除成功！',9,0,'index.php?c=usercert');
	    }else{
	        $this->layer_msg('删除失败！',8);
	    }
	}
}
?>