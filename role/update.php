<?php

/**
 * Update existing role
 * 
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date December 05, 2016 01:31
 */

require "../core/config.php";
require "../core/init.php";
require "../modules/acl/Roles.php";

$acl = new Roles();

$role_id = Input::get('id');
$acls = $acl->findAclsByRoleId($role_id);
$role_name = $acl->getRoleNameById($role_id);
//$uroles = array_column($acl->findRolesById($role_id), 'id');
//Utility::pr($acls); exit;

if(Input::exists()){
    $role_name = Input::post('role_name');
    $acls = $_POST['acls'];
    if(empty($acls)){
        $acls = [];
    }
    
    $validate = new Validate();
    $validation = $validate->check($_POST, [
        'role_name' => [
            'label' => 'Role Name',
            'value' => $role_name,
            'rules' => ['required' => true, 'min' => 3, 'max' => 36, 'unique'=> "roles|name|id|{$role_id}"],
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
                'name'          => $role_name,
                'status_id'     => 1,
                //'version'       => 1,
                'updated_at'    => $now,
                'updated_by'    => $uid,
            ];
            $updated = DB::getInstance()->update('roles', $data, 'id', $role_id);
            if(!$updated){
                throw new Exception("Role update failed!");
            }

            $sql = "DELETE FROM role_acl WHERE role_id = ?";
            DB::getInstance()->exec($sql, [$role_id]);

            foreach($acls as $acl){
                DB::getInstance()->insert('role_acl', ['role_id'=>$role_id, 'acl_id'=>$acl]);
            }

            DB::getInstance()->commitTransaction();
            Session::put('success', 'Role updated successfully.');
            Utility::redirect('index.php');

        } catch (Exception $ex) {
            DB::getInstance()->rollbackTransaction();
            $errors['message'] = 'Failed. '.$ex->getMessage();
        }
    }
}

$roles = $acl->listRolesKeyVal();

$pageCode       = 'role-update';
$pageContent	= 'role/update';
$pageTitle 		= 'Update role';

if(!Auth::isAuthenticatedPage($pageCode)){
    Session::put('error', "You do not have permission to access '{$pageTitle}' page.");
    Utility::redirect(BASE_URL.'/login.php');
}


require BASE_DIRECTORY.'/views/layouts/admin-base.phtml';