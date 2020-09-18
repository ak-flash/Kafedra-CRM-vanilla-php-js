<?php
include_once 'config/config.php';

// требуется для декодирования JWT
include_once 'libs/php-jwt-master/src/BeforeValidException.php';
include_once 'libs/php-jwt-master/src/ExpiredException.php';
include_once 'libs/php-jwt-master/src/SignatureInvalidException.php';
include_once 'libs/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;
 
// получаем значение веб-токена JSON
$data = json_decode(file_get_contents("php://input"));

// получаем JWT
$jwt=isset($data->jwt) ? $data->jwt : "";

// если JWT не пуст
if($jwt) {
 
    // если декодирование выполнено успешно, показать данные пользователя
    try {
        // декодирование jwt
        $decoded = JWT::decode($jwt, JWT_KEY, array('HS256'));

        // код ответа
        http_response_code(200);
 
        // показать детали
        echo json_encode(array(
            "status" => true,
            "message" => "Доступ разрешен.",
            "data" => $decoded->data
        ));
 
    }
 
    // если декодирование не удалось, это означает, что JWT является недействительным
    catch (Exception $e){

        http_response_code(401);

        echo json_encode(array(
            "status" => false,
            "message" => "Доступ закрыт.",
            "error" => $e->getMessage()
        ));
    }
}
 
// показать сообщение об ошибке, если jwt пуст
else {

    http_response_code(401);
    echo json_encode(array("status" => false, "message" => "Доступ запрещён."));
}