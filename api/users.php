<?php
// include database and object files
include_once 'config/config.php';
include_once 'objects/user.php';

// get database connection
$database = new Database();
$db = $database->getConnection(); 
// prepare user object
$user = new User($db);

$data = json_decode(file_get_contents("php://input"));

if(isset($_GET['action']) && @$_GET['action'] == 'list')   {
    print_r($user->list());   
}

if(isset($_GET['action']) && @$_GET['action'] == 'create')   {
    // Создание пользователя
    // устанавливаем значения
    $user->username = htmlspecialchars(strip_tags($data->username));
    $user->password = htmlspecialchars(strip_tags($data->password));
    $user->group = htmlspecialchars(strip_tags($data->group));
    $user->firstname = htmlspecialchars(strip_tags($data->firstname));
    $user->secondname = htmlspecialchars(strip_tags($data->secondname));
    $user->lastname = htmlspecialchars(strip_tags($data->lastname));
    $user->email = htmlspecialchars(strip_tags($data->email));

    if (
        !empty($user->username) &&
        !empty($user->password) &&
        !empty($user->group) &&
        !empty($user->firstname) &&
        !empty($user->lastname) &&
        !empty($user->secondname) &&
        !empty($user->email) &&
        $user->create()
    ) {
        http_response_code(200);
        echo json_encode(array("message" => "Пользователь был создан."));
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "Ошибка создания пользователя."));
    }
}