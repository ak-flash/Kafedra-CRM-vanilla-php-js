<?php include('header.view.php'); ?>

<body class="hold-transition layout-top-nav" style="padding-right: 0px !important;overflow-y: hidden !important;">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-light navbar-white">
    <div class="container">
      <a href="main" class="navbar-brand">
        <img src="assets/logo.png" alt="ВолгГМУ Logo" class="brand-image img-circle elevation-3"
             style="opacity: .8">
        <span class="brand-text font-weight-light">ВолгГМУ</span>
      </a>

      <!-- Left navbar links -->
      <ul class="navbar-nav">
        <li class="nav-item">
        </li>

        <li class="nav-item d-none d-sm-inline-block">
          <a href="#" class="nav-link">Контакты</a>
        </li>
       
      </ul>

      

      <!-- Right navbar links -->
      <ul class="navbar-nav ml-auto">
        
        <!-- Notifications Dropdown Menu -->
        <li class="nav-item dropdown">
          <a class="nav-link" data-toggle="dropdown" href="#">
            <i class="far fa-bell"></i>
            <span class="badge badge-warning navbar-badge">3</span>
          </a>
          <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
            <span class="dropdown-header">3 Уведомления</span>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item">
              <i class="fas fa-envelope mr-2"></i> 4 новых сообщения
              <span class="float-right text-muted text-sm">3 мин</span>
            </a>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item">
              <i class="fas fa-users mr-2"></i> 8 новых поручения
              <span class="float-right text-muted text-sm">12 часов</span>
            </a>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item">
              <i class="fas fa-file mr-2"></i> 3 новых лекции
              <span class="float-right text-muted text-sm">2 дня</span>
            </a>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item dropdown-footer">Посмотреть ВСЕ уведомления</a>
          </div>
        </li>

<!-- Профиль пользователя -->

          <div class="p-2"><?php echo $jwt_response->data->lastname.' '.$jwt_response->data->firstname;?></div>

        <li class="nav-item">
          <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#"><i
              class="fas fa-th-large"></i></a>
        </li>
      </ul>
     <a href="api/logout" class="btn btn-warning">Выход</a>
    </div>
  </nav>
  <!-- /.navbar -->

  <?php include('app/views/layouts/scripts.view.php'); ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper container-fluid">
  <?php echo getTemplate($page); ?>
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
    <div class="p-3">
      <h5>Что-то</h5>
      <p>добавить что-нибудь сюда</p>
    </div>
  </aside>
  <!-- /.control-sidebar -->

  <!-- Main Footer -->
  <footer class="main-footer text-center">
  <div class="float-right d-none d-sm-block">
      Версия <b>0.4</b>
    </div>
   Кафедра Иммунологии и аллергологии &copy; <?php echo date('Y');?> ВолгГМУ
  </footer>
</div>
<!-- ./wrapper -->


</body>
</html>
