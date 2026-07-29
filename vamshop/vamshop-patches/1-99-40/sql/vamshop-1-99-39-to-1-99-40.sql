SET SQL_MODE = "";

DROP TABLE IF EXISTS customers_balance_history;
CREATE TABLE IF NOT EXISTS customers_balance_history (
  customers_balance_id int(11) NOT NULL AUTO_INCREMENT,
  customers_id int(11) NOT NULL,
  customers_balance_from decimal(15,4) DEFAULT '0.0000',
  customers_balance_to decimal(15,4) DEFAULT '0.0000',
  date_action datetime DEFAULT NULL,
  customers_balance_action varchar(255) NOT NULL,
  customers_balance_comment text,
  balance_history_cod varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY (customers_balance_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS products_extra_fields_values;
create table products_extra_fields_values (
  products_extra_fields_value_id int(11) not null auto_increment,
  guid varchar(255) not null,
  products_extra_fields_value_name varchar(255) not null ,
  products_extra_fields_status tinyint(1) default '1' not null ,
  languages_id int(11) default '1' not null ,
  PRIMARY KEY (products_extra_fields_value_id),
  INDEX guid_idx (guid)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_general_ci;

ALTER TABLE customers ADD customers_balance decimal(15,4) DEFAULT '0.0000';
ALTER TABLE manufacturers ADD customers_balance_added_manufacturers decimal(5,3) NOT NULL DEFAULT '0.001';
ALTER TABLE products ADD customers_balance_added_products decimal(5,3) NOT NULL DEFAULT '0.001';
ALTER TABLE orders_products ADD customers_balance_added decimal(15,4) DEFAULT '0.0000';

ALTER TABLE admin_access ADD customers_balance INT( 1 ) NOT NULL ;
UPDATE admin_access SET customers_balance = 1 WHERE customers_id = 1 LIMIT 1;

ALTER TABLE products_extra_fields ADD guid varchar(255) not null AFTER products_extra_fields_id;
ALTER TABLE products_to_products_extra_fields ADD guid varchar(255) not null AFTER products_extra_fields_id;
ALTER TABLE manufacturers ADD guid varchar(255) not null AFTER manufacturers_id;

INSERT INTO configuration (configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('KOVALSKY_BONUS_PERSENT', '5', 1, 72, NULL, now(), NULL, NULL);
INSERT INTO configuration (configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('KOVALSKY_SHIPPING_FINISH_STATUS', '6', 1, 73, NULL, now(), 'vam_get_order_status_name', 'vam_cfg_pull_down_order_statuses(');
INSERT INTO configuration (configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('KOVALSKY_CONST_MAX_PAY_PERSENT', '40', 1, 74, NULL, now(), NULL, NULL);

INSERT INTO `content_manager` VALUES (13, 0, 0, '', 1, 'Бонусная программа', 'Бонусная программа', 'Текст страницы бонусная программа.', '', 0, 1, '', 1, 13, 0,'','Бонусная программа','Бонусная программа','bonus.html', 0, 0);

ALTER TABLE products_extra_fields ADD INDEX guid (guid(255));
ALTER TABLE products_extra_fields_values ADD INDEX guid (guid(255));
ALTER TABLE products_to_products_extra_fields ADD INDEX guid (guid(255));
ALTER TABLE categories ADD INDEX guid (guid(255));
ALTER TABLE manufacturers ADD INDEX guid (guid(255));
ALTER TABLE orders ADD INDEX guid (guid(255));
ALTER TABLE orders_products ADD INDEX guid (guid(255));
ALTER TABLE orders_products_attributes ADD INDEX guid (guid(255));
ALTER TABLE orders_products_download ADD INDEX guid (guid(255));
ALTER TABLE products ADD INDEX guid (guid(255));
ALTER TABLE products_attributes ADD INDEX guid (guid(255));
ALTER TABLE products_attributes_download ADD INDEX guid (guid(255));
