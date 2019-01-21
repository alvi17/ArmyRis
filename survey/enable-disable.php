<?php

/**
 * Enable/Diasable Survey
 * 
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date Mar 20, 2017 05:33
 */

require "../core/config.php";
require "../core/init.php";

$survey_id = Input::get('survey_id');
$action = Input::get('action');

$txt = $action==1 ? 'enabled' : 'disabled';

$data = [
    'is_active'     => $action,
    'dtt_mod'       => date('Y-m-d H:i:s'),
    'uid_mod'       => Session::get('uid'),
];
$updated = DB::getInstance()->update('surveys', $data, 'id', $survey_id);

Session::put('success', "Survey {$txt} successfully.");
Utility::redirect('index.php');