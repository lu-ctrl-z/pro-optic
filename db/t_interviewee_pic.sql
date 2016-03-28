CREATE TABLE t_interviewee_pic(
id int(11) primary key NOT NULL auto_increment comment '項番',
interviewee_id varchar(128) NOT NULL,
pic_id int(8) NOT NULL comment '写真番号',
pic_path varchar(128) NOT NULL comment '写真ファイルパス',
entry_date datetime default NULL,
entry_user varchar(24) default NULL,
update_date datetime default NULL,
update_user varchar(24) default NULL,
delete_date datetime default NULL,
delete_user varchar(24) default NULL,
flag_set tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;