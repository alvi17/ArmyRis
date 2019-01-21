<?php

/**
 * Add Bank Deposit
 * 
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date Mar 06, 2017 08:32
 */

require "../core/config.php";
require "../core/init.php";
require "../modules/acl/Roles.php";
require "../modules/user/User.php";


$pageCode       = 'report-bank-deposit-add';
$pageContent	= 'report/bank-deposit-add';
$pageTitle 		= 'Add Bank Deposit';

if(!Auth::isAuthenticatedPage($pageCode)){
    Session::put('error', "You do not have permission to access '{$pageTitle}' page.");
    Utility::redirect(BASE_URL.'/login.php');
}


$users = User::lisActiveUsers();

$date = Input::post('date');
$amount = Input::post('amount');
$submit_by = Input::post('submit_by');
$purpose = Input::post('purpose');

//$tmp = explode('/', $date);
//$date_db = (isset($tmp[2])) ? $tmp[2].'-'.$tmp[1].'-'.$tmp[0] : '';
$date_db = date('Y-m-d', strtotime($date));

$errors = [];
if(Input::exists()){
    ## VALIDATION
    if(empty($date)){
        $errors['date'] = 'Date should not be empty.';
    } elseif(!Date::isValidDate($date_db)){
        $errors['date'] = 'Date is invalid.';
    } /*elseif(Date::isFutureDate($date_db)){
        $errors['date'] = 'Date should not be Future date.';
    }*/
    
    if(empty($amount)){
        $errors['amount'] = 'Amount should not be empty.';
    } elseif(!is_numeric($amount)){
        $errors['amount'] = 'Amount should be numeric.';
    }
    
    if(empty($submit_by)){
        $errors['submit_by'] = 'Submitted by should not be empty.';
    }
    
    if(empty($errors)){
        $insert_data = [
            'date'          => $date_db, 
            'amount'        => $amount, 
            'submit_by'     => $submit_by,
            'purpose'       => $purpose,
            'dtt_add'       => date('Y-m-d H:i:s'), 
            'uid_add'       => Session::get('uid'),
        ];
        $insert_id = DB::getInstance()->insert('bank_deposits', $insert_data, true);
        Session::put('success', 'Bank Deposit added successfully.');
        Utility::redirect('bank-deposit-list.php');
    }
}

require BASE_DIRECTORY.'/views/layouts/admin-base.phtml';