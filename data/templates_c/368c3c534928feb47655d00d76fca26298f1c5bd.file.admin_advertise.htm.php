<?php /* Smarty version Smarty-3.1.21-dev, created on 2019-03-13 12:15:10
         compiled from "/www/wwwroot/hr/app/template/admin/admin_advertise.htm" */ ?>
<?php /*%%SmartyHeaderCode:4151592615c8883ce537a01-85468611%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '368c3c534928feb47655d00d76fca26298f1c5bd' => 
    array (
      0 => '/www/wwwroot/hr/app/template/admin/admin_advertise.htm',
      1 => 1518060352,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '4151592615c8883ce537a01-85468611',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'config' => 0,
    'nclass' => 0,
    'class' => 0,
    'adv' => 0,
    'pytoken' => 0,
    'linkrows' => 0,
    'key' => 0,
    'v' => 0,
    'pagenav' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_5c8883ce5f2209_15759289',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5c8883ce5f2209_15759289')) {function content_5c8883ce5f2209_15759289($_smarty_tpl) {?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
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
> 
function check_type(id,value){
	if(value=="1"){
		var val = "0";
	}else{
		var val="1";
	}
	$.post("index.php?m=advertise&c=ajax_check",{id:id,val:val,pytoken:$('#pytoken').val()},function(data){
		html = "<a href=\"javascript:void(0);\" onClick=\"check_type("+id+","+val+");\" >"+data+"</a>";
		$("#"+id).html(html);
	});
} 
function audall2(status)
{
	var codewebarr="";
	$(".check_all:checked").each(function(){ //由于复选框一般选中的是多个,所以可以循环输出 
		if(codewebarr==""){codewebarr=$(this).val();}else{codewebarr=codewebarr+","+$(this).val();}
	});
	if(codewebarr==""){
		parent.layer.msg("您还未选择任何信息！",2,8);	return false;
	}else{
		$("input[name=jobid]").val(codewebarr);
 		$.layer({
			type : 1,
			title :'批量延期', 
			closeBtn : [0 , true],
			border : [10 , 0.3 , '#000', true],
			area : ['270px','180px'],
			page : {dom :"#infobox2"}
		}); 		
	}
}
$(document).ready(function() {
	$(".preview").hover(function(){  
		var pic_url=$(this).attr('url');
		layer.tips("<img src="+pic_url+" style='max-width:380px'>", this, {
			maxWidth: '380px'
		});
	},function(){layer.closeAll('tips');});  
});
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
})
<?php echo '</script'; ?>
>
<link href="images/reset.css?v=<?php echo $_smarty_tpl->tpl_vars['config']->value['cachecode'];?>
" rel="stylesheet" type="text/css" />
<link href="images/system.css?v=<?php echo $_smarty_tpl->tpl_vars['config']->value['cachecode'];?>
" rel="stylesheet" type="text/css" /> 
<link href="images/table_form.css?v=<?php echo $_smarty_tpl->tpl_vars['config']->value['cachecode'];?>
" rel="stylesheet" type="text/css" />
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
<div id="wname"  style="display:none; width:350px; "> 
	<div style="height: 160px;" class="job_box_div">  
	   <div class="job_box_inp">
		<table class="table_form "style="width:100%">
			<tr ><td  class='ui_content_wrap' style="width:300px;">复制(CTRL+C)以下内容并添加到模板中</td></tr> 
			<tr><td><input type="text" name="position" id='copy_url' class="input-text" size='45' style="width:310px;"/></td></tr> 
		</table> 
	   </div>
	</div>
</div> 
<div class="infoboxp"> 
<div class="admin_new_tip">
<a href="javascript:;" class="admin_new_tip_close"></a>
<a href="javascript:;" class="admin_new_tip_open" style="display:none;"></a>
<div class="admin_new_tit"><i class="admin_new_tit_icon"></i>操作提示</div>
<div class="admin_new_tip_list_cont">
<div class="admin_new_tip_list">该页面展示了网站所有的广告管理信息，可对广告管理进行编辑操作。</div>
<div class="admin_new_tip_list">可输入名称关键字进行搜索，也可进行详细的高级搜索。</div>
</div>
</div>

<div class="clear"></div>
<div class="admin_new_search_box"> 
	<form action="index.php" name="myform" method="get">
		<input name="m" value="advertise" type="hidden"/> 
		<div class="admin_new_search_name">搜索类型：</div>
		
		<div class="admin_Filter_text formselect" style="width:280px;"  did='dclass_id'>
			<input type="button" value="<?php if ($_GET['class_id']=='1'||$_GET['class_id']=='') {?>不限<?php } else {
echo $_smarty_tpl->tpl_vars['nclass']->value[$_GET['class_id']];
}?>" class="admin_new_select_banner" style="width:260px;"  id="bclass_id">
			
			<input type="hidden" id='class_id' value="<?php echo $_GET['class_id'];?>
" name='class_id'>
			
			<div class="admin_Filter_text_box" style="display:none;width:258px;height:230px;top:31px; overflow:auto; overflow-x:hidden" id='dclass_id'>
				<ul>
					<?php  $_smarty_tpl->tpl_vars['adv'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['adv']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['class']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['adv']->key => $_smarty_tpl->tpl_vars['adv']->value) {
$_smarty_tpl->tpl_vars['adv']->_loop = true;
?>
						<li><a href="javascript:void(0)" onClick="formselect('<?php echo $_smarty_tpl->tpl_vars['adv']->value['id'];?>
','class_id','<?php echo $_smarty_tpl->tpl_vars['adv']->value['class_name'];?>
')"><?php echo $_smarty_tpl->tpl_vars['adv']->value['class_name'];?>
</a></li>
					<?php } ?> 
				</ul>  
			</div>
		</div>
		
		<input class="admin_Filter_search" placeholder="输入你要搜索的关键字" type="text" name="name"  size="25" style="float:left">
		<input  class="admin_Filter_bth"  type="submit" name="comquestion" value="搜索"/>

		<a  href="javascript:void(0)" onclick="$('.admin_screenlist_box').toggle();"   class="admin_new_search_gj">高级搜索</a>
		<a href="index.php?m=advertise&c=ad_add" class="admin_new_cz_tj"> + 添加广告</a>        
		<a href="javascript:void(0)" onClick="layer_del('','index.php?m=advertise&c=cache_ad')" class="admin_new_cz_tj">更新广告缓存</a>
	</form>

    <?php echo $_smarty_tpl->getSubTemplate ("admin/admin_search.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>


</div>
<div class="clear"></div> 

<div class="table-list">
<div class="admin_table_border">
<iframe id="supportiframe"  name="supportiframe" onload="returnmessage('supportiframe');" style="display:none"></iframe> 
<form action="" name="myform" method="get" id='myform' target="supportiframe">
    <input type="hidden" value="advertise" name="m">
    <input type="hidden" value="del" name="c">
 <input type="hidden" id="pytoken" name="pytoken" value="<?php echo $_smarty_tpl->tpl_vars['pytoken']->value;?>
">
<table width="100%">
	<thead>
			<tr class="admin_table_top">
             <th><label for="chkall"><input type="checkbox" id='chkAll'  onclick='CheckAll(this.form)'/></label></th>
			<th align="center">编号</th>
			<th align="left" width="150">广告名称</th>
             <th align="left" width="80">使用范围</th>
             <th align="left" width="200">广告类别</th>
              <th align="center">点击量</th>
              
            <th align="center">类型</th>
            <th align="center">结束时间</th>
            <th align="center">排序</th>
			<th align="center" width="100">调用代码</th>
            <th align="center">状态</th>
			<th class="admin_table_th_bg" align="center">操作</th>
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
    <tr align="left"<?php if (($_smarty_tpl->tpl_vars['key']->value+1)%2=='0') {?>class="admin_com_td_bg"<?php }?> id="list<?php echo $_smarty_tpl->tpl_vars['v']->value['id'];?>
">
     <td align="center"><input type="checkbox" value="<?php echo $_smarty_tpl->tpl_vars['v']->value['id'];?>
" class="check_all"  name='del[]' onclick='unselectall()' rel="del_chk" /></td>
	 <td align="center"><?php echo $_smarty_tpl->tpl_vars['v']->value['id'];?>
</td>
    	<td align="left" class="td1"><span><?php echo $_smarty_tpl->tpl_vars['v']->value['ad_name'];?>
</span></td>
		<td align="left"><?php echo $_smarty_tpl->tpl_vars['v']->value['d_title'];?>
</td>
        <td align="left" class="ud"><?php echo $_smarty_tpl->tpl_vars['v']->value['class_name'];?>
</td>
        <td align="center" class="ud"><?php echo $_smarty_tpl->tpl_vars['v']->value['hits'];?>
</td>
        
        <td  align="center" class="ud"><?php echo $_smarty_tpl->tpl_vars['v']->value['ad_typename'];?>
</td>
        <td class="ud" align="center"><?php echo $_smarty_tpl->tpl_vars['v']->value['time_end'];?>
</td>
        <td class="ud" align="center"><?php echo $_smarty_tpl->tpl_vars['v']->value['sort'];?>
</td>
    	<td class="ud" align="center"> 
			<?php if ($_smarty_tpl->tpl_vars['v']->value['is_end']=='1') {?>
				已过期
			<?php } elseif ($_smarty_tpl->tpl_vars['v']->value['is_open']=='0') {?>
				已停用
			<?php } else { ?> 
        	
            <a href="index.php?m=advertise&c=ad_preview&ad_class=<?php echo $_smarty_tpl->tpl_vars['v']->value['class_id'];?>
&id=<?php echo $_smarty_tpl->tpl_vars['v']->value['id'];?>
"class="admin_cz_yl">调用</a>
		<?php }?>			
        </td>
        <td align="center" class="ud" id="<?php echo $_smarty_tpl->tpl_vars['v']->value['id'];?>
">
			<a href="javascript:void(0);" onClick="check_type(<?php echo $_smarty_tpl->tpl_vars['v']->value['id'];?>
,<?php echo $_smarty_tpl->tpl_vars['v']->value['is_check'];?>
);" >
				<?php if ($_smarty_tpl->tpl_vars['v']->value['is_end']=='1') {?>
					<font color="red">已过期</font>
				<?php } elseif ($_smarty_tpl->tpl_vars['v']->value['is_check']=="1") {?>
					<font color="green">已审核</font>
				<?php } else { ?>
					<font color="EE6723">未审核</font>
				<?php }?>
			</a>
		</td>
        <td align="center"> <a href="index.php?m=advertise&c=ad_preview&ad_class=<?php echo $_smarty_tpl->tpl_vars['v']->value['class_id'];?>
&id=<?php echo $_smarty_tpl->tpl_vars['v']->value['id'];?>
"class="admin_new_c_bth admin_new_c_bthsh">预览</a>
        <a href="index.php?m=advertise&c=ad_add&id=<?php echo $_smarty_tpl->tpl_vars['v']->value['id'];?>
" class="admin_new_c_bth">修改</a> 
        <a href="javascript:void(0)" onClick="layer_del('确定要删除？', 'index.php?m=advertise&c=del_ad&id=<?php echo $_smarty_tpl->tpl_vars['v']->value['id'];?>
');" class="admin_new_c_bth admin_new_c_bth_sc">删除</a>
        </td>
  </tr>
  <?php } ?>
  <tr>
    <td align="center"><input type="checkbox" id='chkAll2' onclick='CheckAll2(this.form)' /></td>
    <td colspan="3" >
    <label for="chkAll2">全选</label>&nbsp;
        <input type="button" onclick="return really('del[]')" value="删除所选" name="delsub" class="admin_button">
<input class="admin_button" type="button" name="delsub" value="批量延期" onClick="audall2('0');" /></td>
  <td colspan="8" class="digg"><?php echo $_smarty_tpl->tpl_vars['pagenav']->value;?>
</td></tr>
  </tbody>
  </table>
</form>
</div>
</div>
</div>
<div id="infobox2" style="display:none;">
	<div class="" >
	<div class="admin_com_t_box"> 
      <form action="index.php?m=advertise&c=ctime" target="supportiframe" method="post" id="formstatus"> 
			<div class="admin_com_smbox_list"><span class="admin_com_smbox_span">延长时间：</span>
            <input class="admin_com_smbox_text" value="" name="endtime" onKeyUp="this.value=this.value.replace(/[^0-9]/g,'')"> <span class="admin_com_smbox_list_s">天</span>
                </div>
             <div class="admin_com_smbox_opt"><input type="submit"  value='确认' class="admin_examine_bth"><input type="button"  onClick="layer.closeAll();" class="admin_examine_bth_qx" value='取消'></div>
		 <input type="hidden" name="pytoken" value="<?php echo $_smarty_tpl->tpl_vars['pytoken']->value;?>
">
        <input name="jobid" value="0" type="hidden"> 
      </form>
    </div>
  </div> 
</div>
</body>
</html><?php }} ?>
