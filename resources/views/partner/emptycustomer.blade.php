@extends('partner/layouts/master')
@section('content')
<style>
  .center-screen {
    width: auto;
    height: auto;
    border: 1px solid #b9b9bf;
    margin: 0;
    border-radius: 15px;
    position: fixed; /* or absolute */
    top: 45%;
    left: 56%;
    transform: translate(-50%, -50%);
    padding: 44px;
  }
  .emptytext {
    text-align: center;
  }
</style>
      <div class="col-md-9">
        <div class="card card-one center-screen">
          <div class="card-body pt-0">
            <div class="container emptytext">
            <h5>No Customers has been added to the system yet.</h5>
            <h5>Click on the button bellow to add a customer</h5>
            <button type="button" class="btn" style="background-color: #0e133c; color:white;">Add Customers</button>
            </div>
          </div><!-- card-body -->
        </div><!-- card -->
      </div>
@endsection