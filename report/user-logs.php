<?php 

//user-logs

/**
 * Show log activitis of system users
 * 
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date August 25, 2017 06:42
 */
 
 
require "../core/config.php";
require "../core/init.php";
require "../modules/acl/Roles.php";
require "../modules/user/User.php";
require "../modules/user/LogHistory.php";

$users = User::listAllUsers();
//Utility::dd($users);


$pageCode       = 'report-user-logs';
$pageContent	= 'report/user-logs';
$pageTitle 		= 'User Log Records';


$user = Input::get('user');
$fr = Input::get('fr');
$to = Input::get('to');
$log = (int)Input::get('log');
$page = (int)Input::get('page');

if(empty($log) || $log>6){$log=1;}
if(empty($page)){$page=1;}
if(!empty($fr)){
	$fr = date("Y-m-d", strtotime($fr));
}
if(!empty($to)){
	$to = date("Y-m-d", strtotime($to));
}


$check_logs = array(
	1 => 'Connectivity history',
	2 => 'Payment history',
	3 => 'Package history',
	4 => 'Category history',
	5 => 'Area history',
	6 => 'Login credentials history',
);

$audit_tables = array(
	1 => 'subscribers_connections_audit',
	2 => 'payments',
	3 => 'subscribers_packages_audit',
	4 => 'subscribers_categories_audit',
	5 => 'subscribers_areas_audit',
	6 => 'subscribers_login_credentials_audit',
);


switch($log){
	case 1:
		$log_histories 	= LogHistory::listConnectivityHistories($user, $fr, $to, false, $page, LIMIT_PER_PAGE);
		$tot_array 		= LogHistory::listConnectivityHistories($user, $fr, $to, true, $page);
		break;

	case 2:
		$log_histories 	= LogHistory::listPaymentHistories($user, $fr, $to, false, $page, LIMIT_PER_PAGE);
		$tot_array 		= LogHistory::listPaymentHistories($user, $fr, $to, true, $page);
		break;

	case 3:
		$log_histories 	= LogHistory::listPackageHistories($user, $fr, $to, false, $page, LIMIT_PER_PAGE);
		$tot_array 		= LogHistory::listPackageHistories($user, $fr, $to, true, $page);
		break;

	case 4:
		$log_histories 	= LogHistory::listCategoryHistories($user, $fr, $to, false, $page, LIMIT_PER_PAGE);
		$tot_array 		= LogHistory::listCategoryHistories($user, $fr, $to, true, $page);
		break;

	case 5:
		$log_histories 	= LogHistory::listAreaHistories($user, $fr, $to, false, $page, LIMIT_PER_PAGE);
		$tot_array 		= LogHistory::listAreaHistories($user, $fr, $to, true, $page);
		break;

	case 6:
		$log_histories 	= LogHistory::listLoginCredentialHistories($user, $fr, $to, false, $page, LIMIT_PER_PAGE);
		$tot_array 		= LogHistory::listLoginCredentialHistories($user, $fr, $to, true, $page);
		break;
}

$tot_log_histories = isset($tot_array[0]['TOTAL']) ? $tot_array[0]['TOTAL'] : 0;
$url = BASE_URL."/report/user-logs.php?user={$user}&fr={$fr}&to={$to}&log={$log}&page=";
$paginationStr = Utility::pagination($tot_log_histories, $url, LIMIT_PER_PAGE, $page);




require BASE_DIRECTORY.'/views/layouts/admin-base.phtml';