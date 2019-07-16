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
class ApiPay extends common{
	function payAll($dingdan,$total_fee,$paytype=''){

		
		if(!preg_match('/^[0-9]+$/', $dingdan)){

			die;
		}

		$order=$this->obj->DB_select_once("company_order","`order_id`='$dingdan'");


		if($order['order_state']!='2' && $order['id']){

			$member=$this->obj->DB_select_once("member","`uid`='".$order['uid']."'","`usertype`,`username`,`wxid`");

			if($member['usertype']=='1'){
				$table='member_statis';
				$marr=$this->obj->DB_select_once("resume","`uid`='".$order['uid']."'","`name`,`email`,`telphone`as `moblie`");
			}else if($member['usertype']=='2'){
				$table='company_statis';
				$tvalue=",`all_pay`=`all_pay`+'".$order["order_price"]."'";
				$marr=$this->obj->DB_select_once("company","`uid`='".$order['uid']."'","`name`,`linkmail` as `email`,`linktel` as `moblie`");
			}else if($member['usertype']=='3'){
				$table='lt_statis';
				$tvalue=",`all_pay`=`all_pay`+'".$order["order_price"]."'";
				$marr=$this->obj->DB_select_once("lt_info","`uid`='".$order['uid']."'","`realname` as `name`,`email`,`moblie`");
			}

			$emaildata['type']="recharge";
			$emaildata['username']=$member['username'];
			$emaildata['name']=$marr['name'];
			$emaildata['price']=$order['order_price'];
			$emaildata['time']=date("Y-m-d H:i:s");
			$emaildata['email']=$marr['email'];
			$emaildata['moblie']=$marr['moblie'];

			$sendInfo['wxid'] = $member['wxid'];
			$sendInfo['first'] = '您有一笔订单支付成功！';
			$sendInfo['username'] = $member['username'];
			$sendInfo['order'] = $order['order_id'];
			$sendInfo['price'] = $order['order_price'];
			$sendInfo['time'] = date('Y-m-d H:i:s');
			switch($paytype){
					
				case 'alipay':$sendInfo['paytype']='支付宝';
				break;
				case 'wxpay':$sendInfo['paytype']='微信支付';
				break;
				case 'wapalipay':$sendInfo['paytype']='支付宝手机支付';
				break;
				case 'tenpay':$sendInfo['paytype']='财付通';
				break;
				default :$sendInfo['paytype']='其他支付方式';

				break;

			}

			if($order['type']=='1' && $order['rating'] && $member['usertype']!='1'){


				$row=$this->obj->DB_select_once("company_rating","`id`='".$order['rating']."'");
				$sendInfo['info'] = '购买：'.$row['name'];

				$ratingM = $this->MODEL('rating');
				if($member['usertype']=='2'){
					$value=$ratingM->rating_info($order['rating'],$order['uid']);
				}else if($member['usertype']=='3'){
					$value=$ratingM->ltrating_info($order['rating'],$order['uid']);
				}
				
				$nid=$this->obj->DB_update_all($table,$value,"`uid`='".$order['uid']."'");

				$sendMail = 1;
			}else if($order['type']=='2' && $order['integral']){
				$nid=$this->obj->DB_update_all($table,"`integral`=`integral`+'".$order['integral']."'".$tvalue,"`uid`='".$order['uid']."'");
				if($nid){
					$this->obj->DB_insert_once("company_pay","`order_id`='".$order['order_id']."',`order_price`='".$order['integral']."',`pay_time`='".time()."',`pay_state`='2',`com_id`='".$order["uid"]."',`pay_remark`='"."购买".$this->config['integral_pricename']."',`type`='1',`pay_type`='2',`did`='".$this->config['did']."'");
					$sendMail = 1;
				}
				
				$sendInfo['info'] = '充值'.$this->config['integral_pricename'].'：'.$order['integral'];

			}else if($order['type']=='5'){
				$value='';
				if($member['usertype']=='2'){
					$row=$this->obj->DB_select_once("company_service_detail","`id`='".$order['rating']."'");
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
				}elseif($member['usertype']=='3'){
					$value.="`lt_job_num`=`lt_job_num`+'".$row['lt_job_num']."',";
					$value.="`lt_down_resume`=`lt_down_resume`+'".$row['lt_resume']."',";
					$value.="`lt_breakjob_num`=`lt_breakjob_num`+'".$row['lt_breakjob_num']."'";
				}

				$nid=$this->obj->DB_update_all($table,$value,"`uid`='".$order['uid']."'");
				$sendMail = 1;
				
				$sendInfo['info'] = '购买增值包：'.$row['name'];
			}elseif($order['type']=='8'){

				$order_info = unserialize($order['order_info']);

				if($order_info['jobid']){
					
					$packjob = $this->obj->DB_select_once("company_job_share","`uid`='".$order['uid']."' AND `jobid`='".$order_info['jobid']."'");

					
					if(!empty($packjob)){

						if($packjob['packnum']<1){

							$nid = $this->obj->DB_update_all("company_job_share","`packnum`='".$order_info['packnum']."',`packprice`='".$order_info['packprice']."'","`id`='".$packjob['id']."'");
							$shareType = 1;

						}elseif($packjob['packmoney']==$order_info['packmoney']){

							$nid = $this->obj->DB_update_all("company_job_share","`packnum`=`packnum`+'".$order_info['packnum']."',`packprice`=`packprice`+'".$order_info['packprice']."'","`id`='".$packjob['id']."'");
							$shareType = 1;
						}else{

							
							$statis = $this->obj->DB_select_once($table,"`uid`='".$order['uid']."'");

							$this->obj->DB_update_all($table,"`packpay`='".($statis['packpay']+$order['order_price'])."'","`uid`='".$order['uid']."'");

							$nid = $this->obj->DB_insert_once("company_pay","`order_id`='".$order['order_id']."',`order_price`='".$order['order_price']."',`pay_time`='".time()."',`pay_state`='2',`com_id`='".$order["uid"]."',`pay_remark`='职位已推广，重复支付推广金退还至红包账户',`type`='2',`pay_type`='2',`did`='".$this->config['did']."'");

						}
							
					}else{

						$job = $this->obj->DB_select_once('company_job',"`id`='".$order_info['jobid']."'","`status`,`edate`");
						if(!empty($job)){
							$nid = $this->obj->DB_insert_once("company_job_share","`uid`='".$order['uid']."',`jobid`='".$order_info['jobid']."',`packnum`='".$order_info['packnum']."',`packmoney`='".$order_info['packmoney']."',`packprice`='".$order_info['packprice']."',`stime`='".time()."'");	
						}else{
							$nid = $order_info['jobid'];
						}
						

						$shareType = 1;

					}
					
					if($shareType=='1'){
						$this->obj->DB_update_all("company_job","`sharepack`='1'","`id`='".$order_info['jobid']."'");
					}


				}

				$sendInfo['info'] = '分享推广红包';

			}else if($order['type']=='9'){
				
				$rewardId = $order['rewardid'];
				$reward = $this->obj->DB_select_once("company_job_rewardlist","`id`='".$rewardId."'");
				if(!empty($reward) && $reward['status']=='0'){
					
					if($reward['sqmoney']>0){
						if($reward['usertype']=='3'){
							$table = 'lt_statis';
						
						}else{
							$table = 'member_statis';
						}
						$statis = $this->obj->DB_select_once($table,"`uid`='".$reward['uid']."'");

						$this->obj->DB_update_all($table,"`packpay`='".($statis['packpay']+$reward['sqmoney'])."'","`uid`='".$reward['uid']."'");

						$nid = $this->obj->DB_insert_once("company_pay","`order_id`='".$order['order_id']."',`order_price`='-".$reward['sqmoney']."',`pay_time`='".time()."',`pay_state`='2',`com_id`='".$reward["comid"]."',`pay_remark`='发放投递赏金',`type`='2',`pay_type`='2',`did`='".$this->config['did']."'");

						$nid = $this->obj->DB_insert_once("company_pay","`order_id`='".$order['order_id']."',`order_price`='".$reward['sqmoney']."',`pay_time`='".time()."',`pay_state`='2',`com_id`='".$reward["uid"]."',`pay_remark`='企业发放投递赏金',`type`='2',`pay_type`='2',`did`='".$this->config['did']."'");
					}
					
					$this->obj->DB_update_all("company_job_rewardlist","`status`='1'","`id`='".$rewardId."'");
					
					$logDataValue ="`jobid`='".$reward['jobid']."'"; 
					$logDataValue .=",`rewardid`='".$reward['id']."'"; 
					$logDataValue .=",`eid`='".$reward['eid']."'"; 
					$logDataValue .=",`status`='1'"; 
					$logDataValue .=",`uid`='".$reward['comid']."'"; 
					$logDataValue .=",`utype`='2'"; 
					$logDataValue .=",`ctime`='".time()."'"; 
					$logDataValue .=",`pay`='".$reward['sqmoney']."'"; 

					$nid = $this->obj->DB_insert_once("company_job_rewardlog",$logDataValue);
			


				}else{
				
					
					$statis = $this->obj->DB_select_once($table,"`uid`='".$order['uid']."'");

					$this->obj->DB_update_all($table,"`packpay`='".($statis['packpay']+$order['order_price'])."'","`uid`='".$order['uid']."'");
					
					$nid = $this->obj->DB_insert_once("company_pay","`order_id`='".$order['order_id']."',`order_price`='".$order['order_price']."',`pay_time`='".time()."',`pay_state`='2',`com_id`='".$order["uid"]."',`pay_remark`='重复职位赏金退还至红包账户',`type`='2',`pay_type`='2',`did`='".$this->config['did']."'");

				}


				$sendMail = 1;
				
				$sendInfo['info'] = '悬赏红包';

			}else if($order['type']=='10'){
				
				$order_info = unserialize($order['order_info']);

				if($order_info['jobid']){
				
					$xsJob = $this->obj->DB_select_once("company_job","`uid`='".$order['uid']."' AND `id` = '".$order_info['jobid']."'","`xsdate`,`id`");
					if(!empty($xsJob)){
						if ($xsjob['xsdate'] > time()){
							$xsdate = $xsjob['xsdate']+$order_info['days']*86400;
						}else {
							$xsdate = strtotime("+".$order_info['days']." day");
						}
						$nid=$this->obj->update_once("company_job",array('xsdate'=>$xsdate),"`uid`='".$order['uid']."' and `id`='".$order_info['jobid']."'");
	 					$this->obj->member_log("购买职位置顶");
					}
 				}

				$sendInfo['info'] = '职位置顶';
			}
			else if($order['type']=='11'){
				
				$order_info = unserialize($order['order_info']);

				if($order_info['jobid']){
					
					$uJob = $this->obj->DB_select_once("company_job","`uid`='".$order['uid']."' AND `id` = '".$order_info['jobid']."'","`urgent_time`,`id`");
					if(!empty($uJob)){
						if ($uJob ['urgent_time'] > time()){
							$urgent_time = $uJob['urgent_time']+$order_info['days']*86400;
						}else {
							$urgent_time = time() + $order_info['days'] * 86400;
						}
						$nid=$this->obj->update_once("company_job",array('urgent_time'=>$urgent_time,'urgent'=>'1'),"`uid`='".$order['uid']."' and `id`='".$order_info['jobid']."'");
	 					$this->obj->member_log("购买紧急招聘");
					}
 				}
				
 				$sendInfo['info'] = '职位紧急';
				
			}else if($order['type']=='12'){
				
			
				$order_info = unserialize($order['order_info']);

				if($order_info['jobid']){
					
					$recJob = $this->obj->DB_select_once("company_job","`uid`='".$order['uid']."' AND `id` = '".$order_info['jobid']."'","`rec_time`,`id`");
					if(!empty($recJob)){
						if ($recJob ['rec_time'] > time()){
							$rec_time = $recJob['rec_time']+$order_info['days']*86400;
						}else {
							$rec_time = time() + $order_info['days'] * 86400;
						}
						$nid=$this->obj->update_once("company_job",array('rec_time'=>$rec_time,'rec'=>'1'),"`uid`='".$order['uid']."' and `id`='".$order_info['jobid']."'");
	 					$this->obj->member_log("购买职位推荐");
					}
 				}
 				
				$sendInfo['info'] = '职位推荐';
			}else if($order['type']=='13'){
				
				$order_info = unserialize($order['order_info']);

				if($order_info['jobid']){
					
					$autoJob = $this->obj->DB_select_all("company_job","`uid`='".$order['uid']."' AND `id`in (".$order_info['jobid'].")","`autotime`,`id`");
						
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
							
							$nid=$this->obj->update_once('company_job',array('autotime'=>$autotime,'autotype'=>'1'),"`uid`='".$order['uid']."' and `id`='".$v['id']."'");
						
						}
						
						$this->obj->update_once('company_statis',array('autotime'=>$lastautotime),array('uid'=>$order['uid']));
						$this->obj->member_log("购买职位自动刷新功能");
					}
 				}

				$sendInfo['info'] = '职位刷新';
			}else if($order['type']=='14'){
				
				
				$order_info = unserialize($order['order_info']);

				if($order_info['resumeid']){
				
					$zdResume = $this->obj->DB_select_once("resume_expect","`uid`='".$order['uid']."' AND `id` = '".$order_info['resumeid']."'","`topdate`,`id`");
					if(!empty($zdResume)){
						if ($zdResume ['topdate'] > time()){
							$topdate = $zdResume['topdate']+$order_info['days']*86400;
						}else {
							$topdate = time() + $order_info['days'] * 86400;
						}
						$nid=$this->obj->update_once("resume_expect",array('topdate'=>$topdate,'top'=>'1'),"`uid`='".$order['uid']."' and `id`='".$order_info['resumeid']."'");
	 					$this->obj->member_log("购买简历置顶");
					}
					
 				}
 				
				$sendInfo['info'] = '简历置顶';
			}else if($order['type']=='15'){
				
			
				$order_info = unserialize($order['order_info']);

				if($order_info['resumeid']){
				
					$wtResume = $this->obj->DB_select_once("resume_expect","`uid`='".$order['uid']."' AND `id` = '".$order_info['resumeid']."' and `is_entrust`='0'","`is_entrust`,`id`,`uid`,`name`");

					if(!empty($wtResume)){
						$idata['uid']      = $wtResume['uid'];
						$idata['did']      = $this->userdid;
						$idata['username'] = $wtResume['name'];
						$idata['eid']      = $wtResume['id'];
						$idata['status']   = $this->config['user_trust_status'];
						$idata['price']    = $this->config['pay_trust_resume'];
						$idata['add_time'] = time();
						$nid=$this->obj->insert_into("user_entrust",$idata);
						if($nid){
							if($this->config['user_trust_status']=='1'){
								$this->obj->update_once("resume_expect",array('is_entrust'=>2),array('uid'=>$wtResume['uid'],'id'=>$order_info['resumeid']));
							}else{
								$this->obj->update_once("resume_expect",array('is_entrust'=>1),array('uid'=>$wtResume['uid'],'id'=>$order_info['resumeid']));
							}
						}
	  					$this->obj->member_log("委托简历");
					}
					
 				}
 				
				$sendInfo['info'] = '委托简历';
			}else if($order['type']=='16'){

				$order_info = unserialize($order['order_info']);
				
				if($order_info['jobid']){
				
					$sxJob = $this->obj->DB_select_all("company_job","`uid`='".$order['uid']."' AND `id` in (".$order_info['jobid'].")","`lastupdate`,`id`");
						
					if(!empty($sxJob)){
  						
 						$nid=$this->obj->DB_update_all('company_job'," `lastupdate` ='".time()."' "," `id` in(".$order_info['jobid'].") " );
						 

						$this->obj->update_once('company',array('lastupdate'=>time()),array('uid'=>$order['uid']));
						
 						$this->obj->member_log("职位刷新");
					}
					
 				}

				$sendInfo['info'] = '职位刷新';
			}else if($order['type']=='17'){

				$order_info = unserialize($order['order_info']);

				if($order_info['jobid']){
					
					$sxPart = $this->obj->DB_select_all("partjob","`uid`='".$order['uid']."' AND `id`in (".$order_info['jobid'].")","`lastupdate`,`id`");
						
					if(!empty($sxPart)){
  						
 						$nid=$this->obj->DB_update_all('partjob'," `lastupdate` ='".time()."' "," `id` in(".$order_info['jobid'].") " );
						 
 						$this->obj->member_log("兼职刷新");
					}
 				}
				$sendInfo['info'] = '兼职刷新';
			}else if($order['type']=='18'){

				$order_info = unserialize($order['order_info']);
				
				if($order_info['jobid']){
					
					$sxltjob = $this->obj->DB_select_all("lt_job","`uid`='".$order['uid']."' AND `id`in (".$order_info['jobid'].") ","`lastupdate`,`id`");
					if(!empty($sxltjob)){
 							
							$nid=$this->obj->DB_update_all('lt_job'," `lastupdate` ='".time()."' "," `id` in(".$order_info['jobid'].") " );
						 
						$this->obj->update_once('company',array('jobtime'=>time()),array('uid'=>$order['uid']));	
						$this->obj->member_log("猎头职位刷新");
  					}
				}
				$sendInfo['info'] = '猎头职位刷新';
			}else if($order['type']=='19'){

				$order_info = unserialize($order['order_info']);
				
				if($order_info['eid']){

					$eid = intval($order_info['eid']);

					$resume = $this->obj->DB_select_alls("resume","resume_expect","a.`r_status`<>'2' and a.`uid`=b.`uid` and b.`id`='".$eid."'", "a.name,a.telphone,a.telhome,a.email,a.uid,b.id");
					$user=$resume[0];

					$data['eid']=$user['id'];
					$data['uid']=$user['uid'];
					$data['comid']=$order_info['uid'];
					//$data['did']=$this->userdid;
					$data['downtime']=time();

					$downresume = $this->obj->DB_select_once("down_resume","`eid`='".$eid."' and `comid`='".$order_info['uid']."'");
					if(empty($downresume)){
						$nid = $this->obj->insert_into("down_resume",$data);

						$this->obj->DB_update_all("resume_expect","`dnum`=`dnum`+'1'","`id`='".$eid."'");
 					}
					$this->obj->member_log("下载简历");
 					 
				}
				$sendInfo['info'] = '下载简历';
			}else if($order['type']=='20'){
				$value='';
				if($member['usertype']=='2'){
 					$value.="`job_num`=`job_num`+'1'";
				}

				$nid=$this->obj->DB_update_all($table,$value,"`uid`='".$order['uid']."'");
				$sendMail = 1;
 				$sendInfo['info'] = '购买职位发布';
			}else if($order['type']=='21'){
				$value='';
				if($member['usertype']=='2'){
 					$value.="`part_num`=`part_num`+'1'";
				}

				$nid=$this->obj->DB_update_all($table,$value,"`uid`='".$order['uid']."'");
				$sendMail = 1;
 				$sendInfo['info'] = '购买兼职发布';
			}else if($order['type']=='22'){
				$value='';

  				$value.="`lt_job_num`=`lt_job_num`+'1'";

				$nid=$this->obj->DB_update_all($table,$value,"`uid`='".$order['uid']."'");
				$sendMail = 1;
 				$sendInfo['info'] = '购买猎头职位发布';
			}else if($order['type']=='23'){
				$value='';
				if($member['usertype']=='2'){
 					$value.="`invite_resume`=`invite_resume`+'1'";
				}

				$nid=$this->obj->DB_update_all($table,$value,"`uid`='".$order['uid']."'");
				$sendMail = 1;
 				$sendInfo['info'] = '购买面试邀请';
			}else if($order['type']=='24'){
				$order_info = unserialize($order['order_info']);

				if($order_info['jobid']){
					
					$recJob = $this->obj->DB_select_once("partjob","`uid`='".$order['uid']."' AND `id` = '".$order_info['jobid']."'","`rec_time`,`id`,`name`");
					if(!empty($recJob)){
						if ($recJob ['rec_time'] > time()){
							$rec_time = $recJob['rec_time']+$order_info['days']*86400;
						}else {
							$rec_time = time() + $order_info['days'] * 86400;
						}
						$nid=$this->obj->update_once("partjob",array('rec_time'=>$rec_time),"`uid`='".$order['uid']."' and `id`='".$order_info['jobid']."'");
	 					$this->obj->member_log("购买兼职《".$recJob['name']."》推荐");
					}
 				}
 				
				$sendInfo['info'] = '职位推荐';
			}
			if($nid){
				
				$this->obj->DB_update_all("company_order","`order_state`='2',`order_type`='".$paytype."'","`id`='".$order['id']."'");

				$Weixin=$this->MODEL('weixin');
				$Weixin->sendWxPay($sendInfo);


				if($sendMail==1){
				  $notice = $this->MODEL('notice');
				  $notice->sendEmailType($emaildata);
				  $notice->sendSMSType($emaildata);
				}
			}
		}
	}
}

?>