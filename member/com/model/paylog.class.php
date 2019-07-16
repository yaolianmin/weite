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
class paylog_controller extends company{
	function index_action(){
		include(CONFIG_PATH."db.data.php");
		$this->yunset("arr_data",$arr_data);
		$this->public_action();
		$statis = $this->company_satic();
		$urlarr=array("c"=>"com","page"=>"{{page}}");
		$pageurl=Url('member',$urlarr);
		if($statis['vip_etime']>time()){
			$statis['vip_time'] = date("Y年m月d日",$statis['vip_etime']);
		}else{
			$statis['vip_time'] = "已过期";
		} 
		$statis[integral]=number_format($statis[integral]);
		$this->yunset("statis",$statis);
		$allprice=$this->obj->DB_select_once("company_pay","`com_id`='".$this->uid."' and `type`='1' and `order_price`<0","sum(order_price) as allprice"); 
		if($allprice['allprice']==''){$allprice['allprice']='0';}
		$this->yunset("integral",number_format(str_replace("-","", $allprice['allprice'])));
		if($_GET['consume']=="ok"){
			$urlarr=array("c"=>"paylog","consume"=>"ok","page"=>"{{page}}");
			$pageurl=Url('member',$urlarr);
			$where="`com_id`='".$this->uid."'";

			$where.="  order by pay_time desc";
			$rows = $this->get_page("company_pay",$where,$pageurl,"10");
			if(is_array($rows)){
				foreach($rows as $k=>$v){
					$rows[$k]['pay_time']=date("Y-m-d H:i:s",$v['pay_time']);
					$rows[$k]['order_price']=str_replace(".00","",$rows[$k]['order_price']);
				}
			} 
			$this->yunset("rows",$rows);
			$this->yunset("ordertype","ok");
		}else{
			$urlarr=array("c"=>"paylog","page"=>"{{page}}");
			$pageurl=Url('member',$urlarr);
			$where="`uid`='".$this->uid."'  order by order_time desc";
			$rows=$this->get_page("company_order",$where,$pageurl,"10");
			foreach($rows as $v){
				$ord[]=$v['order_id'];
			}	
			$ords=@implode(',',$ord);
			$order=$this->obj->DB_select_all("invoice_record","`order_id` in(".$ords.") and `uid`='".$this->uid."'","`status`,`order_id`");
			if($rows&&is_array($rows)&&$this->config['sy_com_invoice']=='1'){
				$last_days=strtotime("-7 day");
				foreach($rows as $key=>$val){
					if($val['order_time']>=$last_days && $val['order_remark']!="使用充值卡"){
						$rows[$key]['invoice']='1';
					
					}
					foreach($order as $k=>$v){
						if($val['order_id']==$v['order_id']){
							$rows[$key]['status']=$v['status'];
						}
					}
				}
				$this->yunset("rows",$rows);
			}
		} 
		if($_POST['submit']){
			if(trim($_POST['order_remark'])==""){
				$this->ACT_layer_msg("备注不能为空！",8,$_SERVER['HTTP_REFERER']);
			}
			$nid=$this->obj->DB_update_all("company_order","`order_remark`='".trim($_POST['order_remark'])."'","`id`='".(int)$_POST['id']."' and `uid`='".$this->uid."'");
			if($nid)
			{
				$this->obj->member_log("修改订单备注");
				$this->ACT_layer_msg("修改成功！",9,$_SERVER['HTTP_REFERER']);
			}else{
				$this->ACT_layer_msg("修改失败！",8,$_SERVER['HTTP_REFERER']);
			}
		}

		$this->yunset("js_def",4);
		$this->com_tpl('paylog');
	}
	function saveinvoice_action(){ 
		if(trim($_POST['invoice_id'])==''||trim($_POST['title'])==''||trim($_POST['link_man'])==''||trim($_POST['link_moblie'])==''||trim($_POST['address'])==''){
			$this->ACT_layer_msg("发票税号、发票抬头、联系人、联系电话、邮寄地址均不能为空！",8,$_SERVER['HTTP_REFERER']);
		}
		if($_POST['rid']){ 
			$data['invoice_id']=trim($_POST['invoice_id']);
			$data['title']=trim($_POST['title']);
			$data['link_man']=trim($_POST['link_man']);
			$data['link_moblie']=trim($_POST['link_moblie']);
			$data['address']=trim($_POST['address']); 
			$data['status']=0;
			$nid=$this->obj->update_once('invoice_record',$data,array('id'=>intval($_POST['rid']),'uid'=>$this->uid));
		}else{
			$value="`order_id`='".$_POST['order_id']."',";
			$value.="`oid`='".$_POST['oid']."',";
			$value.="`uid`='".$this->uid."',";
			$value.="`did`='".$this->userdid."',";
			$value.="`invoice_id`='".trim($_POST['invoice_id'])."',";
			$value.="`title`='".trim($_POST['title'])."',";
			$value.="`link_man`='".trim($_POST['link_man'])."',";
			$value.="`link_moblie`='".trim($_POST['link_moblie'])."',";
			$value.="`address`='".trim($_POST['address'])."',";
			$value.="`status`='0',";
			$value.="`addtime`='".time()."'";
			$nid=$this->obj->DB_insert_once("invoice_record",$value);
			if($nid){$this->obj->update_once("company_order",array("is_invoice"=>'1'),array('order_id'=>$_POST['order_id'],'uid'=>$this->uid));}
		}
		$nid?$this->ACT_layer_msg("申请成功！",9,$_SERVER['HTTP_REFERER']):$this->ACT_layer_msg("申请失败！",8,$_SERVER['HTTP_REFERER']);
	}
	function del_action(){
		if($this->usertype!='2' || $this->uid==''){
			echo '0';die;
		}else{
			$oid=$this->obj->DB_select_once("company_order","`uid`='".$this->uid."' and `id`='".(int)$_GET['id']."' and `order_state`='1'");
			if(empty($oid)){
				echo '0';die;
			}else{
				$this->obj->DB_delete_all("company_order","`id`='".$oid['id']."' and `uid`='".$this->uid."'");
				$this->obj->DB_delete_all("invoice_record","`oid`='".$oid['id']."'  and `uid`='".$this->uid."'");
				echo '1';die;
			}
		}
	}
	function invoice_action()
	{
		$invoice_record=$this->obj->DB_select_once("invoice_record","`order_id`='".$_POST['order_id']."' and `uid`='".$this->uid."'");
		if($invoice_record['id']){
			$data['status']='1';
			$data['id']=$invoice_record['id'];
			$data['invoice_id']=$invoice_record['invoice_id'];
			$data['title']=$invoice_record['title'];
			$data['link_man']=$invoice_record['link_man'];
			$data['link_moblie']=$invoice_record['link_moblie'];
			$data['address']=$invoice_record['address'];
		}else{
			$data['status']='0';
		}
		$data = json_encode($data);
		echo $data;die;
	}
}
?>