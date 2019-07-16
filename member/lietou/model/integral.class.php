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
class integral_controller extends lietou{
	function index_action(){
		
		$baseInfo			= false;	
		$photo				= false;	
		$emailChecked		= false;	
		$phoneChecked		= false;	
		$pay_remark         =false;
		$question        	=false;		
		$answer       		=false;	
		$answerpl           =false;	
		$yyzz				= false;
		
		$row = $this->obj->DB_select_once("lt_info",'`uid` = '.$this->uid,
			"`realname`,`com_name`,
			`photo`,`email_status`,`moblie_status`,
			`yyzz_status`");
		
		if(is_array($row) && !empty($row)){
			if($row['realname'] != '' && $row['com_name'] != '' )
				$baseInfo = true;
			
			if($row['photo'] != '') $photo = true;
			if($row['email_status'] != 0) $emailChecked = true;
			if($row['moblie_status'] != 0) $phoneChecked = true;
			if($row['yyzz_status'] != 0) $yyzz = true;
			
		}
		if($this->config['integral_question_type']=="1"){
			$question=$this->max_time('发布问题');
		}
		if($this->config['integral_answer_type']=="1"){
			$answer=$this->max_time('回答问题');
		}
		if($this->config['integral_answerpl_type']=="1"){
			$answerpl=$this->max_time('评论回答');
		}
		
		$statusList = array(
			'baseInfo'		=>$baseInfo,
			'photo'			=>$photo,
			'emailChecked'	=>$emailChecked,
			'phoneChecked'	=>$phoneChecked,
			'pay_remark'	=>$pay_remark,
			'question'	    =>$question,
			'answer'	    =>$answer,
			'answerpl'	    =>$answerpl,
			'yyzz'			=> $yyzz	
		);
		$this->yunset("statusList",$statusList);
		$this->public_action();
		$this->lietou_tpl('integral');
	}
	
}	
?>