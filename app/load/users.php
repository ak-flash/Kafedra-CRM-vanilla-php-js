<?php
// include database and object files
include_once '../objects/user.php';

// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare user object
$user = new User($db);

if(isset($_GET['action'])){
    switch($_GET['action']) {
        case 'list':
            echo $user->list();
            break;
        case 'login':
            $user->username = isset($_POST['username']) ? $_POST['username'] : die();
            $user->password = isset($_POST['password']) ? $_POST['password'] : die();
            echo $user->login();
            break; 
    }
    
}
