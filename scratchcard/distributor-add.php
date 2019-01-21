<?php

/* 
 * Add Distributor
 * 
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date February 04, 2017 22:28
 */

require "../core/config.php";
require "../core/init.php";
require "../modules/acl/Roles.php";
require_once "../modules/scratchcard/Card.php";

$pageCode       = 'scratchcard-distributor-add';
$pageContent	= 'scratchcard/distributor-add';
$pageTitle 		= 'Add Scratch Card Distributor';

if(!Auth::isAuthenticatedPage($pageCode)){
    Session::put('error', "You do not have permission to access '{$pageTitle}' page.");
    Utility::redirect(BASE_URL.'/login.php');
}


require BASE_DIRECTORY.'/views/layouts/admin-base.phtml';