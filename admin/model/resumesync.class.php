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
class resumesync_controller extends adminCommon{

	function index_action(){
		 
		
		$resumeCount = $this->obj->DB_select_num("resume_expect","`defaults`='1'");
		$this->yunset("resCount",$resumeCount);
		$this->yunset("config",$this->config);
		$this->yuntpl(array('admin/admin_resumesync'));
	}
	 
	function sync_action(){	
		set_time_limit(0);
		
		if($_POST['count'] && $_POST['limit']){
			$page = ceil($_POST['count']/$_POST['limit']);
			if(!$_POST['page']){

				$_POST['page'] = 0;
			}
			 
			$pageSize = intval($_POST['page'])*intval($_POST['limit']);
			 
			$expectAll = $this->obj->DB_select_all("resume_expect","`defaults`='1'  LIMIT ".$pageSize.",".$_POST['limit']);
			 
			if(is_array($expectAll)){
				
				foreach($expectAll as $key=>$value){
					
					$uid[] = $value['uid'];
					$exceptList[$value['uid']] = $value;
					$expectid[] = $value['id'];
					
				}
				$uids = array_unique($uid);
				 
				$resumeList = $this->obj->DB_select_all("resume","`uid` IN (".@implode(',',$uids).")");
				
				 
				$workList = $this->getList('resume_work',$expectid);
				 
				$eduList = $this->getList('resume_edu',$expectid);
				  
				$trainList = $this->getList('resume_train',$expectid);
			 
				$skillList = $this->getList('resume_skill',$expectid);
				 
				$projectList = $this->getList('resume_project',$expectid);
				 
				$otherList = $this->getList('resume_other',$expectid);
			}
			
			if(is_array($resumeList)){
				include PLUS_PATH."/job.cache.php";
				include PLUS_PATH."/user.cache.php";
				include PLUS_PATH."/industry.cache.php";
				include PLUS_PATH."/city.cache.php";
				include(CONFIG_PATH."db.data.php");
		        unset($arr_data['sex'][3]);
		        $this->yunset("arr_data",$arr_data);
				foreach($resumeList as $key=>$value){ 
					$jsonvalue = array();
					$jsonvalue['uid'] = $value['uid']; 
					$jsonvalue['uname'] = $value['name']; 
					$jsonvalue['address'] = $value['address']; 
					$jsonvalue['birthday'] = $value['birthday']; 
					$jsonvalue['height'] = $value['height']; 
					$jsonvalue['weight'] = $value['weight']; 
					$jsonvalue['idcard'] = $value['idcard']; 
					$jsonvalue['nationality'] = $value['nationality']; 
					$jsonvalue['description'] = $value['description'];
					$jsonvalue['living'] = $value['living'];
					$jsonvalue['domicile'] = $value['domicile']; 
					$jsonvalue['telphone'] = $value['telphone']; 
					$jsonvalue['telhome'] = $value['telhome']; 
					$jsonvalue['email'] = $value['email']; 
					$jsonvalue['homepage'] = $value['homepage']; 
					$jsonvalue['sex'] = $arr_data['sex'][$value['sex']]; 
					$jsonvalue['marriage'] = $userclass_name[$value['marriage']]; 
					$jsonvalue['edu'] = $userclass_name[$value['edu']]; 
					$jsonvalue['exp'] = $userclass_name[$value['exp']]; 

					 
					/ 
					
						
					$exceptValue = $exceptList[$value['uid']];
					$resumevalue['province'] = $city_name[$exceptValue['provinceid']]; 
					$resumevalue['city'] = $city_name[$exceptValue['cityid']]; 
					$resumevalue['three_city'] = $city_name[$exceptValue['three_cityid']]; 

					$resumevalue['id'] = $exceptValue['id'];
					$resumevalue['name'] = $exceptValue['name']; 
					if($exceptValue['job_classid']){
						foreach(@explode(',',$exceptValue['job_classid']) as $k=>$v){
							$classid_name[] = $job_name[$v];
						}
						$resumevalue['job_classid'] = @implode(',',$classid_name);
					}
					
					$resumevalue['province'] = $city_name[$exceptValue['provinceid']]; 
					$resumevalue['city'] = $city_name[$exceptValue['provinceid']]; 
					$resumevalue['country'] = $city_name[$exceptValue['three_cityid']]; 
					$resumevalue['salary'] = $userclass_name[$exceptValue['salary']]; 
					$resumevalue['jobstatus'] = $userclass_name[$exceptValue['jobstatus']]; 
					$resumevalue['number'] = $userclass_name[$exceptValue['number']]; 
					$resumevalue['exp'] = $userclass_name[$exceptValue['exp']]; 
					$resumevalue['report'] = $userclass_name[$exceptValue['report']]; 
					$resumevalue['lastupdate'] = $exceptValue['lastupdate']; 
					$jsonvalue['expect'] = $resumevalue;
					

					 
					$jsonvalue['worklist'] = $workList[$exceptValue['id']];
					$jsonvalue['edulist'] = $eduList[$exceptValue['id']];
					$jsonvalue['projectlist'] = $projectList[$exceptValue['id']];
					$jsonvalue['otherlist'] = $otherList[$exceptValue['id']];
					$jsonvalue['trainlist'] = $trainList[$exceptValue['id']];

					$Arr[] = $jsonvalue;
				}
				if(!$_POST['synckey']){
					$_POST['synckey'] = time().rand(1000,9999);
				}
				 
				$Crmjson['syncjson'] = json_encode($Arr);
				$Crmjson['dxuser'] = $this->config['dx_user'];
				$Crmjson['dxkey'] = $this->config['dx_key'];	
				$Crmjson['synckey'] = $_POST['synckey'];
			 
				$url = 'http://sync.dx.com/resume/'; 
			
				
				
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
				curl_setopt($ch, CURLOPT_POST, 1); 
				curl_setopt($ch, CURLOPT_POSTFIELDS, $Crmjson); 
				$return = curl_exec($ch); 
				if(curl_errno($ch)){ 
					 
				}
				curl_close($ch);  
				
				$response = json_decode($return);
				
				
				if($response->state == '1')
				{
					if($response->uids)
					{
						$this->obj->DB_update_all("resume_expect","`sync`='1'","`id` IN (".$response->uids.")");
						$nowcount = count(explode(',',$response->uids));
					}
					
					if(($_POST['page']+1)>=$page){ 
						echo json_encode(array('state'=>'0'));
					}else{
						 
						echo json_encode(array('state'=>'1','readynum'=>$response->readynum,'page'=>($_POST['page']+1),'count'=>($_POST['page']+1)*$_POST['limit'],'synckey'=>$_POST['synckey']));
					}
					die;
				}else{
					
					echo json_encode(array('state'=>'2','error'=>$response->error));
				}
			}
		}else{
			echo json_encode(array('state'=>'2'));
		}
	}
	function getList($table,$expectid){
	
		$List = $this->obj->DB_select_all($table,"`eid` IN (".@implode(',',$expectid).")");
		if(is_array($List)){
			foreach($List as $key=>$value){
					if(is_array($value)){
						foreach($value as $k=>$v){
							$value[$k] = $v;
						}
					}
					$newkList[$value['eid']][] = $value;
			}
		}
		return $newkList;
	}
 
	function save_action(){
 		if($_POST["config"]){
		 unset($_POST["config"]);
		   foreach($_POST as $key=>$v){
		    	$config=$this->obj->DB_select_num("admin_config","`name`='$key'");
			   if($config==false){
				$this->obj->DB_insert_once("admin_config","`name`='$key',`config`='".$v."'");
			   }else{
				$this->obj->DB_update_all("admin_config","`config`='".$v."'","`name`='$key'");
			   }
		 	}
			$this->web_config(); 
			$this->ACT_layer_msg("网站配置设置成功！",9,1);
		 }
	}
}
?>