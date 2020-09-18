<?php
session_start();

// include database and object files
include_once '../config/database.php';
include_once 'topics.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare object
$topic = new Topics($db);
 
// query 

// set doctor property values
$topic->id = $_POST['topic_id'];
$topic->t_number = $_POST['t_number'];
$topic->t_name = $_POST['t_name'];
$topic->semester = $_POST['semester'];
$topic->course_id = $_POST['course_id'];


if(isset($_GET['action'])&&@$_GET['action']=='update'&&@$_POST['topic_id']!=0){
    
// 
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

 }
 


 if(isset($_GET['action'])&&@$_GET['action']=='create'){
    
    // 
    if($topic->create()){
        $topic_arr=array(
            "status" => true,
            "message" => "Успешно добавлено!",
            "topic_id" => $topic->id,
        );
    }
    else {
        $topic_arr=array(
            "status" => false,
            "message" => "Ошибка! Возможно тема с таким номером уже есть"
        );
    }
    print_r(json_encode($topic_arr));
    
     }

if(isset($_GET['action'])&&@$_GET['action']=='delete'&&@$_POST['topic_id']!=0){
    
// 
if($topic->delete()){
    $topic_arr=array(
        "status" => true,
        "message" => "Успешно удалено!"
    );
}
else{
    $topic_arr=array(
        "status" => false,
        "message" => "Ошибка!"
    );
}
print_r(json_encode($topic_arr));

 }



?>