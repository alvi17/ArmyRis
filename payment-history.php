<?php

/**
 * Payment History page for Subscriber
 *
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date October 22, 2016 22:57
 */

require "core/config.php";
require "core/init.php";
require "modules/subscriber/Subscriber.php";

if (!Auth::isSubscriberUser()){
    Utility::redirect(BASE_URL);
}

$subscriber_id = Session::get('uid');
$recharge_params = Payment::rechargAcountParams($subscriber_id);

$sql = "SELECT p.`type`
        , if(p.`debit`=0, '', p.`debit`) AS `debit`
        , if(p.`credit`=0, '', p.`credit`) AS `credit`
        , p.`balance`
        , p.`created_at`
        FROM `payments` p
        WHERE p.`subscriber_id` = ?
        AND p.`is_active` = 1
        ORDER BY p.`id_payment_key` DESC
        LIMIT 25";
$data = DB::getInstance()->query($sql, [$subscriber_id])->results();

$pageCode       = 'payment-history';
$pageContent	= 'payment-history';
$pageTitle 		= 'Payment History';

//$moreJs = ['js/unicorn.form_validation.js'];
require BASE_DIRECTORY.'/views/layouts/admin-base.phtml';
