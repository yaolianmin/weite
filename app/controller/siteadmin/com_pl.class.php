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
class com_pl_controller extends siteadmin_controller
{
	function set_search(){
		$ad_time=array('1'=>'今天','3'=>'最近三天','7'=>'最近七天','15'=>'最近半月','30'=>'最近一个月');
		$search_list[]=array("param"=>"end","name"=>'发送时间',"value"=>$ad_time);
		$a_time=array('1'=>'今天','3'=>'最近三天','7'=>'最近七天','15'=>'最近半月','30'=>'最近一个月');
		$search_list[]=array("param"=>"r_time","name"=>'回复时间',"value"=>$a_time);
		$this->yunset("search_list",$search_list);
	}
	function index_action(){
		$where=1;
		$this->set_search();
		$UserInfoM=$this->MODEL('userinfo');
		$keyword=trim($_GET['keyword']);
		if($_GET['end']){
			if($_GET['end']=='1'){
				$where.=" and `ctime` >= '".strtotime(date("Y-m-d 00:00:00"))."'";
			}else{
				$where.=" and `ctime` >= '".strtotime('-'.(int)$_GET['end'].'day')."'";
			}
			$urlarr['end']=$_GET['end'];
		}
		if($_GET['r_time']){
			if($_GET['r_time']=='1'){
				$where.=" and `reply_time` >= '".strtotime(date("Y-m-d 00:00:00"))."'";
			}else{
				$where.=" and `reply_time` >= '".strtotime('-'.(int)$_GET['r_time'].'day')."'";
			}
			$urlarr['r_time']=$_GET['r_time'];
		}
		if($keyword){
			if($_GET['type']=="1"){
				$user=$UserInfoM->GetMemberList(array("`username` LIKE '%".$keyword."%'"),array("field"=>"uid,username"));
				foreach($user as $v){
					$userid[]=$v['uid'];
				}
				$where.=" and `uid` in (".@implode(",",$userid).")";
			}elseif($_GET['type']=="2"){
				$com=$UserInfoM->GetUserinfoList(array("`name` LIKE '%".$keyword."%'"),array("field"=>"`uid`,`name`","usertype"=>2));
				foreach($com as $v){
					$comid[]=$v['uid'];
				}
				$where.=" and `cuid` in (".@implode(",",$comid).")";
			}elseif ($_GET['type']=="3"){
				$where.=" and `content` LIKE '%".$keyword."%'";
			}elseif ($_GET['type']=="4"){
			    $where.=" and `reply` LIKE '%".$keyword."%'";
			}
			$urlarr['type']=$_GET['type'];
			$urlarr['keyword']=$_GET['keyword'];
		}
		if($_GET['order']){
			$order=$_GET['order'];
		}else{
			$order="desc";
		}
		$urlarr['page']="{{page}}";
		$urlarr=Url($_GET['m'],$urlarr,'admin');
		$M=$this->MODEL();
		$rows = $M->get_page("company_msg",$where." ORDER BY `id` $order",$urlarr,$this->config['sy_listnum']);
		if(is_array($rows['rows'])&&$rows['rows']){
			$uids=$comids=array();
			foreach($rows['rows'] as $v){
				if(in_array($v['uid'],$uids)==false){$uids[]=$v['uid'];}
				if(in_array($v['cuid'],$comids)==false){$comids[]=$v['cuid'];}
			}
			if($_GET['type']!='1'){
				$user=$UserInfoM->GetMemberList(array("`uid` in(".@implode(',',$uids).")"),array("field"=>"uid,username","special"=>'member'));
			}
			if($_GET['type']!='2'){
				$com=$UserInfoM->GetUserinfoList(array("`uid` in(".@implode(',',$comids).")"),array("field"=>"`uid`,`name`","usertype"=>2,"special"=>'member'));
			}
			foreach($rows['rows'] as $k=>$v){
				$rows['rows'][$k]['content'] = str_replace('"',"",$v['content']);
				$rows['rows'][$k]['reply'] = str_replace('"',"",$v['reply']);
				foreach($user as $val){
					if($v['uid']==$val['uid']){
						$rows['rows'][$k]['username']=$val['username'];
					}
				}
				foreach($com as $val){
					if($v['cuid']==$val['uid']){
						$rows['rows'][$k]['com_name']=$val['name'];
					}
				}
			}
		}
		$this->yunset("get_type", $_GET);
		$this->yunset($rows);
		$this->siteadmin_tpl(array('admin_compl'));
	}

	function del_action(){
		$CompanyM=$this->MODEL('company');
	    if($_POST['del']){
	    	$del=$_POST['del'];
	    	if($del){
	    		if(@is_array($del)){
					$del=@implode(',',$del);
		    	}
				$CompanyM->DeleteComMsg(array("`id` in (".$del.")"));
	    		$this->layer_msg( "评论(ID:".$del.")删除成功！",9,1,$_SERVER['HTTP_REFERER']);
	    	}else{
				$this->layer_msg( "请选择您要删除的信息！",8,1,$_SERVER['HTTP_REFERER']);
	    	}
	    }else if(isset($_GET['id'])){
			$this->check_token();
			$result=$CompanyM->DeleteComMsg(array("id"=>$_GET['id']));
			isset($result)?$this->layer_msg('评论(ID:'.$_GET['id'].')删除成功！',9):$this->layer_msg('删除失败！',8);
		}else{
			$this->layer_msg('非法操作！',8);
		}
	}
	function show_action(){
		if($_POST['id']){
			$data=$this->obj->DB_select_once("company_msg","`id`='".$_POST['id']."'","content,othercontent,reply");
			$data['content']=$data['content'];
			$data['othercontent']=$data['othercontent'];
			$data['reply']=$data['reply'];
			echo json_encode($data);die;
		}
	}
}
?>