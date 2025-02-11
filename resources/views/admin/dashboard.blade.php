@extends('admin/layouts/master')
@section('content')
  
  <!-- @php
  //dd($data->admin_name)
  @endphp -->
    <div class="main main-app">
    <style type="text/css"> ::-webkit-scrollbar { width: 0px; height: 0px; } ::-webkit-scrollbar-button { width: 0px; height: 0px; } ::-webkit-scrollbar-thumb { background: transparent; border: 0px none; border-radius: 0px; } ::-webkit-scrollbar-thumb:hover { background: transparent; } ::-webkit-scrollbar-thumb:active { background: transparent; } ::-webkit-scrollbar-track { background: transparent; border: 0px none; border-radius: 0px; } ::-webkit-scrollbar-track:hover { background: transparent; } ::-webkit-scrollbar-track:active { background: transparent; } ::-webkit-scrollbar-corner { background: transparent; } </style>


    <?php   
    // $app_url=env("APP_URL"); 
    // $domain=str_replace("https://","",$app_url);
    // $domain=str_replace("/","",$domain);
    // include("/var/www/html/vendor/laravel/pulse/resources/views/dashboard.blade.php");?>
      <iframe src="https://<?php echo $_SERVER['HTTP_HOST']; ?>/pulse" style="width: 100%; height: 100vh;" ></iframe>
      <!-- <div class="d-flex align-items-center justify-content-between mb-4">
        
        <div>
          <ol class="breadcrumb fs-sm mb-1">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">admin Analytics</li>
          </ol>
          <h4 class="main-title mb-0">Welcome to admin's Dashboard</h4>
        </div>
        <nav class="nav nav-icon nav-icon-lg">
          <a href="" class="nav-link" data-bs-toggle="tooltip" title="Share"><i class="ri-share-line"></i></a>
          <a href="" class="nav-link" data-bs-toggle="tooltip" title="Print"><i class="ri-printer-line"></i></a>
          <a href="" class="nav-link" data-bs-toggle="tooltip" title="Report"><i class="ri-bar-chart-2-line"></i></a>
        </nav> -->
      </div>
      <script>
        $(".dashboard").addClass("active");
      </script>
      @endsection
