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
class ltjob_controller extends common{
	function index_action(){
		$CacheM=$this->MODEL('cache');
		$CacheArr=$CacheM->GetCache(array('job','city','hy','com','lt','ltjob'));		
		$uptime=array(1=>'今天',3=>'最近3天',7=>'最近7天',30=>'最近一个月',90=>'最近三个月');
		$this->yunset("uptime",$uptime);
		$this->yunset($CacheArr);
		foreach($_GET as $k=>$v){
			if($k!=""){
				$searchurl[]=$k."=".$v;
			}
		}
		$this->seo("com_search");
		$searchurl=@implode("&",$searchurl);
		$this->yunset("topplaceholder","请输入职位关键字,如：会计...");
		$this->yunset("searchurl",$searchurl);
		$this->yunset('backurl',Url('wap'));
		$this->yuntpl(array('wap/ltjoblist'));
	}
	function ajax_url_action(){
		if($_POST){
			if($_POST['url']!=""){
				$urls=@explode("&",$_POST['url']);
				foreach($urls as $v){
					if($_POST['type']=="provinceid"||$_POST['type']=="cityid"||$_POST['type']=="three_cityid"){
						if(strpos($v,"provinceid")===false && strpos($v,"cityid")===false&& strpos($v,"three_cityid")===false){
							$gourl[]=$v;
						}
					}else{
						if(strpos($v,$_POST['type'])===false){
							$gourl[]=$v;
						}
					}
				}
				if($_POST['id']>0){
					$gourl=@implode("&",$gourl)."&".$_POST['type']."=".$_POST['id'];
				}else{
					$gourl=@implode("&",$gourl);
				}
			}else{
				$gourl=$_POST['type']."=".$_POST['id'];
			}
			echo "?".$gourl;die;
		}
	}
	
	function show_action(){
		$M=$this->MODEL('lietou');
        $UserInfoM=$this->MODEL('userinfo');
		$job=$this->job($M);
        $JobM=$this->MODEL('job');
		$M->AddLietoujobHits(intval($_GET['id']));
        $ComJobNum=$this->MODEL('job')->GetComjobNum(array('uid'=>$job['uid'],'`r_status`<>2','`status`<>1','state'=>1));
		$com_info=$UserInfoM->GetUserinfoOne(array('uid'=>$job['uid']),array('usertype'=>2));
		if($com_info['logo']==''){
		    $com_info['logo']=$this->config['sy_unit_icon'];
		}else{
		    $com_info['logo']=str_replace("./",$this->config['sy_weburl']."/",$com_info['logo']);
		}
		if($this->uid){ 
			$AskM=$this->MODEL('ask');
			$atn=$AskM->GetAtnOne(array('uid'=>$this->uid,'sc_uid'=>$job['uid']));
			$userjob=$JobM->GetUserJobNum(array("uid"=>$this->uid,"job_id"=>$job['id'],"type"=>'2'));
			$favjob=$JobM->GetFavJobOne(array("uid"=>$this->uid,"job_id"=>$job['id'],"type"=>'2'));
			$this->yunset("favjob",$favjob);
			$this->yunset("userjob",$userjob);
			$this->yunset('atn',$atn);
		}
		if($job['uid']==$this->uid){
			$this->yunset("myself",1);
		}
		
		$this->yunset(array('Info'=>$com_info,'user_job_num'=>$ComJobNum));
		$this->seo("job_show"); 
		$this->yunset("headertitle","猎头职位详情");
		$this->yuntpl(array('wap/ltjobshow'));
	}
	
	function recshow_action(){
		$M=$this->MODEL('lietou');
		$UserInfoM=$this->MODEL('userinfo');
		$AskM=$this->MODEL('ask');
		$JobM=$this->MODEL('job');
		$job=$this->job($M);
		$M->AddLietoujobHits(intval($_GET['id']));
		if($this->uid&&$this->usertype=='1'){
			$atn=$AskM->GetAtnOne(array('uid'=>$this->uid,'sc_uid'=>$job['uid']));
			$userjob=$JobM->GetUserJobNum(array("uid"=>$this->uid,"job_id"=>$job['id'],"type"=>'3'));
			$favjob=$JobM->GetFavJobOne(array("uid"=>$this->uid,"job_id"=>$job['id'],"type"=>'3'));
			$this->yunset("favjob",$favjob);
			$this->yunset("userjob",$userjob);
			$this->yunset('atn',$atn);
		}
		if($job['uid']==$this->uid){
			$this->yunset("myself",1);
		}
	    $Info=$UserInfoM->GetUserinfoOne(array('uid'=>$job['uid']),array('usertype'=>3));
	    include PLUS_PATH."/lt.cache.php";
	    $Info['title_info']=$ltclass_name[$Info['title']];
	    if($Info['photo']==''){$Info['photo']=$this->config['sy_lt_icon'];}
	    $Info['photo']=str_replace("./",$this->config['sy_weburl']."/",$Info['photo']);
	    $LietouJobNum=$M->GetLietoujobNum(array('uid'=>$job['uid'],"status"=>1,"`edate`>'".time()."' and `zp_status`='0' and `r_status`<>'2'"));
	    $this->yunset('Info',$Info);
	    $this->yunset('user_job_num',$LietouJobNum);
		$this->yunset('job',$job);
		$this->seo('job_show');
		$this->yunset("headertitle","悬赏职位详情");
		$this->yuntpl(array('wap/ltjobrecshow'));
	}
	
	function hunter_action(){
        $AskM=$this->MODEL('ask');
        $UserInfoM=$this->MODEL("userinfo");
		$UserInfoM->UpdateUserinfo(array('usertype'=>3,'values'=>array("`hits`=`hits`+1")),array("uid"=>(int)$_GET['uid']));
		$user=$UserInfoM->GetMemberOne(array('uid'=>(int)$_GET['uid']));
		if($user['uid']==''){
			
			$data['msg']='没有找到相关猎头！';
			$data['url']=$_SERVER['HTTP_REFERER'];
			$this->yunset("layer",$data);
		}
		$this->yunset("user",$user);
		if($this->usertype=="1"){
		    $atn=$AskM->GetAtnOne(array('uid'=>$this->uid,'sc_uid'=>(int)$_GET['uid']));
		    $this->yunset('atn',$atn);
		}
        $CacheM=$this->MODEL('cache');
        $CacheList=$CacheM->GetCache(array('city','ltjob','lthy','lt'));
        $city_name=$CacheList['city_name'];
        $ltjob_name=$CacheList['ltjob_name'];
        $lthy_name=$CacheList['lthy_name'];
        $ltclass_name=$CacheList['ltclass_name'];

		$info=$UserInfoM->GetUserinfoOne(array('uid'=>(int)$_GET['uid']),array('usertype'=>3));
		$info['exp_info'] = $ltclass_name[$info['exp']];
		if($info['hy']!=''){
			$hy=@explode(",",$info['hy']);
			foreach($hy as $v){
				$hylist[]=$lthy_name[$v];
			}
			$info['hy_info']=@implode(",",$hylist);
		}
		if($info['job']!=""){
			$job=@explode(",",$info['job']);
			foreach($job as $v){
				$joblist[]=$ltjob_name[$v];
			}
			$info['job_info']=@implode(",",$joblist);
		}
		if($info['client']!=""){
			$client=@explode(",",$info['client']);
			foreach($client as $v){
				$client_arr[]=$v;
			}
			$info['client']=@implode(",",$client_arr);
		}		
		if(!$info['photo_big'] || !file_exists(str_replace($this->config['sy_weburl'],APP_PATH,'.'.$info['photo_big']))){
		    $info['photo_big']=$this->config['sy_weburl']."/".$this->config['sy_lt_icon'];
		}else{
		    $info['photo_big']=str_replace("./",$this->config['sy_weburl']."/",$info['photo_big']);
		}
		$this->yunset("info",$info);
		$data['lt_name']=$info['realname'];
		$this->data=$data;
		$this->seo("headhunter");
		$this->yunset("headertitle","猎头介绍");
		$this->yuntpl(array('wap/lthunter'));
	}
	
	function recuser_action(){
		$M=$this->MODEL('lietou');
		$jobinfo=$M->GetLietoujobOne(array('id'=>(int)$_GET['id']));
		$CacheM=$this->MODEL('cache');
		$CacheList=$CacheM->GetCache(array('city','lt','user','hy','job'));
		$city_name=$CacheList['city_name'];
		$ltclass_name=$CacheList['ltclass_name'];
	
		$jobinfo['provinceid_info'] = $city_name[$jobinfo['provinceid']];
		$jobinfo['cityid_info'] = $city_name[$jobinfo['cityid']];
		$jobinfo['three_cityid_info'] = $city_name[$jobinfo['three_cityid']];
		$jobinfo['salary_info'] = $ltclass_name[$jobinfo['salary']];
		$jobinfo['edu_info'] = $ltclass_name[$jobinfo['edu']];
		$this->yunset("jobinfo",$jobinfo);
		$this->yunset($CacheList);
		$data['job_name']=$jobinfo['job_name'];
		$this->data=$data;
		$this->seo('rec_user');
		$this->yunset("headertitle","推荐人才");
		$this->yuntpl(array('wap/ltrecuser'));
	}
	function recusersave_action(){
		$M=$this->MODEL('lietou');
		

		if($_POST['name']==''){
			echo 3;die;
		}elseif($_POST['phone']==''){
			echo 4;die;
		}elseif($_POST['phone']&&!CheckMoblie($_POST['phone'])){
			echo 5;die;
		}elseif($_POST['content']==''){
			echo 6;die;
		}else{
			$data['uid']=$this->uid;
			$data['job_uid']=(int)$_POST['job_uid'];
			$data['job_id']=(int)$_POST['job_id'];
			$data['name']=$_POST['name'];
			$data['phone']=$_POST['phone'];
			$data['content']=$_POST['content'];
			$data['datetime']=time();
			$nid=$M->InsertRebates($data);
			if($nid){
				$_POST['rid']=$nid;
				$_POST['uname']=$_POST['name'];
				$_POST['telphone']=$_POST['phone'];
				$Resume=$this->MODEL("resume");
				$id=$Resume->TemporaryResume($_POST);
				$M->member_log("为悬赏职位推荐人才");
				echo 1;die;
			}else{
				echo 2;die;
			}
		}
	}
	function job($M){
		include(CONFIG_PATH."db.data.php");		
		$this->yunset("arr_data",$arr_data);
		if((int)$_GET['id']){
			session_start();
			$where['id']=(int)$_GET['id'];
			$job=$M->GetLietoujobOne($where);
			if ($_SESSION['auid']=="" && $job['uid']!=$this->uid){
			    if(!is_array($job)){
			        $data['msg']='没有找到相关职位！';
			        $data['url']=Url('wap',array('c'=>'ltjob'));
			        $this->yunset("layer",$data);
			    }elseif($job['r_status']=='2'){
			        $data['msg']='企业已被锁定！';
			        $data['url']=Url('wap',array('c'=>'ltjob'));
			        $this->yunset("layer",$data);
			    }elseif($job['zp_status']==1){
			        $data['msg']='职位已下架！';
			        $data['url']=Url('wap',array('c'=>'ltjob'));
			        $this->yunset("layer",$data);
			    }elseif($job['status']==0){
			        $data['msg']='职位还未审核，请耐心等待！';
			        $data['url']=Url('wap',array('c'=>'ltjob'));
			        $this->yunset("layer",$data);
			    }elseif($job['zp_status']==2){
			        $data['msg']='职位已过期！';
			        $data['url']=Url('wap',array('c'=>'ltjob'));
			        $this->yunset("layer",$data);
			    }elseif($job['zp_status']==3){
			        $data['msg']='职位审核未通过！';
			        $data['url']=Url('wap',array('c'=>'ltjob'));
			        $this->yunset("layer",$data);
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
			    $welfare=@explode(",",$job['welfare']);
			    foreach($welfare as $v){
			        $welfare_arr[]=$ltclass_name[$v];
			    }
			    $job['welfare']=@implode(",",$welfare_arr);
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
		}else{
		    $data['msg']='没有找到相关职位！';
		    $data['url']=Url('wap',array('c'=>'ltjob'));
		    $this->yunset("layer",$data);
		}
	}
	function favjob_action(){
		$M=$this->MODEL('lietou');
		$UserinfoM=$this->MODEL('userinfo');
		$ResumeM=$this->MODEL('resume');
		if($this->usertype!='1'){
			echo 3;die;
		}else if(!$this->uid || !$this->username){
			echo 0;die;
		}else{
			$lt_job=$M->GetLietoujobOne(array('id'=>(int)$_POST['id']),array('field'=>"`id`,`com_name`,`job_name`,`uid`,`usertype`"));
			$is_set=$ResumeM->GetFavjobOne(array('uid'=>$this->uid,'job_id'=>(int)$_POST['id'],'type'=>$lt_job['usertype']));
			if(is_array($is_set)){
				echo 1;die;
			}else{
				$value=array('job_id'=>(int)$_POST['id'],'job_name'=>$lt_job['job_name'],'com_id'=>$lt_job['uid'],'uid'=>$this->uid,'datetime'=>time());
				$value['com_name']=$lt_job['com_name'];
				$value['type']=$lt_job['usertype'];
				$JobM=$this->MODEL("job");
				$nid=$JobM->AddFavJob($value);
				if($nid){
					$UserinfoM->UpdateMemberstatis(array("`fav_jobnum`=`fav_jobnum`+1"),array('uid'=>$this->uid));
					$state_content = "我收藏了猎头职位 <a href=\"".Url("lietou",array('c'=>'jobcomshow','id'=>$lt_job['id']))."\" target=\"_bank\">".$lt_job['job_name']."</a>";
					$this->addstate($state_content,2);
					$this->obj->member_log("收藏了猎头职位:".$lt_job['job_name']);
					echo 2;die;
				}
			}
		}
	}
	function service_action(){
		$CacheM=$this->MODEL('cache');
        $CacheList=$CacheM->GetCache(array('city','ltjob','lthy'));
		$this->yunset($CacheList);
		foreach($_GET as $k=>$v){
			if($k!=""){
				$searchurl[]=$k."=".$v;
			}
		}
		$searchurl=@implode("&",$searchurl);
		$this->yunset("searchurl",$searchurl);
		$this->seo('ltservice');
		$this->yunset("topplaceholder","请输入关键字");
		$this->yuntpl(array('wap/ltservice'));
	}
	function share_action(){
		$this->get_moblie();
		$M=$this->MODEL('lietou');
        $UserInfoM=$this->MODEL('userinfo');
		$job=$this->job($M);
		$job['welfare'] = @explode(",",$job['welfare']);
		$job['language'] = @explode(",",$job['language']);
		if($job['usertype']=='3'){
			$info=$UserInfoM->GetUserinfoOne(array('uid'=>$job['uid']),array('usertype'=>3));
		}
		$com_info=$UserInfoM->GetUserinfoOne(array('uid'=>$job['uid']),array('usertype'=>2));
		if($com_info['logo']==''){$com_info['logo']=$this->config['sy_unit_icon'];}
		$com_info['logo']=str_replace("./",$this->config['sy_weburl']."/",$com_info['logo']);
		$this->yunset("info",$info);
		$this->yunset('job',$job);
		$this->yunset("headertitle",$job['job_name'].'-'.$job['com_name'].'-'.$this->config['sy_webname']);
		$this->yunset("job_style",$this->config['sy_weburl']."/app/template/wap/job");
		$this->yuntpl(array('wap/job/ltjob'));
	}
	
}
?>