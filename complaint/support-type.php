<?php
/* 
 * Lists Support Types
 * 
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date April 08, 2017 22:29
 */

require "../core/config.php";
require "../core/init.php";
require "../modules/acl/Roles.php";
require "../modules/complaint/Complaint.php";
require "../modules/date_time_dropdown.php";

$pageCode       = 'complaint-support-type';
$pageContent	= 'complaint/support-type';
$pageTitle 		= 'Support Types';

if(!Auth::isAuthenticatedPage($pageCode)){
    Session::put('error', "You do not have permission to access '{$pageTitle}' page.");
    Utility::redirect(BASE_URL.'/login.php');
}


$support_types = [];
$sql = "SELECT s.`id`, s.`name`, s.`is_active`
        FROM `complaint_option_supports` s
        ORDER BY s.`name` ASC";
$result = DB::getInstance()->query($sql)->results();

foreach($result as $res){
    $support_types[$res['id']] =  [
        'name'=> $res['name'],
        'is_active' => $res['is_active'],
    ];
}

require BASE_DIRECTORY.'/views/layouts/admin-base.phtml';