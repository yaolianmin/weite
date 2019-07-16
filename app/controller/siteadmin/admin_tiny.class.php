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
class admin_tiny_controller extends siteadmin_controller
{
	
	function set_search(){
		include PLUS_PATH."/user.cache.php";      
        foreach($userdata['user_word'] as $k=>$v){
               $ltar[$v]=$userclass_name[$v];
        }
		$search_list[]=array("param"=>"sex","name"=>'用户性别',"value"=>array("1"=>"男","2"=>"女"));
		$search_list[]=array("param"=>"exp","name"=>'工作年限',"value"=>$ltar);
		$search_list[]=array("param"=>"status","name"=>'审核状态',"value"=>array("1"=>"已审核","2"=>"未审核"));
		$lo_time=array('1'=>'今天','3'=>'最近三天','7'=>'最近七天','15'=>'最近半月','30'=>'最近一个月');
		$search_list[]=array("param"=>"time","name"=>'发布时间',"value"=>$lo_time);
		$this->yunset("search_list",$search_list);
	}
	function index_action(){
		$this->set_search();
		$where=1;
		extract($_GET);
		if (trim($keyword)){
			if ($type=='1'){
					$where .=" and `username` like '%".trim($keyword)."%'";
			}elseif ($type=='2'){
					$where .=" and `job` like '%".trim($keyword)."%'";
			}elseif ($type=='3'){
					$where .=" and `mobile` like '%".trim($keyword)."%'";
			}elseif($type=='4'){
					$where .=" and `qq` like '%".trim($keyword)."%'";
			}
			$urlarr['type']=$type;
			$urlarr['keyword']=$keyword;
		}
		if($sex){
			$where .=" and `sex`='".$sex."'";
			$urlarr['sex']=$sex;
		}
		if($_GET['time']){
			if($_GET['time']=='1'){
				$where.=" and `time` >= '".strtotime(date("Y-m-d 00:00:00"))."'";
			}else{
				$where.=" and `time` >= '".strtotime('-'.(int)$_GET['time'].'day')."'";
			}
			$urlarr['time']=$_GET['time'];
		}
		if($exp){
			$where .=" and `exp`='".$exp."'";
			$urlarr['exp']=$exp;
		}
		if($status){
			if($status=='2'){
				$where .=" and `status`=0";
			}elseif($status=='1'){
				$where .=" and `status`=1";
			}
			$urlarr['status']=$status;
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
		$PageInfo=$M->get_page("resume_tiny",$where,$pageurl,$this->config['sy_listnum']);
        $this->yunset($PageInfo);
        $rows=$PageInfo['rows'];
		include PLUS_PATH."/user.cache.php";
		include(CONFIG_PATH."db.data.php");
		unset($arr_data['sex'][3]);
		$this->yunset("arr_data",$arr_data);
		if(is_array($rows)){
			foreach($rows as $k=>$v){
				$rows[$k]['sex']=$arr_data['sex'][$v['sex']];
				$rows[$k]['exp']=$userclass_name[$v['exp']];
			}
		}
		$_GET['c']='';
		$this->yunset("get_type", $_GET);
		$this->yunset("rows",$rows);
		$this->siteadmin_tpl(array('admin_tiny'));
	}
	
	function show_action(){
		include(CONFIG_PATH."db.data.php");
		unset($arr_data['sex'][3]);
		$this->yunset("arr_data",$arr_data);
		$rows=$this->obj->DB_select_once("resume_tiny","`id`='".$_GET['id']."'");
		$this->yunset($this->MODEL('cache')->GetCache(array('user')));
		$rows['sex']=$arr_data['sex'][$rows['sex']];
		$this->yunset("rows",$rows);
		$this->siteadmin_tpl(array('admin_tiny_show'));
	}
	function status_action(){
		$this->obj->DB_update_all("resume_tiny","`status`='".$_POST['status']."'","`id` IN (".$_POST['allid'].")");
		$this->MODEL('log')->admin_log("普工简历(ID:".$_POST['allid'].")审核成功");
		echo $_POST['status'];die;
	}
	function ajax_action()
	{
		include(CONFIG_PATH."db.data.php");
		unset($arr_data['sex'][3]);
		$this->yunset("arr_data",$arr_data);
		include PLUS_PATH."/user.cache.php";
		$row=$this->obj->DB_select_once("resume_tiny","`id`='".$_GET['id']."'");
		$info['username']=$row['username'];
		$info['sex']=$arr_data['sex'][$row['sex']];
		$info['exp']=$userclass_name[$row['exp']];
		$info['mobile']=$row['mobile'];
		$info['qq']=$row['qq'];
		$info['job']=$row['job'];
		$info['production']=$row['production'];
		$info['time']=date("Y-m-d",$row['time']);
		$info['status']=$row['status'];
		echo json_encode($info);
	}
	function del_action(){
		$this->check_token();
	    if($_GET['del']){
	    	$del=$_GET['del'];
	    	if(is_array($del)){
				$this->obj->DB_delete_all("resume_tiny","`id` in(".@implode(',',$del).")","");
				$this->layer_msg( "普工简历(ID:".@implode(',',$del).")删除成功！",9,1,$_SERVER['HTTP_REFERER']);
	    	}else{
				$this->layer_msg( "请选择您要删除的信息！",8,1,$_SERVER['HTTP_REFERER']);
	    	}
	    }
	    if(isset($_GET['id'])){
			$result=$this->obj->DB_delete_all("resume_tiny","`id`='".$_GET['id']."'" );
			isset($result)?$this->layer_msg('普工简历(ID:'.$_GET['id'].')删除成功！',9):$this->layer_msg('删除失败！',8);
		}else{
			$this->layer_msg('非法操作！',8);
		}
	}
	
	function edit_action(){
		$this->yunset($this->MODEL('cache')->GetCache(array('user')));
		include(CONFIG_PATH."db.data.php");
		unset($arr_data['sex'][3]);
		$this->yunset("arr_data",$arr_data);
		$id=(int)$_GET['id'];
		$row=$this->obj->DB_select_once('resume_tiny',"`id`='".$id."'");
		$this->yunset("row",$row);
		$this->siteadmin_tpl(array('admin_tiny_add'));
	}
	function save_action(){
		$id=(int)$_POST['id'];
		if($_POST['username']==''){
			$this->ACT_layer_msg('请填写姓名！',8);
		}
		if($_POST['sex']==''){
			$this->ACT_layer_msg('请填写性别！',8);
		}
		if($_POST['exp']==''){
			$this->ACT_layer_msg('请填写工作年限！',8);
		}
		if($_POST['job']==''){
			$this->ACT_layer_msg('请填写意向职位！',8);
		}
		if($_POST['mobile']==''){
			$this->ACT_layer_msg('请填写手机！',8);
		}		
		if($_POST['production']==''){
			$this->ACT_layer_msg('请填写自我介绍！',8);
		}
		if($_POST['password']){ 
			$value=",`password`='".md5($_POST['password'])."'"; 
		}
		if($_POST['id']){
			$password=md5($_POST['password']);
			$arr=$this->MODEL('tiny')->GetTinyresumeOne(array('id'=>$id,'password'=>$password));
			if(empty($arr)){
				$this->ACT_layer_msg("密码不正确",8);
			}
			$tid=$this->obj->DB_update_all('resume_tiny',"`username`='".$_POST['username']."',`sex`='".$_POST['sex']."',`exp`='".$_POST['exp']."',`job`='".$_POST['job']."',`mobile`='".$_POST['mobile']."',`production`='".$_POST['production']."',`status`='1',`did`='".$this->config['did']."'".$value,"`id`='".$id."'");			
			$msg="修改成功!";
		}
		if($tid){
			$this->layer_msg($msg,9,'index.php?m=admin_tiny');
		}else{
			$this->layer_msg('操作失败！',8);
		}
	}
}
?>