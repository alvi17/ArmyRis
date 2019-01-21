<?php

/**
 * Description of User Add
 *
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date October 22, 2016 22:57
 */

require "../core/config.php";
require "../core/init.php";
require "../modules/user/User.php";

//Auth::confirmLoggedIn();

$pageCode       = 'user-add';
$pageContent	= 'user/add';
$pageTitle 		= 'Add User';

if(!Auth::isAuthenticatedPage($pageCode)){
    Session::put('error', "You do not have permission to access '{$pageTitle}' page.");
    Utility::redirect(BASE_URL.'/login.php');
}


$ranks = Utility::listRanks();
$roles = Utility::listRoles();
//Utility::pa($roles); exit;

$username = Input::post('username');
$password = Input::post('password');
$confirm_password = Input::post('confirm_password');
$ba_no = Input::post('ba_no');
$firstname = Input::post('firstname');
$lastname = Input::post('lastname');
$rank = (int) Input::post('rank');
$mobile = Input::post('mobile');
$email = Input::post('email');
$is_support_asst = Input::post('is_support_asst');
$uroles = isset($_POST['roles']) ? $_POST['roles'] : [];
if(empty($uroles)){$uroles = [];} // confirms the variable as array

if(Input::exists()){
    //if(Token::check(TOKEN_LEVEL, Input::post(TOKEN_LEVEL) )){
    if(1){
        $validate = new Validate();
        $validation = $validate->check($_POST, [
            'username' => [
                'label' => 'Username',
                'value' => $username,
                'rules' => ['required' => true, 'min' => 3, 'max' => 12, 'unique'=> 'users|username'],
            ],
            'password' => [
                'label' => 'Password',
                'value' => $password,
                'rules' => ['required' => true, 'min' => 3, 'max' => 20],  
            ],
            'confirm_password' => [
                'label' => 'Confirm Password',
                'value' => $confirm_password,
                'rules' => ['required' => true, 'matches' => 'password'],  
            ],
            'ba_no' => [
                'label' => 'BA No',
                'value' => $ba_no,
                'rules' => ['required' => true, 'min' => 3, 'max' => 10, 'unique'=> 'users|ba_no'],  
            ],
            'firstname' => [
                'label' => 'Firstname',
                'value' => $firstname,
                'rules' => ['required' => true, 'min' => 3, 'max' => 20, 'no_digit' => true],  
            ],
            'rank' => [
                'label' => 'Rank',
                'value' => $rank,
                'rules' => ['required' => true],
            ],
            'mobile' => [
                'label' => 'Mobile',
                'value' => $mobile,
                'rules' => ['required' => true, 'digit' => true],
            ],
            'email' => [
                'label' => 'Email',
                'value' => $email,
                'rules' => ['email' => true],
            ],
            'roles' => [
                'label' => 'Role',
                'value' => $uroles,
                'rules' => ['required' => true],
            ],
        ]);

        $errors = $validation->errors();
        //Utility::pr($errors); exit;
        
        if($validation->passed()) {
            $salt = Hash::salt(32);
            $user = new User();
            $created = $user->create([
                'username'      => $username,
                'password'      => Hash::make($password, $salt),
                'salt'          => $salt,
                'ba_no'         => $ba_no,
                'firstname'     => $firstname,
                'lastname'      => $lastname,
                'rank'          => $rank,
                'mobile'        => $mobile,
                'email'         => $email,
                'is_support_asst' => $is_support_asst,
                'roles'         => $uroles,
                'created_at'    => date('Y-m-d H:i:s'),
                'created_by'    => Session::get('uid'),
            ]);

            if(!$created['error']){
                Session::put('success', 'User added successfully.');
                Utility::redirect('index.php');
            } else{
                $errors['message'] = $created['error'];
            }
        }
        
    } else{
        echo 'Token did not Match';
    }
}

//$moreJs = ['js/unicorn.form_validation.js'];
require BASE_DIRECTORY.'/views/layouts/admin-base.phtml';
