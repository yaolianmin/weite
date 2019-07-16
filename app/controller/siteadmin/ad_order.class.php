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
class ad_order_controller extends siteadmin_controller
{
	function set_search(){
		$search_list[]=array("param"=>"status","name"=>'审核状态',"value"=>array("1"=>"已审核","2"=>"未通过","-1"=>"未审核"));
		$ad_time=array('1'=>'今天','3'=>'最近三天','7'=>'最近七天','15'=>'最近半月','30'=>'最近一个月');
		$search_list[]=array("param"=>"end","name"=>'订单时间',"value"=>$ad_time);
		$this->yunset("search_list",$search_list);
	}

	function index_action()
	{
        $M=$this->MODEL("userinfo");
		$this->set_search();
		if(trim($_GET['keyword'])!="")
		{
            if ($_GET['type']=='1'){
            	$orderinfo=$M->GetMemberList(array("`username` like '%".trim($_GET['keyword'])."%'"),array("field"=>"`uid`"));
            	if (is_array($orderinfo))
            	{
            		foreach ($orderinfo as $val)
            		{
            			$orderuids[]=$val['uid'];
            		}
            		$oruids=@implode(",",$orderuids);
            	}
            	$where.=" `comid` in (".$oruids.")";
            }elseif ($_GET['type']=='2'){
            	$where.=" `order_id` like '%".trim($_GET['keyword'])."%'";
            }elseif($_GET['type']=='3'){
            	$where.=" `adname` like '%".trim($_GET['keyword'])."%'";
            }elseif($_GET['type']=='4'){
            	$g_com=$M->GetUserinfoList(array("`name` like '%".trim($_GET['keyword'])."%'"),array("usertype"=>"2","field"=>"`uid`"));
            	if(is_array($g_com) && !empty($g_com)){
            		foreach($g_com as $v){
            		   $g_uid[]=$v['uid'];
            		}
            		$g_uids=@implode(",",$g_uid);
            	}
				$where.=" `comid` in (".$g_uids.")";
            }
            $urlarr['type']=$_GET['type'];
			$urlarr['keyword']=$_GET['keyword'];
		}else{
			$where=1;
		}
		if($_GET['end']){
			if($_GET['end']=='1'){
				$where.=" and `datetime` >= '".strtotime(date("Y-m-d 00:00:00"))."'";
			}else{
				$where.=" and `datetime` >= '".strtotime('-'.$_GET['end'].'day')."'";
			}
			$urlarr['end']=$_GET['end'];
		}
		if($_GET['status']){
			if($_GET['status']=="-1"){
				$where.=" and `status`='0'";
			}else{
				$where.=" and `status`='".$_GET['status']."'";
			}
			$urlarr['status']=$_GET['status'];
		}
		if($_GET['order'])
		{
			$where.=" order by ".$_GET['t']." ".$_GET['order'];
			$urlarr['order']=$_GET['order'];
			$urlarr['t']=$_GET['t'];
		}else{
			$where.=" order by id desc";
		}
		$urlarr["page"]="{{page}}";
		$pageurl=Url($_GET['m'],$urlarr,'admin');
		$PageInfo=$M->get_page("ad_order",$where,$pageurl,$this->config['sy_listnum']);
        $this->yunset($PageInfo);
        $rows=$PageInfo['rows'];
		include (APP_PATH."/config/db.data.php");
		if(is_array($rows)){
			foreach($rows as $k=>$v){
				$rows[$k][order_state_n]=$arr_data['paystate'][$v['order_state']];
				$classid[]=$v['comid'];
			}
		}
		if(is_array($classid))
		{
			$group=$M->GetMemberList(array("uid in (".@implode(",",$classid).")"),array("field"=>"`uid`,`username`"));
			$group_com=$M->GetUserinfoList(array("uid in (".@implode(",",$classid).")"),array("usertype"=>"2","field"=>"`uid`,`name`"));
		}

		if(is_array($group)){
			foreach($group as $key=>$value){
				foreach($rows as $k=>$v){
					if($value['uid']==$v['comid']){
						$rows[$k]['username']=$value['username'];
					}
				}
			}
		}
		if(is_array($group_com)){
			foreach($group_com as $key=>$value){
				foreach($rows as $k=>$v){
					if($value['uid']==$v['comid']){
						$rows[$k]['comname']=$value['name'];
					}
				}
			}
		}
		$_GET['c']='';
		$this->yunset("get_type", $_GET);
		$this->yunset("rows",$rows);
		$this->yuntpl(array('siteadmin/admin_ad_order'));
	}
	function sbody_action(){
		$M=$this->MODEL("ad");
		$row=$M->GetAdOrderOne(array("id"=>$_GET['pid']));
		echo $row['statusbody'];die;
	}
	function status_action(){
		extract($_POST);
		$M=$this->MODEL("ad");
		$row=$M->GetAdOrderOne(array("id"=>$pid));
		$ComM=$this->MODEL("company");
		$com=$ComM->GetCompanyInfo(array("uid"=>$row['uid']),array("field"=>"`did`"));
		if($status=="1"){
			$data['did']=$com['did'];
			$time_end=mktime()+3600*24*30*$row['buy_time'];
			$data['ad_name']=$row['ad_name'];
			$data['time_start']=date("Y-m-d");
			$data['time_end']=date("Y-m-d",$time_end);
			$data['ad_type']=$row['pic'];
			$data['pic_url']=$row['pic_url'];
			$data['pic_src']=$row['pic_src'];
			$data['class_id']=$row['aid'];
			$data['is_check']=1;
			$id=$M->AddAd($data);
			$M->UpdateOrderAd(array("ad_id"=>$id),array("id"=>$pid));
			$this->model_ad_arr_action();
		}else if($status=="2"){
			if($row['buytype']=="1"){
				$value=array("`pay`=`pay`+".$row['price']."");
			}else{
				$value=array("`integral`=`integral`+'".$row['integral']."'");
			}
			$Member=$this->MODEL("userinfo");
			$Member->UpdateUserStatis($value,array("uid"=>$row['comid']),array("usertype"=>"2"));
		}
		$id=$M->UpdateOrderAd(array("status"=>$status,"statusbody"=>$statusbody),array("id"=>$pid));
		$id?$this->ACT_layer_msg("广告订单(ID:".$pid.")设置成功！",9,$_SERVER['HTTP_REFERER'],2,1):$this->ACT_layer_msg("设置失败！",8,$_SERVER['HTTP_REFERER']);
	}
	function del_action(){
		$this->check_token();
		$M=$this->MODEL("ad");
	    if($_GET['del']){
	    	$del=$_GET['del'];
	    	if($del){
	    		if(is_array($del)){
			    	$pic_url=$M->GetAdOrder(array("`id` in(".@implode(',',$del).")"),array("field"=>"`pic_url`"));
					foreach($pic_url as $val){
						unlink_pic($val['pic_url']);
					}
					$M->DeleteAdOrder(array("`id` in(".@implode(',',$del).")"));
		    	}else{
		    		$M->DeleteAdOrder(array("id"=>$del));
		    	}
				$this->layer_msg('广告订单(ID:'.@implode(',',$del).')删除成功！',9,1,$_SERVER['HTTP_REFERER']);
	    	}else{
				$this->layer_msg('请选择您要删除的订单！',8,1,$_SERVER['HTTP_REFERER']);
	    	}
	    }
	    if(isset($_GET['id'])){
			$where="`id`='".$_GET['id']."'";
			$pic_url=$M->GetAdOrderOne(array("id"=>$_GET['id']),array("field"=>"`pic_url`"));
			unlink_pic($pic_url['pic_url']);
			$result=$M->DeleteAdOrder(array("id"=>$_GET['id']));
			isset($result)?$this->layer_msg('订单(ID:'.$_GET['id'].')删除成功！',9,0,$_SERVER['HTTP_REFERER']):$this->layer_msg('删除失败！',8,0,$_SERVER['HTTP_REFERER']);
		}else{
			$this->layer_msg('非法操作！',8,1,$_SERVER['HTTP_REFERER']);
		}
	}
	function model_ad_arr_action()
	{
		$M=$this->MODEL("ad");
		$show.="<?php\r\n\$ad_label='';\r\n";
		$ad_list = $M->GetAd(array("is_open"=>"1"),array("orderby"=>"`sort` desc,`id` desc"));
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