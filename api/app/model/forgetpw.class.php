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
class forgetpw_controller extends common{
	function index_action(){
		$_POST['username']=$_POST['username'];
		$_POST['desname']=$_POST['desname'];
		$_POST['desword']=$_POST['desword'];
		if(!$_POST['username']){
			$data['error']=2;
		}else{
			$username = $this->stringfilter($_POST['username']);
			$info = $this->obj->DB_select_once("member","`username`='".$_POST['username']."'");
			if(!is_array($info)){
				$data['error']=3;
			}else{
				if($this->config['sy_msg_getpass']=="1")
				{
					if($this->config['sy_msguser']==""||$this->config['sy_msgpw']==""||$this->config['sy_msgkey']=="")
					{
						$data['error']=4;
					}else{
						$pass =array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z","a","b","c","d","e","f","g","h","i","g","k","l","m","n","o","p","q","r","s","t","u","v","w","x","w","z","1","2","3","4","5","6","7","8","9","0");
						$len = rand(8,12);
						for($i=0;$i<$len;$i++)
						{
							$k = rand(0,36);
							$password.=$pass[$k];
						}
						if($this->config['sy_uc_type']=="uc_center" && $info['name_repeat']!="1")
						{
							$this->uc_open();
							uc_user_edit($info['username'], "", $password, $info['email'],"0");
						}else{
							$salt = substr(uniqid(rand()), -6);
							$pass2 = md5(md5($password).$salt);
							$sql['password']=$pass2;
							$sql['salt']=$salt;
							$where['username']=$_POST['username'];
							$this->obj->update_once("member",$sql,$where);
						}
						$sendcode=rand(000000,999999);
            $notice = $this->MODEL('notice');
						$result = $notice->sendSMSType(array("username"=>$_POST['username'],"password"=>$password,"moblie"=>$info['moblie'],'sendcode'=>$sendcode,"type"=>"getpass")); 
						if($result['status'] != -1) {
							$data['error']=1;
						}else{
							$data['error']=6;
						}
						echo json_encode(array('status'=>$result['msg']));die;
					}
				}else{
					$data['error']=5;
				}
			}
		}
		echo json_encode($data);die;
	}
	function getlink_action(){
		$username=trim($_POST['username']);
		$info = $this->obj->DB_select_once("member","`username`='".$username."' or `email`='".$username."' or `moblie`='".$username."'","`email`,`moblie`,`username`");
		
		if(is_array($info)&&$info){
			$list=array(); 
      $list['username']= $info['username'];
			if($info['email']){
				$len = strlen($info['email'])/2;
				$list['email']=substr_replace($info['email'],str_repeat('*',$len),ceil(($len)/2),$len); 
			}
			if($info['moblie']){
				$len = strlen($info['moblie'])/2;
				$list['moblie']=substr_replace($info['moblie'],str_repeat('*',$len),ceil(($len)/2),$len); 
			} 
			$data['list']=count($list)?$list:array();
			$data['error']=1;
		}else{
			$data['error']=2;
		}
		echo json_encode($data);die;
	}
	function send_action(){
		$_POST['sendtype']=intval($_POST['sendtype']);
		$username=trim($_POST['username']);
		$info = $this->obj->DB_select_once("member","`username`='".$username."' or `email`='".$username."' or `moblie`='".$username."'","`email`,`moblie`,`username`,`uid`,`usertype`,`did`"); 
        if($info['uid']){ 
			$sendcode = rand(100000,999999);
      if($_POST['sendtype']==1){
        if($this->config['sy_email_set']!="1"){ 
          $data['error']=3;
					echo json_encode($data);die;
        }elseif($this->config['sy_email_getpass']=="2"){
					$data['error']=4;
					echo json_encode($data);die; 
        }
        
				$check= $senddata['email']=$info['email'];
      }else{
        if(!$this->config["sy_msguser"] || !$this->config["sy_msgpw"] || !$this->config["sy_msgkey"]||$this->config['sy_msg_isopen']!='1'){
					$data['error']=5;
					echo json_encode($data);die;  
        }elseif($this->config['sy_msg_getpass']=="2"){
					$data['error']=6;
          echo json_encode($data);die;
        }
				$check= $senddata['moblie']=$info['moblie'];
      }
			$fdata=$this->forsend(array('uid'=>$info['uid'],'usertype'=>$info['usertype']));
			$senddata['uid']=$info['uid'];
            $senddata['username']=$info['username'];
			$senddata['name']=$fdata['name'];
			$senddata['type']="getpass"; 
			$senddata['sendcode']=$sendcode;
			$senddata['date']=date("Y-m-d"); 
			
      $notice = $this->MODEL('notice');
      if($_POST['sendtype']==1){
        $notice->sendEmailType($senddata);
      }
      else{
        $result = $notice->sendSMSType($senddata);
      }
			
			if($_POST['sendtype']=='1'){
				$data['sendcode']=$sendcode; 
				$data['error']=1; 
			}else{
				if($result['status'] != -1){
					$data['error']=1;  
					$data['sendcode']=$sendcode;  
				}else{
					$data['error']=7;  
				}
			}
			if($data['error']=='1'){
				$this->obj->DB_delete_all("company_cert","`uid`='".$info['uid']."' and `type`='".$_POST['sendtype']."' and  `check`='".$check."'"," ");
				 
				$this->obj->DB_insert_once("company_cert","`type`='".$_POST['sendtype']."',`status`='0',`uid`='".$info['uid']."',`check2`='".$sendcode."',`check`='".$check."',`ctime`='".time()."',`did`='".$info['did']."'");  
			}
			echo json_encode($data);die;
        }else{
			$data['error']=2;
			echo json_encode($data);die;
        }
	}
	function editpw_action(){
		$password=trim($_POST['password']);
		$_POST['password2']=trim($_POST['password2']);
		$_POST['sendtype']=intval($_POST['sendtype']);
		$username=trim($_POST['username']);
		$info = $this->obj->DB_select_once("member","`username`='".$username."' or `email`='".$username."' or `moblie`='".$username."'","`email`,`moblie`,`username`,`uid`,`usertype`,`did`"); 
		if($_POST['sendtype']==1){
			$check=$info['email'];
		}else{
			$check=$info['moblie'];
		}
		$cert=$this->obj->DB_select_once("company_cert","`uid`='".$info['uid']."' and `type`='".$_POST['sendtype']."' and  `check`='".$check."'","`uid`,`check2`,`ctime`,`id`"); 
        if($_POST['code']!=$cert['check2']){
			$data['error']=2;  
			echo json_encode($data);die;
		}
        if(!$password){
			$data['error']=3;  
			echo json_encode($data);die;
		}
        if($password!=$_POST['password2']){
			$data['error']=4;  
			echo json_encode($data);die;
		}
        if($info['uid']){
            if($this->config[sy_uc_type]=="uc_center" && $info['name_repeat']!="1"){
                $this->uc_open();
                uc_user_edit($info[username], "", $password, $info['email'],"0");
            }

            $salt = substr(uniqid(rand()), -6);
            $pass = md5(md5($password).$salt);
			$this->obj->DB_update_all("member","`password`='".$pass."',`salt`='".$salt."'","`uid`='".$cert['uid']."'");  
            $data['error']=1;  
			echo json_encode($data);die;
        }else{
			$data['error']=5;  
			echo json_encode($data);die;
        }
    }
}
?>