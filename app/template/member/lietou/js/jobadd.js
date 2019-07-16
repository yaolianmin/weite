function show_div(id){
	$("#"+id).show();
}
function check_select(id,name,type){
	$("#"+type).val(id);
	$("#"+type+"_button").val(name);
	$("#"+type+"list").hide();
	var jobaddtype=$("#jobaddtype").val();
	if(jobaddtype=="ltinfo"){
		$('#by_'+type).html('');
		$("#by_"+type).attr("class","m_name_gh");
	}
}
function get_city(id,name){
	$("#jobtwo_button").val('请选择');
	$("#jobtwo").val('');
	$.post(weburl+"/member/index.php?m=ajax&c=ajax_ltjob",{id:id},function(data) {
		$("#jobtwolist").html(data);
		check_select(id,name,"jobone")
	})
}
function check_box_type(id,type){
	var date=$("#"+type+id).attr("date");
	if(date>0){
		$("#"+type+id).removeClass("m_name_tag01");
		$("#"+type+id).attr("date","");
	}else{
		$("#"+type+id).addClass("m_name_tag01");
		$("#"+type+id).attr("date",id);
	}
}
function toDate(str){
	var sd=str.split("-");
	return new Date(sd[0],sd[1],sd[2]);
}
function Check_jobadd(){
	if($.trim($("#ltjob_name").val())==""){
		layer.msg("请输入职位名称",2,8);return false;
	}
	if($.trim($("#jobtwo").val())==""){
		layer.msg("请选择职位分类",2,8);return false;
	}
	if($.trim($("#cityid").val())==""){
		layer.msg("请选择工作地点",2,8);return false;
	}
	
	var jobdescT = UE.getEditor('job_desc').getContent();
	if(jobdescT==""){
		layer.msg("请输入职位描述", 2, 8);return false;
	}
	
	var eligibleT = UE.getEditor('eligible').getContent();
	if(eligibleT==""){
		layer.msg("请输入任职资格", 2, 8);return false;
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
		layer.msg('结束日期必须大于今天日期！',2,8);return false
	}
	if($.trim($("#department").val())==""){
		layer.msg("请输入所属部门",2,8);return false;
	}
	if($.trim($("#report").val())==""){
		layer.msg("请输入汇报对象",2,8);return false;
	}
	var min=$.trim($("#minsalary").val());
	var max=$.trim($("#maxsalary").val());
	if(min==''||min=='0'){
		layer.msg("请填写职位年薪",2,8);return false;
	}
	if(max && parseInt(max) <= parseInt(min)){
		layer.msg("最高年薪必须大于最低年薪",2,8);return false;
	}
	 
	var constitute =[];
	$('input[name="constitute[]"]:checked').each(function(){
		constitute.push($(this).val());
	});   
	if(constitute==''){
		layer.msg('请选择薪资构成！',2,8);return false;
	}
	if($.trim($("#age").val())==""){
		layer.msg("请选择年龄要求",2,8);return false;
	}
	if($.trim($("#sexid").val())==""){
		layer.msg("请选择性别要求",2,8);return false;
	}	
	if($.trim($("#exp").val())==""){
		layer.msg("请选择工作经验",2,8);return false;
	}

	if($.trim($("#edu").val())==""){
		layer.msg("请选择学历要求",2,8);return false;
	}
	 
	if($.trim($("#com_name").val())==""){
		layer.msg("请输入公司名称",2,8);return false;
	}
	if($.trim($("#pr").val())==""){
		layer.msg("请选择公司性质",2,8);return false;
	}
	if($.trim($("#hy").val())==""){
		layer.msg("请选择所属行业",2,8);return false;
	}
	if($.trim($("#mun").val())==""){
		layer.msg("请选择公司规模",2,8);return false;
	}
	if($.trim($("#desc").val())==""){
		layer.msg("请输入公司介绍",2,8);return false;
	}	
}
function index_lthy(){
	$.layer({
		type : 1,
		title : '选择行业',
		offset : ['50px' , '50%'],
		closeBtn : [0 , true],
		fix : false,
		border : [10 , 0.3 , '#000', true],
		move : false,
		area : ['720px','auto'],
		page : {dom :'#hydiv'}
	}); 	
}
function index_ltjob(){ 
	$.layer({
		type : 1,
		title : '选择擅长职位',
		offset : ['50px' , '50%'],
		closeBtn : [0 , true],
		fix : false,
		border : [10 , 0.3 , '#000', true],
		move :false,
		area : ['720px','auto'],
		page : {dom :'#jobdiv'}
	}); 	
}
function addClass(id){
	$("#"+id).addClass('post_text_shadow');
}
function removeClass(id){
	$("#"+id).removeClass('post_text_shadow');
}
function oninfotype(id){
	$("#"+id).addClass('m_name_input02');
	$("#by_"+id).attr("class","m_name_zuo");
	$("#by_"+id).html($("#"+id).attr("date"));
}
function blurinfotype(id){
	if($("#"+id).val()==""){
		$("#by_"+id).attr("class","m_name_byy");
		$("#by_"+id).html($("#"+id).attr("date"));
	}else if(id=="moblie"){
		if(isjsMobile($("#"+id).val())==false){
			$("#by_"+id).attr("class","m_name_byy");
			$("#by_"+id).html($("#"+id).attr("date"));
		}else{
			$("#by_"+id).attr("class","m_name_gh");
			$("#by_"+id).html('');
		}
	}else if(id=="phone"){
		if(isjsTell($("#"+id).val())==false){
			$("#by_"+id).attr("class","m_name_byy");
			$("#by_"+id).html($("#"+id).attr("date"));
		}else{
			$("#by_"+id).attr("class","m_name_gh");
			$("#by_"+id).html('');
		}
	}else if(id=="provinceid"&&$("#cityid").val()==""){
		$("#by_cityid").attr("class","m_name_byy");
		$("#by_cityid").html($("#cityid").attr("date"));
	}else{
		$("#by_"+id).attr("class","m_name_gh");
		$("#by_"+id).html('');
	}
	$("#"+id).removeClass('m_name_input02');
}



function CheckPost_info(){	
	var arr=true;
	if($.trim($("#realname").val())==""){
		blurinfotype("realname");
		arr=false;
	}
	if($.trim($("#com_name").val())==""){
		blurinfotype("com_name");
		arr=false;
	}
	if($.trim($("#phone").val())==""){
		blurinfotype("phone");
		arr=false;
	}
	if($.trim($("#email").val())!=""&&check_email($("#email").val())==false){
		$("#by_email").attr("class","m_name_byy");
		$("#by_email").html($("#email").attr("date"));
		arr=false;
	}
	if($.trim($("#moblie").val())==""){
		blurinfotype("moblie");
		arr=false;
	}else if(isjsMobile($("#moblie").val())==false){
			$("#by_moblie").attr("class","m_name_byy");
			$("#by_moblie").html($("#moblie").attr("date"));
      arr=false;
		}
	

	if($.trim($("#cityid").val())==""){
		blurinfotype("cityid");
		arr=false;
	}
	if($.trim($("#exp").val())==""){
		$("#by_exp").attr("class","m_name_byy");
		$("#by_exp").html($("#exp").attr("date"));
		arr=false;
	}
	if($.trim($("#title").val())==""){
		$("#by_title").attr("class","m_name_byy");
		$("#by_title").html($("#title").attr("date"));
		arr=false;
	}
	var hy=$("input[name='qw_hy[]']").val(); 
	if(!hy){
		$("#by_hy").attr("class","m_name_byy");
		$("#by_hy").html("请选择擅长行业");
		arr=false;
	}
	var job=$("input[name='job[]']").val(); 
	if(!job){
		$("#by_job").attr("class","m_name_byy");
		$("#by_job").html("请选择擅长职位");
		arr=false;
	}
	if($.trim($("#content").val())==""){
		$("#by_content").attr("class","m_name_byy");
		$("#by_content").html($("#content").attr("date"));
		arr=false;
	}
	return arr;
}



function CheckPost_pass(){
	var oldpassword=$("#oldpassword").val();
	var password=$("#password").val();
	var password2=$("#password2").val();
	oldpassword=$.trim(oldpassword);
	password=$.trim(password);
	password2=$.trim(password2);
	if(oldpassword==""){
		layer.msg('原密码不能为空！', 2,8);return false;  
	}
	if(password.length<6 ||password.length>20){ 
		layer.msg('新密码应在6-20位！', 2,8);return false;  
	}
	if(password!=password2){ 
		layer.msg('新密码和重复密码不一致！', 2,8);return false;
	}
	if(oldpassword==password){
		layer.msg("旧密码和新密码一致，不需要修改！", 2,8);return false;
	}
}