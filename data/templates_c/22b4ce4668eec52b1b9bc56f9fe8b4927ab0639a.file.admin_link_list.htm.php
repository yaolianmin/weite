<?php /* Smarty version Smarty-3.1.21-dev, created on 2019-03-13 12:32:25
         compiled from "/www/wwwroot/hr/app/template/admin/admin_link_list.htm" */ ?>
<?php /*%%SmartyHeaderCode:21242239015c8887d9500134-80377118%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '22b4ce4668eec52b1b9bc56f9fe8b4927ab0639a' => 
    array (
      0 => '/www/wwwroot/hr/app/template/admin/admin_link_list.htm',
      1 => 1517903408,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '21242239015c8887d9500134-80377118',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'config' => 0,
    'pytoken' => 0,
    'linkrows' => 0,
    'key' => 0,
    'v' => 0,
    'Dname' => 0,
    'pagenav' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_5c8887d95c4a29_08201415',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5c8887d95c4a29_08201415')) {function content_5c8887d95c4a29_08201415($_smarty_tpl) {?><?php if (!is_callable('smarty_function_searchurl')) include '/www/wwwroot/hr/app/include/libs/plugins/function.searchurl.php';
if (!is_callable('smarty_modifier_date_format')) include '/www/wwwroot/hr/app/include/libs/plugins/modifier.date_format.php';
?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
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
	$(".job_name").hover(function(){
		var job_name=$(this).attr('v'); 
		if($.trim(job_name)!=''){
			layer.tips(job_name, this, {guide: 1, style: ['background-color:#5EA7DC; color:#fff;top:-7px', '#5EA7DC']}); 
			$(".xubox_layer").addClass("xubox_tips_border");
		} 
	},function(){
		var job_name=$(this).attr('v'); 
		if($.trim(job_name)!=''){
			layer.closeAll('tips');
		} 
	});
	//弹窗框部分
		$(".status").click(function(){
  		var uid=$(this).attr("pid");
		var pytoken=$("#pytoken").val();
		var status=$(this).attr("status");
		$("#status_"+status).attr("checked",true);
		$("input[name=uid]").val(uid);
		status_div('锁定用户','350','220');
	});
	$(".user_status").click(function(){
		var id=$(this).attr("pid");
		var status=$(this).attr("status");
		$("#status"+status).attr("checked",true);
		var pytoken=$("#pytoken").val();
		$("input[name=yesid]").val(id);
		$.layer({
			type : 1,
			title :'友情链接审核', 
			closeBtn : [0 , true],
			border : [10 , 0.3 , '#000', true],
			area : ['350px','220px'],
			page : {dom :"#infobox2"}
		});
	});
	
	
	 
})
<?php echo '</script'; ?>
>
<link href="images/reset.css?v=<?php echo $_smarty_tpl->tpl_vars['config']->value['cachecode'];?>
" rel="stylesheet" type="text/css" />
<link href="images/system.css?v=<?php echo $_smarty_tpl->tpl_vars['config']->value['cachecode'];?>
" rel="stylesheet" type="text/css" />
<link href="images/table_form.css?v=<?php echo $_smarty_tpl->tpl_vars['config']->value['cachecode'];?>
" rel="stylesheet" type="text/css" />

<title>后台管理</title>
</head>
<body class="body_ifm">
<div id="infobox2"  style="display:none; width: 350px; "> 
      <form action="index.php?m=link&c=status" target="supportiframe" method="post" id="formstatus">
	   <input name="yesid" type="hidden">
        <table cellspacing='1' cellpadding='1' class="admin_examine_table">
  <tr>
    <th width="80">审核操作：</th>
      <td align="left">
        <div class="admin_examine_right">
    <label for="status0"><span class="admin_examine_table_s"><input type="radio" name="status" value="0" id="status0" >未审核</span></label>
        <label for="status1"><span class="admin_examine_table_s"><input type="radio" name="status" value="1" id="status1" >已审核</span></label>
    </div>
         </td>
          </tr>
          <tr>
            <th>审核说明：</th>
   <td align="left"><textarea id="alertcontent" name="statusbody"class="admin_explain_textarea"></textarea></td>
   </tr>
     <tr>
       <td colspan='2' align="center">
       <div class="admin_Operating_sub"> 
       <input name="pid" value="0" type="hidden">
       <input type="submit"  value='确认' class="admin_examine_bth"> 
       <input type="button" onClick="layer.closeAll();" class="admin_examine_bth_qx" value='取消'>
       </div>
       </td>
   </tr>
    </table>
		<input type="hidden" name="pytoken" value="<?php echo $_smarty_tpl->tpl_vars['pytoken']->value;?>
">
      </form> 
</div>
<div class="infoboxp">
<div class="admin_new_tip">
<a href="javascript:;" class="admin_new_tip_close"></a>
<a href="javascript:;" class="admin_new_tip_open" style="display:none;"></a>
<div class="admin_new_tit"><i class="admin_new_tit_icon"></i>操作提示</div>
<div class="admin_new_tip_list_cont">
<div class="admin_new_tip_list">该页面展示了网站的友情链接信息，可对友情链接进行审核，删除操作。</div>
<div class="admin_new_tip_list">可输入名称关键字进行搜索，也可进行详细的高级搜索。</div>
</div>
</div>

<div class="clear"></div>

<div class="admin_new_search_box"> 
 <form action="index.php" name="myform" method="get">
    <input name="m" value="link" type="hidden"/>
    <input name="status" value="<?php echo $_GET['status'];?>
" type="hidden"/>
    	<div class="admin_new_search_name">搜索类型：</div>
 <input class="admin_Filter_search"  placeholder="输入你要搜索的关键字"  type="text" name="keyword"  size="25" style="float:left">
    <input class="admin_Filter_bth"  type="submit" name="news_search" value="检索" style="float:left"/>
	<a  href="javascript:void(0)" onclick="$('.admin_screenlist_box').toggle();"   class="admin_new_search_gj">高级搜索</a>
       <a href="index.php?m=link&c=add" class="admin_new_cz_tj">+ 添加链接</a>
    </form>


  
  <?php echo $_smarty_tpl->getSubTemplate ("admin/admin_search.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

  </div>
<div class="clear"></div> 

<div class="table-list">
  <div class="admin_table_border">
    <iframe id="supportiframe"  name="supportiframe" onload="returnmessage('supportiframe');" style="display:none"></iframe>
    <form action="index.php?m=link&c=del" name="myform" method="post" id='myform' target="supportiframe">
      <input type="hidden" name="pytoken" id='pytoken' value="<?php echo $_smarty_tpl->tpl_vars['pytoken']->value;?>
">
      <table width="100%">
        <thead>
          <tr class="admin_table_top">
            <th><label for="chkall">
              <input type="checkbox" id='chkAll' onclick='CheckAll(this.form)' />
              </label></th>
            <th> <?php if ($_GET['t']=="id"&&$_GET['order']=="asc") {?> <a href="<?php echo smarty_function_searchurl(array('order'=>'desc','t'=>'id','m'=>'link','untype'=>'order,t'),$_smarty_tpl);?>
">编号<img src="images/sanj.jpg"/></a> <?php } else { ?> <a href="<?php echo smarty_function_searchurl(array('order'=>'asc','t'=>'id','m'=>'link','untype'=>'order,t'),$_smarty_tpl);?>
">编号<img src="images/sanj2.jpg"/></a> <?php }?> </th>
            <th align="left">链接标题</th>
            <th align="left">链接地址</th>
            <th align="left">显示站点</th>
            <th> <?php if ($_GET['t']=="link_time"&&$_GET['order']=="asc") {?> <a href="<?php echo smarty_function_searchurl(array('order'=>'desc','t'=>'link_time','m'=>'link','untype'=>'order,t'),$_smarty_tpl);?>
">发布时间<img src="images/sanj.jpg"/></a> <?php } else { ?> <a href="<?php echo smarty_function_searchurl(array('order'=>'asc','t'=>'link_time','m'=>'link','untype'=>'order,t'),$_smarty_tpl);?>
">发布时间<img src="images/sanj2.jpg"/></a> <?php }?> </th>
            <th>类型</th>
            <th> <?php if ($_GET['t']=="link_sorting"&&$_GET['order']=="asc") {?> <a href="<?php echo smarty_function_searchurl(array('order'=>'desc','t'=>'link_sorting','m'=>'link','untype'=>'order,t'),$_smarty_tpl);?>
">排序<img src="images/sanj.jpg"/></a> <?php } else { ?> <a href="<?php echo smarty_function_searchurl(array('order'=>'asc','t'=>'link_sorting','m'=>'link','untype'=>'order,t'),$_smarty_tpl);?>
">排序<img src="images/sanj2.jpg"/></a> <?php }?> </th>
            <th>状态</th>
            <th class="admin_table_th_bg" width="120">操作</th>
          </tr>
        </thead>
        <tbody>
        
        <?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['linkrows']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value) {
$_smarty_tpl->tpl_vars['v']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['v']->key;
?>
        <tr align="center"<?php if (($_smarty_tpl->tpl_vars['key']->value+1)%2=='0') {?>class="admin_com_td_bg"<?php }?> id="list<?php echo $_smarty_tpl->tpl_vars['v']->value['id'];?>
">
          <td><input type="checkbox" value="<?php echo $_smarty_tpl->tpl_vars['v']->value['id'];?>
" name='del[]' onclick='unselectall()' class="check_all" rel="del_chk" /></td>
          <td><span><?php echo $_smarty_tpl->tpl_vars['v']->value['id'];?>
</span></td>
          <td class="ud" align="left"><?php echo $_smarty_tpl->tpl_vars['v']->value['link_name'];?>
</td>
          <td class="od" align="left"><a href="<?php echo $_smarty_tpl->tpl_vars['v']->value['link_url'];?>
" target="_blank"><?php echo $_smarty_tpl->tpl_vars['v']->value['link_url'];?>
</a></td>
          <td class="ud" align="left"><?php echo $_smarty_tpl->tpl_vars['Dname']->value[$_smarty_tpl->tpl_vars['v']->value['did']];?>
</td>
          <td><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['v']->value['link_time'],"%Y-%m-%d");?>
</td>
          <td> <?php if ($_smarty_tpl->tpl_vars['v']->value['link_type']==1) {?>文字链接<?php } else { ?>图片链接<?php }?> </td>
          <td><?php echo $_smarty_tpl->tpl_vars['v']->value['link_sorting'];?>
</td>
          <td> <?php if ($_smarty_tpl->tpl_vars['v']->value['link_state']!=1) {?><span class="admin_com_noAudited">未审核</span><?php } else { ?><span class="admin_com_Audited">已审核</span><?php }?></td>		  
          <td width="180"> <a href="javascript:void(0);" class="user_status admin_new_c_bth admin_new_c_bthsh"  pid="<?php echo $_smarty_tpl->tpl_vars['v']->value['id'];?>
" status="<?php echo $_smarty_tpl->tpl_vars['v']->value['link_state'];?>
">审核</a>
		  
           <a href="index.php?m=link&c=add&id=<?php echo $_smarty_tpl->tpl_vars['v']->value['id'];?>
"class="admin_new_c_bth">修改</a> 
           <a href="javascript:void(0)" onClick="layer_del('确定要删除？', 'index.php?m=link&c=del&id=<?php echo $_smarty_tpl->tpl_vars['v']->value['id'];?>
');" class="admin_new_c_bth admin_new_c_bth_sc">删除</a> </td>
        </tr>
        <?php } ?>
        <tr style="background: #f1f1f1;">
          <td align="center"><input type="checkbox" id='chkAll2' onclick='CheckAll2(this.form)' /></td>
          <td colspan="2" ><label for="chkAll2">全选</label>
            &nbsp;
			   <input class="admin_button" type="button" name="delsub" value="批量选择分站" onClick="checksiteall('index.php?m=link&c=checksitedid');" />
            <input class="admin_button"  type="button" name="delsub" value="删除所选"  onclick="return really('del[]')"/></td>
       
		  <td colspan="7" class="digg"><?php echo $_smarty_tpl->tpl_vars['pagenav']->value;?>
</td>
        </tr>
        </tbody>
        
      </table>
    </form>
  </div>
</div>
<?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['adminstyle']->value)."/checkdomain.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>


<?php echo '<script'; ?>
 type="text/javascript">
  layui.use(['form'], function(){
  });
<?php echo '</script'; ?>
>
</body>
</html>
<?php }} ?>
