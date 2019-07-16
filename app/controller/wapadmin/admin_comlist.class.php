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
class admin_comlist_controller extends adminCommon{
	function index_action(){ 
		$where=$mwhere="1";
		$uids=array();
		if($_GET['status']){
		    if($_GET['status']=='3'){
		        $mwhere.=" and `status`='0'";
		    }else if($_GET['status']){
		        $mwhere.=" and `status`='".intval($_GET['status'])."'";
		    }
		    $urlarr['status']=intval($_GET['status']);
		}
		
		if($mwhere!='1'){
		    $username=$this->obj->DB_select_all("member",$mwhere." and `usertype`='2'","`uid`,`status`");
		    $uids=array();
		    foreach($username as $val){
		        $uids[]=$val['uid'];
		    }
		    $where.=" and `uid` in (".@implode(',',$uids).")";
		}
		if(trim($_GET['keyword'])){
		    $where .= " and `name` like '%".trim($_GET['keyword'])."%' ";
		    $urlarr['keyword']=$_GET['keyword'];
		}
		$where.=" order by `uid` desc";
	
		$urlarr['page']="{{page}}";
		$urlarr['c']=$_GET['c'];
		$pageurl=Url($_GET['m'],$urlarr,'admin');
        $M=$this->MODEL();
		$PageInfo=$M->get_page("company",$where,$pageurl,$this->config['sy_listnum']);
        $this->yunset($PageInfo);
        $rows=$PageInfo['rows'];
		if(is_array($rows)&&$rows){ 
			foreach($rows as $v){
				$uids[]=$v['uid'];
			}
			$username=$this->obj->DB_select_all("member","`uid` in (".@implode(",",$uids).")","`username`,`uid`,`reg_date`,`login_date`,`reg_ip`,`status`,`source`"); 
			if(empty($list)){
				$list=$this->obj->DB_select_all("company_statis","`uid` in (".@implode(",",$uids).")","`uid`,`pay`,`integral`,`rating`,`rating_name`,`vip_etime`,`msg_num`");
			}
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
						$rows[$k]['reg_ip']=$val['reg_ip'];
						$rows[$k]['login_date']=$val['login_date'];
						$rows[$k]['status']=$val['status'];
						$rows[$k]['source']=$val['source'];
					}
				}
				foreach($list as $val){
					if($v['uid']==$val['uid']){
						$rows[$k]['rating_name']=$val['rating_name'];
						$rows[$k]['vip_etime']=$val['vip_etime'];
						$rows[$k]['integral']=$val['integral'];
					}
				}
			}
		} 
        $this->yunset("rows",$rows);
        $this->yunset("backurl", basename($_SERVER['HTTP_REFERER']));
        $this->yunset("headertitle","公司管理");
		$this->yuntpl(array('wapadmin/admin_comlist'));
	}
	function edit_action(){
		$_GET['id']=(int)$_GET['id'];
		if((int)$_GET['id']){
			$row = $this->obj->DB_select_once("company","`uid`='".$_GET['id']."'");
			$member = $this->obj->DB_select_once("member","`uid`='".$_GET['id']."'","`status`,`lock_info`,`username`");  
			$row['username']=$member['username'];
			$row['status']=$member['status'];
			$row['lock_info']=$member['lock_info'];
			if(!$row['logo']||!file_exists(str_replace($this->config['sy_weburl'],APP_PATH,".".$row['logo']))){
				$row['logo']=$this->config['sy_unit_icon'];
			}
			$this->yunset("row",$row); 
			$this->yunset("lasturl",$_SERVER['HTTP_REFERER']); 
			$this->yunset($this->MODEL('cache')->GetCache(array('city','hy','com'))); 
		} 
		
		$lasturl=$_SERVER['HTTP_REFERER'];
		if(strpos($lasturl, 'a=show')===false){
		    if(strpos($lasturl, 'c=admin_comlist')!==false){
		        $this->cookie->setcookie('lasturl',$lasturl,time()+300);
		        $_COOKIE['lasturl']=$lasturl;
		    }
		}
		$this->yunset('lasturl',$_COOKIE['lasturl']);
		
		$this->yunset("row",$row); 
		$this->yunset("headertitle","公司审核");
		$this->yuntpl(array('wapadmin/admin_comshow'));
	}
	function status_action(){
	    if($_POST['id']){
	        $_POST['statusbody']=$this->stringfilter($_POST['statusbody']);
	        $nid=$this->obj->DB_update_all("member","`status`='".$_POST['status']."',`lock_info`='".$_POST['statusbody']."'","`uid`='".$_POST['id']."'");
	        if($nid){
	            if($_POST['status']>0){
	                if($_POST['status']==1){
	                    $_POST['states'] = '审核通过！';
	                }else{
	                    $_POST['states'] = '审核未通过！';
	                }
	            }
	            $company=$this->obj->DB_select_once('company',"uid=".$_POST['id']."","uid,name,linkmail,linktel");              
	            $data = array("uid"=>$company['uid'],"name"=>$company['name'],"email"=>$company['linkmail'],"moblie"=>$company['linktel'],"auto_statis"=>$_POST['states'],"status_info"=>$_POST['statusbody'],"date"=>date("Y-m-d H:i:s"),"type"=>"userstatus");
              $notice = $this->MODEL('notice');
              $notice->sendEmailType($data);
              $notice->sendSMSType($data);
	            $this->layer_msg('公司审核(ID:'.$_POST['id'].')设置成功！',9,0,'index.php?c=admin_comlist');
	        }else{
	            $this->layer_msg('设置失败！',8,0,'index.php?c=admin_comlist&a=status');
	        }
	    }
	}
	function del_action(){
		
	    if($_GET['del']){
	    	$del=$_GET['del'];
	    	if($del){
				$del_array=array("member","company","company_job","company_cert","company_msg","company_news","company_order","company_product","company_show","banner","company_statis","question","attention","lt_job","hotjob","invoice_record","px_zixun","px_subject_collect","special_com","zhaopinhui_com","partjob","answer","answer_review","evaluate_log","subscribe","subscriberecord","coupon_list");

	    		if(is_array($del)){
	    			$layer_type=1;
	    			$uids = @implode(",",$del);
	    			foreach($del as $k=>$v){
	    				delfiledir("../data/upload/tel/".intval($v));
	    			}
				    $company=$this->obj->DB_select_all("company","`uid` in (".$uids.") and `logo`!=''","logo,firmpic");
				    if(is_array($company)){
				    	foreach($company as $v){
				    		unlink_pic(".".$v['logo']);
				    		unlink_pic(".".$v['firmpic']);
				    	}
				    }
		    	    $cert=$this->obj->DB_select_all("company_cert","`uid` in (".$uids.") and `type`='3'","check");
		    	    if(is_array($cert)){
				    	foreach($cert as $v){
				    		unlink_pic("../".$v['check']);
				    	}
				    }
		    	    $product=$this->obj->DB_select_all("company_product","`uid` in (".$uids.")","pic");
		    	    if(is_array($product)){
		    	    	foreach($product as $val){
		    	    		unlink_pic("../".$val['pic']);
		    	    	}
		    	    }
		    	    $show=$this->obj->DB_select_all("company_show","`uid` in (".$uids.")","picurl");
		    	    if(is_array($show)){
		    	    	foreach($show as $val){
		    	    		unlink_pic("../".$val['picurl']);
		    	    	}
		    	    }
		    	    $uhotjob=$this->obj->DB_select_all("hotjob","`uid` in (".$uids.")","hot_pic");
		    	    if(is_array($uhotjob)){
		    	    	foreach($uhotjob as $val){
		    	    		unlink_pic("../".$val['hot_pic']);
		    	    	}
		    	    }
		    	  	$banner=$this->obj->DB_select_all("banner","`uid` in (".$uids.")","pic");
		    	    if(is_array($banner)){
		    	    	foreach($banner as $val)
		    	    	{
		    	    		unlink_pic($val['pic']);
		    	    	}
		    	    }
		    	    

					foreach($del_array as $value){
						$this->obj->DB_delete_all($value,"`uid` in (".$uids.")","");
					}
					$this->obj->DB_delete_all("email_msg","`uid` in (".$uids.") or `cuid` in (".$uids.")"," ");
					$this->obj->DB_delete_all("company_msg","`cuid` in (".$uids.")"," ");
					$this->obj->DB_delete_all("talent_pool","`cuid` in (".$uids.")"," ");
					$this->obj->DB_delete_all("user_entrust_record","`comid` in (".$uids.")"," ");
					$this->obj->DB_delete_all("ad_order","`comid` in (".$uids.")"," ");
		    	    $this->obj->DB_delete_all("company_pay","`com_id` in (".$uids.")"," ");
					$this->obj->DB_delete_all("atn","`uid` in (".$uids.") or `sc_uid` in (".$uids.")","");
					$this->obj->DB_delete_all("look_resume","`com_id` in (".$uids.")","");
					$this->obj->DB_delete_all("fav_job","`com_id` in (".$uids.")","");
					$this->obj->DB_delete_all("userid_msg","`fid` in (".$uids.")","");
					$this->obj->DB_delete_all("userid_job","`com_id` in (".$uids.")","");
					$this->obj->DB_delete_all("look_job","`com_id` in (".$uids.")","");
					$this->obj->DB_delete_all("message","`fa_uid` in (".$uids.")","");
		    	    $this->obj->DB_delete_all("msg","`job_uid` in (".$uids.")","");
		    	    $this->obj->DB_delete_all("blacklist","`c_uid` in (".$uids.")","");
		    	    $this->obj->DB_delete_all("rebates","`job_uid` in (".$uids.") or `uid` in (".$uids.")"," ");
		    	    $this->obj->DB_delete_all("report","`p_uid` in ($uids) or `c_uid` in ($uids)","");
					$this->obj->DB_delete_all("part_apply","`comid` in (".$uids.")","");
					$this->obj->DB_delete_all("part_collect","`comid` in (".$uids.")","");
					$this->obj->DB_delete_all("down_resume","`comid` in (".$uids.")","");
		    	}else{
		    		$layer_type=0;
					$uids=$del = intval($del);
					$uids=$del;
		    		
		    		delfiledir("../data/upload/tel/".$del);
				    $company=$this->obj->DB_select_once("company","`uid`='".$del."' and `logo`!=''","logo,firmpic");
				    unlink_pic(".".$company['logo']);
				    unlink_pic(".".$company['firmpic']);
		    	    $cert=$this->obj->DB_select_once("company_cert","`uid`='".$del."' and `type`='3'","check");
		    	    unlink_pic("../".$cert['check']);
		    	    $product=$this->obj->DB_select_all("company_product","`uid`='".$del."'","pic");
		    	    if(is_array($product))
		    	    {
		    	    	foreach($product as $v)
		    	    	{
		    	    		unlink_pic("../".$v['pic']);
		    	    	}
		    	    }
		    	    $show=$this->obj->DB_select_all("company_show","`uid`='".$del."'","picurl");
		    	    if(is_array($show))
		    	    {
		    	    	foreach($show as $v)
		    	    	{
		    	    		unlink_pic("../".$v['picurl']);
		    	    	}
		    	    }
			    	$uhotjob=$this->obj->DB_select_all("hotjob","`uid`='".$del."'","hot_pic");
		    	    if(is_array($uhotjob))
		    	    {
		    	    	foreach($uhotjob as $val)
		    	    	{
		    	    		unlink_pic("../".$val['hot_pic']);
		    	    	}
		    	    }
		    	    $banner=$this->obj->DB_select_once("banner","`uid`='".$del."'","pic");
					unlink_pic($banner['pic']);
					foreach($del_array as $value){
						$this->obj->DB_delete_all($value,"`uid`='".$del."'","");
					}
					$this->obj->DB_delete_all("email_msg","`uid`='".$del."' or `cuid`='".$del."'"," ");
					$this->obj->DB_delete_all("company_msg","`cuid`='".$del."'"," ");
					$this->obj->DB_delete_all("talent_pool","`cuid`='".$del."'"," ");
					$this->obj->DB_delete_all("user_entrust_record","`comid`='".$del."'"," ");
					$this->obj->DB_delete_all("ad_order","`comid`='".$del."'"," ");
					$this->obj->DB_delete_all("company_pay","`com_id`='".$del."'"," ");
		    	    $this->obj->DB_delete_all("atn","`uid`='".$del."' or `sc_uid`='".$del."'","");
		    	    $this->obj->DB_delete_all("look_resume","`com_id`='".$del."'","");
		    	    $this->obj->DB_delete_all("look_job","`com_id`='".$del."'","");
		    	    $this->obj->DB_delete_all("fav_job","`com_id`='".$del."'","");
		    	    $this->obj->DB_delete_all("userid_msg","`fid`='".$del."'","");
		    	    $this->obj->DB_delete_all("userid_job","`com_id`='".$del."'","");
		    	    $this->obj->DB_delete_all("message","`fa_uid`='".$del."'","");
		    	    $this->obj->DB_delete_all("msg","`job_uid`='".$del."'","");
		    	    $this->obj->DB_delete_all("blacklist","`c_uid`='".$del."'","");
		    	    $this->obj->DB_delete_all("rebates","`job_uid`='".$del."' or `uid` ='".$del."'"," ");
		    	    $this->obj->DB_delete_all("report","`p_uid`='".$del."' or `c_uid`='".$del."'","");
					$this->obj->DB_delete_all("part_apply","`comid` ='".$del."'","");
					$this->obj->DB_delete_all("part_collect","`comid` ='".$del."'","");
					$this->obj->DB_delete_all("down_resume","`comid` ='".$del."'","");
		    	}
	    		$this->layer_msg( "公司(ID:".$uids.")删除成功！",9,$layer_type,'index.php?c=admin_comlist');
	    	}else{
				$this->layer_msg( "请选择您要删除的公司！",8);
	    	}
	    }	
	}
}
?>