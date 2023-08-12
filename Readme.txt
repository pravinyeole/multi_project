Read me


INSERT INTO `parameters` (`parameter_id`, `parameter_key`, `parameter_value`) VALUES (NULL, 'starting_time', '10:00');

INSERT INTO `parameters` (`parameter_id`, `parameter_key`, `parameter_value`) VALUES (NULL, 'end_time', '16:00');

ALTER TABLE `user_sub_info` ADD `status` ENUM('red','yellow','green') NOT NULL AFTER `mobile_id`;
ALTER TABLE `user_sub_info` CHANGE `status` `status` ENUM('red','yellow','green','gray') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;


ALTER TABLE `users` ADD `total_invited` BIGINT(20) NOT NULL DEFAULT '0' AFTER `user_slug`;