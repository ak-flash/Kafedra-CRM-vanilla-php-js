<?php
session_start();

if (isset($_GET['logout'])) {
  session_destroy();
  unset($_COOKIE["jwt"]);
}

if (isset($_SESSION['user_id'])) {
  header('Location: index.php');
  exit;
}
// In JS
//var jwt = getCookie('jwt');
//$.post("api/validate_token.php", JSON.stringify({ jwt:jwt })).done(function(data) {
  //toastr.success(data.message);
  //window.location.href = 'index.php';
//});

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Kafedra | Log in</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="assets/css/adminlte.min.css">
    <!-- Toastr -->
    <link rel="stylesheet" href="plugins/toastr/toastr.css"> 
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  
    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- Serializejson -->
    <script src="plugins/jquery.serializejson.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Toastr -->
    <script src="plugins/toastr/toastr.min.js"></script>
    <!-- Fingerprint2 -->
    <script src="plugins/fingerprint2.min.js"></script>
    <!-- App script -->
    <script src="assets/js/app.js"></script>
    <!-- reCaptcha -->
    <script src="https://www.google.com/recaptcha/api.js?render=6LcS480ZAAAAAAvb6gQyvJG1xgM8KO1BI-ZTpC9-"></script>

<script>
// удаление jwt
setCookie("jwt", "", 1);
  
  $.getJSON('api/users.php?action=list', function(data) {
    $('#username').empty();
    $('#username').append('<option value="">Выберите...</option>');
    $.each(data, function(key, val) {
      $('#username').append('<option value="' + val.email + '">' + val.lastname + ' ' + val.firstname + ' ' + val.secondname + '</option>');
    });
});
  
$(function() {
  $("form").submit(function(event) {
    event.preventDefault();
    var data = JSON.stringify($('form').serializeJSON());

    $.ajax({
    type: 'POST',
    url: '/api/login.php',
    data: data, // or JSON.stringify ({name: 'jonas'}),
    contentType: "application/json",
    dataType: 'json',
    success: function(data) { 
      toastr.success(data.message);
      setCookie("jwt", data.jwt, 1);
      window.location.href = 'index.php';
    },
    error: function (data) {
      result = JSON.parse(data.responseText);  
      $('#password').addClass('is-invalid');
      toastr.error(result['message']);
    }
});

  

  
    
    
  });
});


function clearError() {
  $('#password').removeClass('is-invalid');
}
</script>
</head>
<body class="hold-transition login-page">

<div class="login-box text-center">


    <div class="card col-11">
    <div class="card-body login-card-body">
    <img src="assets/logo.png" class="img-rounded" style="width:90%">
    <hr>
  
    
      <p class="login-box-msg"><b>Авторизация</b></p>
        <form action="#" method="POST">
          <div class="input-group mb-3">
            <select class="custom-select mr-sm-2" id="username" name="email" onchange="clearError()" required>
            </select>
          </div>
          <div class="row">
            <div class="col-7">
              <div class="input-group mb-2">
                <input type="password" class="form-control" placeholder="Пароль" name="password" id="password" onfocus="clearError()" required>
                <div class="input-group-append">
                  <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-5">
              <button type="submit" class="btn btn-primary btn-block g-recaptcha"data-sitekey="reCAPTCHA_site_key" 
        data-callback='onSubmit' 
        data-action='submit'>Вход</button>
            </div>

          </div>
        </form>
      </div>
    </div>

</div>
</body>
</html>