<?php

/* 
 * Lists Scratchcard Lots
 * 
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date February 04, 2017 22:28
 */

require "../core/config.php";
require "../core/init.php";
require "../modules/acl/Roles.php";
require_once "../modules/scratchcard/Card.php";

$pageCode       = 'scratchcard-lot-add';
$pageContent	= 'scratchcard/lot-add';
$pageTitle 		= 'Generate Scratch Card Lot';

if(!Auth::isAuthenticatedPage($pageCode)){
    Session::put('error', "You do not have permission to access '{$pageTitle}' page.");
    Utility::redirect(BASE_URL.'/login.php');
}


$amount = '';
$quantity = '';
$c_digits = 10;
$c_prefix = '';
$s_digits = 6;
$s_prefix = '';

if(Input::exists()){
    if(Token::check(TOKEN_LEVEL, Input::post(TOKEN_LEVEL) )){
        $amount = (int) Input::post('amount');
        $quantity = (int) Input::post('quantity');
        $c_digits = (int) Input::post('c_digits');
        $c_prefix = Input::post('c_prefix');
        $s_digits = (int) Input::post('s_digits');
        $s_prefix = Input::post('s_prefix');
        
        
        $validate = new Validate();
        $validation = $validate->check($_POST, [
            'amount' => [
                'label' => 'Amount',
                'value' => $amount,
                'rules' => ['required' => true, 'digit' => true],
            ],
            'quantity' => [
                'label' => 'Quantity',
                'value' => $quantity,
                'rules' => ['required' => true, 'digit' => true],
            ],
            'c_digits' => [
                'label' => 'Digits',
                'value' => $c_digits,
                'rules' => ['required' => true, 'digit' => true],
            ],
            'c_prefix' => [
                'label' => 'Prefix',
                'value' => $c_prefix,
                'rules' => ['max' => ($c_digits - 1)],
            ],
            'S_digits' => [
                'label' => 'Digits',
                'value' => $s_digits,
                'rules' => ['required' => true, 'digit' => true],
            ],
            's_prefix' => [
                'label' => 'Prefix',
                'value' => $s_prefix,
                'rules' => ['required' => true, 'max' => ($s_digits - 1)],
            ],
        ]);
        $errors = $validation->errors();
        
        if($validation->passed()) {
            $card = new Card();
            $card->generateCards($amount, $quantity, $c_digits, $c_prefix, $s_digits, $s_prefix);
            Session::put('success', 'Scratch Card Lot Generated Successfully.');
            Utility::redirect('lots.php');
        }
    }
}

require BASE_DIRECTORY.'/views/layouts/admin-base.phtml';