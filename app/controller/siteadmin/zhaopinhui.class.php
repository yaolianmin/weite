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
class zhaopinhui_controller extends siteadmin_controller
{
	
	function set_search(){
		$search_list[]=array("param"=>"status","name"=>'审核状态',"value"=>array("0"=>"未开始","1"=>"已开始","2"=>"已结束"));
		$this->yunset("search_list",$search_list);
	}
	function index_action()
	{
		$this->set_search();
		extract($_GET);
		$where="1";
		if($status=='0'){
			$where.=" and `starttime`>'".date("Y-n-j")."'";
		}elseif($status=='1'){
			$where.=" and `starttime`<'".date("Y-n-j")."' and `endtime`>'".date("Y-n-j")."'";
		}elseif($status=='2'){
			$where.=" and `endtime`<'".date("Y-n-j")."'";
		}
		$urlarr['status']=$status;
		if($_GET['news_search'])
		{
			if ((int)$_GET['type']=='2'){
				$where.=" and `address` like '%".trim($_GET['keyword'])."%'";
			}else{
				$where.=" and `title` like '%".trim($_GET['keyword'])."%'";
			}
			$urlarr['type']=(int)$_GET['type'];
			$urlarr['keyword']=$_GET['keyword'];
			$urlarr['news_search']=$_GET['news_search'];
		}
		$where.=" order by id desc";
		$urlarr['page']="{{page}}";
		$pageurl=Url($_GET['m'],$urlarr,'admin');
        $M=$this->MODEL("zph");
		$PageInfo=$M->get_page("zhaopinhui",$where,$pageurl,$this->config['sy_listnum']);
		$this->yunset($PageInfo);
		$rows=$PageInfo['rows'];
		if(is_array($rows)){
			$zids=array();
			foreach($rows as $key=>$v){
				$rows[$key]['booking']=$rows[$key]['comnum']=0;
				$zids[]=$v['id']; 
			}
			$comnum=$M->GetZphComList(array("`zid` in(".pylode(',',$zids).")"),array("field"=>"count(id) as num,zid",'groupby'=>'zid'));
			$booking=$M->GetZphComList(array("`zid` in(".pylode(',',$zids).")",'status'=>'0'),array("field"=>"count(id) as num,zid",'groupby'=>'zid')); 
			foreach($rows as $key=>$v){
				foreach($comnum as $val){
					if($v['id']==$val['zid']){
						$rows[$key]['comnum']=$val['num'];
					}
				}
				foreach($booking as $val){
					if($v['id']==$val['zid']){
						$rows[$key]['booking']=$val['num'];
					}
				} 
			}
		}
		$this->yunset("get_type", $_GET);
		$this->yunset("rows",$rows);
		$this->yuntpl(array('siteadmin/admin_zhaopinhui_list'));
	}

	function add_action()
	{
		if((int)$_GET['id'])
		{
			$num=$this->obj->DB_select_num("zhaopinhui_com","`zid`='".$_GET['id']."'");
			if($num>0){
				$this->get_admin_msg("index.php?m=zhaopinhui","该招聘会已有企业报名，不能修改！");
			}
			$M=$this->MODEL("zph");
			$linkarr=$M->GetZphOnce(array("id"=>(int)$_GET['id']));
			$this->yunset("linkrow",$linkarr);
		}
		$space = $this->obj->DB_select_all("zhaopinhui_space","`keyid`='0'");
		$this->yunset("space",$space);
		$this->yuntpl(array('siteadmin/admin_zhaopinhui_add'));
	}
	
	function del_action()
	{
		$M=$this->MODEL("zph");
		if(is_array($_POST['del'])){
			$linkid=@implode(',',$_POST['del']);
			$layer_msg=1;
		}else{
			$this->check_token();
			$linkid=(int)$_GET['id'];
			$layer_msg=0;
		}
		$zph=$this->obj->DB_select_all("zhaopinhui","`id` in ($linkid)","is_themb");
		if(is_array($zph)){
			foreach($zph as $v){
				unlink_pic(".".$v['is_themb']);
			}
		}
		$delid=$M->DeleteZph(array("`id` in (".$linkid.")"));
		if($delid)
		{
			$row=$M->GetZphPic(array("`zid` in (".$linkid.")"),array("field"=>"`pic`"));
			if(is_array($row)){
				foreach($row as $v){
					unlink_pic("..".$v['pic']);
				}
			}
			$M->DeleteZphPic(array("`zid` in (".$linkid.")"));
			$M->DeleteZphCom(array("`zid` in (".$linkid.")"));
			$this->layer_msg('招聘会(ID:'.$linkid.')删除成功！',9,$layer_msg,$_SERVER['HTTP_REFERER']);
		}else{
			$this->layer_msg('删除失败！',8,$layer_msg,$_SERVER['HTTP_REFERER']);
		}
	}
	
	function save_action(){
		$isthem=$this->obj->DB_select_once("zhaopinhui","`id`='".$_POST['id']."'",'`is_themb`');
		if($_FILES['is_themb']['tmp_name']){
			$UploadM=$this->MODEL('upload');
			$upload=$UploadM->Upload_pic("../data/upload/zhaopinhui/",false);
			$pic=$upload->picture($_FILES['is_themb']);
			$picmsg=$UploadM->picmsg($pic,$_SERVER['HTTP_REFERER']);
			if($picmsg['status'] == $pic){
				$this->ACT_layer_msg($picmsg['msg'],8);
			}
			$_POST['is_themb'] = str_replace("../data","./data",$pic);
			if($isthem['is_themb']){
				unlink_pic(".".$isthem["is_themb"]);
			}
		}
		if($_POST['time']){
			$times=@explode('~',$_POST['time']);
			$_POST['starttime']=$times[0];
			$_POST['endtime']=$times[1];
			unset($_POST['time']);
		}
		$_POST['body']=str_replace("&amp;","&",$_POST['body']);
		$_POST['media']=str_replace("&amp;","&",$_POST['media']);
		$_POST['packages']=str_replace("&amp;","&",$_POST['packages']);
		$_POST['booth']=str_replace("&amp;","&",$_POST['booth']);
		$_POST['participate']=str_replace("&amp;","&",$_POST['participate']);
		$M=$this->MODEL("zph");
		
		if($_POST['add']){
			unset($_POST['add']);
			$_POST['ctime']=mktime();
			$_POST['status']="0";
			$nbid=$M->AddZhaopinhui($_POST);
			isset($nbid)?$this->ACT_layer_msg("招聘会(ID:$nbid)添加成功！",9,"index.php?m=zhaopinhui",2,1):$this->ACT_layer_msg("招聘会(ID:$nbid)添加失败！",8,"index.php?m=zhaopinhui");
		}
		
		if($_POST['update']){
			$id=$_POST['id'];
			unset($_POST['id']);
			unset($_POST['update']);
			unset($_POST['pytoken']);
			unset($_POST['lasturl']);
			$info=$M->GetZphOnce(array("id"=>$id),array("field"=>"`did`"));
			if($info['did']!=$_POST['did']){
				$M->UpdateZphCom(array("did"=>$_POST['did']),array("zid"=>$id));
				$M->UpdateZphPic(array("did"=>$_POST['did']),array("zid"=>$id));
			}
			$nbid=$M->UpdateZhaopinhui($_POST,array("id"=>$id));
 			isset($nbid)?$this->ACT_layer_msg("招聘会(ID:$id)修改成功！",9,"index.php?m=zhaopinhui",2,1):$this->ACT_layer_msg("招聘会(ID:$id)修改失败！",8,$_SERVER['HTTP_REFERER']);
		}
	}
	function upload_action(){ 
		$id=(int)$_GET['id'];
		$M=$this->MODEL("zph");
		if((int)$_GET['editid']){
			$editrow=$M->GetZphPicOnce(array("id"=>(int)$_GET['editid']));
			$this->yunset("pic",substr($editrow['pic'],(strrpos($editrow['pic'],'/')+1)));
			$this->yunset("editrow",$editrow);
			$id=$editrow['zid'];
		}
		$row=$M->GetZphOnce(array("id"=>$id));
		$this->yunset("row",$row);
		$urlarr['c']=$_GET['c'];
		$urlarr['id']=(int)$id;
		$urlarr['page']="{{page}}";
		$pageurl=Url($_GET['m'],$urlarr,'admin');
		$where=" `zid`='".$id."'";
		$PageInfo=$M->get_page("zhaopinhui_pic",$where,$pageurl,$this->config['sy_listnum']); 
		$this->yunset($PageInfo);
		$this->yuntpl(array('siteadmin/admin_zhaopinhui_upload'));
	}
	function uploadsave_action(){
		$M=$this->MODEL("zph");
		$UploadM=$this->MODEL('upload');
		extract($_POST);
		$zid=$_POST['zid'];
		$id=$_POST['id'];
		$add=$_POST['add'];
		$update=$_POST['update'];
		unset($_POST['update']);
		unset($_POST['add']);
		unset($_POST['id']);
		if($add){
			if($_FILES['pic']['tmp_name']){
 				$upload=$UploadM->Upload_pic("../data/upload/zhaopinhui/",false);
				$pic=$upload->picture($_FILES['pic']);
				$picmsg=$UploadM->picmsg($pic,$_SERVER['HTTP_REFERER']);
				if($picmsg['status'] == $pic){
					$this->ACT_layer_msg($picmsg['msg'],8);
				}
				$_POST['pic'] = str_replace("../data","/data",$pic);
			}
			$info=$M->GetZphOnce(array("id"=>$zid),array("field"=>"did"));
			$_POST['is_themb']=0;
			$_POST['did']=$info['did'];
			$nbid=$M->AddZphPic($_POST);
			isset($nbid)?$this->ACT_layer_msg("招聘会图片(ID:".$nbid.")添加成功！",9,$_SERVER['HTTP_REFERER'],2,1):$this->ACT_layer_msg("添加失败！",8,$_SERVER['HTTP_REFERER']);
		}
		if($update){
 			$row=$this->obj->DB_select_once("zhaopinhui_pic","`id`='".$id."'");
			if($_FILES['pic']['tmp_name']){
				$upload=$UploadM->Upload_pic("../data/upload/zhaopinhui/",false);
				$pic=$upload->picture($_FILES['pic']);
				$picmsg=$UploadM->picmsg($pic,$_SERVER['HTTP_REFERER']);
				if($picmsg['status'] == $pic){
					$this->ACT_layer_msg($picmsg['msg'],8);
				}
				$_POST['pic'] = str_replace("../data","/data",$pic);
				if($row['pic']){
					unlink_pic(".".$row["pic"]);
				}
			}	 
			$nbid=$M->UpdateZphPic($_POST,array("id"=>$id));
			isset($nbid)?$this->ACT_layer_msg("招聘会图片(ID:".$id.")修改成功！",9,"index.php?m=zhaopinhui&c=upload&id=".intval($_POST['zid'])."",2,1):$this->ACT_layer_msg("修改失败！",8,"index.php?m=zhaopinhui&c=upload&id=".intval($_POST['zid'])."");
		}
	}
	function pic_action()
	{
		$M=$this->MODEL("zph");
		if($_GET['delid']){
			$_GET['delid']=(int)$_GET['delid'];
			$this->check_token();
			$row=$M->GetZphPicOnce(array("id"=>$_GET['delid']));
			unlink_pic("..".$row['pic']);
			$delid=$M->DeleteZphPic(array("id"=>$_GET['delid']));
 			$delid?$this->layer_msg("招聘会图片(ID:".$_GET['delid'].")删除成功！",9,0,$_SERVER['HTTP_REFERER']):$this->layer_msg('删除失败！',8,0,$_SERVER['HTTP_REFERER']);
		}
	}
	
	function set_searchs(){
		$search_list[]=array("param"=>"status","name"=>'审核状态',"value"=>array("1"=>"已审核","2"=>"未通过","3"=>"未审核"));
		$this->yunset("search_list",$search_list);
	}
	function com_action(){
		$this->set_searchs();
		$M=$this->MODEL("zph");
		extract($_GET);
		$type=array('1'=>'招聘会名称','2'=>'企业名称');
		$this->yunset('type',$type);
		if($_GET['id']){
			$where="`zid`='".$_GET['id']."'";
			$urlarr['id']=$_GET['id'];
		}else{
			$where="1";
		}
		if ($_GET['type']){
		    if($_GET['type']==1){
		        if (trim($_GET['keyword'])){
		            $zph=$this->obj->DB_select_all('zhaopinhui',"`title` like '%".trim($_GET['keyword'])."%'",'id');
		            foreach ($zph as $v){
		                $zid[]=$v['id'];
		            }
		            $where.=' and zid in ('.pylode(',', $zid).')';
		        }
		    }
		    if ($_GET['type']==2){
		        if (trim($_GET['keyword'])){
		            $company=$this->obj->DB_select_all('company',"`name` like '%".trim($_GET['keyword'])."%'",'uid');
		            foreach ($company as $v){
		                $cid[]=$v['uid'];
		            }
		           $where.=' and uid in ('.pylode(',', $cid).')';
		        }
		    }
		    $urlarr['keyword']=$_GET['keyword'];
		}
		if($status){
			if($status=="3")
			{
				$status="0";
			}
			$where.=" and `status`='$status'";
			$urlarr['status']=$status;
		}

		$where.=" order by id desc";
		$urlarr['c']=$_GET['c'];
		$urlarr['page']="{{page}}";
		$pageurl=Url($_GET['m'],$urlarr,'admin');
		$PageInfo=$M->get_page("zhaopinhui_com",$where,$pageurl,$this->config['sy_listnum']);
		$this->yunset($PageInfo);
		$rows=$PageInfo['rows'];
		if(is_array($rows))
		{
			$space=$this->obj->DB_select_all("zhaopinhui_space");
			$spacearr=array();
			foreach($space as $val){
				$spacearr[$val['id']]=$val['name'];
			}
			foreach($rows as $v)
			{
				$uid[]=$v['uid'];
				$jobid[]=$v['jobid'];
				$zid[]=$v['zid'];
			}
			$ComM=$this->MODEL("company");
			$JobM=$this->MODEL("job");
			$company=$ComM->GetComList(array("`uid` in (".@implode(",",$uid).")"),array("field"=>"`uid`,`name`",'special'=>'company'));
			$company_job=$JobM->GetComjobList(array("`id` in (".@implode(",",$jobid).")"),array("field"=>"`name`,`id`",'special'=>'company_job'));
			$zhp=$M->GetZphList(array("`id` in (".@implode(",",$zid).")"),array("field"=>"`id`,`title`"));
			foreach($rows as $k=>$v)
			{
				foreach($company as $val)
				{
					if($v['uid']==$val['uid'])
					{
						$rows[$k]['comname']=$val['name'];
					}
				}
				foreach($zhp as $val)
				{
					if($v['zid']==$val['id'])
					{
						$rows[$k]['zphname']=$val['title'];
					}
				}
				$jobids=array();
				$jobname=array();
				$jobids=@explode(",",$v['jobid']);
				foreach($company_job as $val)
				{
					foreach($jobids as $value)
					{
						if($value==$val['id'])
						{
							$url=Url("job",array("c"=>"comapply","id"=>$val['id']),"1");
							$jobname[]='<a target="_blank" href="'.$url.'">'.$val['name'].'</a>';
						}
					}
					$rows[$k]['jobname']=@implode(",",$jobname);
				}
			}
		}
		$this->yunset("spacearr",$spacearr);
		$this->yunset("rows",$rows);
		$this->yuntpl(array('siteadmin/admin_zhaopinhui_com'));
	}
	function sbody_action(){
		$M=$this->MODEL("zph");
		$com=$M->GetZphComOnce(array("`id`='".$_POST['id']."'"),array("field"=>"statusbody"));
		echo $com['statusbody'];die;
	}
	
	function delcom_action(){
		$M=$this->MODEL("zph");
		if(is_array($_POST['del'])){
			$linkid=@implode(',',$_POST['del']);
			$layer_type=1;
		}else{
			$this->check_token();
			$linkid=(int)$_GET['delid'];
			$layer_type=0;
		}
		$delid=$M->DeleteZphCom(array("`id` in (".$linkid.")"));
		$delid?$this->layer_msg('招聘会参会企业(ID:'.$linkid.')删除成功！',9,$layer_type,$_SERVER['HTTP_REFERER']):$this->layer_msg('删除失败！',8,$layer_type,$_SERVER['HTTP_REFERER']);
	}
	function status_action(){
		$M=$this->MODEL("zph");
		extract($_POST);
		$id = @explode(",",$pid);
		
		if(is_array($id)){
			foreach($id as $value){
				$idlist[] = $value;
			}
			$aid = @implode(",",$idlist);
			$id=$M->UpdateZphCom(array("status"=>$status,"statusbody"=>$statusbody),array("`id` IN ($aid)"));
 			$id?$this->ACT_layer_msg("招聘会(ID:".$aid.")设置成功！",9,$_SERVER['HTTP_REFERER'],2,1):$this->ACT_layer_msg("设置失败！",8,$_SERVER['HTTP_REFERER']);
		}else{
			$this->ACT_layer_msg("非法操作！",8,$_SERVER['HTTP_REFERER']);
		}
	}
	function getzhanwei_action()
	{
		if($_POST['sid']){
			$linkarr=$this->obj->DB_select_once("zhaopinhui","id='".$_POST['zid']."'","`reserved`");
			$reserved=@explode(',',$linkarr['reserved']);
			$list=$this->obj->DB_select_all("zhaopinhui_space","`keyid`='".$_POST['sid']."'");
			if(is_array($list)){
				$html.='<div class="zph_zw_box"><table cellspacing="2" cellpadding="3" class="zp_zw_table">';
				foreach($list as $v){
					$html.='<tr class="zp_zw_title"><td colspan="6">'.$v[name].'</td></tr>';
					$html.='<tr>';
					$rows=$this->obj->DB_select_all("zhaopinhui_space","`keyid`='".$v['id']."' order by sort asc");
					foreach($rows as $key=>$val){
						$ck='';
						if(in_array($val[id],$reserved)){
							$ck=' checked="checked"';
						}
						$html.='<td>&nbsp;<input type="checkbox" name="zhanwei" value="'.$val[id].'" '.$ck.'>&nbsp;'.$val[name].'</td>';
						if(($key+1)%6==0){
							$html.='</tr><tr>';
						}
					}
					$html.='</tr>';
				}
				$html.='</table></div>';
				$html.='<div class="zph_zw_box_b">
            <input type="button" onClick="check_zhanwei();"  value="确认" class="submit_btn">
          &nbsp;&nbsp;<input type="button" onClick="layer.closeAll();" class="submit_btn_hjj" value="取消"></div>';
				echo $html;die;
			}
		}
	}
}

?>