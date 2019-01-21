<?php 
/* 
 * Complaint Summary by Support Types
 * 
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date Oct 10, 2017 10:45 pm
 */

require "../core/config.php";
require "../core/init.php";
require "../modules/acl/Roles.php";
require "../modules/complaint/Complaint.php";
require "../modules/date_time_dropdown.php";

$pageCode       = 'complaint-support-types-summary';
$pageContent	= 'complaint/support-types-summary';
$pageTitle 		= 'Complaint Summary by Support Types';

if(!Auth::isAuthenticatedPage($pageCode)){
    Session::put('error', "You do not have permission to access '{$pageTitle}' page.");
    Utility::redirect(BASE_URL.'/login.php');
}


$date_from = Input::request('date_from');
$date_to = Input::request('date_to');

if(!empty($date_from)){
	$date_from = date('m/d/Y', strtotime($date_from));
} else{
	$date_from = date('m/01/Y');
}
if(!empty($date_to)){
	$date_to = date('m/d/Y', strtotime($date_to));
} else{
	$date_to = date('m/d/Y');
}

$dt_date_from = !empty($date_from) ? date('Y-m-d 00:00:00', strtotime($date_from)) : '';
$dt_date_to = !empty($date_to) ? date('Y-m-d 23:59:59', strtotime($date_to)) : '';

$sql = "SELECT c.`support_reason` AS `support_reason_id`
, s.`name` AS `support_reason_name`
, COUNT(1) AS tot
FROM complains c
LEFT JOIN `complaint_option_supports` s ON s.`id` = c.`support_reason`
WHERE c.`dtt_add` BETWEEN '{$dt_date_from}' AND '{$dt_date_to}'
GROUP BY c.`support_reason`";

$result = DB::getInstance()->query($sql)->results();

$data = array();
foreach($result as $res){
	$data[(int)$res['support_reason_id']]['name'] = $res['support_reason_name'];
	$data[(int)$res['support_reason_id']]['tot'] = @$data[(int)$res['support_reason_id']]['tot'] + $res['tot'];
}


require BASE_DIRECTORY.'/views/layouts/admin-base.phtml';