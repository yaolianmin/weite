function check_search(id,name,type){
	$("#"+type+"name").val(name);
	$("#"+type).val(id);
	$("#list"+type).hide();
}
$(document).ready(function(){
	$(".search_add").hover(function(){
		var aid=$(this).attr("type");
		$("#list"+aid).show();
	},function(){
		var aid=$(this).attr("type");
		$("#list"+aid).hide();
	})   
	$(".post_items").hover(function(){
		var aid=$(this).attr("aid");
		$("#joblist"+aid).addClass("post_items_hover");	
		$("#joblist"+aid+" .post_items_pop").show();
	},function(){
		var aid=$(this).attr("aid");
		$("#joblist"+aid).removeClass("post_items_hover");	
		$("#joblist"+aid+" .post_items_pop").hide();
	})  
	$(".service_items").hover(function(){
		$(this).addClass("service_items_hover");
	},function(){
		$(this).removeClass("service_items_hover");
	}) 
})
function check_show_search(id){
	if(id==2){
		$("#searchtype1").show();
		$("#searchtype2").hide();
	}else{
		$("#searchtype1").hide();
		$("#searchtype2").show();
	}
}
function checkfrom(myform){
	var keyword=myform.keyword.value; 
	if(keyword=="请输入你要查找的信息"){
		myform.keyword.value='';
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
		move :false,
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
function Close(id){
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
function input_check_show2(){
	var name_val = "";
	var id_val = "";
	$("input[name='hy']").each(function(){
		var info = $(this).val().split("+");
		if(id_val==""){
			id_val=info[0];
		}else{
			id_val=id_val+","+info[0];
		}
		if(name_val==""){
			name_val=info[1];
		}else{
			name_val=name_val+","+info[1];
		}
	});
	$("#hy").val(id_val);
	$("#hybutton").val(name_val);
	layer.closeAll();
}
function input_check_show(){
	var name_val = "";
	var id_val = "";
	$("input[name='job']").each(function(){
		var info = $(this).val().split("+");
		if(id_val==""){
			id_val=info[0];
		}else{
			id_val=id_val+","+info[0];
		}
		if(name_val==""){
			name_val=info[1];
		}else{
			name_val=name_val+","+info[1];
		}
	});
	$("#job").val(id_val);
	$("#jobbutton").val(name_val);
	layer.closeAll();
}

	$(function(){
	    //关键字输入框内容与提示信息相同时，当输入框获取焦点则置空该输入框
	    $('.search_keyword').delegate('input[name=keyword]','focus',function(){
	        if($.trim($(this).val())==$(this).attr('placeholder')){
	            $(this).val('');
	        }
	    });
	    //切换 简洁搜索<==>高级搜索
	    $('.hunter_search').delegate('.search_more_bth,.search_more_bth_up', 'click', function () {
	        if($("#formSimpleSearch").is(":hidden")){
	            $('#formAdvanceSearch').hide();
	            $('#formSimpleSearch').show();
	        }else{
	            $('#formSimpleSearch').hide();
	            $('#formAdvanceSearch').show();
	        }
	    });
	    //光标悬停时，显示知名企业关注信息
	    $('.company_items').delegate('.company_logo','mouseover',function(){
	        $(this).find('.company_focus').show();
	    });
	    //光标离开时，隐藏知名企业关注信息
	    $('.company_items').delegate('.company_logo','mouseout',function(){
	        $(this).find('.company_focus').hide();
	    });
	    //单击页面其他区域时，隐藏选择下拉框
	    $(document.body).click(function(evt){
	        var e = evt || event || window.event;
	        var ClickedElement=e.target;
	        if(!($(ClickedElement).hasClass('search_add')||$(ClickedElement).parent().hasClass('search_add')||$(ClickedElement).parent().parent().hasClass('search_add')||$(ClickedElement).parent().parent().parent().hasClass('search_add'))){
	            $('.search_more .search_select_list').hide();
	        } 
	    });
	    //单击选择按钮，显示选择下拉框
	    $('#cityin_name,#hy_name,#mun_name,#pr_name,#uptime_name').click(function () {		    
	        $('.search_more .search_select_list').hide();
	        var SelectedList=$(this).parent().find('.search_select_list');
	        if(SelectedList.length>0){
	            if(SelectedList.is(":hidden")){
	                SelectedList.show();
	            }else{
	                SelectedList.hide();
	            }
	        }
	    });
	    //单击选择下拉框指定项，隐藏选择下拉框，并设置选择项
	    $('.search_more').delegate('.search_select_list li','click',function(){
	        $(this).parent().hide();
	        $(this).parent().prev().val($(this).attr('code'));
	        $(this).parent().prev().prev().val($(this).attr('codename'));
	    });
	});