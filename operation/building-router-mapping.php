<?php

/**
 * Mapping Buildings under a Miktorik Router
 * 
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date December 29, 2016 00:39
 */

require "../core/config.php";
require "../core/init.php";
require "../modules/acl/Roles.php";

$pageCode       = 'operation-building-router-mapping';
$pageContent	= 'operation/building-router-mapping';
$pageTitle 		= 'Building-Router Mapping';

if(!Auth::isAuthenticatedPage($pageCode)){
    Session::put('error', "You do not have permission to access '{$pageTitle}' page.");
    Utility::redirect(BASE_URL.'/login.php');
}


$area = Input::get('area');
$areas = Utility::listServerAreas();
$buildings = Utility::listBuildingDetailsByAreaId($area);


require BASE_DIRECTORY.'/views/layouts/admin-base.phtml';