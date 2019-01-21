<?php

/**
 * Internet Checkin Page for Subscriber
 *
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date October 22, 2016 22:57
 */

require "../core/config.php";
require "../core/init.php";
require "../modules/subscriber/Subscriber.php";
require_once '../modules/mikrotik/PppoeApiService.php';


$subscriber     = new Subscriber();

//=============================================================
$staus_id = 0; $disconnect_date = ''; $package_days = 3;
$connectivity = Subscriber::calcInternectCheckinDuration($staus_id, $disconnect_date, $package_days);
echo "staus_id: ".$staus_id;
echo "<br>disconnect_date: ".$disconnect_date;
echo "<br>package_days: ".$package_days;
Utility::pr($connectivity);
echo '<hr>';

$staus_id = 0; $disconnect_date = '2016-12-10 17:40:30'; $package_days = 3;
$connectivity = Subscriber::calcInternectCheckinDuration($staus_id, $disconnect_date, $package_days);
echo "staus_id: ".$staus_id;
echo "<br>disconnect_date: ".$disconnect_date;
echo "<br>package_days: ".$package_days;
Utility::pr($connectivity);
echo '<hr>';

$staus_id = 1; $disconnect_date = '2016-12-10 17:40:30'; $package_days = 3;
$connectivity = Subscriber::calcInternectCheckinDuration($staus_id, $disconnect_date, $package_days);
echo "staus_id: ".$staus_id;
echo "<br>disconnect_date: ".$disconnect_date;
echo "<br>package_days: ".$package_days;
Utility::pr($connectivity);
echo '<hr>';

$staus_id = 0; $disconnect_date = '2016-12-20 17:40:30'; $package_days = 3;
$connectivity = Subscriber::calcInternectCheckinDuration($staus_id, $disconnect_date, $package_days);
echo "staus_id: ".$staus_id;
echo "<br>disconnect_date: ".$disconnect_date;
echo "<br>package_days: ".$package_days;
Utility::pr($connectivity);
echo '<hr>';

$staus_id = 1; $disconnect_date = '2016-12-20 17:40:30'; $package_days = 3;
$connectivity = Subscriber::calcInternectCheckinDuration($staus_id, $disconnect_date, $package_days);
echo "staus_id: ".$staus_id;
echo "<br>disconnect_date: ".$disconnect_date;
echo "<br>package_days: ".$package_days;
Utility::pr($connectivity);
echo '<hr>';