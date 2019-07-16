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
class admin_domain_controller extends adminCommon{
    function index_action(){
		$this->yunset("config",$this->config);
		$this->yuntpl(array('admin/admin_domain_config'));
	}
	function savecf_action(){
 		if($_POST['config']){
			unset($_POST['config']);
			foreach($_POST as $key=>$v){
		    	$config=$this->obj->DB_select_num("admin_config","`name`='$key'");
			   if($config==false){
				$this->obj->DB_insert_once("admin_config","`name`='$key',`config`='".$v."'");
			   }else{
				$this->obj->DB_update_all("admin_config","`config`='".$v."'","`name`='$key'");
			   }
		 	}
			$this->web_config();
			$this->layer_msg("网站配置设置成功！",9,1);
		 }
	}
	function alllist_action(){
 		$this->yunset($this->MODEL('cache')->GetCache(array('city','hy')));
		$domain_arr=$this->public_action();				
		$where = "1";
		$urlarr["c"]=$_GET['c'];
		$urlarr["page"]="{{page}}";
		
		$pageurl=Url($_GET['m'],$urlarr,'admin');
		$M=$this->MODEL();
		$PageInfo=$M->get_page("domain",$where,$pageurl,$this->config['sy_listnum']);
		$this->yunset($PageInfo);
		$admin_domain=$PageInfo['rows'];								
		$this->yunset("domain",$admin_domain);
		$this->yuntpl(array('admin/admin_domain'));
	}
	function public_action(){
		$nav_user=$this->obj->DB_select_alls("admin_user","admin_user_group","a.`m_id`=b.`id` and a.`uid`='".$_SESSION["auid"]."'");
		return $nav_user[0][domain];
	}
	function AddDomain_action(){
		extract($_POST);
		$this->yunset($this->MODEL('cache')->GetCache(array('city','hy')));
		include_once("model/model/style_class.php"); 
		$style = new style($this->obj);
		$list = $style->model_list_action();
		$this->yunset("list",$list);
		$this->yuntpl(array('admin/admin_adddomain'));
	}
	function save_action(){
	    if ($this->config['sy_web_site']!=1){
	        $this->layer_msg("请先开启分站！",8,1);
	    }
		extract($_POST);
		if($domain){$domain = @str_replace(array('http://','https://'),"",$domain);}
		if($fz_type=='1'){ 
			$hy="";
		}else{				 
			$cityid="";
		}

		if($_POST['id']){
		    if($domain!="" && $title!="" ){
		        $domain_arr=$this->public_action();
		        $domain_list = $this->obj->DB_select_once("domain","`domain`='$domain' AND `id`!='$id'");
		        if(is_array($domain_list)){
		            $this->ACT_layer_msg("该域名已经被绑定！",8,$_SERVER['HTTP_REFERER']);
		        }else{
		            $value="`title`='$title',";
		            $value.="`domain`='$domain',";
		            $value.="`province`='$provinceid',";
		            $value.="`cityid`='$cityid',";
		            $value.="`three_cityid`='$three_cityid',";
		            $value.="`type`='$type',";
		            $value.="`tpl`='$tpl',";
		            $value.="`hy`='$hy',";
		            $value.="`fz_type`='$fz_type',";
		            $value.="`style`='$style',";
 		            
		            if ($_POST['weblogo']){
		                $domain = $this->obj->DB_select_once("domain","`id`='$id'");
		                $logo = $this->checksrc($weblogo,$domain['weblogo']);
		                $value.="`weblogo`='".$logo."',";
		            }
		            $value.="`webtitle`='$webtitle',";
		            $value.="`webkeyword`='$webkeyword',";
		            $value.="`webmeta`='$webmeta'";
 		            
		            $this->obj->DB_update_all("domain",$value,"`id`='$id'");
		            $this->DomainArr_action();
		            $this->ACT_layer_msg("域名绑定(ID:".$id.")修改成功！",9,"index.php?m=admin_domain&c=alllist",2,1);
		        }
		    }else{
		        $this->ACT_layer_msg("信息填写不完整！",8,$_SERVER['HTTP_REFERER']);
		    }
		}else{
		    
		    if($domain!="" && $title!="" ){
		        $domain_list = $this->obj->DB_select_once("domain","`domain`='$domain'");
		        if(is_array($domain_list)){
		            $this->ACT_layer_msg("该域名已经被绑定！",8,$_SERVER['HTTP_REFERER']);
		        }else{
		            $logo = $this->checksrc($weblogo);
		            $value="`title`='$title',";
		            $value.="`domain`='$domain',";
		            $value.="`province`='$provinceid',";
		            $value.="`cityid`='$cityid',";
		            $value.="`three_cityid`='$three_cityid',";
		            $value.="`type`='$type',";
		            $value.="`tpl`='$tpl',";
		            $value.="`hy`='$hy',";
		            $value.="`fz_type`='$fz_type',";
		            $value.="`style`='$style',";
 		            $value.="`weblogo`='$logo',";
		            $value.="`webtitle`='$webtitle',";
		            $value.="`webkeyword`='$webkeyword',";
		            $value.="`webmeta`='$webmeta'";
 		            $id=$this->obj->DB_insert_once("domain",$value);
		            $this->DomainArr_action();
		            $this->ACT_layer_msg("域名(ID".$id.")绑定成功！",9,"index.php?m=admin_domain&c=alllist",2,1);
		        }
		    }else{
		        $this->ACT_layer_msg("信息填写不完整！",8,$_SERVER['HTTP_REFERER']);
		    }
		}
	}
	function logo_upload($picurl,$upload){
		$pictures=$upload->picture($picurl,false);
		$pic = str_replace("../data/logo","data/logo",$pictures);
		return $pic;
	}
	function Modify_action(){
		if($_GET['siteid']){
			$this->yunset($this->MODEL('cache')->GetCache(array('city','hy')));
			include_once("model/model/style_class.php"); 
			$style = new style($this->obj);
			$list = $style->model_list_action();
			$this->yunset("list",$list);
			$site = $this->obj->DB_select_once("domain","`id`='".$_GET['siteid']."'");
			$this->yunset("site",$site);
		}
		$this->yuntpl(array('admin/admin_adddomain'));
	}
	function AjaxCity_action(){
		if($_GET['keyid']){
			$city=$this->obj->DB_select_all("city_class","`keyid`='".$_GET['keyid']."'");
			if(is_array($city)){
				foreach($city as $key=>$value){
					$html.="<option value='".$value['id']."'>".$value['name']."</option>";
				}
			}
			echo $html;
			$html="";die;
		}
	}
	function DelDomain_action(){
		$this->check_token();
		if($_GET['delid']){
			$this->obj->DB_delete_all("domain","`id`='".$_GET['delid']."'");
			$this->DomainArr_action();
			$this->layer_msg('域名(ID:'.$_GET[delid].')删除成功！',9,0,"index.php?m=admin_domain&c=alllist");
		}
	}
	function allDelDomain_action(){
		$this->check_token();
	    if($_GET['del']){
	    	$del=$_GET['del'];
	    	if(is_array($del)){
				$this->obj->DB_delete_all("domain","`id` in(".@implode(',',$del).")","");
 				$this->layer_msg('分站(ID:'.@implode(',',$del).')删除成功！',9,1,$_SERVER['HTTP_REFERER']);
	    	}else{
				$this->layer_msg('请选择您要删除的分站！',8,1,$_SERVER['HTTP_REFERER']);
	    	}
	    }
	}
	function DomainArr_action(){
		include(LIB_PATH."cache.class.php");
		$cacheclass= new cache(PLUS_PATH,$this->obj);
		$makecache=$cacheclass->domain_cache("domain_cache.php");
	}
}
?>