<?php
@include("../../inc/header.php");

/*
		SoftName : EmpireBak
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

E_D("DROP TABLE IF EXISTS `trans_m_phrase_test`;");
E_C("CREATE TABLE `trans_m_phrase_test` (
  `phrase_code` int(11) NOT NULL AUTO_INCREMENT,
  `jpn_word` text,
  `cht_word` text,
  `chs_word` text,
  `eng_word` text,
  `kor_word` text,
  `upd_date` datetime DEFAULT NULL,
  `upd_user` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`phrase_code`)
) ENGINE=MyISAM AUTO_INCREMENT=45 DEFAULT CHARSET=utf8");
E_D("replace into `trans_m_phrase_test` values('1','紹介（日文）djfj','介绍（繁体）','介绍（中文）下载本文档需要登录，并付出相应积分。如何获取积分?下载本文档需要登录，并付出相应积分。如何获取积分?下载本文档需要登录，并付出相应积分。如何获取积分?下载本文档需要登录，并付出相应积分。如何获取积分?','Introduction','12222\r\n\r\nddd','2010-10-12 00:00:00',NULL);");
E_D("replace into `trans_m_phrase_test` values('2','テスト１','21','a','b','d',NULL,NULL);");
E_D("replace into `trans_m_phrase_test` values('3','テスト２','aaaab','d','d','de',NULL,NULL);");
E_D("replace into `trans_m_phrase_test` values('4','12323445aaaddd','bbbcccccppp','oooccc','fadf0000','pppeee',NULL,NULL);");
E_D("replace into `trans_m_phrase_test` values('5','','','','','',NULL,NULL);");
E_D("replace into `trans_m_phrase_test` values('6','','','','','',NULL,NULL);");
E_D("replace into `trans_m_phrase_test` values('7','','','','','',NULL,NULL);");
E_D("replace into `trans_m_phrase_test` values('8','','','','','',NULL,NULL);");
E_D("replace into `trans_m_phrase_test` values('9','','','','','',NULL,NULL);");
E_D("replace into `trans_m_phrase_test` values('10','','','','','',NULL,NULL);");

@include("../../inc/footer.php");
?>