<?php

/* 
 * Frequency Report
 * 
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date January 12, 2017 03:42 am
 */

require "../core/config.php";
require "../core/init.php";
require "../modules/acl/Roles.php";
require "../modules/complaint/Complaint.php";
require "../modules/date_time_dropdown.php";

$pageCode       = 'complaint-frequecny-report';
$pageContent	= 'complaint/frequecny-report';
$pageTitle 		= 'Frequency Report';

if(!Auth::isAuthenticatedPage($pageCode)){
    Session::put('error', "You do not have permission to access '{$pageTitle}' page.");
    Utility::redirect(BASE_URL.'/login.php');
}


$frequency_default = 2;
$frequency_opt_default = 1;

$area = Input::request('area');
$building = Input::request('building');

$ranks = Utility::listRanks();
$areas = Utility::listServerAreas();
$buildings = empty($area) ? Utility::listBuildings() : Utility::listBuildingsByAreaId($area);

$problem_types = Complaint::listProblemTypes();
$support_reasons = Complaint::listSupportReasons();

$search_txt = Input::request('search');
$status = Input::request('status');

$rank = Input::request('rank');
$rank_opt = Input::request('rank_opt');

$date_from = Input::request('date_from');
$date_to = Input::request('date_to');
$problem_type = Input::request('problem_type');
$frequency = (int) Input::request('frequency');
if(empty($frequency)){$frequency = 2;}
$frequency_opt = isset($_REQUEST['frequency_opt']) ? (int) Input::request('frequency_opt') : 1;

$page = Input::request('page');
if(empty($page)){$page = 1;}

if(empty($date_from)){
    $date_from = date('m/d/Y');
}  else {
    $date_from = date('m/d/Y', strtotime($date_from));
}
if(empty($date_to)){
    $date_to = date('m/d/Y');
}  else {
    $date_to = date('m/d/Y', strtotime($date_to));
}


$dt_date_from = !empty($date_from) ? date('Y-m-d 00:00:00', strtotime($date_from)) : '';
$dt_date_to = !empty($date_to) ? date('Y-m-d 23:59:59', strtotime($date_to)) : '';

$limit = 50;

$data = Complaint::frequencyComplaints($search_txt, $area, $building, $status, $rank, $rank_opt, $dt_date_from, $dt_date_to, $problem_type, $frequency, $frequency_opt, $page, $limit);


require BASE_DIRECTORY.'/views/layouts/admin-base.phtml';