<?php
$views_dir = 'app/views/';
$views_name = 'index';

include_once 'app/config/core.php';


function getTemplate($file, $template_params = array()) {

      ob_start(); // start output buffer
      extract($template_params);

      include $file;
      $template = ob_get_contents(); // get contents of buffer
      ob_end_clean();
      
      return $template;
}

$jwt_response = validate_jwt($views_name);


if($jwt_response->status){

      if(isset($_GET['page'])) {
            
            $p = trim($_GET['page']);
            $p = strip_tags($p);
            $p = htmlspecialchars($p);

            $page = $views_dir.$p.'.view.php';
        
      } else $page = $views_dir.'main.view.php';
      
      if(!file_exists($page)) {
            // Page not found
            header('Location: '.$ini_array['APP_CONFIG']['APP_URL'].'/404.html');
            exit;
      } else {
            
            if($jwt_response->data->group=='admin' || $jwt_response->data->group=='instructor'){
                  include($views_dir.'layouts/main.view.php');
            } else {
                  $page = $views_dir.'test_checker.view.php';
                  include($views_dir.'layouts/main_laborant.view.php');
            }
      }

} else {
      header('Location: login.php');
      exit;
  }
