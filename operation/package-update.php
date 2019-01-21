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

$pageCode       = 'operation-package-update';
$pageContent	= 'operation/package-update';
$pageTitle 		= 'Update Package';

if(!Auth::isAuthenticatedPage($pageCode)){
    Session::put('error', "You do not have permission to access '{$pageTitle}' page.");
    Utility::redirect(BASE_URL.'/login.php');
}


$id = Input::get('id');

$sql = "SELECT `code`, `name`, `mb_unit_value`, `price`, `days`
        FROM `packages`
        WHERE `status_id` = 1 AND `id` = ?";
$package = DB::getInstance()->query($sql, [$id])->first();
extract($package);




if(Input::exists()){
    
    $code = Input::post('code');
    $name = Input::post('name');
    $mb_unit_value = Input::post('mb_unit_value');
    $price = Input::post('price');
    $days = Input::post('days');

    $subscriber_id  = Session::get('uid');
    $now = date('Y-m-d H:i:s');
    
    $validate = new Validate();
    $validation = $validate->check($_POST, [
        'code' => [
            'label' => 'Code',
            'value' => $code,
            'rules' => ['required' => true, 'min' => 3, 'max' => 12, 'unique'=> "packages|code|id|{$id}"],
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
        $updData = [
            'code'          => $code,
            'name'          => $name,
            'mb_unit_value' => $mb_unit_value,
            'price'         => $price,
            'days'          => $days,
            //'status_id'     => 1,
            'updated_at'    => $now,
            'updated_by'    => $subscriber_id,
        ];
        DB::getInstance()->update('packages', $updData, 'id', $id);
        
        Session::put('success', 'Package updated successfully.');
        Utility::redirect('package-index.php');
    }
}

//Utility::pr($packages); exit;

require BASE_DIRECTORY.'/views/layouts/admin-base.phtml';