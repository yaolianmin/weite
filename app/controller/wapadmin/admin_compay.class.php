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
class admin_compay_controller extends adminCommon{
	function index_action(){
		$user=$com=$lt=$uids=$username=array();
		$where=1;
		$where.=" order by id desc";
		$urlarr['c']=$_GET['c'];
		$urlarr['page']="{{page}}";
		$pageurl=Url($_GET['m'],$urlarr,'admin');
		$rows=$this->get_page("company_pay",$where,$pageurl,$this->config['sy_listnum']);

		include (APP_PATH."/config/db.data.php");
		if(is_array($rows)){
			foreach($rows as $k=>$v){
				$rows[$k]['pay_state_n']=$arr_data['paystate'][$v['pay_state']];
				$classid[]=$v['com_id'];
				if(in_array($v['com_id'],$uids)==false){$uids[]=$v['com_id'];}
			} 
			if($uids){
				$member=$this->obj->DB_select_all("member","`uid` in(".pylode(',',$uids).")","`uid`,`usertype`,`username`"); 
				foreach($member as $v){
					$username[$v['uid']]=$v['username'];
					if($v['usertype']=='1'){
						$user[]=$v['uid'];
					}else if($v['usertype']=='2'){
						$com[]=$v['uid'];
					}else if($v['usertype']=='3'){
						$lt[]=$v['uid'];
					}else if($v['usertype']=='4'){
						$px[]=$v['uid'];
					}
					
				} 
				$resume=$this->obj->DB_select_all("resume","`uid` in(".pylode(',',$user).")","`uid`,`name`");
				$company=$this->obj->DB_select_all("company","`uid` in(".pylode(',',$com).")","`uid`,`name`");
				$ltinfo=$this->obj->DB_select_all("lt_info","`uid` in(".pylode(',',$lt).")","`uid`,`realname` as `name`"); 
				
			} 
		}
		$info=array_merge($resume,$company,$ltinfo,$px);
		foreach($info as $v){
			$userinfo[$v['uid']]=$v['name'];
		} 
		foreach($rows as $k=>$v){ 
			if(empty($userinfo[$v['com_id']])){
			    $rows[$k]['comname']=$username[$v['com_id']];
				$rows[$k]['username']=$username[$v['com_id']]; 
			}else{
			    $rows[$k]['comname']=$userinfo[$v['com_id']];
				$rows[$k]['username']=$username[$v['com_id']]; 
			}
		} 
		$this->yunset("get_type", $_GET);
		$this->yunset("rows",$rows);
		$this->yunset("headertitle","消费记录");
		$this->yuntpl(array('wapadmin/admin_compay'));
	}

	function del_action(){
	    
	    $delid=(int)$_GET['id'];
	    if(!$delid){
	        $this->layer_msg('请选择要删除的记录！',8,0,$_SERVER['HTTP_REFERER']);
	    }
	    $del=$this->obj->DB_delete_all("company_pay","`id`='".$delid."'");
	    if($del){
	        $this->layer_msg('消费记录(ID:'.$delid.')删除成功！',9,0,$_SERVER['HTTP_REFERER']);
	    }else{
	        $this->layer_msg('删除失败！',8);
	    }
	}
}
?>