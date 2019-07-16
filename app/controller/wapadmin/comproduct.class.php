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
class comproduct_controller extends adminCommon{ 
	function index_action(){ 
		$where=1;
		if($_GET['status']){
			if($_GET['status']=="3"){
				$where.= " and `status`='0'";
			}else{
				$where.= " and `status`='".$_GET['status']."'";
			}
			$urlarr['status']=$_GET['status'];
		}
		$where.=" order by `id` desc";
		$urlarr['c']="comproduct";
		$urlarr['page']="{{page}}";
		$urlarr=Url($_GET['m'],$urlarr,'admin');
		$rows = $this->get_page("company_product",$where,$urlarr,$this->config['sy_listnum'],"`id`,`title`,uid,status"); 
		if($rows&&is_array($rows)){
			$uids=array();
			foreach($rows as $val){
				if(in_array($val['uid'],$uids)==false){
					$uids[]=$val['uid'];
				}
			}
			$company=$this->obj->DB_select_all("company","`uid` in(".@implode(',',$uids).")","`uid`,`name`");			
			foreach($rows as $key=>$val){
				foreach($company as $v){
					if($val['uid']==$v['uid']){
						$rows[$key]['name']=$v['name'];
					}
				} 
			}
		}
		$this->yunset("rows",$rows); 
		$this->yunset("headertitle","企业产品");
		$this->yuntpl(array('wapadmin/admin_comproduct'));
	}  
	function show_action(){
		$row=$this->obj->DB_select_once("company_product","`id`='".$_GET['id']."'");
		$com=$this->obj->DB_select_once("company","`uid`='".$row['uid']."'","`uid`,`name`");
		$row['name']=$com['name'];
		
		
		
		
		$this->yunset('row',$row);
		$this->yunset("headertitle","企业产品");
		$this->yuntpl(array('wapadmin/admin_comproduct_show'));
	}
	function status_action(){		
		if($_POST['id']){
	        $_POST['statusbody']=$this->stringfilter($_POST['statusbody']);
	        $nid=$this->obj->DB_update_all("company_product","`status`='".$_POST['status']."',`statusbody`='".$_POST['statusbody']."'","`id`='".$_POST['id']."'");
	        if($nid){
	            $this->layer_msg('企业产品审核成功！',9,0,"index.php?c=comproduct");
	        }else{
	            $this->layer_msg('企业产品审核失败！',8);
	        }
	    }
	}
	function del_action(){ 
	    $id=intval($_GET['id']);
	    if($id){  
			$product=$this->obj->DB_select_once("company_product","`id`='".$id."'");
			if($product['pic']){
				unlink_pic("../".$product['pic']);
			} 
			$result=$this->obj->DB_delete_all("company_product","`id`='".$id."'" );
			isset($result)?$this->layer_msg('产品(ID:'.$id.')删除成功！',9,0,"index.php?c=comproduct"):$this->layer_msg('删除失败！',8);
		}else{
			$this->layer_msg('非法操作！',2);
		}
	} 
}
?>