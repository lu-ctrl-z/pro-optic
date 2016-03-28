CREATE TABLE t_geocoding(
sid int(11) primary key NOT NULL auto_increment comment '項番',
address_md5 varchar(32)  comment 'md5 住所',
latlng geometry  comment 'Latitude and Longitude',
address_api text comment 'get address from api',
created datetime,
modified datetime
) ENGINE=InnoDB DEFAULT CHARSET=utf8;