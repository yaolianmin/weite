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
class reward_controller extends common{
	
	function index_action(){
		$packM = $this->MODEL('pack');
		$shareJob = $packM->getShareJob();
		
		$this->yunset("uid",$this->uid);
		$this->yunset("rows",$shareJob);
		$this->seo('reward');
		$this->yunset("headertitle","赏金职位");
		$this->yunset("topplaceholder","请输入职位关键字,如：会计...");
		$this->yuntpl(array('wap/reward'));
	}
	
	function share_action(){
		$packM = $this->MODEL('pack');
		$shareJob = $packM->getShareJob();
		
		$this->yunset("uid",$this->uid);
		$this->yunset("rows",$shareJob);
		$this->seo('reward');
		$this->yunset("headertitle","分享赚赏金");
		if(is_weixin()){
			$this->yunset('isweixin','1');
		}
		$this->yuntpl(array('wap/rewardshare'));
	}

	function shareshow_action(){
		
		
		
		

		$packM = $this->MODEL('pack');

		if(is_weixin()){
			$wxUser = $packM->getWxOpenid(Url('wap').'index.php?c=reward&a=shareshow&id='.$_GET['id'].'&u='.$_GET['u']);
		}
		
		
		$shareJobOne = $packM->getShareJobOne($_GET['id']);
		if(empty($shareJobOne)){
			header('Location:'.Url('wap',array('c'=>'job','a'=>'view','id'=>$_GET['id'])));
		}else{
			
			if($wxUser['openid'] && !empty($shareJobOne)){
				
				$packM->shareJobLook($shareJobOne,$_GET['u'],$wxUser['openid']);

			}
			$CacheM=$this->MODEL('cache');
			$CacheArr=$CacheM->GetCache(array('job','city','hy','com'));
			$data['job_name']=$shareJobOne['name'];
			$data['company_name']=$shareJobOne['com_name'];
			$data['industry_class']=$CacheArr['industry_name'][$shareJobOne['hy']];
			$data['job_class']=$CacheArr['job_name'][$shareJobOne['job1']].",".$CacheArr['job_name'][$shareJobOne['job1_son']].",".$CacheArr['job_name'][$shareJobOne['job_post']];
			$data['job_desc']=$this->GET_content_desc($shareJobOne['description']);
			$this->data=$data;
			
			$this->seo('rewardshare');
			$this->yunset("row",$shareJobOne);
			$this->yunset("headertitle","分享赚赏金");
			
			$this->yuntpl(array('wap/rewardshareshow'));
		}
	}

	function ajaxreward_action(){
		

		if($_GET['jobid']){
			$packM = $this->MODEL('pack');
		
			$return  = $packM->veriftyUser($_GET['jobid'],$this->uid,$this->usertype);
			
			if($return['error']=='1' || $return['error']=='7' ){
				
				$reward = $this->obj->DB_select_once("company_job_reward","`jobid`='".$return['data']['jobid']."'");
				
				$this->yunset('error',$return['error']);
				$this->yunset('rdata',$return['data']);
				$this->yunset('reward',$reward);
				
			}elseif($return['error']=='12' || $return['error']=='11'){
				
				header('Location: member/index.php?c=talentreward&jobid='.$_GET['jobid']);
			
			}else{

				if($return['error']<1){
					$data['msg']='请先登录个人账户！';
					$data['url']=Url('wap',array('c'=>'login'));
				}elseif($return['error']=='2'){
					$data['msg']='请先创建一份合适的简历！';
					$data['url']=$this->config['sy_wapdomain'].'/member/index.php?c=resume';
				}elseif($return['error']=='10'){
					$data['msg']='您的简历正在审核，暂无法申请！';
					$data['url']=$this->config['sy_wapdomain'].'/member/index.php?c=resume';
				}else{
					$data['msg']=$return['msg'];
					$data['url']=Url('wap',array('c'=>'job','a'=>'view','id'=>$_GET['jobid']));
				}
				
				$this->yunset("layer",$data);
			}
			$this->yunset("backurl",Url('wap',array('c'=>'job','a'=>'view','id'=>$_GET['jobid'])));
			$this->yunset("headertitle","赏金申请");
			$this->yunset("jobid",$_GET['jobid']);
			$this->yuntpl(array('wap/sqreward'));
		}
	}
	
	function sqreward_action(){
		
		if($_GET['jobid']){
			$packM = $this->MODEL('pack');
			
			$return  = $packM->sqRewardJob((int)$_GET['jobid'],$this->uid,$this->usertype);
			
			if($return['error']=='1'){
			
				$data['msg']='申请成功！';
				$data['url']=Url('wap',array('c'=>'job','a'=>'view','id'=>(int)$_GET['jobid']));
			}else{

				if($return['error']<1){
					$data['msg']='请先登录个人账户！';
					$data['url']=Url('wap',array('c'=>'login'));
				}elseif($return['error']=='2'){
					$data['msg']='请先创建一份合适的简历！';
					$data['url']=$this->config['sy_wapdomain'].'/member/index.php?c=resume';
				}elseif($return['error']=='10'){
					$data['msg']='您的简历正在审核，暂无法申请！';
					$data['url']=$this->config['sy_wapdomain'].'/member/index.php?c=resume';
				}elseif($return['error']=='7'){
					$data['msg']='您暂不符合相关要求！';
					$data['url']=Url('wap',array('c'=>'job','a'=>'view','id'=>(int)$_GET['jobid']));
				}else{
					
					$data['msg']=$return['msg'];
					$data['url']=Url('wap',array('c'=>'job','a'=>'view','id'=>$_GET['jobid']));
				}
				$this->yunset("jobid",$_GET['jobid']);
				
			}
			
			$this->yunset("backurl",Url('wap',array('c'=>'job','a'=>'view','id'=>$_GET['jobid'])));
			$this->yunset("headertitle","赏金申请");
			$this->yunset("layer",$data);
			$this->yuntpl(array('wap/sqreward'));
		}
	    
	}
	
}
?>