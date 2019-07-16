<?php /* Smarty version Smarty-3.1.21-dev, created on 2019-03-13 12:05:27
         compiled from "/www/wwwroot/hr/app/template/default/ajax/login.htm" */ ?>
<?php /*%%SmartyHeaderCode:9052914965c888187af09b2-72065684%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'bedc5167481866be173dfd0ce5fe73600ef686d1' => 
    array (
      0 => '/www/wwwroot/hr/app/template/default/ajax/login.htm',
      1 => 1524796108,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '9052914965c888187af09b2-72065684',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'usertype' => 0,
    'member' => 0,
    'config' => 0,
    'username' => 0,
    'uid' => 0,
    'company' => 0,
    'addjobnum' => 0,
    'lt' => 0,
    'reg_com_url' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_5c888187c62ec6_47128672',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5c888187c62ec6_47128672')) {function content_5c888187c62ec6_47128672($_smarty_tpl) {?><?php if (!is_callable('smarty_function_url')) include '/www/wwwroot/hr/app/include/libs/plugins/function.url.php';
?><?php if ($_smarty_tpl->tpl_vars['usertype']->value=="1") {?>   <div class="login_after_welcome">欢迎登录</div>
	<div class="login_after_user_box">
	  <div class="login_after_user_photo"> <img width="60" height="60" src="<?php echo $_smarty_tpl->tpl_vars['member']->value['photo'];?>
" onerror="showImgDelay(this,'<?php echo $_smarty_tpl->tpl_vars['config']->value['sy_weburl'];?>
/<?php echo $_smarty_tpl->tpl_vars['config']->value['sy_member_icon'];?>
',2);"> </div>
	  <div class="login_after_user_name">
          <div class="">
   尊敬的个人用户：</div>
	    <div class="login_after_user_uname"><span class="login_after_username_id"><?php echo $_smarty_tpl->tpl_vars['username']->value;?>
</span></div>
	  </div>
	</div>
	<div class="login_after_ztbox">
	  <div class="login_after_zt_list"><a href="<?php echo $_smarty_tpl->tpl_vars['config']->value['sy_weburl'];?>
/member/index.php?c=resume"><span class="login_after_zt_list_n"><?php echo $_smarty_tpl->tpl_vars['member']->value['resume_num'];?>
</span>我的简历</a></div>
	  <div class="login_after_zt_list"><a href="<?php echo $_smarty_tpl->tpl_vars['config']->value['sy_weburl'];?>
/member/index.php?c=job"> <span class="login_after_zt_list_n"><?php echo $_smarty_tpl->tpl_vars['member']->value['sq_jobnum'];?>
</span>申请职位</a></div>
	  <div class="login_after_zt_list login_after_zt_list_end"><a href="<?php echo $_smarty_tpl->tpl_vars['config']->value['sy_weburl'];?>
/member/index.php?c=favorite"><span class="login_after_zt_list_n"><?php echo $_smarty_tpl->tpl_vars['member']->value['fav_jobnum'];?>
</span>收藏职位</a></div>
	</div>
	<div class="login_after_bthbox"> <a href="<?php echo $_smarty_tpl->tpl_vars['config']->value['sy_weburl'];?>
/member/index.php?c=expect&add=<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" class="login_after_userbth">创建简历</a> 
		<a href="<?php echo $_smarty_tpl->tpl_vars['config']->value['sy_weburl'];?>
/member/index.php?c=resume" class="login_after_usergz">简历管理</a> 
		<a href="<?php echo $_smarty_tpl->tpl_vars['config']->value['sy_weburl'];?>
/member/index.php?c=atn" class="login_after_userbth">关注的企业</a>
		<a href="<?php echo $_smarty_tpl->tpl_vars['config']->value['sy_weburl'];?>
/member/index.php" class=" login_after_userbthend">进入管理中心</a> 
		<a href="javascript:void(0);" onclick="logout('<?php echo smarty_function_url(array('c'=>'logout'),$_smarty_tpl);?>
');" class="login_after_bttc"> 安全退出</a>
	</div>
<?php } elseif ($_smarty_tpl->tpl_vars['usertype']->value=="2") {?>
 
<div class="login_after_userlogo">
<div class="login_after_comlogo"><img width="80" height="80"src="<?php echo $_smarty_tpl->tpl_vars['company']->value['logo'];?>
"  onerror="showImgDelay(this,'<?php echo $_smarty_tpl->tpl_vars['config']->value['sy_weburl'];?>
/<?php echo $_smarty_tpl->tpl_vars['config']->value['sy_unit_icon'];?>
',2);">
</div>
<div class="login_after_combg"></div>
</div>
<div class="login_after_username">你好！<span class="login_after_username_id"><?php echo $_smarty_tpl->tpl_vars['username']->value;?>
</span></div>
<div class="login_after_webrname">欢迎登录<?php echo $_smarty_tpl->tpl_vars['config']->value['sy_webname'];?>
</div>
<div class="login_after_ztbox">
  <div class="login_after_zt_list"><a href="<?php echo $_smarty_tpl->tpl_vars['config']->value['sy_weburl'];?>
/member/index.php?c=hr"><span class="login_after_zt_list_n"><?php echo $_smarty_tpl->tpl_vars['company']->value['sq_job'];?>
</span>收到简历</a></div>
  <div class="login_after_zt_list"><a href="<?php echo $_smarty_tpl->tpl_vars['config']->value['sy_weburl'];?>
/member/index.php?c=job&w=1"><span class="login_after_zt_list_n"><?php echo $_smarty_tpl->tpl_vars['company']->value['job'];?>
</span>招聘职位</a></div>
  
  <div class="login_after_zt_list login_after_zt_list_end"><a href="<?php echo $_smarty_tpl->tpl_vars['config']->value['sy_weburl'];?>
/member/index.php?c=job&w=2"><span class="login_after_zt_list_n"><?php echo $_smarty_tpl->tpl_vars['company']->value['status2'];?>
</span>已过期职位</a>
  </div>

<div class="login_after_bthbox"> <?php if ($_smarty_tpl->tpl_vars['addjobnum']->value=='1') {?> <a href="<?php echo $_smarty_tpl->tpl_vars['config']->value['sy_weburl'];?>
/member/index.php?c=jobadd" class="login_after_bth">发布职位</a> <?php } else { ?> <a href="javascript:void(0)" onclick="jobaddurl('<?php echo $_smarty_tpl->tpl_vars['addjobnum']->value;?>
','<?php echo $_smarty_tpl->tpl_vars['config']->value['integral_job'];?>
')" class="login_after_bth">发布职位</a> <?php }?> <a href="<?php echo $_smarty_tpl->tpl_vars['config']->value['sy_weburl'];?>
/member/index.php" class=" login_after_bthend">进入管理中心</a> <a href="javascript:void(0);" onclick="logout('<?php echo smarty_function_url(array('c'=>'logout'),$_smarty_tpl);?>
');" class="login_after_bttc"> 安全退出</a> </div>
<?php } elseif ($_smarty_tpl->tpl_vars['usertype']->value=="3") {?>
<div class="login_after_welcome">欢迎登录</div>
<div class="login_after_user_box">
  <div class="login_after_user_photo"> <img width="60" height="60" src="<?php echo $_smarty_tpl->tpl_vars['lt']->value['photo'];?>
" onerror="showImgDelay(this,'<?php echo $_smarty_tpl->tpl_vars['config']->value['sy_weburl'];?>
/<?php echo $_smarty_tpl->tpl_vars['config']->value['sy_lt_icon'];?>
',2);"> </div>
  <div class="login_after_user_name">
    <div class="">
   尊敬的猎头用户：</div>
     <div class="login_after_user_uname"><span class="login_after_username_id"><?php echo $_smarty_tpl->tpl_vars['username']->value;?>
</span></div>
 
  </div>
</div>
<div class="login_after_ztbox">
  <div class="login_after_zt_list"><a href="<?php echo $_smarty_tpl->tpl_vars['config']->value['sy_weburl'];?>
/member/index.php?c=entrust_resume"><span class="login_after_zt_list_n"><?php echo $_smarty_tpl->tpl_vars['lt']->value['entrust'];?>
</span>委托简历</a></div>
  <div class="login_after_zt_list"><a href="<?php echo $_smarty_tpl->tpl_vars['config']->value['sy_weburl'];?>
/member/index.php?c=job&s=1"><span class="login_after_zt_list_n"><?php echo $_smarty_tpl->tpl_vars['lt']->value['lt_job'];?>
</span>招聘职位</a></div>
  <div class="login_after_zt_list login_after_zt_list_end"><a href="<?php echo $_smarty_tpl->tpl_vars['config']->value['sy_weburl'];?>
/member/index.php?c=job&s=2"><span class="login_after_zt_list_n"><?php echo $_smarty_tpl->tpl_vars['lt']->value['lt_status2'];?>
</span>已过期职位</a></div>
</div>
<div class="login_after_bthbox"> <a href="<?php echo $_smarty_tpl->tpl_vars['config']->value['sy_weburl'];?>
/member/index.php?c=jobadd" class="login_after_userbth">发布职位</a> <a href="<?php echo $_smarty_tpl->tpl_vars['config']->value['sy_weburl'];?>
/member/index.php?c=search_resume" class="login_after_usergz">搜索简历</a> <a href="<?php echo $_smarty_tpl->tpl_vars['config']->value['sy_weburl'];?>
/member/index.php?c=yp_resume" class="login_after_userbth">收到简历</a> <a href="<?php echo $_smarty_tpl->tpl_vars['config']->value['sy_weburl'];?>
/member/index.php" class=" login_after_userbthend">进入管理中心</a> <a href="javascript:void(0);" onclick="logout('<?php echo smarty_function_url(array('c'=>'logout'),$_smarty_tpl);?>
');" class="login_after_bttc"> 安全退出</a> </div>
<?php } else { ?>
<!--登录-->
<div class="hp_login_tit">

	<input type="hidden" name="act" id="act_login" value="0"/>
	<ul class="login_box_h_list">
		<li id="acount_login" class="login_box_h_list_cur">账号登录</li>
		<?php if ($_smarty_tpl->tpl_vars['config']->value['sy_msg_isopen']==1&&$_smarty_tpl->tpl_vars['config']->value['sy_msg_login']==1) {?>
		<li id="mobile_login" class="">手机登录<i class="login_box_line"></i></li>
		<?php }?>
	</ul>
	<?php if ($_smarty_tpl->tpl_vars['config']->value['wx_author']=='1') {?>
	<div class="wxcode_login" title="微信扫一扫登录" style="display:block;"></div>
	<div class="normal_login none" title="普通登录"></div>
	<?php }?>
</div>

<div class="wx_login_show none">
 <div id="wx_login_qrcode" class="wxlogintext">正在获取二维码...</div>
 <div class="wxlogintxt">请使用微信扫一扫登录</div>
</div>

<div id="login_normal">
	<!--账号登录-->
	<div class="login_normal_box" id="login_normal_box">
	<div class="hp_login_hy"> <i class="hp_login_hy_icon fl"></i><i class="hp_login_line"></i>
	  <input class="hp_login_hy_but fl" type="text" id="username" name="username" value="邮箱/手机号/用户名" placeholder="邮箱/手机号/用户名"/>
	  <div class="index_logoin_msg none" id="show_name">
	    <div class="index_logoin_msg_tx">请填写用户名</div>
	    <div class="index_logoin_msg_icon"></div>
	  </div>
	</div>
	<div class="hp_login_hy"> <i class="hp_login_mm_icon fl"></i> <i class="hp_login_line"></i>
	<input type="text" id="password2" value="请输入密码" class="hp_login_mm_but fl">
	<input type="password" id="password" name="password" class="hp_login_mm_but fl none" value="" placeholder="请输入密码">
	  <div class="index_logoin_msg none" id="show_pass">
	    <div class="index_logoin_msg_tx">请填写密码</div>
	    <div class="index_logoin_msg_icon"></div>
	  </div>
	</div>
	</div>
	<div class="clear"></div>
	<!--手机动态码登录-->
	<?php if ($_smarty_tpl->tpl_vars['config']->value['sy_msg_isopen']==1&&$_smarty_tpl->tpl_vars['config']->value['sy_msg_login']==1) {?>
	<div class="login_sj_box none" id="login_sj_box">
	  <div class="hp_login_hy">
        <i class="hp_login_sj_icon fl"></i><i class="hp_login_line"></i>
	  	<input name="username" id="usermoblie" type="text" class="hp_login_hy_but hp_login_mm_but" value="请输入手机号码">
	  	<div class="index_logoin_msg none" id="show_mobile">
		  <div class="index_logoin_msg_tx" >请填写正确手机号</div>
		  <div class="index_logoin_msg_icon"></div>
		  </div>
	  </div>
	</div>
	<div class="clear"></div>
	<?php }?>
	<?php if (stripos($_smarty_tpl->tpl_vars['config']->value['code_web'],"前台登录")!==false) {?>
	<?php if ($_smarty_tpl->tpl_vars['config']->value['code_kind']==3) {?>	
	<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['config']->value['sy_weburl'];?>
/js/jquery-1.8.0.min.js?v=<?php echo $_smarty_tpl->tpl_vars['config']->value['cachecode'];?>
" language="javascript"><?php echo '</script'; ?>
>
	<?php echo '<script'; ?>
>
	var weburl="<?php echo $_smarty_tpl->tpl_vars['config']->value['sy_weburl'];?>
",integral_pricename='<?php echo $_smarty_tpl->tpl_vars['config']->value['integral_pricename'];?>
',pricename='<?php echo $_smarty_tpl->tpl_vars['config']->value['integral_pricename'];?>
',code_web='<?php echo $_smarty_tpl->tpl_vars['config']->value['code_web'];?>
',code_kind='<?php echo $_smarty_tpl->tpl_vars['config']->value['code_kind'];?>
';
	<?php echo '</script'; ?>
>
	<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['config']->value['sy_weburl'];?>
/js/geetest/gt.js?v=<?php echo $_smarty_tpl->tpl_vars['config']->value['cachecode'];?>
"><?php echo '</script'; ?>
>
	<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['config']->value['sy_weburl'];?>
/js/geetest/pc.js?v=<?php echo $_smarty_tpl->tpl_vars['config']->value['cachecode'];?>
" type="text/javascript"><?php echo '</script'; ?>
>
	<div class="index_verification" style="margin-top:15px">
  <div id="popup-captcha" data-id='sublogin' data-type='click'></div>
	<input type='hidden' id="popup-submit">
	</div>
  <style>.index_verification .geetest_holder.geetest_wind{min-width:247px;}</style>
	<?php } else { ?>
	<div class=" hp_login_yzbox">
        <i class="hp_login_yzbox_icon"></i><i class="hp_login_line"></i>
		<input type="text" id="txt_CheckCode" name="authcode" value="验证码" class="yun_Indexlogin_yzm" maxlength="4">
		<div class="hp_login_yzbox_pic"><a href="javascript:void(0);" onclick="checkCode('vcode_imgs');"><img id="vcode_imgs" src="<?php echo $_smarty_tpl->tpl_vars['config']->value['sy_weburl'];?>
/app/include/authcode.inc.php" class="yun_Indexlogin_yzm_img"></a></div>
		<div class="index_logoin_msg none" id="show_code">
        
			<div class="index_logoin_msg_tx">请填写验证码</div>
			<div class="index_logoin_msg_icon"></div>
	  </div>
	</div>
	<?php }?>
	<?php }?>
	<div class="clear"></div>
	<div class="login_sj_box none" id="login_sjyz_box">
		<div class="hp_login_hy">
         <i class="hp_login_mm_icon fl"></i><i class="hp_login_line"></i>
	  	<input name="password" type="text" class="login_m_text" id="dynamiccode" value="短信动态码">
	  	<div class="index_logoin_msg none" id="show_dynamiccode">
		  <div class="index_logoin_msg_tx" >请填写短信动态码</div>
		  <div class="index_logoin_msg_icon"></div>
		  </div>
		  <a href="javascript:void(0);" class=" hp_login_hy_send" id="send_msg_tip" onclick="send_msg2('<?php echo smarty_function_url(array('m'=>'login','c'=>'sendmsg'),$_smarty_tpl);?>
');">发送动态码</a>
	  </div>
	</div>
	<div class="hp_login_lg">
	  <input class="hp_login_lg_but" type="button" value="立即登录" onclick="check_login('<?php echo smarty_function_url(array('m'=>'login','c'=>'loginsave'),$_smarty_tpl);?>
','vcode_imgs');"/>
	</div>
	<div class="clear"></div>
	<div class="hp_login_rg fl"> 
		<a href="<?php echo $_smarty_tpl->tpl_vars['reg_com_url']->value;?>
" >注册账号</a>
    <a href="<?php echo smarty_function_url(array('m'=>'forgetpw'),$_smarty_tpl);?>
" style="float:right">忘记密码</a>
	</div></div>
	<?php }?>

<style>#label{height:34px; line-height:34px;border:1px solid #e6e6e6}</style>
<?php echo '<script'; ?>
>

$(document).ready(function(){
	//账号登录和手机登录tab选择
	$('#acount_login').click(function(data){
		$('#acount_login').removeClass().addClass('login_box_h_list_cur');
		$('#mobile_login').removeClass();
		$('#login_normal_box').show();
		$('#login_sj_box').hide();
		$('#login_sjyz_box').hide();
		$('#act_login').val('0');
	});
	$('#mobile_login').click(function(data){
		$('#mobile_login').removeClass().addClass('login_box_h_list_cur');
		$('#acount_login').removeClass();
		$('#login_sj_box').show();
		$('#login_sjyz_box').show();
		$('#login_normal_box').hide();
		$('#act_login').val('1');
	});
	$("#username,#txt_CheckCode,#usermoblie,#dynamiccode").focus(function(){
		var txAreaVal = $(this).val();
		if( txAreaVal == this.defaultValue){$(this).val("");}
	}).blur(function(){
		var txAreaVal = $(this).val();
		if( txAreaVal == this.defaultValue||$(this).val()==""){$(this).val(this.defaultValue);}
	}).keydown(function (e) {
	    var ev = document.all ? window.event : e;
	    if (ev.keyCode == 13) {
	        check_login('<?php echo smarty_function_url(array('m'=>'login','c'=>'loginsave'),$_smarty_tpl);?>
','vcode_imgs');
	    } else { return;}
	});
	$("#password2").focus(function(){
		$("#password").show();
		$("#password").focus();
		$("#password2").hide();
	})
	$("#password").blur(function(){
		if($("#password").val()==""){
			$("#password2").show();
			$("#password").hide();
		}
	}).keydown(function (e) {
	    var ev = document.all ? window.event : e;
	    if (ev.keyCode == 13) {
	        check_login('<?php echo smarty_function_url(array('m'=>'login','c'=>'loginsave'),$_smarty_tpl);?>
','vcode_imgs');
	    } else { return; }
	});
	var setval;
	$('.wxcode_login').click(function(data){
		$('.wxcode_login').hide();
		$('.normal_login').show();
		$('#login_normal').hide();
		$('.login_box_h_list').hide();
		$('.wx_login_show').show();
		$.post('<?php echo smarty_function_url(array('m'=>'login','c'=>'wxlogin'),$_smarty_tpl);?>
',{t:1},function(data){
			if(data==0){
				$('#wx_login_qrcode').html('二维码获取失败..');
			}else{
				$('#wx_login_qrcode').html('<img src="'+data+'" width="100" height="100">');
				setval = setInterval("wxorderstatus()", 2000); 
			}
		});

	});
	$('.normal_login').click(function(data){
		$('.wxcode_login').show();
		$('.normal_login').hide();
		$('#login_normal').show();
		$('.login_box_h_list').show();
		$('.wx_login_show').hide();
		clearInterval(setval);
	});
});

function wxorderstatus() { 
	$.post('<?php echo smarty_function_url(array('m'=>'login','c'=>'getwxloginstatus'),$_smarty_tpl);?>
',{t:1},function(data){
		
		var data=eval('('+data+')');
		if(data.url!='' && data.msg!=''){
			layer.msg(data.msg, 2, 9,function(){window.location.href=data.url;});
		}else if(data.url){
			
			window.location.href=data.url;
		}
	});
}
function jobaddurl(num,integral_job){ 
	var gourl= weburl+'/member/index.php?c=jobadd';
		checkType = 'addjob';

	var url = weburl + '/index.php?m=company&c=ajax_day_action_check';
		$.post(url,
			{'type': checkType},
			function(data){
				data = eval('(' + data + ')');
				if(data.status == -1){
					layer.msg(data.msg, 2, 8);
				}else if(data.status == 1){
					if(num==1){
						window.location.href=gourl;
						window.event.returnValue = false;
						return false;
					}else if(num==2){
						var msg='套餐已用完，您可以<a href="'+weburl+'/member/index.php?c=right&act=added" style="color:red;">购买增值包</a>！是否继续？';
						layer.confirm(msg, function(){
							layer.closeAll();
							window.location.href=weburl+"/member/index.php?c=right&act=added"; 
							
						});
					}else if(num==0){
						var msg='会员已到期，您可以<a href="'+weburl+'/member/index.php?c=right" style="color:red">购买会员</a>！是否继续？';

						layer.confirm(msg, function(){
							window.location.href=weburl+"/member/index.php?c=right"; 
						});  
 						
 					}
				}
			}
		);
	 
}
<?php echo '</script'; ?>
><?php }} ?>
