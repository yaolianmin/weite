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
class excel_controller extends adminCommon{
	function index_action(){
		$this->yuntpl(array('admin/admin_excel'));
	}
	function resume_action(){
		set_time_limit(0);
		include LIB_PATH."/reader.php";
		$data = new Spreadsheet_Excel_Reader();
		$data->setOutputEncoding('utf-8');

		if($_FILES[excel][name]!=""){
			$time = time();
			$excel = $time.".xls";
			move_uploaded_file($_FILES[excel][tmp_name],DATA_PATH."upload/excel/".$excel);
		}else{
			$this->ACT_layer_msg("无文件上传！",8,$_SERVER['HTTP_REFERER'],2,1);
		}

		$data->read(DATA_PATH.'upload/excel/'.$excel);
		if($data->sheets[0]['numRows']<1){
			$this->ACT_layer_msg("数据读取失败，请检查excel格式！",8,$_SERVER['HTTP_REFERER'],2,1);
		}
		$user = array(); 
		$cells=count($data->sheets[0]['cells']);
		$cellsnum=count($data->sheets[0]['cells'][1]); 

		for ($i = 2; $i <= $cells; $i++){
			$user[$i]['username'] 	= trim($data->sheets[0]['cells'][$i][1]);
			$user[$i]['name'] 		= trim($data->sheets[0]['cells'][$i][2]); 
			if($user[$i]['name']){ 
				for($j=3;$j<=$cellsnum;$j++){
					$value=$data->sheets[0]['cells'][$i][$j];
					switch($data->sheets[0]['cells'][1][$j]){ 
						case   '性别':$user[$i]['sex_n']=$value;break;   
						case   '年龄':$user[$i]['age_n']=$value;break;   
						case   '婚姻':$user[$i]['marriage_n'] = $value;break;   
						case   '籍贯':$user[$i]['jiguan_n']= $value;break;   
						case   '联系电话':$user[$i]['telphone']= $value;break; 
						case   '固定电话':$user[$i]['homephone']= $value;break; 
						case   '邮箱':$user[$i]['email']= $value;break; 
						case   '学历':$user[$i]['edu_n']= $value;break; 
						case   '经验':$user[$i]['exp_n']= $value;break; 
						case   '现居住地':$user[$i]['xcity']= $value;break; 
						case   '详细地址':$user[$i]['address']= $value;break; 
						case   '期望工作地址':$user[$i]['jobaddress']= $value;break; 
						case   '期望工作岗位':$user[$i]['job']= $value;break; 
						case   '期望薪资':$user[$i]['salary_n']= $value;break; 
						case   '教育经历':$user[$i]['eduexcel']= $value;break; 
						case   '工作经历':$user[$i]['work_n']= $value;break; 
						case   '专业擅长':$user[$i]['other_n']= $value;break; 
						case   '个人介绍':$user[$i]['description']=$value;break; 
					}  
				} 
			}			
		}
		if(is_array($user)){
			$numres = 0; $numuser=0;$numwork=0;$numedu=0;$los=0;
			include PLUS_PATH."/job.cache.php";
			include PLUS_PATH."/user.cache.php";
			include PLUS_PATH."/city.cache.php";
			foreach($user as $key=>$value){
				if($value[name]!=""){
					$salt = substr(uniqid(rand()), -6);
					$pass =array("a","b","c","d","e","f","g","h","i","g","k","l","m","n","o","p","q","r","s","t","u","v","w","x","w","z","1","2","3","4","5","6","7","8","9","0");

					$password='';
					for($i=0;$i<6;$i++){
						$k = rand(0,36);
						$password.=$pass[$k];
					}
					$npass = md5(md5($password).$salt);
					$time = time();
					if($value['username']==''){
						$value['username'] = 'py'.date('mdHis').rand(100,999);
					}
					if($value['email']!=''){
						$emailuser[] = array('email'=>$value['email'],'username'=>$value[username],'password'=>$password,'name'=>$value['name']);
					}
					$mvalue = "`username`='$value[username]',`password`='$npass',`email`='$value[email]',`usertype`='1',`address`='$value[address]',`status`='1',`salt`='$salt',`moblie`='$value[telphone]',`reg_date`='$time',`passtext`='$password',`source`='7'";
					$uid = $this->obj->DB_insert_once("member",$mvalue);
					$this->obj->DB_insert_once("member_statis","`uid`='$uid'");
					$this->obj->DB_insert_once("resume","`uid`='$uid'");
					if($uid){
						$numuser++;
						$sqlval="`uid`='$uid'";
						$sqlval .=",`name`='$value[job]'";

						$job_row=$this->get_job_class($value['job']);


						if($job_row){
							$job_arr=array();
							foreach($job_row as $vs){
								$job_arr[] = $vs;
							}
							$sqlval.=",`job_classid`='".implode(',',$job_arr)."'";
						}



						$pcity = $value['jiguan_n'];
						$xcity = $value['xcity'];
						$provinceid = $cityid = $three_cityid=0;
						$city_row=$this->get_city($value['jobaddress']);

						$i=1;
						foreach($city_row as $v){
							if($i==1){
								$provinceid=$v;
							}
							if($i==2){
								$cityid=$v;
							}
							if($i==3){
								$three_cityid=$v;
							}
							$i++;
						}
						$sqlval.=",`provinceid`='$provinceid'";
						$sqlval.=",`cityid`='$cityid'";
						$sqlval.=",`three_cityid`='$three_cityid',`lastupdate`='".mktime()."'";
						
						$salaryN = explode('-',$value['salary_n']); 
						$sqlval.=",`minsalary`='".$salaryN[0]."',`maxsalary`='".$salaryN[1]."'";

						$sqlval.=",`type`='58',`report`='46',`source`='7'";
						$sqlval.=",`jobstatus`='".$userdata['user_jobstatus'][0]."'";
						$sqlval.=",`r_status`='1'";
						$resumeid = $this->obj->DB_insert_once("resume_expect",$sqlval);
						$numres++;
						if($uid){
							$ressql ="`def_job`='$resumeid'";
							$ressql.=",`name`='$value[name]'";
							$resume_expect_data = "`uname`='$value[name]'";
							if($value['sex_n']=="女"){
								$ressql.=",`sex`='2'";
								$resume_expect_data .= ",`sex`='2'";
							}else{
								$ressql.=",`sex`='1'";
								$resume_expect_data .= ",`sex`='1'";
							}
							if((int)$value['age_n']>0){
								$birthday = date("Y")-$value['age_n']."-".date("m-d");
								$ressql.=",`birthday`='$birthday'";
								$resume_expect_data .= ",`birthday`='$birthday'";
							}
							
							if($value['marriage_n']!=""){
								$marriage='';
								foreach($userdata['user_marriage'] as $k=>$v){
									if(strpos($userclass_name[$v],$value['marriage_n'])!==false){
										$marriage = $v;
									}
								}
								$ressql.=",`marriage`='$marriage'";
							}
							
							$ressql.=",`telphone`='$value[telphone]',`telhome`='$value[homephone]'";
							$ressql.=",`living` = '$xcity'";
							$ressql.=",`domicile` = '$pcity'";
							$ressql.=",`email`='$value[email]'";
						

							if($value['edu_n']!=""){
								$edu='';
								foreach($userdata['user_edu'] as $k=>$v){
									if(strpos($userclass_name[$v],$value['edu_n'])!==false){
										$edu = $v;
									}
								}
								$ressql.=",`edu`='$edu'";
								$resume_expect_data .= ",`edu`='$edu'";
							}
							
							
							if($value['exp_n']!=""){
								$exp='';
								foreach($userdata['user_word'] as $k=>$v){
									if(strpos($userclass_name[$v],$value['exp_n'])!==false){
										$exp = $v;
									}
								}
								$ressql.=",`exp`='$exp'";
								$resume_expect_data .= ",`exp`='$exp'";
							}	
							
							
							$ressql.=",`address`='$value[address]',`description`='$value[description]'";

							$this->obj->DB_update_all("resume",$ressql,"`uid`='$uid'");

							$resume_expect_data .= ",`defaults`='1'";
							$resume_expect_data .= ",`ctime`='".time()."'";
							$this->obj->DB_update_all("resume_expect",$resume_expect_data,"`uid`='$uid'");
							if($resumeid  && $value['work_n']!=""){
								$work_arr = @explode("||",$value['work_n']);

								if(is_array($work_arr)){
									foreach($work_arr as $k=>$v){
										$sonv = @explode("***",$v);
										$workson_arra = @explode("-",$sonv[0]);
										$sdate = $this->uparr($workson_arra[0]);
										$edate = $this->uparr($workson_arra[1]);
										$content = $workson_arra[2];
										if($sonv[1]!="" || $sonv[2]!="" || $sonv[3]!=""){
										$this->obj->DB_insert_once("resume_work","`uid`='$uid',`eid`='$resumeid',`sdate`='$sdate',`edate`='$edate',`name`='$sonv[1]',`title`='$sonv[3]',`content`='$sonv[2]'");
										$numwork++;
										}
									}
								}
							}
							if($resumeid  && $value['eduexcel']!=""){
								$edu_arr = @explode("||",$value['eduexcel']);
								if(is_array($edu_arr)){
									foreach($edu_arr as $k=>$v){
										$sonv = @explode("***",$v);
										$eduson_arra = @explode("-",$sonv[0]);
										$sdate = $this->uparr($eduson_arra[0]);
										$edate = $this->uparr($eduson_arra[1]);
										$content = $eduson_arra[2];
										if($sonv[1]!="" || $sonv[2]!=""){
											$this->obj->DB_insert_once("resume_edu","`uid`='$uid',`eid`='$resumeid',`sdate`='$sdate',`edate`='$edate',`name`='$sonv[1]',`specialty`='$sonv[2]'");
											$numedu++;
										}
									}
								}
							}
							if($resumeid  && $value['other_n']!=""){
								$this->obj->DB_insert_once("resume_other","`uid`='$uid',`eid`='$resumeid',`content`='$value[other_n]'");
							}

						}
						$user_data['uid'] = $uid;
						$user_data['eid'] = $resumeid;
						$user_data['expect'] ='1';
						$user_data['work'] =$numwork;
						$user_data['edu'] =$numedu;
						$this->obj->insert_into("user_resume",$user_data);
						$resume_row=$this->obj->DB_select_once("user_resume","`eid`='".$resumeid."'");
						$this->MODEL('resume')->complete($resume_row);
					}

				}else{

				}
			}

			$msg = "本次新增个人会员：".$numuser."人，新增简历：".$numres."份;新增工作经历:".$numwork."份;新增教育经历:".$numedu."份.";
			$this->ACT_layer_msg($msg,9,$_SERVER['HTTP_REFERER'],2,1);


		}else{
			$this->ACT_layer_msg("没有找到合适的数据，请查看格式是否规范！",8,$_SERVER['HTTP_REFERER'],2,1);
		}
	}

	function comexcel_action()
	{
		include LIB_PATH."/reader.php";
		$data = new Spreadsheet_Excel_Reader();
		$data->setOutputEncoding('utf-8');

		if($_FILES[excel][name]!="")
		{
			$time = time();
			$excel = $time.".xls";
			move_uploaded_file($_FILES[excel][tmp_name],DATA_PATH."upload/excel/".$excel);
		}

		$data->read(DATA_PATH."upload/excel/".$excel);
		$user = array();
		if($data->sheets[0]['numRows']<1){
			$this->ACT_layer_msg("数据读取失败，请检查excel格式！",8,$_SERVER['HTTP_REFERER'],2,1);
		}
		$cells=count($data->sheets[0]['cells']);
		$cellsnum=count($data->sheets[0]['cells'][1]); 
		for ($i = 2; $i <= $cells; $i++){
			$user[$i]['comname'] 	= trim($data->sheets[0]['cells'][$i][1]); 
			//if($user[$i]['comname']){ 
				for($j=2;$j<=$cellsnum;$j++){
					$value=$data->sheets[0]['cells'][$i][$j];
					switch($data->sheets[0]['cells'][1][$j]){ 
						case   '联系人':$user[$i]['linkman']=$value;break;   
						case   '联系人职位':$user[$i]['linkjob']=$value;break;   
						case   '联系电话':$user[$i]['linktel'] = $value;break;   
						case   '籍贯':$user[$i]['jiguan_n']= $value;break;   
						case   '联系电话':$user[$i]['telphone']= $value;break; 
						case   '固定电话':$user[$i]['linkphone']= $value;break; 
						case   '联系QQ':$user[$i]['linkqq']= $value;break; 
						case   '联系地址':$user[$i]['address']= $value;break; 
						case   '联系邮箱':$user[$i]['email']= $value;break; 
						
						case   '企业行业':$user[$i]['hy']= $value;break; 
						case   '企业规模':$user[$i]['mun']= $value;break; 
						case   '企业简介':$user[$i]['content']= $value;break; 
						case   '招聘职位':$user[$i]['name']= $value;break; 
						
						case   '招聘岗位':$user[$i]['jobclass']= $value;break; 
						case   '招聘人数':$user[$i]['num']= $value;break; 
						case   '岗位要求':$user[$i]['description']= $value;break; 
						
						case   '薪资待遇':$user[$i]['salary']= $value;break; 
						case   '工作经验':$user[$i]['exp']=$value;break; 
						case   '学历要求':$user[$i]['edu']=$value;break; 
						case   '工作地点':$user[$i]['city']=$value;break; 
					}  
				}
			//}			
		}  
		if(is_array($user)){
			$numjob=0;$numuser=0;$los=0;
			
			include PLUS_PATH."/job.cache.php";
			include PLUS_PATH."/industry.cache.php";
			include PLUS_PATH."/city.cache.php";
			include PLUS_PATH."/com.cache.php";
			foreach($user as $key=>$value)
			{
				$salt = substr(uniqid(rand()), -6);
				$pass =array("a","b","c","d","e","f","g","h","i","g","k","l","m","n","o","p","q","r","s","t","u","v","w","x","w","z","1","2","3","4","5","6","7","8","9","0");
				$password =  '';
				$len = rand(8,12);
				for($i=0;$i<$len;$i++)
				{
					$k = rand(0,36);
					$password.=$pass[$k];
				}
				$npass = md5(md5($password).$salt);
				$time = time();
				if($value[comname]!="")
				{
					$comname = $value[comname];

					$mvalue = "`username`='$value[comname]',`password`='$npass',`email`='$value[email]',`usertype`='2',`address`='$value[address]',`status`='1',`salt`='$salt',`moblie`='$value[telphone]',`reg_date`='$time',`passtext`='$password',`source`='7'";
					$uid = $this->obj->DB_insert_once("member",$mvalue);

					$statisval = "`uid`='$uid',";
					
					$ratingM = $this->MODEL('rating');
					$statisval.=$ratingM->rating_info();

					$this->obj->DB_insert_once("company_statis",$statisval);
					if($uid)
					{
						$numuser++;
						$comval = "`uid`='$uid',`name`='$value[comname]',`r_status`='1'";
						$comval.=",`linkman`='$value[linkman]'";
						$comval.=",`linkjob`='$value[linkjob]'";
						$comval.=",`linktel`='$value[linktel]'";
						$comval.=",`linkphone`='$value[linkphone]'";
						$comval.=",`linkqq`='$value[linkqq]'";
						$comval.=",`address`='$value[address]'";
						$comval.=",`linkmail`='$value[email]'";
						$comval.=",`content`='$value[content]'";
						if(is_array($industry_name) && $value[hy]!="")
						{
							foreach($industry_name as $k=>$v)
							{
								if(strpos($v,$value['hy'])!==false)
								{
									$hy = $k;
								}
							}
							$comval.=",`hy`='$hy'";
						}

						if($value[mun])
						{
							$mun = str_replace("人","",$value[mun]);
							$mun = @explode("-",$mun);

							if($mun[1]<20)
							{
								$munval = "27";
							}elseif($mun[1]<99){
								$munval = "28";
							}
							elseif($mun[1]<499){
								$munval = "29";
							}elseif($mun[1]<999){
								$munval = "30";
							}elseif($mun[1]<9999){
								$munval = "31";
							}else{
								$munval = "32";
							}

							$comval.=",`mun`='$munval'";
							$mun = $munval;
						}
					}

					$this->obj->DB_insert_once("company",$comval);
				}

				if($uid)
				{
					$stime = time();
					$etime = $stime+3600*24*30;
					if($value['jobclass']!="")
					{
						$job1 = $job1_son = $job_post=0;
						$jobarr = explode('*',$value['jobclass']);
						$jobval = "`uid`='$uid',`hy`='$hy',`description`='$value[description]',`name`='$value[name]',`state`='1',`sdate`='$stime',`edate`='$etime',`lastupdate`='".$stime."'";
						$job_post="";
						if($jobarr[0]!="")
						{
							foreach($job_index as $k=>$v)
							{
								if((strpos($job_name[$v],$jobarr[0])!==false || strpos($jobarr[0],$job_name[$v])!==false))
								{
									$job1 = $v;
									break;
								}
							}
							if($job1>0 && $jobarr[1]!="")
							{
								if(is_array($job_type[$job1]))
								{
									foreach($job_type[$job1] as $k=>$v)
									{
										if((strpos($job_name[$v],$jobarr[1])!==false || strpos($jobarr[1],$job_name[$v])!==false))
										{
											$job1_son = $v;
											break;
										}
									}
									if($job1_son>0 && $jobarr[2]!="")
									{
										if(is_array($job_type[$job1_son]))
										{
											foreach($job_type[$job1_son] as $k=>$v)
											{
												if((strpos($job_name[$v],$jobarr[2])!==false || strpos($jobarr[2],$job_name[$v])!==false))
												{
													$job_post = $v;
													break;
												}
											}
										}
									}
								}
							}
						}



						if(is_array($job_name))
						{
							foreach($job_name as $k=>$v)
							{
								if(strpos($v,$value['jobclass']))
								{
									$job_post = $k;
									foreach($job_type as $kk=>$vv)
									{
										if($k == $vv)
										{
											$job1_son = $kk;
											foreach($job_index as $kkk=>$vvv)
											{
												if($kk == $vvv)
												{
													$job1 = $kk;
												}
											}
										}
									}
								}
							}
						}
						if($job_post!="")
						{
							$jobval.=",`job1`='$job1',`job1_son`='$job1_son',`job_post`='$job_post'";
						}
						if($value[num]=="若干")
						{
							$num = "40";
						}elseif((int)$value[num]<2){

							$num = "41";

						}elseif((int)$value[num]<10){

							$num = "42";
						}elseif((int)$value[num]<50){

							$num = "43";
						}elseif((int)$value[num]<100){

							$num = "44";
						}elseif((int)$value[num]<999){

							$num = "45";
						}else{
							$num="";
						}
						if($num!="")
						{
							$jobval.=",`number`='$num'";
						}
						if($value[sex]=="女")
						{
							$jobval.=",`sex`='2'";
						}elseif($value[sex]=="男"){
							$jobval.=",`sex`='1'";
						}else{
							$jobval.=",`sex`='3'";
						}

						if($value['exp']!="")
						{
							foreach($comdata['user_word'] as $k=>$v)
							{
								if(strpos($comclass_name[$v],$value['exp'])!==false)
								{
									$exp = $v;
								}
							}
						}
						if($value['edu']!="")
						{
							foreach($comdata['job_edu'] as $k=>$v)
							{
								if(strpos($comclass_name[$v],$value['edu'])!==false)
								{
									$edu = $v;
								}
							}
						}
						$jobval.=",`edu`='$edu'";
						if($value['city'])
						{
							$provinceid = $cityid = $three_cityid=0;
							$city_row=$this->get_city($value['city']);

							$i=1;
							foreach($city_row as $v){
								if($i==1){
									$provinceid=$v;
								}
								if($i==2){
									$cityid=$v;
								}
								if($i==3){
									$three_cityid=$v;
								}
								$i++;
							}


							$jobval.= ",`provinceid`='$provinceid',`cityid`='$cityid',`three_cityid`='$three_cityid'";
						}

						$salaryN = explode('-',$value['salary']); 
						$jobval.=",`minsalary`='".$salaryN[0]."',`maxsalary`='".$salaryN[1]."'";
						$jobval.=",`com_name`='$comname',`mun` = $mun,`source`='7'";
						$numjob++;

						$this->obj->DB_insert_once("company_job",$jobval);
					}
				}
			}
			$msg = "本次新增企业会员：".$numuser."人，新增职位：".$numjob."个";
			$this->ACT_layer_msg($msg,9,$_SERVER['HTTP_REFERER'],2,1);

		}else{
			$this->ACT_layer_msg("未读取到合适的数据，请检查格式是否规范！",8,$_SERVER['HTTP_REFERER'],2,1);
		}
	}
	function excellog_action()
	{
		$urlarr=array("page"=>"{{page}}");
		$pageurl=Url($_GET[act],$urlarr,'admin');
		$rows=$this->get_page("excel"," 1 order by time desc",$pageurl,"15");
		$this->yuntpl(array('admin/admin_excellist'));
	}
	
	function del_action(){

		$this->check_token();
	    if($_GET[delsub]){
	    	$del=$_GET[del];
	    	if($del){
	    		if(is_array($del)){
			    	foreach($del as $v){
			    	    $this->obj->DB_delete_all("excel","`id`='$v'");
			    	}
		    	}else{
		    		$this->obj->DB_delete_all("excel","`id`='$del'");
		    	}
	    		$this->obj->get_admin_msg($_SERVER['HTTP_REFERER'],"删除成功！");
	    	}else{
	    		$this->obj->get_admin_msg($_SERVER['HTTP_REFERER'],"请选择您要删除的招聘");
	    	}
	    }
	    if(isset($_GET[id])){
			$result=$this->obj->DB_delete_all("excel","`id`='".$_GET[id]."'" );
			isset($result)?$this->obj->get_admin_msg($_SERVER['HTTP_REFERER'],"删除成功"):$this->obj->get_admin_msg($_SERVER['HTTP_REFERER'],"删除失败");
		}else{
			$this->obj->get_admin_msg($_SERVER['HTTP_REFERER'],"非法操作");
		}
	}
	function uparr($arr)
	{
		$arr = str_replace("年","-",$arr);
		$arr = str_replace("月","-",$arr);
		$arr = str_replace("日","-",$arr);

		$narr = @explode("-",$arr);

		if($narr[2]=="")
		{
			$narr[2] = "01";
		}
		if($narr[1]=="")
		{
			$narr[1] = "01";
		}
		if($narr[0]!="")
		{
			$arr = $narr[0]."-".$narr[1]."-".$narr[2];
		}
		$arr = strtotime($arr);
		return $arr;
	}
	function add_tj_phpyun($type,$id){
		$data="`uid`='".$this->uid."',";
		$data.="`type`='".$type."',";
		$data.="`nid`='".$id."',";
		$data.="`state`='0',";
		$data.="`ctime`='".mktime()."'";
		$nid=$this->db->DB_insert_once("user_import_tj",$data);
	}

	function locoytostr($arr,$str,$unstr='',$locoy_rate="50"){
		foreach($arr as $key=>$value)
		{
			if($key!=$unstr){
				similar_text($str,$value,$locoy_rate);
				$rows[$locoy_rate]=$key;
				$aaa[$locoy_rate] = $value;
			}
		}
		krsort($rows);
		foreach($rows as $k =>$v){
			if ($k>=$locoy_rate){
				return array('id'=>$v,'percent'=>$k);
			}else{
				return false;
			}
		}
	}

	function tostring($string){
		$length=strlen($string);
		$retstr='';
		for($i=0;$i<$length;$i++) {
			$retstr[]=ord($string[$i])>127?$string[$i].$string[++$i]:$string[$i];
		}
		return $retstr;
	}

	function get_com_type($cat){
		include(PLUS_PATH."com.cache.php");
		foreach($comdata["job_".$cat] as $v){
			$data[$v]=$comclass_name[$v];
		}
		return $data;
	}

	function get_city($name){
		include(PLUS_PATH."city.cache.php");
		$name=str_replace(array("省","市"),"/",$name);
		$city_name_old = $city_name;
		$arr=explode("/",$name);
		if(is_array($arr)){
			foreach($arr as $v){
				$locoystr=$this->locoytostr($city_name,$v,$locoystr['id']);
				if($locoystr['id']){
					foreach($city_type[$locoystr['id']] as $key=>$value){
						$city_name_new[$value] = $city_name_old[$value];
					}
					$city_name = $city_name_new;
				}
				$data[] = $locoystr['id'];
			}
		}

		return $data;
	}

	function get_all_city($city_type,$data,$k=""){
		if(is_array($data)){
			foreach($data as $v){
				foreach($city_type as $key=>$value){
					$a=$k?$k:$v;
					if(in_array($a,$value)){
						if($key){
							$val=$this->get_all_city($city_type,$data,$key);
						}
						$val[$key]=$a;
					}
				}
			}
		}
		return $val;
	}

	function get_once_city($t,$n,$id){
		$row=$n[$id];
		if(is_array($t[$id])){
			foreach($t[$id] as $k=>$v){
				$array[$v]=$n[$v];
			}
		}
		$locoystr=$this->locoytostr($array,$row);
		$r = array('id'=>$locoystr['id'],'percent'=>$locoystr['percent'],'name'=>$row);
		return $r;
	}
	function get_job_class($name){
		include(PLUS_PATH."job.cache.php");

		$arr=explode(",",$name);

		if(is_array($arr)){
			foreach($arr as $v){
				$locoystr=$this->locoytostr($job_name,$v);

				$data[] = $locoystr['id'];
			}
		}
		return $data;
	}
	function get_user_type($cat){
		include(PLUS_PATH."user.cache.php");
		foreach($userdata["user_".$cat] as $v){
			$data[$v]=$userclass_name[$v];
		}
		return $data;
	}
}
