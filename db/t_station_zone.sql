CREATE TABLE t_station_zone(
id int(11) primary key NOT NULL auto_increment comment '項番',
station_CD varchar(16) default NULL comment '店舗コード',
latitude double default NULL comment '緯度',
longitude double default NULL comment '経度',
scope int(11) default 0 comment '訪問範囲',
entry_date datetime default NULL,
entry_user varchar(24) default NULL,
update_date datetime default NULL,
update_user varchar(24) default NULL,
delete_date datetime default NULL,
delete_user varchar(24) default NULL,
flag_set tinyint(1) NOT NULL,

KEY station_CD_index (station_CD)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;