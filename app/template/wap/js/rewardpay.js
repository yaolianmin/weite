function getStatus(rewardid,status){
	if(status=='2'){
		
		window.location.href='index.php?c=rewardinvite&rewardid='+rewardid;

		
	}else if(status=='26'){
		
		window.location.href='index.php?c=arb&rewardid='+rewardid;
	}else{
		
		$.post('index.php?c=logstatus',{rewardid:rewardid,status:status},function(data){
			
			var data=eval('('+data+')');
			if(data.error=='ok'){
				
				layermsg('操作成功', 2, function(){location.reload();});
			}else{
				layermsg(data.error, 3, 8);
			}
			
			
		
		});
	
	}
}
function invite(){
		
		var rewardid = $('#rewardid').val();
		var jobid = $('#jobid').val();
		var linkman = $('#linkman').val();
		var linktel = $('#linktel').val();
		var intertime = $('#intertime').val();
		var address = $('#address').val();
		var content = $('#content').val();
		if(linkman==''){
			layermsg('请填写联系人！', 2,8);return false;
		}
		if(linktel==''){
			layermsg('请填写联系人电话！', 2,8);return false;
		}
		if(intertime==''){
			layermsg('请选择面试日期！', 2,8);return false;
		}
		if(address==''){
			layermsg('请填写面试地址！', 2,8);return false;
		}
		$.post('index.php?c=logstatus',{rewardid:rewardid,status:2,linkman:linkman,linktel:linktel,intertime:intertime,address:address,content:content},function(data){
			
			var data=eval('('+data+')');
			if(data.error=='ok'){
				
				layermsg('操作成功', 2, function(){window.location.href='index.php?c=rewardlog&jobid='+jobid;});
			}else{
				layermsg(data.error, 2, 8);
			}
		});
		
	}

	function withdraw_form(){
		var price=$("#price").val();
		var real_name=$("#real_name").val();
		if(!real_name){
			layermsg('请填写真实姓名！', 2, 8);return false;  
		}
		if(Number(price)<1){
			layermsg('请正确填写提现金额！', 2, 8);return false;  
		}

		return true;
		
	}
	function arb_form(){
		var content=$("#content").val();
		if(!content){
			layermsg('请填写仲裁原因！', 2, 8);return false;  
		}
		
		return true;
		
	}