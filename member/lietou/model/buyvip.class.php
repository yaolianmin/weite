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
class buyvip_controller extends lietou
{
	function index_action()
	{
		$row=$this->obj->DB_select_once("company_rating","`id`='".(int)$_GET['vipid']."' and display='1'");
		$this->yunset("row",$row);
		$coupon=$this->obj->DB_select_all("coupon_list","`uid`='".$this->uid."' and `validity`>'".time()."' and `coupon_scope`<='".$row['service_price']."' and `status`='1'");
		$this->yunset("coupon",$coupon);
		$this->public_action();
		$this->yunset("class","29");
		$this->lietou_tpl('buyvip');
	}
	function buysave_action(){
		$statis=$this->obj->DB_select_once("lt_statis","`uid`='".$this->uid."'");
		$row=$this->obj->DB_select_once("company_rating","`id`='".(int)$_POST['vipid']."'");
		if($row['time_start']<time() && $row['time_end']>time())
		{
			$integral=$row['yh_integral'];
			$price=$row['yh_price'];
		}else{
			$integral=$row['integral_buy'];
			$price=$row['service_price'];
		}
		if($_POST['buytype']==2)
		{
			if($statis['integral']<$integral)
			{
				$this->ACT_layer_msg("你的".$this->config['integral_pricename']."不足，请先充值！",8,"index.php?c=pay");
			}
		}else{
			if($_POST['coupon'])
			{
				$cwhere="`status`='1' and `id`='".(int)$_POST['coupon']."' and `uid`='".$this->uid."' and `validity`>'".time()."' and `coupon_scope`<='".$row['service_price']."'";
				$coupon=$this->obj->DB_select_once("coupon_list",$cwhere);
				$price=$price-$coupon['coupon_amount'];
			}
			if($statis['pay']<$price)
			{
				$this->ACT_layer_msg("你的余额不足，请先充值！",8,"index.php?c=pay");
			}
		}
		
		if($_POST['type']=="vip")
		{
			if($_POST['buytype']==2){
				$nid=$this->obj->lietou_invtal($this->uid,$integral,false,"购买".$row['name'],true,2,'integral',20);
			}else{
				$nid=$this->obj->lietou_invtal($this->uid,$price,false,"购买".$row['name'],true,2,"pay",20);
				if($_POST['coupon'])
				{
					$this->obj->DB_update_all("coupon_list","`status`='2',`xf_time`='".time()."'",$cwhere);
				}
			}
			if($nid){
				if($row['coupon']>0)
				{
					$coupon=$this->obj->DB_select_once("coupon","`id`='".$row['coupon']."'");
					$data.="`uid`='".$this->uid."',";
					$data.="`number`='".time()."',";
					$data.="`ctime`='".time()."',";
					$data.="`coupon_id`='".$coupon['id']."',";
					$data.="`coupon_name`='".$coupon['name']."',";
					$validity=time()+$coupon['time']*86400;
					$data.="`validity`='".$validity."',";
					$data.="`coupon_amount`='".$coupon['amount']."',";
					$data.="`coupon_scope`='".$coupon['scope']."'";
					$this->obj->DB_insert_once("coupon_list",$data);
				}
				$value="`rating`='".$row['id']."',";
				$value.="`rating_name`='".$row['name']."',";
				$value.="`rating_type`='".$row['type']."',";
				$value.="`lt_job_num`='".$row['lt_job_num']."',";
				$value.="`lt_down_resume`='".$row['lt_resume']."',";
				$value.="`lt_editjob_num`='".$row['lt_editjob_num']."',";
				$value.="`lt_breakjob_num`='".$row['lt_breakjob_num']."',";
				if($row['service_time']>0)
				{
					$time=time()+86400*$row['service_time'];
				}else{
					$time=0;
				}
				$value.="`vip_etime`='".$time."'";
				$oid=$this->obj->DB_update_all("lt_statis",$value,"`uid`='".$this->uid."'");
				if($oid){
					$this->obj->member_log("购买会员：".$row['name']);
					$this->ACT_layer_msg("您已购买成功！",9,"index.php");
				}else{
					$this->ACT_layer_msg("购买失败，请稍后再试！",8,$_SERVER['HTTP_REFERER']);
				}
			}else{
				$this->ACT_layer_msg("系统出错，请联系管理员！",8,"index.php");
			}
		}
	}
}
?>