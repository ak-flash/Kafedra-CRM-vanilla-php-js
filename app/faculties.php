<?php
// include database and object files
include_once 'config/core.php';
include_once 'objects/faculty.php';

// get database connection
$database = new Database();
$db = $database->getConnection(); 

// prepare user object
$faculty = new Faculty($db);

$data = json_decode(file_get_contents("php://input"));

if(isset($_GET['id'])){ $faculty->id = (int) $_GET['id']; }

if(isset($data->semester)){ $faculty->semester = htmlspecialchars(strip_tags($data->semester)); } else $faculty->semester = 0;
if(isset($data->faculty)){ $faculty->faculty = htmlspecialchars(strip_tags($data->faculty)); } else $faculty->faculty = 0;

if(isset($_GET['action']) && @$_GET['action'] == 'list')   {
    print_r($faculty->list());  
}

if(isset($_GET['action']) && @$_GET['action'] == 'courses')   {
    print_r($faculty->courses($faculty->semester));  
}
