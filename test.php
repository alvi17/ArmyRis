<?php 
echo date_default_timezone_get();
exit;
?>

<?php 
$str = 'aq1qq';
var_export(has_number($str));

function has_number($str)
{
    return (1 === preg_match('~[0-9]~', $str)) ? true : false;
}
exit;?>

<?php
$table='';
$column='';
$str = 'users|username';
$tmp = explode('|', $str);
if(isset($tmp[0])){ $table = $tmp[0];}
if(isset($tmp[1])){ $column = $tmp[1];}

echo '$table: '. $table;
echo '<br>';
echo '$column: '. $column;
?>

