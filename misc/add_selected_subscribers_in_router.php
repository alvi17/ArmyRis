<?php 

require "../core/config.php";
require "../core/init.php";
require "../modules/subscriber/Subscriber.php";
require_once '../modules/mikrotik/PppoeApiService.php';

$subscriber = new Subscriber();

/*
// ROUTER 1
$sql = "SELECT
  s.`username`
, s.`password`
, s.`local_ip`
, s.`remote_ip`
, p.`code` AS `package_code`
, b.`router_no`
, t.`status`
FROM `tmp_payments_nov_till_unique` t
LEFT JOIN subscribers s ON t.`userName` = s.`username`
LEFT JOIN buildings b ON b.`id_building` = s.`building_id`
INNER JOIN packages p ON p.`id` = s.`package_id`
WHERE t.`routerNo` = 1
-- LIMIT 999999
";
*/

// ROUTER 2
$sql = "SELECT
  s.`username`
, s.`password`
, s.`local_ip`
, s.`remote_ip`
, p.`code` AS `package_code`
, b.`router_no`
, t.`status`
FROM `tmp_payments_nov_till_unique` t
LEFT JOIN subscribers s ON t.`userName` = s.`username`
LEFT JOIN buildings b ON b.`id_building` = s.`building_id`
INNER JOIN packages p ON p.`id` = s.`package_id`
WHERE t.`routerNo` = 2
-- LIMIT 999999
";

$subs_info = DB::getInstance()->query($sql, [])->results();
//Utility::pr($subs_info);
//exit;

foreach($subs_info as $subs){
	addMikrotikUser($subs);
}
Utility::pr($subs_info);
exit;



function addMikrotikUser($fields){
    global $mikrotik_routers;
    Utility::pa($fields);
	$disabled = $fields['status'] = '0' ? true : false;
	//Utility::pa($disabled);
    //exit;
	
    $router = $mikrotik_routers[ $fields['router_no'] ];
    $mikrotik = new PppoeApiService($router['router_ip'], $router['username'], $router['password']);
    $mikUsrCrt = $mikrotik->createUser($fields['username'], $fields['password'], $fields['local_ip'], $fields['remote_ip'], $fields['package_code'], false);
	
	$mikrotik = null;
	unset($mikrotik);
}