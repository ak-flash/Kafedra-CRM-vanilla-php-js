<?php
// include database and object files
include_once 'config/core.php';
include_once 'objects/lecture.php';

// get database connection
$database = new Database();
$db = $database->getConnection(); 

$lecture = new Lecture($db); 

$data = json_decode(file_get_contents("php://input"));

if(isset($_GET['id'])){ $lecture->id = (int) $_GET['id']; }
if(isset($data->l_number)){ $lecture->l_number = htmlspecialchars(strip_tags($data->l_number)); }
if(isset($data->l_name)){ $lecture->l_name = htmlspecialchars(strip_tags($data->l_name)); }
if(isset($data->semester)){ $lecture->semester = htmlspecialchars(strip_tags($data->semester)); } else $lecture->semester = 0;
if(isset($data->course_id)){ $lecture->course_id = htmlspecialchars(strip_tags($data->course_id)); }
if(isset($data->faculty)){ $lecture->faculty = htmlspecialchars(strip_tags($data->faculty)); } else $lecture->faculty = 0;


if(isset($_GET['action']) && @$_GET['action'] == 'list')   {
    print_r($lecture->list($lecture->semester, $lecture->course_id));  
}

if(isset($_GET['action']) && @$_GET['action'] == 'listshort')   {
    print_r($lecture->listshort($lecture->semester, $lecture->course_id));
}

if(isset($_GET['action']) && @$_GET['action'] == 'show')   {
    print_r($lecture->show($lecture->id));
}


// Only with auth responses
$jwt_response = validate_jwt();

 if($jwt_response->status &&  ($jwt_response->data->group=='admin' || $jwt_response->data->group=='instructor')){

    // Create lecture
    if(isset($_GET['action']) && @$_GET['action'] == 'create')   {
        if($lecture->create()) send_message(true, "Успешно добавлено!"); else send_message(false, "Ошибка! Возможно тема с таким номером уже есть или у вас нет доступа");
    }

    // Update lecture
    if(isset($_GET['action']) && @$_GET['action'] == 'update')   {
        if($lecture->update($lecture->id)) send_message(true, "Успешно обновлено!"); else send_message(false, "Ошибка! Возможно тема с таким номером уже есть или у вас нет доступа");
    }

    if(isset($_GET['action']) && @$_GET['action'] == 'delete') {
        
        if($jwt_response->data->group=='admin') {
            if($lecture->delete($lecture->id)) send_message(true, "Успешно удалено!"); else send_message(false, "Произошла ошибка удаления!");
        } else send_message(false, "Ошибка! У вас нет доступа");
          
    }

 }