
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Sohan Bastralaya Admin</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{asset('public/assets/images/favicon.ico')}}">
    
    
    
        <!-- fancybox -->
        <link href="{{asset('public/assets/vendor/fancybox/jquery.fancybox.min.css')}}" rel="stylesheet" type="text/css" />
    
        <!-- Sweet alert -->
        <link href="{{asset('public/assets/css/sweetalert2.min.css')}}" rel="stylesheet" type="text/css" />
    
        <!-- App css -->
        <link href="{{asset('public/assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('public/assets/css/app-modern.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('public/admin_assets/vendors/general/toastr/build/toastr.min.css')}}" rel="stylesheet" type="text/css" />
    
    </head>

    <body class="authentication-bg" data-layout="Psdbox">
        <div class="account-pages pt-2 pt-sm-5 pb-4 pb-sm-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xxl-4 col-lg-5">
                        <div class="card">

                            <!-- Logo -->
                            <div class="card-header pt-4 pb-4 text-center bg-primary">
                                <a href="index.html">
                                    <span><img src="{{asset('storage/app/public/'.$siteSettings->site_logo) }}" alt=""></span>
                                </a>
                            </div>

                            <div class="card-body p-4">
                                
                                <div class="text-center w-75 m-auto">
                                    <h4 class="text-dark-50 text-center mt-0 fw-bold">Forgot Password</h4>
                                    <p class="text-muted mb-4">Enter your email address to Forgot Password.</p>
                                </div>

                                <form action="{{route('forgot_password_save')}}" method="post">
                                    @csrf

                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <div class="input-group input-group-merge">
                                            <input type="email" class="form-control" name="email" placeholder="Enter your email">
                                        </div>
                                            @error('email') <div class="error">{{ $message }}</div> @enderror
                                    </div>

                                    

                                    <div class="mb-3 mb-0 text-center">
                                        <button class="btn btn-primary" type="submit"> Reset </button>
                                    </div>

                                </form>
                            </div> <!-- end card-body -->
                        </div>
                        <!-- end card -->

                        <div class="row mt-3">
                            <div class="col-12 text-center">
                                <p class="text-muted"> <a href="{{route('login')}}" class="text-muted ms-1"><b>Sign In</b></a></p>
                            </div> <!-- end col -->
                        </div>
                        <!-- end row -->

                    </div> <!-- end col -->
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </div>
        <!-- end page -->

        <!-- php artisan make:mail ForgorPasswordMail --markdown=emails.ForgorPasswordMail -->
        <!-- Footer Start -->
        <footer class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6">
                        Sweet Bliss Massage ©
                    </div>
                    <div class="col-md-6">
                        <div class="text-md-end footer-links d-none d-md-block">
                            <a href="javascript::void(0);">About</a>
                            <a href="javascript::void(0);">Support</a>
                            <a href="javascript::void(0);">Contact Us</a>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- end Footer -->







    <!-- Js-->
    <script src="{{asset('public/assets/js/jquery-3.6.0.min.js')}}"></script>
    <script src="{{asset('public/assets/js/theme.min.js')}}"></script>
    <script src="{{asset('public/assets/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('public/assets/js/bootstrap.bundle.min.js')}}"></script>

    <!-- file upload -->
    <script src="{{asset('public/assets/js/sweetalert2.all.min.js')}}"></script>
    <script src="{{asset('public/assets/js/myalert.js')}}"></script>
    

    <!-- file upload -->
    <script src="{{asset('public/assets/js/dropzone.min.js')}}"></script>
    <script src="{{asset('public/assets/js/component.fileupload.js')}}"></script>
    <!-- file upload -->



    <!-- fancybox -->
    <script src="{{asset('public/assets/vendor/fancybox/jquery.fancybox.min.js')}}"></script>

 <script src="{{asset('public/admin_assets/vendors/general/toastr/build/toastr.min.js')}}" type="text/javascript"></script>
@include('pages.includes.script')










        
    </body>
</html>
