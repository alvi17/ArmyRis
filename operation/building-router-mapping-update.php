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

$pageCode       = 'operation-building-router-mapping-update';
$pageContent	= 'operation/building-router-mapping-update';
$pageTitle 		= 'Update Area, Building and Router';

if(!Auth::isAuthenticatedPage($pageCode)){
    Session::put('error', "You do not have permission to access '{$pageTitle}' page.");
    Utility::redirect(BASE_URL.'/login.php');
}


$building_id = Input::get('id');
$areas = Utility::listServerAreas();
extract(Utility::getBuildingnInfoByBuildingId($building_id), EXTR_OVERWRITE);


if(Input::exists()){
    $area_id = Input::post('area');
    $building_name = Input::post('building');
    $router_no = Input::post('router_no');
    
    $validate = new Validate();
    
    $validation = $validate->check($_POST, [
        'area' => [
            'label' => 'Area',
            'value' => $area_id,
            'rules' => ['required' => true],
        ],
        'building' => [
            'label' => 'Building name',
            'value' => $building_name,
            'rules' => ['required' => true, 'min' => 3, 'max' => 26, 'unique'=> "buildings|building_name|id_building|{$building_id}"],
        ],
        'router_no' => [
            'label' => 'Router',
            'value' => $router_no,
            'rules' => ['required' => true],
        ],
    ]);
            
    $errors = $validation->errors();
    
    if(empty($errors)){
        $fields = [
            'area_id'       => $area_id,
            'building_name' => $building_name,
            'router_no'     => $router_no,
            'updated_at'    => date('Y-m-d H:i:s'),
            'updated_by'    => Session::get('uid'),
        ];
        DB::getInstance()->update('buildings', $fields, 'id_building', $building_id);
        Session::put('success', 'Building data updated successfully.');
        Utility::redirect('building-router-mapping.php');
    }
}

require BASE_DIRECTORY.'/views/layouts/admin-base.phtml';