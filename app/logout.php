<?php

//session_destroy();
unset($_COOKIE["jwt"]);
    
header('Location: ../login.php');
exit;
