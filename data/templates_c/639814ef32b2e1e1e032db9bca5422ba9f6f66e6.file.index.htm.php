<?php /* Smarty version Smarty-3.1.21-dev, created on 2019-03-13 12:05:26
         compiled from "/www/wwwroot/hr/app/template/default/index/index.htm" */ ?>
<?php /*%%SmartyHeaderCode:4102960075c888186a64299-10271197%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '639814ef32b2e1e1e032db9bca5422ba9f6f66e6' => 
    array (
      0 => '/www/wwwroot/hr/app/template/default/index/index.htm',
      1 => 1519464006,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '4102960075c888186a64299-10271197',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'title' => 0,
    'keywords' => 0,
    'description' => 0,
    'style' => 0,
    'config' => 0,
    'ishtml' => 0,
    'adlist_73' => 0,
    'adlist_72' => 0,
    'lunbo' => 0,
    'keylist' => 0,
    'urgent_list' => 0,
    'announcementlist' => 0,
    'hotjoblist' => 0,
    'rlist' => 0,
    'adlist_13' => 0,
    'adlist_14' => 0,
    'adlist_15' => 0,
    'rec_list' => 0,
    'job_list' => 0,
    'ulist' => 0,
    'alist' => 0,
    'pkey' => 0,
    'plist' => 0,
    'linklist' => 0,
    'linklist2' => 0,
    'uid' => 0,
    'footer_ad' => 0,
    'key' => 0,
    'left_ad' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_5c888186efe6c0_15658469',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5c888186efe6c0_15658469')) {function content_5c888186efe6c0_15658469($_smarty_tpl) {?><?php if (!is_callable('smarty_function_url')) include '/www/wwwroot/hr/app/include/libs/plugins/function.url.php';
if (!is_callable('smarty_function_listurl')) include '/www/wwwroot/hr/app/include/libs/plugins/function.listurl.php';
if (!is_callable('smarty_function_formatpicurl')) include '/www/wwwroot/hr/app/include/libs/plugins/function.formatpicurl.php';
if (!is_callable('smarty_function_webspecial')) include '/www/wwwroot/hr/app/include/libs/plugins/function.webspecial.php';
?><?php global $db,$db_config,$config;
		$time = time();
		
		
		
        eval('$paramer=array("namelen"=>"30","comlen"=>"30","urgent"=>"\'1\'","limit"=>"12","item"=>"\'urgent_list\'","name"=>"\'urgent_list1\'","nocache"=>"")
;');
		$ParamerArr = GetSmarty($paramer,$_GET,$_smarty_tpl);
		$paramer = $ParamerArr[arr];
        $Purl =  $ParamerArr[purl];
        global $ModuleName;
        if(!$Purl["m"]){
            $Purl["m"]=$ModuleName;
        }
		include_once  PLUS_PATH."/comrating.cache.php";
		include(CONFIG_PATH."db.data.php"); 
		if($config[sy_web_site]=="1"){
			if($config[province]>0 && $config[province]!=""){
				$paramer[provinceid] = $config[province];
			}
			if($config[cityid]>0 && $config[cityid]!=""){
				$paramer[cityid] = $config[cityid];
			}
			if($config[three_cityid]>0 && $config[three_cityid]!=""){
				$paramer[three_cityid] = $config[three_cityid];
			}
			if($config[hyclass]>0 && $config[hyclass]!=""){
				$paramer[hy]=$config[hyclass];
			}
		}
		if($paramer[sdate]){
			$where = "`sdate`>".strtotime("-".intval($paramer[sdate])." day",time())." and `edate`>'$time' and `state`=1";
		}else{
			$where = "`state`=1 and `edate`>'$time'";
		}
        
		
		if($paramer[uid]){
			$where .= " AND `uid` = '$paramer[uid]'";
		}
		
		if($paramer[rec]){
			
			$where.=" AND `rec_time`>=".time();
			
		}
		
		if($paramer['cert']){
			$job_uid=array();
			$company=$db->select_all("company","`yyzz_status`=1","`uid`");
			if(is_array($company)){
				foreach($company as $v){
					$job_uid[]=$v['uid'];
				}
			}
			$where.=" and `uid` in (".@implode(",",$job_uid).")";
		}
		
		if($paramer[noid]){
			$where.= " and `id`<>$paramer[noid]";
		}
		
		if($paramer[r_status]){
			$where.= " and `r_status`=2";
		}else{
			$where.= " and `r_status`=1";
		}
		
		if($paramer[status]){
			$where.= " and `status`='1'";
		}else{
			$where.= " and `status`='0'";
		}
		
		if($paramer[pr]){
			$where .= " AND `pr` =$paramer[pr]";
		}
		
		if($paramer['hy']){
			$where .= " AND `hy` = $paramer[hy]";
		} 
		
		if($paramer[mun]){
			$where .= " AND `mun` = $paramer[mun]";
		}
	
		if($paramer[job1]){
			$where .= " AND `job1` = $paramer[job1]";
		}
		
		if($paramer[job1_son]){
			$where .= " AND `job1_son` = $paramer[job1_son]";
		}
		
		if($paramer[job_post]){
			$where .= " AND (`job_post` IN ($paramer[job_post]))";
		}
		
		if($paramer['jobwhere']){
			$where .=" and ".$paramer['jobwhere'];
		}
		
		if($paramer['jobids']){
			$where.= " AND (`job1` = $paramer[jobids] OR `job1_son`=$paramer[jobids] OR `job_post`=$paramer[jobids])";
		}
		
		if($paramer['jobin']){
			$where .= " AND (`job1` IN ($paramer[jobin]) OR `job1_son` IN ($paramer[jobin]) OR `job_post` IN ($paramer[jobin]))";
		}
		
		if($paramer[provinceid]){
			$where .= " AND `provinceid` = $paramer[provinceid]";
		}
	
		if($paramer['cityid']){
			$where .= " AND (`cityid` IN ($paramer[cityid]))";
		}
	
		if($paramer['three_cityid']){
			$where .= " AND (`three_cityid` IN ($paramer[three_cityid]))";
		}
		if($paramer['cityin']){
			$where .= " AND `three_cityid` IN ($paramer[cityin])";
		}
		
		if($paramer[edu]){
			$where .= " AND `edu` = $paramer[edu]";
		}
		
		if($paramer[exp]){
			$where .= " AND `exp` = $paramer[exp]";
		}
		
		if($paramer[report]){
			$where .= " AND `report` = $paramer[report]";
		}
		
		if($paramer[type]){
			$where .= " AND `type` = $paramer[type]";
		}
	
		if($paramer[sex]){
			$where .= " AND `sex` = $paramer[sex]";
		}
		
		
		if($paramer[mun]){
			$where .= " AND `mun` = $paramer[mun]";
		}
		if($paramer[minsalary]&&$paramer[maxsalary]){
			$where.= " AND ((`minsalary`<=".intval($paramer[minsalary])." and `maxsalary`>=".intval($paramer[minsalary]).") 
						or (`minsalary`<=".intval($paramer[maxsalary])." and `maxsalary`>=".intval($paramer[maxsalary])."))";
			
    	}elseif($paramer[minsalary]&&!$paramer[maxsalary]){
			$where.= " AND ((`minsalary`<=".intval($paramer[minsalary])." and `maxsalary`>=".intval($paramer[minsalary]).") 
						or (`minsalary`>=".intval($paramer[minsalary])." and `maxsalary`>=".intval($paramer[minsalary]).") 
						or (`minsalary`!=0 and  `maxsalary`=0))";
			
		}elseif(!$paramer[minsalary]&&$paramer[maxsalary]){
			$where.= " AND ((`minsalary`<=".intval($paramer[maxsalary])." and `maxsalary`>=".intval($paramer[maxsalary]).") 
						or (`minsalary`<=".intval($paramer[maxsalary])." and `maxsalary`<=".intval($paramer[maxsalary]).") 
						or (`minsalary`<=".intval($paramer[maxsalary])." and maxsalary=0) 
						or (`minsalary`=0 and  `maxsalary`!=0)
						)";
			
		}
       
        $cache_array = $db->cacheget();
		$comclass_name = $cache_array["comclass_name"];
		if($paramer[welfare]){
		    $welfarename=$comclass_name[$paramer[welfare]];
			$welfare=$db->select_all("company"," `welfare` LIKE '%".$welfarename."%'","`uid`");
			if(is_array($welfare)){
				foreach($welfare as $v){
					$welfareid[]=$v['uid'];
				}
			}
			$where .=" AND uid in (".@implode(",",$welfareid).")";
		}
		
		if($paramer[cityin]){
			$where .= " AND (`provinceid` IN ($paramer[cityin]) OR `cityid` IN ($paramer[cityin]) OR `three_cityid` IN ($paramer[cityin]))";
		}
		
		if($paramer[urgent]){
			$where.=" AND `urgent_time`>".time();
		}
		
		if($paramer[uptime]){
			if($paramer[uptime]==1){
				$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
    			$where.=" AND lastupdate>$beginToday";
    		}else{
    			$time=time();
				$uptime = $time-($paramer[uptime]*86400);
				$where.=" AND lastupdate>$uptime";
    		}
		}
		
		if($paramer[comname]){
			$where.=" AND `com_name` LIKE '%".$paramer[comname]."%'";
		}
		
		if($paramer[com_pro]){
			$where.=" AND `com_provinceid` ='".$paramer[com_pro]."'";
		}
	
		if($paramer[keyword]){
			$where1[]="`name` LIKE '%".$paramer[keyword]."%'";
			$where1[]="`com_name` LIKE '%".$paramer[keyword]."%'";
			include  PLUS_PATH."/city.cache.php";
			foreach($city_name as $k=>$v){
				if(strpos($v,$paramer[keyword])!==false){
					$cityid[]=$k;
				}
			}
			if(is_array($cityid)){
				foreach($cityid as $value){
					$class[]= "(provinceid = '".$value."' or cityid = '".$value."')";
				}
				$where1[]=@implode(" or ",$class);
			}
			$where.=" AND (".@implode(" or ",$where1).")";
		}
		
		if($paramer["job"]){
			$where.=" AND `job_post` in ($paramer[job])";
		}
		
		if($paramer[bid]){
			$where.="  and `xsdate`>'".time()."'";
		} 
		
		
		if($paramer[where]){
			$where = $paramer[where];
		}

		
		if($paramer[limit]){
			$limit = " limit ".$paramer[limit];
		}

		if($paramer[ispage]){
			$limit = PageNav($paramer,$_GET,"company_job",$where,$Purl,"",$paramer[islt]?$paramer[islt]:"6",$_smarty_tpl);
          
		} 
		
		if($paramer[order] && $paramer[order]!="lastdate"){
			$order = " ORDER BY ".str_replace("'","",$paramer[order])."  ";
		}else{
			$order = " ORDER BY `lastupdate` ";
		}
		
		if($paramer[sort]){
			$sort = $paramer[sort];
		}else{
			$sort = " DESC";
		} 
		$where.=$order.$sort;  
		 
		$urgent_list = $db->select_all("company_job",$where.$limit);
		if(is_array($urgent_list)){
		
			$cache_array = $db->cacheget();
			$comuid=$jobid=array();
			foreach($urgent_list as $key=>$value){
				if(in_array($value['uid'],$comuid)==false){$comuid[] = $value['uid'];}
				if(in_array($value['id'],$jobid)==false){$jobid[] = $value['id'];} 
			}
			$comuids = @implode(',',$comuid);
			$jobids = @implode(',',$jobid);
			
			if($comuids){
				$r_uids=$db->select_all("company","`uid` IN (".$comuids.")","`uid`,`yyzz_status`,`logo`,`pr`,`hy`,`mun`,`shortname`,`welfare`");
				if(is_array($r_uids)){
					foreach($r_uids as $key=>$value){
						if($value[shortname]){
    						$value['shortname_n'] = $value[shortname];
    					}
						if(!$value['logo'] || !file_exists(str_replace('./',APP_PATH,$value['logo']))){
							$value['logo'] = $config['sy_weburl']."/".$config['sy_unit_icon'];
						}else{
							$value['logo']= $config['sy_weburl']."/".$value['logo'];
						}
						$value['pr_n'] = $cache_array['comclass_name'][$value[pr]];
						$value['hy_n'] = $cache_array['industry_name'][$value[hy]];
						$value['mun_n'] = $cache_array['comclass_name'][$value[mun]];
						$r_uid[$value['uid']] = $value;

					}
				}
			}
			    
			
			if($paramer[bid]){
				$noids=array();
			}	
			foreach($urgent_list as $key=>$value){

				if($paramer[bid]){
					$noids[] = $value[id];
				}
				
				if($paramer[noids]==1 && !empty($noids) && in_array($value['id'],$noids)){
					unset($urgent_list[$key]);
					continue;
				}else{
					$urgent_list[$key] = $db->array_action($value,$cache_array);
					$urgent_list[$key][stime] = date("Y-m-d",$value[sdate]);
					$urgent_list[$key][etime] = date("Y-m-d",$value[edate]);
					if($arr_data['sex'][$value['sex']]){
						$urgent_list[$key][sex_n]=$arr_data['sex'][$value['sex']];
					}
					$urgent_list[$key][lastupdate] = date("Y-m-d",$value[lastupdate]);
					if($value[minsalary]&&$value[maxsalary]){
						$urgent_list[$key][job_salary] =$value[minsalary]."-".$value[maxsalary];
					}elseif($value[minsalary]){
						$urgent_list[$key][job_salary] =$value[minsalary]."以上";
					}else{
						$urgent_list[$key][job_salary] ="面议";
					}
					if($r_uid[$value['uid']][shortname]){
						$urgent_list[$key][com_name] =$r_uid[$value['uid']][shortname];
					}
					$urgent_list[$key][yyzz_status] =$r_uid[$value['uid']][yyzz_status];
					$urgent_list[$key][logo] =$r_uid[$value['uid']][logo];
					$urgent_list[$key][pr_n] =$r_uid[$value['uid']][pr_n];
					$urgent_list[$key][hy_n] =$r_uid[$value['uid']][hy_n];
					$urgent_list[$key][mun_n] =$r_uid[$value['uid']][mun_n];
					if($r_uid[$value['uid']][welfare]){
						$welfareList = @explode(',',$r_uid[$value['uid']][welfare]);

						if(!empty($welfareList)){
							$urgent_list[$key][welfarename] =$welfareList;
						}
					}
					
					

					$time=$value['lastupdate'];
					
					$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
					
					$beginYesterday=mktime(0,0,0,date('m'),date('d')-1,date('Y'));
					
					$week=strtotime(date("Y-m-d",strtotime("-1 week")));
					if($time>$week && $time<$beginYesterday){
						$urgent_list[$key]['time'] ="一周内";
					}elseif($time>$beginYesterday && $time<$beginToday){
						$urgent_list[$key]['time'] ="昨天";
					}elseif($time>$beginToday){	
						$urgent_list[$key]['time'] = date("H:i",$value['lastupdate']);
						$urgent_list[$key]['redtime'] =1;
					}else{
						$urgent_list[$key]['time'] = date("Y-m-d",$value['lastupdate']);
					}
					
					
					
					if($paramer[comlen]){
						if($r_uid[$value['uid']][shortname]){
							$urgent_list[$key][com_n] = mb_substr($r_uid[$value['uid']][shortname],0,$paramer[comlen],"utf-8");
						}else{
							$urgent_list[$key][com_n] = mb_substr($value['com_name'],0,$paramer[comlen],"utf-8");
						}
					}
					
					if($paramer[namelen]){
						if($value['rec_time']>time()){
							$urgent_list[$key][name_n] = "<font color='red'>".mb_substr($value['name'],0,$paramer[namelen],"utf-8")."</font>";
						}else{
							$urgent_list[$key][name_n] = mb_substr($value['name'],0,$paramer[namelen],"utf-8");
						}
					}else{
						if($value['rec_time']>time()){
							$urgent_list[$key]['name_n'] = "<font color='red'>".$value['name']."</font>";
						}
					}
					
					$urgent_list[$key][job_url] = Url("job",array("c"=>"comapply","id"=>$value[id]),"1");
					
					$urgent_list[$key][com_url] = Url("company",array("c"=>"show","id"=>$value[uid]));
					foreach($comrat as $k=>$v){
						if($value[rating]==$v[id]){
							$urgent_list[$key][color] = str_replace("#","",$v[com_color]);
							$urgent_list[$key][ratlogo] = $v[com_pic];
							$urgent_list[$key][ratname] = $v[name];
						}
					}
					if($paramer[keyword]){
						$urgent_list[$key][name]=str_replace($paramer[keyword],"<font color=#FF6600 >".$paramer[keyword]."</font>",$value[name]);
						$urgent_list[$key][com_name]=str_replace($paramer[keyword],"<font color=#FF6600 >".$paramer[keyword]."</font>",$value[com_name]);
						$urgent_list[$key][name_n]=str_replace($paramer[keyword],"<font color=#FF6600 >".$paramer[keyword]."</font>",$urgent_list[$key][name_n]);
						$urgent_list[$key][com_n]=str_replace($paramer[keyword],"<font color=#FF6600 >".$paramer[keyword]."</font>",$urgent_list[$key][com_n]);
						$urgent_list[$key][job_city_one]=str_replace($paramer[keyword],"<font color=#FF6600 >".$paramer[keyword]."</font>",$city_name[$value[provinceid]]);
						$urgent_list[$key][job_city_two]=str_replace($paramer[keyword],"<font color=#FF6600 >".$paramer[keyword]."</font>",$city_name[$value[cityid]]);
					}
				}
			}

			if(is_array($urgent_list)){
				if($paramer[keyword]!=""&&!empty($urgent_list)){
					addkeywords('3',$paramer[keyword]);
				}
			}
		} ?>
<?php $announcementlist=array();$time=time();eval('$paramer=array("limit"=>"2","item"=>"\'announcementlist\'","t_len"=>"20","nocache"=>"")
;');
		global $db,$db_config,$config;
		$ParamerArr = GetSmarty($paramer,$_GET,$_smarty_tpl);
		$paramer = $ParamerArr[arr];
		$Purl =  $ParamerArr[purl];
        global $ModuleName;
        if(!$Purl["m"]){
            $Purl["m"]=$ModuleName;
        }
		$where = 1;
		
		if($config['did']){
			$where.=" and `did`='".$config['did']."'";
		}else if($config['sy_web_site']=="1"){
			$where.=" and `did`='0'";
		}  
		if($paramer[limit]){
			$limit=" LIMIT ".$paramer[limit];
		}else{
			$limit=" LIMIT 20";
		}
		if($paramer[ispage]){
			$limit = PageNav($paramer,$_GET,"admin_announcement",$where,$Purl,"",0,$_smarty_tpl);
		}
	
		if($paramer[order]){
			$where.="  ORDER BY `".$paramer[order]."`";
		}else{
			$where.="  ORDER BY `datetime`";
		}
	
		if($paramer[sort]){
			$where.=" ".$paramer[sort];
		}else{
			$where.=" DESC";
		}

		$announcementlist=$db->select_all("admin_announcement",$where.$limit);
		if(is_array($announcementlist)){
			foreach($announcementlist as $key=>$value){
			
				if($paramer[t_len]){
					$announcementlist[$key][title_n] = mb_substr($value['title'],0,$paramer[t_len],"utf-8");
				}
				$announcementlist[$key][time]=date("Y-m-d",$value[datetime]);
				$announcementlist[$key][url] = Url("announcement",array("id"=>$value[id]),"1");
			}
		} ?>
<?php global $db,$db_config,$config;
        global $ModuleName;
        if(!$Purl["m"]){
            $Purl["m"]=$ModuleName;
        }
		
		if($config[sy_web_site]=="1"){
			$jobwhere="";
			if($config[province]>0 && $config[province]!=""){
				$jobwhere.=" and `provinceid`='$config[province]'";
			}
			if($config[cityid]>0 && $config[cityid]!=""){
				$jobwhere.=" and `cityid`='$config[cityid]'";
			}
			if($config[three_cityid]>0 && $config[three_cityid]!=""){
				$jobwhere.=" and `three_cityid`='$config[three_cityid]'";
			}
			if($config[hyclass]>0 && $config[hyclass]!=""){
				$jobwhere.=" and `hy`='".$config[hyclass]."'";
			} 
			if($jobwhere){
				$comlist=$db->select_all("company","1 ".$jobwhere,"`uid`");
				if(is_array($comlist)){
					foreach($comlist as $v){
						$cuid[]=$v[uid];
					}
				}
				$hotwhere=" and `uid` in (".@implode(",",$cuid).")";
			} 
		}
		

		$time = time();
		$where = "`time_start`<$time AND `time_end`>$time".$hotwhere;$order = " ORDER BY `sort` ";$sort = 'ASC';$limit=" LIMIT 24";$where.=$order.$sort;
        $Query = $db->query("SELECT * FROM $db_config[def]hotjob where ".$where.$limit);
		while($rs = $db->fetch_array($Query)){
			$hotjoblist[] = $rs;
			$ListId[] =  $rs[uid];
		}

		
		$JobId = @implode(",",$ListId);
		$comList=$db->select_all("company","`uid` IN ($JobId)","`shortname`,`uid`");
		
		$JobList=$db->select_all("company_job","`uid` IN ($JobId) and `edate`>'".mktime()."' and state=1 and r_status='1' and status='0' $jobwhere");
		$statis=$db->select_all("company_statis","`uid` IN ($JobId)","`uid`,`comtpl`");
		if(is_array($ListId)){
			
			$cache_array = $db->cacheget();
			foreach($hotjoblist as $key=>$value){
				foreach($comList as $v){
					if($value['uid']==$v['uid']){
						if($v['shortname']){
							$hotjoblist[$key]["username"]= $v[shortname];
						}
					}
				}
				$i=0;
				if(is_array($JobList)){
					$hotjoblist[$key]["job"].="<div class=\"area_left\"> ";
					foreach($JobList as $k=>$v){
						if($value[uid]==$v[uid] && $i<5){
							$job_url = Url("job",array("c"=>"comapply","id"=>"$v[id]"),"1");
							$v[name] = mb_substr($v[name],0,10,"utf-8");
							$hotjoblist[$key]["job"].="<a href='".$job_url."'>".$v[name]."</a>";
							$i++;
						}
					}
					foreach($statis as $v){
						if($value['uid']==$v['uid']){
							if($v['comtpl'] && $v['comtpl']!="default"){
								$jobs_url = Url("company",array("c"=>"show","id"=>$value[uid]))."#job";
							}else{
								$jobs_url = Url("company",array("c"=>"show","id"=>$value[uid]));
							}
						}
					}
					$com_url = Url("company",array("c"=>"show","id"=>$value[uid]));
					$beizhu=mb_substr($value['beizhu'],0,50,"utf-8")."...";
					$hotjoblist[$key]["job"].="</div><div class=\"area_right\"><a href='".$com_url."'>".$value["username"]."</a>".$beizhu."</div>";
					$hotjoblist[$key]["url"]=$com_url;
				}
			}
		} ?>
<?php global $db,$db_config,$config;
		$time = time();
		
		
		//可以做缓存
        eval('$paramer=array("limit"=>"9","reward"=>"1","item"=>"\'rlist\'","nocache"=>"")
;');
		$ParamerArr = GetSmarty($paramer,$_GET,$_smarty_tpl);
		$paramer = $ParamerArr[arr];
        $Purl =  $ParamerArr[purl];
        global $ModuleName;
        if(!$Purl["m"]){
            $Purl["m"]=$ModuleName;
        }
		include_once  PLUS_PATH."/comrating.cache.php";
		include(CONFIG_PATH."db.data.php"); 

		if($paramer[reward]=='1'){
			$where="`rewardpack`='1'";

		}elseif($paramer[share]=='1'){
		
			$where="`sharepack`='1'";
		}
		
		$where .= " AND `r_status`='1' AND `state`=1 and `status`='0'  and `edate`>'$time'";
		
		
		//按照职位名称匹配
		if($paramer[keyword]){
			$where1[]="`name` LIKE '%".$paramer[keyword]."%'";
			$where1[]="`com_name` LIKE '%".$paramer[keyword]."%'";

			$where.=" AND (".@implode(" or ",$where1).")";
		}

		//筛除重复
		if($paramer[noids]==1 && !empty($noids)){
			$where.=" AND `id` NOT IN (".@implode(',',$noids).")";
		}
	

		//查询条数
		if($paramer[limit]){
			$limit = " limit ".$paramer[limit];
		}

		if($paramer[ispage]){
			$limit = PageNav($paramer,$_GET,"company_job",$where,$Purl,"",$paramer[islt]?$paramer[islt]:"6",$_smarty_tpl);
          
		} 
		//排序字段默认为更新时间
		if($paramer[order] && $paramer[order]!="lastdate"){
			$order = " ORDER BY ".str_replace("'","",$paramer[order])."  ";
		}else{
			$order = " ORDER BY `lastupdate` ";
		}
		//排序规则 默认为倒序
		if($paramer[sort]){
			$sort = $paramer[sort];
		}else{
			$sort = " DESC";
		} 
		$where.=$order.$sort;  
		 
		$rlist = $db->select_all("company_job",$where.$limit);
		if(is_array($rlist)){
			//处理类别字段
			$cache_array = $db->cacheget();
			$comuid=$jobid=array();
			foreach($rlist as $key=>$value){
				$comuid[] = $value['uid'];
				$jobid[] = $value['id'];
			}
			$comuids = @implode(',',$comuid);
			$jobids = @implode(',',$jobid);
			
			
			if($comuids){
				$r_uids=$db->select_all("company","`uid` IN (".$comuids.")","`uid`,`shortname`,`yyzz_status`,`logo`,`pr`,`hy`,`mun`");
				if(is_array($r_uids)){
					foreach($r_uids as $key=>$value){
						if($value['shortname']){
    						$value['shortname'] =$value['shortname'];
    					}
						if(!$value['logo'] || !file_exists(str_replace($config['sy_weburl'],APP_PATH,$value['logo']))){
							$value['logo'] = $config['sy_weburl']."/".$config['sy_unit_icon'];
						}else{
							$value['logo']= $config['sy_weburl']."/".$value['logo'];
						}
						$value['pr_n'] = $cache_array['comclass_name'][$value[pr]];
						$value['hy_n'] = $cache_array['industry_name'][$value[hy]];
						$value['mun_n'] = $cache_array['comclass_name'][$value[mun]];
						$r_uid[$value['uid']] = $value;
					}
				}
			}
			if($jobids){
				if($paramer[reward]=='1'){
					
					$rewardList=$db->select_all("company_job_reward","`jobid` IN (".$jobids.")");
					
				}elseif($paramer[share]=='1'){ 

					$rewardList=$db->select_all("company_job_share","`jobid` IN (".$jobids.")","`jobid`,`packmoney`,`packprice`,`packnum`");
				
				}
				if(is_array($rewardList)){
						foreach($rewardList as $key=>$value){
							
							$rewadArr[$value['jobid']] = $value;
						}
					}
			}
			    
			
			$noids=array();
			foreach($rlist as $key=>$value){
				if($paramer[bid]){
					$noids[] = $value[id];
				}
				$rlist[$key] = $db->array_action($value,$cache_array);
				$rlist[$key][stime] = date("Y-m-d",$value[sdate]);
				$rlist[$key][etime] = date("Y-m-d",$value[edate]);
				if($arr_data['sex'][$value['sex']]){
    				$rlist[$key][sex_n]=$arr_data['sex'][$value['sex']];
    			}
				$rlist[$key][lastupdate] = date("Y-m-d",$value[lastupdate]);

				if($value[minsalary] && $value[maxsalary]){
					$rlist[$key][job_salary] =$value[minsalary]."-".$value[maxsalary];
				}elseif($value[minsalary]){
					$rlist[$key][job_salary] =$value[minsalary]."以上";
				}else{
                    $rlist[$key][job_salary] ="面议";
                }
				if($r_uid[$value['uid']][shortname]){
    				$rlist[$key][com_name] =$r_uid[$value['uid']][shortname];
    			}
				$rlist[$key][yyzz_status] =$r_uid[$value['uid']][yyzz_status];
				$rlist[$key][logo] =$r_uid[$value['uid']][logo];
				$rlist[$key][pr_n] =$r_uid[$value['uid']][pr_n];
				$rlist[$key][hy_n] =$r_uid[$value['uid']][hy_n];
				$rlist[$key][mun_n] =$r_uid[$value['uid']][mun_n];
				if($paramer[reward]=='1'){
					$rlist[$key][sqmoney] = $rewadArr[$value['id']][sqmoney];
					$rlist[$key][invitemoney] = $rewadArr[$value['id']][invitemoney];
					$rlist[$key][offermoney] = $rewadArr[$value['id']][offermoney];
					$rlist[$key][money] = $rewadArr[$value['id']][money];
					$rlist[$key][r_exp] = $rewadArr[$value['id']][exp];
					$rlist[$key][r_edu] = $rewadArr[$value['id']][edu];
					$rlist[$key][r_project] = $rewadArr[$value['id']][project];
					$rlist[$key][r_skill] = $rewadArr[$value['id']][skill];
				}

				if($paramer[share]=='1'){
					$rlist[$key][packmoney] = $rewadArr[$value['id']][packmoney];
					$rlist[$key][packnum] = $rewadArr[$value['id']][packnum];
					$rlist[$key][packprice] = $rewadArr[$value['id']][packprice];
					
				}
				

				$time=$value['lastupdate'];
				//今天开始时间戳
				$beginToday=time(0,0,0,date('m'),date('d'),date('Y'));
				//昨天开始时间戳
				$beginYesterday=time(0,0,0,date('m'),date('d')-1,date('Y'));
				//一周内时间戳
				$week=strtotime(date("Y-m-d",strtotime("-1 week")));
				if($time>$week && $time<$beginYesterday){
					$rlist[$key]['time'] ="一周内";
				}elseif($time>$beginYesterday && $time<$beginToday){
					$rlist[$key]['time'] ="昨天";
				}elseif($time>$beginToday){	
					$rlist[$key]['time'] = date("H:i",$value['lastupdate']);
					$rlist[$key]['redtime'] =1;
				}else{
					$rlist[$key]['time'] = date("Y-m-d",$value['lastupdate']);
				}
				//获得福利待遇名称
				if(is_array($rlist[$key]['welfare'])&&$rlist[$key]['welfare']){
					foreach($rlist[$key]['welfare'] as $val){
						//$rlist[$key]['welfarename'][]=$cache_array['comclass_name'][$val];
						$rlist[$key]['welfarename'][]=$val;
					}

				}
				//截取公司名称
				if($paramer[comlen]){
					if($r_uid[$value['uid']][shortname]){
    					$rlist[$key][com_n] = mb_substr($r_uid[$value['uid']][shortname],0,$paramer[comlen],"utf-8");
    				}else{
    					$rlist[$key][com_n] = mb_substr($value['com_name'],0,$paramer[comlen],"utf-8");
    				}
					
				}
				//截取职位名称
				if($paramer[namelen]){
					if($value['rec_time']>time()){
						$rlist[$key][name_n] = "<font color='red'>".mb_substr($value['name'],0,$paramer[namelen],"utf-8")."</font>";
					}else{
						$rlist[$key][name_n] = mb_substr($value['name'],0,$paramer[namelen],"utf-8");
					}
				}else{
					if($value['rec_time']>time()){
						$rlist[$key]['name_n'] = "<font color='red'>".$value['name']."</font>";
					}
				}
				//构建职位伪静态URL
				$rlist[$key][job_url] = Url("job",array("c"=>"comapply","id"=>$value[id]),"1");
				//构建企业伪静态URL
				$rlist[$key][com_url] = Url("company",array("c"=>"show","id"=>$value[uid]));
				foreach($comrat as $k=>$v){
					if($value[rating]==$v[id]){
						$rlist[$key][color] = str_replace("#","",$v[com_color]);
						$rlist[$key][ratlogo] = $v[com_pic];
						$rlist[$key][ratname] = $v[name];
					}
				}
				if($paramer[keyword]){
					$rlist[$key][name]=str_replace($paramer[keyword],"<font color=#FF6600 >".$paramer[keyword]."</font>",$value[name]);
					$rlist[$key][com_name]=str_replace($paramer[keyword],"<font color=#FF6600 >".$paramer[keyword]."</font>",$value[com_name]);
					$rlist[$key][name_n]=str_replace($paramer[keyword],"<font color=#FF6600 >".$paramer[keyword]."</font>",$rlist[$key][name_n]);
					$rlist[$key][com_n]=str_replace($paramer[keyword],"<font color=#FF6600 >".$paramer[keyword]."</font>",$rlist[$key][com_n]);
					$rlist[$key][job_city_one]=str_replace($paramer[keyword],"<font color=#FF6600 >".$paramer[keyword]."</font>",$city_name[$value[provinceid]]);
					$rlist[$key][job_city_two]=str_replace($paramer[keyword],"<font color=#FF6600 >".$paramer[keyword]."</font>",$city_name[$value[cityid]]);
    			}
			}

			if(is_array($rlist)){
				if($paramer[keyword]!=""&&!empty($rlist)){
					addkeywords('3',$paramer[keyword]);
				}
			}
		} ?>
<?php global $db,$db_config,$config;
		$time = time();
		
		
		
        eval('$paramer=array("namelen"=>"12","comlen"=>"18","rec"=>"\'1\'","limit"=>"9","item"=>"\'rec_list\'","name"=>"\'rec_list1\'","nocache"=>"")
;');
		$ParamerArr = GetSmarty($paramer,$_GET,$_smarty_tpl);
		$paramer = $ParamerArr[arr];
        $Purl =  $ParamerArr[purl];
        global $ModuleName;
        if(!$Purl["m"]){
            $Purl["m"]=$ModuleName;
        }
		include_once  PLUS_PATH."/comrating.cache.php";
		include(CONFIG_PATH."db.data.php"); 
		if($config[sy_web_site]=="1"){
			if($config[province]>0 && $config[province]!=""){
				$paramer[provinceid] = $config[province];
			}
			if($config[cityid]>0 && $config[cityid]!=""){
				$paramer[cityid] = $config[cityid];
			}
			if($config[three_cityid]>0 && $config[three_cityid]!=""){
				$paramer[three_cityid] = $config[three_cityid];
			}
			if($config[hyclass]>0 && $config[hyclass]!=""){
				$paramer[hy]=$config[hyclass];
			}
		}
		if($paramer[sdate]){
			$where = "`sdate`>".strtotime("-".intval($paramer[sdate])." day",time())." and `edate`>'$time' and `state`=1";
		}else{
			$where = "`state`=1 and `edate`>'$time'";
		}
        
		
		if($paramer[uid]){
			$where .= " AND `uid` = '$paramer[uid]'";
		}
		
		if($paramer[rec]){
			
			$where.=" AND `rec_time`>=".time();
			
		}
		
		if($paramer['cert']){
			$job_uid=array();
			$company=$db->select_all("company","`yyzz_status`=1","`uid`");
			if(is_array($company)){
				foreach($company as $v){
					$job_uid[]=$v['uid'];
				}
			}
			$where.=" and `uid` in (".@implode(",",$job_uid).")";
		}
		
		if($paramer[noid]){
			$where.= " and `id`<>$paramer[noid]";
		}
		
		if($paramer[r_status]){
			$where.= " and `r_status`=2";
		}else{
			$where.= " and `r_status`=1";
		}
		
		if($paramer[status]){
			$where.= " and `status`='1'";
		}else{
			$where.= " and `status`='0'";
		}
		
		if($paramer[pr]){
			$where .= " AND `pr` =$paramer[pr]";
		}
		
		if($paramer['hy']){
			$where .= " AND `hy` = $paramer[hy]";
		} 
		
		if($paramer[mun]){
			$where .= " AND `mun` = $paramer[mun]";
		}
	
		if($paramer[job1]){
			$where .= " AND `job1` = $paramer[job1]";
		}
		
		if($paramer[job1_son]){
			$where .= " AND `job1_son` = $paramer[job1_son]";
		}
		
		if($paramer[job_post]){
			$where .= " AND (`job_post` IN ($paramer[job_post]))";
		}
		
		if($paramer['jobwhere']){
			$where .=" and ".$paramer['jobwhere'];
		}
		
		if($paramer['jobids']){
			$where.= " AND (`job1` = $paramer[jobids] OR `job1_son`=$paramer[jobids] OR `job_post`=$paramer[jobids])";
		}
		
		if($paramer['jobin']){
			$where .= " AND (`job1` IN ($paramer[jobin]) OR `job1_son` IN ($paramer[jobin]) OR `job_post` IN ($paramer[jobin]))";
		}
		
		if($paramer[provinceid]){
			$where .= " AND `provinceid` = $paramer[provinceid]";
		}
	
		if($paramer['cityid']){
			$where .= " AND (`cityid` IN ($paramer[cityid]))";
		}
	
		if($paramer['three_cityid']){
			$where .= " AND (`three_cityid` IN ($paramer[three_cityid]))";
		}
		if($paramer['cityin']){
			$where .= " AND `three_cityid` IN ($paramer[cityin])";
		}
		
		if($paramer[edu]){
			$where .= " AND `edu` = $paramer[edu]";
		}
		
		if($paramer[exp]){
			$where .= " AND `exp` = $paramer[exp]";
		}
		
		if($paramer[report]){
			$where .= " AND `report` = $paramer[report]";
		}
		
		if($paramer[type]){
			$where .= " AND `type` = $paramer[type]";
		}
	
		if($paramer[sex]){
			$where .= " AND `sex` = $paramer[sex]";
		}
		
		
		if($paramer[mun]){
			$where .= " AND `mun` = $paramer[mun]";
		}
		if($paramer[minsalary]&&$paramer[maxsalary]){
			$where.= " AND ((`minsalary`<=".intval($paramer[minsalary])." and `maxsalary`>=".intval($paramer[minsalary]).") 
						or (`minsalary`<=".intval($paramer[maxsalary])." and `maxsalary`>=".intval($paramer[maxsalary])."))";
			
    	}elseif($paramer[minsalary]&&!$paramer[maxsalary]){
			$where.= " AND ((`minsalary`<=".intval($paramer[minsalary])." and `maxsalary`>=".intval($paramer[minsalary]).") 
						or (`minsalary`>=".intval($paramer[minsalary])." and `maxsalary`>=".intval($paramer[minsalary]).") 
						or (`minsalary`!=0 and  `maxsalary`=0))";
			
		}elseif(!$paramer[minsalary]&&$paramer[maxsalary]){
			$where.= " AND ((`minsalary`<=".intval($paramer[maxsalary])." and `maxsalary`>=".intval($paramer[maxsalary]).") 
						or (`minsalary`<=".intval($paramer[maxsalary])." and `maxsalary`<=".intval($paramer[maxsalary]).") 
						or (`minsalary`<=".intval($paramer[maxsalary])." and maxsalary=0) 
						or (`minsalary`=0 and  `maxsalary`!=0)
						)";
			
		}
       
        $cache_array = $db->cacheget();
		$comclass_name = $cache_array["comclass_name"];
		if($paramer[welfare]){
		    $welfarename=$comclass_name[$paramer[welfare]];
			$welfare=$db->select_all("company"," `welfare` LIKE '%".$welfarename."%'","`uid`");
			if(is_array($welfare)){
				foreach($welfare as $v){
					$welfareid[]=$v['uid'];
				}
			}
			$where .=" AND uid in (".@implode(",",$welfareid).")";
		}
		
		if($paramer[cityin]){
			$where .= " AND (`provinceid` IN ($paramer[cityin]) OR `cityid` IN ($paramer[cityin]) OR `three_cityid` IN ($paramer[cityin]))";
		}
		
		if($paramer[urgent]){
			$where.=" AND `urgent_time`>".time();
		}
		
		if($paramer[uptime]){
			if($paramer[uptime]==1){
				$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
    			$where.=" AND lastupdate>$beginToday";
    		}else{
    			$time=time();
				$uptime = $time-($paramer[uptime]*86400);
				$where.=" AND lastupdate>$uptime";
    		}
		}
		
		if($paramer[comname]){
			$where.=" AND `com_name` LIKE '%".$paramer[comname]."%'";
		}
		
		if($paramer[com_pro]){
			$where.=" AND `com_provinceid` ='".$paramer[com_pro]."'";
		}
	
		if($paramer[keyword]){
			$where1[]="`name` LIKE '%".$paramer[keyword]."%'";
			$where1[]="`com_name` LIKE '%".$paramer[keyword]."%'";
			include  PLUS_PATH."/city.cache.php";
			foreach($city_name as $k=>$v){
				if(strpos($v,$paramer[keyword])!==false){
					$cityid[]=$k;
				}
			}
			if(is_array($cityid)){
				foreach($cityid as $value){
					$class[]= "(provinceid = '".$value."' or cityid = '".$value."')";
				}
				$where1[]=@implode(" or ",$class);
			}
			$where.=" AND (".@implode(" or ",$where1).")";
		}
		
		if($paramer["job"]){
			$where.=" AND `job_post` in ($paramer[job])";
		}
		
		if($paramer[bid]){
			$where.="  and `xsdate`>'".time()."'";
		} 
		
		
		if($paramer[where]){
			$where = $paramer[where];
		}

		
		if($paramer[limit]){
			$limit = " limit ".$paramer[limit];
		}

		if($paramer[ispage]){
			$limit = PageNav($paramer,$_GET,"company_job",$where,$Purl,"",$paramer[islt]?$paramer[islt]:"6",$_smarty_tpl);
          
		} 
		
		if($paramer[order] && $paramer[order]!="lastdate"){
			$order = " ORDER BY ".str_replace("'","",$paramer[order])."  ";
		}else{
			$order = " ORDER BY `lastupdate` ";
		}
		
		if($paramer[sort]){
			$sort = $paramer[sort];
		}else{
			$sort = " DESC";
		} 
		$where.=$order.$sort;  
		 
		$rec_list = $db->select_all("company_job",$where.$limit);
		if(is_array($rec_list)){
		
			$cache_array = $db->cacheget();
			$comuid=$jobid=array();
			foreach($rec_list as $key=>$value){
				if(in_array($value['uid'],$comuid)==false){$comuid[] = $value['uid'];}
				if(in_array($value['id'],$jobid)==false){$jobid[] = $value['id'];} 
			}
			$comuids = @implode(',',$comuid);
			$jobids = @implode(',',$jobid);
			
			if($comuids){
				$r_uids=$db->select_all("company","`uid` IN (".$comuids.")","`uid`,`yyzz_status`,`logo`,`pr`,`hy`,`mun`,`shortname`,`welfare`");
				if(is_array($r_uids)){
					foreach($r_uids as $key=>$value){
						if($value[shortname]){
    						$value['shortname_n'] = $value[shortname];
    					}
						if(!$value['logo'] || !file_exists(str_replace('./',APP_PATH,$value['logo']))){
							$value['logo'] = $config['sy_weburl']."/".$config['sy_unit_icon'];
						}else{
							$value['logo']= $config['sy_weburl']."/".$value['logo'];
						}
						$value['pr_n'] = $cache_array['comclass_name'][$value[pr]];
						$value['hy_n'] = $cache_array['industry_name'][$value[hy]];
						$value['mun_n'] = $cache_array['comclass_name'][$value[mun]];
						$r_uid[$value['uid']] = $value;

					}
				}
			}
			    
			
			if($paramer[bid]){
				$noids=array();
			}	
			foreach($rec_list as $key=>$value){

				if($paramer[bid]){
					$noids[] = $value[id];
				}
				
				if($paramer[noids]==1 && !empty($noids) && in_array($value['id'],$noids)){
					unset($rec_list[$key]);
					continue;
				}else{
					$rec_list[$key] = $db->array_action($value,$cache_array);
					$rec_list[$key][stime] = date("Y-m-d",$value[sdate]);
					$rec_list[$key][etime] = date("Y-m-d",$value[edate]);
					if($arr_data['sex'][$value['sex']]){
						$rec_list[$key][sex_n]=$arr_data['sex'][$value['sex']];
					}
					$rec_list[$key][lastupdate] = date("Y-m-d",$value[lastupdate]);
					if($value[minsalary]&&$value[maxsalary]){
						$rec_list[$key][job_salary] =$value[minsalary]."-".$value[maxsalary];
					}elseif($value[minsalary]){
						$rec_list[$key][job_salary] =$value[minsalary]."以上";
					}else{
						$rec_list[$key][job_salary] ="面议";
					}
					if($r_uid[$value['uid']][shortname]){
						$rec_list[$key][com_name] =$r_uid[$value['uid']][shortname];
					}
					$rec_list[$key][yyzz_status] =$r_uid[$value['uid']][yyzz_status];
					$rec_list[$key][logo] =$r_uid[$value['uid']][logo];
					$rec_list[$key][pr_n] =$r_uid[$value['uid']][pr_n];
					$rec_list[$key][hy_n] =$r_uid[$value['uid']][hy_n];
					$rec_list[$key][mun_n] =$r_uid[$value['uid']][mun_n];
					if($r_uid[$value['uid']][welfare]){
						$welfareList = @explode(',',$r_uid[$value['uid']][welfare]);

						if(!empty($welfareList)){
							$rec_list[$key][welfarename] =$welfareList;
						}
					}
					
					

					$time=$value['lastupdate'];
					
					$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
					
					$beginYesterday=mktime(0,0,0,date('m'),date('d')-1,date('Y'));
					
					$week=strtotime(date("Y-m-d",strtotime("-1 week")));
					if($time>$week && $time<$beginYesterday){
						$rec_list[$key]['time'] ="一周内";
					}elseif($time>$beginYesterday && $time<$beginToday){
						$rec_list[$key]['time'] ="昨天";
					}elseif($time>$beginToday){	
						$rec_list[$key]['time'] = date("H:i",$value['lastupdate']);
						$rec_list[$key]['redtime'] =1;
					}else{
						$rec_list[$key]['time'] = date("Y-m-d",$value['lastupdate']);
					}
					
					
					
					if($paramer[comlen]){
						if($r_uid[$value['uid']][shortname]){
							$rec_list[$key][com_n] = mb_substr($r_uid[$value['uid']][shortname],0,$paramer[comlen],"utf-8");
						}else{
							$rec_list[$key][com_n] = mb_substr($value['com_name'],0,$paramer[comlen],"utf-8");
						}
					}
					
					if($paramer[namelen]){
						if($value['rec_time']>time()){
							$rec_list[$key][name_n] = "<font color='red'>".mb_substr($value['name'],0,$paramer[namelen],"utf-8")."</font>";
						}else{
							$rec_list[$key][name_n] = mb_substr($value['name'],0,$paramer[namelen],"utf-8");
						}
					}else{
						if($value['rec_time']>time()){
							$rec_list[$key]['name_n'] = "<font color='red'>".$value['name']."</font>";
						}
					}
					
					$rec_list[$key][job_url] = Url("job",array("c"=>"comapply","id"=>$value[id]),"1");
					
					$rec_list[$key][com_url] = Url("company",array("c"=>"show","id"=>$value[uid]));
					foreach($comrat as $k=>$v){
						if($value[rating]==$v[id]){
							$rec_list[$key][color] = str_replace("#","",$v[com_color]);
							$rec_list[$key][ratlogo] = $v[com_pic];
							$rec_list[$key][ratname] = $v[name];
						}
					}
					if($paramer[keyword]){
						$rec_list[$key][name]=str_replace($paramer[keyword],"<font color=#FF6600 >".$paramer[keyword]."</font>",$value[name]);
						$rec_list[$key][com_name]=str_replace($paramer[keyword],"<font color=#FF6600 >".$paramer[keyword]."</font>",$value[com_name]);
						$rec_list[$key][name_n]=str_replace($paramer[keyword],"<font color=#FF6600 >".$paramer[keyword]."</font>",$rec_list[$key][name_n]);
						$rec_list[$key][com_n]=str_replace($paramer[keyword],"<font color=#FF6600 >".$paramer[keyword]."</font>",$rec_list[$key][com_n]);
						$rec_list[$key][job_city_one]=str_replace($paramer[keyword],"<font color=#FF6600 >".$paramer[keyword]."</font>",$city_name[$value[provinceid]]);
						$rec_list[$key][job_city_two]=str_replace($paramer[keyword],"<font color=#FF6600 >".$paramer[keyword]."</font>",$city_name[$value[cityid]]);
					}
				}
			}

			if(is_array($rec_list)){
				if($paramer[keyword]!=""&&!empty($rec_list)){
					addkeywords('3',$paramer[keyword]);
				}
			}
		} ?>
<?php global $db,$db_config,$config;
		$time = time();
		
		
		
        eval('$paramer=array("namelen"=>"30","comlen"=>"18","limit"=>"9","item"=>"\'job_list\'","name"=>"\'job_list1\'","nocache"=>"")
;');
		$ParamerArr = GetSmarty($paramer,$_GET,$_smarty_tpl);
		$paramer = $ParamerArr[arr];
        $Purl =  $ParamerArr[purl];
        global $ModuleName;
        if(!$Purl["m"]){
            $Purl["m"]=$ModuleName;
        }
		include_once  PLUS_PATH."/comrating.cache.php";
		include(CONFIG_PATH."db.data.php"); 
		if($config[sy_web_site]=="1"){
			if($config[province]>0 && $config[province]!=""){
				$paramer[provinceid] = $config[province];
			}
			if($config[cityid]>0 && $config[cityid]!=""){
				$paramer[cityid] = $config[cityid];
			}
			if($config[three_cityid]>0 && $config[three_cityid]!=""){
				$paramer[three_cityid] = $config[three_cityid];
			}
			if($config[hyclass]>0 && $config[hyclass]!=""){
				$paramer[hy]=$config[hyclass];
			}
		}
		if($paramer[sdate]){
			$where = "`sdate`>".strtotime("-".intval($paramer[sdate])." day",time())." and `edate`>'$time' and `state`=1";
		}else{
			$where = "`state`=1 and `edate`>'$time'";
		}
        
		
		if($paramer[uid]){
			$where .= " AND `uid` = '$paramer[uid]'";
		}
		
		if($paramer[rec]){
			
			$where.=" AND `rec_time`>=".time();
			
		}
		
		if($paramer['cert']){
			$job_uid=array();
			$company=$db->select_all("company","`yyzz_status`=1","`uid`");
			if(is_array($company)){
				foreach($company as $v){
					$job_uid[]=$v['uid'];
				}
			}
			$where.=" and `uid` in (".@implode(",",$job_uid).")";
		}
		
		if($paramer[noid]){
			$where.= " and `id`<>$paramer[noid]";
		}
		
		if($paramer[r_status]){
			$where.= " and `r_status`=2";
		}else{
			$where.= " and `r_status`=1";
		}
		
		if($paramer[status]){
			$where.= " and `status`='1'";
		}else{
			$where.= " and `status`='0'";
		}
		
		if($paramer[pr]){
			$where .= " AND `pr` =$paramer[pr]";
		}
		
		if($paramer['hy']){
			$where .= " AND `hy` = $paramer[hy]";
		} 
		
		if($paramer[mun]){
			$where .= " AND `mun` = $paramer[mun]";
		}
	
		if($paramer[job1]){
			$where .= " AND `job1` = $paramer[job1]";
		}
		
		if($paramer[job1_son]){
			$where .= " AND `job1_son` = $paramer[job1_son]";
		}
		
		if($paramer[job_post]){
			$where .= " AND (`job_post` IN ($paramer[job_post]))";
		}
		
		if($paramer['jobwhere']){
			$where .=" and ".$paramer['jobwhere'];
		}
		
		if($paramer['jobids']){
			$where.= " AND (`job1` = $paramer[jobids] OR `job1_son`=$paramer[jobids] OR `job_post`=$paramer[jobids])";
		}
		
		if($paramer['jobin']){
			$where .= " AND (`job1` IN ($paramer[jobin]) OR `job1_son` IN ($paramer[jobin]) OR `job_post` IN ($paramer[jobin]))";
		}
		
		if($paramer[provinceid]){
			$where .= " AND `provinceid` = $paramer[provinceid]";
		}
	
		if($paramer['cityid']){
			$where .= " AND (`cityid` IN ($paramer[cityid]))";
		}
	
		if($paramer['three_cityid']){
			$where .= " AND (`three_cityid` IN ($paramer[three_cityid]))";
		}
		if($paramer['cityin']){
			$where .= " AND `three_cityid` IN ($paramer[cityin])";
		}
		
		if($paramer[edu]){
			$where .= " AND `edu` = $paramer[edu]";
		}
		
		if($paramer[exp]){
			$where .= " AND `exp` = $paramer[exp]";
		}
		
		if($paramer[report]){
			$where .= " AND `report` = $paramer[report]";
		}
		
		if($paramer[type]){
			$where .= " AND `type` = $paramer[type]";
		}
	
		if($paramer[sex]){
			$where .= " AND `sex` = $paramer[sex]";
		}
		
		
		if($paramer[mun]){
			$where .= " AND `mun` = $paramer[mun]";
		}
		if($paramer[minsalary]&&$paramer[maxsalary]){
			$where.= " AND ((`minsalary`<=".intval($paramer[minsalary])." and `maxsalary`>=".intval($paramer[minsalary]).") 
						or (`minsalary`<=".intval($paramer[maxsalary])." and `maxsalary`>=".intval($paramer[maxsalary])."))";
			
    	}elseif($paramer[minsalary]&&!$paramer[maxsalary]){
			$where.= " AND ((`minsalary`<=".intval($paramer[minsalary])." and `maxsalary`>=".intval($paramer[minsalary]).") 
						or (`minsalary`>=".intval($paramer[minsalary])." and `maxsalary`>=".intval($paramer[minsalary]).") 
						or (`minsalary`!=0 and  `maxsalary`=0))";
			
		}elseif(!$paramer[minsalary]&&$paramer[maxsalary]){
			$where.= " AND ((`minsalary`<=".intval($paramer[maxsalary])." and `maxsalary`>=".intval($paramer[maxsalary]).") 
						or (`minsalary`<=".intval($paramer[maxsalary])." and `maxsalary`<=".intval($paramer[maxsalary]).") 
						or (`minsalary`<=".intval($paramer[maxsalary])." and maxsalary=0) 
						or (`minsalary`=0 and  `maxsalary`!=0)
						)";
			
		}
       
        $cache_array = $db->cacheget();
		$comclass_name = $cache_array["comclass_name"];
		if($paramer[welfare]){
		    $welfarename=$comclass_name[$paramer[welfare]];
			$welfare=$db->select_all("company"," `welfare` LIKE '%".$welfarename."%'","`uid`");
			if(is_array($welfare)){
				foreach($welfare as $v){
					$welfareid[]=$v['uid'];
				}
			}
			$where .=" AND uid in (".@implode(",",$welfareid).")";
		}
		
		if($paramer[cityin]){
			$where .= " AND (`provinceid` IN ($paramer[cityin]) OR `cityid` IN ($paramer[cityin]) OR `three_cityid` IN ($paramer[cityin]))";
		}
		
		if($paramer[urgent]){
			$where.=" AND `urgent_time`>".time();
		}
		
		if($paramer[uptime]){
			if($paramer[uptime]==1){
				$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
    			$where.=" AND lastupdate>$beginToday";
    		}else{
    			$time=time();
				$uptime = $time-($paramer[uptime]*86400);
				$where.=" AND lastupdate>$uptime";
    		}
		}
		
		if($paramer[comname]){
			$where.=" AND `com_name` LIKE '%".$paramer[comname]."%'";
		}
		
		if($paramer[com_pro]){
			$where.=" AND `com_provinceid` ='".$paramer[com_pro]."'";
		}
	
		if($paramer[keyword]){
			$where1[]="`name` LIKE '%".$paramer[keyword]."%'";
			$where1[]="`com_name` LIKE '%".$paramer[keyword]."%'";
			include  PLUS_PATH."/city.cache.php";
			foreach($city_name as $k=>$v){
				if(strpos($v,$paramer[keyword])!==false){
					$cityid[]=$k;
				}
			}
			if(is_array($cityid)){
				foreach($cityid as $value){
					$class[]= "(provinceid = '".$value."' or cityid = '".$value."')";
				}
				$where1[]=@implode(" or ",$class);
			}
			$where.=" AND (".@implode(" or ",$where1).")";
		}
		
		if($paramer["job"]){
			$where.=" AND `job_post` in ($paramer[job])";
		}
		
		if($paramer[bid]){
			$where.="  and `xsdate`>'".time()."'";
		} 
		
		
		if($paramer[where]){
			$where = $paramer[where];
		}

		
		if($paramer[limit]){
			$limit = " limit ".$paramer[limit];
		}

		if($paramer[ispage]){
			$limit = PageNav($paramer,$_GET,"company_job",$where,$Purl,"",$paramer[islt]?$paramer[islt]:"6",$_smarty_tpl);
          
		} 
		
		if($paramer[order] && $paramer[order]!="lastdate"){
			$order = " ORDER BY ".str_replace("'","",$paramer[order])."  ";
		}else{
			$order = " ORDER BY `lastupdate` ";
		}
		
		if($paramer[sort]){
			$sort = $paramer[sort];
		}else{
			$sort = " DESC";
		} 
		$where.=$order.$sort;  
		 
		$job_list = $db->select_all("company_job",$where.$limit);
		if(is_array($job_list)){
		
			$cache_array = $db->cacheget();
			$comuid=$jobid=array();
			foreach($job_list as $key=>$value){
				if(in_array($value['uid'],$comuid)==false){$comuid[] = $value['uid'];}
				if(in_array($value['id'],$jobid)==false){$jobid[] = $value['id'];} 
			}
			$comuids = @implode(',',$comuid);
			$jobids = @implode(',',$jobid);
			
			if($comuids){
				$r_uids=$db->select_all("company","`uid` IN (".$comuids.")","`uid`,`yyzz_status`,`logo`,`pr`,`hy`,`mun`,`shortname`,`welfare`");
				if(is_array($r_uids)){
					foreach($r_uids as $key=>$value){
						if($value[shortname]){
    						$value['shortname_n'] = $value[shortname];
    					}
						if(!$value['logo'] || !file_exists(str_replace('./',APP_PATH,$value['logo']))){
							$value['logo'] = $config['sy_weburl']."/".$config['sy_unit_icon'];
						}else{
							$value['logo']= $config['sy_weburl']."/".$value['logo'];
						}
						$value['pr_n'] = $cache_array['comclass_name'][$value[pr]];
						$value['hy_n'] = $cache_array['industry_name'][$value[hy]];
						$value['mun_n'] = $cache_array['comclass_name'][$value[mun]];
						$r_uid[$value['uid']] = $value;

					}
				}
			}
			    
			
			if($paramer[bid]){
				$noids=array();
			}	
			foreach($job_list as $key=>$value){

				if($paramer[bid]){
					$noids[] = $value[id];
				}
				
				if($paramer[noids]==1 && !empty($noids) && in_array($value['id'],$noids)){
					unset($job_list[$key]);
					continue;
				}else{
					$job_list[$key] = $db->array_action($value,$cache_array);
					$job_list[$key][stime] = date("Y-m-d",$value[sdate]);
					$job_list[$key][etime] = date("Y-m-d",$value[edate]);
					if($arr_data['sex'][$value['sex']]){
						$job_list[$key][sex_n]=$arr_data['sex'][$value['sex']];
					}
					$job_list[$key][lastupdate] = date("Y-m-d",$value[lastupdate]);
					if($value[minsalary]&&$value[maxsalary]){
						$job_list[$key][job_salary] =$value[minsalary]."-".$value[maxsalary];
					}elseif($value[minsalary]){
						$job_list[$key][job_salary] =$value[minsalary]."以上";
					}else{
						$job_list[$key][job_salary] ="面议";
					}
					if($r_uid[$value['uid']][shortname]){
						$job_list[$key][com_name] =$r_uid[$value['uid']][shortname];
					}
					$job_list[$key][yyzz_status] =$r_uid[$value['uid']][yyzz_status];
					$job_list[$key][logo] =$r_uid[$value['uid']][logo];
					$job_list[$key][pr_n] =$r_uid[$value['uid']][pr_n];
					$job_list[$key][hy_n] =$r_uid[$value['uid']][hy_n];
					$job_list[$key][mun_n] =$r_uid[$value['uid']][mun_n];
					if($r_uid[$value['uid']][welfare]){
						$welfareList = @explode(',',$r_uid[$value['uid']][welfare]);

						if(!empty($welfareList)){
							$job_list[$key][welfarename] =$welfareList;
						}
					}
					
					

					$time=$value['lastupdate'];
					
					$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
					
					$beginYesterday=mktime(0,0,0,date('m'),date('d')-1,date('Y'));
					
					$week=strtotime(date("Y-m-d",strtotime("-1 week")));
					if($time>$week && $time<$beginYesterday){
						$job_list[$key]['time'] ="一周内";
					}elseif($time>$beginYesterday && $time<$beginToday){
						$job_list[$key]['time'] ="昨天";
					}elseif($time>$beginToday){	
						$job_list[$key]['time'] = date("H:i",$value['lastupdate']);
						$job_list[$key]['redtime'] =1;
					}else{
						$job_list[$key]['time'] = date("Y-m-d",$value['lastupdate']);
					}
					
					
					
					if($paramer[comlen]){
						if($r_uid[$value['uid']][shortname]){
							$job_list[$key][com_n] = mb_substr($r_uid[$value['uid']][shortname],0,$paramer[comlen],"utf-8");
						}else{
							$job_list[$key][com_n] = mb_substr($value['com_name'],0,$paramer[comlen],"utf-8");
						}
					}
					
					if($paramer[namelen]){
						if($value['rec_time']>time()){
							$job_list[$key][name_n] = "<font color='red'>".mb_substr($value['name'],0,$paramer[namelen],"utf-8")."</font>";
						}else{
							$job_list[$key][name_n] = mb_substr($value['name'],0,$paramer[namelen],"utf-8");
						}
					}else{
						if($value['rec_time']>time()){
							$job_list[$key]['name_n'] = "<font color='red'>".$value['name']."</font>";
						}
					}
					
					$job_list[$key][job_url] = Url("job",array("c"=>"comapply","id"=>$value[id]),"1");
					
					$job_list[$key][com_url] = Url("company",array("c"=>"show","id"=>$value[uid]));
					foreach($comrat as $k=>$v){
						if($value[rating]==$v[id]){
							$job_list[$key][color] = str_replace("#","",$v[com_color]);
							$job_list[$key][ratlogo] = $v[com_pic];
							$job_list[$key][ratname] = $v[name];
						}
					}
					if($paramer[keyword]){
						$job_list[$key][name]=str_replace($paramer[keyword],"<font color=#FF6600 >".$paramer[keyword]."</font>",$value[name]);
						$job_list[$key][com_name]=str_replace($paramer[keyword],"<font color=#FF6600 >".$paramer[keyword]."</font>",$value[com_name]);
						$job_list[$key][name_n]=str_replace($paramer[keyword],"<font color=#FF6600 >".$paramer[keyword]."</font>",$job_list[$key][name_n]);
						$job_list[$key][com_n]=str_replace($paramer[keyword],"<font color=#FF6600 >".$paramer[keyword]."</font>",$job_list[$key][com_n]);
						$job_list[$key][job_city_one]=str_replace($paramer[keyword],"<font color=#FF6600 >".$paramer[keyword]."</font>",$city_name[$value[provinceid]]);
						$job_list[$key][job_city_two]=str_replace($paramer[keyword],"<font color=#FF6600 >".$paramer[keyword]."</font>",$city_name[$value[cityid]]);
					}
				}
			}

			if(is_array($job_list)){
				if($paramer[keyword]!=""&&!empty($job_list)){
					addkeywords('3',$paramer[keyword]);
				}
			}
		} ?>
<?php $ulist=array();global $db,$db_config,$config;
		if(is_array($_GET)){
			foreach($_GET as $key=>$value){
				if($value=='0'){
					unset($_GET[$key]);
				}
			}
		}
		eval('$paramer=array("item"=>"\'ulist\'","post_len"=>"10","limit"=>"10","key"=>"\'key\'","name"=>"\'userlist1\'","nocache"=>"")
;');
		$ParamerArr = GetSmarty($paramer,$_GET,$_smarty_tpl);
		$paramer = $ParamerArr[arr];
		$Purl =  $ParamerArr[purl];
        global $ModuleName;
        if(!$Purl["m"]){
            $Purl["m"]=$ModuleName;
        }

	    
		$cache_array = $db->cacheget();
		$userclass_name = $cache_array["user_classname"];
		$city_name      = $cache_array["city_name"];
		$job_name		= $cache_array["job_name"];
		$job_type		= $cache_array["job_type"];
		$industry_name	= $cache_array["industry_name"];
		$where = "status='1' and `r_status`='1'  and `open`='1' and `defaults`='1'";
		
		if($config['sy_web_site']=="1"){
			if($config[province]>0 && $config[province]!=""){
				$paramer[provinceid] = $config[province];
			}
			if($config['cityid']>0 && $config['cityid']!=""){
				$paramer['cityid']=$config['cityid'];
			}
			if($config['three_cityid']>0 && $config['three_cityid']!=""){
				$paramer['three_cityid']=$config['three_cityid'];
			}
			if($config['hyclass']>0 && $config['hyclass']!=""){
				$paramer['hy']=$config['hyclass'];
			}
		}
		
		if($paramer[where_uid]){
			$where .=" AND `uid` in (".$paramer['where_uid'].")";
		}
	
		if($_COOKIE['uid']&&$_COOKIE['usertype']=="2"){
			$blacklist=$db->select_all("blacklist","`p_uid`='".$_COOKIE['uid']."'","c_uid");
			if(is_array($blacklist)&&$blacklist){
				foreach($blacklist as $v){
					$buid[]=$v['c_uid'];
				}
			$where .=" AND `uid` NOT IN (".@implode(",",$buid).")";
			}
		}
		
		if($paramer[topdate]){
			$where .=" AND `topdate`>'".time()."'";
		}
		
		
		if($paramer[idcard]){
			$where .=" AND `idcard_status`='1'";
		}
		
		if($paramer[height_status]){
			$where .=" AND height_status=".$paramer[height_status];
		}else{
			$where .=" AND height_status='0'";
		}
		
		if($paramer[rec]){
			$where .=" AND `rec`=1";
		}
	
		if($paramer[rec_resume]){
			$where .=" AND `rec_resume`=1";
		}
		
		if($paramer[work]){
			$show=$db->select_all("resume_show","1 group by eid","`eid`");
			if(is_array($show)){
				foreach($show as $v){
					$eid[]=$v['eid'];
				}
			}
			$where .=" AND id in (".@implode(",",$eid).")";
		}
		
		if($paramer[tag]){
		    $tagname=$userclass_name[$paramer[tag]];
			$tag=$db->select_all("resume","`def_job`>0 and `r_status`<>2 and `status`=1 and FIND_IN_SET('".$tagname."',`tag`)","`def_job`");
			if(is_array($tag)){
				foreach($tag as $v){
					$tagid[]=$v['def_job'];
				}
			}
			$where .=" AND id in (".@implode(",",$tagid).")";
		}
		
		if($paramer[cid]){
			$where .= " AND (cityid=$paramer[cid] or three_cityid=$paramer[cid])";
		}
		
		if($paramer[keyword]){

			$jobid = array();
			$where1[]="`name` LIKE '%$paramer[keyword]%'";
			$where1[]="`uname` LIKE '%$paramer[keyword]%'";
			foreach($job_name as $k=>$v){
				if(strpos($v,$paramer[keyword])!==false){
					$jobid[]=$k;
				}
			}
			if(!empty($jobid)){
				foreach($jobid as $value){
					$class[]="FIND_IN_SET('".$value."',job_classid)";
				}
				$where1[]=@implode(" or ",$class);
			}
			include_once  PLUS_PATH."/city.cache.php";
		    $cityid=array();
			foreach($city_name as $k=>$v){
				if(strpos($v,$paramer[keyword])!==false){
					$cityid[]=$k;
				}
			}
			if(!empty($cityid)){
				foreach($cityid as $value){
					$class[]= "(provinceid = '".$value."' or cityid = '".$value."')";
				}
				$where1[]=@implode(" or ",$class);
			}
			$where.=" AND (".@implode(" or ",$where1).")";
		}
		
		if($paramer[pic]=="0" || $paramer[pic]){
			$where .=" AND photo<>''";
			$where .=" AND phototype!=1";
		}
	
		if($paramer[name]=="0"){
			$where .=" AND uname<>''";
		}
		
		if($paramer[hy]=="0"){
			$where .=" AND hy<>''";
		}elseif($paramer[hy]!=""){
			$where .= " AND (`hy` IN (".$paramer['hy']."))";
		}
		
		$job_classid="";
		$joball=array();
		if($paramer[jobids]){
			$joball=explode(",",$paramer[jobids]);
			foreach(explode(",",$paramer[jobids]) as $v){
				if($job_type[$v]){
					$joball[]=@implode(",",$job_type[$v]);
				}
			}
			$job_classid=implode(",",$joball);
		}
		if($paramer[jobin]){
			$joball=explode(",",$paramer[jobin]);
			foreach(explode(",",$paramer[jobin]) as $v){
				if($job_type[$v]){
					$joball[]=@implode(",",$job_type[$v]);
				}
			}
			$job_classid=implode(",",$joball);
		}
		if($paramer[job1]){
			$joball=$job_type[$paramer[job1]];
			foreach($job_type[$paramer[job1]] as $v){
				$joball[]=@implode(",",$job_type[$v]);
			}
			$joball[] = $paramer[job1];
			$job_classid=@implode(",",$joball);
		}
		if($paramer[job1_son]){
			$joball=$job_type[$paramer[job1_son]];
			foreach($job_type[$paramer[job1_son]] as $v){
				$joball[]=@implode(",",$v);
			}
			$joball[] = $paramer[job1_son];
			$job_classid=@implode(",",$joball);
		}
		if(!empty($job_classid)){
			$classid=@explode(",",$job_classid);
		    $jobclass=array();
			foreach($classid as $value){
				$jobclass[]="FIND_IN_SET('".$value."',job_classid)";
			}
			$classid=@implode(" or ",$jobclass);
			$where .= " AND ($classid)";
		}
		if($paramer[job_post]){
			$where .=" AND FIND_IN_SET('".$paramer[job_post]."',job_classid)";
		}
		
		if($paramer[provinceid]){
			$where .= " AND provinceid = $paramer[provinceid]";
		}
		
		if($paramer[cityid]){
			$where .= " AND (`cityid` IN ($paramer[cityid]))";
		}
	
		if($paramer[three_cityid]){
			$where .= " AND (`three_cityid` IN ($paramer[three_cityid]))";
		}
		
		if($paramer[cityin]){
			$where .= " AND(provinceid IN ($paramer[cityin]) OR cityid IN ($paramer[cityin]) OR three_cityid IN ($paramer[cityin]))";
		}
	
		if($paramer[exp]){
			$where .=" AND exp=$paramer[exp]";
		}
	
		if($paramer[edu]){
			$where .=" AND edu=$paramer[edu]";
		}
		
		if($paramer[sex]){
			$where .=" AND sex=$paramer[sex]";
		}
		
		if($paramer[report]){
			$where .=" AND report=$paramer[report]";
		}
	
		if($paramer[salary]){
			$where .=" AND salary=$paramer[salary]";
		}
		if($paramer[minsalary]&&$paramer[maxsalary]){
			$where.= " AND ((`minsalary`<=".intval($paramer[minsalary])." and `maxsalary`>=".intval($paramer[minsalary]).") 
						or (`minsalary`<=".intval($paramer[maxsalary])." and `maxsalary`>=".intval($paramer[maxsalary])."))";
			
		}elseif($paramer[minsalary]&&!$paramer[maxsalary]){
			$where.= " AND ((`minsalary`<=".intval($paramer[minsalary])." and `maxsalary`>=".intval($paramer[minsalary]).") 
						or (`minsalary`>=".intval($paramer[minsalary])." and `maxsalary`>=".intval($paramer[minsalary]).")
						or (`minsalary`!=0 and  `maxsalary`=0))";
			
		}elseif(!$paramer[minsalary]&&$paramer[maxsalary]){
			$where.= " AND ((`minsalary`<=".intval($paramer[maxsalary])." and `maxsalary`>=".intval($paramer[maxsalary]).")
						or (`minsalary`<=".intval($paramer[maxsalary])." and `maxsalary`<=".intval($paramer[maxsalary]).")  
						or (`minsalary`<=".intval($paramer[maxsalary])." and maxsalary=0) 
						or (`minsalary`=0 and  `maxsalary`!=0)
						)";
			
		}
		
	
		if($paramer[type]){
			$where .= " AND type=$paramer[type]";
		}
	
		if($paramer[uptime]){
			if($paramer[uptime]==1){
				$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
    			$where.=" AND lastupdate>$beginToday";
    		}else{
    			$time=time();
				$uptime = $time-($paramer[uptime]*86400);
				$where.=" AND lastupdate>$uptime";
    		}
		}
	
		if($paramer[adtime]){
			$time=time();
			$adtime = $time-($paramer[adtime]*86400);
			$where.=" AND status_time>$adtime";
		}
		
     
		
		if($paramer[order] && $paramer[order]!="lastdate"){
			if($paramer[order]=='topdate'){
				$nowtime=time();
				$order = " ORDER BY if(topdate>$nowtime,topdate,lastupdate)";
			}else{
				$order = " ORDER BY `".str_replace("'","",$paramer[order])."`";
			}
		}else{
			$order = " ORDER BY lastupdate ";
		}
		
	
		$sort = $paramer[sort]?$paramer[sort]:'DESC';
		
		if($paramer[limit]){
			$limit=" LIMIT ".$paramer[limit];
		}

		
		if($paramer[where]){
			$where = $paramer[where];
		}
		if($paramer[ispage]){
			if($paramer["height_status"]){
				$limit = PageNav($paramer,$_GET,"resume_expect",$where,$Purl,"",$paramer[islt]?$paramer[islt]:"3",$_smarty_tpl);
			}else{
				
				$limit = PageNav($paramer,$_GET,"resume_expect",$where,$Purl,"",'0',$_smarty_tpl);
			}
		}
		$where.=$order.$sort;
 		$ulist=$db->select_all("resume_expect",$where.$limit,"*,uname as username");
        include(CONFIG_PATH."db.data.php");		
		if($ulist && is_array($ulist)){
			
			
			if($paramer['top']){
				$uids=$m_name=array();
				foreach($ulist as $k=>$v){
					$uids[]=$v[uid];
				}

				$member=$db->select_all($db_config[def]."member","`uid` in(".@implode(',',$uids).")","uid,username");
				foreach($member as $val){
					$m_name[$val[uid]]=$val['username'];
				}
			}
			foreach($ulist as $key=>$value){
				$uid[] = $value['uid'];
				$eid[] = $value['id'];
			}
			$eids = @implode(',',$eid);
			$uids = @implode(',',$uid);
            $resume=$db->select_all("resume","`uid` in(".$uids.")","uid,name,nametype,tag,sex,edu,exp,photo,phototype,birthday");
			if($paramer[topdate]){
				$noids=array();
			}	
			foreach($ulist as $k=>$v){
				if($paramer[topdate]){
					$noids[] = $v[id];
				}
				
				if($paramer[noid]=='1' && !empty($noids) && in_array($v['id'],$noids)){
					unset($ulist[$k]);
					continue;
				}
			    foreach($resume as $val){
			        if($v['uid']==$val['uid']){
			    		$ulist[$k]['edu_n']=$userclass_name[$val['edu']];
				        $ulist[$k]['exp_n']=$userclass_name[$val['exp']];
			            if($val['birthday']){
							$year = date("Y",strtotime($val['birthday']));
							$ulist[$k]['age'] =date("Y")-$year;
						}
						if($val['sex']==152){
							$val['sex']='1';
						}elseif ($val['sex']==153){
							$val['sex']='2';
						}
						$ulist[$k]['sex'] =$arr_data[sex][$val['sex']];
		                $ulist[$k]['phototype']=$val[phototype];
						if($config['user_pic']==1 || !$config['user_pic']){
		                if($val['photo'] && $val['phototype']!=1&&(file_exists(str_replace($config['sy_weburl'],APP_PATH,'.'.$val['photo']))||file_exists(str_replace($config['sy_weburl'],APP_PATH,$val['photo'])))){
            				$ulist[$k]['photo']=str_replace("./",$config['sy_weburl']."/",$val['photo']);
            			}else{
            				if($val['sex']==1){
            					$ulist[$k]['photo']=$config['sy_weburl']."/".$config['sy_member_icon'];
            				}else{
            					$ulist[$k]['photo']=$config['sy_weburl']."/".$config['sy_member_iconv'];
            				}
            			}
						}elseif($config['user_pic']==2){
							if($val['photo']&&(file_exists(str_replace($config['sy_weburl'],APP_PATH,'.'.$val['photo']))||file_exists(str_replace($config['sy_weburl'],APP_PATH,$val['photo'])))){
								$ulist[$k]['photo']=str_replace("./",$config['sy_weburl']."/",$val['photo']);
							}else{
								if($val['sex']==1){
									$ulist[$k]['photo']=$config['sy_weburl']."/".$config['sy_member_icon'];
								}else{
									$ulist[$k]['photo']=$config['sy_weburl']."/".$config['sy_member_iconv'];
								}
							}
						}elseif($config['user_pic']==3){
							if($val['sex']==1){
								$ulist[$k]['photo']=$config['sy_weburl']."/".$config['sy_member_icon'];
							}else{
								$ulist[$k]['photo']=$config['sy_weburl']."/".$config['sy_member_iconv'];
							}
						}
						if($val['tag']){
                            $ulist[$k]['tag']=explode(',',$val['tag']);
	                    }
                        $ulist[$k]['nametype']=$val[nametype];
                       
						if($config['user_name']==1 || !$config['user_name']){
						if($val['nametype']==3){
						    if($val['sex']==1){
						        $ulist[$k]['username_n'] = mb_substr($val['name'],0,1,'utf-8')."先生";
						    }else{
						        $ulist[$k]['username_n'] = mb_substr($val['name'],0,1,'utf-8')."女士";
						    }
						}elseif($val['nametype']==2){
						    $ulist[$k]['username_n'] = "NO.".$v['id'];
						}else{
							$ulist[$k]['username_n'] = $val['name'];
						}
						}elseif($config['user_name']==3){
							if($val['sex']==1){
								$ulist[$k]['username_n'] = mb_substr($val['name'],0,1,'utf-8')."先生";
							}else{
								$ulist[$k]['username_n'] = mb_substr($val['name'],0,1,'utf-8')."女士";
							}
						}elseif($config['user_name']==2){
							$ulist[$k]['username_n'] = "NO.".$v['id'];
						}elseif($config['user_name']==4){
							$ulist[$k]['username_n'] = $val['name'];
						}
                    }
                }
				
				
				$time=$v['lastupdate'];
				
				$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
				
				$beginYesterday=mktime(0,0,0,date('m'),date('d')-1,date('Y'));
			
				$week=strtotime(date("Y-m-d",strtotime("-1 week")));
				if($time>$week && $time<$beginYesterday){
					$ulist[$k]['time'] = "一周内";
				}elseif($time>$beginYesterday && $time<$beginToday){
					$ulist[$k]['time'] = "昨天";
				}elseif($time>$beginToday){
					$ulist[$k]['time'] = date("H:i",$v['lastupdate']);
					$ulist[$k]['redtime'] =1;
				}else{
					$ulist[$k]['time'] = date("Y-m-d",$v['lastupdate']);
				} 
				
				
				$ulist[$k]['user_jobstatus_n']=$userclass_name[$v['jobstatus']];
				$ulist[$k]['job_city_one']=$city_name[$v['provinceid']];
				$ulist[$k]['job_city_two']=$city_name[$v['cityid']];
				$ulist[$k]['job_city_three']=$city_name[$v['three_cityid']];
				if($v['minsalary']&&$v['maxsalary']){
					$ulist[$k]["salary_n"] = "￥".$v['minsalary']."-".$v['maxsalary'];    
                }else if($v['minsalary']){
                    $ulist[$k]["salary_n"] = "￥".$v['minsalary']."以上";  
                }else{
    				$ulist[$k]["salary_n"] = "面议";
    			}
				$ulist[$k]['report_n']=$userclass_name[$v['report']];
				$ulist[$k]['type_n']=$userclass_name[$v['type']];
				$ulist[$k]['lastupdate']=date("Y-m-d",$v['lastupdate']);
					
				$ulist[$k]['user_url']=Url("resume",array("c"=>"show","id"=>$v['id']),"1");
				$ulist[$k]["hy_info"]=$industry_name[$v['hy']];
				if($paramer['top']){
					$ulist[$k]['m_name']=$m_name[$v['uid']];
					$ulist[$k]['user_url']=Url("ask",array("c"=>"friend","a"=>"myquestion","uid"=>$v['uid']));
				}
				$kjob_classid=@explode(",",$v['job_classid']);
				$kjob_classid=array_unique($kjob_classid);	
				$jobname=array();
				if(is_array($kjob_classid)){
					foreach($kjob_classid as $val){
					    if($val!=''){
					        if($paramer['keyword']){
                               $jobname[]=str_replace($paramer['keyword'],"<font color=#FF6600 >".$paramer['keyword']."</font>",$job_name[$val]);
                            }else{
                               $jobname[]=$job_name[$val];
                            }
                        }
					}
				}
				$ulist[$k]['job_post']=@implode(",",$jobname);
				$ulist[$k]['expectjob']=$jobname;
				
				if($paramer['post_len']){
					$postname[$k]=@implode(",",$jobname);
					$ulist[$k]['job_post_n']=mb_substr($postname[$k],0,$paramer[post_len],"utf-8");
				}
			}
			foreach($ulist as $k=>$v){
               if($paramer['keyword']){
					$ulist[$k]['username_n']=str_replace($paramer['keyword'],"<font color=#FF6600 >".$paramer['keyword']."</font>",$v['username_n']);
					$ulist[$k]['job_post']=str_replace($paramer['keyword'],"<font color=#FF6600 >".$paramer['keyword']."</font>",$ulist[$k]['job_post']);
					$ulist[$k]['job_post_n']=str_replace($paramer['keyword'],"<font color=#FF6600 >".$paramer['keyword']."</font>",$ulist[$k]['job_post_n']);
					$ulist[$k]['job_city_one']=str_replace($paramer['keyword'],"<font color=#FF6600 >".$paramer['keyword']."</font>",$city_name[$v['provinceid']]);
					$ulist[$k]['job_city_two']=str_replace($paramer['keyword'],"<font color=#FF6600 >".$paramer['keyword']."</font>",$city_name[$v['cityid']]);
				}
            }
			if($paramer['keyword']!=""&&!empty($ulist)){
				addkeywords('5',$paramer['keyword']);
			}
		} ?>
<?php global $db,$db_config,$config;include PLUS_PATH.'/group.cache.php';$alist=array();$rs=null;$nids=null;eval('$paramer=array("item"=>"\'alist\'","t_len"=>"18","limit"=>"7","key"=>"\'key\'","name"=>"\'newlist1\'","nocache"=>"")
;');
		$ParamerArr = GetSmarty($paramer,$_GET,$_smarty_tpl);
		$paramer = $ParamerArr['arr'];
		$Purl =  $ParamerArr['purl'];
        if($paramer[cache]){
			$Purl="{{page}}.html";
		}
        global $ModuleName;
        if(!$Purl["m"]){
            $Purl["m"]=$ModuleName;
        }
		$where=1;
		if($config['did']){
			$where.=" and `did`='".$config['did']."'";
		}
		include PLUS_PATH."/group.cache.php"; 
		if(is_array($paramer)){
			if($paramer['nid']){
				$nid_s = @explode(',',$paramer[nid]);				
				foreach($nid_s as $v){
					if($group_type[$v]){
						$paramer[nid] = $paramer[nid].",".@implode(',',$group_type[$v]);
					}
				}
			}
			
			if($paramer['nid']!=""){
				$where .=" AND `nid` in ($paramer[nid])";
				$nids = @explode(',',$paramer['nid']);$paramer['nid']=$paramer['nid'];
			}else if($paramer['rec']!=""){
				$nids=array();if(is_array($group_rec)){
					foreach($group_rec as $key=>$value){
						if($key<=2){
							$nids[]=$value;
						}
					}
					$paramer[nid]=@implode(',',$nids);
				}
			}
			
			if($paramer['type']){
				$type = str_replace("\"","",$paramer[type]);
				$type_arr =	@explode(",",$type);
				if(is_array($type_arr) && !empty($type_arr)){
					foreach($type_arr as $key=>$value){
						$where .=" AND FIND_IN_SET('".$value."',`describe`)";
						if(count($nids)>0){
							$picwhere .=" AND FIND_IN_SET('".$value."',`describe`)";
						}
					}
				}
			}
			
			if($paramer['pic']!=""){
				$where .=" AND `newsphoto`<>''";
			}
			
			if($paramer['keyword']!=""){
				$where .=" AND `title` LIKE '%".$paramer[keyword]."%'";
			}
			
		
			if(intval($paramer['limit'])>0){
				$limit = intval($paramer['limit']);
				$limit = " limit ".$limit;
			}
			if($paramer['ispage']){
				if($Purl["m"]=="wap"){
					$limit = PageNav($paramer,$_GET,"news_base",$where,$Purl,"","6",$_smarty_tpl);
				}else{
					$limit = PageNav($paramer,$_GET,"news_base",$where,$Purl,"","5",$_smarty_tpl);
				}
			}
			
			if($paramer['order']!=""){
				$order = str_replace("'","",$paramer['order']);
				$where .=" ORDER BY $order";
			}else{
				$where .=" ORDER BY `datetime`";
			}
			
			if($paramer['sort']){
				$where.=" ".$paramer[sort];
			}else{
				$where.=" DESC";
			}
		}

		
		if(!intval($paramer['ispage']) && count($nids)>0){
			$nidArr = @explode(',',$paramer[nid]);
			$rsnids = '';
			if(is_array($group_type)){
				foreach($group_type as $key=>$value){
					if(in_array($key,$nidArr)){						
						if(is_array($value)){
							foreach($value as $v){
								$rsnids[$v] = $key;
								$nidArr[] = $v;
							}
						}
					}
				}							
			}
			$where = " `nid` IN (".@implode(',',$nidArr).")";
			if($config['did']){
				$where.=" and `did`='".$config['did']."'";
			}
			
			if($paramer['pic']){
				if(!$paramer['piclimit']){
					$piclimit = 1;
				}else{
					$piclimit = $paramer['piclimit'];
				}
				$db->query("set @f=0,@n=0");
				$query = $db->query("select * from (select id,title,color,datetime,description,newsphoto,@n:=if(@f=nid,@n:=@n+1,1) as aid,@f:=nid as nid from $db_config[def]news_base  WHERE ".$where." AND `newsphoto` <>''  order by nid asc,datetime desc) a where aid <=".$piclimit);
				while($rs = $db->fetch_array($query)){
					if($rsnids[$rs['nid']]){
						$rs['nid'] = $rsnids[$rs['nid']];
					}
					
					if(intval($paramer[t_len])>0){

						$len = intval($paramer[t_len]);
						$rs[title] = mb_substr($rs[title],0,$len,"utf-8");
					}
					if($rs[color]){
						$rs[title] = "<font color='".$rs[color]."'>".$rs[title]."</font>";
					}
					
					if(intval($paramer[d_len])>0){
						$len = intval($paramer[d_len]);
						$rs[description] = mb_substr($rs[description],0,$len,"utf-8");
					}
					$rs['name'] = $group_name[$rs['nid']];

					
					if($config[sy_news_rewrite]=="2"){
						$rs["url"]=$config['sy_weburl']."/news/".date("Ymd",$rs["datetime"])."/".$rs[id].".html";
					}else{
						$rs["url"] = Url("article",array("c"=>"show","id"=>$rs[id]),"1");
					}
					if(mb_substr($rs[newsphoto],0,4)=="http"){
						$rs["picurl"]=$rs[newsphoto];
					}else{
						$rs["picurl"] = $config['sy_weburl']."/".$rs[newsphoto];
					}
					$rs[time]=date("Y-m-d",$rs[datetime]);
					$rs['datetime']=date("m-d",$rs[datetime]);
					if(count($alist[$rs['nid']]['pic'])<$piclimit){
					    $alist[$rs['nid']]['pic'][] = $rs;
					}
				
				}
			}
			
            $db->query("set @f=0,@n=0");
            $query = $db->query("select * from (select id,title,datetime,color,description,newsphoto,@n:=if(@f=nid,@n:=@n+1,1) as aid,@f:=nid as nid from $db_config[def]news_base  WHERE ".$where." order by nid asc,datetime desc) a where aid <=$paramer[limit]");

            while($rs = $db->fetch_array($query)){
				if($rsnids[$rs['nid']]){
						$rs['nid'] = $rsnids[$rs['nid']];
					}
               
                if(intval($paramer[t_len])>0){

					$len = intval($paramer[t_len]);
					$rs[title] = mb_substr($rs[title],0,$len,"utf-8");
				}
				if($rs[color]){
					$rs[title] = "<font color='".$rs[color]."'>".$rs[title]."</font>";
				}
               
                if(intval($paramer[d_len])>0){
                    $len = intval($paramer[d_len]);
                    $rs[description] = mb_substr($rs[description],0,$len,"utf-8");
                }
               
                $rs['name'] = $group_name[$rs['nid']];
               
                if($config[sy_news_rewrite]=="2"){
                    $rs["url"]=$config['sy_weburl']."/news/".date("Ymd",$rs["datetime"])."/".$rs[id].".html";
                }else{
                    $rs["url"] = Url("article",array("c"=>"show","id"=>$rs[id]),"1");
                }
				if(mb_substr($rs[newsphoto],0,4)=="http"){
                    $rs["picurl"]=$rs[newsphoto];
                }else{
                    $rs["picurl"] = $config['sy_weburl']."/".$rs[newsphoto];
                }
                $rs[time]=date("Y-m-d",$rs[datetime]);
                $rs[datetime]=date("m-d",$rs[datetime]);
				if(count($alist[$rs['nid']]['arclist'])<$paramer[limit]){
					$alist[$rs['nid']]['arclist'][] = $rs;
				}
                
            }
		}else{
			$query = $db->query("SELECT * FROM `$db_config[def]news_base` WHERE ".$where.$limit);
			while($rs = $db->fetch_array($query)){
				
				if(intval($paramer[t_len])>0){
					$len = intval($paramer[t_len]);
					$rs[title] = mb_substr($rs[title],0,$len,"utf-8");
				}
				if($rs[color]){
					$rs[title] = "<font color='".$rs[color]."'>".$rs[title]."</font>";
				}
               
                if(intval($paramer[d_len])>0){
                    $len = intval($paramer[d_len]);
                    $rs[description] = mb_substr($rs[description],0,$len,"utf-8");
                }
               
                $rs['name'] = $group_name[$rs['nid']];
             
                if($config[sy_news_rewrite]=="2"){
                    $rs["url"]=$config['sy_weburl']."/news/".date("Ymd",$rs["datetime"])."/".$rs[id].".html";
                }else{
                    $rs["url"] = Url("article",array("c"=>"show","id"=>$rs[id]),"1");
                }
				if(mb_substr($rs[newsphoto],0,4)=="http"){
                    $rs["picurl"]=$rs[newsphoto];
                }else{
                    $rs["picurl"] = $config['sy_weburl']."/".$rs[newsphoto];
                }
                $rs[time]=date("Y-m-d",$rs[datetime]);
                $rs[datetime]=date("m-d",$rs[datetime]);
                $alist[] = $rs;
            }
		} ?>
<?php global $db,$db_config,$config;include PLUS_PATH.'/group.cache.php';$plist=array();$rs=null;$nids=null;eval('$paramer=array("pic"=>"1","t_len"=>"20","limit"=>"4","item"=>"\'plist\'","key"=>"\'pkey\'","nocache"=>"")
;');
		$ParamerArr = GetSmarty($paramer,$_GET,$_smarty_tpl);
		$paramer = $ParamerArr['arr'];
		$Purl =  $ParamerArr['purl'];
        if($paramer[cache]){
			$Purl="{{page}}.html";
		}
        global $ModuleName;
        if(!$Purl["m"]){
            $Purl["m"]=$ModuleName;
        }
		$where=1;
		if($config['did']){
			$where.=" and `did`='".$config['did']."'";
		}
		include PLUS_PATH."/group.cache.php"; 
		if(is_array($paramer)){
			if($paramer['nid']){
				$nid_s = @explode(',',$paramer[nid]);				
				foreach($nid_s as $v){
					if($group_type[$v]){
						$paramer[nid] = $paramer[nid].",".@implode(',',$group_type[$v]);
					}
				}
			}
			
			if($paramer['nid']!=""){
				$where .=" AND `nid` in ($paramer[nid])";
				$nids = @explode(',',$paramer['nid']);$paramer['nid']=$paramer['nid'];
			}else if($paramer['rec']!=""){
				$nids=array();if(is_array($group_rec)){
					foreach($group_rec as $key=>$value){
						if($key<=2){
							$nids[]=$value;
						}
					}
					$paramer[nid]=@implode(',',$nids);
				}
			}
			
			if($paramer['type']){
				$type = str_replace("\"","",$paramer[type]);
				$type_arr =	@explode(",",$type);
				if(is_array($type_arr) && !empty($type_arr)){
					foreach($type_arr as $key=>$value){
						$where .=" AND FIND_IN_SET('".$value."',`describe`)";
						if(count($nids)>0){
							$picwhere .=" AND FIND_IN_SET('".$value."',`describe`)";
						}
					}
				}
			}
			
			if($paramer['pic']!=""){
				$where .=" AND `newsphoto`<>''";
			}
			
			if($paramer['keyword']!=""){
				$where .=" AND `title` LIKE '%".$paramer[keyword]."%'";
			}
			
		
			if(intval($paramer['limit'])>0){
				$limit = intval($paramer['limit']);
				$limit = " limit ".$limit;
			}
			if($paramer['ispage']){
				if($Purl["m"]=="wap"){
					$limit = PageNav($paramer,$_GET,"news_base",$where,$Purl,"","6",$_smarty_tpl);
				}else{
					$limit = PageNav($paramer,$_GET,"news_base",$where,$Purl,"","5",$_smarty_tpl);
				}
			}
			
			if($paramer['order']!=""){
				$order = str_replace("'","",$paramer['order']);
				$where .=" ORDER BY $order";
			}else{
				$where .=" ORDER BY `datetime`";
			}
			
			if($paramer['sort']){
				$where.=" ".$paramer[sort];
			}else{
				$where.=" DESC";
			}
		}

		
		if(!intval($paramer['ispage']) && count($nids)>0){
			$nidArr = @explode(',',$paramer[nid]);
			$rsnids = '';
			if(is_array($group_type)){
				foreach($group_type as $key=>$value){
					if(in_array($key,$nidArr)){						
						if(is_array($value)){
							foreach($value as $v){
								$rsnids[$v] = $key;
								$nidArr[] = $v;
							}
						}
					}
				}							
			}
			$where = " `nid` IN (".@implode(',',$nidArr).")";
			if($config['did']){
				$where.=" and `did`='".$config['did']."'";
			}
			
			if($paramer['pic']){
				if(!$paramer['piclimit']){
					$piclimit = 1;
				}else{
					$piclimit = $paramer['piclimit'];
				}
				$db->query("set @f=0,@n=0");
				$query = $db->query("select * from (select id,title,color,datetime,description,newsphoto,@n:=if(@f=nid,@n:=@n+1,1) as aid,@f:=nid as nid from $db_config[def]news_base  WHERE ".$where." AND `newsphoto` <>''  order by nid asc,datetime desc) a where aid <=".$piclimit);
				while($rs = $db->fetch_array($query)){
					if($rsnids[$rs['nid']]){
						$rs['nid'] = $rsnids[$rs['nid']];
					}
					
					if(intval($paramer[t_len])>0){

						$len = intval($paramer[t_len]);
						$rs[title] = mb_substr($rs[title],0,$len,"utf-8");
					}
					if($rs[color]){
						$rs[title] = "<font color='".$rs[color]."'>".$rs[title]."</font>";
					}
					
					if(intval($paramer[d_len])>0){
						$len = intval($paramer[d_len]);
						$rs[description] = mb_substr($rs[description],0,$len,"utf-8");
					}
					$rs['name'] = $group_name[$rs['nid']];

					
					if($config[sy_news_rewrite]=="2"){
						$rs["url"]=$config['sy_weburl']."/news/".date("Ymd",$rs["datetime"])."/".$rs[id].".html";
					}else{
						$rs["url"] = Url("article",array("c"=>"show","id"=>$rs[id]),"1");
					}
					if(mb_substr($rs[newsphoto],0,4)=="http"){
						$rs["picurl"]=$rs[newsphoto];
					}else{
						$rs["picurl"] = $config['sy_weburl']."/".$rs[newsphoto];
					}
					$rs[time]=date("Y-m-d",$rs[datetime]);
					$rs['datetime']=date("m-d",$rs[datetime]);
					if(count($plist[$rs['nid']]['pic'])<$piclimit){
					    $plist[$rs['nid']]['pic'][] = $rs;
					}
				
				}
			}
			
            $db->query("set @f=0,@n=0");
            $query = $db->query("select * from (select id,title,datetime,color,description,newsphoto,@n:=if(@f=nid,@n:=@n+1,1) as aid,@f:=nid as nid from $db_config[def]news_base  WHERE ".$where." order by nid asc,datetime desc) a where aid <=$paramer[limit]");

            while($rs = $db->fetch_array($query)){
				if($rsnids[$rs['nid']]){
						$rs['nid'] = $rsnids[$rs['nid']];
					}
               
                if(intval($paramer[t_len])>0){

					$len = intval($paramer[t_len]);
					$rs[title] = mb_substr($rs[title],0,$len,"utf-8");
				}
				if($rs[color]){
					$rs[title] = "<font color='".$rs[color]."'>".$rs[title]."</font>";
				}
               
                if(intval($paramer[d_len])>0){
                    $len = intval($paramer[d_len]);
                    $rs[description] = mb_substr($rs[description],0,$len,"utf-8");
                }
               
                $rs['name'] = $group_name[$rs['nid']];
               
                if($config[sy_news_rewrite]=="2"){
                    $rs["url"]=$config['sy_weburl']."/news/".date("Ymd",$rs["datetime"])."/".$rs[id].".html";
                }else{
                    $rs["url"] = Url("article",array("c"=>"show","id"=>$rs[id]),"1");
                }
				if(mb_substr($rs[newsphoto],0,4)=="http"){
                    $rs["picurl"]=$rs[newsphoto];
                }else{
                    $rs["picurl"] = $config['sy_weburl']."/".$rs[newsphoto];
                }
                $rs[time]=date("Y-m-d",$rs[datetime]);
                $rs[datetime]=date("m-d",$rs[datetime]);
				if(count($plist[$rs['nid']]['arclist'])<$paramer[limit]){
					$plist[$rs['nid']]['arclist'][] = $rs;
				}
                
            }
		}else{
			$query = $db->query("SELECT * FROM `$db_config[def]news_base` WHERE ".$where.$limit);
			while($rs = $db->fetch_array($query)){
				
				if(intval($paramer[t_len])>0){
					$len = intval($paramer[t_len]);
					$rs[title] = mb_substr($rs[title],0,$len,"utf-8");
				}
				if($rs[color]){
					$rs[title] = "<font color='".$rs[color]."'>".$rs[title]."</font>";
				}
               
                if(intval($paramer[d_len])>0){
                    $len = intval($paramer[d_len]);
                    $rs[description] = mb_substr($rs[description],0,$len,"utf-8");
                }
               
                $rs['name'] = $group_name[$rs['nid']];
             
                if($config[sy_news_rewrite]=="2"){
                    $rs["url"]=$config['sy_weburl']."/news/".date("Ymd",$rs["datetime"])."/".$rs[id].".html";
                }else{
                    $rs["url"] = Url("article",array("c"=>"show","id"=>$rs[id]),"1");
                }
				if(mb_substr($rs[newsphoto],0,4)=="http"){
                    $rs["picurl"]=$rs[newsphoto];
                }else{
                    $rs["picurl"] = $config['sy_weburl']."/".$rs[newsphoto];
                }
                $rs[time]=date("Y-m-d",$rs[datetime]);
                $rs[datetime]=date("m-d",$rs[datetime]);
                $plist[] = $rs;
            }
		} ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<title><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</title>
<meta name="keywords" content="<?php echo $_smarty_tpl->tpl_vars['keywords']->value;?>
" />
<meta name="description" content="<?php echo $_smarty_tpl->tpl_vars['description']->value;?>
" />
<link href="<?php echo $_smarty_tpl->tpl_vars['style']->value;?>
/style/index.css?v=<?php echo $_smarty_tpl->tpl_vars['config']->value['cachecode'];?>
" rel="stylesheet" type="text/css" />
<link href="<?php echo $_smarty_tpl->tpl_vars['style']->value;?>
/style/css.css?v=<?php echo $_smarty_tpl->tpl_vars['config']->value['cachecode'];?>
" rel="stylesheet" type="text/css" />
<link href="<?php echo $_smarty_tpl->tpl_vars['config']->value['sy_weburl'];?>
/js/layui/css/layui.css?v=<?php echo $_smarty_tpl->tpl_vars['config']->value['cachecode'];?>
" rel="stylesheet" type="text/css" />
<?php if ($_smarty_tpl->tpl_vars['ishtml']->value) {?>
<?php echo '<script'; ?>
 src="<?php echo smarty_function_url(array('m'=>'ajax','c'=>'wjump'),$_smarty_tpl);?>
" language="javascript"><?php echo '</script'; ?>
>
<?php }?>
</head>
<body style="background:#f5f5f5">
<?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['tplstyle']->value)."/header.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<div class="w1000">
<div class="index_banner none" id="js_ads_banner_top">
		<?php  $_smarty_tpl->tpl_vars['adlist_73'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['adlist_73']->_loop = false;
$AdArr=array();$paramer=array();
			include(PLUS_PATH.'/pimg_cache.php');$add_arr = $ad_label[73];if(is_array($add_arr) && !empty($add_arr)){
				$i=0;$limit = 1;$length = 0;
				foreach($add_arr as $key=>$value){
					if(($value['did']==$config['did'] ||$value['did']=='0')&&$value['start']<time()&&$value['end']>time()){
						if($i>0 && $limit==$i){
							break;
						}
						if($length>0){
							$value['name'] = mb_substr($value['name'],0,$length);
						}
						if($paramer['type']!=""){
							if($paramer['type'] == $value['type']){
								$AdArr[] = $value;
							}
						}else{
							$AdArr[] = $value;
						}
						$i++;
					}
				}
			}$AdArr = $AdArr; if (!is_array($AdArr) && !is_object($AdArr)) { settype($AdArr, 'array');}
foreach ($AdArr as $_smarty_tpl->tpl_vars['adlist_73']->key => $_smarty_tpl->tpl_vars['adlist_73']->value) {
$_smarty_tpl->tpl_vars['adlist_73']->_loop = true;
?>
		<?php echo $_smarty_tpl->tpl_vars['adlist_73']->value['html'];?>

		<?php } ?>
	</div>
	<div class="index_banner" id="js_ads_banner_top_slide">
		<?php  $_smarty_tpl->tpl_vars['adlist_72'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['adlist_72']->_loop = false;
$AdArr=array();$paramer=array();
			include(PLUS_PATH.'/pimg_cache.php');$add_arr = $ad_label[72];if(is_array($add_arr) && !empty($add_arr)){
				$i=0;$limit = 1;$length = 0;
				foreach($add_arr as $key=>$value){
					if(($value['did']==$config['did'] ||$value['did']=='0')&&$value['start']<time()&&$value['end']>time()){
						if($i>0 && $limit==$i){
							break;
						}
						if($length>0){
							$value['name'] = mb_substr($value['name'],0,$length);
						}
						if($paramer['type']!=""){
							if($paramer['type'] == $value['type']){
								$AdArr[] = $value;
							}
						}else{
							$AdArr[] = $value;
						}
						$i++;
					}
				}
			}$AdArr = $AdArr; if (!is_array($AdArr) && !is_object($AdArr)) { settype($AdArr, 'array');}
foreach ($AdArr as $_smarty_tpl->tpl_vars['adlist_72']->key => $_smarty_tpl->tpl_vars['adlist_72']->value) {
$_smarty_tpl->tpl_vars['adlist_72']->_loop = true;
?>
		<?php echo $_smarty_tpl->tpl_vars['adlist_72']->value['html'];?>

		<?php } ?>
	</div>
<div class="hp_kk fl">
  <div class="hp_login fl">
    <div class="hp_login_tit"><i class="hp_login_tit_icon"></i>会员登录</div>
    <div class="hp_login_hy"> <i class="hp_login_hy_icon fl"></i>
      <input class="hp_login_hy_but fl" type="text" id="username" name="username" placeholder="邮箱/手机号/用户名"/>
    </div>
    <div class="hp_login_hy"> <i class="hp_login_mm_icon fl"></i>
      <input class="hp_login_mm_but fl" type="password" id="password" name="password" placeholder="请输入密码"/>
    </div>
    <div class="hp_login_box">
      <div class="hp_login_box_ft fl">
        <input type="checkbox"/>
        <span class="hp_login_box_r">下次自动登录</span> </div>
      <div class="hp_login_box_rt fr"><a href="<?php echo smarty_function_url(array('m'=>'forgetpw'),$_smarty_tpl);?>
">忘记密码？</a></div>
    </div>
    <div class="hp_login_lg">
      <input class="hp_login_lg_but" type="submit" value="登录" onclick="check_login('<?php echo smarty_function_url(array('m'=>'login','c'=>'loginsave'),$_smarty_tpl);?>
');"/>
    </div>
    <div class="hp_login_rg"> <a href="<?php echo smarty_function_url(array('m'=>'register'),$_smarty_tpl);?>
">注册账号</a> </div>
  </div>
  
   <div class="hp_t_cont">
  <div class="hp_banner fl" id="ban">
    <div class="layui-carousel" id="test1" lay-filter="test1">
      <div carousel-item=""> <?php  $_smarty_tpl->tpl_vars["lunbo"] = new Smarty_Variable; $_smarty_tpl->tpl_vars["lunbo"]->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
$AdArr=array();$paramer=array();
			include(PLUS_PATH.'/pimg_cache.php');$add_arr = $ad_label[3];if(is_array($add_arr) && !empty($add_arr)){
				$i=0;$limit = 0;$length = 0;
				foreach($add_arr as $key=>$value){
					if(($value['did']==$config['did'] ||$value['did']=='0')&&$value['start']<time()&&$value['end']>time()){
						if($i>0 && $limit==$i){
							break;
						}
						if($length>0){
							$value['name'] = mb_substr($value['name'],0,$length);
						}
						if($paramer['type']!=""){
							if($paramer['type'] == $value['type']){
								$AdArr[] = $value;
							}
						}else{
							$AdArr[] = $value;
						}
						$i++;
					}
				}
			}$AdArr = $AdArr; if (!is_array($AdArr) && !is_object($AdArr)) { settype($AdArr, 'array');}
foreach ($AdArr as $_smarty_tpl->tpl_vars["lunbo"]->key => $_smarty_tpl->tpl_vars["lunbo"]->value) {
$_smarty_tpl->tpl_vars["lunbo"]->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars["lunbo"]->key;
?>
        <div><?php echo $_smarty_tpl->tpl_vars['lunbo']->value['html'];?>
</div>
        <?php } ?> </div>
    </div>
  </div>
  
<div class="hp_hotjob fl">
  <div class="hp_hotjob_b">
      <?php  $_smarty_tpl->tpl_vars['keylist'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['keylist']->_loop = false;
global $config;eval('$paramer=array("limit"=>"28","recom"=>"1","type"=>"3","item"=>"\'keylist\'","nocache"=>"")
;');$list=array();
        
        $ParamerArr = GetSmarty($paramer,$_GET,$_smarty_tpl);
		$paramer = $ParamerArr[arr];
		
		if($paramer[recom]){
			$tuijian = 1;
		}
		
		if($paramer[type]){
			$type = $paramer[type];
		}
		
		if($paramer[limit]){
			$limit=$paramer[limit];
		}else{
			$limit=5;
		}
		include PLUS_PATH."/keyword.cache.php";
		if($paramer[iswap]){
			$wap = "/wap";
		}else{
			$index =1;
		}
		if(is_array($keyword)){
			if($paramer[iswap]){
				$i=0;
				foreach($keyword as $k=>$v){
					if($tuijian && $v[tuijian]!=1){
						continue;
					}
					if($type && $v[type]!=$type){
						continue;
					}

					$i++;
					if($v[type]=="1"){
						$v[url] = Url("wap",array("c"=>"once","keyword"=>$v['key_name']));
						$v[type_name]='店铺招聘';
					}elseif($v['type']=="13"){
						$v['url'] = Url("wap",array("c"=>"tiny","keyword"=>$v['key_name']));
						$v['type_name']='普工简历';
					}elseif($v[type]=="3"){
						$v[url] = Url("wap",array("c"=>"job","keyword"=>$v['key_name']));
						$v[type_name]='职位';
					}elseif($v['type']=="4"){
						$v['url'] = Url("wap",array("c"=>"company","keyword"=>$v['key_name']));
						$v['type_name']='公司';
					}elseif($v['type']=="5"){
						$v['url'] = Url("wap",array("c"=>"resume","keyword"=>$v['key_name']));
						$v['type_name']='人才';
					}
					$v['key_title']=$v['key_name'];
					if($v['color']){
						$v['key_name']="<font color=\"".$v['color']."\">".$v['key_name']."</font>";
					}
					$list[] = $v;
					if($i==$limit){
						break;
					}
				}
			}else{
				$i=0;
				foreach($keyword as $k=>$v){
					if($tuijian && $v['tuijian']!=1){
						continue;
					}
					if($type && $v['type']!=$type){
						continue;
					}
					$i++;
					if($v['type']=="1"){
						$v['url'] = Url("once",array("keyword"=>$v['key_name']));
						$v['type_name']='店铺招聘';
					}elseif($v['type']=="2"){
						$v['url'] = Url("part",array("keyword"=>$v['key_name']));
						$v['type_name']='兼职';
					}elseif($v['type']=="13"){
						$v['url'] = Url("tiny",array("keyword"=>$v['key_name']));
						$v['type_name']='普工简历';
					}elseif($v['type']=="3"){
						$v['url'] = Url("job",array("c"=>"search","keyword"=>$v['key_name']));
						$v['type_name']='职位';
					}elseif($v['type']=="4"){
						$v['url'] = Url("company",array("keyword"=>$v['key_name']));
						$v['type_name']='公司';
					}elseif($v['type']=="5"){
						$v['url'] = Url("resume",array("c"=>"search","keyword"=>$v['key_name']));
						$v['type_name']='人才';
					}elseif($v['type']=="6"){
						$v['url'] = Url("lietou",array("c"=>"service","keyword"=>$v['key_name']));
						$v['type_name']='猎头';
					}elseif($v['type']=="7"){
						$v['url'] = Url("lietou",array("c"=>"post","keyword"=>$v['key_name']));
						$v['type_name']='猎头职位';
					}else if($v['type']=="12"){
						$v['url'] = Url("ask",array("c"=>"search","keyword"=>$v['key_name']));
						$v['type_name']='问答';
					}
					$v['key_title']=$v['key_name'];
					if($v['color']){
						$v['key_name']="<font color=\"".$v['color']."\">".$v['key_name']."</font>";
					}

					$list[] = $v;
					if($i==$limit){
						break;
					}
				}
			}
		}$list = $list; if (!is_array($list) && !is_object($list)) { settype($list, 'array');}
foreach ($list as $_smarty_tpl->tpl_vars['keylist']->key => $_smarty_tpl->tpl_vars['keylist']->value) {
$_smarty_tpl->tpl_vars['keylist']->_loop = true;
?>
     <a href="<?php echo smarty_function_listurl(array('type'=>'keyword','v'=>$_smarty_tpl->tpl_vars['keylist']->value['key_title']),$_smarty_tpl);?>
" class="jos_tag_a" title="<?php echo $_smarty_tpl->tpl_vars['keylist']->value['key_title'];?>
"><?php echo $_smarty_tpl->tpl_vars['keylist']->value['key_name'];?>
</a>      <?php } ?>
  </div>
</div>
    </div>
  
  <div class="hp_zp fr">
  <div class="wantedjob">
   
    <div class="wantedjob_tit"><span class="wantedjob_tit_s">紧急招聘</span><a href="<?php echo smarty_function_listurl(array('type'=>'tp','v'=>1),$_smarty_tpl);?>
" target="_blank" class="wantedjob_titmore">更多>></a></div>
     <div class="wantedjob_cont">
    <ul class="wantedjob_cont_list">
    <?php  $_smarty_tpl->tpl_vars['urgent_list'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['urgent_list']->_loop = false;
$urgent_list = $urgent_list; if (!is_array($urgent_list) && !is_object($urgent_list)) { settype($urgent_list, 'array');}
foreach ($urgent_list as $_smarty_tpl->tpl_vars['urgent_list']->key => $_smarty_tpl->tpl_vars['urgent_list']->value) {
$_smarty_tpl->tpl_vars['urgent_list']->_loop = true;
?>
     <li>
   
    <div class="wantedjob_name"> <i class="wantedjob_icon"></i><a href="<?php echo $_smarty_tpl->tpl_vars['urgent_list']->value['job_url'];?>
" target="_blank"><?php echo $_smarty_tpl->tpl_vars['urgent_list']->value['name'];?>
</a> <img src="<?php echo $_smarty_tpl->tpl_vars['style']->value;?>
/images/jobjp.png" alt="<?php echo $_smarty_tpl->tpl_vars['urgent_list']->value['com_name'];?>
"></div>
   <div class="wantedjob_info"><i class="hp_urg_job_ct_r"><?php if ($_smarty_tpl->tpl_vars['urgent_list']->value['job_salary']!='面议') {?>￥<?php }
echo $_smarty_tpl->tpl_vars['urgent_list']->value['job_salary'];?>
</i> <?php echo $_smarty_tpl->tpl_vars['urgent_list']->value['job_exp'];?>
经验<i class="index_job_line">|</i><?php echo $_smarty_tpl->tpl_vars['urgent_list']->value['job_edu'];?>
学历</div>
   <div class="wantedjob_comname"><a class="" href="<?php echo $_smarty_tpl->tpl_vars['urgent_list']->value['com_url'];?>
" target="_blank"><?php echo $_smarty_tpl->tpl_vars['urgent_list']->value['com_name'];?>
</a></div>
    </li>
    <?php } ?>
    
    </ul>
    
    
   </div>      </div>
   <div class="hp_web_cont">      
   <div class="hp_web">
      <div class="hp_web_top">网站公告<a href="<?php echo smarty_function_url(array('m'=>'announcement'),$_smarty_tpl);?>
" class="g_more">更多>></a></div>
      <div class="hp_web_ct">
        <ul>
          <?php  $_smarty_tpl->tpl_vars['announcementlist'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['announcementlist']->_loop = false;
$announcementlist = $announcementlist; if (!is_array($announcementlist) && !is_object($announcementlist)) { settype($announcementlist, 'array');}
foreach ($announcementlist as $_smarty_tpl->tpl_vars['announcementlist']->key => $_smarty_tpl->tpl_vars['announcementlist']->value) {
$_smarty_tpl->tpl_vars['announcementlist']->_loop = true;
?>
          <li><a href="<?php echo $_smarty_tpl->tpl_vars['announcementlist']->value['url'];?>
"><?php echo $_smarty_tpl->tpl_vars['announcementlist']->value['title_n'];?>
</a></li>
          <?php } ?>
        </ul>
      </div>
 </div>
  </div>
  </div>
</div>


<!-- 名企-->
<div class="hp_title fl">
  <div class="hp_title_ft fl"><i class="hp_title_icon hp_title_icon_mq"></i>名企招聘</div>
 <div class="index_lookmore"><a href="<?php echo smarty_function_url(array('m'=>'company','rec'=>1),$_smarty_tpl);?>
" target="_blank">查看更多</a></div>
</div>
<section>
  <div class="yunFamousenterprises fl">
    <div class="yunFamousenterprises_box fl">
      <div class="Famous_recruitment_cont">
        <div class="index_left15560">
          <div id="mainids"> <?php  $_smarty_tpl->tpl_vars['hotjoblist'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['hotjoblist']->_loop = false;
$hotjoblist = $hotjoblist; if (!is_array($hotjoblist) && !is_object($hotjoblist)) { settype($hotjoblist, 'array');}
foreach ($hotjoblist as $_smarty_tpl->tpl_vars['hotjoblist']->key => $_smarty_tpl->tpl_vars['hotjoblist']->value) {
$_smarty_tpl->tpl_vars['hotjoblist']->_loop = true;
?>
            <div class="tlogo">
              <ul>
                <li onmouseover="showDiv2(this)" onmouseout="showDiv2(this)"> <a href="<?php echo $_smarty_tpl->tpl_vars['hotjoblist']->value['url'];?>
" target="_blank" class="tlogo_p_a"><img width="185" height="75" border="1" class="on lazy" src="<?php echo smarty_function_formatpicurl(array('path'=>$_smarty_tpl->tpl_vars['hotjoblist']->value['hot_pic'],'type'=>'comlogo'),$_smarty_tpl);?>
" onerror="showImgDelay(this,'<?php echo $_smarty_tpl->tpl_vars['config']->value['sy_weburl'];?>
/<?php echo $_smarty_tpl->tpl_vars['config']->value['sy_unit_icon'];?>
',2);" alt="<?php echo $_smarty_tpl->tpl_vars['hotjoblist']->value['username'];?>
"/></a>
                  <div class="yunFamousenterprises_comname"><?php echo $_smarty_tpl->tpl_vars['hotjoblist']->value['username'];?>
</div>
                  <div class="show">
                    <div class="area"><i class="area_icon"></i><?php echo $_smarty_tpl->tpl_vars['hotjoblist']->value['job'];?>
</div>
                  </div>
                </li>
              </ul>
            </div>
            <?php } ?> </div>
        </div>
      </div>
    </div>
  </div>
  
</section>
<section>
  <div class="hp_title fl">
    <div class="hp_title_ft fl"><i class="hp_title_icon hp_title_icon_sj"></i>赏金职位</div>
  <div class="index_lookmore"><a href="<?php echo smarty_function_url(array('m'=>'reward'),$_smarty_tpl);?>
" target="_blank" target="_blank" class="index_looksjmore">查看更多</a></div>
  </div>
  <div class="index_job_red">
    <ul class="index_job_red_list">
      <?php  $_smarty_tpl->tpl_vars['rlist'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['rlist']->_loop = false;
$rlist = $rlist; if (!is_array($rlist) && !is_object($rlist)) { settype($rlist, 'array');}
foreach ($rlist as $_smarty_tpl->tpl_vars['rlist']->key => $_smarty_tpl->tpl_vars['rlist']->value) {
$_smarty_tpl->tpl_vars['rlist']->_loop = true;
?>
      <li>
        <div class="index_job_red_momey">
          <div class="index_job_red_momey_n">￥<?php echo $_smarty_tpl->tpl_vars['rlist']->value['money'];?>
</div>
        </div>
        <div >
          <div class="reward_hb_list"> <i class="reward_hb_list_line"></i> <span class="reward_hb_fonttd">￥<?php echo $_smarty_tpl->tpl_vars['rlist']->value['sqmoney'];?>
</span>
            <div class="reward_hb_list_P">投递简历</div>
          </div>
          <div class="reward_hb_list"> <i class="reward_hb_list_line"></i> <span class="reward_hb_fontms">￥<?php echo $_smarty_tpl->tpl_vars['rlist']->value['invitemoney'];?>
</span>
            <div class="reward_hb_list_P">面试职位</div>
          </div>
          <div class="reward_hb_list"><span class="reward_hb_fontrz">￥<?php echo $_smarty_tpl->tpl_vars['rlist']->value['offermoney'];?>
</span>
            <div class="reward_hb_list_P">入职成功</div>
          </div>
          <a href="<?php echo $_smarty_tpl->tpl_vars['rlist']->value['job_url'];?>
" class="reward_hb_ls">领赏</a> </div>
        <div class="reward_hb_listjobname">
          <div class="reward_hb_listjobname_l"><a href="<?php echo $_smarty_tpl->tpl_vars['rlist']->value['job_url'];?>
"><?php echo $_smarty_tpl->tpl_vars['rlist']->value['name'];?>
</a></div>
          <div class="reward_hb_listjobinfo"> <?php if ($_smarty_tpl->tpl_vars['rlist']->value['job_salary']!='面议') {?>￥<?php }
echo $_smarty_tpl->tpl_vars['rlist']->value['job_salary'];?>
 <span class="index_job_line">|</span><?php echo mb_substr($_smarty_tpl->tpl_vars['rlist']->value['job_city_two'],0,4,"utf-8");?>
 <?php echo mb_substr($_smarty_tpl->tpl_vars['rlist']->value['job_city_three'],0,4,"utf-8");?>
<span class="index_job_line">|</span><?php echo $_smarty_tpl->tpl_vars['rlist']->value['job_exp'];?>
经验<span class="index_job_line">|</span><?php echo $_smarty_tpl->tpl_vars['rlist']->value['job_edu'];?>
学历</div>
        </div>
      </li>
      <?php } ?>
    </ul>
  </div>
  
</section>
<div class="hp_urg_job_l fl">
  <div class="hp_urg_job_l_1250 fl">
    <?php  $_smarty_tpl->tpl_vars['adlist_13'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['adlist_13']->_loop = false;
$AdArr=array();$paramer=array();
			include(PLUS_PATH.'/pimg_cache.php');$add_arr = $ad_label[13];if(is_array($add_arr) && !empty($add_arr)){
				$i=0;$limit = 5;$length = 0;
				foreach($add_arr as $key=>$value){
					if(($value['did']==$config['did'] ||$value['did']=='0')&&$value['start']<time()&&$value['end']>time()){
						if($i>0 && $limit==$i){
							break;
						}
						if($length>0){
							$value['name'] = mb_substr($value['name'],0,$length);
						}
						if($paramer['type']!=""){
							if($paramer['type'] == $value['type']){
								$AdArr[] = $value;
							}
						}else{
							$AdArr[] = $value;
						}
						$i++;
					}
				}
			}$AdArr = $AdArr; if (!is_array($AdArr) && !is_object($AdArr)) { settype($AdArr, 'array');}
foreach ($AdArr as $_smarty_tpl->tpl_vars['adlist_13']->key => $_smarty_tpl->tpl_vars['adlist_13']->value) {
$_smarty_tpl->tpl_vars['adlist_13']->_loop = true;
?>
    <?php echo $_smarty_tpl->tpl_vars['adlist_13']->value['html'];?>

    <?php } ?>
    <?php  $_smarty_tpl->tpl_vars['adlist_14'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['adlist_14']->_loop = false;
$AdArr=array();$paramer=array();
			include(PLUS_PATH.'/pimg_cache.php');$add_arr = $ad_label[14];if(is_array($add_arr) && !empty($add_arr)){
				$i=0;$limit = 6;$length = 0;
				foreach($add_arr as $key=>$value){
					if(($value['did']==$config['did'] ||$value['did']=='0')&&$value['start']<time()&&$value['end']>time()){
						if($i>0 && $limit==$i){
							break;
						}
						if($length>0){
							$value['name'] = mb_substr($value['name'],0,$length);
						}
						if($paramer['type']!=""){
							if($paramer['type'] == $value['type']){
								$AdArr[] = $value;
							}
						}else{
							$AdArr[] = $value;
						}
						$i++;
					}
				}
			}$AdArr = $AdArr; if (!is_array($AdArr) && !is_object($AdArr)) { settype($AdArr, 'array');}
foreach ($AdArr as $_smarty_tpl->tpl_vars['adlist_14']->key => $_smarty_tpl->tpl_vars['adlist_14']->value) {
$_smarty_tpl->tpl_vars['adlist_14']->_loop = true;
?>
    <?php echo $_smarty_tpl->tpl_vars['adlist_14']->value['html'];?>

    <?php } ?>
    <?php  $_smarty_tpl->tpl_vars['adlist_15'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['adlist_15']->_loop = false;
$AdArr=array();$paramer=array();
			include(PLUS_PATH.'/pimg_cache.php');$add_arr = $ad_label[15];if(is_array($add_arr) && !empty($add_arr)){
				$i=0;$limit = 16;$length = 0;
				foreach($add_arr as $key=>$value){
					if(($value['did']==$config['did'] ||$value['did']=='0')&&$value['start']<time()&&$value['end']>time()){
						if($i>0 && $limit==$i){
							break;
						}
						if($length>0){
							$value['name'] = mb_substr($value['name'],0,$length);
						}
						if($paramer['type']!=""){
							if($paramer['type'] == $value['type']){
								$AdArr[] = $value;
							}
						}else{
							$AdArr[] = $value;
						}
						$i++;
					}
				}
			}$AdArr = $AdArr; if (!is_array($AdArr) && !is_object($AdArr)) { settype($AdArr, 'array');}
foreach ($AdArr as $_smarty_tpl->tpl_vars['adlist_15']->key => $_smarty_tpl->tpl_vars['adlist_15']->value) {
$_smarty_tpl->tpl_vars['adlist_15']->_loop = true;
?>
    <?php echo $_smarty_tpl->tpl_vars['adlist_15']->value['html'];?>

    <?php } ?> </div>
</div>


<div class="hp_title fl">
  <div class="hp_title_ft fl"><i class="hp_title_icon hp_title_icon_tj"></i>推荐职位</div> <div class="index_lookmore"><a href="<?php echo smarty_function_listurl(array('type'=>'tp','v'=>2),$_smarty_tpl);?>
" target="_blank" class="index_looktjmore">查看更多</a></div>
</div>
<div class="index_newjob fl">
  <ul>
    <?php  $_smarty_tpl->tpl_vars['rec_list'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['rec_list']->_loop = false;
$rec_list = $rec_list; if (!is_array($rec_list) && !is_object($rec_list)) { settype($rec_list, 'array');}
foreach ($rec_list as $_smarty_tpl->tpl_vars['rec_list']->key => $_smarty_tpl->tpl_vars['rec_list']->value) {
$_smarty_tpl->tpl_vars['rec_list']->_loop = true;
?>
    <li>
          <div class="index_newjob_name"><a href="<?php echo $_smarty_tpl->tpl_vars['rec_list']->value['job_url'];?>
"><?php echo $_smarty_tpl->tpl_vars['rec_list']->value['name_n'];?>
</a></div>
                 <div class="index_newjob_time">
               		 <?php if ($_smarty_tpl->tpl_vars['rec_list']->value['time']=='昨天'||$_smarty_tpl->tpl_vars['rec_list']->value['redtime']=='1') {?> <span style="color:red;"><?php echo $_smarty_tpl->tpl_vars['rec_list']->value['time'];?>
</span> <?php } else { ?>    <?php echo $_smarty_tpl->tpl_vars['rec_list']->value['time'];?>

        <?php }?>更新 
             
               </div>
          <div class="index_newjob_info">
          <span class="index_newjob_info_xz"><?php if ($_smarty_tpl->tpl_vars['rec_list']->value['job_salary']!='面议') {?>￥<?php }
echo $_smarty_tpl->tpl_vars['rec_list']->value['job_salary'];?>
</span><span class="index_job_line">|</span><?php echo mb_substr($_smarty_tpl->tpl_vars['rec_list']->value['job_city_two'],0,4,"utf-8");?>
 <?php echo mb_substr($_smarty_tpl->tpl_vars['rec_list']->value['job_city_three'],0,4,"utf-8");?>
<span class="index_job_line">|</span><?php echo $_smarty_tpl->tpl_vars['rec_list']->value['job_exp'];?>
经验<span class="index_job_line">|</span><?php echo $_smarty_tpl->tpl_vars['rec_list']->value['job_edu'];?>
学历
          </div>
          <div class="index_newjob_com">
                   <div class="index_newjob_comlogo"><img src="<?php echo $_smarty_tpl->tpl_vars['rec_list']->value['logo'];?>
" onerror="showImgDelay(this,'<?php echo $_smarty_tpl->tpl_vars['config']->value['sy_weburl'];?>
/<?php echo $_smarty_tpl->tpl_vars['config']->value['sy_unit_icon'];?>
',2);" width="48" height="48"/></div>
                 <div class="index_newjob_comname"> <a href="<?php echo $_smarty_tpl->tpl_vars['rec_list']->value['com_url'];?>
"><?php echo $_smarty_tpl->tpl_vars['rec_list']->value['com_n'];?>
</a> <?php if ($_smarty_tpl->tpl_vars['rec_list']->value['ratlogo']&&$_smarty_tpl->tpl_vars['rec_list']->value['ratlogo']!="0") {?><img src="<?php echo $_smarty_tpl->tpl_vars['config']->value['sy_weburl'];?>
/<?php echo $_smarty_tpl->tpl_vars['rec_list']->value['ratlogo'];?>
" title="<?php echo $_smarty_tpl->tpl_vars['rec_list']->value['ratname'];?>
"/><?php }?> 
        <?php if ($_smarty_tpl->tpl_vars['rec_list']->value['yyzz_status']==1) {?><img src="<?php echo $_smarty_tpl->tpl_vars['style']->value;?>
/images/disc_icon10.png" title="认证企业"/><?php }?> 
			 			  </div>
               <div class="index_newjob_cominfo"><?php echo $_smarty_tpl->tpl_vars['rec_list']->value['hy_n'];?>
</div>
                 </div>
   
         </li>
     <?php } ?>
  </ul>
  
</div>
<div class="hp_title fl">
  <div class="hp_title_ft fl"><i class="hp_title_icon hp_title_icon_job"></i>最新职位</div><div class="index_lookmore"><a href="<?php echo smarty_function_url(array('m'=>'job','c'=>'search'),$_smarty_tpl);?>
" target="_blank">查看更多</a></div>
</div>
<div class="index_newjob fl">
  <ul>
    <?php  $_smarty_tpl->tpl_vars['job_list'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['job_list']->_loop = false;
$job_list = $job_list; if (!is_array($job_list) && !is_object($job_list)) { settype($job_list, 'array');}
foreach ($job_list as $_smarty_tpl->tpl_vars['job_list']->key => $_smarty_tpl->tpl_vars['job_list']->value) {
$_smarty_tpl->tpl_vars['job_list']->_loop = true;
?>
    <li>
      <div class="index_newjob_name"><a href="<?php echo $_smarty_tpl->tpl_vars['job_list']->value['job_url'];?>
"><?php echo $_smarty_tpl->tpl_vars['job_list']->value['name_n'];?>
</a></div>
      <div class="index_newjob_time"> <?php if ($_smarty_tpl->tpl_vars['job_list']->value['time']=='昨天'||$_smarty_tpl->tpl_vars['job_list']->value['redtime']=='1') {?> <span style="color:red;"><?php echo $_smarty_tpl->tpl_vars['job_list']->value['time'];?>
</span> <?php } else { ?>
        <?php echo $_smarty_tpl->tpl_vars['job_list']->value['time'];?>

        <?php }?>更新 </div>
      <div class="index_newjob_info"> <span class="index_newjob_info_xz"><?php if ($_smarty_tpl->tpl_vars['job_list']->value['job_salary']!='面议') {?>￥<?php }
echo $_smarty_tpl->tpl_vars['job_list']->value['job_salary'];?>
</span><span class="index_job_line">|</span><?php echo mb_substr($_smarty_tpl->tpl_vars['job_list']->value['job_city_two'],0,4,"utf-8");?>
 <?php echo mb_substr($_smarty_tpl->tpl_vars['job_list']->value['job_city_three'],0,4,"utf-8");?>
<span class="index_job_line">|</span><?php echo $_smarty_tpl->tpl_vars['job_list']->value['job_exp'];?>
经验<span class="index_job_line">|</span><?php echo $_smarty_tpl->tpl_vars['job_list']->value['job_edu'];?>
学历 </div>
      <div class="index_newjob_com">
        <div class="index_newjob_comlogo"><img src="<?php echo $_smarty_tpl->tpl_vars['job_list']->value['logo'];?>
" onerror="showImgDelay(this,'<?php echo $_smarty_tpl->tpl_vars['config']->value['sy_weburl'];?>
/<?php echo $_smarty_tpl->tpl_vars['config']->value['sy_unit_icon'];?>
',2);" width="48" height="48"/></div>
        <div class="index_newjob_comname"> <a href="<?php echo $_smarty_tpl->tpl_vars['job_list']->value['com_url'];?>
"><?php echo $_smarty_tpl->tpl_vars['job_list']->value['com_n'];?>
</a> <?php if ($_smarty_tpl->tpl_vars['job_list']->value['ratlogo']!=''&&$_smarty_tpl->tpl_vars['job_list']->value['ratlogo']!="0") {?><img src="<?php echo $_smarty_tpl->tpl_vars['config']->value['sy_weburl'];?>
/<?php echo $_smarty_tpl->tpl_vars['job_list']->value['ratlogo'];?>
" title="<?php echo $_smarty_tpl->tpl_vars['job_list']->value['ratname'];?>
"/><?php }?>
          <?php if ($_smarty_tpl->tpl_vars['job_list']->value['yyzz_status']==1) {?><img src="<?php echo $_smarty_tpl->tpl_vars['style']->value;?>
/images/disc_icon10.png" title="认证企业"/><?php }?> </div>
        <div class="index_newjob_cominfo"><?php echo $_smarty_tpl->tpl_vars['job_list']->value['hy_n'];?>
</div>
      </div>
    </li>
    <?php } ?>
  </ul>
     
</div>
<div class="hp_title fl">
  <div class="hp_title_ft fl"><i class="hp_title_icon hp_title_icon_user"></i>人才推荐</div> <div class="index_lookmore"><a href="<?php echo smarty_function_url(array('m'=>'resume','c'=>'search'),$_smarty_tpl);?>
" target="_blank">查看更多</a></div>
</div>
<div class="hp_people fl">
  <ul>
    <?php  $_smarty_tpl->tpl_vars['ulist'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['ulist']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
$ulist = $ulist; if (!is_array($ulist) && !is_object($ulist)) { settype($ulist, 'array');}
foreach ($ulist as $_smarty_tpl->tpl_vars['ulist']->key => $_smarty_tpl->tpl_vars['ulist']->value) {
$_smarty_tpl->tpl_vars['ulist']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['ulist']->key;
?>
    <li>
      <div class="hp_people_box">
        <div class="hp_people_box_rt fl"><a href="<?php echo smarty_function_url(array('m'=>'resume','c'=>'show','id'=>'`$ulist.id`'),$_smarty_tpl);?>
" target="_blank"><img width="80" height="80"  src="<?php echo $_smarty_tpl->tpl_vars['ulist']->value['photo'];?>
" onerror="showImgDelay(this,'<?php echo $_smarty_tpl->tpl_vars['config']->value['sy_weburl'];?>
/<?php echo $_smarty_tpl->tpl_vars['config']->value['sy_member_icon'];?>
',2);" /></a></div>
        <div class="hp_people_box_ft fl">
          <div class="hp_people_box_ft_nm"><a href="<?php echo smarty_function_url(array('m'=>'resume','c'=>'show','id'=>'`$ulist.id`'),$_smarty_tpl);?>
" target="_blank"><?php echo mb_substr($_smarty_tpl->tpl_vars['ulist']->value['username_n'],0,10,"utf-8");?>
</a></div>
          <div class="hp_people_box_ft_v"><?php echo $_smarty_tpl->tpl_vars['ulist']->value['exp_n'];?>
经验<i class="index_job_line">|</i><?php echo $_smarty_tpl->tpl_vars['ulist']->value['edu_n'];?>
学历</div>
          <div class="hp_people_box_ft_y"><?php echo $_smarty_tpl->tpl_vars['ulist']->value['job_post_n'];?>
</div>
        </div>
      </div>
    </li>
    <?php } ?>
  </ul>
    
</div>
<div class="hp_title fl">
  <div class="hp_title_ft fl"><i class="hp_title_icon hp_title_icon_news"></i>职场资讯</div>
 <div class="index_lookmore"><a href="<?php echo smarty_function_url(array('m'=>'article'),$_smarty_tpl);?>
" target="_blank">查看更多</a></div>
</div>
<div class="index_news_box">
<div class="index_news_left">
<div class="index_news_tip">
<div class="index_news_tip_tit">专业职场资讯</div>
<div class="">排忧艰难，带你就业带你飞</div>
<i class="index_news_tip_icon"></i>
</div>
 <div class="index_news_list">
      <ul>
      <?php  $_smarty_tpl->tpl_vars['alist'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['alist']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
$alist = $alist; if (!is_array($alist) && !is_object($alist)) { settype($alist, 'array');}
foreach ($alist as $_smarty_tpl->tpl_vars['alist']->key => $_smarty_tpl->tpl_vars['alist']->value) {
$_smarty_tpl->tpl_vars['alist']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['alist']->key;
?>
          <li><a href="<?php echo $_smarty_tpl->tpl_vars['alist']->value['url'];?>
" class="hp_news_list_cr" target="_blank"><?php echo $_smarty_tpl->tpl_vars['alist']->value['title'];?>
</a></li>
          <?php } ?>
       
      </ul>
    </div>
</div>

<div class="index_news_list_imgnews "> 
<div class="index_news_list_imgnews_cont "> 
	<?php  $_smarty_tpl->tpl_vars['plist'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['plist']->_loop = false;
 $_smarty_tpl->tpl_vars['pkey'] = new Smarty_Variable;
$plist = $plist; if (!is_array($plist) && !is_object($plist)) { settype($plist, 'array');}
foreach ($plist as $_smarty_tpl->tpl_vars['plist']->key => $_smarty_tpl->tpl_vars['plist']->value) {
$_smarty_tpl->tpl_vars['plist']->_loop = true;
 $_smarty_tpl->tpl_vars['pkey']->value = $_smarty_tpl->tpl_vars['plist']->key;
?>
		<?php if ($_smarty_tpl->tpl_vars['pkey']->value==0||$_smarty_tpl->tpl_vars['pkey']->value==1) {?>
			<div class="hp_news_t fl">
				<dl>
					<dt><a href="<?php echo $_smarty_tpl->tpl_vars['plist']->value['url'];?>
"><img style="width:450px;height:260px;" src="<?php echo $_smarty_tpl->tpl_vars['config']->value['sy_weburl'];?>
/<?php echo $_smarty_tpl->tpl_vars['plist']->value['newsphoto'];?>
" alt="<?php echo $_smarty_tpl->tpl_vars['plist']->value['title'];?>
"/></a></dt>
					<dd><a href="<?php echo $_smarty_tpl->tpl_vars['plist']->value['url'];?>
"><?php echo $_smarty_tpl->tpl_vars['plist']->value['title'];?>
</a></dd>
				</dl>
		<?php } else { ?>

			<?php if ($_smarty_tpl->tpl_vars['pkey']->value==2) {?>
				<div class="hp_news_w fl">
			<?php }?>
					<div class="hp_news_w_p fl">
						<div class="hp_news_p_img fl"><a href="<?php echo $_smarty_tpl->tpl_vars['plist']->value['url'];?>
"><img src="<?php echo $_smarty_tpl->tpl_vars['config']->value['sy_weburl'];?>
/<?php echo $_smarty_tpl->tpl_vars['plist']->value['newsphoto'];?>
" width="190" height="120" alt="<?php echo $_smarty_tpl->tpl_vars['plist']->value['title'];?>
"/></a></div>
						<div class="hp_news_p_wr fl">
							<div class="hp_news_p_wr_tit"><a href="<?php echo $_smarty_tpl->tpl_vars['plist']->value['url'];?>
"><?php echo $_smarty_tpl->tpl_vars['plist']->value['title'];?>
</a></div>
							<div class="hp_news_p_ct"><?php echo $_smarty_tpl->tpl_vars['plist']->value['description'];?>
</div>
						</div>
					</div>
		<?php }?>
		<?php if ($_smarty_tpl->tpl_vars['pkey']->value!=2) {?>
			</div>
		<?php }?>
	<?php } ?>
</div>
</div>
  </div>    
  <div class="hp_title fl">
      <div class="hp_title_ft fl"><i class="hp_title_icon hp_title_icon_link"></i>友情链接</div>
      <?php if ($_smarty_tpl->tpl_vars['config']->value['sy_linksq']==1) {?>
     <div class="index_lookmore"><a href="<?php echo smarty_function_url(array('m'=>'link'),$_smarty_tpl);?>
">申请链接</a></div>
      <?php }?> </div>
  <div class="hp_link fl">

    <div class="hp_link_banner">
      <ul class="hp_link_banner_img">
        <?php  $_smarty_tpl->tpl_vars['linklist'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['linklist']->_loop = false;
global $config;
		
		$domain='';
		if($config["cityid"]!="" || $config["hyclass"]!=""){
			include(PLUS_PATH."/domain_cache.php");
			$host_url=$_SERVER['HTTP_HOST'];
			foreach($site_domain as $v){
				if($v['host']==$host_url){
					$domain=$v['id'];
				}
			}
		}$tem_type = 2;
        include PLUS_PATH."/link.cache.php";
		if(is_array($link)){$linkList=array();
			$i=0;
			foreach($link as $key=>$value)
			{
				if($config["did"]!=0 && $value["did"]!=$config["did"])
				{
					continue;
				}elseif(is_numeric('2') && $value['tem_type']!='2' && $value['tem_type']!='1'){
					continue;

				}elseif((!is_numeric('2') || '2'=='1') && $value['tem_type']!='1'){

					continue;
				}elseif(!$config["did"] && $value["did"]>0){
					continue;
				}
				if(is_numeric('2') && $value['link_type']!='2')
				{
					continue;
				}
				if(is_numeric('') && intval('')<=$i)
				{
					break;
				}
				$value[picurl] = $config[sy_weburl]."/".$value[pic];
				$linkList[] = $value;
				$i++;
			}
		}$linkList = $linkList; if (!is_array($linkList) && !is_object($linkList)) { settype($linkList, 'array');}
foreach ($linkList as $_smarty_tpl->tpl_vars['linklist']->key => $_smarty_tpl->tpl_vars['linklist']->value) {
$_smarty_tpl->tpl_vars['linklist']->_loop = true;
?>
        <li><a href="<?php echo $_smarty_tpl->tpl_vars['linklist']->value['link_url'];?>
" target="_blank"><img  src="<?php echo $_smarty_tpl->tpl_vars['linklist']->value['pic'];?>
" alt="<?php echo $_smarty_tpl->tpl_vars['linklist']->value['link_name'];?>
" /></a></li>
        <?php } ?>
      </ul>
      <div class="hp_link_banner_wr"> <?php  $_smarty_tpl->tpl_vars['linklist2'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['linklist2']->_loop = false;
global $config;
		
		$domain='';
		if($config["cityid"]!="" || $config["hyclass"]!=""){
			include(PLUS_PATH."/domain_cache.php");
			$host_url=$_SERVER['HTTP_HOST'];
			foreach($site_domain as $v){
				if($v['host']==$host_url){
					$domain=$v['id'];
				}
			}
		}$tem_type = 2;
        include PLUS_PATH."/link.cache.php";
		if(is_array($link)){$linkList=array();
			$i=0;
			foreach($link as $key=>$value)
			{
				if($config["did"]!=0 && $value["did"]!=$config["did"])
				{
					continue;
				}elseif(is_numeric('2') && $value['tem_type']!='2' && $value['tem_type']!='1'){
					continue;

				}elseif((!is_numeric('2') || '2'=='1') && $value['tem_type']!='1'){

					continue;
				}elseif(!$config["did"] && $value["did"]>0){
					continue;
				}
				if(is_numeric('1') && $value['link_type']!='1')
				{
					continue;
				}
				if(is_numeric('') && intval('')<=$i)
				{
					break;
				}
				$value[picurl] = $config[sy_weburl]."/".$value[pic];
				$linkList[] = $value;
				$i++;
			}
		}$linkList = $linkList; if (!is_array($linkList) && !is_object($linkList)) { settype($linkList, 'array');}
foreach ($linkList as $_smarty_tpl->tpl_vars['linklist2']->key => $_smarty_tpl->tpl_vars['linklist2']->value) {
$_smarty_tpl->tpl_vars['linklist2']->_loop = true;
?> <a href="<?php echo $_smarty_tpl->tpl_vars['linklist2']->value['link_url'];?>
" target="_blank"><?php echo $_smarty_tpl->tpl_vars['linklist2']->value['link_name'];?>
</a> <?php } ?> </div>
    </div>
  </div>
</div>

<?php if ($_smarty_tpl->tpl_vars['config']->value['sy_footer_fix']=='1') {?> 
<div class="tip_bottom">
  <div class="tip_bottom_cont">
    <div class="tip_bottom_bg"></div>
      <div class="tip_bottom_cont_c">
    <div class="tip_bottom_main" >
       <div class="tip_bottom_icon png"></div>
      <div class="tip_bottom_left"> <a href="javascript:void(0);" onclick="$('.tip_bottom').hide();" class="tip_bottom_close png"></a> 
      <div class="tip_bottom_ewm"><div class="tip_bottom_ewm_bg"><i class="tip_bottom_ewm_p_icon"></i><img src="<?php echo $_smarty_tpl->tpl_vars['config']->value['sy_weburl'];?>
/<?php echo $_smarty_tpl->tpl_vars['config']->value['sy_wx_qcode'];?>
"  width="90" height="90"></div><div class="tip_bottom_ewm_p">手机也能找工作</div></div>
      <span class="tip_bottom_logo">
        <h2><span class="tip_bottom_fast"><span class="tip_bottom_time">30秒</span> 填写简历</span>，好工作轻松搞定！</h2>
        </span>
        <div class="tip_bottom_num "><span>0</span>公司</div>
        <div class="tip_bottom_num "><span>0</span>职位</div>
       <?php if (!$_smarty_tpl->tpl_vars['uid']->value) {?>
        <div class="tip_bottom_member">
        <a href="<?php echo smarty_function_url(array('m'=>'login'),$_smarty_tpl);?>
" class="tip_bottom_login">登录</a> 
        <a href="<?php echo smarty_function_url(array('m'=>'register'),$_smarty_tpl);?>
" class="tip_bottom_reg" >快速注册<i class="tip_bottom_reg_icon"></i></a> </div>
    <?php }?>
    </div></div>
  </div>  </div>
</div>
<?php }?>

<div id="bg"></div>
<?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['tplstyle']->value)."/backtop.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<div id='footer_ad'> <?php  $_smarty_tpl->tpl_vars['footer_ad'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['footer_ad']->_loop = false;
$AdArr=array();$paramer=array();
			include(PLUS_PATH.'/pimg_cache.php');$add_arr = $ad_label[10];if(is_array($add_arr) && !empty($add_arr)){
				$i=0;$limit = 0;$length = 0;
				foreach($add_arr as $key=>$value){
					if(($value['did']==$config['did'] ||$value['did']=='0')&&$value['start']<time()&&$value['end']>time()){
						if($i>0 && $limit==$i){
							break;
						}
						if($length>0){
							$value['name'] = mb_substr($value['name'],0,$length);
						}
						if($paramer['type']!=""){
							if($paramer['type'] == $value['type']){
								$AdArr[] = $value;
							}
						}else{
							$AdArr[] = $value;
						}
						$i++;
					}
				}
			}$AdArr = $AdArr; if (!is_array($AdArr) && !is_object($AdArr)) { settype($AdArr, 'array');}
foreach ($AdArr as $_smarty_tpl->tpl_vars['footer_ad']->key => $_smarty_tpl->tpl_vars['footer_ad']->value) {
$_smarty_tpl->tpl_vars['footer_ad']->_loop = true;
?>
  <div class="footer_ban" id="">
    <div class="baner_footer" id='bottom_ad_fl'> <span class="ban_close" onclick="colse_bottom()">关闭</span> <?php echo $_smarty_tpl->tpl_vars['footer_ad']->value['html'];?>
 </div>
    <input type="hidden" value='1' id='bottom_ad_is_show' />
  </div>
  <?php } ?>
  <?php  $_smarty_tpl->tpl_vars['left_ad'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['left_ad']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
$AdArr=array();$paramer=array();
			include(PLUS_PATH.'/pimg_cache.php');$add_arr = $ad_label[11];if(is_array($add_arr) && !empty($add_arr)){
				$i=0;$limit = 0;$length = 0;
				foreach($add_arr as $key=>$value){
					if(($value['did']==$config['did'] ||$value['did']=='0')&&$value['start']<time()&&$value['end']>time()){
						if($i>0 && $limit==$i){
							break;
						}
						if($length>0){
							$value['name'] = mb_substr($value['name'],0,$length);
						}
						if($paramer['type']!=""){
							if($paramer['type'] == $value['type']){
								$AdArr[] = $value;
							}
						}else{
							$AdArr[] = $value;
						}
						$i++;
					}
				}
			}$AdArr = $AdArr; if (!is_array($AdArr) && !is_object($AdArr)) { settype($AdArr, 'array');}
foreach ($AdArr as $_smarty_tpl->tpl_vars['left_ad']->key => $_smarty_tpl->tpl_vars['left_ad']->value) {
$_smarty_tpl->tpl_vars['left_ad']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['left_ad']->key;
?>
  <div class="duilian <?php if ($_smarty_tpl->tpl_vars['key']->value=='0') {?>duilian_left<?php } else { ?>duilian_right<?php }?>">
    <div class="duilian_con"><?php echo $_smarty_tpl->tpl_vars['left_ad']->value['html'];?>
</div>
    <div class="close_container">
      <div class="btn_close"></div>
    </div>
  </div>
  <?php } ?> </div>
<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['config']->value['sy_weburl'];?>
/js/jquery-1.8.0.min.js?v=<?php echo $_smarty_tpl->tpl_vars['config']->value['cachecode'];?>
" language="javascript"><?php echo '</script'; ?>
> 
<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['config']->value['sy_weburl'];?>
/js/public.js?v=<?php echo $_smarty_tpl->tpl_vars['config']->value['cachecode'];?>
" language="javascript"><?php echo '</script'; ?>
> 
<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['style']->value;?>
/js/index.js?v=<?php echo $_smarty_tpl->tpl_vars['config']->value['cachecode'];?>
" language="javascript"><?php echo '</script'; ?>
> 
<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['style']->value;?>
/js/reg_ajax.js?v=<?php echo $_smarty_tpl->tpl_vars['config']->value['cachecode'];?>
" type="text/javascript"><?php echo '</script'; ?>
> 
<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['config']->value['sy_weburl'];?>
/js/slides.jquery.js?v=<?php echo $_smarty_tpl->tpl_vars['config']->value['cachecode'];?>
" type="text/javascript"><?php echo '</script'; ?>
> 
<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['config']->value['sy_weburl'];?>
/js/layui/layui.js?v=<?php echo $_smarty_tpl->tpl_vars['config']->value['cachecode'];?>
"><?php echo '</script'; ?>
> 
<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['config']->value['sy_weburl'];?>
/js/layui/phpyun_layer.js?v=<?php echo $_smarty_tpl->tpl_vars['config']->value['cachecode'];?>
"><?php echo '</script'; ?>
> 
<?php echo '<script'; ?>
 type="text/javascript">
layui.use(['carousel'], function(){
  var carousel = layui.carousel;
  carousel.render({
    elem: '#test1'
    ,width: '590px'
    ,height: '270px'
  });
});
//顶部伸展广告
if ($("#js_ads_banner_top_slide").length){  //判断当前广告是否展开，如果没有，则执行下面代码
	var $slidebannertop = $("#js_ads_banner_top_slide"),$bannertop = $("#js_ads_banner_top");
	setTimeout(function(){$bannertop.slideUp(1000);$slidebannertop.slideDown(1000);},1500); //1500毫秒(1.5秒)后，小广告收回，大广告伸开，执行时间都是1秒(1000毫秒)
	setTimeout(function(){$slidebannertop.slideUp(1000,function (){$bannertop.slideDown(1000);});},2000); //2.0秒(2000毫秒)之后，大广告收回，小广告展开。
}
$(document).ready(function() {
    //首页登录框以及登录后显示各会员中心内容
	var loginIndex='<?php echo smarty_function_url(array('m'=>'ajax','c'=>'DefaultLoginIndex'),$_smarty_tpl);?>
';
    $.post(loginIndex,{rand:Math.random()},function(data){
		$(".hp_login").html(data);
	});
    $(".index_new_job li").hover(function(){
		var aid=$(this).attr("aid");
		$("#joblist"+aid).addClass("index_new_job_hover");	
	},function(){
		var aid=$(this).attr("aid");
		$("#joblist"+aid).removeClass("index_new_job_hover");	
	})   
	$(".menu_box").hover(function(){
		var aid=$(this).attr("aid");
		var ftop=Number($(this).offset().top); 
		var sheight=Number($("#jobclass_"+aid).height());  
		if(aid=='0'){ 
			$("#jobclass_"+aid).css("top","0px"); 
		}else if(sheight>ftop){ 
			$("#jobclass_"+aid).css("top","0px"); 
		}else if(ftop>sheight&&Number(sheight)<250){  
			var isIE6=!window.XMLHttpRequest;
			if (isIE6){
				var top=Number(ftop)-Number(99);
			}else if(navigator.appName == "Microsoft Internet Explorer" && navigator.appVersion.match(/9./i)=="9."){
				var top=Number(ftop)-Number(94);
			}else{ 
				var top=Number(ftop)-Number(94);
			}  
			$("#jobclass_"+aid).css("top",top+"px"); 
 		}else if(Number(sheight)>250){ 
			var top=Number(ftop)-Number(320);
			$("#jobclass_"+aid).css("top",top+"px"); 
		} 
		$("#jobclass"+aid).addClass("current");	
		$("#jobclass_"+aid).attr("class","menu_sub db");
	},function(){
		var aid=$(this).attr("aid");
		$("#jobclass"+aid).removeClass("current");	
		$("#jobclass_"+aid).attr("class","menu_sub dn");	
	})  
	$(".select_wrap").hover(function(){
		$(".select_wrap_list").show();
	},function(){
		$(".select_wrap_list").hide();
	})
});
<?php echo '</script'; ?>
>
<?php if ($_smarty_tpl->tpl_vars['config']->value['sy_footer_fix']=='1') {?> 
<?php echo '<script'; ?>
>
$(document).ready(function() {
	$.get('<?php echo smarty_function_url(array('m'=>'ajax','c'=>'footertj'),$_smarty_tpl);?>
',{rand:Math.random()},function(data){
		$(".tip_bottom_left").html(data);
	})
})
<?php echo '</script'; ?>
>
<?php }?>
<!--下面为调用网站主题--> 
<?php echo smarty_function_webspecial(array(),$_smarty_tpl);?>

<?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['tplstyle']->value)."/footer.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>
<?php }} ?>
