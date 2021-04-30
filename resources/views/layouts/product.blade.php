<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Ecommerce Laravel">
    <meta name="keywords" content="Fashi, unica, creative, html">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css?family=Muli:300,400,500,600,700,800,900&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/themify-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('css/elegant-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('css/owl.carousel.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/nice-select.css') }}" rel="stylesheet">
    <link href="{{ asset('css/jquery-ui.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/slicknav.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head>

<body>
    <!-- Page Preloder -->
    {{-- <div id="preloder">
        <div class="loader"></div>
    </div> --}}
    <div class="wrapper" id="app">
        <?php
            $routeName = Route::current()->getName();
            $baseUrl = env('APP_URL');
            ?>
   
            <!-- Header Section Begin -->
            <header class="header-section">
                <div class="header-top">
                    <div class="container">
                        <div class="ht-left">
                            <div class="phone-service">
                                <i class="ti-facebook"></i>
                            </div>
                            <div class="phone-service">
                                <i class="ti-instagram"></i>
                            </div>
                            <div class="phone-service">
                                -
                            </div>
                            <div class="phone-service">
                                <i class=" fa fa-phone"></i>
                                081289482090
                            </div>
                            <div class="phone-service">
                                <a href="<?= $baseUrl; ?>">
                                    <img class="img-responsive" style="height: 30px !important;" src="<?= $baseUrl.'/img/green-store.png'; ?>" alt="">
                                </a>
                            </div>
                        </div>
                        <div class="ht-right">
                            @guest
                                <a href="{{ route('login') }}" class="login-panel"><i class="fa fa-user"></i>Login</a>

                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="login-panel"><i
                                            class="fa fa-registered"></i>Register &nbsp;&nbsp;&nbsp;</a>
                                @endif
                            @else
                                <a href="{{ route('logout') }}" class="login-panel" onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();">
                                    <i class="fa fa-sign-out"></i>{{ __('Logout') }}</a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                                <a href="#" class="login-panel"><i class="fa fa-user-circle-o"></i>{{ Auth::user()->name }}
                                    &nbsp;&nbsp;&nbsp; </a>
                            @endguest
                        </div>
                    </div>
                </div>
            </header>

            <header class="header-section">
                <div class="nav-item">
                    <div class="container">
                        <nav class="nav-menu mobile-menu">
                            <ul>
                                <li><a href="#">Kategori Produk</a>
                                    <ul class="dropdown">
                                        <?php
                                            foreach ($categories as $key => $category) {
                                                ?>
                                                    <li><a href="<?= '/category/'.$category->slug ?>">
                                                        <?=$category->name; ?>    
                                                    </a></li> 
                                                <?php
                                            }
                                        ?>
                                    </ul>
                                </li>
                                <li class="<?php if ($routeName == 'product.detail' || $routeName == 'user.products') echo 'active'; ?>"><a href="<?= $baseUrl.'/products'; ?>">Products</a></li>
                                <li class="<?php if ($routeName == 'user.orders') echo 'active'; ?>"><a href="<?= $baseUrl.'/orders'; ?>">Orders</a></li>
                                <li><a href="#">About Us</a>
                                    <ul class="dropdown">
                                        <li><a href="<?= $baseUrl.'/contact-us'; ?>">Contact Us</a></li> 
                                        <li><a href="<?= $baseUrl.'/testimony'; ?>">Testimony</a></li> 
                                    </ul>
                                </li>
                            </ul>
                        </nav>
                        <div id="mobile-menu-wrap"></div>
                    </div>
                </div>
            </header>
            <main class="py-4">
                @yield('content')
            </main>
        <!-- Header End -->
    </div>

    <!-- REQUIRED SCRIPTS -->
    <script src="/js/app.js"></script>    

    <!-- Js Plugins -->

    <script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('js/jquery.countdown.min.js') }}"></script>
    <script src="{{ asset('js/jquery.nice-select.min.js') }}"></script>
    <script src="{{ asset('js/jquery.zoom.min.js') }}"></script>
    <script src="{{ asset('js/jquery.dd.min.js') }}"></script>
    <script src="{{ asset('js/jquery.slicknav.js') }}"></script>
    
    <script src="{{ asset('js/owl.carousel.min.js') }}"></script>
    
    <script src="{{ asset('js/main.js') }}"></script>
    <script>
        let elMobileMenu = $(".slicknav_menutxt");
        if (!elMobileMenu.is(":hidden")){
            $("#mobile-menu-wrap").css("padding-top", "40px");
        }
    </script>
</body>
</html>
