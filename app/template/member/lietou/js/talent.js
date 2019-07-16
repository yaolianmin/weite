function getshow(divid,title,linktel,id){
	$("#linktel").val(linktel);
	$("#telid").val(id);
	
	$.layer({
		type : 1,
		title :title,
		closeBtn : [0 , true],
		border : [10 , 0.3 , '#000', true],
		area : ['500px','auto'],
		page : {dom :"#"+divid}
	});
}
function sendmoblie(){
	if($("#send").val()=="1"){
		return false;
	}
	var moblie=$("#linktel").val();
	var reg= /^[1][3456789]\d{9}$/; //验证手机号码
	if(moblie==''){
		layer.msg('手机号不能为空！',2,8);return false;
	}else if(!reg.test(moblie)){
		layer.msg('手机号码格式错误！',2,8);return false;
	}
	var i=layer.load('执行中，请稍候...',0);
	$.ajaxSetup({cache:false});
	$.post(weburl+"/member/index.php?m=ajax&c=mobliecert", {str:moblie},function(data) {
		layer.close(i);
		if(data=="发送成功!"){ 
			layer.msg('发送成功！',2,9,function(){send(121);}); 
		}else if(data==1){
			layer.msg('同一手机号一天发送次数已超！', 2, 8);
		}else if(data==2){
			layer.msg('同一IP一天发送次数已超！', 2, 8);
		}else if(data==3){
			layer.msg('短信通知已关闭，请联系管理员！',2,8);
		}else if(data==4){
			layer.msg('还没有配置短信，请联系管理员！',2,8);
		}else if(data==5){
			layer.msg('请不要重复发送！',2,8);
		}else{
			layer.msg(data,2,8);
		}
	})
}
function send(i){
	i--;
	if(i==-1){
		$("#time").html("重新获取");
		$("#send").val(0)
	}else{
		$("#send").val(1)
		$("#time").html(i+"秒");
		setTimeout("send("+i+");",1000);
	}
}
function telstatus(){
	var id = $('#telid').val();
	var linktel = $('#linktel').val();
	
	if(linktel==""){ 
		layer.msg('请输入手机号码！',2,8,function(){getshow('linktel','绑定手机号码',linktel);});return false;
	}
	var code=$("#moblie_code").val();
	if(code==""){ 
		layer.msg('请输入短信验证码！',2,8,function(){getshow('linktel','绑定手机号码',linktel);});return false;
	}
	
	var i=layer.load('执行中，请稍候...',0);
	$.ajaxSetup({cache:false});
	$.post("index.php?c=talent&act=telstatus",{id:id,linktel:linktel,code:code},function(data){
		layer.close(i);
		data = eval('('+data+')');
		if(data.error=='1'){
			
			layer.msg('授权认证成功！',2,9,function(){location.reload();}); 
			
		}else{
			layer.msg(data.msg,2,8); 
		}
	})
}

