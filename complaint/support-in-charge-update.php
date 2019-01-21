<?php

/* 
 * Update Support in Charge
 * 
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date January 12, 2017 03:42 am
 */

require "../core/config.php";
require "../core/init.php";
require "../modules/acl/Roles.php";
require "../modules/complaint/Complaint.php";
require "../modules/date_time_dropdown.php";

$pageCode       = 'complaint-support-in-charge-update';
$pageContent	= 'complaint/support-in-charge-update';
$pageTitle 		= 'Update Support in Charge';

if(!Auth::isAuthenticatedPage($pageCode)){
    Session::put('error', "You do not have permission to access '{$pageTitle}' page.");
    Utility::redirect(BASE_URL.'/login.php');
}

$id = (int) Input::get('id');



if(Input::exists()){
    $uid = Input::post('uid');
    
    $validate = new Validate();
    $validation = $validate->check($_POST, [
        'uid' => [
            'label' => 'Assigned Person',
            'value' => $uid,
            'rules' => ['required' => true],
        ],
    ]);
    
    $errors = $validation->errors();
    if($validation->passed()) {
        $updData = [
            'support_in_charge_id'  => $uid,
            'updated_at'            => date('Y-m-d H:i:s'),
            'updated_by'            => Session::get('uid'),
        ];
        DB::getInstance()->update('areas', $updData, 'id_area', $id);
        
        Session::put('success', 'Data updated successfully.');
        Utility::redirect('support-in-charge.php');
    }
}


$sql = "SELECT 
          a.`area_name` as `area`
        , a.`support_in_charge_id` AS `uid`
        FROM `areas` a
        WHERE a.`id_area` = ?
        AND a.`status_id` = 1";
$result = DB::getInstance()->query($sql, [$id]);
$data = $result->count() ? $result->first() : ['area'=>'', 'uid'=>''];

$supports_in_charge = Complaint::listSupportsInCharge();


require BASE_DIRECTORY.'/views/layouts/admin-base.phtml';