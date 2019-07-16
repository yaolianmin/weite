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
class pack_model extends model{

	
	function getShareJob(){
		
		$where = "`sharepack`='1' AND `state`=1 and `edate`>'".time()."' and `r_status`<>2 and `status`<>1 ";
		
		$jobList = $this->DB_select_all('company_job',$where,"`id`,`uid`,`name`,`com_name`,`lastupdate`,`description`,`cityid`,`minsalary`,`maxsalary`");

		
		if(is_array($jobList)){
			
			foreach($jobList as $key=>$value){
				
				$jobId[] = $value['id'];
				
			
			}
			$shareList = $this->DB_select_all('company_job_share',"`jobid` IN (".@implode(',',$jobId).")");
			
			if(is_array($shareList)){
				include PLUS_PATH."/city.cache.php";
				foreach($jobList as $key=>$value){
					$jobList[$key]['description'] = strip_tags($value['description']);
					$jobList[$key]['cityname'] = $city_name[$value['cityid']];

					if($value['maxsalary']>0){
				
						$jobList[$key]['salary'] = $value['minsalary'].'-'.$value['maxsalary'].'元/月';
					
					}elseif($value['minsalary']>0){
						
						$jobList[$key]['salary'] = $value['minsalary'].'以上/月';
					
					}

					foreach($shareList as $k=>$v){
						if($v['jobid'] == $value['id']){
							$jobList[$key]['packnum'] = $v['packnum'];
							$jobList[$key]['packmoney'] = $v['packmoney'];
							$jobList[$key]['packprice'] = $v['packprice'];
							$jobList[$key]['nowprice'] = sprintf("%.2f", $v['packnum']*$v['packmoney']);
						}
					}
					
				
				}
				
			}
			
		}
		return $jobList;
	}
	function delShareJob($uid,$jobid){
		
		$shareJob = $this->DB_select_once("company_job_share","`uid`='".(int)$uid."' AND `jobid`='".(int)$jobid."'");
		
		if($shareJob['packnum']>0){
			
			$price = $shareJob['packnum']*$shareJob['packmoney'];
			$this->DB_update_all("company_statis","`packpay`=`packpay`+".$price."","`uid`='".(int)$uid."'");
			$this->orderLog($price,(int)$uid,'取消职位赏金分享，退还剩余赏金！');
		}
		$this->DB_update_all("company_job","`sharepack`='0'","`id`='".(int)$jobid."'");
		$this->DB_delete_all("company_job_share","`jobid`='".(int)$jobid."'");
		
	}
	function delrewardJob($uid,$jobid){
		
		$reward = $this->DB_select_once("company_job_reward","`uid`='".(int)$uid."' AND `jobid`='".(int)$jobid."'");
		if($reward['id']){

			$rewardJobNum = $this->DB_select_num("company_job_rewardlist","`comid`='".(int)$uid."' AND `jobid`='".(int)$jobid."' AND status NOT IN ('18','19','20','21','23','26','27','28','29')");
			if($rewardJobNum>0){
				$data['error'] = '1';
				$data['msg'] = '当前职位还有未执行完的推荐赏单！';
			}else{
				$this->DB_delete_all("company_job_reward","`uid`='".(int)$uid."' AND `jobid`='".(int)$jobid."'");
				$this->DB_update_all("company_job","`rewardpack`='0'","`uid`='".(int)$uid."' AND `id`='".(int)$jobid."'");
				$this->DB_delete_all("company_job_rewardlist","`comid`='".(int)$uid."' AND `jobid`='".(int)$jobid."'");
				$this->DB_delete_all("company_job_rewardlog","`jobid`='".(int)$jobid."'");
				$data['error'] = '0';
			}
		}else{
			$data['error'] = '1';
			$data['msg'] = '请选择正确的职位！';
		}
		
		return $data;
	}
	function getShareJobOne($id){
		
		$where = "`id`='".(int)$id."' AND `sharepack`='1' AND `state`=1 and `edate`>'".time()."' and `r_status`<>2 and `status`<>1 ";
		
		$jobOne = $this->DB_select_once('company_job',$where);
		
		
		if(!empty($jobOne)){
			
			
			include PLUS_PATH."/com.cache.php";

			if($jobOne['maxsalary']>0){
				
				$jobOne['salary'] = $jobOne['minsalary'].'-'.$jobOne['maxsalary'].'元/月';
			
			}elseif($jobOne['minsalary']>0){
				
				$jobOne['salary'] = $jobOne['minsalary'].'以上';
			
			}else{
				
				$jobOne['salary'] = '面议';
			
			}

			$jobOne['exp_n'] = $comclass_name[$jobOne['exp']];
			$jobOne['edu_n'] = $comclass_name[$jobOne['edu']];

			$comInfo = $this->DB_select_once('company',"`uid`='".$jobOne['uid']."'","`name`,`address`,`linktel`,`linkphone`");
			
			if($jobOne['islink']=='1'){
				$jobLink = $this->DB_select_once('company_job_link',"`jobid`='".$jobOne['id']."'","`linkman`,`linkmoblie`");
				
			}
			
			if(!empty($jobLink)){
				$jobOne['linktel'] = $jobLink['linkmoblie'];
			}else{
				if($comInfo['linktel']){
					$jobOne['linktel'] = $comInfo['linktel'];
				}else{
					$jobOne['linktel'] = $comInfo['linkphone'];
				}
			}
			$jobOne['address'] = $comInfo['address'];


			$shareList = $this->DB_select_once('company_job_share',"`jobid`='".$jobOne['id']."'");

			if(!empty($shareList)){
				$jobOne['packid'] = $shareList['id'];
				$jobOne['packnum'] = $shareList['packnum'];
				$jobOne['packmoney'] = $shareList['packmoney'];
				$jobOne['packprice'] = $shareList['packprice'];
				$jobOne['nowprice'] = sprintf("%.2f", $shareList['packnum']*$shareList['packmoney']);
			}
		}
		return $jobOne;
	}

	

	function shareJobLook($job,$uid,$openid){
		
		if($job['packid'] && $job['packnum']>0){
		
			$lookLog = $this->DB_select_num("company_job_sharelog","`jobid`='".$job['id']."' AND `wxid`='".$openid."'");
			
			if($lookLog<1){
				

				$User = $this->DB_select_once("member","`uid`='".$uid."'","`usertype`");
				
				if($User['usertype']=='1'){
					$Table = 'member_statis';	
				}elseif($User['usertype']=='2'){
					$Table = 'company_statis';
				}
				
				if($Table){

					$Statis = $this->DB_select_once($Table,"`uid`='".$uid."'");
					if(!empty($Statis)){
						$this->DB_update_all("company_job_share","`packnum`='".($job['packnum']-1)."'","`id`='".$job['packid']."'");

						if($job['packnum']=='1'){
							$this->DB_update_all("company_job","`sharepack`='0'","`id`='".$job['id']."'");
						}	
						$this->DB_insert_once("company_job_sharelog","`uid`='".(int)$uid."',`jobid`='".$job['id']."',`jobname`='".$job['name']."',`packmoney`='".$job['packmoney']."',`comid`='".$job['uid']."',`wxid`='".$openid."',`time`='".time()."'");

							
						$this->DB_update_all($Table,"`packpay`='".($Statis['packpay']+$job[packmoney])."'","`uid`='".$uid."'");

						$dingdan=time().rand(10000,99999);
						$this->DB_insert_once("company_pay","`order_id`='".$dingdan."',`order_price`='".$job['packmoney']."',`pay_time`='".time()."',`pay_state`='2',`com_id`='".$uid."',`pay_remark`='分享红包浏览赏金',`type`='2',`pay_type`='2',`did`='0'");


					}
					
				}
			}
		}
		
	
	}
	
	function getWxOpenid($url){


		$app_id = $this->config['wx_appid'];
		$app_secret = $this->config['wx_appsecret'];
		$my_url = $url;
		$code = $_GET['code'];
		session_start();
		
		if(empty($code) || $code == $_SESSION['wxcode']){
			
			$_SESSION['wx']['state'] = md5(uniqid(rand(), TRUE));
			
			$dialog_url ="https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$app_id."&redirect_uri=".urlencode($my_url)."&response_type=code&scope=snsapi_userinfo&state=".$_SESSION['wx']['state']."#wechat_redirect";
			header("location:".$dialog_url);
		}else{
			$_SESSION['wxcode'] = $code;
		}
		
		if($_GET['state'] == $_SESSION['wx']['state']){

			$token_url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=" . $app_id . "&secret=" . $app_secret . "&code=".$code."&grant_type=authorization_code";
			if(function_exists('curl_init')) {

				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL,$token_url);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  2);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
				$response=curl_exec ($ch);
				curl_close ($ch);

				$user = json_decode($response,true);

			}
		}

		return $user;
	
	}
	
	function redPackOrder($data){

		if($data['jobid'] && $data['packmoney'] && $data['packnum']){
				
			$packmoney = sprintf("%.2f", $data['packmoney']);
			$packnum = floatval($data['packnum']);

			
			if($packmoney>0 && $packnum>0){

				$job = $this->DB_select_once("company_job","`uid`='".$this->uid."' AND `id`='".$data['jobid']."'","`id`,`uid`,`sharepack`");
				if(empty($job)){
					$return['error'] = '请选择正确的推广职位！';
				}elseif($job['sharepack']=='1'){

					$return['error'] = '当前职位正在推广中，请不要重复设置！';
				}else{

					$price = $packnum * $packmoney;
					
					if($price < 1){
						$return['error'] = '抵扣前推广总金额不得小于1元！';
					}else{

						$dingdan=time().rand(10000,99999);
						$orderData['type']='8';
						$orderData['order_id']=$dingdan;
						$orderData['order_price']=$price;
						$orderData['order_time']=time();
						$orderData['order_state']="1";
						$orderData['order_remark']='分享职位推广';
						$orderData['uid']=$this->uid;
						$orderData['did']=$this->userdid;
						$orderData['order_info']=serialize(array('jobid'=>$data['jobid'],'packnum'=>$data['packnum'],'packmoney'=>$data['packmoney'],'packprice'=>$price));
						$id=$this->insert_into("company_order",$orderData);
						
						if($id){

							$orderData['id']=$id;
							$return['order']=$orderData;
						}else{
							$return['error'] = '订单生成失败！';
						}
					}
				}
			}else{
				$return['error'] = '请正确填写推广金额及发放数量！';
			}

		}else{

			$return['error'] = '参数填写错误，请重新设置！';
		}
		return $return;
	}
	
	function rewardPackOrder($data){

		if($data['jobid'] && $data['rewardid']){

			$reward = $this->DB_select_once("company_job_rewardlist","`id`='".(int)$data['rewardid']."' AND `comid`='".$this->uid."' AND `jobid`='".(int)$data['jobid']."'");


			if($reward['money']>0){

				$job = $this->DB_select_once("company_job","`uid`='".$this->uid."' AND `id`='".$data['jobid']."'","`id`,`uid`");

				if(empty($job)){
					$return['error'] = '请选择正确的悬赏职位！';
				}else{

					$price = $reward['money'];


					if($price<1){
						$return['error'] = '职位赏金错误，请重试！';
					}else{

						$dingdan=time().rand(10000,99999);
						$orderData['type']='9';
						$orderData['order_id']=$dingdan;

						$orderData['order_price']=$price;

						$orderData['order_time']=time();
						$orderData['order_state']="1";
						$orderData['order_remark']='预先支付职位赏金';
						$orderData['uid']=$this->uid;
						$orderData['did']=$this->userdid;
						$orderData['rewardid']=$data['rewardid'];
						$id=$this->insert_into("company_order",$orderData);
						if($id){
							$orderData['id']=$id;
							$return['order']=$orderData;
						}else{
							$return['error'] = '订单生成失败！';
						}
					}
				}
			}else{
				$return['error'] = '职位赏金不正确，请重试！';
			}

		}else{

			$return['error'] = '参数填写错误，请重新设置！';
		}
		return $return;
	}

	function rewardJob($data){
		
		if($data['jobid']){

			if(($data['sqmoney']+$data['invitemoney']+$data['offermoney'])>1){
				$job = $this->DB_select_once("company_job","`uid`='".$this->uid."' AND `id`='".$data['jobid']."'","`id`,`uid`,`rewardpack`");
				if(empty($job)){
					$return['error'] = '请选择正确的推广职位！';

				}elseif($job['rewardpack']=='1'){

					$return['error'] = '当前职位已是赏金职位，请不要重复设置！';
				
				}else{
					
					$val  = "`uid`='".$job['uid']."'";
					$val .= ",`jobid`='".$job['id']."'";
					$val .= ",`sqmoney`='".floatval($data['sqmoney'])."'";
					$val .= ",`invitemoney`='".floatval($data['invitemoney'])."'";
					$val .= ",`offermoney`='".floatval($data['offermoney'])."'";
					$val .= ",`money`='".(floatval($data['offermoney'])+floatval($data['invitemoney'])+floatval($data['sqmoney']))."'";
					$val .= ",`stime`='".time()."'";
					$val .= ",`project`='".$data['project']."'";
					$val .= ",`exp`='".$data['exp']."'";
					$val .= ",`edu`='".$data['edu']."'";
					$val .= ",`skill`='".$data['skill']."'";

					$this->DB_insert_once("company_job_reward",$val);
					$this->DB_update_all("company_job","`rewardpack`='1'","`id`='".$job['id']."'");

					$return['error'] = 'ok';
				}
			}else{
			
				$return['error'] = '悬赏总金额不得低于1元！';
			}
			
		}else{
			$return['error'] = '参数填写错误，请重新设置！';
		}

		return $return;
	}
	
	
	
	function getRewardInfo($jobid,$uid,$usertype='1'){
		
		$rewardInfo = $this->DB_select_once("company_job_rewardlist","`jobid`='".(int)$jobid."' AND `uid`='".(int)$uid."'");

		return $rewardInfo;
	
	}
	function getReward($id,$uid){
		
		$rewardInfo = $this->DB_select_once("company_job_rewardlist","`id`='".(int)$id."' AND `comid`='".(int)$uid."'");

		return $rewardInfo;
	
	}
	function getRewardJob($jobid,$isjob='0'){
		
		$rewardJob = $this->DB_select_once("company_job_reward","`jobid`='".(int)$jobid."'");
		if($isjob=='1' && !empty($rewardJob)){
			
			$jobInfo = $this->DB_select_once("company_job","`id`='".$rewardJob['jobid']."'");
			$rewardJob['jobinfo'] = $jobInfo;

		
		}
		return $rewardJob;
	
	}

	function getRewardAll($id,$status=''){
		
		$rewardJob = $this->DB_select_once("company_job_rewardlist","`id`='".(int)$id."'");
		
		if(!empty($rewardJob)){
			$comInfo = $this->DB_select_once("company","`uid`='".$rewardJob['comid']."'");
			$jobInfo = $this->DB_select_once("company_job","`id`='".$rewardJob['jobid']."'");
			$userInfo = $this->DB_select_once("resume","`uid`='".$rewardJob['uid']."'");
			if($status){
				$nowReward = $this->DB_select_once("company_job_rewardlog","`rewardid`='".$rewardJob['id']."' AND `status`='".$status."'");
			
				$Data['loginfo'] =  $nowReward['loginfo'];
				$Data['arbinfo'] = $nowReward['remark'];
				$Data['arbpic'] = $nowReward['arbpic'];
			}
			$Data['comname'] = $comInfo['name'];
			if(!$comInfo['linktel']){
				$Data['linkphone'] = $comInfo['linkphone'];
			}else{
				$Data['linktel'] = $comInfo['linktel'];
			}
			$Data['jobname'] = $jobInfo['name'];
			$Data['username'] = $userInfo['name'];
			$Data['telphone'] = $userInfo['telphone'];
		}
		return $Data;
	
	}



	function veriftyUser($jobid,$uid,$usertype){
	
		
		if(!$jobid){
		    $return['error'] = 5;
			$return['msg'] = '请选择正确的赏金职位！';
		}else{
			if(!$uid){
				$return['error'] = 0;
				$return['msg'] = '请先登录！';
			
			}else{
				if($usertype==1){
					if($this->config['sy_reward_tel']=='1'){
						$userInfo = $this->DB_select_once('resume',"`uid`='".(int)$uid."'","`moblie_status`");
						if($userInfo['moblie_status']!='1'){
							$return['error'] = 9;
							$return['msg'] = '请先进行手机认证！';
						}
					}
					if(!$return['msg']){
					
						$sqNum = $this->DB_select_num("company_job_rewardlist","`uid`='".(int)$uid."' AND `status` NOT IN ('18','19','20','21','23','26','27','28','29')");
						if($this->config['sy_reward_sqnum']>0 && $sqNum>=$this->config['sy_reward_sqnum']){
							$return['error'] = 8;
							$return['msg'] = '最多只能同时申请'.$this->config['sy_reward_sqnum'].'份悬赏职位';
						}else{
							$where = "`id`='".(int)$jobid."' AND `rewardpack`='1' AND `state`=1 and `edate`>'".time()."' and `r_status`=1 and `status`=0 ";
							
							$jobInfo = $this->DB_select_once("company_job",$where);
							if(empty($jobInfo)){
								$return['error'] = 3;
								$return['msg'] = '悬赏职位不存在或已停止赏金招聘！';
							}else{
								$rows = $this->DB_select_once("userid_job","`uid`='".(int)$uid."' AND `job_id`='".(int)$jobid."'","`id`");
								if(!empty($rows)){

									$return['error'] = 6;
									$return['msg'] = '您已申请过该职位！';

								}else{
									$rewardInfo = $this->getRewardInfo($jobid,$uid);

									if(!empty($rewardInfo)){
										$return['error'] = 3;
										$return['msg'] = '您已申请过该职位！';
									}else{
										$rewardJob = $this->DB_select_once("company_job_reward","`jobid`='".(int)$jobInfo['id']."'");
										
										$useridmsg= $this->DB_select_once("userid_msg","`uid`='".(int)$uid."' AND `jobid`='".(int)$jobid."'","`id`");
										if(!empty($useridmsg)){
											$return['error'] = 4;
											$return['msg'] = '该职位已邀请您面试，无需再投简历！';
										}else{
											$rows= $this->DB_select_once("resume_expect","`uid`='".(int)$uid."' AND `defaults`='1'","`id`,`name`,`r_status`");

											if(!empty($rows)){
												if($rows['r_status']=='1'){
													$data['jobid'] = $jobInfo['id'];
													$data['comid'] = $jobInfo['uid'];
													$data['eid'] = $rows['id'];
													$data['name'] = $rows['name'];
										
													$return['error'] = 1;
													if($rewardJob['exp']=='1'){
														$expNum = $this->DB_select_num("resume_work","`eid`='".$rows['id']."'");
														if($expNum<1){
															$data['exptype'] = 1;
														
															$return['error'] = 7;
															$return['msg'] = '简历暂不符合职位要求,缺少工作经历';
														}
													}
													if($rewardJob['edu']=='1'){
														$eduNum = $this->DB_select_num("resume_edu","`eid`='".$rows['id']."'");
														if($eduNum<1){
															$data['edutype'] = 1;
														
															$return['error'] = 7;
															$return['msg'] = '简历暂不符合职位要求,缺少教育经历';
														}
													}
													if($rewardJob['skill']=='1'){
														$skillNum = $this->DB_select_num("resume_skill","`eid`='".$rows['id']."' AND `pic`<>''");
														if($skillNum<1){
															
															$return['error'] = 7;
															$data['skilltype'] = 1;
															$return['msg'] = '简历暂不符合职位要求,缺少技能证书';
														}
													}
													if($rewardJob['project']=='1'){
														$projectNum = $this->DB_select_num("resume_project","`eid`='".$rows['id']."'");
														if($projectNum<1){
															
															$return['error'] = 7;
															$data['projecttype'] = 1;
															$return['msg'] = '简历暂不符合职位要求,缺少项目经历';
														}
													}
													$data['sqmoney'] = $rewardJob['sqmoney'];
													$data['invitemoney'] = $rewardJob['invitemoney'];
													$data['offermoney'] = $rewardJob['offermoney'];
													$data['money'] = $rewardJob['money'];

													$return['data'] = $data;
												
												}else{
													$return['error'] = 10;
													$return['msg'] = '您的简历正在审核，暂无法使用！';
												}
												
											}else{
												$return['error'] = 2;
												$return['msg'] = '您还没有合适的简历，是否先添加简历？';
												
											}
										}
									}
								}
							}
						}
					}
					

				}elseif($usertype==3){
					$talentNum = $this->DB_select_num("lt_talent","`uid`='".$uid."'");

					if($talentNum>0){
						$return['error'] = 12;
					}else{
						$return['error'] = 11;
					}
						
				
				}else{
				
					$return['error'] = 3;
					$return['msg'] = '仅支持个人自荐以及猎头中介推荐！';
				}
			}
		}

		if($return['msg']){
			$return['msg'] = $return['msg'];
		}
		
		return $return;
	}
	function veriftyLtuser($jobid,$uid,$usertype,$eid){
	
		
		if(!$jobid){
		    $return['error'] = 5;
			$return['msg'] = '请选择正确的赏金职位！';
		}else{
			if(!$uid){
				$return['error'] = 0;
				$return['msg'] = '请先登录！';
			
			}else{
				if($usertype==3){
					$where = "`id`='".(int)$jobid."' AND `rewardpack`='1' AND `state`=1 and `edate`>'".time()."' and `r_status`=1 and `status`=0 ";
					
					$jobInfo = $this->DB_select_once("company_job",$where);

					if(empty($jobInfo)){
						$return['error'] = 3;
						$return['msg'] = '悬赏职位不存在或已停止赏金招聘！';
					}else{

						
						$expectInfo = $this->DB_select_once("lt_talent","`uid`='".(int)$uid."' AND `id`='".(int)$eid."'");
						
						if(!empty($expectInfo)){
							if($this->config['sy_reward_tel']=='1' && $expectInfo['tel_status']!='1'){
									$return['error'] = 8;
									
									$return['msg'] = '当前简历还未授权认证，无法推荐!';
							}else{
								$sqNum = $this->DB_select_num("company_job_rewardlist","`eid`='".(int)$eid."' AND `status` NOT IN ('18','19','20','21','23','26','27','28','29')");
								if($sqNum>0){
									$return['error'] = 8;
									$return['msg'] = '当前简历已推荐，请耐心等待企业回复';
								}else{
									$sqNumjob = $this->DB_select_num("company_job_rewardlist","`eid`='".(int)$eid."' AND `jobid`='".(int)$jobid."'");
									if($sqNumjob>0){
									
										$return['error'] = 9;
										$return['msg'] = '请不要重复推荐';
									}else{
										$rewardJob = $this->DB_select_once("company_job_reward","`jobid`='".(int)$jobInfo['id']."'");

										$data['jobid'] = $jobInfo['id'];
										$data['comid'] = $jobInfo['uid'];
										$data['eid'] = $expectInfo['id'];
										$data['name'] = $expectInfo['name'];
										
										$return['error'] = 1;

										if($rewardJob['exp']=='1' && $expectInfo['expinfo']==''){
											
											$data['exptype'] = 1;
											
											$return['error'] = 7;
											$return['msg'] = '简历暂不符合职位要求,缺少工作经历';
											
										}
										if($rewardJob['edu']=='1' && $expectInfo['eduinfo']==''){
											
											$data['edutype'] = 1;
											
											$return['error'] = 7;
											$return['msg'] = '简历暂不符合职位要求,缺少教育经历';
											
										}
										if($rewardJob['skill']=='1' && $expectInfo['skillinfo']==''){
											
												
											$return['error'] = 7;
											$data['skilltype'] = 1;
											$return['msg'] = '简历暂不符合职位要求,缺少技能特长描述';
											
										}
										if($rewardJob['project']=='1' && $expectInfo['projectinfo']==''){
											
											$return['error'] = 7;
											$data['projecttype'] = 1;
											$return['msg'] = '简历暂不符合职位要求,缺少项目经历';
											
										}
										$data['sqmoney'] = $rewardJob['sqmoney'];
										$data['invitemoney'] = $rewardJob['invitemoney'];
										$data['offermoney'] = $rewardJob['offermoney'];
										$data['money'] = $rewardJob['money'];

										$return['data'] = $data;
									}

								}
							}
							
						}else{
							$return['error'] = 2;
							$return['msg'] = '简历数据错误，请重新推荐！';
						}
					}

				}else{
				
					$return['error'] = 3;
					$return['msg'] = '仅支持个人自荐以及猎头中介推荐！';
				}
			}
		}

		if($return['msg']){
			$return['msg'] = $return['msg'];
		}
		
		return $return;
	}
	function sqRewardJob($jobid,$uid,$usertype,$eid=''){
		if($usertype==3){
			$verifty = $this->veriftyLtuser($jobid,$uid,$usertype,$eid);
		}else{
			$verifty = $this->veriftyUser($jobid,$uid,$usertype);
		}
		

		if($verifty['error']=='1'){
			$data['uid'] = (int)$uid;
			$data['eid']= (int)$verifty['data']['eid'];
			$data['comid']= (int)$verifty['data']['comid'];
			$data['jobid'] = (int)$jobid;
			$data['datetime'] = time();
			$data['status'] = 0;
			$data['sqmoney'] = $verifty['data']['sqmoney'];
			$data['invitemoney'] = $verifty['data']['invitemoney'];
			$data['offermoney'] = $verifty['data']['offermoney'];
			$data['money'] = $verifty['data']['money'];
			$data['usertype'] = $usertype;
			$this->insert_into("company_job_rewardlist",$data);

			return array('error'=>1);
		}else{

			return $verifty;
		}
	}
	function getStatusInfo($rewardid,$utype,$status,$rewardLog=array()){
		
		
		$hour = $this->config['sy_reward_hour'];
		if(empty($rewardLog)){
			$rewardLog  = $this->DB_select_all("company_job_rewardlog","`rewardid`='".$rewardid."' ORDER BY id ASC");
		}
		if(is_array($rewardLog)){
			foreach($rewardLog as $key=>$value){
				if($value['status']==$status){
					$rewardInfo = $value;
				}
			   if($value['status']=='1'){
					
					$msg = '企业查阅简历，预先支付全额赏金';
					if($value['pay']>0){
						$msg.=',发放投递赏金'.$value['pay'].'元。';
					}
					
				}elseif($value['status']=='2'){
					$msg = '企业邀请面试。<br/>'.$value['loginfo'];

				}elseif($value['status']=='3'){

					$msg = '求职者接受面试邀请。';
					
				}elseif($value['status']=='4'){
					
					$msg = '求职者已确认面试。';
						
				}elseif($value['status']=='5'){

					$msg = '企业已确认求职者面试';
					if($value['pay']>0){
						$msg.=',发放面试赏金'.$value['pay'].'元。';
					}
					if($key==(count($rewardLog)-1)){
						$endMsg = '等待企业发放Offer';
					}
					
				}elseif($value['status']=='6'){

					$msg = '企业发出Offer';
				
				}elseif($value['status']=='7'){

					$msg = '求职者确认已入职';
					
				}elseif($value['status']=='8'){

					$msg = '企业确认入职';
					if($value['pay']>0){
						$msg.=',发放入职赏金'.$value['pay'].'元。';
					}
				}elseif($value['status']=='18'){

					$msg = '求职者取消申请';
					
				}elseif($value['status']=='19'){

					$msg = '企业结束赏单';
					
				}elseif($value['status']=='20'){

					$msg = '企业放弃该简历';
				}elseif($value['status']=='21'){

					$msg = '求职者拒绝面试';
				}elseif($value['status']=='22'){

					$msg = '企业否认求职者参与面试';
				}elseif($value['status']=='23'){

					$msg = '企业认为未达要求，本次推荐结束';
				}elseif($value['status']=='24'){

					$msg = '求职者放弃入职';
				}elseif($value['status']=='25'){

					$msg = '企业否认已入职';
				}elseif($value['status']=='26'){

					$msg = $value['loginfo'].'<br/>申请自述：'.$value['remark'];

				}elseif($value['status']=='27'){

					$msg = '求职者放弃仲裁';

				}elseif($value['status']=='28'){

					$msg = $value['loginfo'].'<br/>仲裁说明：'.$value['remark'];
				}elseif($value['status']=='29'){

					$msg = $value['loginfo'].'<br/>仲裁说明：'.$value['remark'];
				}
				$showLog['time'] = $value['ctime'];
				$showLog['info'] = $msg;

				$logList['loglist'][] = $showLog;
				
			}
		}
		
		if($status=='0'){
				$nowMsg = '等待企业查看';
				
		}elseif($status=='1'){
				$nowMsg = '企业已预付赏金查看简历';
				$endMsg = '等待企业邀请面试';
				if($utype=='2'){

					$input[] = array('name'=>'邀请面试','status'=>'2');
				}
			
		}elseif($status=='2'){

				$nowMsg = '企业已邀请面试';

				$endMsg = '等待求职者接受面试邀请';
				if($utype=='1'){
					$input[] = array('name'=>'接受面试','status'=>'3');
					$input[] = array('name'=>'拒绝面试','status'=>'21');
				}
				if($utype=='2'){
					
					
					$hourTime = $hour-round((time()-$rewardInfo['ctime'])/3600,1);
					if($hourTime<=0){


						$input[] = array('name'=>'结束赏单','status'=>'19');

					}
				}

		}elseif($status=='3'){
				$nowMsg = '求职者接受邀请';
				$endMsg = '等待确认面试';
				if($utype=='1'){
					$input[] = array('name'=>'确认已面试','status'=>'4');

					
				}
				if($utype=='2'){
					

					$hourTime = $hour-round((time()-$rewardInfo['ctime'])/3600,1);
					if($hourTime<=0){


						$input[] = array('name'=>'结束赏单','status'=>'19');

					}
				}
			
		}elseif($status=='4'){
				$nowMsg = '求职者确认面试';
				$endMsg = '等待企业确认面试';
				if($utype=='2'){
					$input[] = array('name'=>'确认已面试','status'=>'5');
					$input[] = array('name'=>'否认已面试','status'=>'22');
				}
				if($utype=='1'){
					
					$hourTime = $hour-round((time()-$rewardInfo['ctime'])/3600,1);
					if($hourTime<=0){

						$input[] = array('name'=>'领取赏金','status'=>'18');

					}
				}

		}elseif($status=='5'){
				$nowMsg = '企业已确认面试';
				$endMsg = '等待企业发放Offer';
				if($utype=='2'){
					$input[] = array('name'=>'发放Offer','status'=>'6');
					$input[] = array('name'=>'未达要求','status'=>'23');
				}
			
		}elseif($status=='6'){
				$nowMsg = '企业已发放Offer';
				$endMsg = '等待求职者入职';
				if($utype=='1'){
					$input[] = array('name'=>'确认入职','status'=>'7');
					$input[] = array('name'=>'放弃入职','status'=>'24');
				}
				if($utype=='2'){
					

					$hourTime = $hour-round((time()-$rewardInfo['ctime'])/3600,1);
					if($hourTime<=0){


						$input[] = array('name'=>'结束赏单','status'=>'19');

					}
				}
			
		}elseif($status=='7'){
				$nowMsg = '求职者已确认入职';
				$endMsg = '等待企业确认入职';
				if($utype=='2'){
					$input[] = array('name'=>'确认入职','status'=>'8');
					$input[] = array('name'=>'否认入职','status'=>'25');
				}
				if($utype=='1'){
					
					$hourTime = $hour-round((time()-$rewardInfo['ctime'])/3600,1);
					if($hourTime<=0){


						$input[] = array('name'=>'领取赏金','status'=>'18');

					}
				}
			
		}elseif($status=='8'){
				$nowMsg = '企业确认入职';
			
		}elseif($status=='18'){
				$nowMsg = '求职者取消申请';
			
		}elseif($status=='19'){
				$nowMsg = '企业结束赏单';
			
		}elseif($status=='20'){
				$nowMsg = '企业放弃简历';

		}elseif($status=='21'){
			$nowMsg = '求职者拒绝面试';

		}elseif($status=='22'){
			

			$nowMsg = '企业否认求职者参与面试';
			if($utype=='1'){
				$input[] = array('name'=>'申请仲裁','status'=>'26');
				$input[] = array('name'=>'放弃仲裁','status'=>'27');

			}
			if($utype=='2'){
					

					$hourTime = $hour-round((time()-$rewardInfo['ctime'])/3600,1);
					if($hourTime<=0){

						$input[] = array('name'=>'结束赏单','status'=>'19');

					}
				}
		}elseif($status=='23'){

			$nowMsg = '企业认为未达要求';

		}elseif($status=='24'){

			$nowMsg = '求职者放弃入职';

		}elseif($status=='25'){

			$nowMsg = '企业否认已入职';
			if($utype=='1'){
				$input[] = array('name'=>'申请仲裁','status'=>'26');
				$input[] = array('name'=>'放弃仲裁','status'=>'27');
			}
			if($utype=='2'){
					
					$hourTime = $hour-round((time()-$rewardInfo['ctime'])/3600,1);
					if($hourTime<=0){

						$input[] = array('name'=>'结束赏单','status'=>'19');

					}
				}

		}elseif($status=='26'){

			$nowMsg = '求职者发起仲裁';

		}elseif($status=='27'){

			$nowMsg = '求职者放弃仲裁';

		}elseif($status=='28'){

			$nowMsg = '仲裁结果：企业胜出';

		}elseif($status=='29'){

			$nowMsg = '仲裁结果：求职者胜出';
		}
		$logList['nowmsg'] = $nowMsg;
		$logList['input'] = $input;
		$logList['endmsg']= $endMsg;
		$logList['rewardinfo'] = $rewardInfo;

		
		return $logList;
	}
	

		
	function logStatus($rewardid,$status,$uid,$utype,$data=array()){
		
		if($rewardid && $status){
			$hour = $this->config['sy_reward_hour'];
			$rewardInfo = $this->DB_select_once("company_job_rewardlist","`id`='".$rewardid."'");
			if(!empty($rewardInfo)){
				
				$rstatus = $rewardInfo['status'];

				if(in_array($rewardlist['status'],array('18','19','20','21','23','26','27','28','29'))){
					
					$error = '本次悬赏单已结束，无法继续操作！';
				
				}else{
					$logData['jobid'] = $rewardInfo['jobid'];
					$logData['rewardid'] = $rewardInfo['id'];
					$logData['eid'] = $rewardInfo['eid'];
					$logData['uid'] = $uid;
					$logData['utype'] = $utype;
					$logData['status'] = $status;
					$logData['ostatus'] = $rstatus;
					
					if($utype=='2'){

						if($rewardInfo['comid']!=$uid){

							$error = '操作人身份错误！';

						}elseif(!in_array($status,array('2','5','6','8','19','20','22','23','25'))){
							
							$error = '请正确操作！';

						}else{
							if($status=='2'){
							
								if($rstatus!='1'){
									if($rstatus>1){
										$error = '请不要重复邀请！';
									}else{
										$error = '请先支付职位赏金！';
									}
									

								}else{
									if($data['linkman']==''){
										
										$error = '请填写联系人！';
									}elseif($data['linktel']==''){
										
										$error = '请填写联系人电话！';

									}elseif($data['intertime']==''){
										$error = '请选择面试日期！';

									}elseif($data['address']==''){

										$error = '请填写面试地址！';

									}else{
										$invitetime = strtotime($data['intertime']);
										if($invitetime>=strtotime(date('Y-m-d'))){

											$logData['loginfo'] = "联系人：".$data['linkman']."<br/>联系电话：".$data['linktel']."<br/>面试日期：".$data['intertime']."<br/>面试地址：".$data['address']."<br/>面试备注：".$data['content'];

										}else{
											$error = '面试日期不合理！';
										}
									}
								}

							}elseif($status=='5'){
								
								if($rstatus!='4'){

									$error = '请等待求职者确认面试！';
								}else{
									if($rewardInfo['invitemoney']>0){
										$logData['pay'] = $rewardInfo['invitemoney'];
										$msg = '面试赏金：'.$rewardInfo['invitemoney'];
										$this->uppay($rewardInfo['comid'],$rewardInfo['uid'],$rewardInfo['usertype'],$rewardInfo['invitemoney'],$msg);
									}
								}
							
							}elseif($status=='6'){
								
								if($rstatus!='5'){

									$error = '请先确认求职者已面试！';
								}
							}elseif($status=='8'){
								
								if($rstatus!='7'){

									$error = '请等待求职者确认入职！';
								}else{
									if($rewardInfo['offermoney']>0){
										$logData['pay'] = $rewardInfo['offermoney'];
										$msg = '入职赏金：'.$rewardInfo['offermoney'];
										$this->uppay($rewardInfo['comid'],$rewardInfo['uid'],$rewardInfo['usertype'],$rewardInfo['offermoney'],$msg);
									}
								}
							}elseif($status=='19'){
								
								if(!in_array($rstatus,array('2','3','6','22','25'))){

									$error = '请等待求职者回应！';
								}else{
									$nowReward = $this->DB_select_once("company_job_rewardlog","`rewardid`='".$rewardid."' AND `status`='".$rstatus."'");

									$hourTime = $hour-round((time()-$nowReward['ctime'])/3600,1);
									if($hourTime<=0){
										if(in_array($rstatus,array('2','3','22'))){
										
											$hpay = $rewardInfo['invitemoney']+$rewardInfo['offermoney'];
											if($hpay>0){
												$statis = $this->DB_select_once('company_statis',"`uid`='".$rewardInfo['comid']."'","`packpay`");
												$this->DB_update_all("company_statis","`packpay`=`packpay`+$hpay","`uid`='".$rewardInfo['comid']."'");

												$this->orderLog($hpay,$rewardInfo['comid'],'求职者久未回应，归还面试、入职赏金至赏金账户!');
											}
										
										}elseif(in_array($rstatus,array('6','25'))){
										
											$hpay = $rewardInfo['offermoney'];
											if($hpay>0){
												$statis = $this->DB_select_once('company_statis',"`uid`='".$rewardInfo['comid']."'","`packpay`");
												$this->DB_update_all("company_statis","`packpay`=`packpay`+$hpay","`uid`='".$rewardInfo['comid']."'");

												$this->orderLog($hpay,$uid,'求职者久未回应，归还入职赏金至赏金账户!');
											}
										}
									
									}else{
										$error = '请耐心等待求职者回应,剩余时间：'.$hourTime.'小时！';
									}
									
								
								}
							}elseif($status=='20'){
								
								if($rstatus!='1'){

									$error = '您还未接受简历，无需放弃！';
								}else{
									$hpay = $rewardInfo['invitemoney']+$rewardInfo['offermoney'];
									if($hpay>0){
										$statis = $this->DB_select_once('company_statis',"`uid`='".$uid."'","`packpay`");
										$this->DB_update_all("company_statis","`packpay`=`packpay`+$hpay","`uid`='".$uid."'");

										$this->orderLog($hpay,$uid,'放弃简历，归还职位赏金至赏金账户!');
									}
									
								}
							}elseif($status=='22'){
								
								if($rstatus!='4'){

									$error = '请等待求职者确认面试！';
								}
							}elseif($status=='23'){
								
								if($rstatus!='5'){

									$error = '请先确认求职者是否参与面试！';
								}else{
									$hpay = $rewardInfo['offermoney'];
									if($hpay>0){
										$statis = $this->DB_select_once('company_statis',"`uid`='".$uid."'","`packpay`");
										$this->DB_update_all("company_statis","`packpay`=`packpay`+$hpay","`uid`='".$uid."'");

										$this->orderLog($hpay,$uid,'企业认为未达要求，求职者面试失败，归还入职赏金至赏金账户!');
									}
								}
							}elseif($status=='25'){
								
								if($rstatus!='7'){

									$error = '请等待求职者是否确定入职！';
								}
							}
						}
					}elseif($utype=='1' || $utype=='3'){
						if($rewardInfo['uid']!=$uid){

							$error = '操作人身份错误！';

						}elseif(!in_array($status,array('3','4','7','18','21','24','26','27'))){
							
							$error = '请正确操作！';

						}else{
							if($status=='3'){
								if($rstatus!='2'){

									$error = '请先等待企业发出邀请！';
								}
							}elseif($status=='4'){
								if($rstatus!='3'){

									$error = '请先接受邀请面试！';
								}
							}elseif($status=='7'){

								if($rstatus!='6'){

									$error = '请先等待企业发出offer！';
								}
							}elseif($status=='18'){

								if($rstatus!='4' && $rstatus!='7'){

									$error = '请先等待企业回应！';
								}else{
									$nowReward = $this->DB_select_once("company_job_rewardlog","`rewardid`='".$rewardid."' AND `status`='".$rstatus."'");
									
									$hourTime = $hour-round((time()-$nowReward['ctime'])/3600,1);
									
									if($hourTime<=0){

										if($rstatus=='4'){

											if($rewardInfo['invitemoney']>0){
												$logData['pay'] = $rewardInfo['invitemoney'];
												$msg = '面试赏金：'.$rewardInfo['invitemoney'];
												$this->uppay($rewardInfo['comid'],$rewardInfo['uid'],$rewardInfo['usertype'],$rewardInfo['invitemoney'],$msg);
												
											}
										}elseif($rstatus=='7'){
											if($rewardInfo['offermoney']>0){
							
												$logData['pay'] = $rewardInfo['offermoney'];
												$msg = '入职赏金：'.$rewardInfo['offermoney'];
												$this->uppay($rewardInfo['comid'],$rewardInfo['uid'],$rewardInfo['usertype'],$rewardInfo['offermoney'],$msg);
											}
										}

									}else{
										$error = '请耐心等待企业回应,剩余时间：'.$hourTime.'小时！';
									}
								}

							}elseif($status=='21'){
								if($rstatus!='2'){

									$error = '请先等待企业发出邀请！';
								}else{
									$hpay = $rewardInfo['invitemoney']+$rewardInfo['offermoney'];
									if($hpay>0){
										$statis = $this->DB_select_once('company_statis',"`uid`='".$rewardInfo['comid']."'","`packpay`");
										$this->DB_update_all("company_statis","`packpay`=`packpay`+$hpay","`uid`='".$rewardInfo['comid']."'");

										$this->orderLog($hpay,$rewardInfo['comid'],'求职者拒绝面试，归还职位赏金至赏金账户!');
									}
								}
							}elseif($status=='24'){
								if($rstatus!='6'){

									$error = '请先等待企业发出offer！';
								}else{
									$hpay = $rewardInfo['offermoney'];
									if($hpay>0){
										$statis = $this->DB_select_once('company_statis',"`uid`='".$rewardInfo['comid']."'","`packpay`");
										$this->DB_update_all("company_statis","`packpay`=`packpay`+$hpay","`uid`='".$rewardInfo['comid']."'");

										$this->orderLog($hpay,$rewardInfo['comid'],'求职者拒绝入职，归还入职赏金至赏金账户!');
									}
								}
							}elseif($status=='26'){
								if($rstatus!='22' && $rstatus!='25' ){

									$error = '暂无可仲裁需求！';
								}else{
									if($rstatus=='22'){
										$logData['loginfo'] = '企业否认求职者参与面试，求职者发起仲裁需求，申请网站介入';
									}
									if($rstatus=='25'){
										$logData['loginfo'] = '企业否认求职者已入职，求职者发起仲裁需求，申请网站介入';
									}
									if($data['arbpic']){
										$logData['arbpic'] = $data['arbpic'];
									}
									if($data['content']){
										$logData['remark'] = $data['content'];
									}
								}
							}elseif($status=='27'){

								if($rstatus!='22' && $rstatus!='25' ){

									$error = '请按步骤操作！';
								}else{
									if($rstatus=='22'){
										$hpay = $rewardInfo['invitemoney']+$rewardInfo['offermoney'];
										if($hpay>0){
											$statis = $this->DB_select_once('company_statis',"`uid`='".$rewardInfo['comid']."'","`packpay`");
											$this->DB_update_all("company_statis","`packpay`=`packpay`+$hpay","`uid`='".$rewardInfo['comid']."'");

											$this->orderLog($hpay,$rewardInfo['comid'],'求职者放弃仲裁，归还职位赏金至赏金账户!');
										}

									}elseif($rstatus=='25'){
										$hpay = $rewardInfo['offermoney'];
										if($hpay>0){
											$statis = $this->DB_select_once('company_statis',"`uid`='".$rewardInfo['comid']."'","`packpay`");
											$this->DB_update_all("company_statis","`packpay`=`packpay`+$hpay","`uid`='".$rewardInfo['comid']."'");

											$this->orderLog($hpay,$rewardInfo['comid'],'求职者放弃仲裁，归还入职赏金至赏金账户!');
										}
									
									}
								}
							}
						
						}
					}elseif($utype=='admin'){
						if(!in_array($status,array('28','29'))){
							
							$error = '请正确操作！';

						}else{
							
							if($rstatus!='26'){

									$error = '无人申请仲裁或仲裁已结束！';
							}else{
								$logData['remark'] = $data['content'];
								$nowReward = $this->DB_select_once("company_job_rewardlog","`rewardid`='".$rewardid."' AND `status`='".$rstatus."'");

								if($status=='28'){

									$logData['loginfo'] = '仲裁结果：企业胜出，系统退款结束赏单';

									if($nowReward['ostatus']=='22'){
										$hpay = $rewardInfo['invitemoney']+$rewardInfo['offermoney'];
										if($hpay>0){
											$statis = $this->DB_select_once('company_statis',"`uid`='".$rewardInfo['comid']."'","`packpay`");
											$this->DB_update_all("company_statis","`packpay`=`packpay`+$hpay","`uid`='".$rewardInfo['comid']."'");

											$this->orderLog($hpay,$rewardInfo['comid'],'管理员仲裁，面试、入职赏金至赏金账户!');
										}
									}elseif($nowReward['ostatus']=='25'){
										$hpay = $rewardInfo['offermoney'];
										if($hpay>0){
											$statis = $this->DB_select_once('company_statis',"`uid`='".$rewardInfo['comid']."'","`packpay`");
											$this->DB_update_all("company_statis","`packpay`=`packpay`+$hpay","`uid`='".$rewardInfo['comid']."'");

											$this->orderLog($hpay,$rewardInfo['comid'],'管理员仲裁，归还入职赏金至赏金账户!');
										}
									}								
								}elseif($status=='29'){

									

									if($nowReward['ostatus']=='22'){
										
										$logData['loginfo'] = '仲裁结果：求职者胜出，系统发放面试赏金，退还入职赏金，结束赏单';

										$uhpay = $rewardInfo['invitemoney'];
										$chpay = $rewardInfo['offermoney'];
										if($uhpay>0){
												
											$logData['pay'] = $uhpay;
											$msg = '管理员仲裁，发放面试赏金：'.$uhpay;
											$this->uppay($rewardInfo['comid'],$rewardInfo['uid'],$rewardInfo['usertype'],$uhpay,$msg);
										
										}
										if($chpay>0){
											$statis = $this->DB_select_once('company_statis',"`uid`='".$rewardInfo['comid']."'","`packpay`");
											$this->DB_update_all("company_statis","`packpay`=`packpay`+$chpay","`uid`='".$rewardInfo['comid']."'");

											$this->orderLog($chpay,$rewardInfo['comid'],'管理员仲裁，发放面试赏金给用户，并退还剩余入职赏金!');
										}


									}elseif($nowReward['ostatus']=='25'){
										$uhpay = $rewardInfo['offermoney'];
										if($uhpay>0){
											
											$logData['loginfo'] = '仲裁结果：求职者胜出，系统发放入职赏金，结束赏单';


											$logData['pay'] = $uhpay;
											$msg = '管理员仲裁，发放入职赏金：'.$uhpay;
											$this->uppay($rewardInfo['comid'],$rewardInfo['uid'],$rewardInfo['usertype'],$uhpay,$msg);
										
										}
										
									}
								}
							}
						}
					
					}
					if($error==''){
					
						$this->upstatus($rewardInfo['id'],$status);
						$this->statusLog($logData);

						$this->sendMsg($rewardInfo,$utype,$status);
						
					}
				}

			}else{
				
				$error = '当前悬赏职位数据出错！';
			}

		}else{
			$error = '参数不全 ！';
		}
		return array('error'=>$error);
	
	}
	
	function uppay($comid,$uid,$utype,$price,$mark){
		
		if($utype==3){
			$table = 'lt_statis';
		}else{
			$table = 'member_statis';
		}
		$statis = $this->DB_select_once($table,"`uid`='".$uid."'","`packpay`");
		$this->orderLog('-'.$price,$comid,'发放'.$mark);
		$this->DB_update_all($table,"`packpay`=`packpay`+$price","`uid`='".$uid."'");
		$this->orderLog($price,$uid,'获得'.$mark);

	}
	function upstatus($id,$status){
		
		$this->DB_update_all('company_job_rewardlist',"`status`='".$status."'","`id`='".$id."'");
	}
	function statusLog($data){
		
		$data['ctime'] = time();

		$this->insert_into("company_job_rewardlog",$data);
	
	}
	function orderLog($price,$comid,$pay_remark){
	
		$orderid = time().rand(1000,9999);

		$this->DB_insert_once("company_pay","`order_id`='$orderid',`order_price`='".$price."',`pay_time`='".time()."',`pay_state`='2',`com_id`='$comid',`pay_remark`='$pay_remark',`type`='2',`pay_type`='100'");
		
	}

	function withDraw($uid,$usertype,$money,$realname){
		
		if(!$realname || !$uid || !$usertype || !$money){

			$error = '参数不完整，请重试！';
			
		}elseif($this->config['sy_withdraw_minmoney']>0 && $this->config['sy_withdraw_minmoney']>$money){
		
		
			$error = '单次提现金额必须达到'.$this->config['sy_withdraw_minmoney'].'元！';
			
		}else{
			$withNum = $this->DB_select_num("member_withdraw","`time`>='".strtotime(date('Ymd'))."'");
			if($withNum>=$this->config['sy_withdraw_num']){

				$error = '今日提现次数已用完，请明天再试！';
			}else{

				$TableNameListTwo=array(1=>'member_statis',2=>'company_statis',3=>'lt_statis');
				
				$memberInfo = $this->DB_select_once('member',"`uid`='".(int)$uid."'");
				
				$TableNameTwo=$TableNameListTwo[$usertype];
				$statis = $this->DB_select_once($TableNameTwo,"`uid`='".(int)$uid."'");
				if($statis['packpay']>0){
					
					if($money>$statis['packpay']){
						
						$error = '提现金额不足！';

					}else{

						if($memberInfo['wxid']!=''){
							$nid = $this->DB_update_all($TableNameTwo,"`packpay`='".($statis['packpay']-$money)."',`freeze`='".($statis['freeze']+$money)."'","`uid`='".(int)$uid."'");

							if($nid){
								$order = $this->setWdOrder($uid,$usertype,$money,$memberInfo['wxid'],$realname);
								
								if($this->config['sy_withdraw_money']>0 && $money>=$this->config['sy_withdraw_money']){

									$error = '超过限定金额，需等待管理员审核通过后打款！';
									
								}else{

									$wxpay = $this->transfersWxPay($order);

									$this->DB_update_all("member_withdraw","`order_state`='".$wxpay['orderState']."',`order_remark`='".$wxpay['remark']."'","`id`='".$order['id']."'");
									if($wxpay['orderState']!='1'){

										$error = '提现失败:'.$wxpay['remark'];

									}else{

										$this->DB_update_all($TableNameTwo,"`freeze`='".($statis['freeze'])."'","`uid`='".(int)$uid."'");
									}
								}
							}else{
								$error = '提现申请失败！';
							}
							
						}else{
							$error = '还未绑定微信账户！';
						}
					}


				}else{
					$error = '暂无可提现金额！';
				}
			}
		}
		
		return  $error;
	}
	function setWdOrder($uid,$usertype,$money,$wxid,$realname){
		
		
		$dingdan=time().rand(10000,99999);
		
		

		$wData['order_id'] = $dingdan;
		$wData['price'] = $money;
		

		if($this->config['sy_withdraw_pound']){

			$poundPrice = round($money*$this->config['sy_withdraw_pound']/100,2);

		}else{
			$poundPrice = 0;
		}
		$wData['real_name'] = $realname;
		$wData['order_price'] = $money-$poundPrice;
		$wData['pound_price'] = $poundPrice;
		$wData['uid'] = (int)$uid;
		$wData['usertype'] = (int)$usertype;
		$wData['order_state'] = $state;
		$wData['wxid'] = $wxid;

		$wData['time'] = time();

		$nid = $this->insert_into("member_withdraw",$wData);
		$wData['id'] = $nid;
		return $wData;

	}
	function transfersWxPay($order){
		

								
		$wxRedPackArr['openid'] = $order['wxid'];
		$wxRedPackArr['amount'] = $order['order_price']*100;
		$wxRedPackArr['partner_trade_no'] = $order['order_id'];
		$wxRedPackArr['spbill_create_ip'] = $this->config['sy_wxredpack_ip'];
		$wxRedPackArr['desc'] = '商家提现';
		$wxRedPackArr['real_name'] = $order['real_name'];
		include(LIB_PATH."ApiWxHb.class.php");
		$wxHb = new ApiWxHb();
		
		$wxHbMsg = $wxHb->sendPay($wxRedPackArr);
		$wxHbMsg = $wxHbMsg;
		if($wxHbMsg['result_code']=='SUCCESS'){

			$return['orderState'] = '1';

		}else{

			if($wxHbMsg['err_code_des']){
				$return['remark'] = $wxHbMsg['err_code_des'];
			}elseif($wxHbMsg['return_msg']){
				$return['remark'] = $wxHbMsg['return_msg'];
			}else{
				$return['remark'] = '微信接口API调用错误';
			}
			$return['orderState'] = '2';
		}
		
		return $return;
	}
	function delWithdrawOrder($id){
	
		$order = $this->DB_select_once("member_withdraw","`id`='".(int)$id."'");

		if(!empty($order)){
			if($order['order_state']!='1'){
				$TableNameList=array(1=>'member_statis',2=>'company_statis',3=>'lt_statis');

				$Table = $TableNameList[$order['usertype']];

				$Statis = $this->DB_select_once($Table,"`uid`='".$order['uid']."'");
				if($Statis['freeze']>$order['price']){
					$freeze = $Statis['freeze']-$order['price'];
				}else{
					$freeze = 0;
				}
				$packpay = $Statis['packpay']+$order['price'];

				$this->DB_update_all($Table,"`freeze`='".$freeze."',`packpay`='".$packpay."'","`uid`='".$order['uid']."'");
				
				
				$this->orderLog($order['price'],$order['uid'],'管理员删除提现，解冻提现金：'.$order['price']);
			}
			$this->DB_delete_all("member_withdraw","`id`='".$order['id']."'" );

			return true;
		}else{
		
			return false;
		}
	}
	function sendMsg($rewardInfo,$usertype,$status){
		if($usertype=='1'){
			$uid = $rewardInfo['comid'];
			$smsg = '您发布的赏金职位招聘进度有新的提醒';
		}else{
			$uid = $rewardInfo['uid'];
			$smsg = '您申请的赏金职位申请进度有新的提醒';
		}
		
		if($uid){
			$memberInfo = $this->DB_select_once("member","`uid`='".(int)$uid."'","`username`,`wxid`,`moblie`");
			$statusMsg = $this->getStatusInfo(0,0,$status);
			$msguser=$this->config["sy_msguser"];
			$msgpw=$this->config["sy_msgpw"];
			$msgkey=$this->config["sy_msgkey"];

			if($memberInfo['moblie'] && $msguser!='' && $msgpw!='' && $msgkey!=''){
				
				$moblie=$memberInfo['moblie'];
				
				$content=$smsg.':'.$statusMsg['nowmsg'].',请在'.$this->config['sy_reward_hour'].'小时内登录'.$this->config['sy_webname'].'作出回应。';
				if($moblie!=""){
				  require_once('notice.model.php');
				  $notice = new notice_model($this->db,$this->def,array('uid'=>$uid,'username'=>$this->username,'usertype'=>$this->usertype));
				  
				  $notice->sendSMS(array('mobile' => $moblie, 'content' => $content,
					'uid' => $memberInfo['uid'], 'name' => $memberInfo['username']
				  ));
				}
			}
			if($memberInfo['wxid']){

				include PLUS_PATH."/user.cache.php";

				$jobInfo  = $this->DB_select_once("company_job","`id`='".(int)$rewardInfo['jobid']."'","`name`");
				$resume = $this->DB_select_once("resume","`uid`='".(int)$rewardInfo['uid']."'","`name`,`edu`,`exp`");
				$uname = mb_substr($resume['name'],0,1,'utf-8').'**';
				$edu = $userclass_name[$resume['edu']];
				
				$exp = $userclass_name[$resume['exp']];
				$rinfo = $uname.'-'.$edu.'学历-'.$exp.'工作经验';
				include(APP_PATH.'app/model/weixin.model.php');
				$wxM = new weixin_model($db,$def,array());

				$wxM->sendWxReward(array('wxid'=>$memberInfo['wxid'],'first'=>$smsg,'jobname'=>'赏金职位：'.$jobInfo['name'],'rinfo'=>$rinfo,'statusinfo'=>$statusMsg['nowmsg'],'remark'=>'请在'.$this->config['sy_reward_hour'].'小时内登录'.$this->config['sy_webname'].'作出回应。'));

			}
		}
	}
}
?>