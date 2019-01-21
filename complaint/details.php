<?php

/* 
 * Complaint Detials Page. This page is also used to update complaint status and other informations.
 * 
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date January 12, 2017 03:42 am
 */

require "../core/config.php";
require "../core/init.php";
require "../modules/acl/Roles.php";
require "../modules/complaint/Complaint.php";
require "../modules/date_time_dropdown.php";

$pageCode       = 'complaint-details';
$pageContent	= 'complaint/details';
$pageTitle 		= 'Complaint in Details';

if(!Auth::isAuthenticatedPage($pageCode)){
    Session::put('error', "You do not have permission to access '{$pageTitle}' page.");
    Utility::redirect(BASE_URL.'/login.php');
}


$problem_types = Complaint::listProblemTypes();
$support_types = Complaint::listSupportReasons();
//$support_types = Complaint::listProblemTypes();

$id = (int) Input::get('id');

$sql = "SELECT
        s.`username`
      , c.`subscriber_id`
      , s.`firstname`
      , s.`lastname`
      , r.`name` AS `rank`
      , s.`official_mobile`
      , s.`house_no`
      , b.`building_name` AS `building`
      , a.`area_name` AS `area`
      , c.`id_status`
      , c.`pb_type`
      , c.`pb_details`
      , c.`support_reason`
      , c.`support_details`
      , c.`pb_since`
      , c.`dtt_add`
      , c.`dtt_mod`
      , c.`version`
      FROM `complains` c
      LEFT JOIN `subscribers` s ON s.`id_subscriber_key` = c.`subscriber_id`
      LEFT JOIN ranks r ON r.`id` = s.`rank_id`
      LEFT JOIN buildings b ON b.`id_building` = s.`building_id`
      LEFT JOIN areas a ON a.`id_area` = s.`area_id`
      WHERE c.`id` = ?";
$result = DB::getInstance()->query($sql, [$id]);
if($result->count()){
    $result             = $result->first();
    $username           = $result['username'];
    $pb_type            = $result['pb_type'];
    $pb_details         = $result['pb_details'];
    $support_type       = $result['support_reason'];
    $support_details    = $result['support_details'];
    $status             = $result['id_status'];
    $version            = $result['version'];
    $subscriber_id      = $result['subscriber_id'];
    $pb_since           = $result['pb_since'];
}

$old_status = $status;
    
if(Input::exists()){
    $now = date('Y-m-d H:i:s');
    $uid = Session::get('uid');
    $utype = Session::get('usertype');
    
    $status = Input::post('status');
    $pb_type = Input::post('pb_type');
    $pb_details = Input::post('pb_details');
    $support_type = Input::post('support_type');
    $support_details = Input::post('support_details');
    $status = Input::post('status');
    
    if(empty($status)){$status = 1;}
        
    $upd_data = [
        'id_status'         => $status,
        'pb_type'           => $pb_type,
        'pb_details'        => $pb_details,
        'support_reason'    => $support_type,
        'support_details'   => $support_details,
        'uid_mod'           => $uid,
        //'dtt_mod'           => $now,
        'mod_user_type'     => $utype,
        'version'           => $version+1,
    ];
	if(!in_array($old_status, [5,6])){
		$upd_data['dtt_mod'] = $now;
	}
	
    DB::getInstance()->update('complains', $upd_data, 'id', $id);

    $insert_data = [
        'complain_id'       => $id,
        'subscriber_id'     => $subscriber_id,
        'id_status'         => $status,
        'pb_since'          => $pb_since,
        'pb_type'           => $pb_type,
        'pb_details'        => $pb_details,
        'support_reason'    => $support_type,
        'support_details'   => $support_details,
        'uid_mod'           => $uid,
        'dtt_mod'           => $now,
        'mod_user_type'     => $utype,
        'version'           => $version+1,
    ];
    DB::getInstance()->insert('complains_audit', $insert_data);

    Session::put('success', 'Complaint Updated Successfully.');
    Utility::redirect('general-report.php');

}




require BASE_DIRECTORY.'/views/layouts/admin-base.phtml';