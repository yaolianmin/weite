<?php
include("global.php");	
$act = !empty($_GET['act']) ? trim($_GET['act']) : 'set';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>PHPYUN人才系统后台用户名密码重设工具</title>
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 20px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-color: #F4F7F9;
	line-height:180%
}
body,td,th {
	font-size: 12px;
	color: #000000;
}
form{ padding:0px; margin:0px;}
a:link {
	color: #0066CC;
}
a:visited {
	color: #0066CC;
}
a:hover {
	color: #009900;
}

-->
</style></head>
<body>
<?php 
if ($act=="set")
{
?>
  <table width="500" border="0" align="center" cellpadding="5" cellspacing="0">
    <tr>
      <td align="center"><strong  style="font-size:14px;">PHPYUN人才系统后台用户名密码重设工具</strong></td>
    </tr>
    <tr>
      <td>
	  
请在下方表单中输入您要重设的用户名和密码，重设后请务必删除文件。</td>
    </tr>
  </table>
<form id="form1" name="form1" method="post" action="?act=save">
  <table width="500" border="0" align="center" cellpadding="3" cellspacing="1" bgcolor="#B6D1EB">
    <tr>
      <td bgcolor="#009ACD">&nbsp;&nbsp;请填写您要重设的用户名和密码：</td>
    </tr>
    <tr>
      <td height="150" bgcolor="#FFFFFF"><table width="100%" border="0" cellspacing="0" cellpadding="5">
          <tr>
            <td width="100" align="right">设置用户名：</td>
            <td><input name="adminname" type="text" size="20" /></td>
          </tr>
          <tr>
            <td align="right">设置新密码：</td>
            <td><input name="adminpwd" type="text"  size="20" /></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td><input type="submit" name="Submit" value="保存"  style="font-size:12px; padding:8px;" /></td>
          </tr>
    
      </table>
      	  <br />
      	  <strong>说明：</strong>此工具仅适用于PHPYUN人才系统3.0及以上版本<br />
如有问题 请反馈至论坛 http://www.phpyun.com/bbs/	<br />
	专业采集服务，正规公司 官方技术支持，安全 高效  放心，可开发票  可签合同，后续服务有保障！ 支持同步升级。支持企业职位、个人简历、微招聘、招聘会、文章、问答的采集发布请联系QQ：9860259   定制电话：130 7387 8784<br /></td>
    </tr>
  </table>
</form>
<?php 
}
elseif ($act=="save")
{
	$user = $_POST['adminname'];
	$pwd= md5($_POST['adminpwd']);
$nid=$db->query("UPDATE `".$db_config[def]."admin_user` SET `username`='".$user."',`password`='".$pwd."' WHERE `m_id`='1'");		
if(!$nid)
{
exit("密码重设失败！");
}
?>
<table width="500" border="0" align="center" cellpadding="5" cellspacing="1" bgcolor="#FBD08A">
    <tr>
      <td align="center" bgcolor="#FFFDF0"  style="color:#FF0000">
	  恭喜您设置成功，请务必删本工具！	  </td>
    </tr>
</table>
<?php
}
?>
</body>
</html>