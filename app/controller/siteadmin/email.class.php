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
class email_controller extends siteadmin_controller{
	function index_action(){
		$this->siteadmin_tpl(array('admin_send_email'));
	}
	
	function send_action(){ 
		extract($_POST);
		$UserInfoM=$this->MODEL('userinfo');
		if($email_title==''||$content==''){
			$this->ACT_layer_msg("邮件标题均不能为空！",8,$_SERVER['HTTP_REFERER']);
		} 
		$emailarr=$user=$com=$lt=$userinfo=array();
		if(@in_array(1,$all)){
			$userrows=$UserInfoM->GetMemberList(array("usertype"=>1),array("field"=>"email,`uid`,`usertype`")); 
		}
		if(@in_array(2,$all)){
			$userrows=$UserInfoM->GetMemberList(array("usertype"=>2),array("field"=>"email,`uid`,`usertype`")); 
		}
		if(@in_array(4,$all)){
			$userrows=$UserInfoM->GetMemberList(array("usertype"=>3),array("field"=>"email,`uid`,`usertype`"));  			
		}
		if(@in_array(3,$all)){
			$email_user=@explode(',',$_POST['email_user']); 
			$userrows=$this->obj->DB_select_all("member","`email` in('".$_POST['email_user']."')","email,`uid`,`usertype`");  
			$mails=array();
			foreach($userrows as $v){
				$mails[]=$v['email'];
			} 
		}
		if(is_array($userrows)&&$userrows){
			foreach($userrows as $v){
				if($v['usertype']=='1'){$user[]=$v['uid'];}
				if($v['usertype']=='2'){$com[]=$v['uid'];}
				if($v['usertype']=='3'){$lt[]=$v['uid'];}
				$emailarr[$v['uid']]=$v["email"];
			}
			if($user&&is_array($user)){
				$ResumeM=$this->MODEL('resume');
				$userrows=$UserInfoM->GetMemberList(array("`email` in('".@implode("','",$email_user)."')"),array("field"=>"email,`uid`,`usertype`")); 
				$resume=$ResumeM->ResumeAll(array("`uid` in(".@implode(',',$user).")"),array("field"=>"`name`,`uid`")); 
				foreach($resume as $val){
					$userinfo[$val['uid']]=$val['name'];
				}
			}
			if($com&&is_array($com)){
				$company=$UserInfoM->GetUserinfoList(array("`uid` in(".@implode(',',$com).")"),array("usertype"=>2,"field"=>"`name`,`uid`")); 
				foreach($company as $val){
					$userinfo[$val['uid']]=$val['name'];
				}
			}
			if($lt&&is_array($lt)){
				$LtM=$this->MODEL('lietou');
				$lt_info=$LtM->GetLtinfoList(array("`uid` in(".@implode(',',$lt).")"),array("field"=>"`realname`,`uid`")); 
				foreach($lt_info as $val){
					$userinfo[$val['uid']]=$val['realname'];
				}
			} 
		} 
		if(@in_array(3,$all)){
			foreach($email_user as $v){
				if(in_array($v,$mails)==false&&CheckRegEmail($v)){
					$emailarr[]=$v;
				}
			}
		}
		if(!count($emailarr)){ 
			$this->ACT_layer_msg("没有选择用户的邮箱，无法发送！",8,$_SERVER['HTTP_REFERER']);
		}
		set_time_limit(10000);
		$emailid=$this->send_email($emailarr,$email_title,$content,true,$userinfo);
	
	}
	 
	
	function send_email($email=array(),$emailtitle="",$emailcoment="",$emailalert=false,$userinfo=array(),$other=array()){
      
    $notice = $this->MODEL('notice');
		$sendok=0;$sendno=0;
		if(is_array($email)){
			if($other['batch']=='1'){

				
				$emailData['email'] = @implode(',',$email);
				$emailData['subject'] = $emailtitle;
				$emailData['content'] = stripslashes($emailcoment);
				$sendid = $notice->sendEmail($emailData);

			}else{ 
				foreach($email as $key=>$v){
					if($emailcoment==''&&$userinfo['tpl']){
						$data=array(
							'username'=>$userinfo[$key]['name'],
							'date'=>$userinfo[$key]['date'],
							'year'=>$userinfo[$key]['year']
						);
						$emailcoment=$notice->_tpl($userinfo['tpl']['content'],$data);
					}
					if($emailtitle==''&&$userinfo['tpl']){
						$data=array(
							'username'=>$userinfo[$key]['name'],
							'date'=>$userinfo[$key]['date'],
							'year'=>$userinfo[$key]['year']
						);
						$emailtitle=$notice->_tpl($userinfo['tpl']['title'],$data);
					} 
					
					
					$emailData['email'] = $v;
					$emailData['subject'] = $emailtitle;
					$emailData['content'] =stripslashes($emailcoment);
					
					
					$emailData['uid'] = $key;
					$emailData['name'] = $userinfo[$key]['name'];
					$emailData['cuid'] = $userinfo[$key]['cuid'];
					$emailData['cname'] = $userinfo[$key]['cuid'];

          $sendid = $notice->sendEmail($emailData);
					if($sendid['status'] != -1){
						$state=1;
						$sendok++;
					}else{
						$state=0;
						$sendno++;
					}
				}
			}
		}
		if($emailalert){
			$this->ACT_layer_msg($sendok."位发送成功，".$sendno."位发送失败！",1,$_SERVER['HTTP_REFERER']);
		}else{
			return $sendok;
		}
	}
}

?>