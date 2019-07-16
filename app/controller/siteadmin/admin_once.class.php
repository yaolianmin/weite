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
class admin_once_controller extends siteadmin_controller
{
	function set_search(){
		$search_list[]=array("param"=>"status","name"=>'审核状态',"value"=>array("1"=>"已审核","3"=>"未审核","2"=>"已过期"));
		$lo_time=array('1'=>'今天','3'=>'最近三天','7'=>'最近七天','15'=>'最近半月','30'=>'最近一个月');
		$search_list[]=array("param"=>"time","name"=>'发布时间',"value"=>$lo_time);
		$this->yunset("search_list",$search_list);
	}
	function index_action(){
		$this->set_search(); 
		$where=1;
		if(trim($_GET['keyword'])){
			if($_GET['type']){
				if ($_GET['type']=='5'){
					$where.=" and `companyname` like '%".trim($_GET['keyword'])."%'";
				}elseif ($_GET['type']=='2'){
					$where.=" and `title` like '%".trim($_GET['keyword'])."%'";
				}elseif ($_GET['type']=='3'){
					$where.=" and `phone` like '%".trim($_GET['keyword'])."%'";
				}elseif ($_GET['type']=='4'){
					$where.=" and `linkman` like '%".trim($_GET['keyword'])."%'";
				}
				$urlarr['type']=$_GET['type'];
			}
			$urlarr['keyword']=$_GET['keyword'];
		}elseif($_GET['status']){
			$time=mktime();
			if($_GET['status']=='1'){
				$where.=" and status='1' and `edate`>$time ";
				$urlarr['status']='1';
			}elseif($_GET['status']=='3'){
				$where.=" and status='0' and `edate`>$time ";
				$urlarr['status']='3';
			}elseif($_GET['status']=='2'){
				$where.=" and `edate`<$time ";
				$urlarr['status']='2';
			}
		} 
		if($_GET['time']){
			if($_GET['time']=='1'){
				$where.=" and `ctime` >= '".strtotime(date("Y-m-d 00:00:00"))."'";
			}else{
				$where.=" and `ctime` >= '".strtotime('-'.(int)$_GET['time'].'day')."'";
			}
			$urlarr['time']=$_GET['time'];
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
        $M=$this->MODEL(); 
		$rows=$M->get_page("once_job",$where,$pageurl,$this->config['sy_listnum']);  
		if(is_array($rows['rows'])&&$rows['rows']) {
			foreach($rows['rows'] as $key=>$val) {
				if($val['edate']<mktime()){
					$rows['rows'][$key]['status']=2;
				}
			}
		}
		$_GET['c']='';
		$this->yunset($rows);
		$this->yunset("get_type", $_GET);
		$this->siteadmin_tpl(array('admin_once'));
	}

	function ctime_action(){
		extract($_POST);
		$id=@explode(",",$onceids);
		if(is_array($id)){
			$posttime=$endtime*86400;
			$CompanyM=$this->MODEL('company');
			$id=$CompanyM->UpdateOnceJob(array("`edate`=`edate`+'".$posttime."'"),array("`id` in (".$onceids.")")); 
			$id?$this->ACT_layer_msg("职位延期(ID:".$jobid.")设置成功！",9,$_SERVER['HTTP_REFERER'],2,1):$this->ACT_layer_msg("设置失败！",8,$_SERVER['HTTP_REFERER']);
		}else{
			$this->ACT_layer_msg("非法操作！",8,$_SERVER['HTTP_REFERER']);
		}
	}
	function show_action(){
		$this->yunset($this->MODEL('cache')->GetCache(array('city')));
		$CompanyM=$this->MODEL('company');
		$show=$CompanyM->GetOnceJob(array("id"=>$_GET['id'])); 
		$this->yunset("show",$show);
		$this->siteadmin_tpl(array('admin_once_show'));
	}
	function status_action(){
		$CompanyM=$this->MODEL('company');
		$CompanyM->UpdateOnceJob(array("status"=>$_POST['status']),array("`id` IN (".$_POST['allid'].")")); 
		$this->MODEL('log')->admin_log("职位(ID:".$_POST['allid'].")审核成功");
		echo $_POST['status'];die;
	}
	function ajax_action()
	{
		include PLUS_PATH."/user.cache.php";
		include PLUS_PATH."/city.cache.php";
		$CompanyM=$this->MODEL('company');
		$row=$CompanyM->GetOnceJob(array("id"=>$_GET['id'])); 
		$info['title']=$row['title'];
		$info['mans']=$row['mans'];
		$info['require']=$row['require'];
		$info['companyname']=$row['companyname'];
		$info['phone']=$row['phone'];
		$info['linkman']=$row['linkman'];
		$city=$city_name[$row['provinceid']].'-'.$city_name[$row['cityid']];
		if($row['three_cityid']){
			$city.='-'.$city_name[$row['three_cityid']];
		}
		$info['city']=$city;
		$info['address']=$row['address'];
		$info['time']=date("Y-m-d",$row['ctime']);
		$info['status']=$row['status'];
		$info['qq']=$row['qq'];
		$info['email']=$row['email'];
		$info['edate']=date("Y-m-d",$row['edate']);
		echo json_encode($info);
	}
	function edit_action(){
		$this->yunset($this->MODEL('cache')->GetCache(array('city')));
		$id=(int)$_GET['id'];
		$row=$this->obj->DB_select_once('once_job',"`id`='".$id."'");
		$row['edate']=ceil(($row['edate']-mktime())/86400);
		$this->yunset("row",$row);
		$this->yuntpl(array('siteadmin/admin_once_add'));
	}
	function save_action(){
		$id=(int)$_POST['id'];
		unset($_POST['submit']);
		if($_POST['title']==''){
			$this->ACT_layer_msg('请填写职位名称！',8);
		}
		if($_POST['companyname']==''){
			$this->ACT_layer_msg('请填写(店面)名称！',8);
		}
		if($_POST['phone']==''){
			$this->ACT_layer_msg('请填写手机！',8);
		}
		if($_POST['cityid']==''){
			$this->ACT_layer_msg('请选择工作地点！',8);
		}
		if($_POST['require']==''){
			$this->ACT_layer_msg('请填写招聘要求！',8);
		}
		if($_POST['password']){			
			$value=",`password`='".md5($_POST['password'])."'"; 
		}
		if(is_uploaded_file($_FILES['pic']['tmp_name'])){
			$UploadM=$this->MODEL('upload');
			$upload=$UploadM->Upload_pic("../data/upload/once/",false);
			$pictures=$upload->picture($_FILES['pic']);
			$pic=str_replace("../data/upload/once/","data/upload/once/",$pictures);
			$_POST['pic']=$pic;
		}else{
			$row=$this->obj->DB_select_once('once_job',"`id`='".$id."'");
	        $_POST['pic']=$row['pic'];
	    }
			
		$_POST['edate']=strtotime("+".(int)$_POST['edate']." days");
		if($_POST['id']){
			$tid=$this->obj->DB_update_all('once_job',"`title`='".$_POST['title']."',`companyname`='".$_POST['companyname']."',`phone`='".$_POST['phone']."',`linkman`='".$_POST['linkman']."',`require`='".$_POST['require']."',`edate`='".$_POST['edate']."',`provinceid`='".$_POST['provinceid']."',`cityid`='".$_POST['cityid']."',`three_cityid`='".$_POST['three_cityid']."',`pic`='".$_POST['pic']."',`status`='1',`did`='".$this->config['did']."'".$value,"`id`='".$_POST['id']."'");
			$msg="修改成功!";
		}
		if($tid){
			$this->ACT_layer_msg($msg,9,'index.php?m=admin_once');
		}else{
			$this->ACT_layer_msg('操作失败！',8);
		}
	}
	function del_action(){
		$this->check_token();
		$CompanyM=$this->MODEL('company');
	    if($_GET['del']){
	    	$del=$_GET['del'];
	    	if(is_array($del)){
				$CompanyM->DeleteOnceJob("`id` in(".@implode(',',$del).")"); 
				$this->layer_msg("职位(ID:".@implode(',',$del).")删除成功！",9,1,$_SERVER['HTTP_REFERER']);
	    	}else{
				$this->layer_msg("请选择您要删除的招聘！",8,1,$_SERVER['HTTP_REFERER']);
	    	}
	    }
	    if(isset($_GET['id'])){ 
			$result=$CompanyM->DeleteOnceJob(array("id"=>$_GET['id'])); 
			$result?$this->layer_msg("职位(ID:".$_GET['id'].")删除成功！",9,0,$_SERVER['HTTP_REFERER']):$this->layer_msg('删除失败！',8,0,$_SERVER['HTTP_REFERER']);
		}else{ 
			$this->ACT_layer_msg("非法操作！",8,$_SERVER['HTTP_REFERER']);
		}
	}
}
?>