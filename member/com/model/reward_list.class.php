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
class reward_list_controller extends company{
	function index_action(){
		$this->public_action();
		$urlarr['c']='reward_list';
		$urlarr["page"]="{{page}}";
		$pageurl=Url('member',$urlarr);
		$rows=$this->get_page("change"," `uid`='".$this->uid."' order by id desc",$pageurl,"13");
		$num=$this->obj->DB_select_num("change","`uid`='".$this->uid."'");
		$this->yunset("num",$num);
		$statis = $this->company_satic();
		$statis[integral]=number_format($statis[integral]);
		$this->yunset("statis",$statis);
		$this->yunset("js_def",4);
		$this->com_tpl('reward_list');
	}
	
	function del_action(){
		$IntegralM=$this->MODEL('integral');
		if($this->usertype!='2' || $this->uid==''){
			$this->layer_msg('登录超时！',8,0,$_SERVER['HTTP_REFERER']);
		}else{
			$rows=$this->obj->DB_select_once("change","`uid`='".$this->uid."' and `id`='".(int)$_GET['id']."' ");
			if($rows['id']){
				$this->obj->DB_update_all("reward","`num`=`num`-".$rows['num'].",`stock`=`stock`+".$rows['num']."","`id`='".$rows['gid']."'");
				$IntegralM->company_invtal($this->uid,$rows['integral'],true,"取消商品兑换",true,2,'integral',24);
				$this->obj->DB_delete_all("change","`uid`='".$this->uid."' and `id`='".(int)$_GET['id']."' ");
			}
			$this->obj->member_log("取消兑换");
			$this->layer_msg('取消成功！',9,0,$_SERVER['HTTP_REFERER']);
		}
	}
}
?>