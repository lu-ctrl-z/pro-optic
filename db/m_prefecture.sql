CREATE TABLE m_prefecture(
id int(11) primary key NOT NULL auto_increment,
tdfk_no int(11) default NULL,
tdfk_mei varchar(32) default NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;