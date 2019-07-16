function toDate(str){
	var sd=str.split("-");
	return new Date(sd[0],sd[1],sd[2]);
}
//关注
function ltatn(id) {
    var atn_ok = '';
    var atn_cancel = '';
    var tag_name = $("#guanzhu" + id)[0].tagName;
    atn_ok = '+关注';
    atn_cancel = '取消关注';
	if(id){
		layer_load('执行中，请稍候...',0);
	    $.post(wapurl + "/index.php?c=ajax&a=atn", { id: id }, function (data) {
			layer.closeAll(); 
			var num=$("#atn"+id).html();
			if(data==0){
				layermsg('只有个人用户才可以关注！');return false;
			}else if(data=="1"){
				num=parseInt(num)+1;
				$("#atn" + id).html(num);
				if (tag_name == 'INPUT') {
				    $("#guanzhu" + id).val(atn_cancel);
				} else {
				    $("#guanzhu" + id).html(atn_cancel);
				}
				layermsg('关注成功！');return false;
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
				layermsg('取消关注！');return false;
			}else if(data==3){ 
				layermsg('您还没有登录！');return false;
			}else if(data==4){ 
				layermsg('自己不能关注自己！');return false;
			}
		});
	}
}
//猎头发布咨询
function ltmsg(img){
	var msg_content=$.trim($("#msg_content").val());
	var authcode=$("#msg_CheckCode").val();
	var jobid=$("#jobid").val();
	var job_uid=$("#job_uid").val();
	var com_name=$("#com_name").val();
	var job_name=$("#job_name").val();
	if(msg_content==''){
		layermsg('咨询内容不能为空！');return false;
	}else if(authcode==''){
		layermsg('验证码不能为空！');return false;
	}else{
		layer_load('执行中，请稍候...',0);
	    $.post(wapurl + "/index.php?c=ajax&a=pl", {content:msg_content,authcode:authcode,jobid:jobid,job_uid:job_uid,com_name:com_name,job_name:job_name}, function (data) {
			layer.closeAll();
			if(data==0){
				layermsg('只有个人用户才可以关注！');return false;
			}else if(data==1){
				layermsg('留言成功！',2,function(){location.reload();});return false;
			}else if(data==2){
				layermsg('咨询内容不能为空！');return false;
			}else if(data==3){ 
				layermsg('您还没有登录！');return false;
			}else if(data==4){ 
				layermsg('验证码不能为空！');return false;
			}else if(data==5){ 
				layermsg('验证码错误！',2,function(){checkCode(img);});return false;
			}else if(data==6){
				layermsg('咨询失败！');return false;
			}else if(data==7){
				layermsg('该企业暂不接受相关咨询！');return false;
			}
			
	    });
	}
}
//申请职位
function ypjob(type,uid,job_id){
	if(uid==""){ 
		layermsg('您还没有登录！');return false;
	}else{
		//layer.confirm('确定申请该职位吗？', function(){
			layer_load('执行中，请稍候...',0);
			$.post(wapurl+"/index.php?c=ajax&a=yqjob",{type:type,job_id:job_id},function(data){
				layer.closeAll();
				var data=eval('('+data+')');
				layermsg(data.msg, 2,function(){location.reload();});return false; 
			})
		//});
	}
}
//收藏猎头职位 type:普通职位 1 ，公司发布的猎头职位 2，猎头发布的职位 3
function fav_hjob(id){
	layer_load('执行中，请稍候...',0);
	$.post(wapurl+"/index.php?c=ltjob&a=favjob",{id:id},function(data){
		layer.closeAll();	
		if(data=='0'){
			layermsg('请先登录！！');return false;
		}else if(data=='1'){ 
			layermsg('您已收藏过该职位！');return false;
		}else if(data=='2'){ 
			layermsg('收藏成功！', 2,function(){location.reload();});return false;  
		}else if(data=='3'){	
			layermsg('对不起，您不是个人用户，无法收藏职位！');return false;  
		}
	});
}
//推荐人才
function ltrecuser(url){
	var uid=$.trim($("#uid").val());
	var job_uid=$.trim($("#job_uid").val());
	var job_id=$.trim($("#job_id").val());
	var name=$.trim($("#name").val());
	var content=$.trim($("#content").val());
	var phone=$.trim($("#phone").val());
	var hy=$.trim($("#hy").val());
	var job_post=$.trim($("#job_post").val());
	var provinceid=$.trim($("#provinceid").val());
	var cityid=$.trim($("#cityid").val());
	var three_cityid=$.trim($("#three_cityid").val());
	var minsalary=$.trim($("#minsalary").val());
	var maxsalary=$.trim($("#maxsalary").val());
	var type=$.trim($("#type").val());
	var report=$.trim($("#report").val());
	var birthday=$.trim($("#birthday").val());
	var edu=$.trim($("#edu").val());
	var exp=$.trim($("#exp").val());
	var email=$.trim($("#email").val());
	var sex=$.trim($("#sex").val());
	var myreg = /^([a-zA-Z0-9\-]+[_|\_|\.]?)*[a-zA-Z0-9\-]+@([a-zA-Z0-9\-]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;  
	var reg= /^[1][3456789]\d{9}$/; //验证手机号码  
	if(name==""){
		layermsg('姓名不能为空！');return false; 
	}
	if(sex==''){
		layermsg('请选择性别！');return false;
	}
	if(birthday==''){
		layermsg('请选择出生年月！');return false;
	}
	if(edu==''){
		layermsg('请选择最高学历！');return false;
	}
	if(exp==''){
		layermsg('请选择工作经验！');return false;
	}
	if(phone==""){
		layermsg('手机不能为空！');return false; 
	}
	if(!reg.test(phone)){
		layermsg('手机格式不正确！');return false; 
	}
	if(email!=""&&!myreg.test(email)){
		layermsg('邮箱格式不正确！');return false; 
	}
	if(hy==''){
		layermsg('请选择从事行业！');return false;
	}
	if(job_post==''){
		layermsg('请选择期望职位！');return false;
	}
	if(three_cityid==''){
		layermsg('请选择期望城市！');return false;
	}
	if(minsalary==''){
		layermsg('请填写期望薪资！');return false;
	}
	if(maxsalary){
		if(parseInt(minsalary)>=parseInt(maxsalary)){
			layermsg('最低薪资必须小于最高薪资！');return false;
		}
	}
	if(type==''){
		layermsg('请选择工作性质！');return false;
	}
	if(report==''){
		layermsg('请选择到岗时间！');return false;
	}
	if(content==""){
		layermsg('推荐描述不能为空！');return false; 
	}
	layer_load('执行中，请稍候...');
	$.post(url,{uid:uid,job_uid:job_uid,job_id:job_id,name:name,content:content,phone:phone,hy:hy,job_classid:job_post,provinceid:provinceid,cityid:cityid,three_cityid:three_cityid,minsalary:minsalary,maxsalary:maxsalary,type:type,report:report,birthday:birthday,edu:edu,exp:exp,email:email,sex:sex},function(data){
		layer.closeAll();
		if(data=='1'){ 
			layermsg('推荐成功！', 2,function(){location.reload();});return false;
		}else if(data=='2'){ 
			layermsg('推荐失败！');return false;  
		}else if(data=='3'){	
			layermsg('好友姓名不能为空！');return false;  
		}else if(data=='4'){	
			layermsg('好友手机不能为空！');return false;  
		}else if(data=='5'){	
			layermsg('手机格式不正确！');return false;  
		}else if(data=='6'){	
			layermsg('推荐描述不能为空！');return false;  
		}
	});
}
function checkltinfo(){
	var realname=$.trim($("#realname").val());
	var comname=$.trim($("#com_name").val());
	var phone=$.trim($("#phone").val());
	var email=$.trim($("#email").val()); 
	var moblie=$.trim($("#moblie").val());
	var cityid=$.trim($("#cityid").val());
	var exp=$.trim($("#exp").val());
	var title=$.trim($("#title").val());
	var qw_hy=$.trim($("#qw_hy").val());
	var job=$.trim($("#job").val());
	var content=$.trim($("#content").val());
	 var myreg = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+((.[a-zA-Z0-9_-]{2,3}){1,2})$/;
	if(realname==''){layermsg("请输入真实姓名！");return false;}
	if(comname==''){layermsg("请输入所在公司！");return false;}
	if(phone==''){layermsg("请输入公司座机！");return false;}
	if(phone&&!isjsTell(phone)){layermsg("公司座机格式错误！");return false;}
	if(email&&!myreg.test(email)){layermsg("联系邮箱格式错误！");return false;}
	if(moblie==''){layermsg("请输入手机号码！");return false;}
	if(moblie&&!isjsMobile(moblie)){layermsg("手机号码格式错误！");return false;}
	if(cityid==''){layermsg("请选择所在地！");return false;}
	if(exp==''){layermsg("请选择工作经验！");return false;}
	if(title==''){layermsg("请选择目前头衔！");return false;}
	if(qw_hy==''){layermsg("请选择擅长行业！");return false;}
	if(job==''){layermsg("请选择擅长职位！");return false;}
	if(content==''){layermsg("请输入顾问介绍！");return false;}	
}
//猎头基本信息擅长行业、职位清除
function ltremoves(type){
	if(type=='lthy'){
		$("#qw_hy").val(""); 
	}else if(type=='ltjob'){
		$("#job").val(""); 
	}
	$("#"+type+"name").val("请选择");
	$("input[name='"+type+"class']").removeAttr("checked");
	$("input[name='"+type+"classone']").removeAttr("checked");
}
//猎头基本信息擅长行业、职位确认
function ltrealy(type) {
	var info="";
	var value="";
	$("input[name='"+type+"class']:checked").each(function(){
		var obj = $(this).val();
		var name = $(this).attr("data");
		if(info==""){
			info=obj;
			value=name;
		}else{
			var jclass=$(this).attr("class");
		    var rej = jclass.split("jobone")[1];
			if(info.indexOf(rej)<0){
				info=info+","+obj;
				value=value+","+name;
			}
		}
	})
	$("input[name='"+type+"classone']:checked").each(function(){
		obj = $(this).val();
		name = $(this).attr("data");
		if(info==""){
			info=obj;
			value=name;
		}else{
			var oneclass=$(this).attr("class");
		    var ret = oneclass.split("one")[1];
			if(info.indexOf(ret)<0){
				info=info+","+obj;
				value=value+","+name;
			}
		}
	})
	
	if(info==""){
		layermsg("请选择！");return false;
	}else{
		if(type=='lthy'){
			$("#qw_hy").val(info); 
		}else if(type=='ltjob'){
			$("#job").val(info); 
		}
		$("#"+type+"name").val(value);
		Closes(type);
	}
}
//猎头基本信息擅长行业、职位选择
function ltchecked_input(id,type){
	if(type=='lthy'){
		if($("#r"+id).is(':checked')) {
			$("#r"+id).addClass('xz');
			$(".one"+id).removeClass('xz');
			$(".one"+id).attr('checked',false); 
			$(".one"+id).attr('disabled','disabled');
		}else{
			$("#r"+id).removeClass('xz');
			$(".one"+id).attr('disabled',false);
			$(".one"+id).attr('checked',false); 
		}
	}else if(type=='ltjob'){
		if($("#j"+id).is(':checked')) {
			$("#j"+id).addClass('xzj');
			$(".jobone"+id).removeClass('xzj');
			$(".jobone"+id).attr('checked',false); 
			$(".jobone"+id).attr('disabled','disabled');
		}else{
			$("#j"+id).removeClass('xzj');
			$(".jobone"+id).attr('disabled',false);
			$(".jobone"+id).attr('checked',false); 
		}
	}
	//var class_length = $("input[name='"+type+"class']:checked").length;
	/*if((class_length)>2){
		layermsg('搜索条件过多！',2,function(){
			if(type=='lthy'){
				$("#r"+id).attr("checked",false);
				$(".one"+id).attr("checked",false);
				$(".one"+id).attr('disabled',false);
			}else if(type=='ltjob'){
				$("#j"+id).attr("checked",false);
				$(".jobone"+id).attr("checked",false);
				$(".jobone"+id).attr('disabled',false);
			}
		}); 	
	}*/
	var r_length=$(".xz").length;
	if(r_length>5){ 
		layermsg('您最多只能选择五个！',2,function(){
			$("#r"+id).attr("checked",false);
			$("#r"+id).removeClass('xz');	
		}) 	
	}
    var j_length=$(".xzj").length;	
	if(j_length>5){ 
		layermsg('您最多只能选择五个！',2,function(){
			$("#j"+id).attr("checked",false);
			$("#j"+id).removeClass('xzj');
		})
	}
}
//选择类别
function ckcom(type){
	var val=$("#"+type).find("option:selected").text();
	$('.'+type).html(val);
}
//选择职位类别
function checkltjob(id){
	if(id>0){
		$.post(wapurl+"?c=ajax&a=wap_ltjob",{id:id},function(data){
			$("#jobtwo").html(data);
		})
	}else{
		$("#jobtwo").html('<option value="">请选择</option>');
	}
}
function Checkjobadd(){
	if($.trim($("#job_name").val())==""){
		layermsg("请输入职位名称");return false;
	}
	if($.trim($("#jobtwo").val())==""){
		layermsg("请选择职位分类");return false;
	}
	if($.trim($("#cityid").val())==""){
		layermsg("请选择工作地点");return false;
	}
	var job_desc=UE.getEditor('job_desc').hasContents();
	if(job_desc==""){
		layermsg("请输入职位描述");return false;
	}
	var eligible=UE.getEditor('eligible').hasContents();
	if(eligible==""){
		layermsg("请输入任职资格");return false;
	}
	if($.trim($("#edate").val())==""){
		layermsg("请填写截止日期");return false;
	}
	var today=$("#today").val();
	var st=toDate(today).getTime()/1000;
	if($("#edate").val()!=''){
		var edate=$("#edate").val();
	}else{
		var edate='';
	}
	edate=toDate(edate).getTime()/1000;
	if(edate<st){
		layermsg('截止日期必须大于今天日期！',2,8);return false
	}
	if($.trim($("#department").val())==""){
		layermsg("请输入所属部门");return false;
	}
	if($.trim($("#report").val())==""){
		layermsg("请输入汇报对象");return false;
	}
	var min = $.trim($("#minsalary").val());
	var max = $.trim($("#maxsalary").val());
	
	if(min==""||min=="0"){
		layermsg("请填写职位年薪");return false;
	}
	if(max && parseInt(max) <= parseInt(min)){
		layermsg("最高年薪必须大于最低年薪");return false;
	}
	var constitute =[];
	$('input[name="constitute[]"]:checked').each(function(){
		constitute.push($(this).val());
	});
	if(constitute.length==0){
		layermsg("请选择薪资构成！",2);return false;
	}
	if($.trim($("#age").val())<1){
		layermsg("请选择年龄要求");return false;
	}
	if($.trim($("#sex").val())<1){
		layermsg("请选择性别要求");return false;
	}	
	if($.trim($("#exp").val())<1){
		layermsg("请选择工作经验");return false;
	}
	
	if($.trim($("#edu").val())<1){
		layermsg("请选择学历要求");return false;
	}
	if($.trim($("#com_name").val())==""){
		layermsg("请输入公司名称");return false;
	}
	if($.trim($("#pr").val())<1){
		layermsg("请选择公司性质");return false;
	}
	if($.trim($("#hy").val())<1){
		layermsg("请选择所属行业");return false;
	}
	if($.trim($("#mun").val())<1){
		layermsg("请选择公司规模");return false;
	}
	if($.trim($("#desc").val())==""){
		layermsg("请输入公司介绍");return false;
	}	
	 
}
//发私信
function onmsg(fid,uid){
	if(fid == uid){
		layermsg('不可以给自己发私信！');
	}else{ 
		$("#fid").val(fid);
		replyhtml=$("#reply").html();
		$("#reply").html('');
		layer.open({
			type : 1,
			title :'发私信', 
			offset: [($(window).height() - 192)/2 + 'px', ''],
			closeBtn : [0 , true],
			border : [10 , 0.3 , '#000', true],
			area : ['330px','192px'],
			content:replyhtml,
			cancel : function(){$("#reply").html(replyhtml);}
		}); 
	}
}
function send_ltmsg(){
	var fid=$("#fid").val();
	var content=$.trim($("#content").val());
	if(content==""){ 
		layermsg('内容不能为空！');return false;
	}
	layer_load('执行中，请稍候...',0);
	$.post(wapurl+"index.php?c=ltjob&a=send_ltmsg",{content:content,fid:fid},function(data){  
		layer.closeAll();
		if(data=='-1'){
			layermsg('请先登录！');return false;
		}else if(data>'1'){
			layermsg('发私信成功！');return false;
		} else{
			layermsg('发私信失败！');return false;
		}
	})
}
//委托简历
function entrust(uid,name){
	layer_load('执行中，请稍候...',0);
	$.post(wapurl+"index.php?c=ajax&a=entrust",{uid:uid,name:name},function(data){
		layer.closeAll();
		if(data==1){ 
			layermsg('您不是个人用户！');return false;
		}else if(data==2){ 
			layermsg('您已经委托过简历给该猎头！');return false;
		}else if(data==3){ 
			layermsg('委托简历成功！');return false;
		}else if(data==4){ 
			layermsg('先完善简历，成为高级简历以后才可以申请猎头帮您找到合适的工作！');return false;
		}else if(data==5){ 
			layermsg('您的'+pricename+'不足，无法委托简历！');return false;
		}
	})
}