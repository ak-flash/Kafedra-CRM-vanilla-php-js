<?php include('app/views/layouts/header.view.php'); ?>

<body class="hold-transition login-page">

<div class="login-box text-center">


    <div class="card col-12">
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
              <button type="submit" id="btn_submit" class="btn btn-primary btn-block g-recaptcha"data-sitekey="reCAPTCHA_site_key" data-callback='onSubmit' data-action='submit'>Войти</button>
            </div>

          </div>
        </form>
      </div>
    </div>

</div>


<div class="modal modal-warning fade align-center" id="modal-authcode" data-backdrop="static">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
            <div class="modal-body text-center">            

            <div class="row p-3">
              <div class="col-6">
                Введите <b>проверочный код</b>, отправленный на вашу почту
              </div>
              <div class="col-3">
                <input type="text" class="form-control mt-1" id="authcode" name="authcode" value="">
              </div>
              <div class="col-3">
                <button type="button" class="btn btn-primary mt-1" onclick="checkAuthcode($('#authcode').val())">Проверить</button>
              </div>
            </div>

              </div>         
            </div>
          </div>
        </div>



<?php include('app/views/layouts/scripts.view.php'); ?>

    <script src="https://www.google.com/recaptcha/api.js?render=6LcS480ZAAAAAAvb6gQyvJG1xgM8KO1BI-ZTpC9-"></script>
    <!-- fingerprint2 -->
    <!-- <script src="assets/plugins/fingerprint2.min.js"></script> -->
<script>

$.getJSON('api/users/list', function(data) {
  $('#username').empty();
  $('#username').append('<option value="">Выберите...</option>');
  $.each(data, function(key, val) {
    $('#username').append('<option value="' + val.email + '">' + val.lastname + ' ' + val.firstname + ' ' + val.secondname + '</option>');
  });
});
  
$(function() {
  $("form").submit(function(event) {

    $('#btn_submit').prop({'disabled' : true});

    event.preventDefault();
    var data = JSON.stringify($('form').serializeJSON());

    $.ajax({
    type: 'POST',
    url: '/api/login',
    data: data,
    contentType: "application/json",
    dataType: 'json',
    success: function(data) { 
      if(data.status=='error') {
          toastr.error(data.message);
      } else {
          toastr.success(data.message);
          window.location.href = 'main';
      }

    },
    error: function (data) {
      result = JSON.parse(data.responseText);  
      
      if(result['status']=='authcode'){
        
        $('#modal-authcode').modal();
        $('#authcode').trigger('focus');
        toastr.info(result['message']);
      
      } else {
        
        $('#password').addClass('is-invalid');
        toastr.error(result['message']);
      }    
      
    },
    complete: function () {
      $('#btn_submit').prop({'disabled' : false});
    }

    }); 
  });
});

function checkAuthcode(authcode) {
  $.post( "api/login", JSON.stringify({ 'authcode' : authcode }))
    .done(function(data) {
      $("form").submit();    
    })
    .fail(function(data) {
      result = JSON.parse(data.responseText);  
      toastr.error(result['message']);
    })
    .always(function() {
        $('#modal-authcode').modal('hide');

    });
  
}


function clearError() {
  $('#password').removeClass('is-invalid');
}

</script>
</body>
</html>