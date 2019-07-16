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
class admin_tpl_moblies_controller extends adminCommon{
	function index_action(){
		$nav = $this->obj->DB_select_all("tplmobliepic","`type`='nav'");
		foreach($nav as $k=>$v){
			if($v['pic']&&file_exists(str_replace('./',APP_PATH, $v['pic']))){
				$nav[$k]['pic']=str_replace('./', $this->config['sy_weburl'].'/', $v['pic']);
			}else{
				$nav[$k]['pic']='';
			}
		}
		$this->yunset("nav",$nav);
		$hd = $this->obj->DB_select_all("tplmobliepic","`type`='hd'");
		foreach($hd as $k=>$v){
			if($v['pic']&&file_exists(str_replace('./',APP_PATH, $v['pic']))){
				$hd[$k]['pic']=str_replace('./', $this->config['sy_weburl'].'/', $v['pic']);
			}else{
				$hd[$k]['pic']='';
			}
		}
		$this->yunset("hd",$hd);
		$indexnav = $this->obj->DB_select_all("tplmobliepic","`type`='indexnav' order by `id` asc");
		foreach($indexnav as $k=>$v){
			if($v['pic']&&file_exists(str_replace('./',APP_PATH, $v['pic']))){
				$indexnav[$k]['pic']=str_replace('./', $this->config['sy_weburl'].'/', $v['pic']);
			}else{
				$indexnav[$k]['pic']='';
			}
		}
		foreach($indexnav as $k=>$v){
			if($k==0){
				$indexnav1=$v;
			}
			if($k==1){
				$indexnav2=$v;
			}
			if($k==2){
				
				$indexnav3=$v;
			}
		}
		
		$this->yunset("indexnav1",$indexnav1);
		$this->yunset("indexnav2",$indexnav2);
		$this->yunset("indexnav3",$indexnav3);
		$adlist = $this->obj->DB_select_all("tplmobliepic","`type`='ad'");
		foreach($adlist as $k=>$v){
			if($v['pic']&&file_exists(str_replace('./',APP_PATH, $v['pic']))){
				$adlist[$k]['pic']=str_replace('./', $this->config['sy_weburl'].'/', $v['pic']);
			}else{
				$adlist[$k]['pic']='';
			}
		}
		$this->yunset("adlist",$adlist);
		include PLUS_PATH."/tplmoblie.cache.php";
		$this->yunset('tplmoblie',$tplmoblie);
		if( $tplmoblie['sort']){
			$sort=explode(',', $tplmoblie['sort']);
		}else{
			$sort="hearder,hd,search,nav,indexnav,notice,reglogin,ad,rewardjob,hotjob,newjob,hotcom,recjob,urgentjob,resume,article,zph,jobclass";
			$sort=explode(',', $sort);
		}
		$this->yunset('sort',$sort);
		$navall=$this->obj->DB_select_all("navigation","`nid`='20' and `display`=1");
		foreach ($navall as $k=>$val){
			if($val['url']!='index.php'&&$val['url']!='/'){
				$navigation[$k]=$val;
			}
			
		}
		$this->yunset('navigation',$navigation);
		$searchurl=array(
				array('id'=>1,'c'=>'job','name'=>'找工作'),
				array('id'=>2,'c'=>'resume','name'=>'找人才'),
				array('id'=>3,'c'=>'company','name'=>'找企业'),
				array('id'=>4,'c'=>'part','name'=>'找兼职'),
				array('id'=>5,'c'=>'tiny','name'=>'普工专区'),
				array('id'=>6,'c'=>'once','name'=>'店铺招聘'),
				array('id'=>7,'c'=>'ltjob','name'=>'猎头职位'),
				array('id'=>8,'c'=>'ltjob','a'=>'service','name'=>'委托求职'),
				array('id'=>9,'c'=>'train','name'=>'培训课程'),
				array('id'=>10,'c'=>'train','a'=>'agency','name'=>'培训机构'),
				array('id'=>11,'c'=>'train','a'=>'teacher','name'=>'培训讲师'),
				array('id'=>12,'c'=>'ask','a'=>'list','name'=>'互动问答')
		);
		$this->yunset('searchurl',$searchurl);
		$this->yuntpl(array('admin/admin_tpl_moblies'));
	}

	function save_action(){
		if($_POST['submit']){
			$_POST=$this->post_trim($_POST);
			//头部
			if($_POST['type']=='hearder'){
				$data=array(
						'color'=>$_POST['color'],
						'site'=>$_POST['site'],
						'logo'=>$_POST['logo'],
						'heardercss'=>$_POST['heardercss']
						
				);
				$this->savetplmoblie($data,$_POST['type']);
			}
			//幻灯片
			if($_POST['type']=='hd'){
				$UploadM=$this->MODEL("upload");
					
				$this->delpic('hd',$_POST['hdid']);
				//幻灯片
				for($i=0;$i<count($_POST['hdname']);$i++){
					$pictures=$this->imgarray($UploadM,$_FILES['hdpic'], $i);
					$arr=array(
							'name'=>$_POST['hdname'][$i],
							'url'=>$_POST['hdurl'][$i],
							'type'=>'hd',
							'pic'=>$pictures,
							'id'=>$_POST['hdid'][$i],
							'temp_name'=>$_FILES['hdpic']['tmp_name'][$i]
					);
					$this->saveall($arr);
				}
			
			}
			//搜索
			if($_POST['type']=='search'){
				$data=array(
					'searchshow'=>$_POST['searchshow'],
					'search'=>$_POST['search'],
					'searchurl'=>$_POST['searchurl']
				);
				$this->savetplmoblie($data,$_POST['type']);
			}
			//导航
			if($_POST['type']=='nav'){
				$UploadM=$this->MODEL("upload");
				$this->delpic('nav',$_POST['navid']);
				//导航
				for($j=0;$j<count($_POST['navname']);$j++){
					$pictures=$this->imgarray($UploadM,$_FILES['navpic'], $j);
					$arr=array(
							'name'=>$_POST['navname'][$j],
							'url'=>$_POST['navurl'][$j],
							'type'=>'nav',
							'pic'=>$pictures,
							'id'=>$_POST['navid'][$j],
							'temp_name'=>$_FILES['navpic']['tmp_name'][$j]
					);
					$this->saveall($arr);
				}
				
				
			}
			//图片导航
			if($_POST['type']=='indexnav'){
				$UploadM=$this->MODEL("upload");
				//图片导航
				for($k=0;$k<count($_POST['indexnavname']);$k++){
					$pictures=$this->imgarray($UploadM,$_FILES['indexnavpic'], $k);
					$arr=array(
							'name'=>$_POST['indexnavname'][$k],
							'url'=>$_POST['indexnavurl'][$k],
							'desc'=>$_POST['indexnavdesc'][$k],
							'type'=>'indexnav',
							'pic'=>$pictures,
							'id'=>$_POST['indexnavid'][$k],
							'temp_name'=>$_FILES['indexnavpic']['tmp_name'][$k]
					);
					$this->saveall($arr);
				}
				$data=array(
					'indexnav'=>$_POST['indexnav']
				);
				$this->savetplmoblie($data,$_POST['type']);
			}
			//公告
			if($_POST['type']=='notice'){
				$data=array(
					'notice'=>$_POST['notice']
				);
				$this->savetplmoblie($data,$_POST['type']);
			}
			//登录注册
			if($_POST['type']=='reglogin'){
				$data=array(
					'reglogin'=>$_POST['reglogin'],
					'reglogindesc'=>$_POST['reglogindesc'],
					'login'=>$_POST['login'],
					'reg'=>$_POST['reg'],
					'logincolor'=>$_POST['logincolor'],
					'regcolor'=>$_POST['regcolor']
				);
				$this->savetplmoblie($data,$_POST['type']);
			}
			//广告
			if($_POST['type']=='ad'){
				$UploadM=$this->MODEL("upload");
				$this->delpic('ad',$_POST['adlistid']);

				//广告
				for($i=0;$i<count($_POST['adlistname']);$i++){
					$pictures=$this->imgarray($UploadM,$_FILES['adlistpic'], $i);
					$arr=array(
							'name'=>$_POST['adlistname'][$i],
							'url'=>$_POST['adlisturl'][$i],
							'type'=>'ad',
							'pic'=>$pictures,
							'id'=>$_POST['adlistid'][$i],
							'temp_name'=>$_FILES['adlistpic']['tmp_name'][$i]
					);
					$this->saveall($arr);
				}
				$data=array(
					'ad'=>$_POST['ad']
				);
				$this->savetplmoblie($data,$_POST['type']);
					
			}
			//赏金职位
			if($_POST['type']=='rewardjob'){
				if($_POST['rewardjobcom']!=1){
					$_POST['rewardjobcom']=2;
				}
				if($_POST['rewardjobsalary']!=1){
					$_POST['rewardjobsalary']=2;
				}
				if($_POST['rewardjobreward']!=1){
					$_POST['rewardjobreward']=2;
				}
				if($_POST['rewardjobdate']!=1){
					$_POST['rewardjobdate']=2;
				}
				$data=array(
					'rewardjob'=>$_POST['rewardjob'],
					'rewardjobcss'=>$_POST['rewardjobcss'],
					'rewardjobcom'=>$_POST['rewardjobcom'],
					'rewardjobsalary'=>$_POST['rewardjobsalary'],
					'rewardjobreward'=>$_POST['rewardjobreward'],
					'rewardjobdate'=>$_POST['rewardjobdate'],
					'rewardjobmore'=>$_POST['rewardjobmore'],
					'rewardjobnum'=>$_POST['rewardjobnum']
				);
				$this->savetplmoblie($data,$_POST['type']);
			}
			//热门职位
			if($_POST['type']=='hotjob'){
				$data=array(
					'hotjob'=>$_POST['hotjob'],
					'hotjobmore'=>$_POST['hotjobmore'],
					'hotjobnum'=>$_POST['hotjobnum']
				);
				$this->savetplmoblie($data,$_POST['type']);
			}
			//最新职位
			if($_POST['type']=='newjob'){
				if($_POST['newjobcom']!=1){
					$_POST['newjobcom']=2;
				}
				if($_POST['newjobsalary']!=1){
					$_POST['newjobsalary']=2;
				}
				if($_POST['newjobcity']!=1){
					$_POST['newjobcity']=2;
				}
				if($_POST['newjobdate']!=1){
					$_POST['newjobdate']=2;
				}
				if($_POST['newjobwelfare']!=1){
					$_POST['newjobwelfare']=2;
				}
				$data=array(
					'newjob'=>$_POST['newjob'],
					'newjobcom'=>$_POST['newjobcom'],
					'newjobsalary'=>$_POST['newjobsalary'],
					'newjobcity'=>$_POST['newjobcity'],
					'newjobdate'=>$_POST['newjobdate'],
					'newjobwelfare'=>$_POST['newjobwelfare'],
					'newjobmore'=>$_POST['newjobmore'],
					'newjobnum'=>$_POST['newjobnum']
				);
				$this->savetplmoblie($data,$_POST['type']);
			}
			//名企展示
			if($_POST['type']=='hotcom'){
				if($_POST['hotcomlogo']!=1){
					$_POST['hotcomlogo']=2;
				}
				if($_POST['hotcomhy']!=1){
					$_POST['hotcomhy']=2;
				}
				if($_POST['hotcomcity']!=1){
					$_POST['hotcomcity']=2;
				}
				$data=array(
						'hotcom'=>$_POST['hotcom'],
						'hotcomlogo'=>$_POST['hotcomlogo'],
						'hotcomhy'=>$_POST['hotcomhy'],
						'hotcomcity'=>$_POST['hotcomcity'],
						'hotcommore'=>$_POST['hotcommore'],
						'hotcomnum'=>$_POST['hotcomnum']
				);
				$this->savetplmoblie($data,$_POST['type']);
			}
			//推荐职位
			if($_POST['type']=='recjob'){
				if($_POST['recjobcom']!=1){
					$_POST['recjobcom']=2;
				}
				if($_POST['recjobsalary']!=1){
					$_POST['recjobsalary']=2;
				}
				if($_POST['recjobcity']!=1){
					$_POST['recjobcity']=2;
				}
				if($_POST['recjobdate']!=1){
					$_POST['recjobdate']=2;
				}
				if($_POST['recjobwelfare']!=1){
					$_POST['recjobwelfare']=2;
				}
				$data=array(
						'recjob'=>$_POST['recjob'],
						'recjobcom'=>$_POST['recjobcom'],
						'recjobsalary'=>$_POST['recjobsalary'],
						'recjobcity'=>$_POST['recjobcity'],
						'recjobdate'=>$_POST['recjobdate'],
						'recjobwelfare'=>$_POST['recjobwelfare'],
						'recjobmore'=>$_POST['recjobmore'],
						'recjobnum'=>$_POST['recjobnum']
				);
				$this->savetplmoblie($data,$_POST['type']);
			}
			//紧急职位
			if($_POST['type']=='urgentjob'){
				if($_POST['urgentjobcom']!=1){
					$_POST['urgentjobcom']=2;
				}
				if($_POST['urgentjobsalary']!=1){
					$_POST['urgentjobsalary']=2;
				}
				if($_POST['urgentjobcity']!=1){
					$_POST['urgentjobcity']=2;
				}
				if($_POST['urgentjobdate']!=1){
					$_POST['urgentjobdate']=2;
				}
				if($_POST['urgentjobwelfare']!=1){
					$_POST['urgentjobwelfare']=2;
				}
				$data=array(
						'urgentjob'=>$_POST['urgentjob'],
						'urgentjobcom'=>$_POST['urgentjobcom'],
						'urgentjobsalary'=>$_POST['urgentjobsalary'],
						'urgentjobcity'=>$_POST['urgentjobcity'],
						'urgentjobdate'=>$_POST['urgentjobdate'],
						'urgentjobwelfare'=>$_POST['urgentjobwelfare'],
						'urgentjobmore'=>$_POST['urgentjobmore'],
						'urgentjobnum'=>$_POST['urgentjobnum']
				);
				$this->savetplmoblie($data,$_POST['type']);
			}
			//最新职位
			if($_POST['type']=='resume'){
				if($_POST['resumecity']!=1){
					$_POST['resumecity']=2;
				}
				if($_POST['resumeexp']!=1){
					$_POST['resumeexp']=2;
				}
				if($_POST['resumeedu']!=1){
					$_POST['resumeedu']=2;
				}
				if($_POST['resumeexpect']!=1){
					$_POST['resumeexpect']=2;
				}
				$data=array(
						'resume'=>$_POST['resume'],
						'resumepic'=>$_POST['resumepic'],
						'resumeexp'=>$_POST['resumeexp'],
						'resumecity'=>$_POST['resumecity'],
						'resumeedu'=>$_POST['resumeedu'],
						'resumeexpect'=>$_POST['resumeexpect'],
						'resumemore'=>$_POST['resumemore'],
						'resumenum'=>$_POST['resumenum']
				);
				$this->savetplmoblie($data,$_POST['type']);
			}
			//职场资讯
			if($_POST['type']=='article'){
				$data=array(
						'article'=>$_POST['article'],
						'articleclass'=>$_POST['articleclass'],
						'articlecss'=>$_POST['articlecss'],
						'articlemore'=>$_POST['articlemore'],
						'articlenum'=>$_POST['articlenum']
				);
				$this->savetplmoblie($data,$_POST['type']);
			}
			//招聘会
			if($_POST['type']=='zph'){
				$data=array(
						'zph'=>$_POST['zph'],
						'zphmore'=>$_POST['zphmore'],
						'zphnum'=>$_POST['zphnum']
				);
				$this->savetplmoblie($data,$_POST['type']);
			}
			//职位类别
			if($_POST['type']=='jobclass'){
				$data=array(
						//'jobclass'=>$_POST['jobclass'],
						
						'jobclassone'=>$_POST['jobclassone'],
						'jobclassonenum'=>$_POST['jobclassonenum'],
						'jobclassonenumall'=>$_POST['jobclassonenumall'],
						
						'jobclasstwo'=>$_POST['jobclasstwo'],
						'jobclasstwonum'=>$_POST['jobclasstwonum'],
						'jobclasstwonumall'=>$_POST['jobclasstwonumall']
				);
				$this->savetplmoblie($data,$_POST['type']);
			}
			
			
			$this->ACT_layer_msg("保存成功！",9,$_SERVER['HTTP_REFERER']);
			
		}
	}
	//保存到tplmoblie里
	function savetplmoblie($data,$type){
		foreach ($data as $key=>$val){
			$num=$this->obj->DB_select_num("tplmoblie","`name`='".$key."'");
			if($num==false){
				$this->obj->DB_insert_once("tplmoblie","`name`='$key',`config`='".$val."'");
			}else{
				$this->obj->update_once("tplmoblie",array('config'=>$val),array('name'=>$key));
			}
		}
		$this->tplmoblie_cache();
	}
	//图片数组处理
	function imgarray($UploadM,$file,$i){
		if($file['tmp_name'][$i]!=''){
			$files=array(
					'name'=>$file['name'][$i],
					'type'=>$file['type'][$i],
					'tmp_name'=>$file['tmp_name'][$i],
					'error'=>$file['error'][$i],
					'size'=>$file['size'][$i]
			);
			$upload=$UploadM->Upload_pic("../data/upload/wapdiy",false);
			$pictures=$upload->picture($files);
			$picmsg=$UploadM->picmsg($pictures,$_SERVER['HTTP_REFERER']);
			if($picmsg['status'] == $pictures){
				$this->ACT_layer_msg($picmsg['msg'],8);
			}
			$pictures = str_replace("../data","./data",$pictures);
		}
		return $pictures;
	}
	//保存图片
	function saveall($post){
		if($post['temp_name']==''){
			$value="`name`='".$post['name']."',`url`='".$post['url']."',`type`='".$post['type']."'";
			if($post['desc']){
				$value.=",`desc`='".$post['desc']."'";
			}
		}else{
			$value="`name`='".$post['name']."',`url`='".$post['url']."',`type`='".$post['type']."'";
			if($post['pic']){
				$value.=",`pic`='".$post['pic']."'";
			}
			if($post['desc']){
				$value.=",`desc`='".$post['desc']."'";
			}
		}
		//if($post['name']=='' && $post['url']==''){
		//	$value=1;
		//}
		
		if($post['id']){
			if($post['temp_name']!=''){
				$tplpic=$this->obj->DB_select_once('tplmobliepic','`id`='.$post['id'].'');
				if($tplpic['pic']&&file_exists(str_replace('./', APP_PATH, $tplpic['pic']))){
					unlink_pic(str_replace('./', APP_PATH, $tplpic['pic']));
				}
			}
			if($value==1){
				$this->obj->DB_delete_all('tplmobliepic','`id`='.$post['id'].'','');
			}else{
				$this->obj->DB_update_all('tplmobliepic',$value,'`id`='.$post['id'].'');
			}
		}else{
			if($value!=1){
				$this->obj->DB_insert_once('tplmobliepic',$value);
			}
		}
	}
	//删除未包含在最新提交的数据
	function delpic($type,$post){
		$tpltype = $this->obj->DB_select_all("tplmobliepic","`type`='".$type."'");
		foreach($tpltype as $k=>$v){
			if(!in_array($v['id'], $post)){
				if($v['pic']&&file_exists(str_replace('./', APP_PATH, $v['pic']))){
					unlink_pic(str_replace('./', APP_PATH, $v['pic']));
				}
				$this->obj->DB_delete_all('tplmobliepic','`id`='.$v['id'].'','');
			}
		}
	}
	//生成缓存tplmoblie.cache.php
	function tplmoblie_cache(){
		$tplmoblie=$this->obj->DB_select_all('tplmoblie');
		if(is_array($tplmoblie)){
			foreach($tplmoblie as $v){
				$tplmobliearr[$v['name']]=$v['config'];
			}
			made_web(PLUS_PATH.'tplmoblie.cache.php',ArrayToString($tplmobliearr),'tplmoblie');
		}
	}
	function clean_action(){
		$this->obj->DB_delete_all('tplmoblie','1','');
		@unlink(PLUS_PATH.'tplmoblie.cache.php');
		$this->layer_msg('重置样式成功！',9,0,$_SERVER['HTTP_REFERER']);
	}
	//上移下移
	function sort_action(){
		$num=$this->obj->DB_select_num("tplmoblie","`name`='sort'");
		$sort=implode(',', $_POST['sort']);
		if($num){
			$nid=$this->obj->update_once("tplmoblie",array('config'=>$sort),array('name'=>'sort'));
		}else{
			$nid=$this->obj->DB_insert_once("tplmoblie","`name`='sort',`config`='".$sort."'");
		}
		$this->tplmoblie_cache();
		echo $nid;die;
	}
	function wapdiy_action(){
		$num=$this->obj->DB_select_num("tplmoblie","`name`='wapdiy'");
		if($num){
			$nid=$this->obj->update_once("tplmoblie",array('config'=>$_POST['wapdiy']),array('name'=>'wapdiy'));
		}else{
			$nid=$this->obj->DB_insert_once("tplmoblie","`name`='wapdiy',`config`='".$_POST['wapdiy']."'");
		}
		$this->tplmoblie_cache();
		echo $nid;die;
	}
}