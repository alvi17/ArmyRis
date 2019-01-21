<?php

/**
 * Dashboard for System User
 *
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date October 22, 2016 22:57
 */

$pageCode       = 'dashboard';
$pageTitle 		= 'Dashboard';
$pageContent    = 'index-system';


$dtt_from = Date::firstDateCurrMonth() . ' 00:00:00';
$dt_to = date('Y-m-d H:i:s');

$dt_from = Date::firstDateCurrMonth();
$dt_to = date('Y-m-d');


if(in_array(1, Session::get('user_roles'))){

	## Subscribers by Category
	$sql = "SELECT 
	  SUM(s.`category`='Paid') AS `paid`
	, SUM(s.`category`='Complementary') AS `complementary`
	, SUM(s.`category`='Free') AS `free`
	, COUNT(1) AS `total`
	FROM subscribers s
	WHERE s.`subs_type` = 'default'
	AND s.`status_id` <> 2";
	$SubscribersByCategory = DB::getInstance()->query($sql)->first();


	## Subscribers by Package
	$sql = "SELECT 
	  s.`package_id`
	, p.`name` AS `package`
	, COUNT(1) AS `total`
	FROM subscribers s
	INNER JOIN packages p ON p.`id` = s.`package_id`
	WHERE s.`subs_type` = 'default'
	AND s.`status_id` <> 2
	GROUP BY p.`name`
	ORDER BY p.`mb_unit_value` DESC, p.`price` DESC";
	$SubscribersByPackage = DB::getInstance()->query($sql)->results();

	$SubscribersByPackageTotal = 0;
	foreach($SubscribersByPackage as $sp){
		$SubscribersByPackageTotal += $sp['total'];
	}


	## Subscribers by Rank
	$sql = "SELECT 
	  s.`rank_id`
	, r.`name` AS `rank`
	, COUNT(1) AS `total`
	FROM subscribers s
	INNER JOIN ranks r ON r.`id` = s.`rank_id`
	WHERE s.`subs_type` = 'default'
	AND s.`status_id` <> 2
	GROUP BY r.`name`
	ORDER BY r.`order` ASC";
	$SubscribersByRank = DB::getInstance()->query($sql)->results();

	$SubscribersByRankTotal = 0;
	foreach($SubscribersByRank as $sr){
		$SubscribersByRankTotal += $sr['total'];
	}


	## Corporate Subscribers
	$sql = "SELECT 
	  SUM(s.`status_id`=1) 	AS `active`
	, SUM(s.`status_id`=0) 	AS `suspended`
	, COUNT(1) 				AS `total`
	FROM subscribers s
	WHERE s.`subs_type` = 'corporate'
	AND s.`status_id` <> 2";
	$CorporateSubscribers = DB::getInstance()->query($sql)->first();

	## Revenue
	$sql = "SELECT SUM(p.`credit`) AS `total`
	      FROM `payments` p
	      INNER JOIN subscribers s ON s.`id_subscriber_key` = p.`subscriber_id`
	      LEFT JOIN scratch_cards c ON c.`id_card_key` = p.`ref_id` 
	      WHERE (p.`type` = 'Scratch Card' OR p.`type` = 'Corporate Payment')
	      AND p.`created_at` BETWEEN ? AND ?";

	// today
	$today = date('Y-m-d');
	$params = [
	    date("Y-m-d 00:00:00", strtotime($today)),
	    date("Y-m-d 23:59:59", strtotime($today))
	];
	$revenue_today = DB::getInstance()->query($sql, $params)->first();

	// this week
	$firstDateCurrWeek = Date::firstDateCurrWeek();
	$lastDateCurrWeek = Date::lastDateCurrWeek();
	$params = [
	    date("Y-m-d 00:00:00", strtotime($firstDateCurrWeek)),
	    date("Y-m-d 23:59:59", strtotime($lastDateCurrWeek))
	];
	$revenue_this_week = DB::getInstance()->query($sql, $params)->first();

	// this month
	$firstDateCurrMonth = Date::firstDateCurrMonth();
	$lastDateCurrMonth = Date::lastDateCurrMonth();
	if(Date::isFutureDate($lastDateCurrMonth)){
		$lastDateCurrMonth = $today;
	}
	$params = [
	    date("Y-m-d 00:00:00", strtotime($firstDateCurrMonth)),
	    date("Y-m-d 23:59:59", strtotime($lastDateCurrMonth))
	];
	$revenue_this_month = DB::getInstance()->query($sql, $params)->first();

	// this year
	$firstDateCurrYear = Date::firstDateCurrYear();
	$lastDateCurrYear = Date::lastDateCurrYear();
	if(Date::isFutureDate($lastDateCurrYear)){
		$lastDateCurrYear = $today;
	}
	$params = [
	    date("Y-m-d 00:00:00", strtotime($firstDateCurrYear)),
	    date("Y-m-d 23:59:59", strtotime($lastDateCurrYear))
	];
	$revenue_this_year = DB::getInstance()->query($sql, $params)->first();
}


## Subscribers by Status
$sql = "SELECT 
  SUM(s.`status_id`=1) 	AS `active`
, SUM(s.`status_id`=0) 	AS `suspended`
, COUNT(1) 				AS `total`
FROM subscribers s
WHERE s.`subs_type` = 'default'
AND s.`status_id` <> 2";
$SubscribersByStatus = DB::getInstance()->query($sql)->first();
	
## Complaint by Statuses
$sql = "SELECT
  SUM(c.`id_status`=1)  AS `informed`
, SUM(c.`id_status`=2)  AS `in_progress`
, SUM(c.`id_status`=3)  AS `on_hold`
, SUM(c.`id_status`=4)  AS `pending`
, SUM(c.`id_status`=5)  AS `completed`
, SUM(c.`id_status`=6)  AS `cancled`
, COUNT(1) 			    AS `total`
FROM complains c
INNER JOIN `complaint_option_problems` p ON p.`id` = c.`pb_type`
WHERE c.`dtt_add` BETWEEN ? AND ?";
$ComplaintByStatuses = DB::getInstance()->query($sql, [$dtt_from, $dt_to])->first();


## Complaint by Types
$sql = "SELECT 
  c.`pb_type`
, p.`name` AS `problem`
, COUNT(1) AS `total`
FROM complains c
INNER JOIN `complaint_option_problems` p ON p.`id` = c.`pb_type`
WHERE c.`dtt_add` BETWEEN ? AND ?
GROUP BY p.`name`";
$ComplaintByTypes = DB::getInstance()->query($sql, [$dtt_from, $dt_to])->results();
$ComplaintByTypesTotal = 0;
foreach($ComplaintByTypes as $sr){
	$ComplaintByTypesTotal += $sr['total'];
}

## Complaint by Support Types
$sql = "SELECT 
  c.`support_reason`
, spt.`name` AS `support`
, COUNT(1) AS `total`
FROM complains c
LEFT JOIN complaint_option_supports spt ON spt.id = c.support_reason
WHERE c.`dtt_add` BETWEEN ? AND ?
GROUP BY spt.`name`";
$ComplaintBySupportTypes = DB::getInstance()->query($sql, [$dtt_from, $dt_to])->results();
$ComplaintBySupportTypesTotal = 0;
foreach($ComplaintBySupportTypes as $sr){
	$ComplaintBySupportTypesTotal += $sr['total'];
}

## Top Complainee
$sql = "SELECT 
--  s.`id_subscriber_key`
  s.`ba_no`
, s.`rank_id`
, r.`name` AS `rank`
, s.`firstname`
, s.`lastname`
, t.`total`
FROM subscribers s
INNER JOIN ranks r ON r.`id`=s.`rank_id`
INNER JOIN (
    SELECT c.`subscriber_id`
    , COUNT(1) AS total
    FROM complains c
    WHERE c.`subscriber_id` <> 0
    AND c.`dtt_add` BETWEEN ? AND ?
    GROUP BY c.`subscriber_id`
) AS t ON t.`subscriber_id` = s.`id_subscriber_key` AND s.`status_id` <> 2
ORDER BY t.`total` DESC
LIMIT 10";
$topComplainants = DB::getInstance()->query($sql, [$dtt_from, $dt_to])->results();





require BASE_DIRECTORY.'/views/layouts/admin-base.phtml';

