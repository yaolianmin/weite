<?php /* Smarty version Smarty-3.1.21-dev, created on 2019-03-13 11:36:27
         compiled from "/www/wwwroot/hr/app/template/admin/admin_right.htm" */ ?>
<?php /*%%SmartyHeaderCode:9341595405c887abb920387-17864560%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '99ab13c89c3821b253441b20ff8230a6871c2ae0' => 
    array (
      0 => '/www/wwwroot/hr/app/template/admin/admin_right.htm',
      1 => 1545418740,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '9341595405c887abb920387-17864560',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'config' => 0,
    'dirname' => 0,
    'mruser' => 0,
    'soft' => 0,
    'kongjian' => 0,
    'banben' => 0,
    'yonghu' => 0,
    'server' => 0,
    'pytoken' => 0,
    'base' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_5c887abb988a53_34918048',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5c887abb988a53_34918048')) {function content_5c887abb988a53_34918048($_smarty_tpl) {?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<link href="images/reset.css?v=<?php echo $_smarty_tpl->tpl_vars['config']->value['cachecode'];?>
" rel="stylesheet" type="text/css" /> 
<?php echo '<script'; ?>
 src="../js/jquery-1.8.0.min.js?v=<?php echo $_smarty_tpl->tpl_vars['config']->value['cachecode'];?>
"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="js/admin_public.js?v=<?php echo $_smarty_tpl->tpl_vars['config']->value['cachecode'];?>
" language="javascript"><?php echo '</script'; ?>
> 
<title>后台管理</title>
<?php echo '<script'; ?>
> 
/*屏蔽所有的js错误*/
function killerrors() {return true;}
window.onerror = killerrors;
function logout(){
	if (confirm("您确定要退出控制面板吗？"))
	top.location = 'index.php?c=logout';
	return false;
}
var integral_pricename='<?php echo $_smarty_tpl->tpl_vars['config']->value['integral_pricename'];?>
';  
<?php echo '</script'; ?>
>
<!--[if IE 6]>
<?php echo '<script'; ?>
 src="./js/png.js?v=<?php echo $_smarty_tpl->tpl_vars['config']->value['cachecode'];?>
"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
>
  DD_belatedPNG.fix('.png,.header .logo,.header .nav li a,.header .nav li.on,.left_menu h3 span.on');
<?php echo '</script'; ?>
>
<![endif]-->
</head>
<body style="font-size:14px; padding-bottom:0; ">
<div id="yunshengji"></div>

<?php if ($_smarty_tpl->tpl_vars['dirname']->value||$_smarty_tpl->tpl_vars['mruser']->value==1) {?>
<div>
<div class="admin_index_info">
<div class="admin_index_info_box">
<div class="">
<?php if ($_smarty_tpl->tpl_vars['dirname']->value) {?>为了您的网站安全考虑，强烈建议将 admin,install 文件夹名改为其它名称！<?php }?> 
<?php if ($_smarty_tpl->tpl_vars['mruser']->value==1) {?>您还没有更改默认的管理员用户名和密码!<a href="index.php?m=admin_user&c=pass" class="admin_index_info_box_a">【马上修改】</a><?php }?>
</div>
</div>
</div>
<?php }?>



    <div class="admin_index_data_box">
   <div class="admin_index_data_list">
    <div class="admin_index_sj_data_today">今日   </div>
    <div class="admin_index_sj_data_todaydata"><div class="admin_index_sj_data_todaydata_n" id="ajax_new_member_num">0</div><div class="admin_index_sj_data_todaydata_p">新增会员总数</div>  </div>
   <div class="admin_index_data_newlist">
      <a href="index.php?m=user_member&adtime=1">
        <div class="admin_index_data_new_p">新增个人会员</div><div class="admin_index_data_new_n" id="ajax_new_user_num">0</div></a>
   </div>
   <div class="admin_index_data_newlist">
    <a href="index.php?m=admin_resume&adtime=1">
    <div class="admin_index_data_new_p">新增简历</div><div class="admin_index_data_new_n admin_index_data_new_n_c2" id="ajax_new_resume_num">0</div>
    </a>
    </div>
   <div class="admin_index_data_newlist">
    <a href="index.php?m=admin_company&adtime=1">
    <div class="admin_index_data_new_p">新增企业会员</div><div class="admin_index_data_new_n admin_index_data_new_n_c3" id="ajax_new_company_num">0</div>
    </a>
    </div>
   <div class="admin_index_data_newlist">
    <a href="index.php?m=admin_company_job&adtime=1">
    <div class="admin_index_data_new_p">新增职位</div><div class="admin_index_data_new_n admin_index_data_new_n_c4" id="ajax_new_job_num">0</div>
    </a>
    </div>
   </div>
   
     <div class="admin_index_data_list">
    <div class="admin_index_sj_data_today">收益   </div>
    <div class="admin_index_sj_data_todaydata"><div class="admin_index_sj_data_todaydata_n" style="color:#67b930"><span class="admin_index_statistics_fh">￥</span><span id="ajax_money_total">0</span></div><div class="admin_index_sj_data_todaydata_p">今日总收益</div>  </div>
   <div class="admin_index_data_newlist">
    <a href="index.php?m=company_order&typedd=1&order_state=2&time=1">
    <div class="admin_index_data_new_p">会员套餐</div><div class="admin_index_statistics_n"><span class="admin_index_statistics_fh">￥</span><span id="ajax_money_vip">0</span></div>
    </a>
   </div>
   <div class="admin_index_data_newlist">
    <a href="index.php?m=company_order&typedd=2&order_state=2&time=1">
    <div class="admin_index_data_new_p">积分充值</div><div class="admin_index_statistics_n"><span class="admin_index_statistics_fh">￥</span><span id="ajax_money_integral">0</span></div>
    </a>
   </div>
   <div class="admin_index_data_newlist">
    <a href="index.php?m=company_order&typedd=5&order_state=2&time=1">
    <div class="admin_index_data_new_p">增值服务</div><div class="admin_index_statistics_n"><span class="admin_index_statistics_fh">￥</span><span id="ajax_money_service">0</span></div>
    </a>
   </div>
   <div class="admin_index_data_newlist">
    <a href="index.php?m=company_order&typedd=8&order_state=2&time=1">
    <div class="admin_index_data_new_p">职位分享/悬赏</div><div class="admin_index_statistics_n"><span class="admin_index_statistics_fh">￥</span><span id="ajax_money_job">0</span></div>
    </a>
   </div>
   </div>
   
   <div class="admin_index_dealt">
   <div class="admin_index_dealt_tit"><span class="admin_index_dealt_tit_s">待办提醒</span></div>
   <div class="admin_index_dealt_box">
   <ul>
<li><a href="index.php?m=user_member&status=4"><i class="admin_index_dealt_c"></i>待审核个人会员<span class="admin_index_dealt_n" id='ajax_check_user'>0</span></a></li>
<li><a href="index.php?m=admin_company&status=4"><i class="admin_index_dealt_c"></i>待审核企业会员<span class="admin_index_dealt_n" id='ajax_check_company'>0</span></a></li>
<li><a href="index.php?m=admin_company_job&state=4"><i class="admin_index_dealt_c"></i>待审核职位<span class="admin_index_dealt_n" id='ajax_check_job'>0</span></a></li>
<li><a href="index.php?m=admin_resume&status=4"><i class="admin_index_dealt_c"></i>待审核简历<span class="admin_index_dealt_n" id='ajax_check_resume'>0</span></a></li>
<li><a href="index.php?m=company_order&order_state=3"><i class="admin_index_dealt_c"></i>待确认订单<span class="admin_index_dealt_n" id='ajax_check_order'>0</span></a></li>
<li><a href="index.php?m=company_order&order_state=1&time=7"><i class="admin_index_dealt_c"></i>7日内待付款订单<span class="admin_index_dealt_n" id='ajax_check_pay'>0</span></a></li>
</ul>  </div>
   </div>
    
    </div>
<!--数据统计 -->   
   
 <div class="admin_index_center">
<div class="admin_index_Data">
<div class="admin_index_Data_bor">
<div class="admin_message_h1">
<div class="admin_message_h1_tit">
    <span class="admin_message_h1_s admin_message_h1_cur" data-a="index_tj">数据统计</span>
    <span class="admin_message_h1_s" data-a="index_dt">网站动态</span>
    <span class="admin_message_h1_s" data-a="index_rz">会员日志</span>
    </div>
</div>
    <div class="admin_index_Data_cont" style=" position:relative"  id="index_tj">
    <div class="admin_index_Data_cont_box">
        <div class="admin_index_Data_cont_left">
            <div class="admin_index_fw" id="main22">
                <iframe name="right" id="tbrightMain" src="index.php?m=admin_right&c=getweb" frameborder="false" scrolling="auto" style="border:none;" width="100%" height="300" allowtransparency="true"></iframe>
            </div>
        </div>
        <div class="admin_index_date_list">
            <ul>
                <li><a href="javascript:clicktb('resumetj');" class="admin_index_date_tja admin_index_date_a png">简历统计</a></li>
                <li><a href="javascript:clicktb('jobtj');" class="admin_index_date_tja admin_index_date_b png">职位统计</a></li>
                <li><a href="javascript:clicktb('comtj');" class="admin_index_date_tja admin_index_date_c png">企业注册统计</a></li>
                <li><a href="javascript:clicktb('getweb');" class="admin_index_date_tja admin_index_date_d png">个人注册统计</a></li>
                <li><a href="javascript:clicktb('newstj');" class="admin_index_date_tja admin_index_date_e png">新闻统计</a></li>
                <li><a href="javascript:clicktb('adtj');" class="admin_index_date_tja admin_index_date_f png">广告点击统计</a></li>
                <li><a href="javascript:clicktb('wzptj');" class="admin_index_date_tja admin_index_date_g png">店铺招聘统计</a></li>
                <li><a href="javascript:clicktb('wjltj');" class="admin_index_date_tja admin_index_date_h png">普工简历统计</a></li>
            </ul>
        </div>
    </div>
    </div>
    <div class="admin_index_Data_cont" style="position:relative; display:none" id="index_dt">
   
 <div class="admin_index_Data_cont_rz">
 <div class="admin_index_Data_cont_rz_tit">
         <ul>
                <li><a href="javascript:clicktbdt('downresumedt');">下载简历动态</a></li>
                <li><a href="javascript:clicktbdt('useridjobdt');">职位申请动态</a></li>
                <li><a href="javascript:clicktbdt('lookjobdt');" >职位浏览动态</a></li>
                <li><a href="javascript:clicktbdt('lookresumedt');" >简历浏览动态</a></li>
                <li><a href="javascript:clicktbdt('favjobdt');" >职位收藏动态</a></li>
                <li><a href="javascript:clicktbdt('payorderdt');" >充值动态</a></li>
            </ul> <div class="admin_index_date_list_r"  id="tbrightMaindthy">
	          
	        </div>
            </div>
      
        <div class="admin_index_Data_cont_left" >
            <div class="" id="main22">
                <iframe name="right" id="tbrightMaindt" src="index.php?m=admin_right&c=downresumedt" frameborder="false" scrolling="auto" style="border:none;" width="100%" height="300" allowtransparency="true"></iframe>
            </div>
        </div>

    </div>
    </div>
   
     <div class="admin_index_Data_cont" style="position:relative; display:none" id="index_rz">
     <div class="admin_index_Data_cont_rz">
      <div class="admin_index_Data_cont_rz_tit">
       <ul>
                <li><a href="javascript:clicktbrz('userrz');">个人会员日志</a></li>
                <li><a href="javascript:clicktbrz('comrz');">企业会员日志</a></li>
                <li><a href="javascript:clicktbrz('lietoutz');">猎头会员日志</a></li>
             </ul>
             <div class="admin_index_date_list_r" id="tbrightMainrzhy">
	            </div>
            </div>
        <div class="admin_index_Data_cont_left" >
            <div class="" id="main22">
                <iframe name="right" id="tbrightMainrz" src="index.php?m=admin_right&c=userrz" frameborder="false" scrolling="auto" style="border:none;" width="100%" height="300" allowtransparency="true"></iframe>
            </div>
        </div>
     
    </div>
    </div>
</div>
</div>
</div>
  <div class="mainindex_box" style="margin-top:20px;">
     <div class="mainindex_box_cont">
     <div class="mainmsg">
<div class="mainboxtop"><h6>【服务信息】</h6></div>
        <div class="">
        <span class="mainmsg_list">技术支持：联美网络</span>
		<span class="mainmsg_list">官方论坛：<a href="http://site.sanyenet.com/" target="_blank">http://site.sanyenet.com/</a></span>
        <span class="mainmsg_list">联系QQ：931783865</span>
		<span class="mainmsg_list">官方网站：<a href="http://www.lian-mei.com/" target="_blank">http://www.lian-mei.com/</a></span>
		<span class="mainmsg_list">电话/微信：17757765958</span>
</div>   </div>
     </div> 
	 
	 
     <div class="mainindex_box_cont">
     <div class="mainmsg">
<div class="mainboxtop"><h6>【系统信息】</h6></div>
        <div class="">
        <span class="mainmsg_list">程序版本：v1.0 版本</span>
		<span class="mainmsg_list">服务器软件：<?php echo $_smarty_tpl->tpl_vars['soft']->value;?>
</span>
        <span class="mainmsg_list">可用空间(磁盘区)：<?php echo $_smarty_tpl->tpl_vars['kongjian']->value;?>
&nbsp;M</span>
		<span class="mainmsg_list">MySQL 版本：<?php echo $_smarty_tpl->tpl_vars['banben']->value;?>
</span>
		<span class="mainmsg_list">用户 - 服务器：<?php echo $_smarty_tpl->tpl_vars['yonghu']->value;?>
 - <?php echo $_smarty_tpl->tpl_vars['server']->value;?>
</span>
</div>   </div>
     </div> 	 




<input type="hidden" name="pytoken" id="pytoken" value="<?php echo $_smarty_tpl->tpl_vars['pytoken']->value;?>
"/>
<?php echo '<script'; ?>
>
function dk(){$("#edition_box_yun").show();$(".edition_box_bg").show();}
function gb(){$("#edition_box_yun").hide();$(".edition_box_bg").hide();}
function clicktb(name){
	$("#tbrightMain").attr("src","index.php?m=admin_right&c="+name);
}
function clicktbdt(name){
	$("#tbrightMaindt").attr("src","index.php?m=admin_right&c="+name);
	$.post("index.php?m=admin_right&c="+name+"hy",{rand:Math.random()},function(data){
		$("#tbrightMaindthy").html(data);
	})
}
function clicktbrz(name){
	$("#tbrightMainrz").attr("src","index.php?m=admin_right&c="+name);
	$.post("index.php?m=admin_right&c="+name+"hy",{rand:Math.random()},function(data){
		$("#tbrightMainrzhy").html(data);
	})
}
$(document).ready(function(){
	$(".admin_message_h1_s").click(function(){
		$(".admin_message_h1_s").attr("class","admin_message_h1_s");
		$(this).attr("class","admin_message_h1_s admin_message_h1_cur");
		var a=$(this).attr("data-a");
		$(".admin_index_Data_cont").hide();
		$("#"+a).show();
	});
	$.post("index.php?m=admin_right&c=downresumedthy",{rand:Math.random()},function(data){
		$("#tbrightMaindthy").html(data);
	});
	$.post("index.php?m=admin_right&c=userrzhy",{rand:Math.random()},function(data){
		$("#tbrightMainrzhy").html(data);
	})
})
<?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="https://init.phpyun.com/site.php?site=<?php echo $_smarty_tpl->tpl_vars['base']->value;?>
">//此代码为远程获取补丁及通知，请不要删除<?php echo '</script'; ?>
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
<?php echo '<script'; ?>
 type="text/javascript">
$(document).ready(function(){
  $.post("index.php?m=admin_right&c=ajax_statis", {pytoken: '<?php echo $_smarty_tpl->tpl_vars['pytoken']->value;?>
'}, function(data){
    var o = eval( '(' + data + ')' );
    
    $('#ajax_new_member_num').html(o.memberNum);
    $('#ajax_new_user_num').html(o.userNum);
    $('#ajax_new_resume_num').html(o.resumeNum);
    $('#ajax_new_company_num').html(o.companyNum);
    $('#ajax_new_job_num').html(o.jobNum);

    if(o.moneyTotal){
      $('#ajax_money_total').html(o.moneyTotal);
    }
    if(o.moneyVip){
      $('#ajax_money_vip').html(o.moneyVip);
    }
    if(o.moneyIntegral){
      $('#ajax_money_integral').html(o.moneyIntegral);
    }
    if(o.moneyService){
      $('#ajax_money_service').html(o.moneyService);
    }
    if(o.moneyJob){
      $('#ajax_money_job').html(o.moneyJob);
    }

    $('#ajax_check_user').html(o.checkUserNum);
    $('#ajax_check_company').html(o.checkCompanyNum);
    $('#ajax_check_resume').html(o.checkResumeNum);
    $('#ajax_check_job').html(o.checkJobNum);
    $('#ajax_check_order').html(o.checkOrderNum);
	$('#ajax_check_pay').html(o.checkPayNum);
  });
});
<?php echo '</script'; ?>
>
</body>
</html><?php }} ?>
