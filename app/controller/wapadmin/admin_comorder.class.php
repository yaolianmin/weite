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
class admin_comorder_controller extends adminCommon{
	function index_action(){
		$where="1";
		if($_GET['order_state']!=""){
            $where.=" and `order_state`='".$_GET['order_state']."'";
			$urlarr['order_state']=$_GET['order_state'];
	    }
		$where.=" order by id desc";
		$urlarr['c']=$_GET['c'];
		$urlarr['page']="{{page}}";
		$pageurl=Url($_GET['m'],$urlarr,'admin');
		$rows=$this->get_page("company_order",$where,$pageurl,$this->config['sy_listnum']);
		include (APP_PATH."/config/db.data.php");
		if(is_array($rows)){
			foreach($rows as $k=>$v){
				$rows[$k]['order_state_n']=$arr_data['paystate'][$v['order_state']];
				$rows[$k]['order_type_n']=$arr_data['pay'][$v['order_type']];
				$classid[]=$v['uid'];
			}
			$group=$this->obj->DB_select_all("member","uid in (".@implode(",",$classid).")","`uid`,`username`");
			$company=$this->obj->DB_select_all("company","`uid` in (".@implode(",",$classid).")","`uid`,`name`");
			$lt=$this->obj->DB_select_all("lt_info","`uid` in (".@implode(",",$classid).")","`uid`,`realname`");
			$resume=$this->obj->DB_select_all("resume","`uid` in (".@implode(",",$classid).")","`uid`,`name`");
			foreach($rows as $k=>$v){
				foreach($company as $val){
					if($v['uid']==$val['uid']){
						$rows[$k]['comname']=$val['name'];
					}
				}				
				foreach($group as $val){
						if($v['uid']==$val['uid']){
							$rows[$k]['username']=$val['username'];
						}					
				}
				
				foreach($lt as $val){
					if($v['uid']==$val['uid']){
						$rows[$k]['comname']=$val['realname'];
					}
				}
				foreach($resume as $val){
					if($v['uid']==$val['uid']){
						$rows[$k]['comname']=$val['name'];
					}
				}
			}
		}
		
		
		
		
			$this->yunset("backurl", basename($_SERVER['HTTP_REFERER']));
		
		$this->yunset("rows",$rows);
		$this->yunset("headertitle","充值记录");
		$this->yuntpl(array('wapadmin/admin_comorder'));
	}
	function edit_action(){
		$id=(int)$_GET['id'];
		$row=$this->obj->DB_select_alls('member',"company_order","b.`id`='".$id."' and a.`uid`=b.`uid`","a.username,b.*");		
		if(is_array($row)){			
			foreach($row as $k=>$v){
				$classid[]=$v['uid'];
			}
			$company=$this->obj->DB_select_all("company","`uid` in (".@implode(",",$classid).")","`uid`,`name`");
			$lt=$this->obj->DB_select_all("lt_info","`uid` in (".@implode(",",$classid).")","`uid`,`realname`");
			$resume=$this->obj->DB_select_all("resume","`uid` in (".@implode(",",$classid).")","`uid`,`name`");
			foreach($row as $k=>$v){
				$orderbank=@explode("@%",$v['order_bank']);
				if(is_array($orderbank)){
					foreach($orderbank as $key){
						$orderbank[]=$key;
					}
					$row[$k]['bankname']=$orderbank[0];
					$row[$k]['bankid']=$orderbank[1];
				}
				foreach($company as $val){
					if($v['uid']==$val['uid']){
						$row[$k]['comname']=$val['name'];
					}
				}
				foreach($lt as $val){
					if($v['uid']==$val['uid']){
						$row[$k]['comname']=$val['realname'];
					}
				}
				foreach($resume as $val){
					if($v['uid']==$val['uid']){
						$row[$k]['comname']=$val['name'];
					}
				}
			}
		}
		if($row[0]['coupon']){
			$coupon=$this->obj->DB_select_once("coupon_list","`uid`='".$row[0]['uid']."' and `validity`>'".time()."' and `status`='1'");
			$row[0]['price']=number_format($row[0]['order_price'],2);
			$row[0]['order_price']=number_format($row[0]['order_price']-$coupon['coupon_amount'],2);
			$coupon['coupon_amount']=number_format($coupon['coupon_amount'],2);
			$this->yunset("coupon",$coupon);
		}
		if($_POST['update']){
		    $_POST=$this->post_trim($_POST);
		    if($_POST['coupon_amount']){
		        $_POST['order_price']=$_POST['order_price']+$_POST['coupon_amount'];
		    }
		    if(is_uploaded_file($_FILES['order_pic']['tmp_name'])){
				$UploadM=$this->MODEL('upload');
		        $upload=$UploadM->Upload_pic("../data/upload/order/");
		        $pictures=$upload->picture($_FILES['order_pic']);
				$picmsg=$UploadM->picmsg($pictures,$_SERVER['HTTP_REFERER']);
				if($picmsg['status']==$pictures){
					$data['msg']=$picmsg['msg'];
				}else{
				    $pictures = str_replace("../data/upload/order","./data/upload/order",$pictures);
				}
		    }else{
		        $order=$this->obj->DB_select_once("company_order","`id`='".(int)$_POST['id']."'");
		        $pictures=$order['order_pic'];
		    }
		    if(mb_strlen($pictures)!=1){ 
		        $r_id=$this->obj->DB_update_all("company_order","`order_price`='".$_POST['order_price']."',`order_remark`='".$_POST['order_remark']."',`is_invoice`='".$_POST['is_invoice']."',`order_pic`='".$pictures."'","id='".$_POST['id']."'");
		        if($r_id){
		            $data['msg']='充值记录修改成功';
		            $this->MODEL('log')->admin_log($data['msg']);
		        }else{
		            $data['msg']='确认失败,请销后再试';
		        }
		        if($_POST['lasturl']==''){
		            $_POST['lasturl']=$_SERVER['HTTP_REFERER'];
		        }
		        $data['url']=$_POST['lasturl'].'&last=1';
		    }else{
				$data['msg']=$data['msg'];
				if($_POST['lasturl']==''){
		            $_POST['lasturl']=$_SERVER['HTTP_REFERER'];
		        }
		        $data['url']=$_POST['lasturl'].'&last=1';
			}
		    if($data){
		        $this->yunset("layer",$data);
		    }
		}
		
		$lasturl=$_SERVER['HTTP_REFERER'];
		if(strpos($lasturl, 'a=edit')===false){
		    if(strpos($lasturl, 'c=admin_comorder')!==false){
		        $this->cookie->setcookie('lasturl',$lasturl,time()+300);
		        $_COOKIE['lasturl']=$lasturl;
		    }
		}
		$this->yunset('lasturl',$_COOKIE['lasturl']);
		
		if(intval($_GET['last']==1)){
		    $backurl='index.php?c=admin_comorder';
		    $this->yunset("backurl", $backurl);
		}
		
		$this->yunset("row",$row[0]);
		$this->yunset("headertitle","充值记录详情");
		$this->yuntpl(array('wapadmin/admin_comorder_show'));
	}
	
	function setpay_action(){
		$del=(int)$_GET['id'];
		
		$row=$this->obj->DB_select_once("company_order","`id`='$del'");
		if($row['order_state']=='1'||$row['order_state']=='3'){
			$nid=$this->MODEL('qrorder')->upuser_statis($row);
			if($nid){
			    $this->layer_msg('充值记录(ID:'.$del.')确认成功！',9,0,$_SERVER['HTTP_REFERER']);
			}else{
			    $this->layer_msg('确认失败,请销后再试！',8);
			}
		}else{
			$this->layer_msg("订单已确认，请勿重复操作！",8);
		}
	}
	
	function del_action(){
	    
	    $delid=(int)$_GET['id'];
	    if(!$delid){
	        $this->layer_msg('请选择要删除的记录！',8,0,$_SERVER['HTTP_REFERER']);
	    }
	    $order=$this->obj->DB_select_once("company_order","`id`='".$delid."'" ,"`order_id`");
	    $this->obj->DB_delete_all("invoice_record","`order_id`='".$order['order_id']."'" );
	    $del=$this->obj->DB_delete_all("company_order","`id`='".$delid."'");
	    if($del){
	        $this->layer_msg('充值记录(ID:'.$delid.')删除成功！',9,0,$_SERVER['HTTP_REFERER']);
	    }else{
	        $this->layer_msg('删除失败！',8);
	    }
	}
}
?>