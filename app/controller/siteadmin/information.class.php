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
class information_controller extends siteadmin_controller{
	function index_action(){ 
		$this->siteadmin_tpl(array('information'));
	}
	function save_action(){
		extract($_POST); 
		if($userarr==''){
			$this->ACT_layer_msg("手机号码不能为空！",8,$_SERVER['HTTP_REFERER']);
		}
		if(trim($content)==''){
			$this->ACT_layer_msg("请输入短信内容！",8,$_SERVER['HTTP_REFERER']);
		}
		$UserInfoM=$this->MODEL('userinfo');
		$ResumeM=$this->MODEL('resume'); 
		$LtM=$this->MODEL('lietou'); 
		$uidarr=array();
		if($all==4){
			$mobliesarr=@explode(',',$userarr);
			$userrows=$this->obj->DB_select_all("member","`moblie` in(".$userarr.")","`moblie`,`uid`,`usertype`");		 
			$moblies=array();
			foreach($userrows as $v){
				$moblies[]=$v['moblie'];
			}    
		}else{
			$userrows=$UserInfoM->GetMemberList(array("usertype"=>$all),array("field"=>"`moblie`,`uid`,`usertype`"));  
		}
		
		if(is_array($userrows)&&$userrows){ 
			$user=$com=$lt=$userinfo=array();
			foreach($userrows as $v){
				if($v['usertype']=='1'){$user[]=$v['uid'];}
				if($v['usertype']=='2'){$com[]=$v['uid'];}
				if($v['usertype']=='3'){$lt[]=$v['uid'];}
				$uidarr[$v['uid']]=$v["moblie"]; 
			}
			 
			if($user&&is_array($user)){
				$resume=$ResumeM->ResumeAll(array("`uid` in(".@implode(',',$user).")"),array("field"=>"`name`,`uid`")); 
				foreach($resume as $val){
					$userinfo[$val['uid']]=$val['name'];
				}
			}
			if($com&&is_array($com)){
				$company=$UserInfoM->GetUserinfoList(array("`uid` in(".@implode(',',$com).")"),array("field"=>"`name`,`uid`","usertype"=>2)); 
				foreach($company as $val){
					$userinfo[$val['uid']]=$val['name'];
				}
			}
			if($lt&&is_array($lt)){
				$lt_info=$LtM->GetLtinfoList(array("`uid` in(".@implode(',',$lt).")"),array("field"=>"`realname`,`uid`")); 
				foreach($lt_info as $val){
					$userinfo[$val['uid']]=$val['realname'];
				}
			}
		}
		if($all==4){
			foreach($mobliesarr as $v){
				if(in_array($v,$moblies)==false&&CheckMoblie($v)){
					$uidarr[]=$v;
				}
			}
		}
		if(is_array($uidarr)&&$uidarr){
			if($this->config["sy_msguser"]=="" || $this->config["sy_msgpw"]=="" || $this->config["sy_msgkey"]==""||$this->config['sy_msg_isopen']!='1'){ 
				$this->ACT_layer_msg("还没有配置短信！",8,$_SERVER['HTTP_REFERER']);
			}
			
      $notice = $this->MODEL('notice');
			$result = '没有待发送的短信';
			foreach($uidarr as $key=>$v){
				if($userinfo[$key]==''){
					$key='';
				}
				$msguser=$this->config["sy_msguser"];
				$msgpw=$this->config["sy_msgpw"];
				$msgkey=$this->config["sy_msgkey"];
				$result = $notice->sendSMS(array('mobile' => $v, 'content' => $content,
					'uid' => $key, 'name' => $userinfo[$key]
				));
				$result = $result['msg'];
			}
		} 
		$this->ACT_layer_msg($result,14,$_SERVER['HTTP_REFERER'],2,1);
	}
}
?>