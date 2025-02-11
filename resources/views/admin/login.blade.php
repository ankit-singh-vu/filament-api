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

    <title>Convesio - Admin Dashboard - Login</title>

    <!-- Vendor CSS -->
    <link rel="stylesheet" href="{{secure_asset('assets/lib/remixicon/fonts/remixicon.css')}}">

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{secure_asset('assets/css/style.min.css')}}">
  </head>
  <body class="page-sign">
    <?php 
    if(isset($message)){
        echo $message;
    }
    ?>
    <div class="card card-sign">
      <div class="card-header">
        <!-- <a href="{{secure_asset('assets/" class="header-logo mb-4">dashbyte</a> -->
        <a href="{{secure_asset('assets/" class="header-logo mb-0">
        <img width="200" height="45" src="https://cloud.convesio.com/assets/brand/Convesio-Logo-Navy2.png" 
        class="attachment-full size-full wp-image-52319" alt="convesio-logo-mob ()">
        </a>
        <h5>Administrator</h5>
        <!-- <h3 class="card-title">Sign In</h3>
        <p class="card-text">Welcome back! Please signin to continue.</p> -->
      </div><!-- card-header -->
      
      <div class="card-body">
      <div class="mb-1">
        <div class="alert alert-danger d-none login-alert" role="alert">...</div>
      </div>
        <form action="/api/admin/login" class="admin_login" method="post">
        @csrf
        <div class="mb-4">
          <label class="form-label">Email address</label>
          <input type="text" name="email" class="form-control emailform" placeholder="Enter your email address">
        </div>
        
        <div class="mb-4">
          <label class="form-label d-flex justify-content-between">Password <a href="">Forgot password?</a></label>
          <input type="password" name="password" class="form-control passwordform" placeholder="Enter your password">
        </div>
        <button type="submit" class="btn btn-primary btn-sign">Sign In</button>
        </form>
        <!-- <div class="divider"><span>or sign in with</span></div>

        <div class="row gx-2">
          <div class="col"><button class="btn btn-facebook"><i class="ri-facebook-fill"></i> Facebook</button></div>
          <div class="col"><button class="btn btn-google"><i class="ri-google-fill"></i> Google</button></div>
        </div>row -->
      </div><!-- card-body -->
      <div class="card-footer">
        2019-<?php echo date("Y"); ?> Convesio Inc. All rights reserved.
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

      $(".admin_login").submit(function(e){
            e.preventDefault();
            var form = $(this);
            var actionUrl = form.attr('action');
            $.ajax({
                type: "POST",
                url: actionUrl,
                data: form.serialize(), // serializes the form's elements.
                success: function(data)
                {
                    // alert(JSON.stringify(data));
                    console.log(data); // show response from the php script.
                    if(data.token != undefined){
                        var now = new Date();
                        var time = now.getTime();
                        time += 24 * 3600 * 1000;
                        now.setTime(time);
                        document.cookie='admin_access_token='+data.token+'; expires='+now.toUTCString()+'; path=/';
                        window.location.href = "/dashboard";
                    }else{
                        console.log(data);
                        $(".login-alert").css("display","block");
                        $(".login-alert").html("Something Went Wrong. Please try again.");
                    }
                },
                error: function(errdata){
                    // alert(JSON.stringify(errdata.responseText));
                    errdata=JSON.parse(errdata.responseText);
                    // console.log(errdata,typeof(errdata.error));
                    console.log(errdata.error,errdata.message);
                    if(errdata.error == true){
                        if(errdata.message != undefined){
                            $(".login-alert").removeClass("d-none");
                            $(".login-alert").html(errdata.message+". Please try again.");
                        }else{
                            $(".login-alert").css("display","block");
                            $(".login-alert").html("Something Went Wrong. Please try again.");
                        }
                    }else{
                        if(errdata.message != undefined){
                            $(".login-alert").css("display","block");
                            $(".login-alert").html(errdata.message+". Please try again.");
                        }else{
                            $(".login-alert").css("display","block");
                            $(".login-alert").html("Something Went Wrong. Please try again.");
                        }
                    }
                }
            });
        });

    </script>
  </body>
</html>
