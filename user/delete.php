<?php

/**
 * Delete User
 *
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date May 27, 2016 08:02
 */

require "../core/config.php";
require "../core/init.php";
require "../modules/user/User.php";

$pageCode       = 'user-delete';
$pageContent	= 'user/delete';
$pageTitle 		= 'Delete User';

if(!Auth::isAuthenticatedPage($pageCode)){
    Session::put('error', "You do not have permission to access '{$pageTitle}' page.");
    Utility::redirect(BASE_URL.'/user/index.php');
}


$user = new User();
$db = DB::connectDb();


$id = (int) Input::get('id');
$uname = Input::get('uname');


$sql = "UPDATE users u
		SET u.`status_id` = 2
		, u.`version` = u.`version`+1
		, u.`updated_at` = '".date('Y-m-d H:i:s')."'
		, u.`updated_by` = ".Session::get('uid')."
		WHERE u.`id` = ".$id;


DB::connectDb()->exec($sql);

Session::put('success', 'User "'.$uname.'" deleted successfully.');
Utility::redirect('index.php');