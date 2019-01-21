<?php

/**
 * Add New Survey
 * 
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date Mar 18, 2017 18:32
 */

require "../core/config.php";
require "../core/init.php";
require "../modules/acl/Roles.php";


$pageCode       = 'survey-add';
$pageContent	= 'survey/add';
$pageTitle 		= 'Add New Survey';

if(!Auth::isAuthenticatedPage($pageCode)){
    Session::put('error', "You do not have permission to access '{$pageTitle}' page.");
    Utility::redirect(BASE_URL.'/login.php');
}


$title = Input::post('title');
$description = Input::post('description');

if(Input::exists()){
    $validate = new Validate();
    $validation = $validate->check($_POST, [
        'title' => [
            'label' => 'Title',
            'value' => $title,
            'rules' => ['required' => true, 'max' => 200],  //'unique'=> 'surveys|name'
        ],
        'description' => [
            'label' => 'Description',
            'value' => $description,
            'rules' => ['max' => 500],
        ],
    ]);
    
    $errors = $validation->errors();
    
    if(empty($errors)){
        $data = [
            'name'          => $title, 
            'desc'          => $description, 
            'is_active'     => 1, 
            'tot_ques'      => 0,
            'tot_subs'      => 0,
            'dtt_create'    => date('Y-m-d H:i:s'), 
            'uid_create'    => Session::get('uid'),
        ];
        $id = DB::getInstance()->insert('surveys', $data, true);
        
        Session::put('success', 'New Survey created successfully. Please set questions for this survey.');
        Utility::redirect('questions.php?survey_id='.$id);
    }
}


require BASE_DIRECTORY.'/views/layouts/admin-base.phtml';