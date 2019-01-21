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

$pageCode       = 'operation-area-add';
$pageContent	= 'operation/area-add';
$pageTitle 		= 'Add Area';

if(!Auth::isAuthenticatedPage($pageCode)){
    Session::put('error', "You do not have permission to access '{$pageTitle}' page.");
    Utility::redirect(BASE_URL.'/login.php');
}

$name = Input::post('name');

if(Input::exists()){
    $validate = new Validate();
    $validation = $validate->check($_POST, [
        'name' => [
            'label' => 'Name',
            'value' => $name,
            'rules' => ['required' => true, 'min' => 3, 'max' => 120, 'unique'=> 'areas|area_name'],
        ],
    ]);

    $errors = $validation->errors();
    if($validation->passed()) {
        $insertData = [
            'area_name'     => $name,
            'status_id'     => 1,
            'created_at'    => date('Y-m-d H:i:s'), 
            'created_by'    => Session::get('uid'),
        ];
        $payment_id = DB::getInstance()->insert('areas', $insertData, true);
        
        Session::put('success', 'Area added successfully.');
        Utility::redirect('area.php');
    }
}

require BASE_DIRECTORY.'/views/layouts/admin-base.phtml';