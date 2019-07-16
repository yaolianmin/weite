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
class siteadmin_controller extends adminCommon{
	function siteadmin_tpl($tpl){
        foreach($tpl as $k){
            $this->yuntpl(array('siteadmin/'.$k));
        }
    }
    function site_fetchsql($Where){
        if(is_array($Where)){
            if(is_numeric($this->config['did'])){
                $Where['did']=$this->config['did'];
            }
            return $Where;
        }else{
            if(is_numeric($this->config['did'])){
                $Where='`did`='.$this->config['did'].' and '.$Where;
            }
            return $Where;
        }
    }
    function get_expect_member_list($type=1,$where,$field_resume,$field_expect){
		 $UserinfoM=$this->MODEL('userinfo');
        $ResumeM=$this->MODEL('resume');
        if($type=='1'){
            $ResumeList=$UserinfoM->GetUserinfoList(array("`name` like '%".trim($_GET['keyword'])."%'"),array('field'=>$field_resume,'usertype'=>1,'special'=>'resume')); 
			$EIDList=array();
            foreach($ResumeList as $k=>$v){
                $EIDList[]=$v['def_job'];
            }
            $ResumeExpectList=$ResumeM->GetResumeExpectList(array('`id` in ('.implode(',',$EIDList).')'),array('field'=>$field_expect,'special'=>'resume_expect'));
            foreach($ResumeList as $k1=>$v1){
                foreach($ResumeExpectList as $k2=>$v2){
                    if($v1['def_job']==$v2['id']){
                        $resume[]=array_merge($v1,$v2);
                    }
                }
            }
        }elseif($type=='2'){
            $ResumeExpectList=$ResumeM->GetResumeExpectList(array("`name` like '%".trim($_GET['keyword'])."%'"),array('field'=>$field_expect,'special'=>'resume_expect'));
            foreach($ResumeList as $k=>$v){
                $UIDList[]=$v['uid'];
            }
            $ResumeList=$UserinfoM->GetUserinfoList(array('`uid` in ('.implode(',',$UIDList).')'),array('field'=>$field_resume,'usertype'=>1,'special'=>'resume'));
            foreach($ResumeExpectList as $k1=>$v1){
                foreach($ResumeList as $k2=>$v2){
                    if($v1['uid']==$v2['uid'] && $v1['id']==$v2['def_job']){
                        $resume[]=array_merge($v1,$v2);
                    }
                }
            }
        }
        return $resume;
    }
    function get_resume_member_list($type=1,$where_resume,$where_member,$field_resume,$field_member,$limit){
        $UserinfoM=$this->MODEL('userinfo');
        if($type=='1'){
            $MemberList=$UserinfoM->GetMemberList($where_member,array('field'=>$field_member));
            foreach($MemberList as $k1=>$v1){
                $UIDList[]=$v1['uid'];
            }
            array_unshift($where_resume,'`uid` in ('.implode(',',$UIDList).')');
            $ResumeList=$UserinfoM->GetUserinfoList($where_resume,array('field'=>$field_resume,'usertype'=>1,'limit'=>$limit));
            foreach($MemberList as $k1=>$v1){
                foreach($ResumeList as $k2=>$v2){
                    if($v1['uid']==$v2['uid']){
                        $resume[]=array_merge($v1,$v2);
                    }
                }
            }
        }elseif($type=='2'){
            $ResumeList=$UserinfoM->GetUserinfoList($where_resume,array('field'=>$field_resume,'usertype'=>1));
            foreach($ResumeList as $k1=>$v1){
                $UIDList[]=$v1['uid'];
            }
            array_unshift($where_resume,'`uid` in ('.implode(',',$UIDList).')');
            $MemberList=$UserinfoM->GetMemberList($where_member,array('field'=>$field_member,'limit'=>$limit));
            foreach($MemberList as $k1=>$v1){
                foreach($ResumeList as $k2=>$v2){
                    if($v1['uid']==$v2['uid']){
                        $resume[]=array_merge($v1,$v2);
                    }
                }
            }
        }
        return $resume;
    }
    function get_admin_user_list(){
        return $this->obj->DB_select_alls("admin_user","admin_user_group","a.`m_id`=b.`id` and a.`uid`='".$_SESSION['auid']."'");
    }
}
?>