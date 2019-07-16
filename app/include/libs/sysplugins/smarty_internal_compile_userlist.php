<?php
class Smarty_Internal_Compile_Userlist extends Smarty_Internal_CompileBase{
	public $required_attributes = array('item');
	public $optional_attributes = array('name', 'key', 'post_len', 'limit', 'salary', 'minsalary', 'maxsalary', 'idcard', 'edu', 'order', 'work', 'exp', 'sex','birthday', 'keyword', 'hy', 'provinceid', 'report', 'cityid', 'three_cityid', 'adtime', 'jobids', 'pic', 'typeids', 'type', 'job1_son', 'job_post', 'uptime', 'ispage', 'rec_resume','where_uid', 'height_status', 'rec', 't_len' ,'top','job_classid','islt','job1','isshow','cityin','jobin','where','topdate','noid','tag');
	public $shorttag_order = array('from', 'item', 'key', 'name');
	public function compile($args, $compiler, $parameter){
		$_attr = $this->getAttributes($compiler, $args);

		$from = $_attr['from'];
		$item = $_attr['item'];
		$name = $_attr['item'];
		$name=str_replace('\'','',$name);
		$name=$name?$name:'list';$name='$'.$name;
		if (!strncmp("\$_smarty_tpl->tpl_vars[$item]", $from, strlen($item) + 24)) {
			$compiler->trigger_template_error("item variable {$item} may not be the same variable as at 'from'", $compiler->lex->taglineno);
		}

		//自定义标签START
		$OutputStr=''.$name.'=array();global $db,$db_config,$config;
		if(is_array($_GET)){
			foreach($_GET as $key=>$value){
				if($value==\'0\'){
					unset($_GET[$key]);
				}
			}
		}
		eval(\'$paramer='.str_replace('\'','\\\'',ArrayToString($_attr,true)).';\');
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
		$where = "status=\'1\' and `r_status`=\'1\'  and `open`=\'1\' and `defaults`=\'1\'";
		
		if($config[\'sy_web_site\']=="1"){
			if($config[province]>0 && $config[province]!=""){
				$paramer[provinceid] = $config[province];
			}
			if($config[\'cityid\']>0 && $config[\'cityid\']!=""){
				$paramer[\'cityid\']=$config[\'cityid\'];
			}
			if($config[\'three_cityid\']>0 && $config[\'three_cityid\']!=""){
				$paramer[\'three_cityid\']=$config[\'three_cityid\'];
			}
			if($config[\'hyclass\']>0 && $config[\'hyclass\']!=""){
				$paramer[\'hy\']=$config[\'hyclass\'];
			}
		}
		
		if($paramer[where_uid]){
			$where .=" AND `uid` in (".$paramer[\'where_uid\'].")";
		}
	
		if($_COOKIE[\'uid\']&&$_COOKIE[\'usertype\']=="2"){
			$blacklist=$db->select_all("blacklist","`p_uid`=\'".$_COOKIE[\'uid\']."\'","c_uid");
			if(is_array($blacklist)&&$blacklist){
				foreach($blacklist as $v){
					$buid[]=$v[\'c_uid\'];
				}
			$where .=" AND `uid` NOT IN (".@implode(",",$buid).")";
			}
		}
		
		if($paramer[topdate]){
			$where .=" AND `topdate`>\'".time()."\'";
		}
		
		
		if($paramer[idcard]){
			$where .=" AND `idcard_status`=\'1\'";
		}
		
		if($paramer[height_status]){
			$where .=" AND height_status=".$paramer[height_status];
		}else{
			$where .=" AND height_status=\'0\'";
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
					$eid[]=$v[\'eid\'];
				}
			}
			$where .=" AND id in (".@implode(",",$eid).")";
		}
		
		if($paramer[tag]){
		    $tagname=$userclass_name[$paramer[tag]];
			$tag=$db->select_all("resume","`def_job`>0 and `r_status`<>2 and `status`=1 and FIND_IN_SET(\'".$tagname."\',`tag`)","`def_job`");
			if(is_array($tag)){
				foreach($tag as $v){
					$tagid[]=$v[\'def_job\'];
				}
			}
			$where .=" AND id in (".@implode(",",$tagid).")";
		}
		
		if($paramer[cid]){
			$where .= " AND (cityid=$paramer[cid] or three_cityid=$paramer[cid])";
		}
		
		if($paramer[keyword]){

			$jobid = array();
			$where1[]="`name` LIKE \'%$paramer[keyword]%\'";
			$where1[]="`uname` LIKE \'%$paramer[keyword]%\'";
			foreach($job_name as $k=>$v){
				if(strpos($v,$paramer[keyword])!==false){
					$jobid[]=$k;
				}
			}
			if(!empty($jobid)){
				foreach($jobid as $value){
					$class[]="FIND_IN_SET(\'".$value."\',job_classid)";
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
					$class[]= "(provinceid = \'".$value."\' or cityid = \'".$value."\')";
				}
				$where1[]=@implode(" or ",$class);
			}
			$where.=" AND (".@implode(" or ",$where1).")";
		}
		
		if($paramer[pic]=="0" || $paramer[pic]){
			$where .=" AND photo<>\'\'";
			$where .=" AND phototype!=1";
		}
	
		if($paramer[name]=="0"){
			$where .=" AND uname<>\'\'";
		}
		
		if($paramer[hy]=="0"){
			$where .=" AND hy<>\'\'";
		}elseif($paramer[hy]!=""){
			$where .= " AND (`hy` IN (".$paramer[\'hy\']."))";
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
				$jobclass[]="FIND_IN_SET(\'".$value."\',job_classid)";
			}
			$classid=@implode(" or ",$jobclass);
			$where .= " AND ($classid)";
		}
		if($paramer[job_post]){
			$where .=" AND FIND_IN_SET(\'".$paramer[job_post]."\',job_classid)";
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
				$beginToday=mktime(0,0,0,date(\'m\'),date(\'d\'),date(\'Y\'));
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
			if($paramer[order]==\'topdate\'){
				$nowtime=time();
				$order = " ORDER BY if(topdate>$nowtime,topdate,lastupdate)";
			}else{
				$order = " ORDER BY `".str_replace("\'","",$paramer[order])."`";
			}
		}else{
			$order = " ORDER BY lastupdate ";
		}
		
	
		$sort = $paramer[sort]?$paramer[sort]:\'DESC\';
		
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
				
				$limit = PageNav($paramer,$_GET,"resume_expect",$where,$Purl,"",\'0\',$_smarty_tpl);
			}
		}
		$where.=$order.$sort;
 		'.$name.'=$db->select_all("resume_expect",$where.$limit,"*,uname as username");
        include(CONFIG_PATH."db.data.php");		
		if('.$name.' && is_array('.$name.')){
			
			
			if($paramer[\'top\']){
				$uids=$m_name=array();
				foreach('.$name.' as $k=>$v){
					$uids[]=$v[uid];
				}

				$member=$db->select_all($db_config[def]."member","`uid` in(".@implode(\',\',$uids).")","uid,username");
				foreach($member as $val){
					$m_name[$val[uid]]=$val[\'username\'];
				}
			}
			foreach('.$name.' as $key=>$value){
				$uid[] = $value[\'uid\'];
				$eid[] = $value[\'id\'];
			}
			$eids = @implode(\',\',$eid);
			$uids = @implode(\',\',$uid);
            $resume=$db->select_all("resume","`uid` in(".$uids.")","uid,name,nametype,tag,sex,edu,exp,photo,phototype,birthday");
			if($paramer[topdate]){
				$noids=array();
			}	
			foreach('.$name.' as $k=>$v){
				if($paramer[topdate]){
					$noids[] = $v[id];
				}
				
				if($paramer[noid]==\'1\' && !empty($noids) && in_array($v[\'id\'],$noids)){
					unset('.$name.'[$k]);
					continue;
				}
			    foreach($resume as $val){
			        if($v[\'uid\']==$val[\'uid\']){
			    		'.$name.'[$k][\'edu_n\']=$userclass_name[$val[\'edu\']];
				        '.$name.'[$k][\'exp_n\']=$userclass_name[$val[\'exp\']];
			            if($val[\'birthday\']){
							$year = date("Y",strtotime($val[\'birthday\']));
							'.$name.'[$k][\'age\'] =date("Y")-$year;
						}
						if($val[\'sex\']==152){
							$val[\'sex\']=\'1\';
						}elseif ($val[\'sex\']==153){
							$val[\'sex\']=\'2\';
						}
						'.$name.'[$k][\'sex\'] =$arr_data[sex][$val[\'sex\']];
		                '.$name.'[$k][\'phototype\']=$val[phototype];
						if($config[\'user_pic\']==1 || !$config[\'user_pic\']){
		                if($val[\'photo\'] && $val[\'phototype\']!=1&&(file_exists(str_replace($config[\'sy_weburl\'],APP_PATH,\'.\'.$val[\'photo\']))||file_exists(str_replace($config[\'sy_weburl\'],APP_PATH,$val[\'photo\'])))){
            				'.$name.'[$k][\'photo\']=str_replace("./",$config[\'sy_weburl\']."/",$val[\'photo\']);
            			}else{
            				if($val[\'sex\']==1){
            					'.$name.'[$k][\'photo\']=$config[\'sy_weburl\']."/".$config[\'sy_member_icon\'];
            				}else{
            					'.$name.'[$k][\'photo\']=$config[\'sy_weburl\']."/".$config[\'sy_member_iconv\'];
            				}
            			}
						}elseif($config[\'user_pic\']==2){
							if($val[\'photo\']&&(file_exists(str_replace($config[\'sy_weburl\'],APP_PATH,\'.\'.$val[\'photo\']))||file_exists(str_replace($config[\'sy_weburl\'],APP_PATH,$val[\'photo\'])))){
								'.$name.'[$k][\'photo\']=str_replace("./",$config[\'sy_weburl\']."/",$val[\'photo\']);
							}else{
								if($val[\'sex\']==1){
									'.$name.'[$k][\'photo\']=$config[\'sy_weburl\']."/".$config[\'sy_member_icon\'];
								}else{
									'.$name.'[$k][\'photo\']=$config[\'sy_weburl\']."/".$config[\'sy_member_iconv\'];
								}
							}
						}elseif($config[\'user_pic\']==3){
							if($val[\'sex\']==1){
								'.$name.'[$k][\'photo\']=$config[\'sy_weburl\']."/".$config[\'sy_member_icon\'];
							}else{
								'.$name.'[$k][\'photo\']=$config[\'sy_weburl\']."/".$config[\'sy_member_iconv\'];
							}
						}
						if($val[\'tag\']){
                            '.$name.'[$k][\'tag\']=explode(\',\',$val[\'tag\']);
	                    }
                        '.$name.'[$k][\'nametype\']=$val[nametype];
                       
						if($config[\'user_name\']==1 || !$config[\'user_name\']){
						if($val[\'nametype\']==3){
						    if($val[\'sex\']==1){
						        '.$name.'[$k][\'username_n\'] = mb_substr($val[\'name\'],0,1,\'utf-8\')."先生";
						    }else{
						        '.$name.'[$k][\'username_n\'] = mb_substr($val[\'name\'],0,1,\'utf-8\')."女士";
						    }
						}elseif($val[\'nametype\']==2){
						    '.$name.'[$k][\'username_n\'] = "NO.".$v[\'id\'];
						}else{
							'.$name.'[$k][\'username_n\'] = $val[\'name\'];
						}
						}elseif($config[\'user_name\']==3){
							if($val[\'sex\']==1){
								'.$name.'[$k][\'username_n\'] = mb_substr($val[\'name\'],0,1,\'utf-8\')."先生";
							}else{
								'.$name.'[$k][\'username_n\'] = mb_substr($val[\'name\'],0,1,\'utf-8\')."女士";
							}
						}elseif($config[\'user_name\']==2){
							'.$name.'[$k][\'username_n\'] = "NO.".$v[\'id\'];
						}elseif($config[\'user_name\']==4){
							'.$name.'[$k][\'username_n\'] = $val[\'name\'];
						}
                    }
                }
				
				
				$time=$v[\'lastupdate\'];
				
				$beginToday=mktime(0,0,0,date(\'m\'),date(\'d\'),date(\'Y\'));
				
				$beginYesterday=mktime(0,0,0,date(\'m\'),date(\'d\')-1,date(\'Y\'));
			
				$week=strtotime(date("Y-m-d",strtotime("-1 week")));
				if($time>$week && $time<$beginYesterday){
					'.$name.'[$k][\'time\'] = "一周内";
				}elseif($time>$beginYesterday && $time<$beginToday){
					'.$name.'[$k][\'time\'] = "昨天";
				}elseif($time>$beginToday){
					'.$name.'[$k][\'time\'] = date("H:i",$v[\'lastupdate\']);
					'.$name.'[$k][\'redtime\'] =1;
				}else{
					'.$name.'[$k][\'time\'] = date("Y-m-d",$v[\'lastupdate\']);
				} 
				
				
				'.$name.'[$k][\'user_jobstatus_n\']=$userclass_name[$v[\'jobstatus\']];
				'.$name.'[$k][\'job_city_one\']=$city_name[$v[\'provinceid\']];
				'.$name.'[$k][\'job_city_two\']=$city_name[$v[\'cityid\']];
				'.$name.'[$k][\'job_city_three\']=$city_name[$v[\'three_cityid\']];
				if($v[\'minsalary\']&&$v[\'maxsalary\']){
					'.$name.'[$k]["salary_n"] = "￥".$v[\'minsalary\']."-".$v[\'maxsalary\'];    
                }else if($v[\'minsalary\']){
                    '.$name.'[$k]["salary_n"] = "￥".$v[\'minsalary\']."以上";  
                }else{
    				'.$name.'[$k]["salary_n"] = "面议";
    			}
				'.$name.'[$k][\'report_n\']=$userclass_name[$v[\'report\']];
				'.$name.'[$k][\'type_n\']=$userclass_name[$v[\'type\']];
				'.$name.'[$k][\'lastupdate\']=date("Y-m-d",$v[\'lastupdate\']);
					
				'.$name.'[$k][\'user_url\']=Url("resume",array("c"=>"show","id"=>$v[\'id\']),"1");
				'.$name.'[$k]["hy_info"]=$industry_name[$v[\'hy\']];
				if($paramer[\'top\']){
					'.$name.'[$k][\'m_name\']=$m_name[$v[\'uid\']];
					'.$name.'[$k][\'user_url\']=Url("ask",array("c"=>"friend","a"=>"myquestion","uid"=>$v[\'uid\']));
				}
				$kjob_classid=@explode(",",$v[\'job_classid\']);
				$kjob_classid=array_unique($kjob_classid);	
				$jobname=array();
				if(is_array($kjob_classid)){
					foreach($kjob_classid as $val){
					    if($val!=\'\'){
					        if($paramer[\'keyword\']){
                               $jobname[]=str_replace($paramer[\'keyword\'],"<font color=#FF6600 >".$paramer[\'keyword\']."</font>",$job_name[$val]);
                            }else{
                               $jobname[]=$job_name[$val];
                            }
                        }
					}
				}
				'.$name.'[$k][\'job_post\']=@implode(",",$jobname);
				'.$name.'[$k][\'expectjob\']=$jobname;
				
				if($paramer[\'post_len\']){
					$postname[$k]=@implode(",",$jobname);
					'.$name.'[$k][\'job_post_n\']=mb_substr($postname[$k],0,$paramer[post_len],"utf-8");
				}
			}
			foreach('.$name.' as $k=>$v){
               if($paramer[\'keyword\']){
					'.$name.'[$k][\'username_n\']=str_replace($paramer[\'keyword\'],"<font color=#FF6600 >".$paramer[\'keyword\']."</font>",$v[\'username_n\']);
					'.$name.'[$k][\'job_post\']=str_replace($paramer[\'keyword\'],"<font color=#FF6600 >".$paramer[\'keyword\']."</font>",'.$name.'[$k][\'job_post\']);
					'.$name.'[$k][\'job_post_n\']=str_replace($paramer[\'keyword\'],"<font color=#FF6600 >".$paramer[\'keyword\']."</font>",'.$name.'[$k][\'job_post_n\']);
					'.$name.'[$k][\'job_city_one\']=str_replace($paramer[\'keyword\'],"<font color=#FF6600 >".$paramer[\'keyword\']."</font>",$city_name[$v[\'provinceid\']]);
					'.$name.'[$k][\'job_city_two\']=str_replace($paramer[\'keyword\'],"<font color=#FF6600 >".$paramer[\'keyword\']."</font>",$city_name[$v[\'cityid\']]);
				}
            }
			if($paramer[\'keyword\']!=""&&!empty('.$name.')){
				addkeywords(\'5\',$paramer[\'keyword\']);
			}
		}';
		//自定义标签 END
		global $DiyTagOutputStr;
		$DiyTagOutputStr[]=$OutputStr;
		return SmartyOutputStr($this,$compiler,$_attr,'userlist',$name,'',$name);
	}
}
class Smarty_Internal_Compile_Userlistelse extends Smarty_Internal_CompileBase{
	public function compile($args, $compiler, $parameter){
		$_attr = $this->getAttributes($compiler, $args);

		list($openTag, $nocache, $item, $key) = $this->closeTag($compiler, array('userlist'));
		$this->openTag($compiler, 'userlistelse', array('userlistelse', $nocache, $item, $key));

		return "<?php }\nif (!\$_smarty_tpl->tpl_vars[$item]->_loop) {\n?>";
	}
}
class Smarty_Internal_Compile_Userlistclose extends Smarty_Internal_CompileBase{
	public function compile($args, $compiler, $parameter){
		$_attr = $this->getAttributes($compiler, $args);
		if ($compiler->nocache) {
			$compiler->tag_nocache = true;
		}

		list($openTag, $compiler->nocache, $item, $key) = $this->closeTag($compiler, array('userlist', 'userlistelse'));

		return "<?php } ?>";
	}
}
