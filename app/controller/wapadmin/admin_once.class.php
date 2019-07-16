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
class admin_once_controller extends adminCommon{
	function index_action(){  

		$where=1;
		if($_GET['status']){
			if($_GET['status']=="2"){
				$where.= " and `status`='0' and `edate`>'".time()."'";
			}else{
				$where.= " and `status`='".$_GET['status']."' and `edate`>'".time()."'";
			}
			$urlarr['status']=$_GET['status'];
		}
		$where.=" order by `id` desc";
		$urlarr['c']=$_GET['c'];
		$urlarr['page']="{{page}}";
		$pageurl=Url($_GET['m'],$urlarr,'admin');
		$rows=$this->get_page("once_job",$where,$pageurl,$this->config['sy_listnum']);
		include PLUS_PATH."/user.cache.php";
		include PLUS_PATH."/city.cache.php";			
		if(is_array($rows)){
			foreach($rows as $k=>$v){
				$rows[$k]['exp']=$userclass_name[$v['exp']];
			}
		}
		$CacheM=$this->MODEL('cache');
	    $CacheList=$CacheM->GetCache(array('city','job','user'));
        $this->yunset($CacheList);
		$this->yunset("rows",$rows);
		$this->yunset("headertitle","店铺招聘");
		$this->yunset('backurl','index.php?c=company');
		$this->yuntpl(array('wapadmin/admin_once'));
	}
	function show_action(){
		$row=$this->obj->DB_select_once("once_job","`id`='".$_GET['id']."'");
		$this->yunset($this->MODEL('cache')->GetCache(array('user','city')));
		$lasturl=$_SERVER['HTTP_REFERER'];
		if(strpos($lasturl, 'a=show')===false){
		    if(strpos($lasturl, 'c=admin_once')!==false){
		        $this->cookie->setcookie('lasturl',$lasturl,time()+300);
		        $_COOKIE['lasturl']=$lasturl;
		    }
		}
		$this->yunset('lasturl',$_COOKIE['lasturl']); 
		$this->yunset("row",$row);
		$this->yunset("headertitle","店铺招聘审核");
		$this->yunset('backurl','index.php?c=admin_once');
		$this->yuntpl(array('wapadmin/admin_once_show'));
	}
	function status_action(){
	    if($_POST['id']){
	        $nid=$this->obj->DB_update_all("once_job","`status`='".$_POST['status']."'","`id`='".$_POST['id']."'");
	        if($nid){
	            $this->layer_msg('店铺招聘审核(ID:'.$_POST['id'].')设置成功！',9,0);
	        }else{
	            $this->layer_msg('设置失败！',8);
	        }
	    }
	}
	function del_action(){
	    if($_GET['del']){
	    	$del=$_GET['del'];
	    	if(is_array($del)){
				$this->obj->DB_delete_all("once_job","`id` in(".@implode(',',$del).")"," ");
				$this->layer_msg("职位(ID:".@implode(',',$del).")删除成功！",2,"index.php?c=admin_once");
	    	}else{
				$this->layer_msg("请选择您要删除的招聘！",2);
	    	}
	    }
	    if(isset($_GET['id'])){
			$result=$this->obj->DB_delete_all("once_job","`id`='".$_GET['id']."'" );
			$result?$this->layer_msg("店铺招聘(ID:".$_GET['id'].")删除成功！",9,0,"index.php?c=admin_once"):$this->layer_msg('删除失败！',8);
		}else{
			$this->layer_msg("非法操作！",8);
		}
	}
	function add_action(){
		$CacheM=$this->MODEL('cache');
	    $CacheList=$CacheM->GetCache(array('city','job','user'));
        $this->yunset($CacheList);
	    if($_GET['id']){
	        $row=$this->obj->DB_select_once("once_job","`id`='".intval($_GET['id'])."'");
	        
			$row['edate']=ceil(($row['edate']-mktime())/86400);
	        $this->yunset('row',$row);
	        $this->yunset("headertitle","店铺招聘修改");
	    }else{
	        $this->yunset("headertitle","店铺招聘添加");
	    }
	    
	    $_POST=$this->post_trim($_POST);
	    $id=(int)$_POST['id'];
	    if($_POST['add']){
	        if($_POST['title']==''){
	            $data['msg']='请填写职位名称！';
	        }
	        if($_POST['companyname']==''){
	            $data['msg']='请填写(店面)名称！';
	        }
	        if($_POST['phone']==''){
	            $data['msg']='请填写联系电话！';
	        }
	        if($_POST['phone']!='' &&!CheckMoblie($_POST['phone'])){
	            $data['msg']='联系电话格式错误！';
	        }
	        if($_POST['require']==''){
	            $data['msg']='请填写招聘要求！';
	        }
	        if($_POST['edate']==''){
	            $data['msg']='请填写有效时间！';
	        }
	        
	        if($_POST['password']){
	            $_POST['password']=md5($_POST['password']);
	        }
	         
	        if(is_uploaded_file($_FILES['pic']['tmp_name'])){
				$UploadM=$this->MODEL('upload');
	            $upload=$UploadM->Upload_pic("../data/upload/once/",false);
	            $pictures=$upload->picture($_FILES['pic']);
	            $picmsg=$UploadM->picmsg($pictures,$_SERVER['HTTP_REFERER']);
				if($picmsg['status']==$pictures){
					$data['msg']=$picmsg['msg'];
				}else{
					$pic=str_replace("../data/upload/once/","data/upload/once/",$pictures);
					$_POST['pic']=$pic;
				}
	        }else{
	            $_POST['pic']=$row['pic'];
	        }
	        if($_POST['title']!=''&&$_POST['companyname']!=''&&$_POST['phone']!=''&&$_POST['require']!=''&&$_POST['edate']!=''){
	            $_POST['edate']=strtotime("+".(int)$_POST['edate']." days");
				if($data['msg']==""){
					if($_POST['id']){
						$tid=$this->obj->DB_update_all('once_job',"`title`='".$_POST['title']."',`provinceid`='".$_POST['provinceid']."',`cityid`='".$_POST['cityid']."',`three_cityid`='".$_POST['three_cityid']."',`companyname`='".$_POST['companyname']."',`phone`='".$_POST['phone']."',`linkman`='".$_POST['linkman']."',`require`='".$_POST['require']."',`edate`='".$_POST['edate']."',`pic`='".$_POST['pic']."',`status`='1',`did`='".$this->config['did']."'","`id`='".$id."'");
						if($tid){
							$data['msg']='修改成功!';
							$data['url']='index.php?c=admin_once';
						}else{
							$data['msg']='修改失败!';
						}
					}else{
						$tid=$this->obj->insert_into('once_job', array('title'=>$_POST['title'],'provinceid'=>$_POST['provinceid'],'cityid'=>$_POST['cityid'],'three_cityid'=>$_POST['three_cityid'],'companyname'=>$_POST['companyname'],'phone'=>$_POST['phone'],'linkman'=>$_POST['linkman'],'require'=>$_POST['require'],'edate'=>$_POST['edate'],'pic'=>$_POST['pic'],'status'=>1,'did'=>$this->config['did'],'password'=>$_POST['password']));
						if($tid){
							$data['msg']='添加成功!';
							$data['url']='index.php?c=admin_once';
						}else{
							$data['msg']='添加失败!';
						}
					} 
				}else{
					$data['msg']=$data['msg'];
					$data['url']='index.php?c=admin_once';
				}
	        }
	        if($data){
	            $this->yunset("layer",$data);
	        }
	    }
	    if($_GET['id']){
	    	$this->yunset('backurl','index.php?c=admin_once&a=show&id='.$_GET['id']);
	    }else{
	    	$this->yunset('backurl','index.php?c=admin_once');
	    }		
	    $this->yuntpl(array('wapadmin/admin_once_add'));
	}
}
?>