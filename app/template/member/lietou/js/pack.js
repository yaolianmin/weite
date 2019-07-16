$(document).ready(function(){	
	//职位详情页 申请职位
	$(".lt_reward_sq").click(function(){
		
		var jobid=$(this).attr('data-jobid');
		var eid=$(this).attr('data-eid');
		
		var loadi = layer.load('执行中，请稍候...',0);
		$.post(weburl+"/member/index.php?c=reward&act=sqjob",{jobid:jobid,eid:eid},function(data){
			layer.close(loadi);
			var data=eval('('+data+')');
			if(data.error==1){          
        var i = layer.confirm('推荐成功，是否继续推荐？',
          {btn : ['更多赏金职位','继续推荐']},
          function(){
            window.location.href=weburl+"/job";window.event.returnValue = false;return false;
          },
          function(){
            layer.close(i);
            window.location.href=window.location.href;
          }
        );
			}else{
				layer.msg(data.msg,2,8);return false;
			}
		});
	})
	
})
