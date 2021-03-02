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
     <div id="preloder">
        <div class="loader"></div>
    </div>
    <div id="app">
        <?php
            $routeName = Route::current()->getName();
            $baseUrl = env('APP_URL');
            ?>
        @if (
            $routeName == 'login' ||
            $routeName == 'register' || 
            $routeName == 'product.detail'
        )
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

            @if ($routeName == 'product.detail')
                <header class="header-section">
                    {{-- <div class="header-top">
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
                            </div>
                            <div class="ht-right">
                                @guest
                                    <a href="{{ route('login') }}" class="login-panel"><i class="fa fa-user"></i>Login</a>

                                    @if (Route::has('register'))
                                        <a href="{{ route('register') }}" class="login-panel"><i class="fa fa-registered"></i>Register &nbsp;&nbsp;&nbsp;</a>
                                    @endif
                                @else
                                    <a href="{{ route('logout') }}" class="login-panel"
                                        onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">    
                                    <i class="fa fa-sign-out"></i>{{ __('Logout') }}</a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                    <a href="#" class="login-panel"><i class="fa fa-user-circle-o"></i>{{ Auth::user()->name }} &nbsp;&nbsp;&nbsp; </a>
                                @endguest
                            </div>
                        </div>
                    </div> --}}
                    <div class="container">
                        <div class="inner-header">
                            <div class="row">
                                <div class="col-lg-2 col-md-2">
                                    <div class="logo">
                                        <a href="<?= $baseUrl; ?>">
                                            <img src="<?= $baseUrl.'/img/fashi.png'; ?>" alt="">
                                        </a>
                                    </div>
                                </div>
                                <div class="col-lg-7 col-md-7">
                                    <div class="advanced-search" style="border: 0px !important;">
                                        <button type="button" class="category-btn">Search</button>
                                        <div class="input-group">
                                            <input type="text" class="text-gray-dark" placeholder="What do you need?">
                                            <button type="button"><i class="ti-search"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 text-right col-md-3">
                                    <ul class="nav-right">
                                        <li class="cart-icon">
                                            <a href="#">
                                                <i class="icon_bag_alt"></i>
                                                <span>3</span>
                                            </a>
                                            <div class="cart-hover">
                                                <div class="select-items">
                                                    <table>
                                                        <tbody>
                                                            <tr>
                                                                <td class="si-pic"><img src="img/select-product-1.jpg" alt=""></td>
                                                                <td class="si-text">
                                                                    <div class="product-selected">
                                                                        <p>Rp. 60.000 x 1</p>
                                                                        <h6>Product 1</h6>
                                                                    </div>
                                                                </td>
                                                                <td class="si-close">
                                                                    <i class="ti-close"></i>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="si-pic"><img src="img/select-product-2.jpg" alt=""></td>
                                                                <td class="si-text">
                                                                    <div class="product-selected">
                                                                        <p>Rp. 85.000 x 1</p>
                                                                        <h6>Product 2</h6>
                                                                    </div>
                                                                </td>
                                                                <td class="si-close">
                                                                    <i class="ti-close"></i>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="select-total">
                                                    <span>total:</span>
                                                    <h5>Rp. 155.000</h5>
                                                </div>
                                                <div class="select-button">
                                                    <a href="#" class="primary-btn view-card">VIEW CARD</a>
                                                    <a href="#" class="primary-btn checkout-btn">CHECK OUT</a>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="cart-price">Rp. 155.000</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="nav-item">
                        <div class="container">
                            <nav class="nav-menu mobile-menu">
                                <ul>
                                    <li><a href="#">Kategori Produk</a>
                                        <ul class="dropdown">
                                            <?php
                                                foreach ($categories as $key => $category) {
                                                    ?>
                                                        <li><a href="<?= '/category-product/'.$category->slug ?>">
                                                            <?=$category->name; ?>    
                                                        </a></li> 
                                                    <?php
                                                }
                                            ?>
                                        </ul>
                                    </li>
                                    <li><a href="<?= $baseUrl.'/products'; ?>">Products</a></li>
                                    <li><a href="<?= $baseUrl.'/orders'; ?>">Orders</a></li>
                                    <li><a href="<?= $baseUrl.'/payments'; ?>">Payment</a></li>
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
            @endif
            <main class="py-4">
                @yield('content')
            </main>
        @else 
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
                        </div>
                        <div class="ht-right">
                            @guest
                                <a href="{{ route('login') }}" class="login-panel"><i class="fa fa-user"></i>Login</a>

                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="login-panel"><i class="fa fa-registered"></i>Register &nbsp;&nbsp;&nbsp;</a>
                                @endif
                            @else
                                <a href="{{ route('logout') }}" class="login-panel"
                                    onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">    
                                <i class="fa fa-sign-out"></i>{{ __('Logout') }}</a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                                <a href="#" class="login-panel"><i class="fa fa-user-circle-o"></i>{{ Auth::user()->name }} &nbsp;&nbsp;&nbsp; </a>
                            @endguest
                        </div>
                    </div>
                </div>
                <div class="container">
                    <div class="inner-header">
                        <div class="row">
                            <div class="col-lg-2 col-md-2">
                                <div class="logo">
                                    <a href="./">
                                        <img src="img/fashi.png" alt="">
                                    </a>
                                </div>
                            </div>
                            <div class="col-lg-7 col-md-7">
                                <div class="advanced-search" style="border: 0px !important;">
                                    <button type="button" class="category-btn">Search</button>
                                    <div class="input-group">
                                        <input type="text" class="text-gray-dark" placeholder="What do you need?">
                                        <button type="button"><i class="ti-search"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 text-right col-md-3">
                                <ul class="nav-right">
                                    <li class="cart-icon">
                                        <a href="#">
                                            <i class="icon_bag_alt"></i>
                                            <span>3</span>
                                        </a>
                                        <div class="cart-hover">
                                            <div class="select-items">
                                                <table>
                                                    <tbody>
                                                        <tr>
                                                            <td class="si-pic"><img src="img/select-product-1.jpg" alt=""></td>
                                                            <td class="si-text">
                                                                <div class="product-selected">
                                                                    <p>$60.00 x 1</p>
                                                                    <h6>Kabino Bedside Table</h6>
                                                                </div>
                                                            </td>
                                                            <td class="si-close">
                                                                <i class="ti-close"></i>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="si-pic"><img src="img/select-product-2.jpg" alt=""></td>
                                                            <td class="si-text">
                                                                <div class="product-selected">
                                                                    <p>$60.00 x 1</p>
                                                                    <h6>Kabino Bedside Table</h6>
                                                                </div>
                                                            </td>
                                                            <td class="si-close">
                                                                <i class="ti-close"></i>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="select-total">
                                                <span>total:</span>
                                                <h5>$120.00</h5>
                                            </div>
                                            <div class="select-button">
                                                <a href="#" class="primary-btn view-card">VIEW CARD</a>
                                                <a href="#" class="primary-btn checkout-btn">CHECK OUT</a>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="cart-price">$150.00</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="nav-item">
                    <div class="container">
                        <nav class="nav-menu mobile-menu">
                            <ul>
                                <li><a href="#">Kategori Produk</a>
                                    <ul class="dropdown">
                                        <?php
                                            foreach ($categories as $key => $category) {
                                                ?>
                                                    <li><a href="<?= '/category-product/'.$category->slug ?>">
                                                        <?=$category->name; ?>    
                                                    </a></li> 
                                                <?php
                                            }
                                        ?>
                                    </ul>
                                </li>
                                <li><a href="./products">Products</a></li>
                                <li><a href="./orders">Orders</a></li>
                                <li><a href="./orders">Payment</a></li>
                                <li><a href="./contact-us">Contact Us</a>
                                    <ul class="dropdown">
                                        <li><a href="./contact-us">Contact Us</a></li> 
                                        <li><a href="./testimony">Testimony</a></li> 
                                    </ul>
                                </li>
                            </ul>
                        </nav>
                        <div id="mobile-menu-wrap"></div>
                    </div>
                </div>
            </header>

            <section class="hero-section">
                <div class="hero-items owl-carousel">
                    <??>

                    @foreach ($slides as $key => $slide)
                    {{-- {{ print_r($slide) }} --}}
                        <div class="single-hero-items set-bg" data-setbg="<?= $slide->image ?>">
                            <div class="container">
                                <div class="row">
                                    <div class="col-lg-5">
                                        <span>
                                            <?= $slide->relationships->product->relationships->category->name; ?>
                                        </span>
                                        <a href="<?= $slide->url; ?>">
                                            <h1> <?= $slide->title; ?></h1>
                                            <p> <?= $slide->description; ?></p>
                                        </a>
                                        <?php // urlencode();?>
                                        <a href="<?= "./product/".htmlentities($slide->relationships->product->slug); ?>" class="primary-btn">Shop Now</a>
                                    </div>
                                </div>
                                <div class="off-card">
                                    <h2>Ongkir Free 30% <span></span></h2>
                                </div>
                            </div>
                        </div>
                    @endforeach
                        
                    {{-- pohon-kayuputih-ntt-amfoang.jpeg --}}
                    {{-- <div class="single-hero-items set-bg" data-setbg="img/slides/pohon-kayuputih-ntt-amfoang.PNG" style="width:auto;">
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-5">
                                    <span>Madu, Kesehatan</span>
                                    <h1>Madu Hutan NTT</h1>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor
                                        incididunt ut labore et dolore</p>
                                    <a href="#" class="primary-btn">Shop Now</a>
                                </div>
                            </div>
                            <div class="off-card">
                                <h2>Ongkir Free 30% <span></span></h2>
                            </div>
                        </div>
                    </div>
                    <div class="single-hero-items set-bg" data-setbg="img/slides/madu-randu.png">
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-5">
                                    <span>Madu, Kesehatan</span>
                                    <h1>Madu Randu, Jepara</h1>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor
                                        incididunt ut labore et dolore</p>
                                    <a href="#" class="primary-btn">Shop Now</a>
                                </div>
                            </div>
                            <div class="off-card">
                                <h2>Ongkir Free 30% <span></span></h2>
                            </div>
                        </div>
                    </div>
                    <div class="single-hero-items set-bg" data-setbg="img/slides/all_mini.png">
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-5">
                                    <span>Bag,kids</span>
                                    <h1>Black friday</h1>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor
                                        incididunt ut labore et dolore</p>
                                    <a href="#" class="primary-btn">Shop Now</a>
                                </div>
                            </div>
                            <div class="off-card">
                                <h2>Sale <span>50%</span></h2>
                            </div>
                        </div>
                    </div> --}}
                </div>
            </section>
        @endif

        
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
</body>
</html>
