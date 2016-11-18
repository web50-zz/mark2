ALTER TABLE `m2_item_price` ADD INDEX `type` (`type`), ADD INDEX `price_value` (`price_value`); 
ALTER TABLE `m2_item_price` DROP INDEX `ssgn_project_id`, ADD INDEX `item_id` (`item_id`);
ALTER TABLE `m2_item_price` CHANGE `price_value` `price_value` DECIMAL(9,2) DEFAULT 0.00 NOT NULL;
