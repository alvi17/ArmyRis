<?php

/**
 * Description of Subscriber
 * 
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date October 22, 2016 22:57
 */

use Carbon\Carbon;

class Subscriber {
    private $_db;
    
    public function __construct() {
        $this->_db = DB::connectDb();
    }
    
    public static function getStatus($statusId){
        $status = false;
        switch ($statusId){
            case 0: $status = 'Suspended'; break;
            case 1: $status = 'Active'; break;
            case 2: $status = 'Deleted'; break;
            //default: $status = 'Undefined'; break;
        }
        return $status;
    }
    
    public static function listSubscriberStatuses(){
        return [
                1 => 'Active',
                0 => 'Suspended',
                2 => 'Deleted',
            ];
    }
    
    public static function listSubscriberCategories(){
        return ['Paid', 'Complementary', 'Free'];
    }
    
    public static function getSubscriberIdByUsername($username){
        $sql = "SELECT id_subscriber_key FROM subscribers WHERE username = '{$username}'";
        $res = DB::getInstance()->query($sql)->first();
        return  isset($res['id_subscriber_key']) ? $res['id_subscriber_key'] : '';
    }

    public function getSubscriberDetials($subscriber_id){
        $data = [];
        
        $sql = "SELECT
                s.`username`
              , s.`password`
              , s.`login_credential_version`
              , s.`ba_no`
              , s.`firstname`
              , s.`lastname`
              , s.`rank_id`
              , r.`name` AS `rank`
              , s.`area_id`
              , a.`area_name` AS `area`
              , s.`area_version`
              , s.`building_id`
              , b.`building_name` AS `building`
              , b.`router_no`
              , s.`house_no`
              , s.`remote_ip`
              , s.`official_mobile`
              , s.`personal_mobile`
              , s.`residential_phone`
              , s.`email`
              , s.`package_id`
              , p.`code` as `package_code`
              , p.`name` AS `package`
              , s.`package_version`
              , s.`category`
              , s.`complementary_amount`
              , s.`category_version`
              , s.`status_id`
              , s.`connection_from`
              , s.`connection_to`
              , s.`status_version`
              , s.`payment_balance`
              , s.`payment_version`
              , s.`remarks`
              FROM subscribers s
              INNER JOIN ranks r ON r.`id` = s.`rank_id`
              LEFT JOIN `areas` a ON a.`id_area` = s.`area_id`
              LEFT JOIN `buildings` b ON b.`id_building` = s.`building_id`
              LEFT JOIN packages p ON p.`id` = s.`package_id`
              WHERE s.`id_subscriber_key` = ?
              AND s.`subs_type` = 'default'";

        $result = DB::getInstance()->query($sql, [$subscriber_id]);
        if($result->count()){
            $data = $result->first();
        }
        
        return $data;
    }

    public static function calcInternectCheckinDuration($status, $disconnect_date, $duration_days){
        
        $data = ['connection_from'=>'', 'connection_to'=>''];
        
        $now = Carbon::now();
        $today_connect_schedule = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d 12:00:00'));
        
        /*
        if($status==0){
            $data['connection_from'] = $now;
        }elseif($status==1){
            $disconnect_date = !empty($disconnect_date) 
                    ? Carbon::createFromFormat('Y-m-d H:i:s', $disconnect_date)
                    : $now;
            //$data['connection_from'] = $disconnect_date->gt($today_connect_schedule) ? $disconnect_date : $today_connect_schedule;
            $data['connection_from'] = $disconnect_date;
        }
        */
        $date1 = new \DateTime($disconnect_date);
        $date2 = new \DateTime($now);
        $data['connection_from'] =  !empty($disconnect_date) && $date1 > $date2
                                    ? Carbon::createFromFormat('Y-m-d H:i:s', $disconnect_date)
                                    : $now;
        
        $data['connection_to'] = clone $data['connection_from'];
        $data['connection_to']->addDays(($duration_days))
                              //->subMinute(1)
                            ;
        
        $data['connection_from'] = $data['connection_from']->toDateTimeString();
        $data['connection_to'] = date("Y-m-d H:i:s", strtotime($data['connection_to']->toDateTimeString()));
        $compare_date = date("Y-m-d 11:59:59", strtotime($data['connection_to']));
        
        $date1 = new \DateTime($data['connection_to']);
        $date2 = new \DateTime($compare_date);
        $data['connection_to'] = $date1 > $date2 
                                ? date('Y-m-d 11:59:59', strtotime('+1 day', strtotime($compare_date)))
                                : $compare_date;
        
        return $data;
    }

    public static function internetCheckinBtnText($data, $connectivity){
        $prefix = '';
        
        if($data['payment_balance'] < $data['package_price']){
            $btn = '<span class="label label-danger">No sufficient balance available for new/extend internet connection.</span>';
        } else{
            if($data['status_id']==1){$prefix = 'Extend ';}
            elseif($data['status_id']==0){$prefix = 'Enable ';}
            //$connectivity = self::calcInternectCheckinDuration($data['status_id'], $data['disconnect_date'], $data['package_days']);
            $from = Date::niceDateTime2($connectivity['connection_from']);
            $to = Date::niceDateTime2($connectivity['connection_to']);
            $txt = "Click here to {$prefix}Internet Connection from {$from} to {$to}";
            $btn = '<input type="submit" value="'.$txt.'" class="btn btn-primary">';
        }
        return $btn;
    }


    public function login($username = null, $password = null) {
        $subscriber = $this->find($username, $password);
        
        if($subscriber){
            Session::put('usertype'         , 'subscriber');
            Session::put('uid'              , $subscriber['id']);
            Session::put('username'         , $subscriber['username']);
            Session::put('fullname'         , trim($subscriber['firstname'].' '.$subscriber['lastname']));
            Session::put('balance'          , $subscriber['payment_balance']);
            Session::put('category'         , $subscriber['category']);
            Session::put('router_no'        , $subscriber['router_no']);

            return true;
        }
        
        return false;
    }
    
    public function getSubscriberInformation($id){
        $sql = "SELECT
                s.`username`
              , s.`ba_no`
              , s.`firstname`
              , s.`lastname`
              , r.`name` AS `rank`
              , s.`official_mobile`
              , s.`email`
              , s.`payment_balance`
              , s.`connection_from`
              , s.`connection_to`
              , s.`house_no`
              , b.`building_name` AS `building`
              , a.`area_name` AS `area`
              , p.`name` AS `package`
              , CASE s.`status_id`
                  WHEN 1 THEN 'Active'
                  WHEN 0 THEN 'Suspended'
                  WHEN 2 THEN 'Deleted'
                END AS `status`
              , s.`category`
              , s.`complementary_amount`
              FROM `subscribers` s
              LEFT JOIN ranks r ON r.id = s.`rank_id`
              LEFT JOIN `areas` a ON a.`id_area` = s.`area_id`
              LEFT JOIN `buildings` b ON b.`id_building` = s.`building_id`
              LEFT JOIN packages p ON p.`id` = s.`package_id`
              WHERE s.`id_subscriber_key` = ?";
        $stmt = $this->_db->prepare($sql);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $stmt->rowCount() ? $result[0] : [];
    }
    
    public function listDisconnectableSubscribers($from, $to, $string, $rank, $area, $building, $is_total = false, $page=1, $limit = LIMIT_PER_PAGE) {
        $whereCond = '';
        $whereVal = [];
        
        if(!empty($string)){
            $whereCond .= " AND (s.`username` LIKE '%".$string."%' OR s.`firstname` LIKE '%".$string."%' OR s.`lastname` LIKE '%".$string."%')";
            $whereVal[] = $string;
        }
        if(!empty($rank)){
            $whereCond .= " AND s.`rank_id` = ".$rank;
            $whereVal[] = $rank;
        }
        if(!empty($area)){
            $whereCond .= " AND s.`area_id` = ".$area;
            $whereVal[] = $area;
        }
        if(!empty($building)){
            $whereCond .= " AND s.`building_id` = ".$building;
            $whereVal[] = $building;
        }
        
        $whereCond .= " AND s.`connection_to` BETWEEN '{$from}' AND '{$to}'";
        
        if($is_total){
            $fields = "COUNT(1) AS TOTAL";
            $orderLimitStr = "";
        } else{
            $fields = "
                  s.`username`
                , s.`firstname`
                , s.`lastname`
                , r.`name` as `rank`
                , s.`official_mobile`
                , s.`payment_balance`
                , s.`connection_to`
                , s.`house_no`
                , b.`building_name` AS `building`
                , a.`area_name` AS `area`
                , s.`package_id`
                , s.`connection_to` AS `disconnect_at`
                , s.`payment_balance` AS `balance`";
            
            $orderLimitStr = " ORDER BY r.`order` ASC, s.`connection_to` ASC
            LIMIT ".($page-1)*$limit.", {$limit}";
        }
        
        $sql = "SELECT ".$fields."
            FROM `subscribers` s
            LEFT JOIN `ranks` r on r.`id` = s.`rank_id`
            LEFT JOIN `areas` a ON a.`id_area` = s.`area_id`
            LEFT JOIN `buildings` b ON b.`id_building` = s.`building_id`
            WHERE s.`status_id` = 1 ".$whereCond.$orderLimitStr;
        
        //echo '<pre>'; echo $sql; echo '</pre>'; exit;
        $stmt = $this->_db->prepare($sql);
        
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $stmt->rowCount() ? $result : [];
    }
    
    public function listSubscribers($ba='', $string='', $rank='', $area='', $building='', $house='', $category='', $package=0, $status=-1, $is_total = false, $page=1, $limit = LIMIT_PER_PAGE) 
    {
        $whereCond = '';
        $whereVal = [];
        
        if(!empty($ba)){
            $whereCond .= " AND (s.`username` like '%".$ba."%' OR s.`ba_no` like '%".$ba."%')";
            $whereVal[] = $string;
        }
        if(!empty($string)){
            $whereCond .= " AND (s.`firstname` like '%".$string."%' OR s.`lastname` like '%".$string."%' OR s.`official_mobile` like '%".$string."%')";
            $whereVal[] = $string;
        }
        if(!empty($rank)){
            $whereCond .= " AND s.`rank_id` = ".$rank;
            $whereVal[] = $rank;
        }
        if(!empty($area)){
            $whereCond .= " AND s.`area_id` = ".$area;
            $whereVal[] = $area;
        }
        if(!empty($building)){
            $whereCond .= " AND s.`building_id` = ".$building;
            $whereVal[] = $building;
        }
        if(!empty($house)){
            $whereCond .= " AND s.`house_no` LIKE '%".$house."%'";
            $whereVal[] = $house;
        }
        if(!empty($category)){
            $whereCond .= " AND s.`category` = '".$category."'";
            $whereVal[] = $category;
        }
        if(!empty($package)){
            $whereCond .= " AND s.`package_id` = '".$package."'";
            $whereVal[] = $package;
        }
        
        if($status > -1){
            $whereCond .= " AND s.`status_id` = {$status}";
            $whereVal[] = $status;
        } else {
            // Exclude Deleted Subscribers
            $whereCond .= " AND s.`status_id` <> 2";
        }
        

        if($is_total){
            $fields = "COUNT(1) AS TOTAL";
            $orderLimitStr = "";
        } else{
            $fields = "
                  s.`id_subscriber_key` AS `id`
				, b.`router_no`
                , s.`username`
                , s.`ba_no`
                , s.`firstname`
                , s.`lastname`
                , r.`name` as `rank`
                , s.`official_mobile`
                , s.`payment_balance`
                , s.`connection_to`
                , s.`house_no`
                , b.`building_name` AS `building`
                , a.`area_name` AS `area`
                , s.`package_id`
                , s.`status_id`
                , s.`category`
                , s.`complementary_amount`";
            
            $orderLimitStr = " ORDER BY r.`order` ASC, s.`firstname` ASC
            LIMIT ".($page-1)*$limit.", {$limit}";
        }
        
        $sql = "SELECT ".$fields."
            FROM `subscribers` s
            LEFT JOIN `ranks` r on r.`id` = s.`rank_id`
            LEFT JOIN `areas` a ON a.`id_area` = s.`area_id`
            LEFT JOIN `buildings` b ON b.`id_building` = s.`building_id`
            WHERE s.`subs_type` = 'default' ".$whereCond.$orderLimitStr;

        $stmt = $this->_db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        
        return $stmt->rowCount() ? $result : [];
    }
    
    public function internetCheckinParams($subsId){
        $sql = "SELECT
                  s.`payment_balance`
                , s.`status_id`
                , DATE_ADD(s.`connection_to`, INTERVAL 1 MINUTE) AS disconnect_date
                , s.`package_id`
                , p.`code` AS `package_code`
                , p.`name` AS `package_name`
                , p.`price` AS `package_price`
                , p.`days` AS `package_days`
                , s.`router_no`
                , s.`category`
                , s.`complementary_amount`
                FROM `subscribers` s
                INNER JOIN packages p ON p.`id` = s.`package_id`
                WHERE s.`id_subscriber_key` = ?";
        $stmt = $this->_db->prepare($sql);
        $stmt->bindParam(1, $subsId);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $stmt->rowCount() ? $result[0] : false;
    }
    
    public function find($username = null, $password = null) {
        if($username && $password){
            $sql = "SELECT
                      s.`id_subscriber_key`     AS `id`
                    , s.`username`
                    , s.`ba_no`
                    , s.`firstname`
                    , s.`lastname`
                    , s.`rank_id`
                    , r.`name`                  AS `rank`
                    , s.`official_mobile`
                    , s.`personal_mobile`
                    , s.`residential_phone`
                    , s.`email`
                    , s.`payment_balance`
                    , s.`connection_to`
                    , s.`house_no`
                    , s.`area_id`
                    , s.`package_id`
                    , p.`name` AS `package_name`
                    , s.`status_id`
                    , s.`category`
                    , s.`complementary_amount`
                    , s.`router_no`
                FROM `subscribers` s
                INNER JOIN `ranks` r ON r.`id` = s.`rank_id`
                INNER JOIN `packages` p ON p.`id` = s.`package_id`
                WHERE s.username = ? AND s.`password` = ? 
                AND (s.`status_id` = 0 OR s.`status_id` = 1)";
            $stmt = $this->_db->prepare($sql);
            $stmt->bindParam(1, $username);
            $stmt->bindParam(2, $password);
            $stmt->execute();
            $result = $stmt->fetchAll();
            return $stmt->rowCount() ? $result[0] : false;
        }
        return false;
    }
    
    public function getRouterNoBySubscriberId($subscriber_id){
        $sql ="SELECT b.`router_no`
                FROM `buildings` b
                INNER JOIN `subscribers` s ON s.`building_id` = b.`id_building`
                WHERE s.`id_subscriber_key` = ?";
        $stmt = $this->_db->prepare($sql);
        $stmt->bindParam(1, $subscriber_id);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $stmt->rowCount() ? $result[0] : false;
    }
    
    public function passwordMatchesInDb($uid, $password){
        $sql = "SELECT 1 FROM `subscribers` WHERE `id_subscriber_key` = ? AND `password` = ?";
        $stmt = $this->_db->prepare($sql);
        $stmt->bindParam(1, $uid);
        $stmt->bindParam(2, $password);
        $stmt->execute();
        return $stmt->rowCount() ? true : false;
    }
    
    public function updatePasswordBySubscriber($password, $uid){
        global $mikrotik_routers;
        
        $sql = "UPDATE `subscribers` SET `password` = ? WHERE `id_subscriber_key` = ?";
        $stmt = $this->_db->prepare($sql);
        $stmt->bindParam(1, $password);
        $stmt->bindParam(2, $uid);
        if($stmt->execute()){
            ## UPDATE MIKROTIK PASSWORD
            //$router = $mikrotik_routers[Session::get('router_no')];
            //$mikrotik = new PppoeApiService($router['router_ip'], $router['username'], $router['password']);
            //$mikrotik->changePassword(Session::get('username'), $password);
            return true;
        }
        return false;
    }
        
    public function create($fields = []){
        
        global $mikrotik_routers;
        $ret['error'] = false;
        $comment = 'New Subscriber Registration';
        
        $this->_db->beginTransaction();
        try{
            ## 1. SUBSCRIBER
            $subsData = [
                'username'              => $fields['username'],
                'password'              => $fields['password'],
                'ba_no'                 => $fields['ba_no'],
                'firstname'             => $fields['firstname'],
                'lastname'              => $fields['lastname'],
                'rank_id'               => $fields['rank_id'],
                'official_mobile'       => $fields['official_mobile'],
                'personal_mobile'       => $fields['personal_mobile'],
                'residential_phone'     => $fields['residential_phone'],
                'email'                 => $fields['email'],
                'house_no'              => $fields['house_no'],
                'area_id'               => $fields['area_id'],
                'building_id'           => $fields['building_id'],
                'package_id'            => $fields['package_id'],
                'status_id'             => $fields['status_id'],
                'category'              => $fields['category'],
                'complementary_amount'  => $fields['complementary_amount'],
                'local_ip'              => $fields['local_ip'],
                'remote_ip'             => $fields['remote_ip'],
                'created_at'            => $fields['created_at'],
                'created_by'            => $fields['created_by'],
                'router_no'             => $fields['router_no'],
                'status_id'             => $fields['status_id'],
                'remarks'               => $fields['remarks'],
            ];
            $subsId = $this->insert('subscribers', $subsData, true);
            
            # 2. subscribers_login_credentials_audit
            $this->insert('subscribers_login_credentials_audit', [
                'subscriber_id' => $subsId,
                'username'      => $fields['username'],
                'password'      => $fields['password'],
                'comment'       => $comment,
                'version'       => 1,
                'dtt_mod'       => $fields['created_at'],
                'uid_mod'       => $fields['created_by'],
            ]);
            # 3. AREA
            $this->insert('subscribers_areas_audit', [
                'subscriber_id' => $subsId,
                'router_no'     => $fields['router_no'],
                'area_id'       => $fields['area_id'],
                'building_id'   => $fields['building_id'],
                'house_no'      => $fields['house_no'],
                'local_ip'      => $fields['local_ip'],
                'remote_ip'     => $fields['remote_ip'],
                'comment'       => $comment,
                'version'       => 1,
                'dtt_mod'       => $fields['created_at'],
                'uid_mod'       => $fields['created_by'],
            ]);
                       
            # 4. PACKAGE
            $this->insert('subscribers_packages_audit', [
                'subscriber_id'  => $subsId,
                'package_id'    => $fields['package_id'],
                'comment'       => $comment,
                'version'       => 1,
                'dtt_mod'       => $fields['created_at'],
                'uid_mod'       => $fields['created_by'],
            ]);
            
            # 5. CATEGORY
            $this->insert('subscribers_categories_audit', [
                'subscriber_id'         => $subsId,
                'category'              => $fields['category'],
                'complementary_amount'  => $fields['complementary_amount'],
                'comment'               => $comment,
                'version'               => 1,
                'dtt_mod'               => $fields['created_at'],
                'uid_mod'               => $fields['created_by'],
            ]);
            
            #6. CREATE MIKROTIK USER
            $router = $mikrotik_routers[ $fields['router_no'] ];
            $mikrotik = new PppoeApiService($router['router_ip'], $router['username'], $router['password']);
            $profile = $fields['package_code'];
            $mikUsrCrt = $mikrotik->createUser($fields['username'], $fields['password'], $fields['local_ip'], $fields['remote_ip'], $profile);
            $this->saveMikrotikLog($subsId, $fields['username'], $fields['password'], $fields['local_ip'], $fields['remote_ip'], $router['router_ip'], $profile, 'disabled', $comment, 1, $fields['created_at'], $fields['created_by'], $mikUsrCrt);
            
            $this->updIpStatus(1, $fields['remote_ip'], $uid, $fields['created_at'], $fields['created_by']);
            
            $this->_db->commit();
            
        } catch (Exception $ex) {
            $ret['error'] = $ex->getMessage();
            $this->_db->rollBack();
        }
        return $ret;
    }
    
    public function edit($id, $fields, $old_data){
        
        global $mikrotik_routers;
        $ret['error'] = false;
        
        $this->_db->beginTransaction();
        try{
            if($fields['username'] != $old_data['username'] && $fields['password'] != $old_data['password']){
                //$version = $old_data['login_credential_version']+1;
                $data = [
                    'subscriber_id' => $id,
                    'username'      => $fields['username'],
                    'password'      => $fields['password'],
                    'router_no'     => $fields['router_no'],
                    'local_ip'      => $fields['local_ip'],
                    'remote_ip'     => $fields['remote_ip'],
                    'profile'       => $fields['package_code'],
                    'disabled'      => $fields['status_id']==1 ? false : true,
                    'version'       => $old_data['login_credential_version']+1,
                    'now'           => $fields['now'],
                    'uid'           => $fields['uid'],
                    'utype'         => $fields['utype'],
                ];
                
                $username_editted = $this->editUsernameAndPassword($data, $old_data['username'], 'Username and Password Changed');
                if(!$username_editted){
                    throw new Exception('Username and Password modification failed');
                }
            } else{
                if($fields['username'] != $old_data['username']){
                    //$version = $old_data['login_credential_version']+1;
                    $data = [
                        'subscriber_id' => $id,
                        'username'      => $fields['username'],
                        'password'      => $fields['password'],
                        'router_no'     => $fields['router_no'],
                        'local_ip'      => $fields['local_ip'],
                        'remote_ip'     => $fields['remote_ip'],
                        'profile'       => $fields['package_code'],
                        'disabled'      => $fields['status_id']==1 ? false : true,
                        'version'       => $old_data['login_credential_version']+1,
                        'now'           => $fields['now'],
                        'uid'           => $fields['uid'],
                        'utype'         => $fields['utype'],
                    ];
                    $username_editted = $this->editUsername($data, $old_data['username'], 'Username Changed');
                    if(!$username_editted){
                        throw new Exception('Username modification failed');
                    }
                }
                if($fields['password'] != $old_data['password']){
                    //$version = $old_data['login_credential_version']+1;
                    $data = [
                        'subscriber_id' => $id,
                        'username'      => $fields['username'],
                        'password'      => $fields['password'],
                        'router_no'     => $fields['router_no'],
                        'local_ip'      => $fields['local_ip'],
                        'remote_ip'     => $fields['remote_ip'],
                        'profile'       => $fields['package_code'],
                        'disabled'      => $fields['status_id']==1 ? false : true,
                        'version'       => $old_data['login_credential_version']+1,
                        'now'           => $fields['now'],
                        'uid'           => $fields['uid'],
                        'utype'         => $fields['utype'],
                    ];
                    $password_edited = $this->editPassword($data, 'Password Changed');
                    if(!$password_edited){
                        throw new Exception('Password modification failed');
                    }
                }
            }
            
            if($fields['area_id'] != $old_data['area_id'] 
            || $fields['building_id'] != $old_data['building_id']
            || $fields['remote_ip'] != $old_data['remote_ip']){
                $data = [
                    'subscriber_id' => $id,
                    'username'      => $fields['username'],
                    'password'      => $fields['password'],
                    'router_no'     => $fields['router_no'],
                    'router_no_old' => $old_data['router_no'],
                    'area_id'       => $fields['area_id'],
                    'building_id'   => $fields['building_id'],
                    'house_no'      => $fields['house_no'],
                    'local_ip'      => $fields['local_ip'],
                    'remote_ip'     => $fields['remote_ip'],
                    'remote_ip_old' => $old_data['remote_ip'],
                    'profile'       => $fields['package_code'],
                    'disabled'      => $fields['status_id']==1 ? false : true,
                    'version'       => ($old_data['area_version']+1),
                    'now'           => $fields['now'],
                    'uid'           => $fields['uid'],
                    'utype'         => $fields['utype'],
                ];
                $address_editted = $this->editAddress($data, 'Address Changed');
                if(!$address_editted){
                    throw new Exception('Address modification failed');
                }
            }
            
            if($fields['status_id'] != $old_data['status_id']){
                $data = [
                    'subscriber_id'     => $id,
                    'username'         => $fields['username'],
                    'status_id'         => $fields['status_id'],
                    'package_id'        => $fields['package_id'],
                    'version'           => ($old_data['status_version']+1),
                    'router_no'         => $fields['router_no'],
                    'connection_from'   => $fields['connection_from'],
                    'connection_to'     => $fields['connection_to'],
                    'now'               => $fields['now'],
                    'uid'               => $fields['uid'],
                    'utype'             => $fields['utype'],
                ];
                $status_editted = $this->editStatus($data, 'Status Changed');
                if(!$status_editted){
                    throw new Exception('Status modification failed');
                }
            }
            elseif(
                isset($fields['connection_from']) && isset($fields['connection_to']) &&
                ($fields['connection_from']!=$old_data['connection_from'] || $fields['connection_to']!=$old_data['connection_to'])
            ){
                $data = [
                    'subscriber_id'     => $id,
                    'status_id'         => $fields['status_id'],
                    'package_id'        => $fields['package_id'],
                    'version'           => ($old_data['status_version']+1),
                    'router_no'         => $fields['router_no'],
                    'connection_from'   => $fields['connection_from'],
                    'connection_to'     => $fields['connection_to'],
                    'now'               => $fields['now'],
                    'uid'               => $fields['uid'],
                    'utype'             => $fields['utype'],
                ];
                $status_editted = $this->editConnectivity($data, 'Connectivity Changed');
                if(!$status_editted){
                    throw new Exception('Connectivity modification failed');
                }
            }
            
            if($fields['package_code'] != $old_data['package_code']){
                $data = [
                    'subscriber_id'         => $id,
                    'username'         		=> $fields['username'],
                    'package_id'            => $fields['package_id'],
                    'profile'               => $fields['package_code'],
                    'router_no'             => $fields['router_no'],
                    'version'               => ($old_data['package_version']+1),
                    'now'                   => $fields['now'],
                    'uid'                   => $fields['uid'],
                    'utype'                 => $fields['utype'],
                ];
                $category_editted = $this->editPackage($data, 'Package Changed');
                if(!$category_editted){
                    throw new Exception('Package modification failed');
                }
            }
            
            if($fields['category'] != $old_data['category']){
                $data = [
                    'subscriber_id'         => $id,
                    'category'              => $fields['category'],
                    'complementary_amount'  => $fields['complementary_amount'],
                    'version'               => ($old_data['category_version']+1),
                    'now'                   => $fields['now'],
                    'uid'                   => $fields['uid'],
                    'utype'                 => $fields['utype'],
                ];
                $category_editted = $this->editCategory($data, 'Category Changed');
                if(!$category_editted){
                    throw new Exception('Category modification failed');
                }
            }
            
            if(in_array(1, Session::get('user_roles'))){
                if($fields['payment_balance'] != $old_data['payment_balance']){
                    $data = [
                        'subscriber_id'         => $id,
                        'payment_balance'       => $fields['payment_balance'],
                        'version'               => ($old_data['payment_version']+1),
                        'now'                   => $fields['now'],
                        'uid'                   => $fields['uid'],
                        'utype'                 => $fields['utype'],
                    ];
                    $balance_editted = $this->editBalance($data, 'Balance Adjusted by Admin');
                    if(!$balance_editted){
                        throw new Exception('Balance modification failed');
                    }
                }
            }
            
            $this->editOtherFields($id, $fields, $old_data);
            
            $this->_db->commit();
            
        } catch (Exception $ex) {
            $ret['error'] = $ex->getMessage();
            Utility::pa($ret['error']); exit;
            $this->_db->rollBack();
        }
        
        return $ret;
    }

    public function updIpStatus($occupy_status, $ip, $subscriber_id, $time, $by){
        if($occupy_status==1){ // OCCUPY IP for a subscriber
            $sql = "UPDATE `ip_addresses` SET `status_id`=?, `occupied_subscriber_id`=?, `updated_at`=?, `updated_by`=?, `version` = `version`+1 WHERE `ip` = ?";
            $stmt = $this->_db->prepare($sql);
            $stmt->execute([$occupy_status, $subscriber_id, $time, $by, $ip]);
        } else{ // FREE IP for future usage
            
        }
    }
    
    public function editUsernameAndPassword($data, $username_old, $comment){
        global $mikrotik_routers;
        $retval = false;
        
        $router = $mikrotik_routers[$data['router_no']];
        $mikrotik = new PppoeApiService($router['router_ip'], $router['username'], $router['password']);
        
		/* 
		## EDIT MIKROTIK PASSWORD HAS BEEN BLOCKED AS REUESTED BY ENGR. RABIUL
        # DELETE MIKROTIK USER
        $mikrotik->deleteUser($username_old);        
        # CREATE NEW MIKROTIK USER
        $mikUsrCrt = $mikrotik->createUser($data['username'], $data['password'], $data['local_ip'], $data['remote_ip'], $data['profile'], $data['disabled']);
		*/
        
		# INSERT subscribers_login_credentials_audit TABLE
        $this->insert('subscribers_login_credentials_audit', [
            'subscriber_id' => $data['subscriber_id'],
            'username'      => $data['username'],
            'password'      => $data['password'],
            'comment'       => $comment,
            'version'       => $data['version'],
            'dtt_mod'       => $data['now'],
            'uid_mod'       => $data['uid'],
            'user_type'     => $data['utype'],
        ]);
        # UPDATE subscribers TABLE
        $retval = $this->update('subscribers', [
            'username'                  => $data['username'],
            'password'                  => $data['password'],
            'login_credential_version'  => $data['version'],
            'updated_at'                => $data['now'],
            'updated_by'                => $data['uid'],
            'updated_user_type'         => $data['utype'],
        ], 'id_subscriber_key', $data['subscriber_id']);
        
        return $retval;
    }
    
    public function editUsername($data, $username_old, $comment){
        global $mikrotik_routers;
        $retval = false;
        
        $router = $mikrotik_routers[$data['router_no']];
        $mikrotik = new PppoeApiService($router['router_ip'], $router['username'], $router['password']);
        
        # DELETE MIKROTIK USER
        $mikrotik->deleteUser($username_old);
        
        # CREATE NEW MIKROTIK USER
        $mikUsrCrt = $mikrotik->createUser($data['username'], $data['password'], $data['local_ip'], $data['remote_ip'], $data['profile'], $data['disabled']);
//        $this->saveMikrotikLog($subscriber_id, $data['username'], $data['password'], $data['local_ip'], $data['remote_ip'], 
//                            $router['router_ip'], $data['profile'], ($data['disabled']?'disabled':'enabled'), $comment, $version, 
//                            $created_at, $created_by, $mikUsrCrt, $utype);

        # INSERT subscribers_login_credentials_audit TABLE
        $this->insert('subscribers_login_credentials_audit', [
            'subscriber_id' => $data['subscriber_id'],
            'username'      => $data['username'],
            'password'      => $data['password'],
            'comment'       => $comment,
            'version'       => $data['version'],
            'dtt_mod'       => $data['now'],
            'uid_mod'       => $data['uid'],
            'user_type'     => $data['utype'],
        ]);
        
        # UPDATE subscribers TABLE
        $retval = $this->update('subscribers', [
            'username'                  => $data['username'],
            'password'                  => $data['password'],
            'login_credential_version'  => $data['version'],
            'updated_at'                => $data['now'],
            'updated_by'                => $data['uid'],
            'updated_user_type'         => $data['utype'],
        ], 'id_subscriber_key', $data['subscriber_id']);
        
        return $retval;
    }
    
    public function editPassword($data, $comment){
        global $mikrotik_routers;
        $retval = false;
		
        /*
		## EDIT MIKROTIK PASSWORD HAS BEEN BLOCKED AS REUESTED BY ENGR. RABIUL
        $router = $mikrotik_routers[$data['router_no']];
        $mikrotik = new PppoeApiService($router['router_ip'], $router['username'], $router['password']);
        
        # UPDATE MIKROTIK PASSWORD
        //$mikrotik->changePassword($data['username'], $data['password']);
		*/
        
        # INSERT subscribers_login_credentials_audit TABLE
        $this->insert('subscribers_login_credentials_audit', [
            'subscriber_id' => $data['subscriber_id'],
            'username'      => $data['username'],
            'password'      => $data['password'],
            'comment'       => $comment,
            'version'       => $data['version'],
            'dtt_mod'       => $data['now'],
            'uid_mod'       => $data['uid'],
            'user_type'     => $data['utype'],
        ]);
        
        # UPDATE subscribers TABLE
        $retval = $this->update('subscribers', [
            'username'                  => $data['username'],
            'password'                  => $data['password'],
            'login_credential_version'  => $data['version'],
            'updated_at'                => $data['now'],
            'updated_by'                => $data['uid'],
            'updated_user_type'         => $data['utype'],
        ], 'id_subscriber_key', $data['subscriber_id']);
        
        return $retval;
    }
    
    public function editAddress($data, $comment){
        global $mikrotik_routers;
        $retval = false;
        
        # DELETE MIKROTIK USER
        $router = $mikrotik_routers[$data['router_no_old']];
        $mikrotik = new PppoeApiService($router['router_ip'], $router['username'], $router['password']);
        $mikrotik->removeUserFromActive($data['username']);
        $mikrotik->deleteUser($data['username']);
        unset($mikrotik);
        
        # CREATE NEW MIKROTIK USER
        $router = $mikrotik_routers[$data['router_no']];
        $mikrotik = new PppoeApiService($router['router_ip'], $router['username'], $router['password']);
        $mikUsrCrt = $mikrotik->createUser($data['username'], $data['password'], $data['local_ip'], $data['remote_ip'], $data['profile'], $data['disabled']);
        
        # FREE OLD REMOTE IP
        $sql = "UPDATE `ip_addresses`
                SET `status_id` = 0
                , `version` = `version`+1
                , `updated_at` = '".$data['now']."'
                , `updated_by` = '".$data['uid']."'
                WHERE `ip` = '".$data['remote_ip_old']."'";
        $query = $this->_db->prepare($sql);
        $query->execute();
        
        # ASSIGN NEW REMOTE IP
        $sql = "UPDATE `ip_addresses`
                SET `status_id` = 1
                , `version` = `version`+1
                , `updated_at` = '".$data['now']."'
                , `updated_by` = '".$data['uid']."'
                WHERE `ip` = '".$data['remote_ip']."'";
        $query = $this->_db->prepare($sql);
        $query->execute();
        
        # INSERT subscribers_areas_audit TABLE
        $this->insert('subscribers_areas_audit', [
            'subscriber_id' => $data['subscriber_id'],
            'router_no'     => $data['router_no'],
            'area_id'       => $data['area_id'],
            'building_id'   => $data['building_id'],
            'house_no'      => $data['house_no'],
            'local_ip'      => $data['local_ip'],
            'remote_ip'     => $data['remote_ip'],
            'comment'       => $comment,
            'version'       => $data['version'],
            'dtt_mod'       => $data['now'],
            'uid_mod'       => $data['uid'],
            'user_type'     => $data['utype'],
        ]);
        
        # UPDATE subscribers TABLE
        $retval = $this->update('subscribers', [
            'router_no'         => $data['router_no'],
            'area_id'           => $data['area_id'],
            'building_id'       => $data['building_id'],
            'house_no'          => $data['house_no'],
            'local_ip'          => $data['local_ip'],
            'remote_ip'         => $data['remote_ip'],
            'area_version'      => $data['version'],
            'updated_at'        => $data['now'],
            'updated_by'        => $data['uid'],
            'updated_user_type' => $data['utype'],
        ], 'id_subscriber_key', $data['subscriber_id']);
        
        return $retval;
    }
    
    public function editStatus($data, $comment){
        global $mikrotik_routers;
        $retval = false;
        

        $router = $mikrotik_routers[$data['router_no']];
        $mikrotik = new PppoeApiService($router['router_ip'], $router['username'], $router['password']);

        switch($data['status_id']){
            case 1:
                $comment = 'Net Connection Enabled';
                $mikrotik->enableUser($data['username']);
                $package = $this->getPackageBySubscriberId($data['subscriber_id']);
                $mikrotik->changeProfile($data['username'], $package);
                $mikrotik->removeUserFromActive($data['username']);
                break;
            case 0:
                $comment = 'Net Connection Disabled';
                $mikrotik->enableUser($data['username']);
                $mikrotik->changeProfile($data['username'], 'default');
                $mikrotik->removeUserFromActive($data['username']);
                break;
            case 2:
                $comment = 'Net Connection Deleted';
                $mikrotik->deleteUser($data['username']);
                break;
        }
        
        # INSERT subscribers_connections_audit TABLE
        $this->insert('subscribers_connections_audit', [
            'subscriber_id'     => $data['subscriber_id'],
            'status_id'         => $data['status_id'],
            'package_id'        => $data['package_id'],
            'connection_from'   => $data['connection_from'],
            'connection_to'     => $data['connection_to'],
            'comment'           => $comment,
            'version'           => $data['version'],
            'created_at'        => $data['now'],
            'created_by'        => $data['uid'],
            'created_user_type' => $data['utype'],
        ]);
        
        # UPDATE subscribers TABLE
        $retval = $this->update('subscribers', [
            'status_id'         => $data['status_id'],
            'package_id'        => $data['package_id'],
            'connection_from'   => $data['connection_from'],
            'connection_to'     => $data['connection_to'],
            'status_version'    => $data['version'],
            'updated_at'        => $data['now'],
            'updated_by'        => $data['uid'],
            'updated_user_type' => $data['utype'],
        ], 'id_subscriber_key', $data['subscriber_id']);
        
        return $retval;
    }
    
    public function editConnectivity($data, $comment){
        $retval = false;
        
        # INSERT subscribers_connections_audit TABLE
        $this->insert('subscribers_connections_audit', [
            'subscriber_id'     => $data['subscriber_id'],
            'status_id'         => $data['status_id'],
            'package_id'        => $data['package_id'],
            'connection_from'   => $data['connection_from'],
            'connection_to'     => $data['connection_to'],
            'comment'           => $comment,
            'version'           => $data['version'],
            'created_at'        => $data['now'],
            'created_by'        => $data['uid'],
            'created_user_type' => $data['utype'],
        ]);
        
        # UPDATE subscribers TABLE
        $retval = $this->update('subscribers', [
            'status_id'         => $data['status_id'],
            'package_id'        => $data['package_id'],
            'connection_from'   => $data['connection_from'],
            'connection_to'     => $data['connection_to'],
            'status_version'    => $data['version'],
            'updated_at'        => $data['now'],
            'updated_by'        => $data['uid'],
            'updated_user_type' => $data['utype'],
        ], 'id_subscriber_key', $data['subscriber_id']);
        
        return $retval;
    }
    
    public function editPackage($data, $comment){
        global $mikrotik_routers;
        $retval = false;
        
        $router = $mikrotik_routers[$data['router_no']];
        $mikrotik = new PppoeApiService($router['router_ip'], $router['username'], $router['password']);
        
        # UPDATE SUBSCRIBER'S PROFILE IN MIKROTIK
        $mikrotik->changeProfile($data['username'], $data['profile']);
        
        # INSERT subscribers_packages_audit TABLE
        $this->insert('subscribers_packages_audit', [
            'subscriber_id'         => $data['subscriber_id'],
            'package_id'            => $data['package_id'],
            'comment'               => $comment,
            'version'               => $data['version'],
            'dtt_mod'               => $data['now'],
            'uid_mod'               => $data['uid'],
            'user_type'             => $data['utype'],
        ]);
        # UPDATE subscribers TABLE
        $retval = $this->update('subscribers', [
            'package_id'        => $data['package_id'],
            'package_version'  	=> $data['version'],
            'updated_at'        => $data['now'],
            'updated_by'        => $data['uid'],
            'updated_user_type' => $data['utype'],
        ], 'id_subscriber_key', $data['subscriber_id']);
        
        return $retval;
    }
    
    public function editCategory($data, $comment){
        $retval = false;
        # INSERT subscribers_categories_audit TABLE
        $this->insert('subscribers_categories_audit', [
            'subscriber_id'         => $data['subscriber_id'],
            'category'              => $data['category'],
            'complementary_amount'  => $data['complementary_amount'],
            'comment'               => $comment,
            'version'               => $data['version'],
            'dtt_mod'               => $data['now'],
            'uid_mod'               => $data['uid'],
            'user_type'             => $data['utype'],
        ]);
        
        # UPDATE subscribers TABLE
        $retval = $this->update('subscribers', [
            'category'              => $data['category'],
            'complementary_amount'  => $data['complementary_amount'],
            'category_version'      => $data['version'],
            'updated_at'            => $data['now'],
            'updated_by'            => $data['uid'],
            'updated_user_type'     => $data['utype'],
        ], 'id_subscriber_key', $data['subscriber_id']);
        
        return $retval;
    }
    
    public function editBalance($data, $comment){
        $retval = false;
        # INSERT payments TABLE
        $this->insert('payments', [
            'subscriber_id'     => $data['subscriber_id'],
            'type'              => 'Balance Adjusted by Admin',
            'debit'             => 0,
            'credit'            => 0,
            'balance'           => $data['payment_balance'],
            'version'           => $data['version'],
            'comment'           => $comment,
            'created_at'        => $data['now'],
            'created_by'        => $data['uid'],
            'created_user_type' => $data['utype'],
        ]);
        
        # UPDATE subscribers TABLE
        $retval = $this->update('subscribers', [
            'payment_balance'   => $data['payment_balance'],
            'payment_version'   => $data['version'],
            'updated_at'        => $data['now'],
            'updated_by'        => $data['uid'],
            'updated_user_type' => $data['utype'],
        ], 'id_subscriber_key', $data['subscriber_id']);
        
        return $retval;
    }
    
    public function editOtherFields($id, $data, $old_data){
        $retval = true;
        $fields = [];
        if($data['ba_no'] != $old_data['ba_no']){
            $fields['ba_no'] = $data['ba_no'];
        }
        if($data['rank_id'] != $old_data['rank_id']){
            $fields['rank_id'] = $data['rank_id'];
        }
        if($data['firstname'] != $old_data['firstname']){
            $fields['firstname'] = $data['firstname'];
        }
        if($data['lastname'] != $old_data['lastname']){
            $fields['lastname'] = $data['lastname'];
        }
        if($data['house_no'] != $old_data['house_no']){
            $fields['house_no'] = $data['house_no'];
        }
        if($data['official_mobile'] != $old_data['official_mobile']){
            $fields['official_mobile'] = $data['official_mobile'];
        }
        if($data['personal_mobile'] != $old_data['personal_mobile']){
            $fields['personal_mobile'] = $data['personal_mobile'];
        }
        if($data['residential_phone'] != $old_data['residential_phone']){
            $fields['residential_phone'] = $data['residential_phone'];
        }
        if($data['email'] != $old_data['email']){
            $fields['email'] = $data['email'];
        }
        if($data['complementary_amount'] != $old_data['complementary_amount']){
            $fields['complementary_amount'] = $data['complementary_amount'];
        }
        if($data['remarks'] != $old_data['remarks']){
            $fields['remarks'] = $data['remarks'];
        }
        
        if(!empty($fields)){
            # UPDATE subscribers TABLE
            $retval = $this->update('subscribers', $fields, 'id_subscriber_key', $id);
        }   
        
        return $retval;
    }

    public function saveMikrotikLog($subscriber_id, $username, $password, $local_ip, $remote_ip, $router_ip, $profile, $status, $comment, $version, $time, $by, $mikrotik_response, $user_type='system'){
        
        $this->insert('subscribers_miktorik_logs', [
                'subscriber_id' => $subscriber_id,
                'username' => $username,
                'password' => $password,
                'profile' => $profile,
                'local_ip' => $local_ip,
                'remote_ip' => $remote_ip,
                'router_ip' => $router_ip,
                'status' => $status,
                'comment' => $comment,
                'version' => $version,
                'dtt_mod' => $time,
                'uid_mod' => $by,
                'miktorik_response' => json_encode($mikrotik_response),
                'utype' => $user_type
            ]);
    }
    
    public function insert($table, $fields = [], $returnLastInsertId = false)
    {
        $this->_error = false;
        
        if(count($fields)){
            $keys = array_keys($fields);
            $values = '';
            foreach($fields as $field){
                $values .=  '?, ';
            }
            $sql = "INSERT INTO {$table} (`".  implode('`,`', $keys)."`) VALUES(". rtrim($values, ', ') .")";
        }
        
        if($query = $this->_db->prepare($sql)){
            if(count($fields)){
                $x = 1;
                foreach($fields as $field){
                    $query->bindValue($x, $field);
                    $x++;
                }
            }
            if($query->execute()){
                return $returnLastInsertId ? $this->_db->lastInsertId() : true;
            } else{
                //$this->_error = true;
                return false;
            }
        }
        
        return false;
    }
    
    
    public function update($table, $fields, $column, $value)
    {
        $sql = ''; $set = '';
        
        if(count($fields)){
            $values = '';
            foreach($fields as $key=>$val){
                $set .=  $key." = ?, ";
            }
            $sql = "UPDATE {$table} SET ". rtrim($set, ', ') ." WHERE $column = ?";
            
            if($query = $this->_db->prepare($sql)){
                if(count($fields)){
                    $x = 1;
                    foreach($fields as $field){
                        $query->bindValue($x, $field);
                        $x++;
                    }
                    $query->bindValue($x, $value);
                }
                if($query->execute()){
                    return true;
                }
            }
        }
        
        return false;
    }
    
    public function getPackageBySubscriberId($uid){
        $sql = "SELECT p.`code` as `package`
                FROM `subscribers` s
                INNER JOIN packages p ON p.`id` = s.`package_id`
                WHERE s.`id_subscriber_key` = ?";
        $tmp = DB::getInstance()->query($sql, [$uid])->first();
        return isset($tmp['package']) ? $tmp['package'] : '';
    }
    
    public function __destruct(){

    }
}
