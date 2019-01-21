<?php 

require "../core/config.php";
require "../core/init.php";
require "../modules/subscriber/Subscriber.php";
require_once '../modules/mikrotik/PppoeApiService.php';

$subscriber = new Subscriber();
/*
$sql = "
CREATE TABLE subscriber_conection_revised_20180324
SELECT
  s.`id_subscriber_key` AS subscriber_id
, s.`username`
, s.`username` AS `password`
, r.`name` AS `rank`
, CONCAT(s.`firstname`, '', s.`lastname`) AS fullname
, s.`house_no`
, b.`building_name` AS `building`
, a.`area_name` AS `area`
, b.`router_no`
, s.`local_ip`
, s.`remote_ip`
, p.`code` AS `package_code`
, s.`status_id`
, s.`connection_from`
, s.`connection_to`
, s.`category`
, '0' AS is_modified
FROM `subscribers` s
INNER JOIN packages p ON p.`id` = s.`package_id`
LEFT JOIN `ranks` r ON r.`id` = s.`rank_id`
LEFT JOIN `areas` a ON a.`id_area` = s.`area_id`
LEFT JOIN `buildings` b ON b.`id_building` = s.`building_id`
-- LIMIT 666666
";
DB::getInstance()->query($sql, []);
// 3573 row(s) affecte
exit;
*/


$sql = "SELECT 
		subscriber_id,
		username,
		password,
		local_ip,
		remote_ip,
		package_code, 
		router_no, 
		status_id AS status
		FROM subscriber_conection_revised_20180324
		WHERE is_modified = 0
		AND status_id <> 2
		LIMIT 100";
$subs_info = DB::getInstance()->query($sql, [])->results();
Utility::pr($subs_info);
//die;

if(!empty($subs_info)){
	$ids = array();

	foreach($subs_info as $subs){
		addMikrotikUser($subs);
		$ids[] = $subs['subscriber_id'];
	}
	//Utility::pr($subs_info);
	//exit;

	if(!empty($ids)){
		$sql = "UPDATE subscriber_conection_revised_20180324 s
				SET s.`is_modified` = 1
				WHERE s.`subscriber_id` IN (".implode(', ', $ids).")";
		//DB::getInstance()->query($sql, []);
		$upd_info = DB::getInstance()->query($sql, [])->results();
		//echo '<hr>';
		//echo $sql;
	}
	
}


function addMikrotikUser($fields){
    global $mikrotik_routers;
    //Utility::pa($fields);
	$disabled = $fields['status'] = '0' ? true : false;
	
    $router = $mikrotik_routers[ $fields['router_no'] ];
    $mikrotik = new PppoeApiService($router['router_ip'], $router['username'], $router['password']);
    $mikUsrCrt = $mikrotik->createUser($fields['username'], $fields['password'], $fields['local_ip'], $fields['remote_ip'], $fields['package_code'], false);
	
	$mikrotik = null;
	unset($mikrotik);
}