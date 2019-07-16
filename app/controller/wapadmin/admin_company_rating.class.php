<?php

class admin_company_rating_controller extends adminCommon{
	function index_action(){
		$where="1";
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
						$rows[$k]['status']=$val['status'];
					}
				}
				foreach($list as $val){
					if($v['uid']==$val['uid']){
						$rows[$k]['rating']=$val['rating'];
						$rows[$k]['rating_name']=$val['rating_name'];
						$rows[$k]['vip_etime']=$val['vip_etime'];
						$rows[$k]['integral']=$val['integral'];
					}
				}
			}
		} 
        $this->yunset("rows",$rows);
		
		
		$this->yunset("headertitle","设置会员等级");
		
		
		
		$this->yunset('backurl','index.php');
		$this->yuntpl(array('wapadmin/admin_company_rating'));
	}
	
	
	function ratingshow_action(){
		 if((int)$_GET['id']){
			$com_info = $this->obj->DB_select_once("member","`uid`='".$_GET['id']."'");
			$row = $this->obj->DB_select_once("company","`uid`='".$_GET['id']."'");
			$statis = $this->obj->DB_select_once("company_statis","`uid`='".$_GET['id']."'");
			$rating_list = $this->obj->DB_select_all("company_rating","`category`=1");
			$statis['vip_etimes']=ceil(($statis['vip_etime']-mktime())/86400);
			$this->yunset("statis",$statis);
			$this->yunset("row",$row);
			$this->yunset("rating_list",$rating_list);
			$this->yunset("rating",$_GET['rating']);
			
			$this->yunset("com_info",$com_info);
			$this->yunset($this->MODEL('cache')->GetCache(array('city','hy','com')));

		}
		 
		if($_POST['com_update']){
		    $uid = intval($_POST['uid']);
		    if((int)$_POST['addday']>0){
		        if((int)$_POST['oldetime']>0){
		            $_POST['vip_etime'] = intval($_POST['oldetime'])+intval($_POST['addday'])*86400;
		        }else{
		            $_POST['vip_etime'] = time()+intval($_POST['addday'])*86400;
		        }
		    }else{
		        $_POST['vip_etime'] = intval($_POST['oldetime']);
		    }
		    $value="`rating`='".$_POST['ratingid']."',";
    		$value.="`rating_name`='".$_POST['name']."',";
    		$value.="`integral`='".$_POST['integral']."',";
    		$value.="`vip_etime`='".$_POST['vip_etime']."',";
    		$value.="`job_num`='".$_POST['job_num']."',";
    		$value.="`down_resume`='".$_POST['down_resume']."',";
    		$value.="`invite_resume`='".$_POST['invite_resume']."',";
    		$value.="`editjob_num`='".$_POST['editjob_num']."',";
    		$value.="`breakjob_num`='".$_POST['breakjob_num']."',";
    		$value.="`part_num`='".$_POST['part_num']."',";
    		$value.="`editpart_num`='".$_POST['editpart_num']."',";
    		$value.="`breakpart_num`='".$_POST['breakpart_num']."',";
    		$value.="`zph_num`='".$_POST['zph_num']."',";
    		$value.="`lt_job_num`='".$_POST['lt_job_num']."',";
    		$value.="`lt_down_resume`='".$_POST['lt_resume']."',";
    		$value.="`lt_editjob_num`='".$_POST['lt_editjob_num']."',";
    		$value.="`lt_breakjob_num`='".$_POST['lt_breakjob_num']."',";
		    $value.="`rating_type`='".$_POST['rating_type']."'";
			if($statis['rating'] != $_POST['ratingid']){
				$value.=",`vip_stime`='".time()."'";
				$this->obj->DB_update_all("company_job","`rating`='".$_POST['ratingid']."'","`uid`='".$uid."'");
			}
		    $id = $this->obj->DB_update_all("company_statis",$value,"`uid`='".$uid."'");
		    if($id){
		        $data['msg']="修改成功！";
		        
		    }else{
		        $data['msg']="修改失败！";
		    }
		    $data['url']='index.php?c=admin_company_rating';
		    $this->yunset("layer",$data);
		}
		$this->yunset('backurl','index.php?c=admin_company_rating');
		$this->yunset("headertitle","设置等级");
        $this->yuntpl(array('wapadmin/admin_rating_show'));
	}
	
	function rating_action(){
		include(CONFIG_PATH."db.data.php");	
		$this->yunset("arr_data",$arr_data);
		if($_GET['id']){
			$row=$this->obj->DB_select_once("company_rating","`id`='".$_GET['id']."'");
			$this->yunset("row",$row);
		}    
		$coupon=$this->obj->DB_select_all("coupon");
		$this->yunset("coupon",$coupon);
		$this->yunset('backurl','index.php');
		$this->yuntpl(array('wapadmin/admin_comclass_add'));
	}
	function saveclass_action(){
		if($_POST['useradd']){
			$id=$_POST['id'];
			unset($_POST['useradd']);
			unset($_POST['id']);
			if(is_uploaded_file($_FILES['com_pic']['tmp_name'])){
				$UploadM=$this->MODEL('upload');
				$upload=$UploadM->Upload_pic("../data/upload/compic/");
				$pictures=$upload->picture($_FILES['com_pic']);
				$pic = str_replace("../data/upload","/data/upload",$pictures);
			}
			if($_POST['youhui']){
				if($_POST['time_start']==''||$_POST['time_end']==''){
					$this->ACT_layer_msg("请选择优惠开始、结束日期",8,$_SERVER['HTTP_REFERER']);
				}
				if($_POST['yh_price']==''||$_POST['yh_price']>$_POST['service_price']){
					$this->ACT_layer_msg("优惠价格不得大于初始售价",8,$_SERVER['HTTP_REFERER']);
				}

				$_POST['time_start']=strtotime($_POST['time_start']." 00:00:00");
				$_POST['time_end']=strtotime($_POST['time_end']." 23:59:59");
			}else{
				unset($_POST['yh_price']);
				unset($_POST['time_start']);unset($_POST['time_end']);
			}
			foreach($_POST as $key=>$value){
				if($value==''){
					$_POST[$key] = 0;
				}
			}
			if(!$id){
				$_POST['com_pic']=$pic;
				$nid=$this->obj->insert_into("company_rating",$_POST);
				$name="企业会员等级（ID：".$nid."）添加";
			}else{
				if($pic!=""){$_POST['com_pic']=$pic;};
				$where['id']=$id;
				$nid=$this->obj->update_once("company_rating",$_POST,$where);
				$name="企业会员等级（ID：".$id."）更新";
			}
		}
		$this->cache_rating();
		$nid?$this->ACT_layer_msg($name."成功！",9,"index.php?m=admin_company_rating",2,1):$this->ACT_layer_msg($name."失败！",8,"index.php?m=admin_company_rating");
	}
	function del_action(){
		if($_POST['del']){
			$layer_type='1';
			$id=pylode(',',$_POST['del']);
		}else if($_GET['id']){
			$this->check_token();
			$id=$_GET['id'];
			$layer_type='0';
		}
		$nid=$this->obj->DB_delete_all("company_rating","`id` in(".$id.")","");
		$this->cache_rating();
		$nid?$this->layer_msg('企业会员等级（ID：(ID:'.$id.')成功！',9,$layer_type,$_SERVER['HTTP_REFERER']):$this->layer_msg('删除失败！',8,$layer_type,$_SERVER['HTTP_REFERER']);
	}
	function delpic_action(){
		if($_GET['id']){
			$this->check_token();
			$row=$this->obj->DB_select_once("company_rating","`id`='".$_GET['id']."'","`com_pic`");
			@unlink("..".$row['com_pic']);
			$this->obj->DB_update_all("company_rating","`com_pic`=''","`id`='".$_GET['id']."'");
			$this->cache_rating();
			$this->layer_msg('企业会员等级（ID：(ID:'.$_GET['id'].')图标删除成功！',9,0,$_SERVER['HTTP_REFERER']);
		}
	}
	function cache_rating(){
		include(LIB_PATH."cache.class.php");
		$cacheclass= new cache(PLUS_PATH,$this->obj);
		$makecache=$cacheclass->comrating_cache("comrating.cache.php");
	}
	function getrating_action(){
		if($_POST['id']){
			$rating	= $this->obj->DB_select_once("company_rating","`id`='".intval($_POST['id'])."' and `category`='1'");
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
}

?>