<?php
/* *
* $Author ：PHPYUN开发团队
*
* 官网: http://www.phpyun.com
*
* 版权所有 2009-2018 宿迁鑫潮信息技术有限公司，并保留所有权利。
*
* 软件声明：未经授权前提下，不得用于商业运营、二次开发以及任何形式的再次发布。
*/
class jobadd_controller extends lietou{
  
	function index_action(){
		$this->public_action();
		include(CONFIG_PATH."db.data.php");		
		$this->yunset("arr_data",$arr_data);
	
		$rows=$this->obj->DB_select_all("company_cert","`uid`='".$this->uid."' group by type order by id desc");
		foreach($rows as $v){
			$row[$v["type"]]=$v;
		}
		$msg=array();
		$isallow_addjob="1";
		if($this->config['lt_enforce_emailcert']=="1"){
			if($row['1']['status']!="1"){
				$isallow_addjob="0";
				$msg[]="邮箱认证";
			}
		}
		if($this->config['lt_enforce_mobilecert']=="1"){
			if($row['2']['status']!="1"){
				$isallow_addjob="0";
				$msg[]="手机认证";
			}
		}
		if($this->config['lt_enforce_licensecert']=="1"){
			if($row['4']['status']!="1"){
				$isallow_addjob="0";
				$msg[]="职业资格认证";
			}
		}
		if($isallow_addjob=="0"){
			$this->ACT_msg("index.php?c=binding","请先完成".implode("、",$msg)."！");
		}
		$info=$this->obj->DB_select_once("lt_info","`uid`='".$this->uid."'","`com_name`");
		if($info['com_name']==''){
			$this->ACT_msg("index.php?c=info","请先完善基本资料！");
		}
		
		if($_GET['part']){
			$row['constitute']=$row['social']=$row['live']=$row['years']=$row['language']=$hy=array();
			$this->yunset("row",$row);
			$this->yunset("hy",$hy);
			$this->yunset("part",$_GET['part']);
		}

		$static = $this->lt_satic();
 		if( $static['addltjobnum'] == 2){
			$this->ACT_msg("index.php?c=right","你的套餐已用完！",8);
		}else if($static['addltjobnum'] == 0){
			$this->ACT_msg("index.php?c=right","会员已到期！",8);
		}
		
	
		$row['edate']=strtotime("+1 month");
		$this->yunset("row",$row);
		$this->yunset("today",date("Y-m-d"));
		$this->user_shell();
		$this->yunset($this->MODEL('cache')->GetCache(array('city','lt','ltjob','lthy','hy')));
		
		$this->lietou_tpl('jobadd');
	}
	function edit_action(){
		include(CONFIG_PATH."db.data.php");		
		$this->yunset("arr_data",$arr_data);
		$row=$this->obj->DB_select_once("lt_job","`id`='".(int)$_GET['id']."' and `uid`='".$this->uid."'");
		if($row['constitute']!=""){
			$row['constitute']=@explode(",",$row['constitute']);
		}
		if($row['welfare']!=""){
			$row['welfare']=@explode(",",$row['welfare']);
		}
		if($row['language']!=""){
			$row['language']=@explode(",",$row['language']);
		}
		
		$this->yunset("row",$row);
		$this->yunset("today",date("Y-m-d"));
	
		$this->yunset("row",$row);
		$this->yunset("part",$_GET['part']);
		$this->user_shell();
		$this->yunset($this->MODEL('cache')->GetCache(array('city','lt','ltjob','lthy','hy')));
		$this->public_action();
		$this->lietou_tpl('jobadd');
	}
	function save_action(){
		if($_POST['submit']){
			$_POST=$this->post_trim($_POST);
			$id=(int)$_POST['id'];
			$info=$this->obj->DB_select_once("lt_statis","`uid`='".$this->uid."'","`integral`");
			$_POST['desc'] = str_replace("&amp;","&",$_POST['desc']);
			$data['com_name']=$_POST['com_name'];
			$data['real_name']=$_POST['real_name'];
			$data['pr']=$_POST['pr'];
			$data['hy']=$_POST['hyid'];
			$data['mun']=$_POST['mun'];
			$data['desc']=$_POST['desc'];
			$data['job_name']=$_POST['job_name'];
			$data['department']=$_POST['department'];
			$data['edate']=strtotime($_POST['edate']);
			$data['report']=$_POST['report'];
			$data['jobone']=$_POST['jobone'];
			$data['jobtwo']=$_POST['jobtwo'];
			$data['provinceid']=$_POST['provinceid'];
			$data['cityid']=$_POST['cityid'];
			$data['three_cityid']=$_POST['three_cityid'];
			$data['minsalary']=$_POST['minsalary'];
			$data['maxsalary']=$_POST['maxsalary'];
			$data['constitute']=@implode(',',$_POST['constitute']);
			$data['welfare']=@implode(',',$_POST['welfare']);
			
			$data['job_desc']=$_POST['job_desc'];
			$data['age']=$_POST['age'];
			$data['sex']=$_POST['sex'];
			
			$data['exp']=$_POST['exp'];
			
			$data['edu']=$_POST['edu'];
		
			$data['language']=@implode(',',$_POST['language']);
			$data['eligible']=$_POST['eligible'];
			
			$data['rebates']=$_POST['rebates'];
			$data['other']=$_POST['other'];
			$data['lastupdate']=time();
			$data['status']=$this->config['lt_job_status'];
			if($_POST['id']){
				$job=$this->obj->DB_select_once("lt_job","`id`='".$_POST['id']."' and `uid`='".$this->uid."'","`status`");
				if($job['status']>'0'){
					$this->get_com(2);
				} 
				$where['uid']=$this->uid;
				$where['id']=$_POST['id'];
				$id=$this->obj->update_once("lt_job",$data,$where);
				if($id){
					$this->obj->member_log("更新猎头职位",1,2);
					$this->ACT_layer_msg("更新职位成功！",9,"index.php?c=job&s=0");
				}else{
					$this->ACT_layer_msg("更新职位失败！",8,$_SERVER['HTTP_REFERER']);
				}
			}else{
				$data['uid']=$this->uid;
				$data['did']=$this->userdid;
				$this->get_com(1);
				$id=$this->obj->insert_into("lt_job",$data);
				if($id){
					$state_content = "新发布了猎头职位 <a href=\"".$this->config['sy_weburl']."/lietou/index.php?c=jobshow&id=".$id."\" target=\"_blank\">".$_POST['job_name']."</a>。";
					$state['uid']=$this->uid;
					$state['content']=$state_content;
					$state['ctime']=time();
					$state['type']=2;
					$this->obj->insert_into("friend_state",$state);
					$this->obj->member_log("发布猎头职位",1,1);
					$this->ACT_layer_msg("发布成功！",9,"index.php?c=job&s=0");
				}else{
					$this->ACT_layer_msg("发布失败！",8,$_SERVER['HTTP_REFERER']);
				}
			}
		}
	}
	function gotime_action(){

		if($_POST['gotimeid']){
			$_POST['day']=intval($_POST['day']);
			if($_POST['day']<1){
 				$this->ACT_layer_msg("请正确填写延期天数！",8);
			}else{
				$posttime=(int)$_POST['day']*86400;
				$gotimeid=@explode(",",$_POST['gotimeid']);
				$ids=pylode(",",$gotimeid);
				$where="`uid`='".$this->uid."' and `id` in (".$ids.")";
				$id=$this->obj->DB_update_all("lt_job","`edate`= `edate` + {$posttime},`status`='1'",$where);

				if($id){
					$this->obj->member_log("职位延期");//会员日志
					$this->ACT_layer_msg("延期成功！",9,$_SERVER['HTTP_REFERER']);
				}else{
					$this->ACT_layer_msg("延期失败！",8,$_SERVER['HTTP_REFERER']);
				}
			}
		}

	}
}
?>