<?php
// include database and object files
include_once 'config/core.php';
include_once 'objects/quiz_checker.php';

// get database connection
$database = new Database();
$db = $database->getConnection(); 

$quiz_checker = new Quiz_checker($db); 

$data = json_decode(file_get_contents("php://input"));

if(isset($_GET['id'])){ $quiz_checker->id = (int) $_GET['id']; }

if(isset($data->topic_id)) { $quiz_checker->topic_id = (int) $data->topic_id; }
if(isset($data->question_part)) { $quiz_checker->question_part = (int) $data->question_part; }
if(isset($data->version_id)) { $quiz_checker->version_id = (int) $data->version_id; }
if(isset($data->variant_id)) { $quiz_checker->variant_id = (int) $data->variant_id; }

if(isset($data->q1)) { $quiz_checker->q1 = (int) $data->q1; }
if(isset($data->q2)) { $quiz_checker->q2 = (int) $data->q2; }
if(isset($data->q3)) { $quiz_checker->q3 = (int) $data->q3; }
if(isset($data->q4)) { $quiz_checker->q4 = (int) $data->q4; }
if(isset($data->q5)) { $quiz_checker->q5 = (int) $data->q5; }
if(isset($data->q6)) { $quiz_checker->q6 = (int) $data->q6; }
if(isset($data->q7)) { $quiz_checker->q7 = (int) $data->q7; }
if(isset($data->q8)) { $quiz_checker->q8 = (int) $data->q8; }
if(isset($data->q9)) { $quiz_checker->q9 = (int) $data->q9; }
if(isset($data->q10)) { $quiz_checker->q10 = (int) $data->q10; }

if(isset($data->q11)) { $quiz_checker->q11 = (int) $data->q11; }
if(isset($data->q12)) { $quiz_checker->q12 = (int) $data->q12; }
if(isset($data->q13)) { $quiz_checker->q13 = (int) $data->q13; }
if(isset($data->q14)) { $quiz_checker->q14 = (int) $data->q14; }
if(isset($data->q15)) { $quiz_checker->q15 = (int) $data->q15; }
if(isset($data->q16)) { $quiz_checker->q16 = (int) $data->q16; }
if(isset($data->q17)) { $quiz_checker->q17 = (int) $data->q17; }
if(isset($data->q18)) { $quiz_checker->q18 = (int) $data->q18; }
if(isset($data->q19)) { $quiz_checker->q19 = (int) $data->q19; }
if(isset($data->q20)) { $quiz_checker->q20 = (int) $data->q20; }

// Only with auth responses
$jwt_response = validate_jwt();

 if($jwt_response->status){

    $quiz_checker->user_id = $jwt_response->data->id;

    if(isset($_GET['action']) && @$_GET['action'] == 'load_versions')   {
        print_r($quiz_checker->load_versions($quiz_checker->topic_id));  
    }
    
    if(isset($_GET['action']) && @$_GET['action'] == 'load_variants')   {
        print_r($quiz_checker->load_variants($quiz_checker->topic_id, $quiz_checker->version_id));  
    } 

    if(isset($_GET['action']) && @$_GET['action'] == 'load')   { 
        if($quiz_checker->load($quiz_checker->topic_id, $quiz_checker->version_id, $quiz_checker->variant_id)) send_message(true, "Ответы на тесты успешно загружены"); else send_message(false, "Не все ответы для данного варианта есть в базе");
    } 

    if(isset($_GET['action']) && @$_GET['action'] == 'check')   {
        print_r($quiz_checker->check($quiz_checker->topic_id, $quiz_checker->version_id, $quiz_checker->variant_id, $quiz_checker->question_part));  
    }

    if(isset($_GET['action']) && @$_GET['action'] == 'details') {
        
        if($jwt_response->data->group=='admin') {
            if($quiz_checker->generate($quiz_checker->topic_id, $quiz_checker->version_id, $quiz_checker->variant_id)) send_message(true, "Успешно сгенерированы новые варианты тестов!"); else send_message(false, "Ошибка! Возможно отсутствуют вопросы теста для данной темы");
        } else send_message(false, "Ошибка! У вас нет доступа");
          
    }

 }