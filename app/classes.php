<?php
// include database and object files
include_once 'config/core.php';
include_once 'objects/edu_class.php';

// get database connection
$database = new Database();
$db = $database->getConnection(); 

$class = new EduClass($db);

$data = json_decode(file_get_contents("php://input"));

$class->id = $_GET['id'] ?? 0;

$class->course_id = $data->course_id ?? 0;
$class->time_start = $data->time_start ?? 0;
$class->time_end = $data->time_end ?? 0;
$class->day_of_week = $data->day_of_week ?? 0;
$class->week_type = $data->week_type ?? 0;
$class->group_number = $data->group_number ?? 0;
$class->room_id = $data->room_id ?? 0;
$class->moodle_forum_topic = $data->moodle_forum_topic ?? 0;
$class->moodle_forum_end_messages = $data->moodle_forum_end_messages ?? 0;
$class->tabel = $data->tabel ?? 0;

$jwt_response = validate_jwt();
$class->user_group = $jwt_response->data->group ?? 0;
$class->user_real_id = $jwt_response->data->id;

    if($jwt_response->status &&  $jwt_response->data->group=='admin') {
        $class->user_id = $data->user_id ?? $jwt_response->data->id;
        } else {
        $class->user_id = $jwt_response->data->id ?? 0;
    }

$class->year = date('Y');

    if(in_array((int)date('m'), [8,9,10,11,12,1])) {
        $class->semester = 1;
    } else {
        $class->semester = 2;
    }

if(isset($_GET['action']) && @$_GET['action'] == 'count')   {
    print_r($class->class_count());
}

if(isset($_GET['action']) && @$_GET['action'] == 'list')   {
    print_r($class->list());
}

if(isset($_GET['action']) && @$_GET['action'] == 'show')   {
    print_r($class->show($class->id));
}


// Only with auth responses

 if($jwt_response->status &&  ($jwt_response->data->group=='admin' || $jwt_response->data->group=='instructor')){

    // Create topic
    if(isset($_GET['action']) && @$_GET['action'] == 'create')   {
        if($class->create()) send_message(true, "Успешно добавлено!"); else send_message(false, "Ошибка! Возможно у вас нет доступа ".$class->error[2]);
    }

    // Update topic
    if(isset($_GET['action']) && @$_GET['action'] == 'update')   {
        if($class->update($class->id)) send_message(true, "Успешно обновлено!"); else send_message(false, "Ошибка! Возможно у вас нет доступа ".$class->error[2]);
    }

    if(isset($_GET['action']) && @$_GET['action'] == 'delete') {

            if($class->delete($class->id)) send_message(true, "Успешно удалено!"); else send_message(false, "Ошибка! Возможно у вас нет доступа ".$class->error[2]);
          
    }
 }