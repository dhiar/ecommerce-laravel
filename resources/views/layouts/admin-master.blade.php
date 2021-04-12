<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->

<?php
use App\Transformers\AdminTransformer;
use App\Admin;

$admin = fractal(Auth::user(), AdminTransformer::class)->toArray()['data']
?>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>E-commerce Laravel</title>
	<link rel="stylesheet" href="/css/app.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper" id="app">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

    <!-- SEARCH FORM -->
    <div class="form-inline ml-3">
      <div class="input-group">
        <input class="form-control form-control-navbar" @keyup.enter="searchit" v-model="search" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-navbar" @click="searchit">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </div>
    </div>

    <ul class="navbar-nav ml-auto">
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="fa fa-user-circle"></i>
        <span class="badge badge-warning" style="padding: 7px; font-size:12px;">{{Auth::user()->name}}</span>
          <span class="badge badge-danger" style="padding: 7px; font-size:12px;">
          {{ $admin["relationships"]["user_type"]["name"] }}
          </span>
        </a>
        <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
          <a href="/admin/profile" class="dropdown-item">
            <i class="fas fa-user-alt mr-2"></i> Profile
          </a>
          <div class="dropdown-divider"></div>
          <a href="{{ route('admin.logout') }}"
          onclick="event.preventDefault();
            document.getElementById('logout-form').submit();" class="dropdown-item">
            <i class="nav-icon fa fa-power-off red mr-2"></i> Logout
          </a>
        </div>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
      
      <img src="<?php echo env('APP_URL').'/img/logo.png'; ?>" alt="E-commerce Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">E-commerce Admin</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="<?php echo env('APP_URL').'/img/profile.png'; ?>" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">
            {{ Auth::user()->name }}
          </a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item">
            <router-link to="/admin/dashboard" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt blue"></i>
              <p>
                Dashboard
              </p>
            </router-link>
          </li>
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->

          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-universal-access green"></i>
              <p>
                Basic
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              @can('isSuperOrAdmin')
              <li class="nav-item">
                <router-link to="/admin/category" class="nav-link">
                  <i class="fas fa-fw fa-tag nav-icon"></i>
                  <p>Category</p>
                </router-link>
              </li>
              @endcan

              @can('isSuperOrAdmin')
              <li class="nav-item">
                <router-link to="/admin/brand" class="nav-link">
                  <i class="fas fa-fw fa-award nav-icon"></i>
                  <p>Brand</p>
                </router-link>
              </li>
              @endcan

              <li class="nav-item">
                <router-link to="/admin/product" class="nav-link">
                  <i class="fas fa-fw fa-box-open nav-icon"></i>
                  <p>Product</p>
                </router-link>
              </li>

              @can('isSuperOrAdmin')
              <li class="nav-item">
                <router-link to="/admin/promo" class="nav-link">
                  <i class="fas fa-fw fa-fire nav-icon"></i>
                  <p>Promo</p>
                </router-link>
              </li>
              <li class="nav-item">
                <router-link to="/admin/testimony" class="nav-link">
                  <i class="fas fa-fw fa-comments nav-icon"></i>
                  <p>Testimony</p>
                </router-link>
              </li>
              <li class="nav-item">
                <router-link to="/admin/page" class="nav-link">
                  <i class="fas fa-fw fa-file nav-icon"></i>
                  <p>Page</p>
                </router-link>
              </li>
              <li class="nav-item">
                <router-link to="/admin/setting" class="nav-link">
                  <i class="fas fa-cog nav-icon"></i>
                  <p>Setting</p>
                </router-link>
              </li>
              @endcan

              <li class="nav-item">
                <router-link to="/admin/profile" class="nav-link">
                  <i class="nav-icon fas fa-user"></i>
                  <p>
                    Profile
                  </p>
                </router-link>
              </li>
            </ul>
          </li>

          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa fa-tasks green"></i>
              <p>
                Management
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>

            @can('isSuperOrAdmin')
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <router-link to="/admin/users" class="nav-link">
                  <i class="fas fa-users nav-icon"></i>
                  <p>Users</p>
                </router-link>
              </li>
            </ul>
            @endcan

            <ul class="nav nav-treeview">
              <li class="nav-item">
                <router-link to="/admin/orders" class="nav-link">
                  <i class="fas fa-shopping-cart nav-icon"></i>
                  <p>Orders</p>
                </router-link>
              </li>
            </ul>
          </li>

          @can('isSuperAdmin')
          <li class="nav-item">
            <router-link to="/admin/developer" class="nav-link">
              <i class="nav-icon fas fa-cogs"></i>
              <p>
                Developer
              </p>
            </router-link>
          </li>
          @endcan
          
          <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.logout') }}"
              onclick="event.preventDefault();
                document.getElementById('logout-form').submit();">
              <i class="nav-icon fa fa-power-off red"></i>
              <p>
              {{ __('Logout') }}
              </p>
            </a>

            <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <router-view></router-view>
        <!-- set progressbar -->
        <vue-progress-bar></vue-progress-bar>
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Main Footer -->
  <footer class="main-footer">
    <!-- To the right -->
    <div class="float-right d-none d-sm-inline">
      Anything you want
    </div>
    <!-- Default to the left -->
    <strong>Copyright &copy; 2014-2019 <a href="https://adminlte.io">AdminLTE.io</a>.</strong> All rights reserved.
  </footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->
<script src="/js/app.js"></script>
</body>
</html>
