<?php /* Smarty version Smarty-3.1.21-dev, created on 2019-03-13 11:44:39
         compiled from "/www/wwwroot/hr/app/template/admin/admin_wxpay_config.htm" */ ?>
<?php /*%%SmartyHeaderCode:15367415935c887ca71f13e1-80216918%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a60a17c1ee2111d62b5853deb0295f29dd79bdba' => 
    array (
      0 => '/www/wwwroot/hr/app/template/admin/admin_wxpay_config.htm',
      1 => 1517903408,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '15367415935c887ca71f13e1-80216918',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'config' => 0,
    'wxpaydata' => 0,
    'pytoken' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_5c887ca7254434_86516846',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5c887ca7254434_86516846')) {function content_5c887ca7254434_86516846($_smarty_tpl) {?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
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
<link href="<?php echo $_smarty_tpl->tpl_vars['config']->value['sy_weburl'];?>
/js/layui/css/layui.css?v=<?php echo $_smarty_tpl->tpl_vars['config']->value['cachecode'];?>
" rel="stylesheet" type="text/css" />
<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['config']->value['sy_weburl'];?>
/js/layui/layui.js?v=<?php echo $_smarty_tpl->tpl_vars['config']->value['cachecode'];?>
"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['config']->value['sy_weburl'];?>
/js/layui/phpyun_layer.js?v=<?php echo $_smarty_tpl->tpl_vars['config']->value['cachecode'];?>
"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="js/admin_public.js?v=<?php echo $_smarty_tpl->tpl_vars['config']->value['cachecode'];?>
" language="javascript"><?php echo '</script'; ?>
> 
<?php echo '<script'; ?>
>
$(document).ready(function(){
	$(".input-text").focus(function(){ 
		var msg_id=$(this).attr('id');
		var msg=$('#'+msg_id+' + font').html(); 
		if($.trim(msg)!=''){
			layer.tips(msg, this, {
			guide: 1, 
			style: ['background-color:#5EA7DC; color:#fff;top:-7px', '#5EA7DC'], 
			}); 
			$(".xubox_layer").addClass("xubox_tips_border");
		} 
	}).blur(function () {
		layer.closeAll('tips');
	});
});
<?php echo '</script'; ?>
>
<title>后台管理</title>
</head>
<body class="body_ifm">
<div id="subboxdiv" style="width:100%;height:100%;display:none;position: absolute;"></div>

<div class="infoboxp"> 
<div class="admin_new_tip">
<a href="javascript:;" class="admin_new_tip_close"></a>
<a href="javascript:;" class="admin_new_tip_open" style="display:none;"></a>
<div class="admin_new_tit"><i class="admin_new_tit_icon"></i>操作提示</div>
<div class="admin_new_tip_list_cont">
<div class="admin_new_tip_list">微信支付证书需要存放在服务器物理路径中，才能生效！建议使用VPS或独立服务器比较好！</div>
</div>
</div>
<div class="clear"></div>
<div style="height:10px;"></div>


<div class="infoboxp_top">
<h6>微信支付设置</h6>
</div>
<div class="main_tag">
	<div class="tag_box">
	<div>
<div id="right" style="display:block">
<iframe id="supportiframe"  name="supportiframe" onload="returnmessage('supportiframe');" style="display:none"></iframe>
	<form method="post" target="supportiframe" action="" name="config" >
 <div id="paysync">
	<table id="alipay" width="100%" class="table_form">
		<tr>
			<th>APPID:</th>
			<td><input type="text" class="input-text" name="sy_wxpayappid" id="sy_wxpayappid" value="<?php echo $_smarty_tpl->tpl_vars['wxpaydata']->value['sy_wxpayappid'];?>
" size="50" maxlength="255"/><span class="admin_web_tip">绑定支付的APPID（必须配置，公众号平台可查看）</span></td>
		</tr>
		<tr>
		  <th>AppSecret:</th>
		  <td><input type="text" class="input-text" name="sy_wxappsecret" id="sy_wxappsecret" value="<?php echo $_smarty_tpl->tpl_vars['wxpaydata']->value['sy_wxappsecret'];?>
" size="50" maxlength="255"/>
			<span class="admin_web_tip">绑定支付的公众号AppSecret（必须配置，公众号平台可查看）</span></td>
		</tr>
		<tr>
			<th>商户号(MCHID):</th>
			<td><input type="text" class="input-text" name="sy_wxpaymchid" id="sy_wxpaymchid" value="<?php echo $_smarty_tpl->tpl_vars['wxpaydata']->value['sy_wxpaymchid'];?>
" size="50" maxlength="255"/><span class="admin_web_tip">商户号（必须配置，开户邮件中可查看）</span></td>
		</tr>
		<tr class="admin_table_trbg">
			<th>商户密钥(KEY):</th>
			<td><input type="text" class="input-text" name="sy_wxpaykey" id="sy_wxpaykey" value="<?php echo $_smarty_tpl->tpl_vars['wxpaydata']->value['sy_wxpaykey'];?>
" size="50" maxlength="255"/><span class="admin_web_tip">商户支付密钥，参考开户邮件设置（必须配置，登录商户平台自行设置）</span></td>
		</tr>
		<tr class="admin_table_trbg">
			<th>证书（pem格式）路径:</th>
			<td><input type="text" class="input-text" name="sy_wxpem_cert" id="sy_wxpem_cert" value="<?php echo $_smarty_tpl->tpl_vars['wxpaydata']->value['sy_wxpem_cert'];?>
" size="50" maxlength="255"/><span class="admin_web_tip">微信商户平台-API安全-下载证书，证书不要存放于网站目录下，防止被恶意下载，请填绝对路径 如D:\wxca\apiclient_cert.pem</span></td>
		</tr>
		<tr class="admin_table_trbg">
			<th>证书密钥（pem格式）路径:</th>
			<td><input type="text" class="input-text" name="sy_wxpem_key" id="sy_wxpem_key" value="<?php echo $_smarty_tpl->tpl_vars['wxpaydata']->value['sy_wxpem_key'];?>
" size="50" maxlength="255"/><span class="admin_web_tip">微信商户平台-API安全-下载证书，证书不要存放于网站目录下，防止被恶意下载，请填绝对路径 如D:\wxca\apiclient_key.pem</span></td>
		</tr>
		<tr class="admin_table_trbg">
			<th>CA证书:</th>
			<td><input type="text" class="input-text" name="sy_wxpem_ca" id="sy_wxpem_ca" value="<?php echo $_smarty_tpl->tpl_vars['wxpaydata']->value['sy_wxpem_ca'];?>
" size="50" maxlength="255"/><span class="admin_web_tip">微信商户平台-API安全-下载证书，证书不要存放于网站目录下，防止被恶意下载，请填绝对路径 如D:\wxca\rootca.pem</span></td>
		</tr>
		<tr>
			<th>xcxAPPID:</th>
			<td><input type="text" class="input-text" name="sy_xcxappid" id="sy_xcxappid" value="<?php echo $_smarty_tpl->tpl_vars['wxpaydata']->value['sy_xcxappid'];?>
" size="50" maxlength="255"/><span class="admin_web_tip">绑定支付的小程序APPID（必须配置，小程序平台可查看）</span></td>
		</tr>
		<tr>
		  <th>xcxAppSecret:</th>
		  <td><input type="text" class="input-text" name="sy_xcxsecret" id="sy_xcxsecret" value="<?php echo $_smarty_tpl->tpl_vars['wxpaydata']->value['sy_xcxsecret'];?>
" size="50" maxlength="255"/>
			<span class="admin_web_tip">绑定支付的小程序AppSecret（必须配置，小程序平台可查看）</span></td>
		</tr>
	</table>
	</div>
	<table width="100%" class="table_form">
		<tr>
			<td align="center" colspan="2"><input class="admin_button" id="pay_config" type="submit" name="pay_config" value="&nbsp; 修 改 &nbsp;"  />&nbsp;&nbsp;<input class="admin_button" type="reset" name="reset" value="&nbsp; 重 置 &nbsp;" /></td>
		</tr>
	</table>
	<input type="hidden" name="pytoken"  id='pytoken' value="<?php echo $_smarty_tpl->tpl_vars['pytoken']->value;?>
">
	</form>
</div>
</div>

</div>
</body>
</html><?php }} ?>
