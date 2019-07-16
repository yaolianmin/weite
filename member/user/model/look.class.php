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
class look_controller extends user{
	function index_action(){
		$urlarr=array("c"=>"look","page"=>"{{page}}");
		$pageurl=Url('member',$urlarr);
		$look=$this->get_page("look_resume","`uid`='".$this->uid."' and `status`='0' order by datetime desc",$pageurl,"15");
		include PLUS_PATH."/com.cache.php";
		include PLUS_PATH."/lt.cache.php";
		if(is_array($look)){
			foreach($look as $k=>$v){
				$com_uid[] = $v['com_id'];
				$res_uid[] = $v['resume_id'];
			}
			$type=$this->obj->DB_select_all("member","`uid`IN  (".pylode(",",$com_uid).")","uid,usertype");
			foreach($type as  $v){
				if($v['usertype']==2){
					$com_uid[]=$v['uid'];
				}elseif($v['usertype']==3){
					$lt_uid[]=$v['uid'];
				}
			}
			$resume=$this->obj->DB_select_all("resume_expect","`id` IN (".pylode(",",$res_uid).")","id,name");
			$com=$this->obj->DB_select_all("company","`uid` IN (".pylode(",",$com_uid).")","uid,name,pr,mun,sdate");
			$lt=$this->obj->DB_select_all("lt_info","`uid` IN (".pylode(",",$lt_uid).")","uid,com_name,realname,title,exp,rz_time");
			$comjob=$this->obj->DB_select_all("company_job","`uid` IN (".pylode(",",$com_uid).") and `edate`>'".time()."' and `state`=1 and `r_status`<>2 and `status`<>1","uid,id,name");
			foreach ($comjob as $v){
				$url=Url('job',array("c"=>"comapply","id"=>$v['id']));
				$comjobname[$v['uid']][]="<a href='".$url."' target='_bank'>".$v['name']."</a>";
			}
			foreach($look as $k=>$v){
				foreach($resume as $key=>$val){
					if($v['resume_id']==$val['id']){
						$look[$k]['resume']=$val['name'];
					}
				}
				foreach($com as $value){
					if($v['com_id']==$value['uid']){
						$look[$k]['com']=$value['name'];
						$look[$k]['pr']=$comclass_name[$value['pr']];
						$look[$k]['mun']=$comclass_name[$value['mun']];
						$sdate=split('-',$value['sdate']);
						$look[$k]['sdate']=$sdate[0];
						$look[$k]['type']=2;
					}
				}
				foreach($lt as $value){
					if($v['com_id']==$value['uid']){
						if($value['com_name']==''){
							$look[$k]['com']=$value['realname'];
						}else{
							$look[$k]['com']=$value['com_name'];
						}
						$look[$k]['title']=$ltclass_name[$value['title']];
						$look[$k]['exp']=$ltclass_name[$value['exp']];
						$look[$k]['time']=date('Y',$value['rz_time']);
						$look[$k]['type']=3;
					}
				}
				foreach($comjobname as $key=>$va){
					if($v['com_id']==$key){
						$i=0;
						foreach ($va as $value){
							if ($i<2){
								$joblist[$k][]=$value;
							}
							$i++;
						}
						$look[$k]['jobname']=@implode(",", $joblist[$k]);
					}
				}
			}
		}
		$this->yunset("js_def",2);
		$this->yunset("look",$look);
		$this->public_action();
		$this->user_tpl('look');
	}
	function del_action(){
		if($_GET['id']||$_GET['del']){
			if(is_array($_GET['del'])){
				$del=pylode(",",$_GET['del']);
				$layer_type=1;
			}else{
				$del=(int)$_GET['id'];
				$layer_type=0;
			}
			$this->obj->DB_update_all("look_resume","`status`='1'","`id` in (".$del.") and `uid`='".$this->uid."'");
			$this->obj->member_log("删除简历浏览记录（ID:".$del."）");
 			$this->layer_msg('删除成功！',9,$layer_type,"index.php?c=look");
		}
	}
}
?>