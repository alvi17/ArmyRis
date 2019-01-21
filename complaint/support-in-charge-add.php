<?php

/* 
 * Add Support in Charge
 * 
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date January 12, 2017 03:42 am
 */

require "../core/config.php";
require "../core/init.php";
require "../modules/acl/Roles.php";
require "../modules/complaint/Complaint.php";
require "../modules/date_time_dropdown.php";

$pageCode       = 'complaint-support-in-charge-add';
$pageContent	= 'complaint/support-in-charge-add';
$pageTitle 		= 'Add Support in Charge';

if(!Auth::isAuthenticatedPage($pageCode)){
    Session::put('error', "You do not have permission to access '{$pageTitle}' page.");
    Utility::redirect(BASE_URL.'/login.php');
}

require BASE_DIRECTORY.'/views/layouts/admin-base.phtml';