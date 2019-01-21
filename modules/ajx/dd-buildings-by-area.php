<?php

/**
 * Lists all buildings by area_id and sets them in dropdown
 * 
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date November 26, 2016 09:56
 */

require "../../core/config.php";
require "../../core/init.php";

$buildings = Utility::listBuildingsByAreaId((int) Input::request('id'));
echo "<option value=\"\"></option>\n";
foreach ($buildings as $key=>$val){
    echo "<option value=\"{$key}\">{$val}</option>\n";
}