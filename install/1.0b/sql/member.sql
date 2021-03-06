CREATE TABLE IF NOT EXISTS %prfx%.member (
  mbrid int NOT NULL AUTO_INCREMENT,
  barcode_nmbr varchar(20) NOT NULL DEFAULT '',
  create_dt datetime NOT NULL,
  last_change_dt timestamp ON UPDATE CURRENT_TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  last_change_userid int NOT NULL DEFAULT '0',
  first_name varchar(50) NOT NULL DEFAULT '',
  last_name varchar(50) NOT NULL DEFAULT '',
  first_legal_name varchar(50) DEFAULT NULL,
  last_legal_name varchar(50) DEFAULT NULL,
  address1 varchar(32) DEFAULT NULL,
  address2 varchar(32) DEFAULT NULL,
  city varchar(32) DEFAULT NULL,
  state varchar(32) DEFAULT NULL,
  zip varchar(10) DEFAULT NULL,
  zip_ext varchar(10) DEFAULT NULL,
  password char(32) DEFAULT NULL,
  home_phone varchar(15) DEFAULT NULL,
  work_phone varchar(15) DEFAULT NULL,
  email varchar(128) DEFAULT NULL,
  classification smallint NOT NULL,
  siteid tinyint unsigned NOT NULL,
  school_grade tinyint unsigned NOT NULL,
  school_teacher varchar(32) NOT NULL,
  PRIMARY KEY (mbrid)
)   DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
