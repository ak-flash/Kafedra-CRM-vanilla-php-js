<?php include('header.view.php'); ?>

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
          <span class="d-none d-md-inline">&nbsp;&nbsp;&nbsp;<?=$jwt_response->data->lastname.' '.$jwt_response->data->firstname?></span>
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
            <a href="profile" class="btn btn-warning">Профиль</a>
            <a href="api/logout" class="btn btn-danger float-right">Выход</a>
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
<a href="main" class="user-panel mt-3 pb-3 mb-3 d-flex">
      <img src="assets/logo-m.png"
           class="ml-3 brand-image img-circle"
           style="opacity: .8; width:45px;">
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
                <a href="lectures" class="nav-link<?php if(@$_GET['page']=='lectures') echo ' active'; ?>">
                  <i class="fas fa-users nav-icon"></i>
                  Лекции
                <span class="badge badge-info right" id="lectures_count">0</span>
                </a>
              </li>
              <li class="nav-item">
                <a href="classes" class="nav-link<?php if(@$_GET['page']=='classes') echo ' active'; ?>">
                  <i class="fas fa-users nav-icon"></i>
                  Занятия
                <span class="badge badge-info right" id="classes_count">0</span>
                </a>
              </li>
              <li class="nav-item">
                <a href="order" class="nav-link<?php if(@$_GET['page']=='order') echo ' active'; ?>">
                  <i class="far fa-calendar-alt nav-icon"></i>
                  Табель
                  <span class="badge badge-success right" id="tabel_days_count">0</span>
                </a>
              </li>

        <?php if(isAdmin()): ?>
                <li class="nav-item">
                    <a href="courses" class="nav-link<?php if(@$_GET['page']=='courses') echo ' active'; ?>">
                        <i class="fas fa-users nav-icon"></i>
                        Дисциплины
                    </a>
                </li>

                <li class="nav-item">
                    <a href="users" class="nav-link<?php if(@$_GET['page']=='users') echo ' active'; ?>">
                        <i class="fas fa-users nav-icon"></i>
                        Сотрудники
                    </a>
                </li>
        <?php endif ?>

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

                  <p>Вопросы</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="test_keys" class="nav-link<?php if(@$_GET['page']=='test_keys') echo ' active'; ?>">
                  <i class="fas fa-key nav-icon"></i>
                  <p>Ответы и печать</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-item has-treeview<?php if(substr($_GET['page'],0, 4)=='topi'||substr($_GET['page'],0, 4)=='lect') echo ' menu-open'; ?>">
              <hr style="background-color: grey;color: grey"><a href="#" class="nav-link">
              <i class="nav-icon fas fa-book-open"></i>
              <p>
              Тематич. планы
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="topics" class="nav-link<?php if(@$_GET['page']=='topics') echo ' active'; ?>">
                    <i class="nav-icon fas fa-book-open"></i>
                    <p>Темы занятий</p>
                  </a>
              </li>
                  <li class="nav-item">
                    <a href="lectures" class="nav-link<?php if(@$_GET['page']=='lectures') echo ' active'; ?>">
                      <i class="nav-icon fas fa-book-open"></i>
                      <p>Темы лекций</p>
                    </a>
              </li>
            </ul>
          </li>      
          
          <li class="nav-item has-treeview<?php if(substr($_GET['page'],0, 4)=='zoom') echo ' menu-open'; ?>">
              <hr style="background-color: grey;color: grey">
                <a href="#" class="nav-link">
                <i class="nav-icon fas fa-book-open"></i>
                <p>
                Лекции в Zoom
                  <i class="fas fa-angle-left right"></i>
                </p>
              </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="zoom_report" class="nav-link<?php if(@$_GET['page']=='zoom_report') echo ' active'; ?>">
                    <i class="nav-icon fas fa-book-open"></i>
                    <p>Отчёт посещения</p>
                  </a>
              </li>
                  <li class="nav-item">
                    <a href="zoom_create" class="nav-link<?php if(@$_GET['page']=='zoom_create') echo ' active'; ?>">
                      <i class="nav-icon fas fa-book-open"></i>
                      <p>Запланировать</p>
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
            <a href="points" class="nav-link<?php if(@$_GET['page']=='points') echo ' active'; ?>">
              <i class="nav-icon fas fa-file"></i>
              <p>Рейтинг (рассчёт)</p>
            </a>
          </li>


         <!-- <li class="nav-item has-treeview">
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
              </li> -->
              
            </ul>
          </li>
          
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <?php include('app/views/layouts/scripts.view.php'); ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

    <?php include($page); ?>

  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer text-center">
    <div class="float-right d-none d-sm-block">
      Версия <b>0.75</b>
    </div>
   Кафедра Иммунологии и аллергологии &copy; <?=date('Y')?> ВолгГМУ
  </footer>

  <!-- Боковая панель -->
  <aside class="control-sidebar control-sidebar-dark p-3">
    
  <h4>Что то и здесь</h4>
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<script>
toastr.options = {
  "progressBar": true
}


getClassesCount();

// Get count of days until classes "tabel" report end
let element = document.getElementById("tabel_days_count");

<?php
    $origin = date_create("now");
    $target = date_create(date('Y').'-'.date('m', strtotime("+1 month")).'-09');
    $interval = date_diff($origin, $target);
    if($interval->days < 4) echo 'element.classList.remove("badge-success");element.classList.add("badge-danger");';
    echo "$('#tabel_days_count').html('".$interval->days." дн');";
?>

</script>
</body>
</html>
