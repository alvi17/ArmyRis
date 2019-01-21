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

if (!Auth::isSubscriberUser()){
    Utility::redirect(BASE_URL);
}
if(Session::get('category')=='Free'){
    Utility::redirect(BASE_URL);
}

$pageCode       = 'recharge-account';
$pageContent	= 'recharge-account';
$pageTitle 		= 'Recharge Account';

$scratchcard = new ScratchCard();
$payment = new Payment();

$subscriber_id = Session::get('uid');
$recharge_params = Payment::rechargAcountParams($subscriber_id);
//Utility::pr($recharge_params);
//Utility::pr($_SESSION);
//exit;

$card_no = Input::post('card_no');
$errors = [];

if(Input::exists()){
    ## VALIDATION
    if(empty($card_no)){
        $errors['card_no'] = 'Card number is required.';
    } elseif(strlen($card_no)< 5){
        $errors['card_no'] = 'Card number must be minimum of 5 characters.';
    } elseif(!$scratchcard->isAvailable($card_no)){
        $errors['card_no'] = 'Card number is invalid.';
    }
    
    if(empty($errors)){
       // INTIAL DATA
        $now = date('Y-m-d H:i:s');
        
        $sql = "SELECT `id_card_key` AS card_id, `amount` AS card_amount
                FROM `scratch_cards` WHERE `code` = ?";
        $card_info = DB::getInstance()->query($sql, [$card_no])->first();
        
//        $sql = "SELECT `payment_balance`, `payment_version`
//                FROM subscribers WHERE `id_subscriber_key` = ?";
//        $payment_info = DB::getInstance()->query($sql, [$subscriber_id])->first();
        
        $complementary_amount = 0;
        $debit = 0;
        $credit = $card_info['card_amount'];
//        $balance = $payment_info['payment_balance'] + $card_info['card_amount'];
//        $payment_version = $payment_info['payment_version'] + 1;
        $balance = $recharge_params['payment_balance'] + $card_info['card_amount'];
        $payment_version = $payment_balance['payment_version'] + 1;
        
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
            
            Session::put('balance', $balance);
            Session::put('success', $credit.' '.CURRENCY.' added successfully.'.$complementry_success_str);
            Utility::redirect('recharge-account.php');
            
        } catch (Exception $ex) {
            DB::getInstance()->rollbackTransaction();
        }
    }
}


require BASE_DIRECTORY.'/views/layouts/admin-base.phtml';
