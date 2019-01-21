<?php

/**
 * Add Area
 * 
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date January 10, 2017 05:15
 */

require "../core/config.php";
require "../core/init.php";
require "../modules/acl/Roles.php";

$pageCode       = 'operation-area-update';
$pageContent	= 'operation/area-update';
$pageTitle 		= 'Update Area';

if(!Auth::isAuthenticatedPage($pageCode)){
    Session::put('error', "You do not have permission to access '{$pageTitle}' page.");
    Utility::redirect(BASE_URL.'/login.php');
}

$id = Input::get('id');
$sql = "SELECT `area_name` AS `name`, `status_id` AS `status`
        FROM `areas`
        WHERE `id_area` = ? AND status_id = 1";
$res = DB::getInstance()->query($sql, [$id])->first();
$name = isset($res['name']) ? $res['name'] : '';
$status = isset($res['status']) ? $res['status'] : '';



if(Input::exists()){
    $name = Input::post('name');
    
    $validate = new Validate();
    $validation = $validate->check($_POST, [
        'name' => [
            'label' => 'Name',
            'value' => $name,
            'rules' => ['required' => true, 'min' => 3, 'max' => 120, 'unique'=> "areas|area_name|id_area|{$id}"],
        ],
    ]);

    $errors = $validation->errors();
    if($validation->passed()) {
        $updData = [
            'area_name'     => $name,
            'status_id'     => 1,
            'updated_at'    => date('Y-m-d H:i:s'),
            'updated_by'    => Session::get('uid'),
        ];
        DB::getInstance()->update('areas', $updData, 'id_area', $id);
        
        Session::put('success', 'Area updated successfully.');
        Utility::redirect('area.php');
    }
}

require BASE_DIRECTORY.'/views/layouts/admin-base.phtml';