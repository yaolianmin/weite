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
class weixin_model extends model{
   
	function myMsg($wxid='')
	{
		$userBind = $this->isBind($wxid);
		
		if($userBind['bindtype']=='1')
		{
			$Return['centerStr'] = "<Content><![CDATA[您最新没有新的消息！]]></Content>";
			
		}else{

			$Return['centerStr'] = $userBind['cenetrTpl'];
		}
		$Return['MsgType']   = 'text';
		return $Return;
	}
	
	function Audition($wxid='')
	{
		$userBind = $this->isBind($wxid);
		if($userBind['bindtype']=='1')
		{
			$Aud = $this->DB_select_all("userid_msg","`uid`='".$userBind['uid']."' ORDER BY `datetime` DESC limit 5");
			
			if(is_array($Aud) && !empty($Aud))
			{
				foreach($Aud as $key=>$value)
				{
					$Info['title'] = "【".$value['fname']."】邀您面试\n邀请时间：".date('Y-m-d H:i:s',$value['datetime']);
					$Info['pic']   = $this->config['sy_weburl'].'/data/upload/wx/jt.jpg';
					$Info['url']   = $this->config['sy_wapdomain']."/member/index.php?c=invite";
					$List[]        = $Info;
				}
				$Msg['title'] = '面试邀请';
				$Msg['pic'] = $this->config['sy_weburl'].'/'.$this->config['sy_wx_logo'];
				$Msg['url'] = $this->config['sy_wapdomain']."/member/index.php?c=invite";
				$Return['centerStr'] = $this->Handle($List,$Msg);
				$Return['MsgType']   = 'news';

			}else{

				$Return['centerStr'] ='<Content><![CDATA['.'最近暂无面试邀请'.']]></Content>';
				$Return['MsgType']   = 'text';
			}
			return $Return;
		}else{
			$Return['MsgType']   = 'text';
			$Return['centerStr'] = $userBind['cenetrTpl'];
			return $Return;
		}
	}
	function ApplyJob($wxid='')
	{
		$userBind = $this->isBind($wxid,2);
		if($userBind['bindtype']=='1')
		{
			$Apply = $this->DB_select_all("userid_job","`com_id`='".$userBind['uid']."' AND `is_browse`='1' ORDER BY `datetime` DESC limit 5");
			
			if(is_array($Apply) && !empty($Apply))
			{
				foreach($Apply as $key=>$value)
				{
					$uid[] = $value['uid'];
				}
				$userList = $this->DB_select_all("resume","`uid` IN (".@implode(',',$uid).")","`uid`,`name`,`edu`,`exp`");
				if(is_array($userList)){
					
					foreach($userList as $key=>$value)
					{
						$resumeList[$value['uid']] = $value;
					}
				}
				include(PLUS_PATH."/user.cache.php");
				foreach($Apply as $key=>$value)
				{
					$Info['title'] = "【".$resumeList[$value['uid']]['name']."】".$userclass_name[$resumeList[$value['uid']]['edu']]."/".$userclass_name[$resumeList[$value['uid']]['exp']]."工作经验\n向您发布的职位：".$value['job_name']."\n投递一份简历\n投递时间：".date('Y-m-d H:i',$value['datetime']);
					$Info['pic']   = $this->config['sy_weburl'].'/data/upload/wx/jt.jpg';
					$Info['url']   = $this->config['sy_wapdomain']."/member/index.php?c=hr";
					$List[]        = $Info;
				}
				$Msg['title'] = '简历投递';
				$Msg['pic'] = $this->config['sy_weburl'].'/'.$this->config['sy_wx_logo'];
				$Msg['url'] = $this->config['sy_wapdomain']."/member/index.php?c=hr";
				$Return['centerStr'] = $this->Handle($List,$Msg);
				$Return['MsgType']   = 'news';

			}else{

				$Return['centerStr'] ='<Content><![CDATA['.'最近暂无简历投递'.']]></Content>';
				$Return['MsgType']   = 'text';
			}
			
			return $Return;
		}else{
			$Return['MsgType']   = 'text';
			$Return['centerStr'] = $userBind['cenetrTpl'];
			return $Return;
		}
	}
	function PartApply($wxid='')
	{
		$userBind = $this->isBind($wxid,2);
		if($userBind['bindtype']=='1')
		{
			$Apply = $this->DB_select_all("part_apply","`comid`='".$userBind['uid']."' AND `status`='1' ORDER BY `ctime` DESC limit 5");
			
			if(is_array($Apply) && !empty($Apply))
			{
				foreach($Apply as $key=>$value)
				{
					$uid[] = $value['uid'];
					$jobid[] = $value['jobid'];
				}
				$partJob = $this->DB_select_all("partjob","`uid`='".$userBind['uid']."' AND `id` IN (".@implode(',',$jobid).")","`id`,`name`");
				if(is_array($partJob)){
					
					foreach($partJob as $key=>$value)
					{
						$jobname[$value['id']] = $value['name'];
					}
				}
				$userList = $this->DB_select_all("resume","`uid` IN (".@implode(',',$uid).")","`uid`,`name`,`edu`,`exp`");
				if(is_array($userList)){
					
					foreach($userList as $key=>$value)
					{
						$resumeList[$value['uid']] = $value;
					}
				}
				include(PLUS_PATH."/user.cache.php");
				foreach($Apply as $key=>$value)
				{
					$Info['title'] = "【".$resumeList[$value['uid']]['name']."】".$userclass_name[$resumeList[$value['uid']]['edu']]."/".$userclass_name[$resumeList[$value['uid']]['exp']]."工作经验\n报名兼职：".$jobname[$value['jobid']]."\n报名时间：".date('Y-m-d H:i',$value['ctime']);
					$Info['pic']   = $this->config['sy_weburl'].'/data/upload/wx/jt.jpg';
					$Info['url']   = $this->config['sy_wapdomain']."/member/index.php?c=partapply";
					$List[]        = $Info;
				}
				$Msg['title'] = '兼职报名';
				$Msg['pic'] = $this->config['sy_weburl'].'/'.$this->config['sy_wx_logo'];
				$Msg['url'] = $this->config['sy_wapdomain']."/member/index.php?c=partapply";
				$Return['centerStr'] = $this->Handle($List,$Msg);
				$Return['MsgType']   = 'news';

			}else{

				$Return['centerStr'] ='<Content><![CDATA[最近暂无报名]]></Content>';
				$Return['MsgType']   = 'text';
			}
			
			return $Return;
		}else{
			$Return['MsgType']   = 'text';
			$Return['centerStr'] = $userBind['cenetrTpl'];
			return $Return;
		}
	}
	function lookResume($wxid='')
	{
		$userBind = $this->isBind($wxid);
		if($userBind['bindtype']=='1')
		{
			$Aud = $this->DB_select_all("look_resume","`uid`='".$userBind['uid']."'  AND `com_id`>0   ORDER BY `datetime`  DESC limit 5");
			if(is_array($Aud) && !empty($Aud))
			{
				
				foreach($Aud as $key=>$value)
				{
					$comid[] = $value['com_id'];
				}
				$comids =pylode(',',$comid);
		
				if($comids){
					$comList = $this->DB_select_all('company','`uid` IN ('.$comids.')','`uid`,`name`');
					if(is_array($comList)){
						foreach($comList as $key=>$value)
						{
							$comname[$value['uid']] = $value['name'];
						}
					}
					foreach($Aud as $key=>$value)
					{
						$Info['title'] = "查看企业：【".$comname[$value['com_id']]."】\n查看时间：".date('Y-m-d H:i:s',$value['datetime']);
						$Info['pic']   = $this->config['sy_weburl'].'/data/upload/wx/jt.jpg';
						$Info['url']   = $this->config['sy_wapdomain']."/member/index.php?c=look";
						$List[]        = $Info;
					}
					$Msg['title'] = '最近查看我的简历';
					$Msg['pic'] = $this->config['sy_weburl'].'/'.$this->config['sy_wx_logo'];
					$Msg['url'] = $this->config['sy_wapdomain']."/member/index.php?c=look";
					$Return['centerStr'] = $this->Handle($List,$Msg);
					$Return['MsgType']   = 'news';
				}else{
					$Return['centerStr']='<Content><![CDATA[已经很久没公司查看您的简历了！]]></Content>';
					$Return['MsgType']   = 'text';
				}
			}else{

				$Return['centerStr']='<Content><![CDATA[已经很久没公司查看您的简历了！]]></Content>';
				$Return['MsgType']   = 'text';
			}
			return $Return;

		}else{

			
			$Return['MsgType']   = 'text';
			$Return['centerStr'] = $userBind['cenetrTpl'];
			return $Return;
		}
	}
	function refResume($wxid='')
	{
		$userBind = $this->isBind($wxid);
		if($userBind['bindtype']=='1')
		{
			$Resume = $this->DB_select_num("resume_expect","`uid`='".$userBind['uid']."'");
			
			if($Resume>0)
			{
				$this->DB_update_all("resume_expect","`lastupdate`='".time()."'","`uid` = '".$userBind['uid']."'");
				$Return['centerStr']="<Content><![CDATA[简历刷新成功\n刷新时间:".date('Y-m-d H:i:s')."]]></Content>";

			}else{

				$Return['centerStr']='<Content><![CDATA[请先完善您的简历！]]></Content>';
				
			}
		}else{

			$Return['centerStr'] = $userBind['cenetrTpl'];
			
		}
		$Return['MsgType']   = 'text';
		return $Return;
	}

	function refJob($wxid='')
	{
		$userBind = $this->isBind($wxid,2);
		if($userBind['bindtype']=='1')
		{
			$jobNum = $this->DB_select_num('company_job',"`uid`='".$userBind['uid']."' AND `state`='1' AND `status`<>1");
			if($jobNum>0){
				$membeStatis = $this->DB_select_once('company_statis',"`uid`='".$userBind['uid']."'");
				$refIntegral = $this->config['integral_jobefresh']*$jobNum;
				if($membeStatis['rating_type']=='2'){
					if($membeStatis['vip_etime']>=time()){
						
						$this->DB_update_all('company_job',"`lastupdate`='".time()."'","`uid`='".$userBind['uid']."' AND `state`='1' AND `status`<>1");
						$msg = '职位刷新完成，本次共刷新'.$jobNum."个职位！";
					}else{

						$useIntegral = 1;
					}
				}else{
					
					if(($membeStatis['vip_etime']<1 || $membeStatis['vip_etime']>=time()) && $membeStatis['breakjob_num']>=$jobNum){

						$this->DB_update_all('company_job',"`lastupdate`='".time()."'","`uid`='".$userBind['uid']."' AND `state`='1' AND `status`<>1");

						$this->DB_update_all('company_statis',"`breakjob_num`='".($membeStatis['breakjob_num']-$jobNum)."'","`uid`='".$userBind['uid']."'");

						$msg = '职位刷新完成，本次共刷新'.$jobNum."个职位！";
					}else{
						$useIntegral = 1;
					}
				}
				if($useIntegral=='1'){
					
					if($this->config['com_integral_online']=='1'){
							
						if($this->config['integral_jobefresh_type']=='2'){
							
							if($membeStatis['integral']>=$refIntegral){
								
								$this->DB_update_all('company_job',"`lastupdate`='".time()."'","`uid`='".$userBind['uid']."' AND `state`='1' AND `status`<>1");
								$this->DB_update_all('company_statis',"`integral`='".($membeStatis['integral']-$refIntegral)."'","`uid`='".$userBind['uid']."'");

								$msg = '职位刷新完成，本次共刷新'.$jobNum."个职位！";

							}else{

								$msg = "本次刷新共需".$refIntegral."".$this->config['integral_pricename']."，请先充值".$this->config['integral_pricename']."！";
							}
						}else{
							$msg = "权限不足，升级会员，享受更多服务！";
						}
						
					}else{
						$msg = "权限不足，升级会员，享受更多服务！";
					}
				}
			}else{
				$msg = '您没有正在招聘的职位！';
			}
			$Return['centerStr']='<Content><![CDATA['.$msg.']]></Content>';
		}else{

			$Return['centerStr'] = $userBind['cenetrTpl'];
			
		}
		$Return['MsgType']   = 'text';
		return $Return;
	}

	function searchJob($keyword)
	{

		$keyword = trim($keyword);
		
		include(PLUS_PATH."/city.cache.php");
		if($keyword)
		{
			$keywords = @explode(' ',$keyword);
		
			if(is_array($keywords))
			{
				foreach($keywords as $key=>$value)
				{
					$iscity = 0;
					if($value!='')
					{
						foreach($city_name as $k=>$v)
						{
							if(strpos($v,trim($value))!==false)
							{
								$CityId[] = $k;
								$iscity = 1;
							}
						}
						if($iscity==0)
						{
							$searchJob[] = "(`name` LIKE '%".trim($value)."%') OR (`com_name` LIKE '%". trim($value) ."%')";
						}
					}
				}
				
				$searchWhere = "`state`='1' AND `sdate`<='".time()."' AND `edate`>= '".time()."' AND `status`<>'1' AND `r_status`<>'2'";
				if(!empty($searchJob))
				{
					$searchWhere .=  " AND (".implode(' OR ',$searchJob).")";
				}
				if(!empty($CityId))
				{
					$City_id = pylode(',',$CityId);
					$searchWhere .= " AND (`provinceid` IN (".$City_id.") OR `cityid` IN (".$City_id.") OR `three_cityid` IN (".$City_id."))";
				}
				$jobList = $this->DB_select_all("company_job",$searchWhere." order by `lastupdate` desc limit 5","`id`,`name`,`com_name`,`com_logo`");
			}
		}	
	
		if(is_array($jobList) && !empty($jobList))
		{

			foreach($jobList as $key=>$value)
			{
				$Info['title'] = "【".$value['name']."】\n".$value['com_name'];
				$Info['pic'] = $this->config['sy_weburl'].'/data/upload/wx/jt.jpg';
				$Info['url'] = Url("wap",array('c'=>'job','a'=>'view','id'=>$value['id']));
				$List[]     = $Info;
			}
			$Msg['title'] = '查看与【'.$keyword. '】相关的职位';
			if($value['com_logo']){
				$Msg['pic'] = $this->config['sy_weburl'].'/'.$value['com_logo'];
			}else{
				$Msg['pic'] = $this->config['sy_weburl'].'/'.$this->config['sy_wx_logo'];
			}
			
			$Msg['url'] = Url('wap',array('c'=>'job','keyword'=>urlencode($keyword)));
			
			$Return['centerStr'] = $this->Handle($List,$Msg);
			$Return['MsgType']   = 'news';
		}else{

			$Return['centerStr'] = '<Content><![CDATA[未找到合适的职位！]]></Content>';
			$Return['MsgType']   = 'text';
		}
		
		return $Return;
		
	}
	
	function searchKeyword($keyword)
	{
		$keyword = trim($keyword);
		if($keyword)
		{													
			$searchWhere = "(`keyword` LIKE '%".trim($keyword)."%')";																							
			$keywordList = $this->DB_select_once("wxzdkeyword",$searchWhere,"`id`,`keyword`,`content`");			
		}	
		if(!empty($keywordList))
		{		
			$Return['centerStr'] = '<Content><![CDATA['.$keywordList['content'].']]></Content>';
			$Return['MsgType']   = 'text';
		}		
		return $Return;				
	}
	
	
	function bindUser($wxid='')
	{
	
		$bindType = $this->isBind($wxid);
		$Return['MsgType']   = 'text';
		$Return['centerStr'] = $bindType['cenetrTpl'];
		return $Return;
		
	}
	
	function getWxUser($wxid){
		 global $config;
		
		$Token = getToken($config);
	
		$Url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$Token.'&openid='.$wxid.'&lang=zh_CN';
		$CurlReturn  = CurlPost($Url);
		$UserInfo    = json_decode($CurlReturn,true);

		return $UserInfo;
	
	}
	function isBind($wxid='',$usertype=1)
	{	
	
		if($wxid)
		{
			
			$UserInfo    = $this->getWxUser($wxid);
			$unionid	 = $UserInfo['unionid'];
			$User = $this->DB_select_once("member","`wxid`='".$wxid."' OR (`unionid`<>'' AND `unionid`='".$unionid."' )","`uid`,`username`,`usertype`,`wxid`,`unionid`");
			if($User['unionid']!='' && $User['wxid']!=$wxid)
			{
				$this->DB_update_all("member","`wxid`='".$wxid."'","`uid`='".$User['uid']."'");
				$User['wxid']=$wxid;
			}
			if (empty($User['unionid']) && $unionid!=''){  
			    $this->DB_update_all("member","`unionid`='".$unionid."'","`uid`='".$User['uid']."'");
			    $User['unionid']=$unionid;
			}
		}
		if($User['uid']>0)
		{   
			$urlLogin = $this->config['sy_wapdomain']."/index.php?c=login&bind=1&wxid=".$wxid."&unionid=".$unionid;
			if($User['usertype']!=$usertype)
			{
				switch($usertype){
					case '1':
						$User['cenetrTpl'] = "<Content><![CDATA[您的".$this->config['sy_webname']."帐号：".$User['username']."为企业帐号，请登录您的个人帐号进行绑定！ \n\n\n 您也可以<a href=\"".$urlLogin."\">点击这里</a>进行绑定其他帐号]]></Content>";
					break;
					case '2':
						$User['cenetrTpl'] = "<Content><![CDATA[您的".$this->config['sy_webname']."帐号：".$User['username']."为个人帐号，请登录您的企业帐号进行绑定！ \n\n\n 您可以<a href=\"".$urlLogin."\">点击这里</a>进行解绑定其他帐号]]></Content>";
					break;

				}
				
			}else{
				$User['bindtype'] = '1';
				$User['cenetrTpl'] = "<Content><![CDATA[您的".$this->config['sy_webname']."帐号：".$User['username']."已成功绑定！ \n\n\n 您也可以<a href=\"".$urlLogin."\">点击这里</a>进行解绑或绑定其他帐号]]></Content>";
			}
			
		}else{

			$urlLogin = $this->config['sy_wapdomain']."/index.php?c=login&wxid=".$wxid."&unionid=".$unionid;
			$User['cenetrTpl'] = '<Content><![CDATA[您还没有绑定帐号，<a href="'.$urlLogin.'">点击这里</a>进行绑定!]]></Content>';
		}

		return $User;
	}
	function recJob()
	{

		$JobList = $this->DB_select_all("company_job","`sdate`<='".time()."' AND `edate`>= '".time()."' AND `status`<>1 AND `r_status`<>2 AND `rec_time`>'".time()."' order by `lastupdate` desc limit 5","`id`,`name`,`com_name`,`lastupdate`");
		
		if(is_array($JobList) && !empty($JobList))
		{
			foreach($JobList as $key=>$value)
			{
				$Info['title'] = "【".$value['name']."】\n".$value['com_name'];
				$Info['pic'] = $this->config['sy_weburl'].'/data/upload/wx/jt.jpg';
				$Info['url'] = Url("wap",array('c'=>'job','a'=>'view','id'=>$value['id']));
				$List[]        = $Info;
			}
			$Msg['title'] = '推荐职位';
			$Msg['pic'] = $this->config['sy_weburl'].'/'.$this->config['sy_wx_logo'];
			$Msg['url'] = Url("wap",array('c'=>'job'));
			$Return['centerStr'] = $this->Handle($List,$Msg);
			$Return['MsgType']   = 'news';
			
		}else{
			$Return['centerStr'] ='<Content><![CDATA[没有合适的职位！]]></Content>';
			$Return['MsgType']   = 'text';
		}
		
		return $Return;
	}
	
	function Handle($List,$Msg)
	{

		$articleTpl = '<Content><![CDATA['.$Msg['title'].']]></Content>';

		$articleTpl .= '<ArticleCount>'.(count($List)+1).'</ArticleCount><Articles>';

		$centerTpl = "<item>
		<Title><![CDATA[%s]]></Title>  
		<Description><![CDATA[%s]]></Description>
		<PicUrl><![CDATA[%s]]></PicUrl>
		<Url><![CDATA[%s]]></Url>
		</item>";
		
		$articleTpl.=sprintf($centerTpl,$Msg['title'],'',$Msg['pic'],$Msg['url']); 
		
		foreach($List as $value)
		{	
			$articleTpl.=sprintf($centerTpl,$value['title'],'',$value['pic'],$value['url']);
		}
		$articleTpl .= '</Articles>';
		return $articleTpl;
	}
	function valid($echoStr,$signature,$timestamp,$nonce)
  {
      if($this->checkSignature($signature,$timestamp,$nonce)){
      	echo $echoStr;	
      	exit;
      }
  }
	
	function checkSignature($signature, $timestamp,$nonce)
	{   		
		$token = $this->config['wx_token'];
		$tmpArr = array($token, $timestamp, $nonce);
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		
		if( $tmpStr == $signature  && $token!=''){
			return true;
		}else{
			return false;
		}
	}
	function ArrayToString($obj,$withKey=true,$two=false)
	{
		if(empty($obj))	return array();
		$objType=gettype($obj);
		if ($objType=='array') {
			$objstring = "array(";
			foreach ($obj as $objkey=>$objv) {
				if($withKey)$objstring .="\"$objkey\"=>";
				$vtype =gettype($objv) ;
				if ($vtype=='integer') {
				  $objstring .="$objv,";
				}else if ($vtype=='double'){
				  $objstring .="$objv,";
				}else if ($vtype=='string'){
				  $objv= str_replace('"',"\\\"",$objv);
				  $objstring .="\"".$objv."\",";
				}else if ($vtype=='array'){
				  $objstring .="".$this->ArrayToString($objv,false).",";
				}else if ($vtype=='object'){
				  $objstring .="".$this->ArrayToString($objv,false).",";
				}else {
				  $objstring .="\"".$objv."\",";
				}
			}
			$objstring = substr($objstring,0,-1)."";
			return $objstring.")\n";
		}
	}
	function markLog($wxid,$wxuser,$content,$reply){

		$this->DB_insert_once("wxlog","`wxid`='".$wxid."',`wxuser`='".$wxuser."',`content`='".$content."',`reply`='".$reply."',`time`='".time()."'");
	}

	function sendWxTemplate($wxid,$tempid,$url,$data){
       global $config;
		if(!$config){
			include(PLUS_PATH.'config.php');
		}
		$Token = getToken($config);
		
		$wxUrl = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$Token;
		
		$templateDate = array("touser"=>$wxid,
							  "template_id"=>$tempid,
							  "url"=>$url,
							  "topcolor"=>"#FF0000",
							  "data"=>$data
						);
		
		$CurlReturn  = CurlPost($wxUrl,json_encode($templateDate));
		

		$UserInfo    = json_decode($CurlReturn);
		
		
    }
	function sendWxJob($uid,$jobid){
       
		global $config;
		if($config['wx_xxtz']!='1'  || !$config['wx_mbsqzw'])
		{
			return true;
		}
		if($uid && $jobid)
		{
			$Tempid = $config['wx_mbsqzw'];
			if(is_array($jobid))
			{
				$Jids = pylode(",",$jobid);

			}else{
				
				$Jids = pylode(",",@explode(',',$jobid));

			}
			
			$comList = $this->DB_select_all("company_job","`id` IN (".$Jids.")","`uid`,`com_name`,`name`");
			
			if(is_array($comList) && !empty($comList))
			{

				foreach($comList as $value){
				
					$Mid[]					= $value['uid'];
					$Comname[$value['uid']] = $value['com_name'];
					$Jobname[$value['uid']][] = $value['name'];
				}

				$usertList = $this->DB_select_all("member","`uid` IN (".@implode(',',$Mid).") ","`uid`,`wxid`");
				
				if(is_array($usertList) && !empty($usertList))
				{

					$Expect = $this->DB_select_once("resume_expect","`uid` = '".(int)$uid."' AND `defaults`='1'");
					include PLUS_PATH."/city.cache.php";
					include PLUS_PATH."/user.cache.php";
					foreach($usertList as $value){
						
						$First		= $Comname[$value['uid']].'，您好，您发布的职位：'.@implode(',',$Jobname[$value['uid']]).' 收到一份新简历!';
						$Iname		= $Expect['uname'];
						$Edu		= $userclass_name[$Expect['edu']];
						$Exp		= $userclass_name[$Expect['exp']];
						$City		= $city_name[$Expect['provinceid']]." ".$city_name[$Expect['cityid']]." ".$city_name[$Expect['three_cityid']];
						if($Expect['maxsalary']>0){
							$Sarlary	= $Expect['minsalary'].'-'.$Expect['maxsalary'];
						}elseif($Expect['minsalary']>0){
							$Sarlary	= $Expect['minsalary'].'以上';
						}else{
							$Sarlary	= '面议';
						}
						$Remark		= '详情请登录 '.$config['sy_webname'].' 及时查阅!';

						$TempDate['first']	= array('value'=>$First,'color'=>'');
						$TempDate['keyword1']	= array('value'=>$Iname,'color'=>'');
						$TempDate['keyword2']	= array('value'=>$Edu,'color'=>'');
						$TempDate['keyword3']	= array('value'=>$Exp,'color'=>'');
						$TempDate['keyword4']	= array('value'=>$City,'color'=>'');
						$TempDate['keyword5']	= array('value'=>$Sarlary,'color'=>'');
						$TempDate['remark']	= array('value'=>$Remark,'color'=>'');
						$Url = Url('wap',array("c"=>"login"));
					
						
						$this->sendWxTemplate($value['wxid'],$Tempid,$Url,$TempDate);
						
					}
				}
			}
		}
    }
	function sendWxresume($data){
       
	   global $config;
		if($config['wx_xxtz']!='1' || !$config['wx_mbyqms'])
		{
			return true;
		}
		if($data['uid'])
		{
			$Tempid = $config['wx_mbyqms'];
			
			$userInfo = $this->DB_select_once("member","`uid` = '".$data['uid']."'","username,wxid");
			
			if(is_array($userInfo))
			{	
				$First		= $userInfo['username'].'，恭喜你!您收到公司的面试邀请啦！';
				$Job		= $data['jobname'];
				$Company	= $data['fname'];
				$Time		= $data['intertime'];
				$Address	= $data['address'];
				$Contact	= $data['linkman'];
				$Tel		= $data['linktel'];
				$Remark		= $data['content'].' 详情请登录 '.$config['sy_webname'].' 及时查阅!';

				$TempDate['first']	= array('value'=>$First,'color'=>'');
				$TempDate['job']	= array('value'=>$Job,'color'=>'');
				$TempDate['company']	= array('value'=>$Company,'color'=>'');
				$TempDate['time']	= array('value'=>$Time,'color'=>'');
				$TempDate['address']	= array('value'=>$Address,'color'=>'');
				$TempDate['contact']	= array('value'=>$Contact,'color'=>'');
				$TempDate['tel']	= array('value'=>$Tel,'color'=>'');
				$TempDate['remark']	= array('value'=>$Remark,'color'=>'');
				$Url = Url('wap',array('c'=>'login'));
				$this->sendWxTemplate($userInfo['wxid'],$Tempid,$Url,$TempDate);
			}
		}
    }

	function sendWxPay($data){
      
	   global $config;
	   if(!$config){
			include(PLUS_PATH.'config.php');
		}
		if($config['wx_xxtz']!='1' || !$config['wx_mbcztx'])
		{
			return true;
		}
		if($data['wxid'])
		{
			$Tempid = $config['wx_mbcztx'];
			
	
			$First		= $data['first'];
			$UserName	= $data['username'];
			$Order		= $data['order'];
			$Price		= $data['price'];
			$Time		= $data['time'];
			$PayType	= $data['paytype'];
			$Info		= $data['info'];
			$Remark		= '感谢您的支持,详情请登录 '.$config['sy_webname'].' !';

			$TempDate['first']	= array('value'=>$First,'color'=>'');
			$TempDate['keyword1']	= array('value'=>$UserName,'color'=>'');
			$TempDate['keyword2']	= array('value'=>$Price,'color'=>'');
			$TempDate['keyword3']	= array('value'=>$Info,'color'=>'');
			$TempDate['keyword4']	= array('value'=>$PayType,'color'=>'');
			$TempDate['keyword5']	= array('value'=>$Time,'color'=>'');
			$TempDate['remark']	= array('value'=>$Remark,'color'=>'');
			$Url = Url('wap',array('c'=>'login'));

			$this->sendWxTemplate($data['wxid'],$Tempid,$Url,$TempDate);
		}
    }

	function sendWxJobStatus($data){
	  global $config;
		if($config['wx_xxtz']!='1' || !$config['wx_mbzwsh'])
		{
			return true;
		}
	
		if($data['jobid'] && $data['state']>0)
		{
			$Tempid = $config['wx_mbzwsh'];
			
			$User = $this->DB_select_all('company_job',"`id` IN (".pylode(',',$data['jobid']).")","`uid`,`com_name`,`name`");
			
			if(is_array($User)){
				foreach($User as $key=>$value){
					
					$JobName[$value['uid']][] = $value['name'];
					$Uid[] = $value['uid'];
					$ComName[$value['uid']] = $value['com_name'];
					
				}
				
				$Uid = array_unique($Uid);
				switch($data['state']){
					case '1':$Status='审核通过';
					break;
					case '3':$Status='审核不通过';
					break;
				}
				$Member = $this->DB_select_all('member',"`uid` IN (".pylode(',',$Uid).")","`wxid`,`uid`");
				
				if(is_array($Member)){
					foreach($Member as $key=>$value){
						
						$data['first'] = '尊敬的 '.$ComName[$value['uid']].',您好!您发布的职位有一条新的状态通知！';
						$First		= $data['first'];
						$JobName	= @implode(',',$JobName[$value['uid']]);
						$Status		= $Status;
						$Body		= $data['statusbody'];

						$Remark		= '详情请登录 '.$config['sy_webname'].' !';

						$TempDate['first']	= array('value'=>$First,'color'=>'');
						$TempDate['keyword1']	= array('value'=>$JobName,'color'=>'');
						$TempDate['keyword2']	= array('value'=>$Status,'color'=>'');
						$TempDate['keyword3']	= array('value'=>$Body,'color'=>'');
						$TempDate['remark']	= array('value'=>$Remark,'color'=>'');
						$Url = Url('wap',array('c'=>'login'));

						$this->sendWxTemplate($value['wxid'],$Tempid,$Url,$TempDate);
					}
				}
			}
		}
    }
	function sendWxPart($data){
	   global $config;
		if($config['wx_xxtz']!='1' || !$config['wx_mbjzbm'])
		{
			return true;
		}
		if($data['wxid'])
		{
			$Tempid = $config['wx_mbjzbm'];
			
	
			$First		= $data['first'];
			$UserName	= $data['username'];
			$Order		= $data['order'];
			$Price		= $data['price'];
			$Info		= $data['info'];
			$Remark		= '感谢您的支持,详情请登录 '.$config['sy_webname'].' !';

			$TempDate['first']	= array('value'=>$First,'color'=>'');
			$TempDate['keyword1']	= array('value'=>$UserName,'color'=>'');
			$TempDate['keyword2']	= array('value'=>$Order,'color'=>'');
			$TempDate['keyword3']	= array('value'=>$Price,'color'=>'');
			$TempDate['keyword4']	= array('value'=>$Info,'color'=>'');
			$TempDate['remark']	= array('value'=>$Remark,'color'=>'');
			$Url = Url('wap',array('c'=>'login'));

			$this->sendWxTemplate($data['wxid'],$Tempid,$Url,$TempDate);
		}
    }
	function sendWxReward($data){
	   global $config;
		if($config['wx_xxtz']!='1' || !$config['wx_mbreward'])
		{
			return true;
		}
		if($data['wxid'])
		{
		
			$Tempid = $config['wx_mbreward'];
			
	
			$First		= $data['first'];
			$jobName	= $data['jobname'];
			$Status		= $data['statusinfo'];
			$Remark		= $data['remark'];
			$rinfo		= $data['rinfo'];
			$TempDate['first']	= array('value'=>$First,'color'=>'');
			$TempDate['keyword1']	= array('value'=>$jobName,'color'=>'');
			$TempDate['keyword2']	= array('value'=>$rinfo,'color'=>'');//申请信息
			$TempDate['keyword3']	= array('value'=>date('Y-m-d H:i:s'),'color'=>'');//申请时间
			$TempDate['keyword4']	= array('value'=>$Status,'color'=>'');
			$TempDate['remark']	= array('value'=>$Remark,'color'=>'');
			$Url = Url('wap',array('c'=>'login'));
			
			$this->sendWxTemplate($data['wxid'],$Tempid,$Url,$TempDate);
		}
    }
	function applyWxQrcode($wxloginid=''){
		global $config;
		if($config['wx_author']=='1'){
			if($wxloginid){
				$wxqrcode = $this->DB_select_once('wxqrcode',"`wxloginid`='".$wxloginid."' AND `status`='0'");
				if($wxqrcode['time']>=(time()-86000)){
					$ticket =  "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=".urlencode($wxqrcode['ticket']);
					return $ticket;
				}
			}
			$randStr = time().rand(1000,9999);
			$Url  = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token='.getToken();
			$Data['expire_seconds']     = 86400;
			$Data['action_name']        = 'QR_STR_SCENE';
			$Data['action_info']['scene']['scene_str'] = $randStr;
			$CurlReturn  = CurlPost($Url,json_encode($Data));
			$Return       = json_decode($CurlReturn,true);
			if($Return['ticket']){
				$this->DB_insert_once("wxqrcode","`wxloginid`='".$randStr."',`ticket`='".$Return['ticket']."',`time`='".time()."',`status`='0'");
				setcookie("wxloginid",$randStr,time()+86000);
				$ticket = "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=".urlencode($Return['ticket']);
			}
		}
		return $ticket;
  }
  
	function isWxlogin($wxid,$wxloginid){
	  global $config;
		$userBind = $this->isBind($wxid);
		if($userBind['uid']>0){
			$this->DB_update_all("wxqrcode","`status`='1',`wxid`='".$wxid."',`unionid`='".$userBind['unionid']."'","`wxloginid`='".$wxloginid."'");
			return true;
		}else{
			return false;
		}
  }

	function getWxLoginStatus($wxloginid){
		if($wxloginid){
			$status = $this->DB_select_once("wxqrcode","`wxloginid`='".$wxloginid."' AND `status`='1'");
			if($status['wxid'] || $status['unionid']){
				$member = $this->DB_select_once("member","`wxid`='".$status['wxid']."' OR (`unionid`<>'' AND `unionid`='".$status['unionid']."' ) ");
				$this->update_once('member',array('login_date'=>time(),'unionid'=>$status['unionid']),array('uid'=>$member['uid']));
			}
		}
		return $member;
	}
}
?>