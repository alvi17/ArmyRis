<?php 

require "../core/config.php";
require "../core/init.php";
require "../modules/subscriber/Subscriber.php";
require_once '../modules/mikrotik/PppoeApiService.php';

$subscriber = new Subscriber();

$sql = "SELECT
  s.`id_subscriber_key` AS subscriber_id
, s.`username`
, '1' AS status_id
, s.`package_id`
, s.`status_version` AS `version`
, b.`router_no`
, NOW() AS `connection_from`
, '2017-02-03 11:59:59' AS `connection_to`
, NOW() AS `now`
, '1' AS uid
, 'system' AS `utype`
FROM `armyrisdev`.`subscribers` s
INNER JOIN `armyrisdev`.`buildings` b ON b.`id_building` = s.`building_id` AND b.`router_no` = 1
INNER JOIN `armyrisdev`.`tmp_card_payments` p ON p.`userName` = s.`username`
WHERE s.`status_id` = 0
-- LIMIT 2
";

$subs_info = DB::getInstance()->query($sql, [])->results();
Utility::pr($subs_info);
//exit;

foreach($subs_info as $subs){
	$subscriber->editStatus($subs, 'Status Changed');
}
