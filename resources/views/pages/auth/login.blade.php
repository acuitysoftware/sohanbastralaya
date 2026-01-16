
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
                                    <h4 class="text-dark-50 text-center mt-0 fw-bold">Sign In</h4>
                                    <p class="text-muted mb-4">Enter your email address and password to access admin panel.</p>
                                </div>

                                <form action="{{route('login')}}" method="post">
                                    @csrf

                                    <div class="mb-3">
                                        <label for="emailaddress" class="form-label">Email address</label>
                                        <input class="form-control" type="email" id="emailaddress" name="email" placeholder="Enter your email" @if(Cookie::has('adminUser')) value="{{Cookie::get('adminUser')}}" @endif>
                                        @error('email') <div class="error">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="mb-3">
                                        <a href="{{route('forgot_password')}}" class="text-muted float-end"><small>Forgot your password?</small></a>
                                        <label for="password" class="form-label">Password</label>


                                        <div class="input-group input-group-merge">
                                            <input type="password" id="showpassword" class="form-control" name="password" placeholder="Enter your password" required="" @if(Cookie::has('adminPwd')) value="{{Cookie::get('adminPwd')}}" @endif>
                                            <div class="input-group-text" id="eye" data-password="false">
                                                <span class="mdi mdi-eye-off-outline" ></span>
                                            </div>
                                            @error('password') <div class="error">{{ $message }}</div> @enderror
                                        </div>



                                    </div>

                                    <div class="mb-3 mb-3">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="checkbox-signin"  @if(Cookie::has('adminUser')) checked @endif name="remember_me">
                                            <label class="form-check-label" for="checkbox-signin">Remember me</label>
                                        </div>
                                    </div>

                                    <div class="mb-3 mb-0 text-center">
                                        <button class="btn btn-primary" type="submit"> Log In </button>
                                    </div>

                                </form>
                            </div> <!-- end card-body -->
                        </div>
                        <!-- end card -->

                        
                        <!-- end row -->

                    </div> <!-- end col -->
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </div>
        <!-- end page -->

        

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

<script type="text/javascript">
    $('#eye').click(function(){
            var input = $("#showpassword");

            if (input.attr("type") === "password") {
                input.attr("type", "text");
            } else {
                input.attr("type", "password");
            }
        });
</script>









        
    </body>
</html>
