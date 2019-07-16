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
class rating_model extends model{
	function rating_info($id='',$uid=''){
		if(!$id){
			$id =$this->config['com_rating'];
		}
		if(!$uid){
			$uid = $this->uid;
		}
		$row = $this->DB_select_once("company_rating","`id`='".$id."'");
		
		$value="`rating`='$id',";
		$value.="`rating_name`='".$row['name']."',";
		
		$value.="`job_num`='".$row['job_num']."',";
		$value.="`breakjob_num`='".$row['breakjob_num']."',";

		$value.="`down_resume`='".$row['resume']."',";
		$value.="`invite_resume`='".$row['interview']."',";
		
		
		$value.="`part_num`='".$row['part_num']."',";
 		$value.="`breakpart_num`='".$row['breakpart_num']."',";

		$value.="`lt_job_num`='".$row['lt_job_num']."',";
		$value.="`lt_breakjob_num`='".$row['lt_breakjob_num']."',";

		$value.="`lt_down_resume`='".$row['lt_resume']."',";

		$value.="`zph_num`='".$row['zph_num']."',";
 		
		$value.="`rating_type`='".$row['type']."',";

		$value.="`integral`=`integral`+'".$row['integral_buy']."',";

		if($row['service_time']>0){
			$time= time() + 86400 * $row['service_time'];
		}else{
			$time=0;
		}
		$value.="`vip_etime`='".$time."',";
		$value.="`vip_stime`='".time()."'";


		if($row['integral_buy']>0){
			$dingdan=time().rand(10000,99999);
			$data['order_id']=$dingdan;
			$data['com_id']=$uid;
			$data['pay_remark']='购买企业套餐：'.$row['name'].'，赠送'.$row['integral_buy'];
			$data['pay_state']='2';
			$data['pay_time']=time();
			$data['order_price']=$row['integral_buy'];
			$data['pay_type']=27;
			$data['type']=1;
			
			$this->insert_into("company_pay",$data);
		}
		if($row['coupon']>0){
			$coupon=$this->DB_select_once("coupon","`id`='".$row['coupon']."'");
			$data='';
			$data.="`uid`='".$uid."',";
			$data.="`number`='".time()."',";
			$data.="`ctime`='".time()."',";
			$data.="`coupon_id`='".$coupon['id']."',";
			$data.="`coupon_name`='".$coupon['name']."',";
			$validity=time()+$coupon['time']*86400;
			$data.="`validity`='".$validity."',";
			$data.="`coupon_amount`='".$coupon['amount']."',";
			$data.="`coupon_scope`='".$coupon['scope']."'";
			$this->DB_insert_once("coupon_list",$data);
		}
		return $value;
	}
	function ltrating_info($id='',$uid=''){
		if(!$id){
			$id =$this->config['lt_rating'];
		}
		if(!$uid){
			$uid = $this->uid;
		}
		$row = $this->DB_select_once("company_rating","`id`='$id' and `category`=2");
		$value="`rating`='$id',";
		$value.="`rating_name`='".$row['name']."',";
		$value.="`rating_type`='".$row['type']."',";
		$value.="`lt_job_num`='".$row['lt_job_num']."',";
		$value.="`lt_down_resume`='".$row['lt_resume']."',";
		$value.="`lt_editjob_num`='".$row['lt_editjob_num']."',";
		$value.="`lt_breakjob_num`='".$row['lt_breakjob_num']."',";
		if($row['service_time']>0){
			$time=time()+86400*$row['service_time'];
		}else{
			$time=0;
		}
		$value.="`vip_etime`='$time'";
		if($row['coupon']>0){
			$coupon=$this->DB_select_once("coupon","`id`='".$row['coupon']."'");
			$data.="`uid`='".$uid."',";
			$data.="`number`='".time()."',";
			$data.="`ctime`='".time()."',";
			$data.="`coupon_id`='".$coupon['id']."',";
			$data.="`coupon_name`='".$coupon['name']."',";
			$validity=time()+$coupon['time']*86400;
			$data.="`validity`='".$validity."',";
			$data.="`coupon_amount`='".$coupon['amount']."',";
			$data.="`coupon_scope`='".$coupon['scope']."'";
			$this->DB_insert_once("coupon_list",$data);
		}
		return $value;
	}
}
?>