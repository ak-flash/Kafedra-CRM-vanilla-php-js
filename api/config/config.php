<?php
session_start();
// показывать сообщения об ошибках
error_reporting(E_ALL);

// установить часовой пояс по умолчанию
date_default_timezone_set('Europe/Volgograd');

// SQL base credentals config
define("DB_HOST", "localhost");
define("DB_NAME", "kaf");
define("DB_USERNAME", "root");
define("DB_PASSWORD", "");

include_once 'database.php';

// переменные, используемые для JWT
define("JWT_KEY", "secret_kafedri");
define("JWT_ISS", "https://kafedra.test");
define("JWT_AUD", "https://kafedra.test");
define("JWT_IAT", 1600373122);
define("JWT_NBF", 1600373122);

// заголовки
header("Access-Control-Allow-Origin: ".JWT_ISS);
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
