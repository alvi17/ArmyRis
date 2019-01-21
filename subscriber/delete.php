<?php

/**
 * Add Subscriber
 * 
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date December 18, 2016 06:30
 */

require "../core/config.php";
require "../core/init.php";
require "../modules/subscriber/Subscriber.php";
require_once '../modules/mikrotik/PppoeApiService.php';


$pageCode       = 'subscriber-delete';
$pageContent	= 'subscriber/delete';
$pageTitle 		= 'Delete Subscriber';

if(!Auth::isAuthenticatedPage($pageCode)){
    Session::put('error', "You do not have permission to access '{$pageTitle}' page.");
    Utility::redirect(BASE_URL.'/login.php');
}


$subscriber = new Subscriber();
$id = Input::get('id');
$data = $subscriber->getSubscriberDetials($id);
$version = $data['status_version'] + 1;
$now = date('Y-m-d H:i:s');
$uid = Session::get('uid');
$utype = Session::get('usertype');


global $mikrotik_routers;

# DELETE MIKROTIK USER
$router = $mikrotik_routers[$data['router_no']];
$mikrotik = new PppoeApiService($router['router_ip'], $router['username'], $router['password']);
$mikrotik->deleteUser($data['username']);

# INSERT subscribers_connections_audit TABLE
$insertData = [
    'subscriber_id'     => $id,
    'status_id'         => 2,
    'package_id'        => $data['package_id'],
    'comment'           => 'Subscriber Deleted',
    'version'           => $version,
    'created_at'        => $now,
    'created_by'        => $uid,
    'created_user_type' => $utype,
];
$payment_id = DB::getInstance()->insert('subscribers_connections_audit', $insertData, true);

# UPDATE subscribers TABLE
$subsData = [
    'status_id'         => 2,
    'status_version'    => $version,
    'updated_at'        => $now,
    'updated_by'        => $uid,
    'updated_user_type' => $utype,
];
$updated = DB::getInstance()->update('subscribers', $subsData, 'id_subscriber_key', $id);


if($params['status_id'] == 1){
    unset($subsData['connection_from']);
}
$updated = DB::getInstance()->update('subscribers', $subsData, 'id_subscriber_key', $subscriber_id);



Session::put('success', 'Subscriber '.$data['username'].' deleted successfully.');
Utility::redirect('index.php');