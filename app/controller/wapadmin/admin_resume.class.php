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
class admin_resume_controller extends adminCommon{
	function index_action(){  
	    $where="`height_status`=0";
	    if(trim($_GET['keyword'])){
	        $where .= " and `name` like '%".trim($_GET['keyword'])."%' ";
	        $urlarr['keyword']=$_GET['keyword'];
	    }
		$where.=" order by `id` desc";

		$urlarr['c']=$_GET['c'];
		$urlarr['page']="{{page}}";
		$pageurl=Url($_GET['m'],$urlarr,'admin');
        $CacheM=$this->MODEL('cache');
		$rows=$this->get_page('resume_expect',$where,$pageurl,$this->config['sy_listnum']);
        $CacheList=$CacheM->GetCache(array('city','job','user'));
        $this->yunset($CacheList);
        extract($CacheList);
		if(is_array($rows)){
			foreach($rows as $k=>$v){			   
				$rows[$k]['cityid_n']=$city_name[$v['cityid']];
				$job_classid=@explode(",",$v['job_classid']);
				$job_class_name=array();
				if(is_array($job_classid)){
					$i=0;
					foreach($job_classid as $key=>$val){
						$jobname[$key]=$val;
						if($val!=""){
							$i=$i+1;
						}
						$job_class_name[]=$job_name[$val];
					}
					$rows[$k]['jobnum']=$i;
					$rows[$k]['job_post_n']=$job_name[$jobname[0]];
				}				
				$rows[$k]['job_class_name']=@implode('、',$job_class_name);
			}
		}

		$where=str_replace(array("(",")"),array("[","]"),$where);
		$this->yunset(array('where'=>$where,'get_type'=>$_GET,'rows'=>$rows));
		$this->yunset("headertitle","简历管理");
		$this->yunset('backurl','index.php?c=user');
		$this->yuntpl(array('wapadmin/admin_resume'));
	}
	function saveresume_action(){
		$arr=$this->MODEL('cache')->GetCache(array('user','city','job','hy'));
		$this->yunset($arr);
		if($_GET['e']){
			$eid=(int)$_GET['e'];
			$row=$this->obj->DB_select_once("resume_expect","id='".$eid."' AND `uid`='".$_GET['uid']."'"); 
			if(!is_array($row) || empty($row))
			{
				$this->layer_msg("无效的简历！",8);
			}
			$job_classid=@explode(",",$row['job_classid']);
			
			if(is_array($job_classid)){
				foreach($job_classid as $key){
					$job_classname[]=$arr['job_name'][$key];
				} 
				$this->yunset("job_classname",@implode(',',$job_classname));
			}
			$this->yunset("job_classid",$job_classid);
			$this->yunset("row",$row);
			
			$skill = $this->obj->DB_select_all("resume_skill","`eid`='".$eid."' AND `uid` = '".$_GET['uid']."'");
			$this->yunset("skill",$skill);
			
			$work = $this->obj->DB_select_all("resume_work","`eid`='".$eid."' AND `uid` = '".$_GET['uid']."'");
			$this->yunset("work",$work);
			
			$project = $this->obj->DB_select_all("resume_project","`eid`='".$eid."' AND `uid` = '".$_GET['uid']."'");
			$this->yunset("project",$project);
			
			$edu = $this->obj->DB_select_all("resume_edu","`eid`='".$eid."' AND `uid` = '".$_GET['uid']."'");
			$this->yunset("edu",$edu);
			
			$training = $this->obj->DB_select_all("resume_training","`eid`='".$eid."' AND `uid` = '".$_GET['uid']."'");
			$this->yunset("training",$training);
			
			$cert = $this->obj->DB_select_all("resume_cert","`eid`='".$eid."' AND `uid` = '".$_GET['uid']."'");
			$this->yunset("cert",$cert);
			
			$other = $this->obj->DB_select_all("resume_other","`eid`='".$eid."' AND `uid` = '".$_GET['uid']."'");
			$this->yunset("other",$other);
			
		}
		$resume=$this->obj->DB_select_once("resume","`uid`='".$_GET['uid']."'");
		$this->yunset("resume",$resume);
		$this->yunset("uid",$_GET['uid']);
		if($_GET['return_url']){
			$this->yunset("return_url",'myresume');
		}else{
			$this->yunset("return_url",'resume');
		}				
		$this->yunset("headertitle","简历管理");
		$this->yunset('backurl','index.php?c=admin_resume');
		$this->yuntpl(array('wapadmin/admin_saveresume'));
	}
	
	function saveexpect_action(){
		if($_POST['submit']){
			$eid=(int)$_POST['eid'];
			unset($_POST['submit']);
			unset($_POST['eid']);
			unset($_POST['pytoken']);
			unset($_POST['urlid']);
			
			
			$_POST['lastupdate']=time();
			$_POST['height_status']="0";
			$_POST['integrity']=55;
			if($eid==""){
				$_POST['receive_status']='1';
				$resume_num=$this->obj->DB_select_num("resume_expect","`uid`='".$_POST['uid']."'","id");
				if($resume_num>=$this->config['user_number']&&$this->config['user_number']!=''){echo 1;die;}
				$resume = $this->obj->DB_select_once("resume","`uid`='".$_POST['uid']."'");
				$expectDate = array(
				"lastupdate"	=>	time(),
				"height_status"	=>	0,
				"did"			=>	$resume['did'],
				"uid"			=>	$resume['uid'],
				"defaults"		=>	$resume_num<=0?1:0,
				"ctime"			=>	time(),
				"name"			=>	$_POST['name'],
				"hy"			=>	$_POST['hy'],
				"job_classid"	=>	$_POST['job_classid'],
				"minsalary"		=>	$_POST['minsalary'],
				"maxsalary"		=>	$_POST['maxsalary'],
				"provinceid"	=>	$_POST['provinceid'],
				"cityid"		=>	$_POST['cityid'],
				"three_cityid"	=>	$_POST['three_cityid'],
				"type"			=>	$_POST['type'],
				"report"		=>	$_POST['report'],
				"jobstatus"		=>	$_POST['jobstatus'],
				"integrity"		=>	55,
				"edu"=>$resume['edu'],
				"exp"=>$resume['exp'],
				"uname"=>$resume['name'],
				"sex"=>$resume['sex'],
				"birthday"=>$resume['birthday'],
				"idcard_status"=>$resume['idcard_status'],
				"status"=>$resume['status'],
				"r_status"=>$resume['r_status'],
				"photo"=>$resume['photo']);
				$nid=$this->obj->insert_into("resume_expect",$expectDate);

				if ($nid){
					if($resume_num==0){
						$this->obj->update_once('resume',array('def_job'=>$nid,'resumetime'=>time()),array('uid'=>$_POST['uid']));
					}
					$data['uid'] = $_POST['uid'];
					$data['eid'] = $nid;
					$this->obj->insert_into("user_resume",$data);
					$this->obj->DB_update_all('member_statis',"`resume_num`=`resume_num`+1","`uid`='".$_POST['uid']."'");
					$state_content = "发布了 <a href=\"".Url("resume",array("c"=>"show","id"=>$nid))."\" target=\"_blank\">新简历</a>。";
					$fdata['uid']	  = $_POST['uid'];
					$fdata['content'] = $state_content;
					$fdata['ctime']   = time();
					$this->obj->insert_into("friend_state",$fdata);
					$num=$this->obj->DB_select_num("company_pay","`com_id`='".$_POST['uid']."' AND `pay_remark`='发布简历'");
					if($num<1){
						$this->MODEL('integral')->get_integral_action($this->uid,"integral_add_resume","发布简历");
					}
					$this->MODEL('log')->admin_log("添加简历（ID:".$nid."）");
				}
				$eid=$nid;
			}else{
				$expectDate = array(
					"lastupdate"	=>	time(),   
					"name"			=>	$_POST['name'],
					"hy"			=>	$_POST['hy'],
					"job_classid"	=>	$_POST['job_classid'],
					"minsalary"		=>	$_POST['minsalary'],
					"maxsalary"		=>	$_POST['maxsalary'],
					"provinceid"	=>	$_POST['provinceid'],
					"cityid"		=>	$_POST['cityid'],
					"three_cityid"	=>	$_POST['three_cityid'],
					"type"			=>	$_POST['type'],
					"report"		=>	$_POST['report'],
					"jobstatus"		=>	$_POST['jobstatus']
				); 
				$where['id']=$eid;
				$where['uid']=(int)$_POST['uid'];
				$nid=$this->obj->update_once("resume_expect",$expectDate,$where);
				$this->obj->update_once("resume",array('lastupdate'=>time()),array('uid'=>(int)$_POST['uid']));
				$this->MODEL('log')->admin_log("修改简历（ID:".$eid."）");
			}
			$row=$this->obj->DB_select_once("user_resume","`expect`='1'","`eid`='$eid'");
			$this->obj->update_once('user_resume',array('expect'=>1),array('eid'=>$eid,'uid'=>$_POST['uid']));
			if($nid){
				$resume_row=$this->obj->DB_select_once("user_resume","`eid`='".$eid."'");
				$resume=$this->obj->DB_select_once("resume_expect","`id`='".$eid."'");
 				include PLUS_PATH."/user.cache.php";
				include PLUS_PATH."/job.cache.php";
				include PLUS_PATH."/city.cache.php";
				include PLUS_PATH."/industry.cache.php";
				$resume['report']=$userclass_name[$resume['report']];
				$resume['hy']=$industry_name[$resume['hy']];
				$resume['city']=$city_name[$resume['provinceid']]." ".$city_name[$resume['cityid']]." ".$city_name[$resume['three_cityid']];
				$resume['minsalary']=$resume['minsalary'];
				$resume['maxsalary']=$resume['maxsalary'];
				$resume['type']=$userclass_name[$resume['type']];
				$resume['jobstatus']=$userclass_name[$resume['jobstatus']];
				if($resume['job_classid']!=""){
					$job_classid=@explode(",",$resume['job_classid']);
					foreach($job_classid as $v){
						$job_classname[]=$job_name[$v];
					}
					$resume['job_classname']=@implode(",",$job_classname);
				}
				$resume['three_cityid']=$city_name[$resume['three_cityid']];

				if(is_array($resume)){
					foreach($resume as $k=>$v)
					{
						$arr[$k]=$v;
					}
				}
				echo json_encode($arr);die;
			}else{
				echo 0;die;
			}
		}
	}
	function skill_action(){
		$this->resume("resume_skill","skill","expect","填写项目经验");
		$this->yuntpl(array('wapadmin/admin_saveresume'));
	}
	function work_action()
	{
		$this->resume("resume_work","work","expect","填写项目经验");
	}
	function project_action()
	{
		$this->resume("resume_project","project","edu","填写教育经历");
	}
	function edu_action()
	{
		$this->resume("resume_edu","edu","training","填写培训经历");
	}
	function training_action()
	{
		$this->resume("resume_training","training","cert","填写证书");
	}
	function cert_action()
	{
		$this->resume("resume_cert","cert","other","填写其它");
	}
	function other_action()
	{
		$this->resume("resume_other","other","resume","返回简历管理");
	}
	
	function evalute_action()
	{	
		if($_POST["submit"]){	
			$eid=(int)$_POST['eid'];
			$id=(int)$_POST['id'];
			$uid=$_POST['uid'];
			unset($_POST['submit']);
			unset($_POST['id']);
			unset($_POST['table']);					
			if(!$id){					   		
				 $nid=$this->obj->update_once('resume',array('description'=>$_POST['evalute_content']),array('uid'=>$uid));	
				 if($nid){		   				   
				   $data['msg']="自我评价添加成功！";
				   $data['url']='index.php?c=admin_resume&a=saveresume&uid='.$uid.'&e='.$eid.'';
				   $this->yunset("layer",$data);
					}else{
					$data['msg']="自我评价添加失败！";
				   $data['url']='index.php?c=admin_resume&a=saveresume&uid='.$uid.'&e='.$eid.'';
				   $this->yunset("layer",$data);				       
				 }		
			}   								
		}
		$this->yuntpl(array('wapadmin/admin_saveresume'));
	}
	function resume($table,$url,$nexturl,$name=""){
       if($_POST["submit"]){
			$eid=(int)$_POST['eid'];
			$id=(int)$_POST['id'];
			$uid=$_POST['uid'];
			unset($_POST['submit']);
			unset($_POST['id']);
			unset($_POST['table']);
			if($_POST['name']){
			    if($table!='resume_skill'){
			        $_POST['name'] = $this->stringfilter($_POST['name']);
			    }
			}
			if($_POST['content'])$_POST['content'] = $this->stringfilter($_POST['content']);
			if($_POST['title'])$_POST['title'] = $this->stringfilter($_POST['title']);
			if($_POST['department'])$_POST['department'] = $this->stringfilter($_POST['department']);
			if($_POST['sys'])$_POST['sys'] = $this->stringfilter($_POST['sys']);
			if($_POST['specialty'])$_POST['specialty'] = $this->stringfilter($_POST['specialty']);
			if($_POST['sdate']){
				$_POST['sdate']=strtotime($_POST['sdate']);
			}
			if($_POST['edate']){
				$_POST['edate']=strtotime($_POST['edate']);
			}
			if($_FILES['pic']['tmp_name']!=''){
				$UploadM=$this->MODEL('upload');
				$upload=$UploadM->Upload_pic("../data/upload/user/",false);
				$pictures=$upload->picture($_FILES['pic']);
				$picmsg = $UploadM->picmsg($pictures,$_SERVER['HTTP_REFERER']);
				if($picmsg['status']==$pictures){
					$data['msg']=$picmsg['msg'];
				}else{
					$pictures = str_replace("../data/upload/user","./data/upload/user",$pictures);
					$_POST['pic']=$pictures;
				}
			}
			if($data['msg']==""){
 				if(!$id){
					if($table=='resume_skill'){
						$nid=$this->obj->DB_insert_once($table, "`uid`='".$uid."',`eid`='".$eid."',`name`='".$_POST['name']."',`longtime`='".$_POST['longtime']."',`pic`='".$_POST['pic']."'");
					}else{
						$nid=$this->obj->insert_into($table,$_POST);
					}
					$this->obj->DB_update_all("user_resume","`$url`=`$url`+1","`eid`='$eid' and `uid`='".$uid."'");
					if($table=='resume_skill'){
						if($nid){
						   
						   $data['msg']="职业技能添加成功！";
						   $data['url']='index.php?c=admin_resume&a=saveresume&uid='.$uid.'&e='.$eid.'';
						   $this->yunset("layer",$data);
						}else{
							$data['msg']="职业技能添加失败！";
						   $data['url']='index.php?c=admin_resume&a=saveresume&uid='.$uid.'&e='.$eid.'';
						   $this->yunset("layer",$data);
						   
						}
					}else{
						if($nid){
							$resume_row=$this->obj->DB_select_once("user_resume","`eid`='".$eid."'");
							$numresume=$this->MODEL('resume')->complete($resume_row);
							$this->select_resume($table,$nid,$numresume);
						}else{
							echo 0;die;
						}
					}
					
				}else{
					unset($_POST['uid']);
					$where['id']=$id;
					if($table=='resume_skill'){
						if($_POST['pic']==''){
							$nid=$this->obj->DB_update_all($table, "`uid`='".$uid."',`eid`='".$eid."',`name`='".$_POST['name']."',`longtime`='".$_POST['longtime']."'","`id`='".$id."'");
						}else{
							$nid=$this->obj->DB_update_all($table, "`uid`='".$uid."',`eid`='".$eid."',`name`='".$_POST['name']."',`longtime`='".$_POST['longtime']."',`pic`='".$_POST['pic']."'","`id`='".$id."'");
						}
						if($nid){
						   
						   $data['msg']="职业技能修改成功！";
						   $data['url']='index.php?c=admin_resume&a=saveresume&uid='.$uid.'&e='.$eid.'';
						   $this->yunset("layer",$data);
						}else{
							
						   $data['msg']="职业技能修改失败！";
						   $data['url']='index.php?c=admin_resume&a=saveresume&uid='.$uid.'&e='.$eid.'';
						   $this->yunset("layer",$data);
						}
					}else{
						$nid=$this->obj->update_once($table,$_POST,$where);
						if($nid){
							$this->select_resume($table,$id);
						}else{
							echo 0;die;
						}
					}				
				}
			}else{
				$data['msg']=$data['msg'];
				$data['url']='index.php?c=admin_resume&a=saveresume&uid='.$uid.'&e='.$eid.'';
				$this->yunset("layer",$data);
			}
		}
		$rows=$this->obj->DB_select_all($table,"`eid`='".$_GET['e']."'");
		$this->yunset("rows",$rows);
	}
	function select_resume($table,$id,$numresume=""){
		include PLUS_PATH."/user.cache.php";
		$info=$this->obj->DB_select_once($table,"`id`='".$id."'");
		$info['skillval']=$userclass_name[$info['skill']];
		$info['educationval']=$userclass_name[$info['education']];
		$info['ingval']=$userclass_name[$info['ing']];
		$info['sdate']=date("Y-m",$info['sdate']);
		$info['edate']=date("Y-m",$info['edate']);
		$info['numresume']=$numresume;
		if(is_array($info)){
			foreach($info as $k=>$v){
				$arr[$k]=$v;
			}
		}
		echo json_encode($arr);die;
	}
	function resume_ajax_action(){
		include PLUS_PATH."/user.cache.php";
		$table="resume_".$_POST['type'];
		$id=(int)$_POST['id'];
		$info=$this->obj->DB_select_once($table,"`id`='".$id."'");
		$info['skillval']=$userclass_name[$info['skill']];
		$info['educationval']=$userclass_name[$info['education']];
		$info['ingval']=$userclass_name[$info['ing']];
		$info['sdate']=date("Y-m",$info['sdate']);
		$info['edate']=date("Y-m",$info['edate']);
		if(is_array($info)){
			foreach($info as $k=>$v){
				$arr[$k]=$v;
			}
		}
		echo json_encode($arr);die;
	}
	function resume_del_action(){
		
		$_GET['id']=intval($_GET['id']);
		if(!in_array($_GET['type'],array('expect','cert','doc','edu','other','project','show','skill','tiny','training','work'))){
			unset($_GET['type']);
		}

		if($_GET['type']&&intval($_GET['id'])){
			$nid=$this->obj->DB_delete_all("resume_".$_GET['type'],"`eid`='".(int)$_GET['e']."' and `id`='".(int)$_GET['id']."'");
			if($nid){
				$url=$_GET['type'];
				$this->obj->DB_update_all("user_resume","`$url`=`$url`-1","`eid`='".(int)$_GET['e']."'");
				$resume_row=$this->obj->DB_select_once("user_resume","`eid`='".(int)$_GET['e']."'");
				$this->MODEL('resume')->complete($resume_row);
				$this->layer_msg('删除成功！',9);
			}else{
				$this->layer_msg('删除失败！',8);
			}
		}		
	}
	function check_username_action(){
		$username=$_POST['username'];
		$member=$this->obj->DB_select_once("member","`username`='".$username."'","`uid`");
		echo $member['uid'];die;
	}
	
	function del_action(){	
	    if($_GET['del']){
	    	$del=$_GET['del'];
	    	if($del){
	    		if(is_array($del)){
			    	foreach($del as $v){
			    	   $this->del_member($v);
			    	}
					$layer_type='1';
					$del=implode(",",$del);
		    	}else{
		    		 $this->del_member($del);
					 $layer_type='0';
		    	}
				$this->layer_msg("简历(ID:".$del.")删除成功！",9,0,'index.php?c=admin_resume');
	    	}else{
				$this->layer_msg("请选择您要删除的信息！",8,1,$_SERVER['HTTP_REFERER']);
	    	}
	    }
	}
	function del_member($id){
		$id_arr = @explode("-",$id);

		if($id_arr[0]){
			$result=$this->obj->DB_delete_all("resume_expect","`id`='".$id_arr[0]."'" );
			$defid=$this->obj->DB_select_once("resume","`uid`='".$id_arr[1]."' and `def_job`='".$id_arr[0]."'");
			if(is_array($defid)){
			    $row=$this->obj->DB_select_once("resume_expect","`uid`='".$id_arr[1]."'","`id`");
			    if($row['id']!=''){
			        $this->obj->update_once('resume_expect',array('defaults'=>1),array('id'=>$row['id']));
			        $this->obj->update_once('resume',array('def_job'=>$row['id']),array('uid'=>$id_arr[1]));
			    }
			}
			$del_array=array("resume_cert","resume_edu","resume_other","resume_project","resume_skill","resume_training","resume_work","resume_doc","user_resume","resume_show","down_resume","userid_job","user_entrust_record","talent_pool","user_entrust");
			$show=$this->obj->DB_select_all("resume_show","`eid`='".$id_arr[0]."' and `picurl`<>''","`picurl`");
			if(is_array($show)){
				foreach($show as $v){
					unlink_pic(".".$show['picurl']);
				}
			}
			foreach($del_array as $v){
				$this->obj->DB_delete_all($v,"`eid`='".$id_arr[0]."'","");
			}
			$this->obj->DB_delete_all("look_job","`uid`='".$this->uid."'","");
			$this->obj->DB_delete_all("atn","`uid`='".$this->uid."'","");
			$this->obj->DB_delete_all("userid_msg","`uid`='".$this->uid."'","");
			$this->obj->DB_delete_all("look_resume","`resume_id`='".$id_arr[0]."'","");
			$this->obj->DB_update_all("member_statis","`resume_num`=`resume_num`-1","`uid`='".$id_arr[1]."'");
		}
		return $result;
	}
	
	
	function ajax_action(){
		include(PLUS_PATH."city.cache.php");
		if(is_array($_POST['str'])){
			$cityid=$_POST['str'][0];
		}else{
			$cityid=$_POST['str'];
		}
		$data="<option value=''>--请选择--</option>";
		if(is_array($city_type[$cityid])){
			foreach($city_type[$cityid] as $v){
				$data.="<option value='$v'>".$city_name[$v]."</option>";
			}
		}
		echo $data;
	}
	
	
	function logout_action(){
		$this->adminlogout();
		$this->layer_msg("您已成功退出！",9,0,"index.php");
	}
}
?>