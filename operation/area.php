<?php

/**
 * List Areas
 * 
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date January 10, 2017 05:15
 */

require "../core/config.php";
require "../core/init.php";
require "../modules/acl/Roles.php";

$pageCode       = 'operation-area';
$pageContent	= 'operation/area';
$pageTitle 		= 'Areas';

if(!Auth::isAuthenticatedPage($pageCode)){
    Session::put('error', "You do not have permission to access '{$pageTitle}' page.");
    Utility::redirect(BASE_URL.'/login.php');
}


$sql = "SELECT `id_area` AS `id`, `area_name` AS `name`
        FROM `areas`
        WHERE status_id = 1
        ORDER BY `area_name` ASC";
$areas = DB::getInstance()->query($sql)->results();

require BASE_DIRECTORY.'/views/layouts/admin-base.phtml';