<?php

/**
 * Recharge Account page for Subscriber
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

$pageCode       = 'recharge-account';
$pageContent	= 'recharge-account-live';
$pageTitle 		= 'Recharge Account';

$subscriber     = new Subscriber();
$scratchcard = new ScratchCard();
$payment = new Payment();

$subscriber_id = Session::get('uid');
$username       = Session::get('username');
$user_type      = Session::get('usertype');
$now            = date('Y-m-d H:i:s');

$recharge_params = Payment::rechargAcountParams($subscriber_id);


//Utility::pr($recharge_params);
//Utility::pr($_SESSION);
//exit;

$card_no = Input::post('card_no');
$errors = [];

if(Input::exists()){

    $scc_msg = Input::post('internet_checkin_btn');
    if($scc_msg  == 'Internet Check-in'){
        Utility::redirect(BASE_URL);
    }

    ## VALIDATION
    if(empty($card_no)){
        $errors['card_no'] = 'Card number is required.';
    } elseif(strlen($card_no)< 5){
        $errors['card_no'] = 'Card number must be minimum of 5 characters.';
    } elseif(!$scratchcard->isAvailable($card_no)){
        $errors['card_no'] = 'Card number is invalid.';
    }

   // Utility::pr($errors); exit;
    
    if(empty($errors)){
		
		## CARD RECHARGE STARTS ==================================================
       // INTIAL DATA
        $now = date('Y-m-d H:i:s');
        
        $sql = "SELECT `id_card_key` AS card_id, `amount` AS card_amount
                FROM `scratch_cards` WHERE `code` = ?";
        $card_info = DB::getInstance()->query($sql, [$card_no])->first();

       $sql = "SELECT `payment_balance`, `payment_version`
               FROM subscribers WHERE `id_subscriber_key` = ?";
       $payment_info = DB::getInstance()->query($sql, [$subscriber_id])->first();

        $complementary_amount = 0;
        $debit = 0;
        $credit = $card_info['card_amount'];
        $balance = $recharge_params['payment_balance'] + $card_info['card_amount'];
        $payment_version = $payment_info['payment_version'] + 1;
        
        DB::getInstance()->startTransaction();
        
        try{
            ## INSERT INTO payment TABLE
            $insert_data = [
                'subscriber_id'     => $subscriber_id, 
                'type'              => 'Scratch Card', 
                'debit'             => $debit, 
                'credit'            => $credit, 
                'balance'           => $balance, 
                'version'           => $payment_version, 
                'ref_id'            => $card_info['card_id'], 
                'created_at'        => $now, 
                'created_by'        => $subscriber_id,
                'created_user_type' => Session::get('usertype'),
            ];
            $payment_id = DB::getInstance()->insert('payments', $insert_data, true);
            if(!$payment_id){
                throw new Exception("payments insert failed!");
            }
            
            ## UPDATE AND MARK AS USED IN scratch_cards TABLE WITH PAYMENT REFERENCE
            $upd_data = [
                'status_id'         => ScratchCard::CARD_USED_CONDITION,
                'ref_id'            => $payment_id,
                'updated_at'        => $now, 
                'updated_by'        => $subscriber_id,
                'updated_user_type' => Session::get('usertype'),
            ];
            $updated = DB::getInstance()->update('scratch_cards', $upd_data, 'id_card_key', $card_info['card_id']);
            if(!$updated){
                throw new Exception("scratch_cards update failed!");
            }
            
            ## ADD COMPLEMENTARY AMOUNT FOR COMPLEMENTARY USER ONLY (WITH VALID CONDITION).
            ## payment_amount  = (package-price - complementary_amount)
            ## payment_amount MUST BE POSITIVE NUMBER. IF IT IS NEGATIVE, THEN WE CAN SAY INVALID COMLMENTARY AMOUNT ASSIGNED.
            ## DO NOTHING WITH INVALID COMPLEMENTARY AMOUNT
            if($recharge_params['category']=='Complementary' && $recharge_params['payment_amount']>0){
                // VALID for cmplimentary
                $complementary_amount = ($recharge_params['complementary_amount'] / $recharge_params['payment_amount']) * $card_info['card_amount'];
                $complementary_amount = round($complementary_amount);
                $balance += $complementary_amount;
                $payment_version += 1;
                $insert_data = [
                    'subscriber_id'     => $subscriber_id, 
                    'type'              => 'Complementary', 
                    'debit'             => $debit, 
                    'credit'            => $complementary_amount, 
                    'balance'           => $balance, 
                    'version'           => $payment_version, 
                    'ref_id'            => $card_info['card_id'], 
                    'created_at'        => $now, 
                    'created_by'        => $subscriber_id,
                    'created_user_type' => Session::get('usertype'),
                ];
                $complementary_payment_id = DB::getInstance()->insert('payments', $insert_data, true);
            }
            
            $upd_data = [
                'payment_balance' => $balance,
                'payment_version' => $payment_version,
            ];
            $updated = DB::getInstance()->update('subscribers', $upd_data, 'id_subscriber_key', $subscriber_id);
            if(!$updated){
                throw new Exception("subscribers update failed!");
            }
            
            DB::getInstance()->commitTransaction();
            
            $complementry_success_str = $complementary_amount>0 ? ' '. $complementary_amount.' '.CURRENCY.' has been added as complementary.' : '';
            ## CARD RECHARGE ENDS ==================================================
					
            //Session::put('balance', $balance);
            //Session::put('success', $credit.' '.CURRENCY.' added successfully.'.$complementry_success_str);
            //Utility::redirect('recharge-account.php');
            //die('RECHARGE SUCCESS');
        } catch (Exception $ex) {
            //die('RECHARGE FAILED');
            DB::getInstance()->rollbackTransaction();
        }




        ## INTERNET CHECKIN STARTS ================================================
        $internet_checkin_params = $subscriber->internetCheckinParams($subscriber_id);
        $connectivity = Subscriber::calcInternectCheckinDuration($internet_checkin_params['status_id'], $internet_checkin_params['disconnect_date'], $internet_checkin_params['package_days']);
        $btnTxt = Subscriber::internetCheckinBtnText($internet_checkin_params, $connectivity);
        if($internet_checkin_params['payment_balance'] < $internet_checkin_params['package_price']){
            Session::put('error', 'You do not have sufficient balance to enable internet.');
            Utility::redirect('recharge-account.php');
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
                //Session::put('success', 'Internet Checkin added successfully.');
                Session::put('success', 'You have recharged successfully and go for Internet Check-in.');
                //Utility::redirect('internet-checkin.php');
                Utility::redirect('recharge-account.php');
                //Utility::redirect(BASE_URL);

            } catch (Exception $ex) {
                DB::getInstance()->rollbackTransaction();
                $errors['message'] = 'Failed. '.$ex->getMessage();
            }
        }
        ## INTERNET CHECKIN ENDS ==================================================
    }
}


require BASE_DIRECTORY.'/views/layouts/admin-base.phtml';
