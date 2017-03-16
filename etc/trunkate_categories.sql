truncate table m2_category;
truncate table m2_category_chars;
truncate table m2_category_tabs;
truncate table m2_item_category;
truncate table m2_category_manufacturers;
delete from m2_url_indexer where category_id > 0;
INSERT INTO `m2_category` VALUES (1,'Каталог','catalog','/catalog/',1,0,1,'','','',1,2,1,0,'');
