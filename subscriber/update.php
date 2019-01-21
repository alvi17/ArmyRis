<?php

/**
 * Update Subscriber
 * 
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date December 10, 2016 01:31
 */

require "../core/config.php";
require "../core/init.php";
require "../modules/subscriber/Subscriber.php";
require_once '../modules/mikrotik/PppoeApiService.php';


$pageCode       = 'subscriber-update';
$pageContent	= 'subscriber/update';
$pageTitle 		= 'Update Subscriber';

if(!Auth::isAuthenticatedPage($pageCode)){
    Session::put('error', "You do not have permission to access '{$pageTitle}' page.");
    Utility::redirect(BASE_URL.'/login.php');
}

$subscriber = new Subscriber();

$ranks = Utility::listRanks();
$areas = Utility::listServerAreas();
$packages = Utility::listPackages();


$id = (int) Input::get('id');
$old_data = $data = $subscriber->getSubscriberDetials($id);
//Utility::pa($data); exit;

if(empty($data)){
  Session::put('error', "Subscriber information not found.");
  Utility::redirect('index.php');
}

$data['connection_from_split'] = Date::splitParameters12HrFormat($data['connection_from']);
$data['connection_to_split'] = Date::splitParameters12HrFormat($data['connection_to']);

$data['connection_from_split'] = Date::adjustTime($data['connection_from_split'], $connect_begin_time);
$data['connection_to_split'] = Date::adjustTime($data['connection_to_split'], $connect_end_time);

$buildings = Utility::listBuildingsByAreaId($data['area_id']);
$remote_ips = Utility::listRemoteIpsByBuildingId($data['building_id'], $data['remote_ip']);

if(Input::exists()){
    $data['username'] = Input::post('username');
    $data['password'] = Input::post('password');
    $data['ba_no'] = Input::post('ba_no');
    $data['firstname'] = Input::post('firstname');
    $data['lastname'] = Input::post('lastname');
    $data['rank_id'] = (int) Input::post('rank');
    $data['official_mobile'] = Input::post('official_mobile');
    $data['personal_mobile'] = Input::post('personal_mobile');
    $data['residential_phone'] = Input::post('residential_phone');
    $data['email'] = Input::post('email');
    $data['area'] = Input::post('area');
    $data['house_no'] = Input::post('house_no');
    $data['road_no'] = Input::post('road_no');
    $data['package'] = Input::post('package');
    $data['category'] = Input::post('category');
    $data['complementary_amount'] = Input::post('complementary_amount');
    $data['building_id'] = Input::post('building');
    $tmp = Utility::getRouterNoLocalIpByBuildingId($data['building_id']);
    $data['router_no'] = $tmp['router_no'];
    $data['local_ip'] = $tmp['local_ip'];
    $data['remote_ip'] = Input::post('remote_ip');
    $data['status_id'] = Input::post('status_id');
    
    if(isset($_POST['connection_from_split'])){
        $data['connection_from_split'] = $_POST['connection_from_split'];  // filter_input(INPUT_POST, 'connection_from_split');
    }
    if(isset($_POST['connection_to_split'])){
        $data['connection_to_split'] = $_POST['connection_to_split'];  // filter_input(INPUT_POST, 'connection_to_split');
    }
    
    $data['connection_from_split'] = Date::adjustTime($data['connection_from_split'], $connect_begin_time);
    $data['connection_to_split'] = Date::adjustTime($data['connection_to_split'], $connect_end_time);
    
    $data['connection_from'] = Date::convertDate24HrFormatFromArray($data['connection_from_split']);
    $data['connection_to'] = Date::convertDate24HrFormatFromArray($data['connection_to_split']);
    
    $data['payment_balance'] = Input::post('payment_balance');
    $data['remarks'] = Input::post('remarks');
    
    $validate = new Validate();
	
		$validation = $validate->check($_POST, [
            'username' => [
                'label' => 'Username',
                'value' => $data['username'],
                'rules' => ['required' => true, 'min' => 3, 'max' => 12, 'unique'=> "subscribers|username|id_subscriber_key|{$id}"],
            ],
            'password' => [
                'label' => 'Password',
                'value' => $data['password'],
                'rules' => ['required' => true, 'min' => 3, 'max' => 20],  
            ],
            'ba_no' => [
                'label' => 'BA No',
                'value' => $data['ba_no'],
                'rules' => ['required' => true, 'min' => 3, 'max' => 10, 'unique'=> "subscribers|ba_no|id_subscriber_key|{$id}"],  
            ],
            'firstname' => [
                'label' => 'Firstname',
                'value' => $data['firstname'],
                'rules' => ['required' => true, 'min' => 3, 'max' => 20, 'no_digit' => true],  
            ],
            'rank' => [
                'label' => 'Rank',
                'value' => $data['rank_id'],
                'rules' => ['required' => true, 'digit' => true],
            ],
            'area' => [
                'label' => 'Area',
                'value' => $data['area'],
                'rules' => ['required' => true, 'digit' => true],
            ],
            'building' => [
                'label' => 'Building',
                'value' => $data['building_id'],
                'rules' => ['required' => true, 'digit' => true],
            ],
            'house_no' => [
                'label' => 'House no',
                'value' => $data['house_no'],
                'rules' => ['required' => true, 'min' => 1, 'max' => 10],
            ],
            'remote_ip' => [
                'label' => 'Remote IP',
                'value' => $data['remote_ip'],
                'rules' => ['required' => true, 'unique' => "subscribers|remote_ip|id_subscriber_key|{$id}"],
            ],
            'official_mobile' => [
                'label' => 'Official Mobile',
                'value' => $data['official_mobile'],
                //'rules' => ['required' => true, 'digit' => true, 'exact' => 11],
                'rules' => ['digit' => true, 'exact'=> 11],
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
            'package' => [
                'label' => 'Connection Package',
                'value' => $data['package'],
                'rules' => ['required' => true, 'digit' => true],
            ],
            'category' => [
                'label' => 'Subscriber Category',
                'value' => $data['category'],
                'rules' => ['required' => true],
            ],
            'complementary_amount' => [
                'label' => 'Complementary Amount',
                'value' => $data['complementary_amount'],
                'rules' => ['digit' => true],
            ],
        ]);

        $errors = $validation->errors();
        
        if($data['category']=='Complementary' && empty($data['complementary_amount'])){
            $errors['complementary_amount'] = 'Complementary Amount is required.';
        }
		if(in_array(1, Session::get('user_roles'))){
			if($data['status_id'] == 1 && $data['category'] != 'Free'){
				if(empty($data['connection_from'])){
					$errors['connection_from'] = 'Connection from is required.';
				}
				if(empty($data['connection_to'])){
					$errors['connection_to'] = 'Connection to is required.';
				}
			}
		}	
        
        if(empty($errors)){
            $sc = new Subscriber();

            $upd_data = [
                'username'              => $data['username'],
                'password'              => $data['password'],
                'ba_no'                 => $data['ba_no'],
                'firstname'             => $data['firstname'],
                'lastname'              => $data['lastname'],
                'rank_id'               => $data['rank_id'],
                'area_id'               => $data['area'],
                'building_id'           => $data['building_id'],
                'house_no'              => $data['house_no'],
                'router_no'             => $data['router_no'],
                //'router_no_old'         => $old_data['router_no'],
                'local_ip'              => $data['local_ip'],
                'remote_ip'             => $data['remote_ip'],
                'official_mobile'       => $data['official_mobile'],
                'personal_mobile'       => $data['personal_mobile'],
                'residential_phone'     => $data['residential_phone'],
                'email'                 => $data['email'],
                'package_id'            => $data['package'],
                'package_code'          => isset($packages[$data['package']]['code']) 
                                            ? $packages[$data['package']]['code'] : DEFAULT_PACKAGE,
                'category'              => $data['category'],
                'complementary_amount'  => $data['complementary_amount'],
                //'payment_balance'       => $data['payment_balance'],
                //'connection_from'       => $data['connection_from'],
                //'connection_to'         => $data['connection_to'],
                'status_id'             => $data['status_id'],
                'remarks'               => $data['remarks'],

                'now'                   => date('Y-m-d H:i:s'),
                'uid'                   => Session::get('uid'),
                'utype'                 => Session::get('usertype'),
            ];

            if(in_array(1, Session::get('user_roles'))){
                $upd_data['payment_balance'] = $data['payment_balance'];
                $upd_data['connection_from'] = $data['connection_from'];
                $upd_data['connection_to'] = $data['connection_to'];
            }

            $updated = $sc->edit($id, $upd_data, $old_data);
            
            if(!$updated['error']){
                Session::put('success', 'Subscriber updated successfully.');
                Utility::redirect('index.php');
            } else{
                $errors['message'] = $updated['error'];
            }
        }
        
}

$moreJs = ['js/subscriber-add-upd.js'];
require BASE_DIRECTORY.'/views/layouts/admin-base.phtml';