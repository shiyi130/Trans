<?php
if(!defined('InEmpireCMS'))
{
	exit();
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>菜单</title>
<link href="../../data/menu/menu.css" rel="stylesheet" type="text/css">
<script src="../../data/menu/menu.js" type="text/javascript"></script>
<SCRIPT lanuage="JScript">
function tourl(url){
	parent.main.location.href=url;
}
</SCRIPT>
</head>
<body onLoad="initialize()">
<table border='0' cellspacing='0' cellpadding='0'>
	<tr height=20>
			<td id="home"><img src="../../data/images/homepage.gif" border=0></td>
			<td><b>用户面板</b></td>
	</tr>
</table>

<table border='0' cellspacing='0' cellpadding='0'>
  <tr> 
    <td id="pruser" class="menu1" onclick="chengstate('user')">
		<a onmouseout="this.style.fontWeight=''" onmouseover="this.style.fontWeight='bold'">用户管理</a>
	</td>
  </tr>
  <tr id="itemuser" style="display:none"> 
    <td class="list">
	  <table border='0' cellspacing='0' cellpadding='0'>
        <?php 
        if($r[doadduser])
        {
        ?>
        <tr> 
          <td class="file">
			<a href="../../user/AddUser.php" target="main" onmouseout="this.style.fontWeight=''" 
			   onmouseover="this.style.fontWeight='bold'">创建用户</a>
          </td>
        </tr>
        <?php 
        }
        if($r[doedituser])
        {
        ?>
        <tr> 
          <td class="file">
			<a href="../../user/SearchUser.php" target="main" onmouseout="this.style.fontWeight=''" 
			   onmouseover="this.style.fontWeight='bold'">设定用户</a>
          </td>
        </tr>
       <?php 
        }
       if($r[dodeluser]){
        ?>
		<tr> 
          <td class="file">
			<a href="../../user/DeleteUser.php" target="main" onmouseout="this.style.fontWeight=''" 
			   onmouseover="this.style.fontWeight='bold'">删除用户</a>
          </td>
        </tr>
         <?php 
       }
        ?>
      </table>
	</td>
  </tr>
</table>
</body>
</html>