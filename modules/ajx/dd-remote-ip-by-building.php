<?php

/**
 * Lists unoccupied Remmote IPs by building id and sets them in dropdown
 * 
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date November 29, 2016 02:27
 */

require "../../core/config.php";
require "../../core/init.php";

$remote_ips = Utility::listRemoteIpsByBuildingId((int) Input::request('id'));
if(!empty($remote_ips)){
    foreach ($remote_ips as $ip){
        echo "<option value=\"{$ip}\">{$ip}</option>\n";
    }
} else{
    echo "<option value=\"\"></option>\n";
}
