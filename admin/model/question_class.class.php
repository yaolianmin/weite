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
class question_class_controller extends adminCommon{
	function index_action(){
		if($_GET[pid]){
			$where="`pid`='".$_GET['pid']."' ";
			$urlarr['pid']=$_GET['pid'];
			$this->yunset("pid",$_GET['pid']);
		}else{
			$where="`pid`='0' ";
		}

		if(trim($_GET['keyword'])){
			$where.=" and `name` like '%".trim($_GET['keyword'])."%' ";
			$urlarr['keyword']=$_GET['keyword'];
			$this->yunset("name",$_GET['name']);
		}
		if($_GET['order'])
		{
			$where.=" order by ".$_GET['t']." ".$_GET['order'];
			$urlarr['order']=$_GET['order'];
			$urlarr['t']=$_GET['t'];
		}else{
			$where.=" order by id desc";
		}
		$urlarr['page']="{{page}}";
		$pageurl=Url($_GET['m'],$urlarr,'admin');
		$q_class=$this->get_page("q_class",$where,$pageurl,$this->config['sy_listnum']);
		$this->yunset("q_class",$q_class);
		$this->yuntpl(array('admin/admin_q_class_list'));
	}
	function add_action(){
		if($_GET['pid']){
			$this->yunset("pid",$_GET['pid']);
		}
		if($_GET['id']){
			$q_class=$this->obj->DB_select_once("q_class","id='".$_GET['id']."'");
			$this->yunset("q_class",$q_class);
			$this->yunset("pid",$q_class['pid']);
		}
		$all_q_class = $this->obj->DB_select_all("q_class","`pid`='0'","`id`,`name`,`pid`");
		$this->yunset("class_list",$all_q_class); 
		$this->yuntpl(array('admin/admin_q_class_add'));
	}

	function save_action(){
		$value.="`name`='".$_POST['name']."',";
		$value.="`pid`='".$_POST['pid']."',";
		$value.="`sort`='".$_POST['sort']."',";
		if($_GET['pid']){
			$url="index.php?m=question_class&pid=".$_GET['pid'];
		}else{
			$url="index.php?m=question_class";
		}
		$intro = str_replace("&amp;","&",$_POST['intro']);
		$q_class=$this->obj->DB_select_once("q_class","id='".(int)$_POST['id']."'");
		$pic = $this->checksrc($_POST['pic'],$q_class['pic']);
		$value.="`pic`='".$pic."',";
		if($_POST['id']){
		    $value.="`intro`='".$intro."'";
		    $nbid=$this->obj->DB_update_all("q_class",$value,"`id`='".(int)$_POST['id']."'");
		    $this->cache_action();
		    isset($nbid)?$this->ACT_layer_msg("问答分类(ID:".$_POST['id'].")更新成功！",9,$url,2,1):$this->ACT_layer_msg("更新失败！",8,$url);
		}else{
		    $value.="`intro`='".$intro."',";
		    $value.="`add_time`='".time()."'";
		    $nbid=$this->obj->DB_insert_once("q_class",$value);
		    $this->cache_action();
		    isset($nbid)?$this->ACT_layer_msg("问答分类(ID:".$nbid.")添加成功！",9,$url,2,1):$this->ACT_layer_msg("添加失败！",8,$url);
		}
	}

	function del_action(){
		$this->check_token();
		if($_GET['del']){
	    	$del=$_GET['del'];
	    	if($del){
				$this->obj->DB_delete_all("q_class","`id` in(".@implode(',',$del).") or `pid` in(".@implode(',',$del).")","");
				$this->del_question($del);
				$this->cache_action();
				$this->layer_msg('问答分类(ID:'.$del.')删除成功！',9,1,$_SERVER['HTTP_REFERER']);
			}else{
				$this->layer_msg('请选择您要删除的类别！',8,1);
	    	}
	    }
		 
	    if(isset($_GET['id'])){
			$result=$this->obj->DB_delete_all("q_class","`id`='".$_GET['id']."' or `pid`='".$_GET['id']."'","");
			$this->del_question(array($_GET['id']));
			$this->cache_action();
			$result?$this->layer_msg('问答分类(ID:'.$_GET['id'].')删除成功！',9, 0, $this->config['sy_weburl'] . '/admin/index.php?m=question_class'):$this->layer_msg('删除失败！',8);
		}else{
			$this->layer_msg('非法操作！',8);
		}
	}
	function del_question($cid){
		$qid=$this->obj->DB_select_all("question","`cid` in(".$cid.")","`id`");
		foreach($qid as $q_v){
			$qids[]=$q_v['id'];
		}
		$qids=@implode(",",$qids);
		$this->obj->DB_delete_all("question","`id` in(".$qids.")","");
		$this->obj->DB_delete_all("answer","`qid` in(".$qids.")","");
		$this->obj->DB_delete_all("answer_review","`qid` in(".$qids.")","");
	}

	function cache_action()
	{
		include(LIB_PATH."cache.class.php");
		$cacheclass= new cache(PLUS_PATH,$this->obj);
		$makecache=$cacheclass->ask_cache("ask.cache.php");
	}
}
?>