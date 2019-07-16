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
class comnews_controller extends adminCommon{
	function index_action(){
		$where=1;
		if($_GET['status']){
			if($_GET['status']=="3"){
				$where.= " and `status`='0'";
			}else{
				$where.= " and `status`='".$_GET['status']."'";
			}
			$urlarr['status']=$_GET['status'];
		}
		$where.=" order by `id` desc";
		$urlarr['c']=$_GET['c'];
		$urlarr['page']="{{page}}";
		$urlarr=Url($_GET['m'],$urlarr,'admin');
		$rows = $this->get_page("company_news",$where,$urlarr,$this->config['sy_listnum']);
		if($rows&&is_array($rows)){	
			$uids=array();
			foreach($rows as $val){
				if(in_array($val['uid'],$uids)==false){
					$uids[]=$val['uid'];
				}
			}
			$company=$this->obj->DB_select_all("company","`uid` in(".@implode(',',$uids).")","`uid`,`name`");		
			foreach($rows as $key=>$val){
				foreach($company as $v){
					if($val['uid']==$v['uid']){
						$rows[$key]['name']=$v['name'];
					}
				} 
			}
		}
		$this->yunset("rows",$rows);
		$this->yunset('backurl','index.php?c=company');
		$this->yunset("headertitle","企业新闻");
		$this->yuntpl(array('wapadmin/admin_comnews'));
	}
	function show_action(){
		$row=$this->obj->DB_select_once("company_news","`id`='".$_GET['id']."'");
		$com=$this->obj->DB_select_once("company","`uid`='".$row['uid']."'","`uid`,`name`");
		$row['name']=$com['name'];
		
		$lasturl=$_SERVER['HTTP_REFERER'];
		if(strpos($lasturl, 'a=show')===false){
		    if(strpos($lasturl, 'c=comnews')!==false){
		        $this->cookie->setcookie('lasturl',$lasturl,time()+300);
		        $_COOKIE['lasturl']=$lasturl;
		    }
		}
		$this->yunset('lasturl',$_COOKIE['lasturl']);
		
		$this->yunset('row',$row);
		$this->yunset("headertitle","企业新闻设置");
		$this->yuntpl(array('wapadmin/admin_comnews_show'));
	}
	function status_action(){
	    if($_POST['id']){
	        $_POST['statusbody']=$this->stringfilter($_POST['statusbody']);
	        $nid=$this->obj->DB_update_all("company_news","`status`='".$_POST['status']."',`statusbody`='".$_POST['statusbody']."'","`id`='".$_POST['id']."'");
	        if ($_POST['lasturl']!=''){
	            $lasturl=$this->post_trim($_POST['lasturl']);
	        }else{
	            $lasturl=$_SERVER['HTTP_REFERER'];
	        }
	        if($nid){
	            $this->layer_msg('企业新闻审核(ID:'.$_POST['id'].')设置成功！',9,0,$lasturl);
	        }else{
	            $this->layer_msg('设置失败！',8);
	        }
	    }
	}
	function del_action(){
	    if($_GET['id']){
	        $del=$this->obj->DB_delete_all("company_news","`id`='".intval($_GET['id'])."'");
	        if($del){
	            $this->layer_msg('企业新闻(ID:'.intval($_GET["id"]).')删除成功！',9,0,'index.php?c=comnews');
	        }else{
	            $this->layer_msg('删除失败！',8);
	        }
	    }
	}
}
?>