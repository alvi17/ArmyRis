<?php

/**
 * Description of Survey
 *
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date January 27, 2017 08:18
 */
class Survey {
    
    public static $answer_seperator = '|~|';

    public static $question_types = [
        'single_choice' => 'Single Choice',
        'multi_choice'  => 'Multiple Choice',
        'text'          => 'Text',
    ];
    
    public static $instruct_types = [
        'single_choice' => 'Select single',
        'multi_choice'  => 'Select multiple',
        'text'          => 'Write down here',
    ];

    public static function listSurveys()
    {
        $sql = "SELECT
                s.`id`
              , s.`name`
              , s.`desc`
              , s.`tot_ques`
              , s.`tot_subs`
              , s.`is_active`
              , s.`dtt_create`
              FROM surveys s
              ORDER BY s.`dtt_create` DESC";
        
        return DB::getInstance()->query($sql)->results();
    }
    
    public static function getSurveyDetails($id) {
        $sql = "SELECT
                s.`name`
              , s.`desc`
              , s.`tot_ques`
              , s.`tot_subs`
              , s.`is_active`
              , s.`dtt_create`
              FROM surveys s
              WHERE s.`id` = ?";
        $result = DB::getInstance()->query($sql, [$id]);
        return $result->first();
    }
    
    public static function countQuestions($survey_id)
    {
        $sql = "SELECT COUNT(s.`id`) AS tot
                FROM `survey_questions` s
                WHERE s.`survey_id` = ?";
        $result = DB::getInstance()->query($sql, [$survey_id])->first();
        return $result['tot'];
    }
    
    public static function countParticipants($survey_id)
    {
        $questions = [];
        $sql = "SELECT COUNT(DISTINCT sqa.`uid_answer_by`) AS tot
                FROM survey_question_answers sqa
                WHERE sqa.`survey_id` = ?";
        $result = DB::getInstance()->query($sql, [$survey_id])->first();
        return $result['tot'];
    }
    
    public static function listQuestions($survey_id, $include_options=true)
    {
        $questions = [];
        $sql = "SELECT
                  s.`id`
                , s.`question_text`
                , s.`question_type`
                FROM `survey_questions` s
                WHERE s.`survey_id` = ?";
        $tmp = DB::getInstance()->query($sql, [$survey_id])->results();
        
        if($include_options){
            foreach($tmp as $t){
                $questions[$t['id']]  = [
                    'text' => $t['question_text'],
                    'type' => $t['question_type'],
                    'options' => $t['question_type']!='text' ? self::listQuestionOptions($t['id']) : [],
                ];
            }
        } else{
            foreach($tmp as $t){
                $questions[$t['id']] = $t['question_text'];
            }
        }
        
        return $questions;
    }
    
    public static function listParticipantQuestionAnswers($survey_id, $participant_id)
    {
        $questions = [];
        $sql = "SELECT
                  s.`id`
                , s.`question_text`
                , s.`question_type`
                FROM `survey_questions` s
                WHERE s.`survey_id` = ?";
        $tmp = DB::getInstance()->query($sql, [$survey_id])->results();
        
        if($include_options){
            foreach($tmp as $t){
                $questions[$t['id']]  = [
                    'text' => $t['question_text'],
                    'type' => $t['question_type'],
                    'options' => $t['question_type']!='text' ? self::listQuestionOptions($t['id']) : [],
                ];
            }
        } else{
            foreach($tmp as $t){
                $questions[$t['id']] = $t['question_text'];
            }
        }
        
        return $questions;
    }
    
    public static function listQuestionOptions($question_id)
    {
        $options = [];
        $sql = "SELECT `id`
                , `question_option_text` AS `option`
                FROM `survey_question_options`
                WHERE `question_id` = ?";
        $results =  DB::getInstance()->query($sql, [$question_id])->results();
        foreach($results as $res){
            $options[$res['id']] = $res['option'];
        }
        return $options;
    }
    
    public static function listActiveSurveys()
    {
        $surveys = [];
        $sql = "SELECT
                s.`id`
              , s.`name`
              FROM surveys s
              WHERE s.`is_active` = 1
              ORDER BY s.`dtt_create` DESC";
        $results = DB::getInstance()->query($sql)->results();
        foreach($results as $res){
            $surveys[$res['id']] = $res['name'];
        }
        return $surveys;
    }
    
    public static function attendedSurvey($survey_id, $subscriber_id)
    {
        $sql = "SELECT COUNT(s.`id`) AS `tot`
                FROM survey_question_answers s
                WHERE s.`survey_id` = ?
                AND s.`uid_answer_by` = ?";
        $result = DB::getInstance()->query($sql, [$survey_id, $subscriber_id])->first();
        return $result['tot']>0 ? true : false;
    }
     
    public static function listParticipants($survey_id) {
        $sql = "SELECT 
                  sqa.`uid_answer_by` AS `participant_id`
                , sqa.`dtt_answer` AS `participated_at`
                , s.`username` AS ba_no
                , s.`firstname`
                , s.`lastname`
                , r.`name` AS `rank`
                FROM survey_question_answers sqa
                INNER JOIN subscribers s ON s.`id_subscriber_key` = sqa.`uid_answer_by`
                LEFT JOIN ranks r ON r.`id` = s.`rank_id`
                WHERE sqa.`survey_id` = ?
                GROUP BY sqa.`survey_id`, sqa.`uid_answer_by`
                ORDER BY r.`order` ASC, sqa.`dtt_answer` ASC";
        return DB::getInstance()->query($sql, [$survey_id])->results();
    }
     
    public static function listParticularParticipant($survey_id, $participant_id) {
        $sql = "SELECT 
                  s.`username` AS ba_no
                , s.`firstname`
                , s.`lastname`
                , r.`name` AS `rank`
                , sqa.`dtt_answer` AS `participated_at`
                FROM survey_question_answers sqa
                INNER JOIN subscribers s ON s.`id_subscriber_key` = sqa.`uid_answer_by`
                LEFT JOIN ranks r ON r.`id` = s.`rank_id`
                WHERE sqa.`survey_id` = ?
                AND sqa.`uid_answer_by` = ?
                GROUP BY sqa.`survey_id`, sqa.`uid_answer_by`";
        return DB::getInstance()->query($sql, [$survey_id, $participant_id])->first();
    }
    
    public static function listParticipantAnswers($survey_id, $participant_id)
    {
        $data = [];
        $sql = "SELECT
                s.`question_id`
              , s.`answers`
              FROM `survey_question_answers` s
              WHERE s.`survey_id` = ?
              AND s.`uid_answer_by` = ?";
        $result = DB::getInstance()->query($sql, [$survey_id, $participant_id])->results();
        foreach($result as $res){
            $data[$res['question_id']] = $res['answers'];
        }
        
        return $data;
    }
}
