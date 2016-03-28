CREATE TABLE t_station_detail(
id int(11) primary key NOT NULL auto_increment comment '項番',
station_CD varchar(16) default NULL comment '店舗コード',
station_tel varchar(16) default NULL comment '電話番号',
station_fax varchar(16) default NULL comment 'FAX番号',
opening_date varchar(128) default NULL comment '開設日',
business_hour varchar(64) default NULL comment '営業日時',
holiday varchar(128) default NULL comment '定休日',
core_time varchar(128) default NULL comment '対応時間',
transportation varchar(128) default NULL comment '訪問移動手段',
staff_com varchar(256) default NULL,
visit_guidance_com varchar(256) default NULL,
24_hour_aday_com varchar(256) default NULL,
pal_care_com varchar(256) default NULL,
mental_care_com varchar(256) default NULL,
kids_care_com varchar(256) default NULL,
dementia_care_com varchar(256) default NULL,
handicapped_care_com varchar(256) default NULL,
visit_real_record varchar(256) default NULL,
other_info varchar(256) default NULL,

KEY station_CD_index (station_CD)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;