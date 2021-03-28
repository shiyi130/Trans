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
			<td><b>信息管理</b></td>
	</tr>
</table>

<table border='0' cellspacing='0' cellpadding='0'>
  <tr> 
    <td id="prcinfo" class="menu1" onclick="chengstate('cinfo')">
		<a onmouseout="this.style.fontWeight=''" onmouseover="this.style.fontWeight='bold'">信息相关管理</a>
	</td>
  </tr>
  <tr id="itemcinfo" style="display:none"> 
    <td class="list">
		<table border='0' cellspacing='0' cellpadding='0'>
		<?php
		if($r[doeditinfo])
		{
		?>
        <tr> 
          <td class="file">
			<a href="../../data/DispInfo.php" target="main" onmouseout="this.style.fontWeight=''" onmouseover="this.style.fontWeight='bold'">信息查询</a>
          </td>
        </tr>
        <?php
		}
		if($r[doaddinfo])
		{ 
        ?>
        <tr> 
          <td class="file">
			<a href="../../data/AddInfo.php" target="main" onmouseout="this.style.fontWeight=''" onmouseover="this.style.fontWeight='bold'">信息追加</a>
          </td>
        </tr>
        <?php
		}
		if($r[dodelinfo]) 
		{
        ?>
        <tr> 
          <td class="file">
			<a href="../../data/DelInfo.php" target="main" onmouseout="this.style.fontWeight=''" onmouseover="this.style.fontWeight='bold'">信息删除</a>
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