<?php 
$package_price = 700;
$complementary = 400;
//$paid = 300;
$paid = 500;
$complementary = ($complementary/$package_price)*$paid;
echo $complementary;
echo '<br>';
$complementary = round($complementary);
echo $complementary;
exit;?>


<?php
//echo date("H:i", strtotime("04:25 pm"));
$from = [
  'y' => '',
  'm' => '',
  'd' => '',
  'h' => '',
  'i' => '',
  's' => '',
  'a' => '',
];

$connect_begin_time = ['h'=>'06', 'i'=>'59', 's'=>'59', 'a'=>'am'];
$from = array_merge($from, $connect_begin_time);

echo '<pre>';
print_r($from);
echo '</pre>';
exit;?>

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
exit;
?>