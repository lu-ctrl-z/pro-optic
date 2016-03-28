CREATE TABLE t_interviewee(
id int(11) primary key NOT NULL auto_increment comment '項番',
interviewee_id varchar(128) NOT NULL,
com_CD varchar(16) NOT NULL,
station_CD varchar(16) NOT NULL,
name varchar(32) NOT NULL,
station_name varchar(256) NOT NULL,
discription text default NULL,
guide_id int(11) NOT NULL,
entry_date datetime default NULL,
entry_user varchar(24) default NULL,
update_date datetime default NULL,
update_user varchar(24) default NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;