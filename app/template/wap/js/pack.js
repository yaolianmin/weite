$(document).ready(function(){	
	
	//赏金职位申请
	$("#sq_rewardjob").click(function(){
		var jobid=$("#jobid").val();
		$.post(wapurl+"/index.php?c=reward&c=ajaxjob",{jobid:jobid},function(data){
			var data=eval('('+data+')');
			
			if(!data.error || data.error==0){
				showlogin();
			}else if(data.error==2){
				layer.alert('您还没有合适的简历，是否先添加简历？', 0, '提示',function(){window.location.href =wapurl+"/member/index.php?c=expect";window.event.returnValue = false;return false; });
			}else if(data.error==7 || data.error==1){
				
				$(".POp_up_r").html('');
				$(".POp_up_r").append(data.data.name);
				$('.comapply_redpack_list_resume_tj_no').removeClass('comapply_redpack_list_resume_tj_no');
				$('.comapply_redpack_td').hide();
				$('.perresume').show();
				if(data.data.exptype=='1'){
					$('.expno').addClass('comapply_redpack_list_resume_tj_no');
					$('.expmsg').html('不符合');
				}else{
					
					$('.expmsg').html('符合');
				}
				if(data.data.edutype=='1'){
					$('.eduno').addClass('comapply_redpack_list_resume_tj_no');
					$('.edumsg').html('不符合');
				}else{
					
					$('.edumsg').html('符合');
				}
				if(data.data.projecttype=='1'){
					$('.projectno').addClass('comapply_redpack_list_resume_tj_no');
					$('.projectmsg').html('不符合');
				}else{
					
					$('.projectmsg').html('符合');
				}
				if(data.data.skilltype=='1'){
					$('.skillno').addClass('comapply_redpack_list_resume_tj_no');
					$('.skillmsg').html('不符合');
				}else{
					
					$('.skillmsg').html('符合');
				}
				$.layer({
					type : 1,
					title :'申请赏金职位', 
					closeBtn : [0 , true],
					border : [10 , 0.3 , '#000', true],
					area : ['480px','auto'],
					page : {dom :"#sqrewardjob_show"}
				});

			}else if(data.error==1){

				$(".POp_up_r").html('');
				$(".POp_up_r").append(data.data.name);
				$('.comapply_redpack_list_resume_tj_no').removeClass('comapply_redpack_list_resume_tj_no');
				$('.comapply_redpack_td').hide();
				$('.sqreward').show();
				$('.comapply_redpack_list_resume_s').html('符合');
				$.layer({
					type : 1,
					title :'申请赏金职位', 
					closeBtn : [0 , true],
					border : [10 , 0.3 , '#000', true],
					area : ['480px','auto'],
					page : {dom :"#sqrewardjob_show"}
				});
			}else{

				layermsg(data.msg, 2, 8);

			}
		});
		
	});
	
	//职位详情页 申请职位
	$("#clickreward_sq").click(function(){
		
		var jobid=$("#jobid").val();
		
		$('#sqrewardjob_show').hide();
		$('#bg').hide();
		layer.closeAll();
		var loadi = layer.load('执行中，请稍候...',0);
		$.post(weburl+"/index.php?m=reward&c=sqjob",{jobid:jobid},function(data){
			layer.close(loadi);
			var data=eval('('+data+')');
			if(data.error==1){  
        var i = layer.confirm('申请成功，是否继续浏览？',
          {btn : ['查看更多','继续浏览']},
          function(){
            window.location.href=weburl+"/job";window.event.returnValue = false;return false;
          },
          function(){
            layer.close(i);
            window.location.href=window.location.href;
          }
        );
			}else if(data.error==0){
				showlogin();
			}else{
				layermsg(data.msg, 2,8);return false;
			}
		});
	})
	
})
