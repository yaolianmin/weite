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
class comcert_controller extends adminCommon{ 
	function index_action(){ 
		$where="`type`='3'";
		if($_GET['status']){
			if($_GET['status']=='3'){
				$where .= " and `status`='0'";
				$urlarr['status']='0';
			}else{
				$where .= " and `status`='".$_GET['status']."'";
				$urlarr['status']=$_GET['status'];
			}
        }
		$urlarr['c']="comcert";
		$urlarr['page']="{{page}}";
		$urlarr=Url($_GET['m'],$urlarr,'admin');
		$rows = $this->get_page("company_cert",$where." order by `id` desc",$urlarr,$this->config['sy_listnum'],"`uid`,`id`,`ctime`,`status`");
		if($rows && is_array($rows)){
			$uids=array();
			foreach($rows as $v){
				if(in_array($v['uid'],$uids)==false){
					$uids[]=$v['uid'];
				}
			}
			$com=$this->obj->DB_select_all("company","`uid` in(".pylode(',',$uids).")","`uid`,`name`");
			foreach($rows as $k => $v){
				foreach($com as $val){
					if($v['uid'] == $val['uid']){
						$rows[$k]['name'] = $val['name'];
					}
				}
			}
		}
		$this->yunset("rows",$rows); 
		$this->yunset("backurl", basename($_SERVER['HTTP_REFERER']));
		$this->yunset("headertitle","企业认证");
		$this->yuntpl(array('wapadmin/admin_comcert'));
	}
	function show_action(){
		$row=$this->obj->DB_select_once("company_cert","`id`='".intval($_GET['id'])."' and `type`='3'");
		if($row['uid']){
			$com=$this->obj->DB_select_once("company","`uid`='".$row['uid']."'","`uid`,`name`");
			$this->yunset("com",$com); 
		} 
		
		$lasturl=$_SERVER['HTTP_REFERER'];
		if(strpos($lasturl, 'a=show')===false){
		    if(strpos($lasturl, 'c=comcert')!==false){
		        $this->cookie->setcookie('lasturl',$lasturl,time()+300);
		        $_COOKIE['lasturl']=$lasturl;
		    }
		}
		$this->yunset('lasturl',$_COOKIE['lasturl']);
		 
		$this->yunset("row",$row); 
		$this->yunset("headertitle","企业认证设置");
		$this->yuntpl(array('wapadmin/admin_comcertshow'));
	} 
	function status_action(){ 
		$_POST['status']=intval($_POST['status']);
		$_POST['id']=intval($_POST['id']);
		$_POST['statusbody']=$this->stringfilter($_POST['statusbody']);
		if($_POST['id']){
			$row=$this->obj->DB_select_once("company_cert","`id`='".$_POST['id']."'");
			$uid=$row['uid'];
			if($_POST['status']!="1"){
				$yyzz_status=0;
			}else{
				$yyzz_status=1;
				
				$num = $this->obj->DB_select_num("company_pay","`com_id` = '".$uid."' and `pay_remark`='认证营业执照'");
				if($num < 1){
					$this->MODEL('integral')->get_integral_action($uid,"integral_comcert","认证营业执照");
				} 
			}
			$this->obj->DB_update_all("company","`yyzz_status`='".$yyzz_status."'","`uid` = '".$uid."'");
			$id=$this->obj->DB_update_all("company_cert","`status`='".$_POST['status']."',`statusbody`='".$_POST['statusbody']."'","`uid` = '".$uid."' and `type`='3'");
			$company=$this->obj->DB_select_once("company","`uid` = '".$uid."'","uid,name,linkmail");
			if($this->config['sy_email_set']=="1" && is_array($company)){ 
				if($this->config['sy_email_comcert']=='1' && $_POST['status']>0){
					if($_POST['statusbody']==''){
						if($_POST['status']=='1'){
							$_POST['statusbody'] = '营业执照审核通过！';
						}else{
							$_POST['statusbody'] = '营业执照审核未通过！';
						}
					}
          $notice = $this->MODEL('notice');
          $notice->sendEmailType(array("email"=>$company['linkmail'],"certinfo"=>$_POST['statusbody'],"comname"=>$company['name'],'uid'=>$company['uid'],'name'=>$company['name'],"type"=>"comcert"));
				}  
		   }
		   if ($_POST['lasturl']!=''){
		       $lasturl=$this->post_trim($_POST['lasturl']);
		   }else{
		       $lasturl=$_SERVER['HTTP_REFERER'];
		   }
		   if($id){
		       $this->layer_msg('企业认证审核(ID:'.$_POST['id'].')设置成功！',9,0,$lasturl);
		   }else{
		       $this->layer_msg('设置失败！',8);
		   }
		}
	}
	function del_action(){
		$linkid=intval($_GET['id']); 
		$company=$this->obj->DB_select_once("company_cert","`id`='".$linkid."' and `type`='3'","uid,check");
		$this->obj->DB_update_all("company","`yyzz_status`='0'","`uid`='".$company['uid']."'");
	    if(is_array($company)){
	     	unlink_pic("../".$company['check']);
	    }
		$delid=$this->obj->DB_delete_all("company_cert","`id`='".$linkid."'  and `type`='3'","");		
		$delid?$this->layer_msg('企业认证(UID:'.$linkid.')删除成功！',9,0,'index.php?c=comcert'):$this->layer_msg('删除失败！',8);
	}
}

?>