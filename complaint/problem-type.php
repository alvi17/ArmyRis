<?php

/* 
 * Lists Problem Types
 * 
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date January 28, 2017 11:58 am
 */

require "../core/config.php";
require "../core/init.php";
require "../modules/acl/Roles.php";
require "../modules/complaint/Complaint.php";
require "../modules/date_time_dropdown.php";

$pageCode       = 'complaint-problem-type';
$pageContent	= 'complaint/problem-type';
$pageTitle 		= 'Problem Types';

if(!Auth::isAuthenticatedPage($pageCode)){
    Session::put('error', "You do not have permission to access '{$pageTitle}' page.");
    Utility::redirect(BASE_URL.'/login.php');
}


$sql = "SELECT p.`id`, p.`name`
        FROM `complaint_option_problems` p
        WHERE p.`is_active` = 1
        ORDER BY p.`name` ASC";
$data = DB::getInstance()->query($sql)->results();

require BASE_DIRECTORY.'/views/layouts/admin-base.phtml';