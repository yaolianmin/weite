<?php /* Smarty version Smarty-3.1.21-dev, created on 2019-03-13 11:46:55
         compiled from "/www/wwwroot/hr/app/template/admin/admin_msg_tpl.htm" */ ?>
<?php /*%%SmartyHeaderCode:9699928955c887d2fbec9c9-78258461%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '22f962a09eab3a341412d0d57723ff8cc7e1f5f1' => 
    array (
      0 => '/www/wwwroot/hr/app/template/admin/admin_msg_tpl.htm',
      1 => 1524796114,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '9699928955c887d2fbec9c9-78258461',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'config' => 0,
    'pytoken' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_5c887d2fd4d428_86003169',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5c887d2fd4d428_86003169')) {function content_5c887d2fd4d428_86003169($_smarty_tpl) {?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
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
<?php echo '<script'; ?>
 src="js/admin_public.js?v=<?php echo $_smarty_tpl->tpl_vars['config']->value['cachecode'];?>
" language="javascript"><?php echo '</script'; ?>
>
<link href="<?php echo $_smarty_tpl->tpl_vars['config']->value['sy_weburl'];?>
/js/layui/css/layui.css?v=<?php echo $_smarty_tpl->tpl_vars['config']->value['cachecode'];?>
" rel="stylesheet">
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
<title>后台管理</title>
</head>
<body class="body_ifm">
<div class="infoboxp"> 
<div class="admin_new_tip">
<a href="javascript:;" class="admin_new_tip_close"></a>
<a href="javascript:;" class="admin_new_tip_open" style="display:none;"></a>
<div class="admin_new_tit"><i class="admin_new_tit_icon"></i>操作提示</div>
<div class="admin_new_tip_list_cont">
<div class="admin_new_tip_list">设置通知之前，请先配置好短信设置,否则将无法收到短信。</div>
</div>
</div>
<div class="clear"></div>

<div class="main_tag mt10">

<div class="tag_box">

    <form action="" method="post" class="layui-form">
    <table width="100%" class="table_form">
      <tr class="admin_table_trbg">
              <th width="160" bgcolor="#f0f6fb"><span class="admin_bold">参数说明</span></th>
          <td bgcolor="#f0f6fb"><span class="admin_bold">参数值</span></td>
    </tr>
	        <tr>
            <th width="160">订阅通知：</th>
            <td>
            <div class="layui-form-item">
              <div class="layui-input-block">
                 <div class="layui-input-inline">
                   <input type="radio" name="sy_msg_dy" value="1" id="sy_msg_dy_0" <?php if ($_smarty_tpl->tpl_vars['config']->value['sy_msg_dy']=="1") {?>checked<?php }?> title="通知">
                   <input type="radio" name="sy_msg_dy" value="2" id="sy_msg_dy_1" <?php if ($_smarty_tpl->tpl_vars['config']->value['sy_msg_dy']=="2") {?>checked<?php }?> title="不通知">
                 </div>
                 <div class="layui-form-mid layui-word-aux"><a href="?m=msgconfig&c=settpl&name=msgdy" style="color:blue;">设置模板</a> </div>
               </div>
            </div>
             </td>
        </tr>
		        <tr>
            <th width="160">会员到期提醒：</th>
            <td>
            <div class="layui-form-item">
              <div class="layui-input-block">
                 <div class="layui-input-inline">
                   <input type="radio" name="sy_msg_vipmr" value="1" id="sy_msg_vipmr_0" <?php if ($_smarty_tpl->tpl_vars['config']->value['sy_msg_vipmr']=="1") {?>checked<?php }?> title="通知">
                   <input type="radio" name="sy_msg_vipmr" value="2" id="sy_msg_vipmr_1" <?php if ($_smarty_tpl->tpl_vars['config']->value['sy_msg_vipmr']=="2") {?>checked<?php }?> title="不通知">
                 </div>
                 <div class="layui-form-mid layui-word-aux"><a href="?m=msgconfig&c=settpl&name=msgvipmr" style="color:blue;">设置模板</a> </div>
               </div>
            </div>
             </td>
        </tr>
        <tr>
            <th width="160">注册会员：</th>
            <td>
            <div class="layui-form-item">
              <div class="layui-input-block">
                 <div class="layui-input-inline">
                   <input type="radio" name="sy_msg_reg" value="1" id="sy_msg_reg_0" <?php if ($_smarty_tpl->tpl_vars['config']->value['sy_msg_reg']=="1") {?>checked<?php }?> title="通知">
                   <input type="radio" name="sy_msg_reg" value="2" id="sy_msg_reg_1" <?php if ($_smarty_tpl->tpl_vars['config']->value['sy_msg_reg']=="2") {?>checked<?php }?> title="不通知">
                 </div>
                 <div class="layui-form-mid layui-word-aux"><a href="?m=msgconfig&c=settpl&name=msgreg" style="color:blue;">设置模板</a> </div>
               </div>
            </div>
             </td>
        </tr>
        <tr>
            <th width="160">短信验证码登录：</th>
            <td>
            <div class="layui-form-item">
              <div class="layui-input-block">
                 <div class="layui-input-inline">
                   <input type="radio" name="sy_msg_login" value="1" id="sy_msg_login_0" <?php if ($_smarty_tpl->tpl_vars['config']->value['sy_msg_login']=="1") {?>checked<?php }?> title="开启" >
                   <input type="radio" name="sy_msg_login" value="2" id="sy_msg_login_1" <?php if ($_smarty_tpl->tpl_vars['config']->value['sy_msg_login']=="2") {?>checked<?php }?> title="关闭" >
                 </div>
                 <div class="layui-form-mid layui-word-aux"><a href="?m=msgconfig&c=settpl&name=msglogin" style="color:blue;">设置模板</a> </div>
               </div>
            </div>
             </td>
        </tr>
		<tr>
            <th width="160">审核会员：</th>
            <td>
            <div class="layui-form-item">
              <div class="layui-input-block">
                 <div class="layui-input-inline">
                   <input type="radio" name="sy_msg_userstatus" value="1" id="sy_msg_userstatus_0" <?php if ($_smarty_tpl->tpl_vars['config']->value['sy_msg_userstatus']=="1") {?>checked<?php }?> title="通知">
                   <input type="radio" name="sy_msg_userstatus" value="2" id="sy_msg_userstatus_1" <?php if ($_smarty_tpl->tpl_vars['config']->value['sy_msg_userstatus']=="2") {?>checked<?php }?> title="不通知">
                 </div>
                 <div class="layui-form-mid layui-word-aux"><a href="?m=msgconfig&c=settpl&name=msguserstatus" style="color:blue;">设置模板</a> </div>
               </div>
            </div>
             </td>
        </tr>
        <tr class="admin_table_trbg">
            <th width="160">找回密码：</th>
            <td>
            <div class="layui-form-item">
              <div class="layui-input-block">
                 <div class="layui-input-inline">
                   <input type="radio" name="sy_msg_getpass" value="1" id="sy_msg_getpass_0" <?php if ($_smarty_tpl->tpl_vars['config']->value['sy_msg_getpass']=="1") {?>checked<?php }?> title="通知">
                   <input type="radio" name="sy_msg_getpass" value="2" id="sy_msg_getpass_1" <?php if ($_smarty_tpl->tpl_vars['config']->value['sy_msg_getpass']=="2") {?>checked<?php }?> title="不通知">
                 </div>
                 <div class="layui-form-mid layui-word-aux"><a href="?m=msgconfig&c=settpl&name=msggetpass" style="color:blue;">设置模板</a> </div>
               </div>
            </div>
             </td>
        </tr>
         <tr>
            <th width="160">个人生日：</th>
            <td>
            <div class="layui-form-item">
              <div class="layui-input-block">
                 <div class="layui-input-inline">
                   <input type="radio" name="sy_msg_birthday" value="1" id="sy_msg_birthday_0" <?php if ($_smarty_tpl->tpl_vars['config']->value['sy_msg_birthday']=="1") {?>checked<?php }?> title="通知">
                   <input type="radio" name="sy_msg_birthday" value="2" id="sy_msg_birthday_1" <?php if ($_smarty_tpl->tpl_vars['config']->value['sy_msg_birthday']=="2") {?>checked<?php }?> title="不通知">
                 </div>
                 <div class="layui-form-mid layui-word-aux"><a href="?m=msgconfig&c=settpl&name=msgbirthday" style="color:blue;">设置模板</a> </div>
               </div>
            </div>
             </td>
        </tr>
         <tr>
            <th width="160">邀请面试：</th>
            <td>
            <div class="layui-form-item">
              <div class="layui-input-block">
                 <div class="layui-input-inline">
                   <input type="radio" name="sy_msg_yqms" value="1" id="sy_msg_yqms_0" <?php if ($_smarty_tpl->tpl_vars['config']->value['sy_msg_yqms']=="1") {?>checked<?php }?> title="通知">
                   <input type="radio" name="sy_msg_yqms" value="2" id="sy_msg_yqms_1" <?php if ($_smarty_tpl->tpl_vars['config']->value['sy_msg_yqms']=="2") {?>checked<?php }?> title="不通知">
                 </div>
                 <div class="layui-form-mid layui-word-aux"><a href="?m=msgconfig&c=settpl&name=msgyqms" style="color:blue;">设置模板</a> </div>
               </div>
            </div>
             </td>
        </tr>
         <tr>
            <th width="160">回复面试邀请：</th>
            <td>
            <div class="layui-form-item">
              <div class="layui-input-block">
                 <div class="layui-input-inline">
                   <input type="radio" name="sy_msg_yqmshf" value="1" id="sy_msg_yqmshf_0" <?php if ($_smarty_tpl->tpl_vars['config']->value['sy_msg_yqmshf']=="1") {?>checked<?php }?> title="通知">
                   <input type="radio" name="sy_msg_yqmshf" value="2" id="sy_msg_yqmshf_1" <?php if ($_smarty_tpl->tpl_vars['config']->value['sy_msg_yqmshf']=="2") {?>checked<?php }?> title="不通知">
                 </div>
                 <div class="layui-form-mid layui-word-aux"><a href="?m=msgconfig&c=settpl&name=msgyqmshf" style="color:blue;">设置模板</a> </div>
               </div>
            </div>
             </td>
        </tr>
        <tr class="admin_table_trbg">
            <th width="160">付款成功：</th>
            <td>
            <div class="layui-form-item">
              <div class="layui-input-block">
                 <div class="layui-input-inline">
                   <input type="radio" name="sy_msg_fkcg" value="1" id="sy_msg_fkcg_0" <?php if ($_smarty_tpl->tpl_vars['config']->value['sy_msg_fkcg']=="1") {?>checked<?php }?> title="通知">
                   <input type="radio" name="sy_msg_fkcg" value="2" id="sy_msg_fkcg_1" <?php if ($_smarty_tpl->tpl_vars['config']->value['sy_msg_fkcg']=="2") {?>checked<?php }?> title="不通知">
                 </div>
                 <div class="layui-form-mid layui-word-aux"><a href="?m=msgconfig&c=settpl&name=msgfkcg" style="color:blue;">设置模板</a> </div>
               </div>
            </div>
             </td>
        </tr>
 		<tr>
            <th width="160">职位审核通过：</th>
            <td>
            <div class="layui-form-item">
              <div class="layui-input-block">
                 <div class="layui-input-inline">
                   <input type="radio" name="sy_msg_zzshtg" value="1" id="sy_msg_zzshtg_0" <?php if ($_smarty_tpl->tpl_vars['config']->value['sy_msg_zzshtg']=="1") {?>checked<?php }?> title="通知">
                   <input type="radio" name="sy_msg_zzshtg" value="2" id="sy_msg_zzshtg_1" <?php if ($_smarty_tpl->tpl_vars['config']->value['sy_msg_zzshtg']=="2") {?>checked<?php }?> title="不通知">
                 </div>
                 <div class="layui-form-mid layui-word-aux"><a href="?m=msgconfig&c=settpl&name=msgzzshtg" style="color:blue;">设置模板</a> </div>
               </div>
            </div>
             </td>
        </tr>
        <tr class="admin_table_trbg">
            <th width="160">职位审核未通过：</th>
            <td>
            <div class="layui-form-item">
              <div class="layui-input-block">
                 <div class="layui-input-inline">
                   <input type="radio" name="sy_msg_zzshwtg" value="1" id="sy_msg_zzshwtg_0" <?php if ($_smarty_tpl->tpl_vars['config']->value['sy_msg_zzshwtg']=="1") {?>checked<?php }?> title="通知">
                   <input type="radio" name="sy_msg_zzshwtg" value="2" id="sy_msg_zzshwtg_1" <?php if ($_smarty_tpl->tpl_vars['config']->value['sy_msg_zzshwtg']=="2") {?>checked<?php }?> title="不通知">
                 </div>
                 <div class="layui-form-mid layui-word-aux"><a href="?m=msgconfig&c=settpl&name=msgzzshwtg" style="color:blue;">设置模板</a> </div>
               </div>
            </div>
             </td>
        </tr>
 		<tr>
            <th width="160">兼职审核通过：</th>
            <td>
            <div class="layui-form-item">
              <div class="layui-input-block">
                 <div class="layui-input-inline">
                   <input type="radio" name="sy_msg_partshtg" value="1" id="sy_msg_partshtg_0" <?php if ($_smarty_tpl->tpl_vars['config']->value['sy_msg_partshtg']=="1") {?>checked<?php }?> title="通知">
                   <input type="radio" name="sy_msg_partshtg" value="2" id="sy_msg_partshtg_1" <?php if ($_smarty_tpl->tpl_vars['config']->value['sy_msg_partshtg']=="2") {?>checked<?php }?> title="不通知">
                 </div>
                 <div class="layui-form-mid layui-word-aux"><a href="?m=msgconfig&c=settpl&name=msgpartshtg" style="color:blue;">设置模板</a> </div>
               </div>
            </div>
             </td>
        </tr>
        <tr class="admin_table_trbg">
            <th width="160">兼职审核未通过：</th>
            <td>
            <div class="layui-form-item">
              <div class="layui-input-block">
                 <div class="layui-input-inline">
                   <input type="radio" name="sy_msg_partshwtg" value="1" id="sy_msg_partshwtg_0" <?php if ($_smarty_tpl->tpl_vars['config']->value['sy_msg_partshwtg']=="1") {?>checked<?php }?> title="通知">
                   <input type="radio" name="sy_msg_partshwtg" value="2" id="sy_msg_partshwtg_1" <?php if ($_smarty_tpl->tpl_vars['config']->value['sy_msg_partshwtg']=="2") {?>checked<?php }?> title="不通知">
                 </div>
                 <div class="layui-form-mid layui-word-aux"><a href="?m=msgconfig&c=settpl&name=msgpartshwtg" style="color:blue;">设置模板</a> </div>
               </div>
            </div>
             </td>
        </tr>
       <tr>
            <th width="160">申请职位：</th>
            <td>
            <div class="layui-form-item">
              <div class="layui-input-block">
                 <div class="layui-input-inline">
                   <input type="radio" name="sy_msg_sqzw" value="1" id="sy_msg_sqzw_0" <?php if ($_smarty_tpl->tpl_vars['config']->value['sy_msg_sqzw']=="1") {?>checked<?php }?> title="通知">
                   <input type="radio" name="sy_msg_sqzw" value="2" id="sy_msg_sqzw_1" <?php if ($_smarty_tpl->tpl_vars['config']->value['sy_msg_sqzw']=="2") {?>checked<?php }?> title="不通知">
                 </div>
                 <div class="layui-form-mid layui-word-aux"><a href="?m=msgconfig&c=settpl&name=msgsqzw" style="color:blue;">设置模板</a> </div>
               </div>
            </div>
             </td>
        </tr>
         <tr>
            <th width="160">申请职位回复：</th>
            <td>
            <div class="layui-form-item">
              <div class="layui-input-block">
                 <div class="layui-input-inline">
                   <input type="radio" name="sy_msg_sqzwhf" value="1" id="sy_msg_sqzwhf_0" <?php if ($_smarty_tpl->tpl_vars['config']->value['sy_msg_sqzwhf']=="1") {?>checked<?php }?> title="通知">
                   <input type="radio" name="sy_msg_sqzwhf" value="2" id="sy_msg_sqzwhf_1" <?php if ($_smarty_tpl->tpl_vars['config']->value['sy_msg_sqzwhf']=="2") {?>checked<?php }?> title="不通知">
                 </div>
                 <div class="layui-form-mid layui-word-aux"><a href="?m=msgconfig&c=settpl&name=msgsqzwhf" style="color:blue;">设置模板</a> </div>
               </div>
            </div>
             </td>
        </tr>
        <tr class="admin_table_trbg">
            <th width="160">手机认证：</th>
            <td>
            <div class="layui-form-item">
              <div class="layui-input-block">
                 <div class="layui-input-inline">
                   <input type="radio" name="sy_msg_cert" value="1" id="sy_msg_cert_0" <?php if ($_smarty_tpl->tpl_vars['config']->value['sy_msg_cert']=="1") {?>checked<?php }?> title="通知">
                   <input type="radio" name="sy_msg_cert" value="2" id="sy_msg_cert_1" <?php if ($_smarty_tpl->tpl_vars['config']->value['sy_msg_cert']=="2") {?>checked<?php }?> title="不通知">
                 </div>
                 <div class="layui-form-mid layui-word-aux"><a href="?m=msgconfig&c=settpl&name=msgcert" style="color:blue;">设置模板</a> </div>
               </div>
            </div>
             </td>
        </tr>
		<tr>
            <th width="160">认领会员</th>
            <td>
            <div class="layui-form-item">
              <div class="layui-input-block">
                 <div class="layui-input-inline">
                   <input type="radio" name="sy_msg_claim" value="1" id="sy_msg_claim_0" <?php if ($_smarty_tpl->tpl_vars['config']->value['sy_msg_claim']=="1") {?>checked<?php }?> title="通知">
                   <input type="radio" name="sy_msg_claim" value="2" id="sy_msg_claim_1" <?php if ($_smarty_tpl->tpl_vars['config']->value['sy_msg_claim']=="2") {?>checked<?php }?> title="不通知">
                 </div>
                 <div class="layui-form-mid layui-word-aux"><a href="?m=msgconfig&c=settpl&name=msgclaim" style="color:blue;">设置模板</a> </div>
               </div>
            </div>
             </td>
        </tr>
        <tr>
            <th width="160">会员提醒：</th>
            <td>
            <div class="layui-form-item">
              <div class="layui-input-block">
                 <div class="layui-input-inline">
                   <input type="radio" name="sy_msg_remind" value="1" id="sy_msg_remind_0" <?php if ($_smarty_tpl->tpl_vars['config']->value['sy_msg_remind']=="1") {?>checked<?php }?> title="通知">
                   <input type="radio" name="sy_msg_remind" value="2" id="sy_msg_remind_1" <?php if ($_smarty_tpl->tpl_vars['config']->value['sy_msg_remind']=="2") {?>checked<?php }?> title="不通知">
                 </div>
                 <div class="layui-form-mid layui-word-aux"><a href="?m=msgconfig&c=settpl&name=msgremind" style="color:blue;">设置模板</a> </div>
               </div>
            </div>
             </td>
        </tr>

        <tr class="admin_table_trbg">
            <th width="160">个人订阅：</th>
            <td>
            <div class="layui-form-item">
              <div class="layui-input-block">
                 <div class="layui-input-inline">
                   <input type="radio" name="sy_msg_userdy" value="1" id="sy_msg_userdy_0" <?php if ($_smarty_tpl->tpl_vars['config']->value['sy_msg_userdy']=="1") {?>checked<?php }?> title="通知">
                   <input type="radio" name="sy_msg_userdy" value="2" id="sy_msg_userdy_1" <?php if ($_smarty_tpl->tpl_vars['config']->value['sy_msg_userdy']=="2") {?>checked<?php }?> title="不通知">
                 </div>
                 <div class="layui-form-mid layui-word-aux"><a href="?m=msgconfig&c=settpl&name=msguserdy" style="color:blue;">设置模板</a> </div>
               </div>
            </div>
             </td>
        </tr>
        <tr>
            <th width="160">企业订阅：</th>
            <td>
            <div class="layui-form-item">
              <div class="layui-input-block">
                 <div class="layui-input-inline">
                   <input type="radio" name="sy_msg_comdy" value="1" id="sy_msg_comdy_0" <?php if ($_smarty_tpl->tpl_vars['config']->value['sy_msg_comdy']=="1") {?>checked<?php }?> title="通知">
                   <input type="radio" name="sy_msg_comdy" value="2" id="sy_msg_comdy_1" <?php if ($_smarty_tpl->tpl_vars['config']->value['sy_msg_comdy']=="2") {?>checked<?php }?> title="不通知">
                 </div>
                 <div class="layui-form-mid layui-word-aux"><a href="?m=msgconfig&c=settpl&name=msgcomdy" style="color:blue;">设置模板</a> </div>
               </div>
            </div>
             </td>
        </tr>
        <tr class="admin_table_trbg">
            <th width="160">自动发送职位通知：</th>
            <td>
            <div class="layui-form-item">
              <div class="layui-input-block">
                 <div class="layui-input-inline">
                   <input type="radio" name="sy_msg_notice" value="1" id="sy_msg_notice_0" <?php if ($_smarty_tpl->tpl_vars['config']->value['sy_msg_notice']=="1") {?>checked<?php }?> title="通知">
                   <input type="radio" name="sy_msg_notice" value="2" id="sy_msg_notice_1" <?php if ($_smarty_tpl->tpl_vars['config']->value['sy_msg_notice']=="2") {?>checked<?php }?> title="不通知">
                 </div>
                 <div class="layui-form-mid layui-word-aux"><a href="?m=msgconfig&c=settpl&name=msgnotice" style="color:blue;">设置模板</a> </div>
               </div>
            </div>
             </td>
        </tr>
        <tr>
            <th width="160">注册验证码：</th>
            <td>
            <div class="layui-form-item">
              <div class="layui-input-block">
                 <div class="layui-input-inline">
                   <input type="radio" name="sy_msg_regcode" value="1" id="sy_msg_regcode_0" <?php if ($_smarty_tpl->tpl_vars['config']->value['sy_msg_regcode']=="1") {?>checked<?php }?> title="通知">
                   <input type="radio" name="sy_msg_regcode" value="2" id="sy_msg_regcode_1" <?php if ($_smarty_tpl->tpl_vars['config']->value['sy_msg_regcode']=="2") {?>checked<?php }?> title="不通知">
                 </div>
                 <div class="layui-form-mid layui-word-aux"><a href="?m=msgconfig&c=settpl&name=msgregcode" style="color:blue;">设置模板</a> </div>
               </div>
            </div>
             </td>
        </tr>
        <tr class="admin_table_trbg">
            <th width="160">充值提醒：</th>
            <td>
            <div class="layui-form-item">
              <div class="layui-input-block">
                 <div class="layui-input-inline">
                   <input type="radio" name="sy_msg_recharge" value="1" id="sy_msg_recharge_0" <?php if ($_smarty_tpl->tpl_vars['config']->value['sy_msg_recharge']=="1") {?>checked<?php }?> title="通知">
                   <input type="radio" name="sy_msg_recharge" value="2" id="sy_msg_recharge_1" <?php if ($_smarty_tpl->tpl_vars['config']->value['sy_msg_recharge']=="2") {?>checked<?php }?> title="不通知">
                 </div>
                 <div class="layui-form-mid layui-word-aux"><a href="?m=msgconfig&c=settpl&name=msgrecharge" style="color:blue;">设置模板</a> </div>
               </div>
            </div>
             </td>
        </tr>
        <tr>
            <th width="160">兼职报名：</th>
            <td>
            <div class="layui-form-item">
              <div class="layui-input-block">
                 <div class="layui-input-inline">
                   <input type="radio" name="sy_msg_partapply" value="1" id="sy_msg_partapply_0" <?php if ($_smarty_tpl->tpl_vars['config']->value['sy_msg_partapply']=="1") {?>checked<?php }?> title="通知">
                   <input type="radio" name="sy_msg_partapply" value="2" id="sy_msg_partapply_1" <?php if ($_smarty_tpl->tpl_vars['config']->value['sy_msg_partapply']=="2") {?>checked<?php }?> title="不通知">
                 </div>
                 <div class="layui-form-mid layui-word-aux"><a href="?m=msgconfig&c=settpl&name=msgpartapply" style="color:blue;">设置模板</a> </div>
               </div>
            </div>
             </td>
        </tr>
        <tr>
            <th width="160">网站周年庆：</th>
            <td>
            <div class="layui-form-item">
              <div class="layui-input-block">
                 <div class="layui-input-inline">
                   <input type="radio" name="sy_msg_webbirthday" value="1" id="sy_msg_webbirthday_0" <?php if ($_smarty_tpl->tpl_vars['config']->value['sy_msg_webbirthday']=="1") {?>checked<?php }?> title="通知">
                   <input type="radio" name="sy_msg_webbirthday" value="2" id="sy_msg_webbirthday_1" <?php if ($_smarty_tpl->tpl_vars['config']->value['sy_msg_webbirthday']=="2") {?>checked<?php }?> title="不通知">
                 </div>
                 <div class="layui-form-mid layui-word-aux"><a href="?m=msgconfig&c=settpl&name=msgwebbirthday" style="color:blue;">设置模板</a> </div>
               </div>
            </div>
             </td>
        </tr>
        <tr>
            <th width="160">企业会员过期提醒：</th>
            <td>
            <div class="layui-form-item">
              <div class="layui-input-block">
                 <div class="layui-input-inline">
                   <input type="radio" name="sy_msg_viped" value="1" id="sy_msg_viped_0" <?php if ($_smarty_tpl->tpl_vars['config']->value['sy_msg_viped']=="1") {?>checked<?php }?> title="通知">
                   <input type="radio" name="sy_msg_viped" value="2" id="sy_msg_viped_1" <?php if ($_smarty_tpl->tpl_vars['config']->value['sy_msg_viped']=="2") {?>checked<?php }?> title="不通知">
                 </div>
                 <div class="layui-form-mid layui-word-aux"><a href="?m=msgconfig&c=settpl&name=msgviped" style="color:blue;">设置模板</a> </div>
               </div>
            </div>
             </td>
        </tr>
        <tr>
            <th width="160">个人会员未发布简历：</th>
            <td>
            <div class="layui-form-item">
              <div class="layui-input-block">
                 <div class="layui-input-inline">
                   <input type="radio" name="sy_msg_useradd" value="1" id="sy_msg_useradd_0" <?php if ($_smarty_tpl->tpl_vars['config']->value['sy_msg_useradd']=="1") {?>checked<?php }?> title="通知">
                   <input type="radio" name="sy_msg_useradd" value="2" id="sy_msg_useradd_1" <?php if ($_smarty_tpl->tpl_vars['config']->value['sy_msg_useradd']=="2") {?>checked<?php }?> title="不通知">
                 </div>
                 <div class="layui-form-mid layui-word-aux"><a href="?m=msgconfig&c=settpl&name=msguseradd" style="color:blue;">设置模板</a> </div>
               </div>
            </div>
             </td>
        </tr>
        <tr>
            <th width="160">个人会员未更新简历：</th>
            <td>
            <div class="layui-form-item">
              <div class="layui-input-block">
                 <div class="layui-input-inline">
                   <input type="radio" name="sy_msg_userup" value="1" id="sy_msg_userup_0" <?php if ($_smarty_tpl->tpl_vars['config']->value['sy_msg_userup']=="1") {?>checked<?php }?> title="通知">
                   <input type="radio" name="sy_msg_userup" value="2" id="sy_msg_userup_1" <?php if ($_smarty_tpl->tpl_vars['config']->value['sy_msg_userup']=="2") {?>checked<?php }?> title="不通知">
                 </div>
                 <div class="layui-form-mid layui-word-aux"><a href="?m=msgconfig&c=settpl&name=msguserup" style="color:blue;">设置模板</a> </div>
               </div>
            </div>
             </td>
        </tr>
        <tr>
            <th width="160">企业未发布职位提醒：</th>
            <td>
            <div class="layui-form-item">
              <div class="layui-input-block">
                 <div class="layui-input-inline">
                   <input type="radio" name="sy_msg_addjob" value="1" id="sy_msg_addjob_0" <?php if ($_smarty_tpl->tpl_vars['config']->value['sy_msg_addjob']=="1") {?>checked<?php }?> title="通知">
                   <input type="radio" name="sy_msg_addjob" value="2" id="sy_msg_addjob_1" <?php if ($_smarty_tpl->tpl_vars['config']->value['sy_msg_addjob']=="2") {?>checked<?php }?> title="不通知">
                 </div>
                 <div class="layui-form-mid layui-word-aux"><a href="?m=msgconfig&c=settpl&name=msgaddjob" style="color:blue;">设置模板</a> </div>
               </div>
            </div>
             </td>
        </tr>
        <tr>
            <th width="160">企业职位未刷新提醒：</th>
            <td>
            <div class="layui-form-item">
              <div class="layui-input-block">
                 <div class="layui-input-inline">
                   <input type="radio" name="sy_msg_upjob" value="1" id="sy_msg_upjob_0" <?php if ($_smarty_tpl->tpl_vars['config']->value['sy_msg_upjob']=="1") {?>checked<?php }?> title="通知">
                   <input type="radio" name="sy_msg_upjob" value="2" id="sy_msg_upjob_1" <?php if ($_smarty_tpl->tpl_vars['config']->value['sy_msg_upjob']=="2") {?>checked<?php }?> title="不通知">
                 </div>
                 <div class="layui-form-mid layui-word-aux"><a href="?m=msgconfig&c=settpl&name=msgupjob" style="color:blue;">设置模板</a> </div>
               </div>
            </div>
             </td>
        </tr>
   	 <tr class="admin_table_trbg">
         <td colspan="2" align="center"><input class="layui-btn layui-btn-normal" id="msgconfig" type="button" name="msgconfig" value="提交" />&nbsp;&nbsp;<input class="layui-btn layui-btn-normal" type="reset" value="重置" /></td>
        </tr>
    </table>
	<input type="hidden" name="pytoken" id='pytoken' value="<?php echo $_smarty_tpl->tpl_vars['pytoken']->value;?>
">
    </form>

</div>
</div>
<?php echo '<script'; ?>
>

$(function(){
	$("#msgconfig").click(function(){
		$.post("index.php?m=msgconfig&c=save",{
			config : $("#msgconfig").val(),
			
			sy_msg_dy : $("input[name=sy_msg_dy]:checked").val(),
			sy_msg_vipmr : $("input[name=sy_msg_vipmr]:checked").val(),
			
			
			sy_msg_getpass : $("input[name=sy_msg_getpass]:checked").val(),
			sy_msg_userstatus : $("input[name=sy_msg_userstatus]:checked").val(),
			sy_msg_yqms : $("input[name=sy_msg_yqms]:checked").val(),
			sy_msg_birthday : $("input[name=sy_msg_birthday]:checked").val(),
			sy_msg_yqmshf : $("input[name=sy_msg_yqmshf]:checked").val(),
			sy_msg_reg : $("input[name=sy_msg_reg]:checked").val(),
			sy_msg_login : $("input[name=sy_msg_login]:checked").val(),
			sy_msg_fkcg : $("input[name=sy_msg_fkcg]:checked").val(),
			sy_msg_zzshtg : $("input[name=sy_msg_zzshtg]:checked").val(),
			sy_msg_zzshwtg : $("input[name=sy_msg_zzshwtg]:checked").val(),
			sy_msg_partshtg : $("input[name=sy_msg_partshtg]:checked").val(),
			sy_msg_partshwtg : $("input[name=sy_msg_partshwtg]:checked").val(),
			sy_msg_cert : $("input[name=sy_msg_cert]:checked").val(),
			sy_msg_sqzw : $("input[name=sy_msg_sqzw]:checked").val(),
			sy_msg_sqzwhf : $("input[name=sy_msg_sqzwhf]:checked").val(),
			sy_msg_remind : $("input[name=sy_msg_remind]:checked").val(),
			sy_msg_claim : $("input[name=sy_msg_claim]:checked").val(),
			sy_msg_userdy : $("input[name=sy_msg_userdy]:checked").val(),
			sy_msg_comdy : $("input[name=sy_msg_comdy]:checked").val(),
			sy_msg_notice : $("input[name=sy_msg_notice]:checked").val(),
			sy_msg_regcode : $("input[name=sy_msg_regcode]:checked").val(),
			sy_msg_recharge : $("input[name=sy_msg_recharge]:checked").val(),
			sy_msg_partapply : $("input[name=sy_msg_partapply]:checked").val(),
			sy_msg_webbirthday : $("input[name=sy_msg_webbirthday]:checked").val(),
			sy_msg_viped : $("input[name=sy_msg_viped]:checked").val(),
			sy_msg_useradd : $("input[name=sy_msg_useradd]:checked").val(),
			sy_msg_userup : $("input[name=sy_msg_userup]:checked").val(),
			sy_msg_addjob : $("input[name=sy_msg_addjob]:checked").val(),
			sy_msg_upjob : $("input[name=sy_msg_upjob]:checked").val(),
			
			pytoken : $("#pytoken").val()
		},function(data,textStatus){
			config_msg(data);
		});
	});
})
<?php echo '</script'; ?>
>
</div>
<?php echo '<script'; ?>
> 
layui.use(['layer', 'form'], function(){
    var layer = layui.layer
    ,form = layui.form
    ,$ = layui.$;
});
<?php echo '</script'; ?>
>
</body>
</html><?php }} ?>
