<?php

/**
 * Get package price of corporate subscriber to pay montly
 * 
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date June 02, 2017 19:45
 */

require "../../core/config.php";
require "../../core/init.php";

$subs_id = (int) Input::request('id');

$sql = "SELECT s.`corporate_package_price`
		FROM subscribers s
		WHERE s.`id_subscriber_key` = ?
		AND s.`subs_type` = 'corporate'";
$result = DB::getInstance()->query($sql, [$subs_id])->first();

echo isset($result['corporate_package_price']) ? $result['corporate_package_price'] : 0;