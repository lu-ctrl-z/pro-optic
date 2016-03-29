CREATE TABLE t_user(
  id int(11) primary key NOT NULL auto_increment comment 'id',
  mail_address varchar(128) NOT NULL comment 'địa chỉ mail',
  first_name varchar(32) NOT NULL comment 'Tên',
  last_name varchar(32) NOT NULL comment 'Họ',
  tel varchar(16) NOT NULL comment 'Số điện thoại',
  password varchar(32) NOT NULL comment 'Password(hash)',
  user_mode tinyint(1) NOT NULL default 1,
  entry_date datetime default NULL comment 'Ngày tạo',
  entry_user varchar(24) default NULL comment 'Người tạo',
  update_date datetime default NULL comment 'Ngày update',
  update_user varchar(24) default NULL comment 'Người update',
  delete_date datetime default NULL comment 'Ngày xóa',
  delete_user varchar(24) default NULL comment 'Người xóa'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;