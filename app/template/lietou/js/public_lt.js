$(document).ready(function(){
	var search=$("#search").val(); 
	if(search!="输入职位关键词：如销售总监等"){
		$("input[id='search']").css("color","#000"); 
	} 
	var b_class=$("#get_open").attr("class");
	if(b_class==""){
		$("#expire").val("1");
	}
}); 
function myself() { layer.msg('本人发布，无法推荐！', 2, 8); return false; }
function checkjobform_msg(){
	noplaceholder('content');
    var content = $.trim($("#content").val());
    if (content == ""){
        layer.msg('留言内容不能为空！', 2, 8); return false;
    }
	noplaceholder('msg_CheckCode');
	var authcode=$("#msg_CheckCode").val();
	if(authcode==''){
		layer.msg('验证码不能为空！', 2, 8);return false;
	} 
}
function checkform_msg(){
	var name=$.trim($("#uname").val());
	if(name==""){
		layer.msg('姓名不能为空！', 2, 8);return false; 
	}
	if($("input[name='sex']:checked").val()==''){
		layer.msg('请选择性别！', 2, 8);return false;
	}
	if($.trim($("#birthday").val())==''){
		layer.msg('请选择出生年月！', 2, 8);return false;
	}
	if($.trim($("#edu").val())==''){
		layer.msg('请选择最高学历！', 2, 8);return false;
	}
	if($.trim($("#exp").val())==''){
		layer.msg('请选择工作经验！', 2, 8);return false;
	}
	var phone=$.trim($("#telphone").val());
	var reg= /^[1][3456789]\d{9}$/; //验证手机号码  
	if(phone==""){
		layer.msg('请填写手机号码！', 2, 8);return false; 
	}else if(!reg.test(phone)){
		layer.msg('手机格式不正确！', 2, 8);return false; 
	}
	var email=$.trim($("#email").val());
	var myreg = /^([a-zA-Z0-9\-]+[_|\_|\.]?)*[a-zA-Z0-9\-]+@([a-zA-Z0-9\-]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;  
	if(email!="" && !myreg.test(email)){
		layer.msg('邮箱格式不正确！', 2, 8);return false; 
	}	
	if($.trim($("#hy").val())==''){
		layer.msg('请选择从事行业！', 2, 8);return false;
	}
	if($.trim($("#job").val())==''){
		layer.msg('请选择期望职位！', 2, 8);return false;
	}
	if($.trim($("#city").val())==''){
		layer.msg('请选择期望城市！', 2, 8);return false;
	}	
	var min = $.trim($("#minsalary").val());
	var max = $.trim($("#maxsalary").val());
	if(min==''){
		layer.msg('请填写期望薪资！', 2, 8);return false;
	}
	if(max){
		if(parseInt(max)<=parseInt(min)){
			layer.msg('最高工资必须大于最低工资！', 2, 8);return false;
		}
	}
	if($.trim($("#type").val())==''){
		layer.msg('请选择工作性质！', 2, 8);return false;
	}
	if($.trim($("#report").val())==''){
		layer.msg('请选择到岗时间！', 2, 8);return false;
	}
	var content=$.trim($("#content").val());
	if(content==""){
		layer.msg('推荐描述不能为空！', 2, 8);return false; 
	}
}
function lchecklogin(url,img){
	var username=$("#username").val();
	var password=$("#password").val();
	if(username==""||username=="用户名"|| username=="请输入用户名"){ 
		layer.msg('用户名不能为空！', 2, 8);return false;  
	}
	if(password==''){
		layer.msg('密码不能为空！', 2, 8);return false;
	}
	var authcode;
	var geetest_challenge;
	var geetest_validate;
	var geetest_seccode;
	var codesear=new RegExp('前台登录');
	if(codesear.test(code_web)){
		if(code_kind==1){
			authcode=$("#txt_CheckCode").val();
			if(authcode==""|| authcode=="验证码"){
				layer.msg('验证码不能为空！', 2,8);return false; 
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

	$.post(url,{
			username:username,
			password:password,
			authcode:authcode,
			geetest_challenge:geetest_challenge,
			geetest_validate:geetest_validate,
			geetest_seccode:geetest_seccode
		},function(data){
			var data=eval('('+data+')');

			if(data.url){

				window.location.href=data.url;

			}else{

				if(code_kind==1){
					checkCode(img);
				}else if(code_kind == 3){
					$("#popup-submit").trigger("click");
				}
				layer.msg(data.msg, 2, 8);
			}
	})
} 
function reg_lock(url){
	window.location.href=url;
} 
function get_open(obj){
	var b_class=$("#get_open").attr("class");
	if(b_class==""){
		$("#get_open").addClass("open"); 
		$("#expire").val("7");
	}else{
		$("#get_open").removeClass("open");
		$("#expire").val("1");
	} 
} 
$(document).ready(function(){
	$("#username").focus(function(){
		var txAreaVal = $(this).val();
		if( txAreaVal == this.defaultValue){$(this).val("");}
	}).blur(function(){
		var txAreaVal = $(this).val();
		if( txAreaVal == this.defaultValue||$(this).val()==""){$(this).val(this.defaultValue);}
	});
	$("#pw").focus(function(){
		$("#password").show();
		$("#password").focus();
		$("#pw").hide();
	})
	$("#password").blur(function(){
		if($("#password").val()==""){
			$("#pw").show();
			$("#password").hide();
		}
	})
});
//收藏猎头职位 type:普通职位 1 ，公司发布的猎头职位 2，猎头发布的职位 3
function fav_hjob(id){
	layer.load('执行中，请稍候...',0);
	$.post(weburl+"/lietou/index.php?c=favjob",{id:id},function(data){
		layer.closeAll();	
		if(data=='0'){
			layer.alert('请先登录！', 0, '提示',function(){location.href =weburl+"/index.php?m=login&usertype=1" });return false; 
		}else if(data=='1'){ 
			layer.msg('您已收藏过该职位！', 2,8);return false;
		}else if(data=='2'){ 
			$("#ltjob"+id).addClass("job_resp_collect_have");
			$("#ltjob"+id).html("已收藏");
			layer.confirm('收藏成功，是否返回个人中心？', function(){ window.location.href =weburl+"/member/index.php?c=favorite"; window.event.returnValue = false;return false; }); 
		}else if(data=='3'){	
			layer.msg('对不起，您不是个人用户，无法收藏职位！', 2,8);return false;  
		}
	});

}
//关注
function ltatn(id, type) {
    var atn_ok = '';
    var atn_cancel = '';
    var tag_name = $("#guanzhu" + id)[0].tagName;
    if (type == 'lt1') {
        atn_ok =  '<img src="'+weburl+'/app/template/lietou/images/focus_add.png" class="png">关注';
        atn_cancel = '<img src="'+weburl+'/app/template/lietou/images/focus_no_add.png" class="png">取消关注';
    } else {
        atn_ok = '+关注';
        atn_cancel = '取消关注';
    }
	if(id){
		layer.load('执行中，请稍候...',0);
	    $.post(weburl + "/index.php?m=ajax&c=atn", { id: id }, function (data) {
			layer.closeAll(); 
			var num=$("#atn"+id).html();
			if(data==0){
				layer.msg('只有个人用户才可以关注！', 2,8);return false;
			}else if(data=="1"){
				num=parseInt(num)+1;
				$("#atn" + id).html(num);
				if (tag_name == 'INPUT') {
				    $("#guanzhu" + id).val(atn_cancel);
				} else {
				    $("#guanzhu" + id).html(atn_cancel);
				}
				layer.msg('关注成功！', 2,9);return false;
			}else if(data=="2"){
				num=parseInt(num)-1;
				if(num<1){
					num="0";
				}
				$("#atn" + id).html(num);
				if (tag_name == 'INPUT') {
				    $("#guanzhu" + id).val(atn_ok);
				} else {
				    $("#guanzhu" + id).html(atn_ok);
				}
				layer.msg('取消关注！', 2,9);return false;
			}else if(data==3){ 
				layer.msg('您还没有登录！', 2,8);return false;
			}else if(data==4){ 
				layer.msg('自己不能关注自己！', 2,8);return false;
			}
		});
	}

}
//委托简历
function entrust(uid,name){
	layer.load('执行中，请稍候...',0);
	$.post(weburl+"/index.php?m=ajax&c=entrust",{uid:uid,name:name},function(data){
		layer.closeAll();
		if(data==1){ 
			layer.msg('您不是个人用户！', 2,8);return false;
		}else if(data==2){ 
			layer.msg('您已经委托过简历给该猎头！', 2,8);return false;
		}else if(data==3){ 
			layer.msg('委托简历成功！', 2,9);return false;
		}else if(data==4){ 
			layer.msg('先完善简历，成为高级简历以后才可以申请猎头帮您招聘！', 2,8);return false;
		}else if(data==5){ 
			layer.msg('您的'+pricename+'不足，无法委托简历！', 2,8);return false;
		}
	})
}
//发私信
function onmsg(fid,uid){
	if(uid<1){
		showlogin('');
	}
	else if(fid == uid){
		layer.msg('不可以给自己发私信！', 2,8);
	}
	else{ 
		$("#fid").val(fid); 
		$.layer({
			type : 1,
			title :'发私信', 
			offset: [($(window).height() - 192)/2 + 'px', ''],
			closeBtn : [0 , true],
			border : [10 , 0.3 , '#000', true],
			area : ['330px','192px'],
			page : {dom :"#reply"}
		}); 
	}
}
function send_msg(){
	var fid=$("#fid").val();
	var content=$.trim($("#content").val());
	if(content==""){ 
		layer.msg('内容不能为空！', 2,8);return false;
	}
	layer.load('执行中，请稍候...',0);
	$.post(weburl+"/lietou/index.php?c=send_msg",{content:content,fid:fid},function(data){  
		layer.closeAll();
		if(data=='-1'){
			layer.msg('请先登录！', 2,8);return false;
		}else if(data>'1'){
			layer.msg('发私信成功！', 2,9);return false;
		} else{
			layer.msg('发私信失败！', 2,8);return false;
		}
	})
}

function ypjob(type,uid,job_id){
	if(uid==""){ 
		layer.msg('您还没有登录！', 2,8);return false;
	}else{
		layer.confirm('确定申请该职位吗？', function(){
			layer.load('执行中，请稍候...',0);
			$.post(weburl+"/index.php?m=ajax&c=yqjob",{type:type,job_id:job_id},function(data){
				layer.closeAll();
				var data=eval('('+data+')');
				layer.msg(data.msg, 2,data.status,function(){location.reload();});return false; 
			})
		});
	}
}
function check_post_keyword(){
	if($("#keyword").val()=="" || $("#keyword").val()=="输入职位关键词：如销售总监等；只提供年薪10万以上职位。"){ 
		layer.msg('请输入职位关键词！', 2,8);return false;
	}
}
function check_service_keyword(){
	var keyword=$.trim($("#keyword").val());
	var rzid=$.trim($("#rzid").val());
	if((keyword=="" || keyword=="请输入关键字") && (rzid=="" || rzid=="输入认证ID")){ 
		layer.msg('关键字和认证ID至少输入一项！', 2,8);return false;
	}
}
function check_rebates(){
	if($("#uid").val()==""){ 
		layer.msg('您还没有登录，请先登录！', 2,8);return false;
	}	
	if($("#uid").val()==$("#job_uid").val()){ 
		layer.msg('您不能推荐给自己！', 2,8);return false;
	}
	if($.trim($("#content").val())==""){ 
		layer.msg('请输入内容！', 2,8);return false;
	}	
	if($.trim($("#txt_CheckCode").val())==""){ 
		layer.msg('请输入验证码！', 2,8);return false;
	}
}
function showImgDelay(imgObj, imgSrc, maxErrorNum) {
    if (maxErrorNum > 0) {
        imgObj.onerror = function () {
            showImgDelay(imgObj, imgSrc, maxErrorNum - 1);
        };
        setTimeout(function () {
            imgObj.src = imgSrc;
        }, 500);
        maxErrorNum = parseInt(maxErrorNum) - parseInt(1);
    }
}
 
//分享：qq空间、新浪、腾讯微博、人人网，type: qq,sina,qqwb,renren
function shareTO(type,title){ 
	var tip =  '赶紧分享给您的朋友吧。';
	var info = webname+' -- ' + '“'+ title + '”'+ '（来自'+ weburl + ')。  ';
	switch(type){
		case 'qq':
			 var href = 'http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?title=' + encodeURIComponent(info + tip) + '&summary=' + encodeURIComponent(info + tip) + '&url=' + encodeURIComponent(window.location.href);
			break;
		case 'sina':
			var href = 'http://service.weibo.com/share/share.php?title=' + encodeURIComponent(info + tip) + '&url=' + encodeURIComponent(window.location.href) + '&source=bookmark';
			break;
		case 'qqwb':
			 var href = 'http://v.t.qq.com/share/share.php?title=' + encodeURIComponent(info + tip) + '&url=' + encodeURIComponent(window.location.href);
			break;
		case 'renren':
			 var href = 'http://share.renren.com/share/buttonshare.do?link=' + encodeURIComponent(window.location.href) + '&title==' + encodeURIComponent(info + tip);
			break;
	}
	// window.open(href);  
	window.location = href;  
} 

$(document).ready(function() {
    //单击页面其他区域时，隐藏选择下拉框
	    $(document.body).click(function(evt){
	        var e = evt || event || window.event;
	        var ClickedElement=e.target;
	        if(!($(ClickedElement).hasClass('reward_person_search_add')||$(ClickedElement).parent().hasClass('reward_person_search_add')||$(ClickedElement).parent().parent().hasClass('reward_person_search_add')||$(ClickedElement).parent().parent().parent().hasClass('reward_person_search_add'))){
	            $('.reward_person_search_select_list').hide();
	        } 
	    });
	    //单击选择按钮，显示选择下拉框
	    $('#edu_name,#exp_name,#hy_name,#type_name,#salary_name,#report_name').click(function () {		    
	        $('.reward_person_items.reward_person_search_select_list').hide();
	        var SelectedList=$(this).parent().find('.reward_person_search_select_list');
	        if(SelectedList.length>0){
	            if(SelectedList.is(":hidden")){
	                SelectedList.show();
	            }else{
	                SelectedList.hide();
	            }
	        }
	    });
	    //单击选择下拉框指定项，隐藏选择下拉框，并设置选择项
	    $('.reward_person_items').delegate('.reward_person_search_select_list li','click',function(){
	        $(this).parent().hide();
	        $(this).parent().prev().val($(this).attr('code'));
	        $(this).parent().prev().prev().val($(this).attr('codename'));
	    });
		
});
$(document).ready(function() {
	$('body').click(function(evt) {
		if($(evt.target).parents("#reward_province").length==0 && evt.target.id != "province_name") {
		   $('#reward_province').hide();
		}
		if($(evt.target).parents("#reward_city").length==0 && evt.target.id != "city_name") {
		   $('#reward_city').hide();
		}
		if($(evt.target).parents("#reward_three_city").length==0 && evt.target.id != "three_city_name") {
		   $('#reward_three_city').hide();
		}        
    });
   
});