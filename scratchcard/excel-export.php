<?php

/* 
 * Lists Scratchcard Lots
 * 
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date February 04, 2017 22:28
 */
require "../core/config.php";
require "../core/init.php";
require_once "../libs/PHPExcel.php";
require_once "../modules/file/Excel.php";

$id = Input::get('id');

$sql = "SELECT
          s.`serial_no`
        , s.`code`
        , s.`amount`
        FROM scratch_cards s
        WHERE s.`lot_id` = ?";
$data = DB::getInstance()->query($sql, [$id])->results();

$headers = ['Serial No', 'Card No', 'Amount'];
Excel::export($data, $headers, 'Scratch Cards for Print', 'Scratch Cards for Print', 'Scratch Cards');
exit;