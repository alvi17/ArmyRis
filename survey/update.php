<?php

/**
 * Update Survey
 * 
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date Mar 18, 2017 18:32
 */

require "../core/config.php";
require "../core/init.php";
require "../modules/acl/Roles.php";


$pageCode       = 'survey-update';
$pageContent	= 'survey/update';
$pageTitle 		= 'Update Survey';

if(!Auth::isAuthenticatedPage($pageCode)){
    Session::put('error', "You do not have permission to access '{$pageTitle}' page.");
    Utility::redirect(BASE_URL.'/login.php');
}

require BASE_DIRECTORY.'/views/layouts/admin-base.phtml';