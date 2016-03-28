CREATE TABLE t_guide_index(
id int(11) primary key NOT NULL auto_increment comment '項番',
guide_id int(11) NOT NULL,
guide_name varchar(256) NOT NULL,
guide_image varchar(256) NOT NULL,
url_present text default NULL,
entry_date datetime default NULL,
entry_user varchar(24) default NULL,
update_date datetime default NULL,
update_user varchar(24) default NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;