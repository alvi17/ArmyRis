<?php

/**
 * Corporate Subscriber Details
 * 
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date May 16, 2017 04:31
 */

require "../core/config.php";
require "../core/init.php";
require "../modules/subscriber/Subscriber.php";
require_once '../modules/mikrotik/PppoeApiService.php';


$pageCode       = 'corporate-subscriber-details';
$pageContent	= 'corporate-subscriber/details';
$pageTitle 		= 'Corporate Subscriber Details';

if(!Auth::isAuthenticatedPage($pageCode)){
    Session::put('error', "You do not have permission to access '{$pageTitle}' page.");
    Utility::redirect(BASE_URL.'/login.php');
}


$id = (int) Input::get('id');

$sql = "SELECT
		  s.`firstname`
        , s.`address`
        , s.`official_mobile`
        , s.`personal_mobile`
        , s.`residential_phone`
        , s.`email`
        , s.`status_id`
        , s.`corporate_package`
        , s.`corporate_package_price`
        , s.`remarks`
		FROM subscribers s
		WHERE s.`id_subscriber_key` = $id
		AND s.`subs_type` = 'corporate'";
$data = DB::getInstance()->query($sql)->first();
$data = DB::getInstance()->query($sql)->first();

if(empty($data)){
  Session::put('error', "Corporate Subscriber information not found.");
  Utility::redirect('corporate-list.php');
}

require BASE_DIRECTORY.'/views/layouts/admin-base.phtml';