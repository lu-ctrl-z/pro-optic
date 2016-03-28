CREATE TABLE t_colet_contactor(
id int(11) primary key NOT NULL auto_increment comment '項番',
contactor_CD varchar(16) NOT NULL comment '薬局窓口担当者コード',
corporation_name varchar(16) NOT NULL comment '会社名',
contactor_kan_sei varchar(16) NOT NULL comment '薬局窓口担当者：姓（漢字）',
contactor_kan_mei varchar(16) default NULL comment '薬局窓口担当者：名（漢字）',
contactor_kana_sei varchar(16) default NULL comment '薬局窓口担当者：姓（かな）',
contactor_kana_mei varchar(16) default NULL comment '薬局窓口担当者：名（かな）',
contactor_mail varchar(128) NOT NULL comment '薬局窓口担当者：メールアドレス'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;