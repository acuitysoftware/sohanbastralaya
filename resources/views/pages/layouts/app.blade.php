<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>{{ $title }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('public/assets/images/favicon.ico') }}">

    <!-- App css -->
    <link href="{{ asset('public/assets/css/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('public/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('public/assets/css/app-modern.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('public/assets/vendor/fancybox/jquery.fancybox.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('public/assets/vendor/lightbox/css/lightbox.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('public/admin_assets/vendors/general/toastr/build/toastr.min.css') }}" rel="stylesheet"
        type="text/css" />
    <style>
        body {
            visibility: hidden;
        }

        .modal {
            overflow: scroll !important;
        }
        .order_list td{
            background: #ffff6f;
                color: #000;
        }
    </style>

</head>
@yield('css')

<body data-layout="Psdbox">
    @livewireStyles
    <div id="loader" class="center"></div>

    @include('pages.includes.header')
    <!-- Start Content-->
    <div class="container-fluid">
        <div class="wrapper">
            @include('pages.includes.side_menu')
            <!-- content-page -->
            @php
                    
                $user_type = Auth::user()->type == 'A' ? 'admin' : 'user';
                if (Auth::user()->type == 'A') {
                    $storeUser = 1;
                    $store = Session::get('store');
                    if ($store) {
                        $storeUser = $store;
                    }
                } else {
                    $storeUser = Auth::user()->store;
                }

                if ($storeUser == 1) {
                    $cartItemCount = App\Models\CartItem::where('user_id', Auth::user()->id)->count();
                } else {
                    $cartItemCount = App\Models\CartItem2::where('user_id', Auth::user()->id)->count();
                }
                $show = 0;
                if (Route::is('product_index')) {
                    $show = 1;
                } elseif (Route::is('add_to_cart')) {
                    $show = 1;
                }
            @endphp
            <div class="content-page">
                @yield('content')
                <!-- Cart Button -->
                @if ($show != '1')
                    <a href="{{ route('add_to_cart') }}" class="btn btn-primary btn-lg cart-btn " role="button"
                        title="" data-toggle="tooltip" data-original-title="cart" id="cart_home">
                        <i class="uil-shopping-cart-alt" id="h_cart">
                            @if (isset($cartItemCount))
                                {{ $cartItemCount }}
                            @endif
                        </i>
                    </a>
                @endif
                <!-- Cart Button -->
                @include('pages.includes.footer')
            </div>
            <!-- content-page -->
        </div>
    </div>

    @livewireScripts

    <!-- Js-->
    <script src="{{ asset('public/assets/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('public/assets/js/theme.min.js') }}"></script>
    <script src="{{ asset('public/assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('public/assets/js/bootstrap.bundle.min.js') }}"></script>
    <!-- file upload -->
    <script src="{{ asset('public/assets/js/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('public/assets/js/myalert.js') }}"></script>
    <!-- file upload -->
    <script src="{{ asset('public/assets/js/dropzone.min.js') }}"></script>
    <script src="{{ asset('public/assets/js/component.fileupload.js') }}"></script>
    <!-- file upload -->
    <!-- fancybox -->
    <script src="{{ asset('public/assets/vendor/fancybox/jquery.fancybox.min.js') }}"></script>
    <script src="{{ asset('public/assets/vendor/lightbox/js/lightbox.min.js') }}"></script>
    <script src="{{ asset('public/admin_assets/vendors/general/toastr/build/toastr.min.js') }}" type="text/javascript">
    </script>
    @include('pages.includes.script')
    @yield('script')
    <script>
        $(window).on('load', function() {
            document.body.style.visibility = 'visible';
            $('#loader').fadeOut(300);
        });
        /* $(window).on('load', function () {
          $('#loader').fadeOut();
        }); */
        /* document.onreadystatechange = function() {
            if (document.readyState !== "complete") {
                document.querySelector(
                  "body").style.visibility = "hidden";
                document.querySelector(
                  "#loader").style.visibility = "visible";
            } else {
                document.querySelector(
                  "#loader").style.display = "none";
                document.querySelector(
                  "body").style.visibility = "visible";
            }
        }; */
        $('.from-amount').keypress(function(event) {
            console.log('pddlld');
            if (((event.which != 46 || (event.which == 46 && $(this).val() == '')) ||
                    $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
        }).on('paste', function(event) {
            event.preventDefault();
        });
    </script>
</body>

</html>
