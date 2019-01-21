<?php

/**
 * Description of User Detail
 *
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date October 22, 2016 22:57
 */

require "../core/config.php";
require "../core/init.php";
require "../modules/user/User.php";


$pageCode       = 'user-detail';
$pageContent	= 'user/detail';
$pageTitle 		= 'User Detail';

if(!Auth::isAuthenticatedPage($pageCode)){
    Session::put('error', "You have no permission to access '{$pageTitle}' page.");
    Utility::redirect(BASE_URL.'/login.php');
}

$user = new User();
$ranks = Utility::listRanks();
$roles = Utility::listRoles();

$userid = (int) trim($_GET['userid']);

list($username, $ba_no, $firstname, $lastname, $rank, $mobile, $email, $status, $is_support_asst, $uroles) = $user->listUserData($userid);
$password = '';
$confirm_password = '';
//Utility::dd($tmp);
//Utility::dd($username);


require BASE_DIRECTORY.'/views/layouts/admin-base.phtml';
