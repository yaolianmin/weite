function CheckPost(){
    var name=$.trim($("#name").val());
    var sex=$.trim($("#sex").val());
	var telphone=$.trim($("#telphone").val());
	var email=$.trim($("#email").val());
	var birthday=$.trim($("#birthday").val());
	var edu=$.trim($("#educid").val()); 
	var exp=$.trim($("#expid").val()); 
	var living=$.trim($("#living").val());
	var telhome=$.trim($("#telhome").val());
	if(name==''){layer.msg('请填写姓名', 2, 8);return false;}
	if(sex==''){layer.msg('请选择性别', 2, 8);return false;}
	if(birthday==''){layer.msg("请选择出生年月", 2, 8);return false;}
	ifemail = check_email(email); 
	ifmoblie = isjsMobile(telphone);
	if(telphone==''){
		layer.msg('请填写手机号', 2, 8);return false;
	}else{
		if(ifmoblie==false){layer.msg("手机格式不正确", 2, 8);return false;}
	}
	if(email!=""&&ifemail==false){layer.msg("电子邮件格式不正确", 2, 8);return false;}
	if(living==''){layer.msg('请填写现居住地', 2, 8);return false;}
	if(edu==''){layer.msg('请选择学历', 2, 8);return false;} 
	if(exp==''){layer.msg('请选择工作经验', 2, 8);return false;}  
	if(telhome&&isjsTell(telhome)==false){
		layer.msg('请填写正确的座机号', 2, 8);return false;
	}
	layer.load('执行中，请稍候...',0);
}
function ScrollTo(id){
	$("#"+id).ScrollTo(700);
}
function movelook(type){
	$("#"+type+"_upbox").addClass("yun_resume_handle_bg");
	$("#compile_"+type).show();
}
function outlook(type){
	$("#"+type+"_upbox").removeClass("yun_resume_handle_bg");
	$("#compile_"+type).hide();
}
function toDate(str){
    var sd=str.split("-");
    return new Date(sd[0],sd[1],sd[2]);
}
function numresume(numresume,type){
	if(numresume<user_sqintegrity){
		 var showhtml="您现在的简历完整度太低，还不能够使用此简历应聘!"
	}else{
		var showhtml="您的简历已符合要求！"
	}
	$("#_ctl0_UserManage_LeftTree1_msnInfo").html(showhtml);
	$("#numresume").html(numresume+"%");
	//$(".resume_"+type).show();
	$(".play").attr("style","width:"+numresume+"%");
}
function changeRightIntegrityState(id,state){
	if(state=="add"){
		$("#"+id).find(".dom_m_right_state").removeClass("state");
		$("#"+id).find(".dom_m_right_state").addClass("state_done");
		$("."+id).removeClass("state");
		$("."+id).addClass("state_done");		
	}else{
		$("#"+id).find(".dom_m_right_state").removeClass("state_done");
		$("#"+id).find(".dom_m_right_state").addClass("state");	
		$("."+id).removeClass("state_done");
		$("."+id).addClass("state");		
	}
}
function shell(){
	var i = layer.load('执行中，请稍候...',0);
	$.post("index.php?c=expect",{shell:1},function(data){
		layer.close(i);
 		if(data==1){
			layer.msg('请先完善基本资料！', 2, 8);return false;
		}
	});
}
function saveexpect(){	
	shell(); 
	var name = $.trim($("#nameid").val());  
	var hy = $.trim($("#hyid").val());  
	var job_classid = $.trim($("#job_class").val());
	var provinceid = $.trim($("#provinceid").val());
	var cityid = $.trim($("#cityid").val());
	var three_cityid = $.trim($("#three_cityid").val());
	var minsalary = $.trim($("#minsalary").val()); 
	var maxsalary = $.trim($("#maxsalary").val()); 
	var type = $.trim($("#typeid").val()); 
	var report = $.trim($("#reportid").val());
	var eid = $.trim($("#eid").val());
	var jobstatus = $.trim($("#statusid").val());
	if(name==""){layer.msg('请填写期望岗位！', 2, 8);return false;}
	if(hy==""){layer.msg('请选择从事行业！', 2, 8);return false;}
	if(three_cityid==''&&cityid==''){layer.msg('请选择工作地点！', 2, 8);return false;}
	if(job_classid==""){layer.msg('请选择从事职位！', 2, 8);return false;}
	
	if(minsalary=="" || minsalary=="最低薪资"||parseInt(minsalary)<=0){layer.msg('请填写期望薪资！', 2, 8);return false;}
	if(maxsalary && parseInt(maxsalary)!=0 && parseInt(maxsalary) < parseInt(minsalary)){
		layer.msg('最高薪资必须大于最低薪资！', 2, 8);return false;
	}
	
	if(type==""){layer.msg('请选择工作性质！', 2, 8);return false;}
	if (report == "") { layer.msg('请选择到岗时间！', 2, 8); return false; }
	if (jobstatus == "") { layer.msg('请选择求职状态！', 2, 8); return false; }
	layer.load('执行中，请稍候...',0);
	$.post("index.php?c=expect&act=saveexpect", {name:name, hy:hy, job_classid: job_classid, provinceid: provinceid, cityid: cityid, three_cityid: three_cityid, minsalary: minsalary, maxsalary: maxsalary, jobstatus: jobstatus, type: type, report: report, eid: eid, submit: "1", dom_sort: getDomSort() }, function (data) {
		layer.closeAll('loading');
		layer.close($("#layindex").val());
		if(data==0){
			layer.msg('操作失败！', 2, 8);
		}else if(data==1){
			layer.msg('你的简历数已经超过系统设置的简历数了！', 2, 8);
		}else if(data==-1){
			layer.msg('请先完善您的求职意向信息！', 2, 8);
		}else{
			data=eval('('+data+')');
			if(eid==""){
				 window.location.href="index.php?c=expect&e="+data.id;
			}else{
				$("#saveexpect").hide();
				$("#eid").val(data.id);
				if(data.maxsalary && data.maxsalary!=0){
					var salary=data.minsalary+'-'+data.maxsalary;
				}else{
					var salary=data.minsalary+"以上";
				}
				var html='<li>期望岗位：'+data.name+'</li><li>工作职能：'+data.job_classname+'</li><li>期望工作地点：'+data.city+'</li><li>期望从事行业：'+data.hy+'</li><li>期望薪资：'+salary+'</li><li>到岗时间：'+data.report+'</li><li>期望工作性质：'+data.type+'</li><li>求职状态：'+data.jobstatus+'</li>';
				$("#expect").html(html);
				layer.msg('操作成功！', 2,9,function(){ScrollTo("expect_botton");$(".resume_expect").addClass('state_done');}); 
			}
		}
	});
}
function totoday(){
	if($("#totoday").attr("checked")=='checked'){
		$('#work_emonthid').val('');
		$('#work_emonth').val('');
		$('#work_eyearid').val('');
		$('#work_eyear').val('');
		$('#yearwork').hide();
		$('#monthwork').hide();
	}else{
		$('#yearwork').show();
		$('#monthwork').show();
	}
}
function getDomSort(){
	var domsort="";
	var elements=$("#dom0 .dom_m");
	for(var i=0;i<elements.length;i++){
		domsort=domsort+","+$(elements[i]).attr("id");
	}
	return domsort=domsort.substring(1,domsort.length);
}
function showMore(type,width,height,name){
	$("#add"+type+" li").show();
	$(".newresumebox").removeClass("newresumebox");  //打开弹出框时移除新加内容的class
	if(type=='expect'){
		$("#cityshowth").hide();
	}
	var layerindex = $.layer({
		type : 1,
		title : name,
		shift : 'top',
		offset : [($(window).height() - height)/2 + 'px', ''],
		closeBtn : [0 , true],
		border : [10 , 0.3 , '#000', true],
		area : [width+'px',height+'px'],
		page : {dom :"#save"+type},
		close: function(index){
			$(".newresumebox").remove();  //关闭弹出框移除未添加内容的新弹出的class
			$("#save"+type).hide();
			var num=$(".show"+type+"num");
			if(num.length==1){
				$(".hidepic").hide();
			}
		} 
	});
	$("#layindex").val(layerindex);
	form.render();
	if(type=='skill'){
		layui.use('upload', function(){
			var upload = layui.upload;
			upload.render({
				elem: '.resumeupload' 
				,url: weburl+'/index.php?m=ajax&c=layui_upload'
				,data: {path: path}
				,done: function(res){
					layer.closeAll('loading');
					if(res.code > 0){
						return layer.msg(res.msg);
					}else{
						$('#'+this.fileid).val(res.data.src);
						$('#'+this.parentid).removeClass('none');
						$('#'+this.imgid).attr('src', res.data.url); 
					}
				}
			});
		});
	}
}
function hideMore(type){
	$(".newresumebox").remove();//关闭弹出框移除未添加内容的新弹出的class
	$("#save"+type).hide();
	var num=$(".show"+type+"num");
	if(num.length==1){
		$(".hidepic").hide();
	}
	layer.close($("#layindex").val());
}
function changeIntegrityState(id,state){
	if(state=="add"){
		$("#"+id).find(".integrity_degree").addClass("state_done");		
	}else{
		$("#"+id).find(".integrity_degree").removeClass("state_done");	
	}
}
function checkModel(id){
	if(id==1){
		$("#module").addClass("resume_right_box_tit_cur");
		$("#template").removeClass("resume_right_box_tit_cur");
		$("#resume_module").show();
		$("#resume_template").hide();
	}else{
		$("#module").removeClass("resume_right_box_tit_cur");
		$("#template").addClass("resume_right_box_tit_cur");
		$("#resume_module").hide();
		$("#resume_template").show();
	}
}
function untiltoday(id,edate,num,toid,edid){
	if($("#"+id).attr("checked")=='checked'){
		$("#"+edate).attr('disabled','disabled');
		$("#"+edate+"un").removeAttr('disabled');
		$("#"+toid).val('2');
		$("#"+edate).attr('value','至今');
	}else{
		$("#"+edate).removeAttr('disabled');
		$("#"+edate+"un").attr('disabled','disabled');
		$("#"+edate).val('');
		$("#"+toid).val('1');
		$("#"+edate).show();
	}
	$("#"+num).hide();
	$("#"+edid).hide();
}
function deleteupbox(delid,boxid,showid,table){
	var id=$("#add"+table).find("."+delid).val();
	var eid=$("#eid").val();
	$("#"+delid).remove();
	$("#"+boxid).removeClass(showid);	
	var num=$("."+showid);
	if(num.length==1){
		$("."+showid).hide();
		$(".newresumebox").removeClass("newresumebox");  //只剩下最后一个新添加移除新加内容的class
	}
	if(id&&eid&&table){
		$.post("index.php?c=resume&act=publicdel",{id:id,eid:eid,table:table},function(data){
			if(data!='0'){
				
				data=eval('('+data+')');
				if(parseInt(data.integrity)<parseInt(user_sqintegrity)){
					 var showhtml="您现在的简历完整度太低，还不能够使用此简历应聘!"
				}else{
					var showhtml="您的简历已符合要求！"
				}
				//更新右侧完整度
				if(data.num<1){
					changeIntegrityState("m_right_"+table,"remove");
				}
				$("#"+table+""+id).remove();
				$("#_ctl0_UserManage_LeftTree1_msnInfo").html(showhtml);
				$("#numresume").html(data.integrity+"%");
				$(".play").attr("style","width:"+data.integrity+"%");
				
				if(data.num=="0"){
					$(".resume_"+table).removeClass('state_done');
					$("#"+table+"_empty").show();             //没有内容显示提示
				} 			
			}else{
				layer.msg('操作失败！', 2, 9,function(){location.reload();});
			}
		});
	}
}

function addWork(){
	$("#addwork").append(function(){
		$(".lastupbox").removeClass("lastupbox");
		var randnum='w'+parseInt(Math.random()*1000); 
		var html="<li class='yun_resume_popup newresumebox lastupbox' id='"+randnum+"'><i class='yun_resume_popup_del showworknum' id='iw"+randnum+"' onclick=\"deleteupbox('"+randnum+"','iw"+randnum+"','showworknum','work')\">-</i><input type='hidden' name='id[]' value='' class='"+randnum+"'/><input type='hidden' name='nameid[]' value='n"+randnum+"'><input type='hidden' name='sdateid[]' value='s"+randnum+"'><input type='hidden' name='edateid[]' value='ed"+randnum+"'><input type='hidden' name='timeid[]' value='iw"+randnum+"'><input type='hidden' class='usedwork' name='usedid[]' value=''><div class='yun_resume_popup_list'><span class='yun_resume_popup_name'>公司名称：</span><div class='yun_resume_popup_qyname'><input type='text' name='name[]' value='' onfocus=\"hidemsg('mworkn"+randnum+"')\" onblur=\"hidemsg('mworkn"+randnum+"')\" class='yun_resume_popup_text work_name'><i class='yun_resume_popup_qyname_tip' id='mworkn"+randnum+"' style='display:none'>请填写公司名称</i></div></div><div class='yun_resume_popup_list'><span class='yun_resume_popup_name'>担任职位：</span><input type='text' name='title[]' value='' class='yun_resume_popup_text work_title'></div><div class='yun_resume_popup_list'><span class='yun_resume_popup_name'>工作时间：</span><div class='yun_resume_popup_list_box'><input type='text' id='work_sdate"+randnum+"' name='sdate[]' value='' onfocus=\"hidemsg('mworkiw"+randnum+"','mworks"+randnum+"')\" onblur=\"hidemsg('mworkiw"+randnum+"','mworks"+randnum+"')\" class='yun_resume_popup_text yun_resume_popup_textw90 work_sdate'><script>layui.use(['laydate'],function(){var laydate = layui.laydate,$ = layui.$;laydate.render({elem:'#work_sdate"+randnum+"',type:'month'});laydate.render({elem:'#work_edate"+randnum+"',type:'month'});});<\/script><i class='yun_resume_popup_list_box_tip' id='mworks"+randnum+"' style='display:none'>请选择开始日期</i></div><span class='yun_resume_popup_time'>-</span><div class='yun_resume_popup_list_box'><input type='text' id='work_edate"+randnum+"' name='edate[]' value='' onfocus=\"hidemsg('mworkiw"+randnum+"','mworked"+randnum+"')\" onblur=\"hidemsg('mworkiw"+randnum+"','mworked"+randnum+"')\" class='yun_resume_popup_text yun_resume_popup_textw90 work_edate'><input id=\'work_edate"+randnum+"un\' type=\'hidden\' name=\'edate[]\' value=\'至今\' disabled=\'disabled\'/><i class='yun_resume_popup_list_box_tip' id='mworkiw"+randnum+"' style='display:none'>请确认日期先后顺序</i><i class='yun_resume_popup_list_box_tip' id='mworked"+randnum+"' style='display:none'>请选择结束日期</i></div><input type='hidden' id='to"+randnum+"' name='totoday[]' value='1'><input class='yun_resume_popup_checkbox' type='checkbox' value='1' onclick=\"untiltoday('totoday"+randnum+"','work_edate"+randnum+"','mworkiw"+randnum+"','to"+randnum+"','mworked"+randnum+"')\" id='totoday"+randnum+"'><span class='yun_resume_popup_checkbox_s'><label for='totoday'>至今</label></span> </div><div class='yun_resume_popup_list'><span class='yun_resume_popup_name'>工作内容：</span><textarea rows='5' cols='50' name='content[]' id='work_content' class='infor_textarea work_content'></textarea></div></li>";
        return html;
    });
	$(".showworknum").show();
	var div = document.getElementById('work_div');
	$("#work_div").animate({scrollTop:div.scrollHeight},1000);

}
 
function addTraining(){
	$("#addtraining").append(function(){
		$(".lastupbox").removeClass("lastupbox");
		var randnum='t'+parseInt(Math.random()*1000); 
		var html="<li class='yun_resume_popup newresumebox lastupbox' id='"+randnum+"'><i class='yun_resume_popup_del showtrainingnum'  id='it"+randnum+"' onclick=\"deleteupbox('"+randnum+"','it"+randnum+"','showtrainingnum','training')\">-</i><input type='hidden' name='id[]' class='"+randnum+"' value=''/><input type='hidden' name='nameid[]' value='n"+randnum+"'><input type='hidden' name='sdateid[]' value='s"+randnum+"'><input type='hidden' name='edateid[]' value='ed"+randnum+"'><input type='hidden' name='timeid[]' value='it"+randnum+"'><input type='hidden' class='usedtraining' name='usedid[]' value=''><div class='yun_resume_popup_list'><span class='yun_resume_popup_name'>培训中心：</span><div class='yun_resume_popup_qyname'><input type='text' name='name[]' value=''  onfocus=\"hidemsg('mtrainingn"+randnum+"')\" onblur=\"hidemsg('mtrainingn"+randnum+"')\" class='yun_resume_popup_text'><i class='yun_resume_popup_qyname_tip' id='mtrainingn"+randnum+"' style='display:none'>请填写培训中心名称</i></div></div><div class='yun_resume_popup_list'><span class='yun_resume_popup_name'>培训方向：</span><input type='text' name='title[]' value='' class='yun_resume_popup_text'></div><div class='yun_resume_popup_list'><span class='yun_resume_popup_name'>培训时间：</span><div class='yun_resume_popup_list_box'><input type='text' id='training_sdate"+randnum+"' name='sdate[]' value='' onfocus=\"hidemsg('mtrainingit"+randnum+"','mtrainings"+randnum+"')\" onblur=\"hidemsg('mtrainingit"+randnum+"','mtrainings"+randnum+"')\"class='yun_resume_popup_text yun_resume_popup_textw90'><script>layui.use(['laydate'], function(){var laydate = layui.laydate,$ = layui.$;laydate.render({elem: '#training_sdate"+randnum+"',type: 'month'});laydate.render({elem: '#training_edate"+randnum+"',type: 'month'});});<\/script><i class='yun_resume_popup_list_box_tip' id='mtrainings"+randnum+"' style='display:none'>请选择开始日期</i></div><span class='yun_resume_popup_time'>至</span><div class='yun_resume_popup_list_box'><input type='text' id='training_edate"+randnum+"' name='edate[]' value='' onfocus=\"hidemsg('mtrainingit"+randnum+"','mtraininged"+randnum+"')\" onblur=\"hidemsg('mtrainingit"+randnum+"','mtraininged"+randnum+"')\" class='yun_resume_popup_text yun_resume_popup_textw90'><i class='yun_resume_popup_list_box_tip' id='mtrainingit"+randnum+"' style='display:none'>请确认日期先后顺序</i><i class='yun_resume_popup_list_box_tip' id='mtraininged"+randnum+"' style='display:none'>请选择结束日期</i></div><div class='yun_resume_popup_list'><span class='yun_resume_popup_name'>培训内容：</span><textarea rows='5' cols='50' name='content[]' id='training_content' class='infor_textarea '></textarea></div></li>";
        return html;
    });
	$(".showtrainingnum").show();
	var div = document.getElementById('training_div');
	$("#training_div").animate({scrollTop:div.scrollHeight},1000);
}
function addProject(){
	$("#addproject").append(function(){
		$(".lastupbox").removeClass("lastupbox");
		var randnum='p'+parseInt(Math.random()*1000); 
		var html="<li class='yun_resume_popup newresumebox lastupbox' id='"+randnum+"'><i class='yun_resume_popup_del showprojectnum'  id='ip"+randnum+"' onclick=\"deleteupbox('"+randnum+"','ip"+randnum+"','showprojectnum','project')\">-</i><input type='hidden' name='id[]' class='"+randnum+"' value=''/><input type='hidden' name='nameid[]' value='n"+randnum+"'><input type='hidden' name='sdateid[]' value='s"+randnum+"'><input type='hidden' name='edateid[]' value='ed"+randnum+"'><input type='hidden' name='timeid[]' value='ip"+randnum+"'><input type='hidden' class='usedproject' name='usedid[]' value=''><div class='yun_resume_popup_list'><span class='yun_resume_popup_name'>项目名称：</span><div class='yun_resume_popup_qyname'><input type='text' name='name[]' value=''  onfocus=\"hidemsg('mprojectn"+randnum+"')\" onblur=\"hidemsg('mprojectn"+randnum+"')\" class='yun_resume_popup_text'><i class='yun_resume_popup_qyname_tip' id='mprojectn"+randnum+"' style='display:none'>请填写培项目名称</i></div></div><div class='yun_resume_popup_list'><span class='yun_resume_popup_name'>担任职位：</span><input type='text' name='title[]' value='' class='yun_resume_popup_text'></div><div class='yun_resume_popup_list'><span class='yun_resume_popup_name'>项目时间：</span>            <div class='yun_resume_popup_list_box'><input type='text' id='project_sdate"+randnum+"' name='sdate[]' value='' onfocus=\"hidemsg('mprojectip"+randnum+"','mprojects"+randnum+"')\" onblur=\"hidemsg('mprojectip"+randnum+"','mprojects"+randnum+"')\" class='yun_resume_popup_text yun_resume_popup_textw90'><script>layui.use(['laydate'], function(){var laydate = layui.laydate,$ = layui.$;laydate.render({elem: '#project_sdate"+randnum+"',type: 'month'});laydate.render({elem: '#project_edate"+randnum+"',type: 'month'});});<\/script><i class='yun_resume_popup_list_box_tip' id='mprojects"+randnum+"' style='display:none'>请选择开始日期</i></div><span class='yun_resume_popup_time'>至</span><div class='yun_resume_popup_list_box'><input type='text' id='project_edate"+randnum+"' name='edate[]' value='' onfocus=\"hidemsg('mprojectip"+randnum+"','mprojected"+randnum+"')\" onblur=\"hidemsg('mprojectip"+randnum+"','mprojected"+randnum+"')\" class='yun_resume_popup_text yun_resume_popup_textw90'><i class='yun_resume_popup_list_box_tip' id='mprojectip"+randnum+"' style='display:none'>请确认日期先后顺序</i><i class='yun_resume_popup_list_box_tip' id='mprojected"+randnum+"' style='display:none'>请选择结束日期</i></div></div><div class='yun_resume_popup_list'><span class='yun_resume_popup_name'>项目内容：</span><textarea rows='5' cols='50' name='content[]' id='project_content' class='infor_textarea '></textarea></div></li>";
        return html;
    });
	$(".showprojectnum").show();
	var div = document.getElementById('project_div');
	$("#project_div").animate({scrollTop:div.scrollHeight},1000);
}
function addSkill(){
	var randnum='s'+parseInt(Math.random()*1000);
	$("#addskill").append(function(){
		$(".lastupbox").removeClass("lastupbox");
		var sfile = 'sfile'+randnum, simg = 'simg'+randnum ,sparent = 'sparent'+randnum;
		
		var html="<li class='yun_resume_popup newresumebox lastupbox' id='"+randnum+"'><i class='yun_resume_popup_del showskillnum' id='is"+randnum+"' onclick=\"deleteupbox('"+randnum+"','is"+randnum+"','showskillnum','skill')\">-</i><input type='hidden' name='id[]' class='"+randnum+"' value=''/><input type='hidden' name='nameid[]' value='n"+randnum+"'><input type='hidden' name='timeid[]' value='is"+randnum+"'><input type='hidden' class='usedskill' name='usedid[]' value=''><div class='yun_resume_popup_list'><span class='yun_resume_popup_name'>技能名称：</span><div class='yun_resume_popup_qyname'><input type='text' name='name[]' id='skill_name' value='' onfocus=\"hidemsg('mskilln"+randnum+"')\" onblur=\"hidemsg('mskilln"+randnum+"')\" class='yun_resume_popup_text'><i class='yun_resume_popup_qyname_tip' id='mskilln"+randnum+"' style='display:none'>请填写技能名称</i></div></div><div class='yun_resume_popup_list'><span class='yun_resume_popup_name'>掌握时间：</span><input type='text' name='longtime[]' id='skill_longtime' value='' onkeyup='this.value=this.value.replace(/[^0-9.]/g,'')' class='yun_resume_popup_text'>年</div><div class='yun_resume_popup_list'><span class='yun_resume_popup_name'>上传证书：</span> <div class='photo_submit fl'><button type='button' class='yun_bth_pic skillupload"+randnum+"' lay-data=\"{fileid: '"+sfile+"',imgid: '"+simg+"',parentid: '"+sparent+"'}\">上传图片</button><input id='sfile"+randnum+"' type='hidden' name='cert[]' value=''/><div id='sparent"+randnum+"' class='photo_submit_pic none'> <img id='simg"+randnum+"' src='' width='38' height='38'></div></div></div></li>";
        return html;
    });
	layui.use('upload', function(){
		var upload = layui.upload;
		upload.render({
			elem: '.skillupload'+randnum+'' 
			,url: weburl+'/index.php?m=ajax&c=layui_upload'
			,data: {path: path}
			,done: function(res){
				if(res.code > 0){
					return layer.msg(res.msg);
				}else{
					$('#'+this.fileid).val(res.data.src);
					$('#'+this.parentid).removeClass('none');
					$('#'+this.imgid).attr('src', res.data.url); 
				}
			}
		});
	});
	$(".showskillnum").show();
	var div = document.getElementById('skill_div');
	$("#skill_div").animate({scrollTop:div.scrollHeight},1000);
}
function addOther(){
	$("#addother").append(function(){
		$(".lastupbox").removeClass("lastupbox");
		var randnum='o'+parseInt(Math.random()*1000); 
		var html="<li class='yun_resume_popup newresumebox lastupbox' id='"+randnum+"'><i class='yun_resume_popup_del showothernum'  id='io"+randnum+"' onclick=\"deleteupbox('"+randnum+"','io"+randnum+"','showothernum','other')\">-</i><input type='hidden' name='id[]' class='"+randnum+"' value=''/><input type='hidden' name='nameid[]' value='n"+randnum+"'><input type='hidden' name='timeid[]' value='io"+randnum+"'><input type='hidden' class='usedother' name='usedid[]' value=''><div class='yun_resume_popup_list'><span class='yun_resume_popup_name'>标题：</span><div class='yun_resume_popup_qyname'><input type='text' name='name[]' id='skill_title' value='' onfocus=\"hidemsg('mothern"+randnum+"')\" onblur=\"hidemsg('mothern"+randnum+"')\" class='yun_resume_popup_text'><i class='yun_resume_popup_qyname_tip' id='mothern"+randnum+"' style='display:none'>请填写标题</i></div></div><div class='yun_resume_popup_list'><span class='yun_resume_popup_name'>内容：</span><textarea rows='5' cols='50' name='content[]' id='skill_content' class='infor_textarea '></textarea></div></li>";
        return html;
    });
	$(".showothernum").show();
	var div = document.getElementById('other_div');
	$("#other_div").animate({scrollTop:div.scrollHeight},1000);
}

function hidemsg(eid,sid){
	$("#"+eid).hide();
	$("#"+sid).hide();
}
function resumeFanhui(frame_id){ 
	if(frame_id==''||frame_id==undefined){
		frame_id='supportiframe';
	}
	var msg = $(window.frames[frame_id].document).find("#layer_msg").val(); 
	if(msg != null){
		var url=$(window.frames[frame_id].document).find("#layer_url").val();
		var layer_time=$(window.frames[frame_id].document).find("#layer_time").val();
		var layer_st=$(window.frames[frame_id].document).find("#layer_st").val();
		layer.msg(msg, layer_time, Number(layer_st),function(){window.location.href = url;window.event.returnValue = false;return false;});
	}
	var wrong = $(window.frames[frame_id].document).find("#wrong").val(); 
	var timenum = $(window.frames[frame_id].document).find("#timenum").val(); //显示时间顺序出错的
	if(timenum !=null && wrong !=null){
		var tnums=timenum.split('-');
		for(var i=0;i<tnums.length;i++){
			$("#m"+wrong+tnums[i]).show();
		}
	}
	var namenum = $(window.frames[frame_id].document).find("#namenum").val(); //显示name为空的
	if(namenum !=null && wrong !=null){
		var namenums=namenum.split('-');
		for(var i=0;i<namenums.length;i++){
			$("#m"+wrong+namenums[i]).show();
		}
	}
	var sdatenum = $(window.frames[frame_id].document).find("#sdatenum").val(); //显示sdate为空的
	if(sdatenum !=null && wrong !=null){
		var sdatenums=sdatenum.split('-');
		for(var i=0;i<sdatenums.length;i++){
			$("#m"+wrong+sdatenums[i]).show();
		}
	}
	var edatenum = $(window.frames[frame_id].document).find("#edatenum").val(); //显示edate为空的
	if(edatenum !=null && wrong !=null){
		var edatenums=edatenum.split('-');
		for(var i=0;i<edatenums.length;i++){
			$("#m"+wrong+edatenums[i]).show();
		}
	}
	var message = $(window.frames[frame_id].document).find("#resumeAll").val();
	if(message != null){
		if(parseInt(message)==2){
			$(".newresumebox").remove();
			$(".hidepic").hide();
		}else if(parseInt(message)==3){
			var resume="work";
		}else if(parseInt(message)==4){
			var resume="edu";
		}else if(parseInt(message)==5){
			var resume="training";
		}else if(parseInt(message)==6){
			var resume="skill";
		}else if(parseInt(message)==7){
			var resume="project";
		}else if(parseInt(message)==8){
			var resume="cert";
		}else if(parseInt(message)==9){
			var resume="other";
		}else if(parseInt(message)==10){
			var resume="description";
		}
		if(resume!=''){
			var upnum = $(window.frames[frame_id].document).find("#upnum").val();
			if(upnum!=0){
				$("#"+resume+"_empty").hide();
			}
		}
		
		var eid=$.trim($("#eid").val());	
		var x = layer.load('执行中，请稍候...',0); 
		$.post(weburl+"/member/index.php?c=expect&act=showresume",{eid:eid,resume:resume},function(data){
			layer.close(x);
			layer.close($("#layindex").val());
			if(data!=0){

				if(parseInt(message)!=10||parseInt(message)!=9){
					var integrity = $(window.frames[frame_id].document).find("#integrity").val();   //右侧简历百分比
					numresume(integrity,resume);
					changeIntegrityState("m_right"+message,"add");
				}
				$(".newresumebox").removeClass("newresumebox"); //保存后移除新添加class
                var newids = $(window.frames[frame_id].document).find("#newids").val();  //append添加的经历的随机id
				var dels = $(window.frames[frame_id].document).find("#dels").val();      //添加进表格后的id
				if(newids !=null && dels != null){
					var newnums=newids.split('-');
					var delnums=dels.split('-');
					for(var i=0;i<newnums.length;i++){
						$("#add"+resume).find("."+delnums[i]).val(newnums[i]);  //添加用append添加的和开始的空的经历id
					}
				}
				$("#save"+resume).hide();
				$(".used"+resume).val('1');
				$("#"+resume).html(data);
				layer.msg('操作成功！', 2,9,function(){ScrollTo(resume+"_upbox");})
			}else{ 
				layer.msg('操作失败！', 2,8);
			}
	   });
    }
}
function changeModel(type,name,height){
	$.layer({
		type: 2,
		shadeClose: true,
		title: name,
		closeBtn: [0, true],
		shade: [0.8, '#000'],
		border: [0],
		offset: ['20px',''],
		area: ['1000px', ($(window).height() - 50) +'px'],
		iframe: {src: type}
    }); 
}
$(function(){
		//光标悬停时，显示知名企业关注信息
	$('.yun_resume_info').delegate('.user_item','mouseover',function(){
		$(this).find('.photochange').show();
	});
	//光标离开时，隐藏知名企业关注信息
	$('.yun_resume_info').delegate('.user_item','mouseout',function(){
		$(this).find('.photochange').hide();
	});
	
	$(".changetag").live('click',function(){
		
		var tag=$(this).attr('tag-class');
		if(tag=='1'){
			$(this).addClass('resume_pop_bq_cur');
			$(this).attr('tag-class','2');
		}else{
			$(this).removeClass('resume_pop_bq_cur');
			$(this).attr('tag-class','1');
		}
		var tag_value;
		var tagi = 0;
		$(".resume_pop_bq_cur").each(function(){
			if($(this).attr('tag-class')=='2'){
				var info =$(this).attr("data-tag");
		        tag_value+=","+info;
				tagi++;

			}
		});
		if(tagi>5){
			layer.msg('最多只能选择五项！', 2,8);
			if(tag=='1'){
				$(this).removeClass('resume_pop_bq_cur');
			}
			return false;
		}
		if(tag_value){ 
		    tag_value = tag_value.replace("undefined,","");
		    $("#tag").val(tag_value); 
	    }else{
			$("#tag").val(''); 
		}
	});

	//添加自定义个人标签
	$('.checkboxAddBton').click(function(){

		var ntag = $('#addfuli').val();
		//判断当前已选标签数量 限定为5个
		var tagid = $('#tag').val();
		if(tagid && tagid.split(',').length>=5){

			layer.msg('最多只能选择五项！', 2,8);
		}else{
			var error=0;
			if(ntag.length>=2 && ntag.length<=8){
				//判断信息是否已经存在 
				$('.changetag').each(function(){
					var otag = $(this).attr('data-tag');
					if(ntag == otag){
						layer.msg('相同标签已存在，请选择或重新填写！', 2,8);
						error = 1;
					}
				});
				if(error==0){
					$('#newtag').append('<li class="changetag  resume_pop_bq_cur" data-tag="'+ntag+'" tag-class="2"><em>'+ntag+'</em></li>');
					
					var tag_value;
					$(".resume_pop_bq_cur").each(function(){
						if($(this).attr('tag-class')=='2'){
							var info =$(this).attr("data-tag");
							tag_value+=","+info;
						}
					});
					tag_value = tag_value.replace("undefined,","");
					$("#tag").val(tag_value); 
				}
				$('#addfuli').val('');
				
			}else{
				layer.msg('请输入2-8个标签字符！', 2,8);
			}
		}
	});
});
function checkdes(){
	var description=$.trim($("#check_des").val());
 	if(description==''){
		$("#des_show").show();
		return false;
	} 
}