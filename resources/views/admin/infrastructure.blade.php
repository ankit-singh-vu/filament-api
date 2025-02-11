@extends('admin/layouts/master')
@section('content')
  
  <!-- @php
  //dd($data->admin_name)
  @endphp -->
    <div class="main main-app p-3 p-lg-4">
      


        <div class="modal modal-lg" id="deploy_cluster_modal" tabindex="-1" role="dialog" aria-labelledby="deploy_cluster_modal" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Deploying New Cluster</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body p-0">
                <!-- <p>Modal body text goes here.</p> -->
                 <div class="card p-4 m-0 bg-gray-200">
                    <div class="d-inline-flex">
                      <select class="form-select w-40" id="service_provider" aria-label="Default select example">
                        <option value="" >Select Service Provider</option>
                        @foreach ($all_providers as $provider) 
                        <option value="{{$provider->id}}">{{$provider->display_name}}</option>
                        @endforeach
                      </select>
                      <div class="w-10"></div>
                      <select class="form-select w-40" id="cluster_type" aria-label="Default select example">
                        <option value="" >Select Cluster Type</option>
                        @foreach ($cluster_type as $typecluster) 
                        <option value="{{$typecluster->id}}">{{$typecluster->display_name}}</option>
                        @endforeach
                      </select>
                    </div>
                 </div>
                <div class="selected-provider-cluster-type d-none card m-0 mt-4 p-4">
                    <div class="alert alert-danger error-pannel-cluster-job d-none" role="alert">dddd</div>
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
                              <select class="form-select controller-node-type" aria-label="Default select example">
                                
                              </select>
                            </td>
                            <td class="controller-node-number">1 Node</td>
                            <td class="controller-node-total-price" >$299 X 1 = $299</td>
                          </tr>
                          <tr>
                            <th>Hosting Node Type</td>
                            <th>Node Count</td>
                            <th>Costing per month</td>
                          </tr>
                          <tr>
                            <td>
                              <select class="form-select hosting-node-type" aria-label="Default select example">
                              </select>
                            </td>
                            <td>
                              <div class="nodes-list-select d-flex">

                                <select class="form-select w-100 hosting-nodes-count" aria-label="Default select example">
                                  <?php for($nodes=1; $nodes <= 10; $nodes++): ?>
                                  <option value="<?php echo $nodes; ?>"><?php echo $nodes; ?></option>
                                  <?php  endfor; ?>
                                </select> 
                                <div class="node-name-txt m-auto">
                                  
                                </div>
                              </div>
                              </td>
                            <td class="hosting-node-total-price" ></td>
                          </tr>
                        </tbody>
                      </table>

                    </div>

                    <div class="container-fluid p-0 mt-4 border-top border-bottom bg-primary bg-opacity-10">
                      <div class="card p-4 m-0 bg-gray-200">
                          <div class="d-inline-flex">
                            <div class="left w-45">
                            <label for="clusterlocation" class="form-label">Cluster Locations</label>
                                <select class="form-select" id="clusterlocation" aria-label="Default select example">
                                  <option value="" >Select Cluster Locations</option>
                                  @foreach ($all_locations as $location) 
                                  <option value="{{$location->id}}">{{$location->display_name}}</option>
                                  @endforeach
                                </select>
                            </div>
                            <div class="w-10"></div>
                            <div class="right w-45">
                            <label for="scmnetwork" class="form-label">SCM Network</label>
                                <select class="form-select" id="scmnetwork" aria-label="Default select example">
                                  <option value="" >Select SCM Network</option>
                                  @foreach ($all_scm_network as $network) 
                                  <option value="{{$network->id}}">{{$network->name}}</option>
                                  @endforeach
                                </select>
                            </div>
                            
                            
                          </div>
                      </div>
                    </div>

                    <div class="container-fluid p-0 mt-4 border-top border-bottom bg-primary bg-opacity-10">

                    <table class="table">
                        <thead>
                          <tr>
                            <th scope="col">Shared Storage Type</th>
                            <th scope="col">Costing per month</th>
                            <th scope="col">TOTAL</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td>
                              <select class="form-select shared_storage_disk" aria-label="Default select example">
                                @foreach ($all_shared_storage as $storage)
                                <option value="{{$storage->diskSize}}" data-price="{{$storage->price}}">{{$storage->name}}</option>
                                @endforeach 
                              </select>
                            </td>
                            <td class="shared_storage_price">$299</td>
                            <td><h1 class="total_price">$4093</h1></td>
                          </tr>
                          
                        </tbody>
                      </table>

                    </div>

                </div>
                 <div class="no-choice-till-now card p-4 m-0 ">
                    <div class=" p-5">
                        <h4 class="text-secondary">
                          Please select a service provider and cluster type to proceed
                        </h4>
                    </div>
                 </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary deploy-cluster">Deploy</button>
              </div>
            </div>
          </div>
        </div>



      <?php if( count($all_clusters) < 1 ){ 
        
        ?>
        <div class="text-center">
          <div class="card w-50 p-2 text-center m-auto rounded-3">
            <!-- <img src="../assets/img/img2.jpg" class="card-img-top" alt="..."> -->
            <div class="card-body">
              <h5 class="card-title text-dark">No Infrastructure Or Cluster Is Currently Managed By The System.<br/>
              Please Click On The Botton Below To Create Or Deploy A New Cluster</h5>
              <!-- <p class="card-text">No Infrastructure Or Cluster Is Currently Managed By The System. 
              Please Click On The Botton Below To Create Or Deploy A New Cluster</p> -->
              <button type="button" class="btn cluster-deploy-modal-button sidebar-dark btn-primary btn-lg text-white mt-5" 
              >Deploy New Cluster</button>
            </div>
          </div>
        </div>

        
      <?php }else{  ?>


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
                <th scope="col" class="fw-bolder p-3">Cluster Id</th>
                <th scope="col" class="fw-bolder p-3">Cluster Name</th>
                <th scope="col" class="fw-bolder p-3">Cluster Type</th>
                <th scope="col" class="fw-bolder p-3">Cluster Load</th>
                <th scope="col" class="fw-bolder p-3">Is Live</th>
                <th scope="col" class="fw-bolder p-3">Owner</th>
                <th scope="col" class="fw-bolder p-3">Uptime</th>
                <th scope="col" class="fw-bolder p-3">Status</th>
                <th scope="col" class="fw-bolder p-3"></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($all_clusters as $cluster){ ?>
              <tr>
                <td><?php echo $cluster->uuid; ?></td>
                <td><?php echo $cluster->cluster_name; ?></td>
                <td>Nomad v.1.25</td>
                <td>
                  CPU 36% / 27GB RAM / 265 Containers
                </td>
                <td>
                  
                  <?php if($cluster->status == CLUSTER_STATUS_RUNNING){ 
                    ?>
                    <p class=" fw-bold text-success">Yes</p>
                    <?php
                  }else{

                    ?>
                    <p class=" fw-bold text-secondary">No</p>
                    
                    <?php
                  }?>
                  
                </td>
                <td>Convesio. Inc.</td>
                <td>1 year 4 month 12 days</td>
                <td>
                  <?php if($cluster->status == CLUSTER_STATUS_RUNNING){ ?>
                  <p class=" fw-bold text-success" >
                    ACTIVE
                  </p>
                  <?php }elseif($cluster->status == CLUSTER_STATUS_SUSPENDED){
                    ?>
                    <p class="fw-bold text-danger" >
                      Suspended
                    </p>

                    <?php
                  }
                  elseif($cluster->status == CLUSTER_STATUS_PROVISIONING){ ?>
                  <p class="fw-bold text-primary" >
                    Provisioning
                  </p>
                  <?php }elseif($cluster->status == CLUSTER_STATUS_DELETED){
                    ?>
                    <p class=" fw-bold text-danger">
                      Deleted
                    </p>
                    <?php
                  } ?>
                </td>
                <td>
                  <p class="cluster-destroy fw-bold text-danger" cluster-uuid="<?php echo $cluster->uuid; ?>">DELETE</p>
                </td>
                <td class="fw-bold">
                <a href="/infrastructure/cluster/<?php echo $cluster->id;?>" class="">Managed</a>
                </td>
              </tr>
              <?php }  ?>
              
              
            </tbody>
          </table>

        </div> 








      <?php   }   ?>
      </div>
      <script>
        $(document).ready(function(){
          $(".infrastructure").addClass("active");
          $(".cluster-deploy-modal-button").click(function(){
            $("#deploy_cluster_modal").modal("show");
          });


          $("#cluster_type").on('change', function() {
            console.log($(this).val());
            var cluster_type=$(this).val();
            var service_provider = $("#service_provider").val();
            
            console.log(cluster_type,service_provider);

            if(cluster_type != "" && service_provider != "" && cluster_type != undefined && service_provider != undefined){
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
            
            e.preventDefault();
            if(confirm("Are you sure you want to create cluster ?")){
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
              $.ajax({
                      url: '/api/cluster',
                      type: 'POST',
                      data: postData,
                      success: function(response) {
                          // Handle success
                          $(".deploy-cluster").removeClass("disabled");
                          console.log('Success:', response);
                          if(response.status == true){
                            window.location.href = "/infrastructure/cluster/deploy/"+response.job_id;
                          }else{
                            
                            $(".error-pannel-cluster-job").removeClass("d-none");
                            $(".error-pannel-cluster-job").html(response.message);
                          }
                          
                      },
                      error: function(xhr, status, error) {
                          // Handle error
                          $(".deploy-cluster").removeClass("disabled");

                          console.error('Error:', error);
                      }
                  });

            
          
          
            }else{
              
            }
          })


          $(".cluster-destroy").click(function(e){
            
            e.preventDefault();
            if(confirm("Are you sure you want to destroy cluster?")){
              var clusteruuid=$(this).attr("cluster-uuid");
              
              $.ajax({
                      url: '/api/cluster/'+clusteruuid,
                      type: 'DELETE',
                      success: function(response) {
                          // Handle success
                          // $(".deploy-cluster").removeClass("disabled");
                          console.log('Success:', response);
                          if(response.status == true){
                            window.location.href = "/infrastructure/cluster/deploy/"+response.job_id;
                          }else{
                            alert(response.message);
                            // $(".error-pannel-cluster-job").removeClass("d-none");
                            // $(".error-pannel-cluster-job").html(response.message);
                          }
                          
                      },
                      error: function(xhr, status, error) {
                          // Handle error
                          // $(".deploy-cluster").removeClass("disabled");
                          alert(error);
                          console.error('Error:', error);
                      }
                  });

            
            }else{

            }
          })
        });

        

      </script>
      @endsection
