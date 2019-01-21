<?php

/**
 * Survey Index
 * 
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date Mar 18, 2017 18:32
 */

require "../core/config.php";
require "../core/init.php";
require "../modules/acl/Roles.php";
require "../modules/Survey.php";


$pageCode       = 'survey-index';
$pageContent	= 'survey/index';
$pageTitle 		= 'Survey List';

if(!Auth::isAuthenticatedPage($pageCode)){
    Session::put('error', "You do not have permission to access '{$pageTitle}' page.");
    Utility::redirect(BASE_URL.'/login.php');
}


$surveys = Survey::listSurveys();

require BASE_DIRECTORY.'/views/layouts/admin-base.phtml';