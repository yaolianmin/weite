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
class compay_model extends model{

	function buyAutoJob($data){
		if($data['jobautoids'] && ($data['rdays'] || $data['crdays'])){

 			$jobautoids=@explode(',',$data['jobautoids']);
			$jobautoids = pylode(',',$jobautoids);
			if($this->config['com_integral_online']==1){
				if(!empty($data['rdkjf'])){
					$dkjf = intval($data['rdkjf']);
				}
			}
			$autodays= intval($data['crdays']);
			if($autodays < 1){
				$autodays= intval($_POST['rdays']);
			}
			$autotype = 1; 

			if($autodays > 0 && $jobautoids){

				$statis = $this->DB_select_once("company_statis","`uid`='".$this->uid."'","`integral`");

				$jobs = $this->DB_select_all("company_job","`uid`='".$this->uid."' and `id` in(".$jobautoids.")","`autotime`,`id`");

				if(empty($jobs)){
					$return['error'] = '请选择正确的刷新职位！';
				}else {
					$jobnum = $this->DB_select_num("company_job","`uid`='".$this->uid."' and `id` in(".$jobautoids.")"); 
						
					$price = $autodays * $this->config['job_auto'] * $jobnum; 
					
					if($dkjf){
						$price = $price - $dkjf / $this->config['integral_proportion'];
					}
					$price = sprintf("%.2f", $price);
					if ($price < 0.01){
						$return['error'] = '购买总金额不得小于0.01元！';
					} else {

						$dingdan=time().rand(10000,99999);
						$orderData['type']='13';
						$orderData['order_id']=$dingdan;
						$orderData['order_price']=$price;
						$orderData['order_time']=time();
						$orderData['order_state']="1";
						$orderData['order_remark']='自动刷新';
						$orderData['uid']=$this->uid;
						$orderData['did']=$this->userdid;
						$orderData['order_info']=serialize(array('jobid'=>$data['jobautoids'],'days'=>$data['crdays']?$data['crdays']:$data['rdays'],'price'=>$price));
						
						if($dkjf){
							if($statis['integral'] >= $dkjf){
								$orderData['order_dkjf']=$dkjf;
								$id=$this->insert_into("company_order",$orderData);
								if($id){
 									require_once('integral.model.php');
									$integral = new integral_model($this->db,$this->def,array('uid'=>$this->uid,'username'=>$this->username,'usertype'=>$this->usertype));
									$integral->company_invtal($this->uid,$dkjf,$auto,"购买自动刷新职位",true,2,'integral',12);
									$orderData['id']=$id;
									$return['order']=$orderData;
								}else{
									$return['error'] = '订单生成失败！';
								}
							}else{
								$return['error'] = '积分不足，请正确输入抵扣积分！';
							}
						}else{
 							$id=$this->insert_into("company_order",$orderData);
							if($id){
								$orderData['id']=$id;
								$return['order']=$orderData;
							}else{
								$return['error'] = '订单生成失败！';
							}
						}
					}
				}
			}else{
				$return['error'] = '请正确选择自动刷新职位以及刷新天数！';
			}

		} else {
			
			$return['error'] = '参数填写错误，请重新设置！';
			
		}

		return $return;
	}
	
	function buyZdJob($data){
		
		if($data['zdjobid'] && ($data['xsdays'] || $data['cxsdays'])){
			
			$jobid = $data['zdjobid'];
			if($this->config['com_integral_online']==1){
				if(!empty($data['xsdkjf'])){
					$dkjf = intval($data['xsdkjf']);
				}
			}
 
 			$xsdays=intval($data['xsdays']);
 			if($xsdays==''&&$data['cxsdays']){
			    $xsdays=intval($data['cxsdays']);
			}
			if($xsdays<1){
				$xsdays=1;
			}
  			if($xsdays > 0 && $jobid){
				$statis = $this->DB_select_once("company_statis","`uid`='".$this->uid."'","`integral`");

				$job = $this->DB_select_once("company_job","`uid`='".$this->uid."' and `id` ='".$jobid."'");

				if(empty($job)){
					
					$return['error'] = '请选择正确的职位置顶！';
					
				}else {
					$price = $xsdays * $this->config['integral_job_top']; 
					if($dkjf){
						$price = $price - $dkjf / $this->config['integral_proportion'];
					}
					$price = sprintf("%.2f", $price);
						
 					if ($price < 0.01){
 						
						$return['error'] = '购买总金额不得小于0.01元！';
						
					} else {

						$dingdan=time().rand(10000,99999);
						$orderData['type']='10';
						$orderData['order_id']=$dingdan;
						$orderData['order_price']=$price;
						$orderData['order_time']=time();
						$orderData['order_state']="1";
						$orderData['order_remark']='置顶服务';
						$orderData['uid']=$this->uid;
						$orderData['did']=$this->userdid;
						$orderData['order_info']=serialize(array('jobid'=>$data['zdjobid'],'days'=>$data['cxsdays']?$data['cxsdays']:$data['xsdays'],'price'=>$price));
						
						if($dkjf){
							if($statis['integral']>=$dkjf){
								$orderData['order_dkjf']=$dkjf;
								$id=$this->insert_into("company_order",$orderData);
								if($id){
 									require_once('integral.model.php');
									$integral = new integral_model($this->db,$this->def,array('uid'=>$this->uid,'username'=>$this->username,'usertype'=>$this->usertype));
									$integral->company_invtal($this->uid,$dkjf,$auto,"购买职位置顶",true,2,'integral',12);
 									
									$orderData['id']=$id;
									$return['order']=$orderData;
								}else{
									$return['error'] = '订单生成失败！';
								}
							}else{
								$return['error'] = '积分不足，请正确输入抵扣积分！';
							}
						}else{
							$id=$this->insert_into("company_order",$orderData);
							if($id){
								$orderData['id']=$id;
								$return['order']=$orderData;
							}else{
								$return['error'] = '订单生成失败！';
							}
						}
					}
				}
				
			}else{
				
				$return['error'] = '请正确选择职位置顶以及置顶的天数！';
				
			}

		} else {
			
			$return['error'] = '参数填写错误，请重新设置！';
			
		}
		return $return;
	}
	
	function buyRecJob($data){

		if($data['recjobid'] && ($data['recdays'] || $data['crecdays'])){
			
 			$jobid = $data['recjobid'];
			if($this->config['com_integral_online']==1){
				if(!empty($data['recdkjf'])){
					$dkjf = intval($data['recdkjf']);
				}
			}
 
 			$recdays=intval($data['recdays']);
 			if($recdays==''&&$data['crecdays']){
			    $recdays=intval($data['crecdays']);
			}
			if($recdays<1){
				$recdays=1;
			}
  			if($recdays > 0 && $jobid){
				$statis = $this->DB_select_once("company_statis","`uid`='".$this->uid."'","`integral`");

				$job = $this->DB_select_once("company_job","`uid`='".$this->uid."' and `id` ='".$jobid."'");

				if(empty($job)){
					$return['error'] = '请选择正确的职位推荐！';
				}else {
					$price = $recdays * $this->config['com_recjob']; 
					if($dkjf){
						$price = $price - $dkjf / $this->config['integral_proportion'];
					}
					$price = sprintf("%.2f", $price);
						
 					if ($price < 0.01){
						$return['error'] = '购买总金额不得小于0.01元！';
					} else {
						
						$dingdan=time().rand(10000,99999);
						$orderData['type']='12';
						$orderData['order_id']=$dingdan;
						$orderData['order_price']=$price;
						$orderData['order_time']=time();
						$orderData['order_state']="1";
						$orderData['order_remark']='职位推荐';
						$orderData['uid']=$this->uid;
						$orderData['did']=$this->userdid;
						$orderData['order_info']=serialize(array('jobid'=>$data['recjobid'],'days'=>$data['crecdays']?$data['crecdays']:$data['recdays'],'price'=>$price));
						
						if($dkjf){
							if($statis['integral']>=$dkjf){
								$orderData['order_dkjf']=$dkjf;
								$id=$this->insert_into("company_order",$orderData);
								if($id){
 									require_once('integral.model.php');
									$integral = new integral_model($this->db,$this->def,array('uid'=>$this->uid,'username'=>$this->username,'usertype'=>$this->usertype));
									$integral->company_invtal($this->uid,$dkjf,$auto,"购买推荐职位",true,2,'integral',12);
 									
									$orderData['id']=$id;
									$return['order']=$orderData;
								}else{
									$return['error'] = '订单生成失败！';
								}
							}else{
								$return['error'] = '积分不足，请正确输入抵扣积分！';
							}
						}else{
							$id=$this->insert_into("company_order",$orderData);
							if($id){
								$orderData['id']=$id;
								$return['order']=$orderData;
							}else{
								$return['error'] = '订单生成失败！';
							}
						}
					}
				}
			}else{
				$return['error'] = '请正确选择职位推荐以及推荐的时长！';
			}

		} else {
			$return['error'] = '参数填写错误，请重新设置！';
		}

		return $return;
	}

	function buyRecPart($data){

		if($data['recpartid'] && ($data['recdays'] || $data['crecdays'])){
			
 			$jobid = $data['recpartid'];
			if($this->config['com_integral_online']==1){
				if(!empty($data['recdkjf'])){
					$dkjf = intval($data['recdkjf']);
				}
			}
 
 			$recdays=intval($data['recdays']);
 			if($recdays==''&&$data['crecdays']){
			    $recdays=intval($data['crecdays']);
			}
			if($recdays<1){
				$recdays=1;
			}
  			if($recdays > 0 && $jobid){
				$statis = $this->DB_select_once("company_statis","`uid`='".$this->uid."'","`integral`");

				$job = $this->DB_select_once("partjob","`uid`='".$this->uid."' and `id` ='".$jobid."'");

				if(empty($job)){
					$return['error'] = '请选择正确的职位推荐！';
				}else {
					$price = $recdays * $this->config['com_recpartjob']; 
					if($dkjf){
						$price = $price - $dkjf / $this->config['integral_proportion'];
					}
					$price = sprintf("%.2f", $price);
						
 					if ($price < 0.01){
						$return['error'] = '购买总金额不得小于0.01元！';
					} else {
						
						$dingdan=time().rand(10000,99999);
						$orderData['type']='24';
						$orderData['order_id']=$dingdan;
						
						$orderData['order_price']=$price;
						$orderData['order_time']=time();
						$orderData['order_state']="1";
						$orderData['order_remark']='兼职推荐';
						$orderData['uid']=$this->uid;
						$orderData['did']=$this->userdid;
						$orderData['order_info']=serialize(array('jobid'=>$data['recpartid'],'days'=>$data['crecdays']?$data['crecdays']:$data['recdays'],'price'=>$price));

						if($dkjf){
							if($statis['integral']>=$dkjf){
								$orderData['order_dkjf']=$dkjf;
								$id=$this->insert_into("company_order",$orderData);
								if($id){
 									require_once('integral.model.php');
									$integral = new integral_model($this->db,$this->def,array('uid'=>$this->uid,'username'=>$this->username,'usertype'=>$this->usertype));
									$integral->company_invtal($this->uid,$dkjf,$auto,"购买推荐兼职",true,2,'integral',12);
 									$orderData['id']=$id;
									$return['order']=$orderData;
								}else{
									$return['error'] = '订单生成失败！';
								}
							}else{
								$return['error'] = '积分不足，请正确输入抵扣积分！';
							}
						}else{
 							$id=$this->insert_into("company_order",$orderData);
							if($id){
								$orderData['id']=$id;
								$return['order']=$orderData;
								
							}else{
								$return['error'] = '订单生成失败！';
							}
						}
 					}
				}
			}else{
				$return['error'] = '请正确选择职位推荐以及推荐的时长！';
			}

		} else {
			$return['error'] = '参数填写错误，请重新设置！';
		}

		return $return;
	}
	
	function buyUrgentJob($data){
		
		if($data['ujobid'] && ($data['udays'] || $data['cudays'])){
			
			$jobid = $data['ujobid'];
			if($this->config['com_integral_online']==1){
				if(!empty($data['urdkjf'])){
					$dkjf = intval($data['urdkjf']);
				}
			}
 
 			$udays=intval($data['udays']);
 			if($udays==''&&$data['cudays']){
			    $udays=intval($data['cudays']);
			}
			if($udays<1){
				$udays=1;
			}
  			if($udays > 0 && $jobid){
				$statis = $this->DB_select_once("company_statis","`uid`='".$this->uid."'","`integral`");

				$job = $this->DB_select_once("company_job","`uid`='".$this->uid."' and `id` ='".$jobid."'");

				if(empty($job)){
					$return['error'] = '请选择正确的职位！';
				}else {
					$price = $udays * $this->config['com_urgent']; 
					if($dkjf){
						$price = $price - $dkjf / $this->config['integral_proportion'];
					}
					$price = sprintf("%.2f", $price);
						
 					if ($price < 0.01){
						$return['error'] = '购买总金额不得小于0.01元！';
					} else {

						$dingdan=time().rand(10000,99999);
						$orderData['type']='11';
						$orderData['order_id']=$dingdan;
						
						$orderData['order_price']=$price;
						$orderData['order_time']=time();
						$orderData['order_state']="1";
						$orderData['order_remark']='紧急招聘';
						$orderData['uid']=$this->uid;
						$orderData['did']=$this->userdid;
						$orderData['order_info']=serialize(array('jobid'=>$data['ujobid'],'days'=>$data['cudays']?$data['cudays']:$data['udays'],'price'=>$price));
						
						if($dkjf){
							if($statis['integral']>=$dkjf){
								$orderData['order_dkjf']=$dkjf;
								$id=$this->insert_into("company_order",$orderData);
								if($id){
 									require_once('integral.model.php');
									$integral = new integral_model($this->db,$this->def,array('uid'=>$this->uid,'username'=>$this->username,'usertype'=>$this->usertype));
									$integral->company_invtal($this->uid,$dkjf,$auto,"购买紧急职位",true,2,'integral',12);
									
									$orderData['id']=$id;
									$return['order']=$orderData;
								}else{
									$return['error'] = '订单生成失败！';
								}
							}else{
								$return['error'] = '积分不足，请正确输入抵扣积分！';
							}
						}else{
 							$id=$this->insert_into("company_order",$orderData);
							if($id){
								$orderData['id']=$id;
								$return['order']=$orderData;
							}else{
								$return['error'] = '订单生成失败！';
							}
						}
					}
				}
			}else{
				$return['error'] = '请正确选择职位以及紧急招聘天数！';
			}

		} else {
			$return['error'] = '参数填写错误，请重新设置！';
		}

		return $return;
	}
	
	
	function buyPackOrder($data){

		if($data['tid']){
			
			$tid = intval($data['tid']);

			if($this->config['com_integral_online']==1){
				if(!empty($data['dkjf'])){
					$dkjf = intval($data['dkjf']);
				}
			}
 
			$service = $this->DB_select_once("company_service_detail","`id` = {$tid}","`service_price`");

			$statis = $this->DB_select_once("company_statis","`uid`='".$this->uid."'","`integral`,`rating`");

			$rating = $this->DB_select_once("company_rating","`id` = {$statis['rating']}","`service_discount`");
			
			if($rating['service_discount']){

 				$discount = intval($rating['service_discount']);
				$price = $service['service_price'] * $discount * 0.01 ;
 			}else{
 
				$price = $service['service_price'];
			}			
			
			if($dkjf){
				$price = $price - $dkjf / $this->config['integral_proportion'];
			}
			

			$price = sprintf("%.2f", $price);
			
			$rating = (int)$data['tid'];

			
			if($price > 0){

				$packinfo = $this->DB_select_once("company_service_detail","`id`='".$data['tid']."'");
				if(empty($packinfo)){
					$return['error'] = '请选择正确的增值套餐！';
				}else {
					$dingdan=time().rand(10000,99999);
					$orderData['type']='5';
					$orderData['order_id']=$dingdan;
					$orderData['order_price']=$price;
					$orderData['order_time']=time();
					$orderData['order_state']="1";
					$orderData['rating']=$rating;
					$orderData['order_remark']='购买增值服务';
					$orderData['uid']=$this->uid;
					$orderData['did']=$this->userdid;

					if($dkjf){
						if($statis['integral']>=$dkjf){
							$orderData['order_dkjf']=$dkjf;
							$id=$this->insert_into("company_order",$orderData);
							if($id){
								require_once('integral.model.php');
								$integral = new integral_model($this->db,$this->def,array('uid'=>$this->uid,'username'=>$this->username,'usertype'=>$this->usertype));
 								$integral->company_invtal($this->uid,$dkjf,$auto,"购买增值包",true,2,'integral',12);
 								$orderData['id']=$id;
								$return['order']=$orderData;
							}else{
								$return['error'] = '订单生成失败！';
							}
						}else{
							$return['error'] = '积分不足，请正确输入抵扣积分！';
						}
					}else{
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
				$return['error'] = '套餐金额出错！';
			}
		}else{

			$return['error'] = '参数错误，请重新选择！';
		}
		return $return;
	}

	function buyRefreshJob($data){
		if($data['sxjobid']){

			$jobid=@explode(',',$data['sxjobid']);
			$jobid = pylode(',',$jobid);
			
			if($this->config['com_integral_online']==1){
				if(!empty($data['sxdkjf'])){
					$dkjf = intval($data['sxdkjf']);
				}
			}
  		 

			if($jobid){
				$statis = $this->DB_select_once("company_statis","`uid`='".$this->uid."'","`integral`,`breakjob_num`");
				$breakjob_num = intval($statis['breakjob_num']);

				$jobs = $this->DB_select_all("company_job","`uid`='".$this->uid."' and `id` in(".$jobid.")","`id`");

				if(empty($jobs)){
					$return['error'] = '请选择正确的职位刷新！';
				}else {
					$jobnum = $this->DB_select_num("company_job","`uid`='".$this->uid."' and `id` in(".$jobid.")"); 
					
					if($breakjob_num){
						$jobnum = $jobnum - $breakjob_num;
					}
						
					$price = $this->config['integral_jobefresh'] * $jobnum; 
					
					if($dkjf){
						$price = $price - $dkjf / $this->config['integral_proportion'];
					}
					$price = sprintf("%.2f", $price);
						
					if ($price < 0.01){
						$return['error'] = '购买总金额不得小于0.01元！';
					} else {

						$dingdan=time().rand(10000,99999);
						$orderData['type']='16';
						$orderData['order_id']=$dingdan;
						
						$orderData['order_price']=$price;
						$orderData['order_time']=time();
						$orderData['order_state']="1";
						$orderData['order_remark']='刷新职位';
						$orderData['uid']=$this->uid;
						$orderData['did']=$this->userdid;
						$orderData['order_info']=serialize(array('jobid'=>$data['sxjobid'],'price'=>$price,'breakjob_num'=>$breakjob_num?$breakjob_num:0));
						

						if($dkjf){
							if($statis['integral']>=$dkjf){
								$orderData['order_dkjf']=$dkjf;
								$id=$this->insert_into("company_order",$orderData);
								if($id){
									require_once('integral.model.php');
									$integral = new integral_model($this->db,$this->def,array('uid'=>$this->uid,'username'=>$this->username,'usertype'=>$this->usertype));
 									$integral->company_invtal($this->uid,$dkjf,$auto,"购买刷新职位",true,2,'integral',12);
 									if($breakjob_num){
										$this->update_once('company_statis',array('breakjob_num'=>'0'),array('uid'=>$this->uid));
									}
									$orderData['id']=$id;
									$return['order']=$orderData;
								}else{
									$return['error'] = '订单生成失败！';
								}
							}else{
								$return['error'] = '积分不足，请正确输入抵扣积分！';
							}
						}else{
 							$id=$this->insert_into("company_order",$orderData);
							if($id){
								if($breakjob_num){
									$this->update_once('company_statis',array('breakjob_num'=>'0'),array('uid'=>$this->uid));
								}
								$orderData['id']=$id;
								$return['order']=$orderData;
							}else{
								$return['error'] = '订单生成失败！';
							}
						}
					}
				}
			}else{
				$return['error'] = '请正确选择职位刷新！';
			}

		} else {
			
			$return['error'] = '参数填写错误，请重新设置！';
			
		}

		return $return;
	}

	function buyRefreshPart($data){
		if($data['sxpartid']){

			$jobid=@explode(',',$data['sxpartid']);
			$jobid = pylode(',',$jobid);

			if($this->config['com_integral_online']==1){
				if(!empty($data['sxpdkjf'])){
					$dkjf = intval($data['sxpdkjf']);
				}
			}
  			 
 			
 
			if($jobid){
				$statis = $this->DB_select_once("company_statis","`uid`='".$this->uid."'","`integral`,`breakpart_num`");
				$breakpart_num = intval($statis['breakpart_num']);

				$parts = $this->DB_select_all("partjob","`uid`='".$this->uid."' and `id` in(".$jobid.")","`id`");

				if(empty($parts)){
					$return['error'] = '请选择正确的职位刷新！';
				}else {
					$partnum = $this->DB_select_num("partjob","`uid`='".$this->uid."' and `id` in(".$jobid.")"); 
					if($breakpart_num){
						$partnum = $partnum - $breakpart_num;
					}
					$price = $this->config['integral_partjobefresh'] * $partnum; 
 
					if($dkjf){
						$price = $price - $dkjf / $this->config['integral_proportion'];
					}
					$price = sprintf("%.2f", $price);
						
					if ($price < 0.01){
						$return['error'] = '购买总金额不得小于0.01元！';
					} else {

						$dingdan=time().rand(10000,99999);
						$orderData['type']='17';
						$orderData['order_id']=$dingdan;
						
						$orderData['order_price']=$price;
						$orderData['order_time']=time();
						$orderData['order_state']="1";
						$orderData['order_remark']='刷新兼职';
						$orderData['uid']=$this->uid;
						$orderData['did']=$this->userdid;
						$orderData['order_info']=serialize(array('jobid'=>$data['sxpartid'],'price'=>$price,'breakpart_num'=>$breakpart_num?$breakpart_num:0));

						if($dkjf){
							if($statis['integral']>=$dkjf){
								$orderData['order_dkjf']=$dkjf;
								$id=$this->insert_into("company_order",$orderData);
								if($id){
									require_once('integral.model.php');
									$integral = new integral_model($this->db,$this->def,array('uid'=>$this->uid,'username'=>$this->username,'usertype'=>$this->usertype));
 									$integral->company_invtal($this->uid,$dkjf,$auto,"购买兼职刷新",true,2,'integral',12);
 									if($breakpart_num){
										$this->update_once('company_statis',array('breakpart_num'=>'0'),array('uid'=>$this->uid));
									}
									$orderData['id']=$id;
									$return['order']=$orderData;
								}else{
									$return['error'] = '订单生成失败！';
								}
							}else{
								$return['error'] = '积分不足，请正确输入抵扣积分！';
							}
						}else{
 							$id=$this->insert_into("company_order",$orderData);
							if($id){
								if($breakpart_num){
									$this->update_once('company_statis',array('breakpart_num'=>'0'),array('uid'=>$this->uid));
								}
								$orderData['id']=$id;
								$return['order']=$orderData;
							}else{
								$return['error'] = '订单生成失败！';
							}
						}
					}
				}
			}else{
				$return['error'] = '请正确选择职位刷新！';
			}

		} else {
			
			$return['error'] = '参数填写错误，请重新设置！';
			
		}

		return $return;
	}

	function buyRefreshLtJob($data){
		if($data['sxltjobid']){

			$jobid=@explode(',',$data['sxltjobid']);
			$jobid = pylode(',',$jobid);

			if($this->config['com_integral_online']==1){
				if(!empty($data['sxltdkjf'])){
					$dkjf = intval($data['sxltdkjf']);
				}
			}
 
			if($data['lt_breakjob_num']){
				$lt_breakjob_num = intval($data['lt_breakjob_num']);
			}

			if($jobid){
				$statis = $this->DB_select_once("company_statis","`uid`='".$this->uid."'","`integral`");

				$ltjobs = $this->DB_select_all("lt_job","`uid`='".$this->uid."' and `id` in(".$jobid.")","`id`");

				if(empty($ltjobs)){
					$return['error'] = '请选择正确的职位刷新！';
				}else {
					$ltjobnum = $this->DB_select_num("lt_job","`uid`='".$this->uid."' and `id` in(".$jobid.")"); 

					$ltjobnum = $ltjobnum - $lt_breakjob_num;
						
					$price = $this->config['integral_lt_jobefresh'] * $ltjobnum; 
 
					if($dkjf){
						$price = $price - $dkjf / $this->config['integral_proportion'];
					}
					$price = sprintf("%.2f", $price);
						
					if ($price < 0.01){
						$return['error'] = '购买总金额不得小于0.01元！';
					} else {

						$dingdan=time().rand(10000,99999);
						$orderData['type']='18';
						$orderData['order_id']=$dingdan;
						
						$orderData['order_price']=$price;
						$orderData['order_time']=time();
						$orderData['order_state']="1";
						$orderData['order_remark']='刷新猎头职位';
						$orderData['uid']=$this->uid;
						$orderData['did']=$this->userdid;
						$orderData['order_info']=serialize(array('jobid'=>$data['sxltjobid'],'price'=>$price,'lt_breakjob_num'=>$lt_breakjob_num?$lt_breakjob_num:0));
						
						if($dkjf){
							if($statis['integral']>=$dkjf){
								$orderData['order_dkjf']=$dkjf;
								$id=$this->insert_into("company_order",$orderData);
								if($id){
									require_once('integral.model.php');
									$integral = new integral_model($this->db,$this->def,array('uid'=>$this->uid,'username'=>$this->username,'usertype'=>$this->usertype));
 									$integral->company_invtal($this->uid,$dkjf,$auto,"购买猎头职位刷新",true,2,'integral',12);
									if($lt_breakjob_num){
										$this->update_once('company_statis',array('lt_breakjob_num'=>'0'),array('uid'=>$this->uid));
									}
									$orderData['id']=$id;
									$return['order']=$orderData;
								}else{
									$return['error'] = '订单生成失败！';
								}
							}else{
								$return['error'] = '积分不足，请正确输入抵扣积分！';
							}
						}else{
							$orderData['order_dkjf']=$dkjf;
							$id=$this->insert_into("company_order",$orderData);
							if($id){
 								if($lt_breakjob_num){
									$this->update_once('company_statis',array('lt_breakjob_num'=>'0'),array('uid'=>$this->uid));
								}
								$orderData['id']=$id;
								$return['order']=$orderData;
							}else{
								$return['error'] = '订单生成失败！';
							}
						}
						
					}
				}
			}else{
				$return['error'] = '请正确选择职位刷新！';
			}

		} else {
			
			$return['error'] = '参数填写错误，请重新设置！';
			
		}

		return $return;
	}

	function buyDownresume($data){
		if($data['eid']){

			$eid = intval($data['eid']);
			
			if($this->config['com_integral_online']==1){
				if(!empty($data['dkjf'])){
					$dkjf = intval($data['dkjf']);
				}
			}
			 
			if($eid){
				$statis = $this->DB_select_once("company_statis","`uid`='".$this->uid."'","`integral`");

 				$resume = $this->DB_select_alls("resume","resume_expect","a.`r_status`<>'2' and a.`uid`=b.`uid` and b.`id`='".$eid."'", "a.name,a.telphone,a.telhome,a.email,a.uid,b.id");

				if(empty($resume)){
					
					$return['error'] = '请选择正确的简历下载';

				}else {
						
					$price = $this->config['integral_down_resume']; 
					
					if($dkjf){
						$price = $price - $dkjf / $this->config['integral_proportion'];
					}
					$price = sprintf("%.2f", $price);
						
					if ($price < 0.01){
						$return['error'] = '购买总金额不得小于0.01元！';
					} else {

						$dingdan=time().rand(10000,99999);
						$orderData['type']='19';
						$orderData['order_id']=$dingdan;
						
						$orderData['order_price']=$price;
						$orderData['order_time']=time();
						$orderData['order_state']="1";
						$orderData['order_remark']='下载简历';
						$orderData['uid']=$this->uid;
						$orderData['did']=$this->userdid;
						$orderData['order_info']=serialize(array('eid'=>$data['eid'],'price'=>$price,'uid'=>$this->uid));

 						if($dkjf){
							if($statis['integral']>=$dkjf){
								$orderData['order_dkjf']=$dkjf;
								$id=$this->insert_into("company_order",$orderData);
								if($id){
									require_once('integral.model.php');
									$integral = new integral_model($this->db,$this->def,array('uid'=>$this->uid,'username'=>$this->username,'usertype'=>$this->usertype));
 									$integral->company_invtal($this->uid,$dkjf,$auto,"购买简历下载",true,2,'integral',12);
 									$orderData['id']=$id;
									$return['order']=$orderData;
								}else{
									$return['error'] = '订单生成失败！';
								}
							}else{
								$return['error'] = '积分不足，请正确输入抵扣积分！';
							}
						}else{
							$orderData['order_dkjf']=$dkjf;
							$id=$this->insert_into("company_order",$orderData);
							if($id){
								$orderData['id']=$id;
								$return['order']=$orderData;
							}else{
								$return['error'] = '订单生成失败！';
							}
						}
					}
				}
			}else{
				$return['error'] = '请正确选择简历下载！';
			}

		} else {
			
			$return['error'] = '参数填写错误，请重新设置！';
			
		}

		return $return;
	}

	function buyIssueJob($data){
		if($data['issuejob']){

			if($this->config['com_integral_online']==1){
				if(!empty($data['issue_dkjf'])){
					$dkjf = intval($data['issue_dkjf']);
				}
			}
					$price = $this->config['integral_job']; 
					
					if($dkjf){
						$price = $price - $dkjf / $this->config['integral_proportion'];
					}
					$price = sprintf("%.2f", $price);

					$statis = $this->DB_select_once("company_statis","`uid`='".$this->uid."'","`integral`");
						
					if ($price < 0.01){
						$return['error'] = '购买总金额不得小于0.01元！';
					} else {

						$dingdan=time().rand(10000,99999);
						$orderData['type']='20';
						$orderData['order_id']=$dingdan;
						
						$orderData['order_price']=$price;
						$orderData['order_time']=time();
						$orderData['order_state']="1";
						$orderData['order_remark']='发布职位';
						$orderData['uid']=$this->uid;
						$orderData['did']=$this->userdid;
 
						if($dkjf){
							if($statis['integral']>=$dkjf){
								$orderData['order_dkjf']=$dkjf;
								$id=$this->insert_into("company_order",$orderData);
								if($id){
									require_once('integral.model.php');
									$integral = new integral_model($this->db,$this->def,array('uid'=>$this->uid,'username'=>$this->username,'usertype'=>$this->usertype));
 									$integral->company_invtal($this->uid,$dkjf,$auto,"购买职位发布",true,2,'integral',12);
 									$orderData['id']=$id;
									$return['order']=$orderData;
								}else{
									$return['error'] = '订单生成失败！';
								}
							}else{
								$return['error'] = '积分不足，请正确输入抵扣积分！';
							}
						}else{
 							$id=$this->insert_into("company_order",$orderData);
							if($id){
								$orderData['id']=$id;
								$return['order']=$orderData;
							}else{
								$return['error'] = '订单生成失败！';
							}
						}
 						
					}
		} else {
			
			$return['error'] = '参数填写错误，请重新设置！';
			
		}

		return $return;
	}

	function buyIssuePart($data){
		if($data['issuepart']){

			if($this->config['com_integral_online']==1){
				if(!empty($data['issuep_dkjf'])){
					$dkjf = intval($data['issuep_dkjf']);
				}
			}
		 
			$statis = $this->DB_select_once("company_statis","`uid`='".$this->uid."'","`integral`");

					$price = $this->config['integral_partjob']; 
					
					if($dkjf){
						$price = $price - $dkjf / $this->config['integral_proportion'];
					}
					$price = sprintf("%.2f", $price);
						
					if ($price < 0.01){
						$return['error'] = '购买总金额不得小于0.01元！';
					} else {

						$dingdan=time().rand(10000,99999);
						$orderData['type']='21';
						$orderData['order_id']=$dingdan;
						
						$orderData['order_price']=$price;
						$orderData['order_time']=time();
						$orderData['order_state']="1";
						$orderData['order_remark']='发布兼职';
						$orderData['uid']=$this->uid;
						$orderData['did']=$this->userdid;
						
						if($dkjf){
							if($statis['integral']>=$dkjf){
								$orderData['order_dkjf']=$dkjf;
								$id=$this->insert_into("company_order",$orderData);
								if($id){
									require_once('integral.model.php');
									$integral = new integral_model($this->db,$this->def,array('uid'=>$this->uid,'username'=>$this->username,'usertype'=>$this->usertype));
 									$integral->company_invtal($this->uid,$dkjf,$auto,"购买兼职发布",true,2,'integral',12);
 									$orderData['id']=$id;
									$return['order']=$orderData;
								}else{
									$return['error'] = '订单生成失败！';
								}
							}else{
								$return['error'] = '积分不足，请正确输入抵扣积分！';
							}
						}else{
							$orderData['order_dkjf']=$dkjf;
							$id=$this->insert_into("company_order",$orderData);
							if($id){
    							$orderData['id']=$id;
								$return['order']=$orderData;
							}else{
								$return['error'] = '订单生成失败！';
							}
						}
					}

		} else {
			
			$return['error'] = '参数填写错误，请重新设置！';
			
		}

		return $return;
	}

	function buyIssueLtJob($data){
		if($data['issueltjob']){

			if($this->config['com_integral_online']==1){
				if(!empty($data['issuelt_dkjf'])){
					$dkjf = intval($data['issuelt_dkjf']);
				}
			}
  			 
			$statis = $this->DB_select_once("company_statis","`uid`='".$this->uid."'","`integral`");

					$price = $this->config['integral_lt_job']; 
					
					if($dkjf){
						$price = $price - $dkjf / $this->config['integral_proportion'];
					}
					$price = sprintf("%.2f", $price);
						
					if ($price < 0.01){
						$return['error'] = '购买总金额不得小于0.01元！';
					} else {

						$dingdan=time().rand(10000,99999);
						$orderData['type']='22';
						$orderData['order_id']=$dingdan;
						
						$orderData['order_price']=$price;
						$orderData['order_time']=time();
						$orderData['order_state']="1";
						$orderData['order_remark']='发布猎头职位';
						$orderData['uid']=$this->uid;
						$orderData['did']=$this->userdid;
						
						if($dkjf){
							if($statis['integral']>=$dkjf){
								$orderData['order_dkjf']=$dkjf;
								$id=$this->insert_into("company_order",$orderData);
								if($id){
									require_once('integral.model.php');
									$integral = new integral_model($this->db,$this->def,array('uid'=>$this->uid,'username'=>$this->username,'usertype'=>$this->usertype));
 									$integral->company_invtal($this->uid,$dkjf,$auto,"购买猎头职位发布",true,2,'integral',12);
 									$orderData['id']=$id;
									$return['order']=$orderData;
								}else{
									$return['error'] = '订单生成失败！';
								}
							}else{
								$return['error'] = '积分不足，请正确输入抵扣积分！';	
							}
						}else{
 							$id=$this->insert_into("company_order",$orderData);
							if($id){
								$orderData['id']=$id;
								$return['order']=$orderData;
							}else{
								$return['error'] = '订单生成失败！';
							}
						}
					}
		} else {
			
			$return['error'] = '参数填写错误，请重新设置！';
			
		}

		return $return;
	}

	function buyInviteResume($data){
 		
		if($data['invite']){

			if($this->config['com_integral_online']==1){
				if(!empty($data['dkjf'])){
					$dkjf = intval($data['dkjf']);
				}else if($data['invite_dkjf']){
					$dkjf = intval($data['invite_dkjf']);
				}
			}
			 
			$statis = $this->DB_select_once("company_statis","`uid`='".$this->uid."'","`integral`");

					$price = $this->config['integral_interview']; 
					
					if($dkjf){
						$price = $price - $dkjf / $this->config['integral_proportion'];
					}
					$price = sprintf("%.2f", $price);
						
					if ($price < 0.01){
						$return['error'] = '购买总金额不得小于0.01元！';
					} else {

						$dingdan=time().rand(10000,99999);
						$orderData['type']='23';
						$orderData['order_id']=$dingdan;
						
						$orderData['order_price']=$price;
						$orderData['order_time']=time();
						$orderData['order_state']="1";
						$orderData['order_remark']='面试邀请';
						$orderData['uid']=$this->uid;
						$orderData['did']=$this->userdid;
 
						if($dkjf){
							if($statis['integral']>=$dkjf){
								$orderData['order_dkjf']=$dkjf;
								$id=$this->insert_into("company_order",$orderData);
								if($id){
									require_once('integral.model.php');
									$integral = new integral_model($this->db,$this->def,array('uid'=>$this->uid,'username'=>$this->username,'usertype'=>$this->usertype));
 									$integral->company_invtal($this->uid,$dkjf,$auto,"购买面试邀请",true,2,'integral',12);
 									$orderData['id']=$id;
									$return['order']=$orderData;
								}else{
									$return['error'] = '订单生成失败！';
								}
							}else{
								$return['error'] = '积分不足，请正确输入抵扣积分！';	
							}
						}else{
							$orderData['order_dkjf']=$dkjf;
							$id=$this->insert_into("company_order",$orderData);
							if($id){
								$orderData['id']=$id;
								$return['order']=$orderData;
							}else{
								$return['error'] = '订单生成失败！';
							}
						}
					}

		} else {
			
			$return['error'] = '参数填写错误，请重新设置！';
			
		}

		return $return;
	}

	function buyLtIssueJob($data){
		if($data['issuejob']){

			if($this->config['com_integral_online']==1){
				if(!empty($data['job_dkjf'])){
					$dkjf = intval($data['job_dkjf']);
				} 
			}
			
			$statis = $this->DB_select_once("lt_statis","`uid`='".$this->uid."'","`integral`");

					$price = $this->config['integral_lt_job']; 
					
					if($dkjf){
						$price = $price - $dkjf / $this->config['integral_proportion'];
					}
					$price = sprintf("%.2f", $price);
						
					if ($price < 0.01){
						$return['error'] = '购买总金额不得小于0.01元！';
					} else {

						$dingdan=time().rand(10000,99999);
						$orderData['type']='22';
						$orderData['order_id']=$dingdan;
						
						$orderData['order_price']=$price;
						$orderData['order_time']=time();
						$orderData['order_state']="1";
						$orderData['order_remark']='发布职位';
						$orderData['uid']=$this->uid;
						$orderData['did']=$this->userdid;
						
						if($dkjf){
							if($statis['integral']>=$dkjf){
								$orderData['order_dkjf']=$dkjf;
								$id=$this->insert_into("company_order",$orderData);
								if($id){
									require_once('integral.model.php');
									$integral = new integral_model($this->db,$this->def,array('uid'=>$this->uid,'username'=>$this->username,'usertype'=>$this->usertype));
 									$integral->company_invtal($this->uid,$dkjf,$auto,"购买职位发布",true,2,'integral',12);
 									$orderData['id']=$id;
									$return['order']=$orderData;
								}else{
									$return['error'] = '订单生成失败！';
								}
							}else{
								$return['error'] = '积分不足，请正确输入抵扣积分！';
							}
						}else{
 							$id=$this->insert_into("company_order",$orderData);
							if($id){
								$orderData['id']=$id;
								$return['order']=$orderData;
							}else{
								$return['error'] = '订单生成失败！';
							}
						}
					}
		} else {
			
			$return['error'] = '参数填写错误，请重新设置！';
			
		}

		return $return;
	}

	function buyLtJobRefresh($data){
		if($data['jobid']){

			$jobid=intval($data['jobid']);

			if($this->config['com_integral_online']==1){
				if(!empty($data['dkjf'])){
					$dkjf = intval($data['dkjf']);
				} 
			}

			if($jobid){
				$statis = $this->DB_select_once("lt_statis","`uid`='".$this->uid."'","`integral`");

				$ltjob = $this->DB_select_once("lt_job","`uid`='".$this->uid."' and `id` ='".$jobid."' ","`id`");

				if(empty($ltjob)){
					
					$return['error'] = '职位参数错误！';

				}else {
 						
					$price = $this->config['integral_lt_jobefresh'] ; 
 
					if($dkjf){
						$price = $price - $dkjf / $this->config['integral_proportion'];
					}
					$price = sprintf("%.2f", $price);
						
					if ($price < 0.01){
						$return['error'] = '购买总金额不得小于0.01元！';
					} else {

						$dingdan=time().rand(10000,99999);
						$orderData['type']='18';
						$orderData['order_id']=$dingdan;
						
						$orderData['order_price']=$price;
						$orderData['order_time']=time();
						$orderData['order_state']="1";
						$orderData['order_remark']='刷新职位';
						$orderData['uid']=$this->uid;
						$orderData['did']=$this->userdid;
						$orderData['order_info']=serialize(array('jobid'=>$data['jobid'],'price'=>$price));
						
						if($dkjf){
							if($statis['integral']>=$dkjf){
								$orderData['order_dkjf']=$dkjf;
								$id=$this->insert_into("company_order",$orderData);
								if($id){
									require_once('integral.model.php');
									$integral = new integral_model($this->db,$this->def,array('uid'=>$this->uid,'username'=>$this->username,'usertype'=>$this->usertype));
 									$integral->company_invtal($this->uid,$dkjf,$auto,"购买职位刷新",true,2,'integral',12);
 									$orderData['id']=$id;
									$return['order']=$orderData;
								}else{
									$return['error'] = '订单生成失败！';
								}
							}else{
								$return['error'] = '积分不足，请正确输入抵扣积分！';
							}
						}else{
 							$id=$this->insert_into("company_order",$orderData);
							if($id){
								$orderData['id']=$id;
								$return['order']=$orderData;
							}else{
								$return['error'] = '订单生成失败！';
							}
						}
					}
				}
			}else{
				$return['error'] = '请正确选择职位刷新！';
			}

		} else {
			
			$return['error'] = '参数填写错误，请重新设置！';
			
		}

		return $return;
	}

	function buyLtDownresume($data){
		if($data['eid']){

			$eid = intval($data['eid']);
			
			if($this->config['com_integral_online']==1){
				if(!empty($data['down_dkjf'])){
					$dkjf = intval($data['down_dkjf']);
				} 
			}
			 
			if($eid){

				$statis = $this->DB_select_once("lt_statis","`uid`='".$this->uid."'","`integral`");

 				$resume = $this->DB_select_alls("resume","resume_expect","a.`r_status`<>'2' and a.`uid`=b.`uid` and b.`height_status`='2' and b.`id`='".$eid."'", "a.name,a.telphone,a.telhome,a.email,a.uid,b.id");

				if(empty($resume)){
					
					$return['error'] = '请选择正确的简历下载';

				}else {
						
					$price = $this->config['integral_lt_downresume']; 
					
					if($dkjf){
						$price = $price - $dkjf / $this->config['integral_proportion'];
					}
					$price = sprintf("%.2f", $price);
						
					if ($price < 0.01){
						$return['error'] = '购买总金额不得小于0.01元！';
					} else {

						$dingdan=time().rand(10000,99999);
						$orderData['type']='19';
						$orderData['order_id']=$dingdan;
						
						$orderData['order_price']=$price;
						$orderData['order_time']=time();
						$orderData['order_state']="1";
						$orderData['order_remark']='下载简历';
						$orderData['uid']=$this->uid;
						$orderData['did']=$this->userdid;
						$orderData['order_info']=serialize(array('eid'=>$data['eid'],'price'=>$price,'uid'=>$this->uid));

 						if($dkjf){
							if($statis['integral']>=$dkjf){
								$orderData['order_dkjf']=$dkjf;
								$id=$this->insert_into("company_order",$orderData);
								if($id){
									require_once('integral.model.php');
									$integral = new integral_model($this->db,$this->def,array('uid'=>$this->uid,'username'=>$this->username,'usertype'=>$this->usertype));
 									$integral->company_invtal($this->uid,$dkjf,$auto,"购买简历下载",true,2,'integral',12);
 									$orderData['id']=$id;
									$return['order']=$orderData;
								}else{
									$return['error'] = '订单生成失败！';
								}
							}else{
								$return['error'] = '积分不足，请正确输入抵扣积分！';
							}
						}else{
							$orderData['order_dkjf']=$dkjf;
							$id=$this->insert_into("company_order",$orderData);
							if($id){
								$orderData['id']=$id;
								$return['order']=$orderData;
							}else{
								$return['error'] = '订单生成失败！';
							}
						}
					}
				}
			}else{
				$return['error'] = '请正确选择简历下载！';
			}

		} else {
			
			$return['error'] = '参数填写错误，请重新设置！';
			
		}

		return $return;
	}
}
?>