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
class qrorder_model extends model{

     function upuser_statis($order){

 		if($order['order_state']!='2'){
			$usertype=$this->DB_select_once("member","`uid`='".$order["uid"]."'","usertype");
			if($usertype['usertype']=='1'){
				$table='member_statis';
			}else if($usertype['usertype']=='2'){
				$table='company_statis';
				$tvalue=",`all_pay`=`all_pay`+'".$order["order_price"]."'";
			}else if($usertype['usertype']=='3'){
				$table='lt_statis';
				$tvalue=",`all_pay`=`all_pay`+'".$order["order_price"]."'";
			}
			if($order['type']=='1' && $order['rating'] && $usertype['usertype']!='1'){
				
				require_once('rating.model.php');
				$rating = new rating_model($this->db,$this->def,array('uid'=>$this->uid,'username'=>$this->username,'usertype'=>$this->usertype));
				if($usertype['usertype']=='2'){
					$value=$rating->rating_info($order['rating'],$order['uid']);
				}else if($usertype['usertype']=='3'){
					$value=$rating->ltrating_info($order['rating'],$order['uid']);
				}
				$status=$this->DB_update_all($table,$value,"`uid`='".$order["uid"]."'");
				$this->DB_update_all("company_job","`rating`='".$order['rating']."'","`uid`='".$order["uid"]."'");
				
			}else if($order['type']=='2'&&$order['integral']){
				$status=$this->DB_update_all($table,"`integral`=`integral`+'".$order['integral']."'".$tvalue,"`uid`='".$order["uid"]."'");
			}else if($order['type']=='3'||$order['type']=='4'){
				$status=$this->DB_update_all($table,"`pay`=`pay`+'".$order["order_price"]."'".$tvalue,"`uid`='".$order["uid"]."'");
			}else if($order['type']=='5'){
				if($usertype['usertype']=='2'){
					$row=$this->DB_select_once("company_service_detail","`id`='".$order['rating']."'");
					$value.="`job_num`=`job_num`+'".$row['job_num']."',";
					$value.="`down_resume`=`down_resume`+'".$row['resume']."',";
					$value.="`invite_resume`=`invite_resume`+'".$row['interview']."',";
					$value.="`breakjob_num`=`breakjob_num`+'".$row['breakjob_num']."',";
					$value.="`part_num`=`part_num`+'".$row['part_num']."',";
					$value.="`breakpart_num`=`breakpart_num`+'".$row['breakpart_num']."',";
					$value.="`all_pay`=`all_pay`+'".$order["order_price"]."',";
					$value.="`lt_job_num`=`lt_job_num`+'".$row['lt_job_num']."',";
					$value.="`lt_down_resume`=`lt_down_resume`+'".$row['lt_resume']."',";
					$value.="`lt_breakjob_num`=`lt_breakjob_num`+'".$row['lt_breakjob_num']."'";
					$status=$this->DB_update_all($table,$value,"`uid`='".$order["uid"]."'");
				}
				if($usertype['usertype']=='3'){
					$row=$this->DB_select_once("lt_service_detail","`id`='".$order['rating']."'");
					$value.="`all_pay`=`all_pay`+'".$order["order_price"]."',";
					$value.="`lt_job_num`=`lt_job_num`+'".$row['lt_job_num']."',";
					$value.="`lt_down_resume`=`lt_down_resume`+'".$row['resume']."',";
					$value.="`lt_breakjob_num`=`lt_breakjob_num`+'".$row['lt_breakjob_num']."'";
					$status=$this->DB_update_all($table,$value,"`uid`='".$order["uid"]."'");
				}
			}else if($order['type']=='6'){

				$status=$this->DB_update_all($table,$tvalue,"`uid`='".$order["uid"]."'");

			}else if($order['type']=='7'){

				$status=$this->DB_update_all("company_statis","`xcx`=1,`xcxetime`='".strtotime("+1 year")."',`xcxstime`='".time()."'","`uid`='".$order["uid"]."'");

			}elseif($order['type']=='8'){

				$order_info = unserialize($order['order_info']);

				if($order_info['jobid']){
					$packjob = $this->DB_select_once("company_job_share","`uid`='".$order['uid']."' AND `jobid`='".$order_info['jobid']."'");

					if(!empty($packjob)){

						if($packjob['packnum']<1){

							$status = $this->DB_update_all("company_job_share","`packnum`='".$order_info['packnum']."',`packprice`='".$order_info['packprice']."'","`id`='".$packjob['id']."'");
							$shareType = 1;

						}elseif($packjob['packmoney']==$order_info['packmoney']){

							$status = $this->DB_update_all("company_job_share","`packnum`=`packnum`+'".$order_info['packnum']."',`packprice`=`packprice`+'".$order_info['packprice']."'","`id`='".$packjob['id']."'");
							$shareType = 1;
						}else{

							$statis = $this->DB_select_once($table,"`uid`='".$order['uid']."'");

							$this->DB_update_all($table,"`packpay`='".($statis['packpay']+$order['order_price'])."'","`uid`='".$order['uid']."'");

							$status = $this->DB_insert_once("company_pay","`order_id`='".$order['order_id']."',`order_price`='".$order['order_price']."',`pay_time`='".time()."',`pay_state`='2',`com_id`='".$order["uid"]."',`pay_remark`='职位已推广，重复支付推广金退还至红包账户',`type`='2',`pay_type`='2',`did`='".$this->config['did']."'");

						}
							
					}else{
						
						$job = $this->DB_select_once('company_job',"`id`='".$order_info['jobid']."'","`status`,`edate`");
						if(!empty($job)){
							$status = $this->DB_insert_once("company_job_share","`uid`='".$order['uid']."',`jobid`='".$order_info['jobid']."',`packnum`='".$order_info['packnum']."',`packmoney`='".$order_info['packmoney']."',`packprice`='".$order_info['packprice']."',`stime`='".time()."'");	
						}
						

						$shareType = 1;

					}
					if($shareType=='1'){
						$this->DB_update_all("company_job","`sharepack`='1'","`id`='".$order_info['jobid']."'");
					}


				}

			}elseif($order['type']=='9'){
				
				$rewardId = $order['rewardid'];
				$reward = $this->DB_select_once("company_job_rewardlist","`id`='".$rewardId."'");
				if(!empty($reward) && $reward['status']=='0'){
					if($reward['sqmoney']>0){
						if($reward['usertype']=='3'){
							$table = 'lt_statis';
						
						}else{
							$table = 'member_statis';
						}
						$statis = $this->DB_select_once($table,"`uid`='".$reward['uid']."'");

						$this->DB_update_all($table,"`packpay`='".($statis['packpay']+$reward['sqmoney'])."'","`uid`='".$reward['uid']."'");

						$nid = $this->DB_insert_once("company_pay","`order_id`='".$order['order_id']."',`order_price`='-".$reward['sqmoney']."',`pay_time`='".time()."',`pay_state`='2',`com_id`='".$reward["comid"]."',`pay_remark`='发放投递赏金',`type`='2',`pay_type`='2',`did`='".$this->config['did']."'");

						$nid = $this->DB_insert_once("company_pay","`order_id`='".$order['order_id']."',`order_price`='".$reward['sqmoney']."',`pay_time`='".time()."',`pay_state`='2',`com_id`='".$reward["uid"]."',`pay_remark`='企业发放投递赏金',`type`='2',`pay_type`='2',`did`='".$this->config['did']."'");
					}
					$this->DB_update_all("company_job_rewardlist","`status`='1'","`id`='".$rewardId."'");
					$logDataValue ="`jobid`='".$reward['jobid']."'"; 
					$logDataValue .=",`rewardid`='".$reward['id']."'"; 
					$logDataValue .=",`eid`='".$reward['eid']."'"; 
					$logDataValue .=",`status`='1'"; 
					$logDataValue .=",`uid`='".$reward['comid']."'"; 
					$logDataValue .=",`utype`='2'"; 
					$logDataValue .=",`ctime`='".time()."'"; 
					$logDataValue .=",`pay`='".$reward['sqmoney']."'"; 

					$status = $this->DB_insert_once("company_job_rewardlog",$logDataValue);
			
				}else{
				
					$statis = $this->DB_select_once($table,"`uid`='".$order['uid']."'");

					$this->DB_update_all($table,"`packpay`='".($statis['packpay']+$order['order_price'])."'","`uid`='".$order['uid']."'");
					
					$status = $this->DB_insert_once("company_pay","`order_id`='".$order['order_id']."',`order_price`='".$order['order_price']."',`pay_time`='".time()."',`pay_state`='2',`com_id`='".$order["uid"]."',`pay_remark`='重复职位赏金退还至红包账户',`type`='2',`pay_type`='2',`did`='".$this->config['did']."'");

				}
			}else if($order['type']=='10'){
				$order_info = unserialize($order['order_info']);
				
				if($order_info['jobid']){
					$xsjob = $this->DB_select_once("company_job","`id`='".$order_info['jobid']."' ","name,xsdate");
					if(!empty($xsjob)){
						if ($xsjob['xsdate'] > time()){
							$xsdate = $xsjob['xsdate']+$order_info['days']*86400;
						}else {
							$xsdate = strtotime("+".$order_info['days']." day");
						}
	 					$status=$this->update_once("company_job",array('xsdate'=>$xsdate),"`uid`='".$order['uid']."' and `id`='".$order_info['jobid']."'");
					}
				}
 			}else if($order['type']=='11'){
				$order_info = unserialize($order['order_info']);
				
				if($order_info['jobid']){
					$ujob = $this->DB_select_once("company_job","`id`='".$order_info['jobid']."' ","`name`,`urgent_time`");
					if(!empty($ujob)){
						if ($ujob['urgent_time'] > time()){
							$urgent_time = $ujob['urgent_time']+$order_info['days']*86400;
						}else {
							$urgent_time = strtotime("+".$order_info['days']." day");
						}
	 					$status=$this->update_once("company_job",array('urgent_time'=>$urgent_time,'urgent'=>'1'),"`uid`='".$order['uid']."' and `id`='".$order_info['jobid']."'");
 					}
				}
 			}else if($order['type']=='12'){
				$order_info = unserialize($order['order_info']);
				
				if($order_info['jobid']){
					$recjob = $this->DB_select_once("company_job","`id`='".$order_info['jobid']."' ","`name`,`rec_time`");
					if(!empty($recjob)){
						if ($recjob['rec_time'] > time()){
							$rec_time = $recjob['rec_time']+$order_info['days']*86400;
						}else {
							$rec_time = time()+$order_info['days']*86400;
						}
	 					$status=$this->update_once("company_job",array('rec_time'=>$rec_time,'rec'=>'1'),"`uid`='".$order['uid']."' and `id`='".$order_info['jobid']."'");
					}
				}
 			}else if($order['type']=='13'){
				
				$order_info = unserialize($order['order_info']);
				
				if($order_info['jobid']){
					
					$autoJob = $this->DB_select_all("company_job","`uid`='".$order['uid']."' AND `id`in (".$order_info['jobid'].") ","`autotime`,`id`");
					if(!empty($autoJob)){
						$lastautotime=0;
						foreach ($autoJob as $v){
	 						if ($v['autotime'] >= time()) {
								$autotime = $v['autotime'] + $order_info['days']*86400;
							} else {
								$autotime = time() + $order_info['days']*86400;
							}
							
							if ($autotime > $lastautotime) {
								$lastautotime = $autotime;
							}
							$this->update_once('company_job',array('autotime'=>$autotime,'autotype'=>'1'),"`uid`='".$order['uid']."' and `id`='".$v['id']."'");
							
						}
						$status=$this->update_once('company_statis',array('autotime'=>$lastautotime),array('uid'=>$order['uid']));
					}
				}
			}else if($order['type']=='14'){
				$order_info = unserialize($order['order_info']);
				
				if($order_info['resumeid']){
					$zdresume = $this->DB_select_once("resume_expect","`id`='".$order_info['resumeid']."' ","`topdate`,`id`");
					if(!empty($zdresume)){
						if ($zdresume['topdate'] > time()){
							$topdate = $zdresume['topdate']+$order_info['days']*86400;
						}else {
							$topdate = time()+$order_info['days']*86400;
						}
	 					$status=$this->update_once("resume_expect",array('topdate'=>$topdate,'top'=>'1'),"`uid`='".$order['uid']."' and `id`='".$order_info['resumeid']."'");
					}
				}
 			}else if($order['type']=='15'){
				$order_info = unserialize($order['order_info']);
				
				if($order_info['resumeid']){
					
					$wtresume = $this->DB_select_once("resume_expect","`id`='".$order_info['resumeid']."' and `is_entrust`='0'","`is_entrust`,`id`,`uid`,`name`");
					$user=$this->DB_select_once("member","`uid`='".$wtresume['uid']."'","`uid`,`username`");
					if(!empty($wtresume)){
						$idata['uid']      = $wtresume['uid'];
						$idata['did']      = $this->userdid;
						$idata['username'] = $user['username'];
						$idata['eid']      = $wtresume['id'];
						$idata['status']   = $this->config['user_trust_status'];
						$idata['price']    = $this->config['pay_trust_resume'];
						$idata['add_time'] = time();
						$nid=$this->insert_into("user_entrust",$idata);
						if($nid){
							if($this->config['user_trust_status']=='1'){
								$status=$this->update_once("resume_expect",array('is_entrust'=>2),array('uid'=>$wtresume['uid'],'id'=>$order_info['resumeid']));
							}else{
								$status=$this->update_once("resume_expect",array('is_entrust'=>1),array('uid'=>$wtresume['uid'],'id'=>$order_info['resumeid']));
							}
						}
					}
 				}
 			}else if($order['type']=='16'){
				
				$order_info = unserialize($order['order_info']);
				
				if($order_info['jobid']){
					
					$sxJob = $this->DB_select_all("company_job","`uid`='".$order['uid']."' AND `id`in (".$order_info['jobid'].") ","`lastupdate`,`id`");
					if(!empty($sxJob)){
 						
						$status=$this->DB_update_all('company_job'," `lastupdate` ='".time()."' "," `id` in(".$order_info['jobid'].") " );
						 
						$this->update_once('company',array('lastupdate'=>time()),array('uid'=>$order['uid']));	
 					}
				}
			}else if($order['type']=='17'){
				
				$order_info = unserialize($order['order_info']);
				
				if($order_info['jobid']){
					
					$sxPart = $this->DB_select_all("partjob","`uid`='".$order['uid']."' AND `id`in (".$order_info['jobid'].") ","`lastupdate`,`id`");
					if(!empty($sxPart)){
 						 
						$status=$this->DB_update_all('partjob'," `lastupdate` ='".time()."' "," `id` in(".$order_info['jobid'].") " );
					 
  					}
				}
			}else if($order['type']=='18'){
				
				$order_info = unserialize($order['order_info']);
				
				if($order_info['jobid']){
					
					$sxltjob = $this->DB_select_all("lt_job","`uid`='".$order['uid']."' AND `id`in (".$order_info['jobid'].") ","`lastupdate`,`id`");
					if(!empty($sxltjob)){
 						 
						$status=$this->DB_update_all('lt_job'," `lastupdate` ='".time()."' "," `id` in(".$order_info['jobid'].") " );
					 
  					}
				}
			}else if($order['type']=='19'){
				
				$order_info = unserialize($order['order_info']);
				
				if($order_info['eid']){

					$eid = intval($order_info['eid']);

					$resume = $this->DB_select_alls("resume","resume_expect","a.`r_status`<>'2' and a.`uid`=b.`uid` and b.`id`='".$eid."'", "a.name,a.telphone,a.telhome,a.email,a.uid,b.id");
					$user=$resume[0];

					$data['eid']=$user['id'];
					$data['uid']=$user['uid'];
					$data['comid']=$this->uid;
					$data['did']=$this->userdid;
					$data['downtime']=time();

					$downresume = $this->DB_select_once("down_resume","`eid`='".$eid."' and `comid`='".$order_info['uid']."'");
					if(empty($downresume)){
						$status = $this->insert_into("down_resume",$data);

						$this->DB_update_all("resume_expect","`dnum`=`dnum`+'1'","`id`='".$eid."'");
 					}
 					 
				}
			}else if($order['type']=='20'){
				
 				if($usertype['usertype']=='2'){
					$status=$this->DB_update_all($table,"`job_num`=`job_num`+'1'","`uid`='".$order["uid"]."'");
				}
					 
 			}else if($order['type']=='21'){
				
 				if($usertype['usertype']=='2'){
					$status=$this->DB_update_all($table,"`part_num`=`part_num`+'1'","`uid`='".$order["uid"]."'");
				}
					 
 			}else if($order['type']=='22'){
				
 				if($usertype['usertype']=='2'){
					$status=$this->DB_update_all($table,"`lt_job_num`=`lt_job_num`+'1'","`uid`='".$order["uid"]."'");
				}
				if($usertype['usertype']=='3'){
					$status=$this->DB_update_all($table,"`lt_job_num`=`lt_job_num`+'1'","`uid`='".$order["uid"]."'");
				}
					 
 			}else if($order['type']=='23'){
				
 				if($usertype['usertype']=='2'){
					$status=$this->DB_update_all($table,"`invite_resume`=`invite_resume`+'1'","`uid`='".$order["uid"]."'");
				}
					 
 			}else if($order['type']=='24'){
				$order_info = unserialize($order['order_info']);
				
				if($order_info['jobid']){
					$recjob = $this->DB_select_once("partjob","`id`='".$order_info['jobid']."' ","`name`,`rec_time`");
					if(!empty($recjob)){
						if ($recjob['rec_time'] > time()){
							$rec_time = $recjob['rec_time']+$order_info['days']*86400;
						}else {
							$rec_time = time()+$order_info['days']*86400;
						}
	 					$status=$this->update_once("partjob",array('rec_time'=>$rec_time),"`uid`='".$order['uid']."' and `id`='".$order_info['jobid']."'");
					}
				}
 			}
			if($status){
				if($this->config['sy_msg_fkcg']=='1'||$this->config['sy_email_fkcg']=='1'){
					$member=$this->DB_select_once("member","`uid`='".$order['uid']."'","`email`,`moblie`,`uid`,`usertype`");
					
 					if($member['usertype']=='1'){
						$fdata=$this->DB_select_once("resume","`uid`='".$member['uid']."'","`name`,`uid`");
					}elseif($member['usertype']=='2'){
						$fdata=$this->DB_select_once("company","`uid`='".$member['uid']."'","`name`,`uid`");
					}elseif($member['usertype']=='3'){
						$fdata=$this->DB_select_once("lt_info","`uid`='".$member['uid']."'","`realname` as `name`,`uid`");
					}
 					$data=array();
					$data["date"]=date("Y-m-d");
					$data["uid"]=$order['uid'];
					$data["name"]=$fdata['name'];
					$data["type"]="fkcg";
					$data["order_id"]=$order['order_id'];
					$data["price"]=$order['order_price'];
					$data['webtel']=$this->config['sy_freewebtel'];
					$data['webname']=$this->config['sy_webname'];
					if($this->config['sy_msg_fkcg']=='1'&&$member['moblie']&&$this->config["sy_msguser"]&&$this->config["sy_msgpw"]&&$this->config["sy_msgkey"]&&$this->config['sy_msg_isopen']=='1'){
						$data["moblie"]=$member["moblie"]; 
					}
					
					if($this->config['sy_email_fkcg']=='1' && $member['email'] && $this->config['sy_email_set']=="1"){
						$data["email"]=$member["email"]; 
					}
 					if($data['email']||$data['moblie']){
						require_once('notice.model.php');
						$notice = new notice_model($this->db,$this->def,array('uid'=>$this->uid,'username'=>$this->username,'usertype'=>$this->usertype));

    					$nid=$notice->sendEmailType($data);
 						$nid=$notice->sendSMSType($data);
					}
				}
				$this->DB_update_all("company_order","`order_state`='2'","`id`='".$order['id']."'");
				if($order['type']=='2'){
 					require_once('integral.model.php');
					$integral = new integral_model($this->db,$this->def,array('uid'=>$this->uid,'username'=>$this->username,'usertype'=>$this->usertype));
					$integral->insert_company_pay($order['integral'],2,$order['uid'],"购买".$this->config['integral_pricename'],1,2,true);
				}
			}
			
  			return $status;
		}
	}
	 
}
?>