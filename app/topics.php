<?php
// include database and object files
include_once 'config/core.php';
include_once 'objects/topic.php';

// get database connection
$database = new Database();
$db = $database->getConnection(); 

$topic = new Topic($db); 

$data = json_decode(file_get_contents("php://input"));

if(isset($_GET['id'])){ $topic->id = (int) $_GET['id']; }
if(isset($data->t_number)){ $topic->t_number = htmlspecialchars(strip_tags($data->t_number)); }
if(isset($data->t_name)){ $topic->t_name = htmlspecialchars(strip_tags($data->t_name)); }
if(isset($data->semester)){ $topic->semester = htmlspecialchars(strip_tags($data->semester)); } else $topic->semester = 0;
if(isset($data->course_id)){ $topic->course_id = htmlspecialchars(strip_tags($data->course_id)); }
if(isset($data->faculty)){ $topic->faculty = htmlspecialchars(strip_tags($data->faculty)); } else $topic->faculty = 0;


if(isset($_GET['action']) && @$_GET['action'] == 'list')   {
    print_r($topic->list($topic->semester, $topic->course_id));  
}

if(isset($_GET['action']) && @$_GET['action'] == 'listshort')   {
    print_r($topic->listshort($topic->semester, $topic->course_id));
}

if(isset($_GET['action']) && @$_GET['action'] == 'show')   {
    print_r($topic->show($topic->id));
}


// Only with auth responses
$jwt_response = validate_jwt();

 if($jwt_response->status &&  ($jwt_response->data->group=='admin' || $jwt_response->data->group=='instructor')){

    // Create topic
    if(isset($_GET['action']) && @$_GET['action'] == 'create')   {
        if($topic->create()) send_message(true, "Успешно добавлено!"); else send_message(false, "Ошибка! Возможно тема с таким номером уже есть или у вас нет доступа");
    }

    // Update topic
    if(isset($_GET['action']) && @$_GET['action'] == 'update')   {
        if($topic->update($topic->id)) send_message(true, "Успешно обновлено!"); else send_message(false, "Ошибка! Возможно тема с таким номером уже есть или у вас нет доступа");
    }

    if(isset($_GET['action']) && @$_GET['action'] == 'delete') {
        
        if($jwt_response->data->group=='admin') {
            if($topic->delete($topic->id)) send_message(true, "Успешно удалено!"); else send_message(false, "Произошла ошибка удаления!");
        } else send_message(false, "Ошибка! У вас нет доступа");
          
    }
 }