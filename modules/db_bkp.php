<?php

/*
 * E:/xampp/php/php.exe -f E:/xampp/htdocs/armyris/modules/db_bkp.php
 */
set_time_limit(0);

require('E:/xampp/htdocs/armyris/core/init_alt.php');
$db = connectDb(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

$username = DB_USERNAME;
$dbname = DB_DATABASE;


$filename = BASE_DIRECTORY.'/DB/'.date('Y').'/'.date('m')."/{$dbname}_".date('Ymd_His').".sql";
confirmDirExists($filename);

$command = "mysqldump -u $username $dbname > $filename";
exec($command);