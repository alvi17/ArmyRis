<?php

/**
 * Contains all functions/methods of ACL Role
 *
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date October 22, 2016 22:57
 */

class Roles {
    private $_db;
    
    public function __construct() {
        $this->_db = DB::connectDb();
    }
    
    public function listRolesKeyVal(){
        $roles = [];
        $result = $this->findAllRoles();
        foreach($result as $res){
            //$roles[$res['module']][$res['id']] = $res['name'];
            $roles[$res['module']][] = [
                'id'    => $res['id'],
                'name'  => $res['name'],
            ];
        }
        return $roles;
    }
    
    public function getRoleNameById($role_id){
        $sql = "SELECT `name` FROM `roles` WHERE `id` = ?";
        $stmt = $this->_db->prepare($sql);
        $stmt->bindParam(1, $role_id);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return isset($result[0]['name']) ? $result[0]['name'] : '';
    }

    public function findAclsByRoleId($role_id){
        $acls = [];
        $sql = "SELECT `acl_id`
                FROM `role_acl`
                WHERE `role_id` = ?";
        $stmt = $this->_db->prepare($sql);
        $stmt->bindParam(1, $role_id);
        $stmt->execute();
        $result = $stmt->fetchAll();
        foreach($result as $res){
            $acls[] = $res['acl_id'];
        }
        return $acls;
    }

    public function findAllRoles(){
        $sql = "SELECT a.`id`
                , m.`module_name` AS `module`
                , a.`code`
                , a.`name`
                , a.`relative_url`
                , a.`is_menu`
                FROM `acls` a
                INNER JOIN `acl_modules` m ON m.`id_acl_module` = a.`module_id`
                WHERE a.`is_active` = 1
                ORDER BY m.`order` ASC, a.`order` ASC";
        $stmt = $this->_db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function findUserRoles($uid){
        $sql = "SELECT a.`id`
                , m.`module_name` AS `module`
                , a.`code`
                , a.`name`
                , a.`relative_url`
                , a.`is_menu`
                FROM `acls` a
                INNER JOIN `acl_modules` m ON m.`id_acl_module` = a.`module_id`
                INNER JOIN `role_acl` ra ON ra.`acl_id` = a.`id`
                INNER JOIN `user_role` ur ON ur.`role_id` = ra.`role_id`
                WHERE ur.`user_id` = ?
                AND a.`is_active` = 1
                ORDER BY m.`order` ASC, a.`order` ASC";
        $stmt = $this->_db->prepare($sql);
        $stmt->bindParam(1, $uid);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function listUserPagesByRoles($uid){
        $data = [];
        $result = Auth::isSuperAdminRole() ? $this->findAllRoles() : $this->findUserRoles($uid);
        
        foreach($result as $res){
            $data[$res['module']][] = [
                'id'            => $res['id'],
                'code'          => $res['code'],
                'title'         => $res['name'],
                'relative_url'  => $res['relative_url'],
                'is_menu'       => $res['is_menu'],
            ];                    
        }
        
        return $data;
    }
}
