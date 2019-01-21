<?php

/**
 * Add Survey Questions
 * 
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date Mar 28, 2017 03:28
 */

require "../core/config.php";
require "../core/init.php";
require "../modules/acl/Roles.php";
require "../modules/Survey.php";


$pageCode       = 'survey-add-question';
$pageContent	= 'survey/add-question';
$pageTitle 		= 'Add Survey Question';

if(!Auth::isAuthenticatedPage($pageCode)){
    Session::put('error', "You do not have permission to access '{$pageTitle}' page.");
    Utility::redirect(BASE_URL.'/login.php');
}

$survey_id = Input::get('survey_id');
if(empty($survey_id)){
    Utility::redirect(BASE_URL.'/survey/index.php');
}

$question = '';
$qtype = 'single_choice'; // Default value
$tot_options = 1;
$ques_opt = [];
$errors = [];

if(Input::exists()){
    $question = Input::post('question');
    $qtype = Input::post('qtype');
    $ques_opt = $_POST['ques_opt'];
    $tot_options = count($ques_opt);
    
    $now = date('Y-m-d H:i:s');
    $uid = Session::get('uid');
    //$utype = Session::get('usertype');
    
    
    ## VALIDATION -----------------------------
    if(empty($question)){
        $errors['question'] = "Question should not be empty!";
    }
    if('text'!=$qtype){
        foreach($ques_opt as $q){
            if(empty($q)){
                $errors['ques_opt'] = "Below of the options should not be empty!";
                break;
            }
        }
    }
    
    if(empty($errors)){
        $ques_data = [
            'survey_id' => $survey_id,
            'question_text' => $question,
            'question_type' => $qtype,
            'uid_add' => $uid,
            'dtt_add' => $now,
        ];
        $ques_id = DB::getInstance()->insert('survey_questions', $ques_data, true);
        
        if('text'!=$qtype){
            foreach($ques_opt as $qo){
                $ques_opt_data = [
                    'survey_id' => $survey_id,
                    'question_id' => $ques_id,
                    'question_option_text' => $qo,
                    'uid_add' => $uid,
                    'dtt_add' => $now,
                ];
                DB::getInstance()->insert('survey_question_options', $ques_opt_data);
            }
        }
        
        Session::put('success', 'Question Added Successfully.');
        Utility::redirect('questions.php?survey_id='.$survey_id);
    }
}

require BASE_DIRECTORY.'/views/layouts/admin-base.phtml';
