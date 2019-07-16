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
class down_resume_controller extends lietou{
 	function index_action(){
 	    $where="`comid`='".$this->uid."'";
 	    $urlarr['c']='down_resume';
 	    $urlarr["page"]="{{page}}";
 	    $pageurl=Url('member',$urlarr);
 	    $list=$this->get_page("down_resume","$where order by id desc",$pageurl,"10");
 	    if(is_array($list)&&$list){
            foreach($list as $v){
                $uid[]=$v['uid'];
            }
            $resume=$this->obj->DB_select_alls("resume","resume_expect","a.uid=b.uid and a.`r_status`<>'2' and a.uid in (".@implode(",",$uid).")","a.`name`,a.`uid`,b.`hy`,b.`cityid`,b.`job_classid`,b.`lastupdate`,b.`height_status`");
 	        if(is_array($resume)){
 	            include(PLUS_PATH."user.cache.php");
 	            include(PLUS_PATH."job.cache.php");
 	            include PLUS_PATH."/industry.cache.php";
 	            include PLUS_PATH."/city.cache.php";
 	            foreach($list as $key=>$val){
 	                foreach($resume as $va){
 	                    if($val['uid']==$va['uid']){
 	                        $list[$key]['name']=$va['name'];
 	                        $list[$key]['hy']=$industry_name[$va['hy']];
 	                        $list[$key]['cityid']=$city_name[$va['cityid']];
 	                        $list[$key]['lastupdate']=$va['lastupdate'];
 	                        $list[$key]['height_status']=$va['height_status'];
 	                        if($va['job_classid']!=""){
 	                            $job_classid=@explode(",",$va['job_classid']);
 	                            $list[$key]['joblist']=$job_name[$job_classid[0]];
 	                        }
 	                    }
 	                }
 	            }
 	        }
 	    }
 	    $this->yunset("list",$list);
		$this->public_action();
		$this->lietou_tpl('down_resume');
 	}
 	function del_action(){
 		if($_POST['delid'] || $_GET['del']){
 			if($_POST['delid']){
 				$delid=pylode(',',$_POST['delid']);
 				$layer_status=1;
 			}else{
 				$delid=(int)$_GET['del'];
 				$layer_status=0;
 			}
			$nid=$this->obj->DB_delete_all("down_resume","`comid`='".$this->uid."' and `id` in (".$delid.")","");
 			if($nid){
 				$this->obj->member_log("删除下载的简历");
 				$this->layer_msg('删除成功！',9,$layer_status,$_SERVER['HTTP_REFERER']);
 			}else{
 				$this->layer_msg('删除失败！',8,$layer_status,$_SERVER['HTTP_REFERER']);
 			}
 		}
 	}
}
?>