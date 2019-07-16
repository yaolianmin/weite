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
class admin_company_job_controller extends siteadmin_controller{
	function set_search(){
		include PLUS_PATH."/com.cache.php";
        foreach($comdata['job_type'] as $k=>$v){
		   $comarr[$v]=$comclass_name[$v];
        }
        foreach($comdata['job_salary'] as $k=>$v){
		   $comar[$v]=$comclass_name[$v];
        }
		include(CONFIG_PATH."db.data.php");
		$source=$arr_data['source'];
		$this->yunset('source',$source);
		$search_list[]=array("param"=>"state","name"=>'审核状态',"value"=>array("1"=>"已审核","4"=>"未审核","3"=>"未通过","2"=>"已过期"));
		$search_list[]=array("param"=>"status","name"=>'招聘状态',"value"=>array("1"=>"已下架","2"=>"发布中"));
		$search_list[]=array("param"=>"jtype","name"=>'职位类型',"value"=>array("urgent"=>"紧急职位","xuanshang"=>"置顶职位","rec"=>"推荐职位"));
		$search_list[]=array("param"=>"source","name"=>'数据来源',"value"=>$source);
		$search_list[]=array("param"=>"adtime","name"=>'发布时间',"value"=>array("1"=>"今天","3"=>"最近三天","7"=>"最近七天","15"=>"最近半月","30"=>"最近一个月"));
		$search_list[]=array("param"=>"etime","name"=>'到期时间',"value"=>array("1"=>"已到期","3"=>"最近三天","7"=>"最近七天","15"=>"最近半月","30"=>"最近一个月"));
		$this->yunset("search_list",$search_list);
	}
	function index_action(){
		$this->set_search();
		$time = time();
        $wheres = "1 ";
		if($_GET['type']){
			$wheres .= " AND `type` = '".$_GET['type']."' ";
			$urlarr['type']=$_GET['type'];
		}
		if($_GET['number']){
			$wheres .= " AND `number` = '".$_GET['number']."' ";
			$urlarr['number']=$_GET['number'];
		}
		if($_GET['exp']){
			$wheres .= " AND `exp` = '".$_GET['exp']."' ";
			$urlarr['exp']=$_GET['exp'];
		}
		if($_GET['report']){
			$wheres .= " AND `report` = '".$_GET['report']."' ";
			$urlarr['report']=$_GET['report'];
		}
		if($_GET['sex']){
			$wheres .= " AND `sex` = '".$_GET['sex']."' ";
			$urlarr['sex']=$_GET['sex'];
		}
		if($_GET['edu']){
			$wheres .= " AND `edu` = '".$_GET['edu']."' ";
			$urlarr['edu']=$_GET['edu'];
		}
		if($_GET['marriage']){
			$wheres .= " AND `marriage` = '".$_GET['marriage']."' ";
			$urlarr['marriage']=$_GET['marriage'];
		}
        if(trim($_GET['keyword'])){
			$wheres .= " AND `name` like '%".trim($_GET['keyword'])."%' ";
			$urlarr['keyword']=$_GET['keyword'];
		}
		$where=1;
		if(trim($_GET['keyword'])!=""){
			if($_GET['type']=='1'){
				$where .=" and  `com_name` like '%".trim($_GET['keyword'])."%'";
			}else{
				$where .=" and `name` like '%".trim($_GET['keyword'])."%'";
			}
			$urlarr['type']=$_GET['type'];
			$urlarr['keyword']=$_GET['keyword'];
		}
		if($_GET['source']){
			$where .=" and `source`='".$_GET['source']."'";
			$urlarr['source']=$_GET['source'];
		}
		if ($_GET['job_type']){
			$where .=" and `type`='".$_GET['job_type']."'";
			$urlarr['job_type']=$_GET['job_type'];
		}
		if($_GET['adtime']){
			if($_GET['adtime']=='1'){
				$where .=" and `sdate`>'".strtotime(date("Y-m-d 00:00:00"))."'";
			}else{
				$where .=" and `sdate`>'".strtotime('-'.intval($_GET['adtime']).' day')."'";
			}
			$urlarr['adtime']=$_GET['adtime'];
		}
		if($_GET['etime']){
			if($_GET['etime']=='1'){
				$where .=" and `edate`<'".time()."'";
			}else{
				$where .=" and `edate`>'".time()."' AND `edate`<'".strtotime('+'.intval($_GET['etime']).' day')."'";
			}
			$urlarr['etime']=$_GET['etime'];
		}
		if ($_GET['salary']){
			$where .=" and `salary`='".$_GET['salary']."'";
			$urlarr['salary']=$_GET['salary'];
		}
		if($_GET['id']){
			$where.=" and `id`='".$_GET['id']."'";
			$urlarr['id']=$_GET['id'];
		}
		if($_GET['state']){
			if($_GET['state']=="1"){
				$where.= "  and `edate`>'".time()."' and `state`='1'";
			}elseif($_GET['state']=="2"){
				$where.= "  and `edate`<'".time()."'";
			}elseif($_GET['state']=="3"){
				$where.= " and `state`='".$_GET['state']."'";
			}elseif($_GET['state']=="4"){
				$where.= "  and `state`='0'";
			}
			$urlarr['state']=$_GET['state'];
		}
		if($_GET['status']){
			if($_GET['status']=="1"){
				$where.=" and `status`='1'";
			}else{
				$where.=" and `status`!='1'";
			}
			$urlarr['status']=$_GET['status'];
		}
		if($_GET['jtype']){
			if($_GET['jtype']=='rec'){
				$where.= "  and `rec_time`>".time();
			}else if($_GET['jtype']=='urgent'){
				$where.= "  and `urgent_time`>".time();
			}else if($_GET['jtype']=='xuanshang'){
				$where.= "  and `xuanshang`<>'' and `xsdate`>".time()."";
			}
			$urlarr['jtype']=$_GET['jtype'];
		}
		if($_GET['order']) {
			$where.=" order by ".$_GET['t']." ".$_GET['order'];
			$urlarr['order']=$_GET['order'];
			$urlarr['t']=$_GET['t'];
		}else{
			$where.=" order by state asc,lastupdate desc";
		}
		if($_GET['advanced']){
			$where= $wheres;
			$urlarr['advanced']=$_GET['advanced'];
		}

		$urlarr['page']="{{page}}";
		$pageurl=Url($_GET['m'],$urlarr,'admin');
        $M=$this->MODEL();
		$PageInfo=$M->get_page("company_job",$where,$pageurl,$this->config['sy_listnum']);
        $this->yunset($PageInfo);
        $rows=$PageInfo['rows'];
		$CacheM=$this->MODEL('cache');
		$CacheList=$CacheM->GetCache(array('com','job','hy'));
		if(is_array($rows)){
			$jobids=array();
		    foreach ($rows as $v){ 
				$jobids[]=$v['id'];
		    }
			$useridjob=$this->MODEL('job')->GetUseridJob(array("`job_id` in(".pylode(',',$jobids).")",'is_browse'=>1),array('field'=>"count(id) as num,`job_id`",'groupby'=>'job_id'));			
			
			$msgnum=$this->MODEL('resume')->GetUserMsgNums(array("`jobid` in(".pylode(',',$jobids).")"),array('field'=>"count(id) as num,`jobid`",'groupby'=>'jobid')); 
			foreach($rows as $k=>$v){
				if($v['rec_time']>1000){
					$rows[$k]['recdate']=date("Y-m-d",$v['rec_time']);
				}
				if($v['urgent_time']>1000){
					$rows[$k]['eurgent']=date("Y-m-d",$v['urgent_time']);
				}
				
				$rows[$k]['browseNum']=$rows[$k]['inviteNum']=0;
				$rows[$k]['edu']=$CacheList['comclass_name'][$v['edu']];
				$rows[$k]['exp']=$CacheList['comclass_name'][$v['exp']];
				if($v['job_post']){
					$rows[$k]['job']=$CacheList['job_name'][$v['job_post']];
				}else{
					$rows[$k]['job']=$CacheList['job_name'][$v['job1_son']];
				}

				$rows[$k]['salary']=$CacheList['comclass_name'][$v['salary']];
				$rows[$k]['type']=$CacheList['comclass_name'][$v['type']];
				if($v['edate']<time())
				{
					$rows[$k]['edatetxt'] = "<font color='red'>已到期</font>";
				}elseif($v['edate']<(time()+3*86400)){

					$rows[$k]['edatetxt'] = "<font color='blue'>3天内到期</font>";

				}elseif($v['edate']<(time()+7*86400)){

					$rows[$k]['edatetxt'] = "<font color='blue'>7天内到期</font>";
				}else{
					$rows[$k]['edatetxt'] = date("Y-m-d",$v['edate']);
				}
				if($v['urgent_time']>$time){
					$rows[$k]['urgent_day'] = ceil(($v['urgent_time']-$time)/86400);
				}else{
					$rows[$k]['urgent_day'] = "0";
				}
				if($v['rec_time']>$time){
					$rows[$k]['rec_day'] = ceil(($v['rec_time']-$time)/86400);
				}else{
					$rows[$k]['rec_day'] = "0";
				}
				foreach($useridjob as $val){
					if($val['job_id']==$v['id']){
						$rows[$k]['browseNum']=$val['num'];
					}
				}
				foreach($msgnum as $val){
					if($val['jobid']==$v['id']){
						$rows[$k]['inviteNum']=$val['num'];
					}
				}
			}
		}
		$adtime=array('1'=>'今天','3'=>'最近三天','7'=>'最近七天','15'=>'最近半月','30'=>'最近一个月');
		$adtime=array('1'=>'今天','3'=>'最近三天','7'=>'最近七天','15'=>'最近半月','30'=>'最近一个月');
		$this->yunset("adtime",$adtime);
		$where=str_replace(array("(",")"),array("[","]"),$where);
		$this->yunset($CacheList);
		$this->yunset("where",$where);
		$this->yunset("get_type", $_GET);
		$this->yunset("rows",$rows);
		$this->yunset("time",$time);
		$this->siteadmin_tpl(array('admin_company_job'));
	}
	function show_action(){
		include(CONFIG_PATH."db.data.php");		
		$this->yunset("arr_data",$arr_data);
		$CacheM=$this->MODEL('cache');
		$CacheList=$CacheM->GetCache(array('com','city','job','hy','circle'));
		$JobM=$this->MODEL('job');
		if($_GET['id']){
			$show=$JobM->GetComjobOne(array("id"=>$_GET['id']));
			$show['lang']=@explode(',',$show['lang']);
			
			if($row['three_cityid']){
				$row['circlecity']=$row['three_cityid'];
			}else if($row['cityid']){
				$row['circlecity']=$row['cityid'];
			}else if($row['provinceid']){
				$row['circlecity']=$row['provinceid'];
			}
			$this->yunset("show",$show);
		}
		if($_POST['update']){
			$CompanyM=$this->MODEL('company');
			$_POST['lang']=@implode(',',$_POST['lang']);
			
			$_POST['edate']=strtotime($_POST['edate']);
			$_POST['description'] = str_replace("&amp;","&",$_POST['content']);
			$_POST['lastupdate'] = time();
			unset($_POST['update']);unset($_POST['content']);
			if($_POST['edate']>time()){
				$_POST['state']="1";
			}else{
				$this->ACT_layer_msg("结束时间不能小于当前时间",8,"index.php?m=admin_company_job",2,1);
			}
			if($_POST['salary_type']){
				$_POST['minsalary']=$_POST['maxsalary']=0;
			}

			if($_POST['id']&&$_POST['uid']==''){
				$job=$JobM->GetComjobOne(array("id"=>$_POST['id']),array("file"=>"uid"));
				$where['id']=$_POST['id'];
				unset($_POST['id']);
				$JobM->UpdateComjob($_POST,$where);
				$CompanyM->UpdateCompany(array('lastupdate'=>time()),array('uid'=>$job['uid']));
				$this->ACT_layer_msg("职位(ID:".$where['id'].")修改成功！",9,"index.php?m=admin_company_job",2,1);
			}else if($_POST['uid']){
				
				$UserinfoM=$this->MODEL('userinfo');
				$company=$CompanyM->GetCompanyInfo(array("uid"=>$_POST['uid']),array("field"=>'name,welfare'));
				$statis=$UserinfoM->GetUserstatisOne(array("uid"=>$_POST['uid']),array("usertype"=>"2","field"=>"`vip_etime`,`job_num`,`integral`"));
				if($statis['vip_etime']>time() || $statis['vip_etime']=="0"){
					if($statis['rating_type']==1){
						if($statis['job_num']<1){
							if($this->config['com_integral_online']=="1"){
								if($statis['integral']<$this->config['integral_job']){
									$this->ACT_layer_msg($this->config['integral_pricename']."不够发布职位！",8,"index.php?m=admin_company_job");
								}
							}else{
								$this->ACT_layer_msg("该会员发布职位用完！",8,"index.php?m=admin_company_job");
							}
						}else{
							$job_num=$statis['job_num']-1;
							$UserinfoM->UpdateUserStatis(array("job_num"=>$job_num),array("uid"=>$_POST['uid']),array("usertype"=>2));
						}
					}
				}else{
					if($this->config['com_integral_online']=="1"){
						if($statis['integral']<$this->config['integral_job']){
							$this->ACT_layer_msg($this->config['integral_pricename']."不够发布职位！",8,"index.php?m=admin_company_job");
						}
					}else{
						$this->ACT_layer_msg("该会员发布职位用完！",8,"index.php?m=admin_company_job");
					}
				}
				$_POST['com_name']=$company['name'];
				$_POST['welfare']=$company['welfare'];
				$_POST['sdate']=time();
				$_POST['did']=$this->config['did'];
				$id=$JobM->AddCompanyJob($_POST);
				if($id){
					$CompanyM->UpdateCompany(array('jobtime'=>time()),array('uid'=>$_POST['uid']));
					$this->ACT_layer_msg( "职位(ID:".$id.")发布成功！",9,'index.php?m=admin_company_job&c=show&uid='.$_POST['uid'],2,1);
				}else{
					$this->ACT_layer_msg( "职位发布失败！",8,'index.php?m=admin_company_job&c=show&uid='.$_POST['uid'],2,1);
				}
			}
		}
		$this->yunset($CacheList);
		$this->yunset("uid",$_GET['uid']);
		$this->siteadmin_tpl(array('admin_company_job_show'));
	}
	function lockinfo_action(){
		$JobM=$this->MODEL('job');
		$userinfo=$JobM->GetComjobOne(array("id"=>$_POST['id']),array("field"=>"statusbody"));
		echo $userinfo['statusbody'];die;
	}
	function status_action(){
		 extract($_POST);
		 $id = @explode(",",$pid);
		 if(is_array($id)){
			foreach($id as $value){
				if($value) {
					$idlist[] = $value;
					$data[] = $this->shjobmsg($value,$status,$statusbody);
				}
			}

			if($data!=""){
				$notice = $this->MODEL('notice');
				foreach($data as $key=>$value){
					$notice->sendEmailType($value);
          $notice->sendSMSType($value);
				}
			}
			$aid = @implode(",",$idlist);
			$JobM=$this->MODEL('job');
			$id=$JobM->UpdateComjob(array("state"=>$status,"statusbody"=>$statusbody,"lastupdate"=>time()),array("`id` IN ($aid)"));
			$id?$this->ACT_layer_msg("职位审核(ID:".$aid.")设置成功！",9,$_SERVER['HTTP_REFERER'],2,1):$this->ACT_layer_msg("设置失败！",8,$_SERVER['HTTP_REFERER']);
		}else{
			$this->ACT_layer_msg("非法操作！",8,$_SERVER['HTTP_REFERER']);
		}
	}
	function saveclass_action(){
		extract($_POST);
		if($hy==""){
			$this->ACT_layer_msg("请选择行业类别！",8);
		}
		if($job1==""){
			$this->ACT_layer_msg("请选择职位类别！",8);
		}
		$JobM=$this->MODEL('job');
		$id=$JobM->UpdateComjob(array("hy"=>$hy,"job1"=>$job1,"job1_son"=>$job1_son,"job_post"=>$job_post),array("`id` IN ($jobid)"));
		$id?$this->ACT_layer_msg("类别(ID:".$jobid.")修改成功！",9,$_SERVER['HTTP_REFERER'],2,1):$this->ACT_layer_msg("修改失败！",8,$_SERVER['HTTP_REFERER']);
	}
	function jobclass_action(){
		include(PLUS_PATH."industry.cache.php");
		include(PLUS_PATH."job.cache.php");
		if(is_array($job_type[$_POST['val']])&&$job_type[$_POST['val']]){
			foreach($job_type[$_POST['val']] as $val){
				$html.="<option value='".$val."'>".$job_name[$val]."</option>";
			}
		}else{$html.="<option value=''>暂无分类</option>";}
		echo $html;
	}
	function shjobmsg($jobid,$yesid,$statusbody) {
		$data=array();
		$JobM=$this->MODEL('job');
		$comarr=$JobM->GetComjobOne(array("id"=>$jobid),array("field"=>"uid,name,com_name"));
		if($yesid==1){
			$data['type']="zzshtg";
			$this->send_dingyue($jobid,2);
		}elseif($yesid==3){
			$data['type']="zzshwtg";
		}
		if($data['type']!=""){
			$UserinfoM=$this->MODEL('userinfo');
			$member=$UserinfoM->GetMemberOne(array("uid"=>$comarr['uid']),array("field"=>"email,moblie,uid"));
			$data['uid']=$member['uid'];
			$data['name']=$comarr['com_name'];
			$data['email']=$member['email'];
			$data['moblie']=$member['moblie'];
			$data['jobname']=$comarr['name'];
			$data['date']=date("Y-m-d H:i:s");
			$data['status_info']=$statusbody;
			return $data;
		}
	}
	function ctime_action(){
		extract($_POST);
		$id=@explode(",",$jobid);
		if(is_array($id)){
			$JobM=$this->MODEL('job');
			$posttime=$endtime*86400;
			foreach($id as $value){
				$row=$JobM->GetComjobOne(array("id"=>$value),array("field"=>"state,edate"));
				if($row['state']==2 || $row['edate']<time()){
					$time=time()+$posttime;
					$JobM->UpdateComjob(array("edate"=>$time,"state"=>1),array("id"=>$value));
				}else{
					$time=$row['edate']+$posttime;
					$JobM->UpdateComjob(array("edate"=>$time),array("id"=>$value));
				}
			}
			$id?$this->ACT_layer_msg("职位延期(ID:".$jobid.")设置成功！",9,$_SERVER['HTTP_REFERER'],2,1):$this->ACT_layer_msg("设置失败！",8,$_SERVER['HTTP_REFERER']);
		}else{
			$this->ACT_layer_msg("非法操作！",8,$_SERVER['HTTP_REFERER']);
		}
	}
	function xuanshang_action(){
	    if($_POST['s']==1){
	        $id=$this->obj->DB_update_all("company_job","`xsdate`='0'","`id`='".intval($_POST['pid'])."'");
	    }else{
	        $info=$this->obj->DB_select_once("company_job","`id`='".intval($_POST['pid'])."'","`xsdate`");
	        $xsdays=intval($_POST['xsdays']);
	        $time=$xsdays*86400;
	        if($info['xsdate']>time()){
	            $id=$this->obj->DB_update_all("company_job","`xsdate`=`xsdate`+'".$time."'","`id`='".intval($_POST['pid'])."'");
	        }else{
	            $xsdate=time()+$time;
	            $id=$this->obj->DB_update_all("company_job","`xsdate`='".$xsdate."'","`id`='".intval($_POST['pid'])."'");
	        }
	    }
	    $id?$this->ACT_layer_msg("职位置顶(ID:".$_POST['pid'].")设置成功！",9,$_SERVER['HTTP_REFERER'],2,1):$this->ACT_layer_msg("设置失败！",8,$_SERVER['HTTP_REFERER']);
	}
	function recommend_action(){
		extract($_POST);
		if($addday<1&&$s==''){$this->ACT_layer_msg("推荐天数不能为空！",8);}
		$addtime = 86400*$addday;
		$JobM=$this->MODEL('job');
		if($pid){
			if($s==1){
				$JobM->UpdateComjob(array("rec"=>'0',"rec_time"=>'0'),array("id"=>$pid));
			}elseif($eid>time()){ 
				$JobM->UpdateComjob(array("rec"=>'1',"`rec_time`=`rec_time`+$addtime"),array("id"=>$pid));
			}else{
				$time=time()+$addtime;
				$JobM->UpdateComjob(array("rec"=>'1',"rec_time"=>$time),array("id"=>$pid));
			}
			$this->ACT_layer_msg("职位推荐(ID:".$pid.")设置成功！",9,$_SERVER['HTTP_REFERER'],2,1);
		}else if(!empty($codearr)){
			if($s==1){
				$JobM->UpdateComjob(array("rec"=>'0',"rec_time"=>0),array("`id` in (".$codearr.")"));
				$this->ACT_layer_msg("取消职位推荐设置成功！",9,$_SERVER['HTTP_REFERER'],2,1);
			}else{
				$code_com=@explode(",",$codearr);
				if(is_array($code_com)){
					foreach($code_com as $k=>$v){
						$r_time[$v]=$JobM->GetComjobOne(array("id"=>$v),array("field"=>"rec_time"));
					}
				}
                if(is_array($r_time)){
                	$ti=time();
                	foreach($r_time as $ke=>$va){
                       if($va['rec_time']<$ti){
                       	    $g_id[]=$ke;
                       }else{
                       	    $m_id[]=$ke;
                       }
                	}
                	$guoqi=@implode(",",$g_id);
                	$meiguo=@implode(",",$m_id);
                	if(is_array($g_id)){
						$time=time()+$addtime;
						$JobM->UpdateComjob(array("rec"=>'1',"rec_time"=>$time),array("`id` in (".$guoqi.")"));
                	}elseif($m_id){
						$time=time()+$addtime;
						$JobM->UpdateComjob(array("rec"=>'1',"rec_time"=>$time),array("`id` in (".$meiguo.")"));
                	}
                	$this->ACT_layer_msg("职位推荐设置成功！",9,$_SERVER['HTTP_REFERER'],2,1);
                }
			}
		}

	}
	function urgent_action(){
		extract($_POST);
		if($addday<1&&$s==''){$this->ACT_layer_msg("紧急天数不能为空！",8);}
		$addtime = 86400*$addday;
		$JobM=$this->MODEL('job');
		if($pid){
			$info=$JobM->GetComjobOne(array("id"=>$pid),array("file"=>"urgent_time"));
			$urgent_time='';
			if($s==1){
				$JobM->UpdateComjob(array("urgent_time"=>'0','urgent'=>'0'),array("id"=>$pid));
			}elseif($eid>time()){
				$urgent_time=$addtime+$info['urgent_time'];
				$JobM->UpdateComjob(array("urgent_time"=>$urgent_time,'urgent'=>'1'),array("id"=>$pid));
			}else{
				$urgent_time=$addtime+time();
				$JobM->UpdateComjob(array("urgent_time"=>$urgent_time,'urgent'=>'1'),array("id"=>$pid));
			}
			$this->ACT_layer_msg("紧急招聘(ID:".$pid.")设置成功！",9,$_SERVER['HTTP_REFERER'],2,1);
		}
		if(!empty($codeugent)){
			if($s==1){
				$JobM->UpdateComjob(array("urgent_time"=>'0','urgent'=>'0'),array("`id` in (".$codeugent.")"));
				$this->ACT_layer_msg("取消职位紧急设置成功！",9,$_SERVER['HTTP_REFERER'],2,1);
			}else{
				$code_ugent=@explode(",",$codeugent);
				if(is_array($code_ugent)){
					foreach($code_ugent as $k=>$v){
						$r_time[$v]=$JobM->GetComjobOne(array("id"=>$v),array("file"=>"urgent_time"));
					}
				}
                if(is_array($r_time)){
                	$ti=time();
                	foreach($r_time as $ke=>$va){
                       if($va['urgent_time']<$ti){
                       	    $g_id[]=$ke;
                       }else{
                       	    $m_id[]=$ke;
                       }
                	}
                	$guoqi=@implode(",",$g_id);
                	$meiguo=@implode(",",$m_id);
                	if($g_id){
						$JobM->UpdateComjob(array("urgent_time"=>(time()+$addtime),'urgent'=>'1'),array("`id` in (".$guoqi.")"));
                	}elseif($m_id){
						$JobM->UpdateComjob(array("urgent_time"=>(time()+$addtime),'urgent'=>'1'),array("`id` in (".$guoqi.")"));
                	}
                	$this->ACT_layer_msg("职位紧急设置成功！",9,$_SERVER['HTTP_REFERER'],2,1);
                }
			}
		}
	}
	function del_action() {
		$this->check_token();
	    if($_GET['del']||$_GET['id']){
    		if(is_array($_GET['del'])){
    			$layer_type=1;
				$del=@implode(',',$_GET['del']);
	    	}else{
	    		$layer_type=0;
	    		$del=$_GET['id'];
	    	}
			$JobM=$this->MODEL('job');
			$JobM->DelJob($del);
			$this->layer_msg("职位(ID:".$del.")删除成功！",9,$layer_type,$_SERVER['HTTP_REFERER']);
    	}else{
			$this->layer_msg("请选择您要删除的信息！",8,1);
    	}
	}
	function refresh_action() {
		$JobM=$this->MODEL('job');
		$UserinfoM=$this->MODEL('userinfo');
		$list=$JobM->GetComjobList(array("`id` in (".$_POST['ids'].")"),array("field"=>"uid"));
		if(is_array($list)) {
			foreach($list as $v) {
				$uid[]=$v['uid'];
			}
			$UserinfoM->UpdateCompany(array("lastupdate"=>time()),array("`uid` in (".@implode(",",$uid).")"));
		}
		$JobM->UpdateComjob(array("lastupdate"=>time()),"`id` in (".$_POST['ids'].")");
		$this->MODEL('log')->admin_log("职位(ID".$_POST['ids']."刷新成功");
	}
	function xls_action() {
		include(CONFIG_PATH."db.data.php");		
		$this->yunset("arr_data",$arr_data);
		if($_POST['where']) {
			$JobM=$this->MODEL('job');
			$_POST['where']=str_replace(array("[","]","an d","\&acute;","\\"),array("(",")","and","'",""),$_POST['where']);
			if(in_array("lastdate",$_POST['type'])) {
				foreach($_POST['type'] as $v) {
					if($v=="lastdate"){
						$type[]="lastupdate";
					}else{
						$type[]=$v;
					}
				}
				$_POST['type']=$type;
			}
			$select=@implode(",",$_POST['type']);
			$list=$JobM->GetComjobList(array("`id` in (".$_POST["pid"].") and ".$_POST['where']),array("field"=>$select));
			if(!empty($list)) {
				foreach($list as $k=>$v){
					if($v['lang']!=""){
						include PLUS_PATH."/com.cache.php";
						$lang=@explode(",",$v['lang']);
						foreach($lang as $val){
							$langs[]=$comclass_name[$val];
						}
						$list[$k]['lang']=@implode(",",$langs);
					}
					if($v['welfare']!=""){
						include PLUS_PATH."/com.cache.php";
						$welfare=@explode(",",$v['welfare']);
						foreach($welfare as $val){
							
							$welfares[]=$val;
						}
						$list[$k]['welfare']=@implode(",",$welfares);
					}
					$list[$k]['sex']=$arr_data['sex'][$v['sex']];
				}
				
				$this->yunset("list",$list);

				$CacheM=$this->MODEL('cache');
				$CacheList=$CacheM->GetCache(array('com','city','job','hy'));
				$this->yunset($CacheList);
				$this->yunset("type",$_POST['type']);
				
				$this->MODEL('log')->admin_log("导出职位信息");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename=job.xls");
				$this->siteadmin_tpl(array('admin_job_xls'));
			}
		}
	}
	function matching_action(){
	    if($_GET['id']){
	        $id=intval($_GET['id']);
	        $this->yunset($this->MODEL('cache')->GetCache(array('city')));
	        $where = "status<>'2' and `r_status`<>'2' and `job_classid`<>'' and `open`='1' and `defaults`='1'";
	        $JobM=$this->MODEL('job');
	        $jobinfo=$JobM->GetComjobOne(array('id'=>$id),array('field'=>'uid,job1,cityid'));
	        $this->yunset('comid',$jobinfo['uid']);
	        if($jobinfo){
	            $where .="and `cityid`='".$jobinfo['cityid']."'";
	        }
	        include PLUS_PATH."job.cache.php";
	        if($jobinfo['job1']){
	            $joball=$job_type[$jobinfo['job1']];
	            foreach($job_type[$jobinfo['job1']] as $v){
	                $joball[]=@implode(",",$job_type[$v]);
	            }
	            $job_classid=@implode(",",$joball);
	        }
	        if(!empty($job_classid)){
	            $classid=@explode(",",$job_classid);
	            foreach($classid as $value){
	                $jobclass[]="FIND_IN_SET('".$value."',job_classid)";
	            }
	            $classid=@implode(" or ",$jobclass);
	            $where .= " AND ($classid)";
	        }
			$record=$this->obj->DB_select_all("user_entrust_record","`jobid`='".$id."'");
			foreach($record as $k=>$v){
				$eids[]=$v['eid'];
			}
	        $where.=" and `id` not in(".pylode(',',$eids).")";
	        $where.="order by `lastupdate` desc";
	        $urlarr["page"]="{{page}}";
	        $pageurl=Url('admin_company_job&c=matching&id='.$id.'',$urlarr,'admin');
	        $rows=$this->get_page("resume_expect",$where,$pageurl,$this->config['sy_listnum'],'`id`,`uid`,`uname`,`job_classid`,`provinceid`,`cityid`');
			foreach ($rows as $key=>$val){
	            $job_classid=explode(",",$val['job_classid']);
				$jobname=array();
				if(is_array($job_classid)){
					foreach($job_classid as $val){
						$jobname[]=$job_name[$val];
					}
				}
				$rows[$key]['job_name']=implode(",",$jobname);
	        }
	        $this->yunset('resumes',$rows);
	        $this->siteadmin_tpl(array('admin_matching'));
	    }
	}
	function directrecom_action(){
	    if($_GET['eid']&&$_GET['id']&&$_GET['comid']){
	        $eid=intval($_GET['eid']);
	        $id=intval($_GET['id']);
	        $comid=intval($_GET['comid']);
	        $row=$this->obj->DB_select_once("user_entrust_record","`jobid`='".$id."' and `eid`='".$eid."'");
	        if(!empty($row)){
	            $arr['msg']='请勿重复推送！';
	            $arr['type']='8';
	        }else{
	            $linkmail=$this->obj->DB_select_once("company","`uid`='".$comid."'","`linkmail`,`uid`,`did`");
	            $id=$this->obj->DB_insert_once("user_entrust_record","`jobid`='".$id."',`eid`='".$eid."',`comid`='".$comid."',`ctime`='".time()."',`did`='".$linkmail['did']."'");
	            if($id){
	                if($this->config['sy_email_set']=="1"){
	                     
	                    $contents=file_get_contents($this->config['sy_weburl']."/index.php?m=resume&c=sendresume&id=".$eid."&type=charge");
						
						$emailData['email'] = $linkmail['linkmail'];
						$emailData['subject'] = $this->config['sy_webname']."向您推荐了简历！";
						$emailData['content'] = $contents;
						$notice = $this->MODEL('notice');
            $notice->sendEmail($emailData);
	                }
	                $arr['msg']='推送成功！';
	                $arr['type']='9';
	            }else{
	                $arr['msg']='推送失败';
	                $arr['type']='8';
	            }
	        }
	        echo json_encode($arr);die;
	    }
	}
}
?>