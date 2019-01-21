-- Import Unused Scratch Cards:
INSERT INTO `armyrisdev`.`scratch_cards` (`code`, `amount`, `log_id`, `created_at`, `created_by`)
SELECT `uniqueCode`, `amount`, 1, NOW(), 1
FROM `armyris_card_db`.`rechargecard`
WHERE `status` = 0
;




-- IMPORT BUILDING RAW DATA
LOAD DATA LOCAL INFILE "E:/xampp/htdocs/armyrisdev/misc/area-building-ip-combined.txt"
INTO TABLE `buildings` 
FIELDS TERMINATED BY '\t' 
LINES TERMINATED BY '\r\n' 
IGNORE 1 LINES 
(`building_name`, `area_name`, `ip_block`, `local_ip`, `remote_ip_first`, `remote_ip_last`, `router_no`)
;

INSERT INTO `areas` (`area_name`)
SELECT DISTINCT b.`area_name` 
FROM `buildings` b
ORDER BY b.`area_name` ASC
;

SELECT * FROM `areas`;

UPDATE `areas` a
SET a.`created_at` = NOW()
, a.`created_by` = 1
;

UPDATE `buildings` b
INNER JOIN `areas` a ON a.`area_name` = b.`area_name`
SET b.`area_id` = a.`id_area`
, b.`created_at` = NOW()
, b.`created_by` = 1
;

update `buildings` b
set b.`remote_ip_last` = REPLACE (b.`remote_ip_last`, '2524', '254')
;
update `buildings` b
set b.`remote_ip_last` = REPLACE (b.`remote_ip_last`, '54', '254')
;
update `buildings` b
set b.`remote_ip_last` = REPLACE (b.`remote_ip_last`, '2254', '254')
;