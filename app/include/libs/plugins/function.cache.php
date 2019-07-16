<?php
/*
 * 职位调用封装
 * ----------------------------------------------------------------------------
 * 分别对 职位，对应招聘公司，公司所属企业级别，认证等级等作操作
 *
 * ============================================================================
*/
function smarty_function_cache($paramer,&$smarty){	
	$path = PLUS_PATH;
	
	if(is_array($cachename))
	{
		foreach($cachename as $key=>$value)
		{
			switch($value){
				case "job" :
				include($path."/job.cache.php");
				$smarty->assign("job_index",$job_index);
				$smarty->assign("job_type",$job_type);
				$smarty->assign("job_name",$job_name);
				break;
				case "industry" :
				include_once($path."/industry.cache.php");
				$smarty->assign("industry_index",$industry_index);
				$smarty->assign("industry_name",$industry_name);
				break;
				case "city" :
				include_once($path."/city.cache.php");
				$smarty->assign("city_index",$city_index);
				$smarty->assign("city_type",$city_type);
				$smarty->assign("city_name",$city_name);
				break;
				case "user" :
				include_once($path."/user.cache.php");
				$smarty->assign("userdata",$job_index);
				$smarty->assign("userclass_name",$userclass_name);
				break;
				case "com" :
				include_once($path."/com.cache.php");
				$smarty->assign("comdata",$comdata);
				$smarty->assign("comclass_name",$comclass_name);
				break;
				case "domain" :
				include_once($path."/domain.cache.php");
				$smarty->assign("site_domain",$site_domain);
				break;
				
				
			}
		}
	}


}