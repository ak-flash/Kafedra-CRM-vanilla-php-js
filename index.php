<?php
session_start();

if (!isset($_SESSION['user_id'])) {
      header('Location: login.php');
      exit;
}

function getTemplate($file, $template_params = array()) {

      ob_start(); // start output buffer
      extract($template_params);

      include $file;
      $template = ob_get_contents(); // get contents of buffer
      ob_end_clean();
      
      return $template;
}

if($_SESSION['user_group']!='laborant'){

      if(isset($_GET['page'])) {
            
            $p = trim($_GET['page']);
            $p = strip_tags($p);
            $p = htmlspecialchars($p);

      switch ($p) {
            case 'test_editor':
                  $page='template/test_editor.html';
                  break;
            case 'test_checker':
                  $page='template/test_checker.html';
                  break;
            case 'test_keys':
                  $page='template/test_keys.html';
                  break;
            case 'classes':
                  $page='template/classes.html';
                  break;
            case 'docs':
                  $page='template/docs.html';
                  break;
            case 'order':
                  $page='template/order.html';
                  break;
            case 'profile':
                  $page='template/profile.html';
                  break;
            case 'topics':
                  $page='template/topics.html';
                  break;
            case 'points':
                  $page='template/points.html';
                  break;
            }

      } else {
            $page='template/main.html';
      }
      
include('api/objects/template.php');

} else {
      $page='template/test_checker.html';
      include('api/objects/template_laborant.php');
}