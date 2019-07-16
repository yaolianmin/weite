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
class lietou_controller extends common{
	function public_action(){
		$this->yunset('lietoustyle',TPL_PATH.'lietou');
		$this->yunset('lietou_style',$this->config['sy_weburl'].'/app/template/lietou');
		$this->yunset('uid',$this->uid);
		$this->yunset('username',$this->username);
	}
	function lietou_tpl($tpl){
		$this->yuntpl(array('lietou/'.$tpl));
	}
	function job($M){
		
		if($_POST['submit']){
			if(!$this->uid){
				$this->ACT_layer_msg("请先登录！",8,$_SERVER['HTTP_REFERER']);
			}
			if($this->usertype=="4"){
				$this->ACT_layer_msg("只有个人用户和hr可以留言！",8,$_SERVER['HTTP_REFERER']);
			}
			if(trim($_POST['content'])==""){
				$this->ACT_layer_msg( "留言内容不能为空！",8,$_SERVER['HTTP_REFERER']);
			}
			if(trim($_POST['authcode'])==""){
				$this->ACT_layer_msg( "验证码不能为空！",8,$_SERVER['HTTP_REFERER']);
			}
			session_start();
			if(md5(strtolower($_POST['authcode']))!=$_SESSION['authcode'] || empty($_SESSION['authcode'])){
				$this->ACT_layer_msg("验证码错误！", 8,$_SERVER['HTTP_REFERER']);
			}
			$_POST['uid']=$this->uid;
			$_POST['username']=$this->username;
			$_POST['datetime']=time();
			unset($_POST['submit']);
			$CompanyM=$this->MODEL('company');
			$CompanyM->AddMsg($_POST);
			$this->ACT_layer_msg("留言成功！",9,$_SERVER['HTTP_REFERER']);
		}else if((int)$_GET['id']){
			session_start();
			if($_SESSION['auid']!=""){
				$where['id']=(int)$_GET['id'];
			}else{
				$where['id']=(int)$_GET['id'];
				$where['status']=1;
				$where[]='zp_status<>\'1\'';
			}
			$job=$M->GetLietoujobOne($where);
		}
		if($_SESSION['auid']!=""){
			if(!is_array($job)){
				$this->ACT_msg(url("lietou",array("c"=>"post")),"没有该职位！");
			}
		}else{
			if(!is_array($job)){
				$this->ACT_msg(url("lietou",array("c"=>"post")),"没有该职位！");
			}elseif($job['r_status']=='2'){
				$this->ACT_msg(url("lietou",array("c"=>"post")),'企业已被锁定！');
			}elseif($job['zp_status']==1){
				$this->ACT_msg(url("lietou",array("c"=>"post")),'职位已下架！');
			}elseif($job['status']==0){
				$this->ACT_msg(url("lietou",array("c"=>"post")),'职位还未审核，请耐心等待！');
			}elseif($job['zp_status']==2){
				$this->ACT_msg(url("lietou",array("c"=>"post")),'职位已过期！');
			}elseif($job['zp_status']==3){
				$this->ACT_msg(url("lietou",array("c"=>"post")),'职位审核未通过！');
			}
		}
		
        $CacheM=$this->MODEL('cache');
        $CacheList=$CacheM->GetCache(array('city','com','hy','lt','ltjob','lthy'));
        $this->yunset($CacheList);
        $comclass_name=$CacheList['comclass_name'];
        $industry_name=$CacheList['industry_name'];
        $city_name=$CacheList['city_name'];
        $ltclass_name=$CacheList['ltclass_name'];
        $ltjob_name=$CacheList['ltjob_name'];  
		include(CONFIG_PATH."db.data.php");		
		$this->yunset("arr_data",$arr_data);
        $job=array_merge($job,array('mun_info'=>$ltclass_name[$job['mun']],'pr_info'=>$ltclass_name[$job['pr']],'hy_info'=>$industry_name[$job['hy']],'provinceid_info'=>$city_name[$job['provinceid']],'cityid_info'=>$city_name[$job['cityid']],'three_cityid_info'=>$city_name[$job['three_cityid']],'salary_info'=>$ltclass_name[$job['salary']],'jobone_info'=>$ltjob_name[$job['jobone']],'jobtwo_info'=>$ltjob_name[$job['jobtwo']],'age_info'=>$ltclass_name[$job['age']],'edu_info'=>$ltclass_name[$job['edu']],'sex'=>$arr_data['sex'][$job['sex']],'exp_info'=>$ltclass_name[$job['exp']],'full_info'=>$ltclass_name[$job['full']],'com_mun'=>$comclass_name[$job['mun']],'com_pr'=>$comclass_name[$job['pr']]));
        $UserInfoM=$this->MODEL('userinfo');
        $com=$UserInfoM->GetUserinfoOne(array('uid'=>$job['uid']),array('usertype'=>2));
        if($com['shortname']){
        	$job['com_name']=$com['shortname'];
        }
		if($job['edate']<time()&&$job['status']<>'2'){
			$job['status']=2; 
			$M->UpdateLietoujob(array("status"=>2),array("id"=>$job['id']));
		}
		if($job['constitute']!=""){ 
			$constitute=@explode(",",$job['constitute']);
			foreach($constitute as $v){
				 $const[]=$ltclass_name[$v];
			}
			$job['constitute']=@implode(",",$const);
		}
		if($job['welfare']!=""){					
			 $welfare = @explode(",",$job['welfare']);
		     foreach($welfare as $k=>$v){
			    if(!$v){
			    unset($welfare[$k]);
			    }
		     }
		     $job['welfare']=$welfare;			
		}
		if($job['language']!=""){
			$language=@explode(",",$job['language']);
			foreach($language as $v){
				$language_arr[]=$ltclass_name[$v];
			}
			$job['language']=@implode(",",$language_arr);
		}
		if($job['status']=='2'||$job['zp_status']=='1'){
			$job['notuserjob']=1;
		} 		
		$this->yunset('job',$job);
		$data['job_name']=$job['job_name'];
		$this->data=$data;
		return $job;
	}
}
?>