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
class admin_lt_job_controller extends siteadmin_controller
{
	
	function set_search(){
		include PLUS_PATH."/lt.cache.php";
        foreach($ltdata['lt_exp'] as $k=>$v){
               $ltar[$v]=$ltclass_name[$v];
        }
		$search_list[]=array("param"=>"rec","name"=>'推荐状态',"value"=>array("1"=>"已推荐","2"=>"未推荐"));
		$search_list[]=array("param"=>"status","name"=>'审核状态',"value"=>array("1"=>"已审核","2"=>"已过期","3"=>"未通过","4"=>"未审核"));
		$search_list[]=array("param"=>"ltexp","name"=>'工作经验',"value"=>$ltar);
		$this->yunset("search_list",$search_list);
	}
	function index_action(){
		$this->set_search();
        $wheres =$where= "1 ";
		if($_GET['hy']){
			$wheres .= " AND `hy` = '".$_GET['hy']."' ";
			$urlarr['hy']=$_GET['hy'];
		}
		if($_GET['pr']){
			$wheres .= " AND `pr` = '".$_GET['pr']."' ";
			$urlarr['pr']=$_GET['pr'];
		}
		if($_GET['mun']){
			$wheres .= " AND `mun` = '".$_GET['mun']."' ";
			$urlarr['mun']=$_GET['mun'];
		}
		if($_GET['jobone']){
			$wheres .= " AND `jobone` = '".$_GET['jobone']."' ";
			$urlarr['jobone']=$_GET['jobone'];
		}
		if($_GET['jobtwo']){
			$wheres .= " AND `jobtwo` = '".$_GET['jobtwo']."' ";
			$urlarr['jobtwo']=$_GET['jobtwo'];
		}
		if($_GET['provinceid']){
			$wheres .= " AND `provinceid` = '".$_GET['provinceid']."' ";
			$urlarr['provinceid']=$_GET['provinceid'];
		}
		if($_GET['cityid']){
			$wheres .= " AND `cityid` = '".$_GET['cityid']."' ";
			$urlarr['cityid']=$_GET['cityid'];
		}
		if($_GET['three_cityid']){
			$wheres .= " AND `three_cityid` = '".$_GET['three_cityid']."' ";
			$urlarr['three_cityid']=$_GET['three_cityid'];
		}
		if($_GET['age']){
			$wheres .= " AND `age` = '".$_GET['age']."' ";
			$urlarr['age']=$_GET['age'];
		}
		if($_GET['sex']){
			$wheres .= " AND `sex` = '".$_GET['sex']."' ";
			$urlarr['sex']=$_GET['sex'];
		}
		if($_GET['exp']){
			$wheres .= " AND `exp` = '".$_GET['exp']."' ";
			$urlarr['exp']=$_GET['exp'];
		}
		if($_GET['full']){
			$wheres .= " AND `full` = '".$_GET['full']."' ";
			$urlarr['full']=$_GET['full'];
		}
		if($_GET['edu']){
			$wheres .= " AND `edu` = '".$_GET['edu']."' ";
			$urlarr['edu']=$_GET['edu'];
		}
		if(trim($_GET['keywords'])){
			$wheres .= " AND `job_name` like '%".trim($_GET['keywords'])."%' ";
			$urlarr['keywords']=$_GET['keywords'];
		}

		if ($_GET['news_search']){

			if ($_GET['ltname']=='1'){
				$info=$this->obj->DB_select_all("member","`username` like '%".trim($_GET['keyword'])."%'","`uid`");
				if(is_array($info)){
					foreach ($info as $v){
						$uid[]=$v['uid'];
					}
				}
				$where.=" and `uid` in (".@implode(",",$uid).")";				
			}elseif($_GET['ltname']=='2'){
				$where.=" and `job_name`like '%".trim($_GET['keyword'])."%'";
			}elseif($_GET['ltname']=="3"){
				$where.="and `com_name` LIKE '%".trim($_GET['keyword'])."%'";
			}
			$urlarr['ltname']=$_GET['ltname'];
            $urlarr['keyword']=$_GET['keyword'];
            $urlarr['news_search']=$_GET['news_search'];

		}
		if ($_GET['salary']!=''){
			$where .=" and  `salary`='".$_GET['salary']."'";
			$urlarr['salary']=$_GET['salary'];
		}
		if ($_GET['ltexp']){
			$where.=" and `exp`='".$_GET['ltexp']."'";
			$urlarr['ltexp']=$_GET['ltexp'];
		}
		if($_GET['status']){
			if($_GET['status']=="1"){
				$where.= " and `status`=1";
			}elseif($_GET['status']=="2"){
				$where.= " and `edate`<'".time()."'";
			}elseif($_GET['status']=="3"){
				$where.= " and `status`='3'";
			}elseif($_GET['status']=="4"){
				$where.= " and `status`='0'";
			}
			$urlarr['status']=$_GET['status'];
		}
		if($_GET['rec']){
			if($_GET['rec']=='2'){
				$where.= " and `rec`=0";
				$urlarr['rec']=0;
			}else{
				$where.= " and `rec`='".$_GET['rec']."'";
				$urlarr['rec']=$_GET['rec'];
			}
		}
		if($_GET['order'])
		{
			$where.=" order by ".$_GET['t']." ".$_GET['order'];
			$urlarr['order']=$_GET['order'];
			$urlarr['t']=$_GET['t'];
		}else{
			$where.=" order by `id` desc";
		}
		if($_GET['advanced']){
			$where = $wheres;
			$urlarr['advanced']=$_GET['advanced'];
		}
		$urlarr['page']="{{page}}";
		$pageurl=Url($_GET['m'],$urlarr,'admin');
        $M=$this->MODEL();
		$PageInfo=$M->get_page("lt_job",$where,$pageurl,$this->config['sy_listnum']);
        $this->yunset($PageInfo);
        $rows=$PageInfo['rows'];
		include PLUS_PATH."/ltjob.cache.php";
		include PLUS_PATH."/lt.cache.php";
        include PLUS_PATH."/city.cache.php";
		if(is_array($rows)){
			foreach($rows as $k=>$v){
		        $rows[$k]['jobone']=$ltjob_name[$v['jobone']];
		        $rows[$k]['exp']=$ltclass_name[$v['exp']];
			    $rows[$k]['salary']=$ltclass_name[$v['salary']];
		        $rows[$k]['cityid']=$city_name[$v['cityid']];
				$uids[]=$v['uid'];
			}
			$member=$M->DB_select_all("member","`uid` in(".@implode(',',$uids).")","`uid`,`username`");
			foreach($rows as $key=>$val){
				foreach($member as $v){
					if($val['uid']==$v['uid']){
						$rows[$key]['username']=$v['username'];
					}
				}
			}
		}
		$this->yunset("get_type", $_GET);
		$this->yunset($this->MODEL('cache')->GetCache(array('lt')));
        $this->yunset("rows",$rows);
		$this->siteadmin_tpl(array('admin_lt_job'));
	}
	function show_action(){
		include(CONFIG_PATH."db.data.php");		
		$this->yunset("arr_data",$arr_data);
		$show=$this->obj->DB_select_alls("lt_job","member","a.id='".$_GET['id']."' and a.uid=b.uid");
		include PLUS_PATH."/ltjob.cache.php";
		include PLUS_PATH."/com.cache.php";
		include PLUS_PATH."/city.cache.php";
		include PLUS_PATH."/lt.cache.php";
		if(is_array($show)){
			foreach($show as $k=>$v){
				$show[$k]['sex']=$arr_data['sex'][$v['sex']];				
				
				$show[$k]['language']=$ltclass_name[$v['language']];
				$show[$k]['age']=$ltclass_name[$v['age']];
				$show[$k]['exp']=$ltclass_name[$v['exp']];
	            $show[$k]['salary']=$ltclass_name[$v['salary']];
	           
	            $show[$k]['hy']=$ltclass_name[$v['hy']];
	            $show[$k]['report']=$ltclass_name[$v['report']];
	            $show[$k]['pr']=$ltclass_name[$v['pr']];
	            $show[$k]['constitute']=$ltclass_name[$v['constitute']];
	            $show[$k]['social']=$ltclass_name[$v['social']];
	            $show[$k]['live']=$ltclass_name[$v['live']];
	            $show[$k]['years']=$ltclass_name[$v['years']];
	            $show[$k]['city']=$city_name[$v['cityid']];
	            $show[$k]['jobone']=$ltjob_name[$v['jobone']];
	            $show[$k]['mun']=$comclass_name[$v['mun']];
			}
		}
		$this->yunset("show",$show[0]);
		$this->siteadmin_tpl(array('admin_lt_job_show'));
	}

     function status_action(){
		extract($_POST);

		$id = @explode(",",$pid);
		if(is_array($id)){
			foreach($id as $value){
				$idlist[] = $value;
				$data[] = $this->shjobmsg($value,$status);
			}
			
			if($data!="")
			{
        $notice = $this->MODEL('notice');
				foreach($data as $key=>$value)
				{
					$notice->sendEmailType($value);
          $notice->sendSMSType($value);
				}
			}
			$aid = @implode(",",$idlist);
			$id=$this->obj->DB_update_all("lt_job","`status`='$status',`statusbody`='".$statusbody."'","`id` IN ($aid)");
 			$id?$this->ACT_layer_msg("猎头职位(ID:".$aid.")设置成功！",9,$_SERVER['HTTP_REFERER'],2,1):$this->ACT_layer_msg("设置失败！",8,$_SERVER['HTTP_REFERER']);
		}else{
			$this->ACT_layer_msg("非法操作！",8,$_SERVER['HTTP_REFERER']);
		}
	}
	function lockinfo_action(){
		$userinfo = $this->obj->DB_select_once("lt_job","`id`=".$_GET['id'],"`statusbody`");
		echo $userinfo['statusbody'];die;
	}
	 function ajaxjob_action(){
		extract($_POST);
		if($id!=""){
		    $jobs=$this->obj->DB_select_all("job_class","`keyid`=$id");
			if(is_array($jobs)){
				foreach($jobs as $key=>$v){
					$html .= "<option value='".$v['id']."'>".$v['name']."</option>";
				}
				echo $html;
			 	die;
				}
			die;
		}

		if($nid!=""){
		    $jobs=$this->obj->DB_select_all("job_class","`keyid`=$nid");
			if(is_array($jobs)){
				foreach($jobs as $key=>$v){
					$html .= "<option value='".$v['id']."'>".$v['name']."</option>";
				}
				echo $html;
			 	die;
				}
			die;
		}
	}

	function shjobmsg($jobid,$yesid){
		$data=array();
		$comarr=$this->obj->DB_select_once("lt_job","`id`='$jobid'","uid,job_name,status");
		if($yesid==1){
			$data['type']="zzshtg";
		}elseif($yesid==3){
			$data['type']="zzshwtg";
		}
		if($data['type']!=""){
			$uid=$this->obj->DB_select_alls("member","lt_info","a.`uid`='".$comarr['uid']."' and a.`uid`=b.`uid`","a.email,a.moblie,a.uid,a.realname");
			$data['uid']=$uid[0]['uid'];
			$data['name']=$uid[0]['realname'];
			$data['email']=$uid[0]['email'];
			$data['moblie']=$uid[0]['moblie'];
			$data['jobname']=$comarr['job_name'];
			$data['date']=date("Y-m-d");
			return $data;
		}
	}
	function ctime_action()
	{
		$jobid=$_POST['jobid'];
		if($jobid)
		{
			$posttime=$_POST['endtime']*86400;
			$rows=$this->obj->DB_select_all("lt_job","`id` in (".$jobid.")","id,status,edate");
			foreach($rows as $v)
			{
				if($v['status']==2 || $v['edate']<=mktime())
				{
					$id=$this->obj->DB_update_all("lt_job","`edate`=`edate`+'".$posttime."',`status`='1'","`id`='".$v['id']."'");
				}else{
					$id=$this->obj->DB_update_all("lt_job","`edate`=`edate`+'".$posttime."'","`id`='".$v['id']."'");
				}
			}
			$id?$this->ACT_layer_msg("猎头职位延期(ID:".$jobid.")设置成功！",9,$_SERVER['HTTP_REFERER'],2,1):$this->ACT_layer_msg("设置失败！",8,$_SERVER['HTTP_REFERER']);
		}else{
			$this->ACT_layer_msg("非法操作！",8,$_SERVER['HTTP_REFERER']);
		}
    }


	
	function recommend_action(){
		$time=time();
		$nid=$this->obj->DB_update_all("lt_job","`".$_GET['type']."`='".$_GET['rec']."'","`id`='".$_GET['id']."'");
		if($nid && ($_GET['rec']==1)){
			$this->obj->DB_update_all("lt_job","`rec_time`='".$time."'","`id`='".$_GET['id']."'");
		}elseif($nid && ($_GET['rec']==0)){
			$this->obj->DB_update_all("lt_job","`rec_time`=''","`id`='".$_GET['id']."'");
		}
		echo $nid?1:0;die;
	}

	function del_action()
	{
		$this->check_token();
	    if($_GET['del'])
	    {
	    	$del=$_GET['del'];
    		if(is_array($del))
    		{
    			$layer_type=1;
		    	$del=@implode(",",$del);
	    	}else{
	    		$layer_type=0;
	    	}
	    	$this->obj->DB_delete_all("lt_job","`id` in (".$del.")","");
    		$this->layer_msg( "猎头职位(ID:".@explode(",",$del).")删除成功！",9,$layer_type,$_SERVER['HTTP_REFERER']);
		}else{
			$this->layer_msg( "请选择您要删除的信息！",8,1,$_SERVER['HTTP_REFERER']);
    	}
	}
}
?>