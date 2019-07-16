function exitsid(id){
	if(document.getElementById(id)){
		return true;
	}else{
		return false;
	}
}

function checkreg(type){
	$(".reg_cur").removeClass("reg_cur");
	$("#reg"+type).addClass("reg_cur");
	$("#regtype"+type).show();
	if(type=="1"){
		$("#regtype2").hide();
		$("#regtype3").hide();
	}else if(type=="2"){
		$("#regtype1").hide();
		$("#regtype3").hide();
	}else{
		$("#regtype1").hide();
		$("#regtype2").hide();
		$("#reg2").addClass("reg_cur");
	}
}
function uppassword(id){
	var password = $("#password").val();
	S_level=checkStrong(password);
	switch(S_level) { 
	case 0:
		$(".psw_span").removeClass("psw_span_cur");
	break; 
	case 1: //弱
		$("#pass1_"+id).addClass("psw_span_red");
		$("#pass2_"+id).removeClass("psw_span_yellow");
		$("#pass3_"+id).removeClass("psw_span_green");
	break; 
	case 2: //中
		$("#pass1_"+id).removeClass("psw_span_red");
		$("#pass2_"+id).addClass("psw_span_yellow");
		$("#pass3_"+id).removeClass("psw_span_green");
	break; 
	default: //强
		$("#pass1_"+id).removeClass("psw_span_red");
		$("#pass2_"+id).removeClass("psw_span_yellow");
		$("#pass3_"+id).addClass("psw_span_green");
	} 
}
//返回密码的强度级别 
function checkStrong(sPW){
	if (sPW.length<=4) 
	return 0; //密码太短 
	Modes=0; 
	for (i=0;i<sPW.length;i++){
	//测试每一个字符的类别并统计一共有多少种模式. 
	Modes|=CharMode(sPW.charCodeAt(i)); 
	}
	return bitTotal(Modes); 
} 
function CharMode(iN){ 
	if (iN>=48 && iN <=57) //数字 
	return 1; 
	if (iN>=65 && iN <=90) //大写字母 
	return 2; 
	if (iN>=97 && iN <=122) //小写 
	return 4; 
	else 
	return 8; //特殊字符 
} 

//计算出当前密码当中一共有多少种模式 
function bitTotal(num){ 
	modes=0; 
	for (i=0;i<4;i++){ 
	if (num & 1) modes++; 
	num>>>=1; 
	} 
	return modes; 
} 
//---邮箱获取后缀--
function get_def_email(email,type){
		$("#ajax_email"+type).hide();
		var postemail=email.split("@");
		var configemail = $('#defEmail').val();
		var def_email=configemail.split("|");
		var emails=[];
		if($.trim(postemail[1])!=""){
			$.each(def_email,function(index,data){ 
				if(data.indexOf(postemail[1])>-1){
					emails.push(data);
				};
			});
		}else{
			emails=def_email;
		}
		var html='';
		$.each(emails,function(index,data){ 
			if(index==0){
				$class=" reg_email_box_list_hover";
			}else{
				$class="";
			}
			html+='<div class="reg_email_box_list'+$class+' email'+index+'" aid="'+type+'" onclick="click_email('+index+','+type+');" onmousemove="hover_email('+index+');"><span class="eg_email_box_list_left">'+postemail[0]+'</span>'+data+'</div>';
		})
		$(".reg_email_box").html(html);
		$(".reg_email_box").show();
		$("#def").val(email);
		$("#default").val(0);
		$("#allnum").val(emails.length);
}
function hover_email(id){
	$(".reg_email_box_list_hover").removeClass("reg_email_box_list_hover");
	$(".email"+id).addClass("reg_email_box_list_hover");
	$("#default").val(id);
}
function click_email(id,type){
	var email=$(".email"+id).html();
	email=email.replace('<span class="eg_email_box_list_left">','');
	email=email.replace('</span>','');
	email=email.replace('<SPAN class=eg_email_box_list_left>','');
	email=email.replace('</SPAN>','');
	var myreg = /^([a-zA-Z0-9\-]+[_|\_|\.]?)*[a-zA-Z0-9\-]+@([a-zA-Z0-9\-]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,4}$/;
	if(myreg.test(email)){
		$("#email"+type).val(email);
	}else{
		$("#email"+type).val('');
	}
	$("#email"+type).val(email);
	$(".reg_email_box").hide();
}

function keyDown(event) {
	var aevt=event;
	var evt = (aevt) ? aevt : ((window.event) ? window.event : ""); //兼容IE和Firefox获得keyBoardEvent对象  
	var key = evt.keyCode?evt.keyCode:evt.which; //兼容IE和Firefox获得keyBoardEvent对象的键值
    if (key==38){//上
		var def=$("#default").val();
		if(def>0){
			var num=parseInt(def)-1;
			$("#default").val(num);
			$(".reg_email_box_list_hover").removeClass("reg_email_box_list_hover");
			$(".email"+num).addClass("reg_email_box_list_hover");
		}
	}
    if (key==40){//下
		var def=$("#default").val();
		var num=parseInt(def)+1;
		var allnum=$("#allnum").val();
		if(num<allnum){
			$("#default").val(num);
			$(".reg_email_box_list_hover").removeClass("reg_email_box_list_hover");
			$(".email"+num).addClass("reg_email_box_list_hover");
		}
	}
    if (key==13){//回车
		var type=$(".reg_email_box_list_hover").attr("aid");
		var email=$(".reg_email_box_list_hover").html();
		if(email){
			email=email.replace('<span class="eg_email_box_list_left">','');
			email=email.replace('</span>','');
			email=email.replace('<SPAN class=eg_email_box_list_left>','');
			email=email.replace('</SPAN>','');
			$("#event").val('13');
			var myreg = /^([a-zA-Z0-9\-]+[_|\_|\.]?)*[a-zA-Z0-9\-]+@([a-zA-Z0-9\-]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,4}$/;
			if(myreg.test(email)){
				$("#email"+type).val(email);
			}else{
				$("#email"+type).val('');
			}
			$(".reg_email_box").hide();
			setTimeout(function (){ $("#event").val('1');},1000);
		}
	}
}

$(function(){
	$('body').click(function(evt){
		if($(evt.target).parents("#defemail1").length==0 && evt.target.id != "defemail1") {
			$('#defemail1').hide();
		}
		if($(evt.target).parents("#defemail3").length==0 && evt.target.id != "defemail3") {
			$('#defemail3').hide();
		}
	});
	$("#email1").blur(function(){
		setTimeout("reg_checkAjax('email1')",300);
	});
	$("#email3").blur(function(){
		setTimeout("reg_checkAjax('email3')",300);
	});
})
document.onkeydown = keyDown;

//---邮箱获取后缀end--
function reg_checkAjax(id){
	var obj = $.trim($("#"+id).val());
	var msg; 
	if(id=="username1"){
		if(obj==""){
			//msg='请输入2至16位不包含特殊字符的用户名！';
			msg='用户名不能为空！';
			update_html(id,"0",msg); 
		}else if(obj.length<2||obj.length>16){
			msg='请输入2至16位不包含特殊字符的用户名！';
			update_html(id,"0",msg);
		}else{	
			$.post("index.php?m=register&c=ajaxreg",{username:obj},function(data){
				if(data==0){	
					msg='填写正确！';
					update_html(id,"1",msg);
				}else{
					if(data==1){
						msg="用户名已存在！";
					}else if(data==2){
						msg="用户名不得包含特殊字符！";
					}else if(data==3){
						msg="该用户名已被禁止注册！";
					} 
					update_html(id,"0",msg);
				}
			});	
		}
	}
	if(id=="password"){
	
		if(obj==""){
			 msg='密码不能为空！';
			 update_html(id,"0",msg);
		 }else if(obj.length<6 || obj.length>20 ){
			 msg='只能输入6至20位密码！';
			update_html(id,"0",msg);
		 }else{
			 msg='输入正确！';
			 update_html(id,"1",msg);
		 }
	}
	if(id=="passconfirm"){
		if(obj==""){
			 msg='确认密码不能为空！';
			 update_html(id,"0",msg);
		 }else if(obj.length<6 || obj.length>20 ){
			 msg='只能输入6至20位密码！';
			update_html(id,"0",msg);
		 }else{
			 var password = $('#password').val();
			 if(obj!=password){
				msg='两次输入密码不一致！';
				update_html(id,"0",msg);
			 }else{
				msg='输入正确！';
				update_html(id,"1",msg);
			 }			
		 }  
	}
	if(id=="email1"||id=="email3"){
		 //对电子邮件的验证
	    var myreg = /^([a-zA-Z0-9\-]+[_|\_|\.]?)*[a-zA-Z0-9\-]+@([a-zA-Z0-9\-]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,4}$/;
		if(obj==""){
			 msg='邮箱不能为空！';
			 update_html(id,"0",msg);
		 }else if(!myreg.test(obj)){
			msg="邮箱格式错误！";
			update_html(id,"0",msg);
	     }else{
	     	
			$.post("index.php?m=register&c=regemail",{email:obj},function(data){
				
				if(data==0){	
					msg='填写正确！';
					update_html(id,"1",msg); 
				}else{
					if(document.getElementById('written_off').style.display!='none'){
						return;
					}
						var data = eval('('+data+')');
						var msglayer = layer.open({
							type: 1,
							title: '邮箱已被占用',
							closeBtn: 1,
							border: [10, 0.3, '#000', true],
							area: ['550px', 'auto'],
							content: $("#written_off"),
							cancel:function(){
								window.location.reload();
							}
						});
						
						$("#email").val("");
						$("#zy_uid").val(data.uid);
						$("#zy_email").val(obj);
						
						$("#desc_toast").html("2. 解除邮箱与该账号的绑定，解除绑定后，您无法继续用该邮箱登录");
						
						if(data.usertype=='1'){
							$("#zy_type").html("该邮箱已被注册为个人账号");
							if(data.name){
								$("#zy_name").html("个人名称：<span class=reg_have_tip_tit_name>"+data.name.substr(0,1)+"**</span>");
							}
							
						}else if(data.usertype=='2'){
							$("#zy_type").html("该邮箱已被注册为企业账号");
							if(data.name){$("#zy_name").html("企业名称：<span class=reg_have_tip_tit_name>"+data.name+"</span>");
							}
							
						} 
						
					
					
 				}
			});	
	     	 
		}
	}
	
	if(id=="moblie" || id=="linkphone" || id=="usertel"){
		var reg= /^[1][3456789]\d{9}$/; //验证手机号码  
		if(obj==''){
			msg="手机号不能为空！";
			 update_html(id,"0",msg);
		}else if(!reg.test(obj)){
			msg="手机号码格式错误！";
			 update_html(id,"0",msg);
		 }else{
			$.post("index.php?m=register&c=regmoblie",{moblie:obj},function(data){
				
				if(data==0){	
					msg='填写正确！';
					update_html(id,"1",msg); 
				}else{
					if(data==2){
						msg="该手机号已被禁止使用！";
						update_html(id,"0",msg);
					} else{
						if(document.getElementById('written_off').style.display!='none'){
							return;
						}
						var data = eval('('+data+')');
						var msglayer = layer.open({
							type: 1,
							title: '手机号已被占用',
							closeBtn: 1,
							border: [10, 0.3, '#000', true],
							area: ['550px', 'auto'],
							content: $("#written_off"),
							cancel:function(){
								window.location.reload();
							}
						});
						$("#moblie").val("");
						$("#zy_uid").val(data.uid);
						$("#zy_mobile").val(obj);
						if(data.usertype=='1'){
							$("#zy_type").html("该手机号已被注册为个人账号");
							if(data.name){
								$("#zy_name").html("个人名称：<span class=reg_have_tip_tit_name>"+data.name.substr(0,1)+"**</span>");
							}
							
						}else if(data.usertype=='2'){
							$("#zy_type").html("该手机号已被注册为企业账号");
							if(data.name){
								$("#zy_name").html("企业名称：<span class=reg_have_tip_tit_name>"+data.name+"</span>");
							}
							
						} 
						
						
					}
					
 				}
			});	
		 }
	}
	if(id=="moblie_code"){
		 if(obj==""){
			msg="短信验证码不能为空！";
			 update_html(id,"0",msg);
		 }else{
			msg="输入成功！";
			update_html(id,"1",msg); 
		 }
	}
	if(id=="name"){
		 var rname = /^[\u4e00-\u9fa5]+(·[\u4e00-\u9fa5]+)*$/; 
		 if(obj==""){
			msg="真实姓名不能为空!";
			update_html(id,"0",msg);
			return false;
		 }else if(obj.length < 2){
			msg = "真实姓名应该多于1个字符！";
			return update_html(id,"0",msg);
		}else if(!rname.test(obj)){
			msg = "真实姓名格式不规范！"
			return update_html(id,"0",msg);
		}else{
			msg="输入成功！";
			update_html(id,"1",msg); 
		 }
	}
	if(id=="unit_name"){
		 if(obj==""){
			msg="公司名称不能为空！";
			update_html(id,"0",msg);
		 }else{
			 $.post("index.php?m=register&c=checkcomname",{unit_name:obj},function(data){
				if(data==0){	
					msg='输入成功！';
					update_html(id,"1",msg);
				}else {
					
					var msglayer = layer.open({
						type: 1,
						title: '注册提示',
						closeBtn: 1,
						border: [10, 0.3, '#000', true],
						area: ['600px', 'auto'],
						content: $("#like_company"),
						cancel:function(){
							layer.closeAll();
						}
					});
					
					$("#like_com_list").html(data);
					
				}
			});
		 }
	}
	if(id=="linkman"){
		var rnames = /^[\u4e00-\u9fa5]+(·[\u4e00-\u9fa5]+)*$/;
		 if(obj==""){
			msg="联系人不能为空！";
			 update_html(id,"0",msg);
		 }else if(obj.length < 2){
			msg = "真实姓名应该多于1个字符！";
			return update_html(id,"0",msg);
		}else if(!rnames.test(obj)){
			msg = "真实姓名格式不规范！"
			return update_html(id,"0",msg);
		}
		 else{
			msg="输入成功！";
			update_html(id,"1",msg);
		 }
	}
	if(id=="address"){
		 if(obj==""){
			msg="公司地址不能为空！";
			 update_html(id,"0",msg);
		 }else{
			msg="输入成功！";
			update_html(id,"1",msg);
		 }
	}
	if(id=="CheckCode"){
		if(obj==""){
			msg="请输入验证码！";
			 update_html(id,"0",msg);
		 }else{
			msg="输入成功！";
			update_html(id,"1",msg);
		 }
	}else if(id=="unlock"){
		if(obj=="0"){
			msg="请点击按钮进行验证！";
			 update_html(id,"0",msg);
		 }else{
			msg="完成验证！";
			update_html(id,"1",msg);
		 }
	}
}
function update_html(id,type,msg){
	$("#ajax_"+id).show();
	$("#ajax_"+id).html('<i class="reg_tips_icon"></i>'+msg); 
	if(type=="0"){  
		$("#ajax_"+id).attr("class","reg_tips reg_tips_red");
		$("#"+id).addClass("logoin_text_focus");
		$("#"+id).attr('date','0');
		return false;
	}else{ 
		$("#ajax_"+id).attr("class","reg_tips reg_tips_blue");
		$("#"+id).removeClass("logoin_text_focus");
		$("#"+id).attr('date','1');
	}
}
function showpw(id){
	if($("#showpw"+id).html()=="显示密码"){
		PasswordToText("password");
		$("#showpw"+id).html('隐藏密码');
	}else{
		TextToPassword("password");
		$("#showpw"+id).html('显示密码');
	}
}
function TextToPassword(name){
	var control=document.getElementById(name);
	var newpassword = document.createElement("input");
	newpassword.type="password";
	//newpassword.name=control.name;
	newpassword.id=control.id;
	newpassword.value=control.value;
	newpassword.setAttribute("class",control.getAttribute("class"));
	newpassword.setAttribute("className",control.getAttribute("className"));
	newpassword.setAttribute("onblur",control.getAttribute("onblur"));
	newpassword.setAttribute("onkeyup",control.getAttribute("onkeyup"));
	setTimeout('document.getElementById("'+control.id+'").focus()',200);
	$("#"+name).replaceWith(newpassword);
}
function PasswordToText(name){
	var control=document.getElementById(name);
	var newpassword = document.createElement("input");
	newpassword.type="text";
	//newpassword.name=control.name;
	newpassword.id=control.id;
	newpassword.value=control.value;
	newpassword.setAttribute("class",control.getAttribute("class"));
	newpassword.setAttribute("className",control.getAttribute("className"));
	newpassword.setAttribute("onblur",control.getAttribute("onblur"));
	newpassword.setAttribute("onkeyup",control.getAttribute("onkeyup"));
	$("#"+name).replaceWith(newpassword);
}
function showtip(id){
	$("#tip"+id).show();
}
function hidetip(id){
	$("#tip"+id).hide();
}
function sendmsg(img){
	reg_checkAjax("moblie");
	var date=$("#moblie").attr("date");
	var send=$("#send").val();

	var moblie = $("#moblie").val();
	if(!moblie){
		moblie = $("#usertel").val();
	}
	if(!moblie){
		moblie = $("#linkphone").val();
	}

	if(!moblie){
		layer.msg('手机不能为空！', 2, 8);return false;
	} 

	var geetest_challenge='';
	var geetest_validate='';
	var geetest_seccode = '';
	var code = '';
	if(code_kind==1){
		if($("#CheckCode").length>0){
			code=$.trim($("#CheckCode").val());  
			if(!code){
				layer.msg('图片验证码不能为空！', 2, 8);return false;
			}	
	    } 
	}else if(code_kind==3){
		geetest_challenge = $('input[name="geetest_challenge"]').val();
		geetest_validate = $('input[name="geetest_validate"]').val();
		geetest_seccode = $('input[name="geetest_seccode"]').val();
		if(geetest_challenge =='' || geetest_validate=='' || geetest_seccode==''){

			$("#popup-submit").trigger("click");
			layer.msg('请点击按钮进行验证！', 2, 8);return false;
			return false;
		}
	}
	if(send>0){ 
		layer.msg('请不要频繁重复发送！', 2, 8);return false;  
	}

	date = 1;
	if(date==1 && send==0){
		layer.load('执行中，请稍候...',0);
		$.post(weburl+"/index.php?m=ajax&c=regcode",{
			moblie:moblie,
				code:code,
				geetest_challenge:geetest_challenge,
				geetest_validate:geetest_validate,
				geetest_seccode:geetest_seccode
				},function(data){ 
			layer.closeAll();
			if(data==0){
				layer.msg('手机不能为空！', 2, 8,function(){
					if(code_kind==1){
						checkCode(img);
					}else if(code_kind==3){
						$("#popup-submit").trigger("click");
					}
				});return false; 
			}else if(data==1){
				layer.msg('同一手机号一天发送次数已超！', 2, 8,function(){
					if(code_kind==1){
						checkCode(img);
					}else if(code_kind==3){
						$("#popup-submit").trigger("click");
					}
				});
			}else if(data==2){
				layer.msg('同一IP一天发送次数已超！', 2, 8,function(){
					if(code_kind==1){
						checkCode(img);
					}else if(code_kind==3){
						$("#popup-submit").trigger("click");
					}
				});
			}else if(data==3){
				layer.msg('短信还没有配置，请联系管理员！', 2, 8,function(){
					if(code_kind==1){
						checkCode(img);
					}else if(code_kind==3){
						$("#popup-submit").trigger("click");
					}
				});return false; 
			}else if(data==4){
				layer.msg('请不要频繁重复发送！', 2, 8,function(){
					if(code_kind==1){
						checkCode(img);
					}else if(code_kind==3){
						$("#popup-submit").trigger("click");
					}
				});return false; 
			}else if(data==5){
				layer.msg('图片验证码错误！', 2, 8,function(){checkCode(img);});return false; 
			}else if(data==6){
				layer.msg('请点击按钮进行验证！', 2, 8,function(){
					if(code_kind==1){
						checkCode(img);
					}else if(code_kind==3){
						$("#popup-submit").trigger("click");
					}
				
				});return false; 
				
			}else if(data=="发送成功!"){
				sendtime("121"); 
			}else{
				layer.msg(data, 2, 8,function(){
					if(code_kind==1){
						checkCode(img);
					}else if(code_kind==3){
						$("#popup-submit").trigger("click");
					}
				});return false; 
			}
		})
	}
}
function sendtime(i){
	i--;
	if(i==-1){
		$("#time").html("重新获取");
		$("#send").val(0)
	}else{
		$("#send").val(1)
		$("#time").html(i+"秒");
		setTimeout("sendtime("+i+");",1000);
	}
}
function exitsdate(id){
	if(document.getElementById(id)){
		if($('#'+id).attr('date')!='1'){
			return false;
		}else{
			return true;
		}
	}else{
		return true;
	}
}
function check_user(id,img){
	var email;
	var moblie;
	var moblie_code;
	var authcode;
	var unit_name;
	var address;
	var linkman;
	var name;
	var username;
	var usertype=$("#usertype").val();	
	var arrayObj = new Array();
	var password;
	var geetest_challenge;
	var geetest_validate;
	var geetest_seccode;
	
	if(exitsid("moblie")){
 		moblie = $.trim($('#moblie').val());
		arrayObj.push('moblie');
	}

	if(exitsid("email1")){
 		email = $.trim($('#email1').val());
		arrayObj.push('email1');
	}

	reg_checkAjax("password");
	password = $.trim($("#password").val());
	arrayObj.push('password');

	if(exitsid("passconfirm")){
		reg_checkAjax("passconfirm");
		arrayObj.push('passconfirm');
	}

	if(usertype=='1'){
		if(exitsid("name")){
			reg_checkAjax("name");
			name = $.trim($('#name').val());
			arrayObj.push('name');
		}
	}
	else if(usertype == 2){
		if(exitsid("unit_name")){
			reg_checkAjax("unit_name");
			unit_name = $.trim($('#unit_name').val());
			arrayObj.push('linkman');
		}
		if(exitsid("address")){
			reg_checkAjax("address");
			address = $.trim($('#address').val());
			arrayObj.push('address');
		}
		if(exitsid("linkman")){
			reg_checkAjax("linkman");
			linkman = $.trim($('#linkman').val());
			arrayObj.push('linkman');
		}
	}
	if(exitsid("moblie")){
		reg_checkAjax("moblie");
		moblie = $.trim($('#moblie').val());
		arrayObj.push('moblie');
	}
	if(exitsid("email1")){
		reg_checkAjax("email1");
		email = $.trim($('#email1').val());
		arrayObj.push('email1');
	}
	if(id=="1"){
		username=$.trim($("#username1").val());
		arrayObj.push('username1');
		reg_checkAjax("username1");
	}else if(id=="2"){
		//moblie = $.trim($('#moblie').val());
		var username=moblie;
		//arrayObj.push('moblie');
	}else if(id=="3"){
		email=$.trim($("#email3").val());
		var username=email;
		arrayObj.push('email3');
	}

	if(exitsid("moblie_code")){
		reg_checkAjax("moblie_code");
		arrayObj.push('moblie_code');
		moblie_code=$.trim($("#moblie_code").val());
	}

	if(exitsid("CheckCode")){
			reg_checkAjax("CheckCode");
			arrayObj.push('CheckCode');
	}
	
	for(i=0;i<arrayObj.length;i++){
		if(!exitsdate(arrayObj[i])){
			return false;
		}
	}

	var codesear=new RegExp('注册会员');
	if(codesear.test(code_web) && !exitsid("moblie_code") ){
		if(code_kind==1){
			authcode=$("#CheckCode").val();
			if(authcode==''){
				return false;
			}
		}else if(code_kind==3){
			geetest_challenge = $('input[name="geetest_challenge"]').val();
			geetest_validate = $('input[name="geetest_validate"]').val();
			geetest_seccode = $('input[name="geetest_seccode"]').val();
			if(geetest_challenge =='' || geetest_validate=='' || geetest_seccode==''){
				$("#popup-submit").trigger("click");
				layer.msg('请点击按钮进行验证！', 2, 8);return false;
			}
		}
	}
	
	if($("#xieyi"+id).attr("checked")!='checked'){  
		layer.msg('您必须同意注册协议才能成为本站会员！', 2, 8);return false;  
	}else{
		var loadi = layer.load('正在注册……',0);
		$.post(weburl+"/index.php?m=register&c=regsave",{
				username:username,
				password:password,
				email:email,
				moblie:moblie,
				moblie_code:moblie_code,
				unit_name:unit_name,
				address:address,
				authcode:authcode,
				usertype:usertype,
				name:name,linkman:linkman,
				codeid:id,
				geetest_challenge:geetest_challenge,
				geetest_validate:geetest_validate,
				geetest_seccode:geetest_seccode
			},function(data){

			layer.close(loadi);
			var data=eval('('+data+')');
			var status=data.status; 
			var msg=data.msg; 
			if(usertype==1){
				if(status==1){
					window.location.href=weburl+"/member/index.php?c=expect";//注册成功
				}else if(status==7){
					layer.msg(msg, 2,status,function(){window.location.href ="index.php";});return false; 
				}else if(status==6){
					window.location.href ="index.php?m=register&c=ok&type=1";
				}else{  
					layer.msg(msg, 2,status,function(){
						if(code_kind==1){
							checkCode(img);
						}else if(code_kind==3){
							$("#popup-submit").trigger("click");
						}
					});return false;
				}
			}else{
				if(status==1){
					//注册成功,后台企业设置开启不审核直接跳转到会员中心
					layer.msg(msg, 2, 9,function(){window.location.href=weburl+"/member/";}); 
				}else if(status==8){
					layer.msg(msg, 2,status,function(){
						if(code_kind==1){
							checkCode(img);
						}else if(code_kind==3){
							$("#popup-submit").trigger("click");
						}
					});return false;
				}else if(status==7){
					//注册成功,后台企业设置开启审核跳转到待审核页面
					window.location.href ="index.php?m=register&c=ok&type=1";
				}
			}
		});
	}
}
function check_login(url,img){
	var act_login=$("#act_login").val();
	var username='';
	var password='';
	if (act_login==0) {//普通账号登录
		username=$("#username").val();
		if(username=="" || username=="用户名"|| username=="邮箱/手机号/用户名"){ 
			$("#show_name").show();
			$("#username").focus(
			    function(){
			       $("#show_name").hide();
			    }
			);
			return false;
		}else{
		    $("#show_name").hide();
		}
		password=$("#password").val();
		if(password==""){
			$("#show_pass").show();
			$("#password").focus(
			    function(){
				    $("#show_pass").hide();
				}
			);
			return false;
		}else{
		    $("#show_pass").hide();
		}


	}else{//手机验证码登录
		
		username = $('#usermoblie').val();
		checkmoblie(username);
		password= $('#dynamiccode').val();
		
		if(password=="" || password=="短信动态码"){
			
			$("#show_dynamiccode").show();
			$("#dynamiccode").focus(
			    function(){
				    $("#show_dynamiccode").hide();
				}
			);
			return false;
		}else{
		    $("#show_dynamiccode").hide();
		}
	}

	//验证码验证
	var geetest_challenge='';
	var geetest_validate='';
	var geetest_seccode = '';
	var authcode = '';
	var codesear=new RegExp('前台登录');
	if(codesear.test(code_web) && act_login==0){
		if(code_kind==1){//数字验证
			if(exitsid("txt_CheckCode")){
				authcode=$("#txt_CheckCode").val();
				if(authcode==""||authcode=="验证码"){
					$("#show_code").show();
					$("#txt_CheckCode").focus(
						function(){
							$("#show_code").hide();
						}
					);
					return false;
				}else{
					$("#show_code").hide();
				}
			}
		}else if(code_kind==3){//极验验证
			geetest_challenge = $('input[name="geetest_challenge"]').val();
			geetest_validate = $('input[name="geetest_validate"]').val();
			geetest_seccode = $('input[name="geetest_seccode"]').val();
			if(geetest_challenge =='' || geetest_validate=='' || geetest_seccode==''){
				$("#popup-submit").trigger("click");
				layer.msg('请点击按钮进行验证！', 2, 8);return false;
			}
		}
	}
	//是否记住登录状态
	if($("input[name=loginname]").attr("checked")=='checked'){
		var loginname=7;
	}else{
		var loginname=0;
	}
	var path=$("#path").val();
	var loadIndex = layer.load('登录中,请稍候...');
	$.post(url,{act_login:act_login,username:username,password:password,path:path,loginname:loginname,authcode:authcode,geetest_challenge:geetest_challenge,geetest_validate:geetest_validate,geetest_seccode:geetest_seccode},function(data){ 
		layer.close(loadIndex);
		
		var jsonObject = eval("(" + data + ")"); 
		if(jsonObject.error == '3'){//UC登录激活
			$('#uclogin').html(jsonObject.msg);
			setTimeout("window.location.href='"+jsonObject.url+"';",500); 
		}else if(jsonObject.error == '2'){//UC登录成功 
			$('#uclogin').html(jsonObject.msg); 
			setTimeout("window.location.href='"+jsonObject.url+"';",500); 
		}else if(jsonObject.error == '1'){//正常登录成功 			
			window.location.href=jsonObject.url; window.event.returnValue = false;return false;
		}else if(jsonObject.error == '0'){//登录失败或需要审核等提示 
			layer.msg(jsonObject.msg, 2, 8,function(){ 
				if(jsonObject.url){
					window.location.href=jsonObject.url; 
					window.event.returnValue = false;return false;
				}else{
					if(code_kind==1){
						checkCode(img);
					}else if(code_kind==3){
						$("#popup-submit").trigger("click");
					
					}
				}
			}); 
			
		}
	});
	$("#txt_CheckCode").val('');
}
function checktype(id){
	$(".login_box_tit>li").attr('class','');
	if(id=='login_cur'){
		$("#lilogin_fast").addClass("login_fast");
	}else{
		$("#lilogin_cur").addClass("login_cur");
	}
	$(".login_box_cont>.lgoin_box_cot").hide();
	$("#"+id).show(); 
}
function checkmoblie(moblie){
	if(!testMb(moblie)){ 
		$("#show_mobile").show();
		$("#usermobile").focus(
		    function(){
		       $("#show_mobile").hide();
		    }
		);
		return false;
	}else{
	    $("#show_mobile").hide();
	    return true;
	}
}

var Timer;
var smsTimer_time = 90;		//倒数 90
var smsTimer_flag = 90;		//倒数 90
var smsTime_speed = 1000;	//速度 1秒钟
//发送手机短信
function send_msg(url){
	var moblie = $('#usermoblie').val();
	checkmoblie(moblie);
	var returntype = 1;
	$.ajax({ 
		async: false, 
		type : "POST", 
		url : weburl+"/index.php?m=register&c=regmoblie", 
		dataType : 'text', 
		data:{'moblie':moblie},
		success : function(data) {
			if(data == 0){
				returntype = 0;
			}
		} 
	});
	if(returntype == 0){
		layer.msg("手机号码不存在！",2,8);return false;
	}
	var geetest_challenge='';
	var geetest_validate='';
	var geetest_seccode = '';
	var code = '';
	var showCodeCheck = code_web.indexOf('注册会员');
	if(code_kind==1 && showCodeCheck >= 0){
		if($("#CheckCode").length>0){
			code=$.trim($("#CheckCode").val());  
			if(!code){
				layer.msg('图片验证码不能为空！', 2, 8);return false;
			}	
	    } 
	}else if(code_kind==3 && showCodeCheck >= 0){
		geetest_challenge = $('input[name="geetest_challenge"]').val();
		geetest_validate = $('input[name="geetest_validate"]').val();
		geetest_seccode = $('input[name="geetest_seccode"]').val();
		if(geetest_challenge =='' || geetest_validate=='' || geetest_seccode==''){
			$("#popup-submit").trigger("click");
			layer.msg('请点击按钮进行验证！', 2, 8);return false;
			return false;
		}
	}
	
	if(smsTimer_time==smsTimer_flag){
		
		$.post(url,{moblie:moblie},function(data){
			var jsonObject = eval("(" + data + ")"); 
			if(jsonObject.error !== 1){
				clearInterval(Timer);
				layer.msg(jsonObject.msg, 2, 8,function(){ 
					if(jsonObject.url){
						window.location.href=jsonObject.url; 
						window.event.returnValue = false;return false;
					}
				});
			}else{
				Timer = setInterval("smsTimer($('#send_msg_tip'))", smsTime_speed);

				layer.msg('短信动态码发送成功，请注意查收！', 2, 9);return false;
			}
			
		})
	}else {
		layer.msg('请勿重复发送！', 2, 8);return false;
	}
}
//手机号码校验
function testMb(mbNo){
	var reg= /^[1][3456789]\d{9}$/;	//验证手机号码   
	return reg.test(mbNo);
}
//倒计时
function smsTimer(obj){
	if (smsTimer_flag > 0) {
		$(obj).html('重新发送('+smsTimer_flag+'s)');
		$(obj).attr({'style':'background:#909394;'});
		smsTimer_flag--;
	}else{
		$(obj).html('重新发送');
		$(obj).attr({'style':'background:#06C;'});
		smsTimer_flag = smsTimer_time;
		clearInterval(Timer);
	}
}

//发送手机短信
function send_msg2(url){
	var moblie = $('#usermoblie').val();
	if(!checkmoblie(moblie)){
		return false;
	}

	var geetest_challenge='';
	var geetest_validate='';
	var geetest_seccode = '';
	var code = '';
	
	var showCodeCheck = code_web.indexOf('前台登录');
	if(showCodeCheck >= 0){		
		if(code_kind==1){
			if($("#txt_CheckCode").length>0){

				code=$.trim($("#txt_CheckCode").val());  
				if(!code || code == '验证码'){
					layer.msg('图片验证码不能为空！', 2, 8);return false;
				}	
	    } 
		}else if(code_kind==3){
			geetest_challenge = $('input[name="geetest_challenge"]').val();
			geetest_validate = $('input[name="geetest_validate"]').val();
			geetest_seccode = $('input[name="geetest_seccode"]').val();
			if(geetest_challenge =='' || geetest_validate=='' || geetest_seccode==''){

				$("#popup-submit").trigger("click");
				layer.msg('请点击按钮进行验证！', 2, 8);return false;
				return false;
			}
		}
	}

	var returntype = 1;
	$.ajax({ 
		async: false, 
		type : "POST", 
		url : weburl+"/index.php?m=register&c=regmoblie", 
		dataType : 'text', 
		data:{
			'moblie':moblie
		},
		success : function(data) {
			if(data == 0){
				returntype = 0;
			}
		} 
	});
	if(returntype == 0){
		layer.msg("手机号码不存在！",2,8);return false;
	}
	
	if(smsTimer_time==smsTimer_flag){
		
		$.post(url,{moblie:moblie,code:code,
			geetest_challenge : geetest_challenge,
			geetest_validate : geetest_validate,
			geetest_seccode : geetest_seccode
		},function(data){
			var jsonObject = eval("(" + data + ")"); 
			if(jsonObject.error !== 1){
				clearInterval(Timer);
				layer.msg(jsonObject.msg, 2, 8,function(){ 
					if(jsonObject.url){
						window.location.href=jsonObject.url; 
						window.event.returnValue = false;return false;
					}
				});
			}else{
				Timer = setInterval("smsTimer($('#send_msg_tip'))", smsTime_speed);
				
				layer.msg('短信动态码发送成功，请注意查收！', 2, 9);return false;
			
			}
			
		})
	}else {
		layer.msg('请勿重复发送！', 2, 8);return false;
	}
}

//快捷登录绑定
function binduser(url){
	
	var username = $('#username').val();
	var password = $('#password').val();
	if(username && password){
		$.post(url,{username:username,password:password},function(data){
			var info = eval('('+data+')');
			if(info.url){
					layer.msg('绑定成功！', 2, 9,function(){window.location.href=info.url;}); 
			}else if(info.msg){
			
				layer.msg(info.msg, 2, 8);return false;
			}
		});
	}else{
	
		layer.msg('请输入需要绑定的账户、密码！', 2, 8);return false;
	}

}

function CheckPW(){
	
	$.layer({
		type: 1,
		title: '验证身份',
		offset: [($(window).height() - 200) / 2 + 'px', ''],
		closeBtn: [0, true],
		border: [10, 0.3, '#000', true],
		area: ['350px', '220px'],
		page: {
			dom: "#postpw"
		}
	});

}

function post_pass(img) {
	var zyuid = $("#zy_uid").val();
	var mobile = $("#zy_mobile").val();
	var email = $("#zy_email").val();
	var pw = $("#pw").val();
	var code = $("#code").val();
	
	if(zyuid==""){
		layer.msg('该用户不存在', 2, 8);
		return false;
	}
	if(pw == "") {
		layer.msg('请输入密码', 2, 8);
		return false;
	}
	if(code == "") {
		layer.msg('请输入验证码', 2, 8);
		return false;
	}
	$.post("index.php?m=register&c=writtenOff", {
		zyuid: zyuid,
		mobile: mobile,
		email: email,
		pw: pw,
		code: code
	}, function(data) {
		
		if(data == 3) {
			layer.msg('验证码错误！', 2, 8);
			checkCode(img);
			return false;
		} else if(data == 2) {
			layer.msg('密码错误！', 2, 8);
			return false;
		} else {
			layer.closeAll();
			
			layer.msg('解绑成功！', 2, 9,function(){
				window.location.reload();
			});
			
 		}
	})
}


function CloseToast(){
	layer.closeAll();
}
