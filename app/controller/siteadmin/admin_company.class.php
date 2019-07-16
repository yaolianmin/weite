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
class admin_company_controller extends siteadmin_controller{
	function set_search(){

		$UserinfoM=$this->MODEL('userinfo');
		$rating=$UserinfoM->GetRatinginfoAll(array('category'=>1),array("field"=>"`id`,`name`","orderby"=>'sort'));
		if(!empty($rating)){
			foreach($rating as $k=>$v){
                 $ratingarr[$v['id']]=$v['name'];
			}
		}
		include(CONFIG_PATH."db.data.php");
		$source=$arr_data['source'];
		$adtime=array('1'=>'今天','3'=>'最近三天','7'=>'最近七天','15'=>'最近半月','30'=>'最近一个月');
		$lotime=array('1'=>'今天','3'=>'最近三天','7'=>'最近七天','15'=>'最近半月','30'=>'最近一个月');
		$status=array('1'=>'已审核','2'=>'已锁定','3'=>'未通过','4'=>'未审核');
		$edtime=array('1'=>'7天内','2'=>'一个月内','3'=>'半年内','4'=>'一年内');	
		$search_list[]=array("param"=>"rating","name"=>'会员等级',"value"=>$ratingarr);
		$search_list[]=array("param"=>"time","name"=>'到期时间',"value"=>$edtime);	
		$search_list[]=array("param"=>"status","name"=>'审核状态',"value"=>$status);
		$search_list[]=array('param'=>'source','name'=>'数据来源','value'=>$source);
		$search_list[]=array("param"=>"rec","name"=>'知名企业',"value"=>array("1"=>"是","2"=>"否"));		
		$search_list[]=array("param"=>"gw","name"=>'企业顾问',"value"=>array("1"=>"已分配","2"=>"未分配"));		
		$search_list[]=array("param"=>"lotime","name"=>'最近登录',"value"=>$lotime);
		$search_list[]=array("param"=>"adtime","name"=>'最近注册',"value"=>$lotime);
		$this->yunset("ratingarr",$ratingarr);
		$this->yunset("search_list",$search_list);
	}
	function index_action(){
		$this->set_search();
		$UserinfoM=$this->MODEL('userinfo');
		$where="1";
		$mwhere=$uids=array();
		if((int)$_GET['status']){
			$mwhere['usertype']="2";
			if($_GET['status']=='4'){
				$mwhere['status']="0";
			}else if($_GET['status']){
				$mwhere['status']=intval($_GET['status']);
			}
			$urlarr['status']=intval($_GET['status']);
		}
		if((int)$_GET['rating']){
			$swhere['rating']=(int)$_GET['rating'];
			$urlarr['rating']=(int)$_GET['rating'];
		}
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
			if(count($swhere)){
				$swhere[]=" and `vip_etime`>'".time()."' and `vip_etime`<'".strtotime($num)."'";
			}else{
				$swhere[]=" `vip_etime`>'".time()."' and `vip_etime`<'".strtotime($num)."'";
			}
			$urlarr['time']=$_GET['time'];
		}
		if($swhere){
			$list=$UserinfoM->GetUserstatisAll($swhere,array('usertype'=>2,'field'=>"`uid`,`pay`,`rating`,`rating_name`,`vip_etime`"));
			foreach($list as $val){
				$uids[]=$val['uid'];
			}
			$where.=" and `uid` in (".@implode(',',$uids).")";
		}
		if((int)$_GET['rec']){
       	   if((int)$_GET['rec']=='1'){
 				$where.= "  and `rec`=1 ";
       	   }else{
 				$where.= "  and `rec`=0 ";
       	   }
			$urlarr['rec']=(int)$_GET['rec'];
       }
	   if($_GET['gw']){
       	   if($_GET['gw']=='1'){
 				$where.= "  and `conid`!=0 ";
                
       	   }else{
 				$where.= "  and `conid`=0 ";
       	   }
			$urlarr['gw']=$_GET['gw'];
       }

	   if((int)$_GET['hy']){
			$where .= " and `hy` = '".(int)$_GET['hy']."' ";
			$urlarr['hy']=(int)$_GET['hy'];
		}
	   if((int)$_GET['provinceid']){
			$where .= " and `provinceid` = '".(int)$_GET['provinceid']."' ";
			$urlarr['provinceid']=(int)$_GET['provinceid'];
		}
		if((int)$_GET['cityid']){
			$where .= " and `cityid` = '".(int)$_GET['cityid']."' ";
			$urlarr['cityid']=(int)$_GET['cityid'];
		}
		 if((int)$_GET['pr']){
			$where .= " and `pr` = '".(int)$_GET['pr']."' ";
			$urlarr['pr']=(int)$_GET['pr'];
		}
		 if((int)$_GET['mun']){
			$where .= " and `mun` = '".(int)$_GET['mun']."' ";
			$urlarr['mun']=(int)$_GET['mun'];
		}
	    if(trim($_GET['keywords'])){
			$where .= " and `name` like '%".trim($_GET['keywords'])."%' ";
			$urlarr['keywords']=$_GET['keywords'];
		}
	   if(trim($_GET['keyword'])){
		   $_GET['com_type']=(int)$_GET['com_type'];
            if($_GET['com_type']=='1'){
				$where.= "  AND `name` like '%".trim($_GET['keyword'])."%' ";
            }elseif($_GET['com_type']=='2'){
				$mwhere[]=" `username` like '%".trim($_GET['keyword'])."%'";
            }elseif($_GET['com_type']=='3'){
				$where.= "  AND `linkman` like '%".trim($_GET['keyword'])."%' ";
            }elseif($_GET['com_type']=='4'){
				$where.= "  AND `linktel` like '%".trim($_GET['keyword'])."%' ";
            }elseif($_GET['com_type']=='5'){
				$where.= "  AND `linkmail` like '%".trim($_GET['keyword'])."%' ";
            }
			$urlarr['com_type']=$_GET['com_type'];
			$urlarr['keyword']=$_GET['keyword'];
		}
		if($_GET['adtime']){
			if($_GET['adtime']=='1'){
				$mwhere[]=" `reg_date`>'".strtotime(date("Y-m-d 00:00:00"))."'";
			}else{
				$mwhere[]=" `reg_date`>'".strtotime('-'.intval($_GET['adtime']).' day')."'";
			}
			$urlarr['adtime']=$_GET['adtime'];
		}
		if($_GET['lotime']){
			if($_GET['lotime']=='1'){
				$mwhere[]=" `login_date`>'".strtotime(date("Y-m-d 00:00:00"))."'";
			}else{
				$mwhere[]=" `login_date`>'".strtotime('-'.intval($_GET['lotime']).' day')."'";
			}
			$urlarr['lotime']=$_GET['lotime'];
		}
		if($_GET['source']){
			$mwhere[]=" `source`='".$_GET['source']."'";
			$urlarr['source']=$_GET['source'];
		}
		if(count($mwhere)){
			$username=$UserinfoM->GetMemberList($mwhere,array("field"=>"`username`,`uid`,`reg_date`,`login_date`,`status`,`source`"));
			$uids=array();
			foreach($username as $val){
				$uids[]=$val['uid'];
			}
			$where.=" and `uid` in (".@implode(',',$uids).")";
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
		$PageInfo=$M->get_page("company",$where,$pageurl,$this->config['sy_listnum']);
		$guweninfo=$this->obj->DB_select_all("company_consultant","`id`");
        $this->yunset($PageInfo);
        $rows=$PageInfo['rows'];

		$rows=$this->get_page("company",$where,$pageurl,$this->config['sy_listnum']);

 		if(is_array($rows)&&$rows){
			if(empty($username)){
				foreach($rows as $v){$uids[]=$v['uid'];}
				$username=$UserinfoM->GetMemberList(array("`uid` in (".@implode(",",$uids).")"),array("field"=>"`username`,`uid`,`reg_date`,`login_date`,`status`,`source`,`login_ip`"));
			}
			if(empty($list)){
				$list=$UserinfoM->GetUserstatisAll(array("`uid` in (".@implode(",",$uids).")"),array('usertype'=>2,'field'=>"`uid`,`pay`,`rating`,`rating_name`,`vip_etime`"));
			} 
			$con=$this->obj->DB_select_all("company_consultant");
 			foreach($rows as $k=>$v){
				if(mb_strlen($v['name'])>12){
					$rows[$k]['name']=mb_substr($v['name'],"0","12","utf-8")."...";
 				}
				if($v['did']<1){
					$rows[$k]['did'] = 0;
				}
				foreach($username as $val){
					if($v['uid']==$val['uid']){
						$rows[$k]['username']=$val['username'];
						$rows[$k]['reg_date']=$val['reg_date'];
						$rows[$k]['login_date']=$val['login_date'];
						$rows[$k]['status']=$val['status'];
						$rows[$k]['source']=$val['source'];
						$rows[$k]['login_ip']=$val['login_ip'];
					}
				}
				foreach($list as $val){
					if($v['uid']==$val['uid']){
						$rows[$k]['rating']=$val['rating'];
						$rows[$k]['pay']=$val['pay'];
						$rows[$k]['rating_name']=$val['rating_name'];
						$rows[$k]['vip_etime']=$val['vip_etime'];
						$rows[$k]['integral']=$val['integral'];
					}
				}
				foreach($con as $val){
					if($v['conid']==$val['id']){
						$rows[$k]['con']=$val['username'];
					}
				}
			}
		}
		$guweninfo=$this->obj->DB_select_all("company_consultant","`id`>'0'");
		$this->yunset("guweninfo",$guweninfo);
		$AdminM=$this->MODEL('admin');
		$power=$AdminM->GetPurview();
		if(in_array('141',$power)){
			$this->yunset("email_promiss",'1');
		}
		if(in_array('163',$power)){
			$this->yunset("moblie_promiss",'1');
		}

		$where=str_replace(array("(",")"),array("[","]"),$where);
		$this->yunset("guweninfo",$guweninfo);
		$this->yunset("where", $where);
		$this->yunset("rows",$rows);
		$this->siteadmin_tpl(array('admin_company'));
	}

	function edit_action(){
		$_GET['id']=(int)$_GET['id'];
		if((int)$_GET['id']){
			$UserinfoM=$this->MODEL('userinfo');
			$CompanyM=$this->MODEL('company');
			$com_info = $UserinfoM->GetMemberOne(array("uid"=>(int)$_GET['id']));
			$row =$CompanyM->GetCompanyInfo(array("uid"=>(int)$_GET['id']));
			$statis =$UserinfoM->GetUserstatisOne(array("uid"=>(int)$_GET['id']),array('usertype'=>2));
			$rating_list =$UserinfoM->GetRatinginfoAll(array("category"=>1));
			if($row['linkphone']){
				$linkphone=@explode('-',$row['linkphone']);
				$row['phoneone']=$linkphone[0];
				$row['phonetwo']=$linkphone[1];
				$row['phonethree']=$linkphone[2]; 
			}
			if ($row['comqcode']&& file_exists(str_replace('..',APP_PATH,'.'.$row['comqcode']))){
			    $row['comqcode']=str_replace('./', $this->config['sy_weburl'].'/', $row['comqcode']);
			}else{
				$row['comqcode']='';
			}
			if ($row['welfare']){
				$row['arraywelfare']=explode(',', $row['welfare']);
			}
			$this->yunset("statis",$statis);
			$this->yunset("row",$row);
			$this->yunset("rating_list",$rating_list);
			$this->yunset("rating",(int)$_GET['rating']);
			$this->yunset("com_info",$com_info);
			$CacheM=$this->MODEL('cache');
			$CacheList=$CacheM->GetCache(array('hy','city','com'));
            $this->yunset($CacheList);
		}else if($_POST['com_update']){
			
		}
		$this->siteadmin_tpl(array('admin_member_comedit'));
	}
	function editcom_action(){
	    $email=$_POST['email'];
	    $uid=(int)$_POST['uid'];
	    $UserinfoM=$this->MODEL('userinfo');
	    $CompanyM=$this->MODEL('company');
	    $mem = $UserinfoM->GetMemberOne(array("uid"=>$uid));
	    $company =$CompanyM->GetCompanyInfo(array("uid"=>$uid));
	    if($mem['username']!=$_POST['username'] && $_POST['username']!=""){
	        $num = $UserinfoM->GetMemberNum(array("username"=>$_POST['username']));
	        if($num>0){
	            $this->ACT_layer_msg("用户名已存在！",8,$_SERVER['HTTP_REFERER'],2,1);
	        }else{
	            $UserinfoM->UpdateMember(array("username"=>$_POST['username']),array("uid"=>$uid));
	        }
	    }
	    $user = $UserinfoM->GetMemberOne(array("email"=>$email,"`uid`<>'$uid'"),array("field"=>'name'));
	    if(is_array($user)){
	        $this->ACT_layer_msg( "邮箱已存在！",8,$_SERVER['HTTP_REFERER'],2,1);
	    }else{
	        $UserinfoM->UpdateCompany(array("r_status"=>$_POST['status']),array("uid"=>$uid));
	        if($_POST['status']=='2'){
	            $mem = $UserinfoM->GetMemberOne(array("uid"=>$uid),array("field"=>'`email`,`status`,`usertype`,`uid`'));
	            if($mem['status']!='2'){
	                $data=$this->forsend($mem);
                  $notice = $this->MODEL('notice');
                  $notice->sendEmailType(array("email"=>$mem['email'],"lock_info"=>$_POST['lock_info'],"uid"=>$data['uid'],"name"=>$data['name'],"type"=>"lock"));
	                $UserinfoM->UpdateCompany(array("lock_info"=>$_POST['lock_info']),array("uid"=>$uid));
	            }
	        }
	        $this->obj->DB_update_all("member","`lock_info`='".$_POST['lock_info']."',`status`='".$_POST['status']."'","`uid`='".$_POST['uid']."'");
	        unset($_POST['com_update']);
	        $ratingid = (int)$_POST['ratingid'];
	        unset($_POST['ratingid']);
	        $post['uid']=$uid;
	        $post['password']=$_POST['password'];
	        $post['email']=$_POST['email'];
	        $post['moblie']=$_POST['moblie'];
	        $post['status']=$_POST['status'];
	        if(trim($post['password'])){
	            $nid = $this->uc_edit_pw($post,1,"index.php?m=com_member");
	        }
	        $linkphone=array();
	        if($_POST['phoneone']){
	            $linkphone[]=$_POST['phoneone'];
	        }
	        if($_POST['phonetwo']){
	            $linkphone[]=$_POST['phonetwo'];
	        }
	        if($_POST['phonethree']){
	            $linkphone[]=$_POST['phonethree'];
	        }
	        $_POST['linkphone']=pylode('-',$linkphone);
	        if($_FILES['comqcode']['tmp_name']){
				$UploadM=$this->MODEL('upload');
	            $upload=$UploadM->Upload_pic("../data/upload/company/",false);
	            $comqcode=$upload->picture($_FILES['comqcode']);
	            $picmsg=$UploadM->picmsg($comqcode,$_SERVER['HTTP_REFERER']);
 				if($picmsg['status'] == $comqcode){
					$this->ACT_layer_msg($picmsg['msg'],8);
				}
	            $comqcode = str_replace("../data/","./data/",$comqcode);
	            if($company['comqcode']){
	                unlink_pic(".".$company['comqcode']);
	            }
	        }
	        $value=array();
	        $value['name']=$_POST['name'];
	        $value['shortname']=$_POST['shortname'];
	        $value['hy']=$_POST['hy'];
	        $value['pr']=$_POST['pr'];
	        $value['address']=$_POST['address'];
	        $value['provinceid']=$_POST['provinceid'];
	        $value['cityid']=$_POST['cityid'];
	        $value['three_cityid']=$_POST['three_cityid'];
	        $value['mun']=$_POST['mun'];
	        $value['linkphone']=$_POST['linkphone'];
	        $value['linktel']=$_POST['moblie'];
	        $value['money']=$_POST['money'];
	        $value['moneytype']=$_POST['moneytype'];
	        $value['zip']=$_POST['zip'];
	        $value['linkman']=$_POST['linkman'];
	        $value['linkjob']=$_POST['linkjob'];
	        $value['linkqq']=$_POST['linkqq'];
	        $value['website']=$_POST['website'];
	        $value['admin_remark']=$_POST['admin_remark'];
	        $value['infostatus']=$_POST['infostatus'];
	        $value['comqcode']=$comqcode;
	        $value['linkmail']=$_POST['email'];
	        $content=str_replace(array("&amp;","background-color:#ffffff","background-color:#fff","white-space:nowrap;"),array("&",'','',''),$_POST['content']);
	        $value['content']=$content;
	        $value['welfare']=@implode(',',$_POST['welfare']);
	       	$value['busstops']=$_POST['busstops'];
	        $ncid=$UserinfoM->UpdateCompany($value,array("uid"=>$uid));
	        $this->obj->DB_update_all("member","`email`='".$_POST['email']."',`moblie`='".$_POST['moblie']."'","`uid`='".$_POST['uid']."'");
	        $statis =$UserinfoM->GetUserstatisOne(array("uid"=>$uid),array('field'=>'rating',"usertype"=>2));
	    
	        if($ratingid != $statis['rating']){
	            $rat_value=$UserinfoM->FetchRatingInfo(array(),array('id'=>$ratingid,"uid"=>$uid));
	            $newrating=$UserinfoM->GetRatinginfoOne(array("id"=>$ratingid),array('field'=>"name"));
	            $this->MODEL('log')->admin_log("企业会员(ID".$uid.")更新为【".$newrating['name']."】");
	        }else{
	            if($_POST['vip_etime']){
	                $rat_value['vip_etime']=strtotime($_POST['vip_etime']);
	            }else{
	                $rat_value['vip_etime']='0';
	            }
	            $rat_value['job_num']=$_POST['job_num'];
	            $rat_value['down_resume']=$_POST['down_resume'];
	            $rat_value['editjob_num']=$_POST['editjob_num'];
	            $rat_value['invite_resume']=$_POST['invite_resume'];
	            $rat_value['breakjob_num']=$_POST['breakjob_num'];
	            $rat_value['part_num']=$_POST['part_num'];
	            $rat_value['editpart_num']=$_POST['editpart_num'];
	            $rat_value['breakpart_num']=$_POST['breakpart_num'];
	            $rat_value['lt_job_num']=$_POST['lt_job_num'];
	            $rat_value['lt_down_resume']=$_POST['lt_resume'];
	            $rat_value['lt_editjob_num']=$_POST['lt_editjob_num'];
	            $rat_value['lt_breakjob_num']=$_POST['lt_breakjob_num'];
	            $rat_value['zph_num']=$_POST['zph_num'];
	        }
	        $UserinfoM->UpdateUserStatis($rat_value,array("uid"=>$uid),array('usertype'=>2));
	        $job_value['com_name']=$_POST['name'];
	        $job_value['pr']=$_POST['pr'];
	        $job_value['mun']=$_POST['mun'];
	        $job_value['rating']=$ratingid;
	        $job_value['com_provinceid']=$_POST['provinceid'];
	        if($_POST['status']=='1'){
	            $rstatus='1';
	        }else{
	            $rstatus='2';
	        }
	        $job_value['r_status']=$rstatus;
	        $JobM=$this->MODEL('job');
	        $CompanyM=$this->MODEL('company');
	        $CompanyM->UpdateNames(array("name"=>$_POST['name']),array("uid"=>$uid));
	        $JobM->UpdateComjob($job_value,array("uid"=>$uid));
	        $this->obj->DB_update_all("partjob","`r_status`='".$rstatus."'","`uid`='".$uid."'");
	        delfiledir("../data/upload/tel/".$uid);
	        if ($ncid){
	            $this->ACT_layer_msg( "企业会员(ID:".$uid.")修改成功！",9,$_SERVER['HTTP_REFERER'],2,1);
	        }
	    }
	}
	function rating_action(){
		$ratingid = (int)$_POST['ratingid'];
		$UserinfoM=$this->MODEL('userinfo');
		$statis = $UserinfoM->GetUserstatisAll(array("uid"=>$_POST['uid']),array("usertype"=>2));
		if(is_array($statis) && !empty($statis)) {
			$value=$UserinfoM->FetchRatingInfo(array(),array('id'=>$ratingid,"uid"=>$_POST['uid']));
			$UserinfoM->UpdateUserStatis($value,array("uid"=>$_POST['uid']),array('usertype'=>2));
			$newrating=$UserinfoM->GetRatinginfoOne(array("id"=>$ratingid),array('field'=>"name")); 
			$this->MODEL('log')->admin_log("企业会员(ID".$uid.")更新为【".$newrating['name']."】");  
		}else{
			$member=$UserinfoM->GetMemberOne(array("uid"=>$_POST['uid']),array("field"=>"did"));
			$AdminM=$this->MODEL('admin');
			$value=$UserinfoM->FetchRatingInfo(array("uid"=>$_POST['uid']),array('id'=>$ratingid,"uid"=>$_POST['uid']));
			$value['did']=$member['did'];
			$AdminM->InsertInfo('company_statis',$value);
			$this->MODEL('log')->admin_log("企业会员(ID".$_POST['uid'].")添加会员等级");
		}
		echo "1";die;
	}
	function add_action(){
		$rating_list = $this->obj->DB_select_all("company_rating","`category`=1");
		if($_POST['submit']){
			extract($_POST);
			if($username==""||mb_strlen($username)<2||mb_strlen($username)>16){
				$data['msg']= "会员名不能为空或不符合要求！";
				$data['type']='8';
			}elseif($password==""||mb_strlen($password)<6||mb_strlen($password)>20){
				$data['msg']= "密码不能为空或不符合要求！";
				$data['type']='8';
			}else{
				if($this->config['sy_uc_type']=="uc_center"){
					$this->uc_open();
					$user = uc_get_user($username);
				}else{
					if ($username!=""){
						$user = $this->obj->DB_select_once("member","`username`='$username'");
					}
					if ($email!=""){
						$comemail = $this->obj->DB_select_once("member","`email`='$email'");
					}
					if ($moblie!=""){
						$commoblie = $this->obj->DB_select_once("company","`linktel`='$moblie'");
					}
					if ($name!=""){
						$comname = $this->obj->DB_select_once("company","`name`='$name'");
					}
				}
				if(is_array($user)){
					$data['msg']= "用户名已存在！";
					$data['type']='8';
				}elseif(is_array($comemail)){
					$data['msg']= "邮箱已存在！";
					$data['type']='8';
				}elseif(is_array($commoblie)){
					$data['msg']= "联系手机已存在！";
					$data['type']='8';
				}elseif(is_array($comname)){
					$data['msg']= "公司全称已存在！";
					$data['type']='8';
				}else{
					$ip = fun_ip_get();
					$time = time();
					if($this->config['sy_uc_type']=="uc_center"){
						$uid=uc_user_register($_POST['username'],$_POST['password'],$_POST['email']);
						if($uid<0){
							$this->obj->get_admin_msg("index.php?m=com_member&c=add","该邮箱已存在！");
						}else{
							list($uid,$username,$email,$password,$salt)=uc_get_user($username);
							$value = "`username`='$username',`password`='$password',`email`='$email',`usertype`='2',`address`='$address',`status`='$status',`salt`='$salt',`moblie`='$moblie',`reg_date`='$time',`reg_ip`='$ip'";
						}
					}else{
						$salt = substr(uniqid(rand()), -6);
						$pass = md5(md5($password).$salt);
						$value = "`username`='$username',`password`='$pass',`email`='$email',`usertype`='2',`address`='$address',`status`='$status',`salt`='$salt',`moblie`='$moblie',`reg_date`='$time',`reg_ip`='$ip'";
					}
					$nid = $this->obj->DB_insert_once("member",$value);
					$uid = $nid;
					
					if($uid>0){
						$this->obj->DB_insert_once("company","`uid`='$uid',`name`='$name',`shortname`='$shortname',`linkphone`='$areacode-$telphone-$exten',`linktel`='$moblie',`linkmail`='$email',`address`='$address'");
						$value = "`uid`='$uid',";
						$rat_arr = @explode("+",$rating_name);
						
						$ratingM = $this->MODEL('rating');
						$value.=$ratingM->rating_info($rat_arr[0]);
						$this->obj->DB_insert_once("company_statis",$value);
						$data['msg']="会员(ID:".$uid.")添加成功";
						$data['type']='9';
					}
				}
			}
			if($_POST['type']){
				echo "<script type='text/javascript'>window.location.href='index.php?m=admin_company_job&c=show&uid=".$nid."'</script>";die;
			}else{
				if($data['type']=='9'){
				$this->ACT_layer_msg($data['msg'],$data['type'],"index.php?m=admin_company",2,1);
				}else{
				$this->ACT_layer_msg($data['msg'],$data['type']);
				}
			}

		}
		$this->yunset("get_info",$_GET);
		$this->yunset("rating_list",$rating_list);
		$this->siteadmin_tpl(array('admin_member_comadd'));
	}
	function getstatis_action(){
		if($_POST['uid']){
			$UserinfoM=$this->MODEL('userinfo');
			$rating	= $UserinfoM->GetUserstatisOne(array("uid"=>intval($_POST['uid'])),array('usertype'=>2));
			if($rating['vip_etime']>0){
				$rating['vipetime'] = date("Y-m-d",$rating['vip_etime']);
			}else{
				$rating['vipetime'] = '不限';
			}
			echo json_encode($rating);die;
		}
	}
	function getrating_action(){
		if($_POST['id']){
			$UserinfoM=$this->MODEL('userinfo');
			$rating	= $UserinfoM->GetRatinginfoOne(array("id"=>intval($_POST['id'])));
			if($rating['service_time']>0){
				$rating['oldetime'] = time()+$rating['service_time']*86400;
				$rating['vipetime'] = date("Y-m-d",(time()+$rating['service_time']*86400));
			}else{
				$rating['oldetime'] = 0;
				$rating['vipetime'] = '不限';
			}
			echo json_encode($rating);
		}
	}
	function uprating_action(){
		 if($_POST['ratuid']){
			$uid = intval($_POST['ratuid']);
			$UserinfoM=$this->MODEL('userinfo');
			$statis	= $UserinfoM->GetUserstatisOne(array("uid"=>intval($uid)),array('usertype'=>2,'field'=>'rating'));
			unset($_POST['ratuid']);unset($_POST['pytoken']);
			if((int)$_POST['addday']>0){
				if((int)$_POST['oldetime']>0){
					$_POST['vip_etime'] = intval($_POST['oldetime'])+intval($_POST['addday'])*86400;
				}else{
					$_POST['vip_etime'] = time()+intval($_POST['addday'])*86400;
				}
			}else{
				$_POST['vip_etime'] = intval($_POST['oldetime']);
			}
			unset($_POST['addday']);
			unset($_POST['oldetime']);
			foreach($_POST as $key=>$value){
				$statisValue[$key] =$value;
			}
			if($statis['rating'] != $_POST['rating']){
				$statisValue['vip_stime']=time();
			}
			$id=$UserinfoM->UpdateUserStatis($statisValue,array('uid'=>$uid),array('usertype'=>2));
			if($statis['rating'] != $_POST['rating']&&$id){
				$JobM=$this->MODEL('job');
				$JobM->UpdateComjob(array('rating'=>$_POST['rating']),array('uid'=>$uid));
			}
			$id?$this->ACT_layer_msg("企业会员等级(ID:".$uid.")修改成功！",9,$_SERVER['HTTP_REFERER'],2,1):$this->ACT_layer_msg("修改失败！",8,$_SERVER['HTTP_REFERER']);
		}else{
			$this->ACT_layer_msg( "非法操作！",8,$_SERVER['HTTP_REFERER']);
		}

	}
	function recommend_action(){
		$UserinfoM=$this->MODEL('userinfo');
		$nid=$UserinfoM->UpdateCompany(array($_GET['type']=>(int)$_GET['rec']),array("uid"=>(int)$_GET['id']));
		$this->MODEL('log')->admin_log("知名企业(ID:".(int)$_GET['id'].")设置成功");
		echo $nid?1:0;die;
	}
	function del_action(){
		$this->check_token();
	    if($_GET['del']){
	    	$del=$_GET['del'];
	    	if($del){
				$UserinfoM=$this->MODEL('userinfo');
				$CompanyM=$this->MODEL('company');
				$FriendM=$this->MODEL('friend');
				$AdminM=$this->MODEL('admin'); 
				$ResumeM=$this->MODEL('resume');
				$JobM=$this->MODEL('job');
	    		if(is_array($del)){
	    			$layer_type=1;
	    			$uids = pylode(",",$del);
	    			foreach($del as $k=>$v){
	    				delfiledir("../data/upload/tel/".intval($v));
	    			}
					$company=$UserinfoM->GetUserinfoList(array("`uid` in (".$uids.") and `logo`<>''"),array("usertype"=>"2",'field'=>"logo,firmpic"));

				    if(is_array($company)){
				    	foreach($company as $v){
				    		unlink_pic(".".$v['logo']);
				    		unlink_pic(".".$v['firmpic']);
				    	}
				    }
					$cert=$CompanyM->GetCertAll(array("`uid` in (".$uids.")","type"=>'3'),array('field'=>"check"));
		    	    if(is_array($cert)){
				    	foreach($cert as $v){
				    		unlink_pic("../".$v['check']);
				    	}
				    }
					$product=$CompanyM->GetProductAll(array("`uid` in (".$uids.")"),array('field'=>"pic"));
		    	    if(is_array($product)){
		    	    	foreach($product as $val){
		    	    		unlink_pic("../".$val['pic']);
		    	    	}
		    	    }
					$show=$CompanyM->GetShowAll(array("`uid` in (".$uids.")"),array('field'=>"picurl"));
		    	    if(is_array($show)){
		    	    	foreach($show as $val){
		    	    		unlink_pic("../".$val['picurl']);
		    	    	}
		    	    }
					$uhotjob=$CompanyM->GetHotJobAll(array("`uid` in (".$uids.")"),array('field'=>"hot_pic"));
		    	    if(is_array($uhotjob)){
		    	    	foreach($uhotjob as $val){
		    	    		unlink_pic("../".$val['hot_pic']);
		    	    	}
		    	    }
					$banner=$CompanyM->GetBannerAll(array("`uid` in (".$uids.")"),array('field'=>"pic"));
		    	    if(is_array($banner)){
		    	    	foreach($banner as $val){
		    	    		unlink_pic($val['pic']);
		    	    	}
		    	    }

					
					$ResumeM->DeleteLookResume(array("`com_id` in (".$uids.")"));
					$ResumeM->DeleteDownResume(array("`comid` in (".$uids.")"));
					$JobM->DeleteLookJob(array("`com_id` in (".$uids.")"));
		    	}else{
		    		$layer_type=0;
					$uids=$del = intval($del);
		    		delfiledir("../data/upload/tel/".$del);
					$company=$CompanyM->GetCompanyInfo(array("uid"=>$del,"`logo`!=''"),array("field"=>"logo,firmpic"));
				    unlink_pic(".".$company['logo']);
				    unlink_pic(".".$company['firmpic'])
					;
					$cert=$CompanyM->GetCertOne(array("uid"=>$del,"type"=>3),array("field"=>"check"));
		    	    unlink_pic("../".$cert['check']);
					$product=$CompanyM->GetProductAll(array("uid"=>$del),array("field"=>"pic"));
		    	    if(is_array($product)){
		    	    	foreach($product as $v){
		    	    		unlink_pic("../".$v['pic']);
		    	    	}
		    	    }
					$show=$CompanyM->GetShowAll(array("uid"=>$del),array('field'=>"picurl"));
		    	    if(is_array($show)) {
		    	    	foreach($show as $v){
		    	    		unlink_pic("../".$v['picurl']);
		    	    	}
		    	    }
					$uhotjob=$CompanyM->GetHotJobAll(array("uid"=>$del),array('field'=>"hot_pic"));
		    	    if(is_array($uhotjob)) {
		    	    	foreach($uhotjob as $val) {
		    	    		unlink_pic("../".$val['hot_pic']);
		    	    	}
		    	    }
					$banner=$CompanyM->GetBannerOnes(array("uid"=>$del),array('field'=>"pic"));
					unlink_pic($banner['pic']);
					$ResumeM->DeleteLookResume(array("com_id"=>$del));
					$ResumeM->DeleteDownResume(array("comid"=>$del));
					$JobM->DeleteLookJob(array("com_id"=>$del));
		    	}

				$deltable=array("member","company","company_job","company_cert","company_msg","company_news","company_order","company_product","company_show","banner","company_statis","question","attention","lt_job","hotjob","invoice_record","px_zixun","px_subject_collect","special_com","zhaopinhui_com","partjob","answer","answer_review","evaluate_log","subscribe","subscriberecord","coupon_list",'member_log');
				$AdminM->DelUsers($deltable,$uids,2);
	    		$this->layer_msg( "公司(ID:".$uids.")删除成功！",9,$layer_type,$_SERVER['HTTP_REFERER']);
	    	}else{
				$this->layer_msg( "请选择您要删除的公司！",8,1);
	    	}
	    }
	}
	function lockinfo_action(){
		$UserinfoM=$this->MODEL('userinfo');
		$userinfo = $UserinfoM->GetMemberOne(array("uid"=>$_POST['uid']),array('field'=>'lock_info'));
		echo $userinfo['lock_info'];die;
	}
	function lock_action(){
		$_POST['uid']=intval($_POST['uid']); 
		$_POST['status']=intval($_POST['status']); 
		$UserinfoM=$this->MODEL('userinfo');
		$JobM=$this->MODEL('job');
		$email=$UserinfoM->GetUserinfoOne(array("uid"=>$_POST['uid']),array("usertype"=>2,"field"=>"`email`,`name`"));
		$JobM->UpdateComjob(array("r_status"=>$_POST['status']),array("uid"=>$_POST['uid']));
		$this->obj->DB_update_all("partjob","`r_status`='".$_POST['status']."'","`uid`='".$_POST['uid']."'");
		$UserinfoM->UpdateCompany(array("r_status"=>$_POST['status']),array("uid"=>$_POST['uid']));
		$id=$UserinfoM->UpdateMember(array("status"=>$_POST['status'],"lock_info"=>$_POST['lock_info']),array("uid"=>$_POST['uid']));

    $notice = $this->MODEL('notice');
    $notice->sendEmailType(array("email"=>$email['email'],"uid"=>$_POST['uid'],"name"=>$email['name'],"lock_info"=>$_POST['lock_info'],"type"=>"lock"));
		$id?$this->ACT_layer_msg("企业会员(ID:".$_POST['uid'].")锁定设置成功！",9,$_SERVER['HTTP_REFERER'],2,1):$this->ACT_layer_msg( "设置失败！",8,$_SERVER['HTTP_REFERER']);
	}
	function status_action(){
		extract($_POST);
		$id = @explode(",",$uid);
		$UserinfoM=$this->MODEL('userinfo');
		$member=$UserinfoM->GetMemberList(array("`uid` in (".$uid.")"),array("field"=>"`email`,`uid`"));
		if(is_array($member)&&$member){
			$company=$UserinfoM->GetUserinfoList(array("`uid` in (".$uid.")"),array("field"=>"`name`,`uid`"));
			$info=array();
			foreach($company as $val){
				$info[$val['uid']]=$val['name'];
			}
      
      $notice = $this->MODEL('notice');
			foreach($member as $v){
			    $idlist[] =$v['uid'];
			    if($status>0){
			        if($status==1){
			            $_POST['states'] = '审核通过！';
			        }else{
			            $_POST['states'] = '审核未通过！';
			        }
			    }
			    $data = array("uid"=>$v['uid'],"name"=>$info[$v['uid']],"email"=>$v['email'],"moblie"=>$v['moblie'],
            "auto_statis"=>$_POST['states'],"status_info"=>$statusbody,"date"=>date("Y-m-d H:i:s"),"type"=>"userstatus");
          $notice->sendEmailType($data);
          $notice->sendSMSType($data);
			}
			if(trim($statusbody)){
				$lock_info=$statusbody;
			}
			$aid = @implode(",",$idlist);
			$id=$UserinfoM->UpdateMember(array("status"=>$status,"lock_info"=>$lock_info),array("`uid` IN (".$aid.")"));
			if($id){
				if($status=="1"){
					$rstatus='1';
				}else{
					$rstatus='2';
				}
				$JobM=$this->MODEL("job");
				$CompanyM=$this->MODEL("company");
				$CompanyM->UpdateCompany("company","`r_status`='".$rstatus."'","`uid` IN (".$aid.")");
				$JobM->UpdateComjob("company_job","`r_status`='".$rstatus."'","`uid` IN (".$aid.")");
				$this->obj->DB_update_all("partjob","`r_status`='".$rstatus."'","`uid` IN (".$aid.")");
				$this->ACT_layer_msg("企业会员审核(ID:".$aid.")设置成功！",9,$_SERVER['HTTP_REFERER'],2,1);
			}else{
				$this->ACT_layer_msg("审核设置失败！",8,$_SERVER['HTTP_REFERER']);
			}
		}else{
			$this->ACT_layer_msg( "非法操作！",8,$_SERVER['HTTP_REFERER']);
		}
	}
	function delhot_action(){
		$this->check_token();
	    if(isset($_GET['id'])){
	    	$hot=$this->obj->DB_select_once("hotjob","`uid`='".$_GET['id']."'");
			unlink_pic("../".$hot['hot_pic']);
			$result=$this->obj->DB_delete_all("hotjob","`uid`='".$_GET['id']."'" );
			if($result){
				$this->obj->DB_update_all("company","`hottime`='',`rec`='0'","`uid`='".$hot['uid']."'");
				$this->layer_msg('名企招聘(ID:'.$_GET['id'].')删除成功！',9,0,$_SERVER['HTTP_REFERER']);
			}else{
				$this->layer_msg('删除失败！',8,0,$_SERVER['HTTP_REFERER']);
			}
		}
	}
	function changeorder_action(){
		if($_POST['uid']){
			if(!$_POST['order']){
				$_POST['order']='0';
			}
			$CompanyM=$this->MODEL('company');
			$CompanyM->UpdateCompany(array("order"=>$_POST['order'],"rec"=>'0'),array("uid"=>$_POST['uid']));
			$this->MODEL('log')->admin_log("公司(ID:".$_POST['uid'].")排序设置成功");
		}
		die;
	}
	
	function Imitate_action(){
		extract($_GET);
		$UserInfoM=$this->MODEL('userinfo');
		$user_info =$UserInfoM->GetMemberOne(array("uid"=>$uid));
		$this->cookie->unset_cookie();
		$this->cookie->add_cookie($user_info['uid'],$user_info['username'],$user_info['salt'],$user_info['email'],$user_info['password'],$user_info['usertype'],1,$user_info['did']);
		if($_GET['type']){
		    $url = '/index.php?c=tongji';
		}
		header('Location: '.$this->config['sy_weburl'].'/member'.$url);
	}
	function xls_action(){
		if($_POST['where']){
			$UserInfoM=$this->MODEL('userinfo');
			$gettype=$_POST['type'];
			$_POST['where']=str_replace(array("[","]","an d","\&acute;","\\"),array("(",")","and","'",""),$_POST['where']);
			if(in_array("lastdate",$_POST['type']) || in_array("rating",$_POST['type']))
			{
				foreach($_POST['type'] as $v)
				{
					if($v=="lastdate"){
						$type[]="lastupdate";
					}elseif($v!="rating"&& $v!="vip_stime"){
						$type[]=$v;
					}
				}
				$_POST['type']=$type;
			}
			$select=@implode(",",$_POST['type']);
			if(strstr($select,"rating") && strstr($select,"uid")==false)
			{
				$select=$select.",uid";
			}
			$list=$UserInfoM->GetUserinfoList(array("uid in (".$_POST['uid'].") and ".$_POST['where']),array("field"=>$select,"usertype"=>2));
			if(!empty($list)) {
				if(in_array("rating",$gettype)) {
					foreach($list as $v) {
						$uid[]=$v['uid'];
					}
					$rating=$UserInfoM->GetUserstatisAll(array("uid in (".@implode(",",$uid).")"),array("usertype"=>2,"field"=>"uid,rating_name,vip_stime"));
					foreach($list as $k=>$v) {
						foreach($rating as $val) {
							if($v['uid']==$val['uid']) {
								$list[$k]['rating']=$val['rating_name'];
								$list[$k]['vip_stime']=$val['vip_stime'];
							}
						}
					}
				}
				$this->yunset("list",$list);

				$CacheM=$this->MODEL('cache');
				$CacheList=$CacheM->GetCache(array('hy','city','com'));
				$this->yunset($CacheList);
				$this->yunset("type",$gettype);
				
				$this->MODEL('log')->admin_log("导出公司信息");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename=company.xls");
				$this->siteadmin_tpl(array('admin_company_xls'));
			}
		}
	}
	function check_username_action(){
		$UserInfoM=$this->MODEL('userinfo');
		$username=$_POST['username'];
		$member=$UserInfoM->GetMemberOne(array("username"=>$username),array("field"=>'uid'));
		echo $member['uid'];die;
	}
	function hotjobinfo_action(){
		$CompanyM=$this->MODEL('company');

		if($_GET['id']){
			$hotjob=$CompanyM->GetHotJobOne(array("uid"=>(int)$_GET['id']));
		}else if($_GET['uid']){
			$UserinfoM=$this->MODEL('userinfo');
			$info=$CompanyM->GetCompanyInfo(array("uid"=>(int)$_GET['uid']),array("field"=>"`content`,`name` as username,`uid`,`logo` as `hot_pic`"));
			$rating=$UserinfoM->GetUserstatisOne(array("uid"=>$info['uid']),array("usertype"=>2,"field"=>"`rating` as `rating_id`,`rating_name` as `rating`"));
			$hotjob=array_merge($info,$rating);
			$hotjob['content']=@explode(' ',trim(strip_tags($hotjob['content'])));
			if(is_array($hotjob['content'])&&$hotjob['content']){
				foreach($hotjob['content'] as $val){
					$hotjob['beizhu'].=trim($val);
				}
			}else{
				$hotjob['beizhu']=$hotjob['content'];
			}
			$hotjob['time_start']=time();
		}
		$this->yunset("hotjob",$hotjob);
		$this->yuntpl(array('siteadmin/admin_hotjob_info'));
	}
	function saveusername_action()
	{
		if($_POST['username']){
			$username=$_POST['username'];
			$M=$this->MODEL("userinfo");
			$num=$M->GetMemberNum(array("username"=>$username));
			if($num>0){
				echo 1;die;
			}else{
				$M->UpdateMember(array("username"=>$username),array("uid"=>$_POST['uid']));
				
				echo 0;die;
			}
		}
	}
	function getinfo_action(){
	    if($_POST['comid']){
	        $info= $this->obj->DB_select_once("company","`uid`='".intval($_POST['comid'])."'");
	        $member=$this->obj->DB_select_once("member","`uid`='".$info['uid']."'","`username`,`reg_ip`,`status`");
	        $statis=$this->obj->DB_select_once("company_statis","`uid`='".$info['uid']."'","`rating`");
	        $yyzz=$this->obj->DB_select_once("company_cert","`uid`='".$info['uid']."' and `type`=3 ","`check`");
	        $conid=$info['conid'];
	        if ($conid){
	            $adviser=$this->obj->DB_select_once("company_consultant","`id`=$conid");
	            $info['adviser']=$adviser['username'];
	        }else{
	            $info['adviser']=null;
	        }
	        $info['username']=$member['username'];
	        $info['reg_ip']=$member['reg_ip'];
	        $info['status']=$member['status'];
	        $info['rating']=$statis['rating'];
	        $info['yyzzurl']=str_replace("./",$this->config['sy_weburl']."/",$yyzz['check']);
	        if ($info['linktel']){
	            $info['phone']=$info['linktel'];
	        }else{
	            $info['phone']=$info['linkphone'];
	        }
	        echo json_encode($info);
	    }
	}
	function mintegral_action(){
	    include(CONFIG_PATH."db.data.php");
	    $this->yunset("arr_data",$arr_data);
	    $where='`com_id`='.intval($_GET['comid']).' and `type`=1';
	    $urlarr['c']='mintegral';
	    $urlarr['comid']=intval($_GET['comid']);
	    if($_GET['order']){
	        $where.=" order by ".$_GET['t']." ".$_GET['order'];
	        $urlarr['order']=$_GET['order'];
	        $urlarr['t']=$_GET['t'];
	    }else{
	        $where.=" order by id desc";
	    }
	    $urlarr["page"]="{{page}}";
	    $pageurl=Url($_GET['m'],$urlarr,'admin');
	    $list=$this->get_page("company_pay",$where,$pageurl,$this->config['sy_listnum']);
	    $this->yunset("list",$list);
	    $this->yuntpl(array('siteadmin/admin_company_mintegral'));
	}
	function morder_action(){
	    $search_list[]=array("param"=>"typezf","name"=>'支付类型',"value"=>array("alipay"=>"支付宝","tenpay"=>"财富通","bank"=>"银行转帐"));
	    $search_list[]=array("param"=>"typedd","name"=>'订单类型',"value"=>array("1"=>"会员充值","2"=>"积分充值","3"=>"银行转帐","4"=>"金额充值"));
	    $search_list[]=array("param"=>"order_state","name"=>'订单状态',"value"=>array("0"=>"支付失败","1"=>"等待付款","2"=>"支付成功","3"=>"等待确认"));
	    $lo_time=array('1'=>'今天','3'=>'最近三天','7'=>'最近七天','15'=>'最近半月','30'=>'最近一个月');
	    $search_list[]=array("param"=>"time","name"=>'充值时间',"value"=>$lo_time);
	    $this->yunset("search_list",$search_list);
	    $where='`uid`='.intval($_GET['comid']).'';
	    $urlarr['c']='morder';
	    $urlarr['comid']=intval($_GET['comid']);
	    if($_GET['typezf']){
	        $where .=" and `order_type`='".$_GET['typezf']."'";
	        $urlarr['typezf']=$_GET['typezf'];
	    }
	    if($_GET['typedd']){
	        $where .=" and `type`='".$_GET['typedd']."'";
	        $urlarr['typedd']=$_GET['typedd'];
	    }
	    if (trim($_GET['keyword'])!=""){
	        $where .=" and `order_id` like '%".trim($_GET['keyword'])."%'";
	        $urlarr['keyword']=$_GET['keyword'];
	    }
	    if($_GET['time']){
	        if($_GET['time']=='1'){
	            $where.=" and `order_time` >='".strtotime(date("Y-m-d 00:00:00"))."'";
	        }else{
	            $where .=" and `order_time`>'".strtotime('-'.intval($_GET['time']).' day')."'";
	        }
	        $urlarr['time']=$_GET['time'];
	    }
	    if($_GET['order_state']!=""){
	        $where.=" and `order_state`='".$_GET['order_state']."'";
	        $urlarr['order_state']=$_GET['order_state'];
	    }
	    if($_GET['fb']!=""){
	        $where.=" and `is_invoice`='".$_GET['fb']."'";
	        $urlarr['fb']=$_GET['fb'];
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
	    $rows=$this->get_page("company_order",$where,$pageurl,$this->config['sy_listnum']);
	    include (APP_PATH."/config/db.data.php");
	    if(is_array($rows)){
	        foreach($rows as $k=>$v){
	            $rows[$k]['order_state_n']=$arr_data['paystate'][$v['order_state']];
	            $rows[$k]['order_type_n']=$arr_data['pay'][$v['order_type']];
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
	    $this->yuntpl(array('siteadmin/admin_company_morder'));
	}
	function morderdel_action(){
	    $this->check_token();
	    if($_GET['del']){
	        $del=$_GET['del'];
	        if(is_array($del)){
	            $company_order=$this->obj->DB_select_all("company_order","`id` in(".@implode(',',$del).")","`order_id`");
	            if($company_order&&is_array($company_order)){
	                foreach($company_order as $val){
	                    $order_ids[]=$val['order_id'];
	                }
	                $this->obj->DB_delete_all("invoice_record","`order_id` in(".@implode(',',$order_ids).")","");
	                $this->obj->DB_delete_all("company_order","`id` in(".@implode(',',$del).")","");
	            }
	            $this->layer_msg( "充值记录(ID:".@implode(',',$del).")删除成功！",9,1,$_SERVER['HTTP_REFERER']);
	        }else{
	            $this->layer_msg( "请选择您要删除的信息！",8,1,$_SERVER['HTTP_REFERER']);
	        }
	    }
	    if(isset($_GET['id'])){
	        $company_order=$this->obj->DB_select_once("company_order","`id`='".$_GET['id']."'" ,"`order_id`");
	        $this->obj->DB_delete_all("invoice_record","`order_id`='".$company_order['order_id']."'" );
	        $result=$this->obj->DB_delete_all("company_order","`id`='".$_GET['id']."'" );
	        isset($result)?$this->layer_msg('充值记录(ID:'.$_GET['id'].')删除成功！',9,0,$_SERVER['HTTP_REFERER']):$this->layer_msg('删除失败！',8,0,$_SERVER['HTTP_REFERER']);
	    }else{
	        $this->ACT_layer_msg("非法操作！",8,$_SERVER['HTTP_REFERER']);
	    }
	}
	function msetpay_action(){
	    $del=(int)$_GET['id'];
	    $this->check_token();
	    $row=$this->obj->DB_select_once("company_order","`id`='$del'");
	    if($row['order_state']=='1'||$row['order_state']=='3'){
	        $nid=$this->MODEL('qrorder')->upuser_statis($row);
	        isset($nid)?$this->layer_msg("充值记录(ID:".$del.")确认成功！",9):$this->layer_msg("确认失败,请销后再试！",8);
	    }else{
	        $this->layer_msg("订单已确认，请勿重复操作！",8);
	    }
	}
	function morderedit_action(){
	    $id=(int)$_GET['id'];
	    $row=$this->obj->DB_select_once("company_order","`id`='".$id."'");
	    if(is_array($row)){
	        $member=$this->obj->DB_select_once('member',"`uid`='".$row['uid']."'","uid,username,usertype");
	        $row['username']=$member['username'];
	
	        if($member['usertype']=='1'){
	            $resume=$this->obj->DB_select_once("resume","`uid`='".$member['uid']."'","`uid`,`name`");
	            $row['comname']=$resume['name'];
	        }else if($member['usertype']=='2'){
	            $company=$this->obj->DB_select_once("company","`uid`='".$member['uid']."'","`uid`,`name`");
	            $row['comname']=$company['name'];
	        }else if($member['usertype']=='3'){
	            $lt=$this->obj->DB_select_once("lt_info","`uid`='".$member['uid']."'","`uid`,`realname`");
	            $row['comname']=$lt['realname'];
	        }
	        $orderbank=@explode("@%",$row['order_bank']);
	        if(is_array($orderbank)){
	            foreach($orderbank as $key){
	                $orderbank[]=$key;
	            }
	            $row['bankname']=$orderbank[0];
	            $row['bankid']=$orderbank[1];
	        }
	    }
	    if($row['coupon']){
	        $coupon=$this->obj->DB_select_once("coupon_list","`uid`='".$row[0]['uid']."' and `validity`>'".time()."' and `status`='1'");
	        $row['price']=number_format($row['order_price'],2);
	        $row['order_price']=number_format($row['order_price']-$coupon['coupon_amount'],2);
	        $coupon['coupon_amount']=number_format($coupon['coupon_amount'],2);
	        $this->yunset("coupon",$coupon);
	    }
	    $this->yunset("row",$row);
	    $this->yuntpl(array('siteadmin/admin_company_morder_edit'));
	}
	function mordersave_action(){
	    if($_POST['coupon_amount']){
	        $_POST['order_price']=$_POST['order_price']+$_POST['coupon_amount'];
	    }
	    if(is_uploaded_file($_FILES['order_pic']['tmp_name'])){
			$UploadM=$this->MODEL('upload');
	        $upload=$UploadM->Upload_pic("../data/upload/order/");
	        $pictures=$upload->picture($_FILES['order_pic']);
 			$picmsg=$UploadM->picmsg($pictures,$_SERVER['HTTP_REFERER']);
			if($picmsg['status'] == $pictures){
				$this->ACT_layer_msg($picmsg['msg'],8);
			}
	        $pictures = str_replace("../data/upload/order","./data/upload/order",$pictures);
	    }else{
	        $order=$this->obj->DB_select_once("company_order","`id`='".(int)$_POST['id']."'");
	        $pictures=$order['order_pic'];
	    }
	    $r_id=$this->obj->DB_update_all("company_order","`order_price`='".$_POST['order_price']."',`order_remark`='".$_POST['order_remark']."',`is_invoice`='".$_POST['is_invoice']."',`order_pic`='".$pictures."'","id='".$_POST['id']."'");
	    isset($r_id)?$this->ACT_layer_msg("充值记录(ID:".$_POST['id'].")修改成功！",9,$_SERVER['HTTP_REFERER'],2,1):$this->ACT_layer_msg("修改失败,请销后再试！",8,$_SERVER['HTTP_REFERER']);
	}
	function morderadd_action(){
	    $comid=intval($_GET['comid']);
	    $member=$this->obj->DB_select_once("member","`uid`='".$comid."'",'username');
	    $this->yunset('user',array('uid'=>$comid,'username'=>$member['username']));
	    if(isset($_POST['insert'])){
	        $fsmsg=$_POST['fs']==1?"充值":"扣除";
	        $dingdan=mktime().rand(10000,99999);
	        $num=$_POST['price_int'];
	        $msg=$_POST['price_int'].$this->config['integral_pricename'];
	        $comid=intval($_POST['uid']);
	        if($_POST['fs']==1){
	            $type=true;
	            $integral_v="`integral`=`integral`+".$num."";
	            $_POST['order_type']="adminpay";
	            $data['type']=2;
	        }else{
	            $statis=$this->obj->DB_select_once('comstatis',"`uid`='".$comid."'","pay,integral");
	            if($statis['integral']<$num){
	                $num=$statis['integral'];
	            }
	            $type=false;
	            $integral_v="`integral`=`integral`-".$num."";
	            $data['order_type']="admincut";
	            $data['type']=5;
	        }
	        $data['order_id']=$dingdan;
	        $data['order_price']=$num/$this->config['integral_proportion'];
	        $data['order_time']=mktime();
	        $data['order_state']="2";
	        $data['order_remark']=$_POST['remark'];
	        $data['uid']=$comid;
	        $data['integral']=$num;
	        $nid=$this->obj->DB_update_all('company_statis',$integral_v,"`uid`='".$comid."'");
	        if($nid){
	            $this->MODEL('integral')->insert_company_pay($num,2,$comid,$_POST['remark'],1,'',$type);
	            $nid=$this->obj->insert_into("company_order",$data);
	            $this->ACT_layer_msg("会员(ID:".$comid.")".$fsmsg.$msg."成功！",9,$_SERVER['HTTP_REFERER'],2,1);
	        }else{
	            $this->ACT_layer_msg($fsmsg."失败！",8,$_SERVER['HTTP_REFERER']);
	        }
	    }
	    $this->yuntpl(array('siteadmin/admin_company_morder_add'));
	}
	function mdown_action(){
	    $where='`comid`='.intval($_GET['comid']).'';
	    $urlarr['c']='mdown';
	    $urlarr['comid']=intval($_GET['comid']);
	    if(trim($_GET['keyword'])){
	        if($_GET['type']=="1"){
	            $info=$this->obj->DB_select_all("member","`username` like '%".trim($_GET['keyword'])."%'","`uid`");
	            if(is_array($info)){
	                foreach ($info as $v){
	                    $uid[]=$v['uid'];
	                }
	            }
	            $where.=" and `uid` in (".@implode(",",$uid).")";
	        }elseif ($_GET['type']=="2"){
	            $info=$this->obj->DB_select_all("resume_expect","`name` like '%".trim($_GET['keyword'])."%'","`id`");
	            if(is_array($info)){
	                foreach ($info as $v){
	                    $eid[]=$v['id'];
	                }
	            }
	            $where.=" and `eid` in (".@implode(",",$eid).")";
	        }
	        $urlarr['type']=$_GET['type'];
	        $urlarr['keyword']=$_GET['keyword'];
	    }
	    if($_GET['order']){
	        $where.=" order by ".$_GET['t']." ".$_GET['order'];
	        $urlarr['order']=$_GET['order'];
	        $urlarr['t']=$_GET['t'];
	    }else{
	        $where.=" order by id desc";
	    }
	    $urlarr["page"]="{{page}}";
	    $pageurl=Url($_GET['m'],$urlarr,'admin');
	    $list=$this->get_page("down_resume",$where,$pageurl,$this->config['sy_listnum']);
	    if(is_array($list)){
	        foreach($list as $v){
	            $eid[]=$v['eid'];
	            $uid[]=$v['uid'];
	            $uid[]=$v['comid'];
	            $comid[]=$v['comid'];
	        }
	        $resume=$this->obj->DB_select_all("resume_expect","`id` in (".@implode(",",$eid).")","name,id");
	        $member=$this->obj->DB_select_all("member","`uid` in (".@implode(",",$uid).")","username,uid,usertype");
	        $company=$this->obj->DB_select_all("company","`uid` in (".@implode(",",$comid).")","name,uid");
	        foreach($list as $k=>$v){
	            foreach($company as $val){
	                if($v['comid']==$val['uid']){
	                    $list[$k]['com_name']=$val['name'];
	                }
	            }
	            foreach($resume as $val){
	                if($v['eid']==$val['id']){
	                    $list[$k]['resume']=$val['name'];
	                }
	            }
	            foreach($member as $val){
	                if($v['uid']==$val['uid']){
	                    $list[$k]['username']=$val['username'];
	                }
	                if($v['comid']==$val['uid']){
	                    $list[$k]['com_username']=$val['username'];
	                    $list[$k]['usertype']=$val['usertype'];
	                }
	            }
	        }
	    }
	    $this->yunset("list",$list);
	    $this->yuntpl(array('siteadmin/admin_company_mdown'));
	}
	function mdowndel_action(){
	    $this->check_token();
	    if($_GET['del']){
	        if(is_array($_GET['del'])){
	            $del=@implode(",",$_GET['del']);
	            $layer_status=1;
	        }else{
	            $del=$_GET['del'];
	            $layer_status=0;
	        }
	        $this->obj->DB_delete_all("down_resume","`id` in (".$del.")","");
	        $this->layer_msg( "下载记录(ID:".$del.")删除成功！",9,$layer_status,$_SERVER['HTTP_REFERER']);
	    }else{
	        $this->layer_msg( "请选择您要删除的信息！",8,1,$_SERVER['HTTP_REFERER']);
	    }
	}
	function mapply_action(){
	    $where='`com_id`='.intval($_GET['comid']).'';
	    $urlarr['c']='mapply';
	    $urlarr['comid']=intval($_GET['comid']);
	    if(trim($_GET['keyword'])){
	        if($_GET['type']=="1"){
	            $info=$this->obj->DB_select_all("member","`username` like '%".trim($_GET['keyword'])."%'","`uid`");
	            if(is_array($info)){
	                foreach ($info as $v){
	                    $uid[]=$v['uid'];
	                }
	            }
	            $where.=" and `uid` in (".@implode(",",$uid).")";
	        }elseif ($_GET['type']=="2"){
	            $info=$this->obj->DB_select_all("resume_expect","`name` like '%".trim($_GET['keyword'])."%'","`id`");
	            if(is_array($info)){
	                foreach ($info as $v){
	                    $eid[]=$v['id'];
	                }
	            }
	            $where.=" and `eid` in (".@implode(",",$eid).")";
	        }
	        $urlarr['type']=$_GET['type'];
	        $urlarr['keyword']=$_GET['keyword'];
	    }
	    if($_GET['order']){
	        $where.=" order by ".$_GET['t']." ".$_GET['order'];
	        $urlarr['order']=$_GET['order'];
	        $urlarr['t']=$_GET['t'];
	    }else{
	        $where.=" order by id desc";
	    }
	    $urlarr["page"]="{{page}}";
	    $pageurl=Url($_GET['m'],$urlarr,'admin');
	    $list=$this->get_page("userid_job",$where,$pageurl,$this->config['sy_listnum']);
	    if(is_array($list)){
	        foreach($list as $v){
	            $eid[]=$v['eid'];
	            $uid[]=$v['uid'];
	            $comid[]=$v['com_id'];
	        }
	        $resume=$this->obj->DB_select_all("resume_expect","`id` in (".@implode(",",$eid).")","name,id");
	        $member=$this->obj->DB_select_all("member","`uid` in (".@implode(",",$uid).")","username,uid");
	        $company=$this->obj->DB_select_all("company","`uid` in (".@implode(",",$comid).")","name,uid");
	        foreach($list as $k=>$v){
	            foreach($company as $val){
	                if($v['comid']==$val['uid']){
	                    $list[$k]['com_name']=$val['name'];
	                }
	            }
	            foreach($resume as $val){
	                if($v['eid']==$val['id']){
	                    $list[$k]['resume']=$val['name'];
	                }
	            }
	            foreach($member as $val){
	                if($v['uid']==$val['uid']){
	                    $list[$k]['username']=$val['username'];
	                }
	            }
	        }
	    }
	    $this->yunset("list",$list);
	    $this->yuntpl(array('siteadmin/admin_company_mapply'));
	}
	
	function mapplydel_action(){
	    $this->check_token();
	    if($_GET['del']){
	        if(is_array($_GET['del'])){
	            $id=@implode(",",$_GET['del']);
	            $layer_status=1;
	        }else{
	            $id=$_GET['del'];
	            $layer_status=0;
	        }
	        $sq_num = $this->obj->DB_select_all("userid_job","`id` in (".$id.") and `com_id`='".intval($_GET['comid'])."'","`uid`,`job_id`");
	        if(is_array($sq_num)){
	            $jobid=array();
	            $uid=array();
	            foreach($sq_num as $v){
	                $jobid[]=$v['job_id'];
	                $uid[]=$v['uid'];
	            }
	            if(intval($_POST['type'])!=2){
	                $this->obj->DB_update_all("company_job","`operatime`='".time()."'","`id` in (".pylode(",",$jobid).") and `uid`='".intval($_GET['comid'])."'");
	            }
	            $this->obj->DB_update_all("member_statis","`sq_jobnum`=`sq_jobnum`-1","`uid`  in(".pylode(",",$uid).")");
	        }
	        $num=count($sq_num);
	        $this->obj->DB_update_all("company_statis","`sq_job`=`sq_job`-$num","`uid`='".intval($_GET['comid'])."'");
	        $nid=$this->obj->DB_delete_all("userid_job","`id` in (".$id.") and `com_id`='".intval($_GET['comid'])."'"," ");
	        if($nid){
	            $this->layer_msg('删除成功！',9,$layer_status,$_SERVER['HTTP_REFERER']);
	        }else{
	            $this->layer_msg('删除失败！',8,$layer_status,$_SERVER['HTTP_REFERER']);
	        }
	    }else{
	        $this->layer_msg( "请选择您要删除的信息！",8,1,$_SERVER['HTTP_REFERER']);
	    }
	}
	
	function minvite_action(){
	    $where='`fid`='.intval($_GET['comid']).'';
	    $urlarr['c']='minvite';
	    $urlarr['fid']=intval($_GET['comid']);
	    if(trim($_GET['keyword'])){
	        if($_GET['type']=="1"){
	            $info=$this->obj->DB_select_all("member","`username` like '%".trim($_GET['keyword'])."%'","`uid`");
	            if(is_array($info)){
	                foreach ($info as $v){
	                    $uid[]=$v['uid'];
	                }
	            }
	            $where.=" and `uid` in (".@implode(",",$uid).")";
	        }elseif ($_GET['type']=="2"){
	            $info=$this->obj->DB_select_all("resume_expect","`name` like '%".trim($_GET['keyword'])."%'","`id`");
	            if(is_array($info)){
	                foreach ($info as $v){
	                    $eid[]=$v['id'];
	                }
	            }
	            $where.=" and `eid` in (".@implode(",",$eid).")";
	        }
	        $urlarr['type']=$_GET['type'];
	        $urlarr['keyword']=$_GET['keyword'];
	    }
	    if($_GET['order']){
	        $where.=" order by ".$_GET['t']." ".$_GET['order'];
	        $urlarr['order']=$_GET['order'];
	        $urlarr['t']=$_GET['t'];
	    }else{
	        $where.=" order by id desc";
	    }
	    $urlarr["page"]="{{page}}";
	    $pageurl=Url($_GET['m'],$urlarr,'admin');
	    $list=$this->get_page("userid_msg",$where,$pageurl,$this->config['sy_listnum']);
	    if(is_array($list)){
	        foreach($list as $v){
	            $uid[]=$v['uid'];
	        }
	        $resume=$this->obj->DB_select_all("resume","`uid` in (".@implode(",",$uid).")","name,uid");
	        foreach($list as $k=>$v){
	            foreach($resume as $val){
	                if($v['uid']==$val['uid']){
	                    $list[$k]['resume']=$val['name'];
	                }
	            }
	        }
	    }
	    $this->yunset("list",$list);
	    $this->yuntpl(array('siteadmin/admin_company_minvite'));
	}
	function minvitedel_action(){
	    $this->check_token();
	    if($_GET['del']){
	        if(is_array($_GET['del'])){
	            $del=@implode(",",$_GET['del']);
	            $layer_status=1;
	        }else{
	            $del=$_GET['del'];
	            $layer_status=0;
	        }
	        $this->obj->DB_delete_all("userid_msg","`id` in (".$del.")","");
	        $this->layer_msg( "邀请面试记录(ID:".$del.")删除成功！",9,$layer_status,$_SERVER['HTTP_REFERER']);
	    }else{
	        $this->layer_msg( "请选择您要删除的信息！",8,1,$_SERVER['HTTP_REFERER']);
	    }
	}
	function mjob_action(){
	    $urlarr['c']='mjob';
	    $urlarr['comid']=intval($_GET['comid']);
	    $urlarr['page']="{{page}}";
	    $pageurl=Url($_GET['m'],$urlarr,'admin');
	    $M=$this->MODEL();
	    $PageInfo=$M->get_page("company_job",'`uid`='.intval($_GET['comid']).'',$pageurl,$this->config['sy_listnum']);
	    $this->yunset($PageInfo);
	    $rows=$PageInfo['rows'];
	    include PLUS_PATH."job.cache.php";
	    include PLUS_PATH."industry.cache.php";
	    include PLUS_PATH."com.cache.php";
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
	            $rows[$k]['edu']=$comclass_name[$v['edu']];
	            $rows[$k]['exp']=$comclass_name[$v['exp']];
	            if($v['job_post']){
	                $rows[$k]['job']=$job_name[$v['job_post']];
	            }else{
	                $rows[$k]['job']=$job_name[$v['job1_son']];
	            }
	             
	            $rows[$k]['salary']=$comclass_name[$v['salary']];
	            $rows[$k]['type']=$comclass_name[$v['type']];
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
	            if($v['urgent_time']>time()){
	                $rows[$k]['urgent_day'] = ceil(($v['urgent_time']-time())/86400);
	            }else{
	                $rows[$k]['urgent_day'] = "0";
	            }
	            if($v['rec_time']>time()){
	                $rows[$k]['rec_day'] = ceil(($v['rec_time']-time())/86400);
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
	    $where=str_replace(array("(",")"),array("[","]"),$where);
	    $this->yunset("where",$where);
	    $this->yunset($this->MODEL('cache')->GetCache(array('job','hy')));
	    $this->yunset("rows",$rows);
	    $this->yunset("time",time());
	    $this->yuntpl(array('siteadmin/admin_company_mjob'));
	}
	function mjobshow_action(){
	    $this->yunset($this->MODEL('cache')->GetCache(array('city','hy','com','job')));
	    if($_GET['id']){
	        $show=$this->obj->DB_select_once("company_job","id='".$_GET['id']."'");
	        $show['lang']=@explode(',',$show['lang']);
	        
	        if($show['three_cityid']){
	            $show['circlecity']=$show['three_cityid'];
	        }else if($show['cityid']){
	            $show['circlecity']=$show['cityid'];
	        }else if($show['provinceid']){
	            $show['circlecity']=$show['provinceid'];
	        }
	        $this->yunset("show",$show);
	        $this->yunset("lasturl",$_SERVER['HTTP_REFERER']);
	    }
	
	    if($_POST['update']){
	        $_POST['lang']=@implode(',',$_POST['lang']);
	        
	
	        $_POST['edate']=strtotime($_POST['edate']);
	        $_POST['description'] = str_replace("&amp;","&",$_POST['content']);
	        $_POST['lastupdate'] = time();
	        $lasturl=$_POST['lasturl'];
	        unset($_POST['update']);unset($_POST['content']);unset($_POST['lasturl']);
	        if($_POST['edate']>time()){
	            $_POST['state']="1";
	        }else{
	            $this->ACT_layer_msg("结束时间不能小于当前时间",8,$_SERVER['HTTP_REFERER'],2,1);
	        }
	
	        if($_POST['id']&&$_POST['uid']==''){
	            $job=$this->obj->DB_select_once("company_job","`id`='".$_POST['id']."'","`uid`");
	            $where['id']=$_POST['id'];
	            unset($_POST['id']);
	            $this->obj->update_once("company_job",$_POST,$where);
	            $this->obj->DB_update_all("company","`lastupdate`='".time()."'","`uid`='".$job['uid']."'");
	            $this->ACT_layer_msg("职位(ID:".$where['id'].")修改成功！",9,$lasturl,2,1);
	        }else if($_POST['uid']){
	            $company=$this->obj->DB_select_once("company","`uid`='".$_POST['uid']."'","name,welfare");
	            $statis=$this->obj->DB_select_once("company_statis","`uid`='".$_POST['uid']."'","`vip_etime`,`job_num`,`integral`");
	
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
	                        $this->obj->DB_update_all("company_statis","`job_num`=`job_num`-1","`uid`='".$_POST['uid']."'");
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
	            $id=$this->obj->insert_into("company_job",$_POST);
	            if($id){
	                $this->obj->DB_update_all("company","`jobtime`='".time()."'","`uid`='".$_POST['uid']."'");
	                $this->ACT_layer_msg( "职位(ID:".$id.")发布成功！",9,$_SERVER['HTTP_REFERER'],2,1);
	            }else{
	                $this->ACT_layer_msg( "职位发布失败！",8,$_SERVER['HTTP_REFERER'],2,1);
	            }
	        }
	    }
	    $this->yunset("uid",$_GET['uid']);
	    $this->yuntpl(array('siteadmin/admin_company_job_show'));
	}
	function mshow_action(){
	    $comid=intval($_GET['comid']);
	    $urlarr['c']="mshow";
	    $urlarr['comid']=$comid;
	    $urlarr["page"]="{{page}}";
	    $pageurl=Url($_GET['m'],$urlarr,'admin');
	    $this->get_page("company_show","`uid`='".$comid."' order by sort desc",$pageurl,"12","`title`,`id`,`picurl`,`uid`");
	    $this->yuntpl(array('siteadmin/admin_company_mshow'));
	}
	
	function mshowdel_action(){
	    if($_GET['id']){
	        $row=$this->obj->DB_select_once("company_show","`id`='".(int)$_GET['id']."'","`picurl`");
	        if(is_array($row)){
	            unlink_pic(".".$row['picurl']);
	            $oid=$this->obj->DB_delete_all("company_show","`id`='".(int)$_GET['id']."'");
	        }
	        if($oid){
	            $this->layer_msg('删除成功！',9);
	        }else{
	            $this->layer_msg('删除失败！',8);
	        }
	    }
	}
	function mshowadd_action(){
	    $this->yuntpl(array('siteadmin/admin_company_mshowadd'));
	}
	function mshowsave_action(){
	    if($_POST['submitbtn']){
	        $pid=pylode(',',$_POST['id']);
	        $comid=intval($_POST['comid']);
	        $company_show=$this->obj->DB_select_all("company_show","`id` in (".$pid.") and `uid`='".$comid."'","`id`");
	        if($company_show&&is_array($company_show)){
	            foreach($company_show as $val){
	                $title=$_POST['title_'.$val['id']];
	                $this->obj->update_once("company_show",array("title"=>trim($title)),array("id"=>(int)$val['id']));
	            }
	            $this->ACT_layer_msg("保存成功！",9,'index.php?m=admin_company&c=mshow&comid='.$comid);
	        }else{
	            $this->ACT_layer_msg("非法操作！",8,'index.php?m=admin_company&c=mshow&comid='.$comid);
	        }
	    }else{
	        $this->ACT_layer_msg("非法操作！",8,'index.php?m=admin_company&c=mshow&comid='.$comid);
	    }
	}
	function mshowedit_action(){
	    $picurl=$this->obj->DB_select_once("company_show","`id`='".(int)$_GET['id']."' and `uid`='".(int)$_GET['comid']."'","`picurl`,`title`,`sort`");
	    $this->yunset("picurl",$picurl);
	    $this->yuntpl(array('siteadmin/admin_company_mshowedit'));
	}
	function mshowup_action(){
	    if($_POST['submitbtn']){
	        $time=time();
	        unset($_POST['submitbtn']);
	        if(!empty($_FILES['uplocadpic']['tmp_name'])){
				$UploadM=$this->MODEL('upload');
	            $upload=$UploadM->Upload_pic("../data/upload/show/",false);
	            $uplocadpic=$upload->picture($_FILES['uplocadpic']);
	            $picmsg=$UploadM->picmsg($uplocadpic,$_SERVER['HTTP_REFERER']);
 				if($picmsg['status'] == $uplocadpic){
					$this->ACT_layer_msg($picmsg['msg'],8);
				}
	            $uplocadpic = str_replace("../data/upload/show","./data/upload/show",$uplocadpic);
	            $row=$this->obj->DB_select_once("company_show","`uid`='".(int)$_POST['uid']."' and `id`='".intval($_POST['id'])."'","`picurl`");
	            if(is_array($row)){
	                unlink_pic(".".$row['picurl']);
	            }
	        }else{
	            $uplocadpic=$_POST['picurl'];
	        }
	        $nid=$this->obj->DB_update_all("company_show","`picurl`='".$uplocadpic."',`title`='".$_POST['title']."',`sort`='".$_POST['showsort']."',`ctime`='".$time."'","`uid`='".(int)$_POST['uid']."'and `id`='".$_POST['id']."'");
	        if($nid){
	            $this->ACT_layer_msg("更新成功！",9,'index.php?m=admin_company&c=mshow&comid='.(int)$_POST['uid']);
	        }else{
	            $this->ACT_layer_msg("更新失败！",8,'index.php?m=admin_company&c=mshow&comid='.(int)$_POST['uid']);
	        }
	    }
	}
	function mcomtpl_action(){
	    $comid=intval($_GET['comid']);
	    $list=$this->obj->DB_select_all("company_tpl","`status`='1' and (`service_uid`='0' or FIND_IN_SET('".$comid."',service_uid)) order by id desc");
	    $this->yunset("list",$list);
	    $this->yunset("comid",$comid);
	    $statis=$this->obj->DB_select_once("company_statis","`uid`='".$comid."'","`comtpl`");
	    $this->yunset('statis',$statis);
	    $this->yuntpl(array('siteadmin/admin_company_mcomtpl'));
	}
	function msettpl_action(){
	    $this->check_token();
	    $tpl=$this->obj->DB_select_once("company_tpl","`id`='".intval($_GET['id'])."'","`url`");
	    $oid=$this->obj->update_once("company_statis",array("comtpl"=>$tpl['url']),array("uid"=>intval($_GET['comid'])));
	    if ($oid){
	        $this->layer_msg('设置成功！',9);
	    }else{
	        $this->layer_msg('设置失败！',9);
	    }
	}
	function member_log_action(){
	    $opera=array('1'=>'职位操作','3'=>'下载简历','4'=>'邀请面试','7'=>'修改基本信息','8'=>'修改密码');
	    $search_list[]=array("param"=>"operas","name"=>'操作类型',"value"=>$opera);
	    if($_GET['operas']=='1'||$_GET['operas']=='2'){
	        $parr=array('1'=>'增加','2'=>'修改','3'=>'删除','4'=>'刷新');
	        $search_list[]=array("param"=>"parrs","name"=>'操作内容',"value"=>$parr);
	    }
	    $ad_time=array('1'=>'今天','3'=>'最近三天','7'=>'最近七天','15'=>'最近半月','30'=>'最近一个月');
	    $search_list[]=array("param"=>"end","name"=>'发布时间',"value"=>$ad_time);
	    $this->yunset("search_list",$search_list);
	     
	    $where='`uid`='.intval($_GET['comid']).'';
	    $urlarr['c']='member_log';
	    $urlarr['comid']=intval($_GET['comid']);
	    if($_GET['operas']){
	        $where.=" and `opera`='".$_GET['operas']."'";
	        $urlarr['operas']=$_GET['operas'];
	    }
	    if($_GET['parr']){
	        $where.=" and `type`='".$_GET['parrs']."'";
	        $urlarr['parrs']=$_GET['parrs'];
	    }
	    if($_GET['end']){
	        if($_GET['end']=='1'){
	            $where.=" and `ctime` >= '".strtotime(date("Y-m-d 00:00:00"))."'";
	        }else{
	            $where.=" and `ctime` >= '".strtotime('-'.(int)$_GET['end'].'day')."'";
	        }
	        $urlarr['end']=$_GET['end'];
	    }
	    if($_GET['time']){
			$times=@explode('~',$_GET['time']);
	        $where.=" and `ctime` >='".strtotime($times[0]."00:00:00")."' and `ctime` <='".strtotime($times[1]."23:59:59")."'";
	        $urlarr['time']=$_GET['time'];
	    }
	    if($_GET['order']){
	        $where.=" order by ".$_GET['t']." ".$_GET['order'];
	        $urlarr['order']=$_GET['order'];
	        $urlarr['t']=$_GET['t'];
	    }else{
	        $where.=" order by `id` desc";
	    }
	    $urlarr['page']="{{page}}";
	    $pageurl=Url($_GET['m'],$urlarr,'admin');
	    $rows=$this->get_page("member_log",$where,$pageurl,$this->config['sy_listnum']);
	    if(is_array($rows)){
	        foreach($rows as $v){
	            $uid[]=$v['uid'];
	            $member=$this->obj->DB_select_all("member","`uid` in (".@implode(",",$uid).")","`uid`,`username`");
	        }
	        foreach($rows as $k=>$v){
	            foreach($member as $val){
	                if($v['uid']==$val['uid']){
	                    $rows[$k]['username']=$val['username'];
	                }
	            }
	        }
	    }
	    $this->yunset("rows",$rows);
	    $this->yuntpl(array('siteadmin/admin_company_member_log'));
	}
	function mmeberlogdel_action(){
	    $this->check_token();
	    if($_GET['del']){
	        $del=$_GET['del'];
	        if($del){
	            if(is_array($del)){
	                $layer_type=1;
	                $this->obj->DB_delete_all("member_log","`id` in (".@implode(',',$del).")","");
	                $del=@implode(',',$del);
	            }else{
	                $this->obj->DB_delete_all("member_log","`id`='".$del."'");
	                $layer_type=0;
	            }
	            $this->layer_msg('会员日志(ID:'.$del.')删除成功！',9,$layer_type,$_SERVER['HTTP_REFERER']);
	        }else{
	            $this->layer_msg('请选择您要删除的信息！',8,0,$_SERVER['HTTP_REFERER']);
	        }
	    }
	}
	
	function reset_companypassword_action(){
	    $this->check_token();
	    $data['password']="123456";
	    $data['uid']=$_GET['uid'];
	    $this->uc_edit_pw($data,1,$_SERVER['HTTP_REFERER']);
	    $this->MODEL('log')->admin_log("企业会员（ID:".$_GET['uid']."）重置密码成功");
	    echo "1";
	}
	
	function addgw_action(){
	    extract($_POST);
	    $value="`conid`='".$_POST['conid']."',";
	    $value.="`addtime`='".time()."'";
	    $where="`uid` in (".$uid.")";
	    $nid=$this->obj->DB_update_all("company",$value,$where);
	    $member=$this->obj->DB_select_all("member","`uid` in (".$uid.")");
	    if (is_array($member)&&!empty($member)){
	        if ($nid){
	            $this->ACT_layer_msg("顾问分配成功！",9,$_SERVER['HTTP_REFERER']);
	        }else{
	            $this->ACT_layer_msg("顾问分配失败！",8,$_SERVER['HTTP_REFERER']);
	        }
	    }else{
	        $this->ACT_layer_msg( "非法操作！",8,$_SERVER['HTTP_REFERER']);
	    }
	}
}
?>