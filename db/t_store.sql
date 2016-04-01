CREATE TABLE t_store(
    id int(11) primary key NOT NULL auto_increment comment 'store id',
    store_name_unique varchar(128) comment 'Kính Mắt Việt Long => kinhmatvietlong',
    store_name varchar(128) comment 'tên cửa hàng',
    store_address varchar(128) comment 'Địa chỉ cửa hàng',
    store_tel varchar(16) comment 'số điện thoại',
    latitude double(25, 20) default NULL comment 'Kinh độ',
    longitude double(25, 20) default NULL comment 'Vĩ độ',
    display_flg tinyint(1) NOT NULL default 0,
    entry_date datetime NOT NULL,
    entry_user int(11),
    update_date datetime,
    update_user int(11),
    delete_date datetime,
    delete_user int(11),
    KEY `m_store_unique_index` (`store_name_unique`, `delete_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;