<?php
define('EmpireCMSAdmin','1');
require("./../class/connect.php");
require("./../class/config.php");
require("./../class/db_sql.php");
require("./../class/functions.php");
require "../".LoadLang("pub/fun.php");
$link=db_connect();
$empire=new mysqlquery();
$editor=1;

//验证用户
$lur=is_login();
$logininid=$lur['userid'];
$loginin=$lur['username'];
$loginrnd=$lur['rnd'];
$loginlevel=$lur['groupid'];
$loginadminstyleid=$lur['adminstyleid'];

//验证权限
$gr=CheckLevel($logininid,$loginin,$classid,"deluser");

//删除用户组
function DelUser($del_userid,$login_userid,$username){
	global $empire,$dbtbpre,$trans_admin_groupId;
	$del_userid=(int)$del_userid;
	if(empty($del_userid))
	{
		printerror("NotDelUserid","history.go(-1)");
	}
	//验证权限
	CheckLevel($login_userid,$username,$classid,"deluser");
	
	$r=$empire->fetch1("select * from {$dbtbpre}m_user where userid='$del_userid'");
	
	//不能对超级管理员进行删除
	if((int)$r[groupid]==(int)$trans_admin_groupId){
		printerror("DelAdminfail","DeleteUser.php");
	}else{
		//用户删除
		if((int)$r[userid]==(int)$login_userid){
			//删除当前登录用户失败
			printerror("DelUserfail","DeleteUser.php");
		}else {
			//用户删除成功
			$sql=$empire->query("delete from {$dbtbpre}m_user where userid='$del_userid'");
			$sql=$empire->query("delete from {$dbtbpre}m_group where groupid='$r[groupid]'");
		}
	}
	
	if($sql)
	{
		//操作日志
		insert_dolog("groupid=".$del_userid."<br>groupname=".$r[username]);
		printerror("DelUserSuccess","DeleteUser.php");
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}

$enews=$_GET['enews'];

if($enews=="DelUser"){
  //删除用户组
  $del_userid=$_GET['userid'];
  DelUser($del_userid,$logininid,$loginin);
}
if((int)$loginlevel==(int)$trans_login_level){
	$sql=$empire->query("select u.userid, u.groupid,username,g.groupname,loginnum,lasttime,lastip from {$dbtbpre}m_user u,{$dbtbpre}m_group g where u.groupid=g.groupid  order by u.groupid desc");
}else{
	$sql=$empire->query("select u.userid, u.groupid,username,g.groupname,loginnum,lasttime,lastip from {$dbtbpre}m_user u,{$dbtbpre}m_group g where u.groupid=g.groupid and u.groupid != '1' order by u.groupid desc");
}

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css"> 
<title></title>
</head>
<body>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1">
  <tr>
    <td>位置: 用户面板 > 用户管理 > <a href="DeleteUser.php"> 删除用户</a></td>
    <td width="50%"><div align="right"></div></td>
  </tr>
</table>
<form name="form1" method="post" action="DeletUser.php">
  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder" style="word-warp:break-word; word-break:break-all;">
    <tr class="header">
      <td width="5%"><div align="center">ID</div></td>
      <td width="20%" height="25"><div align="center">用户名</div></td>
      <td width="25%" height="25"><div align="center">用户组名称</div></td>
      <td width="9%" height="25"><div align="center">登陆次数</div></td>
      <td width="25%" height="25"><div align="center">最后登录</div></td>
      <td width="15%" height="25"><div align="center">操作</div></td>
    </tr>
    <?
    	while($s=$empire->fetch($sql)){
    		$lasttime='---';
    		if($s[lasttime]){
    			$lasttime=date("Y-m-d H:i:s",$s[lasttime]);
    		}
    ?>
    <tr bgcolor="#FFFFFF">
      <td height="25"><div align="center"><?=$s[userid]?></div></td>
      <td height="25"><div align="center"><?=$s[username]?></div></td>
      <td height="25"><div align="left"><?=$s[groupname]?></div></td>
      <td height="25"><div align="center"><?=$s[loginnum]?></div></td>
      <td height="25"><div align="left">时间:<?=$lasttime?><br>IP:<?=$s[lastip]?></div></td>
      <td height="25"><div align="center"> 
        [<a href="DeleteUser.php?enews=DelUser&userid=<?=$s[userid]?>" onclick="return confirm('确认要删除此用户？');">删除</a>]</div>
      </td>
    </tr>
    <?
        }
    ?>
  </table>
</form>
  <?
  db_close();
  $empire=null;
  ?>
</body>
</html>