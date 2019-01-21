<?php

/**
 * List Packages
 * 
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date December 09, 2016 12:31
 */

require "../core/config.php";
require "../core/init.php";
require "../modules/acl/Roles.php";

$pageCode       = 'operation-package-index';
$pageContent	= 'operation/package-index';
$pageTitle 		= 'Packages';

if(!Auth::isAuthenticatedPage($pageCode)){
    Session::put('error', "You do not have permission to access '{$pageTitle}' page.");
    Utility::redirect(BASE_URL.'/login.php');
}


$sql = "SELECT `id`, `code`, `name`, `mb_unit_value`, `price`, `days`, `status_id`
        FROM `packages`
        ORDER BY `mb_unit_value` DESC";
$packages = DB::getInstance()->query($sql)->results();

require BASE_DIRECTORY.'/views/layouts/admin-base.phtml';