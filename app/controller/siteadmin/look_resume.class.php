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
class look_resume_controller extends siteadmin_controller{ 
	function set_search(){
		$lo_time=array('1'=>'今天','3'=>'最近三天','7'=>'最近七天','15'=>'最近半月','30'=>'最近一个月');
		$search_list[]=array('param'=>'time','name'=>'下载时间','value'=>$lo_time);
		$this->yunset('search_list',$search_list);
	}
	function index_action(){
		$where = '1';
		$this->set_search();
        $UserinfoM=$this->MODEL('userinfo');
        $ResumeM=$this->MODEL('resume');
		if(trim($_GET['keyword'])){
			if((int)$_GET['type']=="3"){
				$company=$UserinfoM->GetUserinfoList(array("`name` like '%".trim($_GET['keyword'])."%'"),array('field'=>"name,uid",'usertype'=>2,'special'=>'company'));
				if(is_array($company)){
					foreach($company as $v){
						$com_id[]=$v['uid'];
					}
				}
				$where.=" and `com_id` in (".@implode(",",$com_id).")";
			}else{
                $resume=$this->get_expect_member_list((int)$_GET['type'],"`name` like '%".trim($_GET['keyword'])."%'","name,uid,def_job",'`name` as resume_name,`id`,`uid`');
				if(is_array($resume)){
					foreach($resume as $v){
						$resume_id[]=$v['id'];
					}
				}
				$where.=" and `resume_id` in (".@implode(",",$resume_id).")";
			}
			$urlarr['type']=(int)$_GET['type'];
			$urlarr['keyword']=$_GET['keyword'];
		}
		if((int)$_GET['time']){
			if((int)$_GET['time']=='1'){
				$where.=" and `datetime` >= '".strtotime(date("Y-m-d 00:00:00"))."'";
			}else{
				$where.=" and `datetime` >= '".strtotime('-'.(int)$_GET['time'].'day')."'";
			}
			$urlarr['time']=$_GET['time'];
		}
		if($_GET['sdate']){
			$sdate=strtotime($_GET['sdate']);
			$where.=" and `datetime`>'$sdate'";
			$urlarr['sdate']=$_GET['sdate'];
		}
		if($_GET['edate']){
			$edate=strtotime($_GET['edate']);
			$where.=" and `datetime`<'$edate'";
			$urlarr['edate']=$_GET['edate'];
		}
		if($_GET['order']){
			$where.=" order by ".$_GET['t']." ".$_GET['order'];
			$urlarr['order']=$_GET['order'];
			$urlarr['t']=$_GET['t'];
		}else{
			$where.=' order by id desc';
		}
		$urlarr['page']='{{page}}';
		$pageurl=Url($_GET['m'],$urlarr,'admin');
		$list=$this->get_page('look_resume',$where,$pageurl,$this->config['sy_listnum']);
		if(is_array($list)){
			foreach($list as $v){
				$resume_ids[]=$v['resume_id'];
				$com_ids[]=$v['com_id'];
			}
			if(($_GET['type']!="1" && $_GET['type']!="2") || !trim($_GET['keyword'])){
                $ResumeExpectList=$ResumeM->GetResumeExpectList(array("`id` in (".@implode(",",$resume_ids).")"),array('field'=>'`name` as resume_name,`id`,`uid`','special'=>'resume_expect'));
                foreach($ResumeExpectList as $k=>$v){
                    $UIDList[]=$v['uid'];
                }
				
                $ResumeList=$UserinfoM->GetUserinfoList(array('`uid` in ('.implode(',',$UIDList).')'),array('field'=>'name,uid,def_job','usertype'=>1,'special'=>'resume'));
				
                foreach($ResumeExpectList as $k1=>$v1){
                    foreach($ResumeList as $k2=>$v2){
                        if($v1['uid']==$v2['uid']){
                            $resume[]=array_merge($v1,$v2);
                        }
                    }
                }
			}
			if($_GET['type']!="3" || !trim($_GET['keyword'])){
				$company=$UserinfoM->GetUserinfoList(array("`uid` in (".@implode(",",$com_ids).")"),array('field'=>"name,uid",'usertype'=>2,'special'=>'company'));
			}

			foreach($list as $k=>$v){
				foreach($resume as $val){
					if($v['resume_id']==$val['id']){
						$list[$k]['name']=$val['name'];
						$list[$k]['resume_name']=$val['resume_name'];
					}
				}
				foreach($company as $val){
					if($v['com_id']==$val['uid']){
						$list[$k]['com_name']=$val['name'];
					}
				}
			}
		}
		$this->yunset('list',$list);
		$this->siteadmin_tpl(array('look_resume'));
	}
	function del_action(){
		$this->check_token();
	    if($_GET['del']){
	    	if(is_array($_GET['del'])){
	    		$del=@implode(",",$_GET['del']);
	    		$layer_status=1;
	    	}else{
	    		$del=(int)$_GET['del'];
	    		$layer_status=0;
	    	}
			$this->MODEL('resume')->DeleteLookResume(array("`id` in (".$del.")"));
			$this->layer_msg( "简历浏览记录(ID:".$del.")删除成功！",9,$layer_status,$_SERVER['HTTP_REFERER']);
	   }else{
			$this->layer_msg( "请选择您要删除的信息！",8,1,$_SERVER['HTTP_REFERER']);
    	}
	}
}
?>