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
class comment_controller extends user{
	function index_action(){

		if($_GET['id']){
			$this->public_action();
			$msg = $this->obj->DB_select_once('userid_msg',"`id`='".(int)$_GET['id']."' AND `uid`='".$this->uid."'");
			if(!empty($msg)){
				if($msg['is_browse']=='3'){
					$jobInfo = $this->obj->DB_select_once("company_job","`id`='".$msg['jobid']."'","`id`,`uid`,`name`,`com_name`");
					$msgInfo = $this->obj->DB_select_once("company_msg","`msgid`='".$msg['id']."'");
					if(!empty($msgInfo)){
						if($msgInfo['tag']){
							$msgInfo['tag'] = @explode(',',$msgInfo['tag']);
						}
						
						$this->yunset("msgInfo",$msgInfo);
					}
					$this->yunset($this->MODEL('cache')->GetCache(array('com')));
					$this->yunset("msg",$msg);
					$this->yunset("jobInfo",$jobInfo);
					$this->user_tpl('comment');
				}else{
					$this->layer_msg('参与面试后方可评论！',8,0,$_SERVER['HTTP_REFERER']);
				}
			}else{
				$this->layer_msg('请选择正确的信息！',8,0,$_SERVER['HTTP_REFERER']);
				
			}
			
		}
	}

	function save_action(){
		if($_POST['id']){
			$id = (int)$_POST['id'];
			$msg = $this->obj->DB_select_once('userid_msg',"`id`='".$id."' AND `uid`='".$this->uid."' AND `is_browse`='3'");
			
			if(!empty($msg)){
				$msgInfo = $this->obj->DB_select_once("company_msg","`msgid`='".$msg['id']."'");
				if(!empty($msgInfo)){
					$this->ACT_layer_msg("请不要重复评论！",8,$_SERVER['HTTP_REFERER']);
				}else{
				
					$desscore = (int)$_POST['desscore'];
					$hrscore = (int)$_POST['hrscore'];
					$comscore = (int)$_POST['comscore'];
					$content  = strip_tags($_POST['content']);
					$othercontent  = strip_tags($_POST['othercontent']);

					if($desscore<1 || $hrscore<1 || $comscore<1 || !$content){
						$this->ACT_layer_msg("请完整填写评论信息！",8,$_SERVER['HTTP_REFERER']);
					}else{
						$score = round(($desscore+$hrscore+$comscore)/3,1);
						$data['uid'] = $this->uid;
						$data['cuid'] = $msg['fid'];
						$data['ctime'] = time();
						$data['jobid'] = $msg['jobid'];
						$data['desscore'] = $desscore;
						$data['hrscore'] = $hrscore;
						$data['comscore'] = $comscore;
						$data['score'] = $score;
						$data['status'] = 1;
						if($_POST['tag']){

							include PLUS_PATH.'com.cache.php';
							$tags = explode(',',$_POST['tag']);
							foreach($tags as $key=>$value){
								if($comclass_name[$value]){
									$tagsList[] = $value;
								}
							}
							$data['tag'] = @implode(',',$tagsList);
						}
						$data['did']=$this->userdid;
						$data['content'] = $content;
						$data['othercontent'] = $othercontent;
						$data['msgid'] = $msg['id'];
						$data['isnm'] = (int)$_POST['isnm'];
					
						$this->obj->insert_into("company_msg",$data);
						$this->ACT_layer_msg("面试评价成功！",9,'index.php?c=msg');
					}
				}


				
				
				
			}else{
				$this->ACT_layer_msg("暂不符合评论条件！",8,$_SERVER['HTTP_REFERER']);
			}
		}else{
			$this->ACT_layer_msg("请选择正确的信息！",8,$_SERVER['HTTP_REFERER']);
		}
	
	}
	
}
?>