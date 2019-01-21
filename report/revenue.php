<?php

/**
 * Lists Revenue within a date-range
 * 
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date Jan 06, 2017 07:44
 */

require "../core/config.php";
require "../core/init.php";
require "../modules/acl/Roles.php";

$pageCode       = 'report-revenue';
$pageContent	= 'report/revenue';
$pageTitle 		= 'Revenue Report';

if(!Auth::isAuthenticatedPage($pageCode)){
    Session::put('error', "You do not have permission to access '{$pageTitle}' page.");
    Utility::redirect(BASE_URL.'/login.php');
}


$date_from = Input::get('date_from');
$date_to = Input::get('date_to');
//if(empty($date_from)) {$date_from = date('01-m-Y');}
if(empty($date_from)) {$date_from = date('d-m-Y');}
if(empty($date_to)) {$date_to = date('d-m-Y');}

$limit = 100;
$page = (int) Input::get('page');
if(empty($page)){$page = 1;}

$params = [
    date("Y-m-d 00:00:00", strtotime($date_from)),
    date("Y-m-d 23:59:59", strtotime($date_to))
];

$sql = "SELECT
        s.`username`
      , s.`firstname`
      , c.`code` AS `scratch_card`
      , p.`credit` AS `amount`
      , p.`type`
      , p.`comment`
      , p.`created_at` AS `date`
      FROM `payments` p
      INNER JOIN subscribers s ON s.`id_subscriber_key` = p.`subscriber_id`
      LEFT JOIN scratch_cards c ON c.`id_card_key` = p.`ref_id` 
      WHERE (p.`type` = 'Scratch Card' OR p.`type` = 'Corporate Payment')
      AND p.`created_at` BETWEEN ? AND ?
      ORDER BY p.`created_at` ASC
	  LIMIT ".($page-1)*$limit.", {$limit}";
$revenue = DB::getInstance()->query($sql, $params)->results();

$sql = "SELECT COUNT(1) AS TOTAL
      FROM `payments` p
      INNER JOIN subscribers s ON s.`id_subscriber_key` = p.`subscriber_id`
      LEFT JOIN scratch_cards c ON c.`id_card_key` = p.`ref_id` 
      WHERE (p.`type` = 'Scratch Card' OR p.`type` = 'Corporate Payment')
      AND p.`created_at` BETWEEN ? AND ?";
$tmp = DB::getInstance()->query($sql, $params)->results();
$total = isset($tmp[0]['TOTAL']) ? $tmp[0]['TOTAL'] : 0;

$url = BASE_URL."/report/revenue.php?date_from={$date_from}&date_to={$date_to}&page=";
$paginationStr = Utility::pagination($total, $url, $limit, $page);


$sql = "SELECT
		  SUM(IF(p.`type` = 'Scratch Card', p.`credit`, 0)) AS `card_amount`
		, SUM(IF(p.`type` = 'Corporate Payment', p.`credit`, 0)) AS `corporate_amount`
		, SUM(p.`credit`) AS `total_amount`
		, SUM(p.`type` = 'Scratch Card') AS `total_cards`
		FROM `payments` p
		INNER JOIN subscribers s ON s.`id_subscriber_key` = p.`subscriber_id`
		LEFT JOIN scratch_cards c ON c.`id_card_key` = p.`ref_id` 
		WHERE (p.`type` = 'Scratch Card' OR p.`type` = 'Corporate Payment')
		AND p.`created_at` BETWEEN ? AND ?";
$tmp = DB::getInstance()->query($sql, $params)->first();
$card_amount = isset($tmp['card_amount']) ? $tmp['card_amount'] : 0;
$corporate_amount = isset($tmp['corporate_amount']) ? $tmp['corporate_amount'] : 0;
$total_amount = isset($tmp['total_amount']) ? $tmp['total_amount'] : 0;
$total_cards = isset($tmp['total_cards']) ? $tmp['total_cards'] : 0;


$sql = "SELECT
            b.`id`
          , b.`date`
          , b.`amount`
          , u.`firstname`
          , u.`lastname`
          FROM bank_deposits b
          LEFT JOIN users u ON u.`id`=b.`uid_add`
          WHERE b.`date` BETWEEN ? AND ?
          ORDER BY b.`date` ASC";
$deposit = DB::getInstance()->query($sql, $params)->results();
$total_deposit = 0;
foreach($deposit as $d){
    $total_deposit += $d['amount'];
}

require BASE_DIRECTORY.'/views/layouts/admin-base.phtml';

