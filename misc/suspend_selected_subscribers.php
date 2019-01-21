<?php 
/*

http://localhost/armyris/misc/suspend_selected_subscribers.php?router_no=1
http://localhost/armyris/misc/suspend_selected_subscribers.php?router_no=2
*/


require "../core/config.php";
require "../core/init.php";
require "../modules/subscriber/Subscriber.php";
require_once '../modules/mikrotik/PppoeApiService.php';

$subscriber = new Subscriber();

//$router_no = 1;
$router_no = (int) Input::get('router_no');


/*
$sql = "SELECT s.`username`
		FROM subscribers s
		WHERE s.`status_id` = 0
		AND s.`router_no` = {$router_no}";
*/
/*
SELECT
-- s.*,
s.`username`
, s.`category`
FROM subscribers s
WHERE s.`status_id` = 0
-- and s.`router_no` = 1
AND s.`category` <> 'Free'
-- limit 10
;
*/

/*$sql = "SELECT u.`userName` AS username
	FROM `tmp_payments_nov_till_unique` u
	INNER JOIN subscribers s ON s.`username` = u.`userName` AND s.`category` <> 'Free'
	WHERE u.`status` = 0
	AND u.`routerNo` = {$router_no}
;";*/
    
$sql = "SELECT s.`username`, b.`router_no`, s.`connection_to`
        FROM subscribers s
        INNER JOIN `areas` a ON a.`id_area` = s.`area_id`
        INNER JOIN `buildings` b ON b.`id_building` = s.`building_id`
        WHERE s.`status_id` = 0
        AND b.`router_no` = ?
        AND s.`connection_to` >= DATE_SUB(NOW(), INTERVAL 40 DAY)
		-- AND s.`connection_to` >= '2017-02-07 11:59:59'
		";

$subs_info = DB::getInstance()->query($sql, [$router_no])->results();
Utility::pr($subs_info);
echo '<hr>';
//exit;

$router = $mikrotik_routers[$router_no];
$mikrotik = new PppoeApiService($router['router_ip'], $router['username'], $router['password']);

$i=0;
foreach($subs_info as $subs){
	//$mikrotik->disableUser($subs['username']);
	//$mikrotik->removeUserFromActive($subs['username']);
	
	echo ++$i.'. USER '. $subs['username'].'<br>';
	
	## Confirms user is enabled
    $mikrotik->enableUser($subs['username']);  
    $mikrotik->changeProfile($subs['username'], 'default');
	$mikrotik->removeUserFromActive($subs['username']);
	echo "<hr>";
}
