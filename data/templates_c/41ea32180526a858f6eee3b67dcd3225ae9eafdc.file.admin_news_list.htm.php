<?php /* Smarty version Smarty-3.1.21-dev, created on 2019-03-13 12:15:08
         compiled from "/www/wwwroot/hr/app/template/admin/admin_news_list.htm" */ ?>
<?php /*%%SmartyHeaderCode:4715967525c8883cccfe817-27474500%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '41ea32180526a858f6eee3b67dcd3225ae9eafdc' => 
    array (
      0 => '/www/wwwroot/hr/app/template/admin/admin_news_list.htm',
      1 => 1517903408,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '4715967525c8883cccfe817-27474500',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'config' => 0,
    'property' => 0,
    'property_row' => 0,
    'pytoken' => 0,
    'adminnews' => 0,
    'key' => 0,
    'v' => 0,
    'Dname' => 0,
    'pagenav' => 0,
    'propertys' => 0,
    'pv' => 0,
    'one_class' => 0,
    'two_class' => 0,
    'val' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_5c8883cce26e42_92667734',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5c8883cce26e42_92667734')) {function content_5c8883cce26e42_92667734($_smarty_tpl) {?><?php if (!is_callable('smarty_function_searchurl')) include '/www/wwwroot/hr/app/include/libs/plugins/function.searchurl.php';
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
<link href="<?php echo $_smarty_tpl->tpl_vars['config']->value['sy_weburl'];?>
/js/layui/css/layui.css?v=<?php echo $_smarty_tpl->tpl_vars['config']->value['cachecode'];?>
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
 src="js/show_pub.js?v=<?php echo $_smarty_tpl->tpl_vars['config']->value['cachecode'];?>
"><?php echo '</script'; ?>
>
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
 type="text/javascript">
 
$(document).ready(function(){	
	$('#classbutton').click(function(){		
		var pytoken = $('#pytoken').val();
		var keyword = $('#classkeyword').val();
		$.post("index.php?m=admin_news&c=selclass",{pytoken:pytoken,keyword:keyword},function(data){
			$('#nid_select').html(data);		
		});
	});	
});
function changeClass(){
	var codewebarr="";
	$(".check_all:checked").each(function(){ //由于复选框一般选中的是多个,所以可以循环输出
		if(codewebarr==""){codewebarr=$(this).val();}else{codewebarr=codewebarr+","+$(this).val();}
	});
	if(codewebarr==""){
		parent.layer.msg('您还未选择任何信息！', 2, 8);	return false;
	}else{
		$('#classid').val(codewebarr);
		$.layer({
			type : 1,
			title :'批量转移新闻类别', 
			closeBtn : [0 , true],
			border : [10 , 0.3 , '#000', true],
			area : ['350px','250px'],
			page : {dom :"#infoboxclass"}
		});
	}
}

function update(id,name,value){
	$("#upid").val(id);
	$("#nameid").val(name);
	$("#valueid").val(value);
	$("#submit").val('修改');
}
function check_form(myform){
	if (myform.name.value==""){ 
		parent.layer.msg('请填写名称！', 2, 8); 
		myform.name.focus();
		return (false);
	}	
	if (myform.value.value==""){
		parent.layer.msg('请填写标识符！', 2, 8); 
		myform.name.focus();
		return (false);
	}	
}
function add_pro(){
	var codewebarr="";
	$("input[name='del[]']:checked").each(function(){  
		if($.trim($(this).val())){
			if(codewebarr==""){codewebarr=$(this).val();}else{codewebarr=codewebarr+","+$(this).val();}
		} 
	});  
	if(codewebarr==""){ 
		parent.layer.msg('您必须选择一个或多个！', 2, 8);
	}else{
		$("#protype").val('add');
		$("#proid").val(codewebarr); 
		$.layer({
			type : 1,
			title : '批量设置属性',
			closeBtn : [0 , true], 
			offset : ['20%' , '30%'],
			border : [10 , 0.3 , '#000', true],
			area : ['380px','220px'],
			page : {dom : '#property'}
		});  
	}
}
function del_pro(){
	var codewebarr="";
	$("input[name='del[]']:checked").each(function(){  
		if($.trim($(this).val())){
			if(codewebarr==""){codewebarr=$(this).val();}else{codewebarr=codewebarr+","+$(this).val();}
		} 
	}); 
	if(codewebarr==""){
		parent.layer.msg('您必须选择一个或多个！', 2, 8);
	}else{
		$("#protype").val('del'); 
		$("#proid").val(codewebarr); 
		$.layer({
			type : 1,
			title : '批量取消属性',
			closeBtn : [0 , true], 
			offset : ['20%' , '30%'],
			border : [10 , 0.3 , '#000', true],
			area : ['380px','220px'],
			page : {dom : '#property'}
		});  
	}
}
<?php echo '</script'; ?>
>
<title>后台管理</title>
</head>
<body class="body_ifm">
<div id="property" style="display:none;">
  <form action="index.php?m=admin_news&c=savepro" method="post" target="supportiframe">
  
  <div class="admin_news_tck_box" style="padding-top:0px;">
     <table cellspacing='1' cellpadding='1' class="admin_examine_table">
      <tr>
        <th align="right" width="80">属性：</th>
        <td>
           <div class="admin_examine_right" style="width:300px;">
           <div style="max-height:80px;_height:80px; overflow:hidden; overflow-y:auto">
       <?php  $_smarty_tpl->tpl_vars['property_row'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['property_row']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['property']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['property_row']->key => $_smarty_tpl->tpl_vars['property_row']->value) {
$_smarty_tpl->tpl_vars['property_row']->_loop = true;
?>
        <label for="status0"><span class="admin_examine_news_s">  <input type="checkbox" name="describe[]" value="<?php echo $_smarty_tpl->tpl_vars['property_row']->value['value'];?>
"/>
          <?php echo $_smarty_tpl->tpl_vars['property_row']->value['name'];?>
</span></label>
          <?php } ?>
          </div>
          </div>
          </td>
      </tr>
      <tr>
        <th align="right">文章编号：</th>
        <td><input  type="text" id="proid" name="proid" value="" class="input-text" style="width:220px;"></td>
      </tr>
      <tr>
        <td colspan='2' style="text-align:center"><input type="submit" value="确 定" name="submit" class="admin_examine_bth "></td>
      </tr>
    </table>
    </div>
    <input type="hidden" id="protype" name="type" value=""/>
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
<div class="admin_new_tip_list">该页面展示了网站所有的新闻信息，可对新闻进行审核删除操作。</div>
<div class="admin_new_tip_list">可输入名称关键字进行搜索，也可进行详细的高级搜索。</div>
</div>
</div>

<div class="clear"></div>

<div class="admin_new_search_box"> 
 <form action="index.php" name="myform" method="get" >
      <input name="m" value="admin_news" type="hidden"/>
      <input name="cate" value="<?php echo $_GET['cate'];?>
" type="hidden"/>
	<div class="admin_new_search_name">搜索类型：</div>
     <div class="admin_Filter_text formselect"  did='dtype'>
        <input type="button" value="<?php if ($_GET['type']=='1'||$_GET['type']=='') {?>标题<?php } else { ?>作者<?php }?>" class="admin_Filter_but"  id="btype">
        <input type="hidden" id='type' value="<?php if ($_GET['type']) {
echo $_GET['type'];
} else { ?>1<?php }?>" name='type'>
        <div class="admin_Filter_text_box" style="display:none" id='dtype'>
          <ul>
            <li><a href="javascript:void(0)" onClick="formselect('1','type','标题')">标题</a></li>
            <li><a href="javascript:void(0)" onClick="formselect('2','type','作者')">作者</a></li>
          </ul>
        </div>
      </div>
      <input class="admin_Filter_search" placeholder="输入你要搜索的关键字" type="text" name="keyword"  size="25" style=" float:left">
      <input class="admin_Filter_bth"  type="submit" name="news_search" value="检索"/>
	<a  href="javascript:void(0)" onclick="$('.admin_screenlist_box').toggle();"   class="admin_new_search_gj">高级搜索</a>
    </form>


  
  <?php echo $_smarty_tpl->getSubTemplate ("admin/admin_search.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

  </div>
<div class="clear"></div> 


  <div class="table-list">
    <div class="admin_table_border">
      <iframe id="supportiframe"  name="supportiframe" onload="returnmessage('supportiframe');" style="display:none"></iframe>
      <form action="index.php" target="supportiframe" name="myform" method="get" id='myform'>
        <input name="m" value="admin_news" type="hidden"/>
        <input name="c" value="delnews" type="hidden"/>
        <table width="100%">
          <thead>
            <tr class="admin_table_top">
              <th style="width:20px;"><label for="chkall">
                  <input type="checkbox" id='chkAll' value="" onclick='CheckAll(this.form)'/>
                </label></th>
              <th width="70"> <?php if ($_GET['t']=="id"&&$_GET['order']=="asc") {?> <a href="<?php echo smarty_function_searchurl(array('order'=>'desc','t'=>'id','m'=>'admin_news','untype'=>'order,t'),$_smarty_tpl);?>
">编号<img src="images/sanj.jpg"/></a> <?php } else { ?> <a href="<?php echo smarty_function_searchurl(array('order'=>'asc','t'=>'id','m'=>'admin_news','untype'=>'order,t'),$_smarty_tpl);?>
">编号<img src="images/sanj2.jpg"/></a> <?php }?> </th>
              <th width="130" align="left">新闻类别</th>
              <th width="350" align="left">标题</th>
              <th align="left">作者</th>
              <th > <?php if ($_GET['t']=="datetime"&&$_GET['order']=="asc") {?> <a href="<?php echo smarty_function_searchurl(array('order'=>'desc','t'=>'datetime','m'=>'admin_news','untype'=>'order,t'),$_smarty_tpl);?>
">发布时间 <img src="images/sanj.jpg"/></a> <?php } else { ?> <a href="<?php echo smarty_function_searchurl(array('order'=>'asc','t'=>'datetime','m'=>'admin_news','untype'=>'order,t'),$_smarty_tpl);?>
">发布时间 <img src="images/sanj2.jpg"/></a> <?php }?> </th>
              <th > <?php if ($_GET['t']=="hits"&&$_GET['order']=="asc") {?> <a href="<?php echo smarty_function_searchurl(array('order'=>'desc','t'=>'hits','m'=>'admin_news','untype'=>'order,t'),$_smarty_tpl);?>
">浏览量 <img src="images/sanj.jpg"/></a> <?php } else { ?> <a href="<?php echo smarty_function_searchurl(array('order'=>'asc','t'=>'hits','m'=>'admin_news','untype'=>'order,t'),$_smarty_tpl);?>
">浏览量 <img src="images/sanj2.jpg"/></a> <?php }?> </th>
              <th width="60"> <?php if ($_GET['t']=="sort"&&$_GET['order']=="asc") {?> <a href="<?php echo smarty_function_searchurl(array('order'=>'desc','t'=>'sort','m'=>'admin_news','untype'=>'order,t'),$_smarty_tpl);?>
">排序 <img src="images/sanj.jpg"/></a> <?php } else { ?> <a href="<?php echo smarty_function_searchurl(array('order'=>'asc','t'=>'sort','m'=>'admin_news','untype'=>'order,t'),$_smarty_tpl);?>
">排序 <img src="images/sanj2.jpg"/></a> <?php }?> </th>
			  <th width="60">分站</th>
              <th  width="80" class="admin_table_th_bg">操作</th>
            </tr>
          </thead>
          <tbody>
          
          <?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['adminnews']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value) {
$_smarty_tpl->tpl_vars['v']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['v']->key;
?>
          <tr align="center"<?php if (($_smarty_tpl->tpl_vars['key']->value+1)%2=='0') {?>class="admin_com_td_bg"<?php }?> id="list<?php echo $_smarty_tpl->tpl_vars['v']->value['id'];?>
">
            <td><input type="checkbox" value="<?php echo $_smarty_tpl->tpl_vars['v']->value['id'];?>
"  class="check_all" name='del[]' onclick='unselectall()' rel="del_chk" /></td>
            <td align="left" class="td1" style="text-align:center;" width="70"><span><?php echo $_smarty_tpl->tpl_vars['v']->value['id'];?>
</span></td>
            <td class="ud" align="left"><a href="<?php echo $_smarty_tpl->tpl_vars['v']->value['classurl'];?>
" target="_blank" class="admin_cz_sc" ><?php echo $_smarty_tpl->tpl_vars['v']->value['name'];?>
</a></td>
            <td class="od" align="left"><a href="<?php echo $_smarty_tpl->tpl_vars['v']->value['url'];?>
" target="_blank" class="admin_cz_sc" <?php if ($_smarty_tpl->tpl_vars['v']->value['color']) {?>style="color:<?php echo $_smarty_tpl->tpl_vars['v']->value['color'];?>
"<?php }?>><?php echo $_smarty_tpl->tpl_vars['v']->value['title'];?>
</a> <?php echo $_smarty_tpl->tpl_vars['v']->value['titype'];?>
</td>
            <td class="gd" align="left"><?php echo $_smarty_tpl->tpl_vars['v']->value['author'];?>
</td>
            <td class="td"><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['v']->value['datetime'],"%Y-%m-%d");?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['v']->value['hits'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['v']->value['sort'];?>
</td>
			 <td>
			<div><?php echo $_smarty_tpl->tpl_vars['Dname']->value[$_smarty_tpl->tpl_vars['v']->value['did']];?>
</div>
			<div><a href="javascript:;" onclick="checksite('<?php echo $_smarty_tpl->tpl_vars['v']->value['title'];?>
','<?php echo $_smarty_tpl->tpl_vars['v']->value['id'];?>
','index.php?m=admin_news&c=checksitedid');" class="admin_company_xg_icon">重新分配</a></div>
			</td>
            <td><a href="?m=admin_news&c=news&id=<?php echo $_smarty_tpl->tpl_vars['v']->value['id'];?>
" class="admin_new_c_bth admin_n_sc">修改</a> 
             <a href="javascript:void(0)" onClick="layer_del('确定要删除？','index.php?m=admin_news&c=delnews&id=<?php echo $_smarty_tpl->tpl_vars['v']->value['id'];?>
');"class="admin_new_c_bth admin_new_c_bth_sc mt5">删除</a></td>
          </tr>
          <?php } ?>
            <td align="center"><input type="checkbox" id='chkAll2' value='' onclick='CheckAll2(this.form)' /></td>
            <td colspan="3"><label for="chkAll2">全选</label>
              &nbsp;
              <input class="admin_button"  type="button" name="delsub" value="删除所选" onClick="return really('del[]')" />
              <input class="admin_button"  type="button" value="设置属性" onClick="add_pro()" />
              <input class="admin_button"  type="button"  value="取消属性" onClick="del_pro()" />
			  <input class="admin_button" type="button" name="delsub" value="批量选择分站" onClick="checksiteall('index.php?m=admin_news&c=checksitedid');" />
              <input class="admin_button"  type="button"  value="批量转移分类" onClick="changeClass()" />
              </td>
            <td colspan="6" class="digg"><?php echo $_smarty_tpl->tpl_vars['pagenav']->value;?>
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
<div id="houtai_div" style=" width:470px; display:none;">
  <div class="subnav">
    <div class="content-menu ib-a blue line-x">
      <form name="myform" action="index.php?m=admin_news&c=property" target="supportiframe" method="post" onSubmit="return check_form(this);" style="">
        <div class="new_dd_but fl">
             <span class="news_dd_nm fl" style="color:#555">名称：</span>
             <input type="text" class="new_dd_mc fl" id="nameid" name="name" class="input-text">
             <span class="news_dd_nm fl" style="color:#555;padding-left:10px;">调用标识：</span>
             <input type="text" id="valueid" name="value" class="new_dd_mc fl" size="10">
        </div>
        <input type="hidden" id="upid" name="id" value="">
        <input type="hidden" name="pytoken" value="<?php echo $_smarty_tpl->tpl_vars['pytoken']->value;?>
">
        <input class="admin_button" style="float:right;" name="submit" id="submit" type="submit" value="添加">
      </form>
      <div class="clear"></div>
      <table width="100%" class="table_form table_dd" style="text-align:center;border:1px solid #e6e6e6; line-height:30px;">
        <tr>
          <th style="text-align:center;" width="30%">名称</th>
          <th style="text-align:center;" width="35%">调用标识</th>
          <th style="text-align:center;" width="20%">操作</th>
        </tr>
        <?php  $_smarty_tpl->tpl_vars['pv'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['pv']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['propertys']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['pv']->key => $_smarty_tpl->tpl_vars['pv']->value) {
$_smarty_tpl->tpl_vars['pv']->_loop = true;
?>
        <tr id="pro<?php echo $_smarty_tpl->tpl_vars['pv']->value['id'];?>
">
          <td class="od"><?php echo $_smarty_tpl->tpl_vars['pv']->value['name'];?>
</td>
          <td class="od"><?php echo $_smarty_tpl->tpl_vars['pv']->value['value'];?>
</td>
          <td class="od"><a href="javascript:;" onClick="update('<?php echo $_smarty_tpl->tpl_vars['pv']->value['id'];?>
','<?php echo $_smarty_tpl->tpl_vars['pv']->value['name'];?>
','<?php echo $_smarty_tpl->tpl_vars['pv']->value['value'];?>
');">修改</a> | <a href="javascript:layer_del('确定要删除？','index.php?m=admin_news&c=delpro&id=<?php echo $_smarty_tpl->tpl_vars['pv']->value['id'];?>
');">删除</a></td>
        </tr>
        <?php } ?>
      </table>
    </div>
  </div>
</div>
<style>
.admin_compay_fp{width:340px; margin-top:10px;}
.admin_compay_fp_s{width:100px; text-align:right; font-weight:bold; display:inline-block}
.admin_compay_fp_sub{width:140px;height:25px;border:1px solid #ddd;}
.admin_compay_fp_sub1{width:40px;height:27px; background:#3692cf;color:#fff;border:none; cursor:pointer}
.table_dd tr th{border-right:none;border-bottom:1px solid #e6e6e6;}
.line-x{border:none;}
.new_dd_but{margin-bottom:15px;}
.news_dd_nm{height:30px;line-height:30px;}
.new_dd_mc{width:110px;height:30px;line-height:30px;border:1px solid #ccc;}

</style>
	<div id="infoboxclass"  style="display:none; width: 350px; ">
		<form action="index.php?m=admin_news&c=changeClass" target="supportiframe" method="post" id="classform"> 
				<div class="admin_compay_fl_l">
				<span class="admin_compay_fl_s">类别搜索：</span>
				<input type="text" value="" id="classkeyword" class="admin_compay_fl_text">
				<input type='button' id="classbutton" value="搜索" class="admin_compay_fl_bth">
			</div>
			
			<div class="admin_compay_fl_l">
			<span class="admin_compay_fl_s">新闻类别：</span>
            
               <div class="yun_admin_select_box zindex100"> 
				  <input type="button" value="请选择" class="yun_admin_select_box_text" id="nid_name" onClick="select_click('nid');">
				  <input name="nid" type="hidden" id="nid_val" value="">
              <!--这块后加-->                  
                <div class="yun_admin_select_box_list_box dn" id="nid_select"> 
				<?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['one_class']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value) {
$_smarty_tpl->tpl_vars['v']->_loop = true;
?>
                <div class="yun_admin_select_box_list"> <a href="javascript:;" onClick="select_new('nid','<?php echo $_smarty_tpl->tpl_vars['v']->value['id'];?>
','<?php echo $_smarty_tpl->tpl_vars['v']->value['name'];?>
')"><?php echo $_smarty_tpl->tpl_vars['v']->value['name'];?>
</a> </div>
                 <?php  $_smarty_tpl->tpl_vars['val'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['val']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['two_class']->value[$_smarty_tpl->tpl_vars['v']->value['id']]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['val']->key => $_smarty_tpl->tpl_vars['val']->value) {
$_smarty_tpl->tpl_vars['val']->_loop = true;
?>
                <div class="yun_admin_select_box_list"> <a href="javascript:;" onClick="select_new('nid','<?php echo $_smarty_tpl->tpl_vars['val']->value['id'];?>
','<?php echo $_smarty_tpl->tpl_vars['val']->value['name'];?>
')"> ┗<?php echo $_smarty_tpl->tpl_vars['val']->value['name'];?>
</a> </div>
                <?php } ?>
                <?php } ?> 
				</div>
             </div>
              
			</div>
			<div class="admin_compay_fp">
			<span style="width:350px;text-align:center;font-weight:bold; display:inline-block"><font color="red"> 说明：新闻类别转移可转移到任意类别</font></span>
			</div>
			<div class="admin_compay_fp">
				<span class="admin_compay_fp_s">&nbsp;</span>
				<input type="submit"  value='确认' class="admin_examine_bth"><input type="button"  onClick="layer.closeAll();" class="admin_examine_bth_qx" value='取消' style="margin-left:10px;">
			</div> 
			<input type="hidden" name="pytoken" value="<?php echo $_smarty_tpl->tpl_vars['pytoken']->value;?>
">
			<input name="id" value="0" id="classid" type="hidden">
		</form> 
	</div>
<?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['adminstyle']->value)."/checkdomain.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

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
