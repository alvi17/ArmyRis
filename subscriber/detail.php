<?php

/**
 * Subscriber Details
 * 
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date December 10, 2016 01:31
 */

require "../core/config.php";
require "../core/init.php";
require "../modules/subscriber/Subscriber.php";


$pageCode       = 'subscriber-detail';
$pageContent	= 'subscriber/detail';
$pageTitle 		= 'Subscriber Details';

if(!Auth::isAuthenticatedPage($pageCode)){
  Session::put('error', "You do not have permission to access '{$pageTitle}' page.");
  Utility::redirect(BASE_URL.'/login.php');
}

$id = (int) Input::get('id');

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	if(isset($_POST['pdf'])){
        $url = BASE_URL .'/subscriber/pdf-detail.php?id=' . $id;
        Utility::redirect($url);
        exit;
    }
}

$subscriber = new Subscriber();


$data = $subscriber->getSubscriberDetials($id);

//Utility::pr($data); exit;

if(empty($data)){
  Session::put('error', "Subscriber information not found.");
  Utility::redirect('index.php');
}


require BASE_DIRECTORY.'/views/layouts/admin-base.phtml';