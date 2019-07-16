<?php /* Smarty version Smarty-3.1.21-dev, created on 2019-03-13 12:12:36
         compiled from "/www/wwwroot/hr/app/template/admin/admin_member_ltlist.htm" */ ?>
<?php /*%%SmartyHeaderCode:19237751985c8883343158a7-82627602%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'f755cb9b9a56280f6e479cea39ddcf3d8de3ed37' => 
    array (
      0 => '/www/wwwroot/hr/app/template/admin/admin_member_ltlist.htm',
      1 => 1524796114,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '19237751985c8883343158a7-82627602',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'config' => 0,
    'pytoken' => 0,
    'ratingarr' => 0,
    'key' => 0,
    'ratlist' => 0,
    'rows' => 0,
    'v' => 0,
    'Dname' => 0,
    'pagenav' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_5c888334429a98_87469083',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5c888334429a98_87469083')) {function content_5c888334429a98_87469083($_smarty_tpl) {?><?php if (!is_callable('smarty_function_searchurl')) include '/www/wwwroot/hr/app/include/libs/plugins/function.searchurl.php';
if (!is_callable('smarty_modifier_date_format')) include '/www/wwwroot/hr/app/include/libs/plugins/modifier.date_format.php';
?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8">

<link href="<?php echo $_smarty_tpl->tpl_vars['config']->value['sy_weburl'];?>
/js/layui/css/layui.css?v=<?php echo $_smarty_tpl->tpl_vars['config']->value['cachecode'];?>
" rel="stylesheet">
<link href="images/reset.css?v=<?php echo $_smarty_tpl->tpl_vars['config']->value['cachecode'];?>
" rel="stylesheet" type="text/css" />
<link href="images/system.css?v=<?php echo $_smarty_tpl->tpl_vars['config']->value['cachecode'];?>
" rel="stylesheet" type="text/css" />
<link href="images/table_form.css?v=<?php echo $_smarty_tpl->tpl_vars['config']->value['cachecode'];?>
" rel="stylesheet" type="text/css" />

<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['config']->value['sy_weburl'];?>
/js/jquery-1.8.0.min.js?v=<?php echo $_smarty_tpl->tpl_vars['config']->value['cachecode'];?>
"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="js/admin_public.js?v=<?php echo $_smarty_tpl->tpl_vars['config']->value['cachecode'];?>
" language="javascript"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['config']->value['sy_weburl'];?>
/js/layui/layui.js?v=<?php echo $_smarty_tpl->tpl_vars['config']->value['cachecode'];?>
" language="javascript"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['config']->value['sy_weburl'];?>
/js/layui/phpyun_layer.js?v=<?php echo $_smarty_tpl->tpl_vars['config']->value['cachecode'];?>
"><?php echo '</script'; ?>
>

<?php echo '<script'; ?>
>
$(function(){
	$(".status").click(function(){
		$("input[name=pid]").val($(this).attr("pid"));
		var uid=$(this).attr("pid");
		var status=$(this).attr("status");
		$("#status_"+status).attr("checked",true);
		 layui.use(['form'],function(){
			var form = layui.form;
			form.render();
		});
		$("input[name=uid]").val(uid);
		var pytoken=$("#pytoken").val();
		$.post("index.php?m=admin_lt_member&c=lockinfo",{pytoken:pytoken,uid:uid},function(msg){
 			$("#alertcontent").val(msg);
			status_div('锁定用户','350','240');
		});
	});
	$(".user_status").click(function(){
		var uid=$(this).attr("pid");
		var status=$(this).attr("status");
		$("#status"+status).attr("checked",true);
		 layui.use(['form'],function(){
			var form = layui.form;
			form.render();
		});
		$("input[name=uid]").val(uid);
		var pytoken=$("#pytoken").val();
 		$.post("index.php?m=admin_lt_member&c=lockinfo",{pytoken:pytoken,uid:uid},function(msg){
			$("#statusbody").val(msg);
			$.layer({
				type : 1,
				title :'猎头用户审核', 
				closeBtn : [0 , true],
				border : [10 , 0.3 , '#000', true],
				area : ['390px','230px'],
				page : {dom :"#infobox2"}
			});
		});
	});
	$(".comrating").click(function(){
  		var uid=$(this).attr("data-uid");
		var pytoken=$("#pytoken").val();
		$.post("index.php?m=admin_lt_member&c=getstatis",{uid:uid,pytoken:pytoken},function(data){
			if(data){
				var dataJson = eval("(" + data + ")"); 
				$('#lt_job_num').val(dataJson.lt_job_num);
				$('#lt_down_resume').val(dataJson.lt_down_resume);
				$('#lt_editjob_num').val(dataJson.lt_editjob_num); 
				$('#lt_breakjob_num').val(dataJson.lt_breakjob_num);
				$('#job_num').val(dataJson.job_num);
				$('#down_resume').val(dataJson.down_resume);
				$('#editjob_num').val(dataJson.editjob_num);
				$('#invite_resume').val(dataJson.invite_resume);
				$('#breakjob_num').val(dataJson.breakjob_num);
				$('#oldetime').val(dataJson.vip_etime);
				$('#vipetime').text(dataJson.vipetime);
				$('#pay').val(dataJson.pay);
				$('#integral').val(dataJson.integral);
				$('#ratuid').val(uid);
 				$("#lt_rating_val").val(dataJson.rating);
			
				
				layui.use(['form'],function(){
					var f = layui.form;
					f.render();
				});
				var ratingname = $("#lt_rating_val").find("option:selected").text();
				$('#rating_name').val(ratingname);
				$.layer({
					type : 1,
					title :'猎头会员等级修改', 
					closeBtn : [0 , true],
					border : [10 , 0.3 , '#000', true],
					area : ['600px','300px'],
					page : {dom :"#comrating"}
				});
			}else{
				parent.layer.msg('用户信息获取失败！', 2, 8);	return false;
			}
		});
	});
});
<?php echo '</script'; ?>
>

<title>后台管理</title>
</head>

<body class="body_ifm">
	<div id="status_div"  style="display:none; width: 350px; ">
		<div class="" style=" margin-top:10px; "  > 
			<form class="layui-form" name=MyForm action="index.php?m=admin_lt_member&c=lock" target="supportiframe" method="post" id="formstatus">
				<table cellspacing='1' cellpadding='1' class="admin_examine_table">
					<tr>
						<th width="80">锁定操作：</th>
						<td align="left">
							<div class="layui-form-item">
								<div class="layui-input-block">
									<div class="admin_examine_right">
										<input name="status" id="status_1" value="1" title="正常" type="radio"/>
										<input name="status" id="status_2" value="2" title="锁定" type="radio"/>
									</div>
								</div>
						   </div>
						</td>
					</tr>
					<tr>
						<th>锁定说明：</th>
						<td><textarea id="alertcontent" name="lock_info" class="admin_explain_textarea"></textarea></td>
					</tr>
					<tr>
						<td colspan='2' align="center"> 
							<input type="submit" value='确认' class="admin_examine_bth">
							<input type="button" onClick="layer.closeAll();" class="admin_examine_bth_qx" value='取消'>
						</td>
					</tr>
				</table>
				<input type="hidden" name="pytoken" value="<?php echo $_smarty_tpl->tpl_vars['pytoken']->value;?>
">
				<input name="uid" value="0" type="hidden">
			</form> 
		</div>
	</div>

	<div id="comrating"  style="display:none; width: 600px; ">  
		<form class="layui-form" action="index.php?m=admin_lt_member&c=uprating" target="supportiframe" method="post" id="formstatus">
			<table cellspacing='1' cellpadding='1' class="admin_company_table">
				<tr>
					<td align="right"><span style="font-weight:bold;">会员等级：</span></td>
					<td align="left">
            			<div class="layui-form-item">
							<div class="layui-input-block">
								<div class="layui-input-inline">
									<select name="rating" id="lt_rating_val" lay-filter="rating">
										<option value="">请选择</option>
										<?php  $_smarty_tpl->tpl_vars['ratlist'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['ratlist']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['ratingarr']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['ratlist']->key => $_smarty_tpl->tpl_vars['ratlist']->value) {
$_smarty_tpl->tpl_vars['ratlist']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['ratlist']->key;
?>
											<option value="<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['ratlist']->value;?>
</option>
										<?php } ?>
									</select>
								</div>
							</div>
						</div>

					</td>
					<td align="right" >账户<?php echo $_smarty_tpl->tpl_vars['config']->value['integral_pricename'];?>
：</td>
					<td><input type="text" name="integral"  id="integral" size="15" class="admin_c_input-tex" value="" /></td>
				</tr>
				<tr class="admin_table_trbg" >
					<td align="right"><span style="font-weight:bold;">会员到期时间：</span></td>
					<td><p id="vipetime"></p></td>
					<td align="right">延长会员有效期：</td>
					<td><input type="text" name="addday" style="width:40px;" class="admin_c_input-tex"> 天</td>
				</tr> 
				<tr>
					<td  align="right">发布职位数：</td>
					<td>
						<input type="text" name="lt_job_num" id="lt_job_num" size="15" class="admin_c_input-tex" value="" onKeyUp="this.value=this.value.replace(/[^0-9]/g,'')"/>
					</td>
					<td align="right">简历下载数：</td>
					<td><input type="text" name="lt_down_resume"  id="lt_down_resume" size="15" class="admin_c_input-tex" value="" /></td>
				</tr> 
				<tr class="admin_table_trbg" >
					<td align="right">刷新职位：</td>
					<td><input type="text" name="lt_breakjob_num"  id="lt_breakjob_num" size="15" class="admin_c_input-tex" value="" /></td>
				</tr> 
				<tr style="text-align:center;margin-top:10px">
					<td colspan='4' >
						<input type="submit"  value='确认' class="submit_btn">
						&nbsp;&nbsp;
						<input type="button"  onClick="layer.closeAll();" class="cancel_btn" value='取消'>
					</td>
				</tr>
			</table>
			<input type="hidden" name="pytoken" value="<?php echo $_smarty_tpl->tpl_vars['pytoken']->value;?>
">
			<input type="hidden" name="rating_name" id="rating_name" value="">
			<input type="hidden" name="oldetime" id="oldetime" value="">
			<input name="ratuid" id="ratuid" value="0" type="hidden">
		</form> 
	</div>

	<div id="infobox2"  style="display:none; width: 390px; ">  
		<form class="layui-form" action="index.php?m=admin_lt_member&c=status" target="supportiframe" method="post" id="formstatus">
			<table cellspacing='1' cellpadding='1' class="admin_examine_table">
				<tr>
					<th width="80">审核操作：</th>
					<td align="left">
						<div class="layui-form-item">
							<div class="layui-input-block">
								<input name="status" id="status0" value="0" title="未审核" type="radio"/>
								<input name="status" id="status1" value="1" title="正常" type="radio"/>
								<input name="status" id="status3" value="3" title="未通过" type="radio"/>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<th>审核说明：</th>
					<td><textarea id="statusbody" name="statusbody" class="admin_explain_textarea"></textarea></td> 
				</tr>
				<tr>
					<td colspan='2' align="center">
						<input type="submit" value='确认' class="admin_examine_bth">
						<input type="button" onClick="layer.closeAll();" class="admin_examine_bth_qx" value='取消'>
					</td>
				</tr>
				<input type="hidden" name="pytoken" value="<?php echo $_smarty_tpl->tpl_vars['pytoken']->value;?>
">
				<input name="uid" value="0" type="hidden">
			</table>
		</form> 
	</div>
	
	<div class="infoboxp">
		<div class="admin_new_tip">
			<div class="admin_new_tit"><i class="admin_new_tit_icon"></i>操作提示</div>
			<div class="admin_new_tip_list_cont">
				<div class="admin_new_tip_list">该页面展示了网站所有的猎头会员管理信息，可对猎头会员进行审核，删除操作。</div>
				<div class="admin_new_tip_list">可输入名称关键字进行搜索，也可进行详细的高级搜索。</div>
			</div>
		</div>
		
		<div class="clear"></div>

		<div class="admin_new_search_box"> 
			<form action="index.php" name="myform" method="get">
				<input name="m" value="admin_lt_member" type="hidden"/>
				<input type="hidden" name="status" value="<?php echo $_GET['status'];?>
"/>
				<div class="admin_new_search_name">搜索类型：</div>
				<div class="admin_Filter_text formselect" did='dtype'>
					<input type="button" value="<?php if ($_GET['type']=='1'||$_GET['type']=='') {?>用户名<?php } elseif ($_GET['type']=='2') {?>公司名称<?php } elseif ($_GET['type']=='3') {?>EMAIL<?php } else { ?>手机号<?php }?>" class="admin_Filter_but"  id="btype">
					<input type="hidden" id='type' value="<?php if ($_GET['type']) {
echo $_GET['type'];
} else { ?>1<?php }?>" name='type'>
				
					<div class="admin_Filter_text_box" style="display:none" id='dtype'>
						<ul>
							<li><a href="javascript:void(0)" onClick="formselect('1','type','用户名')">用户名</a></li>
							<li><a href="javascript:void(0)" onClick="formselect('2','type','公司名称')">公司名称</a></li> 
							<li><a href="javascript:void(0)" onClick="formselect('3','type','EMAIL')">EMAIL</a></li> 
							<li><a href="javascript:void(0)" onClick="formselect('4','type','手机号')">手机号</a></li> 
						</ul>  
					</div>
				</div> 
				<input type="text" placeholder="输入你要搜索的关键字" value="<?php echo $_GET['keyword'];?>
" name='keyword' class="admin_Filter_search"size="25">
				<input type="submit" name='search' value="搜索" class="admin_Filter_bth">
				<a href="javascript:void(0)" onclick="$('.admin_screenlist_box').toggle();"   class="admin_new_search_gj">高级搜索</a>
			</form>
			<?php echo $_smarty_tpl->getSubTemplate ("admin/admin_search.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

		</div>
		
		<div class="clear"></div> 

		<div class="table-list">
			<div class="admin_table_border">
				<iframe id="supportiframe"  name="supportiframe" onload="returnmessage('supportiframe');" style="display:none"></iframe>
				<form action="index.php" name="myform" method="get" target="supportiframe" id='myform'>
					<input name="m" value="admin_lt_member" type="hidden"/>
					<input name="c" value="del" type="hidden"/>
					<table width="100%">
						<thead>
							<tr class="admin_table_top">
								<th style="width:20px;"><label for="chkall"><input type="checkbox" id='chkAll'  onclick='CheckAll(this.form)'/></label></th>
								<th>
									<?php if ($_GET['t']=="uid"&&$_GET['order']=="asc") {?>
										<a href="<?php echo smarty_function_searchurl(array('order'=>'desc','t'=>'uid','m'=>'admin_lt_member','untype'=>'order,t'),$_smarty_tpl);?>
">用户ID<img src="images/sanj.jpg"/></a>
									<?php } else { ?>
										<a href="<?php echo smarty_function_searchurl(array('order'=>'asc','t'=>'uid','m'=>'admin_lt_member','untype'=>'order,t'),$_smarty_tpl);?>
">用户ID<img src="images/sanj2.jpg"/></a>
									<?php }?>
								</th>
								
								<th align="left">用户名/公司名称</th>
								<th>会员等级</th>
								<th>企业认证</th>
								<th align="left">手机号/EMAIL</th>
								<th>登录/注册</th>
								<th>状态</th>
								<th>站点</th>
								<th>推荐</th>
								<th>重置密码</th>
								<th class="admin_table_th_bg">操作</th>
							</tr>
						</thead>
						<tbody>
							<?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['rows']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value) {
$_smarty_tpl->tpl_vars['v']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['v']->key;
?>
								<tr align="center" <?php if (($_smarty_tpl->tpl_vars['key']->value+1)%2=='0') {?>class="admin_com_td_bg"<?php }?> id="list<?php echo $_smarty_tpl->tpl_vars['v']->value['uid'];?>
">
									<td><input type="checkbox" value="<?php echo $_smarty_tpl->tpl_vars['v']->value['uid'];?>
"  name='del[]' onclick='unselectall()' rel="del_chk" /></td>
									<td class="td1" style="text-align:center;"><?php echo $_smarty_tpl->tpl_vars['v']->value['uid'];?>
</td>
									<td class="ud" align="left">
										<a href="index.php?m=user_member&c=Imitate&uid=<?php echo $_smarty_tpl->tpl_vars['v']->value['uid'];?>
" target="_blank" class="admin_lt_username"><?php echo $_smarty_tpl->tpl_vars['v']->value['username'];?>
</a>
										<?php if ($_smarty_tpl->tpl_vars['v']->value['status']==2) {?><img src="../config/ajax_img/suo.png" alt="已锁定"><?php }?>
										<div class="mt8"><?php echo $_smarty_tpl->tpl_vars['v']->value['com_name'];?>
</div>
									</td>
									<td class="td1" style="text-align:center;">
										<?php echo $_smarty_tpl->tpl_vars['v']->value['rat_name'];?>

										<a data-uid="<?php echo $_smarty_tpl->tpl_vars['v']->value['uid'];?>
" href="javascript:void(0);" class="comrating">
											<span class="admin_company_xg_icon">[修改]</span>
										</a>
									</td>
									<td>
										<?php if ($_smarty_tpl->tpl_vars['v']->value['email_status']==1) {?>
											<img src="../config/ajax_img/1-1.png" alt="邮箱已认证" width="20" height="20">
										<?php } else { ?>
											<img src="../config/ajax_img/1-2.png" alt="邮箱未认证" width="20" height="20">
										<?php }?>
										<?php if ($_smarty_tpl->tpl_vars['v']->value['moblie_status']==1) {?>
											<img src="../config/ajax_img/2-1.png" title="手机已认证" width="20" height="20">
										<?php } else { ?>
											<img src="../config/ajax_img/2-2.png" alt="手机未认证" width="20" height="20">
										<?php }?>
										<?php if ($_smarty_tpl->tpl_vars['v']->value['yyzz_status']==1) {?>
											<img src="../config/ajax_img/3-1.png" alt="营业执照已认证" width="20" height="20">
										<?php } else { ?>
											<img src="../config/ajax_img/3-2.png" alt="营业执照未认证" width="20" height="20">
										<?php }?>
									</td>
									
									<td class="od" align="left">
										<span class="admin_new_sj"><?php echo $_smarty_tpl->tpl_vars['v']->value['moblie'];?>
</span>
										<div class=" mt8"><span class="admin_new_yx"> <?php echo $_smarty_tpl->tpl_vars['v']->value['email'];?>
</span></div>
									</td>
									
									<td class="td">
										<?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['v']->value['login_date'],"%Y-%m-%d");?>

										<div class=" mt8"><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['v']->value['reg_date'],"%Y-%m-%d");?>
</div>
									</td>
									
									<td>
										<?php if ($_smarty_tpl->tpl_vars['v']->value['status']=='1') {?>
											<span class="admin_com_Audited">已审核</span>
										<?php } elseif ($_smarty_tpl->tpl_vars['v']->value['status']=='2') {?>
											<span class="admin_com_Lock">已锁定</span>
										<?php } elseif ($_smarty_tpl->tpl_vars['v']->value['status']=='3') {?>
											<span class="admin_com_tg">未通过</span>
										<?php } else { ?>
											<span class="admin_com_noAudited">未审核</span>
										<?php }?>
									</td> 
									<td>
										<div><?php echo $_smarty_tpl->tpl_vars['Dname']->value[$_smarty_tpl->tpl_vars['v']->value['did']];?>
</div>
										<div>
											<a href="javascript:;" onclick="checksite('<?php echo $_smarty_tpl->tpl_vars['v']->value['username'];?>
','<?php echo $_smarty_tpl->tpl_vars['v']->value['uid'];?>
','index.php?m=admin_lt_member&c=checksitedid');" class="admin_company_xg_icon">重新分配</a>
										</div>
									</td>
									
									<td id="rec<?php echo $_smarty_tpl->tpl_vars['v']->value['uid'];?>
">
										<?php if ($_smarty_tpl->tpl_vars['v']->value['rec']=="1") {?>
											<a href="javascript:void(0);" onClick="rec_up('index.php?m=admin_lt_member&c=lt_rec','<?php echo $_smarty_tpl->tpl_vars['v']->value['uid'];?>
','0','rec');">
												<img src="../config/ajax_img/doneico.gif">
											</a>
										<?php } else { ?>
											<a href="javascript:void(0);" onClick="rec_up('index.php?m=admin_lt_member&c=lt_rec','<?php echo $_smarty_tpl->tpl_vars['v']->value['uid'];?>
','1','rec');">
												<img src="../config/ajax_img/errorico.gif">
											</a>
										<?php }?>
									</td>
									
									<td>
										<a href="javascript:void(0);" onClick="resetpw('<?php echo $_smarty_tpl->tpl_vars['v']->value['username'];?>
','<?php echo $_smarty_tpl->tpl_vars['v']->value['uid'];?>
');" class="admin_lt_username">
											重置密码
										</a>
									</td>
									
									<td>
										<div class="admin_new_bth_c">
											<a href="javascript:void(0);" class="admin_new_c_bth admin_new_c_bthsh user_status" pid="<?php echo $_smarty_tpl->tpl_vars['v']->value['uid'];?>
" status="<?php echo $_smarty_tpl->tpl_vars['v']->value['status'];?>
">审核</a>
											
											<a href="javascript:void(0);"  class="admin_new_c_bth admin_new_c_bthsd status" pid="<?php echo $_smarty_tpl->tpl_vars['v']->value['uid'];?>
" status="<?php echo $_smarty_tpl->tpl_vars['v']->value['status'];?>
">锁定</a>
										</div>
										<div class="admin_new_bth_c"> 
											<a href="index.php?m=admin_lt_member&c=edit&id=<?php echo $_smarty_tpl->tpl_vars['v']->value['uid'];?>
&rating=<?php echo $_smarty_tpl->tpl_vars['v']->value['rating'];?>
"class="admin_new_c_bth ">修改</a>
											
											<a href="javascript:void(0)" onClick="layer_del('确定要删除？', 'index.php?m=admin_lt_member&c=del&del=<?php echo $_smarty_tpl->tpl_vars['v']->value['uid'];?>
');" class="admin_new_c_bth admin_new_c_bth_sc">删除</a>
										</div>
									</td>
								</tr>
							<?php } ?>
							<tr style="background:#f1f1f1;">
								<td align="center"><input type="checkbox" id='chkAll2' onclick='CheckAll2(this.form)' /></td>
								<td colspan="2">
									<label for="chkAll2">全选</label>&nbsp;
									<input class="admin_button"  type="button" name="delsub" value="删除所选"  onclick="return really('del[]')"/>
								</td>
								<td colspan="12" class="digg"><?php echo $_smarty_tpl->tpl_vars['pagenav']->value;?>
</td>
							</tr>
						</tbody>
					</table>
					<input type="hidden" name="pytoken" id='pytoken' value="<?php echo $_smarty_tpl->tpl_vars['pytoken']->value;?>
">
				</form>
			</div>
		</div>
	</div>
	<?php echo '<script'; ?>
 type="text/javascript">
		layui.use(['layer', 'form'], function(){
			var layer = layui.layer,
				form = layui.form,
				$ = layui.$,
				url="index.php?m=admin_lt_member&c=getrating";

			var pytoken=$("#pytoken").val();

			form.on('select(rating)', function(data){
				$.post(url, {id : data.value, pytoken:pytoken},function(htm){
					if(htm){
						var dataJson = eval("(" + htm + ")"); 
						$('#lt_job_num').val(dataJson.lt_job_num);
						$('#lt_down_resume').val(dataJson.lt_resume);
 						$('#lt_breakjob_num').val(dataJson.lt_breakjob_num); 
						$('#vipetime').text(dataJson.vipetime);
						$('#oldetime').val(dataJson.oldetime);				
						$('#rating_name').val(dataJson.name);
					}else{
						layer.msg('请选择会员等级');
					}
					form.render('select');
				});
			});
		});
	<?php echo '</script'; ?>
>
<?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['adminstyle']->value)."/checkdomain.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

</body>
</html>
<?php }} ?>
