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
$gr = CheckLevel ( $logininid, $loginin, $classid, "adduser" );

//更新用户
function add_User($parameter,$login_userid,$login_username){
	global $empire,$dbtbpre;
	if(empty($parameter))
	{
		printerror("EnterUserInfo","history.go(-1)");
	}

	//用户名不能为空
	if(empty($parameter[param_username]))
	{
		printerror("EnterUsername","history.go(-1)");
	}

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
			
		}
	}else{
			printerror("EmptyUserpassword","history.go(-1)");
	}
	
	//验证权限
	CheckLevel($login_userid,$login_username,$classid,"adduser");

	//重复用户名的判定
	$num=$empire->gettotal("select count(*) as total from {$dbtbpre}m_user where username='$parameter[param_username]' limit 1");
	if($num)
	{
		printerror("ReUsername","history.go(-1)");
	}
	//用户id fetch1
	$max_UserId=$empire->fetch1("select max(userid) as maxUserId from {$dbtbpre}m_user ");
	$addUserId=(int)$max_UserId[maxUserId]+1;
	
	//-----------增加用户
	//操作界面
	$parameter[param_styleid]=(int)$parameter[param_styleid];
	//adminclass
	$adminclass="1";
	$rnd=make_password(20);
	$salt=make_password(8);
	$parameter[param_password]=md5(md5($parameter[param_password]).$salt);
	//sql文执行,修改用户表
	$sql_user=$empire->query("insert into {$dbtbpre}m_user (userid,username, password,rnd,adminclass,groupid,styleid,salt,truename,email) 
	                          values ('$addUserId','$parameter[param_username]','$parameter[param_password]','$rnd','$adminclass','$parameter[param_groupid]',
	                          '$parameter[param_styleid]','$salt','$parameter[param_truename]','$parameter[param_email]');");
	
	//-----------增加用户组
	//用户组（groupdid）
	$parameter[param_groupid]=(int)$parameter[param_groupid];
	//DB数据管理
	$parameter[param_dodbdata]=(int)$parameter[param_dodbdata];
	//SQL执行
	$parameter[param_doexecsql]=(int)$parameter[param_doexecsql];
	//信息追加
	$parameter[param_doaddinfo]=(int)$parameter[param_doaddinfo];
	//信息删除
	$parameter[param_dodelinfo]=(int)$parameter[param_dodelinfo];
	//信息查询
	$parameter[param_doeditinfo]=(int)$parameter[param_doeditinfo];
	//更新开启或关闭
	$parameter[param_off_update]=(int)$parameter[param_off_update];
	if($parameter[param_doeditinfo]!=0){
		if($parameter[param_off_update]==1 ||$parameter[param_off_update]==2){
			//信息查询
			$parameter[param_doeditinfo]=$parameter[param_off_update];
		}else{
			//信息查询
			$parameter[param_doeditinfo]="";
		}
	}
	
	//创建用户
	$parameter[param_doadduser]=(int)$parameter[param_doadduser];
	//用户设定
	$parameter[param_doedituser]=(int)$parameter[param_doedituser];
	//删除用户
	$parameter[param_dodeluser]=(int)$parameter[param_dodeluser];
	
	//sql文执行
	$sql_group=$empire->query("insert into {$dbtbpre}m_group (groupid,groupname,dodbdata,doexecsql,doaddinfo,dodelinfo,doeditinfo,doadduser,doedituser,dodeluser) 
                               values ('$parameter[param_groupid]','$parameter[param_groupname]','$parameter[param_dodbdata]','$parameter[param_doexecsql]',
                               '$parameter[param_doaddinfo]','$parameter[param_dodelinfo]','$parameter[param_doeditinfo]','$parameter[param_doadduser]',
                               '$parameter[param_doedituser]','$parameter[param_dodeluser]');");
	
	if($sql_group&&$sql_user)
	{
		//操作日志
		insert_dolog("add_username=".$parameter[param_username]);
		printerror("AddUserSuccess","AddUser.php");
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}
/*****************************
 * 初期画面内容设定
 *****************************/
//用户组id
$max_GroupId=$empire->fetch1("select max(groupid) as maxGroupId from {$dbtbpre}m_group ");
$displayGroupid=(int)$max_GroupId[maxGroupId]+1;

//checkbox默认值
$checked="";

//DB数据管理
$check_dodbdata=$checked;
//SQL文执行
$check_doexecsql=$checked;
//信息追加
$check_doaddinfo=$checked;
//信息删除
$check_dodelinfo=$checked;
//信息查询
$check_doeditinfo=$checked;
//更新开启
$check_onupdate=$checked;
//更新关闭
$check_offupdate=$checked;
//创建用户
$check_doadduser=$checked;
//用户设定
$check_doedituser=$checked;
//删除用户
$check_dodeluser=$checked;
	
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
 * 增加用户处理
 *****************************/
//参数取得
$submit_add=$_POST['submit_add'];
if(empty($submit_add))
{
  $submit_add=$_GET['submit_add'];
}

//更新用户信息取得
if($submit_add=="add_user")
{
    //更新用户的信息集合
    $parameter_user="";
	//用户名param_username
	$parameter_user[param_username]=$_POST['param_username'];
	//密码param_password
	$parameter_user[param_password]=$_POST['displypassword'];
	//确认密码
	$parameter_user[param_repassword]=$_POST['param_repassword'];
	//姓名param_truename
	$parameter_user[param_truename]=$_POST['param_truename'];
	//邮箱param_email
	$parameter_user[param_email]=$_POST['param_email'];
	//用户组param_groupid
	$parameter_user[param_groupid]=$_POST['param_groupid'];
	//用户组名param_groupname
	$parameter_user[param_groupname]=$_POST['param_groupname'];
	//操作界面param_styleid
	$parameter_user[param_styleid]=$_POST['param_styleid'];
	//DB数据管理param_dodbdata
	$parameter_user[param_dodbdata]=$_POST['param_dodbdata'];
	//SQL执行param_doexecsql
	$parameter_user[param_doexecsql]=$_POST['param_doexecsql'];
	//信息追加param_doaddinfo
	$parameter_user[param_doaddinfo]=$_POST['param_doaddinfo'];
	//信息删除param_dodelinfo
	$parameter_user[param_dodelinfo]=$_POST['param_dodelinfo'];
	//信息查询param_doeditinfo
	$parameter_user[param_doeditinfo]=$_POST['param_doeditinfo'];
	//更新开启或关闭param_off_update
	$parameter_user[param_off_update]=$_POST['param_off_update'];
	//创建用户param_doadduser
	$parameter_user[param_doadduser]=$_POST['param_doadduser'];
	//用户设定param_doedituser
	$parameter_user[param_doedituser]=$_POST['param_doedituser'];
	//删除用户param_dodeluser
	$parameter_user[param_dodeluser]=$_POST['param_dodeluser'];
	
	//更新用户
	add_User($parameter_user,$logininid,$loginin);
}

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<script language="JavaScript">
//光标焦点变更
function checkText1() {
    document.all("param_groupname").focus();
  }
</script>
<script language="JavaScript">
//重置
function resetElement()
{
	document.all.param_open_update.disabled=true;
	document.all.param_close_update.disabled=true;
}
</script>
<script language="JavaScript">
//radiobutton是否可用
function checkSearchinfo() {
	if(document.all.param_doeditinfo.checked){
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

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css"> 
<title></title>
</head>
<body>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1">
  <tr>
    <td>位置: 用户面板 > 用户管理 > <a href="AddUser.php">创建用户</a></td>
    <td width="50%"><div align="right"></div></td>
  </tr>
</table>
<form name="form1" method="post" action="AddUser.php">
  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder" >
    <tr class="header"> 
      <td height="25" colspan="2">创建用户 </td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td width="19%" height="25">用户名：</td>
      <td width="81%" height="25"> 
        <input name="param_username" type="text" id="param_username" size="38" maxlength="30" >&nbsp;&nbsp;*<font color="#666666">(必须入力)</font>
      </td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">密&nbsp;&nbsp;&nbsp;码：</td>
      <td height="25" name="passwordcolor" id="passwordcolor">
        <input name="displypassword" type="password" id="displypassword" maxlength="10" size="38"><font color="#666666">
        &nbsp;(请入力10位以内的英文字母或数字)</font>
      </td>
    </tr>
    <tr bgcolor="#FFFFFF" id="resetpassword"> 
      <td height="25">确认密码：</td>
      <td height="25">
        <input name="param_repassword" type="password" id="param_repassword" maxlength="10" size="38">
      </td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">姓&nbsp;&nbsp;&nbsp;名：</td>
      <td height="25">
        <input name="param_truename" type="text" id="param_truename" size="38" maxlength="20">
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">邮&nbsp;&nbsp;&nbsp;箱：</td>
      <td height="25">
        <input name="param_email" type="text" id="param_email" size="38" maxlength="120">
     </td>
    </tr>
	<tr bgcolor="#FFFFFF">
      <td height="25">用户组：</td>
      <td height="25" >
        <input name="param_groupid" type="text" id ="param_groupid" readonly="true" style= "background-color:#d0d0d0" size="38" 
             value="<?= $displayGroupid?>" onfocus ="checkText1();">&nbsp;&nbsp;<font color="#666666">*(不可入力)</font>
        </td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td height="25">用户组名称：</td>
      <td height="25" > 
        <input name="param_groupname" type="text" id="param_groupname" size="38" maxlength="50">
      </td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="26">操作界面：</td>
      <td height="26">
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
        <input name="param_dodbdata" type="checkbox" id="param_dodbdata" value="1"<?=$check_dodbdata?>>
        <label for="param_dodbdata">DB数据管理</label>   
        <input name="param_doexecsql" type="checkbox" id="param_doexecsql" value="1"<?=$check_doexecsql?>>
        <label for="param_doexecsql">SQL文执行</label>   
      </td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">信息管理：</td>
      <td height="25">
        <input name="param_doaddinfo" type="checkbox" id="param_doaddinfo" value="1"<?=$check_doaddinfo?>>
        <label for="param_doaddinfo">信息追加</label>
        <input name="param_dodelinfo" type="checkbox" id="param_dodelinfo" value="1"<?=$check_dodelinfo?>>
        <label for="param_dodelinfo">信息删除</label>
        <input name="param_doeditinfo" type="checkbox" id="param_doeditinfo" value="1"<?=$check_doeditinfo?> onclick ="checkSearchinfo();">
        <label for="param_doeditinfo">信息查询</label>&nbsp;(
        <input name="param_off_update" type="radio" id="param_open_update" value="1"<?=$check_offupdate?> disabled=false >
        <label for="param_open_update">更新开启</label>
        <input name="param_off_update" type="radio" id="param_close_update" value="2"<?=$check_onupdate?> disabled=false>
        <label for="param_close_update">更新关闭</label>&nbsp;&nbsp;)
      </td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">用户面板：</td>
      <td height="25">
        <input name="param_doadduser" type="checkbox" id="param_doadduser" value="1"<?=$check_doadduser?>>
        <label for="param_doadduser">创建用户</label>
        <input name="param_doedituser" type="checkbox" id="param_doedituser" value="1"<?=$check_doedituser?>>
        <label for="param_doedituser">设定用户</label>
        <input name="param_dodeluser" type="checkbox" id="param_dodeluser" value="1"<?=$check_dodeluser?>>
        <label for="param_dodeluser">删除用户</label>
      </td>
    </tr>
  </table>
  <br>
   <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1">
     <tr bgcolor="#FFFFFF" border="0"> 
       <td height="25" width="19%">&nbsp;</td>
       <td height="25" width="81%">
         <input type="submit" name="Submit" value="提交" >
         <input name="submit_add" type="hidden" id="submit_add" value="add_user">
         <input type="reset" name="Submit2" value="重置" onclick ="resetElement();">
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
