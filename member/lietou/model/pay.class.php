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
class pay_controller extends lietou
{

	function index_action()
	{
		if($_GET['type']!="integral"){
			$rows=$this->obj->DB_select_all("company_rating","`display`='1' and `category`=2 order by sort desc","name,service_price,id");
			$this->yunset("rows",$rows);
		}
        $this->public_action();
		$this->yunset("class","21");
		$this->lietou_tpl('pay');
	}
	function dingdan_action(){
		if($_POST['price'] || $_POST['comvip']|| $_POST['comservice']){
			if($_POST['comvip']){
				$ratinginfo =  $this->obj->DB_select_once("company_rating","`id`='".(int)$_POST['comvip']."'");
				if($ratinginfo['time_start']<time() && $ratinginfo['time_end']>time()){
					$price = $ratinginfo['yh_price'];
				}else{
					$price = $ratinginfo['service_price'];
				}
				if($ratinginfo['category']==1 || $ratinginfo['category']==2){
					$type=1;
				}else{
					$type=5;
				}
			}elseif ($_POST['comservice']){
				$this->get_com();
 				$id=(int)$_POST['comservice'];
				$detailinfo = $this->obj->DB_select_once("lt_service_detail","`id`='".$id."'");
				$statis=$this->obj->DB_select_once("lt_statis","`uid`='".$this->uid."'");
				if ($statis){
					$rating=$statis['rating'];
					$discount=$this->obj->DB_select_once("company_rating"," `id`='".$rating."' ");
					if($discount){
						$dis=$discount['service_discount'];
						if ($dis !=0 && $dis !=100){
							$price = $detailinfo['service_price'] * $dis *0.01;
						}else {
							$price = $detailinfo['service_price'];
						}
					}
				}
				$type=5;
			}elseif($_POST['price_int']){
				$price_int=(int)$_POST['price_int'];
				$integral_proportion = $this->config['integral_proportion'];
				if($this->config['integral_min_recharge']&&$price_int<$this->config['integral_min_recharge']){
					$price_int=$this->config['integral_min_recharge'];
				}
				$price = $price_int/$integral_proportion;
				$type=2;
			}else{
				$this->ACT_layer_msg("参数不正确，请正确填写！",8,$_SERVER['HTTP_REFERER']);
			}
			$dingdan=mktime().rand(10000,99999);
			$data['order_id']=$dingdan;
			$data['order_price']=$price;
			$data['order_time']=mktime();
			$data['order_state']="1";
			$data['order_remark']=$_POST['remark'];
			$data['uid']=$this->uid;
			$data['did']=$this->userdid;
			$data['rating']=(int)$_POST['comvip']?$_POST['comvip']:$_POST['comservice'];
			$data['integral']=$price_int;
			$data['type']=$type;
			$id=$this->obj->insert_into("company_order",$data);
			if($id){
				$this->obj->member_log("录入订单ID".$dingdan);
				$this->ACT_layer_msg("下单成功，请付款！",9,$this->config['sy_weburl']."/member/index.php?c=payment&id=".$id);
			}else{
				$this->ACT_layer_msg("提交失败，请重新提交订单！",8,$_SERVER['HTTP_REFERER']);
			}
		}else{
			$this->ACT_layer_msg("参数不正确，请正确填写！",8,$_SERVER['HTTP_REFERER']);
		}
	}
}
?>