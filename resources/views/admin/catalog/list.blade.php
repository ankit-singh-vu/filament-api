@extends('admin/layouts/master')
@section('content')
<style>
  .table-striped tbody tr:nth-of-type(odd) {
    background-color: #F4F8FB;
    /* Light gray color for odd rows */
  }

  .table-striped tbody tr:nth-of-type(even) {
    background-color: #FFFFFF;
    /* White color for even rows */
  }

  /* Additional customization */
  .table-striped tbody tr:hover {
    background-color: #D8E8F5;
    /* Highlight color on hover */
  }

  .mb-3 {
    position: relative;
  }

  .mb-3 label {
    position: absolute;
    top: -9px;
    left: 15px;
    background-color: #fff;
    padding: 0px 5px;
    font-size: 11px;
    font-weight: 500;
    border-radius: 2px;
  }

  .yelow-box {
    display: flex;
    justify-content: space-between;
    width: auto;
    height: 28px;
    border: 1px solid #ececef;
    margin: 0;
    border-radius: 4px;
    background-color: #F3F0D0;
    padding: 4px;
  }

  .app-cat-table li {
    padding: 0 10px !important;
  }

  .app-cat-table li .people-body a {
    color: #506fd9;
  }

  .app-cat-table li .avatar {
    border: 1px solid #eee;
    background: #fff;
  }
</style>
<div class="main main-app p-3 p-lg-4">
  <div class=" center-screen">


    <div style="display: flex; align-items:center;">
      <h3 class="card-title">Application Catalog Manager</h3>
      <nav class="nav nav-icon nav-icon-sm ms-auto">
        <div class="dropdown">
          <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false" style="margin-right: 10px;background-color: #ededed;color: #41505f;border: 1px solid #e2e2e3;">
            Add filters
          </button>
          <ul class="dropdown-menu shadow" aria-labelledby="dropdownMenuButton1">
            <li><a class="dropdown-item" href="#">Action</a></li>
            <li><a class="dropdown-item" href="#">Another action</a></li>
            <li><a class="dropdown-item" href="#">Something else here</a></li>
          </ul>
        </div>
        <div class="form-search me-auto" style="background-color: #ededed;color: #41505f;padding: 0px 10px;border: 1px solid #e2e2e3;box-shadow:none">
          <input type="text" class="form-control" placeholder="Search by name or tag...">
          <i class="ri-search-line"></i>
        </div><!-- form-search -->
        <!-- <button type="button" class="btn btn-sm btn-primary rounded-pill import-catalog-item" style="margin-left: 16px;font-weight:600">Import</button> -->
        </i>
      </nav><i class="ri-import-fill">
      </i>
    </div>
    <br>

    <div class="row g-3">
      <div class="col-md-6 col-xl-7">
        <div class="card card-one">
          <div class="card-body">
            <div class="chart-bar-one">
              <table class="table app-cat-table table-striped">
                <thead>
                  <tr>
                    <th scope="col">Catalog Name</th>
                    <th scope="col">Source</th>
                    <th scope="col">Url</th>
                    <th scope="col">last Engagement</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($catalog as $catalogitem)
                  <tr class="catalogitemtr" data-id="{{ $catalogitem->id }}">
                    <!-- <td>{{ $catalogitem->id }}</td> -->
                    <td>
                      <li class="people-item">
                        <div class="avatar"><img src="{{ $catalogitem->imagesrc }}" alt="" style="object-fit: contain; "></div>
                        <div class="people-body">
                          <h6><a href="">{{ $catalogitem->name }}</a></h6>
                        </div><!-- people-body -->
                      </li>
                    </td>
                    <td>{{ $catalogitem->src }}</td>
                    <td>Conves.io</td>
                    <td>7 days ago</td>

                  </tr>
                  @endforeach
                </tbody>
              </table>


            </div>
          </div><!-- card-body -->
        </div><!-- card -->
      </div><!-- col -->
      <div class="col-md-6 col-xl-5">
        <div class="card card-one">
          <div class="view-catalog" style="height: 100%;">
          </div>
        </div><!-- col -->
      </div>
    </div>

  </div>
</div>

<!-- modal -->

<div class="modal ImportingApplicationBlueprint" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" style="color: black;">Importing Application Blueprint...</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" style="background-color: #F5F5F5;">
        <div style="display: flex;justify-content: space-between;">
          <div class="mb-3" style="width: 250px;">
            <label for="exampleFormControlInput1" class="form-label">Git Repository</label>
            <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="e.g. git@repo.convesio.com/application">
          </div>
          <div class="mb-3" style="width: 190px;">
            <label for="exampleFormControlInput1" class="form-label">Branch </label>
            <select class="form-select" aria-label="Default select example">
              <option selected>Select Branch</option>
            </select>
          </div>
        </div>
        <div class="mb-3">
          <label for="exampleFormControlTextarea1" class="form-label">Private SSH Key</label>
          <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" placeholder="Copy and paste your private ssh key over here. This is optional and only needed if you are using a private repository for your catalog blueprints"></textarea>
        </div>
        <div class="yelow-box">
          <p style="font-size: 11px;">Allow pinning of version to avoid auto-upgrade or notice for the same</p>
          <div class="form-check form-switch">
            <input class="form-check-input catalog-item" data-id="data-id" data-type="data-type" type="checkbox" role="switch" id="popupswitch">
            <label class="form-check-label" for="popupswitch"></label>
          </div>
        </div>


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-dark">Verify</button>
        <button type="button" class="btn btn-primary">Start Import</button>
      </div>
    </div>
  </div>
</div>


<script>
  $(document).ready(function() {

    $('.catalog-item').change(function() {
      let data = {};
      data.type = $(this).attr('data-type');
      data.catalog_item_id = $(this).attr('data-id');
      if (this.checked) {
        data.status = 1;
      } else {
        data.status = 0;
      }
      console.log(data);
      $.ajax({
        url: '/api/partner/2/catalog/edit',
        type: 'PUT',
        data: data,
        success: function(response) {
          console.log('Status updated successfully:', response.message);
        },
        error: function(xhr, status, error) {
          console.error('Error updating status:', error);
        }
      });
    });

    $('.catalogitemtr').click(function() {
      let id = $(this).attr('data-id');
      $.get('/viewcatalogitem?id=' + id, function(data) {
        $('.view-catalog').html(data);

      })
    });

    $('.import-catalog-item').click(function() {
      $(".ImportingApplicationBlueprint").modal('show');
    });
  });
</script>
@endsection