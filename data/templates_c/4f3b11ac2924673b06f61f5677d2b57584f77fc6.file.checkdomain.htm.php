<?php /* Smarty version Smarty-3.1.21-dev, created on 2019-03-13 12:12:30
         compiled from "/www/wwwroot/hr//app/template/admin/checkdomain.htm" */ ?>
<?php /*%%SmartyHeaderCode:17043378435c88832ed0e380-48981031%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '4f3b11ac2924673b06f61f5677d2b57584f77fc6' => 
    array (
      0 => '/www/wwwroot/hr//app/template/admin/checkdomain.htm',
      1 => 1518147940,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '17043378435c88832ed0e380-48981031',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'config' => 0,
    'Dname' => 0,
    'key' => 0,
    'dlist' => 0,
    'pytoken' => 0,
    'guweninfo' => 0,
    'glist' => 0,
    'crmlist' => 0,
    'clist' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_5c88832ed40ad1_97263123',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5c88832ed40ad1_97263123')) {function content_5c88832ed40ad1_97263123($_smarty_tpl) {?><?php echo '<script'; ?>
 type="text/javascript">
var weburl="<?php echo $_smarty_tpl->tpl_vars['config']->value['sy_weburl'];?>
";
function checksiteall(url){
	var codewebarr="";
	$(".check_all:checked").each(function(){ 
		if(codewebarr==""){codewebarr=$(this).val();}else{codewebarr=codewebarr+","+$(this).val();}
	});
	if(codewebarr==""){
		parent.layer.msg('您还未选择任何信息！', 2, 8);	return false;
	}else{
		checksite('',codewebarr,url);
	}
}
function checksite(name,id,url){ 
	if(name==''){
		$("#com_name").html("");
	}else if(url=='index.php?m=admin_news&c=checksitedid'){
		$("#com_name").html("新闻标题：");
	}else if(url=='index.php?m=zhaopinhui&c=checksitedid'){
		$("#com_name").html("招聘会标题：");
	}else{
		$("#com_name").html("用户名：");
	}
	name=name.substr(0,15);
	$('#formDomain').attr('action', url);
	$('#siteusername').html(name);
    $('#siteuid').val(id);
	$.layer({
		type : 1,
		title :'分配站点', 
		closeBtn : [0 , true],
		border : [10 , 0.3 , '#000', true],
		area : ['350px','260px'],
		offset: ['20px', ''],
		page : {dom :"#infoboxdomain"}
	});
}

function checkguwenall(url){
	var codewebarr="";
	$(".check_all:checked").each(function(){ 
		if(codewebarr==""){codewebarr=$(this).val();}else{codewebarr=codewebarr+","+$(this).val();}
	});
	if(codewebarr==""){
		parent.layer.msg('您还未选择任何信息！', 2, 8);	return false;
	}else{
		checkguwen('',codewebarr,url);
	}
}

function checkguwen(name,id,url){ 
	if(name==''){
		$("#com_username").html("");
	}else if(url=='index.php?m=admin_company&c=checkguwen'){
		$("#com_username").html("企业用户名：");
	}
	name=name.substr(0,15);
	$('#formGuwen').attr('action', url);
	$('#comusername').html(name);
    $('#comid').val(id);
	$.layer({
		type : 1,
		title :'分配顾问', 
		closeBtn : [0 , true],
		border : [10 , 0.3 , '#000', true],
		area : ['350px','260px'],
		offset: ['20px', ''],
		page : {dom :"#infoguwen"}
	});
}

//绑定业务员
function checkcrm(name,id,url){ 
	if(url=='index.php?m=admin_company_consultant&c=checkcrmid'){
		$("#con_name").html("顾问姓名：");
	}
	name=name.substr(0,15);
	$('#formCrm').attr('action', url);
	$('#crmusername').html(name);
    $('#conid').val(id);
	$.layer({
		type : 1,
		title :'绑定业务员', 
		closeBtn : [0 , true],
		border : [10 , 0.3 , '#000', true],
		area : ['350px','260px'],
		offset: ['20px', ''],
		page : {dom :"#infocrm"}
	});
}
function searchdomain(id){
	var pytoken = $('#pytoken').val();
	if(id==1){
	    var keyword = $.trim($('#sitekeyword').val());
	}else{
	    var keyword = $.trim($('#domainkeyword').val());
	}
	if(!keyword){
		parent.layer.msg('请输入搜索关键词！', 2, 8);return false;
	}
	var i=layer.load('执行中，请稍后...',0);
	$.post(weburl+"/index.php?m=ajax&c=selsite",{pytoken:pytoken,keyword:keyword,id:id},function(data){
		layer.close(i);
		if(data==0){
			parent.layer.msg('请输入搜索关键词！', 2, 8);return false;
		}else if(data==1){
			parent.layer.msg('未查询到相关数据！', 2, 8);return false;
		}else{
		    if(id==1){
				$('#did_val').html(data);
				layui.use(['form'],function(){
				var f = layui.form;
				f.render();
				});

			}else{
				$('#domain_val').html(data);
				layui.use(['form'],function(){
				var f = layui.form;
				f.render();
				});
				
			}
		}
	});

}

function searchguwen(id){
	var pytoken = $('#pytoken').val();
	if(id==1){
	    var keyword = $.trim($('#guwenkeyword').val());
	}else{
	    var keyword = $.trim($('#guwenkeyword').val());
	}
	if(!keyword){
		parent.layer.msg('请输入搜索关键词！', 2, 8);return false;
	}
	var i=layer.load('执行中，请稍后...',0);
	$.post(weburl+"/index.php?m=ajax&c=guwen",{pytoken:pytoken,keyword:keyword,id:id},function(data){
		layer.close(i);
		if(data==0){
			parent.layer.msg('请输入搜索关键词！', 2, 8);return false;
		}else if(data==1){
			parent.layer.msg('未查询到相关数据！', 2, 8);return false;
		}else{
		    if(id==1){
				$('#gid_val').html(data);
				layui.use(['form'],function(){
				var f = layui.form;
				f.render();
				});
			}
		}
	});
}

function searchcrm(id){
	var pytoken = $('#pytoken').val();
	if(id==1){
	    var keyword = $.trim($('#crmkeyword').val());
	}else{
	    var keyword = $.trim($('#crmkeyword').val());
	}
	if(!keyword){
		parent.layer.msg('请输入搜索关键词！', 2, 8);return false;
	}
	var i=layer.load('执行中，请稍后...',0);
	$.post(weburl+"/index.php?m=ajax&c=selcrm",{pytoken:pytoken,keyword:keyword,id:id},function(data){
		layer.close(i);
		if(data==0){
			parent.layer.msg('请输入搜索关键词！', 2, 8);return false;
		}else if(data==1){
			parent.layer.msg('未查询到相关数据！', 2, 8);return false;
		}else{
		    if(id==1){
				$('#cid_val').html(data);
				layui.use(['form'],function(){
				var f = layui.form;
				f.render();
				});
			}
		}
	});
}

function add_site(id,name){
    if(id!=''){
	   $("#domain_val").val(id);
	}
	if(name!=''){
	   $("#domain_name").val(name);
	}
	$.layer({
		type : 1,
		title :'分配站点', 
		closeBtn : [0 , true],
		border : [10 , 0.3 , '#000', true],
		area : ['400px','200px'],
		offset: ['20px', ''],
		page : {dom :"#domainlist"}
	});
}
function check_domain(){
    
	var domain_val=$("#domain_val").val();
	var domain_name = domain_val.split(',');
		var id=domain_name[0];
		var name=domain_name[1];
		$(".city_news_but").val(name);
		$("#did").val(id);
	
	layer.closeAll(); 
}
function domaincheck(){
   var did=$("#did_val").val();
   if(!did){
       layer.msg('请选择需要切换的站点',2,8);return false;
   }
}
function guwencheck(){
   var gid=$("#gid_val").val();
   if(!gid){
       layer.msg('请选择需要分配的顾问',2,8);return false;
   }
}
function crmcheck(){
   var cid=$("#cid_val").val();
   if(!cid){
       layer.msg('请选择需要绑定的业务员',2,8);return false;
   }
}
<?php echo '</script'; ?>
>
<style>
	.admin_compay_fp{width:340px; margin-top:15px;}
	.admin_compay_fp_s{width:100px; text-align:right; font-weight:bold; display:inline-block}
	.admin_compay_fp_sub{width:140px;height:25px;border:1px solid #ddd;}
	.admin_compay_fp_sub1{width:40px;height:27px; background:#3692cf;color:#fff;border:none; cursor:pointer}

	.layui-layer-page .layui-layer-content{
		overflow: visible !important;
	}
</style>
<div id="infoboxdomain"  style="display:none; width: 350px; ">
	<form class="layui-form" action="" target="supportiframe" method="post" id="formDomain" onsubmit="return domaincheck()"> 
		<div class="admin_compay_fp">
			<span class="admin_compay_fp_s" id="com_name"></span> 
			<em  id="siteusername"></em>
		</div>
		
		<div class="admin_compay_fp">
			<span class="admin_compay_fp_s">分站搜索：</span>
			<input type="text" value="" id="sitekeyword" class="admin_compay_fp_sub">
			<input type='button' onclick="searchdomain('1')" value="搜索" class="admin_compay_fp_sub1">
		</div>
		<div class="admin_compay_fp" style="height:34px;">
			<span class="admin_compay_fp_s" style="float:left; line-height:34px; display:inline-block; margin-right:5px;">切换站点：</span>
            
			<div class="yun_admin_select_box zindex100"> 
				<div class="layui-form-item">
				  <div class="layui-input-inline" >
						<select name="did" id="did_val" lay-filter="did">
						<option value="">请选择</option>
						
						<?php  $_smarty_tpl->tpl_vars['dlist'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['dlist']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['Dname']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['dlist']->key => $_smarty_tpl->tpl_vars['dlist']->value) {
$_smarty_tpl->tpl_vars['dlist']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['dlist']->key;
?>
						<option  value="<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
" ><?php echo $_smarty_tpl->tpl_vars['dlist']->value;?>
</option>
						<?php } ?>
						</select>
				  </div>
				 </div>
           </div>
            
            
		</div>
		<div class="admin_compay_fp">
			<span class="admin_compay_fp_s">&nbsp;</span>
			<input type="submit"  value='确认' class="admin_examine_bth"><input type="button"  onClick="layer.closeAll();" class="admin_examine_bth_qx" value='取消' style="margin-left:10px;">
		</div> 
		<input type="hidden" name="pytoken" value="<?php echo $_smarty_tpl->tpl_vars['pytoken']->value;?>
">
		<input name="uid" value="0" id="siteuid" type="hidden">
	</form> 
</div>

<div id="infoguwen"  style="display:none; width: 350px; ">
	<form class="layui-form" action="" target="supportiframe" method="post" id="formGuwen" onsubmit="return guwencheck()"> 
		<div class="admin_compay_fp">
			<span class="admin_compay_fp_s" id="com_username"></span> 
			<em id="comusername"></em>
		</div>
		
		<div class="admin_compay_fp">
			<span class="admin_compay_fp_s">顾问搜索：</span>
			<input type="text" value="" id="guwenkeyword" class="admin_compay_fp_sub">
			<input type='button' onclick="searchguwen('1')" value="搜索" class="admin_compay_fp_sub1">
		</div>

		<div class="admin_compay_fp" style="height:34px;">
			<span class="admin_compay_fp_s" style="float:left; line-height:34px; display:inline-block; margin-right:5px;">选择顾问：</span>
            <div class="yun_admin_select_box zindex100"> 
				<div class="layui-form-item">
				  <div class="layui-input-inline" >
						<select name="gid" id="gid_val" lay-filter="gid">
						<option value="">请选择</option>
						
						<?php  $_smarty_tpl->tpl_vars['glist'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['glist']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['guweninfo']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['glist']->key => $_smarty_tpl->tpl_vars['glist']->value) {
$_smarty_tpl->tpl_vars['glist']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['glist']->key;
?>
							<option value="<?php echo $_smarty_tpl->tpl_vars['glist']->value['id'];?>
" ><?php echo $_smarty_tpl->tpl_vars['glist']->value['username'];?>
</option>
						<?php } ?>
						</select>
				  </div>
				 </div>
           </div>
		</div>

		<div class="admin_compay_fp">
			<span class="admin_compay_fp_s">&nbsp;</span>
			<input type="submit"  value='确认' class="admin_examine_bth"><input type="button"  onClick="layer.closeAll();" class="admin_examine_bth_qx" value='取消' style="margin-left:10px;">
		</div> 
		<input type="hidden" name="pytoken" value="<?php echo $_smarty_tpl->tpl_vars['pytoken']->value;?>
">
		<input name="comid" value="0" id="comid" type="hidden">
	</form> 
</div>
 

<div id="infocrm"  style="display:none; width: 350px; ">
	<form class="layui-form" action="" target="supportiframe" method="post" id="formCrm" onsubmit="return crmcheck()"> 
		<div class="admin_compay_fp">
			<span class="admin_compay_fp_s" id="con_name"></span> 
			<em  id="crmusername"></em>
		</div>
		
		<div class="admin_compay_fp">
			<span class="admin_compay_fp_s">业务员搜索：</span>
			<input type="text" value="" id="crmkeyword" class="admin_compay_fp_sub">
			<input type='button' onclick="searchcrm('1')" value="搜索" class="admin_compay_fp_sub1">
		</div>

		<div class="admin_compay_fp" style="height:34px;">
			<span class="admin_compay_fp_s" style="float:left; line-height:34px; display:inline-block; margin-right:5px;">选择业务员：</span>
            <div class="yun_admin_select_box zindex100"> 
				<div class="layui-form-item">
				  <div class="layui-input-inline" >
						<select name="cid" id="cid_val" lay-filter="cid">
						<option value="">请选择</option>
						
						<?php  $_smarty_tpl->tpl_vars['clist'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['clist']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['crmlist']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['clist']->key => $_smarty_tpl->tpl_vars['clist']->value) {
$_smarty_tpl->tpl_vars['clist']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['clist']->key;
?>
							<option  value="<?php echo $_smarty_tpl->tpl_vars['clist']->value['uid'];?>
" ><?php echo $_smarty_tpl->tpl_vars['clist']->value['username'];?>
</option>
						<?php } ?>
						</select>
				  </div>
				 </div>
           </div>
            
            
		</div>
		<div class="admin_compay_fp">
			<span class="admin_compay_fp_s">&nbsp;</span>
			<input type="submit"  value='确认' class="admin_examine_bth"><input type="button"  onClick="layer.closeAll();" class="admin_examine_bth_qx" value='取消' style="margin-left:10px;">
		</div> 
		<input type="hidden" name="pytoken" value="<?php echo $_smarty_tpl->tpl_vars['pytoken']->value;?>
">
		<input name="conid" value="0" id="conid" type="hidden">
	</form> 
</div>

<div id="domainlist" style="display:none;float:left">
<form class="layui-form">
	<div class="admin_compay_fp">
		<span class="admin_compay_fp_s">分站搜索：</span>
		<input type="text" value="" id="domainkeyword" class="admin_compay_fp_sub">
		<input type='button' onclick="searchdomain('2')" value="搜索" class="admin_compay_fp_sub1">
	</div>
	<div class="admin_compay_fp" style="height:34px;">
		<span class="admin_compay_fp_s" style="float:left; line-height:34px; display:inline-block; margin-right:5px;">切换站点：</span>
		<div class="yun_admin_select_box zindex100">
			<div class="layui-form-item">
				  <div class="layui-input-inline" >
						<select name="did" id="domain_val" lay-filter="did">
						<option value="">请选择</option>
						<?php  $_smarty_tpl->tpl_vars['dlist'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['dlist']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['Dname']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['dlist']->key => $_smarty_tpl->tpl_vars['dlist']->value) {
$_smarty_tpl->tpl_vars['dlist']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['dlist']->key;
?>
						<option  value="<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
,<?php echo $_smarty_tpl->tpl_vars['dlist']->value;?>
" ><?php echo $_smarty_tpl->tpl_vars['dlist']->value;?>
</option>
						<?php } ?>
						</select>
				  </div>
				 </div>
		</div>

	</div>
	<div class="admin_compay_fp">
		<span class="admin_compay_fp_s">&nbsp;</span>
		<input type="button"  value='确认' onclick="check_domain()" class="admin_examine_bth"><input type="button"  onClick="layer.closeAll();" class="admin_examine_bth_qx" value='取消' style="margin-left:10px;">
	</div> 
	</form>
</div>
<?php echo '<script'; ?>
>
layui.use(['form'], function(){var form = layui.form;});
<?php echo '</script'; ?>
><?php }} ?>
