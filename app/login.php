<?php
include_once 'config/core.php';
include_once 'objects/auth.php';

use \Firebase\JWT\JWT;
// Load Composer's autoloader
require '../vendor/autoload.php';

// получаем соединение с базой данных
$database = new Database();
$db = $database->getConnection();

// создание объекта 
$user = new Auth($db);
 
// получаем данные
$data = json_decode(file_get_contents("php://input"));

if( !empty($data->authcode)) {
    // set authcode
    $data->authcode = htmlspecialchars(strip_tags($data->authcode));;
    setcookie("authcode", $data->authcode, ['expires'=>0, 'path'=>'/', 'domain'=>$ini_array['APP_CONFIG']['APP_URL'], 'httponly'=>true, 'secure'=>true, 'SameSite'=>'Strict']);
    http_response_code(200);
    echo json_encode(array("message" => "Код проверки установлен. Необходимо войти заново"));
} else {
// устанавливаем значения
if( !empty($data->email)) {
    $user->email = $data->email;
    $email_exists = $user->login();

// Generate hash - dev mode only
//echo password_hash($data->password, PASSWORD_BCRYPT); 

    // существует ли электронная почта и соответствует ли пароль тому, что находится в базе данных
    if ($email_exists && password_verify($data->password, $user->password) ) {
        
   
        $user->auth_code = isset($_COOKIE['authcode']) ? $_COOKIE['authcode'] : "";
        $isAuthcode = $user->check_authcode();
        
        if($isAuthcode) {

        $nextWeek = time() + (7 * 24 * 60 * 60);
        
        $token = array(
        "iss" => 'https://'.$ini_array['APP_CONFIG']['APP_URL'],
        "aud" => 'https://'.$ini_array['APP_CONFIG']['APP_URL'],
        "iat" => time(),
        "exp" => $nextWeek,
        "data" => array(
            "id" => $user->user_id,
            "firstname" => $user->firstname,
            "secondname" => $user->secondname,
            "lastname" => $user->lastname,
            "group" => $user->group,
            "email" => $user->email,
            "session_id" => $user->session_id,
        )
        );
    
        //$_SESSION['user_id'] = $user->id;
        //$_SESSION['user_group'] = $user->group;
        //$_SESSION['user_firstname'] = $user->firstname;
        //$_SESSION['user_secondname'] = $user->secondname;
        //$_SESSION['user_lastname'] = $user->lastname;
        //$_SESSION['user_email'] = $user->email;

        // код ответа
        http_response_code(200);
    
        // создание jwt
        $jwt = JWT::encode($token, $ini_array['JWT_CONFIG']['JWT_KEY']);
        
        //header("Set-Cookie: jwt=".$jwt."; secure; httpOnly");
        setcookie("jwt", $jwt,['expires'=>0, 'path'=>'/', 'domain'=>$ini_array['APP_CONFIG']['APP_URL'], 'httponly'=>true, 'secure'=>true, 'SameSite'=>'Strict']);
        
        
        
        $message = "Успешный вход в систему";

        echo json_encode(array( "message" => $message ));

        write_log($user->user_id, $message);

        } else {
           
            if($user->send_authcode($user->email, $user->firstname)){
                http_response_code(401);
                echo json_encode(array( "status" => "authcode", "message" => "Код отправлен на вашу почту"));
                write_log($user->user_id, 'Запрос дополнительной проверки');
            } else {
                echo json_encode(array( "status" => "error", "message" => "Ошибка дополнительной проверки"));
                write_log($user->user_id, 'Ошибка дополнительной проверки');
            }
           
            
        }
    }
    
    // Если электронная почта не существует или пароль не совпадает,
    // сообщим пользователю, что он не может войти в систему
    else {
        $message = "Ошибка входа. Неправильный логин или пароль";
        http_response_code(401);
        echo json_encode(array( "message" => $message ));
        write_log($user->user_id, $message);
    }
}

}