<?php

/**
 * Description of Complaint
 * 
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date January 27, 2017 08:18
 */
class Complaint {
    
    public static $complaintAdminStatuses = array(
        1 => 'Informed',
        2 => 'In Progress',
        3 => 'On Hold',
        4 => 'Pending',
        5 => 'Solved',
        6 => 'Canceled',
    );
    
    public static $complaintUserStatuses = array(
        1 => 'Informed',
        4 => 'Solved',
        5 => 'Canceled',
    );
    
    public static $prefix = "complaint_";
    
    public function __construct() {
        //TODO
	}
    
    public static function listProblemTypes($includeAll = false)
    {
        $data = [];
        
        $cond = !$includeAll ? " WHERE `is_active` = 1" : "";
        $sql = "SELECT `id`, `name`
            FROM `complaint_option_problems`
            ".$cond."
            ORDER BY `name` ASC";
        $result = DB::getInstance()->query($sql, [])->results();
        foreach($result as $res){
            $data[ $res['id'] ] = $res['name'];
        }
        
        return $data;
    }
    
    public static function listSupportReasons($includeAll = false)
    {
        $data = [];
        
        $cond = !$includeAll ? " WHERE `is_active` = 1" : "";
        $sql = "SELECT `id`, `name`
            FROM `complaint_option_supports`
            ".$cond."
            ORDER BY `name` ASC";
        $result = DB::getInstance()->query($sql, [])->results();
        foreach($result as $res){
            $data[ $res['id'] ] = $res['name'];
        }

        return $data;
    }
    
    public static function listSupportsInCharge(){
        $data = [];
        $sql = "SELECT
                u.`id`
              , u.`firstname`
              , u.`lastname`
              , u.`mobile`
              FROM `users` u
              WHERE u.`is_support_asst` = 1
              AND u.`status_id` = 1";
        $result = DB::getInstance()->query($sql)->results();
        foreach($result as $res){
            //$data[ $res['id'] ] = trim($res['firstname'].' '.$res['lastname']).' ('.$res['mobile'].')';
            $data[ $res['id'] ]['name'] = trim($res['firstname'].' '.$res['lastname']);
            $data[ $res['id'] ]['mobile'] = $res['mobile'];
        }
        return $data;
    }
    
    public static function listSupportsInChargeByMobile(){
        $data = [];
        $sql = "SELECT
                u.`firstname`
              , u.`lastname`
              , u.`mobile`
              FROM `users` u
              INNER JOIN `user_role` r ON u.`id` = r.`user_id` AND r.`role_id` = ".SUPPORT_ROLE_ID."
              WHERE u.`status_id` = 1";
        $result = DB::getInstance()->query($sql)->results();
        foreach($result as $res){
            $data[ $res['mobile'] ] = trim($res['firstname'].' '.$res['lastname']).' ('.$res['mobile'].')';
        }
        return $data;
    }
    
    public static function frequencyComplaints($code, $area, $building, $status, $rank, $rank_option, $from, $to, $problem_type, $freqeucny, $freqeucny_option, $offset, $limit)
	{
        $data = array();
        $whereCond = "";
        $countCond = "";
        
        if(!empty($code)){
            if(!empty($whereCond)){$whereCond .= " AND ";}
            $whereCond .= "(s.`ba_no` LIKE '%".$code."%' "
                    . "OR s.`firstname` LIKE '%".$code."%' "
                    . "OR s.`lastname` LIKE '%".$code."%' "
                    . "OR s.`username` LIKE '%".$code."%'"
                    . "OR s.`official_mobile` LIKE '%".$code."%' "
                    . "OR c.`pb_details` LIKE '%".$code."%')";
        }
        if(!empty($rank) && $rank!=-1){
            if(!empty($whereCond)){$whereCond .= " AND ";}
            if($rank_option==1){
                $whereCond .= "r.`id` <= ".$rank;
            } else{
                $whereCond .= "r.`id` = ".$rank;
            }
        }
        if(!empty($area) && $area!=-1){
            if(!empty($whereCond)){$whereCond .= " AND ";}
            $whereCond .= "a.`id_area` = ".$area;
        }
        if(!empty($building)){
            if(!empty($whereCond)){$whereCond .= " AND ";}
            $whereCond .= "s.`building_id` = ".$building;
        }
        if(!empty($status)){
            if(!empty($whereCond)){$whereCond .= " AND ";}
            $whereCond .= "c.`id_status` = ".$status;
        }
        if(!empty($from)){
            if(!empty($whereCond)){$whereCond .= " AND ";}
            $whereCond .= "c.`dtt_add` >= '".$from."'";
        }
        if(!empty($to)){
            if(!empty($whereCond)){$whereCond .= " AND ";}
            $whereCond .= "c.`dtt_add` <= '".$to."'";
        }
        if(!empty($problem_type) && $problem_type != -1){
            if(!empty($whereCond)){$whereCond .= " AND ";}
            $whereCond .= "c.`pb_type` = '".$problem_type."'";
        }
        if(!empty($support_reason) && $support_reason != -1){
            if(!empty($whereCond)){$whereCond .= " AND ";}
            $whereCond .= "c.`support_reason` = '".$support_reason."'";
        }
        if(!empty($whereCond)){
            $whereCond = "WHERE ".$whereCond;
        }
        
        if(!empty($freqeucny)){
            if($freqeucny_option==1){
                $countCond .= "HAVING COUNT(c.`subscriber_id`) >= ".$freqeucny;
            } else{
                $countCond .= "HAVING COUNT(c.`subscriber_id`) = ".$freqeucny;
            }
        }
        
        $sql = "SELECT
                    c.`subscriber_id`,
                    s.`username`,
                    s.`firstname`,
                    s.`lastname`,
                    r.`name` AS `rank`,
                    CONCAT(s.`house_no`, ', ', b.`building_name`, ', ', a.`area_name`) AS `address`,
                    COUNT(c.`subscriber_id`) AS total_complains
                FROM complains c
                INNER JOIN subscribers s ON s.`id_subscriber_key` = c.`subscriber_id`
                LEFT JOIN ranks r ON r.`id` = s.`rank_id`
                LEFT JOIN complaint_option_problems cop ON cop.id = c.pb_type
                LEFT JOIN complaint_option_supports spt ON spt.id = c.support_reason
                LEFT JOIN `areas` AS a ON s.`area_id` = a.`id_area`
                LEFT JOIN buildings b ON b.`id_building` = s.`building_id`
                ".$whereCond."
                GROUP BY c.`subscriber_id`
                ".$countCond."
                ORDER BY r.`order` ASC, s.`firstname` ASC"
                ;
        //echo "<pre>$sql</pre>";
        $data = DB::getInstance()->query($sql)->results();
		return $data;
	}
    
    public static function listUserComplaints($code, $area, $building, $status, $designation, $designationOption, $from, $to, $problem_type, $support_reason, $offset, $limit)
	{
        $data = array();
        $whereCond = "";
        
        if($offset<=0) $offset = 1;
        
        if(!empty($code)){
            if(!empty($whereCond)){$whereCond .= " AND ";}
            $whereCond .= "(s.`ba_no` LIKE '%".$code."%' "
                    . "OR s.`firstname` LIKE '%".$code."%' "
                    . "OR s.`lastname` LIKE '%".$code."%' "
                    . "OR s.`username` LIKE '%".$code."%'"
                    . "OR s.`official_mobile` LIKE '%".$code."%' "
                    . "OR c.`pb_details` LIKE '%".$code."%')";
            //$whereCond .= "s.`ba_no` LIKE '%ba%' OR s.`firstname` LIKE '%ba%' or s.`lastname` LIKE '%ba%' OR s.`username` LIKE '%ba%' OR s.`official_mobile` LIKE '%ba%' OR c.`pb_details` LIKE '%ba%'";
        }
        if(!empty($designation) && $designation!=-1){
            if(!empty($whereCond)){$whereCond .= " AND ";}
            if($designationOption==1){
                $whereCond .= "r.`id` <= ".$designation;
            } else{
                $whereCond .= "r.`id` = ".$designation;
            }
        }
        if(!empty($area) && $area!=-1){
            if(!empty($whereCond)){$whereCond .= " AND ";}
            $whereCond .= "a.`id_area` = ".$area;
        }
        if(!empty($building)){
            if(!empty($whereCond)){$whereCond .= " AND ";}
            $whereCond .= "s.`building_id` = ".$building;
        }
        if(!empty($status)){
            if(!empty($whereCond)){$whereCond .= " AND ";}
            $whereCond .= "c.`id_status` = ".$status;
        }
        if(!empty($from)){
            if(!empty($whereCond)){$whereCond .= " AND ";}
            $whereCond .= "c.`dtt_add` >= '".$from."'";
        }
        if(!empty($to)){
            if(!empty($whereCond)){$whereCond .= " AND ";}
            $whereCond .= "c.`dtt_add` <= '".$to."'";
        }
        if(!empty($problem_type) && $problem_type != -1){
            if(!empty($whereCond)){$whereCond .= " AND ";}
            $whereCond .= "c.`pb_type` = '".$problem_type."'";
        }
        if(!empty($support_reason) && $support_reason != -1){
            if(!empty($whereCond)){$whereCond .= " AND ";}
            $whereCond .= "c.`support_reason` = '".$support_reason."'";
        }
        if(!empty($whereCond)){
            $whereCond = " WHERE ".$whereCond;
        }
        
        $sql = "SELECT c.`id`,
                s.`firstname`,
                s.`lastname`,
                r.`name` AS `rank`,
                s.`username`,
                s.`house_no`,
                b.`building_name`,
                a.`area_name`,
                CONCAT(s.`house_no`, ', ', b.`building_name`, ', ', a.`area_name`) AS `address`,
                -- DATE_FORMAT(c.`pb_since`,'%d/%m/%Y %H:%i') AS `pb_since`,
				c.`pb_since`,
                c.`pb_type`,
                cop.name AS pb_title,
                c.`support_reason`,
                spt.name AS support_reason,
                c.`support_details`,
                SUBSTR(c.`pb_details`,1,500) AS pb_details,
                c.`id_status`,
                -- DATE_FORMAT(c.`dtt_mod`,'%d/%m/%Y %H:%i') AS `dtt_mod`,
				c.`dtt_mod`,
                u.`firstname` AS assist_fisrtname, 
                u.`lastname` AS assist_lastname,
				c.source
                FROM complains c
                INNER JOIN subscribers s ON s.`id_subscriber_key` = c.`subscriber_id`
                LEFT JOIN ranks r ON r.`id` = s.`rank_id`
                LEFT JOIN complaint_option_problems cop ON cop.id = c.pb_type
                -- LEFT JOIN complaint_option_problems spt ON cop.id = c.support_reason
                LEFT JOIN complaint_option_supports spt ON spt.id = c.support_reason
                LEFT JOIN `areas` AS a ON s.`area_id` = a.`id_area`
                LEFT JOIN buildings b ON b.`id_building` = s.`building_id`
                LEFT JOIN `users` u ON u.`id` = c.`uid_in_charge`
				".$whereCond."
                GROUP BY c.`id`
                ORDER BY c.`dtt_add` ASC
                LIMIT " . $limit . " OFFSET " . ($offset-1)*$limit;
        //echo '<pre>'; echo $sql; echo '<pre>'; exit;
        return DB::getInstance()->query($sql)->results();
	}
    
    public static function countUserComplaints($code, $area, $building, $status, $designation, $designationOption, $from, $to, $problem_type, $support_reason)
	{
        $count = 0;
        $whereCond = "";
        
        if(!empty($code)){
            if(!empty($whereCond)){$whereCond .= " AND ";}
            $whereCond .= "(s.`ba_no` LIKE '%".$code."%' "
                    . "OR s.`firstname` LIKE '%".$code."%' "
                    . "OR s.`lastname` LIKE '%".$code."%' "
                    . "OR s.`username` LIKE '%".$code."%'"
                    . "OR s.`official_mobile` LIKE '%".$code."%' "
                    . "OR c.`pb_details` LIKE '%".$code."%')";
            //$whereCond .= "s.`ba_no` LIKE '%ba%' OR s.`firstname` LIKE '%ba%' or s.`lastname` LIKE '%ba%' OR s.`username` LIKE '%ba%' OR s.`official_mobile` LIKE '%ba%' OR c.`pb_details` LIKE '%ba%'";
        }
        if(!empty($designation) && $designation!=-1){
            if(!empty($whereCond)){$whereCond .= " AND ";}
            if($designationOption==1){
                $whereCond .= "r.`id` <= ".$designation;
            } else{
                $whereCond .= "r.`id` = ".$designation;
            }
        }
        if(!empty($area) && $area!=-1){
            if(!empty($whereCond)){$whereCond .= " AND ";}
            $whereCond .= "a.`id_area` = ".$area;
        }
        if(!empty($building)){
            if(!empty($whereCond)){$whereCond .= " AND ";}
            $whereCond .= "s.`building_id` = ".$building;
        }
        if(!empty($status)){
            if(!empty($whereCond)){$whereCond .= " AND ";}
            $whereCond .= "c.`id_status` = ".$status;
        }
        if(!empty($from)){
            if(!empty($whereCond)){$whereCond .= " AND ";}
            $whereCond .= "c.`dtt_add` >= '".$from."'";
        }
        if(!empty($to)){
            if(!empty($whereCond)){$whereCond .= " AND ";}
            $whereCond .= "c.`dtt_add` <= '".$to."'";
        }
        if(!empty($problem_type) && $problem_type != -1){
            if(!empty($whereCond)){$whereCond .= " AND ";}
            $whereCond .= "c.`pb_type` = '".$problem_type."'";
        }
        if(!empty($support_reason) && $support_reason != -1){
            if(!empty($whereCond)){$whereCond .= " AND ";}
            $whereCond .= "c.`support_reason` = '".$support_reason."'";
        }
        if(!empty($whereCond)){
            $whereCond = " WHERE ".$whereCond;
        }
        
        $sql = "SELECT COUNT(1) AS tot
                FROM complains c
                INNER JOIN subscribers s ON s.`id_subscriber_key` = c.`subscriber_id`
                LEFT JOIN ranks r ON r.`id` = s.`rank_id`
                LEFT JOIN complaint_option_problems cop ON cop.id = c.pb_type
                LEFT JOIN complaint_option_supports spt ON spt.id = c.support_reason
                LEFT JOIN `areas` AS a ON s.`area_id` = a.`id_area`
                LEFT JOIN buildings b ON b.`id_building` = s.`building_id` 
                LEFT JOIN `users` u ON u.`id` = c.`uid_in_charge`".
				$whereCond;
        
        $result = DB::getInstance()->query($sql);
        if($result->count()){
            $data = $result->first();
            $count = $data['tot'];
        }
        
        return $count;
	}
    
    public static function prepareGeneralReportUrlPostfix($code, $area, $building, $status, $rank, $rank_opt, $dt_md, $from, $to, $problem_type, $support_reason, $page, $include_page = true)
    {
        $postfix = '';
        if(!empty($code)){
            if(!empty($postfix)){$postfix .= '&';}
            $postfix .= 'search='.$code;
        }
        if(!empty($area)){
            if(!empty($postfix)){$postfix .= '&';}
            $postfix .= 'area='.$area;
        }
        if(!empty($building)){
            if(!empty($postfix)){$postfix .= '&';}
            $postfix .= 'building='.$building;
        }
        if(!empty($status)){
            if(!empty($postfix)){$postfix .= '&';}
            $postfix .= 'status='.$status;
        }
        if(!empty($rank)){
            if(!empty($postfix)){$postfix .= '&';}
            $postfix .= 'rank='.$rank;
        }
        if(!empty($rank_opt)){
            if(!empty($postfix)){$postfix .= '&';}
            $postfix .= 'rank_opt='.$rank_opt;
        }
        if(!empty($dt_md)){
            if(!empty($postfix)){$postfix .= '&';}
            $postfix .= 'dt_md='. $dt_md;
        }
        if(!empty($from)){
            if(!empty($postfix)){$postfix .= '&';}
            $postfix .= 'date_from='. date('Y-m-d', strtotime($from));
        }
        if(!empty($to)){
            if(!empty($postfix)){$postfix .= '&';}
            $postfix .= 'date_to='. date('Y-m-d', strtotime($to));
        }
        if(!empty($problem_type)){
            if(!empty($postfix)){$postfix .= '&';}
            $postfix .= 'problem_type='. $problem_type;
        }
        if(!empty($support_reason)){
            if(!empty($postfix)){$postfix .= '&';}
            $postfix .= 'support_reason='. $support_reason;
        }
        if($include_page && !empty($page)){
            if(!empty($postfix)){$postfix .= '&';}
            $postfix .= 'page='.$page;
        }
        if(!empty($postfix)){$postfix = '?'.$postfix;}
        
        return $postfix;
    }
    
    public static function getSmsReceiverMobileNumber($subscriber_id){
        $msisdn = '';
        $sql = "SELECT u.`mobile`
                FROM `subscribers` s
                INNER JOIN `areas` a ON a.`id_area` = s.`area_id`
                INNER JOIN `users` u ON u.`id` = a.`support_in_charge_id`
                WHERE s.`id_subscriber_key` = ?";
        $result = DB::getInstance()->query($sql, [$subscriber_id]);
        if($result->count()){
            $result = $result->first();
            $msisdn = $result['mobile'];
        }
        return $msisdn;
    }
    
    public static function getSubscriberMobileNumber($subscriber_id){
        $msisdn = '';
        $sql = "SELECT s.`official_mobile` AS `msisdn`
                FROM `subscribers` s
                WHERE s.`id_subscriber_key` = ?";
        $result = DB::getInstance()->query($sql, [$subscriber_id]);
        if($result->count()){
            $result = $result->first();
            $msisdn = $result['msisdn'];
        }
        return $msisdn;
    }
    
    public static function getComplaintSmsText($subscriber_id, $problem='')
    {
        $txt = '';
        
        $sql = "SELECT
                  s.`username`
                , s.`firstname`
                , s.`lastname`
                , r.`name`          AS `rank`
                , s.`house_no`      AS `house`
                , b.`building_name` AS `building`
                , a.`area_name`     AS `area`
                FROM subscribers s
                LEFT JOIN ranks r ON r.`id` = s.`rank_id`
                LEFT JOIN buildings b ON b.`id_building` = s.`building_id`
                LEFT JOIN areas a ON a.`id_area` = s.`area_id`
                WHERE s.`id_subscriber_key` = ?";
        $result = DB::getInstance()->query($sql, [$subscriber_id]);
        if($result->count()){
            $result = $result->first();
            $pb_str = !empty($problem) ? "

            $problem" : "";
            $txt = "Res Int Service Center.{$pb_str}

BA: {$result['username']}
Name: ".trim($result['firstname'].' '.$result['lastname'])."
Address: H#{$result['house']}, B#{$result['building']}, {$result['area']}

Residential Internet
AITSO";
            
        }
        
        return $txt;
    }
    
    public static function getComplaintAcknowledgeSmsText($complain, $date){
        return "Respected Subscriber

Your complaint ({$complain}) has been received ({$date}) with care. 

We will attend the issue ASAP.

Residential Internet
AITSO";
    }
}