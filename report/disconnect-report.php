<?php

/**
 * Lists Disconnected subscribers
 * 
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date Feb 18, 2017 14:32
 */

require "../core/config.php";
require "../core/init.php";
require "../modules/acl/Roles.php";
require "../modules/subscriber/Subscriber.php";

$pageCode       = 'report-disconnect-report';
$pageContent	= 'report/disconnect-report';
$pageTitle 		= 'Disconnect Report';

if(!Auth::isAuthenticatedPage($pageCode)){
    Session::put('error', "You do not have permission to access '{$pageTitle}' page.");
    Utility::redirect(BASE_URL.'/login.php');
}

$date = Input::get('date');

if(empty($page)){$page = 1;}
if(empty($date)) {$date = date('d-m-Y');}

$sql = "SELECT
            s.`username`
          , b.`router_no`
          , s.`firstname`
          , s.`lastname`
          , r.`name` AS `rank`
          , s.`house_no`
          , b.`building_name` AS `building`
          , a.`area_name` AS `area`
          , s.`connection_to` AS `disconnect_schedule`
          , sa.`created_at` AS `disconneted_at`
          FROM subscribers_connections_audit sa
          INNER JOIN subscribers s ON s.`id_subscriber_key` = sa.`subscriber_id`
          INNER JOIN buildings b ON b.`id_building` = s.`building_id`
          INNER JOIN areas a ON a.`id_area` = s.`area_id`
          LEFT JOIN ranks r ON r.`id` = s.`rank_id`
          WHERE DATE(sa.`created_at`) = ?
          AND sa.`comment` = 'Account Suspended by System'";
$params = [date("Y-m-d 00:00:00", strtotime($date))];
$data = DB::getInstance()->query($sql, $params)->results();

//Utility::pa($data);

require BASE_DIRECTORY.'/views/layouts/admin-base.phtml';