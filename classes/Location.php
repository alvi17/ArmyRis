<?php

/**
 * Description of Payment
 *
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date January 10, 2017 08:09
 */

class Location {
    public static function listBuildingsByAreaRouter($area_id, $router_no){
        $buildings = [];
        $cond = ''; $condVal = [];
        
        if(!empty($area_id)){
            $cond .= " AND b.`area_id` = ? ";
            $condVal[] = $area_id;
        } if(!empty($router_no)){
            $cond .= " AND b.`router_no` = ?";
            $condVal[] = $router_no;
        }
        
        $sql = "SELECT b.`id_building`, a.`area_name`, b.`building_name`, b.`router_no`, b.`local_ip`
                , b.`ip_block`, b.`remote_ip_first`, b.`remote_ip_last`
                FROM `buildings` b
                INNER JOIN `areas` a ON a.`id_area` = b.`area_id`
                WHERE b.`is_active` = 1 {$cond}
                ORDER BY b.`area_id` ASC, b.`building_name` ASC";
        $result = DB::getInstance()->query($sql, $condVal);
        if($result->count()){
            foreach($result->results() as $row){
                $buildings[$row['id_building']] = [
                    'area'      => $row['area_name'],
                    'building'  => $row['building_name'],
                    'router_no' => $row['router_no'],
                    'local_ip'  => $row['local_ip'],
                    'ip_block'  => $row['ip_block'],
                    'remote_ip_first'  => $row['remote_ip_first'],
                    'remote_ip_last'  => $row['remote_ip_last'],
                ];
            }
        }
        
        return $buildings;
    }
}
