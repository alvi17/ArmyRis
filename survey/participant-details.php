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


$pageCode       = 'survey-participant-details';
$pageContent	= 'survey/participant-details';
$pageTitle 		= 'Survey Participant Details';

if(!Auth::isAuthenticatedPage($pageCode)){
    Session::put('error', "You do not have permission to access '{$pageTitle}' page.");
    Utility::redirect(BASE_URL.'/login.php');
}

$survey_id = Input::get('sid');
$participant_id = Input::get('pid');
$survey_details = Survey::getSurveyDetails($survey_id);
$participant = Survey::listParticularParticipant($survey_id, $participant_id);

$questions = Survey::listQuestions($survey_id);
$answers = Survey::listParticipantAnswers($survey_id, $participant_id);

require BASE_DIRECTORY.'/views/layouts/admin-base.phtml';