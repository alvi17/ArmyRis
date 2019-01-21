<?php

/**
 * Description of User List
 *
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date October 22, 2016 22:57
 */

require "../core/config.php";
require "../core/init.php";
require "../modules/user/User.php";

$pageCode       = 'user-index';
$pageContent	= 'user/index';
$pageTitle 		= 'List Users';

if(!Auth::isAuthenticatedPage($pageCode)){
    Session::put('error', "You do not have permission to access '{$pageTitle}' page.");
    Utility::redirect(BASE_URL.'/login.php');
}


$user = new User();


$page = (int)Input::get('page');
if(empty($page)){$page=1;}
$limit = LIMIT_PER_PAGE;

// $db = DB::connectDb();
// $query = $db->prepare($sql);
// $query->execute();
// $data = $query->fetchAll();
// $count = $query->rowCount();


$sql = "SELECT
         u.`id`
       , u.`username`
       , TRIM(CONCAT_WS(' ', u.`firstname`, u.`lastname`)) AS fullname
       , rnk.`name` AS rank
       , u.`mobile`
       , u.`status_id`
       FROM `users` u
       LEFT JOIN `ranks` rnk ON rnk.`id` = u.`rank`
       WHERE u.`status_id` <> 2
       ORDER BY rnk.`order` ASC, u.`firstname` ASC
       LIMIT ".($page-1)*$limit.", {$limit}";
$data = DB::getInstance()->query($sql)->results();

foreach($data as $key=>$val){
    $data[$key]['roles'] = $user->getUserRolesAsString($val['id']);
}

$sql = "SELECT COUNT(1) AS TOTAL
       FROM `users` u
       LEFT JOIN `ranks` rnk ON rnk.`id` = u.`rank`
       WHERE u.`status_id` <> 2";
$tot_array = DB::getInstance()->query($sql)->results();

$total = isset($tot_array[0]['TOTAL']) ? $tot_array[0]['TOTAL'] : 0;
$url = BASE_URL."/user/index.php?page=";
$paginationStr = Utility::pagination($total, $url, $limit, $page);


require BASE_DIRECTORY.'/views/layouts/admin-base.phtml';