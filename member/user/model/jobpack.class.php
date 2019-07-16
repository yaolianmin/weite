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
class jobpack_controller extends user{
	
	function index_action(){
		
		$this->public_action();
		include(CONFIG_PATH."db.data.php");
		$urlarr=array("c"=>"rewardlog","page"=>"{{page}}");
		$where="`uid`='".$this->uid."' ";
		
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
				$eid[]=$v['eid'];
				$rewardid[] = $v['id'];
			}
			$joblist = $this->obj->DB_select_all("company_job","`id` IN (".@implode(',',$jobids).")");
			include PLUS_PATH."/user.cache.php";
			include PLUS_PATH."/job.cache.php";
			$ulist = $this->obj->DB_select_all("resume_expect","`id` IN (".@implode(',',$eid).")");
			$M			=	$this->MODEL('pack');
			

			$log = $this->obj->DB_select_all("company_job_rewardlog","`rewardid` IN (".@implode(',',$rewardid).") ORDER BY id ASC");
			if(is_array($log)){
				foreach($log as $value){
					$logList[$value['rewardid']][] = $value;
					
				}
			}
			foreach($rows as $k=>$v){
				
				$rows[$k]['log'] = $M->getStatusInfo($v['id'],1,$v['status'],$logList[$v['id']]);
				
				foreach($joblist as $val){
					if($v['jobid']==$val['id']){
						$rows[$k]['name']=$val['name'];
					}
				}
				foreach($ulist as $val){
					if($v['eid']==$val['id']){
						$rows[$k]['uname']=$val['uname'];
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
		}
		$this->yunset("StateList",$arr_data['rewardstate']);
		$this->yunset("rows",$rows);
		$this->yunset("js_def",4);
		$this->user_tpl('jobpack');
	}
	function logstatus_action(){
		if($_POST){
			
			 $M			=	$this->MODEL('pack');

			 $return	=  $M->logStatus((int)$_POST['rewardid'],(int)$_POST['status'],$this->uid,'1',$_POST);
			
			 if($return['error']==''){
				 echo json_encode(array('error'=>'ok'));
					
			 }else{
				 
				 echo json_encode(array('error'=>$return['error']));
			 }
		}
	
	
	}
	function arb_action(){
		if($_POST){

			if(!$_POST['rewardid']){
				$this->ACT_layer_msg("请选择需要仲裁的赏单！",8,$_SERVER['HTTP_REFERER']);
			}
			if(!$_POST['content']){
				$this->ACT_layer_msg("请填写仲裁原因！",8,$_SERVER['HTTP_REFERER']);
			}else{
				$data['content'] = $_POST['content'];
			}

			
			if (is_uploaded_file($_FILES['file']['tmp_name'])) {
				$UploadM=$this->MODEL('upload');
				$upload=$UploadM->Upload_pic("../data/upload/pack/".$this->uid.'/',false);
				$arbpic=$upload->picture($_FILES['file']);
				
				$picmsg=$UploadM->picmsg($arbpic,$_SERVER['HTTP_REFERER']);
				if($picmsg['status'] == $arbpic){
					$this->ACT_layer_msg($picmsg['msg'],8);
				}
				$arbpic = str_replace("../data/","./data/",$arbpic);
				$data['arbpic'] = $arbpic;
			}
			
			 $M			=	$this->MODEL('pack');

			 $return	=  $M->logStatus((int)$_POST['rewardid'],26,$this->uid,'1',$data);
				
			 if($return['error']==''){
				$this->ACT_layer_msg("仲裁提交成功！",9,$_SERVER['HTTP_REFERER']);
					
			 }else{
				 $this->ACT_layer_msg($return['error'],8,$_SERVER['HTTP_REFERER']);
			 }
		}
	
	
	}
	function loglist_action(){
		$userM  = $this->MODEL('userinfo');
		$statis = $userM->GetUserstatisOne(array('uid'=>$this->uid),array('usertype'=>1));
		$urlarr['c']=$_GET['c'];
		$urlarr['act']=$_GET['act'];
		$urlarr["page"]="{{page}}";
		$pageurl=Url('member',$urlarr);
		$rows=$this->get_page("company_job_sharelog","`uid`='".$this->uid."' order by time desc",$pageurl,"10");
		

		$this->yunset("rows",$rows);
		$statis['freeze'] = sprintf("%.2f", $statis['freeze']);
		$this->yunset("statis",$statis);
		$this->public_action();
		$this->user_tpl('loglist');
	}
	function withdraw_action(){
		
		if($_POST){

			$M			=	$this->MODEL('pack');
			
			 $return	=  $M->withDraw($this->uid,$this->usertype,$_POST['price'],$_POST['real_name']);
				
			 if($return==''){
				 $this->ACT_layer_msg("提现成功，请关注微信账户提醒！",9,$_SERVER['HTTP_REFERER']);
					
			 }else{
				 $this->ACT_layer_msg($return,8,$_SERVER['HTTP_REFERER']);
				
			 }
			
		}else{
			$userM  = $this->MODEL('userinfo');
			$statis = $userM->GetUserstatisOne(array('uid'=>$this->uid),array('usertype'=>1));

			$this->yunset("statis",$statis);
			$this->yunset("js_def",4);
			$this->public_action();
			$this->user_tpl('withdraw');
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
		$statis = $userM->GetUserstatisOne(array('uid'=>$this->uid),array('usertype'=>1));

		$this->yunset("statis",$statis);
		$this->yunset("rows",$rows);
		$this->yunset("js_def",4);
		$this->public_action();
		$this->user_tpl('withdrawlist');
	}
}
?>