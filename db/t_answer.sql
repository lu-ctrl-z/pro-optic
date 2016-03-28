CREATE TABLE t_answer(
id int(11) primary key NOT NULL auto_increment,
answer_id int(11) NOT NULL,
interviewee_id varchar(256) NOT NULL,
question_id int(11) NOT NULL,
answer_text text NOT NULL,
entry_date datetime default NULL,
entry_user varchar(24) default NULL,
update_date datetime default NULL,
update_user varchar(24) default NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;