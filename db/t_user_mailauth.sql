CREATE TABLE t_user_mailauth(
id int(11) primary key NOT NULL auto_increment comment '項番',
user_mail varchar(128) NOT NULL comment '登録希望者のメールアドレス',
unique_key varchar(128) NOT NULL comment 'unique_key',
entry_date datetime NOT NULL comment '登録日',
delete_date datetime default NULL comment '削除日'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;