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
class index_controller extends lietou_controller{
	function index_action(){
		$M=$this->MODEL('lietou');
		$ResumeM=$this->MODEL('resume');
        $UserInfoM=$this->MODEL('userinfo');

        if(is_numeric($this->uid)&&(intval($this->uid)>0)){
            $UserJobNumAll=$M->GetLietoujobNum(array('uid'=>$this->uid,"status"=>1,"`edate`>'".time()."' and `zp_status`='0' and `r_status`<>'2'"));
            $UserJobNumOverdue=$M->GetLietoujobNum(array('uid'=>$this->uid,'edate<'.time()));
            $UserReceiveResumeNum=$M->GetReceiveResumeNum(array('com_id'=>$this->uid));
            $down_num=$ResumeM->SelectDownResumeNum(array('comid'=>$this->uid));
			$Info=$UserInfoM->GetUserinfoOne(array('uid'=>$this->uid),array('usertype'=>3),array('field'=>'`photo`'));
			$this->yunset("Info",$Info);
            $this->yunset("down_num",$down_num);
            $this->yunset(array('UserJobNumAll'=>$UserJobNumAll,'UserJobNumOverdue'=>$UserJobNumOverdue,'UserReceiveResumeNum'=>$UserReceiveResumeNum));
        }
         
        include PLUS_PATH."keyword.cache.php";
        if(is_array($keyword)){
          foreach($keyword as $k=>$v){
            if($v['type']=='7'&&$v['tuijian']=='1'){
              $postkeyword[]=$v;
            }
          }
        }
        $this->yunset("postkeyword",$postkeyword);
    
		$this->public_action();
		$this->seo('ltindex');
		$this->lietou_tpl('index');
	}
	
	function post_action(){
		$this->public_action();
        $CacheM=$this->MODEL('cache');
        $CacheList=$CacheM->GetCache(array('city','lt','ltjob','hy'));
        $this->yunset($CacheList);
		$uptime=array("1"=>"1个月内","3"=>"3个月内","6"=>"6个月内","12"=>"12个月内","-1"=>"12个月以上",);
		$this->yunset("uptime",$uptime);

		if($_GET['jobid']||$_GET['cityid']||$_GET['minsalary']||$_GET['maxsalary']||$_GET['uptime']){
			$this->yunset("searchtype","1");
			
				
				
				
				
				
			
			if($_GET['jobid']){
				$jobid=@explode(",",$_GET['jobid']);
				foreach($jobid as $v){
					$jobname[]=$CacheList[ltjob_name][$v];
				}
				$this->yunset("jobname",@implode(",",$jobname));
			}
			if($_GET['cityid']){
				$cityid=@explode(",",$_GET['cityid']);
				foreach($cityid as $v){
					$cityname[]=$CacheList[city_name][$v];
				}
				$this->yunset("cityname",@implode(",",$cityname));
			}
		}
		$_GET['keyword']=trim($_GET['keyword']);
		
		$ypjob=$this->obj->DB_select_all("userid_job","`uid`=".$this->uid,'job_id');
		if(is_array($ypjob)){
			foreach($ypjob as $k=>$v){
				$ypjobarr[]=$v['job_id'];
			}
		}
		$this->yunset("ypjob",$ypjobarr);

		$favjob=$this->obj->DB_select_all('fav_job',"`uid`=".$this->uid,'job_id');
		if(is_array($favjob)){
			foreach($favjob as $k=>$v){
				$favjobarr[]=$v['job_id'];
			}
		}
		$this->yunset("favjob",$favjobarr);

		$this->yunset('lietou_member_style',TPL_PATH.'member/lietou');
		$this->seo("ltpost");
		$this->lietou_tpl('post');
	}
    
	function jobshow_action(){
		$this->public_action();		
		$M=$this->MODEL('lietou');
        $UserInfoM=$this->MODEL('userinfo');
        $AskM=$this->MODEL('ask');
        $JobM=$this->MODEL('job');
		$job=$this->job($M);		
        $LietouJobNum=$M->GetLietoujobNum(array('uid'=>$job['uid'],"status"=>1,"`edate`>'".time()."' and `zp_status`='0' and `r_status`<>'2'"));
        $M->AddLietoujobHits(intval($_GET['id']));
		$Info=$UserInfoM->GetUserinfoOne(array('uid'=>$job['uid']),array('usertype'=>3));	
		include PLUS_PATH."/lt.cache.php";
		$Info['title_info']=$ltclass_name[$Info['title']];
		if(!$Info['photo'] || !file_exists(str_replace($this->config['sy_weburl'],APP_PATH,'.'.$Info['photo']))){
		    $Info['photo']=$this->config['sy_weburl']."/".$this->config['sy_lt_icon'];
		}else{
		    $Info['photo']=str_replace("./",$this->config['sy_weburl']."/",$Info['photo']);
		}
		if($this->uid&&$this->usertype=='1'){
			$atn=$AskM->GetAtnOne(array('uid'=>$this->uid,'sc_uid'=>$job['uid']));
			$userjob=$JobM->GetUserJobNum(array("uid"=>$this->uid,"job_id"=>$job['id'],"type"=>'3'));
			$favjob=$JobM->GetFavJobOne(array("uid"=>$this->uid,"job_id"=>$job['id'],"type"=>'3'));
			$this->yunset("favjob",$favjob);
			$this->yunset("userjob",$userjob);
		}
		
		if(empty($atn)){
			$Info['atn']="+关注";
		}else{
			$Info['atn']="取消关注";
		}
		if($job['uid']==$this->uid){
			$this->yunset("myself",1);
		}
		
		$data['job_name']=$job['job_name'];
		$data['job_desc']=strip_tags($job['desc']);
		$this->data=$data;
		$this->yunset(array('job'=>$job,'user_job_num'=>$LietouJobNum));
		$this->yunset('Info',$Info);
		$this->seo('job_show');
		$this->lietou_tpl('job_lt_show');
	}
    
	function jobcomshow_action(){
		
		$M=$this->MODEL('lietou');
        $UserInfoM=$this->MODEL('userinfo');
		$job=$this->job($M);
        $JobM=$this->MODEL('job');
		$M->AddLietoujobHits(intval($_GET['id']));
        $ComJobNum=$M->GetLietoujobNum(array('uid'=>$job['uid']));
		$com_info=$UserInfoM->GetUserinfoOne(array('uid'=>$job['uid']),array('usertype'=>2));
		if(!$com_info['logo'] || !file_exists(str_replace($this->config['sy_weburl'],APP_PATH,'.'.$com_info['logo']))){
		    $com_info['logo']=$this->config['sy_weburl']."/".$this->config['sy_unit_icon'];
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
		}
		if(empty($atn)){
			$com_info['atn']="+关注";
		}else{
			$com_info['atn']="取消关注";
		}
		if($job['uid']==$this->uid){
			$this->yunset("myself",1);
		}
		$data['job_name']=$job['job_name'];
		$data['job_desc']=strip_tags($job['desc']);
		$this->data=$data;
		$this->yunset(array('Info'=>$com_info,'user_job_num'=>$ComJobNum));
		$this->public_action();
		$this->seo("job_show"); 
		$this->lietou_tpl('job_com_show');
	}
	function recuser_action(){
		$M=$this->MODEL('lietou');
		if($_POST['submit']){
			$data="`uid`='".$this->uid."',";
			$data.="`job_uid`='".(int)$_POST['job_uid']."',";
			$data.="`job_id`='".(int)$_POST['job_id']."',";
			$data.="`name`='".$_POST['uname']."',";
			$data.="`phone`='".$_POST['telphone']."',";
			$data.="`content`='".strip_tags($_POST['content'])."',";
			$data.="`datetime`='".time()."'";
			$nid=$this->obj->DB_insert_once("rebates",$data);
			if($nid){
				$_POST['rid']=$nid;
				$_POST['name']=$_POST['uname'];
				$Resume=$this->MODEL("resume");
				$id=$Resume->TemporaryResume($_POST);
				$M->member_log("为悬赏职位推荐人才");
				$this->ACT_layer_msg("发布成功！",9,$_SERVER['HTTP_REFERER']);
			}else{
				$this->ACT_layer_msg("发布失败！",8);
			}
		}
		$jobinfo=$M->GetLietoujobOne(array('id'=>(int)$_GET['id']));
        $CacheM=$this->MODEL('cache');
        $CacheList=$CacheM->GetCache(array('city','lt','user','hy'));
        $city_name=$CacheList['city_name'];
        $ltclass_name=$CacheList['ltclass_name'];

		$jobinfo['provinceid_info'] = $city_name[$jobinfo['provinceid']];
		$jobinfo['cityid_info'] = $city_name[$jobinfo['cityid']];
		$jobinfo['three_cityid_info'] = $city_name[$jobinfo['three_cityid']];
		$jobinfo['edu_info'] = $ltclass_name[$jobinfo['edu']];
		$this->yunset("jobinfo",$jobinfo);
		$this->public_action();
		$this->yunset($CacheList);
		$data['job_name']=$jobinfo['job_name'];
		$this->data=$data;
		$this->seo('rec_user');
		$this->lietou_tpl('rec_user');
	}
	function headhunter_action(){
        $AskM=$this->MODEL('ask');
        $UserInfoM=$this->MODEL("userinfo");
        if($this->usertype=="1"){
			$atn=$AskM->GetAtnOne(array('uid'=>$this->uid,'sc_uid'=>(int)$_GET['uid']));
			$this->yunset('atn',$atn);
        }
		$UserInfoM->UpdateUserinfo(array('usertype'=>3,'values'=>array("`hits`=`hits`+1")),array("uid"=>(int)$_GET['uid']));
		$user=$UserInfoM->GetMemberOne(array('uid'=>(int)$_GET['uid']));
		if($user['uid']==''){
			$this->ACT_msg($this->config['sy_weburl'],"没有找到相关猎头！");
		}
		$this->yunset("user",$user);

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
			$info['client']=@explode(",",$info['client']);
		}
		if(!$info['photo_big'] || !file_exists(str_replace($this->config['sy_weburl'],APP_PATH,'.'.$info['photo_big']))){
		    $info['photo_big']=$this->config['sy_weburl']."/".$this->config['sy_lt_icon'];
		}else{
		    $info['photo_big']=str_replace("./",$this->config['sy_weburl']."/",$info['photo_big']);
		}
		$data['lt_name']=$info['realname'];
		$this->data=$data;

		$this->yunset("info",$info);
		$this->public_action();
		$this->seo("headhunter");
		$this->lietou_tpl('headhunter');
	}
	function service_action(){
		$this->public_action();
        $CacheM=$this->MODEL('cache');
        $CacheList=$CacheM->GetCache(array('ltjob','lthy'));
		$this->yunset($CacheList);
		if($_GET['hyid']||$_GET['jobid']){
			$this->yunset("searchtype","1");
			if($_GET['hyid']){
				$hyid=@explode(",",$_GET['hyid']);
				foreach($hyid as $v){
					$hyname[]=$CacheList[lthy_name][$v];
				}
				$this->yunset("hyname",@implode(",",$hyname));
			}
			if($_GET['jobid']){
				$jobid=@explode(",",$_GET['jobid']);
				foreach($jobid as $v){
					$jobname[]=$CacheList[ltjob_name][$v];
				}
				$this->yunset("jobname",@implode(",",$jobname));
			}
		}
		$this->yunset('lietou_member_style',TPL_PATH.'member/lietou');
		$this->seo('ltservice');
		$this->lietou_tpl('service');
	}
	function famous_action(){
		if($_GET['order']=="lastdate"){
			$_GET['order']=$_GET['lastupdate'];
		}
		$this->yunset('getinfo',$_GET);
        $CacheM=$this->MODEL('cache');
        $CacheList=$CacheM->GetCache(array('hy','com','city'));
		$this->yunset($CacheList);
        $UptimeNameList=array(1=>'今天',3=>'最近3天',7=>'最近7天',30=>'最近一个月',90=>'最近三个月');
        if($_GET['cityin']){
            $CityList=explode(',',$_GET['cityin']);
            foreach($CityList as $k=>$v){
                $CityNameList[]=$CacheList['city_name'][$v];
            }
            $CityName=implode('+',$CityNameList);
        }
		
        
        $FilterList=array('keyword'=>array('id'=>$_GET['keyword'],'value'=>$_GET['keyword'],'desc'=>'关键字','placeholder'=>'请输入你要查找的信息'),
            'pr'=>array('id'=>$_GET['pr'],'value'=>$CacheList['comclass_name'][$_GET['pr']],'desc'=>'企业性质','placeholder'=>'请选择企业性质'),
            'mun'=>array('id'=>$_GET['mun'],'value'=>$CacheList['comclass_name'][$_GET['mun']],'desc'=>'企业规模','placeholder'=>'请选择企业规模'),
            'uptime'=>array('id'=>$_GET['uptime'],'value'=>$UptimeNameList[$_GET['uptime']],'desc'=>'更新时间','placeholder'=>'请选择更新时间'),
            'cityin'=>array('id'=>$_GET['cityin'],'value'=>$CityName,'desc'=>'所在地区','placeholder'=>'请选择城市'),
            'hy'=>array('id'=>$_GET['hy'],'value'=>$CacheList['industry_name'][$_GET['hy']],'desc'=>'行业','placeholder'=>'请选择行业'));

		$SelectorList['count']=0;
        foreach($FilterList as $k=>$v){
            $SelectorList[$k]=$v;
            if(!trim($v['value'])){
                unset($FilterList[$k]);
            }else{
                $SelectorList['count']++;
                $SelectorList[$k][3]=$v[1];
            }
        }
        
        $this->yunset(array('FilterList'=>$FilterList,'SelectorList'=>$SelectorList,'UptimeNameList'=>$UptimeNameList));

		$this->public_action();
		$this->seo('ltfamous');
		$this->lietou_tpl('famous');
	}
	function register_action(){ 
		$this->public_action();
		$this->seo('register');
		if($this->config['reg_user_stop']!=1){
			$this->lietou_tpl('stopreg');
		}else{ 
			$LietouM=$this->MODEL('lietou');
			$UserinfoM=$this->MODEL('userinfo');
			$IntegralM=$this->MODEL('integral');
			if($this->uid!=""&&$this->username!=""&&$this->usertype=="3"){
				$this->ACT_msg("index.php","您已经登录了！");
			}else{
				$this->cookie->unset_cookie();
			}
			if($_POST){
				session_start();
				
				$_POST=$this->post_trim($_POST);
				 
				if($this->uid!=""&&$this->username!=""){
					$arr['status']=8;
					$arr['msg']='您已经登录了！';
				}elseif(trim($_POST['password'])&&trim($_POST['password'])!=trim($_POST['passconfirm'])){
					$arr['status']=8;
					$arr['msg']='两次输入密码不一致！';
				}elseif(CheckRegEmail($_POST['email'])==false &&  $arr['status']==''){
					$arr['status']=8;
					$arr['msg']='Email格式不规范！';
				}elseif(CheckRegUser($_POST['username'])==false && CheckRegEmail($_POST['email'])==false &&$arr['status']==''){
					$arr['status']=8;
					$arr['msg']='用户名包含特殊字符！';
				}elseif($_POST['moblie']==""){
					$arr['status']=8;
					$arr['msg']='手机号码不能为空';
				}elseif($_POST['username']!=""&&$arr['status']==''){
					$nid = $UserinfoM->GetMemberNum(array("`username`='".$_POST['username']."' or `email`='".$_POST['email']."'"),array('`uid`'));
					if($nid){
						$arr['status']=8;
						$arr['msg']='账户名或邮箱已存在！';
					}else{

						$msgChecked = false;
						if($this->config['reg_real_name_check']=="1"){
							if(!isset($_POST['realname']) || trim($_POST['realname']) == ''){
								$arr['status']=8;
								$arr['msg']='请输入真实姓名！';
								 
								echo json_encode($arr);die;
							}

							if($_POST['moblie_code']){
								$Member=$this->MODEL('userinfo');

								$regCertMobile = $Member->GetCompanyCert(array("type"=>'2',"check"=>$_POST['moblie']));

								if($regCertMobile['check2']!=$_POST['moblie_code'] || $regCertMobile['check2']==''){
									$arr['status']=8;
									$arr['msg']= '短信验证码错误！';
									 
									echo json_encode($arr);die;
								}
							}
							else{
								$arr['status']=8;
								$arr['msg']= '短信验证码错误！';
								 
								echo json_encode($arr);die;
							}

							$msgChecked = true;
						}

						
						if(!$msgChecked){
							if(strpos($this->config['code_web'],'注册会员')!==false){
								if($this->config['code_kind']==3){
									 
									if(!gtauthcode($this->config)){
										$arr['status']=8;
										$arr['msg']='请点击按钮进行验证！';
										 
										echo json_encode($arr);die;
									}
								}else{
									if(md5(strtolower($_POST['authcode']))!=$_SESSION['authcode'] || empty($_SESSION['authcode'])){
										$arr['status']=8;
										$arr['msg']='验证码错误！';
										unset($_SESSION['authcode']);
										 
										echo json_encode($arr);die;
									}
								}
							}
						}

						$salt = substr(uniqid(rand()), -6);
						$pass = md5(md5($_POST['password']).$salt);
						$ip = fun_ip_get();
			            
						$data=array('username'=>$_POST['username'],'email'=>$_POST['email'],'moblie'=>$_POST['moblie'],'password'=>$pass,'usertype'=>3,'status'=>$this->config['lt_status'],'salt'=>$salt,'reg_date'=>time(),'reg_ip'=>$ip,'regcode'=>(int)$_COOKIE['regcode'],'did'=>$this->config['did']);
						$userid=$UserinfoM->AddMember($data);
						if($userid){
							
							if($_COOKIE['regcode']!=""){
								
								if($this->config['integral_invite_reg_type']=="1"){
									$auto=true;
								}else{
									$auto=false;
								}
								

								$IntegralM->company_invtal((int)$_COOKIE['regcode'],$this->config['integral_invite_reg'],$auto,"邀请注册",true,2,'integral',23);
							}
							$ratingM = $this->MODEL('rating');
							$statisSql = "`uid`='".$userid."',";
							$statisSql .= $ratingM->ltrating_info();
							$this->obj->DB_insert_once("lt_statis",$statisSql);
							$value2=array('uid'=>$userid,'email'=>$_POST['email'],'moblie'=>$_POST['moblie'],'realname'=>$_POST['username'],'did'=>$this->config['did']);
							
							if(isset($_POST['realname']) && trim($_POST['realname']) != ''){
								$value2 ['realname'] = addslashes($_POST['realname']);
							}
							if($this->config['reg_real_name_check'] == 1){
								$value2['moblie_status'] = 1;
							}
							$LietouM->AddLtInfo($value2);
							if($this->config['integral_reg']!=""){
								$IntegralM->company_invtal($userid,$this->config['integral_reg'],true,"注册",true,2,'integral','26');
							}
							if($this->config['reg_coupon']){
								$coupon=$this->obj->DB_select_once("coupon","`id`='".$this->config['reg_coupon']."'");
								$cdata.="`uid`='".$userid."',";
								$cdata.="`number`='".time()."',";
								$cdata.="`ctime`='".time()."',";
								$cdata.="`coupon_id`='".$coupon['id']."',";
								$cdata.="`coupon_name`='".$coupon['name']."',";
								$validity=time()+$coupon['time']*86400;
								$cdata.="`validity`='".$validity."',";
								$cdata.="`coupon_amount`='".$coupon['amount']."',";
								$cdata.="`coupon_scope`='".$coupon['scope']."'";
								$this->obj->DB_insert_once("coupon_list",$cdata);
							}
              $notice = $this->MODEL('notice');
              $notice->sendEmailType(array("username"=>$_POST['username'],"password"=>$_POST['password'],"email"=>$_POST['email'],"moblie"=>$_POST['moblie'],"type"=>"reg"));
              $notice->sendSMSType(array("username"=>$_POST['username'],"password"=>$_POST['password'],"email"=>$_POST['email'],"moblie"=>$_POST['moblie'],"type"=>"reg"));
							
							$arr['status']=$this->config['lt_status'];
							if($this->config['lt_status']=="1"){
								$this->MODEL('integral')->get_integral_action($userid,"integral_login","会员登录");
								$UserinfoM->UpdateMember(array("login_date"=>time(),"login_ip"=>$ip),array("uid"=>$userid));
                $this->cookie->add_cookie($userid,$_POST['username'],$salt,$_POST['email'],$pass,3);
							}
						}else{
							$arr['status']=8;
							$arr['msg']='注册失败！';
						}
					}
				}
				 
				echo json_encode($arr);die;
			} 
			$this->lietou_tpl('register');
		}
	}
	function login_action(){
    $this->yunset("cookie", $_COOKIE['checkurl']);
		$Member=$this->MODEL('userinfo');
		if($this->uid!=""&&$this->username!=""&&$this->usertype=="3"){
			$this->ACT_msg("index.php","您已经登录了！");
		}else{
			$this->cookie->unset_cookie();
		}
		if($this->uid!=""&&$this->username!=""){
			echo "您已经登录了！";die;
		}
		$this->public_action();
		$this->seo("login");
		$this->lietou_tpl('login');
	}
	function loginsave_action(){
		$username=$_POST['username'];
		if($username && $_POST['password']){
			$Member=$this->MODEL('userinfo');
			if(trim($username)==''||trim($_POST['password'])==''){
			    echo "用户名密码均不能为空！";die();
			}
			if(strpos($this->config['code_web'],"前台登录")!==false){
			    session_start();
		        
				if ($this->config['code_kind']==3){
					 
					if(!gtauthcode($this->config)){
						echo json_encode(array('msg'=>'请点击按钮进行验证！'));die;
					}
		        }else{
		            if(md5(strtolower($_POST['authcode']))!=$_SESSION['authcode'] || empty($_SESSION['authcode'])){
		                unset($_SESSION['authcode']);
						echo json_encode(array('msg'=>'验证码错误'));die;
		            }
		        }
			}
			$user = $Member->GetMemberOne(array('username'=>$username,'usertype'=>3));
			if(is_array($user)){
				$pass = md5(md5($_POST['password']).$user['salt']);
				$userinfo = $Member->GetMemberOne(array('username'=>$username,'password'=>$pass));
				if(is_array($userinfo)){
					if($userinfo['status']=="2"){
					    echo json_encode(array('url'=>$this->config['sy_weburl'].'/index.php?m=register&c=ok&type=2'));die;
					}elseif($userinfo['status']=="3"){
					    echo json_encode(array('url'=>$this->config['sy_weburl'].'/index.php?m=register&c=ok&type=3'));die;
					}elseif($userinfo['status']!="1"){
					   echo json_encode(array('url'=>$this->config['sy_weburl'].'/index.php?m=register&c=ok&type=5'));die;
					}
					$ip=fun_ip_get();
					$Member->UpdateMember(array('login_ip'=>$ip,'login_date'=>time(),"`login_hits`=`login_hits`+1"),array('uid'=>$userinfo['uid']));
					
					
					$state_content = "登录成功";
					$this->obj->DB_insert_once("login_log","`uid`='".$userinfo['uid']."',`content`='".$state_content."',`ip`='".$ip."',`usertype`='".$userinfo['usertype']."',`ctime`='".time()."'");
					$this->cookie->add_cookie($userinfo['uid'],$userinfo['username'],$userinfo['salt'],$userinfo['email'],$userinfo['password'],3,$_POST['expire']);

					$logtime=date("Ymd",$user['login_date']);
					$nowtime=date("Ymd",time());
					if($logtime!=$nowtime){
						$this->MODEL('integral')->get_integral_action($userinfo['uid'],"integral_login","会员登录");
					}
					 echo json_encode(array('url'=>$this->config['sy_weburl'].'/member/'));die;
				}else{
					echo json_encode(array('msg'=>'密码不正确！'));die;
				}
			}else{
				echo json_encode(array('msg'=>'不存在该猎头用户！'));die;
			}
		}
	}
	function logout_action(){
		$this->logout();
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
}
?>