<?php
define('EmpireCMSAdmin','1');
require("./../class/connect.php");
require("./../class/db_sql.php");
require("./../class/config.php");
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
$gr = CheckLevel ( $logininid, $loginin, $classid, "edituser" );
//更新用户
function upd_User($parameter,$login_userid,$login_username){
	global $empire,$dbtbpre,$trans_admin_groupId;
	$parameter[userid]=(int)$parameter[userid];
	if(empty($parameter))
	{
		printerror("EnterUserInfo","history.go(-1)");
	}

	//用户名不能为空
	if(empty($parameter[param_name]))
	{
		printerror("EnterUsername","history.go(-1)");
	}
	
    if (( int ) $parameter[userid] == ( int ) $login_userid) {
		//不能修改登录用户
		printerror ( "EditLoginUserfail", "SearchUser.php" );
	}	
	//不能修改超级管理员
	if((int)$parameter[param_usergroup]==(int)$trans_admin_groupId)
	{
		printerror("EditAdminfail","SearchUser.php");
	}
	//验证权限
	CheckLevel($login_userid,$login_username,$classid,"edituser");
    //修改用户名
	if(strcmp($parameter[oldusername],$parameter[param_name])!=0)
	{
		$num=$empire->gettotal("select count(*) as total from {$dbtbpre}m_user where username='$parameter[param_name]' and userid<>$parameter[userid] limit 1");
		if($num)
		{
			printerror("ReUsername","history.go(-1)");
		}
		//修改日志
		$lsql=$empire->query("update {$dbtbpre}m_log set username='$parameter[param_name]' where username='$parameter[oldusername]'");
		$lsql=$empire->query("update {$dbtbpre}m_dolog set username='$parameter[param_name]' where username='$parameter[oldusername]'");
	}
	//修改密码的判定
	if(!empty($parameter[edit_password])){
		if(!empty($parameter[param_password])){
			//判定密码是否正确
			$lenth_password=strlen($parameter[param_password]);
			$regs="^[-A-Za-z0-9_]";
			for($i = 1;$i < $lenth_password;$i++) {
				$regs=$regs."+[-A-Za-z0-9_]";
			}
			if(ereg($regs, $parameter[param_password])==false){
				printerror("reEnterpassword","history.go(-1)");
			}
			//密码比较
			if(strcmp($parameter[param_password],$parameter[param_repassword])!=0)
			{
				printerror("NotRepassword","history.go(-1)");
			}else{
				$salt=make_password(8);
				$parameter[param_password]=md5(md5($parameter[param_password]).$salt);
			}
		}else{
			printerror("EmptyUserpassword","history.go(-1)");
		}
	}else{
		$user_r=$empire->fetch1("select salt from {$dbtbpre}m_user where userid ='$parameter[userid]'");
		$salt = $user_r['salt'];
		$parameter[param_password]=$parameter[oldpassword];
	}
	
	//-----------修改用户表
	//操作界面
	$parameter[param_styleid]=(int)$parameter[param_styleid];
	//sql文执行,修改用户表
	$sql_user=$empire->query("update {$dbtbpre}m_user set username= '$parameter[param_name]', password= '$parameter[param_password]' ,
	                   truename= '$parameter[param_truename]',email= '$parameter[param_email]',styleid='$parameter[param_styleid]',salt='$salt'
	                    where userid ='$parameter[userid]'");
	
	//-----------修改用户组表
	//用户组（groupdi）
	$parameter[param_usergroup]=(int)$parameter[param_usergroup];
	//DB数据管理
	$parameter[param_crDBrecord]=(int)$parameter[param_crDBrecord];
	//SQL执行
	$parameter[param_runSQL]=(int)$parameter[param_runSQL];
	//信息追加
	$parameter[param_addinfo]=(int)$parameter[param_addinfo];
	//信息删除
	$parameter[param_delinfo]=(int)$parameter[param_delinfo];
	//信息查询
	$parameter[param_searchinfo]=(int)$parameter[param_searchinfo];
	//更新开启或关闭
	$parameter[param_off_update]=(int)$parameter[param_off_update];
	if($parameter[param_searchinfo]!=0){
		if($parameter[param_off_update]==1 ||$parameter[param_off_update]==2){
			//信息查询
			$parameter[param_searchinfo]=$parameter[param_off_update];
		}else{
			//信息查询
			$parameter[param_searchinfo]="";
		}
	}
	
	//创建用户
	$parameter[param_createuser]=(int)$parameter[param_createuser];
	//用户设定
	$parameter[param_setuser]=(int)$parameter[param_setuser];
	//删除用户
	$parameter[param_deluser]=(int)$parameter[param_deluser];
	
	//sql文执行
	$sql_group=$empire->query("update {$dbtbpre}m_group set groupname='$parameter[param_usergroupname]',dodbdata='$parameter[param_crDBrecord]',
	                          doexecsql='$parameter[param_runSQL]',doaddinfo='$parameter[param_addinfo]',dodelinfo='$parameter[param_delinfo]',
	                          doeditinfo='$parameter[param_searchinfo]',doadduser='$parameter[param_createuser]',doedituser='$parameter[param_setuser]',
	                          dodeluser='$parameter[param_deluser]' where groupid='$parameter[param_usergroup]'");
	
	if($sql_group&&$sql_user)
	{
		//操作日志
		insert_dolog("update_userid=".$parameter[userid]);
		printerror("EditUserSuccess","SearchUser.php");
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}
/*****************************
 * 初期画面内容设定
 *****************************/
//-----页面参数取得
$enews = $_GET ['enews_searchuser'];
if($enews == "SearchUser"){
	$get_userid=$_GET ['userid'];
}
//SQL文做成
$strSql = "";
$strSql = $strSql . "select u.userid,u.groupid,u.password,u.username,u.truename,u.email,u.styleid,u.lasttime,
	             u.lastip,g.groupname,g.dodbdata,g.doexecsql,g.doaddinfo,g.dodelinfo,g.doeditinfo,g.doadduser,
	             g.dodeluser,g.doedituser from {$dbtbpre}m_user u,{$dbtbpre}m_group g ";
//检索条件
$where = " where u.groupid=g.groupid and u.userid = '$get_userid' ";
//排序
$order = "order by u.groupid desc";
//SQL执行
$strSql=$strSql.$where.$order;
$sqlResult = $empire->fetch1( $strSql );

//-----操作界面下拉菜单做成
$stylesql="";
$stylesql=$empire->query("select styleid,stylename,path from {$dbtbpre}m_adminstyle order by styleid");
$style="";
$styler="";
while($styler=$empire->fetch($stylesql))
{
	if($sqlResult[styleid]==$styler[styleid])
	{
		$sselect=" selected";
	}
	else
	{
		$sselect="";
	}
	$style.="<option value=".$styler[styleid].$sselect.">".$styler[stylename]."</option>";
}
/*****************************
 * 权限内容设定处理
 *****************************/
//默认值
$checked="";

//DB数据管理
$dodbdata=$checked;
//SQL文执行
$doexecsql=$checked;
//信息追加
$doaddinfo=$checked;
//信息删除
$dodelinfo=$checked;
//信息查询
$doeditinfo=$checked;
//更新开启
$on_update=$checked;
//更新关闭
$off_update=$checked;
//创建用户
$doadduser=$checked;
//用户设定
$doedituser=$checked;
//删除用户
$dodeluser=$checked;
//checkbox选定值
$checkbox_true="checked";
//DB数据管理
if($sqlResult[dodbdata]){
	$dodbdata=$checkbox_true;
}
//SQL文执行
if($sqlResult[doexecsql]){
	$doexecsql=$checkbox_true;
}
//信息追加
if($sqlResult[doaddinfo]){
	$doaddinfo=$checkbox_true;
}
//信息删除
if($sqlResult[dodelinfo]){
	$dodelinfo=$checkbox_true;
}
//信息查询
if((int)$sqlResult[doeditinfo]==1||(int)$sqlResult[doeditinfo]==2){
	//checkbox信息查询
	$doeditinfo=$checkbox_true;
	
	if((int)$sqlResult[doeditinfo]==1){
		//更新开启
		$on_update=$checkbox_true;
	}else{
		//更新关闭
		$off_update=$checkbox_true;
	}
}
//创建用户
if($sqlResult[doadduser]){
	$doadduser=$checkbox_true;
}
//用户设定
if($sqlResult[doedituser]){
	$doedituser=$checkbox_true;
}
//删除用户
if($sqlResult[dodeluser]){
	$dodeluser=$checkbox_true;
}

/*****************************
 * 用户更新处理
 *****************************/
//参数取得
$submit_update=$_POST['submit_update'];
if(empty($submit_update))
{
  $submit_update=$_GET['submit_update'];
}

//更新用户信息取得
if($submit_update=="update_user")
{
    //更新用户的信息集合
    $parameter_user="";
    //用户id
    $parameter_user[userid]=$_POST['param_userid'];
	//用户名param_name
	$parameter_user[param_name]=$_POST['param_name'];
	//旧用户名oldusername
	$parameter_user[oldusername]=$_POST['oldusername'];
	//密码修正checkbox
	$parameter_user[edit_password]=$_POST['edit_password'];
	//密码param_password
	$parameter_user[param_password]=$_POST['displypassword'];
	//确认密码
	$parameter_user[param_repassword]=$_POST['param_repassword'];
	//旧密码
	$parameter_user[oldpassword]=$_POST['param_password'];
	//姓名param_truename
	$parameter_user[param_truename]=$_POST['param_truename'];
	//邮箱param_email
	$parameter_user[param_email]=$_POST['param_email'];
	//用户组param_usergroup
	$parameter_user[param_usergroup]=$_POST['param_usergroup'];
	//用户组名param_usergroupname
	$parameter_user[param_usergroupname]=$_POST['param_usergroupname'];
	//操作界面param_styleid
	$parameter_user[param_styleid]=$_POST['param_styleid'];
	//DB数据管理param_crDBrecord
	$parameter_user[param_crDBrecord]=$_POST['param_crDBrecord'];
	//SQL执行param_runSQL
	$parameter_user[param_runSQL]=$_POST['param_runSQL'];
	//信息追加param_addinfo
	$parameter_user[param_addinfo]=$_POST['param_addinfo'];
	//信息删除param_delinfo
	$parameter_user[param_delinfo]=$_POST['param_delinfo'];
	//信息查询param_searchinfo
	$parameter_user[param_searchinfo]=$_POST['param_searchinfo'];
	//更新开启或关闭param_off_update
	$parameter_user[param_off_update]=$_POST['param_off_update'];
	//创建用户param_createuser
	$parameter_user[param_createuser]=$_POST['param_createuser'];
	//用户设定param_setuser
	$parameter_user[param_setuser]=$_POST['param_setuser'];
	//删除用户param_deluser
	$parameter_user[param_deluser]=$_POST['param_deluser'];
	
	//更新用户
	upd_User($parameter_user,$logininid,$loginin);
}

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<script language="JavaScript">
//光标焦点变更
function checkText1() {
    document.all("param_usergroupname").focus();
  }
</script>

<script language="JavaScript">
//radiobutton是否可用
function checkSearchinfo() {
	if(document.all.param_searchinfo.checked){
		document.all.param_open_update.disabled=false;
		document.all.param_close_update.disabled=false;
		//默认更新关闭选中
		document.all.param_close_update.checked=true;
	}else{
		document.all.param_open_update.disabled=true;
		document.all.param_close_update.disabled=true;
	}
}
</script>
<script language="JavaScript">
//重置
function resetElement()
{
	var a = document.getElementsByName("reset_update");
	if (a[0].value!=0)
	{
		document.all.param_open_update.disabled=false;
		document.all.param_close_update.disabled=false;
	}
	else
	{
		document.all.param_open_update.disabled=true;
		document.all.param_close_update.disabled=true;
	}
	//密码栏背景色
	document.all.displypassword.style.backgroundColor ="#d0d0d0";
	//密码不可用
	document.all.displypassword.disabled=true;
	//修改密码不显示
	document.getElementById("resetpassword").style.display="none";
}
</script>
<script language="JavaScript">
//修改密码
function editPassword()
{
	if(document.all.editpassword.checked){	
		//背景色
		document.all.displypassword.style.backgroundColor ="#FFFFFF";
		//密码值清空
		document.all.displypassword.value ="";
		//密码
		document.all.displypassword.disabled=false;
		//修改密码
		document.getElementById("resetpassword").style.display="block";
	
	}else{
		//背景色
		document.all.displypassword.style.backgroundColor ="#d0d0d0";
		//密码值清空
		document.all.displypassword.value ="123456";
		//密码不可入力
		document.all.displypassword.disabled=true;
		//修改密码
		document.getElementById("resetpassword").style.display="none";
	}
}
</script>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css"> 
<title></title>
</head>
<body onload="editPassword()">
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1">
  <tr>
    <td>位置: 用户面板 > 用户管理 > <a href="EditUser.php">设定用户</a></td>
    <td width="50%"><div align="right"></div></td>
  </tr>
</table>
<form name="form1" method="post" action="EditUser.php">
  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder" >
    <tr class="header"> 
      <td height="25" colspan="2">设定用户 
        <input name="param_userid" type="hidden" id="param_userid" value="<?=$get_userid?>"></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td width="19%" height="25">用户名：</td>
      <td width="81%" height="25"> 
        <input name="param_name" type="text" id="param_name" size="38" maxlength="30" value="<?= $sqlResult[username]?>">&nbsp;&nbsp;*<font color="#666666">(必须入力)</font>
        <input name="oldusername" type="hidden" id="oldusername" value="<?=$sqlResult[username]?>"> 
      </td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">密&nbsp;&nbsp;&nbsp;码：</td>
      <td height="25" name="passwordcolor" id="passwordcolor">
        <input name="displypassword" type="password" id="displypassword" maxlength="10" size="38" value="123456" style= "background-color:#d0d0d0" disabled="true">
        <input name="param_password" type="hidden" id="param_password" maxlength="32" size="38" value="<?= $sqlResult[password]?>">
        <input name="edit_password" type="checkbox" id="editpassword" onclick="editPassword();"><label for="editpassword">是否修改密码</label>
        &nbsp;(请入力10位以内的英文字母或数字)</font>
      </td>
    </tr>
    <tr bgcolor="#FFFFFF" id="resetpassword" style="display:none"> 
      <td height="25">确认密码：</td>
      <td height="25">
        <input name="param_repassword" type="password" id="param_repassword" maxlength="10" size="38">
      </td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">姓&nbsp;&nbsp;&nbsp;名：</td>
      <td height="25">
        <input name="param_truename" type="text" id="param_truename" size="38" maxlength="20" value="<?= $sqlResult[truename]?>">
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">邮&nbsp;&nbsp;&nbsp;箱：</td>
      <td height="25">
        <input name="param_email" type="text" id="param_email" size="38" maxlength="120" value="<?= $sqlResult[email]?>">
     </td>
    </tr>
	<tr bgcolor="#FFFFFF">
      <td height="25">用户组：</td>
      <td height="25" >
        <input name="param_usergroup" type="text" readonly="true" style= "background-color:#d0d0d0" size="38" 
             value="<?= $sqlResult[groupid]?>" onfocus ="checkText1();">&nbsp;&nbsp;<font color="#666666">*(不可入力)</font>
        </td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td height="25">用户组名称：</td>
      <td height="25" > 
        <input name="param_usergroupname" type="text" id="param_usergroupname" size="38" maxlength="50" value="<?= $sqlResult[groupname]?>">
      </td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">操作界面：</td>
      <td height="25">
         <select name="param_styleid" id="param_styleid" style="width:150px") disabled=true>
          <?=$style?>
         </select>
      </td>
    </tr>
    
  </table>
  <br>
  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
    <tr class="header"> 
      <td height="25" colspan="2">权限设定</td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td width="19%" height="25">系统设置：</td>
      <td width="81%" height="25"> 
        <input name="param_crDBrecord" type="checkbox" id="param_crDBrecord" value="1"<?=$dodbdata?>>
        <label for="param_crDBrecord">DB数据管理</label>   
        <input name="param_runSQL" type="checkbox" id="param_runSQL" value="1"<?=$doexecsql?>>
        <label for="param_runSQL">SQL文执行</label>   
      </td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">信息管理：</td>
      <td height="25">
        <input name="param_addinfo" type="checkbox" id="param_addinfo" value="1"<?=$doaddinfo?>>
        <label for="param_addinfo">信息追加</label>
        <input name="param_delinfo" type="checkbox" id="param_delinfo" value="1"<?=$dodelinfo?>>
        <label for="param_delinfo">信息删除</label>
        <input name="param_searchinfo" type="checkbox" id="param_searchinfo" value="1"<?=$doeditinfo?> onclick ="checkSearchinfo();">
        <label for="param_searchinfo">信息查询</label>&nbsp;(

        <input name="param_off_update" type="radio" id="param_open_update" 
             value="1"<?=$on_update?> <?if($doeditinfo!=$checkbox_true){?>disabled=false <?php }?>>
        <label for="param_open_update">更新开启</label>
        <input name="param_off_update" type="radio" id="param_close_update"
             value="2"<?=$off_update?><?if($doeditinfo!=$checkbox_true){?>disabled=false <?php }?>>
        <label for="param_close_update">更新关闭</label>&nbsp;&nbsp;)
      </td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">用户面板：</td>
      <td height="25">
        <input name="param_createuser" type="checkbox" id="param_createuser" value="1"<?=$doadduser?>>
        <label for="param_createuser">创建用户</label>
        <input name="param_setuser" type="checkbox" id="param_setuser" value="1"<?=$doedituser?>>
        <label for="param_setuser">设定用户</label>
        <input name="param_deluser" type="checkbox" id="param_deluser" value="1"<?=$dodeluser?>>
        <label for="param_deluser">删除用户</label>
      </td>
    </tr>
  </table>
  <br>
   <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1">
     <tr bgcolor="#FFFFFF" border="0"> 
       <td height="25" width="19%">&nbsp;</td>
       <td height="25" width="81%">
         <input type="submit" name="Submit" value="提交" >
         <input name="submit_update" type="hidden" id="submit_update" value="update_user">
         <input type="reset" name="Submit2" value="重置" onclick ="resetElement();">
         <input name="reset_update" type="hidden" id="reset_update" value=<?=$doeditinfo?>>
       </td>
     </tr>
  </table>
</form>
<?
db_close ();
$empire = null;
?>
</body>
</html>
