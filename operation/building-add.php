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

$pageCode       = 'operation-building-add';
$pageContent	= 'operation/building-add';
$pageTitle 		= 'Add New Building';

if(!Auth::isAuthenticatedPage($pageCode)){
    Session::put('error', "You do not have permission to access '{$pageTitle}' page.");
    Utility::redirect(BASE_URL.'/login.php');
}


$id = Input::get('id');
$areas = Utility::listServerAreas();

$area = Input::post('area');
$building = Input::post('building');
$router = Input::post('router');
$local_ip = Input::post('local_ip');
$ip_block = Input::post('ip_block');
$remote_ip_first = Input::post('remote_ip_first');
$remote_ip_last = Input::post('remote_ip_last');


if(Input::exists()){
    $now = date('Y-m-d H:i:s');
    $uid = Session::get('uid');
    
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
            'rules' => ['required' => true, 'min' => 3, 'max' => 26, 'unique'=> "buildings|building_name"],
        ],
        'router' => [
            'label' => 'Router',
            'value' => $router,
            'rules' => ['required' => true],
        ],
        'local_ip' => [
            'label' => 'Local IP',
            'value' => $local_ip,
            'rules' => ['required' => true, 'valid_ip' => $local_ip],
        ],
        'ip_block' => [
            'label' => 'IP Block',
            'value' => $ip_block,
            'rules' => ['required' => true],
        ],
        'remote_ip_first' => [
            'label' => 'First Remote IP',
            'value' => $remote_ip_first,
            'rules' => ['required' => true, 'valid_ip' => $remote_ip_first, 'unique'=> "buildings|remote_ip_first"],
        ],
        'remote_ip_last' => [
            'label' => 'Last Remote IP',
            'value' => $remote_ip_last,
            'rules' => ['required' => true, 'valid_ip' => $remote_ip_last, 'unique'=> "buildings|remote_ip_last"],
        ],
    ]);
            
    $errors = $validation->errors();
    
    if(empty($errors)){
        $instData = [
            'building_name' => $building, 
            'area_id' => $area, 
            'router_no' => $router, 
            'ip_block' => $ip_block, 
            'local_ip' => $local_ip, 
            'remote_ip_first' => $remote_ip_first, 
            'remote_ip_last' => $remote_ip_last, 
            'is_active' => '1', 
            'created_at' => $now, 
            'created_by' => $uid,
        ];
        $building_id = DB::getInstance()->insert('buildings', $instData, true);
        
        $ips = IpAddress::listIpsBetweenTwoValues($remote_ip_first, $remote_ip_last);
        
        $sql = "DROP TABLE IF EXISTS `tmp_ip_addresses`";
        DB::getInstance()->exec($sql);
        
        $sql = "CREATE TABLE `tmp_ip_addresses` (
                `id_ip_key` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `ip` VARCHAR(54) COLLATE utf8_unicode_ci DEFAULT NULL,
                `building_id` INT(10) UNSIGNED DEFAULT NULL,
                `status_id` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '0=Available, 1=Occupied',
                `occupied_subscriber_id` INT(10) UNSIGNED DEFAULT NULL,
                `created_at` DATETIME DEFAULT NULL,
                `created_by` INT(10) UNSIGNED DEFAULT NULL,
                `version` INT(10) UNSIGNED DEFAULT '1',
                PRIMARY KEY (`id_ip_key`),
                UNIQUE KEY `ip` (`ip`)
              ) ENGINE=INNODB";
        DB::getInstance()->exec($sql);
        //Utility::pr($ips); exit;
        $sqlHdr = "INSERT INTO `tmp_ip_addresses` (`ip`,`building_id`,`created_at`,`created_by`,`version`) VALUES ";
        $sqlVal = '';
        foreach($ips as $ip){
            $sqlVal .= "('{$ip}', '{$building_id}', '{$now}', '{$uid}', 1), ";
        }
        $sql = $sqlHdr . rtrim($sqlVal, ', ');
        DB::getInstance()->exec($sql);
        
        $sql = "INSERT INTO ip_addresses(`ip`, `building_id`, `status_id`, `created_at`, `created_by`, `version`)
                SELECT t.`ip`, t.`building_id`, '0', t.`created_at`, t.`created_by`, '1'
                FROM `tmp_ip_addresses` t
                LEFT JOIN `ip_addresses` i ON t.`ip` = i.`ip`
                WHERE i.`ip` IS NULL";
        DB::getInstance()->exec($sql);
        
//        $fields = [
//            'area_id'       => $area_id,
//            'building_name' => $building_name,
//            'router_no'     => $router_no,
//            'updated_at'    => date('Y-m-d H:i:s'),
//            'updated_by'    => Session::get('uid'),
//        ];
//        DB::getInstance()->update('buildings', $fields, 'id_building', $building_id);
//        Session::put('success', 'Building added successfully.');
//        Utility::redirect('building.php');
        Session::put('success', 'Building added successfully.');
        Utility::redirect('building.php');
    }
}

require BASE_DIRECTORY.'/views/layouts/admin-base.phtml';