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
class comcert_controller extends siteadmin_controller{
	function set_search(){
		$search_list[]=array("param"=>"status","name"=>'审核状态',"value"=>array("3"=>"未审核","1"=>"已审核","2"=>"未通过"));
		$ad_time=array('1'=>'今天','3'=>'最近三天','7'=>'最近七天','15'=>'最近半月','30'=>'最近一个月');
		$search_list[]=array("param"=>"end","name"=>'申请时间',"value"=>$ad_time);
		$this->yunset("search_list",$search_list);
	}
	function index_action() {
		$this->set_search();
		$UserInfoM=$this->MODEL('userinfo');
		$where="`type`='3'";
        if($_GET['status']){
			if($_GET['status']=='3'){
				$where.=" and `status`='0'";
				$urlarr['status']='0';
			}else{
				$where.=" and `status`='".$_GET['status']."'";
				$urlarr['status']=$_GET['status'];
			}
        }
		if(trim($_GET['keyword'])){			
			$cwhere=array("`name` LIKE '%".trim($_GET['keyword'])."%'");
			$com=$UserInfoM->GetUserinfoList($cwhere,array("field"=>"`uid`,`name`","usertype"=>2));
			if(is_array($com)){
				foreach($com as $val){
					$comids[]=$val['uid'];
				}
			}
			$where.=" and `uid` in(".@implode(',',$comids).")";
			$urlarr['keyword']=$_GET['keyword'];
		} 
		
		if($_GET['end']){
			if($_GET['end']=='1'){
				$where.=" and `ctime` >= '".strtotime(date("Y-m-d 00:00:00"))."'";
			}else{
				$where.=" and `ctime` >= '".strtotime('-'.(int)$_GET['end'].'day')."'";
			}
			$urlarr['end']=$_GET['end'];
		}
		if($_GET['order'])
		{
			$where.=" order by `".$_GET['t']."` ".$_GET['order'];
			$urlarr['order']=$_GET['order'];
			$urlarr['t']=$_GET['t'];
		}else{
			$where.=" order by `id` desc";
		}
		$urlarr['page']="{{page}}";
		$urlarr=Url($_GET['m'],$urlarr,'admin');
		$M=$this->MODEL();
		$rows = $M->get_page("company_cert",$where,$urlarr,$this->config['sy_listnum']);
		if(is_array($rows['rows'])&&$rows['rows']){
			if($com==''||is_array($com)==false){
				$uids=array();
				foreach($rows as $k => $v){
					if(in_array($v['uid'],$uids)==false){
						$uids[]=$v['uid'];
					} 
				}
				$com=$UserInfoM->GetUserinfoList($cwhere,array("field"=>"`uid`,`name`","usertype"=>2));
			}
			foreach($rows['rows'] as $k=>$v){
				foreach($com as $val){
					if($v['uid']==$val['uid']){
						$rows['rows'][$k]['name']=$val['name'];
					}
				}
			}
		}
		$this->yunset($rows);
		$this->siteadmin_tpl(array('admin_com_cert'));
	}
	function sbody_action(){
		$CompanyM=$this->MODEL('company');
		$userinfo =$CompanyM->GetCertOne(array("type"=>3,"uid"=>$_POST['uid']),array("field"=>"statusbody"));
		echo $userinfo['statusbody'];die;
	}
	function status_action() {
		if($_POST['uid']) {
			$uid=$_POST['uid'];
			if($_POST['status']!="1"){
				$yyzz_status=0;
			}else{
				$yyzz_status=1;
			}
			$CompanyM=$this->MODEL('company');
			$UserInfoM=$this->MODEL('userinfo');
			$FriendM=$this->MODEL('friend');
			$UserInfoM->UpdateCompany(array("yyzz_status"=>$yyzz_status),array("`uid` in (".$uid.")"));
			$id=$UserInfoM->UpdateCompanyCert(array("status"=>$_POST['status'],"statusbody"=>$_POST['statusbody']),array("type"=>'3',"`uid` in (".$uid.")"));
			$company=$CompanyM->GetComList(array("`uid` in (".$uid.")"),array("field"=>"uid,name,linkmail"));
			if(is_array($company)){
				if($_POST['status']==1)
				{
					foreach($company as $v){
						$uids[]=$v['uid'];
					}
					$comlist=$UserInfoM->GetCompanyPay(array("`com_id` in (".@implode(",",$uids).")","pay_remark"=>"认证营业执照"),array("field"=>"`com_id`"));
					if(is_array($comlist))
					{
						foreach($comlist as $v){
							if(!in_array($v['com_id'],$uids))
							{
								$this->MODEL('integral')->get_integral_action($v['com_id'],"integral_comcert","认证营业执照");
							}
						}
					}else{
						foreach($uids as $v){
							$this->MODEL('integral')->get_integral_action($v,"integral_comcert","认证营业执照");
						}
					}
				}
				$notice = $this->MODEL('notice');
				if(is_array($company)){
				 foreach($company as $v){
				   if($this->config['sy_email_comcert']=='1' && $_POST['status']>0){
					   if($_POST['status']=='1'){
						 $certinfo = '营业执照审核通过！';
					   }else{
						 $certinfo = '营业执照审核未通过！';
				       }
               $notice->sendEmailType(array("email"=>$v['linkmail'],"certinfo"=>$certinfo,"comname"=>$v['name'],'uid'=>$v['uid'],'name'=>$v['name'],"type"=>"comcert"));
			       }
			    }
		     }
			}
			if($id){
				$this->ACT_layer_msg("企业认证审核(UID:".$uid.")设置成功！",9,$_SERVER['HTTP_REFERER'],2,1);
			}else{
				$this->ACT_layer_msg("设置失败！",8,$_SERVER['HTTP_REFERER']);
			}
		}else{
			$this->ACT_layer_msg("非法操作！",8,$_SERVER['HTTP_REFERER']);
		}
	}
	function del_action() {
		$UserInfoM=$this->MODEL('userinfo');
		$FriendM=$this->MODEL('friend');
		$CompanyM=$this->MODEL('company');
		if(is_array($_POST['del'])){
			$linkid=@implode(',',$_POST['del']);
			$type=1;
		}else{
			$this->check_token();
			$linkid=$_GET['uid'];
			$type=0;
		}
		if($linkid==""){
			$this->layer_msg('请选择您要删除的数据！',8,1,$_SERVER['HTTP_REFERER']);
		}
		$UserInfoM->UpdateCompany(array("yyzz_status"=>'0'),array("`uid` in (".$linkid.")"));
		$cert=$CompanyM->GetCertAll(array("type"=>'3',"`uid` in (".$linkid.")"),array("field"=>"check"));
	    if(is_array($cert)){
	     	foreach($cert as $v){
	     		unlink_pic("../".$v['check']);
	     	}
	    }
		$delid=$CompanyM->DeleteComCert(array("`uid` in (".$linkid.")","type"=>'3'));
		$delid?$this->layer_msg('企业认证(UID:'.$linkid.')删除成功！',9,$type,$_SERVER['HTTP_REFERER']):$this->layer_msg('删除失败！',8,$type,$_SERVER['HTTP_REFERER']);
	}
}

?>