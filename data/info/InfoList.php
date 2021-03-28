<?php
define('EmpireCMSAdmin','1');
require("../../class/connect.php");
require("../../class/db_sql.php");
require("../../class/functions.php");
require "../../".LoadLang("pub/fun.php");
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
$gr=CheckLevel($logininid,$loginin,$classid,"editinfo");

//删除日志
function upd_Phrase($phrase_code,$userid,$username){
	global $empire,$dbtbpre;
	//验证权限
	CheckLevel($userid,$username,$classid,"editinfo");
	$phrase_code=(int)$phrase_code;
	if(!$phrase_code)
	{
		printerror("Notupd_Phraseid","history.go(-1)");
	}
//	$sql=$empire->query("delete from {$dbtbpre}m_phrase where phrase_code='$phrase_code'");
	$sql=$empire->query("update {$dbtbpre}m_phrase set jpn_word='abc' where phrase_code='$phrase_code'");
	
	if($sql)
	{
		//操作日志
		insert_dolog("phrase_code=".$phrase_code);
		printerror("upd_PhraseSuccess","InfoList.php");
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}

//批量删除日志
function update_all($phrase_code,$userid,$username){
	global $empire,$dbtbpre;
	//验证权限
	CheckLevel($userid,$username,$classid,"editinfo");
	
	if (count($_POST)>=0)
	{
		$code="";
		$jpn="";
		$cht="";
		$chs="";
		$eng="";
		$kor="";
		foreach ($_POST as $k=>$v) 
		{
			$codeInfo= explode('_',$k);
			if (count($codeInfo)>2) 
			{
				if (empty($code)||$code!=$codeInfo[0])
				{
					$code=$codeInfo[0];
					$jpn=$v;
					$cht="";
					$chs="";
					$eng="";
					$kor="";
				}
				elseif(strcmp($code,$codeInfo[0])==0)
				{
					if (strcmp($code._cht_word,$k)==0)
					{
						$cht=$v;
					}
					elseif(strcmp($code._chs_word,$k)==0)
					{
						$chs=$v;
					}
					elseif(strcmp($code._eng_word,$k)==0)
					{
						$eng=$v;
					}
					elseif(strcmp($code._kor_word,$k)==0)
					{
						$kor=$v;
						$sql=$empire->query("update {$dbtbpre}m_phrase set jpn_word='$jpn', cht_word='$cht', chs_word='$chs', eng_word='$eng', kor_word='$kor' where phrase_code='$code'");
						if(!$sql)
						{
								printerror("DbError","history.go(-1)");
						}
					}
				}
//				echo "Current value of ".$v.'<br />';
//		   		echo "Current value of ".$k.'<br />';
			}
		}
		//操作日志
		insert_dolog("");
		printerror("upd_Phrase_Success","InfoList.php");
	}
	else 
	{
		printerror("Not_upd_Phrase_Code","history.go(-1)");
	}
}

//日期删除日志
function upd_Phrase_date($add,$userid,$username){
	global $empire,$dbtbpre;
	//验证权限
	CheckLevel($userid,$username,$classid,"editinfo");
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
		printerror("upd_PhraseSuccess","InfoList.php");
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
if($enews=="upd_Phrase")
{
	$phrase_code=$_GET['phrase_code'];
	upd_Phrase($phrase_code,$logininid,$loginin);
}
//批量语句更新
elseif($enews=="update_all")
{
	$phrase_code=$_POST['phrase_code'];
	update_all($phrase_code,$logininid,$loginin);
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
$query="select phrase_code,jpn_word,cht_word,chs_word,eng_word,kor_word from {$dbtbpre}m_phrase ";
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
		$and=$a?' and ':'';
		$show=$_GET['show'];
		if($show==1)
		{
			$a.=$and."username like '%$keyboard%'";
		}
		elseif($show==2)
		{
			$a.=$and."loginip like '%$keyboard%'";
		}
		else
		{
			$a.=$and."(username like '%$keyboard%' or loginip like '%$keyboard%')";
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
$desc=$mydesc?'asc':'desc';
$orderby=(int)$_GET['orderby'];
if($orderby==1)//日本语
{
	$order="jpn_word ".$desc.",phrase_code desc";
	$jpn_worddesc=$mydesc?0:1;
}
elseif($orderby==2)//中文繁体
{
	$order="cht_word ".$desc.",phrase_code desc";
	$cht_worddesc=$mydesc?0:1;
}
elseif($orderby==3)//中文简体
{
	$order="chs_word ".$desc.",phrase_code desc";
	$chs_worddesc=$mydesc?0:1;
}
elseif($orderby==4)//英文
{
	$order="eng_word ".$desc.",phrase_code desc";
	$eng_worddesc=$mydesc?0:1;
}
elseif($orderby==5)//韩国语
{
	$order="kor_word ".$desc.",phrase_code desc";
	$kor_worddesc=$mydesc?0:1;
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
<link href="../../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css"> 
<title>信息查询</title>
<!--<script src="../ecmseditor/fieldfile/setday.js"></script>-->
<script>
function CheckAll(form)
  {
  for (var i=0;i<form.elements.length;i++)
    {
    var e = form.elements[i];
    if (e.name != 'chkall')
       e.checked = form.chkall.checked;
    }
  }
</script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1">
  <tr> 
    <td>位置：信息管理 &gt; <a href="InfoList.php">信息查询</a></td>
    <td width="50%"><div align="right">
      </div></td>
  </tr>
</table>
  
<br>
<table width="100%" align=center cellpadding=0 cellspacing=0>
  <form name=searchlogform method=get action='InfoList.php'>
    <tr> 
               关键字： 
          <input name="keyboard" type="text" id="keyboard" value="<?=$keyboard?>">
          <select name="show" id="show">
            <option value="0"<?=$show==0?' selected':''?>>不限</option>
            <option value="1"<?=$show==1?' selected':''?>>用户名</option>
            <option value="2"<?=$show==2?' selected':''?>>登陆IP</option>
          </select>
          <input name=submit1 type=submit id="submit12" value=搜索>
          <input name="sear" type="hidden" id="sear" value="1">
        </div></td>
    </tr>
  </form>
</table>
<form name="form2" method="post" action="InfoList.php" onsubmit="return confirm('确认要更新');">
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="1" class="tableborder">
    <tr class="header">
      <td width="5%"><div align="center"><a href="InfoList.php?orderby=0&mydesc=<?=$phrase_codedesc.$search2?>">ID</a></div></td>
      <td height="25"><div align="center"><a href="InfoList.php?orderby=1&mydesc=<?=$jpn_worddesc.$search2?>">日本语</a></div></td>
      <td width="18%"><div align="center"><a href="InfoList.php?orderby=2&mydesc=<?=$cht_worddesc.$search2?>">中文繁体</a></div></td>
      <td width="18%" height="25"><div align="center"><a href="InfoList.php?orderby=3&mydesc=<?=$chs_worddesc.$search2?>">中文简体</a></div></td>
      <td width="18%"><div align="center"><a href="InfoList.php?orderby=4&mydesc=<?=$eng_worddesc.$search2?>">英语</a></div></td>
      <td width="18%"><div align="center"><a href="InfoList.php?orderby=5&mydesc=<?=$kor_worddesc.$search2?>">韩国语</a></div></td>
    </tr>
    <?
  while($r=$empire->fetch($sql))
  {
  ?>
    <tr bgcolor="#FFFFFF" id=<?=$r[phrase_code]._phrase_code?>>
      <td><div align="center"><?=$r[phrase_code]?></div></td>
      <?php
      	if ($gr[doeditinfo]=="1")
      	{
      ?>
       <td><div align="center" style="width:100%; height:100%;" >
       <textarea name=<?=$r[phrase_code]._jpn_word?> style="width:100%; height:100%; overflow-y:visible"><?=$r[jpn_word]?></textarea>
        </div></td>
      <td><div align="center" style="width:100%; height:100%;" >
      <textarea name=<?=$r[phrase_code]._cht_word?> style="width:100%; height:100%; overflow-y:visible" ><?=$r[cht_word]?></textarea>
        </div></td>
      <td><div align="center" style="width:100%; height:100%;"> 
       <textarea name=<?=$r[phrase_code]._chs_word?> style="width:100%; height:100%; overflow-y:visible"><?=$r[chs_word]?></textarea>
        </div></td>
      <td><div align="center" style="width:100%; height:100%;" >
      <textarea name=<?=$r[phrase_code]._eng_word ?> style="width:100%; height:100%; overflow-y:visible" ><?=$r[eng_word]?></textarea>
        </div></td>
      <td><div align="center" style="width:100%; height:100%;"> 
      <textarea name=<?=$r[phrase_code]._kor_word?> style="width:100%; height:100%; overflow-y:visible" ><?=$r[kor_word]?></textarea>
        </div></td>
      <?php
      	}
      	else
      	{ 
      ?>
      <td height="25"><div align="center"><?=$r[jpn_word]?></div></td>
      <td><div align="center"><?=$r[cht_word]?></div></td>
      <td><div align="center"><?=$r[chs_word]?></div></td>
      <td><div align="center"><?=$r[eng_word]?></div></td>
      <td><div align="center"><?=$r[kor_word]?></div></td>
      <?php
      	} 
      ?>
    </tr>
    <?
  }
  ?>
    <tr bgcolor="#FFFFFF"> 
      <td height="25" colspan="6"> 
        <?=$returnpage?>
      <?php
      	if ($gr[doeditinfo]=="1")
      	{
      ?>
        &nbsp;&nbsp; <input type="submit" name="Submit" value="批量更新"> <input name="enews" type="hidden" id="phome" value="update_all"> 
        &nbsp; <input type=checkbox name=chkall value=on onClick=CheckAll(this.form)>
        选中全部 
        <?php
      	} 
        ?>
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