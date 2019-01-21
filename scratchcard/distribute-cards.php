<?php

/* 
 * Distribute Scratch Cards
 * 
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date February 04, 2017 22:28
 */

require "../core/config.php";
require "../core/init.php";
require "../modules/acl/Roles.php";
require_once "../modules/scratchcard/Card.php";

$pageCode       = 'scratchcard-distribute-cards';
$pageContent	= 'scratchcard/distribute-cards';
$pageTitle 		= 'Distribute Scratch Cards';

if(!Auth::isAuthenticatedPage($pageCode)){
    Session::put('error', "You do not have permission to access '{$pageTitle}' page.");
    Utility::redirect(BASE_URL.'/login.php');
}


require BASE_DIRECTORY.'/views/layouts/admin-base.phtml';