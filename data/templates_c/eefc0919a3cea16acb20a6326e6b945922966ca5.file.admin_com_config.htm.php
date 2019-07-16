<?php /* Smarty version Smarty-3.1.21-dev, created on 2019-03-13 12:13:04
         compiled from "/www/wwwroot/hr/app/template/admin/admin_com_config.htm" */ ?>
<?php /*%%SmartyHeaderCode:2132620525c8883500d8198-84843131%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'eefc0919a3cea16acb20a6326e6b945922966ca5' => 
    array (
      0 => '/www/wwwroot/hr/app/template/admin/admin_com_config.htm',
      1 => 1517903410,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2132620525c8883500d8198-84843131',
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
  'unifunc' => 'content_5c8883501b0f65_35684815',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5c8883501b0f65_35684815')) {function content_5c8883501b0f65_35684815($_smarty_tpl) {?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
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
<title>后台管理</title>
</head>
<body class="body_ifm">
<form class="layui-form">
<div class="infoboxp"> 
<div class="tabs_info">
	<ul>
		<li class="curr"><a href="index.php?m=admin_comset">企业设置</a></li>
		<li><a href="index.php?m=admin_comset&c=logo">头像设置</a></li> 
		<li><a href="index.php?m=admin_comset&c=set"><?php echo $_smarty_tpl->tpl_vars['config']->value['integral_pricename'];?>
设置</a></li> 
		<li><a href="index.php?m=admin_comset&c=comspend">消费设置</a></li>  
		<li><a href="index.php?m=admin_comset&c=rating">套餐设置</a></li>  
		<li><a href="index.php?m=admin_comset&c=reward">职位推广设置</a></li> 
	</ul>
</div>
    
<div class="admin_new_tip">
<a href="javascript:;" class="admin_new_tip_close"></a>
<a href="javascript:;" class="admin_new_tip_open" style="display:none;"></a>
<div class="admin_new_tit"><i class="admin_new_tit_icon"></i>操作提示</div>
<div class="admin_new_tip_list_cont">
<div class="admin_new_tip_list">管理员可以设置网站企业用户相关（职位、企业审核、手机强制认证等）设置操作，通过该设置实现网站部分功能开启或关闭功能！</div>
</div>
</div>
<div class="clear"></div>
<div class="main_tag mt10">
<div class="tag_box"><div>
<table width="100%" class="table_form">
  <tr class="admin_table_trbg">
    <th width="260" bgcolor="#f0f6fb"><span class="admin_bold">参数说明</span></th>
    <td bgcolor="#f0f6fb"><span class="admin_bold">参数值</span></td>
    </tr>
  <tr>
    <th width="220">开启企业会员审核：</th>
    <td>
      <div class="layui-form-item">
        <div class="layui-input-block">
          <input type="radio" name="com_status" value="0" title="审核" <?php if ($_smarty_tpl->tpl_vars['config']->value['com_status']==0) {?>checked<?php }?>>
          <input type="radio" name="com_status" value="1" title="不审核" <?php if ($_smarty_tpl->tpl_vars['config']->value['com_status']==1) {?>checked<?php }?>>
        </div>
      </div>
    </td>
  </tr>
  <tr class="admin_table_trbg">
    <th width="220">强制邮箱认证：</th>
    <td>
      <div class="layui-form-item">
        <div class="layui-input-block">
          <input type="radio" name="com_enforce_emailcert" value="1" title="开启" <?php if ($_smarty_tpl->tpl_vars['config']->value['com_enforce_emailcert']==1) {?>checked<?php }?>>
          <input type="radio" name="com_enforce_emailcert" value="0" title="关闭" <?php if ($_smarty_tpl->tpl_vars['config']->value['com_enforce_emailcert']==0) {?>checked<?php }?>>
        </div>
      </div>
    </td>
  </tr>
    <tr>
    <th width="220">强制手机认证：</th>
    <td>
      <div class="layui-form-item">
        <div class="layui-input-block">
          <input type="radio" name="com_enforce_mobilecert" value="1" title="开启" <?php if ($_smarty_tpl->tpl_vars['config']->value['com_enforce_mobilecert']==1) {?>checked<?php }?>>
          <input type="radio" name="com_enforce_mobilecert" value="0" title="关闭" <?php if ($_smarty_tpl->tpl_vars['config']->value['com_enforce_mobilecert']==0) {?>checked<?php }?>>
        </div>
      </div>
    </td>
  </tr>
    <tr class="admin_table_trbg">
    <th width="220">强制营业执照认证：</th>
    <td>
      <div class="layui-form-item">
        <div class="layui-input-block">
          <input type="radio" name="com_enforce_licensecert" value="1" title="开启" <?php if ($_smarty_tpl->tpl_vars['config']->value['com_enforce_licensecert']==1) {?>checked<?php }?>>
          <input type="radio" name="com_enforce_licensecert" value="0" title="关闭" <?php if ($_smarty_tpl->tpl_vars['config']->value['com_enforce_licensecert']==0) {?>checked<?php }?>>
        </div>
      </div>
    </td>
  </tr>
    <tr>
    <th width="220">强制设置地理位置：</th>
    <td>
      <div class="layui-form-item">
        <div class="layui-input-block">
          <input type="radio" name="com_enforce_setposition" value="1" title="开启" <?php if ($_smarty_tpl->tpl_vars['config']->value['com_enforce_setposition']==1) {?>checked<?php }?>>
          <input type="radio" name="com_enforce_setposition" value="0" title="关闭" <?php if ($_smarty_tpl->tpl_vars['config']->value['com_enforce_setposition']==0) {?>checked<?php }?>>
        </div>
      </div>
    </td>
  </tr>
  <tr class="admin_table_trbg">
    <th width="220">开启求职咨询：</th>
    <td>
      <div class="layui-form-item">
        <div class="layui-input-block">
          <input type="radio" name="com_message" value="1" title="开启" <?php if ($_smarty_tpl->tpl_vars['config']->value['com_message']==1) {?>checked<?php }?>>
          <input type="radio" name="com_message" value="0" title="关闭" <?php if ($_smarty_tpl->tpl_vars['config']->value['com_message']==0) {?>checked<?php }?>>
        </div>
      </div>
        </td>
  </tr>
  <tr>
    <th width="220">举报虚假人才：</th>
    <td>
      <div class="layui-form-item">
        <div class="layui-input-block">
          <input type="radio" name="com_report" value="1" title="开启" <?php if ($_smarty_tpl->tpl_vars['config']->value['com_report']==1) {?>checked<?php }?>>
          <input type="radio" name="com_report" value="0" title="关闭" <?php if ($_smarty_tpl->tpl_vars['config']->value['com_report']==0) {?>checked<?php }?>>
        </div>
      </div>
    </td>
  </tr>
  <tr class="admin_table_trbg">
    <th width="220">企业搜索器数量：</th>
    <td><input class="input-text tips_class input_text_rp" type="text" name="com_finder" id="com_finder" value="<?php echo $_smarty_tpl->tpl_vars['config']->value['com_finder'];?>
" size="20" maxlength="255" onKeyUp="this.value=this.value.replace(/[^0-9.]/g,'')"/>个
    <span class="admin_web_tip">数量太多，发送订阅邮件会很慢，为空则不限</span>
    </td>
  </tr>
    <tr>
    <th width="220">上传公司LOGO大小：</th>
    <td><input class="input-text tips_class input_text_rp" type="text" name="com_pickb" id="com_pickb" value="<?php echo $_smarty_tpl->tpl_vars['config']->value['com_pickb'];?>
" size="20" maxlength="255" onKeyUp="this.value=this.value.replace(/[^0-9.]/g,'')"/>KB <span class="admin_web_tip">说明：1024KB=1M</span></td>
  </tr>
  <tr class="admin_table_trbg">
    <th width="220">企业上传图片大小：</th>
    <td><input class="input-text tips_class input_text_rp" type="text" name="com_uppic" id="com_uppic" value="<?php echo $_smarty_tpl->tpl_vars['config']->value['com_uppic'];?>
" size="20" maxlength="255" onKeyUp="this.value=this.value.replace(/[^0-9.]/g,'')"/>KB </td>
  </tr>
    <tr>
    <th width="220">企业职位发布审核：</th>
    <td>
      <div class="layui-form-item">
        <div class="layui-input-block">
          <input type="radio" name="com_job_status" value="0" title="开启" <?php if ($_smarty_tpl->tpl_vars['config']->value['com_job_status']==0) {?>checked<?php }?>>
          <input type="radio" name="com_job_status" value="1" title="关闭" <?php if ($_smarty_tpl->tpl_vars['config']->value['com_job_status']==1) {?>checked<?php }?>>
        </div>
      </div>
        </td>
  </tr>
    
    <tr class="admin_table_trbg">
    <th width="220">企业职位自动刷新时间是否随机：</th>
    <td>
      <div class="layui-form-item">
        <div class="layui-input-block">
          <input type="radio" name="sy_aurorefrand" value="1" title="是" <?php if ($_smarty_tpl->tpl_vars['config']->value['sy_aurorefrand']==1) {?>checked<?php }?>>
          <input type="radio" name="sy_aurorefrand" value="0" title="否" <?php if ($_smarty_tpl->tpl_vars['config']->value['sy_aurorefrand']==0) {?>checked<?php }?>>
        </div>
      </div>
    </td>
  </tr>
    
    <tr class="admin_table_trbg">
    <th width="220">兼职职位发布审核：</th>
    <td>
      <div class="layui-form-item">
        <div class="layui-input-block">
          <input type="radio" name="com_partjob_status" value="0" title="开启" <?php if ($_smarty_tpl->tpl_vars['config']->value['com_partjob_status']==0) {?>checked<?php }?>>
          <input type="radio" name="com_partjob_status" value="1" title="关闭" <?php if ($_smarty_tpl->tpl_vars['config']->value['com_partjob_status']==1) {?>checked<?php }?>>
        </div>
      </div>
    </td>
  </tr>
  <tr>
    <th width="220">企业认证审核：</th>
    <td>
      <div class="layui-form-item">
        <div class="layui-input-block">
          <input type="radio" name="com_cert_status" value="1" title="开启" <?php if ($_smarty_tpl->tpl_vars['config']->value['com_cert_status']==1) {?>checked<?php }?>>
          <input type="radio" name="com_cert_status" value="0" title="关闭" <?php if ($_smarty_tpl->tpl_vars['config']->value['com_cert_status']==0) {?>checked<?php }?>>
        </div>
      </div>  
        </td>
  </tr>
    
    <tr>
    <th width="220">已认证企业免职位审核：</th>
    <td>
      <div class="layui-form-item">
        <div class="layui-input-block">
          <input type="radio" name="com_free_status" value="1" title="开启" <?php if ($_smarty_tpl->tpl_vars['config']->value['com_free_status']==1) {?>checked<?php }?>>
          <input type="radio" name="com_free_status" value="0" title="关闭" <?php if ($_smarty_tpl->tpl_vars['config']->value['com_free_status']==0) {?>checked<?php }?>>
        </div>
      </div>  
    </td>
  </tr>
  <tr>
    <th width="220">会员到期提醒：</th>
    <td><input class="input-text tips_class input_text_rp" type="text" name="sy_maturityday" id="sy_maturityday" value="<?php echo $_smarty_tpl->tpl_vars['config']->value['sy_maturityday'];?>
" size="10" />天之内</td>
  </tr>
  <tr class="admin_table_trbg">
    <th width="220">会员到期提醒频率：</th>
    <td><input class="input-text tips_class input_text_rp" type="text" name="sy_maturityfrequency" id="sy_maturityfrequency" value="<?php echo $_smarty_tpl->tpl_vars['config']->value['sy_maturityfrequency'];?>
" size="10" />天一次</td>
  </tr>
  <tr>
    <th width="220">会员到期后默认为：</th>
    <td>
      <div class="layui-form-item">
        <div class="layui-input-block">
          <input type="radio" name="com_vip_done" value="1" title="系统会员" <?php if ($_smarty_tpl->tpl_vars['config']->value['com_vip_done']==1) {?>checked<?php }?>>
          <input type="radio" name="com_vip_done" value="0" title="非会员" <?php if ($_smarty_tpl->tpl_vars['config']->value['com_vip_done']==0) {?>checked<?php }?>>
          <span class="admin_web_tip">‘系统会员’，即企业注册默认等级会员</span>
        </div>
      </div>
    </td>
  </tr>
  <tr>
      <th width="220">企业申请消费发票：</th>
    <td>
      <div class="layui-form-item">
        <div class="layui-input-block">
          <input type="radio" name="sy_com_invoice" value="1" title="开启" <?php if ($_smarty_tpl->tpl_vars['config']->value['sy_com_invoice']==1) {?>checked<?php }?>>
          <input type="radio" name="sy_com_invoice" value="0" title="关闭" <?php if ($_smarty_tpl->tpl_vars['config']->value['sy_com_invoice']==0) {?>checked<?php }?>>
          <span class="admin_web_tip">若选择“关闭”，则不显示开具发票功能</span>
        </div>
      </div>
    </td>
    </tr>
    <tr class="admin_table_trbg">
    <th width="220">登录才能搜索简历：</th>
    <td>
      <div class="layui-form-item">
        <div class="layui-input-block">
          <input type="radio" name="com_search" value="1" title="开启" <?php if ($_smarty_tpl->tpl_vars['config']->value['com_search']==1) {?>checked<?php }?>>
          <input type="radio" name="com_search" value="0" title="关闭" <?php if ($_smarty_tpl->tpl_vars['config']->value['com_search']==0) {?>checked<?php }?>>
          </div>
      </div>
    </td>
  </tr>
    
  <tr class="admin_table_trbg">
    <td colspan="2" align="center">
        <input type="hidden" name="pytoken" id='pytoken' value="<?php echo $_smarty_tpl->tpl_vars['pytoken']->value;?>
">
        <input class="layui-btn layui-btn-normal" id="mconfig" type="button" name="config" value="提交" />&nbsp;&nbsp;
        <input class="layui-btn layui-btn-normal" type="reset" value="重置" /></td>
  </tr>
</table>
</div>

</div>
</div>
<?php echo '<script'; ?>
>
layui.use(['layer', 'form'], function(){
    var layer = layui.layer
    ,form = layui.form
    ,$ = layui.$;
  });

$(function(){
  $("#mconfig").click(function(){
    $.post("index.php?m=admin_comset&c=save",{
      config : $("#mconfig").val(),
      com_pickb : $("#com_pickb").val(),
      com_enforce_emailcert : $("input[name=com_enforce_emailcert]:checked").val(),
      com_enforce_mobilecert : $("input[name=com_enforce_mobilecert]:checked").val(),
      com_enforce_licensecert : $("input[name=com_enforce_licensecert]:checked").val(),
      com_enforce_setposition : $("input[name=com_enforce_setposition]:checked").val(),
      com_uppic : $("#com_uppic").val(),
      com_finder : $("#com_finder").val(),
      com_job_status : $("input[name=com_job_status]:checked").val(),
      com_partjob_status : $("input[name=com_partjob_status]:checked").val(),
      sy_aurorefrand : $("input[name=sy_aurorefrand]:checked").val(),
      com_cert_status : $("input[name=com_cert_status]:checked").val(),
      com_vip_done : $("input[name=com_vip_done]:checked").val(),
      com_message : $("input[name=com_message]:checked").val(),
      com_report : $("input[name=com_report]:checked").val(),
      com_status : $("input[name=com_status]:checked").val(),
      com_free_status : $("input[name=com_free_status]:checked").val(),
      sy_maturityday: $("#sy_maturityday").val(),
      sy_maturityfrequency: $("#sy_maturityfrequency").val(),
      sy_com_invoice : $("input[name=sy_com_invoice]:checked").val(),
      com_search : $("input[name=com_search]:checked").val(),
      pytoken:$("#pytoken").val()
    },function(data,textStatus){
      config_msg(data);
    });
  });
})
<?php echo '</script'; ?>
></div>
</form>
</body>
</html><?php }} ?>
