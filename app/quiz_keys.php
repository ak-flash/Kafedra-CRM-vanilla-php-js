<?php
// include database and object files
include_once 'config/core.php';
include_once 'objects/quiz_key.php';

// get database connection
$database = new Database();
$db = $database->getConnection(); 

$quiz_key = new Quiz_key($db); 

$data = json_decode(file_get_contents("php://input"));

if(isset($_GET['id'])){ $quiz_key->id = (int) $_GET['id']; }

if(isset($data->topic_id)) { $quiz_key->topic_id = (int) $data->topic_id; }
if(isset($data->question_part)) { $quiz_key->question_part = (int) $data->question_part; }
if(isset($data->version_id)) { $quiz_key->version_id = (int) $data->version_id; }
if(isset($data->variants_count)) { $quiz_key->variants_count = (int) $data->variants_count; }
if(isset($data->questions_count)) { $quiz_key->questions_count = (int) $data->questions_count; }


// Only with auth responses
$jwt_response = validate_jwt();

 if($jwt_response->status &&  ($jwt_response->data->group=='admin' || $jwt_response->data->group=='instructor')){

    $quiz_key->user_id = $jwt_response->data->id;

    if(isset($_GET['action']) && @$_GET['action'] == 'list')   {
        print_r($quiz_key->list($quiz_key->topic_id, $quiz_key->question_part));  
    }
    
    if(isset($_GET['action']) && @$_GET['action'] == 'show')   {
        print_r($quiz_key->show($quiz_key->id));
    }
    
    if(isset($_GET['action']) && @$_GET['action'] == 'print')   {
        //if($quiz_key->print($quiz_key->topic_id, $quiz_key->version_id)) send_message(true, "Успешно создано и отправлено на загрузку"); else send_message(false, "Ошибка!");

        print_r($quiz_key->print($quiz_key->topic_id, $quiz_key->version_id, $quiz_key->questions_count));
    }

    if(isset($_GET['action']) && @$_GET['action'] == 'update') {
        
        if($jwt_response->data->group=='admin') {
            if($quiz_key->update($quiz_key->id)) send_message(true, "Успешно обновлено"); else send_message(false, "Ошибка обновления");
        } else send_message(false, "Ошибка! У вас нет доступа");
          
    }

    if(isset($_GET['action']) && @$_GET['action'] == 'generate') {
        
        if($jwt_response->data->group=='admin') {
            if($quiz_key->generate($quiz_key->topic_id, $quiz_key->version_id, $quiz_key->variants_count)) send_message(true, "Успешно сгенерированы новые варианты тестов!"); else send_message(false, "Ошибка! Возможно отсутствуют вопросы теста для данной темы");
        } else send_message(false, "Ошибка! У вас нет доступа");
          
    }

 }