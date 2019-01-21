<?php

/**
 * Edit Corporate Subscriber
 * 
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date May 16, 2017 04:31
 */

require "../core/config.php";
require "../core/init.php";
require "../modules/subscriber/Subscriber.php";
require_once '../modules/mikrotik/PppoeApiService.php';


$pageCode       = 'corporate-subscriber-edit';
$pageContent	= 'corporate-subscriber/edit';
$pageTitle 		= 'Edit Corporate Subscriber';

if(!Auth::isAuthenticatedPage($pageCode)){
    Session::put('error', "You do not have permission to access '{$pageTitle}' page.");
    Utility::redirect(BASE_URL.'/login.php');
}

$packages = Utility::listPackages();


$id = (int) Input::get('id');
$sql = "SELECT
		  s.`firstname`
        , s.`address`
        , s.`official_mobile`
        , s.`personal_mobile`
        , s.`residential_phone`
        , s.`email`
        , s.`status_id`
        , s.`corporate_package`
        , s.`corporate_package_price`
        , s.`remarks`
		FROM subscribers s
		WHERE s.`id_subscriber_key` = ?
		AND s.`subs_type` = 'corporate'";
$data = DB::getInstance()->query($sql, [$id])->first();

if(empty($data)){
  Session::put('error', "Corporate Subscriber information not found.");
  Utility::redirect('corporate-list.php');
}

if(Input::exists()){
	$data['firstname'] = Input::post('firstname');
    $data['address'] = Input::post('address');
	$data['official_mobile'] = Input::post('official_mobile');
	$data['personal_mobile'] = Input::post('personal_mobile');
	$data['residential_phone'] = Input::post('residential_phone');
	$data['email'] = Input::post('email');
	$data['status_id'] = Input::post('status_id');
	$data['corporate_package'] = Input::post('corporate_package');
    $data['corporate_package_price'] = Input::post('corporate_package_price');
    $data['remarks'] = Input::post('remarks');

	$validate = new Validate();
	$validation = $validate->check($_POST, [
		'firstname' => [
            'label' => 'Corporate Name',
            'value' => $data['firstname'],
            'rules' => ['required' => true, 'min' => 3, 'max' => 90, 'no_digit' => true, 
                        'unique'=> "subscribers|firstname|id_subscriber_key|{$id}"],  
        ],
        'address' => [
            'label' => 'Address',
            'value' => $data['address'],
            'rules' => ['required' => true, 'min' => 3, 'max' => 80,],
        ],
        'official_mobile' => [
            'label' => 'Official Mobile',
            'value' => $data['official_mobile'],
            'rules' => ['required' => true, 'digit' => true, 'exact'=> 11],
        ],
        'personal_mobile' => [
            'label' => 'Personal Mobile',
            'value' => $data['personal_mobile'],
            'rules' => ['digit' => true, 'exact'=> 11],
        ],
        'residential_phone' => [
            'label' => 'Residential Phone',
            'value' => $data['residential_phone'],
            'rules' => ['digit' => true],
        ],
        'email' => [
            'label' => 'Email',
            'value' => $data['email'],
            'rules' => ['email' => true],
        ],
        'corporate_package' => [
            'label' => 'Connection Package',
            'value' => $data['corporate_package'],
            'rules' => ['required' => true, 'min' => 3, 'max' => 20,],
        ],
        'corporate_package_price' => [
            'label' => 'Connection Price',
            'value' => $data['corporate_package_price'],
            'rules' => ['required' => true, 'digit' => true,],
        ],
        'status_id' => [
            'label' => 'Status',
            'value' => $data['status_id'],
            'rules' => ['required' => true,],
        ],
    ]);

	$errors = $validation->errors();

	if(empty($errors)){
		$data['updated_at'] = date('Y-m-d H:i:s');
		$data['updated_by'] = Session::get('uid');
		$data['updated_user_type'] = 'system';

		DB::getInstance()->update('subscribers', $data, 'id_subscriber_key', $id);
        
        Session::put('success', 'Corporate Subscriber Information updated successfully.');
        Utility::redirect('list.php');
	}
}



require BASE_DIRECTORY.'/views/layouts/admin-base.phtml';