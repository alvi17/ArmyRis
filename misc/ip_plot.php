<?php
die('***');

require "../core/config.php";
require "../core/init.php";
require "../classes/IpAddress.php";

ini_set('memory_limit', '-1');

## SELECT
//$subscribers = DB::getInstance()->get("subscribers", array('login_id', '=', 'TEST-1903'));

$raw = [];

$sql = "SELECT 
          b.`id_building` AS `building_id`
        , b.`remote_ip_first`
        , b.`remote_ip_last`
        FROM `buildings` b
        WHERE b.`is_ip_table_plotted` = 0
        LIMIT 15";

$building_info = DB::getInstance()->query($sql);
if($building_info->count()){
    //Utility::pr($subscriber->results());
    foreach($building_info->results() as $bi){
        //Utility::pr($bi); exit;
        $raw[$bi['building_id']] = [
            'first_ip' => $bi['remote_ip_first'],
            'last_ip' => $bi['remote_ip_last'],
        ];
    }
} 

Utility::pa($raw);


/*$db = DB::connectDb();
$sql = "INSERT INTO `ip_table` (`ip`,`building_id`,`created_at`,`created_by`) VALUES (?,?,?,?)";
$now = date('Y-m-d H:i:s');
$uid = 1;
$query = $db->prepare($sql);
$buildings_done = [];
foreach($raw as $building_id=>$row){
    $ips = IpAddress::listIpsBetweenTwoValues($row['first_ip'], $row['last_ip']);
    foreach($ips as $ip){
        $query->bindValue(1, $ip);
        $query->bindValue(2, $building_id);
        $query->bindValue(3, $now);
        $query->bindValue(4, $uid);
        $query->execute();
    }
    //Utility::pr($ips); exit;
    $buildings_done[] = $building_id;
}*/


$sqlHdr = "INSERT INTO `ip_table` (`ip`,`building_id`,`created_at`,`created_by`) VALUES ";
$now = date('Y-m-d H:i:s');
$uid = 1;
$buildings_done = [];
foreach($raw as $building_id=>$row){
    $ips = IpAddress::listIpsBetweenTwoValues($row['first_ip'], $row['last_ip']);
    $sqlVal = '';
    foreach($ips as $ip){
        $sqlVal .= "('{$ip}', '{$building_id}', '{$now}', '{$uid}'), ";
    }
    $sql = $sqlHdr . rtrim($sqlVal, ', ');
    //echo $sql; exit;
    
    DB::getInstance()->exec($sql);
    $buildings_done[] = $building_id;
    //Utility::pr($ips); exit;
    unset($sqlVal);
    unset($sql);
    unset($ips);
}

if(!empty($buildings_done)){
    $sql = "UPDATE `buildings` b 
            SET b.`is_ip_table_plotted` = 1
            WHERE b.`id_building` IN (".implode(', ', $buildings_done).")";
    DB::getInstance()->exec($sql);
}

//Utility::pr($buildings_done);
