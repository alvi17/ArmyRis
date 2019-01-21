<?php

/**
 * My Account page for Subscriber
 *
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date October 22, 2016 22:57
 */

$subscriber = new Subscriber();
$myInfo = $subscriber->getSubscriberInformation(Session::get('uid'));

//Utility::pr($myInfo); exit;

$pageCode       = 'my-account';
$pageContent	= 'my-account-subscriber';
$pageTitle 		= 'My Account';

require BASE_DIRECTORY.'/views/layouts/admin-base.phtml';

