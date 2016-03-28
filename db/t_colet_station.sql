CREATE TABLE t_colet_station(
id int(11) primary key NOT NULL auto_increment comment '項番',
station_CD varchar(16) NOT NULL comment '薬局コード',
contactor_CD varchar(16) default NULL comment '薬局窓口担当者コード',
agent_CD varchar(16) default NULL comment 'SMS担当者コード'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;