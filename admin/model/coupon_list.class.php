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
class coupon_list_controller extends adminCommon{
		 
	function set_search(){
		 
		$search_list[]=array("param"=>"status","name"=>'消费状态',"value"=>array("1"=>"未消费","2"=>"已消费","3"=>"已到期"));
		$search_list[]=array("param"=>"end","name"=>'到期时间',"value"=>array("1"=>"今天","3"=>"最近三天","7"=>"最近七天","15"=>"最近半月","30"=>"最近一个月"));
		$search_list[]=array("param"=>"change","name"=>'消费时间',"value"=>array("1"=>"今天","3"=>"最近三天","7"=>"最近七天","15"=>"最近半月","30"=>"最近一个月"));
		$this->yunset("search_list",$search_list);
	}
	function index_action()
	{
		$this->set_search();
		$this->obj->DB_update_all("coupon_list","`status`='3'","`validity`<'".time()."' and `status`='1'");
		$where="1";
		if($_GET['status']){
			$where.=" and `status`='".$_GET['status']."'";
			$urlarr['status']=$_GET['status'];
		}
		if($_GET['change']){
			if($_GET['change']=='1'){
				$where.=" and `xf_time` >= '".strtotime(date("Y-m-d 00:00:00"))."'";
			}else{
				$where.=" and `xf_time` >= '".strtotime('-'.$_GET['change'].'day')."'";
			}
			$urlarr['change']=$_GET['change'];
		}
		if($_GET['end']){
			$time=time();
			if($_GET['end']=='1'){
				$where.=" and ((`validity` <= '".strtotime(date("Y-m-d 11:59:59"))."') and (`validity` >= '".strtotime(date("Y-m-d 00:00:00"))."'))";
			}else{
				$where.=" and ((`validity` <= '".strtotime('+'.$_GET['end'].'day')."') and (`validity` >= '".$time."') )";
			}
			$urlarr['end']=$_GET['end'];
		}
		if($_GET['receive']){
			if($_GET['receive']=='1'){
				$where.=" and `ctime` >= '".strtotime(date("Y-m-d 00:00:00"))."'";
			}else{
				$where.=" and `ctime` >= '".strtotime('-'.$_GET['receive'].'day')."'";
			}
			$urlarr['receive']=$_GET['receive'];
		}
		if(trim($_GET['keyword']))
		{
			if($_GET['type']=='1'){
				$m_uid=$this->obj->DB_select_all("member","`username` like '%".trim($_GET['keyword'])."%' ","`uid`");
				if(is_array($m_uid) && !empty($m_uid)){
					foreach($m_uid as $k){
						$m_id[]=$k['uid'];
					}
				}
				$where.=" and `uid` in(".@implode(',',$m_id).")";
			}elseif($_GET['type']=='2'){
				$where.=" and `number` like '%".trim($_GET['keyword'])."%'";
			}elseif($_GET['type']=='3'){
				$where.=" and `coupon_name` like '%".trim($_GET['keyword'])."%'";
			}
			$urlarr['type']="".$_GET['type']."";
			$urlarr['keyword']="".$_GET['keyword']."";
		}
		if($_GET['order']){
			if($_GET['order']=="desc"){
				$order=" order by `".$_GET['t']."` desc";
			}else{
				$order=" order by `".$_GET['t']."` asc";
			}

		}else{
			$order=" order by `id` desc";
		}
		if($_GET['order']=="asc"){
			$this->yunset("order","desc");
		}else{
			$this->yunset("order","asc");
		}
		$urlarr['page']="{{page}}";
		$pageurl=Url($_GET['m'],$urlarr,'admin');
		$rows=$this->get_page("coupon_list",$where.$order,$pageurl,$this->config['sy_listnum']);
		if(is_array($rows))
		{
			foreach($rows as $v)
			{
				$uid[]=$v['uid'];
			}
			$member=$this->obj->DB_select_all("member","`uid` in (".@implode(",",$uid).")","`uid`,`username`");
			foreach($rows as $k=>$v)
			{
				foreach($member as $val)
				{
					if($v['uid']==$val['uid'])
					{
						$rows[$k]['username']=$val['username'];
					}
				}
			}
			$this->yunset("rows",$rows);
		}
		$this->yunset("get_type",$_GET);
		$changetime=array('1'=>'一天','3'=>'最近三天','7'=>'最近七天','15'=>'最近半月','30'=>'最近一个月');
        $this->yunset("change",$changetime);
		$receivetime=array('1'=>'一天','3'=>'最近三天','7'=>'最近七天','15'=>'最近半月','30'=>'最近一个月');
        $this->yunset("receive",$receivetime);
		$endtime=array('1'=>'一天','3'=>'最近三天','7'=>'最近七天','15'=>'最近半月','30'=>'最近一个月');
        $this->yunset("end",$endtime);
		$this->yuntpl(array('admin/coupon_list'));
	}
	function del_action()
	{
		if($_GET['del'])
		{
			$this->check_token();
			$del=$_GET['del'];
			if(is_array($del)){
				$del=@implode(',',$del);
				$layer_type=1;
			}else{
				$layer_type=0;
			}
			$id=$this->obj->DB_delete_all("coupon_list","`id` in (".$del.")"," ");
			$del?$this->layer_msg('优惠券记录(ID:'.$del.')删除成功！',9,$layer_type,$_SERVER['HTTP_REFERER']):$this->layer_msg('删除失败！',8,$layer_type,$_SERVER['HTTP_REFERER']);
		}else{
			$this->layer_msg('请选择要删除的内容！',8,0,$_SERVER['HTTP_REFERER']);
		}
	}
}

?>