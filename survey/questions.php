<?php

/**
 * Survey Details
 * 
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date Mar 18, 2017 18:32
 */

require "../core/config.php";
require "../core/init.php";
require "../modules/acl/Roles.php";
require "../modules/Survey.php";


$pageCode       = 'survey-questions';
$pageContent	= 'survey/questions';
$pageTitle 		= 'Survey Questions';

if(!Auth::isAuthenticatedPage($pageCode)){
    Session::put('error', "You do not have permission to access '{$pageTitle}' page.");
    Utility::redirect(BASE_URL.'/login.php');
}

$survey_id = Input::get('survey_id');
$info = Survey::getSurveyDetails($survey_id);

if(empty($survey_id) || empty($info))
{
    Utility::redirect(BASE_URL.'/survey/index.php');
}

$questions = Survey::listQuestions($survey_id);


require BASE_DIRECTORY.'/views/layouts/admin-base.phtml';