<?php
set_include_path( get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] );
require $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';

$ini_array = parse_ini_file("config.ini", true);
// заголовки
if(isset($views_name) && $views_name=='index'){
    // header for index...
} else {
    header("Access-Control-Allow-Origin: https://".$ini_array['APP_CONFIG']['APP_URL']);
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
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
    return json_decode(include("app/validate_token.php"));
}


function write_log($user_id, $text) {
    $database = new Database();
    $database->getConnection();
    
    $query = "INSERT INTO logs
                    SET
                        user_id = :user_id,
                        ip = :ip,
                        text = :text";
    
    $stmt = $database->conn->prepare($query);
    // привязываем значения
    $ip = get_client_ip();
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':text', $text);
    $stmt->bindParam(':ip', $ip);

    if($stmt->execute()) {
        return true;
    }
    return false;
}

// Function to get the client IP address
function get_client_ip() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
       $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}