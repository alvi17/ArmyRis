<?php

/**
 * Add new role
 * 
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date December 05, 2016 01:31
 */

require "../core/config.php";
require "../core/init.php";
require "../modules/user/User.php";
require "../modules/acl/Roles.php";

$pageCode       = 'role-add';
$pageContent	= 'role/add';
$pageTitle 		= 'Add new role';

if(!Auth::isAuthenticatedPage($pageCode)){
    Session::put('error', "You do not have permission to access '{$pageTitle}' page.");
    Utility::redirect(BASE_URL.'/login.php');
}

$acl = new Roles();

$role_name = Input::post('role_name');
$acls = isset($_POST['acls']) ? $_POST['acls'] : [];
if(empty($acls)){
    $acls = [];
}

if(Input::exists()){
    $validate = new Validate();
    $validation = $validate->check($_POST, [
        'role_name' => [
            'label' => 'Role Name',
            'value' => $role_name,
            'rules' => ['required' => true, 'min' => 3, 'max' => 36, 'unique'=> 'roles|name'],
        ],
        'acls' => [
            'label' => 'Role Access',
            'value' => $acls,
            'rules' => ['required' => true],
        ],
    ]);
    $errors = $validation->errors();
    //Utility::pr($errors); exit;

    if($validation->passed()) {
        $now = date('Y-m-d H:i:s');
        $uid = Session::get('uid');

        DB::getInstance()->startTransaction();
        try {
            $data = [
                'name' => $role_name,
                'status_id' => 1,
                'version' => 1,
                'created_at' => $now,
                'created_by' => $uid,
            ];
            $role_id = DB::getInstance()->insert('roles', $data, true);
            if(!$role_id){
                throw new Exception("Role insert failed!");
            }

            //$sql = "DELETE FROM role_acl WHERE role_id = ?";

            foreach($acls as $acl){
                DB::getInstance()->insert('role_acl', ['role_id'=>$role_id, 'acl_id'=>$acl]);
            }

            DB::getInstance()->commitTransaction();
            Session::put('success', 'New Role created successfully.');
            Utility::redirect('index.php');

        } catch (Exception $ex) {
            DB::getInstance()->rollbackTransaction();
            $errors['message'] = 'Failed. '.$ex->getMessage();
        }
    }
}


$roles = $acl->listRolesKeyVal();



require BASE_DIRECTORY.'/views/layouts/admin-base.phtml';