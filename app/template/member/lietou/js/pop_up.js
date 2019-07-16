function input_check_show(){
	var skill_val = "";
	/*$("input[name='job']").each(function(){
	var info = $(this).val().split("+");
	skill_val+="<span class=\"m_name_fw\" id=\"sk"+info[0]+"\"><em>"+info[1]+"</em><input type=\"button\" class=\"m_fw_submit\" onclick=\"del_type('"+info[0]+"');\"><input type=\"hidden\" value=\""+info[0]+"\" name=\"job[]\" /></span>";
	});*/
	var jobids = $('#jobids').val();
	var jobidlist = jobids.split(',');
	var jobnames = $('#jobnames').val();
	var jobnamelist = jobnames.split(',');
	for (var i = 0; i < jobidlist.length; i++) {
	    skill_val += "<span class=\"m_name_fw\" id=\"ltjob" + jobidlist[i] + "\"><em>" + jobnamelist[i] + "</em><input type=\"button\" class=\"m_fw_submit\" onclick=\"del_type('" + jobidlist[i] + "');\"><input type=\"hidden\" value=\"" + jobidlist[i] + "\" name=\"job[]\" /></span>";
	}
	$("#job").html(skill_val);
	var jobaddtype=$("#jobaddtype").val();
	if(jobaddtype=="ltinfo"){
		$("#by_job").html('');
		$("#by_job").attr("class","m_name_gh");
	}
	layer.closeAll();
}

function input_check_show2(){
	var skill_val = "";
	/*$("input[name='hy']").each(function(){
	var info = $(this).val().split("+");
	skill_val += "<span class=\"m_name_fw\" id=\"lthy" + hyidlist[i] + "\"><em>" + hynamelist[i] + "</em><input type=\"button\" class=\"m_fw_submit\" onclick=\"del_type2('" + hyidlist[i] + "');\"><input type=\"hidden\" value=\"" + hyidlist[i] + "\" name=\"qw_hy[]\" /></span>";
	});*/
	var hyids = $('#hyids').val();
	var hyidlist = hyids.split(',');
	var hynames = $('#hynames').val();
	var hynamelist = hynames.split(',');
	for (var i = 0; i < hyidlist.length; i++) {
	    skill_val += "<span class=\"m_name_fw\" id=\"lthy" + hyidlist[i] + "\"><em>" + hynamelist[i] + "</em><input type=\"button\" class=\"m_fw_submit\" onclick=\"del_type2('" + hyidlist[i] + "');\"><input type=\"hidden\" value=\"" + hyidlist[i] + "\" name=\"qw_hy[]\" /></span>";
	}
	$("#qw_hy").html(skill_val);
	//猎头基本信息--start
	var jobaddtype=$("#jobaddtype").val();
	if(jobaddtype=="ltinfo"){
		if(skill_val==""){
			$("#by_hy").attr("class","m_name_gh");
		}else{
			$("#by_hy").html('');
			$("#by_hy").attr("class","m_name_gh");
		}
	}
	//猎头基本信息--end
	layer.closeAll();
}
function del_type(id){
	$("#ltjob"+id).remove();
	$("#sk"+id).remove();
	$("#zn"+id).removeAttr("checked");
}
function del_type2(id){
	$("#lthy"+id).remove();
	$("#hy_"+id).remove();
	$("#hy"+id).removeAttr("checked");
} 
/*----弹出end---*/
function Close(id){
	layer.closeAll();
}
function showdiv(id){
	layer.closeAll();
} 
function checked_input(id){
	var check_length = $("input[name='job']").length;
	if($("#zn"+id).attr("checked")=="checked"){
			if(check_length>=5){
				layer.msg('您最多只能选择五个！', 2, 8);
				$("#zn"+id).attr("checked",false);
			}else{
				var info = $("#zn"+id).val();
				var info_arr = info.split("+");
				$("#job_"+id).remove(); 
				$("#jobname").append('<li id="job_'+id+'"><a class="clean g3" href="javascript:void(0);"><input id="chk_'+id+'" type="hidden" name="job" value="'+id+'+'+info_arr[1]+'" checked="" onclick="box_delete2('+id+');" class="lt_joadd_chk"><span class="text">'+info_arr[1]+'</span><span class="delete" onclick="box_delete('+id+');">移除</span></a></li>');
			}
	}else{
		$("#job_"+id).remove();
	}
}
function checked_input2(id){
	var check_length = $("input[name='hy']").length;
	if($("#hy"+id).attr("checked")=="checked"){
			if(check_length>=5){ 
				layer.msg('您最多只能选择五个！', 2, 8); 
				$("#hy"+id).attr("checked",false);
			}else{
				var info = $("#hy"+id).val();
				var info_arr = info.split("+");
				$("#hy_"+id).remove(); 
				$("#hyname").append('<li id="hy_'+id+'"><a class="clean g3" href="javascript:void(0);"><input id="chk_'+id+'" type="hidden" name="hy" value="'+id+'+'+info_arr[1]+'" checked="" onclick="box_delete2('+id+');" class="lt_joadd_chk"><span class="text">'+info_arr[1]+'</span><span class="delete" onclick="box_delete2('+id+');">移除</span></a></li>');
			}
	}else{
		$("#hy_"+id).remove();
	}
}
function box_delete(id){
	$("#job_"+id).remove();
	$("#zn"+id).attr("checked",false);
}
function box_delete2(id){
	$("#hy_"+id).remove();
	$("#hy"+id).attr("checked",false);
}
function input_check_show3(){
	var skill_val = "";
	/*$("input[name='hy']").each(function(){
	  var info = $(this).val().split("+");
	  skill_val+="<li id=\"lthy"+info[0]+"\" style=\"margin-top:0px;color:#666;\"><input onclick=\"del_type2('"+info[0]+"');\" type=\"checkbox\" class=\"it_jobadd_chk\" name=\"qw_hy[]\" checked=\"\" value="+info[0]+" style=\"margin-top:9px; float:left; margin-right:5px;\">"+info[1]+"</li>";
	  });*/
	var hyids = $('#hyids').val();
	var hyidlist = hyids.split(',');
	var hynames = $('#hynames').val();
	var hynamelist = hynames.split(',');
	for (var i = 0; i < hyidlist.length; i++) {
	    skill_val += "<li id=\"lthy" + hyidlist[i] + "\" style=\"margin-top:0px;color:#666;\"><input onclick=\"del_type2('" + hyidlist[i] + "');\" type=\"checkbox\" class=\"it_jobadd_chk\" name=\"qw_hy[]\" checked=\"\" value=" + hyidlist[i] + " style=\"margin-top:9px; float:left; margin-right:5px;\">" + hynamelist[i] + "</li>";
    }
	 // $("#qw_hy").html(skill_val);
	   layer.closeAll();
}
//私信
function ltreply(id,pid){ 
    $("#fid").val(id);
    $("#pid").val(pid); 
	$.layer({
		type : 1,
		title :'发私信', 
		offset: [($(window).height() - 192)/2 + 'px', ''],
		closeBtn : [0 , true],
		border : [10 , 0.3 , '#000', true],
		area : ['330px','220px'],
		page : {dom :"#reply"}
	}); 
}
function check_xin(){
	var content=$.trim($("#content").val());
	var fid=$.trim($("#fid").val());
	if(content==""){ 
		layer.msg('回复内容不能为空！', 2, 8); 
		return false;
	}	 
}
//私信---end