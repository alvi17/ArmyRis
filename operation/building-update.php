<?php

/**
 * Edit Area, Building and Router
 * 
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date December 29, 2016 00:39
 */

require "../core/config.php";
require "../core/init.php";
require "../modules/acl/Roles.php";

$pageCode       = 'operation-building-update';
$pageContent	= 'operation/building-update';
$pageTitle 		= 'Update Building';

if(!Auth::isAuthenticatedPage($pageCode)){
    Session::put('error', "You do not have permission to access '{$pageTitle}' page.");
    Utility::redirect(BASE_URL.'/login.php');
}


$id = Input::get('id');
$areas = Utility::listServerAreas();
//extract(Utility::getBuildingnInfoByBuildingId($building_id), EXTR_OVERWRITE);

$sql = "SELECT 
    b.`area_id`, b.`building_name`, b.`router_no`, b.`local_ip`
  , b.`ip_block`, b.`remote_ip_first`, b.`remote_ip_last`
FROM `buildings` b
WHERE b.`id_building` = ?";
$result = DB::getInstance()->query($sql, [$id]);
if($result->count()){
    $data = $result->first();
    $area = $data['area_id'];
    $building = $data['building_name'];
    $router = $data['router_no'];
    $local_ip = $data['local_ip'];
    $ip_block = $data['ip_block'];
    $remote_ip_first = $data['remote_ip_first'];
    $remote_ip_last = $data['remote_ip_last'];
}


if(Input::exists()){
    $area = Input::post('area');
    $building = Input::post('building');
    $router = Input::post('router');
    $local_ip = Input::post('local_ip');
    $ip_block = Input::post('ip_block');
    $remote_ip_first = Input::post('remote_ip_first');
    $remote_ip_last = Input::post('remote_ip_last');
    
    $validate = new Validate();
    
    $validation = $validate->check($_POST, [
        'area' => [
            'label' => 'Area',
            'value' => $area,
            'rules' => ['required' => true],
        ],
        'building' => [
            'label' => 'Building name',
            'value' => $building,
            'rules' => ['required' => true, 'min' => 3, 'max' => 26, 'unique'=> "buildings|building_name|id_building|{$id}"],
        ],
        'router_no' => [
            'label' => 'Router',
            'value' => $router,
            'rules' => ['required' => true],
        ],
    ]);
            
    $errors = $validation->errors();
    if(empty($errors)){
        $fields = [
            'area_id'       => $area,
            'building_name' => $building,
            'router_no'     => $router,
            'updated_at'    => date('Y-m-d H:i:s'),
            'updated_by'    => Session::get('uid'),
        ];
        DB::getInstance()->update('buildings', $fields, 'id_building', $id);
        Session::put('success', 'Building updated successfully.');
        Utility::redirect('building.php');
    }
}

require BASE_DIRECTORY.'/views/layouts/admin-base.phtml';