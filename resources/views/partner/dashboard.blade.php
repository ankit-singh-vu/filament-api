@extends('partner/layouts/master')
@section('content')
  
  <!-- @php
  //dd($data->partner_name)
  @endphp -->
    <!-- <div class="main main-app p-3 p-lg-4"> -->
      <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
          <ol class="breadcrumb fs-sm mb-1">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">partner Analytics</li>
          </ol>
          <h4 class="main-title mb-0">Welcome to Partner's Dashboard</h4>
        </div>
        <nav class="nav nav-icon nav-icon-lg">
          <a href="" class="nav-link" data-bs-toggle="tooltip" title="Share"><i class="ri-share-line"></i></a>
          <a href="" class="nav-link" data-bs-toggle="tooltip" title="Print"><i class="ri-printer-line"></i></a>
          <a href="" class="nav-link" data-bs-toggle="tooltip" title="Report"><i class="ri-bar-chart-2-line"></i></a>
        </nav>
      </div>

      @endsection
