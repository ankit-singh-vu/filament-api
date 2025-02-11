@extends('partner/layouts/master')
@section('content')
<style>
  .yelow-box {
        width: auto;
        height: 30px;
        border: 1px solid #ececef;
        margin: 0;
        border-radius: 4px;
        background-color: #F3F0D0;
        padding: 4px;
    }

    b,
    strong {
        color: red;
    }
    .setting-item h6, .setting-item .h6 {
    color: black;
    }
.nav .nav-item button.active {
  background-color: transparent;
  color: #140974 !important;
}
.nav .nav-item button.active::after {
  content: "";
  border-right: 4px solid var(--bs-danger);
  height: 100%;
  position: absolute;
  right: -1px;
  top: 0;
  border-radius: 5px 0 0 5px;
}
</style>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-one">
                <div class="card-body">
                <h2 class="main-title" style="margin-left: 53px;margin-top: 16px;font-size: 40px;">Settings</h2>
                @if($partner_details->partner_url == '')
                    <marquee direction="right" class="marquee">
                        <strong>Add a domain to complete you account.</strong>
                    </marquee>
                    @endif

<div class="container p-5 d-flex align-items-start">
  <ul class="nav nav-pills flex-column nav-pills border-end border-3 me-3 align-items-end" id="pills-tab" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link text-primary fw-semibold active position-relative" id="pills-general-tab" data-bs-toggle="pill" data-bs-target="#pills-general" type="button" role="tab" aria-controls="pills-general" aria-selected="true">General</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link text-primary fw-semibold position-relative" id="pills-notifications-tab" data-bs-toggle="pill" data-bs-target="#pills-notifications" type="button" role="tab" aria-controls="pills-notifications" aria-selected="false">Notifications</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link text-primary fw-semibold position-relative" id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#pills-contact" type="button" role="tab" aria-controls="pills-contact" aria-selected="false">Accessibility</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link text-primary fw-semibold position-relative" id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#pills-contact" type="button" role="tab" aria-controls="pills-contact" aria-selected="false">Advanced</button>
    </li>
  </ul>
  <div class="tab-content border rounded-3 border-primary p-3 text-danger w-100" id="pills-tabContent">
    <div class="tab-pane fade show active" id="pills-general" role="tabpanel" aria-labelledby="pills-general-tab">
      <!-- ---------------------------------------------- -->
      <div class="card card-settings">
                        <div class="card-header">
                            <h4 class="card-title" style="font-size: 25px;">Partner Information</h4>
                        </div><!-- card-header -->
                        <div class="card-body p-0">
                            <div class="setting-item">
                                <div class="row g-2 align-items-center">
                                    <div class="col-md-5">
                                        <h6>Partner Name</h6>
                                        <p>Enter the name of the partner/organization</p>
                                    </div><!-- col -->
                                    <div class="col-md">
                                        <input type="text" class="form-control" value="{{ $partner_details->partner_name }}" placeholder="Enter partner name">
                                    </div><!-- col -->
                                </div><!-- row -->
                            </div><!-- setting-item -->
                            <div class="setting-item">
                                <div class="row g-2">
                                    <div class="col-md-5">
                                        <h6>Partner Contact no</h6>
                                        <p>Temporibus autem quibusdam et aut officiis.</p>
                                    </div><!-- col -->
                                    <div class="col-md">
                                        <input type="text" class="form-control" value="{{ $partner_details->phone }}" placeholder="Enter partner contact no">
                                    </div><!-- col -->
                                </div><!-- row -->
                            </div><!-- setting-item -->

                            <div class="setting-item">
                                <div class="row g-2">
                                    <div class="col-md-5">
                                        <h6>Partner Email</h6>
                                        <p>Neque porro quisquam est qui dolorem.</p>
                                    </div><!-- col -->
                                    <div class="col-md">
                                        <input type="text" class="form-control" value="{{ $partner_details->email }}" placeholder="Enter email address">
                                    </div><!-- col -->
                                </div><!-- row -->
                            </div><!-- setting-item -->

                            <div class="setting-item">
                                <div class="row g-2">
                                    <div class="col-md-5">
                                        <h6>Partner Domain</h6>
                                        <p>Partner domain will be used to create endpoints for partner's user</p>
                                    </div><!-- col -->
                                    <div class="col-md">
                                        <input type="text" class="form-control" value="{{ $partner_details->partner_url }}" placeholder="Enter partner domain">
                                        <div class="yelow-box">
                                            <p style="font-size: 12px;">Point your domain to <b>192.168.62.101</b> to access endpoints.</p>
                                        </div>
                                    </div><!-- col -->
                                </div><!-- row -->
                            </div><!-- setting-item -->

                            <div class="setting-item">
                                <div class="row g-2">
                                    <div class="col-md" style="text-align: end;">
                                        <button type="button" id="deploy-btn" class="btn btn-primary deploy-cluster"></i> Update</button>
                                    </div><!-- col -->
                                </div><!-- row -->
                            </div><!-- setting-item -->

                        </div><!-- card-body -->
                    </div><!-- card -->
      <!-- ---------------------------------------------- -->
    </div>
    <div class="tab-pane fade" id="pills-notifications" role="tabpanel" aria-labelledby="pills-notifications-tab">
      <h2>Notifications Settings</h2>
    </div>
  </div>
</div>



                </div>
            </div>
        </div>
    </div>
</div>

      @endsection
