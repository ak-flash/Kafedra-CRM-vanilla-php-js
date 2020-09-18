<?php
session_start();

// include database and object files
include_once '../../config/database.php';
include_once 'quiz.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare object
$quiz = new Quiz($db);
 
// query 

// set doctor property values
$quiz->id = $_POST['quiz_id'];
$quiz->question = $_POST['quiz_question'];
$quiz->good = $_POST['quiz_good'];
$quiz->bad1 = $_POST['quiz_bad1'];
$quiz->bad2 = $_POST['quiz_bad2'];
$quiz->bad3 = $_POST['quiz_bad3'];
if(isset($_POST['quiz_topic1'])) $quiz->tag_topic1 = $_POST['quiz_topic1'];
if(isset($_POST['quiz_topic2'])) $quiz->tag_topic2 = $_POST['quiz_topic2'];

if(isset($_POST['tag_lech'])) $quiz->tag_lech = $_POST['tag_lech'];
if(isset($_POST['tag_ped'])) $quiz->tag_ped = $_POST['tag_ped'];
if(isset($_POST['tag_mbf3'])) $quiz->tag_mbf3 = $_POST['tag_mbf3'];
if(isset($_POST['tag_mbf4'])) $quiz->tag_mbf4 = $_POST['tag_mbf4'];
if(isset($_POST['tag_stom'])) $quiz->tag_stom = $_POST['tag_stom'];
if(isset($_POST['tag_mpd'])) $quiz->tag_mpd = $_POST['tag_mpd'];
if(isset($_POST['tag_lecheng'])) $quiz->tag_lecheng = $_POST['tag_lecheng'];
if(isset($_POST['tag_stomeng'])) $quiz->tag_stomeng = $_POST['tag_stomeng'];


if(isset($_GET['action'])&&@$_GET['action']=='update'&&@$_POST['quiz_id']!=0){
    
// 
if($quiz->update()){
    $quiz_arr=array(
        "status" => true,
        "message" => "Успешно обновлено!"
    );
}
else{
    $quiz_arr=array(
        "status" => false,
        "message" => "Ошибка!"
    );
}
print_r(json_encode($quiz_arr));

 }
 


 if(isset($_GET['action'])&&@$_GET['action']=='create'){
    
    // 
    if($quiz->create()){
        $quiz_arr=array(
            "status" => true,
            "message" => "Успешно добавлено!",
            "quiz_id" => $quiz->id,
        );
    }
    else{
        $quiz_arr=array(
            "status" => false,
            "message" => "Ошибка! Такой вопрос уже есть в базе"
        );
    }
    print_r(json_encode($quiz_arr));
    
     }

if(isset($_GET['action'])&&@$_GET['action']=='delete'&&@$_POST['quiz_id']!=0){
    
// 
if($quiz->delete()){
    $quiz_arr=array(
        "status" => true,
        "message" => "Успешно удалено!"
    );
}
else{
    $quiz_arr=array(
        "status" => false,
        "message" => "Ошибка!"
    );
}
print_r(json_encode($quiz_arr));

 }



?>