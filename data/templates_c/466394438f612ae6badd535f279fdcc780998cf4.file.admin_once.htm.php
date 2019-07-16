<?php /* Smarty version Smarty-3.1.21-dev, created on 2019-03-13 12:12:48
         compiled from "/www/wwwroot/hr/app/template/admin/admin_once.htm" */ ?>
<?php /*%%SmartyHeaderCode:10111138785c888340800618-05288514%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '466394438f612ae6badd535f279fdcc780998cf4' => 
    array (
      0 => '/www/wwwroot/hr/app/template/admin/admin_once.htm',
      1 => 1518143946,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '10111138785c888340800618-05288514',
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
  'unifunc' => 'content_5c8883408e6652_20804276',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5c8883408e6652_20804276')) {function content_5c8883408e6652_20804276($_smarty_tpl) {?><?php if (!is_callable('smarty_function_url')) include '/www/wwwroot/hr/app/include/libs/plugins/function.url.php';
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

<link href="images/reset.css?v=<?php echo $_smarty_tpl->tpl_vars['config']->value['cachecode'];?>
" rel="stylesheet" type="text/css" />
<link href="images/system.css?v=<?php echo $_smarty_tpl->tpl_vars['config']->value['cachecode'];?>
" rel="stylesheet" type="text/css" />
<link href="images/table_form.css?v=<?php echo $_smarty_tpl->tpl_vars['config']->value['cachecode'];?>
" rel="stylesheet" type="text/css" />
<title>后台管理</title>
</head>
<body class="body_ifm">
<div id="infobox2" style="display:none;">
 <div class="admin_com_t_box"> 
    <form action="index.php?m=admin_once&c=ctime" target="supportiframe" method="post" id="formstatus">
      <input type="hidden" name="pytoken" value="<?php echo $_smarty_tpl->tpl_vars['pytoken']->value;?>
">
      <div class="admin_com_smbox_list"><span class="admin_com_smbox_span">延长时间：</span>
       <input  class="admin_com_smbox_text" value="" name="endtime" onKeyUp="this.value=this.value.replace(/[^0-9]/g,'')">
        <span class="admin_com_smbox_list_s">天</span>
              </div>
         <div class="admin_com_smbox_opt"><input type="submit"  value='确认' class="admin_examine_bth"><input type="button"  onClick="layer.closeAll();" class="admin_examine_bth_qx" value='取消'></div>
	
      <input name="onceids" value="0" type="hidden">
    </form>
  </div>
</div>
<div class="infoboxp">
<div class="admin_new_tip">
<a href="javascript:;" class="admin_new_tip_close"></a>
<a href="javascript:;" class="admin_new_tip_open" style="display:none;"></a>
<div class="admin_new_tit"><i class="admin_new_tit_icon"></i>操作提示</div>
<div class="admin_new_tip_list_cont">
<div class="admin_new_tip_list">该页面展示了网站所有的店铺招聘信息，可对店铺招聘进行审核删除操作。</div>
<div class="admin_new_tip_list">可输入名称关键字进行搜索，也可进行详细的高级搜索。</div>
</div>
</div>

<div class="clear"></div>

<div class="admin_new_search_box"> 
 <form method="get" action="index.php" name="myform" >
    <input type="hidden" name="m" value="admin_once"/>
    <input type="hidden" name="status" value="<?php echo $_GET['status'];?>
"/>
	<div class="admin_new_search_name">搜索类型：</div>
    <div class="admin_Filter_text formselect" did="dtype">
      <input type="button" <?php if ($_GET['type']==''||$_GET['type']=='2') {?> value="职位名称" <?php } elseif ($_GET['type']=='3') {?> value="联系电话" <?php } elseif ($_GET['type']=='4') {?> value="联系人" <?php } elseif ($_GET['type']=='5') {?> value="店铺名"<?php }?> class="admin_Filter_but" id="btype">
      <input type="hidden" name="type" id="type" value="<?php if ($_GET['type']) {
echo $_GET['type'];
} else { ?>2<?php }?>"/>
      <div class="admin_Filter_text_box" style="display:none" id="dtype">
        <ul>
          <li><a href="javascript:void(0)" onClick="formselect('2','type','职位名称')">职位名称</a></li>
          <li><a href="javascript:void(0)" onClick="formselect('3','type','联系电话')">联系电话</a></li>
          <li><a href="javascript:void(0)" onClick="formselect('4','type','联系人')">联系人</a></li>
          <li><a href="javascript:void(0)" onClick="formselect('5','type','店铺名')">店铺名</a></li>
        </ul>
      </div>
    </div>
    <input type="text" placeholder="输入你要搜索的关键字" name="keyword" class="admin_Filter_search" value="<?php echo $_GET['keyword'];?>
">
    <input type="submit" value="搜索" class="admin_Filter_bth">
	<a  href="javascript:void(0)" onclick="$('.admin_screenlist_box').toggle();"   class="admin_new_search_gj">高级搜索</a></form>


  
  <?php echo $_smarty_tpl->getSubTemplate ("admin/admin_search.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

  </div>
<div class="clear"></div> 

  <div class="table-list">
    <div class="admin_table_border">
      <iframe id="supportiframe"  name="supportiframe" onload="returnmessage('supportiframe');" style="display:none"></iframe>
      <form action="index.php" target="supportiframe" name="myform" id='myform' method="get">
        <input name="m" value="admin_once" type="hidden"/>
        <input name="c" value="del" type="hidden"/>
        <table width="100%">
          <thead>
            <tr class="admin_table_top">
              <th ><label for="chkall">
                <input type="checkbox" id='chkAll'  onclick='CheckAll(this.form)'/>
                </label></th>
              <th> <?php if ($_GET['t']=="id"&&$_GET['order']=="asc") {?> <a href="index.php?m=admin_once&order=desc&t=id">编号<img src="images/sanj.jpg"/></a> <?php } else { ?> <a href="index.php?m=admin_once&order=asc&t=id">编号<img src="images/sanj2.jpg"/></a> <?php }?> </th>
            
              <th align="left">职位名称/店铺名</th>
          	  <th align="left">店铺形象</th>
              <th align="left">联系电话</th>
              <th align="left">联系人</th>
              <th> <?php if ($_GET['t']=="ctime"&&$_GET['order']=="asc") {?> <a href="index.php?m=admin_once&order=desc&t=ctime">发布时间<img src="images/sanj.jpg"/></a> <?php } else { ?> <a href="index.php?m=admin_once&order=asc&t=ctime">发布时间 <img src="images/sanj2.jpg"/></a> <?php }?> </th>
              <th> <?php if ($_GET['t']=="edate"&&$_GET['order']=="asc") {?> <a href="index.php?m=admin_once&order=desc&t=edate">结束时间<img src="images/sanj.jpg"/></a> <?php } else { ?> <a href="index.php?m=admin_once&order=asc&t=edate">结束时间 <img src="images/sanj2.jpg"/></a> <?php }?> </th>
              <th>状态</th>
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
          <tr align="center"<?php if (($_smarty_tpl->tpl_vars['key']->value+1)%2=='0') {?>class="admin_com_td_bg"<?php }?> id="list<?php echo $_smarty_tpl->tpl_vars['v']->value['id'];?>
">
            <td><input type="checkbox" class="check_all" value="<?php echo $_smarty_tpl->tpl_vars['v']->value['id'];?>
"  name='del[]' onclick='unselectall()' rel="del_chk" /></td>
            <td align="left" class="td1" style="text-align:center;"><span><?php echo $_smarty_tpl->tpl_vars['v']->value['id'];?>
</span></td>
          <td class="od" align="left">
		  <a class="admin_cz_sc" href="<?php echo smarty_function_url(array('m'=>'once','c'=>'show','id'=>$_smarty_tpl->tpl_vars['v']->value['id']),$_smarty_tpl);?>
" target="_blank"><?php echo $_smarty_tpl->tpl_vars['v']->value['title'];?>
</a>
		  <div class="mt8"><?php echo $_smarty_tpl->tpl_vars['v']->value['companyname'];?>
</div></td>
          <td align="left">
          <?php if ($_smarty_tpl->tpl_vars['v']->value['pic']) {?>
          <a href="javascript:void(0)" class="preview admin_n_img" url="<?php echo $_smarty_tpl->tpl_vars['config']->value['sy_weburl'];?>
/<?php echo $_smarty_tpl->tpl_vars['v']->value['pic'];?>
"></a>
          <?php } else { ?>
          <a href="javascript:void(0)"  >无</a>
          <?php }?>
          </td>
          <td align="left"><?php echo $_smarty_tpl->tpl_vars['v']->value['phone'];?>
</td>
            <td align="left"><?php echo $_smarty_tpl->tpl_vars['v']->value['linkman'];?>
</td>
            <td><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['v']->value['ctime'],"%Y-%m-%d");?>
</td>
            <td><?php if ($_smarty_tpl->tpl_vars['v']->value['edate']<=time()) {?><font color="orange"><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['v']->value['edate'],"%Y-%m-%d");?>
</font><?php } else {
echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['v']->value['edate'],"%Y-%m-%d");
}?></td>
            <td><?php if ($_smarty_tpl->tpl_vars['v']->value['edate']<=time()) {?><span class="admin_com_Lock">已过期</span><?php } elseif ($_smarty_tpl->tpl_vars['v']->value['status']==1) {?><span class="admin_com_Audited">已审核</span><?php } elseif ($_smarty_tpl->tpl_vars['v']->value['status']==0) {?><span class="admin_com_noAudited">未审核</span><?php }?></td>
            <td>
             <div class="admin_new_bth_c">
             <a href="javascript:void(0)" class="admin_new_c_bth admin_new_c_bthsh" onClick="show_status('<?php echo $_smarty_tpl->tpl_vars['v']->value['id'];?>
');">审核</a> 
             <a href="index.php?m=admin_once&c=show&id=<?php echo $_smarty_tpl->tpl_vars['v']->value['id'];?>
" class="admin_new_c_bth admin_new_c_bth_yl">查看</a>
             </div>
            <a href="index.php?m=admin_once&c=edit&id=<?php echo $_smarty_tpl->tpl_vars['v']->value['id'];?>
"class="admin_new_c_bth ">修改</a> 
            <a href="javascript:void(0)" onClick="layer_del('确定要删除？', 'index.php?m=admin_once&c=del&id=<?php echo $_smarty_tpl->tpl_vars['v']->value['id'];?>
');"class="admin_new_c_bth admin_new_c_bth_sc">删除</a></td>
          </tr>
          <?php } ?>
          <tr style="background:#f1f1f1;">
            <td align="center"><input type="checkbox" id='chkAll2' onclick='CheckAll2(this.form)' /></td>
            <td colspan="4" ><label for="chkAll2">全选</label>
              &nbsp;
              <input  class="admin_button" type="button" name="delsub" value="删除所选" onClick="return really('del[]')" />
              &nbsp;&nbsp;
              <input  class="admin_button" type="button" name="delsub" value="批量审核" onClick="audall('1');" />
              &nbsp;&nbsp;
              <input  class="admin_button" type="button" name="delsub" value="批量取消审核" onClick="audall('0');" />
              &nbsp;&nbsp;
              <input class="admin_button" type="button" onClick="audall2();" value="批量延期" >
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
<div id="preview" style="display:none;width:460px"> 
  <div style="overflow:auto;width:460px;" >
    <form method="post"  class="layui-form">
	<input id="statusid" type="hidden">
    <table cellspacing='1' cellpadding='1' class="admin_examine_table table_form" style="width:100%; font-size:12px;">
      <tr>
       <th width="110">职位名称：</th>
        <td id="title"></td>
      </tr>
       <tr class="admin_table_trbg">
        <th>(店面)名称：</th>
        <td id="companyname"></td>
      </tr>
      <tr>
        <th>联系电话：</th>
        <td id="phone"></td>
      </tr>
      <tr>
        <th>联系人：</th>
        <td id="linkman"></td>
      </tr>
      <tr>
        <th>工  资：</th>
        <td id="salary"></td>
      </tr>
    <tr>
        <th>工作地点：</th>
        <td id="address"></td>
      </tr>
      <tr>
        <th>具体要求：</th>
       <td><div style="max-height:70px;_height:70px; overflow:hidden; overflow-y:auto;word-break:break-all; "><div class="" id="require"></div></div></td>
      </tr>
      <tr>
        <th>有效期：</th>
        <td id="edate"></td>
      </tr>
      <tr>
        <th>发布时间：</th>
        <td id="time"></td>
      </tr>
  
    <tr>
    <th width="80">审核操作：</th>
      <td align="left">
        <!--<div class="admin_examine_right">
      	<label><span class="admin_examine_table_s"><input name='status' type='radio' value='1' id='status_1'>已审核</span></label>
          	<label><span class="admin_examine_table_s"><input name='status' type='radio' value='0' id='status_0'>未审核</span></label>
    
       </div>-->
	   <div class="layui-form-item">
                  <div class="layui-input-block">
                    <div class="layui-input-inline">
                      	<input name="status" id="status_1" value="1" title="已审核" type="radio"/>
							<input name="status" id="status_0" value="0" title="未审核" type="radio"/>
                    </div>
                  </div>
             </div>	
	   </td>
     </tr>
      <tr>
           <td colspan='2' align="center">
   <div class="admin_Operating_sub">    <input class="admin_examine_bth" type="button" onClick="check_status();" value="提交"></div></td>
   </tr>
     </table>
   </form>
  </div>
</div>
<?php echo '<script'; ?>
 type="text/javascript">
  layui.use(['layer', 'form'], function(){
    var layer = layui.layer
    ,form = layui.form
    ,$ = layui.$;
  });

$(document).ready(function() {
	$(".preview").hover(
    function(){  
		  var pic_url=$(this).attr('url');
		  layer.tips("<img src="+pic_url+" style='width:180px;height:180px;' >", this);
	  },
    function(){
      layer.closeAll('tips');
    }
  );  
});
function audall(status){
	var codewebarr="";
	$(".check_all:checked").each(function(){ //由于复选框一般选中的是多个,所以可以循环输出 
		if(codewebarr==""){codewebarr=$(this).val();}else{codewebarr=codewebarr+","+$(this).val();}
	});
	if(codewebarr==""){
		parent.layer.msg("您还未选择任何信息！", 2, 8);	return false;			
	}else{	
		var pytoken=$("#pytoken").val();
		$.post("index.php?m=admin_once&c=status",{allid:codewebarr,status:status,pytoken:pytoken},function(data){				
			if(data=="1"){ 
				parent.layer.msg("批量审核成功！", 2, 9,function(){window.location.reload();}); 
			}else{			
				parent.layer.msg("批量取消审核成功！", 2, 9,function(){window.location.reload();}); 
			} 
		}); 
	}
} 
function audall2(){
	var codewebarr="";
	$(".check_all:checked").each(function(){ //由于复选框一般选中的是多个,所以可以循环输出 
		if(codewebarr==""){codewebarr=$(this).val();}else{codewebarr=codewebarr+","+$(this).val();}
	});
	if(codewebarr==""){
		parent.layer.msg("您还未选择任何信息！",2,8);	return false;
	}else{
		$("input[name=onceids]").val(codewebarr);
 		$.layer({
			type : 1,
			title :'批量延期', 
			closeBtn : [0 , true],
			border : [10 , 0.3 , '#000', true],
			area : ['290px','180px'],
			offset: ['60px', ''],
			page : {dom :"#infobox2"}
		}); 		
	}
}
function show_status(id){ 
	$.get("index.php?m=admin_once&c=ajax&id="+id,function(data){
		var data=eval('('+data+')');
		$("#title").html(data.title);
		$("#companyname").html(data.companyname);
		$("#require").html(data.require);
		$("#address").html(data.address);
		$("#phone").html(data.phone);
		$("#salary").html(data.salary);
		$("#linkman").html(data.linkman);
		$("#city").html(data.city);
		$("#time").html(data.time);
		$("#edate").html(data.edate);
		
		$("#status_"+data.status).attr("checked","checked");
		 layui.use(['form'],function(){
			var form = layui.form;
			form.render();
		});
		$("#statusid").val(id);
		$.layer({
			type : 1,
			title : '审核操作',
			closeBtn : [0 , true], 
			offset : ['20%' , '30%'],
			border : [10 , 0.3 , '#000', true],
			area : ['460px','auto'],
			page : {dom : '#preview'}
		}); 
	})
}  
function check_status(){
	var id=$("#statusid").val();
	var pytoken=$("#pytoken").val();
	var status=$("input[name='status']:checked").val();
	$.post("index.php?m=admin_once&c=status",{allid:id,status:status,pytoken:pytoken},function(data){
		if(data=="1"){ 
			parent.layer.msg('审核成功！', 2, 9,function(){window.location.reload();});
		}else{
			parent.layer.msg('取消审核成功！', 2, 9,function(){window.location.reload();}); 
		}
	});
}
<?php echo '</script'; ?>
>
</body>
</html>
<?php }} ?>
