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
			<td><b>系统设置</b></td>
	</tr>
</table>

<table border='0' cellspacing='0' cellpadding='0'>
<?
if($r[dodbdata]||$r[doexecsql])
{
?>
  <tr> 
    <td id="prbak" class="menu3" onclick="chengstate('bak')">
		<a onmouseout="this.style.fontWeight=''" onmouseover="this.style.fontWeight='bold'">备份与恢复数据</a>
	</td>
  </tr>
  <tr id="itembak" style="display:none"> 
    <td class="list1">
		<table border='0' cellspacing='0' cellpadding='0'>
		<?
		if($r[dodbdata])
		{
		?>
        <tr> 
          <td class="file">
			<a href="../../ebak/ChangeDb.php" target="main" onmouseout="this.style.fontWeight=''" onmouseover="this.style.fontWeight='bold'">备份数据</a>
          </td>
        </tr>
		<tr> 
          <td class="file">
			<a href="../../ebak/ReData.php" target="main" onmouseout="this.style.fontWeight=''" onmouseover="this.style.fontWeight='bold'">恢复数据</a>
          </td>
        </tr>
		<tr> 
          <td class="file">
			<a href="../../ebak/ChangePath.php" target="main" onmouseout="this.style.fontWeight=''" onmouseover="this.style.fontWeight='bold'">管理备份目录</a>
          </td>
        </tr>
		<?
		}
		if($r[doexecsql])
		{
		?>
		<tr> 
          <td class="file1">
			<a href="../../db/DoSql.php" target="main" onmouseout="this.style.fontWeight=''" onmouseover="this.style.fontWeight='bold'">执行SQL语句</a>
          </td>
        </tr>
		<?
		}
		if ($r[dophpmyadmin] && (int)$r[groupid]==$trans_admin_groupId) {
		?>
		<tr> 
          <td class="file1">
			<a href="../../phpMyAdmin/index.php" target="_blank" onmouseout="this.style.fontWeight=''" onmouseover="this.style.fontWeight='bold'">数据库管理</a>
			<input name="supperAdmin" type="hidden" value="1" style="display:none">
          </td>
        </tr>
        <?php
		} 
        ?>
      </table>
	</td>
  </tr>
<?
}
?>
</table>
</body>
</html>