<?php

/**
 * Description of Operation
 *
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date November 26, 2016 09:56
 */
class Operation {
    public static function listActiveSubsceibersMobileNumbers($area_id, $building_id)
    {
        $data = [];
        $area_id = (int) $area_id;
        $building_id = (int) $building_id;
        
        $where_cond = 's.`status_id` = 1';
        if(!empty($area_id)){
            $where_cond .= " AND s.`area_id` = {$area_id}";
        }
        if(!empty($building_id)){
            $where_cond .= " AND s.`building_id` = {$building_id}";
        }
        
        $sql = "SELECT
                  s.`id_subscriber_key` AS `id`
                , s.`official_mobile` AS `mobile`
                FROM `subscribers` s
                WHERE ".$where_cond;
        $result = DB::getInstance()->query($sql)->results();
        foreach($result as $res){
            $data[$res['id']] = $res['mobile'];
        }
        
        return $data;
    }
}
