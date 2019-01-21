<?php

/**
 * List roles
 * 
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date December 05, 2016 01:31
 */

require "../core/config.php";
require "../core/init.php";
require "../modules/acl/Roles.php";

$pageCode       = 'role-index';
$pageContent	= 'role/index';
$pageTitle 		= 'List Roles';

if(!Auth::isAuthenticatedPage($pageCode)){
    Session::put('error', "You do not have permission to access '{$pageTitle}' page.");
    Utility::redirect(BASE_URL.'/login.php');
}


$sql = "SELECT `id`, `name` FROM roles ORDER BY `id` ASC";
$roles = DB::getInstance()->query($sql)->results();

require BASE_DIRECTORY.'/views/layouts/admin-base.phtml';