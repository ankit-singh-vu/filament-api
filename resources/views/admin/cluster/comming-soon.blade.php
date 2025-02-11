@extends('admin/layouts/master')
@section('content')
  
  <!-- @php
  //dd($data->admin_name)
  @endphp -->
  <style>
    
  </style>
    <div class="main main-app p-3 p-lg-4">
      <div class="container-fluid border-bottom border-grey  pt-2 pb-0 mb-5">
        <div class="row">
          <div class="col-4">
            <h5 class="fw-bolder text-dark">Infrastructure / Cluster / <?php echo $cluster->cluster_name; ?></h5>
            <div class=" text-grey-400">
              <span class="text-secondary fw-bold">Owner: <a href="">Convesio Inc</a></span>
            </div>
          </div>
          <div class="col-8 text-end">
            <nav class="nav nav-line mt-3 justify-content-end">
              <a href="/infrastructure/cluster/<?php echo $cluster->id; ?>" class="nav-link outer-nav nav-overview ">Overview</a>
              <a href="/infrastructure/cluster/<?php echo $cluster->id; ?>/servers" class="nav-link outer-nav nav-nodes active">Nodes</a>
              <a href="/infrastructure/cluster/<?php echo $cluster->id; ?>/jobs" class="nav-link outer-nav nav-jobs">Jobs</a>
              <a href="/infrastructure/cluster/<?php echo $cluster->id; ?>/storage" class="nav-link outer-nav nav-storage">Storage</a>
              <a href="/infrastructure/cluster/<?php echo $cluster->id; ?>/stats" class="nav-link outer-nav nav-stats">Stats & Logs</a>
              <a href="/infrastructure/cluster/<?php echo $cluster->id; ?>/settings" class="nav-link outer-nav nav-settings">Settings</a>
            </nav>
          </div>
        </div>
      </div>      

        <div class="container-fluid p-3">

          <div class="row">

           
            <div class="col-12 m-0 p-0">
              <div class="card m-0 p-0 pt-0">

                    <div class="card-body m-5">
                        <h5 class="card-title text-center text-dark">Comming soon ...</h5>
                    </div>

              </div>
              <div class="row g-3">
                
              
                
                
              </div>
            </div>

          </div>

        </div>
    <script>
            $(".outer-nav").removeClass("active");
            $(".<?php echo $class; ?>").addClass("active");

    </script>
@endsection
