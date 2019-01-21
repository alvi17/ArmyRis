<?php

/**
 * List of Corporate Subscriber
 * 
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date May 16, 2017 04:31
 */


require "../core/config.php";
require "../core/init.php";
require "../modules/subscriber/Subscriber.php";
require_once '../modules/mikrotik/PppoeApiService.php';


$pageCode       = 'corporate-subscriber-list';
$pageContent	= 'corporate-subscriber/list';
$pageTitle 		= 'Corporate Subscribers';

if(!Auth::isAuthenticatedPage($pageCode)){
    Session::put('error', "You do not have permission to access '{$pageTitle}' page.");
    Utility::redirect(BASE_URL.'/login.php');
}

$page = (int)Input::get('page');
if(empty($page)){$page=1;}
$limit = LIMIT_PER_PAGE;

$sql = "SELECT
		  s.`id_subscriber_key` AS `id`
		, s.`firstname`
		, s.`address`
		, s.`official_mobile`
		, s.`personal_mobile`
		, s.`residential_phone`
		, s.`email`
		, s.`status_id`
		, s.`corporate_package` AS `package`
        , s.`corporate_package_price` AS `price`
		FROM subscribers s
		WHERE s.`subs_type` = 'corporate'
		ORDER BY s.`created_at` ASC
		LIMIT ".($page-1)*$limit.", {$limit}";
$data = DB::getInstance()->query($sql)->results();


$sql = "SELECT COUNT(1) AS TOTAL
		FROM subscribers s
		LEFT JOIN packages p ON p.`id` = s.`package_id`
		WHERE s.`subs_type` = 'corporate'";
$tot_array = DB::getInstance()->query($sql)->results();

$total = isset($tot_array[0]['TOTAL']) ? $tot_array[0]['TOTAL'] : 0;
$url = BASE_URL."/subscriber/corporate-list.php?page=";
$paginationStr = Utility::pagination($total, $url, $limit, $page);

require BASE_DIRECTORY.'/views/layouts/admin-base.phtml';