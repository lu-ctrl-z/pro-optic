CREATE TABLE t_question(
id int(11) primary key NOT NULL auto_increment comment '項番',
question_id int(11) NOT NULL,
question_text text NOT NULL,
entry_date datetime default NULL,
entry_user varchar(24) default NULL,
update_date datetime default NULL,
update_user varchar(24) default NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;