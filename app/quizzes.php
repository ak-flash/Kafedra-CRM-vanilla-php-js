<?php
// include database and object files
include_once 'config/core.php';
include_once 'objects/quiz.php';

// get database connection
$database = new Database();
$db = $database->getConnection(); 

$quiz = new Quiz($db); 

$data = json_decode(file_get_contents("php://input"));

if(isset($_GET['id'])){ $quiz->id = (int) $_GET['id']; }
if(isset($data->items_per_page)){ $quiz->items_per_page = htmlspecialchars(strip_tags($data->items_per_page)); }
if(isset($data->page)){ $quiz->page = htmlspecialchars(strip_tags($data->page)); }
if(isset($data->search_q)){ $quiz->search_q = htmlspecialchars(strip_tags($data->search_q)); }
if(isset($data->search_topic)){ $quiz->search_topic = htmlspecialchars(strip_tags($data->search_topic)); }

if(isset($data->question)){ $quiz->question = htmlspecialchars(strip_tags($data->question)); }
if(isset($data->good_answer)){ $quiz->good_answer = htmlspecialchars(strip_tags($data->good_answer)); }
if(isset($data->bad1_answer)){ $quiz->bad1_answer = htmlspecialchars(strip_tags($data->bad1_answer)); }
if(isset($data->bad2_answer)){ $quiz->bad2_answer = htmlspecialchars(strip_tags($data->bad2_answer)); }
if(isset($data->bad3_answer)){ $quiz->bad3_answer = htmlspecialchars(strip_tags($data->bad3_answer)); }
if(isset($data->topic_tags)){ $quiz->topic_tags = $data->topic_tags; }

// Only with auth responses
$jwt_response = validate_jwt();

 if($jwt_response->status &&  ($jwt_response->data->group=='admin' || $jwt_response->data->group=='instructor')){

    $quiz->user_id = $jwt_response->data->id;

    if(isset($_GET['action']) && @$_GET['action'] == 'list')   {
        print_r($quiz->list($quiz->page, $quiz->items_per_page));  
    }
    
    
    if(isset($_GET['action']) && @$_GET['action'] == 'show')   {
        print_r($quiz->show($quiz->id));
    }

    // Create quiz
    if(isset($_GET['action']) && @$_GET['action'] == 'create')   {
        if($quiz->create()) send_message(true, "Успешно добавлено!"); else send_message(false, "Ошибка! Возможно такой вопрос уже есть или у вас нет доступа");
    }

    // Update quiz
    if(isset($_GET['action']) && @$_GET['action'] == 'update')   {
        if($quiz->update($quiz->id)) {
            send_message(true, "Успешно обновлено!");
        } else {
            if(!empty($quiz->already_exist_id)) {
                send_message(false, "Ошибка! Такой вопрос уже есть. Проверьте вопрос №<b>".$quiz->already_exist_id."</b>"); 
            } else {
                send_message(false, "Произошла неизвестная ошибка!"); 
            }
            
        }
    }

    if(isset($_GET['action']) && @$_GET['action'] == 'delete') {
        
        if($jwt_response->data->group=='admin') {
            if($quiz->delete($quiz->id)) send_message(true, "Успешно удалено!"); else send_message(false, "Произошла ошибка удаления!");
        } else send_message(false, "Ошибка! У вас нет доступа");
          
    }

 }