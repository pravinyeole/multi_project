====================================05-11-2023==========================================================
CREATE TABLE `db_hpa_local`.`payment_distribution` (`pd_id` INT(11) NOT NULL AUTO_INCREMENT , `sender_id` INT(10) NOT NULL , `reciver_id` INT(10) NOT NULL , `mobile_id` VARCHAR(20) NOT NULL , `amount` FLOAT(10,2) NOT NULL , `level` INT NOT NULL , `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP , `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`pd_id`)) ENGINE = InnoDB;

====================================04-11-2023==========================================================
ALTER TABLE `payments` ADD `comments` TEXT NULL DEFAULT NULL AFTER `payment_type`;
====================================28-10-2023==========================================================
CREATE TABLE `db_hpa`.`user_mpin` (`mid` INT(11) NOT NULL AUTO_INCREMENT , `uid` INT(11) NOT NULL , `mpin` INT(11) NOT NULL , `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP , `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP , INDEX (`mid`)) ENGINE = InnoDB;

========================================================================================================
SELECT COUNT(pd_id) AS ADMIN FROM `payment_distribution` WHERE `level` LIKE 'ADMIN';
SELECT COUNT(pd_id) AS LVL1 FROM `payment_distribution` WHERE `level` LIKE 'LVL1';
SELECT COUNT(pd_id) AS LVL2 FROM `payment_distribution` WHERE `level` LIKE 'LVL2';
SELECT COUNT(pd_id) AS LVL3 FROM `payment_distribution` WHERE `level` LIKE 'LVL3';
SELECT COUNT(pd_id) AS LVL4 FROM `payment_distribution` WHERE `level` LIKE 'LVL4';
SELECT COUNT(pd_id) AS LVL5 FROM `payment_distribution` WHERE `level` LIKE 'LVL5';
SELECT COUNT(pd_id) AS LVL6 FROM `payment_distribution` WHERE `level` LIKE 'LVL6';
SELECT COUNT(pd_id) AS LVL7 FROM `payment_distribution` WHERE `level` LIKE 'LVL7';

========================================================================================================
dhananjaykhillari@gmail.com

Password: IcNsP3g(5

<!-- MObile Circle Table Start -->
ALTER TABLE `user_otp` CHANGE `user_otp_id` `user_otp_id` BIGINT(20) NOT NULL AUTO_INCREMENT;
ALTER TABLE `users` CHANGE `id` `id` BIGINT(20) NOT NULL AUTO_INCREMENT;
ALTER TABLE `user_pins` CHANGE `user_pin_id` `user_pin_id` INT(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `user_referral` CHANGE `user_referral_id` `user_referral_id` INT(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `user_sub_info` CHANGE `user_sub_info_id` `user_sub_info_id` BIGINT(20) NOT NULL AUTO_INCREMENT;
ALTER TABLE `user_roles` CHANGE `user_role_id` `user_role_id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `user_map_new` CHANGE `id` `id` BIGINT(20) NOT NULL AUTO_INCREMENT;
ALTER TABLE `transfer_pin_history` CHANGE `trans_id` `trans_id` INT(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `request_pin` CHANGE `pin_request_id` `pin_request_id` INT(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `payments` CHANGE `payment_id` `payment_id` INT(11) NOT NULL AUTO_INCREMENT;


composer require hashids/hashids

==================================05 09 2023========================================================

CREATE TABLE `db_hpa`.`transfer_pin_history` (`trans_id` INT(11) NOT NULL AUTO_INCREMENT , `trans_by` INT(5) NOT NULL , `trans_to` INT(5) NOT NULL , `trans_reason` TEXT NULL DEFAULT NULL , `trans_count` INT(5) NOT NULL , `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP , `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB;

==================================10 09 2023========================================================

    ALTER TABLE `user_map_new` ADD `user_mobile_id` VARCHAR(30) NOT NULL AFTER `user_id`;
