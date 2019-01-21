<?php

/**
 * Lists all buildings by area_id and sets them in dropdown
 * Counts total active subscribers
 * 
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date April 08, 2017 15:56
 */

require "../../core/config.php";
require "../../core/init.php";

$area_id = (int) Input::request('id');

$buildings = Utility::listBuildingsByAreaId($area_id);
echo "<option value=\"\"></option>\n";
foreach ($buildings as $key=>$val){
    echo "<option value=\"{$key}\">{$val}</option>\n";
}
echo '##';

$sql = "SELECT COUNT(1) AS tot 
        FROM `subscribers` s
        WHERE s.`area_id` = ?
        AND s.`status_id` = 1";
$result = DB::getInstance()->query($sql, [$area_id])->first();
echo $result['tot'];