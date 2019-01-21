<?php

/**
 * My Account page for System User
 *
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date October 22, 2016 22:57
 */


$user = new User();
$ranks = Utility::listRanks();
$roles = Utility::listRoles();

$uid = Session::get('uid');

list($username, $ba_no, $firstname, $lastname, $rank, $mobile, $email, $status, $uroles) = $user->listUserData($uid);
if(!is_array($uroles)){$uroles = [$uroles];}


$pageCode       = 'my-account';
$pageContent	= 'my-account-system';
$pageTitle 		= 'My Account';

require BASE_DIRECTORY.'/views/layouts/admin-base.phtml';

