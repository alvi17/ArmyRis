<?php

/**
 * Delete Inactive Subscribers
 * 
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date May 12, 2017 21:52
 */

require "../core/config.php";
require "../core/init.php";
require "../modules/acl/Roles.php";
require "../modules/subscriber/Subscriber.php";
require_once '../modules/mikrotik/PppoeApiService.php';

$pageCode       = 'operation-delete-inactive-subscribers';
$pageContent	= 'operation/delete-inactive-subscribers';
$pageTitle 		= 'Delete Inactive Subscribers';

if(!Auth::isAuthenticatedPage($pageCode)){
    Session::put('error', "You do not have permission to access '{$pageTitle}' page.");
    Utility::redirect(BASE_URL.'/login.php');
}

$days = (int) Input::get('days');
$router_no = (int) Input::get('rn');
if(empty($router_no)){$router_no=1;}
$page = (int)Input::get('page');
if(empty($page)){$page=1;}
$limit = LIMIT_PER_PAGE;

$data = [];

if($days>0){
	$cond = !empty($router_no) ? " AND b.`router_no` = {$router_no}" : "";
	$sql = "SELECT
			  s.`id_subscriber_key`
			, s.`username`
			, s.`firstname`
			, s.`lastname`
			, r.`name` AS `rank`
			, s.`official_mobile`
			, a.`area_name` AS `area`
			, b.`building_name` AS `building`
			, s.`house_no`
			, b.`router_no`
			, DATEDIFF(NOW(), s.`connection_to`) AS `inactive_days`
			FROM `subscribers` s
			LEFT JOIN `ranks` r ON r.`id` = s.`rank_id`
			LEFT JOIN `areas` a ON a.`id_area` = s.`area_id`
			LEFT JOIN `buildings` b ON b.`id_building` = s.`building_id`
			WHERE s.`status_id` = 0
		 	AND b.`router_no` = {$router_no}
			AND s.`connection_to`<= DATE_SUB(NOW(), INTERVAL {$days} DAY)
			ORDER BY s.`connection_to` ASC
			LIMIT ".($page-1)*$limit.", {$limit}";
	$data = DB::getInstance()->query($sql)->results();


	$sql = "SELECT COUNT(1) AS TOTAL
			FROM `subscribers` s
			LEFT JOIN `ranks` r ON r.`id` = s.`rank_id`
			LEFT JOIN `areas` a ON a.`id_area` = s.`area_id`
			LEFT JOIN `buildings` b ON b.`id_building` = s.`building_id`
			WHERE s.`status_id` = 0
		 	AND b.`router_no` = {$router_no}
			AND s.`connection_to`<= DATE_SUB(NOW(), INTERVAL {$days} DAY)";
	$tot_array = DB::getInstance()->query($sql)->results();

	$total = isset($tot_array[0]['TOTAL']) ? $tot_array[0]['TOTAL'] : 0;
	$url = BASE_URL."/operation/delete-inactive-subscribers.php?days={$days}&rn={$router_no}&page=";
	$paginationStr = Utility::pagination($total, $url, $limit, $page);
}



if(Input::exists()){
	$uname = isset($_POST['uname']) ? $_POST['uname'] : array();

	if(!empty($uname)){
		$now = date('Y-m-d H:i:s');
		global $mikrotik_routers;
		$sqlAuditBody = "";

		$router = $mikrotik_routers[$router_no];
		$mikrotik = new PppoeApiService($router['router_ip'], $router['username'], $router['password']);
		
		foreach($uname as $u){
			$sid = Subscriber::getSubscriberIdByUsername($u);
			
			$router_no = 0;
			foreach($data as $d){
				if($d['username'] == $u){
					$router_no = $d['router_no'];
					break;
				}
			}

	        # DELETE MIKROTIK USER (ENABLE THIS BLOCK IN LIVE SERVER)
	        $mikrotik->deleteUser($u);

	        $sqlAuditBody .= "({$sid}, 2, 'Subscriber Account Deleted', 0, '{$now}', '".Session::get('uid')."', '".Session::get('usertype')."'),";
		}

		$sqlAuditHdr = "INSERT INTO subscribers_connections_audit (subscriber_id, status_id, `comment`, `version`, `created_at`, created_by, `created_user_type`) VALUES ";
	    $sqlAudit = $sqlAuditHdr . rtrim($sqlAuditBody, ',');
	    DB::getInstance()->exec($sqlAudit);

	    $sqlSubs = "UPDATE subscribers
	    			SET status_id =2
					, updated_at = '{$now}'
					, updated_by = '".Session::get('uid')."'
					, updated_user_type = '".Session::get('usertype')."'
					WHERE username IN('".implode("','", $uname)."')";
		DB::getInstance()->exec($sqlSubs);

		Session::put('success', 'Subscribers deleted successfully.');
		Utility::redirect("delete-inactive-subscribers.php?days={$days}&rn={$router_no}");
	}	
}


require BASE_DIRECTORY.'/views/layouts/admin-base.phtml';