<?php

/**
 * Lists Bank Deposit
 * 
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date Mar 06, 2017 08:32
 */

require "../core/config.php";
require "../core/init.php";
require "../modules/acl/Roles.php";


$pageCode       = 'report-bank-deposit-list';
$pageContent	= 'report/bank-deposit-list';
$pageTitle 		= 'Bank Deposits';

if(!Auth::isAuthenticatedPage($pageCode)){
    Session::put('error', "You do not have permission to access '{$pageTitle}' page.");
    Utility::redirect(BASE_URL.'/login.php');
}



$sql = "SELECT
            b.`id`
          , b.`date`
          , b.`amount`
          , u.`firstname`
          , u.`lastname`
          , b.`purpose`
          FROM bank_deposits b
          LEFT JOIN users u ON u.`id`=b.`submit_by`
          ORDER BY b.`date` ASC";
$data = DB::getInstance()->query($sql)->results();

require BASE_DIRECTORY.'/views/layouts/admin-base.phtml';