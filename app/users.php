<?php
// include database and object files
include_once 'config/core.php';
include_once 'objects/user.php';

// get database connection
$database = new Database();
$db = $database->getConnection(); 
// prepare user object
$user = new User($db);

$data = json_decode(file_get_contents("php://input"));

if(isset($data->id)){ $user->id = htmlspecialchars(strip_tags($data->id)); }
if(isset($data->username)){ $user->username = htmlspecialchars(strip_tags($data->username)); }
if(isset($data->password)){ $user->password = htmlspecialchars(strip_tags($data->password)); }
if(isset($data->group)){ $user->group = htmlspecialchars(strip_tags($data->group)); }
if(isset($data->firstname)){ $user->firstname = htmlspecialchars(strip_tags($data->firstname)); }
if(isset($data->secondname)){ $user->secondname = htmlspecialchars(strip_tags($data->secondname)); }
if(isset($data->lastname)){ $user->lastname = htmlspecialchars(strip_tags($data->lastname)); }
if(isset($data->email)){  $user->email = htmlspecialchars(strip_tags($data->email)); }

if(isset($_GET['action']) && @$_GET['action'] == 'list')   {
    print_r($user->list()); 
    die();  
}

// Only with auth responses

$jwt_response = validate_jwt();

 if($jwt_response->status && $jwt_response->data->group=='admin'){
        
    // Создание пользователя
        if(isset($_GET['action']) && @$_GET['action'] == 'create')   {

            if (!empty($user->username) &&
                !empty($user->password) &&
                !empty($user->group) &&
                !empty($user->firstname) &&
                !empty($user->lastname) &&
                !empty($user->secondname) &&
                !empty($user->email) &&
                $user->create()) {
                    send_message(true, "Пользователь был создан");
                } else {
                    send_message(true, "Ошибка создания пользователя");
                }
        }


    }
