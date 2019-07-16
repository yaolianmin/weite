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
class ltresume_controller extends common{
	function index_action(){
		$this->rightinfo();
		$this->get_moblie();
		$CacheM=$this->MODEL('cache');
        $CacheArr=$CacheM->GetCache(array('user','job','city','hy'));
        $uptime=array(1=>'今天',3=>'最近3天',7=>'最近7天',30=>'最近一个月',90=>'最近三个月');
        $this->yunset("uptime",$uptime);
		if($_GET['jobin']){
			$job_classid=@explode(',',$_GET['jobin']);
			$jobname=$CacheArr['job_name'][$job_classid[0]];
			$this->yunset("jobname",mb_substr($jobname,0,6,'utf-8'));
		}
		foreach($_GET as $k=>$v){
			if($k!=""){
				$searchurl[]=$k."=".$v;
			}
		}
		$searchurl=@implode("&",$searchurl);
		$this->yunset("searchurl",$searchurl);
		$this->yunset("backurl",Url('wap',array(),'member'));
		$this->yunset($CacheArr);
		$this->yunset("topplaceholder","请输入简历关键字,如：服务员...");
		$this->seo("user_search");
		$this->yuntpl(array('wap/ltresume'));
	}
	function show_action(){
		include(CONFIG_PATH."db.data.php");
		unset($arr_data['sex'][3]);
		$this->yunset("arr_data",$arr_data);
		$this->rightinfo();
		$this->get_moblie();
		if($this->usertype!="3" && $_GET['look']==""&&$this->uid!=$resume_expect['uid']){
			
			$data['msg']='您不是猎头用户，不能查看高级人才！';
			$data['url']=$_SERVER['HTTP_REFERER'];
			$this->yunset("layer",$data);
		}
		$ResumeM=$this->MODEL('resume');
		if((int)$_GET['uid']){
			if((int)$_GET['type']=="2"){
				$user=$ResumeM->GetResumeExpectOne(array("uid"=>(int)$_GET['uid'],"height_status"=>'2'));
				$id=$user['id'];
			}else{
				$def_job=$ResumeM->SelectResumeOne(array("uid"=>(int)$_GET['uid'],"`r_status`<>'2'"));
				$id=$def_job['def_job'];
			}
		}else{
			$id=(int)$_GET['id'];
		}
		$user=$ResumeM->resume_select((int)$_GET['id']);
		$user['sex']=$arr_data['sex'][$user['sex']];
		$euid=$this->obj->DB_select_once("resume_expect","`id`='".(int)$_GET['id']."'","`uid`");
		$talent_pool=$this->obj->DB_select_num("talent_pool","`eid`='".(int)$_GET['id']."'and `cuid`='".$this->uid."'");
		$userid_msg=$this->obj->DB_select_num("userid_msg","`uid`='".$euid['uid']."'and `fid`='".$this->uid."'");
		$user['talent_pool']=$talent_pool;
		$user['userid_msg']=$userid_msg;
       
        if($this->usertype=="2" || $this->usertype=="3"){
			$this->yunset("uid",$this->uid);
			$ResumeM->RemindDeal("userid_job",array("is_browse"=>"2"),array("com_id"=>$this->uid,"eid"=>(int)$_GET['id'], "is_browse"=>"1"));
			if($this->usertype=="2"){
				
			}else{
				
			}
			
			$look_resume=$ResumeM->SelectLookResumeOne(array("com_id"=>$this->uid,"resume_id"=>$id));
			if(!empty($look_resume)){
				$ResumeM->SaveLookResume(array("datetime"=>time()),array("resume_id"=>$id,"com_id"=>$this->uid));
			}else{
				$ResumeM->AddExpectHits($id);
				$data['uid']=$user['uid'];
				$data['resume_id']=$id;
				$data['com_id']=$this->uid;
				$data['did']=$this->userdid;
				$data['datetime']=time();
				$ResumeM->SaveLookResume($data);
			}
        }
		$data['resume_username']=$user['username_n'];
		$data['resume_city']=$user['city_one'].",".$user['city_two'];
		$data['resume_job']=$user['hy'];
		$this->data=$data;
		$this->seo("resume");
		
		$this->yunset("Info",$user);
		$this->yunset("headertitle","高级人才");
		$this->yuntpl(array('wap/ltresumeshow'));
	}
}
?>