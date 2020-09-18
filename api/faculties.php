<?php
// include database and object files
include_once 'config/config.php';
include_once 'objects/faculty.php';

// get database connection
$database = new Database();
$db = $database->getConnection(); 

// prepare user object
$faculty = new Faculty($db);

if(isset($_GET['action'])) {
    switch($_GET['action']) {
        case 'list':
            print_r($faculty->list());
            break;
    }  
}