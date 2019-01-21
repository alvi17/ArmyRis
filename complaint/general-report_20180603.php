<?php

/* 
 * General Report of Complaint
 * 
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date January 12, 2017 03:42 am
 */

require "../core/config.php";
require "../core/init.php";
require "../modules/acl/Roles.php";
require "../modules/complaint/Complaint.php";
require "../modules/date_time_dropdown.php";

$pageCode       = 'complaint-general-report';
$pageContent	= 'complaint/general-report';
$pageTitle 		= 'General Report';

if(!Auth::isAuthenticatedPage($pageCode)){
    Session::put('error', "You do not have permission to access '{$pageTitle}' page.");
    Utility::redirect(BASE_URL.'/login.php');
}

$area = Input::request('area');
$ranks = Utility::listRanks();
$areas = Utility::listServerAreas();
$buildings = empty($area) ? Utility::listBuildings() : Utility::listBuildingsByAreaId($area);
$problem_types = Complaint::listProblemTypes();
$support_reasons = Complaint::listSupportReasons();

$search_txt = Input::request('search');
$building = Input::request('building');
$status = Input::request('status');

$rank = Input::request('rank');
$rank_opt = Input::request('rank_opt');

$dt_md = Input::request('dt_md');  // Date selection mode. if its value is strict then if date_fromis empty, no today's value will be set
$date_from = Input::request('date_from');
$date_to = Input::request('date_to');
$problem_type = Input::request('problem_type');
$support_reason = Input::request('support_reason');
$page = Input::request('page');
if(empty($page)){$page = 1;}

if($dt_md!='strict'){
	if(empty($date_from)){
		$date_from = date('m/d/Y');
	}
	if(empty($date_to)){
		$date_to = date('m/d/Y');
	}
}

if(!empty($date_from)){
	$date_from = date('m/d/Y', strtotime($date_from));
}
if(!empty($date_to)){
	$date_to = date('m/d/Y', strtotime($date_to));
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if(isset($_POST['pdf'])){
        $url = BASE_URL .'/complaint/pdf-general-report.php' . Complaint::prepareGeneralReportUrlPostfix($search_txt, $area, $building, $status, $rank, $rank_opt, $dt_md, $date_from, $date_to, $problem_type, $support_reason, $page);
        Utility::redirect($url);
        exit;
    } elseif(isset($_POST['list'])){
        $page = 1;
        $url = BASE_URL .'/complaint/general-report.php' . Complaint::prepareGeneralReportUrlPostfix($search_txt, $area, $building, $status, $rank, $rank_opt, $dt_md, $date_from, $date_to, $problem_type, $support_reason, $page);
        Utility::redirect($url);
        exit;
    }
}

$dt_date_from = !empty($date_from) ? date('Y-m-d 00:00:00', strtotime($date_from)) : '';
$dt_date_to = !empty($date_to) ? date('Y-m-d 23:59:59', strtotime($date_to)) : '';

$limit = 50;

$data = Complaint::listUserComplaints($search_txt, $area, $building, $status, $rank, $rank_opt, $dt_date_from, $dt_date_to, $problem_type, $support_reason, $page, $limit);
$total = Complaint::countUserComplaints($search_txt, $area, $building, $status, $rank, $rank_opt, $dt_date_from, $dt_date_to, $problem_type, $support_reason);
//$resultCount = count($data);

//Utility::pa($data); exit;

$url = BASE_URL .'/complaint/general-report.php' . Complaint::prepareGeneralReportUrlPostfix($search_txt, $area, $status, $rank, $rank_opt, $dt_md, $date_from, $date_to, $problem_type, $support_reason, $page, false)."&page=";
$paginationStr = Utility::pagination($total, $url, $limit, $page);

require BASE_DIRECTORY.'/views/layouts/admin-base.phtml';
