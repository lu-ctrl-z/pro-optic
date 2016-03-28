CREATE TABLE t_user_mailauth(
id int(11) primary key NOT NULL auto_increment comment '項番',
user_mail varchar(128) NOT NULL comment '登録希望者のメールアドレス',
unique_key varchar(16) NOT NULL,
entry_date datetime NOT NULL,
delete_date datetime NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;