<?php

/* 
 * Lists Scratchcards and Search Scratchcards using a key
 * 
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date January 12, 2017 03:42 am
 */

require "../core/config.php";
require "../core/init.php";
require "../modules/acl/Roles.php";
require_once "../modules/scratchcard/Card.php";

$pageCode       = 'scratchcard-index';
$pageContent	= 'scratchcard/index';
$pageTitle 		= 'Scratch Cards';

if(!Auth::isAuthenticatedPage($pageCode)){
    Session::put('error', "You do not have permission to access '{$pageTitle}' page.");
    Utility::redirect(BASE_URL.'/login.php');
}

$keyword = Input::get('keyword');
$page = (int) Input::get('page');
if(empty($page)){$page = 1;}
$keyword = strtoupper($keyword);

$url = BASE_URL."/scratchcard/index.php?keyword={$keyword}&page=";

$card = new Card();

$data = $card->listCards($keyword, false, $page, LIMIT_PER_PAGE);

$tmp = $card->listCards($keyword, true, $page, LIMIT_PER_PAGE);
$total = isset($tmp[0]['TOTAL']) ? $tmp[0]['TOTAL'] : 0;

$paginationStr = Utility::pagination($total, $url, LIMIT_PER_PAGE, $page);

require BASE_DIRECTORY.'/views/layouts/admin-base.phtml';