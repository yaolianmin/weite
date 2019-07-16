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
class admin_memberlog_controller extends adminCommon{	 
	function index_action(){
		$where = "1";
		if($_GET['utype']){
			$utype=$_GET['utype'];
			$where.=" and `usertype`='".$_GET['utype']."'";
			$urlarr['utype']=$_GET['utype'];
		}else{
			$utype=1;
			$where.=" and `usertype`='1'";
			$urlarr['utype']=$_GET['utype'];
		}
		if($_GET['end']){
			if($_GET['end']=='1'){
				$where.=" and `ctime` >= '".strtotime(date("Y-m-d 00:00:00"))."'";
			}else{
				$where.=" and `ctime` >= '".strtotime('-'.(int)$_GET['end'].'day')."'";
			}
			$urlarr['end']=$_GET['end'];
		} 
		
		
		if(trim($_GET['keyword'])){
			if($_GET['type']==1){
				$member=$this->obj->DB_select_all("member","`username` like '%".trim($_GET['keyword'])."%' and `usertype`='".$utype."'","`uid`,`username`");
				foreach($member as $v){
					$uid[]=$v['uid'];
				}
				$where.=" and `uid` in (".@implode(",",$uid).")";
			}else{
				$where.=" and `content` like '%".trim($_GET['keyword'])."%'";
			}
			$urlarr['keyword']=$_GET['keyword'];
			$urlarr['type']=$_GET['type'];
		}
		if($_GET['time']){
			$time=@explode('~',$_GET['time']);
			$where.=" and `ctime` >='".strtotime($time[0]."00:00:00")."' and `ctime` <='".strtotime($time[1]."23:59:59")."'";
			$urlarr['time']=$_GET['time'];
		}
		if($_GET['order']){
			$where.=" order by ".$_GET['t']." ".$_GET['order'];
			$urlarr['order']=$_GET['order'];
			$urlarr['t']=$_GET['t'];
		}else{
			$where.=" order by `id` desc";
		}
		$urlarr['page']="{{page}}";
		$pageurl=Url($_GET['m'],$urlarr,'admin');
		$rows=$this->get_page("member_log",$where,$pageurl,$this->config['sy_listnum']);
		if(is_array($rows)){
			foreach($rows as $v){
				$uid[]=$v['uid'];
			}
			$member=$this->obj->DB_select_all("member","`uid` in (".@implode(",",$uid).") and `usertype`='".$utype."'","`uid`,`username`");
			foreach($rows as $k=>$v){
				foreach($member as $val){
					if($v['uid']==$val['uid']){
						$rows[$k]['username']=$val['username'];
					}
				}
			}
		}
		$ad_time=array('1'=>'今天','3'=>'最近三天','7'=>'最近七天','15'=>'最近半月','30'=>'最近一个月');
		$search_list[]=array("param"=>"end","name"=>'操作时间',"value"=>$ad_time);
		$this->yunset("search_list",$search_list);
		$this->yunset("type",$_GET['type']);
		$this->yunset("rows",$rows);
		if($_GET['utype']==2){
			$this->yuntpl(array('admin/admin_comlog'));
		}elseif($_GET['utype']==3){
			$this->yuntpl(array('admin/admin_ltlog'));
		}elseif($_GET['utype']==4){
			$this->yuntpl(array('admin/admin_trainlog'));
		}else{
			$this->yuntpl(array('admin/admin_userlog'));
		}
	}
	function dellog_action(){
		$this->check_token();
 		if($_GET['del']=='allcom'){
	    	$this->obj->DB_delete_all("member_log","`usertype`='2'","");
			$this->layer_msg('已清空企业日志！',9,0,$_SERVER['HTTP_REFERER']);
	    }elseif($_GET['del']=='alluser'){
	    	$this->obj->DB_delete_all("member_log","`usertype`='1'","");
			$this->layer_msg('已清空个人日志！',9,0,$_SERVER['HTTP_REFERER']);
	    }elseif($_GET['del']=='alllt'){
	    	$this->obj->DB_delete_all("member_log","`usertype`='3'","");
			$this->layer_msg('已清空猎头日志！',9,0,$_SERVER['HTTP_REFERER']);
	    }elseif($_GET['del']){
	    	$del=$_GET['del'];
	    	if($del){
	    		if(is_array($del)){
					$layer_type=1;
					$this->obj->DB_delete_all("member_log","`id` in (".@implode(',',$del).")","");
					$del=@implode(',',$del);
		    	}else{
					$this->obj->DB_delete_all("member_log","`id`='".$del."'");
					$layer_type=0;
		    	}
				$this->layer_msg('会员日志(ID:'.$del.')删除成功！',9,$layer_type,$_SERVER['HTTP_REFERER']);
	    	}else{
				$this->layer_msg('请选择您要删除的信息！',8,0,$_SERVER['HTTP_REFERER']);
	    	}
	    }
	}
	
	function login_action(){
		$where = "1";
		if(trim($_GET['keyword'])){
			if($_GET['type']==1){
				$member=$this->obj->DB_select_all("member","`username` like '%".trim($_GET['keyword'])."%' ","`uid`,`username`");
				foreach($member as $v){
					$uid[]=$v['uid'];
				}
				$where.=" and `uid` in (".@implode(",",$uid).")";
			}else{
				$where.=" and `content` like '%".trim($_GET['keyword'])."%'";
			}
			$urlarr['keyword']=$_GET['keyword'];
			$urlarr['type']=$_GET['type'];
		}
		if($_GET['time']){
			$time=@explode('~',$_GET['time']);
			$where.=" and `ctime` >='".strtotime($time[0]."00:00:00")."' and `ctime` <='".strtotime($time[1]."23:59:59")."'";
			$urlarr['time']=$_GET['time'];
		}
		if($_GET['end']){
			if($_GET['end']=='1'){
				$where.=" and `ctime` >= '".strtotime(date("Y-m-d 00:00:00"))."'";
			}else{
				$where.=" and `ctime` >= '".strtotime('-'.(int)$_GET['end'].'day')."'";
			}
			$urlarr['end']=$_GET['end'];
		} 
		if($_GET['order']){
			$where.=" order by ".$_GET['t']." ".$_GET['order'];
			$urlarr['order']=$_GET['order'];
			$urlarr['t']=$_GET['t'];
		}else{
			$where.=" order by `id` desc";
		}
		$urlarr['c']="login";$urlarr['utype']="5";
		$urlarr['page']="{{page}}";
		$pageurl=Url($_GET['m'],$urlarr,'admin');
		$rows=$this->get_page("login_log",$where,$pageurl,$this->config['sy_listnum']);		
		if(is_array($rows)){
			foreach($rows as $v){
				$uid[]=$v['uid'];
			}
			$member=$this->obj->DB_select_all("member","`uid` in (".@implode(",",$uid).")","`uid`,`username`");
			foreach($rows as $k=>$v){
				foreach($member as $val){
					if($v['uid']==$val['uid']){
						$rows[$k]['username']=$val['username'];
					}
				}
			}
		}
		$ad_time=array('1'=>'今天','3'=>'最近三天','7'=>'最近七天','15'=>'最近半月','30'=>'最近一个月');
		$search_list[]=array("param"=>"end","name"=>'操作时间',"value"=>$ad_time);
		$this->yunset("search_list",$search_list);
		$this->yunset("rows",$rows);
		$this->yuntpl(array('admin/admin_loginlog'));
	}	
	function dellogs_action(){
		$this->check_token();
	    if($_GET['del']){
	    	$del=$_GET['del'];
	    	if($del){
	    		if(is_array($del)){
					$layer_type=1;
					$this->obj->DB_delete_all("login_log","`id` in (".@implode(',',$del).")","");
					$del=@implode(',',$del);
		    	}else{
					$this->obj->DB_delete_all("login_log","`id`='".$del."'");
					$layer_type=0;
		    	}
				$this->layer_msg('登录日志(ID:'.$del.')删除成功！',9,$layer_type,$_SERVER['HTTP_REFERER']);
	    	}else{
				$this->layer_msg('请选择您要删除的信息！',8,0,$_SERVER['HTTP_REFERER']);
	    	}
	    }
	}
	function clearlog_action(){
		$del=$this->obj->DB_delete_all("login_log","1"," ");
		$this->layer_msg('已清空登录日志！',9,0,$_SERVER['HTTP_REFERER']);
	}
}
?>