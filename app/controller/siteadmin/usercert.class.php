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
class usercert_controller extends siteadmin_controller{ 
	function set_search(){
		$search_list[]=array('param'=>'status','name'=>'审核状态','value'=>array('1'=>'已审核','2'=>'未审核',"3"=>"未通过"));
		$lo_time=array('1'=>'今天','3'=>'最近三天','7'=>'最近七天','15'=>'最近半月','30'=>'最近一个月');
		$search_list[]=array('param'=>'time','name'=>'发布时间','value'=>$lo_time);
		$this->yunset('search_list',$search_list);
	}
	function index_action(){
		$this->set_search();
		$where="`idcard_pic`<>''";
		if((int)$_GET['status']){
			if((int)$_GET['status']==1){
				$where.=" and `idcard_status`='1'";
				$urlarr['status']='1';
			}else if((int)$_GET['status']==2){
				$where.=" and `idcard_status`='0'";
				$urlarr['status']='2';
			}else if($_GET['status']==3){
				$where.=" and `idcard_status`='2'";
				$urlarr['status']='3';
			}
		}
		if(trim($_GET['keyword'])){
			$where.=" and `name` like '%".trim($_GET['keyword'])."%'";
			$urlarr['keyword']=$_GET['keyword'];
		}
		if((int)$_GET['time']){
			if((int)$_GET['time']=='1'){
				$where.=" and `cert_time` >= '".strtotime(date("Y-m-d 00:00:00"))."'";
			}else{
				$where.=" and `cert_time` >= '".strtotime('-'.(int)$_GET['time'].'day')."'";
			}
			$urlarr['time']=(int)$_GET['time'];
		}
		if($_GET['order']){
			$where.=" order by ".$_GET['t']." ".$_GET['order'];
			$urlarr['order']=$_GET['order'];
			$urlarr['t']=$_GET['t'];
		}else{
			$where.="order by `cert_time` desc";
		}
		$urlarr['page']='{{page}}';
		$pageurl=Url($_GET['m'],$urlarr,'admin');
		$rows=$this->get_page('resume',$where,$pageurl,$this->config['sy_listnum']);
		foreach($rows as $k=>$v){
		    $rows[$k]['pic']=str_replace("./data/upload/user","/data/upload/user",$v['idcard_pic']);
		}
		$this->yunset("rows",$rows);
		$this->siteadmin_tpl(array('admin_user_cert'));
	}
	function status_action(){
		extract($_POST);
        $UserinfoM=$this->MODEL('userinfo');
        $FriendM=$this->MODEL('friend');
		$id=$UserinfoM->UpdateUserinfo(array("values"=>array('idcard_status'=>$status,'statusbody'=>$statusbody),"usertype"=>"1"),array('uid'=>$uid));
		if($this->config['sy_email_usercert']=='1'){
			$userinfo = $UserinfoM->GetMemberOne(array('uid'=>$uid),array('field'=>"`email`,`name`,`uid`,`usertype`"));
			$data=$this->forsend($userinfo);
      $notice = $this->MODEL('notice');
      $notice->sendEmailType(array("email"=>$userinfo['email'],'uid'=>$data['uid'],'name'=>$data['name'],"certinfo"=>$statusbody,"username"=>$userinfo['username'],"type"=>"usercert"));
		}
		if($id){
			if($status==1){
				$com=$UserinfoM->GetPayinfoOne(array("com_id"=>$uid,"pay_remark"=>"上传身份验证"),array("field"=>"`com_id`"));
				if(empty($com)){
					$this->MODEL('integral')->get_integral_action($uid,"integral_identity","上传身份验证");
				}
			}
			$this->ACT_layer_msg( "个人认证审核(ID:".$uid.")设置成功！",9,$_SERVER['HTTP_REFERER'],2,1);
		}else{
			$this->ACT_layer_msg( "设置失败！",8,$_SERVER['HTTP_REFERER']);
		}
	}
	function sbody_action(){
        $Where=array('uid'=>$_POST['uid']);
        if(is_numeric($this->config['did'])){
            $Where['did'] = $this->config['did'];
        }
		$userinfo = $this->MODEL('userinfo')->GetUserinfoOne($Where,array('field'=>"`statusbody`",'usertype'=>1));
		echo $userinfo['statusbody'];die;
	}
	function idcard_status_action(){
		$M=$this->MODEL('userinfo');
		$FriendM=$this->MODEL('friend');
		$M->UpdateUserinfo(array("values"=>array('idcard_status'=>$_POST['idcard_status']),"usertype"=>"1"),array("`uid` IN (".$_POST['allid'].")"));
		if($this->config['sy_email_usercert']=='1'){
			$userinfo = $M->GetMemberList(array("`uid` IN (".$_POST['allid'].")"),array("field"=>"`email`,`name`,`uid`,`usertype`"));
			if(is_array($userinfo)){
				if($_POST['idcard_status']==1)
				{
					$uids=@explode(",",$_POST['allid']);
					$comlist=$M->GetCompanyPay(array("`com_id` in (".@implode(",",$_POST['allid']).")","pay_remark"=>"上传身份验证"),array("field"=>"`com_id`"));
					if(is_array($comlist))
					{
						foreach($comlist as $v){
							if(!in_array($v['com_id'],$uids))
							{
								$this->MODEL('integral')->get_integral_action($v['com_id'],"integral_identity","上传身份验证");
							}
						}
					}else{
						foreach($uids as $v){
							$this->MODEL('integral')->get_integral_action($v,"integral_identity","上传身份验证");
						}
					}
				}
				$notice = $this->MODEL('notice');
				foreach($userinfo as $v){
					$data=$this->forsend($v);
          $notice->sendEmailType(array("email"=>$v['email'],'uid'=>$data['uid'],'name'=>$data['name'],"username"=>$v['username'],"type"=>"usercert"));
				}
			}


		}
		$this->MODEL('log')->admin_log("个人认证(ID:".$_POST['allid'].")审核成功");
		echo $_POST['idcard_status'];die;
	}
	function del_action(){
		$this->check_token();
		if(is_array($_GET['del'])){
			$linkid=@implode(',',$_GET['del']);
			$layer_type=1;
		}else{
			$linkid=(int)$_GET['id'];
			$layer_type=0;
		}
		if($linkid==""){
			$this->layer_msg('请选择您要删除的数据！',8,1,$_SERVER['HTTP_REFERER']);
		}
		$M=$this->MODEL('userinfo');
	    $cert=$M->GetUserinfoList(array("`uid` in ($linkid)"),array('field'=>"`idcard_pic`"));
	    if(is_array($cert)){
	     	foreach($cert as $v){
	     		unlink_pic($v['idcard_pic']);
	     	}
	    }
		$del=$M->UpdateUserinfo(array("values"=>array('idcard_pic'=>'','idcard_status'=>'0','cert_time'=>'','statusbody'=>''),"usertype"=>1),array("`uid` in ($linkid)"));
		$del?$this->layer_msg('个人认证审核(ID:'.$linkid.')删除成功！',9,$layer_type,$_SERVER['HTTP_REFERER']):$this->layer_msg('删除失败！',8,$layer_type,$_SERVER['HTTP_REFERER']);
	}
}
?>