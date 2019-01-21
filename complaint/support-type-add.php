<?php

/* 
 * Add Support Type
 * 
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date April 08, 2017 22:29
 */

require "../core/config.php";
require "../core/init.php";
require "../modules/acl/Roles.php";
require "../modules/complaint/Complaint.php";
require "../modules/date_time_dropdown.php";

$pageCode       = 'complaint-support-type-add';
$pageContent	= 'complaint/support-type-add';
$pageTitle 		= 'Add Support Type';

if(!Auth::isAuthenticatedPage($pageCode)){
    Session::put('error', "You do not have permission to access '{$pageTitle}' page.");
    Utility::redirect(BASE_URL.'/login.php');
}


$support_type = Input::post('support_type');

if(Input::exists()){
    if(Token::check(TOKEN_LEVEL, Input::post(TOKEN_LEVEL) )){
        $validate = new Validate();
        $validation = $validate->check($_POST, [
            'support_type' => [
                'label' => 'Support Type Type',
                'value' => $support_type,
                'rules' => ['required' => true, 'unique'=> 'complaint_option_supports|name'],
            ],
        ]);
        
        $errors = $validation->errors();
        if($validation->passed()) {
            $inst_data = [
                'name'      => $support_type,
                'uid_add'   => date('Y-m-d H:i:s'),
                'dtt_add'   => Session::get('uid'),
                'is_active' => 1,
            ];
            DB::getInstance()->insert('complaint_option_supports', $inst_data, true);
            
            Session::put('success', 'Support Type Created Successfully.');
            Utility::redirect('support-type.php');
        }
    }
}

require BASE_DIRECTORY.'/views/layouts/admin-base.phtml';