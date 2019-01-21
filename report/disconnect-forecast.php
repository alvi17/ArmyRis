<?php

/**
 * Lists Disconnectable subscribers for a particular date
 * 
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date Jan 10, 2017 22:32
 */

require "../core/config.php";
require "../core/init.php";
require "../modules/acl/Roles.php";
require "../modules/subscriber/Subscriber.php";

$pageCode       = 'report-disconnect-forecast';
$pageContent	= 'report/disconnect-forecast';
$pageTitle 		= 'Disconnect Forecast';

if(!Auth::isAuthenticatedPage($pageCode)){
    Session::put('error', "You do not have permission to access '{$pageTitle}' page.");
    Utility::redirect(BASE_URL.'/login.php');
}

$ranks = Utility::listRanks();
$areas = Utility::listServerAreas();
$packages = Utility::listPackages();

$str = Input::get('str');
$rank = Input::get('rank');
$area = Input::get('area');
$bld = Input::get('bld');

$date_from = Input::get('date_from');
$date_to = Input::get('date_to');
$page = (int) Input::get('page');

if(empty($page)){$page = 1;}
if(empty($date_from)) {$date_from = date('d-m-Y');}
if(empty($date_to)) {$date_to = date('d-m-Y', strtotime("+3 day"));}

$buildings = Utility::listBuildingsByAreaId($area);

$url = BASE_URL."/report/disconnect-forecast.php?date_from={$date_from}&date_to={$date_to}&str={$str}&rank={$rank}&area={$area}&bld={$bld}&page=";

$sc = new Subscriber();

$data = $sc->listDisconnectableSubscribers(date("Y-m-d 00:00:00", strtotime($date_from)), date("Y-m-d 23:59:59", strtotime($date_to)), $str, $rank, $area, $bld, false, $page, LIMIT_PER_PAGE);

$tmp = $sc->listDisconnectableSubscribers(date("Y-m-d 00:00:00", strtotime($date_from)), date("Y-m-d 23:59:59", strtotime($date_to)), $str, $rank, $area, $bld, true, $page, LIMIT_PER_PAGE);
$total = isset($tmp[0]['TOTAL']) ? $tmp[0]['TOTAL'] : 0;

$paginationStr = Utility::pagination($total, $url, LIMIT_PER_PAGE, $page);

//$sql = "SELECT
//        s.`username`
//      , s.`ba_no`
//      , s.`firstname`
//      , s.`lastname`
//      , r.`name` AS `rank`
//      , s.`connection_to` AS `disconnect_at`
//      , s.`payment_balance` AS `balance`
//      FROM subscribers s
//      LEFT JOIN ranks r ON r.`id` = s.`rank_id`
//      WHERE s.`status_id` = 1
//      AND s.`connection_to` BETWEEN ? AND ?
//      ORDER BY r.`order` ASC, s.`connection_to` ASC
//      ";
//$params = [
//    date("Y-m-d 00:00:00", strtotime($date_from)),
//    date("Y-m-d 23:59:59", strtotime($date_to))
//];
//$data = DB::getInstance()->query($sql, $params)->results();





require BASE_DIRECTORY.'/views/layouts/admin-base.phtml';