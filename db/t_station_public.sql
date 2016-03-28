CREATE TABLE t_station_public(
id int(11) primary key NOT NULL auto_increment comment '項番',
station_CD varchar(16) NOT NULL comment '店舗コード',
station_name varchar(128) NOT NULL comment '店舗名',
trust_number varchar(16) default NULL comment '指定事業所番号',
zip varchar(16) default NULL comment '郵便番号',
address varchar(128) default NULL comment '所在地',
station_tel varchar(16) default NULL comment '電話番号',
corporation_name varchar(16) default NULL,
opening_date varchar(128) default NULL comment '開設日',
entry_date datetime default NULL,
entry_user varchar(24) default NULL,
update_date datetime default NULL,
update_user varchar(24) default NULL,
delete_date datetime default NULL,
delete_user varchar(24) default NULL,
flag_set tinyint(1) NOT NULL,

KEY station_CD_index (station_CD)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;