<?php

/* 
 * Update Support Type
 * 
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date April 08, 2017 22:29
 */

require "../core/config.php";
require "../core/init.php";
require "../modules/acl/Roles.php";
require "../modules/complaint/Complaint.php";
require "../modules/date_time_dropdown.php";

$pageCode       = 'complaint-support-type-update';
$pageContent	= 'complaint/support-type-update';
$pageTitle 		= 'Update Support Type';

if(!Auth::isAuthenticatedPage($pageCode)){
    Session::put('error', "You do not have permission to access '{$pageTitle}' page.");
    Utility::redirect(BASE_URL.'/login.php');
}

$id = Input::get('id');

if(Input::exists()){
    $support_type = Input::post('support_type');
    $is_active = Input::post('is_active');

    if(Token::check(TOKEN_LEVEL, Input::post(TOKEN_LEVEL) )){
        $validate = new Validate();
        $validation = $validate->check($_POST, [
            'support_type' => [
                'label' => 'Support Type',
                'value' => $support_type,
                'rules' => ['required' => true, 'unique'=> "complaint_option_supports|name|id|{$id}"],
            ],
        ]);
        
        $errors = $validation->errors();
        if($validation->passed()) {
            $upd_data = [
                'name'      => $support_type,
                'uid_mod'   => date('Y-m-d H:i:s'),
                'dtt_mod'   => Session::get('uid'),
                'is_active' => $is_active,
            ];
            DB::getInstance()->update('complaint_option_supports', $upd_data, 'id', $id);
            
            Session::put('success', 'Data Updated Successfully.');
            Utility::redirect('support-type.php');
        }
    }
}

$sql = "SELECT s.`name` AS `support_type`, s.`is_active`
        FROM `complaint_option_supports` s
        WHERE s.`id` = ?";
$data = DB::getInstance()->query($sql, [$id]);
if(!$data->count()){
    Utility::redirect('support-type.php');
}
$data = $data->first();
$support_type = $data['support_type'];
$is_active = $data['is_active'];

require BASE_DIRECTORY.'/views/layouts/admin-base.phtml';

