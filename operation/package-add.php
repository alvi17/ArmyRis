<?php

/**
 * Adds new Package
 * 
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date December 09, 2016 12:35
 */

require "../core/config.php";
require "../core/init.php";
require "../modules/acl/Roles.php";

$pageCode       = 'operation-package-add';
$pageContent	= 'operation/package-add';
$pageTitle 		= 'Add New Package';

if(!Auth::isAuthenticatedPage($pageCode)){
    Session::put('error', "You do not have permission to access '{$pageTitle}' page.");
    Utility::redirect(BASE_URL.'/login.php');
}

$code = Input::post('code');
$name = Input::post('name');
$mb_unit_value = Input::post('mb_unit_value');
$price = Input::post('price');
$days = Input::post('days');



if(Input::exists()){
    $subscriber_id  = Session::get('uid');
    $now            = date('Y-m-d H:i:s');
    
    $validate = new Validate();
    $validation = $validate->check($_POST, [
        'code' => [
            'label' => 'Code',
            'value' => $code,
            'rules' => ['required' => true, 'min' => 3, 'max' => 12, 'unique'=> 'packages|code'],
        ],
        'name' => [
            'label' => 'Name',
            'value' => $name,
            'rules' => ['required' => true, 'min' => 3, 'max' => 120],
        ],
        'price' => [
            'label' => 'Price',
            'value' => $price,
            'rules' => ['required' => true, 'digit' => true],
        ],
        'days' => [
            'label' => 'Days',
            'value' => $days,
            'rules' => ['required' => true, 'digit' => true],
        ],
    ]);

    $errors = $validation->errors();
    
    if($validation->passed()) {
        $insertData = [
            'code'          => $code, 
            'name'          => $name, 
            'mb_unit_value' => $mb_unit_value, 
            'price'         => $price, 
            'days'          => $days,
            'status_id'     => 1,
            'created_at'    => $now, 
            'created_by'    => $subscriber_id,
        ];
        $payment_id = DB::getInstance()->insert('packages', $insertData, true);
        
        Session::put('success', ' Package added successfully.');
        Utility::redirect('package-index.php');
    }
}

require BASE_DIRECTORY.'/views/layouts/admin-base.phtml';