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

    if(isset($_GET['action']) && @$_GET['action'] == 'details') {
        
        if($jwt_response->data->group=='admin') {
            if($quiz_checker->generate($quiz_checker->topic_id, $quiz_checker->version_id, $quiz_checker->variant_id)) send_message(true, "Успешно сгенерированы новые варианты тестов!"); else send_message(false, "Ошибка! Возможно отсутствуют вопросы теста для данной темы");
        } else send_message(false, "Ошибка! У вас нет доступа");
          
    }

 }