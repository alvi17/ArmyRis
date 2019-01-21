<?php

/**
 * Lists Buildings
 * 
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date January 10, 2017 06:08
 */

require "../core/config.php";
require "../core/init.php";
require "../modules/acl/Roles.php";

$pageCode       = 'operation-building';
$pageContent	= 'operation/building';
$pageTitle 		= 'List Buildings';

if(!Auth::isAuthenticatedPage($pageCode)){
    Session::put('error', "You do not have permission to access '{$pageTitle}' page.");
    Utility::redirect(BASE_URL.'/login.php');
}


$area = Input::get('area');
$router = Input::get('router');
$areas = Utility::listServerAreas();
$buildings = Location::listBuildingsByAreaRouter($area, $router);





require BASE_DIRECTORY.'/views/layouts/admin-base.phtml';