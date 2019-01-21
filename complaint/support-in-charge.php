<?php

/* 
 * Support in Charge
 * 
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date January 12, 2017 03:42 am
 */

require "../core/config.php";
require "../core/init.php";
require "../modules/acl/Roles.php";
require "../modules/complaint/Complaint.php";
require "../modules/date_time_dropdown.php";

$pageCode       = 'complaint-support-in-charge';
$pageContent	= 'complaint/support-in-charge';
$pageTitle 		= 'Support in Charge';

if(!Auth::isAuthenticatedPage($pageCode)){
    Session::put('error', "You do not have permission to access '{$pageTitle}' page.");
    Utility::redirect(BASE_URL.'/login.php');
}

$sql = "SELECT
            a.`id_area` AS `id`
          , a.`area_name`
          , u.`firstname`
          , u.`lastname`
          , u.`mobile`
          FROM `areas` a
          LEFT JOIN `users` u ON u.`id` = a.`support_in_charge_id` AND u.`status_id` = 1
          WHERE a.`status_id` = 1
          ORDER BY a.`area_name` ASC";
$data = DB::getInstance()->query($sql)->results();

require BASE_DIRECTORY.'/views/layouts/admin-base.phtml';