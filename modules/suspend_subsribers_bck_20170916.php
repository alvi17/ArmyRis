<?php 

/*
 * E:/xampp/php/php.exe -f E:/xampp/htdocs/armyris/modules/suspend_subsribers.php
 * C:/xampp/php/php.exe -f C:/xampp/htdocs/armyris/modules/suspend_subsribers.php
 */

/*
require('E:/xampp/htdocs/armyris/core/init_alt.php');
require "E:/xampp/htdocs/armyris/modules/subscriber/Subscriber.php";
require_once 'E:/xampp/htdocs/armyris/modules/mikrotik/PppoeApiService.php';
*/

require('C:/xampp/htdocs/armyris/core/init_alt.php');
require "C:/xampp/htdocs/armyris/modules/subscriber/Subscriber.php";
require_once 'C:/xampp/htdocs/armyris/modules/mikrotik/PppoeApiService.php';


$db = connectDb(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);


$sql = "DROP TABLE IF EXISTS tmp_suspend_users";
$stmt = $db->prepare($sql);
$stmt->execute();
//$stmt->closeCursor();

$sql = "CREATE TABLE tmp_suspend_users
        SELECT 
          s.`id_subscriber_key`
        , s.`username`
        , s.`router_no`
        , s.`category`
        , s.`connection_to`
        , (s.`connection_version` + 1) AS `connection_version`
        , NOW() AS `dtt_mod`
        , '-1' AS is_processed
        FROM `subscribers` s
        WHERE 1
        AND s.`status_id` = 1
        AND s.`category` <> 'Free'
        AND s.`connection_to` IS NOT NULL
        AND s.`connection_to`< NOW()";
/*
$sql = "CREATE TABLE tmp_suspend_users
        SELECT 
          s.`id_subscriber_key`
        , s.`username`
        , s.`router_no`
        , s.`category`
        , s.`connection_to`
        , (s.`connection_version` + 1) AS `connection_version`
        , NOW() AS `dtt_mod`
        , '-1' AS is_processed
        FROM `subscribers` s
        WHERE s.`username` LIKE '%aamrapc%'";
*/

$stmt = $db->prepare($sql);
$stmt->execute();




$sql = "SELECT `username`, `router_no`
        FROM `tmp_suspend_users`";
$stmt = $db->prepare($sql);
$stmt->execute();
$results = $stmt->fetchAll();
//print_r($results);
//exit;
foreach($results as $res){
    suspendMikrotikUsers($res['username'], $res['router_no']);
	sleep(2);
}

$sql = "INSERT INTO subscribers_connections_audit
        (subscriber_id, status_id, `comment`, `version`, `created_at`, created_by, `created_user_type`)
        SELECT `id_subscriber_key`, 0, 'Account Suspended by System', `connection_version`, NOW(), 1, 'system'
        FROM tmp_suspend_users";
$stmt = $db->prepare($sql);
$stmt->execute();

$sql = "UPDATE `subscribers` s
        INNER JOIN tmp_suspend_users t ON t.`username` = s.`username`
        SET s.`status_id` = 0
        , s.`connection_version` = t.connection_version
        , s.`updated_at` = NOW()
        , s.`updated_by` = 1
        , s.`updated_user_type` = 'system'";
$stmt = $db->prepare($sql);
$stmt->execute();

function suspendMikrotikUsers($username, $router_no){
    global $mikrotik_routers;
    
    //echo '$username: '. $username;
    //echo PHP_EOL;
    //echo '$router_no: '. $router_no;
    //exit;
    
    $router = $mikrotik_routers[$router_no];
    //echo PHP_EOL;
    //echo '$router: '; print_r($router); exit;
    $mikrotik = new PppoeApiService($router['router_ip'], $router['username'], $router['password']);
    
    ## Confirms user is enabled
    $mikrotik->enableUser($username);  
    $mikrotik->changeProfile($username, 'default');
	$mikrotik->removeUserFromActive($username);
    unset($mikrotik);
}