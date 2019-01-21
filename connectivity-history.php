<?php

/**
 * Description of Conectivity History
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

$sql = "SELECT
        p.`name` AS `package_name`
      , ca.`connection_from`
      , ca.`connection_to`
      , ca.`created_at`
      , ca.`comment`
      FROM `subscribers_connections_audit` ca
      LEFT JOIN packages p ON p.id = ca.`package_id`
      WHERE ca.`subscriber_id` = ?
      ORDER BY ca.`id_connection_key` DESC
      LIMIT 25";
$data = DB::getInstance()->query($sql, [$subscriber_id])->results();


$pageCode       = 'connectivity-history';
$pageContent	= 'connectivity-history';
$pageTitle 		= 'Connectivity History';

//
//$moreJs = ['js/unicorn.form_validation.js'];
require BASE_DIRECTORY.'/views/layouts/admin-base.phtml';
