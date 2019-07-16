<?php /* Smarty version Smarty-3.1.21-dev, created on 2019-03-13 12:15:03
         compiled from "/www/wwwroot/hr/app/template/admin/admin_hotjob.htm" */ ?>
<?php /*%%SmartyHeaderCode:19253273525c8883c75b7997-89096930%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '82b5509d377b50fe948445de011ad48b71ebfd3d' => 
    array (
      0 => '/www/wwwroot/hr/app/template/admin/admin_hotjob.htm',
      1 => 1517903408,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '19253273525c8883c75b7997-89096930',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'config' => 0,
    'pytoken' => 0,
    'rows' => 0,
    'key' => 0,
    'v' => 0,
    'pagenav' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_5c8883c766bc26_80870247',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5c8883c766bc26_80870247')) {function content_5c8883c766bc26_80870247($_smarty_tpl) {?><?php if (!is_callable('smarty_function_searchurl')) include '/www/wwwroot/hr/app/include/libs/plugins/function.searchurl.php';
if (!is_callable('smarty_modifier_date_format')) include '/www/wwwroot/hr/app/include/libs/plugins/modifier.date_format.php';
?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
<link href="images/reset.css?v=<?php echo $_smarty_tpl->tpl_vars['config']->value['cachecode'];?>
" rel="stylesheet" type="text/css" />
<link href="images/system.css?v=<?php echo $_smarty_tpl->tpl_vars['config']->value['cachecode'];?>
" rel="stylesheet" type="text/css" />
<link href="images/table_form.css?v=<?php echo $_smarty_tpl->tpl_vars['config']->value['cachecode'];?>
" rel="stylesheet" type="text/css" />
<!--[if IE 6]>
<?php echo '<script'; ?>
 src="./js/png.js?v=<?php echo $_smarty_tpl->tpl_vars['config']->value['cachecode'];?>
"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
>
  DD_belatedPNG.fix('.png,.admin_infoboxp_tj,');
<?php echo '</script'; ?>
>
<![endif]-->
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
 type="text/javascript" src="js/admin_public.js?v=<?php echo $_smarty_tpl->tpl_vars['config']->value['cachecode'];?>
"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 type="text/javascript" src="js/show_pub.js?v=<?php echo $_smarty_tpl->tpl_vars['config']->value['cachecode'];?>
"><?php echo '</script'; ?>
>  
<title>后台管理</title>
<?php echo '<script'; ?>
>
$(document).ready(function() {
	$(".preview").hover(function(){  
		var pic_url=$(this).attr('url');
		layer.tips("<img src="+pic_url+" style='max-width:380px'>", this, {
			guide:3,
			style: ['background-color:#5EA7DC; color:#fff;top:-7px;left:-20px', '#5EA7DC']
		});
		$(".xubox_layer").addClass("xubox_tips_border");
	},function(){layer.closeAll('tips');});  
});
<?php echo '</script'; ?>
>
</head>

<body class="body_ifm" style="font-size:12px; line-height:20px;">
<div class="infoboxp"> 

<div class="admin_new_tip">
<a href="javascript:;" class="admin_new_tip_close"></a>
<a href="javascript:;" class="admin_new_tip_open" style="display:none;"></a>
<div class="admin_new_tit"><i class="admin_new_tit_icon"></i>操作提示</div>
<div class="admin_new_tip_list_cont">
<div class="admin_new_tip_list">该页面展示了网站所有的名企招聘信息，可对名企招聘进行编辑修改操作。</div>
<div class="admin_new_tip_list">可输入名称关键字进行搜索，也可进行详细的高级搜索。</div>
</div>
</div>
<div class="clear"></div>
<div class="admin_new_search_box">
 <form action="index.php" name="myform" method="get" >
 <input type="hidden" name="m" value="admin_hotjob"/>
<input type="hidden" name="status" value="<?php echo $_GET['status'];?>
"/>
<input type="hidden" name="rec" value="<?php echo $_GET['rec'];?>
"/>
<input type="hidden" name="time" value="<?php echo $_GET['time'];?>
"/>
<input type="hidden" name="rating" value="<?php echo $_GET['rating'];?>
"/>
<div class="admin_new_search_name">搜索类型：</div>

<div class="admin_Filter_text formselect" did="dctype">
        <input type="button" <?php if ($_GET['ctype']=='2') {?> value="备注" <?php } else { ?> value="企业名称" <?php }?> class="admin_new_select_text" id="bctype">
        <input type="hidden" name="ctype" id="ctype"/>
        <div class="admin_Filter_text_box" style="display:none" id="dctype">
          <ul>
            <li><a href="javascript:void(0)" onClick="formselect('1','ctype','企业名称')">企业名称</a></li>
            <li><a href="javascript:void(0)" onClick="formselect('2','ctype','备注')">备注</a></li>
          </ul>
        </div>
      </div>
 <input type="text" placeholder="输入你要搜索的关键字" name="keyword" class="admin_new_text">
	<input type="submit" value="搜索" class="admin_new_bth"/>

<a  href="javascript:void(0)" onclick="$('.admin_screenlist_box').toggle();"   class="admin_new_search_gj">高级搜索</a>


  </form>
 <?php echo $_smarty_tpl->getSubTemplate ("admin/admin_search.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

</div>
<div class="clear"></div>
 <div class="table-list">
    <div class="admin_table_border">
      <iframe id="supportiframe"  name="supportiframe" onload="returnmessage('supportiframe');" style="display:none"></iframe>
      <form action="index.php" name="myform" method="get" id='myform' target="supportiframe">
      <input type="hidden" name="pytoken"  id='pytoken' value="<?php echo $_smarty_tpl->tpl_vars['pytoken']->value;?>
">
        <input name="m" value="admin_hotjob" type="hidden"/>
        <input name="c" value="del" type="hidden"/>
        <table width="100%">
          <thead>
            <tr class="admin_table_top" >
              <th style="width:20px;"> <label for="chkall"><input type="checkbox" id='chkAll'  onclick='CheckAll(this.form)'/></label></th>
              <th> <?php if ($_GET['t']=="uid"&&$_GET['order']=="asc") {?> <a href="<?php echo smarty_function_searchurl(array('order'=>'desc','t'=>'uid','m'=>'admin_hotjob','untype'=>'order,t'),$_smarty_tpl);?>
">用户ID<img src="images/sanj.jpg"/></a> <?php } else { ?> <a href="<?php echo smarty_function_searchurl(array('order'=>'asc','t'=>'uid','m'=>'admin_hotjob','untype'=>'order,t'),$_smarty_tpl);?>
">用户ID<img src="images/sanj2.jpg"/></a> <?php }?> </th>
              <th align="left">企业名称</th>
              <th align="center">会员等级</th>
              <th>名企图片</th>
              <th>服务价格</th>
              <th>开始时间</th>
              <th>结束时间</th>
              <th>备注</th>
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
" class="check_all"  name='del[]' onclick='unselectall()' rel="del_chk" /></td>
           <td align="left" class="td1" style="text-align:center;"><span><?php echo $_smarty_tpl->tpl_vars['v']->value['uid'];?>
</span></td>
            <td  style="width:180px" align="left">
			<div style="width:180px;"><a href="index.php?m=admin_company&c=Imitate&uid=<?php echo $_smarty_tpl->tpl_vars['v']->value['uid'];?>
" target="_blank" class="admin_cz_sc"><?php echo $_smarty_tpl->tpl_vars['v']->value['username'];?>
</a></div></td>            
			<td align="center"><div class="admin_table_w84"><?php echo $_smarty_tpl->tpl_vars['v']->value['rating'];?>
</div></td>
            <td><div class="admin_table_w84"><?php if ($_smarty_tpl->tpl_vars['v']->value['hot_pic']) {?><a href="javascript:void(0)" class="preview admin_n_img" url="<?php echo $_smarty_tpl->tpl_vars['v']->value['hot_pic'];?>
"></a><?php } else { ?>无<?php }?></div></td>
             <td><div class="admin_table_w84"><?php echo $_smarty_tpl->tpl_vars['v']->value['service_price'];?>
元</div></td>
              <td><div class="admin_table_w84"><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['v']->value['time_start'],"%Y-%m-%d");?>
</div></td>
           <td><div class="admin_table_w84"><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['v']->value['time_end'],"%Y-%m-%d");?>
</div></td>
             <td><div  class="admin_table_w84"><?php if ($_smarty_tpl->tpl_vars['v']->value['beizhu']) {
echo $_smarty_tpl->tpl_vars['v']->value['beizhu'];
} else { ?>未备注<?php }?></div></td>
            <td>
      
          <div class="admin_new_bth_c"> <a href="javascript:void(0);" onClick="showdiv8('<?php echo $_smarty_tpl->tpl_vars['v']->value['uid'];?>
');" class="admin_new_c_bth admin_new_c_bthsh">修改</a></div>
          <div class="admin_new_bth_c"><a href="javascript:void(0);" onClick="layer_del('确定要取消该名企？','index.php?m=admin_hotjob&c=del&id=<?php echo $_smarty_tpl->tpl_vars['v']->value['uid'];?>
');" class="admin_new_c_bth admin_new_c_bthsc">删除</a></div>
          
            </td>
          </tr>
          <?php } ?>
          <tr style="background:#f1f1f1;">
          <td align="center"><label for="chkall2"><input type="checkbox" id='chkAll2' onclick='CheckAll2(this.form)' /></label></td>
         <td colspan="4" >
         <input class="admin_button" type="button" name="delsub" value="删除所选" onClick="return really('del[]')" />
         </td>
            <td colspan="5" class="digg"><?php echo $_smarty_tpl->tpl_vars['pagenav']->value;?>
</td>
          </tr>
          </tbody>

        </table>
      </form>
    </div>
  </div>
</div> 
</body>
</html>
<?php }} ?>
