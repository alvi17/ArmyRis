<?php

/**
 * Description of User Update
 *
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date October 22, 2016 22:57
 */

require "../core/config.php";
require "../core/init.php";
require "../modules/user/User.php";

$pageCode       = 'user-update';
$pageContent	= 'user/update';
$pageTitle 		= 'Update User';

if(!Auth::isAuthenticatedPage($pageCode)){
    Session::put('error', "You do not have permission to access '{$pageTitle}' page.");
    Utility::redirect(BASE_URL.'/login.php');
}

$user = new User();
$ranks = Utility::listRanks();
$roles = Utility::listRoles();

$userid = (int) trim($_GET['userid']);

list($username, $ba_no, $firstname, $lastname, $rank, $mobile, $email, $status_id, $is_support_asst, $uroles) = $user->listUserData($userid);
$password = '';
$confirm_password = '';
//Utility::dd($tmp);
//Utility::dd($username);

if(Input::exists()){
    $username = Input::post('username');
    $password = Input::post('password');
    $confirm_password = Input::post('confirm_password');
    $ba_no = Input::post('ba_no');
    $firstname = Input::post('firstname');
    $lastname = Input::post('lastname');
    $rank = (int) Input::post('rank');
    $mobile = Input::post('mobile');
    $email = Input::post('email');
    $status_id = Input::post('status_id');
    $is_support_asst = Input::post('is_support_asst');
    $uroles = $_POST['roles'];
    if(empty($uroles)){$uroles = [];} // confirms the variable as array
    
    //if(Token::check(TOKEN_LEVEL, Input::post(TOKEN_LEVEL) )){
    if(1){
        $validate = new Validate();
        $validation = $validate->check($_POST, [
            'username' => [
                'label' => 'Username',
                'value' => $username,
                'rules' => ['required' => true, 'min' => 3, 'max' => 12, 'unique'=> "users|username|id|{$userid}"],
            ],
            'password' => [
                'label' => 'Password',
                'value' => $password,
                'rules' => ['required' => false, 'min' => 3, 'max' => 20],  
            ],
            'confirm_password' => [
                'label' => 'Confirm Password',
                'value' => $confirm_password,
                'rules' => ['required' => false, 'matches' => 'password'],  
            ],
            'ba_no' => [
                'label' => 'BA No',
                'value' => $ba_no,
                'rules' => ['required' => true, 'min' => 3, 'max' => 10, 'unique'=> "users|ba_no|id|{$userid}"],  
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
        if($validation->passed()) {
            $user = new User();
            $fields = [
                'username'      => $username,
                'ba_no'         => $ba_no,
                'firstname'     => $firstname,
                'lastname'      => $lastname,
                'rank'          => $rank,
                'mobile'        => $mobile,
                'email'         => $email,
                'roles'         => $uroles,
                'is_support_asst'   => $is_support_asst,
                'status_id'     => $status_id,
                'updated_at'    => date('Y-m-d H:i:s'),
                'updated_by'    => Session::get('uid'),
            ];
            if(!empty($password)){
                $salt = Hash::salt(32);
                $fields['password'] = Hash::make($password, $salt);
                $fields['salt'] = $salt;
            }

            $updated = $user->update($fields, $userid);

            if(!$updated['error']){
                Session::put('success', 'User updated successfully.');
                Utility::redirect('index.php');
            } else{
                $errors['message'] = $updated['error'];
            }
        }
        
    } else{
        echo 'Token did not Match';
    }
}


require BASE_DIRECTORY.'/views/layouts/admin-base.phtml';
