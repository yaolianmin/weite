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
class map_controller extends common{
	function list_action(){
		$where="`r_status`<>'2'";
		$limit=!$limit?10:$limit;
		$xy=getAround($_POST[x],$_POST[y],$_POST[r]); 
		if($xy[0]){
			$where.=" AND `x`>='".$xy[0]."' AND `x`<='".$xy[1]."' AND `y`>='".$xy[3]."' AND `y`<='".$xy[2]."'";
		} 
		if($_POST['hy']){
			$where .= " AND `hy` = '".(int)$_POST['hy']."'";
		}
		if($_POST['provinceid']){
			$where .= " AND `provinceid` = '".(int)$_POST['provinceid']."'";
		}
		if($_POST['cityid']){
			$where .= " AND `cityid` = '".(int)$_POST['cityid']."'";
		}
		if($_POST['three_cityid']){
			$where .= " AND `three_cityid` = '".(int)$_POST['three_cityid']."'";
		}
		
		$jobwhere="`edate`>'".time()."' and `state`='1'  and `r_status`='1'";
		if($_POST['job1']){
			$jobwhere.=" AND `job1`='".(int)$_POST['job1']."'";
		}
		if($_POST['job1_son']){
			$jobwhere.=" AND `job1_son`='".(int)$_POST['job1_son']."'";
		}
		if($_POST['job_post']){
			$jobwhere.=" AND `job_post`='".(int)$_POST['job_post']."'";
		}
		
		$rows=$this->obj->DB_select_all("company",$where,"uid");
		
		if(is_array($rows)&&$rows){
			$uids=array();
			foreach($rows as $v){
				$uids[]=$v['uid'];
			}
			$jobwhere.=" and `uid` in(".pylode(',',$uids).")";
			$joball=$this->obj->DB_select_all("company_job",$jobwhere." group by `uid` desc","uid"); 
			$rows=array();
			
			if(is_array($joball)&&$joball){
				$uid=array();
				foreach($joball as $v){
					$uid[]=$v['uid'];
				}
				$uid=pylode(",",$uid);
				$where.=" and `uid` in (".$uid.")  order by `jobtime` desc"; 
				$rows=$this->obj->DB_select_all("company",$where,"uid,name,shortname,address,linkphone,linktel,x,y,logo");
			}
			 
		}
		 
		if(is_array($rows)&&$rows){
			
			$list=array();
			foreach($rows as $key=>$k){
				$list[$key]['uid']			=$k['uid'];
				if($k['shortname']){
					$list[$key]['name']			=$k['shortname'];
				}else{
					$list[$key]['name']			=$k['name'];
				}
				
				$list[$key]['address']		=$k['address'];
				$list[$key]['linkphone']	=$k['linkphone'];
				$list[$key]['linktel']		=$k['linktel'];
				$list[$key]['x']			=$k['x'];
				$list[$key]['y']			=$k['y'];
				$list[$key]['logo']			=$k['logo'];
			}

			$data['list']=$list;
			$data['error']='1';
		}else{
			$data['error']='2';
		}
		echo json_encode($data);die;
	}
    function comlist_action(){
		$where="`r_status`<>'2'";
		$limit=!$limit?10:$limit;
		$xy=getAround($_POST[x],$_POST[y],$_POST[r]);
		if($xy[0]){
			$where.=" AND `x`>='".$xy[0]."' AND `x`<='".$xy[1]."' AND `y`>='".$xy[3]."' AND `y`<='".$xy[2]."'";
		}
		if($_POST['hy']){
			$where .= " AND `hy` = '".(int)$_POST['hy']."'";
		}
		if($_POST['provinceid']){
			$where .= " AND `provinceid` = '".(int)$_POST['provinceid']."'";
		}

		if($_POST['cityid']){
			$where .= " AND `cityid` = '".(int)$_POST['cityid']."'";
		}
		if($_POST['three_cityid']){
			$where .= " AND `three_cityid` = '".(int)$_POST['three_cityid']."'";
		}
	
		$jobwhere=1;
		if($_POST['job1']){
			$jobwhere.=" AND `job1`='".(int)$_POST['job1']."'";
		}
		if($_POST['job1_son']){
			$jobwhere.=" AND `job1_son`='".(int)$_POST['job1_son']."'";
		}
		if($_POST['job_post']){
			$jobwhere.=" AND `job_post`='".(int)$_POST['job_post']."'";
		}
       
        if($_POST['isjob']){
            
            if($_POST['joblimit']){

            }
		}
		$joball=$this->obj->DB_select_all("company_job",$jobwhere);
		if(is_array($joball)){
			foreach($joball as $v){
				$uid[]=$v['uid'];
			}
			$uid=pylode(",",$uid);
			$where.=" and `uid` in ($uid)";
		}
		$where.=" order by jobtime desc";
		$page=$_POST['page'];
		if($page){
			$pagenav=($page-1)*$limit;
			$where.=" limit $pagenav,$limit";
		}else{
			$where.=" limit $limit";
		}
		$rows=$this->obj->DB_select_all("company",$where);

		if(is_array($rows)){
			foreach($rows as $key=>$k){
				$list[$key]['uid']			=$k['uid'];
				if($k['shortname']){
					$list[$key]['name']			=$k['shortname'];
				}else{
					$list[$key]['name']			=$k['name'];
				}
				
				$list[$key]['address']		=$k['address'];
				$list[$key]['linkphone']	=$k['linkphone'];
				$list[$key]['linktel']		=$k['linktel'];
				$list[$key]['x']			=$k['x'];
				$list[$key]['y']			=$k['y'];
				$list[$key]['logo']			=$k['logo'];
                $list[$key]['logo']			=$k['logo'];
                $list[$key]['joblist']			=array();
                $distance=$this->GetDistance($_POST['x'],$_POST['y'],$k['x'],$k['y']);
                if(is_float($distance)){
                    if($distance<=1){
                        $distance=ceil($distance*1000).'m';
                    }else{
                        $distance=round($distance, 2).'km';
                    }
                }else{
                    $distance='距离不详';
                }

 
                $list[$key]['distance']			=$distance;
                $list[$key]['sdate']			=$k['sdate'];
                $list[$key]['cityid']			=$k['cityid'];
                $list[$key]['provinceid']			=$k['provinceid'];
                $list[$key]['three_cityid']			=$k['three_cityid'];
			}
			foreach($list as $k=>$v){
				if(is_array($v)){
					foreach($v as $key=>$val){
						$list[$k][$key]=isset($val)?$val:'';
					}
				}else{
					$list[$k]=isset($v)?$v:'';
				}
			}
			$data['list']=count($list)?$list:array();
			$data['error']=1;
		}else{
			$data['error']=2;
		}
		echo json_encode($data);die;
	}
    function joblist_action(){
		$where="`r_status`<>'2'";
		$limit=!$limit?10:$limit;
		$xy=getAround($_POST[x],$_POST[y],$_POST[r]);
		if($xy[0]){
			$where.=" AND `x`>='".$xy[0]."' AND `x`<='".$xy[1]."' AND `y`>='".$xy[3]."' AND `y`<='".$xy[2]."'";
		}
		if($_POST['hy']){
			$where .= " AND `hy` = '".(int)$_POST['hy']."'";
		}
		if($_POST['provinceid']){
			$where .= " AND `provinceid` = '".(int)$_POST['provinceid']."'";
		}

		if($_POST['cityid']){
			$where .= " AND `cityid` = '".(int)$_POST['cityid']."'";
		}
		if($_POST['three_cityid']){
			$where .= " AND `three_cityid` = '".(int)$_POST['three_cityid']."'";
		}
		$where.=" order by jobtime desc";
		$rows=$this->obj->DB_select_all("company",$where);
		
		$jobwhere=1;
        if(is_array($rows)){
            foreach($rows as $k=>$v){
                if(is_numeric($v['uid'])&&!in_array($v['uid'],$UIDList)){
                    $UIDList[]=$v['uid'];
                }
            }
        }
		$page=$_POST['page'];
		if($page){
			$pagenav=($page-1)*$limit;
			$where.=" limit $pagenav,$limit";
		}else{
			$where.=" limit $limit";
		}
        if(is_array($UIDList)&&count($UIDList)>0){
            $jobwhere.=' and `uid` in ('.pylode(",",$UIDList).')';
        }
		if($_POST['job1']){
			$jobwhere.=" AND `job1`='".(int)$_POST['job1']."'";
		}
		if($_POST['job1_son']){
			$jobwhere.=" AND `job1_son`='".(int)$_POST['job1_son']."'";
		}
		if($_POST['job_post']){
			$jobwhere.=" AND `job_post`='".(int)$_POST['job_post']."'";
		}
		$joball=$this->obj->DB_select_all("company_job",$jobwhere);
		if(is_array($joball)){
			foreach($joball as $key=>$va){
				$list[$key]['id']		=$va['id'];
				$list[$key]['name']		=$va['name'];
				$list[$key]['comid']	=$va['uid'];
				$list[$key]['comname']	=$va['com_name'];
				$list[$key]['hy']		=$va['hy'];
				$list[$key]['job1']		=$va['job1'];
				$list[$key]['job1_son']	=$va['job1_son'];
				$list[$key]['job_post']	=$va['job_post'];
				$list[$key]['provinceid']=$va['provinceid'];
				$list[$key]['cityid']	=$va['cityid'];
				$list[$key]['three_cityid']=$va['three_cityid'];
				$list[$key]['salary']	=$va['salary'];
				$list[$key]['type']		=$va['type'];
				$list[$key]['number']	=$va['number'];
				$list[$key]['exp']		=$va['exp'];
				$list[$key]['report']	=$va['report'];
				$list[$key]['edu']		=$va['edu'];
				$list[$key]['state']	=$va['state'];
				$list[$key]['sex']		=$va['sex'];
				$list[$key]['marriage']	=$va['marriage'];
				$list[$key]['description']=$va['description'];
				$list[$key]['xuanshang']=$va['xuanshang'];
				$list[$key]['sdate']	=$va['sdate'];
				$list[$key]['edate']	=$va['edate'];
				$list[$key]['jobhits']	=$va['jobhits'];
				$list[$key]['lastupdate']=$va['lastupdate'];
				$list[$key]['rec']		=$va['rec'];
				$list[$key]['cloudtype']=$va['cloudtype'];
				$list[$key]['statusbody']=$va['statusbody'];
				$list[$key]['age']		=$va['age'];
                foreach($joball as $key=>$va){
                    foreach($rows as $comKey=>$comVal){
                        if($va['uid']==$comVal['uid']){
                            $list[$key]['address']=$comVal['address'];
                            if($comVal['shortname']){
                            	$list[$key]['comname']=$comVal['shortname'];
                            }else{
                            	$list[$key]['comname']=$comVal['name'];
                            }
                           
                        }
                    }
                }
			}
			foreach($list as $k=>$v){
				if(is_array($v)){
					foreach($v as $key=>$val){
						$list[$k][$key]=isset($val)?$val:'';
					}
				}else{
					$list[$k]=isset($v)?$v:'';
				}
			}
			$data['list']=count($list)?$list:array();
			$data['error']=1;
		}else{
			$data['error']=2;
		}
		echo json_encode($data);die;
	}
    function GetDistance($lat1, $lng1, $lat2, $lng2){
        define('PI',3.1415926535898);
        define('EARTH_RADIUS',6378.137);
        $radLat1 = $lat1 * (PI / 180);
        $radLat2 = $lat2 * (PI / 180);

        $a = $radLat1 - $radLat2;
        $b = ($lng1 * (PI / 180)) - ($lng2 * (PI / 180));

        $s = 2 * asin(sqrt(pow(sin($a/2),2) + cos($radLat1)*cos($radLat2)*pow(sin($b/2),2)));
        $s = $s * EARTH_RADIUS;
        $s = round($s * 10000) / 10000;
        return $s;
    }
}
?>