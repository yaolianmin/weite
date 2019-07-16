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
class admin_lt_cert_controller extends siteadmin_controller
{
	
	function set_search(){
		$search_list[]=array("param"=>"status","name"=>'审核状态',"value"=>array("1"=>"已审核","2"=>"未通过","0"=>"未审核"));
		$ad_time=array('1'=>'今天','3'=>'最近三天','7'=>'最近七天','15'=>'最近半月','30'=>'最近一个月');
		$search_list[]=array("param"=>"end","name"=>'申请时间',"value"=>$ad_time);
		$this->yunset("search_list",$search_list);
	}
	function index_action()
	{
		$this->set_search();
		$UserInfoM=$this->MODEL("userinfo");
		$where="`type`='4'";
		if($_GET['status']!=""){
			$where.=" and `status`='".$_GET['status']."'";
			$urlarr['status']=$_GET['status'];
		}
		if(trim($_GET['keyword'])){
			$urlarr['keyword']=$_GET['keyword'];
			$cwhere=array("`realname` LIKE '%".trim($_GET['keyword'])."%'");
		}else{
			$cwhere=array();
		}
		$com=$UserInfoM->GetUserinfoList($cwhere,array("field"=>"`uid`,`realname`","usertype"=>3));
		if(is_array($com)){
			foreach($com as $val){
				$comids[]=$val['uid'];
			}
		}
		$where.=" and `uid` in(".@implode(',',$comids).")";
		if($_GET['end']){
			if($_GET['end']=='1'){
				$where.=" and `ctime` >= '".strtotime(date("Y-m-d 00:00:00"))."'";
			}else{
				$where.=" and `ctime` >= '".strtotime('-'.(int)$_GET['end'].'day')."'";
			}
			$urlarr['end']=$_GET['end'];
		}
		if($_GET['order'])
		{
			$where.=" order by ".$_GET['t']." ".$_GET['order'];
			$urlarr['order']=$_GET['order'];
			$urlarr['t']=$_GET['t'];
		}else{
			$where.=" order by `id` desc";
		}

		$urlarr['page']="{{page}}";
		$urlarr=Url($_GET['m'],$urlarr,'admin');
		$rows = $UserInfoM->get_page("company_cert",$where,$urlarr,$this->config['sy_listnum']);
		if(is_array($rows['rows'])&&$rows['rows']){
			foreach($rows['rows'] as $k=>$v){
				foreach($com as $val){
					if($v['uid']==$val['uid']){
						$rows['rows'][$k]['realname']=$val['realname'];
					}
				}
			}
		}
		$this->yunset($rows);
		$this->siteadmin_tpl(array('admin_lt_cert'));
	}
	function status_action()
	{
		if($_POST['uid'])
		{
			$ltlist=$this->obj->DB_select_all("lt_info","`uid` in (".$_POST['uid'].")","`email`,`uid`,`realname`,`rzid`");
		}else{
			$this->ACT_layer_msg("非法操作！",8,$_SERVER['HTTP_REFERER']);
		}
		if($_POST['status']!="1"){
			$cert_status=0;
		}else{
			$cert_status=1;
		}
		if(is_array($ltlist))
		{
      $notice = $this->MODEL('notice');
			foreach($ltlist as $v)
			{
				$ulen=9-strlen($v['uid']);
				$uid=$v['uid'];
				for($a=1;$a<$ulen;$a++){
					$uid="0".$uid;
				}
				$rzid="YLT".$uid;
				$this->obj->DB_update_all("lt_info","`yyzz_status`='".$cert_status."',`rzid`='".$rzid."'","`uid`='".$uid."'");
        $notice->sendEmailType(array("uid"=>$v['uid'],"name"=>$v['realname'],"email"=>$v['email'],"certinfo"=>$_POST['statusbody'],"comname"=>$v['realname'],"type"=>"comcert"));
 				if($v['rzid']=="" && $_POST['status']=="1")
 				{
 					$this->MODEL('integral')->get_integral_action($v['uid'],"integral_ltcert","猎头执照认证");
 				}
			}
		}
		$id=$this->obj->DB_update_all("company_cert","`status`='".$_POST['status']."',`statusbody`='".$_POST['statusbody']."'","`uid` IN (".$_POST['uid'].") and `type`='4'");
		if($id)
		{
			$this->ACT_layer_msg("猎头认证(UID:".$_POST['status'].")设置成功！",9,$_SERVER['HTTP_REFERER'],2,1);
		}else{
			$this->ACT_layer_msg("设置失败！",8,$_SERVER['HTTP_REFERER']);
		}
	}
	function lockinfo_action(){
		$userinfo = $this->obj->DB_select_once("company_cert","`uid`='".$_POST['uid']."' and `type`='4'","`statusbody`");
		echo $userinfo['statusbody'];die;
	}
	function del_action(){
		extract($_POST);
		if(is_array($del)){
			$linkid=@implode(',',$del);
			$layer_type='1';
		}else{
			$this->check_token();
			$linkid=$_GET['id'];
			$layer_type='0';
		}
		if($linkid==""){
			$this->layer_msg("请选择您要删除的信息！",8,1,$_SERVER['HTTP_REFERER']);
		}
	    $cert=$this->obj->DB_select_all("company_cert","`id` in (".$linkid.") and `type`='4'");
	    if(is_array($cert)){
	     	foreach($cert as $v){
	     		unlink_pic("../".$v['check']);
	     		$uid[]=$v['uid'];
	     	}
	    }
	    $this->obj->DB_update_all("lt_info","`yyzz_status`='0'","`uid` in (".$uid.")");
		$delid=$this->obj->DB_delete_all("company_cert","`id` in (".$linkid.")","");
 		isset($delid)?$this->layer_msg('猎头认证(ID:'.$linkid.')删除成功！',9,$layer_type,$_SERVER['HTTP_REFERER']):$this->layer_msg('删除失败！',8,$layer_type,$_SERVER['HTTP_REFERER']);
	}

}

?>