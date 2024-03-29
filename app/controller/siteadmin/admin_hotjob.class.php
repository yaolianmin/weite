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
class admin_hotjob_controller extends siteadmin_controller{
	
	function set_search(){
		
		$UserinfoM=$this->MODEL('userinfo');
		$rating=$UserinfoM->GetRatinginfoAll(array('category'=>1),array("field"=>"`id`,`name`","orderby"=>'sort'));
		if(!empty($rating)){
			foreach($rating as $k=>$v){
                 $ratingarr[$v['id']]=$v['name'];
			}
		}
		$nrating=array();
		foreach($rating as $val){
			$nrating[$val['id']]=$val['name'];
		}
		$this->yunset("rating", $nrating);
		$edtime=array('1'=>'7天内','2'=>'一个月内','3'=>'半年内','4'=>'一年内');
		$this->yunset("edtime",$edtime);
		
		$search_list[]=array("param"=>"rating","name"=>'会员等级',"value"=>$ratingarr);
		$search_list[]=array("param"=>"time","name"=>'到期时间',"value"=>$edtime);
		$this->yunset("ratingarr",$ratingarr);
		$this->yunset("search_list",$search_list);
	}
	function index_action(){
		$this->set_search();
		$UserinfoM=$this->MODEL('userinfo');
		$where=$mwhere="1";
		$uids=array();
		if($_GET['time']){
            if($_GET['time']=='1'){
            	$num="+7 day"; 
            }elseif($_GET['time']=='2'){
				$num="+1 month"; 
            }elseif($_GET['time']=='3'){
				$num="+6 month"; 
            }elseif($_GET['time']=='4'){
                $num="+1 year"; 
            }
			$where.=" and `time_end`>'".time()."' and `time_end`<'".strtotime($num)."'";
			$urlarr['time']=$_GET['time'];
		}
		if($_GET['rating']){
			$where.=" and  `rating_id`='".$_GET['rating']."'";
			$urlarr['rating']=$_GET['rating'];
		}
	    if(trim($_GET['keyword'])){
			if($_GET['ctype']=='2'){
	    		$where .= " and `beizhu` like '%".trim($_GET['keyword'])."%' ";
	    	}else{
	    		$where .= " and `username` like '%".trim($_GET['keyword'])."%' ";
	    	}
			$urlarr['keyword']=$_GET['keyword'];
		}
		if($_GET['order'])
		{
			if($_GET['t']=="time")
			{
				$where.=" order by `lastupdate` ".$_GET['order'];
			}else{
				$where.=" order by ".$_GET['t']." ".$_GET['order'];
			}
			$urlarr['order']=$_GET['order'];
			$urlarr['t']=$_GET['t'];
		}else{
			$where.=" order by `uid` desc";
		}
		$urlarr['page']="{{page}}";
		$pageurl=Url($_GET['m'],$urlarr,'admin');
        $M=$this->MODEL();
		$PageInfo=$M->get_page("hotjob",$where,$pageurl,$this->config['sy_listnum']);
        $this->yunset($PageInfo);
        $rows=$PageInfo['rows'];
 		if(is_array($rows)&&$rows){
			if(empty($list)){
				$list=$this->obj->DB_select_all("company_statis","`uid` in (".@implode(",",$uids).")","`uid`,`pay`,`integral`,`rating`,`rating_name`,`vip_etime`,`msg_num`");
			}
 			foreach($rows as $k=>$v){
				$rows[$k]['hot_pics']="<a href=\"javascript:void(0)\" class=\"previews\" url=\"".$this->config['sy_weburl'].'/'.$v['hot_pic']."\">图片预览</a>";
 				if(mb_strlen($v['username'])>12){
					$rows[$k]['username']=mb_substr($v['username'],"0","12","utf-8")."...";
 				}
				if(mb_strlen($v['beizhu'])>12){
					$rows[$k]['beizhu']=mb_substr($v['beizhu'],"0","12","utf-8")."...";
 				}
				if($v['did']<1)
				{
					$rows[$k]['did'] = 0;
				}
				if($v['hot_pic']&&file_exists(APP_PATH.$v['hot_pic'])){
				    $rows[$k]['hot_pic']=str_replace('./data', $this->config['sy_weburl'].'/data', $v['hot_pic']);
				}else{
				    $rows[$k]['hot_pic']='';
				}
			}
		}	
		$nav_user=$this->obj->DB_select_alls("admin_user","admin_user_group","a.`m_id`=b.`id` and a.`uid`='".$_SESSION["auid"]."'");
		$power=unserialize($nav_user[0]["group_power"]);
		if(in_array('141',$power)){
			$this->yunset("email_promiss", '1');
		}
		if(in_array('163',$power)){
			$this->yunset("moblie_promiss", '1');
		}
		$where=str_replace(array("(",")"),array("[","]"),$where);
		$this->yunset("where", $where);
		$this->yunset("rows",$rows);
		$this->siteadmin_tpl(array('admin_hotjob'));
	}
	function hotjobinfo_action(){
		if($_GET['id']){
			$hotjob=$this->obj->DB_select_once("hotjob","`uid`='".(int)$_GET['id']."'");
		}else if($_GET['uid']){
			$row = $this->obj->DB_select_alls("company","company_statis","a.`uid`='".(int)$_GET['uid']."' and b.`uid`='".(int)$_GET['uid']."'","a.`content`,a.`name` as username,b.`rating` as rating_id,b.`rating_name` as rating,a.`uid`,a.`logo` as hot_pic");
			$row=$row[0];
			$hotjob=$row;
			$hotjob['time_start']=time();
		}
		$this->yunset("hotjob",$hotjob);
		$this->siteadmin_tpl(array('admin_hotjob_info'));
	}
	function delhot_action(){
		$this->check_token();
	    if(isset($_GET['id'])){
	    	$hot=$this->obj->DB_select_once("hotjob","`uid`='".$_GET['id']."'");
			unlink_pic("../".$hot['hot_pic']);
			$result=$this->obj->DB_delete_all("hotjob","`uid`='".$_GET['id']."'" );
			if($result){
				$this->obj->DB_update_all("company","`hottime`='',`rec`='0'","`uid`='".$hot['uid']."'");
				$this->layer_msg('名企(ID:'.$_GET['id'].')删除成功！',9,0,$_SERVER['HTTP_REFERER']);
			}else{
				$this->layer_msg('删除失败！',8,0,$_SERVER['HTTP_REFERER']);
			}
		}
	}
	function save_action(){
		extract($_POST);
		if(is_uploaded_file($_FILES['hot_pic']['tmp_name'])){
			$UploadM=$this->MODEL('upload');
			$upload=$UploadM->Upload_pic("../data/upload/hotpic/");
			$pictures=$upload->picture($_FILES['hot_pic']);
			$pic = str_replace("../data/upload","/data/upload",$pictures);
		}else{
			if($_POST['hotad']){
				$defpic=".".$defpic;
				$url=@explode("/",$defpic);
				$url2=str_replace($url[4],date("Ymd"),$defpic);
				$name=@explode(".",$url[5]);
				$url2=str_replace($name[0],time(),$url2);
				if(!file_exists('../data/upload/company/'.date("Ymd")))
				{
					mkdir ('../data/upload/company/'.date("Ymd"));
				}
				copy($defpic,$url2);
				$pic = str_replace("../data/upload","data/upload",$url2);
			}
		}
		if($_POST['hotad']){
			$times=@explode('~',$time);
			$start = strtotime($times[0]);
			$end = strtotime($times[1]);
			$nid=$this->obj->DB_insert_once("hotjob","`uid`='$uid',`username`='$username',`sort`='$sort',`rating_id`='$rating_id',`rating`='$rating',`hot_pic`='$pic',`service_price`='$service_price',`beizhu`='$beizhu',`time_start`='$start',`time_end`='$end'");
			$this->obj->DB_update_all("company","`hottime`='".$end."',`rec`='1'","`uid`='".$uid."'");
			$this->ACT_layer_msg("名企(ID:".$nid.")设定成功！",9,"index.php?m=admin_company",2,1);
		}elseif($_POST['hotup']){
			$times=@explode('~',$time);
			$start = strtotime($times[0]);
			$end = strtotime($times[1]);
			$value = "`service_price`='$service_price',`time_start`='$start',`time_end`='$end',`beizhu`='$beizhu',`sort`='$sort'";
			if($pic!=""){
				$hot=$this->obj->DB_select_once("hotjob","`id`='$id'");
				unlink_pic("../".$hot['hot_pic']);
				$value.=",`hot_pic`='$pic'";
			}
			$this->obj->DB_update_all("hotjob",$value,"`id`='$id'");
			$this->obj->DB_update_all("company","`hottime`='".$end."'","`uid`='".$uid."'");
			$this->ACT_layer_msg("名企(ID:".$id.")修改成功！",9,"index.php?m=admin_hotjob",2,1);
		}
		$this->siteadmin_tpl(array('admin_hotjob_add'));
	}
	
}
?>