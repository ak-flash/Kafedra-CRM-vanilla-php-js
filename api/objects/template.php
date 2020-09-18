<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Кафедра "Иммунологии и аллергологии"</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Select2 -->
  <link rel="stylesheet" href="assets/css/select2.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="assets/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <!-- Toastr -->
  <link rel="stylesheet" href="plugins/toastr/toastr.css">
  
  <!-- REQUIRED SCRIPTS -->
  
  <!-- jQuery -->
  <script src="plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- overlayScrollbars -->
  <script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
  <!-- Select2 -->
  <script src="plugins/select2.min.js"></script>
  <!-- AdminLTE App -->
  <script src="assets/js/adminlte.min.js"></script>
  <!-- Toastr -->
  <script src="plugins/toastr/toastr.min.js"></script>
  <!-- App script -->
  <script src="assets/js/app.js"></script>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<!-- Site wrapper -->
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-dark navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
      </li>
   
    </ul>

    <!-- SEARCH FORM 
    <form class="form-inline ml-3">
      <div class="input-group input-group-sm">
        <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-navbar" type="submit">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </div>
    </form>-->
<div id="page-title" class="text-white"></div>
    
    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto"> 
      
              <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-bell"></i>
          <span class="badge badge-warning navbar-badge">5</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header">5 уведомлений</span>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-envelope mr-2"></i> 4 чего-то
            <span class="float-right text-muted text-sm">3 мин</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-users mr-2"></i> 8 чего-то
            <span class="float-right text-muted text-sm">12 часа</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-file mr-2"></i> 3 чего-то
            <span class="float-right text-muted text-sm">2 дня</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">Показать все уведомления</a>
        </div>
      </li>
      
      <!-- Профиль пользователя -->
    <li class="nav-item dropdown user-menu">
        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
          <img src="assets/img/avatar5.png" class="user-image img-circle elevation-2" alt="User Image">
          <span class="d-none d-md-inline">&nbsp;&nbsp;&nbsp;<?php echo $_SESSION['user_lastname'].' '.$_SESSION['user_firstname'];?></span>
        </a>
        <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <!-- User image -->
          <li class="user-header bg-secondary">
            <img src="assets/img/avatar5.png" class="img-circle elevation-2" alt="User Image">

            <p>
              Доцент - 1 ставка
              <small>На кафедре с 2013 года</small>
            </p>
          </li>
         
          <!-- Menu Footer-->
          <li class="user-footer">
            <a href="profile&id=<?php echo $_SESSION['user_id'];?>" class="btn btn-warning">Профиль</a>
            <a href="login.php?logout" class="btn btn-danger float-right">Выход</a>
          </li>
    </ul>

    
          <li class="nav-item">
        <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#">
          <i class="fas fa-th-large"></i>
        </a>
      </li> 
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    

    <div class="sidebar">
      <!-- Brand Logo -->
<a href="index.php" class="user-panel mt-3 pb-3 mb-3 d-flex">
      <img src="assets/logo-m.png"
           class="ml-3 brand-image img-circle elevation-3"
           style="opacity: .7; width:45px;">
      <h4 class="brand-text pt-2 ml-3 success">ВолгГМУ</h4>
    </a>
    <!-- Sidebar -->
      

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
       
               <li class="nav-item has-treeview<?php if(@$_GET['page']=='classes'||@$_GET['page']=='order') echo ' menu-open'; ?>">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
              Кафедра
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="classes" class="nav-link<?php if(@$_GET['page']=='classes') echo ' active'; ?>">
                  <i class="fas fa-users nav-icon"></i>
                  Занятия
                <span class="badge badge-info right">2</span>
                </a>
              </li>
              <li class="nav-item">
                <a href="order" class="nav-link<?php if(@$_GET['page']=='order') echo ' active'; ?>">
                  <i class="far fa-calendar-alt nav-icon"></i>
                  Табель
                  <span class="badge badge-info right">32 дн.</span>
                </a>
              </li>
            
            </ul>
          </li>


          


          <li class="nav-item has-treeview<?php if(substr($_GET['page'],0, 4)=='test') echo ' menu-open'; ?>">
              <hr style="background-color: grey;color: grey"><a href="#" class="nav-link">
              <i class="nav-icon fas fa-random"></i>
              <p>
              Тестовые задания
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="test_checker" class="nav-link<?php if(@$_GET['page']=='test_checker') echo ' active'; ?>">
                  <i class="fas fa-graduation-cap nav-icon"></i>
                  <p>Проверить</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="test_editor" class="nav-link<?php if(@$_GET['page']=='test_editor') echo ' active'; ?>">
                <i class="fas fa-puzzle-piece nav-icon"></i>

                  <p>Редактировать</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="test_keys" class="nav-link<?php if(@$_GET['page']=='test_keys') echo ' active'; ?>">
                  <i class="fas fa-key nav-icon"></i>
                  <p>Ответы и варианты</p>
                </a>
              </li>
            </ul>
          </li>

                 
          
         
          <li class="nav-header">Дополнительно</li>
          <li class="nav-item">
            <a href="docs" class="nav-link<?php if(@$_GET['page']=='docs') echo ' active'; ?>">
              <i class="nav-icon fas fa-file"></i>
              <p>Документы</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="topics" class="nav-link<?php if(@$_GET['page']=='topics') echo ' active'; ?>">
              <i class="nav-icon fas fa-book-open"></i>
              <p>Темы занятий</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="points" class="nav-link<?php if(@$_GET['page']=='points') echo ' active'; ?>">
              <i class="nav-icon fas fa-file"></i>
              <p>Рейтинг (рассчёт)</p>
            </a>
          </li>


          <li class="nav-item has-treeview">
              <hr style="background-color: grey;color: grey"><a href="#" class="nav-link">
              <i class="nav-icon far fa-envelope"></i>
              <p>
                Почта
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="mailbox/mailbox.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Входящие</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="mailbox/compose.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Исходящие</p>
                </a>
              </li>
              
            </ul>
          </li>
          
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    
    <?php include($page); ?>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer text-center">
    <div class="float-right d-none d-sm-block">
      Версия <b>0.5</b>
    </div>
   Кафедра Иммунологии и аллергологии &copy; <?php echo date('Y');?> ВолгГМУ
  </footer>

  <!-- Боковая панель -->
  <aside class="control-sidebar control-sidebar-dark p-3">
    
  <h4>Что то и здесь</h4>
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

</body>
</html>
