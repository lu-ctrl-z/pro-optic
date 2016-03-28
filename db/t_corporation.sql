CREATE TABLE t_corporation(
id int(11) primary key NOT NULL auto_increment comment '項番',
com_CD varchar(16) comment '法人CD ',
corporation_name varchar(16) comment '',
corporation_name_kana varchar(128) comment '法人名（カナ）',
corporation_zip varchar(16) comment '郵便番号',
corporation_address varchar(128) comment '所在地',
corporation_building varchar(128) comment '建物名と階数',
corporation_tel varchar(16) comment '代表電話番号',
entry_date datetime comment '登録日時',
entry_user varchar(24) comment '登録者',
update_date datetime comment '更新日時',
update_user varchar(24) comment '更新者',
delete_date datetime comment '削除日時',
delete_user varchar(24) comment '削除者'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;