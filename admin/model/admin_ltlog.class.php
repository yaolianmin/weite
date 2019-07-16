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
class admin_ltlog_controller extends adminCommon{	 
	function index_action(){
		$where = "`usertype`='3'";
		if($_GET['operas']){
			$where.=" and `opera`='".$_GET['operas']."'";
			$urlarr['operas']=$_GET['operas'];
		}
		if($_GET['parr']){
			$where.=" and `type`='".$_GET['parrs']."'";
			$urlarr['parrs']=$_GET['parrs'];
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
			$member=$this->obj->DB_select_all("member","`username` like '%".trim($_GET['keyword'])."%' and `usertype`='3'","`uid`,`username`");
			foreach($member as $v){
				$uid[]=$v['uid'];
			}
			$where.=" and `uid` in (".@implode(",",$uid).")";
			$urlarr['keyword']=$_GET['keyword'];
		}
		if($_GET['stime']){
			$where.=" and `ctime` >='".strtotime($_GET['stime']."00:00:00")."'";
			$urlarr['stime']=$_GET['stime'];
		}
		if($_GET['etime']){
			$where.=" and `ctime` <='".strtotime($_GET['etime']."23:59:59")."'";
			$urlarr['etime']=$_GET['etime'];
		}
		if($_GET['order']){
			$where.=" order by ".$_GET['t']." ".$_GET['order'];
			$urlarr['order']=$_GET['order'];
			$urlarr['t']=$_GET['t'];
		}else{
			$where.="order by `id` desc";
		}
		$urlarr['page']="{{page}}";
		$pageurl=Url($_GET['m'],$urlarr,'admin');
		$rows=$this->get_page("member_log",$where,$pageurl,$this->config['sy_listnum']);
		if(is_array($rows)){
			if(trim($_GET['keyword'])==""){
				foreach($rows as $v){
					$uid[]=$v['uid'];
				}
				$member=$this->obj->DB_select_all("member","`uid` in (".@implode(",",$uid).") and `usertype`='3'","`uid`,`username`");
			}
			foreach($rows as $k=>$v){
				foreach($member as $val){
					if($v['uid']==$val['uid']){
						$rows[$k]['username']=$val['username'];
					}
				}
			}
		} 
		if($_GET['operas']=='1'||$_GET['operas']=='2'){
			$parr=array('1'=>'增加','2'=>'修改','3'=>'删除','4'=>'刷新');
			$search_list[]=array("param"=>"parrs","name"=>'操作内容',"value"=>$parr);
		}
		$ad_time=array('1'=>'今天','3'=>'最近三天','7'=>'最近七天','15'=>'最近半月','30'=>'最近一个月');
		$search_list[]=array("param"=>"end","name"=>'操作时间',"value"=>$ad_time);
		$this->yunset("search_list",$search_list);
		$this->yunset("type",$_GET['type']);
		$this->yunset("rows",$rows);
		$this->yuntpl(array('admin/admin_ltlog'));
	}  
	function dellog_action(){
		$this->check_token();
 	    if($_GET['del']){
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
 		if($_GET['time']){
			$time=strtotime($_GET['time']);
			$this->obj->DB_delete_all("member_log","`ctime`<'".$time."' and `usertype`='".(int)$_GET['type']."'","");
			$this->layer_msg('会员日志删除成功！',9,0,$_SERVER['HTTP_REFERER']);
		}
	}
}
?>