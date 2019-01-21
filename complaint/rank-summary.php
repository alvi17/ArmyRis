<?php 
/* 
 * Rank-wise Complaint Summary
 * 
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date Oct 10, 2017 10:45 pm
 */

require "../core/config.php";
require "../core/init.php";
require "../modules/acl/Roles.php";
require "../modules/complaint/Complaint.php";
require "../modules/date_time_dropdown.php";

$pageCode       = 'complaint-rank-summary';
$pageContent	= 'complaint/rank-summary';
$pageTitle 		= 'Rank-wise Complaint Summary';

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


$sql = "SELECT
s.`rank_id`
, r.`name` AS `rank`
, COUNT(1) AS tot
FROM complains c
INNER JOIN subscribers s ON s.`id_subscriber_key` = c.`subscriber_id`
INNER JOIN ranks r ON r.`id`=s.`rank_id`
WHERE c.`dtt_add` BETWEEN '{$dt_date_from}' AND '{$dt_date_to}'
GROUP BY s.`rank_id`
ORDER BY r.`order` ASC";

$result = DB::getInstance()->query($sql)->results();

/*$SubscribersByRankTotal = 0;
foreach($result as $sr){
	$SubscribersByRankTotal += $sr['total'];
}*/

require BASE_DIRECTORY.'/views/layouts/admin-base.phtml';