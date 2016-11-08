ALTER TABLE `m2_file_types` ADD COLUMN `preview_type` TINYINT(1) UNSIGNED NOT NULL AFTER `is_image`, ADD COLUMN `dop_params` VARCHAR(255) NULL AFTER `preview_type`; 
