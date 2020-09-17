<?php
    session_start();

    if (isset($_GET['logout'])) {
        session_destroy();
    }

    if (isset($_SESSION['user_id'])) {
      header('Location: index.php');
    exit;
}

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
  <link rel="stylesheet" href="assert/css/adminlte.min.css">
  <!-- Toastr -->
  <link rel="stylesheet" href="plugins/toastr/toastr.css"> 
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  
  <!-- jQuery -->
  <script src="plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- Toastr -->
  <script src="plugins/toastr/toastr.min.js"></script>

<script>
  
$.getJSON('app/load/users.php?action=list', function(data) {
    $('#username').empty();
    $('#username').append('<option value="">Выберите...</option>');
    $.each(data['users'], function(key, val) {
      $('#username').append('<option value="' + val.username + '">' + val.surname + ' ' + val.name + '</option>');
    });
});
  
$(function() {
  $("form").submit(function(event) {
    event.preventDefault();
    var data = $('form').serializeArray();

    $.ajax({
      type: "POST",
      url: "app/load/users.php?action=login",
      dataType: 'json',
        data: data,
        error: function (result) {
            
            alert(result.responseText);

        },
        success: function (result) {
            
            if (result['status'] == true) {
              toastr.success(result['message']);
              window.location.href = 'index.php';
            } else {
              $('#password').addClass('is-invalid');
              toastr.error(result['message']);
            }

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
  <!-- /.login-logo -->
  <img src="assert/logo.png" class="img-rounded mb-3" style="opacity: .8;">
             
<div class="login-box text-center">

<div class="login-logo">
        
   <h3>Кафедра <b>Иммунологии и Аллергологии</b></h3>
  </div>

  <div class="card col-11 ml-3">
    <div class="card-body login-card-body">
      <p class="login-box-msg"><b>Авторизация</b></p>

      <form action="#" method="POST">
        <div class="input-group mb-3">
          
          <select class="custom-select mr-sm-2" id="username" name="username" onchange="clearError()" required>
          </select>

        </div>
        
        <div class="row">
          <div class="col-7">
          
           <div class="input-group mb-2">
                <input type="password" class="form-control" placeholder="Пароль" name="password" id="password" onfocus="clearError()">
                <div class="input-group-append">
                  <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                  </div>
                </div>
              </div>
          </div>
          <!-- /.col -->
          <div class="col-5">
            <button type="submit" class="btn btn-primary btn-block">Вход</button>
          </div>
          <!-- /.col -->
        </div>

      </form>


      
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->


</body>
</html>