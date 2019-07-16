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
class index_controller extends lietou{
	function index_action(){
		$downnum=$this->obj->DB_select_num("down_resume","`comid`='".$this->uid."'");
		$this->yunset("downnum",$downnum);
		$ypnum=$this->obj->DB_select_num("userid_job","`com_id`='".$this->uid."'");
		$this->yunset("ypnum",$ypnum);
		$jobnum=$this->obj->DB_select_num("lt_job","`uid`='".$this->uid."' and `status`='1' and `zp_status`<>'1'");
		$this->yunset("jobnum",$jobnum);
		$yqresume=$this->obj->DB_select_all("userid_job","`com_id`='".$this->uid."' and `is_browse`=1 limit 10");
		if(is_array($yqresume)){
			include PLUS_PATH."/city.cache.php";
			include PLUS_PATH."/job.cache.php";
			include PLUS_PATH."/industry.cache.php";
			foreach ($yqresume as $v){
				$eids[]=$v['eid'];
			}
			$resumeexp=$this->obj->DB_select_all("resume_expect","`id` in(".pylode(',', $eids).")");
			foreach ($yqresume as $k=>$val){
				foreach ($resumeexp as $v){
					if($val['eid']=$v['id']){
						$yqresume[$k]['name']=$v['name'];
						$yqresume[$k]['hyname']=$industry_name[$v['hy']];
						$yqresume[$k]['lastupdate_n']=date('Y-m-d',$v['lastupdate']);
						$yqresume[$k]['cityname']=$city_name[$v['provinceid']].'-'.$city_name[$v['cityid']].'-'.$city_name[$v['three_cityid']];
						
						if($v['job_classid']!=""){
							$job=@explode(",",$v['job_classid']);
							$joblist=array();
							foreach($job as $val){
								$joblist[]=$job_name[$val];
							}
							$yqresume[$k]['jobclassname']=$joblist['0'];
						}
					}
				}
			}
		}
		$this->yunset("yqresume",$yqresume);
		if($_GET['type']=='recresume'){
			$recresume=$this->obj->DB_select_all("rebates","`job_uid`='".$this->uid."' and `status`=0 order by id desc limit 10");
			if(is_array($recresume)){
				include PLUS_PATH."/city.cache.php";
				include PLUS_PATH."/job.cache.php";
				include PLUS_PATH."/industry.cache.php";
				foreach($recresume as $k=>$v){
					$id[]=$v['id'];
				}
				$temporary=$this->obj->DB_select_all("temporary_resume","`rid` in (".pylode(",",$id).")");
				foreach($recresume as $k=>$val){
					$recresume[$k]['lastupdate_n']=date('Y-m-d',$v['datetime']);
					foreach($temporary as $v){
						if($v['rid']==$val['id']){
							$recresume[$k]['hyname']=$industry_name[$v['hy']];
							
							$recresume[$k]['cityname']=$city_name[$v['provinceid']].'-'.$city_name[$v['cityid']].'-'.$city_name[$v['three_cityid']];
							
							if($v['job_classid']!=""){
								$job=@explode(",",$v['job_classid']);
								$joblist=array();
								foreach($job as $val){
									$joblist[]=$job_name[$val];
								}
								$recresume[$k]['jobclassname']=$joblist['0'];
							}
						}
					}
				}
			}
			$this->yunset("recresume",$recresume);
		}
		
		$this->public_action();
		$this->seo("ltindex");
		$this->lietou_tpl('index');
	}
}
?>