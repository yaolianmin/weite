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
class advertise_controller extends siteadmin_controller{
	function set_search(){
		$search_list[]=array("param"=>"is_check","name"=>'审核状态',"value"=>array("1"=>"已过期","2"=>"已审核","-1"=>"未审核"));
		$search_list[]=array("param"=>"ad","name"=>'广告类型',"value"=>array("1"=>"文字广告","2"=>"图片广告","3"=>"FLASH广告"));
		$this->yunset("search_list",$search_list);
	}
	function index_action(){
		$this->set_search();
		$where = '1';
		if($_GET['is_check']){
			if($_GET['is_check']=='1'){
				$where .=" AND `time_end`<'".date("Y-m-d",time())."'";
				$urlarr['end']=1;
			}
			if($_GET['is_check']=='-1'){
				$where .=" AND `is_check`='0'";
				$urlarr['is_check']=$_GET['is_check'];
			}elseif($_GET['is_check']=='2'){
				$where .=" AND `is_check`='1' AND `time_end`>'".date("Y-m-d",time())."' ";
				$urlarr['is_check']=$_GET['is_check'];
			}
		}
		if($_GET['class_id']){
			$where .=" AND `class_id`='".$_GET['class_id']."'";
			$urlarr['class_id']=$_GET['class_id'];
		}
		if($_GET['name']){
			$where .=" AND `ad_name` LIKE '%".$_GET['name']."%'";
			$urlarr['name']=$_GET['name'];
		}
		if($_GET['ad']){
			if($_GET['ad']=='1'){
                 $where .=" AND `ad_type`='word'";
			}
			if($_GET['ad']=='2'){
                 $where .=" AND `ad_type`='pic'";
			}
			if($_GET['ad']=='3'){
                 $where .=" AND `ad_type`='flash'";
			}
		}
		$where.=" order by  `class_id` desc,`id` desc";
		$urlarr['page']="{{page}}";
		$M=$this->MODEL("ad");
		$pageurl=Url($_GET['m'],$urlarr,'admin');
		$PageInfo=$M->get_page("ad",$where,$pageurl,$this->config['sy_listnum']);
		$this->yunset($PageInfo);
		$linkrows=$PageInfo['rows'];
		$class = $M->GetAdClass("",array("orderby"=>"`orders` desc"));
		$nclass=array();
		if(is_array($class)&&$class){
			foreach($class as $val){
				$nclass[$val['id']]=$val['class_name'];
			}
		}
		if(is_array($linkrows)){
			foreach($linkrows as $key=>$value){
				$start = @strtotime($value['time_start']);
				$end = @strtotime($value['time_end']." 23:59:59");
				$time = time();
				$linkrows[$key]['class_name'] = $nclass[$value['class_id']];
				if($value['is_check']=="1"){
					$linkrows[$key]['check']="<font color='green'>已审核</font>";
				}else{
					$linkrows[$key]['check']="<font color='red'>未审核</font>";
				}
				switch($value['ad_type']){
					case "word":$linkrows[$key]['ad_typename'] ="文字广告";
					break;
					case "pic":$linkrows[$key]['ad_typename'] ="<a href=\"javascript:void(0)\" class=\"preview\" url=\"".$value['pic_url']."\">图片广告</a>";
					break;
					case "flash":$linkrows[$key]['ad_typename'] ="FLASH广告";
					break;
					case "lianmeng":$linkrows[$key]['ad_typename'] ="联盟广告";
					break;
				}
				if($value['time_start']!="" && $start!="" &&($value['time_end']==""||$end!="")){
					if($value['time_end']=="" || $end>$time){
			
						if($value['is_open']=='1'&&$start<$time){
							$linkrows[$key]['type']="<font color='green'>使用中..</font>";
						}else if($start<$time&&$value['is_open']=='0'){
							$linkrows[$key]['type']="<font color='red'>已停用</font>";
						}elseif($start>$time && ($end>$time || $value['time']=="")){
							$linkrows[$key]['type']="<font color='#ff6600'>广告暂未开始</font>";
						}
					}else{
						$linkrows[$key]['type']="<font color='red'>过期广告</font>";
						$linkrows[$key]['is_end']='1';
					}
				}else{
					$linkrows[$key]['type']="<font color='red'>无效广告</font>";
				}
			}
		}
		$ad_time=array('1'=>'一天','3'=>'最近三天','7'=>'最近七天','15'=>'最近半月','30'=>'最近一个月');
        $this->yunset("ad_time",$ad_time);
		$this->yunset("get_type", $_GET);
		$this->yunset("nclass",$nclass);
		$this->yunset("class",$class);
		$this->yunset("linkrows",$linkrows);
		$this->yuntpl(array('siteadmin/admin_advertise'));
	}
	function ad_add_action() {
		$class = $this->obj->DB_select_all("ad_class","1 order by `orders` desc");

		include PLUS_PATH."/domain_cache.php";

		if($_GET['id']){
		    $info=$this->obj->DB_select_once("ad","id='".$_GET['id']."'");
		    $class = $this->obj->DB_select_all("ad_class","1 order by `orders` desc");
		    $this->yunset("class",$class);
		    $this->yunset("info",$info);
		    $this->yunset("lasturl",$_SERVER['HTTP_REFERER']);
		}
		$this->yunset("class",$class);
		$this->yuntpl(array('siteadmin/admin_advertise_add'));
	}
	function ad_saveadd_action(){
	 	include_once("model/advertise_class.php");
	 	$_POST['did']=$this->config['did'];
	 	$adver = new advertise($this->obj,$this);
		if($_FILES['ad_url']['size']>0){
		 	if($_POST['ad_type']=="flash"){
		 		$time = time();
				$flash_name = $time.rand(0,999).".swf";
		 		move_uploaded_file($_FILES['ad_url']['tmp_name'],DATA_PATH."/upload/flash/$flash_name");
		 		$pictures = "../data/upload/flash/".$flash_name;
		 	}else{
				$UploadM = $this->MODEL('upload');
		 		$upload = $UploadM->Upload_pic("../data/upload/pimg/");
		 		$pictures=$upload->picture($_FILES['ad_url']);
		 	}
		}
		$_POST['target']=$_POST['target']==2?2:1;
		$html = $adver->model_saveadd_action($_POST,$pictures);
	}
	function modify_save_action()
	{
		include_once("model/advertise_class.php");
	 	$_POST['did']=$this->config['did'];
	 	$adver = new advertise($this->obj,$this);
		$UploadM=$this->MODEL('upload');
		if($_FILES['upload_pic']['size']>0 && $_POST['ad_type'] == 'pic')
	 	{

	 		$upload = $UploadM->Upload_pic("../data/upload/pimg/");
	 		$pictures=$upload->picture($_FILES['ad_url']);	 
		}
		else if($_FILES['upload_flash']['size']>0 && $_POST['ad_type'] == 'flash'){
			$time = time();
			$flash_name = $time.rand(0,999).".swf";
			move_uploaded_file($_FILES['ad_url']['tmp_name'],DATA_PATH."/upload/flash/".$flash_name);
			$pictures = "../data/upload/flash/".$flash_name;
		}
		$adver->model_modify_save_action($_POST,$pictures);
	}
	function modify_action()
	{
		$M=$this->MODEL("ad");
		$ad_info = $M->GetAdOne(array("id"=>$_GET['id']));
		$class = $M->GetAdClass("",array("orderby"=>"`orders` desc"));
		$this->yunset("class",$class);
		$this->yunset("ad_info",$ad_info);
		$this->yunset("lasturl",$_SERVER['HTTP_REFERER']);
		$this->yuntpl(array('siteadmin/admin_advertise_add'));
	}

	function del_ad_action()
	{
		$this->check_token();
		$M=$this->MODEL("ad");
		if($_GET['id']){
			$ad=$M->GetAdOne(array("id"=>$_GET['id']));
			if(is_array($ad)){
				unlink_pic($ad['pic_url']);
				@unlink($ad['flash_url']);
				$M->DeleteAd(array("id"=>$_GET['id']));
			}
		}
		$this->model_ad_arr_action();
		$this->layer_msg('广告(ID:'.$_GET['id'].')删除成功！',9,0,"index.php?m=advertise");
	}
	function ad_preview_action(){
		$M=$this->MODEL("ad");
		$ad=$M->GetAdOne(array("id"=>$_GET['id']));
		if($ad_type=="word"){
			$ad['html']="<a href='".$ad['word_url']."'>".$ad['word_info']."</a>";
		}else if($ad['ad_type']=='pic'){
			if(@!stripos("ttp://",$ad['pic_url'])){
				$pic_url = str_replace("../",$this->config['sy_weburl']."/",$ad['pic_url']);
			}
			$height = $width="";
			if($ad['pic_height']){
				$height = "height='".$ad['pic_height']."'";
			}
			if($ad['pic_width']){
				$width = "width='".$ad['pic_width']."'";
			}
			$ad['html']="<a href='".$ad['pic_src']."' target='_blank' rel='nofollow'><img src='".$pic_url."'  ".$height." ".$width." ></a>";
		}else if($ad['ad_type']=='flash'){
			if(@!stripos("ttp://",$ad['flash_url'])){
				$flash_url = str_replace("../",$this->config['sy_weburl']."/",$ad['flash_url']);
			}
			$ad['html']="<object type='application/x-shockwave-flash' data='".$flash_url."' width='".$ad['flash_width']."' height='".$ad['flash_height']."'><param name='movie' value='".$flash_url."' /><param value='transparent' name='wmode'></object>";
		}
		if(@strtotime($ad['time_end']." 23:59:59")<time()){
			$ad['is_end']='1';
		}
		$ad['src']=$this->config['sy_weburl']."/data/plus/yunimg.php?classid=".$ad['class_id']."&id=".$ad['id'];
		$this->yunset("ad",$ad);
		$this->yuntpl(array('siteadmin/admin_ad_preview'));
	}
	function ajax_check_action()
	{
		$M=$this->MODEL("ad");
		$M->UpdateAd(array("is_check"=>$_POST['val']),array("id"=>$_POST['id']));
		$this->model_ad_arr_action();
		if($_POST['val']=="1")
		{
			echo "<font color='green'>已审核</font>";
		}else{
			echo "<font color='red' >未审核</font>";
		}

	}
	function cache_ad_action()
	{
		$this->model_ad_arr_action();
		$this->layer_msg("广告更新成功！",9,0,"index.php?m=advertise");
	}
	function ctime_action()
	{
		extract($_POST);
		$id=$this->obj->DB_update_all("ad","`time_end`=DATE_ADD(time_end,INTERVAL ".$endtime." DAY)","`id` IN (".$jobid.")");
		$this->model_ad_arr_action();
		$id?$this->ACT_layer_msg("广告批量延期(ID:".$jobid.")设置成功！",9,$_SERVER['HTTP_REFERER'],2,1):$this->ACT_layer_msg("设置失败！",8,$_SERVER['HTTP_REFERER']);
	}
	function model_ad_arr_action()
	{
		$M=$this->MODEL("ad");
		$show.="<?php\r\n\$ad_label='';\r\n";
		$ad_list = $M->GetAd(array("is_open"=>"1"),array("orderby"=>"`sort` desc,`id` desc","special"=>'1'));
		if(is_array($ad_list)){
			$time = time();
			foreach($ad_list as $key=>$value){
				$start = @strtotime($value[time_start]." 00:00:00");
				$end = @strtotime($value[time_end]." 23:59:59");
				if($end!=""){
					if($end>$time){
						$end_type = 1;
					}else{
						$end_type = 2;
					}
				}else{$end_type=1;}
				if($start&&$start<$time && $end_type==1 && $value[is_check]=="1"){
					extract($value);
					if($ad_type=="word"){
						$show.= "\$ad_label['$value[class_id]']['ad_$id']['html']=\"<a href='$word_url'>$word_info</a>\";\r\n";
					}elseif($ad_type=="pic"){
						if(@!stripos("ttp://",$pic_url)){
							$pic_url = str_replace("../",$this->config["sy_weburl"]."/",$pic_url);
						}
						$height = $width="";
						if($pic_height){
							$height = "height='$pic_height'";
						}
						if($pic_width){
							$width = "width='$pic_width'";
						}
						if($this->config['sy_seo_rewrite']=='1')
						{
							$pic_src=$this->config['sy_weburl']."/c_clickhits-id_".$id.".html";
						}else{
							$pic_src=$this->config['sy_weburl']."/index.php?c=clickhits&id=".$id;
						}

						if($value['target']==1){
							$show.= "\$ad_label['$value[class_id]']['ad_$id']['html']=\"<a href='$pic_src' target='_blank' rel='nofollow'><img src='$pic_url'  ".$height." ".$width." ></a>\";\r\n";
						}else{
							$show.= "\$ad_label['$value[class_id]']['ad_$id']['html']=\"<a href='$pic_src' rel='nofollow'><img src='$pic_url' ".$height." ".$width." ></a>\";\r\n";
						}
						$show.= "\$ad_label['$value[class_id]']['ad_$id']['pic']=\"$pic_url\";\r\n";
						$show.= "\$ad_label['$value[class_id]']['ad_$id']['src']=\"$pic_src\";\r\n";
					}elseif($ad_type=="flash"){
						if(@!stripos("ttp://",$flash_url)){
							$flash_url = str_replace("../",$this->config["sy_weburl"]."/",$flash_url);
						}
						$show.= "\$ad_label['$value[class_id]']['ad_$id']['html']=\"<object type='application/x-shockwave-flash' data='$flash_url' width='$flash_width' height='$flash_height'><param name='movie' value='$flash_url' /><param value='transparent' name='wmode'></object>\";\r\n";
					}
					$show.= "\$ad_label['$value[class_id]']['ad_$id']['start']=\"".@strtotime(date('Y-m-d H:i:s',$start))."\";\r\n";
					$show.= "\$ad_label['$value[class_id]']['ad_$id']['end']=\"".@strtotime(date('Y-m-d H:i:s',$end))."\";\r\n";
					$show.= "\$ad_label['$value[class_id]']['ad_$id']['type']=\"".$ad_type."\";\r\n";
					$show.="\$ad_label['$value[class_id]']['ad_$id']['name']=\"".$ad_name."\";\r\n";
					$show.="\$ad_label['$value[class_id]']['ad_$id']['did']=\"".$did."\";\r\n";
					$show.="\$ad_label['$value[class_id]']['ad_$id']['id']=\"".$id."\";\r\n";
					$show.="\$ad_label['$value[class_id]']['ad_$id']['class_id']=\"".$value['class_id']."\";\r\n";
				}
			}
		}
		$show.="?>";
		$path = PLUS_PATH."/pimg_cache.php";
		$fp = @fopen($path,"w");
		@fwrite($fp,$show);
		@fclose($fp);
		@chmod($path,0777);
		$show="";
	}
}
?>