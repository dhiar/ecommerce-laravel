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
<div id="app">
  <div class="d-flex" id="wrapper">

    <!-- Sidebar -->
    <div class="bg-light border-right" id="sidebar-wrapper" style="">
      <div class="sidebar-heading">OTHER MENU </div>
      <div class="list-group list-group-flush">
        <a href="#" class="list-group-item list-group-item-action bg-light">OUR EVENTS</a>
        <a href="#" class="list-group-item list-group-item-action bg-light">BLOG UPDATES</a>
        <a href="#" class="list-group-item list-group-item-action bg-light">CONTACT US</a>
      </div>
    </div>
    <!-- /#sidebar-wrapper -->

    <!-- Page Content -->
    <div id="page-content-wrapper">

      <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
        {{-- <button class="btn btn-primary" id="menu-toggle">Toggle Menu</button> --}}
        <button class="btn btn-light" id="menu-toggle">
          <span class="navbar-toggler-icon"></span>
        </button>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav ml-auto mt-2 mt-lg-0">
            <li class="nav-item active">
              <a class="nav-link" href="#">TRAVELMART</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">PARTICIPANTS</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">REGISTRATION</a>
            </li>
          </ul>
        </div>
      </nav>

      <div class="container-fluid">    
        <router-view></router-view>
      </div>
    </div>
    <!-- /#page-content-wrapper -->
  </div>
  <!-- /#wrapper -->
</div>


<!-- REQUIRED SCRIPTS -->
<script src="/js/app.js"></script>
<!-- Menu Toggle Script -->
<script>
  $("#menu-toggle").click(function(e) {
    e.preventDefault();
    $("#wrapper").toggleClass("toggled");
  });
</script>
</body>
</html>
