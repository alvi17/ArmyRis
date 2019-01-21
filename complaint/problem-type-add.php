<?php

/* 
 * Add Problem Type
 * 
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date February 05, 2017 13:58
 */

require "../core/config.php";
require "../core/init.php";
require "../modules/acl/Roles.php";
require "../modules/complaint/Complaint.php";
require "../modules/date_time_dropdown.php";

$pageCode       = 'complaint-problem-type-add';
$pageContent	= 'complaint/problem-type-add';
$pageTitle 		= 'Add Problem Type';

if(!Auth::isAuthenticatedPage($pageCode)){
    Session::put('error', "You do not have permission to access '{$pageTitle}' page.");
    Utility::redirect(BASE_URL.'/login.php');
}


$problem_type = Input::post('problem_type');

if(Input::exists()){
    if(Token::check(TOKEN_LEVEL, Input::post(TOKEN_LEVEL) )){
        $validate = new Validate();
        $validation = $validate->check($_POST, [
            'problem_type' => [
                'label' => 'Problem Type',
                'value' => $problem_type,
                'rules' => ['required' => true, 'unique'=> 'complaint_option_problems|name'],
            ],
        ]);
        
        $errors = $validation->errors();
        if($validation->passed()) {
            $inst_data = [
                'name'      => $problem_type,
                'uid_add'   => date('Y-m-d H:i:s'),
                'dtt_add'   => Session::get('uid'),
                'is_active' => 1,
            ];
            DB::getInstance()->insert('complaint_option_problems', $inst_data, true);
            
            Session::put('success', 'Problem Type Created Successfully.');
            Utility::redirect('problem-type.php');
        }
    }
}

require BASE_DIRECTORY.'/views/layouts/admin-base.phtml';
