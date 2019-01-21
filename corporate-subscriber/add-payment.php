<?php

/**
 * Add Payment for Corporate Subscriber
 * 
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date May 19, 2017 14:24
 */


require "../core/config.php";
require "../core/init.php";
require "../modules/subscriber/Subscriber.php";
require "../modules/subscriber/Corporate.php";


$pageCode       = 'corporate-subscriber-add-payment';
$pageContent	= 'corporate-subscriber/add-payment';
$pageTitle 		= 'Add Corporate Payment';

if(!Auth::isAuthenticatedPage($pageCode)){
    Session::put('error', "You do not have permission to access '{$pageTitle}' page.");
    Utility::redirect(BASE_URL.'/login.php');
}

$comment_prefix = "Corporate Payment for ";
$years = Date::listYearsBetnRangeWthCurrYear(25, 2, 'desc');
$months = Date::$months;
$subscribers = Corporate::listCorporateSubscribers();



$subscriber = (int) Input::post('subscriber');
$month = (int) Input::post('month');
$year = (int) Input::post('year');
$amount = (int) Input::post('amount');

if(empty($year)){
	$year = date("Y");
}


if(Input::exists()){
	$validate = new Validate();
	$validation = $validate->check($_POST, [
		'subscriber' => [
            'label' => 'Corporate Subscriber',
            'value' => $subscriber,
            'rules' => ['required' => true, 'digit' => true],
        ],
        'month' => [
            'label' => 'Month',
            'value' => $month,
            'rules' => ['required' => true, 'digit' => true],
        ],
        'year' => [
            'label' => 'Year',
            'value' => $year,
            'rules' => ['required' => true, 'digit' => true],
        ],
        'amount' => [
            'label' => 'Amount',
            'value' => $amount,
            'rules' => ['required' => true, 'digit' => true],
        ],
    ]);

	$errors = $validation->errors();


	$now = date('Y-m-d H:i:s');
    $uid = Session::get('uid');


	if(empty($errors)){
		$data = array(
			'subscriber_id' => $subscriber,
			'type' => 'Corporate Payment',
			'comment' => trim($comment_prefix.$months[$month].' '.$year),
			'debit' => 0,
			'credit' => $amount,
			'balance' => 0,
			'version' => '1',
			'ref_id' => NULL,
			'created_at' => $now,
			'created_by' => $uid,
			'created_user_type' => 'system',
			'is_active' => 1,
		);

		$payment_id = DB::getInstance()->insert('payments', $data, true);
		Session::put('success', 'Corporate Payment added successfully.');
	    Utility::redirect('add-payment.php');
	}
}

require BASE_DIRECTORY.'/views/layouts/admin-base.phtml';