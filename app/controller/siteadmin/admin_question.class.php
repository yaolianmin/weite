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
class admin_question_controller extends siteadmin_controller
{
	
	function set_search(){
		$search_list[]=array("param"=>"is_recom","name"=>'是否推荐',"value"=>array("1"=>"已推荐","2"=>"未推荐"));
		$ad_time=array('1'=>'今天','3'=>'最近三天','7'=>'最近七天','15'=>'最近半月','30'=>'最近一个月');
		$search_list[]=array("param"=>"end","name"=>'提问时间',"value"=>$ad_time);
		$this->yunset("search_list",$search_list);
	}
	function index_action()
	{
		$this->set_search();
		$where=1;
		if($_GET['id'])
		{
			$where.=" and `id`='".$_GET['id']."'";
			$urlarr['id']=$_GET['id'];
		}
		if($_GET['end']){
			if($_GET['end']=='1'){
				$where.=" and `add_time` >= '".strtotime(date("Y-m-d 00:00:00"))."'";
			}else{
				$where.=" and `add_time` >= '".strtotime('-'.(int)$_GET['end'].'day')."'";
			}
			$urlarr['end']=$_GET['end'];
		}
		if(trim($_GET['keyword'])){
			if ($_GET['type']=='1'){
				$where.=" and `title` like '%".trim($_GET['keyword'])."%' ";
			}elseif ($_GET['type']=='2'){
				$queinfo=$this->obj->DB_select_all("member","`username` like '%".trim($_GET['keyword'])."%'","`uid`");
				if (is_array($queinfo)){
					foreach ($queinfo as $k=>$v){
						$queuids[]=$v['uid'];
					}
					$uids=@implode(",",$queuids);
				}
				$where.=" and `uid` in (".$uids.")";
			}
			$urlarr['keyword']=$_GET['keyword'];
			$urlarr['type']=$_GET['type'];
			$this->yunset("name",$_GET['name']);
		}
		if ($_GET['is_recom']=='2'){
			$where.=" and `is_recom`='0'";
		}elseif($_GET['is_recom']=='1'){
			$where.=" and `is_recom`='1'";
		}
 		
		if($this->config['sy_web_site']=='1'){
			$wheres=1;
			if($_SESSION['admin_city']){
				$wheres.=" and `cityid`='".$_SESSION['admin_city']."'";
			}
			if($_SESSION['admin_hy']){
				$wheres.=" and `hy`='".$_SESSION['admin_hy']."'";
			}
			if($_SESSION['def_city'] || $_SESSION['def_hy'])
			{
				if($_SESSION['def_city']){
					$session[]="`cityid` in (".$_SESSION['def_city'].")";
				}
				if($_SESSION['def_hy']){
					$session[]="`hy` in (".$_SESSION['def_hy'].")";
				}
				$wheres.=" and (".@implode(" or ",$session).")";
			}
			$com=$this->obj->DB_select_all("company",$wheres,"`uid`");
			$ltwheres=1;
			if($_SESSION['admin_city']){
				$ltwheres.=" and `cityid`='".$_SESSION['admin_city']."'";
			}
			if($_SESSION['admin_tcity']){
				$ltwheres.=" and `three_cityid`='".$_SESSION['admin_tcity']."'";
			}
			if($_SESSION['def_city'] || $_SESSION['def_tcity'])
			{
				if($_SESSION['def_city']){
					$session[]="`cityid` in (".$_SESSION['def_city'].")";
				}
				if($_SESSION['def_tcity']){
					$session[]="`three_cityid` in (".$_SESSION['def_tcity'].")";
				}
				$ltwheres.=" and (".@implode(" or ",$session).")";
			}
			$lt=$this->obj->DB_select_all("lt_info",$ltwheres,"`uid`");
			if(is_array($com))
			{
				foreach($com as $v)
				{
					$uid[]=$v['uid'];
				}
			}
			if(is_array($lt))
			{
				foreach($lt as $v)
				{
					$uid[]=$v['uid'];
				}
			}
			if($uid&&is_array($uid)){
				$where.=" AND `uid` in (".@implode(",",$uid).")";
			}
		}
		
		if($_GET['order']){
			$where.=" order by ".$_GET['t']." ".$_GET['order'];
			$urlarr['order']=$_GET['order'];
			$urlarr['t']=$_GET['t'];
		}else{
			$where.=" order by id desc";
		}
		$urlarr['page']="{{page}}";
		$pageurl=Url($_GET['m'],$urlarr,'admin');
        $M=$this->MODEL();
		$PageInfo=$M->get_page("question",$where,$pageurl,$this->config['sy_listnum']);
        $this->yunset($PageInfo);
        $question=$PageInfo['rows'];
		$m_id=array();
		$c_id=array();
		foreach($question as $k=>$v){
			if(in_array($v['uid'],$m_id)==false){
				$m_id[]=$v['uid'];
			}
			if(in_array($v['cid'],$c_id)==false){
				$c_id[]=$v['cid'];
			}
		}
		$m_ids=@implode(',',$m_id);
		$c_ids=@implode(',',$c_id);
		$member=$M->DB_select_all("member","`uid` in(".$m_ids.")","`uid`,`username`");

		$q_class=$M->DB_select_all("q_class","`id` in(".$c_ids.")","`id`,`name`");
		foreach($question as $key=>$val){
			foreach($member as $m_v){
				if($val['uid']==$m_v['uid']){
					$question[$key]['username']=$m_v['username'];
				}
			}
			foreach($q_class as $v){
				if($val['cid']==$v['id']){
					$question[$key]['classname']=$v['name'];
				}
			}
		}
        $this->yunset("get_type", $_GET);
		$this->yunset("question",$question);
		$this->siteadmin_tpl(array('admin_question_list'));
	}
	function recommend_action(){
		$nid=$this->obj->DB_update_all("question","`".$_GET['type']."`='".$_GET['rec']."'","`id`='".$_GET['id']."'");
		echo $nid?1:0;die;
	}

	function add_action(){

		if($_GET['id']){
			$question_show=$this->obj->DB_select_once("question","id='".$_GET['id']."'");
			$this->yunset("question_show",$question_show);
		}
		$all_class = $this->obj->DB_select_all("q_class","1 order by sort","`id`,`name`,`pid`");
		foreach($all_class as $k=>$v){
			if($v['id']==$question_show['cid']){
				$pid=$v['pid'];
				$all_class[$k]['is_select']='1';

			}
			if($v['pid']=='0'){
				$p_class[]=$v;
			}
		}
		$this->yunset("class_list",$p_class);
		$this->yunset("pid",$pid);
		$s_class = $this->obj->DB_select_all("q_class","`pid`='".$pid."' order by sort","`id`,`name`,`pid`");
		$this->yunset("s_class",$s_class);
		$this->siteadmin_tpl(array('admin_question_add'));
	}

	function get_class_action(){
		if($_POST['pid']){
			$q_class=$this->obj->DB_select_all("q_class","pid='".$_POST['pid']."' order by `sort`","`id`,`name`,`pid`");
			if($q_class[0]){
				$html='';
				foreach($q_class as $v){
					$html.="<option value='".$v['id']."'>".$v['name']."</option>";
				}
				echo $html;
			}else{
				echo $html="<option value='0'>该分类下暂无子类！</option>";
			}

		}
	}

	function save_action(){
		if($_POST['update']){
			$value.="`title`='".$_POST['title']."',";
			$value.="`cid`='".$_POST['cid']."',";
			$value.="`visit`='".$_POST['visit']."',";
			$value.="`is_recom`='".$_POST['is_recom']."',";
			$content = str_replace("&amp;","&",$_POST['content']);
			$value.="`content`='".$content."'";
			$nbid=$this->obj->DB_update_all("question",$value,"`id`='".$_POST['id']."'");
			isset($nbid)?$this->ACT_layer_msg("问答(ID:".$_POST['id'].")更新成功！",9,"index.php?m=admin_question",2,1):$this->ACT_layer_msg("更新失败！",8,"index.php?m=admin_question");
		}
	}

	function del_action(){

		$this->check_token();
		if($_GET['del']){

	    	$del=$_GET['del'];
	    	if($del){
	    		if(is_array($del)){
					$ids=@implode(',',$del);
					$this->obj->DB_delete_all("question","`id` in(".$ids.")","");
					$this->obj->DB_delete_all("answer","`qid` in(".$ids.")","");
					$this->obj->DB_delete_all("answer_review","`qid` in(".$ids.")","");
			    }else{
					$ids=$del;
	    		 	$this->obj->DB_delete_all("question","`id`='$del'");
					$this->obj->DB_delete_all("answer","`qid`='".$del."'","");
					$this->obj->DB_delete_all("answer_review","`qid`='".$del."'","");
	    		}
	    		$this->layer_msg( "问答(ID:".$ids.")删除成功！",9,1,$_SERVER['HTTP_REFERER']);
	    	}else{
				$this->layer_msg( "请选择您要删除的信息！",8,1,$_SERVER['HTTP_REFERER']);
	    	}
	    }
	    if(isset($_GET['id'])){
			$result=$this->obj->DB_delete_all("answer","`qid`='".$_GET['id']."'","");
			$this->obj->DB_delete_all("answer_review","`qid`='".$_GET['id']."'","");
			$this->obj->DB_delete_all("question","`id`='".$_GET['id']."'");
			isset($result)?$this->layer_msg('问答(ID:'.$_GET['id'].')删除成功！',9,0,$_SERVER['HTTP_REFERER']):$this->layer_msg('删除失败！',8,0,$_SERVER['HTTP_REFERER']);
		}else{
			$this->layer_msg('非法操作！',8,0,$_SERVER['HTTP_REFERER']);
		}
	}

	function get_answer_action(){
		if($_GET['aid'])
		{
			$where="`id`='".$_GET['aid']."'";
		}else{
			$where="`qid`='".$_GET['id']."'";
		}
		$all_answer = $this->obj->DB_select_all("answer",$where." order by add_time desc");
		foreach($all_answer as $v){
			$a_uid[]=$v['uid'];
		}
		$a_ids=@implode(',',$a_uid);
		$a_member=$this->obj->DB_select_all("member","`uid` in(".$a_ids.")","`uid`,`username`");
		foreach($all_answer as $key=>$val){
			foreach($a_member as $v){
				if($val['uid']==$v['uid']){
					$all_answer[$key]['username']=$v['username'];
				}
			}
		}
		$this->yunset("qid",$_GET['id']);
		$this->yunset("all_answer",$all_answer);
		$this->siteadmin_tpl(array('admin_answer_list'));
	}


	function add_answer_action(){
		if($_GET['id']){
			$answer=$this->obj->DB_select_alls("question","answer","b.`id`='".$_GET['id']."' and b.`qid`=a.`id`");
			$this->yunset("answer",$answer[0]);
		}
		if($_GET['qid']){
			$answer=$this->obj->DB_select_once("question","`id`='".$_GET['qid']."'","title,id as qid");
			$this->yunset("answer",$answer);
		}
		if($_GET['back_url']){
			$this->yunset("back_url",$_GET['back_url']);
		}
		$this->siteadmin_tpl(array('admin_add_answer'));
	}

	function save_answer_action(){
		$url="index.php?m=admin_question&c=get_answer&id=".$_POST['qid'];
		if($_POST['update']){
			$value.="`oppose`='".$_POST['oppose']."',";
			$value.="`support`='".$_POST['support']."',";
			$content = str_replace("&amp;","&",$_POST['content']);
			$value.="`content`='".$content."'";
			$a_id=$this->obj->DB_update_all("answer",$value,"`id`='".$_POST['id']."'");
			$a_id?$this->ACT_layer_msg("回答(ID:".$_POST['id'].")更新成功！",9,$url,2,1):$this->ACT_layer_msg("更新失败！",8,$url);
		}
	}

	function del_answer_action(){

		$this->check_token();
		if($_GET['del']){
	    	$a_del=$_GET['del'];
	    	if($a_del){
	    		if(is_array($a_del)){
					$a_ids=@implode(',',$a_del);
					$this->obj->DB_delete_all("answer", "`id` in(".$a_ids.")","");
					$this->obj->DB_delete_all("answer_review","`aid` in(".$a_ids.")","");
			    }else{
					$a_ids=$a_del;
	    		 	$this->obj->DB_delete_all("answer","`id`='$a_del'");
					$this->obj->DB_delete_all("answer_review","`aid`='".$a_del."'","");
	    		}
	    		$this->obj->DB_update_all("question","`answer_num`=`answer_num`-".count($a_del),"`id`='".$_GET['qid']."'");
	    		$this->layer_msg( "用户回答(".$a_ids.")删除成功！",9,1,$_SERVER['HTTP_REFERER']);
	    	}else{
				$this->layer_msg( "请选择您要删除的信息！",8,1,$_SERVER['HTTP_REFERER']);
	    	}
	    }
		
	    if(isset($_GET["id"])){
			$this->obj->DB_delete_all("answer_review","`aid`='".$_GET['id']."'","");	 
			$result=$this->obj->DB_delete_all("answer", "`id`='".$_GET['id']."'");
			$this->obj->DB_update_all("question","`answer_num`=`answer_num`-1","`id`='".$_GET['qid']."'");
			isset($result)?$this->layer_msg('用户回答('.$_GET['qid'].')删除成功！',9,0,$_SERVER['HTTP_REFERER']):$this->layer_msg('删除失败！',8,0,$_SERVER['HTTP_REFERER']);
		}else{
			$this->layer_msg('非法操作！',3,0,$_SERVER['HTTP_REFERER']);
		}
	}

	function get_comment_action(){
		if($_GET['aid'])
		{
			$where="b.`id`='".$_GET['aid']."'";
		}else{
			$where="b.`aid`='".$_GET['id']."'";
		}
		$answer_review=$this->obj->DB_select_alls("member","answer_review",$where." and b.`uid`=a.`uid`","b.`content`,b.`id`,a.`username`,b.`add_time`,b.`qid`");
		$this->yunset("answer_review",$answer_review);
		$this->siteadmin_tpl(array('admin_answer_review'));
	}

	function add_review_action(){ 
		if($_GET['id']){
			$review_show=$this->obj->DB_select_once("answer_review","`id`='".$_GET['id']."'");
			$this->yunset("review_show",$review_show);
		}else{
			$this->yunset("aid",$_GET['aid']);
			$this->yunset("qid",$_GET['qid']);
		}
		$this->siteadmin_tpl(array('admin_add_review'));
	}

	function save_review_action(){
		$url="index.php?m=admin_question&c=get_comment&id=".$_POST['aid'];
		if($_POST['update']){
			$review.="`content`='".$_POST['content']."'";
			$r_id=$this->obj->DB_update_all("answer_review",$review,"`id`='".$_POST['id']."'");
			isset($r_id)?$this->ACT_layer_msg("问答评论(ID:".$_POST['id'].")更新成功！",9,$url,2,1):$this->ACT_layer_msg("更新失败！",8,$url);
		}
	}
	function del_review_action(){
		$this->check_token();
		if($_GET['del'])
		{
	    	$del=$_GET['del'];
	    	if($del){
	    		if(is_array($del)){
					$r_ids=@implode(',',$del);
					$this->obj->DB_delete_all("answer_review", "`id` in(".$r_ids.")","");
			    }else{
					$r_ids=$del;
	    		 	$this->obj->DB_delete_all("answer_review","`id`='".$del."'");
	    		}
	    		$this->obj->DB_update_all("answer","`comment`=`comment`-".count($del),"`id`='".$_GET['aid']."'");
	    		$this->layer_msg( "问答评论(ID:".$r_ids.")删除成功！",9,1,$_SERVER['HTTP_REFERER']);
	    	}else{
				$this->layer_msg( "请选择您要删除的信息！",8,1,$_SERVER['HTTP_REFERER']);
	    	}
	    }
	}
}
?>