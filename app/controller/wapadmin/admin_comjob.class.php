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
class admin_comjob_controller extends adminCommon{
	function index_action(){ 
		$where="1";
		if(trim($_GET['keyword'])){
		    $where .= " and `name` like '%".trim($_GET['keyword'])."%' ";
		    $urlarr['keyword']=$_GET['keyword'];
		}
		if($_GET['state']){
		    if($_GET['state']=="4"){
		        $where.= "  and `edate`>'".time()."' and `state`='0'";
		    }
		    $urlarr['state']=$_GET['state'];
		}
		$urlarr['c']=$_GET['c'];
		$urlarr['page']="{{page}}";
		$pageurl=Url($_GET['m'],$urlarr,'admin');
        $M=$this->MODEL();
		$PageInfo=$M->get_page("company_job",$where." order by state asc,lastupdate desc",$pageurl,$this->config['sy_listnum']);
        $this->yunset($PageInfo);
        $rows=$PageInfo['rows'];		
		include(CONFIG_PATH."db.data.php");
		$source=$arr_data['source'];
		$this->yunset('source',$source);
		$this->yunset("rows",$rows);
		$this->yunset('backurl', basename($_SERVER['HTTP_REFERER']));
		$this->yunset("headertitle","职位管理");
		$this->yuntpl(array('wapadmin/admin_comjob'));
	}
	function show_action(){
		if($_GET['id']){
			$row=$this->obj->DB_select_once("company_job","`id`='".$_GET['id']."'");
			$this->yunset("row",$row);
		}
		$this->yunset($this->MODEL('cache')->GetCache(array('city','job','hy')));
		
		$lasturl=$_SERVER['HTTP_REFERER'];
		if(strpos($lasturl, 'a=show')===false){
		    if(strpos($lasturl, 'c=admin_comjob')!==false){
		        $this->cookie->setcookie('lasturl',$lasturl,time()+300);
		        $_COOKIE['lasturl']=$lasturl;
		    }
		}
		$this->yunset('lasturl',$_COOKIE['lasturl']);
		
		$this->yunset("headertitle","职位详情");
		$this->yuntpl(array('wapadmin/admin_comjob_show'));
	}
	function edit_action(){
		include(CONFIG_PATH."db.data.php");		
		$this->yunset("arr_data",$arr_data);
		$this->yunset($this->MODEL('cache')->GetCache(array('city','hy','com','job')));
		if($_GET['id']){
			$row=$this->obj->DB_select_once("company_job","`id`='".$_GET['id']."'");
			$row['lang']=@explode(',',$row['lang']);
			
			if($row['three_cityid']){
				$row['circlecity']=$row['three_cityid'];
			}else if($row['cityid']){
				$row['circlecity']=$row['cityid'];
			}else if($row['provinceid']){
				$row['circlecity']=$row['provinceid'];
			}
			$this->yunset("row",$row);
			$this->yunset("lasturl",$_SERVER['HTTP_REFERER']);
		}
		if($_POST['update']){
			$_POST['lang']=@implode(',',$_POST['lang']);
			

			$_POST['edate']=strtotime($_POST['edate']);
			$_POST['description'] = str_replace("&amp;","&",html_entity_decode($_POST['description'],ENT_QUOTES));
			$_POST['lastupdate'] = time();
			$lasturl=$_POST['lasturl'];
			unset($_POST['update']);unset($_POST['content']);unset($_POST['lasturl']);
			if($_POST['edate']>time()){
				$_POST['state']="1";
			}else{
				$this->ACT_layer_msg("结束时间不能小于当前时间",8,$_SERVER['HTTP_REFERER'],2,1);
			}
			if($_POST['salary_type']){
				$_POST['minsalary']=$_POST['maxsalary']=0;
			}

			if($_POST['id']&&$_POST['uid']==''){
				$job=$this->obj->DB_select_once("company_job","`id`='".$_POST['id']."'","`uid`");
				$where['id']=$_POST['id'];
				unset($_POST['id']);
				$this->obj->update_once("company_job",$_POST,$where);
				$this->obj->DB_update_all("company","`lastupdate`='".time()."'","`uid`='".$job['uid']."'");
				
				
				$data['msg']="职位修改成功！";
				$data['url']='index.php?c=admin_comjob';
				$this->yunset("layer",$data);
			}else if($_POST['uid']){
				$company=$this->obj->DB_select_once("company","`uid`='".$_POST['uid']."'","name,welfare");
				$statis=$this->obj->DB_select_once("company_statis","`uid`='".$_POST['uid']."'","`vip_etime`,`job_num`,`integral`");

				if($statis['vip_etime']>time() || $statis['vip_etime']=="0")
				{
					if($statis['rating_type']==1)
					{
						if($statis['job_num']<1)
						{
							if($this->config['com_integral_online']=="1")
							{
								if($statis['integral']<$this->config['integral_job'])
								{
									$this->ACT_layer_msg($this->config['integral_pricename']."不够发布职位！",8,"index.php?c=admin_comjob");
								}
							}else{
								$this->ACT_layer_msg("该会员发布职位用完！",8,"index.php?c=admin_comjob");
							}
						}else{
							$this->obj->DB_update_all("company_statis","`job_num`=`job_num`-1","`uid`='".$_POST['uid']."'");
						}
					}
				}else{
					if($this->config['com_integral_online']=="1")
					{
						if($statis['integral']<$this->config['integral_job'])
						{
							$this->ACT_layer_msg($this->config['integral_pricename']."不够发布职位！",8,"index.php?c=admin_comjob");
						}
					}else{
						$this->ACT_layer_msg("该会员发布职位用完！",8,"index.php?c=admin_comjob");
					}
				}
				$_POST['com_name']=$company['name'];
				
				$_POST['sdate']=time();
				$id=$this->obj->insert_into("company_job",$_POST);
				if($id){
					$this->obj->DB_update_all("company","`jobtime`='".time()."'","`uid`='".$_POST['uid']."'");
					$this->ACT_layer_msg( "职位(ID:".$id.")发布成功！",9,'index.php?c=admin_comjob&a=show&id='.$_POST['id'],2,1);
				}else{
					$this->ACT_layer_msg( "职位发布失败！",8,'index.php?c=admin_comjob&a=show&id='.$_POST['id'],2,1);
				}
			}
		}
		$this->yunset("headertitle","职位操作");
		$this->yuntpl(array('wapadmin/admin_comjob_edit'));
	}
	function status_action(){
		if($_POST['id']){
			$_POST['statusbody']=$this->stringfilter($_POST['statusbody']);
			$nid=$this->obj->DB_update_all("company_job","`state`='".$_POST['status']."',`statusbody`='".$_POST['statusbody']."'","`id`='".$_POST['id']."'");
		    if ($_POST['lasturl']!=''){
		       $lasturl=$this->post_trim($_POST['lasturl']);
		    }else{
		       $lasturl=$_SERVER['HTTP_REFERER'];
		    }
			if($nid){
			    $data=$this->shjobmsg($_POST['id'],$_POST['status'],$_POST['statusbody']);
			    if($data!=""){
			        $notice = $this->MODEL('notice');
              $notice->sendEmailType($data);
              $notice->sendSMSType($data);
			    }
			    $this->layer_msg('职位审核(ID:'.$_POST['id'].')设置成功！',9,0,$lasturl);
			}else{
			    $this->layer_msg('设置失败！',8);
			}
		}
	}
	function shjobmsg($jobid,$yesid,$statusbody){
	    $data=array();
	    $comarr=$this->obj->DB_select_once("company_job","`id`='".$jobid."'","uid,name");
	    if($yesid==1){
	        $data['type']="zzshtg";
	        $this->send_dingyue($jobid,2);
	    }elseif($yesid==3){
	        $data['type']="zzshwtg";
	    }
	    if($data['type']!=""){
	        $uid=$this->obj->DB_select_alls("member","company","a.`uid`='".$comarr['uid']."' and a.`uid`=b.`uid`","a.email,a.moblie,a.uid,b.name");
	        $data['uid']=$uid[0]['uid'];
	        $data['name']=$uid[0]['name'];
	        $data['email']=$uid[0]['email'];
	        $data['moblie']=$uid[0]['moblie'];
	        $data['jobname']=$comarr['name'];
	        $data['date']=date("Y-m-d H:i:s");
	        $data['status_info']=$statusbody;
	        return $data;
	    }
	}
	function del_action(){
	    if($_GET['del']||$_GET['id']){
    		if(is_array($_GET['del'])){
    			$layer_type=1;
				$del=@implode(',',$_GET['del']);
	    	}else{
	    		$layer_type=0;
	    		$del=$_GET['id'];
	    	}
			$this->obj->DB_delete_all("user_entrust_record","`jobid` in (".$del.")","");
			$this->obj->DB_delete_all("company_job","`id` in (".$del.")","");
			$this->obj->DB_delete_all("company_job_link","`jobid` in (".$del.")","");
			$this->obj->DB_delete_all("userid_msg","`jobid` in (".$del.")","");
			$this->obj->DB_delete_all("userid_job","`job_id` in (".$del.")","");
			$this->obj->DB_delete_all("fav_job","`job_id` in (".$del.")","");
			$this->obj->DB_delete_all("look_job","`jobid` in (".$del.")","");
			$this->obj->DB_delete_all("report","`usertype`=1 and `type`=0 and `eid` in (".$del.")","");
			$this->obj->DB_delete_all("fav_job","`job_id` in (".$del.")","");
			$this->layer_msg("职位(ID:".$del.")删除成功！",9,0,'index.php?c=admin_comjob');
    	}else{
			$this->layer_msg("请选择您要删除的信息！",8);
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
	    $id?$this->layer_msg("职位置顶(ID:".$_POST['pid'].")设置成功！",2):$this->layer_msg("设置失败！",2);	
	}
	function save_action(){
		if($_POST['id']){
			$nid=$this->obj->DB_update_all("company_job",$_POST,"`id`='".$_POST['id']."'");
			$nid?$this->layer_msg("职位(ID:".$_POST['pid'].")修改成功！",2):$this->layer_msg("修改失败！",2);
		}
	}
}
?>