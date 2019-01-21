<?php

/**
 * Description of Change Password
 *
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date October 22, 2016 22:57
 */

require "core/config.php";
require "core/init.php";
require "modules/user/User.php";
require "modules/subscriber/Subscriber.php";
require_once 'modules/mikrotik/PppoeApiService.php';

Auth::confirmLoggedIn();

$current_password = Input::post('current_password');
$new_password = Input::post('new_password');
$confirm_password = Input::post('confirm_password');

if(Input::exists()){
    $errors = [];
    
    $user = new User();
    $subscriber = new Subscriber();
    
    $uid = Session::get('uid');
    $usertype = Session::get('usertype');
    
    if(empty($current_password)){
        $errors['current_password'] = 'Current Password is empty!';
    }
    else{
        if($usertype == 'system' && !$user->passwordMatchesInDb($uid, $current_password)){
            $errors['current_password'] = 'Current Password does not match!';
        } elseif($usertype == 'subscriber' && !$subscriber->passwordMatchesInDb($uid, $current_password)){
            $errors['current_password'] = 'Current Password does not match!';
        }
    }
    
    if(empty($new_password)){
        $errors['new_password'] = 'New Password is empty!';
    }elseif(strlen($new_password)<3){
        $errors['new_password'] = 'New Password must be minimum of 3 characters!';
    }elseif(strlen($new_password)>20){
        $errors['new_password'] = 'New Password must be maximum of 20 characters!';
    }
    
    if(empty($confirm_password)){
        $errors['confirm_password'] = 'Confirm Password is empty!';
    }elseif($new_password!=$confirm_password){
        $errors['confirm_password'] = 'Confirm Password does not match with New Password!';
    }
    
    $passwordChanged = false;
    if(empty($errors)){
        if($usertype == 'system'){
            $password = Hash::make($new_password, $user->getSalt($uid));
            $user->updatePassword($password, $uid);
            $passwordChanged = true;
        } else if($usertype == 'subscriber'){
            $subscriber->updatePasswordBySubscriber($new_password, $uid);
            $passwordChanged = true;
        }
    }
    if($passwordChanged){
        Session::put('success', 'Password Changed Successfully.');
        Utility::redirect('change-password.php');
    }
}
    

$pageCode       = 'change-password';
$pageContent	= 'change-password';
$pageTitle 		= 'Change Password';

//
//$moreJs = ['js/unicorn.form_validation.js'];
require BASE_DIRECTORY.'/views/layouts/admin-base.phtml';
