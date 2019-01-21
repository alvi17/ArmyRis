<?php

/**
 * Add Corporate Subscriber
 * 
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date May 16, 2017 04:31
 */


require "../core/config.php";
require "../core/init.php";
require "../modules/subscriber/Subscriber.php";
require_once '../modules/mikrotik/PppoeApiService.php';


$pageCode       = 'corporate-subscriber-add';
$pageContent	= 'corporate-subscriber/add';
$pageTitle 		= 'Add Corporate Subscriber';

if(!Auth::isAuthenticatedPage($pageCode)){
    Session::put('error', "You do not have permission to access '{$pageTitle}' page.");
    Utility::redirect(BASE_URL.'/login.php');
}


$packages = Utility::listPackages();

$firstname = Input::post('firstname');
$address = Input::post('address');
$official_mobile = Input::post('official_mobile');
$personal_mobile = Input::post('personal_mobile');
$residential_phone = Input::post('residential_phone');
$email = Input::post('email');
$package = Input::post('package');
$price = Input::post('price');
$remarks = Input::post('remarks');


if(Input::exists()){
	$validate = new Validate();
	$validation = $validate->check($_POST, [
		    'firstname' => [
            'label' => 'Corporate Name',
            'value' => $firstname,
            'rules' => ['required' => true, 'min' => 3, 'max' => 90, 'no_digit' => true, 'unique'=> 'subscribers|firstname'],  
        ],
        'address' => [
            'label' => 'Address',
            'value' => $address,
            'rules' => ['required' => true, 'min' => 3, 'max' => 80,],
        ],
        'official_mobile' => [
            'label' => 'Official Mobile',
            'value' => $official_mobile,
            'rules' => ['required' => true, 'digit' => true, 'exact'=> 11],
        ],
        'personal_mobile' => [
            'label' => 'Personal Mobile',
            'value' => $personal_mobile,
            'rules' => ['digit' => true, 'exact'=> 11],
        ],
        'residential_phone' => [
            'label' => 'Residential Phone',
            'value' => $residential_phone,
            'rules' => ['digit' => true],
        ],
        'email' => [
            'label' => 'Email',
            'value' => $email,
            'rules' => ['email' => true],
        ],
        'package' => [
            'label' => 'Connection Package',
            'value' => $package,
            'rules' => ['required' => true, 'min' => 3, 'max' => 20,],
        ],
        'price' => [
            'label' => 'Connection Price',
            'value' => $price,
            'rules' => ['required' => true, 'digit' => true,],
        ],
    ]);

	$errors = $validation->errors();


  if(empty($errors)){
    $now = date('Y-m-d H:i:s');
    $uid = Session::get('uid');

    $subs_data = [
      'firstname' => $firstname,
      'address' => $address,
      'official_mobile' => $official_mobile,
      'personal_mobile' => $personal_mobile,
      'residential_phone' => $residential_phone,
      'email' => $email,
      'corporate_package' => $package,
      'corporate_package_price' => $price,
      'package_version' => 1,
      'status_id' => 1,
      'status_version' => 1,
      'subs_type' => 'corporate',
      'category' => 'Paid',
      'created_at' => $now,
      'created_by' => $uid,
      'username' => '',
      'password' => '',
      'login_credential_version' => 0,
      'ba_no' => '',
      'rank_id' => 0,
      'remarks' => $remarks,
      'payment_balance' => 0,
      'payment_version' => 0,
      'connection_from' => NULL,
      'connection_to' => NULL,
      'connection_version' => 0,
    ];
    $subs_id = DB::getInstance()->insert('subscribers', $subs_data, true);

    Session::put('success', 'Corporate subscriber added successfully.');
    Utility::redirect('list.php');
  }
}

require BASE_DIRECTORY.'/views/layouts/admin-base.phtml';