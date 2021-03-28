<?php
define ( 'EmpireCMSAdmin', '1' );
require ("./../class/connect.php");
require ("./../class/db_sql.php");
require ("./../class/functions.php");
require "../" . LoadLang ( "pub/fun.php" );
$link = db_connect ();
$empire = new mysqlquery ( );
$editor = 1;
//验证用户
$lur = is_login ();
$logininid = $lur ['userid'];
$loginin = $lur ['username'];
$loginrnd = $lur ['rnd'];
$loginlevel = $lur ['groupid'];
$loginadminstyleid = $lur ['adminstyleid'];
//验证权限
$gr = CheckLevel ( $logininid, $loginin, $classid, "edituser" );

/*****************************
 * 用户信息检索
 *****************************/
//关键字
$keyboard = "";
//关键字取得
if ($_GET ['submit1'] == "搜索") {
	$keyboard = $_GET ['keyboard'];
}

//SQL文做成
$strSql = "";
$like = "";
$strSql = $strSql . "select u.userid,u.groupid,u.password,u.username,u.truename,u.email,u.lasttime,
	             u.lastip,g.groupname from {$dbtbpre}m_user u,{$dbtbpre}m_group g ";
//检索条件
if (( int ) $loginlevel == ( int ) $trans_login_level) {
	//超级管理员登录时
	$where = " where u.groupid=g.groupid ";
} else {
	//非超级管理员登录时
	$where = " where u.groupid=g.groupid and g.groupid!='1'";
}
//排序
$order = "order by u.groupid desc";
//SQL文
if ($keyboard == null) {
	$strSql = $strSql . $where . $order;
} else {
	//去掉关键字的空格
	$checknull = trim ( $keyboard );
	//判断关键字是否为空
	if ($checknull == null) {
		//搜索失败
		printerror ( "KEYBOARDNOTNULL", "SearchUser.php" );
	} else {
		$like = " u.username like '%$keyboard%' or u.truename like '%$keyboard%' or u.email like '%$keyboard%'or g.groupname like '%$keyboard%'";
		$strSql = $strSql . $where . " and (" . $like . ")" . $order;
	}
}
//SQL执行
$sql = $empire->query ( $strSql );

/*****************************
 * 页面控制
 *****************************/
//用户groupid
$getgroupid = "";
//用户userid
$getuserid = "";
//取得用户groupid的SQL
$getsql = "";

//页面参数取得
$enews = $_GET ['enews'];

//页面未跳转
if ($enews == "SearchUser") {
	//被修改的用户userid取得
	$getuserid = $_GET ['userid'];
	
	if (( int ) $getuserid == ( int ) $logininid) {
		//不能修改登录用户
		printerror ( "EditLoginUserfail", "history.go(-1)" );
	}
	//被修正用户groupid的取得
	$getsql = $empire->fetch1 ( "select groupid from {$dbtbpre}m_user where userid='$getuserid'" );
	$getgroupid = $getsql [groupid];
	
	//不能修改超级管理员
	if ((int)$getgroupid == (int)$trans_admin_groupId) {
		printerror ( "EditAdminfail", "SearchUser.php" );
	}
	//页面跳转至用户设定页面
	printerror ( "EditUserInfo", "EditUser.php?enews_searchuser=SearchUser&userid=$getuserid" );
}

/*****************************
 * 登录用户能否修改用户权限取得
*****************************/
//取得用户修改权限SQL
$getUserEditLevel="";
//用户修改权限
$userEditLevel="";
$getUserEditLevel = $empire->fetch1 ( "select doedituser from {$dbtbpre}m_group where groupid='$loginlevel'" );
$userEditLevel = $getUserEditLevel [groupid];

?>

<html>
<head>
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css"
	rel="stylesheet" type="text/css">
<title>用户管理</title>
<!--<script src="../ecmseditor/fieldfile/setday.js"></script>-->

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body>
<table width="100%" border="0" align="center" cellpadding="3"
	cellspacing="1">
	<tr>
		<td>位置: 用户面板 > 用户管理 > <a href="SearchUser.php">用户信息查询</a></td>
		<td width="50%">
		<div align="right"></div>
		</td>
	</tr>
</table>

<br>
<form name="searchuserform" method="get" action="SearchUser.php">
<table width="100%" align=center cellpadding=0 cellspacing=0>
	<tr>
	  <td><div align="left">
		关键字：
		<input name="keyboard" type="text" id="keyboard"
			value="<?=$keyboard?>">
		<input name=submit1 type=submit id="submit12" value=搜索>
		</div>
		</td>
	</tr>
</table>
</form>
<form name="form1" method="post" action="SearchUser.php">
<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1" class="tableborder" style="word-warp:break-word; word-break:break-all;">	
	<tr class="header">
		<td width="5%" height="25">
		<div align="center">ID</div>
		</td>
		<td width="10%" height="25">
		<div align="center">用户名</div>
		</td>
		<td width="10%" height="25">
		<div align="center">姓名</div>
		</td>
		<td width="15%" height="25">
		<div align="center">邮箱</div>
		</td>
		<td width="15%" height="25">
		<div align="center">用户组名称</div>
		</td>
		<td width="15%" height="25">
		<div align="center">IP</div>
		</td>
		<td width="15%" height="25">
		<div align="center">最后登录时间</a></div>
		</td>
		<td width="5%" height="25">
		<div align="center">操作</div>
		</td>
	</tr>
    
    <?
    //取得条数
    $num=$empire->num1($sql);
    if($num==0){
    ?>	
      <tr bgcolor="#FFFFFF">
        <td height="30" colSpan=8><div align="center">没有记录</div></td></TR>
	  </tr>
    <?
    }else{
    	if((int)$userEditLevel!=(int)$trans_edituser_level)
    	{
    		while ( $r = $empire->fetch ( $sql ) ) 
    		{
    			$lasttime='---';
    			if($r[lasttime]){
    				$lasttime=date("Y-m-d H:i:s",$r[lasttime]);
    			}
    	
	?>
      <tr bgcolor="#FFFFFF">
		<td width="5%" height="25">
		<div align="center"><?=$r [userid]?></div>
		</td>
		<td width="10%" height="25">
		<div align="left"><?=$r [username]?></div>
		</td>
		<td width="10%" height="25">
		<div align="left"><?=$r [truename]?></div>
 		</td>
		<td width="15%" height="25">
		<div align="left"><?=$r [email]?></div>
		</td>
		<td width="15%" height="25">
		<div align="left"><?=$r [groupname]?></div>
		</td>
		<td width="15%" height="25">
		<div align="left"><?=$r [lastip]?></div>
		</td>
		<td width="15%" height="25">
		<div align="left"><?=$lasttime?></div>
		</td>
		<td width="5%" height="25" >
		<div align="center">[<a href="SearchUser.php?enews=SearchUser&userid=<?=$r [userid]?>"
			 onclick="return confirm('确认要修改此用户？');">修改</a>]</div>
		</td>
	  </tr>
    <?php
    		}
	    }else{
	    	while ( $r = $empire->fetch ( $sql ) ) 
    		{
    			if($r[lasttime]){
    				$r[lasttime]=date("Y-m-d H:i:s",$r[lasttime]);
    			}
	?>
	  <tr bgcolor="#FFFFFF">
		<td width="5%" height="25">
		<div align="center"><?=$r [userid]?></div>
		</td>
		<td width="10%" height="25">
		<div align="left"><?=$r [username]?></div>
		</td>
		<td width="10%" height="25">
		<div align="left"><?=$r [truename]?></div>
		</td>
		<td width="15%" height="25">
		<div align="left"><?=$r [email]?></div>
		</td>
		<td width="15%" height="25">
		<div align="left"><?=$r [groupname]?></div>
		</td>
		<td width="15%" height="25">
		<div align="left"><?=$r [lastip]?></div>
		</td>
		<td width="15%" height="25">
		<div align="left"><?=$r [lasttime]?></div>
		</td>
		<td width="5%" height="25" >
		<div align="center"><font color="#d0d0d0">修改</font></div>
		</td>
	</tr>
	<?php 
    		}
	    }
    }
	?>
    </tr>
</table>
</form>
<?
db_close ();
$empire = null;
?>
</body>
</html>