<?php
$ini_array = parse_ini_file("config.ini", true);
// заголовки
if(isset($views_name) && $views_name=='index'){
    // header for index...
} else {
    header("Access-Control-Allow-Origin: https://".$ini_array['APP_CONFIG']['APP_URL']);
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
}
// показывать сообщения об ошибках
error_reporting(E_ALL);

// установить часовой пояс по умолчанию
date_default_timezone_set('Europe/Volgograd');


// SQL base credentals config
define("DB_HOST", $ini_array['DB_CONFIG']['DB_HOST']);
define("DB_NAME", $ini_array['DB_CONFIG']['DB_NAME']);
define("DB_USERNAME", $ini_array['DB_CONFIG']['DB_USERNAME']);
define("DB_PASSWORD", $ini_array['DB_CONFIG']['DB_PASSWORD']);

include_once 'database.php';

function send_message($status, $text) {
    if($status) http_response_code(200); else http_response_code(400);
    echo json_encode(array("message" => $text));
}

function validate_jwt($index = null) {
    if(isset($_COOKIE["jwt"])){ 
       $jwt = $_COOKIE["jwt"];  
    } else {
        header('Location: login.php');
        exit;
    }
    if($index=='index') $path = 'app/'; else $path = '';
    return json_decode(include($path."validate_token.php"));
}