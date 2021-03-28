<?php
define('EmpireCMSAdmin','1');
define('EmpireCMSAPage','login');
require("./class/connect.php");
require("./class/functions.php");
//风格
$loginadminstyleid=EcmsReturnAdminStyle();
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>多语言查询系统  - TRANS</title>
<link rel="stylesheet" href="loginimg/css.css" type="text/css">
<base onmouseover="window.status='多语言查询系统  - TRANS';return true">
<script>
if(self!=top)
{
	parent.location.href='index.php';
}
function CheckLogin(obj){
	if(obj.username.value=='')
	{
		alert('请输入用户名');
		obj.username.focus();
		return false;
	}
	if(obj.password.value=='')
	{
		alert('请输入登录密码');
		obj.password.focus();
		return false;
	}
	return true;
}
</script>
</head>

<body text="383636" bgcolor="#FFFFFF" onload="document.login.username.focus();">
<table width="98" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr> 
    <td height="60">&nbsp;</td>
  </tr>
</table>
<table width="524" border="0" cellspacing="0" cellpadding="0" align="center" height="320">
  <form name="login" method="post" action="ecmsadmin.php" onsubmit="return CheckLogin(document.login);">
    <input type="hidden" name="enews" value="login">
    <tr>
      <td width="61" rowspan="3" valign="top"> <TABLE WIDTH=61 height="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
          <TR> 
            <TD height="63"> <IMG SRC="loginimg/login_r1_c1_01.jpg" WIDTH=61 HEIGHT=63></TD>
          </TR>
          <TR> 
            <TD height="163" background="loginimg/login_r1_c1_02.jpg">&nbsp; </TD>
          </TR>
          <TR> 
            <TD height="100%" background="loginimg/login_r1_c1_003.jpg">&nbsp;</TD>
          </TR>
          <TR> 
            <TD height="23"> <IMG SRC="loginimg/login_r1_c1_03.jpg" WIDTH=61 HEIGHT=23></TD>
          </TR>
        </TABLE></td>
      <td colspan="3"><img src="loginimg/login_r1_c2.gif" width="463" height="65"></td>
    </tr>
    <tr> 
      <td width="241" valign="top" bgcolor="#FFFFFF"><img src="loginimg/login_r2_c2.gif" width="241" height="104"></td>
      <td width="157" rowspan="2" valign="top" bgcolor="#FFFFFF"> 
        <table width="157" height="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td> <br><br>
              <img src="loginimg/login_r2_c3.gif" width="157" height="184"></td>
          </tr>
          <tr>
            <td height="100%" valign="bottom"> <div align="right">
            <img src="loginimg/homepage.gif" width="79" height="21" border="0">&nbsp;<img src="loginimg/empirebbs.gif" width="69" height="21" border="0"></div></td>
          </tr>
        </table> </td>
      <td width="65" rowspan="2" valign="top"> 
        <table width="65" height="100%" border="0" cellpadding="0" cellspacing="0">
          <tr> 
            <td height="184" background="loginimg/login_r2_c4.gif">&nbsp;</td>
          </tr>
          <tr> 
            <td height="100%" background="loginimg/login_r2_c4_002.jpg">&nbsp;</td>
          </tr>
        </table>
      </td>
    </tr>
     <tr> 
      <td height="80"> <table width="230" height="100%" border="0" align="right" cellpadding="0" cellspacing="0">
          <tr> 
            <td width="50" height="27">用户名: </td>
            <td colspan="2"> <input name="username" type="text" class="b-form2" size="24" value="sys"> 
            </td>
          </tr>
          <tr> 
            <td height="27">密&nbsp;&nbsp;码:&nbsp;</td>
            <td colspan="2"> <input name="password" type="password" class="b-form2" size="24" value="123456"> 
            </td>
          </tr>
          <tr> 
            <td height="27">窗&nbsp;&nbsp;口:&nbsp;</td>
            <td colspan="2"><input type="radio" name="adminwindow" value="0" checked>
              正常 
              <input type="radio" name="adminwindow" value="1">
              全屏</td>
          </tr>
          <tr> 
            <td height="27">&nbsp;</td>
            <td colspan="2" valign="bottom"> <input name="imageField" type="image" src="loginimg/login2.gif" width="69" height="21" border="0"> 
            </td>
          </tr>
        </table></td>
    </tr>
    <tr> 
      <td colspan="4"><table width="100%" border="0" cellpadding="0" cellspacing="0" background="loginimg/login_r4_c1.gif">
          <tr> 
            <td width="111" height="32">&nbsp;</td>
            <td width="111" valign="top">&nbsp;</td>
            <td width="302">&nbsp;</td>
          </tr>
        </table></td>
    </tr>
    <tr> 
      <td height="39" colspan="4" valign="top" bgcolor="ECECEC">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr> 
            <td width="19%"><div align="center"></div></td>
            <td width="73%" height="30">Powered by <a href="http://www.mobile-system.co.jp/index.html" target="_blank"><strong>Mobile-System</strong></a> 
              <font color="#FF9900"><strong>1.0</strong></font> &copy; 2010 
              <a href="http://www.mobile-system.co.jp/index.html" target="_blank">TransSoft</a> Inc.</td>
            <td width="8%">&nbsp;</td>
          </tr>
        </table></td>
    </tr>
  </form>
</table>
</body>
</html>