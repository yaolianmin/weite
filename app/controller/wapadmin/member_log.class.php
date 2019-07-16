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
class member_log_controller extends adminCommon{ 
	function index_action(){
		$where = "1";
		$urlarr['c']=$_GET['c'];
		$urlarr["page"]="{{page}}";
		$pageurl=Url($_GET['m'],$urlarr,'admin');
        $M=$this->MODEL(); 
		$list=$M->get_page("member_log",$where." order by `id` desc",$pageurl,$this->config["sy_listnum"]);
		$rows=$list['rows']; 
		if($rows&&is_array($rows)){
			$uids=array();
			foreach($rows as $v){
				if(in_array($v['uid'],$uids)==false){
					$uids[]=$v['uid'];
				} 
			}
			$member=$this->obj->DB_select_all("member","`uid` in (".@implode(",",$uids).")","`uid`,`username`");
			foreach($rows as $k=>$v){
				foreach($member as $val){
					if($v['uid']==$val['uid']){
						$rows[$k]['username']=$val['username'];
					}
				}
			}
		}  
		$list['list']=$list['rows']; 
		$this->yunset($list);
		$this->yunset("rows",$rows);
		$this->yunset("headertitle","日志");
		
		$this->yuntpl(array('wapadmin/admin_memberlog'));
	}

	function del_action(){
		
		
	    if($_GET["del"]){
	    	$del=$_GET["del"];
	    	if(is_array($del)){
				$this->obj->DB_delete_all("member_log","`id` in(".@implode(',',$del).")","");
	    		$this->layer_msg( "后台日志删除(ID:".@implode(',',$del).")成功！",9,1,$_SERVER['HTTP_REFERER']);
	    	}else{
				$this->layer_msg( "请选择您要删除的信息！",8,1,$_SERVER['HTTP_REFERER']);
	    	}
	    }
		
	    if(isset($_GET["id"])){
			$result=$this->obj->DB_delete_all("member_log","`id`='".$_GET["id"]."'","");
 			isset($result)?$this->layer_msg('后台日志删除(ID:'.$_GET['id'].')成功！',9,0,$_SERVER['HTTP_REFERER']):$this->layer_msg('删除失败！',8,0,$_SERVER['HTTP_REFERER']);
		}else{
			$this->ACT_layer_msg("非法操作！",8,$_SERVER['HTTP_REFERER']);
		}
	}

}
?>