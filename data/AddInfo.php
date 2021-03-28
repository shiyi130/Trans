<?php
define('EmpireCMSAdmin','1');
require("./../class/connect.php");
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
$gr=CheckLevel($logininid,$loginin,$classid,"addinfo");


//追加信息
function InsertInfo($userid,$username){
	global $empire,$dbtbpre;
	//验证权限
	CheckLevel($userid,$username,$classid,"addinfo");
	//追加信息
	$sql=$empire->query("insert into trans_m_phrase(big_category, middle_category, small_category, key_word,
 jp_word, tw_word, cn_word, en_word, kr_word, upd_date, upd_user) values('$_POST[p_big_category]',
 '$_POST[p_middle_category]','$_POST[p_small_category]','$_POST[p_key_word]','$_POST[p_jp_word]',
 '$_POST[p_tw_word]','$_POST[p_cn_word]','$_POST[p_en_word]','$_POST[p_kr_word]',now(),'$username')");
 
	if($sql)
	{
		//操作日志
		insert_dolog("phrase_code=".$phrase_code);
		printerror("add_PhraseSuccess","AddInfo.php");
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}

if($_POST[p_jp_word]||$_POST[p_tw_word]||$_POST[p_cn_word]||$_POST[p_en_word]||$_POST[p_kr_word])
{
	InsertInfo($logininid,$loginin);
}

$r=$empire->fetch1("SELECT MAX(phrase_code)+1 max_phrase_code FROM trans_m_phrase");

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>信息追加</title>
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1">
  <tr>
    <td>位置：信息管理 &gt; <a href="AddInfo.php">信息追加</a></td>
  </tr>
</table>
<form name="form1" method="post" action="AddInfo.php">
  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
    <tr class="header"> 
      <td height="25" colspan="2">信息追加</td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td width="22%" height="25">ID：</td>
      <td width="78%" height="25"><input name="p_phrase_code" style= "background:#F0F0F0;" type="text" id="p_phrase_code" disabled="disabled" value="<?=$r[max_phrase_code]?>" size="32">
        *</td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">大类：</td>
      <td height="25"><input name="p_big_category" type="text" id="p_big_category" value="" size="32" maxLength="2"></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">中类：</td>
      <td height="25"><input name="p_middle_category" type="text" id="p_middle_category" value="" size="32" maxLength="2"></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">小类：</td>
      <td height="25"><input name="p_small_category" type="text" id="p_small_category" value="" size="32" maxLength="2"></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">关键字：</td>
      <td height="25"><input name="p_key_word" type="text" id="p_key_word" value="" size="32" maxLength="200"></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">日本语：</td>
      <td><div align="center" style="width:100%; height:100%;" >
       <textarea name="p_jp_word" style="width:100%; height:100%; overflow-y:visible"></textarea>
        </div></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">中文繁体：</td>
      <td><div align="center" style="width:100%; height:100%;" >
       <textarea name="p_tw_word" style="width:100%; height:100%; overflow-y:visible"></textarea>
        </div></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">中文简体：</td>
      <td><div align="center" style="width:100%; height:100%;" >
       <textarea name="p_cn_word" style="width:100%; height:100%; overflow-y:visible"></textarea>
        </div></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">英语：</td>
      <td><div align="center" style="width:100%; height:100%;" >
       <textarea name="p_en_word" style="width:100%; height:100%; overflow-y:visible"></textarea>
        </div></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">韩国语：</td>
      <td><div align="center" style="width:100%; height:100%;" >
       <textarea name="p_kr_word" style="width:100%; height:100%; overflow-y:visible"></textarea>
        </div></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">&nbsp;</td>
      <td height="25"><input type="submit" name="Submit" value="提交"> <input type="reset" name="Submit2" value="重置"></td>
    </tr>
  </table>
</form>
<?
db_close();
$empire=null;
?>
</body>
</html>

