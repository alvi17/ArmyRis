<?php

/**
 * Lists latest 25 payment histories for a subscriber
 * 
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date Jan 10, 2017 22:32
 */

require "../core/config.php";
require "../core/init.php";
require "../modules/acl/Roles.php";

$pageCode       = 'report-payment-history';
$pageContent	= 'report/payment-history';
$pageTitle 		= 'Payment History';

if(!Auth::isAuthenticatedPage($pageCode)){
    Session::put('error', "You do not have permission to access '{$pageTitle}' page.");
    Utility::redirect(BASE_URL.'/login.php');
}

$username = Input::get('username');

$sql = "SELECT p.`type`
        , if(p.`debit`=0, '', p.`debit`) AS `debit`
        , if(p.`credit`=0, '', p.`credit`) AS `credit`
        , p.`balance`
        , p.`created_at`
        FROM `payments` p
        INNER JOIN `subscribers` s ON s.`id_subscriber_key` = p.`subscriber_id`
        WHERE s.`username` = ?
        ORDER BY p.`id_payment_key` DESC
        LIMIT 25";
$data = DB::getInstance()->query($sql, [$username])->results();

require BASE_DIRECTORY.'/views/layouts/admin-base.phtml';