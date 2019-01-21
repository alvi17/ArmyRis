<?php

/**
 * Counts total active subscribers
 * 
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date April 08, 2017 16:26
 */

require "../../core/config.php";
require "../../core/init.php";

$building_id = (int) Input::request('id');
$sql = "SELECT COUNT(1) AS tot 
        FROM `subscribers` s
        WHERE s.`building_id` = ?
        AND s.`status_id` = 1";
$result = DB::getInstance()->query($sql, [$building_id])->first();
echo $result['tot'];