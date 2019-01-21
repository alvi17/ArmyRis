<?php

/**
 * List Subscribers
 * 
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date December 05, 2016 01:31
 */

require "../core/config.php";
require "../core/init.php";
require "../modules/subscriber/Subscriber.php";


$pageCode       = 'subscriber-index';
$pageContent	= 'subscriber/index';
$pageTitle 		= 'List Subscribes';

if(!Auth::isAuthenticatedPage($pageCode)){
    Session::put('error', "You do not have permission to access '{$pageTitle}' page.");
    Utility::redirect(BASE_URL.'/login.php');
}

$ranks = Utility::listRanks();
$areas = Utility::listServerAreas();
$packages = Utility::listPackages();
$statuses = Subscriber::listSubscriberStatuses();
$categories = Subscriber::listSubscriberCategories();


$ba = Input::get('ba');
$str = Input::get('str');
$rank = Input::get('rank');
$area = Input::get('area');
$bld = Input::get('bld');
$house = Input::get('house');
$cat = Input::get('cat');
$sts = Input::get('sts');
$pkg = Input::get('pkg');
$page = (int) Input::get('page');
if(empty($page)){$page = 1;}
if(is_null($sts)){$sts = -1;}

$url = BASE_URL."/subscriber/index.php?ba={$ba}&str={$str}&rank={$rank}&area={$area}&bld={$bld}&house={$house}&cat={$cat}&pkg={$pkg}&sts={$sts}&page=";



$buildings = Utility::listBuildingsByAreaId($area);

$sc = new Subscriber();
$tmp = $sc->listSubscribers($ba, $str, $rank, $area, $bld, $house, $cat, $pkg, $sts, true, $page, LIMIT_PER_PAGE);
$totalSubscribers = isset($tmp[0]['TOTAL']) ? $tmp[0]['TOTAL'] : 0;
$subscribers = $sc->listSubscribers($ba, $str, $rank, $area, $bld, $house, $cat, $pkg, $sts, false, $page, LIMIT_PER_PAGE);
$paginationStr = Utility::pagination($totalSubscribers, $url, LIMIT_PER_PAGE, $page);

require BASE_DIRECTORY.'/views/layouts/admin-base.phtml';