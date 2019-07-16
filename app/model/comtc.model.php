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
class comtc_model extends model{


	function invite_resume($data){

		if($data['show_job'] || $data['jobid'] || $data['jobtype']){
			$jobtype=intval($data['jobtype']);
			$show_job=$data['show_job'];
			$jobid=intval($data['jobid']);
		}
		if($this->usertype=='' || $this->uid==''){
				
			$return['status'] = 7;
				
		}else if($this->usertype!='2'){
			
			$return['status'] = 6;

		}else if($this->usertype == '2'){
			
			$member = $this->DB_select_once("member","`uid`='".$this->uid."'","`status`");
			
			if($member['status'] != 1){
			
				$return['status'] = 5;
				return $return;

			}else if($show_job){
 				
				if($jobtype=='2'){
					$company_job=$this->DB_select_all("lt_job"," `uid`='".$this->uid."' and `etime` > '".time()."' and `r_status` <> '2' and `status`='1' and `zp_status` ='0'","`job_name` as `name`,`id`");
				}else{
					$company_job=$this->DB_select_all("company_job","`uid`='".$this->uid."' and `state`='1' and `edate` > '".time()."' and `r_status` <> '2' and `status` <> '1'","`name`,`id`");
				}
					
				if($company_job && is_array($company_job)){

					foreach($company_job as $val){
						if($jobid && $val['id'] == $jobid){
							$jobname=$val['name'];
						}
					}
					$return['jobname']=$jobname;
					
				}else{

					$return['status']=4;

				}
			}
		}

		if($return['status']==''){
 				
			$company_yq=$this->DB_select_once("userid_msg","`fid`='".$this->uid."'");
				
			if(!$company_yq['linkman'] || !$company_yq['linktel'] || !$company_yq['address']){

				$company = $this->DB_select_once("company","`uid`='".$this->uid."'","`linkman`,`linktel`,`linkphone`,`address`");

				if(!$company_yq['linkman']){
					$company_yq['linkman'] = $company['linkman'];
				}
				if(!$company_yq['address']){
					$company_yq['address'] = $company['address'];
				}
				if(!$company_yq['linktel']){
					if($company['linktel']){
						$company_yq['linktel'] = $company['linktel'];
					}else{
						$company_yq['linktel'] = $company['linkphone'];
					}
				}
			}
			$return['linkman']=$company_yq['linkman'];
			$return['linktel']=$company_yq['linktel'];
			$return['content']=$company_yq['content'];
			$return['address']=$company_yq['address'];
			$return['intertime']=$company_yq['intertime'];
			$return['integral']=$this->config['integral_interview'];
			$return['pro']=$this->config['integral_proportion'];
				
	
				
			$row = $this->DB_select_once("company_statis","`uid`='".$this->uid."'","`rating`,`vip_etime`,`invite_resume`,`rating_type`");
				
			if($row['vip_etime'] > time() || $row['vip_etime'] == '0'){

				if($config['integral_interview']=='0' && $row['invite_resume']=='0'){
					$this->DB_update_all("company_statis","`invite_resume`='1'","`uid`='".$this->uid."'");
					$row = $this->DB_select_once("company_statis","`uid`='".$this->uid."'","`rating`,`vip_etime`,`invite_resume`,`rating_type`");

  				}
				
				if($row['rating_type']=="1"){

					if($row['invite_resume'] > 0){

						$return['status']=1;
						
					}else{
						$return['type']=$this->config['com_integral_online'];
						$return['status']=2;
						
					}
					
				}else if($row['rating_type']=='2'){
					
					$return['status']=1;
					
				}else{
 					$return['status']=3;
				}
				
			}else{
				 
				$return['status']=3;
				
			}
		}
		return $return;
	}


	function refresh_job($data){
		
		if($data['jobid']){
			
 			$jobid=@explode(',',$data['jobid']);

			$jobnum = count($jobid);
			
			$jobid = pylode(',',$jobid);

  			$jobs = $this->DB_select_all("company_job","`uid`='".$this->uid."' and `id` in (".$jobid.") ","`id`,`name`");
 			
			if(empty($jobs)){
				$return['msg'] = '职位参数错误！';
			}else{

			
				$statis = $this->DB_select_once("company_statis","`uid`='".$this->uid."'");

			
				if($statis['vip_etime'] > time() || $statis['vip_etime'] == '0' ){

					if($config['integral_jobefresh']=='0' && $statis['breakjob_num']=='0'){
						$this->DB_update_all("company_statis","`breakjob_num`='".$jobnum."'","`uid`='".$this->uid."'");
						$statis = $this->DB_select_once("company_statis","`uid`='".$this->uid."'");
 					}
					
					if($statis['rating_type']=='1'){
						
						if($statis['breakjob_num'] >= $jobnum){
						
							$nid = $this->DB_update_all("company_job","`lastupdate`='".time()."'","`uid`='".$this->uid."' and `id` in(".$jobid.") ");
							
							if($nid){
								$this->DB_update_all("company","`lastupdate`='".time()."'","`uid`='".$this->uid."'");
								$this->DB_update_all("company_statis","`breakjob_num`='".($statis['breakjob_num']-$jobnum)."'","`uid`='".$this->uid."'");
								
								if($jobnum == 1){
									$this->member_log("刷新职位《".$jobs[0]['name']."》",1,4);
								}else{
									$this->member_log("批量刷新职位",1,4);
								}

								$return['status']='1';
								$return['msg']='职位刷新成功';
							}else{
								$return['msg']='职位刷新失败';
							}

						}else{
							
							$return['status']='2';
							$return['msg']='刷新套餐数不足，是否继续刷新？<br>您还可以<a href="index.php?c=right&act=added" style="color:red;">购买增值包</a>！';
						}

					}else if($statis['rating_type']=='2'){
					
						$nid = $this->DB_update_all("company_job","`lastupdate`='".time()."'","`uid`='".$this->uid."' and `id`in (".$jobid.") ");
						if($nid){
							$this->DB_update_all("company","`lastupdate`='".time()."'","`uid`='".$this->uid."'");
							if($jobnum == 1){
								$this->member_log("刷新职位《".$jobs[0]['name']."》",1,4);
							}else{
								$this->member_log("批量刷新职位",1,4);
							}
							$return['status']='1';
							$return['msg']='职位刷新成功';
						}else{
							$return['msg']='职位刷新失败';
						}
				
					}else{
						if($config['integral_jobefresh']=='0'){
							$nid = $this->DB_update_all("company_job","`lastupdate`='".time()."'","`uid`='".$this->uid."' and `id`in (".$jobid.") ");
							if($nid){
								$this->DB_update_all("company","`lastupdate`='".time()."'","`uid`='".$this->uid."'");
								if($jobnum == 1){
									$this->member_log("刷新职位《".$jobs[0]['name']."》",1,4);
								}else{
									$this->member_log("批量刷新职位",1,4);
								}
								$return['status']='1';
								$return['msg']='职位刷新成功';
							}else{
								$return['msg']='职位刷新失败';
							}
						}else{
							$return['status']='2';
							$return['msg']='会员已到期，是否继续刷新？<br>您还可以<a href="index.php?c=right" style="color:red;">购买特权</a>！';
						}
					}

				}else{
					
					if($config['integral_jobefresh']=='0'){
						
						$nid = $this->DB_update_all("company_job","`lastupdate`='".time()."'","`uid`='".$this->uid."' and `id`in (".$jobid.") ");
					
						if($nid){
							
							$this->DB_update_all("company","`lastupdate`='".time()."'","`uid`='".$this->uid."'");
						
							if($jobnum == 1){
						
								$this->member_log("刷新职位《".$jobs[0]['name']."》",1,4);
							
							}else{
							
								$this->member_log("批量刷新职位",1,4);
							
							}
						
							$return['status']='1';
							$return['msg']='职位刷新成功';

						}else{
						
							$return['msg']='职位刷新失败';
						}

					}else{

						$return['status']='2';
						$return['msg']='会员已到期，是否继续刷新？<br>您还可以<a href="index.php?c=right" style="color:red;">购买特权</a>！';

					}

				}
				
			}
			
		}else{
		
			$return['msg'] = '请先选择职位！';
		}
		
		return $return;
		
	}


	function refresh_part($data){

		if($data['partid']){

 			$partid=@explode(',',$data['partid']);

			$partnum = count($partid);
  			
			$partid = pylode(',',$partid);

  			$parts = $this->DB_select_all("partjob","`uid`='".$this->uid."' and `id` in (".$partid.") ","`id`,`name`");

 			if(empty($parts)){

				$return['msg'] = '职位参数错误！';

			}else{

				
				$statis = $this->DB_select_once("company_statis","`uid`='".$this->uid."'");
 
			
				if($statis['vip_etime'] > time() || $statis['vip_etime'] == '0'){

					if($config['integral_partjobefresh']=='0' && $statis['breakpart_num']=='0'){
						$this->DB_update_all("company_statis","`breakpart_num`='".$partnum."'","`uid`='".$this->uid."'");
						$statis = $this->DB_select_once("company_statis","`uid`='".$this->uid."'");
 					}
					
					if($statis['rating_type']=='1'){
						
						if($statis['breakpart_num'] >= $partnum){

							$nid = $this->DB_update_all("partjob","`lastupdate`='".time()."'","`uid`='".$this->uid."' and `id` in(".$partid.") ");
							
							if($nid){
 								$this->DB_update_all("company_statis","`breakpart_num`='".($statis['breakpart_num']-$partnum)."'","`uid`='".$this->uid."'");
								
								if($partnum == 1){
 									$this->member_log("刷新兼职《".$parts[0]['name']."》",9,4);
								}else{
									$this->member_log("批量刷新兼职",9,4);
								}

								$return['status']='1';
								$return['msg']='兼职刷新成功';
							}else{
								$return['msg']='兼职刷新失败';
							}

						}else{
							
							$return['status']='2';
							$return['msg']='刷新套餐数不足，是否继续刷新？<br>您还可以<a href="index.php?c=right&act=added" style="color:red;">购买增值包</a>！';
						}

					}else if($statis['rating_type']=='2'){
					
						$nid = $this->DB_update_all("partjob","`lastupdate`='".time()."'","`uid`='".$this->uid."' and `id`in (".$partid.") ");
						if($nid){
							if($partnum == 1){
 								$this->member_log("刷新兼职《".$parts[0]['name']."》",9,4);
							}else{
								$this->member_log("批量刷新兼职",9,4);
							}
							$return['status']='1';
							$return['msg']='兼职刷新成功';
						}else{
							$return['msg']='兼职刷新失败';
						}
				
					}else{
						if($config['integral_partjobefresh']=='0'){
							
							$nid = $this->DB_update_all("partjob","`lastupdate`='".time()."'","`uid`='".$this->uid."' and `id`in (".$partid.") ");
							if($nid){
								if($partnum == 1){
									$this->member_log("刷新兼职《".$parts[0]['name']."》",9,4);
								}else{
									$this->member_log("批量刷新兼职",9,4);
								}
								$return['status']='1';
								$return['msg']='兼职刷新成功';
							}else{
								$return['msg']='兼职刷新失败';
							}
						
						}else{
							$return['status']='2';
							$return['msg']='会员已到期，是否继续刷新？<br>您还可以<a href="index.php?c=right" style="color:red;">购买特权</a>！';
						}
					}

				}else{
					
					if($config['integral_partjobefresh']=='0'){
							
						$nid = $this->DB_update_all("partjob","`lastupdate`='".time()."'","`uid`='".$this->uid."' and `id`in (".$partid.") ");
						if($nid){
							if($partnum == 1){
								$this->member_log("刷新兼职《".$parts[0]['name']."》",9,4);
							}else{
								$this->member_log("批量刷新兼职",9,4);
							}
							$return['status']='1';
							$return['msg']='兼职刷新成功';
						}else{
							$return['msg']='兼职刷新失败';
						}
						
					}else{
						$return['status']='2';
						$return['msg']='会员已到期，是否继续刷新？<br>您还可以<a href="index.php?c=right" style="color:red;">购买特权</a>！';
					}

				}
				
			}
			
		}else{
		
			$return['msg'] = '请正确选择职位刷新！';
		}

		return $return;
		
	}


	function refresh_ltjob($data){

		if($data['ltjobid']){
			
			
 			$jobid=@explode(',',$data['ltjobid']);

			$jobnum = count($jobid);
			
			$jobid = pylode(',',$jobid);
			

  			$ltjobs = $this->DB_select_all("lt_job","`uid`='".$this->uid."' and `id` in (".$jobid.") ","`id`,`job_name`");

			if(empty($ltjobs)){

				$return['msg'] = '职位参数错误2！';

			}else{

			
				$statis = $this->DB_select_once("company_statis","`uid`='".$this->uid."'");

			
				if($statis['vip_etime'] > time() || $statis['vip_etime'] == '0'){

					if($config['integral_lt_jobefresh']=='0' && $statis['lt_breakjob_num']=='0'){
						$this->DB_update_all("company_statis","`lt_breakjob_num`='".$jobnum."'","`uid`='".$this->uid."'");
						$statis = $this->DB_select_once("company_statis","`uid`='".$this->uid."'");
 					}
					
					if($statis['rating_type']=='1'){
						
						if($statis['lt_breakjob_num'] >= $jobnum){
						
							$nid = $this->DB_update_all("lt_job","`lastupdate`='".time()."'","`uid`='".$this->uid."' and `id` in(".$jobid.") ");
							
							if($nid){
								$this->DB_update_all("company","`jobtime`='".time()."'","`uid`='".$this->uid."'");
								$this->DB_update_all("company_statis","`lt_breakjob_num`='".($statis['lt_breakjob_num']-$jobnum)."'","`uid`='".$this->uid."'");
								
								if($jobnum == 1){
									$this->member_log("刷新猎头职位《".$ltjobs[0]['job_name']."》",10,4);
								}else{
									$this->member_log("批量刷新猎头职位",10,4);
								}

								$return['status']='1';
								$return['msg']='猎头职位刷新成功';
							}else{
								$return['msg']='猎头职位刷新失败';
							}

						}else{
							
							$return['status']='2';
							$return['msg']='刷新套餐数不足，是否继续刷新？<br>您还可以<a href="index.php?c=right&act=added" style="color:red;">购买增值包</a>！';
						}

					}else if($statis['rating_type']=='2'){
					
						$nid = $this->DB_update_all("lt_job","`lastupdate`='".time()."'","`uid`='".$this->uid."' and `id`in (".$jobid.") ");
						if($nid){
							$this->DB_update_all("company","`jobtime`='".time()."'","`uid`='".$this->uid."'");
							if($jobnum == 1){
								$this->member_log("刷新猎头职位《".$ltjobs[0]['job_name']."》",10,4);
							}else{
								$this->member_log("批量刷新猎头职位",10,4);
							}
							$return['status']='1';
							$return['msg']='猎头职位刷新成功';
						}else{
							$return['msg']='猎头职位刷新失败';
						}
				
					}else{
						if($config['integral_lt_jobefresh']=='0'){
							$nid = $this->DB_update_all("lt_job","`lastupdate`='".time()."'","`uid`='".$this->uid."' and `id`in (".$jobid.") ");
							if($nid){
								$this->DB_update_all("company","`jobtime`='".time()."'","`uid`='".$this->uid."'");
								if($jobnum == 1){
									$this->member_log("刷新猎头职位《".$ltjobs[0]['job_name']."》",10,4);
								}else{
									$this->member_log("批量刷新猎头职位",10,4);
								}
								$return['status']='1';
								$return['msg']='猎头职位刷新成功';
							}else{
								$return['msg']='猎头职位刷新失败';
							}
						}else{
							$return['status']='2';
							$return['msg']='会员已到期，是否继续刷新？<br>您还可以<a href="index.php?c=right" style="color:red;">购买特权</a>！';
						}
						
					}

				}else{
					
						if($config['integral_lt_jobefresh']=='0'){
							$nid = $this->DB_update_all("lt_job","`lastupdate`='".time()."'","`uid`='".$this->uid."' and `id`in (".$jobid.") ");
							if($nid){
								$this->DB_update_all("company","`jobtime`='".time()."'","`uid`='".$this->uid."'");
								if($jobnum == 1){
									$this->member_log("刷新猎头职位《".$ltjobs[0]['job_name']."》",10,4);
								}else{
									$this->member_log("批量刷新猎头职位",10,4);
								}
								$return['status']='1';
								$return['msg']='猎头职位刷新成功';
							}else{
								$return['msg']='猎头职位刷新失败';
							}
						}else{
							$return['status']='2';
							$return['msg']='会员已到期，是否继续刷新？<br>您还可以<a href="index.php?c=right" style="color:red;">购买特权</a>！';
						}

				}
				
			}
			
		}else{
			
			$return['msg'] = '请先选择职位！';
		}

		return $return;
		
	}

	function ltRefreshJob($data){

		if($data['jobid']){
			
			$jobid = intval($data['jobid']);

  			$job = $this->DB_select_once("lt_job","`uid`='".$this->uid."' and `id` ='".$jobid."' ","`id`,`job_name`");
 			
 			if(empty($job)){
				$return['msg'] = '职位参数错误！';
			}else{
				
				$statis = $this->DB_select_once("lt_statis","`uid`='".$this->uid."'");
			
				if($statis['vip_etime'] > time() || $statis['vip_etime'] =='0'){
					
					if($statis['rating_type']=='1'){
						
						if($statis['lt_breakjob_num'] > 0){
						
							$nid = $this->DB_update_all("lt_job","`lastupdate`='".time()."'","`uid`='".$this->uid."' and `id` ='".$jobid."' ");
							
							if($nid){
 								$this->DB_update_all("lt_statis","`lt_breakjob_num`='".($statis['lt_breakjob_num']-1)."'","`uid`='".$this->uid."'");
								
								$this->member_log("刷新职位《".$job['job_name']."》",1,4);
								$return['status']='1';
								$return['msg']='职位刷新成功';
							}else{
								$return['msg']='职位刷新失败';
							}

						}else{
							$return['status']='2';
							$return['msg']='刷新套餐数不足，是否继续刷新？<br>您还可以<a href="index.php?c=right&act=added" style="color:red;">购买增值包</a>！';
						}

					}else if($statis['rating_type']=='2'){
					
						$nid = $this->DB_update_all("lt_job","`lastupdate`='".time()."'","`uid`='".$this->uid."' and `id`in (".$jobid.") ");
						if($nid){
							$this->member_log("刷新职位《".$job[0]['job_name']."》",1,4);
							$return['status']='1';
							$return['msg']='职位刷新成功';
						}else{
							$return['msg']='职位刷新失败';
						}
				
					}else{
						$return['status']='2';
						$return['msg']='会员已到期，是否继续刷新？<br>您还可以<a href="index.php?c=right" style="color:red;">购买会员</a>！';
					}

				}else{ 
					
					$return['status']='2';
					$return['msg']='会员已到期，是否继续刷新？<br>您还可以<a href="index.php?c=right" style="color:red;">购买会员</a>！';

				}
			}
			
		}else{
 			$return['msg'] = '请先选择职位！';
		}

		return $return;
		
	}
}
?>