<!DOCTYPE html>
<html lang="en">
  <head>

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Meta -->
    <meta name="description" content="">
    <meta name="author" content="Themepixels">

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="https://convesio.com/wp-content/uploads/2024/05/convesio-favicon.png">

    <title>Convesio - Partner Login</title>

    <!-- Vendor CSS -->
    <link rel="stylesheet" href="{{secure_asset('assets/lib/remixicon/fonts/remixicon.css')}}">

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{secure_asset('assets/css/style.min.css')}}">
    <style>
      .signin-title {
        font-weight: bold;
        color: #27305c;
        font-size: 20px;
        /* float: right; */
        margin-right: 12px;
      }
      .topright { position: absolute; top: 15px; right: 10px; text-align: right; }
      .bottomright { position: absolute; bottom: 5px; right: 5px; text-align: right; }
    </style>
      @if(count($errors) > 0)
      @foreach( $errors->all() as $message )
        <div class="alert alert-danger display-hide topright">
        <!-- <button class="close" data-close="alert"></button> -->
        <span>{{ $message }}</span>
        </div>
      @endforeach
      @endif
      
  </head>
  <body class="page-sign">

    <div class="card card-sign">
      <div class="card-header">
        <!-- <a href="ui/HTML/dist/" class="header-logo mb-4">dashbyte</a> -->
        <a href="partner.convesio.com">
        <img width="200" height="45" src="https://cloud.convesio.com/assets/brand/Convesio-Logo-Navy2.png" 
        class="attachment-full size-full wp-image-52319" alt="convesio-logo-mob ()">
        <h3 class="signin-title">Partner</h3>
        </a>
      </div><!-- card-header -->
      <div class="card-body">
        <form action="{{ route('partner.signin') }}" method='post'>
        @csrf
        <div class="mb-4">
          <label class="form-label">Email address</label>
          <input type="email" name="access_key" class="form-control" placeholder="Enter your email address" required>
        </div>
        <div class="mb-4">
          <label class="form-label d-flex justify-content-between">Password <a href="">Forgot password?</a></label>
          <input type="password" name="access_secret" class="form-control" placeholder="Enter your password" required>
        </div>
        <input type="hidden" name="type" class="form-control" value="partner">
        <button class="btn btn-primary btn-sign">Sign In</button>

        <!-- <div class="divider"><span>or sign in with</span></div>

        <div class="row gx-2">
          <div class="col"><button class="btn btn-facebook"><i class="ri-facebook-fill"></i> Facebook</button></div>
          <div class="col"><button class="btn btn-google"><i class="ri-google-fill"></i> Google</button></div>
        </div>row -->
        </form>
      </div><!-- card-body -->
      <div class="card-footer">
      © 2019-2024 Convesio Inc. All rights reserved.
      </div><!-- card-footer -->
    </div><!-- card -->

    <script src="{{secure_asset('assets/lib/jquery/jquery.min.js')}}"></script>
    <script src="{{secure_asset('assets/lib/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script>
      'use script'

      var skinMode = localStorage.getItem('skin-mode');
      if(skinMode) {
        $('html').attr('data-skin', 'dark');
      }

      setTimeout(function() {
        $('.alert').fadeOut('fast');
        }, 1000);
    </script>
  </body>
</html>
