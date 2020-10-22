<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Travelmart</title>

  <link rel="stylesheet" href="/css/app.css">
  <link rel="stylesheet" href="/css/simple-sidebar.css">
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
    {{-- <div class="form-inline ml-3">
      <div class="input-group input-group-sm">
        <input class="form-control form-control-navbar" @keyup.enter="searchit" v-model="search" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-navbar" @click="searchit">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </div>
    </div> --}}
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <!-- Sidebar -->
  <div class="bg-light border-right" id="sidebar-wrapper">
    <div class="sidebar-heading">Start Bootstrap </div>
    <div class="list-group list-group-flush">
      <a href="#" class="list-group-item list-group-item-action bg-light">Dashboard</a>
      <a href="#" class="list-group-item list-group-item-action bg-light">Shortcuts</a>
      <a href="#" class="list-group-item list-group-item-action bg-light">Overview</a>
      <a href="#" class="list-group-item list-group-item-action bg-light">Events</a>
      <a href="#" class="list-group-item list-group-item-action bg-light">Profile</a>
      <a href="#" class="list-group-item list-group-item-action bg-light">Status</a>
    </div>
  </div>
  <!-- /#sidebar-wrapper -->

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        aaa
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
