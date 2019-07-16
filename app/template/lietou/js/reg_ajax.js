function exitsid(id){
	if(document.getElementById(id)){
		return true;
	}else{
		return false;
	}
}

function clickpw(){
	$("#pw").hide();
	$("#password").show();
	$("#password").focus();
}
//JS -- 登录注册 
function reg_checkAjax(id){
	var obj = $("#"+id).val();
	var msg;
	if(id=="username"){		
		if(obj==""){			
			msg='用户名不能为空！';
			update_html(id,"0",msg); 
			return false;
		}else if(obj.length<2||obj.length>16){
			msg = "用户名应该在2-16个字符！";
			return update_html(id,"0",msg);
		}else{	
			$.post(weburl+"/index.php?m=register&c=ajaxreg",{username:obj},function(data){
				if(data==0){	
					msg = "可以使用！";
					return update_html(id,"1",msg);
				}else if(data==2){
					msg = "该用户名禁止使用，请重新输入！";
					return update_html(id,"0",msg);
				}else{
					msg = "用户名已存在！";
					return update_html(id,"0",msg);
				}
			});	
		}
	}
	
	if(id=="regpassword")
	{
		if(obj==""){
			 msg='密码不能为空！';
			 update_html(id,"0",msg);
			 return false;
		 }else if(obj.length<6 || obj.length>20){
			 msg = "密码应该在6-20个字符！";
			 return update_html(id,"0",msg);
		 }else{
			msg = "填写正确！";
			 return update_html(id,"1",msg);
		 }   	
		
	}
	if(id=="passconfirm")
	{
		var obj2 = $("#regpassword").val();
		 if(obj==""){
			  msg = "重复密码不能为空！";
			 return update_html(id,"0",msg);
		 }else if(obj2!=obj){
			 msg = "重复密码不一致！";
			 return update_html(id,"0",msg);
		}else{
			msg = "填写正确！";
			return update_html(id,"1",msg);
		 }   	
		
	}
	if(id=="moblie"){
		var reg= /^[1][3456789]\d{9}$/; //验证手机号码  
		if(obj==''){
			msg="手机号不能为空！";
			 update_html(id,"0",msg);
			 return false;
		}else if(!reg.test(obj)){
			msg="手机号码格式错误！";
			 update_html(id,"0",msg);
			 return false;
		 }else{
			$.post(weburl+"/index.php?m=register&c=regmoblie",{moblie:obj},function(data){
				if(data==0){	
					msg='填写正确！';
					update_html(id,"1",msg);
					return true;
				}else{					
					msg="号码已存在！";					
					update_html(id,"0",msg);
					return false;
				}
			});	
		 }
	}
	if(id=="email1"){
		 //对电子邮件的验证
         var myreg = /^([a-zA-Z0-9\-]+[_|\_|\.]?)*[a-zA-Z0-9\-]+@([a-zA-Z0-9\-]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
		 if(obj==""){
			 msg='邮箱不能为空！';
			 update_html(id,"0",msg);
			 return false;
		 }else if(!myreg.test(obj)){
			 msg = "邮箱格式不正确！"
			 return update_html(id,"0",msg);
           }else{
			$.post(weburl+"/index.php?m=register&c=ajaxreg",{email:obj},function(data){
				if(data==0){
					 msg = "填写正确！"
					 return update_html(id,"1",msg);
				}else{
					 msg = "邮箱已存在！"
					return update_html(id,"0",msg);
				}
			});
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
	}

	if(id == "moblie_code"){
		if(obj==""){
			msg="请输入短信验证码！";
			update_html(id,"0",msg);
		 }else{
			msg="输入成功！";
			update_html(id,"1",msg);
		 }
	}

	if(id=="realname"){		
		if(obj==""){			
			msg='真实姓名不能为空！';
			update_html(id,"0",msg); 
			return false;
		}else if(obj.length<2){
			msg = "真实姓名应该多于1个字符！";
			return update_html(id,"0",msg);
		}else{	
			$.post(weburl+"/index.php?m=register&c=ajaxreg",{realname:obj},function(data){
				if(data==0){	
					msg = "可以使用！";
					return update_html(id,"1",msg);
				}else{
					msg = "请输入真实姓名！";
					return update_html(id,"0",msg);
				}
			});	
		}
	}
}
function update_html(id,type,msg){
	if(type=="1"){  
		$("#ajax_"+id).html('<i class="reg_tips_icon"></i>'+msg); 
		$("#ajax_"+id).attr('class','reg_tips reg_tips_blue false');  
	}else{  
		$("#ajax_"+id).html('<i class="reg_tips_icon"></i>'+msg); 
		$("#ajax_"+id).attr('class','reg_tips reg_tips_red false');  
	} 
	$("#ajax_"+id).show();
	$("#"+id).attr('date',type);
}
function checkform(img){
	var username=$.trim($("#username").val());
	var realname=$.trim($("#realname").val());
	var passconfirm=$.trim($("#passconfirm").val());
	var password=$.trim($("#regpassword").val());
	var email=$.trim($("#email1").val());
	var moblie=$.trim($("#moblie").val());
	var authcode=$.trim($("#CheckCode").val());
    var geetest_challenge;
	var geetest_validate;
	var geetest_seccode;

	var moblie_code = '';

    reg_checkAjax("username");
	reg_checkAjax("regpassword");
	reg_checkAjax("passconfirm");
	reg_checkAjax("email1");
	reg_checkAjax("moblie");
	
	if(exitsid("CheckCode")){
		reg_checkAjax("CheckCode");
	}

	if(exitsid("moblie_code")){
		reg_checkAjax("moblie_code");
	}

	if(exitsid("realname")){
		reg_checkAjax("realname");
	}
		
	if($("#username").attr('date')!="1"||$("#regpassword").attr('date')!="1"||$("#passconfirm").attr('date')!="1"||$("#email1").attr('date')!="1"||$("#moblie").attr('date')!="1"){
		return false; 
	}else{
		var codesear=new RegExp('注册会员');
	　　if(codesear.test(code_web)){
		　　if(code_kind==1){

				reg_checkAjax("CheckCode");
				if($("#CheckCode").attr('date')!="1"){
					return false;
				}

			}else if(code_kind==3){

				geetest_challenge = $('input[name="geetest_challenge"]').val();
				geetest_validate = $('input[name="geetest_validate"]').val();
				geetest_seccode = $('input[name="geetest_seccode"]').val();
				if(geetest_challenge =='' || geetest_validate=='' || geetest_seccode==''){
					
					$("#popup-submit").trigger("click");
					layer.msg('请点击按钮进行验证！', 2, 8);
					return false;
				}
			}
	　　}
		if($("#xieyi").attr("checked")!='checked'){
			layer.msg('您必须同意注册协议才能成为本站会员！', 2, 8);return false;  
		}else{

			if(exitsid("moblie_code")){
				reg_checkAjax("moblie_code");
				moblie_code = $.trim($("#moblie_code").val());
			}

			var loadi = layer.load('正在注册……',0);
			$.post(weburl+"/lietou/index.php?c=register",{
				username:username,
				realname:realname,
				password:password,
				passconfirm:passconfirm,
				email:email,
				moblie:moblie,
				moblie_code:moblie_code,
				authcode:authcode,
				geetest_challenge:geetest_challenge,
				geetest_validate:geetest_validate,
				geetest_seccode:geetest_seccode
			},function(data){ 
				layer.close(loadi);
				var data=eval('('+data+')');
				var status=data.status;
				var msg=data.msg;
				if(status==1){
					window.location.href=weburl+"/member/index.php";
				}else if(status==0){
					window.location.href =weburl+"/index.php?m=register&c=ok&type=1";
				}else if(status==8){
					if(code_kind==1){
						checkCode(img);
					}else if(code_kind==3){
						$("#popup-submit").trigger("click");
					}
					layer.msg(msg, 2, 8);
				}
			});
		}
	}
}

$(function(){			
	$(".tips_class").click(function(){
		if($('#username').attr('date')=='1'||$('#password').attr('date')=='1'){
			layer.closeTips();
		} 
	});
}); 
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
	var myreg = /^([a-zA-Z0-9\-]+[_|\_|\.]?)*[a-zA-Z0-9\-]+@([a-zA-Z0-9\-]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
	if(myreg.test(email)){
		$("#email"+type).val(email);
	}else{
		$("#email"+type).val('');
	}
	$("#email"+type).val(email);
	$(".reg_email_box").hide();
	reg_checkAjax("email"+type);
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
			var myreg = /^([a-zA-Z0-9\-]+[_|\_|\.]?)*[a-zA-Z0-9\-]+@([a-zA-Z0-9\-]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
			if(myreg.test(email)){
				$("#email"+type).val(email);
			}else{
				$("#email"+type).val('');
			}
			$(".reg_email_box").hide();
			reg_checkAjax("email"+type);
			setTimeout(function (){ $("#event").val('1');},1000);
		}
	}
}
$(function(){
	$('body').click(function(evt){
		if($(evt.target).parents("#defemail1").length==0 && evt.target.id != "defemail1") {
			$('#defemail1').hide();
		}
	});
	$("#email1").blur(function(){
		setTimeout("reg_checkAjax('email1')",300);
	});
})
document.onkeydown = keyDown;
//---邮箱获取后缀end--

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

function sendmsg(img){
	reg_checkAjax("moblie");

	var moblie = $("#moblie").val();
	
	var send=$("#send").val();

	var geetest_challenge='';
	var geetest_validate='';
	var geetest_seccode = '';
	var code = '';
	
	if(!moblie){
		layer.msg('手机不能为空！', 2, 8);return false;
	} 

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