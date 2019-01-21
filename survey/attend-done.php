<?php

require "../core/config.php";
require "../core/init.php";
require "../modules/acl/Roles.php";
require "../modules/Survey.php";


$pageCode       = 'survey-attend-done';
$pageContent	= 'survey/attend-done';
$pageTitle 		= 'Participate Survey';

if (!Auth::isSubscriberUser()){
    Utility::redirect(BASE_URL);
}

$survey_id = Input::get('id');
$uid = Session::get('uid');

if(empty($survey_id) || !Survey::attendedSurvey($survey_id, $uid))
{
    Utility::redirect(BASE_URL);
}
$info = Survey::getSurveyDetails($survey_id);
$questions = Survey::listQuestions($survey_id);
require BASE_DIRECTORY.'/views/layouts/admin-base.phtml';