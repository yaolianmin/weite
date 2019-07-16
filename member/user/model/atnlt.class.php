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
class atnlt_controller extends user{
	function index_action(){
		$this->public_action();
		$urlarr=array("c"=>"atnlt","page"=>"{{page}}");
		$pageurl=Url('member',$urlarr);
		$rows=$this->get_page("atn","`uid`='".$this->uid."' and `sc_usertype`='3' order by `id` desc",$pageurl,"10");
		if($rows&&is_array($rows)){
			foreach($rows as $val){
				$uids[]=$val['sc_uid'];
			}
			$job=$this->obj->DB_select_all("lt_job","`uid` in(".pylode(',',$uids).") and `status`='1' and `zp_status`<>'1'","`uid`,`job_name`,`id`");
			$company=$this->obj->DB_select_all("lt_info","`uid` in(".pylode(',',$uids).")","`uid`,`realname`");
			foreach($job as $v){
				$url=Url('lietou',array("c"=>"jobshow","id"=>$v['id']));
				$jobname[$v['uid']][]="<a href='".$url."' target='_bank'>".$v['job_name']."</a>";
			}
			foreach($rows as $key=>$val){
				foreach($company as $v){
					if($val['sc_uid']==$v['uid']){
						$rows[$key]['com_name']=$v['realname'];
					}
				}
				foreach($jobname as $k=>$v){
					if($val['sc_uid']==$k){
						$rows[$key]['jobnum']=count($v);
						$i=0;
						foreach($v as $value){
							if($i<2){
								$joblist[$key][]=$value;
							}
							$i++;
						}
						$rows[$key]['jobname']=@implode(",",$joblist[$key]);
					}
				}
			}
		}
		$this->yunset("rows", $rows);
 		$this->user_tpl('atnlt');
	}
	function del_action(){
		if($_GET['id']){
			$this->obj->DB_delete_all("atn","`id`='".intval($_GET['id'])."' AND `uid`='".$this->uid."'");
			$this->obj->DB_update_all("lt_info","`ant_num`=`ant_num`-1","`uid`='".intval($_GET['uid'])."'");
			$this->obj->member_log("取消关注猎头");
 			$this->layer_msg('取消关注猎头成功！',9,0,"index.php?c=atnlt");
		}
	}
}
?>