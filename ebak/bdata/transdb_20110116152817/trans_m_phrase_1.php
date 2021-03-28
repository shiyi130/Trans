<?php
@include("../../inc/header.php");
E_D("DROP TABLE IF EXISTS `trans_m_phrase`;");
E_C("CREATE TABLE `trans_m_phrase` (
  `phrase_code` int(11) NOT NULL AUTO_INCREMENT,
  `jpn_word` text,
  `cht_word` text,
  `chs_word` text,
  `eng_word` text,
  `kor_word` text,
  `upd_date` datetime DEFAULT NULL,
  `upd_user` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`phrase_code`)
) ENGINE=MyISAM AUTO_INCREMENT=58 DEFAULT CHARSET=utf8");
E_D("replace into `trans_m_phrase` values('1','紹介（日文）djfj','介绍（繁体）','1、	此表填写一式两份，分别报送社保机构政教部门和财务部门各一份\r\n2、	养老保险基金转往外省市需提供转入地社保经办机构开户银行详细信息，并必须经过社保经办机构盖章确认。\r\n','介绍（中文）下载本文档需要登录','介绍（中文）下载本文档需要登录','0000-00-00 00:00:00','Introduction');");
E_D("replace into `trans_m_phrase` values('2','テスト１','21','a','b','d',NULL,NULL);");
E_D("replace into `trans_m_phrase` values('3','テスト２','aaaab','d','d','de',NULL,NULL);");
E_D("replace into `trans_m_phrase` values('4','12323445aaaddd','bbbcccccppp','oooccc','fadf0000','pppeee',NULL,NULL);");
E_D("replace into `trans_m_phrase` values('5','日本语','中文繁体','中文简体','英语','韩国语',NULL,NULL);");
E_D("replace into `trans_m_phrase` values('6','','','','','',NULL,NULL);");
E_D("replace into `trans_m_phrase` values('7','','','','','',NULL,NULL);");
E_D("replace into `trans_m_phrase` values('8','','','','','',NULL,NULL);");
E_D("replace into `trans_m_phrase` values('9','','','','','',NULL,NULL);");
E_D("replace into `trans_m_phrase` values('10','','','','','',NULL,NULL);");
E_D("replace into `trans_m_phrase` values('11','','','','','',NULL,NULL);");
E_D("replace into `trans_m_phrase` values('12','呆呆地','','','','',NULL,NULL);");
E_D("replace into `trans_m_phrase` values('13','','','','','',NULL,NULL);");
E_D("replace into `trans_m_phrase` values('14','','','','','',NULL,NULL);");
E_D("replace into `trans_m_phrase` values('15','','','','','',NULL,NULL);");
E_D("replace into `trans_m_phrase` values('16','','','','','',NULL,NULL);");
E_D("replace into `trans_m_phrase` values('17','','','','','',NULL,NULL);");
E_D("replace into `trans_m_phrase` values('18','','','','','',NULL,NULL);");
E_D("replace into `trans_m_phrase` values('19','','','','','',NULL,NULL);");
E_D("replace into `trans_m_phrase` values('20','','','','','',NULL,NULL);");
E_D("replace into `trans_m_phrase` values('21','','','','','',NULL,NULL);");
E_D("replace into `trans_m_phrase` values('22','','','','','',NULL,NULL);");
E_D("replace into `trans_m_phrase` values('23','','','','','',NULL,NULL);");
E_D("replace into `trans_m_phrase` values('24','日本语24','','','','',NULL,NULL);");
E_D("replace into `trans_m_phrase` values('25',NULL,NULL,NULL,NULL,NULL,NULL,NULL);");
E_D("replace into `trans_m_phrase` values('26',NULL,NULL,NULL,NULL,NULL,NULL,NULL);");
E_D("replace into `trans_m_phrase` values('27',NULL,NULL,NULL,NULL,NULL,NULL,NULL);");
E_D("replace into `trans_m_phrase` values('28','04-2240-8222
04-2241-2071 （海外对应）

崎玉县川口市芝園町3-1-1014

080-4111-5588，张国峰

未払い金額がありそうです。。。。。。
支払うことは全部王さんに任せて

名字，生日，电话，卡号
',NULL,NULL,NULL,NULL,NULL,NULL);");
E_D("replace into `trans_m_phrase` values('29',NULL,NULL,NULL,NULL,NULL,NULL,NULL);");
E_D("replace into `trans_m_phrase` values('30',NULL,NULL,NULL,NULL,NULL,NULL,NULL);");
E_D("replace into `trans_m_phrase` values('31',NULL,NULL,NULL,NULL,NULL,NULL,NULL);");
E_D("replace into `trans_m_phrase` values('32',NULL,NULL,NULL,NULL,NULL,NULL,NULL);");
E_D("replace into `trans_m_phrase` values('33',NULL,NULL,NULL,NULL,NULL,NULL,NULL);");
E_D("replace into `trans_m_phrase` values('34',NULL,NULL,NULL,NULL,NULL,NULL,NULL);");
E_D("replace into `trans_m_phrase` values('35',NULL,NULL,NULL,NULL,NULL,NULL,NULL);");
E_D("replace into `trans_m_phrase` values('36',NULL,NULL,NULL,NULL,NULL,NULL,NULL);");
E_D("replace into `trans_m_phrase` values('37',NULL,NULL,NULL,NULL,NULL,NULL,NULL);");
E_D("replace into `trans_m_phrase` values('38',NULL,NULL,NULL,NULL,NULL,NULL,NULL);");
E_D("replace into `trans_m_phrase` values('39',NULL,NULL,NULL,NULL,NULL,NULL,NULL);");
E_D("replace into `trans_m_phrase` values('40',NULL,NULL,NULL,NULL,NULL,NULL,NULL);");
E_D("replace into `trans_m_phrase` values('41',NULL,NULL,NULL,NULL,NULL,NULL,NULL);");
E_D("replace into `trans_m_phrase` values('42',NULL,NULL,NULL,NULL,NULL,NULL,NULL);");
E_D("replace into `trans_m_phrase` values('43',NULL,NULL,NULL,NULL,NULL,NULL,NULL);");
E_D("replace into `trans_m_phrase` values('44',NULL,NULL,NULL,NULL,NULL,NULL,NULL);");
E_D("replace into `trans_m_phrase` values('45','','','','','','0000-00-00 00:00:00','');");
E_D("replace into `trans_m_phrase` values('46','','','','','','0000-00-00 00:00:00','');");
E_D("replace into `trans_m_phrase` values('47','','','','','','0000-00-00 00:00:00','');");
E_D("replace into `trans_m_phrase` values('48','','','','','','0000-00-00 00:00:00','');");
E_D("replace into `trans_m_phrase` values('49','','','','','','0000-00-00 00:00:00','');");
E_D("replace into `trans_m_phrase` values('50','','','','','','0000-00-00 00:00:00','');");
E_D("replace into `trans_m_phrase` values('51','','','','','','0000-00-00 00:00:00','');");
E_D("replace into `trans_m_phrase` values('52','','','','','','0000-00-00 00:00:00','');");
E_D("replace into `trans_m_phrase` values('53','','','','','','0000-00-00 00:00:00','');");
E_D("replace into `trans_m_phrase` values('54','わたしは楽聖','','','','','0000-00-00 00:00:00','');");
E_D("replace into `trans_m_phrase` values('55','','','','','','0000-00-00 00:00:00','');");
E_D("replace into `trans_m_phrase` values('56','','','','','','0000-00-00 00:00:00','');");
E_D("replace into `trans_m_phrase` values('57','','持之以恒','赫赫','','','0000-00-00 00:00:00','');");
@include("../../inc/footer.php");
?>
