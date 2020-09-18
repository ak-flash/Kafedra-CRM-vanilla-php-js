<?php
// include database and object files
include_once 'config/config.php';
include_once 'objects/topic.php';

// get database connection
$database = new Database();
$db = $database->getConnection(); 

$topic = new Topic($db); 

if(isset($_POST['topic_id'])){
// set doctor property values
$topic->id = $_POST['topic_id'];
$topic->t_number = $_POST['t_number'];
$topic->t_name = $_POST['t_name'];
$topic->semester = $_POST['semester'];
$topic->course_id = $_POST['course_id'];
}

if(isset($_GET['action'])) {
    switch($_GET['action']) {
        case 'list':
            print_r($topic->list((int)$_GET['faculty'], (int)$_GET['semester']));
            break;
        case 'listshort':
            print_r($topic->listshort((int)$_GET['faculty'], (int)$_GET['semester']));
            break;
        case 'show':
            print_r($topic->show((int)$_GET['id']));
            break;
        case 'update':
            if($topic->update((int)$_POST['topic_id'])){
                $status = true;
                $error_message = "Успешно обновлено!";
            } else {
                $status = false;
                $error_message = "Ошибка! Возможно тема с таким номером уже есть";
            }
            $topic_message=array(
                "status" => $status,
                "message" => $error_message,
            );
            print_r(json_encode($topic_message));
            break;
    }  
}