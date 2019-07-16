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
class admin_recharge_controller extends adminCommon{
	function index_action(){
		extract($_POST);
		if(isset($_POST['insert'])){
			$userarr=@explode(',',trim($userarr));
			if(is_array($userarr)){
				$uidarr=array();
				foreach($userarr as $k=>$v){
					$userarr=$this->obj->DB_select_once("member","`username`='".trim($v)."'");
					if(is_array($userarr)){
						$uidarr[$k]['uid']=$userarr['uid'];
						$uidarr[$k]['usertype']=$userarr['usertype'];
						$uids[]=$userarr['uid'];
					}
				}
			}
			unset($_POST['userarr']);
			unset($_POST['type']);
			unset($_POST['fs']);
			unset($_POST['price_int']);
			unset($_POST['order_price']);
			unset($_POST['insert']);
			unset($_POST['remark']); 
			$num=$price_int;
			$msg=$price_int.$this->config['integral_pricename']; 
			$fsmsg=$fs==1?"充值":"扣除";
			if(is_array($uidarr)){
				foreach($uidarr as $v){ 
					if($v['usertype']=="1"){
						$table="member_statis";
					}elseif($v['usertype']=="2"){
						$table="company_statis";
					}elseif($v['usertype']=="3"){
						$table="lt_statis";
					}
					if($fs==2){
						$statis=$this->obj->DB_select_once($table,"`uid`='".$v['uid']."'","pay,integral");
						if($statis['integral']<$num){
							$num=$statis['integral'];
						} 
					}
					$nid=$this->pay_member($table,$v['uid'],$num,$remark,$v['usertype'],$fs);
				}
			}
			if($nid){
			    $data['msg']="会员(ID:".@implode(',',$uids).")".$fsmsg.$msg."成功！";
			    $this->MODEL('log')->admin_log($data['msg']);
			}else{
			    $data['msg']="不存在该会员,无法".$fsmsg;
			}
			$data['msg'] = preg_replace('/\([^\)]+?\)/x',"",str_replace(array("（","）"),array("(",")"),$data['msg']));
			$data['url']=$_SERVER['HTTP_REFERER'];
			$this->yunset("layer",$data);
		}		
        $this->yunset("headertitle","会员充值");
		$this->yuntpl(array('wapadmin/admin_recharge'));
	}
	function pay_member($table,$uid="",$num,$remark,$usertype,$fs){
		$dingdan=mktime().rand(10000,99999);

		if($fs==1){
			$type=true;
			$integral_v="`integral`=`integral`+$num";
			$_POST['order_type']="adminpay";
		}else{
			$type=false;
			$integral_v="`integral`=`integral`-$num";
			$_POST['order_type']="admincut";
		}
		$_POST['order_id']=$dingdan;
		$_POST['order_price']=$num/$this->config['integral_proportion'];
		$_POST['order_time']=mktime();
		$_POST['order_state']="2";
		$_POST['order_remark']=$remark;
		$_POST['uid']=$uid; 
		$_POST['type']=2; 
		$_POST['integral']=$num;
		$nid=$this->obj->DB_update_all($table,$integral_v,"`uid`='".$uid."'"); 
		if($fs==2)$_POST['type']=5;
		if($nid){
			$this->MODEL('integral')->insert_company_pay($num,2,$uid,$remark,1,'',$type); 
			$nid=$this->obj->insert_into("company_order",$_POST);
		}
		return $nid;
	}
	
}
?>