@extends('partner/layouts/master')
@section('content')

<style>
    .bg-convesio-primary {
        background-color: #1B3532 !important;
    }

    .serverinfo .card-one,
    .serverinfo .card {
        box-shadow: none;
        border-radius: 0;
    }
</style>
<div class="container">
    <div class="row">
        <div class="card card-one">
            <div class="card-body">
                <div class="container-fluid pt-2 pb-0">
                    <div class="row">
                        <div class="col-12">
                            <h3 class=" text-dark">Infrastructure / Cluster / VD4659</h3>
                            <div class="h5 text-grey-400">
                                <span class="text-dark">Owner: <a href="">Convesio Inc</a></span>
                            </div>
                        </div>
                        <div class="col-12 p-0 text-end">
                            <nav class="nav nav-line justify-content-end">
                                <a href="/infrastructure/cluster/2" class="nav-link fw-bold ">Overview</a>
                                <a href="/infrastructure/cluster/2/servers" class="nav-link fw-bold active">Servers</a>
                                <a href="/infrastructure/cluster/2/jobs" class="nav-link fw-bold">Jobs</a>
                                <a href="/infrastructure/cluster/2/storage" class="nav-link fw-bold">Storage</a>
                                <a href="/infrastructure/cluster/2/stats" class="nav-link fw-bold">Stats & Logs</a>
                                <a href="/infrastructure/cluster/2/settings" class="nav-link fw-bold">Settings</a>
                            </nav>
                        </div>
                    </div>
                </div>

                <div class="container-fluid">
                    <div class="row">
                        <div class="col-4 m-0 p-0">
                            <div class="card h-100">
                                <div class="container-fluid  m-0 pt-3 pb-0">
                                    <div class="container-fluid m-0 p-3">
                                        <div class="row">
                                            <div class="col-12 fs-4 text-dark fw-bold">Active Servers
                                                <span class="numberofservers fs-4 text-secondary">3</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card border-top-0">
                                    <div class="cluster-servers border-bottom container-fluid  m-0 pt-3 pb-3">
                                        <div class="container-fluid m-0 p-3">
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="server-detail-name h6 fw-bold">Ubuntu_webdev</div>
                                                    <div class="server-detail-addr">220.165.74.20</div>
                                                </div>
                                                <div class="col-6 text-end">
                                                    <span class="badge badge-pill bg-secondary bg-opacity-50">1 core , 8GB</span>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="cluster-servers border-bottom container-fluid m-0 bg-convesio-primary text-white bg-opacity-25 pt-3 pb-3">
                                        <div class="container-fluid m-0 p-3">
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="server-detail-name h6 fw-bold">Support Server</div>
                                                    <div class="server-detail-addr">210.145.54.10</div>
                                                </div>
                                                <div class="col-6  text-end">
                                                    <span class="badge badge-pill bg-secondary bg-opacity-50">1 core , 8GB</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="cluster-servers border-bottom container-fluid  m-0 pt-3 pb-3">
                                        <div class="container-fluid m-0 p-3">
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="server-detail-name h6 fw-bold">Capitalizer Server</div>
                                                    <div class="server-detail-addr">175.55.84.101</div>
                                                </div>
                                                <div class="col-6 text-end">
                                                    <span class="badge badge-pill bg-secondary bg-opacity-50">1 core , 8GB</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="container-fluid  m-0 pt-3 pb-3">
                                            <div class="container-fluid m-0 p-3">
                                                <div class="row">
                                                    <div class="col-12 fs-4 text-dark fw-bold">Shutdown Servers
                                                        <span class="numberofservers fs-4 text-secondary">2</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="cluster-servers border-bottom container-fluid  m-0 pt-3 pb-3">
                                        <div class="container-fluid m-0 p-3">
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="server-detail-name h6 fw-bold">Support Server</div>
                                                    <div class="server-detail-addr">210.145.54.10</div>
                                                </div>
                                                <div class="col-6  text-end">
                                                    <span class="badge badge-pill bg-secondary bg-opacity-50">1 core , 8GB</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="cluster-servers border-bottom container-fluid  m-0 pt-3 pb-3">
                                        <div class="container-fluid m-0 p-3">
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="server-detail-name h6 fw-bold">Support Server</div>
                                                    <div class="server-detail-addr">210.145.54.10</div>
                                                </div>
                                                <div class="col-6  text-end">
                                                    <span class="badge badge-pill bg-secondary bg-opacity-50">1 core , 8GB</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-8 m-0 p-0 bg-white serverinfo">
                            <div class="card m-0 p-0 pt-0" style="border-bottom: 1px solid #0000002d;border-right:0">
                                <ul class="nav">
                                    <li class="nav-item">
                                        <a class="nav-link clustertab text-dark active" aria-current="page" href="#">
                                            <h5>Overview</h5>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link clustertab text-dark" href="#">
                                            <h5>
                                                Proccess <span class="text-secondary proccess-badge">5</span>
                                            </h5>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link clustertab text-dark" href="#">
                                            <h5>Firewall</h5>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="row m-0">
                                <div class="col-xl-4 p-0">
                                    <div class="card card-one" style="border-right: 1px solid #0000002d;">
                                        <div class="card-header border-0 pb-0">
                                            <h6 class="card-title">Info</h6>
                                        </div><!-- card-header -->
                                        <div class="card-body p-3">
                                            <div class="revenue-item p-3 pb-0 pt-0">
                                                <div class="revenue-item-body">
                                                    <span>OS</span>
                                                    <span>Ubuntu 22.04</span>
                                                    <span class="text-white"></span>
                                                </div><!-- revenue-item-body -->
                                            </div><!-- revenue-item -->
                                            <div class="revenue-item p-3 pb-0 pt-0">
                                                <div class="revenue-item-body">
                                                    <span>CPU</span>
                                                    <span>10 Cores</span>
                                                    <span class="text-white"></span>
                                                </div><!-- revenue-item-body -->
                                            </div><!-- revenue-item -->
                                            <div class="revenue-item p-3 pb-0 pt-0">
                                                <div class="revenue-item-body">
                                                    <span>Memory</span>
                                                    <span>16 GB</span>
                                                    <span class="text-white"></span>
                                                </div><!-- revenue-item-body -->
                                            </div><!-- revenue-item -->
                                            <div class="revenue-item p-3 pb-0 pt-0">
                                                <div class="revenue-item-body">
                                                    <span>Storage</span>
                                                    <span>30 GB</span>
                                                    <span class="text-white"></span>
                                                </div><!-- revenue-item-body -->
                                            </div><!-- revenue-item -->
                                            <div class="revenue-item p-3 pb-0 pt-0">
                                                <div class="revenue-item-body">
                                                    <span>IP</span>
                                                    <span>211.108.90.108</span>
                                                    <span class="text-white"></span>
                                                </div><!-- revenue-item-body -->
                                            </div><!-- revenue-item -->
                                            <div class="row gap-1 m-auto">
                                                <button type="button" class="col-5 btn bg-secondary bg-opacity-25 d-flex 
                        align-items-center justify-content-center">

                                                    Stop
                                                </button>
                                                <button type="button" class="col-4 btn bg-secondary bg-opacity-25 d-flex 
                        align-items-center justify-content-center">
                                                    Restart
                                                </button>

                                                <button type="button" class="col-2 btn bg-secondary bg-opacity-25 d-flex align-items-center 
                        text-center fw-bolder justify-content-center"><i class="ri-more-2-line"></i></button>

                                            </div>

                                        </div><!-- card-body -->

                                    </div><!-- card -->
                                </div>
                                <div class="col-xl-8 col-xl-4 p-0">
                                    <div class="col-xl-12 col-xl-4">
                                        <div class="card card-one">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="card card-one">
                                                            <div class="card-body overflow-hidden">
                                                                <h6 class="text-dark fw-semibold mb-1">CPU usage</h6>
                                                                <div id="apexChart2" class="apex-chart-three"></div>
                                                            </div>
                                                        </div><!-- card -->
                                                    </div><!-- col -->

                                                </div><!-- row -->
                                            </div><!-- card-body -->
                                        </div><!-- card-one -->
                                    </div>
                                    <div class="col-xl-12 col-xl-4">
                                        <div class="card card-one">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="card card-one">
                                                            <div class="card-body overflow-hidden">
                                                                <h6 class="text-dark fw-semibold mb-1">RAM usage</h6>
                                                                <div id="apexChart3" class="apex-chart-three"></div>
                                                            </div>
                                                        </div><!-- card -->
                                                    </div><!-- col -->
                                                </div><!-- row -->
                                            </div><!-- card-body -->
                                        </div><!-- card-one -->
                                    </div>
                                </div>
                                <div class="col-md-5 col-xl-7 p-0" style="border-top: 1px solid #0000002d;border-right:1px solid #00000028">
                                    <div class="card card-one">
                                        <div class="card-header border-0 pb-0">
                                            <h6 class="card-title">Bandwith</h6>
                                        </div><!-- card-header -->
                                        <div class="card-body pb-4">

                                            <div id="apexChart2" class="apex-chart-one mb-3" style="min-height: 180px;"></div>

                                        </div><!-- card-body -->
                                    </div><!-- card -->
                                </div>
                                <div class="col-md-6 col-xl-5 p-0" style="border-top: 1px solid #0000002d;">
                                    <div class="card card-one">
                                        <div class="card-body p-3 text-center">
                                            <div class="row g-3 row-cols-auto align-items-center">
                                                <div class="col-12">
                                                    <div class="apex-donut-one m-auto">
                                                        <div id="chartDonut2" style="min-height: 138.7px;"></div>

                                                        <div>
                                                            <h4 class="ff-numerals text-dark mb-0">68%</h4>
                                                            <span class="fs-xs text-secondary">23.5 TB</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 text-center">

                                                    <h6 class="card-label text-dark fw-semibold mb-1">Traffic Usage</h6>
                                                    <p class="fs-sm text-secondary mb-0">Total Traffic Usage we have on this server.</p>
                                                    <p class="fs-sm text-secondary mb-0">23.4 TB / 35 TB</p>
                                                </div>
                                            </div><!-- row -->
                                        </div><!-- card-body -->
                                    </div><!-- card -->
                                </div>
                                <div class="col-md-12 col-xl-12 m-0 p-0" style="border-top: 1px solid #0000002d;">
                                    <div class="card card-one m-0 p-3">
                                        <div class="row p-1">
                                            <div class="col-6">
                                                <h5 class="h5 text-gray fw-bold">
                                                    Docker Containers
                                                </h5>
                                            </div>
                                            <div class="col-6">
                                                <ul class="nav justify-content-end">
                                                    <li class="nav-item">
                                                        <a class="nav-link docker-container-nav text-secondary p-3 pt-0 pb-0 active" aria-current="page" href="#">All</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link docker-container-nav text-dark p-3 pt-0 pb-0" href="#">Active</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link docker-container-nav text-secondary p-3 pt-0 pb-0" href="#">Shutdown</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="row p-1 pt-5">
                                            <div class="col-6">

                                                <div class="container d-flex">
                                                    <h5 class="h6 fw-bold w-50">
                                                        chatgpt_telegram
                                                    </h5>
                                                    <h6 class="h6 text-secondary text-end w-50">
                                                        233243
                                                    </h6>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="container d-flex">
                                                    <h5 class="h6 fw-bold w-50">
                                                        vpn_servers_netherland
                                                    </h5>
                                                    <h6 class="h6 text-secondary text-end w-50">
                                                        233243
                                                        </h5>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="row p-1">
                                            <div class="col-3">
                                                <div class="cpu-cont">
                                                    <h6 class="h6 text-center">
                                                        CPU: 8%
                                                    </h6>
                                                    <div class="progress ">
                                                        <div class="progress-bar w-10" role="progressbar" aria-valuenow="8" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="cpu-cont">
                                                    <h6 class="h6 text-center">
                                                        RAM: 5%
                                                    </h6>
                                                    <div class="progress">
                                                        <div class="progress-bar w-5" role="progressbar" aria-valuenow="5" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="cpu-cont">
                                                    <h6 class="h6 text-center">
                                                        CPU: 15%
                                                    </h6>
                                                    <div class="progress">
                                                        <div class="progress-bar w-15" role="progressbar" aria-valuenow="11" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="cpu-cont">
                                                    <h6 class="h6 text-center">
                                                        RAM: 30%
                                                    </h6>
                                                    <div class="progress">
                                                        <div class="progress-bar w-30" role="progressbar" aria-valuenow="12" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




<script>
    $(".cluster-servers").click(function() {

        $(".cluster-servers").removeClass("bg-convesio-primary");
        $(".cluster-servers").removeClass("bg-opacity-25");
        $(".cluster-servers").removeClass("text-white");
        $(this).toggleClass("bg-convesio-primary");
        $(this).toggleClass("bg-opacity-25");
        $(this).toggleClass("text-white");
    });

    $(".docker-container-nav").click(function() {
        $(".docker-container-nav").removeClass("fw-bold");
        $(".docker-container-nav").removeClass("text-secondary");
        $(this).toggleClass("fw-bold");
        $(this).toggleClass("text-dark");
    });

    $(".clustertab").click(function() {

        $(".clustertab").removeClass("fw-bold");
        $(".clustertab").removeClass("text-dark");
        $(".clustertab").removeClass("text-white");
        $(".clustertab").removeClass("bg-convesio-primary");
        $(".clustertab").addClass("text-dark");
        $(this).toggleClass("fw-bold");
        $(this).toggleClass("text-white");
        $(this).toggleClass("bg-convesio-primary");

    });
</script>

@endsection