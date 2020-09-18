<?php
include_once 'config/config.php';
include_once 'objects/user.php';

// подключение файлов jwt
include_once 'libs/php-jwt-master/src/BeforeValidException.php';
include_once 'libs/php-jwt-master/src/ExpiredException.php';
include_once 'libs/php-jwt-master/src/SignatureInvalidException.php';
include_once 'libs/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;

// получаем соединение с базой данных
$database = new Database();
$db = $database->getConnection();

// создание объекта 'User'
$user = new User($db);
 
// получаем данные
$data = json_decode(file_get_contents("php://input"));
 
// устанавливаем значения
if( !empty($data->email)) {
    $user->email = $data->email;
    $email_exists = $user->login();

// Generate hash - dev mode only
//echo password_hash($data->password, PASSWORD_BCRYPT); 

    // существует ли электронная почта и соответствует ли пароль тому, что находится в базе данных
    if ($email_exists && password_verify($data->password, $user->password) ) {
    
        $token = array(
        "iss" => JWT_ISS,
        "aud" => JWT_AUD,
        "iat" => JWT_IAT,
        "nbf" => JWT_NBF,
        "data" => array(
            "id" => $user->id,
            "firstname" => $user->firstname,
            "secondname" => $user->secondname,
            "lastname" => $user->lastname,
            "group" => $user->group,
            "email" => $user->email,
            "useragent" => $_SERVER['HTTP_USER_AGENT'],
        )
        );
    
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_group'] = $user->group;
        $_SESSION['user_firstname'] = $user->firstname;
        $_SESSION['user_secondname'] = $user->secondname;
        $_SESSION['user_lastname'] = $user->lastname;
        $_SESSION['user_email'] = $user->email;

        // код ответа
        http_response_code(200);
    
        // создание jwt
        $jwt = JWT::encode($token, JWT_KEY);
        echo json_encode(
            array(
                "message" => "Успешный вход в систему.",
                "jwt" => $jwt
            )
        );
    
    }
    
    // Если электронная почта не существует или пароль не совпадает,
    // сообщим пользователю, что он не может войти в систему
    else {
    http_response_code(401);
    echo json_encode(array("message" => "Ошибка входа. Неправильный логин или пароль"));
    }
}