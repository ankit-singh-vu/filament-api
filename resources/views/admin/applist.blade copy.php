@extends('admin/layouts/master')
@section('content')
<style>
  .center-screen {
    margin-top: 120px;
    margin: auto;
  }

  .nav-btn {
    float: right;
  }

  #boxed {
    width: auto;
    height: 42px;
    border: 1px solid #ececef;
    margin: 0;
    display: flex;
    border-radius: 10px;
  }

  #box1 {
    width: 110px;
    border: 1px solid #ececef;
    margin: 0;
    background-color: #ececef;
    border-radius: 10px;
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
  }
  #box2{
    margin-left: auto;
  }
  .box3{
    padding-top: 8px;
    padding-left: 15px;
  }

  p {
    font-family: Open Sans, sans-serif;
  }

  h1,
  h2,
  h3,
  h4,
  h5,
  h6 {
    font-family: Open Sans, sans-serif;
  }
</style>

<div class="col-md-9 center-screen">
  <div class="card card-one">
    <div class="card-body overflow-hidden">
      <div class="card-header p-0 border-0">
        <h6 class="card-title" style="font-size: 24px;">Customers</h6>
        <nav class="nav nav-icon nav-icon-sm ms-auto">
          <button type="button" class="btn" style="background-color: #f1f1f1;"><i class="fa fa-download" aria-hidden="true"></i></i> Import</button>
          <button type="button" class="btn" style="background-color: #f1f1f1; margin-left: 5px; margin-right: 5px;"><i class="fa fa-upload" aria-hidden="true"></i> Export</button>
          <button type="button" class="btn" style="background-color: #8c46cd; color:white;">Add Customers</button>
        </nav>
      </div>
      <p>List of customers</p>
      <div id="boxed">
        <div id="box1">
          <h6 style="text-align: center;margin-top: 10px;">1 Customer</h6>
        </div>
        <div class="box3"><b>100%</b> of your customer base</div>
        <div id="box2">
          <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false" style="margin-top: 3px;margin-right: 10px;background-color: #f0f1f5;color: black;border: none;">
              Add filter
            </button>
            <ul class="dropdown-menu shadow" aria-labelledby="dropdownMenuButton1">
              <li><a class="dropdown-item" href="#">Action</a></li>
              <li><a class="dropdown-item" href="#">Another action</a></li>
              <li><a class="dropdown-item" href="#">Something else here</a></li>
            </ul>
          </div>
        </div>
      </div>
      <div class="card-body pt-2 p-0">
        <div class="col-md-12">
          <div class="card card-one" style="margin-top: 10px;">
            <div class="card-header">
              <div class="form-search me-auto">
                <input type="text" class="form-control" placeholder="Search Customer">
                <i class="ri-search-line"></i>
              </div><!-- form-search -->
              <div class="d-flex gap-2 mt-3 mt-md-0">
                <button style="padding: 6px 8px;" type="button" class="btn btn-white d-flex align-items-center gap-2"><i class="ri-arrow-up-down-fill fs-18 lh-1"></i></button>
                <button style="padding: 6px 8px;" type="button" class="btn btn-white d-flex align-items-center gap-2"><i class="ri-more-fill fs-18 lh-1"></i></button>
              </div>
            </div><!-- card-header -->
            <div class="card-body pt-0">
              <div class="table-responsive">
                <table class="table table-one">
                  <thead>
                    <tr>
                      <th>UUID</th>
                      <th>Name</th>
                      <th>Email</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($customers as $customer)
                    <tr>
                      <td>{{ $customer->uuid }}</td>
                      <td>{{ $customer->name }}</td>
                      <td>{{ $customer->email }}</td>
                      <td><a href="" class="nav-link"><i class="ri-more-2-fill"></i></a></td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div><!-- table-responsive -->
            </div><!-- card-body -->
          </div><!-- card -->
        </div>
      </div>
    </div>
  </div>
</div>
</div>
@endsection
