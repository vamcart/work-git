ALTER TABLE `orders` ADD INDEX `order_status` (`orders_status`);

ALTER TABLE `orders_products` ADD INDEX `orders_id` (`orders_id`);
ALTER TABLE `orders_products` ADD INDEX `products_id` (`products_id`);
ALTER TABLE `orders_products` ADD INDEX `orders_id_products_id` (`orders_id`,`products_id`);

ALTER TABLE `orders` ADD INDEX `orders_id` (`orders_id`);
ALTER TABLE `orders` ADD INDEX `customers_id` (`customers_id`);
ALTER TABLE `orders` ADD INDEX `orders_id_customers_id` (`orders_id`,`customers_id`);

ALTER TABLE `products` ADD INDEX `products_status` (`products_status`);

ALTER TABLE `categories` ADD INDEX `categories_status_categories_id` (`categories_status`, `categories_id`);


ALTER TABLE `orders_total` ADD INDEX `class` (`class`);
ALTER TABLE `orders_total` ADD INDEX `orders_id_class` (`orders_id`, `class`);

ALTER TABLE `orders` ADD INDEX `date_purchased` (`date_purchased`);

ALTER TABLE reviews ADD status INT(1) NOT NULL DEFAULT 0;
ALTER TABLE site_reviews ADD status INT(1) NOT NULL DEFAULT 0;
