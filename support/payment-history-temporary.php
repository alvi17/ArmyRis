<?php 

/**
 * Add Subscriber
 * 
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date December 05, 2016 01:31
 */

require "../core/config.php";
require "../core/init.php";

$pageCode       = 'support-payment-history-temporary';
$pageContent	= 'support/payment-history-temporary';
$pageTitle 		= "Subscriber' payment history search (temporary)";


if(!Auth::isAuthenticatedPage($pageCode)){
    Session::put('error', "You do not have permission to access '{$pageTitle}' page.");
    Utility::redirect(BASE_URL.'/login.php');
}


$ba_no = Input::get('ba_no');

$data = [];

if(!empty($ba_no)){
	$sql = "SELECT 
			  t.`userName`		AS ba_no
			, t.`billTime`		AS billed
			, t.`disconnectTime`	AS disconnect
			FROM `tmp_payments_nov_till` t
			LEFT JOIN subscribers s ON s.`username` = t.`userName`
			WHERE t.`userName` LIKE '%".$ba_no."%'
			GROUP BY t.`userName`
			LIMIT 30";
	$data = DB::getInstance()->query($sql, [])->results();
}


require BASE_DIRECTORY.'/views/layouts/admin-base.phtml';