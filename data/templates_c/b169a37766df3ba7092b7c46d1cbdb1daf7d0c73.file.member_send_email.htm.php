<?php /* Smarty version Smarty-3.1.21-dev, created on 2019-03-13 12:12:30
         compiled from "/www/wwwroot/hr//app/template/admin/member_send_email.htm" */ ?>
<?php /*%%SmartyHeaderCode:12138770805c88832ecf1da5-13254600%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b169a37766df3ba7092b7c46d1cbdb1daf7d0c73' => 
    array (
      0 => '/www/wwwroot/hr//app/template/admin/member_send_email.htm',
      1 => 1517903408,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '12138770805c88832ecf1da5-13254600',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'pytoken' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_5c88832ecfd059_14658874',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5c88832ecfd059_14658874')) {function content_5c88832ecfd059_14658874($_smarty_tpl) {?><?php echo '<script'; ?>
>
$(function(){
   $('.closebutton').on('click', function(){
	    var index = layer.index; //获取当前弹层的索引号
		layer.close(index);    //关闭当前弹层
	});
})
function send_email(email){
	$("#email_user").val(email);
	$.layer({
		type : 1,
		title :'发送邮件', 
		closeBtn : [0 , true],
		border : [10 , 0.3 , '#000', true],
		area : ['410px','250px'],
		page : {dom :"#email_div"}
	});
}
function send_moblie(moblie){
	$("#userarr").val(moblie);
	$.layer({
		type : 1,
		title :'发送短信', 
		closeBtn : [0 , true],
		border : [10 , 0.3 , '#000', true],
		area : ['410px','220px'],
		page : {dom :"#moblie_div"}
	});
}
function send_sysmsg(uid,username){
	$("#sysmsg_user").val(uid);
	$("#sys_username").val(username);
	$.layer({
		type : 1,
		title :'发送系统消息', 
		closeBtn : [0 , true],
		border : [10 , 0.3 , '#000', true],
		area : ['410px','220px'],
		page : {dom :"#sysmsg_div"}
	});
}
function confirm_email(msg,name){
	var chk_value=[];
	var email=moblie=[];
	$('input[name="del[]"]:checked').each(function(){
		chk_value.push($(this).val());
	});
	if(chk_value.length==0){
		parent.layer.msg("请选择账户！",2,8);return false;
	}else{
		var cf=parent.layer.confirm(msg,function(){
			parent.layer.close(cf);
			if(name=='email_div'){
				$('input[name="del[]"]:checked').each(function(){
					email.push($(this).attr('email'));
				});
				$("#email_user").val(email);
				$.layer({
					type : 1,
					title :'发送邮件',  
					offset: ['20px', ''],
					area : ['410px','250px'],
					page : {dom :"#email_div"}
				});
			}else{
				$('input[name="del[]"]:checked').each(function(){
					moblie.push($(this).attr('moblie'));
				});
				$("#userarr").val(moblie);
				$.layer({
					type : 1,
					title :'发送短信', 
					offset: ['20px', ''],
					area : ['410px','220px'],
					page : {dom :"#moblie_div"}
				});
			}
		});
	}
}
function emailload(){
	if($.trim($("input[name='email_title']").val())==''){
		parent.layer.msg('请输入邮件标题！', 2, 8);return false;
	}
	if($.trim($("#content").val())==''){
		parent.layer.msg('请输入邮件内容！', 2, 8);return false;
	} 
	layer.closeAll();
	parent.layer.load('执行中，请稍候...',0);
}
function moblieload(){
	if($.trim($("#mcontent").val())==''){
		parent.layer.msg('请输入短信内容！', 2, 8);return false;
	}
	layer.closeAll();
	parent.layer.load('执行中，请稍候...',0);
}
function sysmsgload(){
	if($.trim($("#syscontent").val())==''){
		parent.layer.msg('请输入系统消息内容！', 2, 8);return false;
	}
	layer.closeAll();
	parent.layer.load('执行中，请稍候...',0);
}
<?php echo '</script'; ?>
>
<div id="moblie_div" style=" display:none;">
	<form id="formstatus" method="post" target="supportiframe" action="index.php?m=email&c=msgsave" onsubmit="return moblieload();" >
	  <table class="table_form ">
			<tr><td>短信内容：</td>
			<td><div class="formstatus_t_box">
			<textarea name="content" id="mcontent" style="width:220px;height:90px;border:1px solid #ddd"class="text"></textarea>
			</div></td></tr>
			<tr><td colspan='2' style='border-bottom:none'>
				<div class="admin_Operating_sub" style="margin-top:0px">
				<input class="submit_btn" type="submit" name='message_send' value="确认">
				<input  class="cancel_btn closebutton" type="button" value="取消">
				</div>
			</td></tr>
	  </table>
  		<input type="hidden" name="pytoken" value="<?php echo $_smarty_tpl->tpl_vars['pytoken']->value;?>
"/>
		<input type="hidden" id='userarr' name="userarr"/>
		<input type="hidden" value="4" name="all"/>
	 </form>
</div>
<div id="email_div" style=" display:none;">
	<form id="formstatus" method="post" target="supportiframe" action="index.php?m=email&c=send" onsubmit="return emailload();" >
	  <table class="table_form "  id="">
			<tr><td>邮件标题：</td><td><input name="email_title"  class="input-text" type="text" size="40"></td></tr>
			<tr><td>邮件内容：</td><td>
            <div class="formstatus_t_box"><textarea name="content" id="content" style="width:220px;height:70px;border:1px solid #ddd" class="text"></textarea></div></td></tr>
			<tr><td colspan='2' style='border-bottom:none'>
				<div class="admin_Operating_sub" style="margin-top:0px">
				<input class="submit_btn" type="submit" name='email_send' value="确认">
				<input  class="cancel_btn closebutton" type="button" value="取消">
				</div>
			</td></tr>
	  </table>
	  <input type="hidden" name="pytoken" value="<?php echo $_smarty_tpl->tpl_vars['pytoken']->value;?>
"/>
	  <input type="hidden" id='emailtpl' name="emailtpl" value="2"/>
	  <input type="hidden" id='email_user' name="email_user"/>
	  <input type="hidden" value="3" name="all[]"/>
	 </form>
</div>
<div id="sysmsg_div" style=" display:none;">
	<form id="formstatus" method="post" target="supportiframe" action="index.php?m=admin_company&c=sendsysmsg" onsubmit="return sysmsgload();" >
	  <table class="table_form ">
			<tr><td>内容：</td>
			<td><div class="formstatus_t_box">
			<textarea name="content" id="syscontent" style="width:220px;height:90px;border:1px solid #ddd" class="text"></textarea>
			</div></td></tr>
			<tr><td colspan='2' style='border-bottom:none'>
				<div class="admin_Operating_sub" style="margin-top:0px">
				<input class="submit_btn" type="submit" name='sysmsg_send' value="确认">
				<input  class="cancel_btn closebutton" type="button" value="取消">
				</div>
			</td></tr>
	  </table>
  		<input type="hidden" name="pytoken" value="<?php echo $_smarty_tpl->tpl_vars['pytoken']->value;?>
"/>
		<input type="hidden" id='sysmsg_user' name="sysmsg_user"/>
		<input type="hidden" id='sys_username' name="sys_username"/>
	 </form>
</div><?php }} ?>
