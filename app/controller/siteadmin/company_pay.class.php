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
class company_pay_controller extends siteadmin_controller
{
	function set_search(){
		$ad_time=array('1'=>'今天','3'=>'最近三天','7'=>'最近七天','15'=>'最近半月','30'=>'最近一个月');
		$search_list[]=array("param"=>"end","name"=>'发布时间',"value"=>$ad_time);
		$search_list[]=array("param"=>"pay_state","name"=>'消费状态',"value"=>array("0"=>"支付失败","1"=>"等待付款","2"=>"支付成功","3"=>"等待确认"));
		$this->yunset("search_list",$search_list);
	}
	function index_action(){
		$this->set_search(); 
		$where=1;
		$UserInfoM=$this->MODEL('userinfo');
		if(trim($_GET['keyword'])!=""){
			if ($_GET['type']=='1'){
				$where.=" and `order_id` like '%".trim($_GET['keyword'])."%'";
			}elseif ($_GET['type']=='3'){
				$where.=" and `pay_remark` like '%".trim($_GET['keyword'])."%'";
			}elseif ($_GET['type']=='2'){ 
				$member=$UserInfoM->GetMemberList(array("`username` like '%".trim($_GET['keyword'])."%'"),array("field"=>"uid"));
				if(is_array($member)) {
					foreach($member as $v) {
						$uids[]=$v['uid'];
					}
				}
				$where.=" and `com_id` in (".@implode(",",$uids).")";
			}
			$urlarr['type']=$_GET['type'];
			$urlarr['keyword']=$_GET['keyword'];
		}
		if($_GET['pay_state']!=""){
            $where.=" and `pay_state`='".$_GET['pay_state']."'";
			$urlarr['pay_state']=$_GET['pay_state'];
	    }
		if($_GET['end']){
			if($_GET['end']=='1'){
				$where.=" and `pay_time` >= '".strtotime(date("Y-m-d 00:00:00"))."'";
			}else{
				$where.=" and `pay_time` >= '".strtotime('-'.$_GET['end'].'day')."'";
			}
			$urlarr['end']=$_GET['end'];
		}

		if($_GET['order'])
		{
			$where.=" order by ".$_GET['t']." ".$_GET['order'];
			$urlarr['order']=$_GET['order'];
			$urlarr['t']=$_GET['t'];
		}else{
			$where.=" order by id desc";
		}
		$urlarr['page']="{{page}}";
		$pageurl=Url($_GET['m'],$urlarr,'admin');
		$rows=$this->MODEL()->get_page("company_pay",$where,$pageurl,$this->config['sy_listnum']);

		include (APP_PATH."/config/db.data.php");
		if(is_array($rows['rows'])){
			$classid=array();
			foreach($rows['rows'] as $k=>$v){
				$rows['rows'][$k]['pay_state_n']=$arr_data['paystate'][$v['pay_state']];
				if(in_array($v['com_id'],$classid)==false){
					$classid[]=$v['com_id'];
				} 
			}
		}
		if(is_array($classid)){
			$member=$UserInfoM->GetMemberList(array("uid in (".@implode(",",$classid).")"),array("field"=>"`uid`,`username`,`usertype`","special"=>'member'));
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
			$user=$UserInfoM->GetUserinfoList(array("`uid` in(".pylode(',',$user).")"),array("field"=>"`uid`,`name`","special"=>'member','usertype'=>1));
			$com=$UserInfoM->GetUserinfoList(array("`uid` in(".pylode(',',$com).")"),array("field"=>"`uid`,`name`","special"=>'company','usertype'=>2));
			$lt=$UserInfoM->GetUserinfoList(array("`uid` in(".pylode(',',$lt).")"),array("field"=>"`uid`,`realname` as `name`","special"=>'lt_info','usertype'=>3));
			$px=$UserInfoM->GetUserinfoList(array("`uid` in(".pylode(',',$px).")"),array("field"=>"`uid`,`name`","special"=>'px_train','usertype'=>4)); 			
			$group=array_merge($com,$lt,$user,$px);
		}
		foreach($rows['rows'] as $k=>$v){
			foreach($group as $val){
				if($v['com_id']==$val['uid']){
					$rows['rows'][$k]['comname']=$val['name'];
					$rows['rows'][$k]['username']=$username[$val['uid']];
				}
			}
		}
		$this->yunset("get_type", $_GET);
		$this->yunset($rows);
		$this->siteadmin_tpl(array('admin_company_pay'));
	}

	function del_action(){
		$this->check_token();
		$UserInfoM=$this->MODEL('userinfo');
	    if($_GET['del']){
	    	$del=$_GET['del'];
	    	if($del){
	    		if(is_array($del)){
					$UserInfoM->DeleteComPay(array("`id` in(".@implode(',',$_GET['del']).")"));
					$del=@implode(',',$_GET['del']);
		    	}else{
					$UserInfoM->DeleteComPay(array("id"=>$del));
		    	}
				$this->layer_msg('消费记录(ID:'.$del.')删除成功！',9,1,$_SERVER['HTTP_REFERER']);
	    	}else{
				$this->layer_msg('请选择您要删除的信息！',9,1,$_SERVER['HTTP_REFERER']);
	    	}
	    }
	    if(isset($_GET['id'])){
			$result=$UserInfoM->DeleteComPay(array("id"=>$_GET['id']));
			isset($result)?$this->layer_msg('消费记录(ID:'.$_GET['id'].')删除成功！',9,0,$_SERVER['HTTP_REFERER']):$this->layer_msg('删除失败！',8,0,$_SERVER['HTTP_REFERER']);
		}else{
			$this->layer_msg('非法操作！',9,0,$_SERVER['HTTP_REFERER']);
		}
	}
}
?>