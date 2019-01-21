<?php

/* 
 * Lists Scratchcard Lots
 * 
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date February 04, 2017 22:28
 */

require "../core/config.php";
require "../core/init.php";
require "../modules/acl/Roles.php";
require_once "../modules/scratchcard/Card.php";

$pageCode       = 'scratchcard-lots';
$pageContent	= 'scratchcard/lots';
$pageTitle 		= 'Scratch Card Lots';

if(!Auth::isAuthenticatedPage($pageCode)){
    Session::put('error', "You do not have permission to access '{$pageTitle}' page.");
    Utility::redirect(BASE_URL.'/login.php');
}

$sql = "SELECT 
          s.`id_lot_key` AS id
        , s.`amount`
        , s.`qty`
        , s.`created_at`
        , COUNT(IF(c.`status_id`=5, 1, NULL)) AS `available`
        , COUNT(IF(c.`status_id`=6, 1, NULL)) AS `used`
        FROM `scratch_card_lots` s
        INNER JOIN `scratch_cards` c ON c.`lot_id` = s.`id_lot_key`
        GROUP BY c.`lot_id`
        ORDER BY s.`id_lot_key` DESC";
$data = DB::getInstance()->query($sql)->results();

require BASE_DIRECTORY.'/views/layouts/admin-base.phtml';