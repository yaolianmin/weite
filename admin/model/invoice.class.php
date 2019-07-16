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
class invoice_controller extends adminCommon{
 
	function set_search(){
		$search_list[]=array("param"=>"status","name"=>'发票状态',"value"=>array("0"=>"未审核","1"=>"已审核","2"=>"未通过","3"=>"已打印","4"=>"已邮寄"));
		 
		$lo_time=array('1'=>'今天','3'=>'最近三天','7'=>'最近七天','15'=>'最近半月','30'=>'最近一个月');
		$search_list[]=array("param"=>"time","name"=>'申请时间',"value"=>$lo_time);
		$this->yunset("search_list",$search_list);
	}
	function index_action(){
		$this->set_search();
		$where=1;
		if(trim($_GET['keyword'])){
			$where.=" and `order_id` like '%".trim($_GET['keyword'])."%'";
			$urlarr['keyword']=$_GET['keyword'];
		}
		if($_GET['status']!=""){
			$where.=" and `status`='".$_GET['status']."'";
			$urlarr['status']=$_GET['status'];
		}
		if($_GET['time']){
			if($_GET['time']=='1'){
				$where.=" and `addtime` >='".strtotime(date("Y-m-d 00:00:00"))."'";
			}else{
				$where .=" and `addtime`>'".strtotime('-'.intval($_GET['time']).' day')."'";
			}
			$urlarr['time']=$_GET['time'];
		}
		if($_GET['order']){
			$where.=" order by ".$_GET['t']." ".$_GET['order'];
			$urlarr['order']=$_GET['order'];
			$urlarr['t']=$_GET['t'];
		}else{
			$where.=" order by status asc,addtime desc";
		}
		$urlarr['page']="{{page}}";
		$pageurl=Url($_GET['m'],$urlarr,'admin');
		$rows=$this->get_page("invoice_record",$where,$pageurl,$this->config['sy_listnum']);
		include (APP_PATH."/config/db.data.php");
		if(is_array($rows)){
			foreach($rows as $v){
				if($v['oid']!=""){
					$oid[]=$v['oid'];
				}
				$uid[]=$v['uid'];
			}
			$order=$this->obj->DB_select_all("company_order","`id` in (".@implode(",",$oid).")","order_state,order_price,id");
			$company=$this->obj->DB_select_all("company","`uid` in (".@implode(",",$uid).")","`uid`,`name`");
			$lt=$this->obj->DB_select_all("lt_info","`uid` in (".@implode(",",$uid).")","`uid`,`realname` as name");
			$member=array_merge($company,$lt);
			foreach($rows as $k=>$v){
				foreach($order as $val){
					if($v['oid']==$val['id']){
						$rows[$k]['order_state']=$arr_data['paystate'][$val['order_state']];
						$rows[$k]['order_price']=$val['order_price'];
					}
				}
				foreach($member as $val){
					if($v['uid']==$val['uid']){
						$rows[$k]['comname']=$val['name'];
					}
				}
			}
		}
		$this->yunset("rows",$rows);
		$this->yuntpl(array('admin/admin_invoice'));
	}
	function status_action(){ 
		$nid=$this->obj->DB_update_all("invoice_record","`status`='".$_POST['status']."',`statusbody`='".$_POST['statusbody']."'","`id` in (".$_POST['pid'].")");
		$nid?$this->ACT_layer_msg("发票状态设置成功！",9,$_SERVER['HTTP_REFERER'],2,1):$this->ACT_layer_msg("发票状态设置失败！",8,$_SERVER['HTTP_REFERER']);
	}
	function statusbody_action(){
		$invoice=$this->obj->DB_select_once("invoice_record","`id`='".$_POST['id']."'",'statusbody');
		echo $invoice['statusbody'];die;
	}
	function edit_action(){
		$invoice=$this->obj->DB_select_once("invoice_record","`id`='".$_GET['id']."'");
		$this->yunset("invoice",$invoice);
		$row=$this->obj->DB_select_once("company_order","`order_id`='".$invoice['order_id']."'","order_price,order_state");
		include (APP_PATH."/config/db.data.php");
		$row['order_state_n']=$arr_data['paystate'][$row['order_state']];
		$this->yunset("row",$row);
		$this->yuntpl(array('admin/admin_invoice_show'));
	}
	function del_action(){
	    $this->check_token();
	    if($_GET['del']||$_GET['id']){
	        if(is_array($_GET['del'])){
	            $layer_type=1;
	            $del=@implode(',',$_GET['del']);
	        }else{
	            $layer_type=0;
	            $del=$_GET['id'];
	        }
	        $this->obj->DB_delete_all("invoice_record","`id` in (".$del.")","");
	        $this->layer_msg("发票记录(ID:".$del.")删除成功！",9,$layer_type,$_SERVER['HTTP_REFERER']);
	    }else{
	        $this->layer_msg("请选择您要删除的信息！",8,1);
	    }
	}
}
?>