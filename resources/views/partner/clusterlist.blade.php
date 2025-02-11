@extends('partner/layouts/master')
@section('content')
<style>
  .deploy-cluster {
    padding: 5px;
    width: 100px;
    border-radius: 11px;
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
  .design-form {
    padding: 0px;
    background-color: #EDF0FB;
    border: none;
  }
  .table thead th {
    font-weight: 600;
    font-size: 12px;
  }
  .table tbody th {
    font-weight: 600;
    font-size: 12px;
}
</style>

@if( count($data) == 0 )

<div class="text-center">
  <div class="card w-50 p-2 text-center m-auto rounded-3">
    <!-- <img src="../assets/img/img2.jpg" class="card-img-top" alt="..."> -->
    <div class="card-body">
      <h5 class="card-title text-dark">No Infrastructure Or Cluster Is Currently Assigned to you.<br />
        Please Contact with Convesio to deploy a new cluster</h5>
      <button type="button" class="btn cluster-deploy-modal-button sidebar-dark btn-primary btn-lg text-white mt-5">Ask Convesio to deploy a New Cluster</button>
    </div>
  </div>
</div>
@else



<div class="modal" id="deploy_cluster_modal" tabindex="-1" role="dialog" aria-labelledby="deploy_cluster_modal" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Deploying New Cluster</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-0">
        <!-- <p>Modal body text goes here.</p> -->
        <div class="card p-3 m-0 bg-gray-200">
        <div class="d-flex justify-content-between">
          <div class="mb-3">
          <label for="service_provider" class="form-label">Service Provider</label>
            <select class="form-select" id="service_provider" aria-label="Default select example">
              <option value="">Select Service Provider</option>
              @foreach ($data['all_providers'] as $provider)
              <option value="{{$provider->id}}">{{$provider->display_name}}</option>
              @endforeach
            </select>
          </div>
            <!-- <div class="w-10"></div> -->
            <div class="mb-3">
            <label for="cluster_type" class="form-label">Cluster Type</label>
            <select class="form-select" id="cluster_type" aria-label="Default select example">
              <option value="">Select Cluster Type</option>
              @foreach ($data['cluster_type'] as $typecluster)
              <option value="{{$typecluster->id}}">{{$typecluster->display_name}}</option>
              @endforeach
            </select>
            </div>
          </div>
        </div>
        <div class="selected-provider-cluster-type d-none card m-0 mt-4 p-3">

          <div class="d-inline-flex bg-primary bg-opacity-10 p-4 pb-0">
            <div class="left-section w-45">
              <div class="mb-3">
                <label for="cluster_name" class="form-label">Cluster Name</label>
                <input class="form-control" id="cluster_name" placeholder="e.g. Name">
              </div>
            </div>
            <div class="w-10"></div>
            <div class="right-section w-45">
              <div class="mb-3">
                <label for="cluster_domain_group" class="form-label">Group</label>
                <select id="cluster_domain_group" class="form-select" aria-label="Default select example">
                  <option selected>Conves.io</option>
                </select>
              </div>
            </div>
          </div>
          <div class="container-fluid bg-primary bg-opacity-10">

            <table class="table">
              <thead>
                <tr>
                  <th scope="col">Controller Node Type</th>
                  <th scope="col">Node Count</th>
                  <th scope="col">Costing per month</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>
                    <select class="form-select controller-node-type design-form" aria-label="Default select example">
                      <option value=""selected disabled>X-Medium/32GB Ram</option>
                    </select>
                  </td>
                  <td class="controller-node-number">1 Node</td>
                  <td class="controller-node-total-price">$299 X 1 = $299</td>
                </tr>
                <tr>
                  <th>Hosting Node Type</td>
                  <th>Node count</td>
                  <th>Costing per month</td>
                </tr>
                <tr>
                  <td>
                    <select class="form-select hosting-node-type design-form" aria-label="Default select example">
                    <option value=""selected disabled>X-Medium/32GB Ram</option>
                    </select>
                  </td>
                  <td>
                    <div class="nodes-list-select d-flex">

                      <select class="form-select w-50 hosting-nodes-count design-form" aria-label="Default select example">
                        <?php for ($nodes = 1; $nodes <= 10; $nodes++) : ?>
                          <option value="<?php echo $nodes; ?>"><?php echo $nodes.'Nodes'; ?></option>
                        <?php endfor; ?>
                      </select>
                    </div>
                  </td>
                  <td class="hosting-node-total-price"></td>
                </tr>
              </tbody>
            </table>

          </div>

          <div class="container-fluid p-0 mt-3 border-top border-bottom bg-primary bg-opacity-10">
            <div class="card p-3 m-0 bg-gray-200">
              <div class="d-inline-flex">
                <div class="left w-45 mb-3">
                  <label for="clusterlocation" class="form-label">Cluster Locations</label>
                  <select class="form-select" id="clusterlocation" aria-label="Default select example">
                    <option value="">Select Cluster Locations</option>
                    @foreach ($data['all_locations'] as $location)
                    <option value="{{$location->id}}">{{$location->display_name}}</option>
                    @endforeach
                  </select>
                </div>
                <div class="w-10"></div>
                <div class="right w-45 mb-3">
                  <label for="scmnetwork" class="form-label">SCM Network</label>
                  <select class="form-select" id="scmnetwork" aria-label="Default select example">
                    <option value="">Select SCM Network</option>
                    @foreach ($data['all_scm_network'] as $network)
                    <option value="{{$network->id}}">{{$network->name}}</option>
                    @endforeach
                  </select>
                </div>


              </div>
            </div>
          </div>

          <div class="d-flex justify-content-between container-fluid p-0 mt-4 border-top border-bottom bg-primary bg-opacity-10" style="height: 77px;">

          <div class="mb-3" style="width: 170px;margin-top: 19px;margin-left: 10px;">
              <label for="sharedstoragetype" class="form-label">Shared Storage Type</label>
              <select class="form-select shared_storage_disk" id="sharedstoragetype" aria-label="Default select example">
                @foreach ($data['all_shared_storages'] as $storage)
                <option value="" selected disabled>6.25 TB</option>
                <option value="{{$storage->diskSize}}" data-price="{{$storage->price}}">{{$storage->name}}</option>
                @endforeach
              </select>
          </div>
          <div>
            <table class="table">
              <thead>
                <tr>
                  <th scope="col">Costing per month</th>
                  <th scope="col">TOTAL</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td class="shared_storage_price">$299</td>
                  <td>
                    <h3 class="total_price">$4093</h3>
                  </td>
                </tr>

              </tbody>
            </table>
            </div>
          </div>

        </div>
        <div class="no-choice-till-now card p-4 m-0 ">
          <div class="p-4" style="text-align: center;">
            <h4 class="text-secondary">
              Please select a service provider and cluster type to proceed
            </h4>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
        <button type="button" id="deploy-btn" class="btn btn-primary deploy-cluster"><i class="fa-solid fa-check"></i> Deploy</button>
      </div>
    </div>
  </div>
</div>








<div class="container-fluid p-3">
  <div class="row">
    <div class="col-6">
      <h5 class="h5 fw-bold">Infrastructure Management</h5>
      <p class="text-grey fs-6">
        A status of all clusters in your account including their owner, uptime, status, and load
      </p>
    </div>
    <div class="col-6 text-end ">
      <div class="row">


        <div class="filer-owner col-4">
          <select class="form-select w-35" aria-label="Default select example">
            <option selected="">Filter By Owner</option>
            <option value="1">One</option>
          </select>
        </div>
        <div class="filer-type col-4">
          <select class="form-select w-35" aria-label="Default select example">
            <option selected="">Filter By Type</option>
            <option value="1">One</option>
          </select>
        </div>
        <div class="deploy-cluster-button col-4">
          <button type="button" class="btn cluster-deploy-modal-button 
                sidebar-dark btn-primary btn-lg text-white">
            Deploy New Cluster
          </button>
        </div>

      </div>

    </div>
  </div>

  <table class="table border table-hover fs-0 mt-5">
    <thead>
      <tr>
        <th scope="col" class="fw-bolder">Cluster Id</th>
        <th scope="col" class="fw-bolder">Cluster Type</th>
        <th scope="col" class="fw-bolder">Cluster Load</th>
        <th scope="col" class="fw-bolder">Is Live</th>
        <th scope="col" class="fw-bolder">Owner</th>
        <th scope="col" class="fw-bolder">Uptime</th>
        <th scope="col" class="fw-bolder">Status</th>
        <th scope="col" class="fw-bolder"></th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>569e3a3c-e760-4c4a-80f3-e67860039407</td>
        <td>Nomad v.1.25</td>
        <td>
          CPU 36% / 27GB RAM / 265 Containers
        </td>
        <td>
          <p class=" fw-bold text-success">
            Yes
          </p>
        </td>
        <td>Convesio. Inc.</td>
        <td>1 year 4 month 12 days</td>
        <td>
          <p class=" fw-bold text-success">
            ACTIVE
          </p>
        </td>
        <td class="fw-bold">
          <a href="/overview" class="">Managed</a>
        </td>
      </tr>

      <tr>
        <td>2e1ddeea-d055-458e-8985-bbf26d847ce4</td>
        <td>Kubernets v.2.5</td>
        <td>
          CPU 11% / 16GB RAM / 89 Containers
        </td>
        <td>
          <p class=" fw-bold text-danger">
            No
          </p>
        </td>
        <td>Convesio. Inc.</td>
        <td>1 year 4 month 12 days</td>
        <td>
          <p class=" fw-bold text-danger">
            SUSPENDED
          </p>
        </td>
        <td class="fw-bold">
          <a href="/overview" class="">Managed</a>

        </td>
      </tr>

    </tbody>
  </table>

</div>
@endif

<script>
        $(".infrastructure").addClass("active");
        $(".cluster-deploy-modal-button").click(function(){
          $("#deploy_cluster_modal").modal("show");
          $("#deploy-btn").prop("disabled", true);
        });


        $("#cluster_type").on('change', function() {
          console.log($(this).val());
          var cluster_type=$(this).val();
          var service_provider = $("#service_provider").val();
          
          console.log(cluster_type,service_provider);

          if(cluster_type != "" && service_provider != "" && cluster_type != undefined && service_provider != undefined){
            $("#deploy").removeAttr('disabled');
            $(".no-choice-till-now").addClass("d-none");
            $(".selected-provider-cluster-type").removeClass("d-none");

            $.get('/api/admin/cluster/get_packages?provider='+service_provider, function(data) {
              // alert( "success",data );
              console.log(data);
              data=data.data;
              for(var dataloop=0;dataloop<data.length; dataloop++){
                var package_name=data[dataloop].name;
                var package_id = data[dataloop].id;
                var package_price=data[dataloop].price;
                console.log(package_name);
                var selectdata='<option data-price="'+package_price+'" value="'+package_id+'">'+package_name+'</option>';
                $(".controller-node-type").append(selectdata);
                $(".hosting-node-type").append(selectdata);
                
              }
              
              var new_controller_price=$(".controller-node-type option:selected").attr("data-price");
              $(".controller-node-total-price").html("$"+new_controller_price+" X 1 = $"+new_controller_price);
              $(".controller-node-total-price").attr("total-price",new_controller_price);


              var new_controller_price=$(".hosting-node-type option:selected").attr("data-price");
              var hosting_node_number=$(".hosting-nodes-count").val();
              $(".hosting-node-total-price").html("$"+new_controller_price+" X "+hosting_node_number+" = $"+new_controller_price*hosting_node_number);
              var hosting_node_total_price = new_controller_price*hosting_node_number;
              $(".hosting-node-total-price").attr("total-price",hosting_node_total_price);
              var shared_storage_price=$(".shared_storage_disk option:selected").attr("data-price");
              var total_price = parseInt(new_controller_price)+parseInt(hosting_node_total_price)+parseInt(shared_storage_price);
              $(".total_price").html("$"+total_price);

            })
              .fail(function() {
                alert( "error" );
              })

          }else{
            alert("Please Select Both Cluster Type & Service Provider");
          }
         
          

        });

        $(".controller-node-type").on('change', function(){
          var new_controller_price=$(".controller-node-type option:selected").attr("data-price");
          $(".controller-node-total-price").html("$"+new_controller_price+" X 1 = $"+new_controller_price);
          $(".controller-node-total-price").attr("total-price",new_controller_price);
          var hosting_node_total_price = $(".hosting-node-total-price").attr("total-price");
          var shared_storage_price=$(".shared_storage_disk option:selected").attr("data-price");
          var total_price = parseInt(new_controller_price)+parseInt(hosting_node_total_price)+parseInt(shared_storage_price);
          $(".total_price").html("$"+total_price);
        });

        $(".hosting-node-type").on('change', function(){
          var new_controller_price=$(".hosting-node-type option:selected").attr("data-price");
          var hosting_node_number=$(".hosting-nodes-count").val();
          $(".hosting-node-total-price").html("$"+new_controller_price+" X "+hosting_node_number+" = $"+new_controller_price*hosting_node_number);
          var hosting_node_total_price = new_controller_price*hosting_node_number;
          $(".hosting-node-total-price").attr("total-price",hosting_node_total_price);
          var controller_node_total_price = $(".controller-node-total-price").attr("total-price");
          var shared_storage_price=$(".shared_storage_disk option:selected").attr("data-price");
          var total_price = parseInt(controller_node_total_price)+parseInt(hosting_node_total_price)+parseInt(shared_storage_price);
          $(".total_price").html("$"+total_price);
        });
        
        $(".hosting-nodes-count").on('change', function(){
          var new_node_price=$(".hosting-node-type option:selected").attr("data-price");
          var hosting_node_number=$(".hosting-nodes-count").val();
          $(".hosting-node-total-price").html("$"+new_node_price+" X "+hosting_node_number+" = $"+new_node_price*hosting_node_number);
          var controller_node_total_price = $(".controller-node-total-price").attr("total-price");
          var shared_storage_price=$(".shared_storage_disk option:selected").attr("data-price");
          var total_price = parseInt(controller_node_total_price)+parseInt(new_node_price*hosting_node_number)+parseInt(shared_storage_price);
          $(".total_price").html("$"+total_price);
        });

        $(".shared_storage_disk").on('change', function(){

          var new_node_price=$(".hosting-node-type option:selected").attr("data-price");
          var hosting_node_number=$(".hosting-nodes-count").val();
          $(".hosting-node-total-price").html("$"+new_node_price+" X "+hosting_node_number+" = $"+new_node_price*hosting_node_number);
          var controller_node_total_price = $(".controller-node-total-price").attr("total-price");
          var shared_storage_price=$(".shared_storage_disk option:selected").attr("data-price");
          $(".shared_storage_price").html("$"+shared_storage_price);
          var total_price = parseInt(controller_node_total_price)+parseInt(new_node_price*hosting_node_number)+parseInt(shared_storage_price);
          $(".total_price").html("$"+total_price);

        });

        $(".deploy-cluster").click(function(e){
          alert("deploy cluster");
          e.preventDefault();
          $(".deploy-cluster").addClass("disabled");
          var cluster_provider=$("#service_provider").val();
          var cluster_type=$("#cluster_type").val();
          var cluster_name=$("#cluster_name").val();
          var cluster_group=$("#cluster_domain_group").val();
          var controller_node_inst=$(".controller-node-type").val();
          var hosting_node_inst=$(".hosting-node-type").val();
          var hosting_node_count=$(".hosting-nodes-count").val();
          var cluster_location = $("#clusterlocation").val();
          var scm_network = $("#scmnetwork").val();
          var shared_disk_size = $(".shared_storage_disk").val();
          
          var postData = {
            "provider": cluster_provider,
            "type": cluster_type,
            "name": cluster_name,
            "cluster_group": cluster_group,
            "controller_node": controller_node_inst,
            "hosting_node": hosting_node_inst,
            "hosting_node_count": hosting_node_count,
            "location": cluster_location,
            "scm_network": scm_network,
            "shared_disk_size": shared_disk_size
            
          };
          // $.ajax({
          //           url: '/api/cluster',
          //           type: 'POST',
          //           data: postData,
          //           success: function(response) {
          //               // Handle success
          //               $(".deploy-cluster").removeClass("disabled");
          //               console.log('Success:', response);
          //           },
          //           error: function(xhr, status, error) {
          //               // Handle error
          //               $(".deploy-cluster").removeClass("disabled");
          //               console.error('Error:', error);
          //           }
          //       });

          
        })

      </script>
@endsection