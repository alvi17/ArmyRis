<?php

/**
 * Description of Register
 *
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date October 22, 2016 22:57
 */

require "core/config.php";
require "core/init.php";
require "modules/user/User.php";


Utility::pr($_SESSION);

$username = Input::post('username');
$password = Input::post('password');
$password_again = Input::post('password_again');
$name = Input::post('name');
$email = Input::post('email');

if(Input::exists()){
    //Utility::pa(Input::post(TOKEN));
    //Utility::pa(Token::check(TOKEN, Input::post(TOKEN)));
    //exit;
    //if(Token::check(TOKEN)){
    if(1){
        $validate = new Validate();
        $validation = $validate->check($_POST, array(
            'username' => array(
                'label' => 'Username',
                'value' => $username,
                'rules' => array(
                    'required' => true,
                    'min' => 2,
                    'max' => 20,
                    'unique'=> 'users'
                ),
            ),
            'password' => array(
                'label' => 'Password',
                'value' => $password,
                'rules' => array(
                    'required' => true,
                    'min' => 6,
                ),  
            ),
            'password_again' => array(
                'label' => 'Password Again',
                'value' => $password_again,
                'rules' => array(
                    'required' => true,
                    'matches' => 'password',
                ),  
            ),
            'name' => array(
                'label' => 'Full Name',
                'value' => $name,
                'rules' => array(
                    'required' => true,
                    'min' => 2,
                    'max' => 50,
                ),
            ),
            'email' => array(
                'label' => 'Email',
                'value' => $email,
                'rules' => array(
                    'required' => true,
                    'min' => 2,
                    'max' => 24,
                    'email' => '',
                ),
            ),
        ));

        $errors = $validation->errors();
        Utility::dd($errors);

        if($validation->passed()) {
            // register user
            $user = new User();
            try{
                $salt = Hash::salt(32);
                $user->create(
                    array(
                        'username'  => $username,
                        'password'  => Hash::make($password, $salt),
                        'salt'      => $salt,
                        'name'      => $name,
                        'email'     => $email,
                        'joined'    => date('Y-m-d H:i:s'),
                    )
                );
                
                Session::put('success', 'Registered successfully.');
                Utility::redirect('register.php');
                
            } catch (Exception $ex) {
                Utility::pr($ex->getMessage());
            }
        } else{
            // output error
            Utility::pr($errors);
        }
    }
}


//if(Input::exists()){
//    echo Input::request('username');
//}

//## Check GET
//var_dump(Input::exists('get'));
//echo Input::get('username');
?>

<h2><?php echo Session::flash('success');?></h2>
<form action="" method="post">
    <table>
        <tr>
            <td><label for="username">Username</label></td>
            <td><input type="text" name="username" id="username" value="<?php echo $username;?>" autocomplete="off" /></td>
        </tr>
        <tr>
            <td><label for="password">Password</label></td>
            <td><input type="text" name="password" id="password" value="<?php echo $password;?>" autocomplete="off" /></td>
        </tr>
        <tr>
            <td><label for="password_again">Password Again</label></td>
            <td><input type="text" name="password_again" id="password_again" value="<?php echo $password_again;?>" autocomplete="off" /></td>
        </tr>
        <tr>
            <td><label for="name">Full Name</label></td>
            <td><input type="text" name="name" id="name" value="<?php echo $name;?>" autocomplete="off" /></td>
        </tr>
        <tr>
            <td><label for="email">Email</label></td>
            <td><input type="text" name="email" id="email" value="<?php echo $email;?>" autocomplete="off" /></td>
        </tr>
        <tr>
            <td><input type="hidden" name="<?php echo TOKEN;?>" id="<?php echo TOKEN;?>" value="<?php echo Token::generate(TOKEN);?>"></td>
            <td><input type="submit" value="Submit"></td>
        </tr>
    </table>
</form>