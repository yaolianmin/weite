<?php
include("global.php");	
$act = !empty($_GET['act']) ? trim($_GET['act']) : 'set';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>PHPYUN�˲�ϵͳ��̨�û����������蹤��</title>
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
      <td align="center"><strong  style="font-size:14px;">PHPYUN�˲�ϵͳ��̨�û����������蹤��</strong></td>
    </tr>
    <tr>
      <td>
	  
�����·�����������Ҫ������û��������룬����������ɾ���ļ���</td>
    </tr>
  </table>
<form id="form1" name="form1" method="post" action="?act=save">
  <table width="500" border="0" align="center" cellpadding="3" cellspacing="1" bgcolor="#B6D1EB">
    <tr>
      <td bgcolor="#009ACD">&nbsp;&nbsp;����д��Ҫ������û��������룺</td>
    </tr>
    <tr>
      <td height="150" bgcolor="#FFFFFF"><table width="100%" border="0" cellspacing="0" cellpadding="5">
          <tr>
            <td width="100" align="right">�����û�����</td>
            <td><input name="adminname" type="text" size="20" /></td>
          </tr>
          <tr>
            <td align="right">���������룺</td>
            <td><input name="adminpwd" type="text"  size="20" /></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td><input type="submit" name="Submit" value="����"  style="font-size:12px; padding:8px;" /></td>
          </tr>
    
      </table>
      	  <br />
      	  <strong>˵����</strong>�˹��߽�������PHPYUN�˲�ϵͳ3.0�����ϰ汾<br />
�������� �뷴������̳ http://www.phpyun.com/bbs/	<br />
	רҵ�ɼ��������湫˾ �ٷ�����֧�֣���ȫ ��Ч  ���ģ��ɿ���Ʊ  ��ǩ��ͬ�����������б��ϣ� ֧��ͬ��������֧����ҵְλ�����˼�����΢��Ƹ����Ƹ�ᡢ���¡��ʴ�Ĳɼ���������ϵQQ��9860259   ���Ƶ绰��130 7387 8784<br /></td>
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
exit("��������ʧ�ܣ�");
}
?>
<table width="500" border="0" align="center" cellpadding="5" cellspacing="1" bgcolor="#FBD08A">
    <tr>
      <td align="center" bgcolor="#FFFDF0"  style="color:#FF0000">
	  ��ϲ�����óɹ��������ɾ�����ߣ�	  </td>
    </tr>
</table>
<?php
}
?>
</body>
</html>