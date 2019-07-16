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
class job_controller extends lietou{
	function index_action(){
		$where = "`uid`='".$this->uid."'";
		 if($_GET['zp_status']==1){
			$where.="and `zp_status`='1'";
			$urlarr['zp_status']=$_GET['zp_status'];
		}else{
            if($_GET["s"]){
			$where .= " and `status`='".$_GET['s']."' and `zp_status`='0'";
			$urlarr['s']=$_GET['s'];
            }else{
                $where .= " and `status`='0' and `zp_status`='0'";
                $urlarr['s']=0;
            } 
        }
		$urlarr['c'] = "job";
		$urlarr['page'] = "{{page}}";
		$pageurl=Url('member',$urlarr);
		$joblist=$this->get_page("lt_job",$where." order by lastupdate desc",$pageurl,"10");
		$this->yunset("joblist",$joblist);
		$this->public_action();
		$this->yunset("s",$_GET['s']);
        $this->yunset("zp_status",$_GET['zp_status']);
		$this->lietou_tpl('job');
	}
	function del_action(){
		if($_GET['del']!=""||$_GET['id']){
			if(is_array($_GET['del'])){
				$del=pylode(",",$_GET['del']);
				$layer_type=1;
			}else{
				$del=(int)$_GET['id'];
				$layer_type=0;
			}
			$did=$this->obj->DB_delete_all("lt_job","`uid`='".$this->uid."' and `id` in (".$del.")","");
			  $this->obj->DB_delete_all("fav_job","`job_id` in (".$del.")","");
			  $this->obj->DB_delete_all("rebates","`job_id` in (".$del.")","");
			  $this->obj->DB_delete_all("userid_job","`job_id` in (".$del.")","");
			if($did)
			{
				$this->obj->member_log("删除猎头职位",1,3);
				$this->layer_msg('删除成功！',9,$layer_type,$_SERVER['HTTP_REFERER']);
			}else{
				$this->layer_msg('删除失败！',8,$layer_type,$_SERVER['HTTP_REFERER']);
			}
		}else{
			$this->layer_msg('请选择要删除的职位！',8,2,$_SERVER['HTTP_REFERER']);
		}
	}
	function jobset_action(){		
		if($_GET['id']){
			$where['id']=(int)$_GET['id'];
			$where['uid']=$this->uid;
			$did=$this->obj->update_once("lt_job",array("zp_status"=>(int)$_GET['zp']),$where);
			if($did){
				$this->obj->member_log("设置猎头职位招聘状态");
				$this->layer_msg('操作成功！',9,0,$_SERVER['HTTP_REFERER']);
			}else{
				$this->layer_msg('操作失败！',8,0,$_SERVER['HTTP_REFERER']);
			}
		}
		
	}

	function ltRefreshJob_action(){
		if($_POST){
 			$M=$this->MODEL('comtc');
 			$return = $M->ltRefreshJob($_POST);
 			if($return['status']==1){
				
				echo json_encode(array('error'=>1,'msg'=>$return['msg']));
			}else if($return['status']==2){
				
				echo json_encode(array('error'=>2,'msg'=>$return['msg']));
			}else{
				
				echo json_encode(array('error'=>3,'msg'=>$return['msg']));
			}
		}else{
			echo json_encode(array('error'=>3,'msg'=>'参数错误，请重试！'));
		}
	}

	
	function dkBuy_action(){
 		if($_POST){
   			$M=$this->MODEL('jfdk');
			if ($_POST['jobid']){
				$return = $M->buyLtJobRefresh($_POST);
			}elseif ($_POST['issuejob']){
				$return = $M->buyLtIssueJob($_POST);
			}  
			if($return['status']==1){
				
				echo json_encode(array('error'=>0,'msg'=>$return['msg']));
			}else{
				
				echo json_encode(array('error'=>1,'msg'=>$return['error'],'url'=>$return['url']));
			}
		}else{
			echo json_encode(array('error'=>1,'msg'=>'参数错误，请重试！'));
		}
	}


	function buyJob_action(){
 		if($_POST){
  			$M=$this->MODEL('compay');
			if ($_POST['jobid']){
				$return = $M->buyLtJobRefresh($_POST);
			}else if ($_POST['issuejob']) {
				$return = $M->buyLtIssueJob($_POST);
			}	

			if($return['order']['order_id'] && $return['order']['id']){
				
				echo json_encode(array('error'=>0,'orderid'=>$return['order']['order_id'],'id'=>$return['order']['id']));
			}else{
				
				echo json_encode(array('error'=>1,'msg'=>$return['error']));
			}
		}else{
			echo json_encode(array('error'=>1,'msg'=>'参数错误，请重试！'));
		}
	}
}
?>