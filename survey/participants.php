<?php

/**
 * Survey Participants
 * 
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date Mar 18, 2017 18:32
 */

require "../core/config.php";
require "../core/init.php";
require "../modules/acl/Roles.php";
require "../modules/Survey.php";


$pageCode       = 'survey-participants';
$pageContent	= 'survey/participants';
$pageTitle 		= 'Survey Participants';



if(!Auth::isAuthenticatedPage($pageCode)){
    Session::put('error', "You do not have permission to access '{$pageTitle}' page.");
    Utility::redirect(BASE_URL.'/login.php');
}

$survey_id = Input::get('survey_id');
$survey_details = Survey::getSurveyDetails($survey_id);
$participants = Survey::listParticipants($survey_id);

require BASE_DIRECTORY.'/views/layouts/admin-base.phtml';