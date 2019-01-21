<?php

/**
 * Dashboard for Subscriber
 *
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date October 22, 2016 22:57
 */

use Carbon\Carbon;

$pageCode       = 'dashboard';
$pageTitle 		= 'Dashboard';
$pageContent    = 'index-subscriber';

$subscriber     = new Subscriber();
$subscriber_id  = Session::get('uid');
$username       = Session::get('username');
$now            = date('Y-m-d H:i:s');

$params = $subscriber->internetCheckinParams($subscriber_id);

$discondt = strlen($params['disconnect_date']) ? Carbon::createFromFormat('Y-m-d H:i:s', $params['disconnect_date']) : null;
$curdt = Carbon::createFromFormat('Y-m-d H:i:s', $now);


if($params['category'] == 'Free'){
    if($params['status_id']==0){
        $days_remaining_str = '<span class="badge badge-danger">Account has been Suspended.</span> Please contact with NOC to activate internet connection.';
    } elseif($params['status_id']==2){
        $days_remaining_str = '<span class="badge badge-danger">Account has been Deleted. Please contact with support team to continue.</span>';
    } elseif($params['status_id']==1){
        $days_remaining_str = '<span class="badge badge-danger">Account is Active.</span>';
    }
} else{
    if($params['status_id']==0){
        $days_remaining_str = '<span class="badge badge-danger">Account has been Suspended.</span> Please recharge account and check-in internet connection to continue.';
    } elseif($params['status_id']==2){
        $days_remaining_str = '<span class="badge badge-danger">Account has been Deleted. Please contact with support team to continue.</span>';
    } elseif(is_null($discondt)){
        $days_remaining_str = '';
    } elseif($discondt->gt($curdt)){
        $days_remaining_str = Date::duration($params['disconnect_date'], date('Y-m-d H:i:s'));
        $days_remaining_str = '<span class="badge badge-danger">'.$days_remaining_str.'</span> remaining to disconnect. Please recharge account and schedule next connectivity to avoid disconnection. ';
    } else{
        $days_remaining_str = '<span class="badge badge-danger">Connection already expired.</span> Please recharge account to continue.';
    }
}


$surveys = Survey::listActiveSurveys();

$moreJs = ['js/jquery.marquee.min.js'];
require BASE_DIRECTORY.'/views/layouts/subscriber-base.phtml';

