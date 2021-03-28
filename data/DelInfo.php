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
$gr=CheckLevel($logininid,$loginin,$classid,"delinfo");
//删除日志
function DelPhrase($phrase_code,$userid,$username){
	global $empire,$dbtbpre;
	//验证权限
	CheckLevel($userid,$username,$classid,"delinfo");
	$phrase_code=(int)$phrase_code;
	if(!$phrase_code)
	{
		printerror("Notupd_Phraseid","history.go(-1)");
	}
	$sql=$empire->query("delete from {$dbtbpre}m_phrase where phrase_code='$phrase_code'");
	
	if($sql)
	{
		//操作日志
		insert_dolog("phrase_code=".$phrase_code);
		printerror("DeletePhraseSuccess","DelInfo.php");
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}

//批量删除日志
function DelPhrase_all($phrase_code,$userid,$username){
	global $empire,$dbtbpre;
	//验证权限
	CheckLevel($userid,$username,$classid,"delinfo");
	$count=count($phrase_code);
	if(!$count)
	{
		printerror("NotDelLogid","history.go(-1)");
	}
	for($i=0;$i<$count;$i++)
	{
		$add.=" phrase_code='".intval($phrase_code[$i])."' or";
	}
	$add=substr($add,0,strlen($add)-3);
	$sql=$empire->query("delete from {$dbtbpre}m_phrase where".$add);
	if($sql)
	{
		//操作日志
		insert_dolog("");
		printerror("DeleteBakPhraseSuccess","DelInfo.php");
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}

//日期删除日志
function upd_Phrase_date($add,$userid,$username){
	global $empire,$dbtbpre;
	//验证权限
	CheckLevel($userid,$username,$classid,"delinfo");
	$start=RepPostVar($add['startday']);
	$end=RepPostVar($add['endday']);
	if(!$start||!$end)
	{
		printerror('Emptyupd_PhraseTime','');
	}
	$startday=$start.' 00:00:00';
	$endday=$end.' 23:59:59';
	$sql=$empire->query("delete from {$dbtbpre}m_phrase where logintime<='$endday' and logintime>='$startday'");
	if($sql)
	{
		//操作日志
		insert_dolog("time=".$start."~".$end);
		printerror("upd_PhraseSuccess","DelInfo.php");
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}

//补零
function ToAddDateZero($n){
	if($n<10)
	{
		$n='0'.$n;
	}
	return $n;
}

//返回日期
function ReturnLogSelectDate($y,$m,$d){
	//年
	if(empty($y))
	{
		$y=date("Y");
	}
	for($i=2003;$i<=$thisyear+1;$i++)
	{
		$selected='';
		if($i==$y)
		{
			$selected=' selected';
		}
		$r['year'].="<option value='".$i."'".$selected.">".$i."</option>";
	}
	//月
	if(empty($m))
	{
		$m=date("m");
	}
	for($i=1;$i<=12;$i++)
	{
		$selected='';
		$mi=ToAddDateZero($i);
		if($mi==$m)
		{
			$selected=' selected';
		}
		$r['month'].="<option value='".$mi."'".$selected.">".$mi."</option>";
	}
	//日
	if(empty($d))
	{
		$d=date("d");
	}
	for($i=1;$i<=31;$i++)
	{
		$selected='';
		$di=ToAddDateZero($i);
		if($di==$d)
		{
			$selected=' selected';
		}
		$r['day'].="<option value='".$di."'".$selected.">".$di."</option>";
	}
	return $r;
}

$enews=$_POST['enews'];
if(empty($enews))
{$enews=$_GET['enews'];}
//单条语句更新
if($enews=="del_Phrase")
{
	$phrase_code=$_GET['phrase_code'];
	DelPhrase($phrase_code,$logininid,$loginin);
}
//批量语句更新
elseif($enews=="del_all_Phrase")
{
	$phrase_code=$_POST['phrase_code'];
	DelPhrase_all($phrase_code,$logininid,$loginin);
}
elseif($enews=="upd_Phrase_date")
{
	upd_Phrase_date($_POST,$logininid,$loginin);
}

$line=20;//每页显示条数
$page_line=18;//每页显示链接数
$page=(int)$_GET['page'];
$start=0;
$offset=$page*$line;//总偏移量
$query="select phrase_code,big_category,middle_category,small_category,key_word,jp_word,tw_word,cn_word,en_word,kr_word from {$dbtbpre}m_phrase ";
$totalquery="select count(*) as total from {$dbtbpre}m_phrase";
//搜索
$search='';
$where='';
if($_GET['sear']==1)
{
	$search.="&sear=1";
	$a='';
//	$startday=RepPostVar($_GET['startday']);
//	$endday=RepPostVar($_GET['endday']);
//	if($startday&&$endday)
//	{
//		$search.="&startday=$startday&endday=$endday";
//		$a.="upd_date<='".$endday." 23:59:59' and upd_date>='".$startday." 00:00:00'";
//	}
	$keyboard=RepPostVar($_GET['keyboard']);
	if($keyboard)
	{
		$keyboard=trim($keyboard);
		$and=$a?' and ':'';
		$show=$_GET['show'];
		if($show==1)
		{
			$a.=$and."jp_word like '%$keyboard%'";
		}
		elseif($show==2)
		{
			$a.=$and."tw_word like '%$keyboard%'";
		}
		elseif($show==3)
		{
			$a.=$and."cn_word like '%$keyboard%'";
		}
		elseif($show==4)
		{
			$a.=$and."en_word like '%$keyboard%'";
		}
		elseif($show==5)
		{
			$a.=$and."kr_word like '%$keyboard%'";
		}
		else
		{
			$a.=$and."((jp_word like '%$keyboard%')
						or(tw_word like '%$keyboard%')
						or(cn_word like '%$keyboard%')
						or(en_word like '%$keyboard%')
						or(kr_word like '%$keyboard%'))";
		}
		$search.="&keyboard=$keyboard&show=$show";
	}
	if($a)
	{
		$where.=" where ".$a;
	}
	$query.=$where;
	$totalquery.=$where;
}
$search2=$search;
//排序
$mydesc=(int)$_GET['mydesc'];
$desc=$mydesc?'desc':'asc';
$orderby=(int)$_GET['orderby'];
if($orderby==1)//日本语
{
	$order="jp_word ".$desc.",phrase_code desc";
	$jp_worddesc=$mydesc?0:1;
}
elseif($orderby==2)//中文繁体
{
	$order="tw_word ".$desc.",phrase_code desc";
	$tw_worddesc=$mydesc?0:1;
}
elseif($orderby==3)//中文简体
{
	$order="cn_word ".$desc.",phrase_code desc";
	$cn_worddesc=$mydesc?0:1;
}
elseif($orderby==4)//英文
{
	$order="en_word ".$desc.",phrase_code desc";
	$en_worddesc=$mydesc?0:1;
}
elseif($orderby==5)//韩国语
{
	$order="kr_word ".$desc.",phrase_code desc";
	$kr_worddesc=$mydesc?0:1;
}elseif($orderby==6)//大类
{
	$order="big_category ".$desc.",phrase_code desc";
	$big_categorydesc=$mydesc?0:1;
}elseif($orderby==7)//中类
{
	$order="middle_category ".$desc.",phrase_code desc";
	$middle_categorydesc=$mydesc?0:1;
}elseif($orderby==8)//小类
{
	$order="small_category ".$desc.",phrase_code desc";
	$small_categorydesc=$mydesc?0:1;
}elseif($orderby==9)//guanjianzi
{
	$order="key_word ".$desc.",phrase_code desc";
	$key_worddesc=$mydesc?0:1;
}
else//ID
{
	$order="phrase_code ".$desc;
	$phrase_codedesc=$mydesc?0:1;
}
$search.="&orderby=$orderby&mydesc=$mydesc";
$num=$empire->gettotal($totalquery);//取得总条数
$query=$query." order by ".$order." limit $offset,$line";
$sql=$empire->query($query);
$returnpage=page2($num,$line,$page_line,$start,$page,$search);
?>
<html>
<head>
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css"> 
<title>信息查询</title>
<!--<script src="../ecmseditor/fieldfile/setday.js"></script>-->
<script>
function CheckAll(form)
  {
  for (var i=0;i<form.elements.length;i++)
    {
    var e = form.elements[i];
    if (e.name != 'chkall')
       e.checked = form.chkall.	checked;
    }
  }
</script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1">
  <tr> 
    <td>位置：信息管理 &gt; <a href="DelInfo.php">信息删除</a></td>
    <td width="50%"><div align="right">
      </div></td>
  </tr>
</table>
  
<br>
<table width="100%" align=center cellpadding=0 cellspacing=0>
  <form name=searchlogform method=get action='DelInfo.php'>
    <tr> 
               关键字： 
          <input name="keyboard" type="text" id="keyboard" value="<?=$keyboard?>">
          <select name="show" id="show">
          	<option value="0"<?=$show==0?' selected':''?>></option>
            <option value="1"<?=$show==1?' selected':''?>>日本语</option>
            <option value="2"<?=$show==2?' selected':''?>>中文繁体</option>
            <option value="3"<?=$show==3?' selected':''?>>中文简体</option>
            <option value="4"<?=$show==4?' selected':''?>>英语</option>
            <option value="5"<?=$show==5?' selected':''?>>韩国语</option>
          </select>
          <input name=submit1 type=submit id="submit12" value=搜索>
          <input name="sear" type="hidden" id="sear" value="1">
        </div></td>
    </tr>
  </form>
</table>
<form name="form2" method="post" action="DelInfo.php" onsubmit="return confirm('确认要删除选中语句?');">
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="1" class="tableborder">
    <tr class="header">
      <td width="3%"><div align="center"><a href="DelInfo.php?orderby=0&mydesc=<?=$phrase_codedesc.$search2?>">ID</a></div></td>
      <td height="25"><div align="center"><a href="DelInfo.php?orderby=6&mydesc=<?=$big_categorydesc.$search2?>">大类</a></div></td>
      <td height="25"><div align="center"><a href="DelInfo.php?orderby=7&mydesc=<?=$middle_categorydesc.$search2?>">中类</a></div></td>
      <td height="25"><div align="center"><a href="DelInfo.php?orderby=8&mydesc=<?=$small_categorydesc.$search2?>">小类</a></div></td>
      <td height="25"><div align="center"><a href="DelInfo.php?orderby=9&mydesc=<?=$key_worddesc.$search2?>">关键字</a></div></td>
      <td height="25" width="18%"><div align="center"><a href="DelInfo.php?orderby=1&mydesc=<?=$jp_worddesc.$search2?>">日本语</a></div></td>
      <td width="16%"><div align="center"><a href="DelInfo.php?orderby=2&mydesc=<?=$tw_worddesc.$search2?>">中文繁体</a></div></td>
      <td width="16%" height="25"><div align="center"><a href="DelInfo.php?orderby=3&mydesc=<?=$cn_worddesc.$search2?>">中文简体</a></div></td>
      <td width="16%"><div align="center"><a href="DelInfo.php?orderby=4&mydesc=<?=$en_worddesc.$search2?>">英语</a></div></td>
      <td width="8%"><div align="center"><a href="DelInfo.php?orderby=5&mydesc=<?=$kr_worddesc.$search2?>">韩国语</a></div></td>
      <td width="8%" height="25"><div align="center">删除</div></td>
    </tr>
    <?
  while($r=$empire->fetch($sql))
  {
  ?>
    <tr bgcolor="#FFFFFF" id=<?=$r[phrase_code]._phrase_code?>>
      <td><div align="center"><?=$r[phrase_code]?></div></td>
      <td height="25"><div align="center"><?=$r[big_category]?></div></td>
      <td height="25"><div align="center"><?=$r[middle_category]?></div></td>
      <td height="25"><div align="center"><?=$r[small_category]?></div></td>
      <td height="25"><div style="word-warp:break-word; word-break:break-all;" align="center"><?=$r[key_word]?></div></td>
      <td height="25"><div align="center"><?=$r[jp_word]?></div></td>
      <td><div align="center"><?=$r[tw_word]?></div></td>
      <td><div align="center"><?=$r[cn_word]?></div></td>
      <td><div align="center"><?=$r[en_word]?></div></td>
      <td><div align="center"><?=$r[kr_word]?></div></td>
      <td height="25"><div align="center">[<a href=DelInfo.php?enews=del_Phrase&phrase_code=<?=$r[phrase_code]?> onclick="return confirm('确认要删除此语句?');">删除</a> 
          <input name="phrase_code[]" type="checkbox" id="phrase_code[]" value="<?=$r[phrase_code]?>" onclick="if(this.checked){log<?=$r[phrase_code]?>.style.backgroundColor='#DBEAF5';}else{log<?=$r[phrase_code]?>.style.backgroundColor='#ffffff';}">
          ]</div></td>
    </tr>
    <?
  }
  ?>
    <tr bgcolor="#FFFFFF"> 
      <td height="25" colspan="7"> 
        <?=$returnpage?>
        &nbsp;&nbsp; <input type="submit" name="Submit" value="批量删除"> <input name="enews" type="hidden" id="phome" value="del_all_Phrase"> 
        &nbsp; <input type=checkbox name=chkall  id="chkall" value=on onClick=CheckAll(this.form)><label name="lblALlSel" style="position:relative;bottom:3px" for="chkall">选中全部</label>
        </td>
    </tr>
  </table>
</form>
<?
db_close();
$empire=null;
?>
</body>
</html>