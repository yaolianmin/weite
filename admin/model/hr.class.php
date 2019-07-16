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
class hr_controller extends adminCommon{
 
	function set_search(){
		$search_list[]=array("param"=>"status","name"=>'前台显示',"value"=>array("1"=>"显示","0"=>"不显示"));
		$ad_time=array('1'=>'今天','3'=>'最近三天','7'=>'最近七天','15'=>'最近半月','30'=>'最近一个月');
		$search_list[]=array("param"=>"end","name"=>'上传日期',"value"=>$ad_time);
		$this->yunset("search_list",$search_list);
	}
	function index_action(){
		$where="1";
		$this->set_search();
		$t_class=$this->obj->DB_select_all("toolbox_class");
		if($_GET["type"]!=""){
			if($_GET["type"]=="2"){
				$class=$this->obj->DB_select_all("toolbox_class","`name` like '%".trim($_GET['keyword'])."%'","`id`");
				foreach($class as $val){
					$cids[]=$val['id'];
				}
				$where.=" and `cid` in(".@implode(',',$cids).")";
			}elseif($_GET["type"]=="1"){
				$where.=" and  `name` LIKE '%".trim($_GET['keyword'])."%'";
			}
			$urlarr["keyword"]=$_GET["keyword"];
			$urlarr["type"]=$_GET["type"];
		}
		if($_GET['status']!=''){
			$where.=" and `is_show` = '".$_GET['status']."'";
		}
		if($_GET['end']){
			if($_GET['end']=='1'){
				$where.=" and `add_time` >= '".strtotime(date("Y-m-d 00:00:00"))."'";
			}else{
				$where.=" and `add_time` >= '".strtotime('-'.(int)$_GET['end'].'day')."'";
			}
			$urlarr['end']=$_GET['end'];
		}
		if($_GET['order'])
		{
			$where.=" order by ".$_GET['t']." ".$_GET['order'];
			$urlarr['order']=$_GET['order'];
			$urlarr['t']=$_GET['t'];
		}else{
			$where.=" order by id desc";
		}
		$urlarr['page']="{{page}}";
		$pageurl=Url($_GET['m'],$urlarr,'admin');
		$rows=$this->get_page("toolbox_doc",$where,$pageurl,$this->config['sy_listnum']);
		if(is_array($rows)){
			foreach($rows as $key=>$val){
				foreach($t_class as $value){
					if($val['cid']==$value['id']){
						$rows[$key]['cname']=$value['name'];
					}
				}
			}
		}
		$this->yunset("get_type", $_GET);
		$this->yunset("rows",$rows);
		$this->yuntpl(array('admin/admin_hr_toolbox'));
	}
	function add_action(){
		if($_GET['id']){
			$row=$this->obj->DB_select_once("toolbox_doc","`id`='".$_GET['id']."'");
			$this->yunset("row", $row);
		}
		$t_class=$this->obj->DB_select_all("toolbox_class");
		$this->yunset("t_class", $t_class);
		$this->yuntpl(array('admin/admin_hr_adddoc'));
	}
	function save_action(){
		if($_POST['submit']){

			if($_POST['name'] == ''){
				$this->ACT_layer_msg("文档名称不能为空！",8,$_SERVER['HTTP_REFERER']);
			}else if($_POST['cid'] == ''){
				$this->ACT_layer_msg("请选择文档分类！",8,$_SERVER['HTTP_REFERER']);
			}

			$value="`name`='".$_POST['name']."',";
			$value.="`cid`='".$_POST['cid']."',";
			$value.="`is_show`='".$_POST['is_show']."',";
			$value.="`add_time`='".time()."',";
			
			if($_FILES['file']['name']==''){
				
				if($_POST['url']==''){

					$this->ACT_layer_msg("请选择上传文档！",8,$_SERVER['HTTP_REFERER']);

				}else{
					$_POST['url']=str_replace($this->config['sy_weburl'],"",$_POST['url']);
					$value.="`url`='".$_POST['url']."'";
				}
				$nbid=$this->obj->DB_update_all("toolbox_doc",$value,"`id`='".$_POST['id']."'");
				isset($nbid)?$this->ACT_layer_msg("文档(".$_POST['id'].")更新成功！",9,"index.php?m=hr",2,1):$this->ACT_layer_msg("更新失败！",8,"index.php?m=hr");

			}else{
				
				$nametype=@explode('.',$_FILES['file']['name']);
				$ntype=array('doc', 'docx');

				if(!in_array(strtolower(end($nametype)),$ntype)){
					$this->ACT_layer_msg("请上传doc、docx格式文件！",8,$_SERVER['HTTP_REFERER']);
				}

				if(!is_dir("../data/upload/hrdoc/".date("Ymd"))){
					mkdir("../data/upload/hrdoc/".date("Ymd"),0777,true);
				}

				$upload="upload/hrdoc/".date("Ymd")."/".time().$this->uid.'.'.strtolower(end($nametype));
				$urlname="/data/".$upload;
				$pathname=DATA_PATH.$upload;
 
				$result=move_uploaded_file($_FILES['file']['tmp_name'],$pathname);

				if($result==true){

					$_POST['url']=str_replace(DATA_PATH,"",$urlname);
					$value.="`url`='".$_POST['url']."'";
					
					if($_POST['id']==''){
						$nbid=$this->obj->DB_insert_once("toolbox_doc",$value);
						isset($nbid)?$this->ACT_layer_msg("文档(".$nbid.")添加成功！",9,"index.php?m=hr",2,1):$this->ACT_layer_msg("添加失败！",8,"index.php?m=hr");
					}else{
						$nbid=$this->obj->DB_update_all("toolbox_doc",$value,"`id`='".$_POST['id']."'");
						isset($nbid)?$this->ACT_layer_msg("文档(".$_POST['id'].")更新成功！",9,"index.php?m=hr",2,1):$this->ACT_layer_msg("更新失败！",8,"index.php?m=hr");
					}
				}
			}
		}
	}
	function del_action(){
		if($_GET['del']){
			$this->check_token();
			if(is_array($_GET['del']))
			{
				$del=@implode(",",$_GET['del']);
				$layer_type=1;
			}else{
				$del=$_GET['del'];
				$layer_type=0;
			}
		}
		$row=$this->obj->DB_select_all("toolbox_doc","`id` in (".$del.")");
		if(is_array($row)){
			foreach($row as $v){
				@unlink("..".$v['url']);
			}
		}
		$delid=$this->obj->DB_delete_all("toolbox_doc","`id` in ($del)","");
		$delid?$this->layer_msg('文档(ID:'.$del.')删除成功！',9,$layer_type,$_SERVER['HTTP_REFERER']):$this->layer_msg('删除失败！',8,$layer_type,$_SERVER['HTTP_REFERER']);
	}
    function show_action(){
		if((int)$_GET['id']){
			$nid=$this->obj->DB_update_all("toolbox_doc","`".$_GET['type']."`='".(int)$_GET['rec']."'","`id`='".(int)$_GET['id']."'");
			echo $nid?1:0;
		}
	}

}

?>