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
	function index_action(){
		$this->get_moblie();
        $this->yunset("title","附近职位"); 
		$this->yuntpl(array('wap/map'));
	}
    function joblist_action(){
		$this->get_moblie();
        $CompanyM=$this->MODEL('company');
        $JobM=$this->MODEL('job');
        $page=$_POST['page'];
        $pagesize=10;
        $jobwhere="`edate`>'".time()."' and `state`='1' and `status`='0'";
        $where="`name`<>'' and `r_status`<>'2'";
       if($_POST['keyword']){
            $nwhere=' and `name` like \'%'.trim($_POST['keyword']).'%\'';
            $keyword=trim($_POST['keyword']);
        }else{
			if($_POST['minx']){
				$lng=" and `x`>='".$_POST['minx']."' AND `x`<='".$_POST['maxx']."' AND `y`>='".$_POST['miny']."' AND `y`<='".$_POST['maxy']."'";
				$where.=$lng;
			}
		}
		$Company=$JobM->GetComjobList(array($jobwhere),array("field"=>"uid","order"=>" group by `uid`"));
		foreach($Company as $v){
			$uid[]=$v['uid'];
		}
		$CompanyNum=$CompanyM->GetComNum(array($where.' and `uid` in ('.implode(',',$uid).')'));
        if(!$page || $page<=1){
            $pagelimit=10;
        }else if($page<=($CompanyNum/$pagesize)){
            $pagelimit=$pagesize*$page.','.$pagesize;
        }else{
            $pagelimit=($CompanyNum/$pagesize)*$page.','.$pagesize;
        }
        $CompanyList=$CompanyM->GetComList(array($where.' and `uid` in ('.implode(',',$uid).')'),array('limit'=>$pagelimit));
       	$UIDList=array();
        foreach($CompanyList as $k=>$v){
			if(in_array($v['uid'],$UIDList)==false){
				$UIDList[]=$v['uid'];
			}           
        } 
        $JobList=$JobM->GetComjobList(array("`uid` in (".implode(',',$UIDList).") and ".$jobwhere));
        foreach($CompanyList as $k1=>$v1){
            foreach($JobList as $k2=>$v2){
                if($v1['uid']==$v2['uid']){
                    $CompanyList[$k1]['joblist'][]=$v2;
                }
            }
        }
    	$json_list=array();
        foreach($CompanyList as $k1=>$v1){
            $json_list[$k1]['name']=mb_substr($v1['name'],0,10,'utf-8');
            $json_list[$k1]['x']=$v1['x'];
            $json_list[$k1]['y']=$v1['y'];
            $json_list[$k1]['address']=$v1['address'];
            $json_list[$k1]['com_url']=Url('wap',array('c'=>'company','a'=>'show','id'=>$v1['uid']));
            foreach($CompanyList[$k1]['joblist'] as $k2=>$v2){
                $json_list[$k1]['joblist'][]=array('name'=>mb_substr($v2['name'],0,10,'utf-8'),'job_url'=>Url('wap',array('c'=>'job','a'=>'view','id'=>$v2['id'])));
            }
        }
        echo json_encode(array('list'=>$json_list,'pagecount'=>($CompanyNum/$pagesize+1)));die;
	}
}
?>