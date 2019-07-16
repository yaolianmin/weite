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
class admin_withdraw_controller extends adminCommon{
	 
	function set_search(){
		
		$search_list[]=array("param"=>"order_state","name"=>'提现状态',"value"=>array("0"=>"等待审核","1"=>"提现成功","2"=>"提现失败"));
		$lo_time=array('1'=>'今天','3'=>'最近三天','7'=>'最近七天','15'=>'最近半月','30'=>'最近一个月');
		$search_list[]=array("param"=>"time","name"=>'提现时间',"value"=>$lo_time);
		$this->yunset("search_list",$search_list);
	}
	function index_action(){
		 
		$this->set_search();
		$where="1 ";
		
		if($_GET['news_search']){
			if ($_GET['keyword']!=""){
				if ($_GET['typeca']=='1'){
					$where .=" and `order_id` like '%".trim($_GET['keyword'])."%'";				   
			     }elseif($_GET['typeca']=='2'){
				    $orderinfo=$this->obj->DB_select_all("member","`username` like '%".trim($_GET['keyword'])."%'","`uid`");
					if (is_array($orderinfo))
					{
						foreach ($orderinfo as $val)
						{
							$orderuids[]=$val['uid'];
						}
						$oruids=@implode(",",$orderuids);
					}
					$where.=" and `uid` in (".$oruids.")";
			     }
			     $urlarr['typeca']=$_GET['typeca'];
			     $urlarr['keyword']=$_GET['keyword'];				 
			}
			 $urlarr['news_search']=$_GET['news_search'];
		}
		if($_GET['time']){
			if($_GET['time']=='1'){
				$where.=" and `time` >='".strtotime(date("Y-m-d 00:00:00"))."'";
			}else{
				$where .=" and `time`>'".strtotime('-'.intval($_GET['time']).' day')."'";
			}
			$urlarr['time']=$_GET['time'];
		}
		if($_GET['order_state']!=""){
            $where.=" and `order_state`='".$_GET['order_state']."'";
			$urlarr['order_state']=$_GET['order_state'];
	    }
		
		
		$where.=" order by id desc";
	
		$urlarr['page']="{{page}}";
		$pageurl=Url($_GET['m'],$urlarr,'admin');
		$rows=$this->get_page("member_withdraw",$where,$pageurl,$this->config['sy_listnum']);
		include (APP_PATH."/config/db.data.php");
		if(is_array($rows)){
			foreach($rows as $k=>$v){
				$rows[$k]['order_state_n']=$arr_data['withdrawstate'][$v['order_state']];
				$classid[]=$v['uid'];
			}
			$group=$this->obj->DB_select_all("member","uid in (".@implode(",",$classid).")","`uid`,`username`");
			$company=$this->obj->DB_select_all("company","`uid` in (".@implode(",",$classid).")","`uid`,`name`");
			$lt=$this->obj->DB_select_all("lt_info","`uid` in (".@implode(",",$classid).")","`uid`,`realname`");
			$resume=$this->obj->DB_select_all("resume","`uid` in (".@implode(",",$classid).")","`uid`,`name`");
			foreach($rows as $k=>$v){
				foreach($company as $val){
					if($v['uid']==$val['uid']){
						$rows[$k]['comname']=$val['name'];
					}
				}				
				foreach($group as $val){
					
						if($v['uid']==$val['uid']){
							$rows[$k]['username']=$val['username'];
						}					
				}
				
				foreach($lt as $val){
					if($v['uid']==$val['uid']){
						$rows[$k]['comname']=$val['realname'];
					}
				}
				foreach($resume as $val){
					if($v['uid']==$val['uid']){
						$rows[$k]['comname']=$val['name'];
					}
				}
			}
		}
        $this->yunset("get_type", $_GET);
		$this->yunset("rows",$rows);
		$this->yuntpl(array('admin/admin_withdraw'));
	}
	
	
	function setpay_action(){
		$del=(int)$_GET['id'];
		$this->check_token();
		$row=$this->obj->DB_select_once("member_withdraw","`id`='$del'");
		
		if($row['order_state']!='1'){

			$TableNameListTwo=array(1=>'member_statis',2=>'company_statis',3=>'lt_statis');
			
			$TableNameTwo=$TableNameListTwo[$row['usertype']];

			$statis = $this->obj->DB_select_once($TableNameTwo,"`uid`='".(int)$row['uid']."'");

			$M = $this->MODEL('pack');
			
			$wxpay = $M->transfersWxPay($row);
			
			$this->obj->DB_update_all("member_withdraw","`order_state`='".$wxpay['orderState']."',`order_remark`='".$wxpay['remark']."'","`id`='".$row['id']."'");
			
			if($wxpay['orderState']=='1'){
				$TableNameList=array(1=>'member_statis',2=>'company_statis');
				
				$TableNameTwo=$TableNameList[$row[usertype]];

				$this->obj->DB_update_all($TableNameTwo,"`freeze`='".($statis['freeze']-$row['price'])."'","`uid`='".(int)$row['uid']."'");

				$this->layer_msg("管理员手动提现(ID:".$del.")确认成功！",9,0,$_SERVER['HTTP_REFERER']);
				
			}else{
				$error = '提现失败:'.$wxpay['remark'];
				$this->layer_msg($error,8,0,$_SERVER['HTTP_REFERER']);
				
			}
		}else{

			$this->layer_msg("提现单已成功提现，请勿重复提现！",8,0,$_SERVER['HTTP_REFERER']);
		}
	}
	 
	function del_action(){

		$this->check_token();
	    
	    if(isset($_GET['id'])){
			 
			$M = $this->MODEL('pack');
			
			$result=$M->delWithdrawOrder($_GET['id']);

			$result?$this->layer_msg('提现记录(ID:'.$_GET['id'].')删除成功！',9,0,$_SERVER['HTTP_REFERER']):$this->layer_msg('删除失败！',8,0,$_SERVER['HTTP_REFERER']);
		}else{
			$this->ACT_layer_msg("非法操作！",8,$_SERVER['HTTP_REFERER']);
		}
	}
}
?>