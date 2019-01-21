<?php

/**
 * Add Subscriber
 * 
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date December 05, 2016 01:31
 */

require "../core/config.php";
require "../core/init.php";
require "../modules/subscriber/Subscriber.php";
require_once '../modules/mikrotik/PppoeApiService.php';


$pageCode       = 'subscriber-add';
$pageContent	= 'subscriber/add';
$pageTitle 		= 'Add Subscriber';

if(!Auth::isAuthenticatedPage($pageCode)){
    Session::put('error', "You do not have permission to access '{$pageTitle}' page.");
    Utility::redirect(BASE_URL.'/login.php');
}


$ranks = Utility::listRanks();
$areas = Utility::listServerAreas();
$packages = Utility::listPackages();

$username = Input::post('username');
$password = Input::post('password');
$ba_no = Input::post('ba_no');
$firstname = Input::post('firstname');
$lastname = Input::post('lastname');
$rank = (int) Input::post('rank');
$official_mobile = Input::post('official_mobile');
$personal_mobile = Input::post('personal_mobile');
$residential_phone = Input::post('residential_phone');
$email = Input::post('email');
$area = Input::post('area');
$house_no = Input::post('house_no');
$road_no = Input::post('road_no');
$package = Input::post('package');
$category = Input::post('category');
$complementary_amount = Input::post('complementary_amount');
$remarks = Input::post('remarks');

$buildings = Utility::listBuildingsByAreaId($area);
$building = Input::post('building');

$remote_ips = Utility::listRemoteIpsByBuildingId($building);
$remote_ip = Input::post('remote_ip');

if(Input::exists()){
    //$tmp = Utility::getRouterNoLocalIpByBuildingId($building);
    $router_no = ''; $local_ip='';
    extract(Utility::getRouterNoLocalIpByBuildingId($building));
    
    $validate = new Validate();
        $validation = $validate->check($_POST, [
            'username' => [
                'label' => 'Username',
                'value' => $username,
                'rules' => ['required' => true, 'min' => 3, 'max' => 12, 'unique'=> 'subscribers|username'],
            ],
            'password' => [
                'label' => 'Password',
                'value' => $password,
                'rules' => ['required' => true, 'min' => 3, 'max' => 20],  
            ],
            'ba_no' => [
                'label' => 'BA No',
                'value' => $ba_no,
                'rules' => ['required' => true, 'min' => 3, 'max' => 10, 'unique'=> 'subscribers|ba_no'],  
            ],
            'firstname' => [
                'label' => 'Firstname',
                'value' => $firstname,
                'rules' => ['required' => true, 'min' => 3, 'max' => 20, 'no_digit' => true],  
            ],
            'rank' => [
                'label' => 'Rank',
                'value' => $rank,
                'rules' => ['required' => true, 'digit' => true],
            ],
            'area' => [
                'label' => 'Area',
                'value' => $area,
                'rules' => ['required' => true, 'digit' => true],
            ],
            'building' => [
                'label' => 'Building',
                'value' => $building,
                'rules' => ['required' => true, 'digit' => true],
            ],
            'house_no' => [
                'label' => 'House no',
                'value' => $house_no,
                'rules' => ['required' => true, 'min' => 1, 'max' => 10],
            ],
            'remote_ip' => [
                'label' => 'Remote IP',
                'value' => $remote_ip,
                'rules' => ['required' => true, 'unique' => 'subscribers|remote_ip'],
            ],
            'official_mobile' => [
                'label' => 'Official Mobile',
                'value' => $official_mobile,
                //'rules' => ['required' => true, 'digit' => true, 'exact'=> 11],
                'rules' => ['digit' => true, 'exact'=> 11],
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
                'rules' => ['required' => true, 'digit' => true],
            ],
            'category' => [
                'label' => 'Subscriber Category',
                'value' => $category,
                'rules' => ['required' => true],
            ],
            'complementary_amount' => [
                'label' => 'Complementary Amount',
                'value' => $complementary_amount,
                'rules' => ['digit' => true],
            ],
        ]);

        $errors = $validation->errors();
        if($category=='Complementary' && empty($complementary_amount)){
            $errors['complementary_amount'] = 'Complementary Amount is required.';
        }
        
        //if($validation->passed()) {
        if(empty($errors)){
            $sc = new Subscriber();
            $created = $sc->create([
                'username'              => $username,
                'password'              => $password,
                'ba_no'                 => $ba_no,
                'firstname'             => $firstname,
                'lastname'              => $lastname,
                'rank_id'               => $rank,
                'area_id'               => $area,
                'building_id'           => $building,
                'house_no'              => $house_no,
                'official_mobile'       => $official_mobile,
                'personal_mobile'       => $personal_mobile,
                'residential_phone'     => $residential_phone,
                'email'                 => $email,
                'package_id'            => $package,
                'package_code'          => isset($packages[$package]['code']) 
                                            ? $packages[$package]['code'] : DEFAULT_PACKAGE,
                'category'              => $category,
                'complementary_amount'  => $complementary_amount,
                'created_at'            => date('Y-m-d H:i:s'),
                'created_by'            => Session::get('uid'),
                'router_no'             => $router_no,
                'local_ip'              => $local_ip,
                'remote_ip'             => $remote_ip,
                'status_id'             => 0,
                'remarks'               => $remarks,
            ]);
            
            if(!$created['error']){
                Session::put('success', 'Subscriber added successfully.');
                Utility::redirect('index.php');
            } else{
                $errors['message'] = $created['error'];
            }
        }
        
}

$moreJs = ['js/subscriber-add-upd.js'];
require BASE_DIRECTORY.'/views/layouts/admin-base.phtml';