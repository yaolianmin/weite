<?php
/* *
 * $Author ：PHPYUN开发团队
 *
 * 官网: http://www.phpyun.com
 *
 * 版权所有 2009-2018 宿迁鑫潮信息技术有限公司，并保留所有权利。
 *
 * 软件声明：未经授权前提下，不得用于商业运营、二次开发以及任何形式的再次发布。
 */
class jobpack_controller extends company{

	function index_action(){
		$this->public_action();
		$this->company_satic();
		$this->yunset("js_def",3);
		if($_GET['t']=='r'){
			$this->rewardjob();
		}else{
			$this->sharejob();
		}
	}
	
	function sharejob(){	
		$urlarr=array("c"=>"jobpack","page"=>"{{page}}");
		$where="`uid`='".$this->uid."' ";
		$pageurl=Url('member',$urlarr);
		$rows=$this->get_page("company_job_share",$where,$pageurl,'10');

		if(is_array($rows) && !empty($rows)){
			$jobids=array();
			foreach($rows as $v){
				$jobids[]=$v['jobid'];
			}
			$joblist = $this->obj->DB_select_all("company_job","`uid`='".$this->uid."' AND `id` IN (".@implode(',',$jobids).")");

			$shareNum = $this->obj->DB_select_all("company_job_sharelog","`jobid` IN (".@implode(',',$jobids).") group by jobid","count(*) as num,jobid");
			
			foreach($rows as $k=>$v){
				
				$rows[$k]['nowprice']=sprintf("%.2f", $rows[$k]['packnum']*$rows[$k]['packmoney']);

				foreach($joblist as $val){
					if($v['jobid']==$val['id']){
						$rows[$k]['name']=$val['name'];
						$rows[$k]['status']=$val['status'];
						$rows[$k]['lastupdate']=$val['lastupdate'];
						
					}
				}

				foreach($shareNum as $val){
					if($v['jobid']==$val['jobid']){

						$rows[$k]['sharenum']=$val['num'];
						
					}
				}
				$rows[$k]['sharenum'] = $rows[$k]['sharenum']?$rows[$k]['sharenum']:0;
				
			}
		}

		$this->yunset("rows",$rows);
		$this->com_tpl('jobshrelist');
	
	}

	function delshare_action(){
		
		
		if($_GET['id']){
			
			$packM = $this->MODEL('pack');
			$return = $packM->delShareJob($this->uid,$_GET['id']);
			
			$this->layer_msg('赏金职位取消成功！',9,0,$_SERVER['HTTP_REFERER']);
		}else{
			$this->layer_msg('请选择正确的职位！',8,0,$_SERVER['HTTP_REFERER']);
		}
	
	}
	function delreward_action(){
		
		
		if($_GET['id']){
			
			$packM = $this->MODEL('pack');
			$return = $packM->delrewardJob($this->uid,$_GET['id']);
			if($return['msg']){
				$this->layer_msg($return['msg'],8,0,$_SERVER['HTTP_REFERER']);
			}else{
				$this->layer_msg('悬赏职位取消成功！',9,0,$_SERVER['HTTP_REFERER']);
			}
			
		}else{
			$this->layer_msg('请选择正确的职位！',8,0,$_SERVER['HTTP_REFERER']);
		}
	
	}
	function rewardjob(){
	
		$urlarr=array("c"=>"jobpack",'t'=>'r',"page"=>"{{page}}");
		$where="`uid`='".$this->uid."' ";
		
		$pageurl=Url('member',$urlarr);

		$rows=$this->get_page("company_job_reward",$where,$pageurl,'10');
		
		if(is_array($rows) && !empty($rows)){
			$jobids=array();
			foreach($rows as $v){
				$jobids[]=$v['jobid'];
			}
			$joblist = $this->obj->DB_select_all("company_job","`uid`='".$this->uid."' AND `id` IN (".@implode(',',$jobids).")");

			$sqNum = $this->obj->DB_select_all("company_job_rewardlist","`jobid` IN (".@implode(',',$jobids).") group by jobid","count(*) as num,jobid");
			
			foreach($rows as $k=>$v){
				
				foreach($joblist as $val){
					if($v['jobid']==$val['id']){
						$rows[$k]['name']=$val['name'];
						$rows[$k]['status']=$val['status'];
						$rows[$k]['lastupdate']=$val['lastupdate'];
					}
				}
				foreach($sqNum as $val){
					if($v['jobid']==$val['jobid']){
						$rows[$k]['sqnum']=$val['num'];
						
					}
				}
				$rows[$k]['sqnum'] = $rows[$k]['sqnum']?$rows[$k]['sqnum']:0;
			}
		}

		$this->yunset("rows",$rows);
		$this->com_tpl('jobrewardlist');
	}
	function pay_action(){
	
		if($_POST){
			
			 $M=$this->MODEL('pack');
			 $return  =  $M->redPackOrder($_POST);
			
			 if($return['order']['order_id']&&$return['order']['id']){
				echo json_encode(array('error'=>0,'orderid'=>$return['order']['order_id'],'id'=>$return['order']['id']));
					
			 }else{
				 echo json_encode(array('error'=>1,'msg'=>$return['error']));
			 }
		}else{
			echo json_encode(array('error'=>1,'msg'=>'参数错误，请重试！'));
		}
	
	}

	function rewardpay_action(){
	
		if($_POST){
			
			 $M=$this->MODEL('pack');
			 $return  =  $M->rewardPackOrder($_POST);
				
			 if($return['order']['order_id']&&$return['order']['id']){
				
				echo json_encode(array('error'=>0,'orderid'=>$return['order']['order_id'],'id'=>$return['order']['id']));
					
			 }else{
				 
				 echo json_encode(array('error'=>1,'msg'=>$return['error']));
			 }
		}else{
			echo json_encode(array('error'=>1,'msg'=>'参数错误，请重试！'));
		
		}
	}
	
	function loglist_action(){
		$this->public_action();
		$userM  = $this->MODEL('userinfo');
		$statis = $userM->GetUserstatisOne(array('uid'=>$this->uid),array('usertype'=>2));
		
		$urlarr['c']=$_GET['c'];
		$urlarr['act']=$_GET['act'];
		$urlarr["page"]="{{page}}";
		$pageurl=Url('member',$urlarr);
		$rows=$this->get_page("company_job_sharelog","`uid`='".$this->uid."' order by time desc",$pageurl,"10");
		$this->yunset("rows",$rows);
		$this->company_satic();
		$statis['freeze'] = sprintf("%.2f", $statis['freeze']);
		$this->yunset("statis",$statis);
		$this->yunset("js_def",4);
		$this->com_tpl('loglist');
	}
	
	function rewardjob_action(){
		
		if($_POST){
			
			 $M = $this->MODEL('pack');
			 $return = $M->rewardJob($_POST);
				
			 if($return['error']=='ok'){
				
				 echo json_encode(array('error'=>1));
					
			 }else{
				 
				 echo json_encode(array('msg'=>$return['error']));
			 }
		}else{

			echo json_encode(array('msg'=>'参数错误，请重试！'));
		}
	}
	function rewardlog_action(){
		include(CONFIG_PATH."db.data.php");
		$this->public_action();
		$urlarr=array("c"=>"jobpack",'c'=>'rewardlog',"page"=>"{{page}}");
		$where="`comid`='".$this->uid."' ";
		if($_GET['jobid']){
			$where.=" AND `jobid`='".(int)$_GET['jobid']."'";
			$urlarr['jobid']=$_GET['jobid'];
		}
		if($_GET['state']){
			
			$where.=" AND `status` IN (".@implode(',',$arr_data['rewardstate'][$_GET['state']]['state']).")";
			$urlarr['state']=$_GET['state'];
		}
		$pageurl=Url('member',$urlarr);
 
		$rows=$this->get_page("company_job_rewardlist",$where." order by datetime DESC",$pageurl,'10');
		
		if(is_array($rows) && !empty($rows)){
			$jobids=array();
			foreach($rows as $v){
				$jobids[]=$v['jobid'];
				if($v['usertype']=='3'){
					$lteid[]=$v['eid'];
				}else{
					$eid[]=$v['eid'];
				}
				
				$rewardid[] = $v['id'];
			}
			$joblist = $this->obj->DB_select_all("company_job","`uid`='".$this->uid."' AND `id` IN (".@implode(',',$jobids).")");
			include PLUS_PATH."/user.cache.php";
			include PLUS_PATH."/job.cache.php";
			if(!empty($eid)){
				$ulist = $this->obj->DB_select_all("resume_expect","`id` IN (".@implode(',',$eid).")");

			}
			if(!empty($lteid)){
				$ltulist = $this->obj->DB_select_all("lt_talent","`id` IN (".@implode(',',$lteid).")");

			}
			
			$M = $this->MODEL('pack');
			

			$log = $this->obj->DB_select_all("company_job_rewardlog","`rewardid` IN (".@implode(',',$rewardid).") ORDER BY id ASC");
			if(is_array($log)){
				foreach($log as $value){
					$logList[$value['rewardid']][] = $value;
					
				}
			}
			foreach($rows as $k=>$v){
				
				$rows[$k]['log'] = $M->getStatusInfo($v['id'],2,$v['status'],$logList[$v['id']]);
				
				foreach($joblist as $val){
					if($v['jobid']==$val['id']){
						$rows[$k]['name']=$val['name'];
					}
				}
				if(is_array($ulist)){
					foreach($ulist as $val){
						if($v['eid']==$val['id']){
							$rows[$k]['uname']=mb_substr($val['uname'],0,1,'utf-8').'**';
							$rows[$k]['edu']=$userclass_name[$val['edu']];
							$rows[$k]['exp']=$userclass_name[$val['exp']];
							if($val['job_classid']){
								$class = @explode(',',$val['job_classid']);
								foreach($class as $v){
									$classname[] = $job_name[$v];
								}
								$rows[$k]['jobclass']=@implode(',',$classname);
								unset($classname);
							}
						}
					}
				}
				if(is_array($ltulist)){
					foreach($ltulist as $val){
						if($v['eid']==$val['id']){
							$rows[$k]['uname']=mb_substr($val['name'],0,1,'utf-8').'**';
							$rows[$k]['edu']=$userclass_name[$val['edu']];
							$rows[$k]['exp']=$userclass_name[$val['exp']];
							
							$rows[$k]['jobclass']=$val['jobname'];
								
						}
					}
				}
				
				
			}
		}
		$userM  = $this->MODEL('userinfo');
		$statis = $userM->GetUserstatisOne(array('uid'=>$this->uid),array('usertype'=>2));
		$this->yunset("statis",$statis);
		$this->yunset("StateList",$arr_data['rewardstate']);
		$this->yunset("rows",$rows);
		$this->yunset("js_def",3);
		$this->com_tpl('jobrewardlog');
	}

	function logstatus_action(){
		
		if($_POST){
			
			$M = $this->MODEL('pack');
			$return = $M->logStatus((int)$_POST['rewardid'],(int)$_POST['status'],$this->uid,'2',$_POST);
			
			if($return['error']==''){
				echo json_encode(array('error'=>'ok'));

			}else{
				echo json_encode(array('error'=>$return['error']));

			}
		}
	}

	function lookresume_action(){
	
		if($_GET['id']){
			
			$M = $this->MODEL('pack');
			$reward = $M->getReward((int)$_GET['id'],$this->uid); 
			
			if(empty($reward)){
				
				$this->ACT_msg('index.php?c=jobpack&t=r', '未找到相关数据！',8);

			}elseif($reward['status']=='0'){

				$this->ACT_msg('index.php?c=jobpack&act=rewardlog&jobid='.$reward['jobid'], '请先支付职位赏金！',8);
			
			}else{
				if($reward['usertype']=='3'){
					$talentM = $this->MODEL('talent');
					$Info = $talentM->getTalent($reward['uid'],$reward['eid'],'1');
					
				}else{

					$resumeM=$this->MODEL('resume');
					$Info = $resumeM->resume_select($reward['eid']);
					include(CONFIG_PATH."db.data.php");
					$Info['sex']=$arr_data['sex'][$Info['sex']];
				}
				
				$this->yunset(array("resumestyle"=>$this->config['sy_weburl']."/app/template/resume"));  
				$this->yunset("Info",$Info);
				$this->yunset("reward",$reward);
			}
			$this->yunset("js_def",3);
			$this->com_tpl('lookresume');
		}
	}

	function withdraw_action(){
		
		if($_POST){

			$M = $this->MODEL('pack');
			$return = $M->withDraw($this->uid,$this->usertype,$_POST['price'],$_POST['real_name']);
				
			if($return==''){
				$this->ACT_layer_msg("提现成功，请关注微信账户提醒！",9,$_SERVER['HTTP_REFERER']);
			}else{
				$this->ACT_layer_msg($return,8,$_SERVER['HTTP_REFERER']);
			}

		}else{
			$userM  = $this->MODEL('userinfo');
			$statis = $userM->GetUserstatisOne(array('uid'=>$this->uid),array('usertype'=>2));
			$this->yunset("statis",$statis);
			$this->yunset("js_def",4);
			$this->com_tpl('withdraw');
		}
		
	}
	function withdrawlist_action(){
		
		$urlarr["c"]="jobpack";
		$urlarr["act"]="withdrawlist";
		$urlarr["page"]="{{page}}";
		$pageurl=Url('member',$urlarr);
		$where = "`uid`='".$this->uid."'";
		$rows=$this->get_page("member_withdraw",$where." order by id desc",$pageurl,"10");

		if(is_array($rows)){
			include (APP_PATH."/config/db.data.php");
			foreach($rows as $k=>$v){
				$rows[$k]['order_state_n']=$arr_data['withdrawstate'][$v['order_state']];
			}
		}
		$userM  = $this->MODEL('userinfo');
		$statis = $userM->GetUserstatisOne(array('uid'=>$this->uid),array('usertype'=>2));

		$this->yunset("statis",$statis);
		$this->yunset("rows",$rows);
		$this->yunset("js_def",4);
		$this->com_tpl('withdrawlist');
	}
}
?>