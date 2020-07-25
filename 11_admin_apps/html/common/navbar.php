<?php
session_start();

$userName = $_SESSION['userName'];
$function = $_SESSION['function'];

?>

<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-fixed-top">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <a href="../000_system/000_dashboard.php" class="nav-link">Home</a>
    </li>
  </ul>

  <!-- SEARCH FORM -->
  <form class="form-inline ml-3">
    <div class="input-group input-group-sm">
      <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
      <div class="input-group-append">
        <button class="btn btn-navbar" type="submit">
          <i class="fas fa-search"></i>
        </button>
      </div>
    </div>
  </form>

  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto">
    <li class="nav-item dropdown">
      <a class="nav-link" data-toggle="dropdown" href="#">
        <i class="fas fa-sign-out"></i><?php echo $groupName . " " . $userName; ?>
      </a>
      <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <a href="javascript:void(0)" onclick="if(window.confirm('ログアウトしますか？')){location.href = '/index.php';}" class="dropdown-item">
          <i class="fas fa-sign-out-alt mr-2"></i> ログアウト
        </a>
        <div class="dropdown-divider"></div>
        <a href="../000_system/005_password.php" class="dropdown-item">
          <i class="fas fa-key mr-2"></i> パスワード変更
        </a>
      </div>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#">
        <i class="fas fa-th-large"></i>
      </a>
    </li>


  </ul>
</nav>
<!-- /.navbar -->


<!-- Main Sidebar Container -->
<aside class="main-sidebar elevation-4">
  <!-- Brand Logo -->
  <a href="../000_system/000_dashboard.php" class="brand-link" data-toggle="tooltip" data-placement="bottom">
    <img src="../images/logo.png" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light">予約くん</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
                with font-awesome or any other icon font library -->


        <!--　###################################################################################### -->
        <li class="nav-item has-treeview menu-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-clinic-medical"></i>
            <p>
              店舗管理
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="../900_shop/900_shop.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>店舗一覧</p>
              </a>
            </li>
          </ul>

        </li>


        <!--　###################################################################################### -->
        <li class="nav-item has-treeview menu-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-tools"></i>
            <p>
              システム管理
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="../000_system/001_calendar.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>カレンダー設定</p>
              </a>
            </li>
          </ul>

          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="../000_system/003_staff.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>スタッフ管理</p>
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