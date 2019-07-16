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
class wx_controller extends adminCommon{  
 
	function set_search(){
		 
		$type=array('1'=>'关注微信','2'=>'绑定微信账户','3'=>'创建首份简历','4'=>'企业完善资料');
		$usertype=array('1'=>'个人','2'=>'企业','3'=>'猎头');
		$status=array('2'=>'失败','1'=>'成功');
		 
		$time=array('1'=>'今天','3'=>'最近三天','7'=>'最近七天','15'=>'最近半月','30'=>'最近一个月');
		$this->yunset("type",$type);
		$this->yunset("usertype",$usertype);
		$this->yunset("status",$status);
		$this->yunset("time",$time);
	 
		$search_list[]=array("param"=>"usertype","name"=>'用户类型',"value"=>$usertype);
		$search_list[]=array("param"=>"status","name"=>'状态',"value"=>$status);
		$search_list[]=array("param"=>"time","name"=>'发放时间',"value"=>$time);
		$search_list[]=array("param"=>"type","name"=>'红包类型',"value"=>$type);
		$this->yunset("search_list",$search_list);
	}
	function setwx_search(){
		 
		$status=array('2'=>'未登录','1'=>'已登录');
		$time=array('1'=>'今天','3'=>'最近三天','7'=>'最近七天','15'=>'最近半月','30'=>'最近一个月');
		 
		$this->yunset("status",$status);
		$this->yunset("time",$time);
		 
	 
		$search_list[]=array("param"=>"status","name"=>'状态',"value"=>$status);
		$search_list[]=array("param"=>"time","name"=>'登录时间',"value"=>$time);
	 
		$this->yunset("search_list",$search_list);
	}
	
	function index_action(){
		$this->yuntpl(array('admin/admin_wx'));
	}
	
	function wxtz_action(){
		$this->yuntpl(array('admin/admin_wxtz'));
	}
	
	function save_action(){
 		if($_POST["msgconfig"]){

			
			unset($_POST["msgconfig"]);
           
            foreach($_POST as $key=>$v){
		    	$config=$this->obj->DB_select_num("admin_config","`name`='$key'");
			   if($config==false){
				$this->obj->DB_insert_once("admin_config","`name`='$key',`config`='".$v."'");
			   }else{
				$this->obj->DB_update_all("admin_config","`config`='".$v."'","`name`='$key'");
			   }
		 	}
			$this->web_config();
			$this->ACT_layer_msg("微信配置更新成功！",9,$_SERVER['HTTP_REFERER'],2,1);
		}
	}
	function wxredpack_action(){
 		if($_POST["msgconfig"]){
			unset($_POST["msgconfig"]);
			unset($_POST["pytoken"]);
			foreach($_POST as $key=>$v){
		    	$config=$this->obj->DB_select_num("admin_config","`name`='$key'");
			   if($config==false){
				$this->obj->DB_insert_once("admin_config","`name`='$key',`config`='".$v."'");
			   }else{
				$this->obj->DB_update_all("admin_config","`config`='".$v."'","`name`='$key'");
			   }
		 	}
			$this->web_config();
			$this->ACT_layer_msg("微信红包配置更新成功！",9,$_SERVER['HTTP_REFERER'],2,1);
		}
		$this->yuntpl(array('admin/admin_wxredpack'));
	}
	function binduser_action(){

 		$where = "(`wxid`!='' &&  `wxid` is not null)";
		if(trim($_GET['keyword'])){
			$where.=" and `username` like '%".trim($_GET['keyword'])."%'";
			$urlarr['keyword']=$_GET['keyword'];
		}
		$order = " ORDER BY `wxbindtime` DESC";
		$urlarr['c']=$_GET['c'];  
		$urlarr['page']="{{page}}";
		$pageurl=Url($_GET['m'],$urlarr,'admin');
		$userList=$this->get_page("member",$where.$order,$pageurl,$this->config['sy_listnum'],"`uid`,`username`,`wxid`,`wxbindtime`");

		$this->yunset("userList",$userList);
		$this->yuntpl(array('admin/admin_wxbind'));
	}

	function keyword_action(){

 		$where = "`type`='8'";
		if(trim($_GET['keyword'])){
			$where.=" and `key_name` like '%".trim($_GET['keyword'])."%'";
			$urlarr['keyword']=trim($_GET['keyword']);
		}
		$order = " ORDER BY `num` DESC";
		$urlarr['c']=$_GET['c'];
		$urlarr['page']="{{page}}";
		$pageurl=Url($_GET['m'],$urlarr,'admin');
		$keyList=$this->get_page("hot_key",$where.$order,$pageurl,$this->config['sy_listnum']);

		$this->yunset("keyList",$keyList);
		$this->yuntpl(array('admin/admin_wxkey'));
	}

	function wxnav_action()
	{
  		$list = $this->obj->DB_select_all("wxnav","1 ORDER BY `sort` ASC");
		if(is_array($list)){
			foreach($list as $value){
				if($value['keyid']=='0' || $value['keyid']==''){
					$navlist[$value['id']] = $value;
				}
			}
			foreach($list as $val){
				foreach($navlist as $key=>$v){
					if($v['id']==$val['keyid']){
						$navlist[$key]['list'][] = $val;
					}
				}
			}
		}
		$this->yunset('navlist',$navlist);
		$this->yuntpl(array('admin/admin_wxnav'));
	}

	function wxlog_action()
	{
		$this->set_search();
 		$where = '1';
		if(trim($_GET['keyword'])){
			if($_GET['wtype']=='1'){
				$where.= "  AND `re_nick` like '%".trim($_GET['keyword'])."%' ";
            }elseif($_GET['wtype']=='2'){
				$mwhere.=" and `username` like '%".trim($_GET['keyword'])."%'";
            }
			$where.=" and (`username` like '%".trim($_GET['keyword'])."%' OR  `re_nick` like '%".trim($_GET['keyword'])."%')";
			$urlarr['keyword']=trim($_GET['keyword']);
		}

		if($_GET['status']){
			if($_GET['status']=='2'){
				$status = 0;
			}else{
				$status = $_GET['status'];
			}
			$where.=" and `status`='".$status."'";
			$urlarr['status']=trim($_GET['status']);
		}	
		if($_GET['usertype']){
			$where.=" and `usertype`='".$_GET['usertype']."'";
			$urlarr['usertype']=trim($_GET['usertype']);
		}	
		if($_GET['type']){
			$where.=" and `type`='".$_GET['type']."'";
			$urlarr['type']=trim($_GET['type']);
		}	
		if($_GET['time']){
			if($_GET['time']=='1'){
				$where .=" and `time`>'".strtotime(date("Y-m-d 00:00:00"))."'";
			}else{
				$where .=" and `time`>'".strtotime('-'.intval($_GET['time']).' day')."'";
			}
			$urlarr['time']=$_GET['time'];
		}	
		$urlarr['c']="wxlog";
		$urlarr['page']="{{page}}";
		$order = " ORDER BY `time` DESC";
		$pageurl=Url($_GET['m'],$urlarr,'admin');
		$logList=$this->get_page("wxredpack",$where.$order,$pageurl,$this->config['sy_listnum']);
		$this->yunset("logList",$logList);
		$this->yuntpl(array('admin/admin_wxlog'));
	}
	
	function wxqrcodelog_action()
	{
		$this->setwx_search();
 		$where = '1';
		if(trim($_GET['keyword'])){
			if($_GET['wtype']=='1'){
				$where.= "  AND `re_nick` like '%".trim($_GET['keyword'])."%' ";
            }elseif($_GET['wtype']=='2'){
				$mwhere.=" and `username` like '%".trim($_GET['keyword'])."%'";
            }
			$where.=" and (`username` like '%".trim($_GET['keyword'])."%' OR  `re_nick` like '%".trim($_GET['keyword'])."%')";
			$urlarr['keyword']=trim($_GET['keyword']);
		}

		if($_GET['status']){
			if($_GET['status']=='2'){
				$status = 0;
			}else{
				$status = $_GET['status'];
			}
			$where.=" and `status`='".$status."'";
			$urlarr['status']=trim($_GET['status']);
		}	
		if($_GET['usertype']){
			$where.=" and `usertype`='".$_GET['usertype']."'";
			$urlarr['usertype']=trim($_GET['usertype']);
		}	
		if($_GET['time']){
			if($_GET['time']=='1'){
				$where .=" and `time`>'".strtotime(date("Y-m-d 00:00:00"))."'";
			}else{
				$where .=" and `time`>'".strtotime('-'.intval($_GET['time']).' day')."'";
			}
			$urlarr['time']=$_GET['time'];
		}	
		$urlarr['c']="wxqrcodelog";
		$urlarr['page']="{{page}}";
		$order = " ORDER BY `time` DESC";
		$pageurl=Url($_GET['m'],$urlarr,'admin');
		$logList=$this->get_page("wxqrcode",$where.$order,$pageurl,$this->config['sy_listnum']);
		if(is_array($logList)){
			foreach($logList as $k=>$v){
				$uids[] = $v['unionid'];
			}
			$member = $this->obj->DB_select_all("member","`unionid` IN (".@implode(',',$uids).")","`unionid`,`username`,`usertype`");
			foreach($logList as $key=>$value){
				foreach($member as $v){
					if($value['unionid'] == $v['unionid']){
						$logList[$key]['username'] = $v['username'];
						$logList[$key]['usertype'] = $v['usertype'];
					}
				}
			}
		}
		$this->yunset("logList",$logList);
		$this->yuntpl(array('admin/admin_wxqrcodelog'));
	}
	
	public function edit_action()
	{
		if($_POST['name'] && $_POST['keyid']!=='')
		{
			$_POST['name'] = $_POST['name'];
			$_POST['key'] = $_POST['key'];
			$where = "`name`='".$_POST['name']."'";
			if($_POST['keyid']>0)
			{
				if(!$_POST['key'] && $_POST['type']!='view')
				{
					echo 1;
					exit();
				}elseif($_POST['key']!=""){

					$where = "(`name`='".$_POST['name']."' AND  `keyid`='".$_POST['keyid']."')";
				}
			}
			
			if($_POST['navid']>0)
			{
				$where .= " AND  `id`<>'".$_POST['navid']."'";
			}
			
 			$nav = $this->obj->DB_select_num("wxnav",$where);
			if($nav>0)
			{
				echo 2;
				exit();
			}
			
			unset($_POST['pytoken']);
			if($_POST['navid']>0)
			{
				$navid = $_POST['navid'];
				unset($_POST['navid']);

				$this->obj->update_once("wxnav",$_POST,array('id'=>$navid));
				$this->MODEL('log')->admin_log("微信菜单(ID:".$navid.")修改成功");
			}else{
				$navid=$this->obj->insert_into("wxnav",$_POST);
				$this->MODEL('log')->admin_log("微信菜单(ID:".$navid.")添加成功");
			}

			echo 3;
			exit();
		}else{
			echo 1;
			exit();
		}

	}
 	public function creat_action()
	{
 		$list = $this->obj->DB_select_all("wxnav","1 ORDER BY `keyid` ASC,`sort` ASC");
		if(is_array($list))
		{
			foreach($list as $value){
				if($value['keyid']=='0'){
					$navlist[$value['id']] = $value;
				}
			}
			foreach($list as $val){
				foreach($navlist as $key=>$v){
					if($v['id']==$val['keyid']){
						$navlist[$key]['list'][] = $val;
					}
				}
			} 
			 
			$CreatNav = array();
			$i=0;
			foreach($navlist as $key=>$value)
			{
				$t=0;
				$CreatNav['button'][$i]['name'] = urlencode(trim($value['name']));
				
				if(!empty($value['list'])){
					

					foreach($value['list'] as $k=>$v)
					{
						$CreatNav['button'][$i]['sub_button'][$t]['name'] = urlencode(trim($v['name']));
						if($v['type']=='view'){
							$CreatNav['button'][$i]['sub_button'][$t]['type'] = 'view';
							$CreatNav['button'][$i]['sub_button'][$t]['url'] = trim($v['url']);
						}elseif($v['type']=='click'){
							$CreatNav['button'][$i]['sub_button'][$t]['type'] = 'click';
							$CreatNav['button'][$i]['sub_button'][$t]['key'] = urlencode(trim($v['key']));
						}
						$t++;
					}
					
				}else{

					if($value['type']=='view'){
						$CreatNav['button'][$i]['type'] = 'view';
						$CreatNav['button'][$i]['url'] = trim($value['url']);
					}elseif($value['type']=='click'){
						$CreatNav['button'][$i]['type'] = 'click';
						$CreatNav['button'][$i]['key'] = urlencode(trim($value['key']));
					}
				}

				$i++;
				
			}
			 
 			$Token = getToken($this->config);

 			$DelUrl = 'https://api.weixin.qq.com/cgi-bin/menu/delete?access_token='.$Token;
			CurlPost($DelUrl);
			
			$Url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$Token;
			$result = CurlPost($Url,urldecode(json_encode($CreatNav)));
			
			$Info = json_decode($result);
			
			if($Info->errcode=='0' || $Info->errmsg=='ok'){
 				echo 1;die;
			}else{
 				echo 2;die;
			}
		}
	}
	
 	function delnav_action(){
		if($_POST['del']){
			$this->obj->DB_delete_all("wxnav","`id` in(".@implode(',',$_POST['del']).")",'');
			$this->obj->DB_delete_all("wxnav","`keyid` in(".@implode(',',$_POST['del']).")",'');
			$this->layer_msg('微信菜单(ID:'.@implode(',',$_POST['del']).')删除成功！',9,1,$_SERVER['HTTP_REFERER']);
		}
		if((int)$_GET['delid']){
			$this->check_token();
			$id=$this->obj->DB_delete_all("wxnav","`id`in(".$_GET['delid'].")","");
			$this->obj->DB_delete_all("wxnav","`keyid` in(".$_GET['delid'].")",""); 
			$id?$this->layer_msg('微信菜单(ID:'.$_GET['delid'].')删除成功！',9,0,$_SERVER['HTTP_REFERER']):$this->layer_msg('删除失败！',8,0,$_SERVER['HTTP_REFERER']);
		}
	}
	
 	function deluser_action(){
		if($_GET['del']){
			$this->check_token();
			$this->obj->DB_update_all("member","`wxid`=''","`uid` in(".@implode(',',$_GET['del']).")");
			$this->layer_msg('微信用户(ID:'.@implode(',',$_GET['del']).')取消绑定成功！',9,1,$_SERVER['HTTP_REFERER']);
		}
	}
	function ajax_action()
	{
		if($_POST['sort'])
		{
			$this->obj->DB_update_all("wxnav","`sort`='".$_POST['sort']."'","`id`='".$_POST['id']."'");
			$this->MODEL('log')->admin_log("微信菜单(ID:".$_POST['id'].")排序修改成功");
		}
		if($_POST['name'])
		{
			$this->obj->DB_update_all("wxnav","`name`='".$_POST['name']."'","`id`='".$_POST['id']."'");
			$this->MODEL('log')->admin_log("微信菜单(ID:".$_POST['id'].")名称修改成功");
		}
		echo '1';die;
	}
	
	function zdkeyword_action()
	{		
	    $where ="1";
		if(trim($_GET['keyword'])){
			$where.=" and `keyword` like '%".trim($_GET['keyword'])."%'";
			$urlarr['keyword']=trim($_GET['keyword']);
		}
		$order = " ORDER BY `time` DESC";
		$urlarr['c']=$_GET['c'];
		$urlarr['page']="{{page}}";
		$pageurl=Url($_GET['m'],$urlarr,'admin');
		$keyList=$this->get_page("wxzdkeyword",$where.$order,$pageurl,$this->config['sy_listnum']);
		$this->yunset("keyList",$keyList);		
		$this->yuntpl(array('admin/admin_zdkeyword'));
	}
 
	function clearwx_action()
	{	
		$del=$this->obj->DB_delete_all("wxqrcode","DATEDIFF(CURDATE(),FROM_UNIXTIME(`time`, '%Y-%m-%d %H:%i:%S')) > 3"," ");		
		echo $del ? '清理完成！' : '删除失败！';
	}
	
	function zdaddkeyword_action()
	{	
	    $id=(int)$_GET['id'];
		$row=$this->obj->DB_select_once('wxzdkeyword',"`id`='".$id."'");
	    $this->yunset("row",$row);
		if($_POST["submit"]){          
			if(trim($_POST['title'])==''){
				$this->ACT_layer_msg("规则名称不能为空！",8);
			}	
			if(trim($_POST['keyword'])==''){
				$this->ACT_layer_msg("关键字不能为空！",8);
			}	
			if(trim($_POST['content'])==''){
				$this->ACT_layer_msg("回复内容不能为空！",8);
			}	  		  
			$this->obj->DB_insert_once("wxzdkeyword","`title`='".$_POST['title']."',`keyword`='".$_POST['keyword']."',`content`='".$_POST['content']."',`time`='".time()."'");
			$this->ACT_layer_msg("添加成功！",9,"index.php?m=wx&c=zdkeyword",2,1);
		}
		if($_POST["update"]){       
			$this->obj->DB_update_all('wxzdkeyword',"`title`='".$_POST['title']."',`keyword`='".$_POST['keyword']."',`content`='".$_POST['content']."',`time`='".time()."'","`id`='".$_POST['id']."'");	
	        $this->ACT_layer_msg("修改成功！",9,"index.php?m=wx&c=zdkeyword",2,1);		
		}
		$this->yuntpl(array('admin/admin_zdaddkeyword'));
	}
		
	function delkeyword_action(){
		extract($_GET);
		extract($_POST);
		if(is_array($del)){
			$delid=@implode(',',$del);
			$layer_type=1;
		}else{
			$this->check_token();
			$delid=$id;
			$layer_type=0;
		}
		if(!$delid){
			$this->layer_msg('请选择要删除的内容！',8);
		}
		$del=$this->obj->DB_delete_all("wxzdkeyword","`id` in (".$delid.")"," ");		
		$del?$this->layer_msg('(ID:'.$delid.')删除成功！',9,$layer_type,$_SERVER['HTTP_REFERER']):$this->layer_msg('删除失败！',8,$layer_type,$_SERVER['HTTP_REFERER']);
	}
}

?>