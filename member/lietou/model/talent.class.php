<?php
/* *
* $Author ：PHPYUN开发团队
*
* 官网: http://www.phpyun.com
*
* 版权所有 2009-2018 宿迁鑫潮信息技术有限公司，并保留所有权利。
*
* 软件声明：未经授权前提下，不得用于商业运营、二次开发以及任何形式的再次发布。
*/
class talent_controller extends lietou{

	function index_action(){
		$this->public_action();
		$this->yunset("class",14);

		$urlarr=array("c"=>"talent","page"=>"{{page}}");
		$pageurl=Url('member',$urlarr);
		$rows=$this->get_page("lt_talent","`uid`='".$this->uid."' order by id desc",$pageurl,"10");
		if(is_array($rows)){
			foreach($rows as $key=>$value){
				$id[] = $value['id'];
			}
			
			$rewardList = $this->obj->DB_select_all('company_job_rewardlist',"`eid` IN (".pylode(',',$id).") AND `status` NOT IN ('18','19','20','21','23','26','27','28','29')");
			if(is_array($rewardList)){ 
				foreach($rewardList as $key=>$value){
					$rewardStatusId[] = $value['eid'];
				}
				foreach($rows as $key=>$value){
					if(in_array($value['id'],$rewardStatusId)){
						$rows[$key]['rewardstatus'] = '1';
					}
				}
			}
			
		}
		$this->yunset("rows",$rows);
		$this->yunset($this->MODEL('cache')->GetCache(array('city','user')));
		$this->lietou_tpl('talent');
	}

	function expect_action(){
		$this->public_action();
		$this->yunset("class",14);
		$talentM = $this->MODEL('talent');

		if($_GET['id']){
			$expectInfo = $talentM->getTalent($this->uid,$_GET['id']);
			
			$this->yunset("resume",$expectInfo);
			
		}
		$this->yunset($this->MODEL('cache')->GetCache(array('city','user','hy')));
		
		include(CONFIG_PATH."db.data.php");
		unset($arr_data['sex'][3]);
		$this->yunset("arr_data",$arr_data);

		$this->public_action();
		$this->lietou_tpl('talent_expect');
	}
	function saveexpect_action(){
		if($_POST){
			
			$talentM = $this->MODEL('talent');
			$return  = $talentM->addTalent($_POST);
			
			if($return['error']=='1'){

				$this->ACT_layer_msg($return['msg'],9,'index.php?c=talent');

			}else{
				$this->ACT_layer_msg($return['msg'],8);
			}
		}
	
	}
	function del_action(){
		if($_GET['id']){
			$del=(int)$_GET['id'];
			$this->obj->DB_delete_all("temporary_resume","`rid`='".$del."'","");
			$nid=$this->obj->DB_delete_all("rebates","`id`='".$del."' and `uid`='".$this->uid."'","");
			$this->obj->member_log("删除我推荐的人才");
			$nid?$this->layer_msg('删除成功！',9,0,"index.php?c=my_rebates"):$this->layer_msg('删除失败！',8,0,"index.php?c=my_rebates");
		}
	}

	function telstatus_action(){
		
		$talentM = $this->MODEL('talent');
		$return  = $talentM->telStatus($_POST['id'],$_POST['linktel'],$_POST['code']);
		
		if($return['error']=='1'){

			$this->obj->member_log("简历库授权认证");
		}
		echo json_encode($return);
	}
	function regmoblie_action(){
		if($_POST['telphone']){
			$Member=$this->MODEL("userinfo");
			$num = $Member->GetMemberNum(array("`uid`<>'".$this->uid."' and (moblie='".$_POST['telphone']."' or `username`='".$_POST['telphone']."')"));
			if ($num){
			    echo 1;die;
			}else{	
			    echo 0;die;
			}
		}
	}

}
?>