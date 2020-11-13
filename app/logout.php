<?php
$ini_array = parse_ini_file("config/config.ini", true);
//session_destroy();

if(!empty($_GET["mode"]) && @$_GET["mode"]=='reset') {
    unset($_COOKIE["authcode"]);
    setcookie ('authcode', '', 1,'/', $ini_array['APP_CONFIG']['APP_URL'], false, true);
}

unset($_COOKIE["jwt"]);
setcookie ('jwt', '', 1,'/', $ini_array['APP_CONFIG']['APP_URL'], false, true);
header('Location: ../login.php');
exit;