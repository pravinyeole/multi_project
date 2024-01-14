--  Find Referal Ids Count is 0
SELECT users.id, users.user_fname, users.user_lname, users.mobile_number,users.created_at AS joinng_date FROM users WHERE users.mobile_number NOT IN ( SELECT DISTINCT user_referral.referral_id FROM user_referral WHERE user_referral.referral_id IS NOT NULL ) AND user_role='U' and user_status='Active'

--  Find Referal Ids Count > 0
SELECT users.id, user_fname, user_lname, created_at AS joining_date, referal_count FROM users JOIN ( SELECT referral_id, COUNT(user_referral_id) AS referal_count FROM user_referral GROUP BY referral_id ) AS ur ON ur.referral_id = users.mobile_number WHERE user_status = 'Active' AND user_role = 'U';


-- Each user level wise data
$usersall = DB::select(DB::raw("SELECT users.id, mobile_number,user_fname, user_lname, created_at AS joining_date, referal_count FROM users JOIN ( SELECT referral_id, COUNT(user_referral_id) AS referal_count FROM user_referral GROUP BY referral_id ) AS ur ON ur.referral_id = users.mobile_number WHERE user_status = 'Active' AND user_role = 'U'"));

$myLveledata = [];
foreach($usersall AS $key => $u ){
    $lvlOneA = UserReferral::join('users', 'users.id', 'user_referral.user_id')->select('users.mobile_number')->where('user_referral.referral_id', $u->mobile_number)->pluck('users.mobile_number')->toArray();
    $myLveledata[]= [$u->mobile_number];
    $myLveledata[$u->mobile_number][] = ['level_1' => count($lvlOneA)];
    if ($lvlOneA) {
        $lvlTwoA = UserReferral::join('users', 'users.id', 'user_referral.user_id')->select('users.mobile_number')->whereIn('user_referral.referral_id', $lvlOneA)->pluck('users.mobile_number')->toArray();
        $myLveledata[$u->mobile_number][] = ['level_2' => count($lvlTwoA)];
        if ($lvlTwoA) {
            $lvlThreeA = UserReferral::join('users', 'users.id', 'user_referral.user_id')->select('users.mobile_number')->whereIn('user_referral.referral_id', $lvlTwoA)->pluck('users.mobile_number')->toArray();
            $myLveledata[$u->mobile_number][] = ['level_3' => count($lvlThreeA)]; 
            if ($lvlThreeA) {
                $lvlFourA = UserReferral::join('users', 'users.id', 'user_referral.user_id')->select('users.mobile_number')->whereIn('user_referral.referral_id', $lvlThreeA)->pluck('users.mobile_number')->toArray();
                $myLveledata[$u->mobile_number][] = ['level_4' => count($lvlFourA)];
                if ($lvlFourA) {
                    $lvlFiveA = UserReferral::join('users', 'users.id', 'user_referral.user_id')->select('users.mobile_number')->whereIn('user_referral.referral_id', $lvlFourA)->pluck('users.mobile_number')->toArray();
                    $myLveledata[$u->mobile_number][] = ['level_5' => count($lvlFiveA)];
                }
            }
        }
    }
}


=====================================================17-12-2023==============================================================
ALTER TABLE `users` ADD `tel_chat_Id` VARCHAR(11) NULL DEFAULT NULL AFTER `user_sub_info_id`;


===================================================================================================================
DROP TABLE IF EXISTS `create_button`;
CREATE TABLE `create_button` (
  `id` int NOT NULL AUTO_INCREMENT,
  `button` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `create_button` (`id`, `button`) VALUES
(1,	1);
07-11-2023
ALTER TABLE `payments` ADD `comments` TEXT NOT NULL AFTER `attachment`;
ALTER TABLE `payments` CHANGE `comments` `comments` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;


UPDATE `user_sub_info` SET `created_at` = '2023-10-30 20:59:42' WHERE `created_at` = '2023-11-06 20:59:42';
UPDATE `user_sub_info` SET `created_at` = '2023-10-30 20:59:42' WHERE `created_at` = '2023-11-05 20:59:42';


$date  = date('Y-m-12 20:59:42');
$id = '12112023';
for($i=1;$i <= 5; $i++){
    $mobile_id = 'AJ0'.$i.$id;
    $values = array('user_id'=> 5,'mobile_id' => $mobile_id,'status' => "red",'created_at' => $date);
    print_r($values);
    UserSubInfo::insert($values);
}
for($i=1;$i <= 5; $i++){
    $mobile_id = 'SK0'.$i.$id;
    $values = array('user_id'=> 10,'mobile_id' => $mobile_id,'status' => "red",'created_at' => $date);
    print_r($values);
    UserSubInfo::insert($values);
}
for($i=1;$i <= 5; $i++){
    $mobile_id = 'DK0'.$i.$id;
    $values = array('user_id'=> 11,'mobile_id' => $mobile_id,'status' => "red",'created_at' => $date);
    print_r($values);
    UserSubInfo::insert($values);
}
for($i=1;$i <= 5; $i++){
    $mobile_id = 'AK0'.$i.$id;
    $values = array('user_id'=> 12,'mobile_id' => $mobile_id,'status' => "red",'created_at' => $date);
    print_r($values);
    UserSubInfo::insert($values);
}
for($i=1;$i <= 5; $i++){
    $mobile_id = 'SP0'.$i.$id;
    $values = array('user_id'=> 14,'mobile_id' => $mobile_id,'status' => "red",'created_at' => $date);
    print_r($values);
    UserSubInfo::insert($values);
}
 dd('ddd');

====================================06-11-23====================================================
ALTER TABLE `users` ADD `upi_id` varchar(255) COLLATE 'utf8mb4_unicode_ci' NULL AFTER `email`;
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
SELECT COUNT(user_sub_info_id) AS 09ids FROM `user_sub_info` WHERE `created_at` BETWEEN '2023-10-09 00:00:00' AND '2023-10-09 23:59:59';

SELECT COUNT(id) AS 09ids FROM `user_map_new` WHERE `created_at` BETWEEN '2023-10-09 00:00:00' AND '2023-10-09 23:59:59';
==================================05 09 2023========================================================

CREATE TABLE `db_hpa`.`transfer_pin_history` (`trans_id` INT(11) NOT NULL AUTO_INCREMENT , `trans_by` INT(5) NOT NULL , `trans_to` INT(5) NOT NULL , `trans_reason` TEXT NULL DEFAULT NULL , `trans_count` INT(5) NOT NULL , `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP , `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB;

==================================10 09 2023========================================================

    ALTER TABLE `user_map_new` ADD `user_mobile_id` VARCHAR(30) NOT NULL AFTER `user_id`;
