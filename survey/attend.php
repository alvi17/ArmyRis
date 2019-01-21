<?php

require "../core/config.php";
require "../core/init.php";
require "../modules/acl/Roles.php";
require "../modules/Survey.php";


$pageCode       = 'survey-attend';
$pageContent	= 'survey/attend';
$pageTitle 		= 'Participate Survey';

if (!Auth::isSubscriberUser()){
    Utility::redirect(BASE_URL);
}

$survey_id = Input::get('id');
$now = date('Y-m-d H:i:s');
$uid = Session::get('uid');

$info = Survey::getSurveyDetails($survey_id);

if(empty($survey_id) || empty($info) || $info['is_active']==0)
{
    Utility::redirect(BASE_URL);
}

$answers = Survey::listParticipantAnswers($survey_id, $uid);
if(!empty($answers)){
    Session::put('success', 'You already have participated in this survey.');
    Utility::redirect('attend-done.php?id='.$survey_id);
}


if(Input::exists()){

    if(Token::check(TOKEN_LEVEL, Input::post(TOKEN_LEVEL))){
        $survey_results = $_POST;
        foreach ($survey_results as $key=>$val){
            if (strpos($key, 'resp_') !== false) {
                $answers = [
                    'survey_id' => $survey_id,
                    'question_id' => str_replace('resp_', '', $key),
                    'answers' => is_array($val) ? implode(Survey::$answer_seperator, $val) : trim($val),
                    'dtt_answer' => $now,
                    'uid_answer_by' => $uid,
                ];
                var_export($answers); echo '<hr>';
                DB::getInstance()->insert('survey_question_answers', $answers, true);
            }
        }
        
        Session::put('success', 'Thanks for your valuable participation.');
        Utility::redirect('attend-done.php?id='.$survey_id);
    }
}

$questions = Survey::listQuestions($survey_id);
require BASE_DIRECTORY.'/views/layouts/admin-base.phtml';