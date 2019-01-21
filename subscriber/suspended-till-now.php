<?php 
//suspended-till-now.php
/**
 * Add Subscriber
 * 
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date December 05, 2016 01:31
 */

require "../core/config.php";
require "../core/init.php";
require "../modules/subscriber/Subscriber.php";
require_once '../modules/mikrotik/PppoeApiService.php';


$pageCode       = 'subscriber-suspended-till-now';
$pageContent	= 'subscriber/suspended-till-now';
$pageTitle 		= 'Subscribers Suspended Till Now';

if(!Auth::isAuthenticatedPage($pageCode)){
    Session::put('error', "You do not have permission to access '{$pageTitle}' page.");
    Utility::redirect(BASE_URL.'/login.php');
}


$fr = Input::get('fr');
$to = Input::get('to');
$page = (int)Input::get('page');
$limit = LIMIT_PER_PAGE;

if(empty($page)){$page=1;}
if(empty($fr)){
	//$fr = date('Y-m-d', strtotime('-7 days'));
	$fr = date('Y-m-d');
}
if(empty($to)){
	$to = date('Y-m-d');
}

$fr = date("Y-m-d", strtotime($fr));
$to = date("Y-m-d", strtotime($to));

$cond = !empty($router_no) ? " AND b.`router_no` = {$router_no}" : "";
$sql = "SELECT
		  s.`username`
		, s.`firstname`
		, s.`lastname`
		, r.`name` AS `rank`
		, s.`official_mobile`
		, a.`area_name` AS `area`
		, b.`building_name` AS `building`
		, s.`house_no`
		, b.`router_no`
		, p.`name` AS `package`
		, DATEDIFF(NOW(), s.`connection_to`) AS `inactive_days`
		FROM `subscribers` s
		LEFT JOIN `ranks` r ON r.`id` = s.`rank_id`
		LEFT JOIN `areas` a ON a.`id_area` = s.`area_id`
		LEFT JOIN `buildings` b ON b.`id_building` = s.`building_id`
		LEFT JOIN packages p ON p.`id` = s.`package_id`
		WHERE s.`status_id` = 0
		AND s.`connection_to` BETWEEN '{$fr} 00:00:00' AND '{$to} 23:59:59'
		ORDER BY s.`connection_to` ASC
		LIMIT ".($page-1)*$limit.", {$limit}";

$data = DB::getInstance()->query($sql)->results();


$sql = "SELECT COUNT(1) AS TOTAL
		FROM `subscribers` s
		LEFT JOIN `ranks` r ON r.`id` = s.`rank_id`
		LEFT JOIN `areas` a ON a.`id_area` = s.`area_id`
		LEFT JOIN `buildings` b ON b.`id_building` = s.`building_id`
		LEFT JOIN packages p ON p.`id` = s.`package_id`
		WHERE s.`status_id` = 0
		AND s.`connection_to` BETWEEN '{$fr} 00:00:00' AND '{$to} 23:59:59'";
$tot_array = DB::getInstance()->query($sql)->results();

$total = isset($tot_array[0]['TOTAL']) ? $tot_array[0]['TOTAL'] : 0;
$url = BASE_URL."/subscriber/suspended-till-now.php?fr={$fr}&page=";
$paginationStr = Utility::pagination($total, $url, $limit, $page);

require BASE_DIRECTORY.'/views/layouts/admin-base.phtml';