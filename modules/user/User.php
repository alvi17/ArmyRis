<?php

/**
 * Description of User
 *
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date October 22, 2016 22:57
 */

class User {
    private $_db,
            $_data;
    
    public function __construct() {
        $this->_db = DB::connectDb();
    }
    
    public function create($fields = []){
        $ret['error'] = false;
        $this->_db->beginTransaction();
        try{
            $sql = "INSERT INTO users 
                    (username, password, salt, ba_no, firstname, lastname, rank, mobile, email, created_at, created_by, version)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->_db->prepare($sql);
            $stmt->execute([$fields['username'], $fields['password'], $fields['salt'], $fields['ba_no'], $fields['firstname'], $fields['lastname'], $fields['rank'], $fields['mobile'], $fields['email'], $fields['created_at'], $fields['created_by'], 1]);
            $uid = $this->_db->lastInsertId();
            
            $sql = "INSERT INTO user_role (user_id, role_id) VALUES(?, ?)";
            $stmt = $this->_db->prepare($sql);
            $stmt->bindParam(1, $uid);
            $stmt->bindParam(2, $role);
            foreach($fields['roles'] as $role){
                $stmt->execute();
            }
            $this->_db->commit();
        } catch (Exception $e) {
            $ret['error'] = $e->getMessage();
            $this->_db->rollBack();
        }
        return $ret;
    }
    
    public function update($fields = [], $userid){
        $ret['error'] = false;
        $this->_db->beginTransaction();
        
        $roles = $fields['roles'];
        unset($fields['roles']);

        // Utility::pa($fields);
        // exit;
        
        try{
            if(isset($fields['password'])){
                $sql = "UPDATE `users` 
                        SET `username` = ?, `ba_no` = ?, `firstname` = ?, `lastname` = ?, `rank` = ?, `mobile` = ?, `email` = ?, `is_support_asst` = ?, `status_id` = ?,`updated_at` = ?, `updated_by` = ?, `password` = ?, `salt` = ?, `version` = `version`+1 
                        WHERE `id` = ?";
            } else{
                $sql = "UPDATE `users` 
                        SET `username` = ?, `ba_no` = ?, `firstname` = ?, `lastname` = ?, `rank` = ?, `mobile` = ?, `email` = ?, `is_support_asst` = ?, `status_id` = ?, `updated_at` = ?, `updated_by` = ?, `version` = `version`+1 
                        WHERE `id` = ?";
            }
            $stmt = $this->_db->prepare($sql);
            //$values = $fields;
            $x = 1;
            foreach($fields as $field){
                $stmt->bindValue($x, $field);
                $x++;
            }
            $stmt->bindValue($x, $userid);
            $stmt->execute();
            
            $sql = "DELETE FROM user_role WHERE user_id = ?";
            $stmt = $this->_db->prepare($sql);
            $stmt->bindValue(1, $userid);
            $stmt->execute();
            
            $sql = "INSERT INTO user_role (user_id, role_id) VALUES(?, ?)";
            $stmt = $this->_db->prepare($sql);
            $stmt->bindParam(1, $userid);
            $stmt->bindParam(2, $role);
            foreach($roles as $role){
                $stmt->execute();
            }
            
            $this->_db->commit();
        } catch (Exception $e) {
            $ret['error'] = $e->getMessage();
            $this->_db->rollBack();
        }
        return $ret;
    }
    
    public function find($username = null)
    {
        if($username){
            $field = is_numeric($username) ? 'id' : 'username';
            $sql = "SELECT * FROM users WHERE username = ?";
            $stmt = $this->_db->prepare($sql);
            $stmt->bindParam(1, $username);
            $stmt->execute();
            $result = $stmt->fetchAll();
            return $stmt->rowCount() ? $result[0] : false;
        }
        return false;
    }
    
    public function passwordMatchesInDb($uid, $password){
        $pwHsh = $this->_getPasswordAndSalt($uid);
        if($pwHsh && $pwHsh['password'] === Hash::make($password, $pwHsh['salt'])){
            return true;
        }
        return false;
    }
    
    private function _getPasswordAndSalt($uid)
    {
        $sql = "SELECT `password`, `salt` FROM users WHERE id = ?";
        $stmt = $this->_db->prepare($sql);
        $stmt->bindParam(1, $uid);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $stmt->rowCount() ? $result[0] : false;
    }
    
    public function getSalt($uid)
    {
        $sql = "SELECT `salt` FROM users WHERE id = ?";
        $stmt = $this->_db->prepare($sql);
        $stmt->bindParam(1, $uid);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $stmt->rowCount() ? $result[0]['salt'] : false;
    }
    
    public function updatePassword($password, $uid){
        $sql = "UPDATE users SET `password` = ? WHERE id = ?";
        $stmt = $this->_db->prepare($sql);
        $stmt->bindParam(1, $password);
        $stmt->bindParam(2, $uid);
        $stmt->execute();
    }

    public function login($username = null, $password = null)
    {
        $user = $this->find($username);
        if($user){
            if($user['password'] === Hash::make($password, $user['salt'])){
                $acl = new Roles();
                Session::put('usertype'         , 'system');
                Session::put('uid'              , $user['id']);
                Session::put('username'         , $user['username']);
                Session::put('fullname'         , trim($user['firstname'].' '.$user['lastname']));
                Session::put('user_roles'       , $this->listRoles($user['id']));
                Session::put('acl_pages'        , $acl->listUserPagesByRoles($user['id']));
                
                return true;
            }
        }
        
        return false;
    }
    
    public function listRoles($uid){
        $user_roles = [];
        $sql = "SELECT `role_id` FROM `user_role` WHERE `user_id` = ?";
        $stmt = $this->_db->prepare($sql);
        $stmt->bindParam(1, $uid);
        $stmt->execute();
        $results = $stmt->fetchAll();
        foreach($results as $res){
            $user_roles[] = $res['role_id'];
        }
        return $user_roles;
    }
    
    private function data() {
        return $this->_data;
    }
    
    public function getUserRolesAsString($uid){
        $sql = "SELECT GROUP_CONCAT(r.`name` SEPARATOR ', ') AS roles
                FROM `user_role` ur
                INNER JOIN `roles` r ON r.`id` = ur.role_id
                WHERE ur.user_id = ?";
        $query = $this->_db->prepare($sql);
        $query->bindValue(1, $uid);
        if($query->execute()){
            $results = $query->fetchAll();
            return $results[0]['roles'];
        }
        return '';
    }
    
    public function listRoleIdsByUserId($uid){
        $uroles = [];
        $sql = "SELECT `role_id` FROM `user_role` WHERE `user_id` = ?";
        $query = $this->_db->prepare($sql);
        $query->bindValue(1, $uid);
        if($query->execute()){
            $results = $query->fetchAll();
            foreach($results as $res){ $uroles[] = $res['role_id']; }
        }

        return $uroles;
    }
    
    public function listUserData($uid){
        $data = [ 'username' => '', 'ba_no' => '', 'firstname' => '', 'lastname' => '', 'rank' => '', 'mobile' => '', 'email' => '', 'status' => '', 'is_support_asst' => '', 'uroles' => [] ];
        
        $sql = "SELECT `username`, `ba_no`, `firstname`, `lastname`, `rank`, `mobile`, `email`, `status_id` AS `status`, `is_support_asst`
                FROM `users` WHERE `id` = ? LIMIT 1";
        $query = $this->_db->prepare($sql);
        $query->bindValue(1, $uid);
        if($query->execute()){
            $results = $query->fetchAll(PDO::FETCH_NUM);
            $data = $results[0];
            $data[] = $this->listRoleIdsByUserId($uid);
        }
        
        return $data;
    }
    
    public static function lisActiveUsers(){
        $data = array();
        $sql = "SELECT
                u.`id`
              , u.`firstname`
              , u.`lastname`
              , u.`mobile`
              FROM `users` u
              WHERE u.`status_id` = 1
			  ORDER BY u.`firstname` ASC, u.`lastname` ASC";
        $result = DB::getInstance()->query($sql)->results();
        foreach($result as $res){
            $data[ $res['id'] ] = trim($res['firstname'].' '.$res['lastname']).' ('.$res['mobile'].')';
        }
        return $data;
    }
    
    public static function listAllUsers(){
        $data = array();
        $sql = "SELECT
                u.`id`
              , u.`firstname`
              , u.`lastname`
              , u.`mobile`
              FROM `users` u
			  ORDER BY u.`firstname` ASC, u.`lastname` ASC";
        $result = DB::getInstance()->query($sql)->results();
        foreach($result as $res){
            $data[ $res['id'] ] = trim($res['firstname'].' '.$res['lastname']).' ('.$res['mobile'].')';
        }
        return $data;
    }
}
