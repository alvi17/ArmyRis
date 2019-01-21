<?php

/* 
 * Update Problem Type
 * 
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date February 05, 2017 13:58
 */

require "../core/config.php";
require "../core/init.php";
require "../modules/acl/Roles.php";
require "../modules/complaint/Complaint.php";
require "../modules/date_time_dropdown.php";

$pageCode       = 'complaint-problem-type-update';
$pageContent	= 'complaint/problem-type-update';
$pageTitle 		= 'Update Problem Type';

if(!Auth::isAuthenticatedPage($pageCode)){
    Session::put('error', "You do not have permission to access '{$pageTitle}' page.");
    Utility::redirect(BASE_URL.'/login.php');
}

$id = Input::get('id');

if(Input::exists()){
    $problem_type = Input::post('problem_type');
    $is_active = Input::post('is_active');

    if(Token::check(TOKEN_LEVEL, Input::post(TOKEN_LEVEL) )){
        $validate = new Validate();
        $validation = $validate->check($_POST, [
            'problem_type' => [
                'label' => 'Problem Type',
                'value' => $problem_type,
                'rules' => ['required' => true, 'unique'=> "complaint_option_problems|name|id|{$id}"],
            ],
        ]);
        
        $errors = $validation->errors();
        if($validation->passed()) {
            $upd_data = [
                'name'      => $problem_type,
                'uid_mod'   => date('Y-m-d H:i:s'),
                'dtt_mod'   => Session::get('uid'),
                'is_active' => $is_active,
            ];
            DB::getInstance()->update('complaint_option_problems', $upd_data, 'id', $id);
            
            Session::put('success', 'Problem Type Updated Successfully.');
            Utility::redirect('problem-type.php');
        }
    }
}

$sql = "SELECT p.`name` AS `problem_type`, p.`is_active`
        FROM `complaint_option_problems` p
        WHERE p.`id` = ?";
$data = DB::getInstance()->query($sql, [$id]);
if(!$data->count()){
    Utility::redirect('problem-type.php');
}
$data = $data->first();
$problem_type = $data['problem_type'];
$is_active = $data['is_active'];

require BASE_DIRECTORY.'/views/layouts/admin-base.phtml';