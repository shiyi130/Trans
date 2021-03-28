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
<link href="../../../data/menu/menu.css" rel="stylesheet" type="text/css">
<script src="../../../data/menu/menu.js" type="text/javascript"></script>
<SCRIPT lanuage="JScript">
function tourl(url){
	parent.main.location.href=url;
}
</SCRIPT>
</head>
<body onLoad="initialize()">
<table border='0' cellspacing='0' cellpadding='0'>
	<tr height=20>
			<td id="home"><img src="../../../data/images/homepage.gif" border=0></td>
			<td><b>插件管理</b></td>
	</tr>
</table>

<table border='0' cellspacing='0' cellpadding='0'>
<?
if($r[doad])
{
?>
  <tr> 
    <td id="prad" class="menu1" onclick="chengstate('ad')">
		<a onmouseout="this.style.fontWeight=''" onmouseover="this.style.fontWeight='bold'">广告系统</a>
	</td>
  </tr>
  <tr id="itemad" style="display:none"> 
    <td class="list">
		<table border='0' cellspacing='0' cellpadding='0'>
        <tr> 
          <td class="file">
			<a href="../../tool/AdClass.php" target="main" onmouseout="this.style.fontWeight=''" onmouseover="this.style.fontWeight='bold'">管理广告分类</a>
          </td>
        </tr>
		<tr> 
          <td class="file1">
			<a href="../../tool/ListAd.php" target="main" onmouseout="this.style.fontWeight=''" onmouseover="this.style.fontWeight='bold'">管理广告</a>
          </td>
        </tr>
      </table>
	</td>
  </tr>
<?
}
?>

<?
if($r[dovote])
{
?>
  <tr> 
    <td id="prvote" class="menu1" onclick="chengstate('vote')">
		<a onmouseout="this.style.fontWeight=''" onmouseover="this.style.fontWeight='bold'">投票系统</a>
	</td>
  </tr>
  <tr id="itemvote" style="display:none"> 
    <td class="list">
		<table border='0' cellspacing='0' cellpadding='0'>
        <tr> 
          <td class="file">
			<a href="../../tool/AddVote.php?enews=AddVote" target="main" onmouseout="this.style.fontWeight=''" onmouseover="this.style.fontWeight='bold'">增加投票</a>
          </td>
        </tr>
		<tr> 
          <td class="file1">
			<a href="../../tool/ListVote.php" target="main" onmouseout="this.style.fontWeight=''" onmouseover="this.style.fontWeight='bold'">管理投票</a>
          </td>
        </tr>
      </table>
	</td>
  </tr>
<?
}
?>

<?
if($r[dolink])
{
?>
  <tr> 
    <td id="prlink" class="menu1" onclick="chengstate('link')">
		<a onmouseout="this.style.fontWeight=''" onmouseover="this.style.fontWeight='bold'">友情链接管理</a>
	</td>
  </tr>
  <tr id="itemlink" style="display:none"> 
    <td class="list">
		<table border='0' cellspacing='0' cellpadding='0'>
        <tr> 
          <td class="file">
			<a href="../../tool/LinkClass.php" target="main" onmouseout="this.style.fontWeight=''" onmouseover="this.style.fontWeight='bold'">管理友情链接分类</a>
          </td>
        </tr>
		<tr> 
          <td class="file1">
			<a href="../../tool/ListLink.php" target="main" onmouseout="this.style.fontWeight=''" onmouseover="this.style.fontWeight='bold'">管理友情链接</a>
          </td>
        </tr>
      </table>
	</td>
  </tr>
<?
}
?>

<?
if($r[dogbook])
{
?>
  <tr> 
    <td id="prgbook" class="menu1" onclick="chengstate('gbook')">
		<a onmouseout="this.style.fontWeight=''" onmouseover="this.style.fontWeight='bold'">留言板管理</a>
	</td>
  </tr>
  <tr id="itemgbook" style="display:none"> 
    <td class="list">
		<table border='0' cellspacing='0' cellpadding='0'>
        <tr> 
          <td class="file">
			<a href="../../tool/GbookClass.php" target="main" onmouseout="this.style.fontWeight=''" onmouseover="this.style.fontWeight='bold'">管理留言分类</a>
          </td>
        </tr>
		<tr> 
          <td class="file1">
			<a href="../../tool/gbook.php" target="main" onmouseout="this.style.fontWeight=''" onmouseover="this.style.fontWeight='bold'">管理留言</a>
          </td>
        </tr>
      </table>
	</td>
  </tr>
<?
}
?>

<?
if($r[dofeedback]||$r[dofeedbackf])
{
?>
  <tr> 
    <td id="prfeedback" class="menu1" onclick="chengstate('feedback')">
		<a onmouseout="this.style.fontWeight=''" onmouseover="this.style.fontWeight='bold'">信息反馈管理</a>
	</td>
  </tr>
  <tr id="itemfeedback" style="display:none"> 
    <td class="list">
		<table border='0' cellspacing='0' cellpadding='0'>
		<?
		if($r[dofeedbackf])
		{
		?>
        <tr> 
          <td class="file">
			<a href="../../tool/FeedbackClass.php" target="main" onmouseout="this.style.fontWeight=''" onmouseover="this.style.fontWeight='bold'">管理反馈分类</a>
          </td>
        </tr>
		<tr> 
          <td class="file">
			<a href="../../tool/ListFeedbackF.php" target="main" onmouseout="this.style.fontWeight=''" onmouseover="this.style.fontWeight='bold'">管理反馈字段</a>
          </td>
        </tr>
		<?
		}
		if($r[dofeedback])
		{
		?>
		<tr> 
          <td class="file1">
			<a href="../../tool/feedback.php" target="main" onmouseout="this.style.fontWeight=''" onmouseover="this.style.fontWeight='bold'">管理信息反馈</a>
          </td>
        </tr>
		<?
		}
		?>
      </table>
	</td>
  </tr>
<?
}
?>

<?
if($r[donotcj])
{
?>
  <tr> 
    <td id="prnotcj" class="menu3" onclick="chengstate('notcj')">
		<a onmouseout="this.style.fontWeight=''" onmouseover="this.style.fontWeight='bold'">防采集插件</a>
	</td>
  </tr>
  <tr id="itemnotcj" style="display:none"> 
    <td class="list1">
		<table border='0' cellspacing='0' cellpadding='0'>
		<tr> 
          <td class="file1">
			<a href="../../template/NotCj.php" target="main" onmouseout="this.style.fontWeight=''" onmouseover="this.style.fontWeight='bold'">管理防采集随机字符</a>
          </td>
        </tr>
      </table>
	</td>
  </tr>
<?
}
?>
</table>
</body>
</html>