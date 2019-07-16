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
class userpay_model extends model{

	function buyZdresume($data){
		
		if($data['resumeid'] && $data['days']){
			
			$resumeid = $data['resumeid'];
 			$days=intval($data['days']);
 			
 			if($days>0 && $resumeid){
 				
				$resume= $this->DB_select_once("resume_expect","`uid`='".$this->uid."' and `id` ='".$resumeid."'");
				if(empty($resume)){
					$return['error'] = '请选择正确的简历置顶！';
				}else {
					$price = $days * $this->config['integral_resume_top']; 
					$price = sprintf("%.2f", $price);
						
 					if ($price < 0.01){
						$return['error'] = '购买总金额不得小于0.01元！';
					} else {

						$dingdan=time().rand(10000,99999);
						$orderData['type']='14';
						$orderData['order_id']=$dingdan;
						$orderData['order_price']=$price;
						$orderData['order_time']=time();
						$orderData['order_state']="1";
						$orderData['order_remark']='简历置顶';
						$orderData['uid']=$this->uid;
						$orderData['did']=$this->userdid;
						$orderData['order_info']=serialize(array('resumeid'=>$data['resumeid'],'days'=>$data['days'],'price'=>$price));
						$id=$this->insert_into("company_order",$orderData);
						 
 						if($id){
 							$orderData['id']=$id;
 							$return['order']=$orderData;
						}else{
							$return['error'] = '订单生成失败！';
						}
					}
				}
 			}else{
				$return['error'] = '请正确选择简历置顶以及置顶的天数！';
			}
			 
		} else {
			$return['error'] = '参数填写错误，请重新设置！';
		}

		return $return;
	}
	
	
	function wtResume($data){
		
		if($data['wteid']){
			
 			$resumeid = $data['wteid'];
			
 			if($resumeid){
 				
				$resume= $this->DB_select_once("resume_expect","`uid`='".$this->uid."' and `id` ='".$resumeid."'","`is_entrust`,`id`");
	 			
				if(empty($resume)){
					
					$return['error'] = '请选择正确的简历委托！';
					
				}else if((int)$this->config['user_trust_number'] < 1 && $resume['is_entrust']=='0'){
					
					$return['error'] = '网站已关闭此服务。';
					
				}else if($resume['is_entrust']=='0'){
					
					$entrust_num=$this->DB_select_num("resume_expect","`uid`='".$this->uid."' and `is_entrust`>'0'");
					
					if($entrust_num < (int)$this->config['user_trust_number']){
						
						$price = $this->config['pay_trust_resume']; 
						$price = sprintf("%.2f", $price);
					
						$oldorder = $this->DB_select_once("company_order","`uid`='".$this->uid."' and `type`='15'");
						
						if($oldorder){
							$odata[]=unserialize($oldorder['order_info']);
							
							if($odata['0']['resumeid']==$data['wteid']){ 
								
								$this->DB_delete_all("company_order", "`uid`='".$this->uid."' and `type`='15'");
								
							}
 						}
						
						$dingdan=time().rand(10000,99999);
						$orderData['type']='15';
						$orderData['order_id']=$dingdan;
						$orderData['order_price']=$price;
						$orderData['order_time']=time();
						$orderData['order_state']="1";
						$orderData['order_remark']='委托简历';
						$orderData['uid']=$this->uid;
						$orderData['did']=$this->userdid;
						$orderData['order_info']=serialize(array('resumeid'=>$data['wteid'],'price'=>$price));
						$id=$this->insert_into("company_order",$orderData);
						
  						if($id){
  							$orderData['id']=$id;
 							$return['order']=$orderData;
						}else{
							$return['error'] = '订单生成失败！';
						}
					}else{
						$return['error'] = '您已委托'.$entrust_num.'份简历，无法再次操作。';
					}
				}
 			}
			 
		} else {
			$return['error'] = '参数填写错误，请重新设置！';
		}

		return $return;
	}
	 
}
?>