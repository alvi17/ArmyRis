<?php

/**
 * Internet Checkin Page for Subscriber
 *
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date October 22, 2016 22:57
 */

require "core/config.php";
require "core/init.php";
require "modules/subscriber/Subscriber.php";
require_once 'modules/mikrotik/PppoeApiService.php';

if (!Auth::isSubscriberUser()){
    Utility::redirect(BASE_URL);
}
if(Session::get('category')=='Free'){
    Utility::redirect(BASE_URL);
}

$subscriber     = new Subscriber();
$subscriber_id  = Session::get('uid');
$username       = Session::get('username');
$user_type      = Session::get('usertype');
$now            = date('Y-m-d H:i:s');

$internet_checkin_params = $subscriber->internetCheckinParams($subscriber_id);
$connectivity = Subscriber::calcInternectCheckinDuration($internet_checkin_params['status_id'], $internet_checkin_params['disconnect_date'], $internet_checkin_params['package_days']);
$btnTxt = Subscriber::internetCheckinBtnText($internet_checkin_params, $connectivity);

if(Input::exists()){
    if(Token::check(TOKEN_LEVEL, Input::post(TOKEN_LEVEL))){
        
        if($internet_checkin_params['payment_balance'] < $internet_checkin_params['package_price']){
            $errors['message'] = 'Failed';
        } else{
            $sql = "SELECT `payment_balance`, `payment_version`, `connection_version`
                    FROM subscribers WHERE `id_subscriber_key` = ?";
            $subs_info = DB::getInstance()->query($sql, [$subscriber_id])->first();

            $debit = $internet_checkin_params['package_price'];
            $credit = 0;
            $balance = $subs_info['payment_balance'] - $debit;
            $payment_version = $subs_info['payment_version'] + 1;
            $connection_version = $subs_info['connection_version'] + 1;

            DB::getInstance()->startTransaction();

            try{
                $paymentData = [
                    'subscriber_id'     => $subscriber_id,
                    'type'              => 'Enable Internet',
                    'debit'             => $debit,
                    'credit'            => $credit,
                    'balance'           => $balance,
                    'version'           => $payment_version,
                    //'ref_id'            => $card_info['card_id'], 
                    'comment'           => 'Net Connection Enabled',
                    'created_at'        => $now,
                    'created_by'        => $subscriber_id,
                    'created_user_type' => $user_type,
                ];
                $payment_id = DB::getInstance()->insert('payments', $paymentData, true);
                if(!$payment_id){
                    throw new Exception("payments insert failed!");
                }

                $connectionData = [
                    'subscriber_id'     => $subscriber_id,
                    'package_id'        => $internet_checkin_params['package_id'],
                    'status_id'         => $internet_checkin_params['status_id'],
                    'amount'            => $internet_checkin_params['package_price'],
                    'payment_ref_id'    => $payment_id,
                    'connection_from'   => $connectivity['connection_from'],
                    'connection_to'     => $connectivity['connection_to'],
                    'comment'           => 'Net Connection Enabled',
                    'version'           => $connection_version,
                    'created_at'        => $now, 
                    'created_by'        => $subscriber_id,
                    'created_user_type' => $user_type,
                ];

                $connection_id = DB::getInstance()->insert('subscribers_connections_audit', $connectionData, true);
                if(!$connection_id){
                    throw new Exception("payments insert failed!");
                }

                $paymentData = ['ref_id' => $connection_id];
                $updated = DB::getInstance()->update('payments', $paymentData, 'id_payment_key', $payment_id);

                $subsData = [
                    'connection_from'   => $connectivity['connection_from'],
                    'connection_to'     => $connectivity['connection_to'],
                    'connection_version'=> $connection_version,
                    'status_id'         => 1,
                    'payment_balance'   => $balance,
                    'payment_version'   => $payment_version,
                ];
                if($internet_checkin_params['status_id'] == 1){
                    unset($subsData['connection_from']);
                }
                $updated = DB::getInstance()->update('subscribers', $subsData, 'id_subscriber_key', $subscriber_id);
                if(!$updated){
                    throw new Exception("subscribers update failed!");
                }

                DB::getInstance()->commitTransaction();
                
                ## UPDATE MIKROTIK USER
                $router = $mikrotik_routers[ $internet_checkin_params['router_no'] ];
                $mikrotik = new PppoeApiService($router['router_ip'], $router['username'], $router['password']);
                $mikrotik->enableUser(Session::get('username'));
                $package = $subscriber->getPackageBySubscriberId(Session::get('uid'));
                $mikrotik->changeProfile(Session::get('username'), $package);
                $mikrotik->removeUserFromActive(Session::get('username'));

                Session::put('balance', $balance);
                Session::put('success', 'Internet Checkin added successfully.');
                Utility::redirect('internet-checkin.php');

            } catch (Exception $ex) {
                DB::getInstance()->rollbackTransaction();
                $errors['message'] = 'Failed. '.$ex->getMessage();
            }
        }
    }
}

$pageCode       = 'internet-checkin';
$pageContent	= 'internet-checkin';
$pageTitle 		= 'Internet Checkin';

//$moreJs = ['js/unicorn.form_validation.js'];
require BASE_DIRECTORY.'/views/layouts/admin-base.phtml';
