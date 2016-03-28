CREATE TABLE t_colet_agent(
id int(11) primary key NOT NULL auto_increment comment '項番',
agent_CD varchar(16) NOT NULL comment 'SMS担当者コード',
agent_kan_sei varchar(16) NOT NULL comment 'SMS担当者：姓（漢字）',
agent_kan_mei varchar(16) default NULL comment 'SMS担当者：名（漢字）',
agent_kana_sei varchar(16) default NULL comment 'SMS担当者：姓（かな）',
agent_kana_mei varchar(16) default NULL comment 'SMS担当者：名（かな）'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;