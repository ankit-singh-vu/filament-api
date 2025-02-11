@extends('admin/layouts/master')
@section('content')
  
  <!-- @php
  //dd($data->admin_name)
  @endphp -->
  @vite(['resources/js/echo.js']);
    <div class="main main-app p-3 p-lg-4">
      <div class="container-fluid border-bottom border-dark p-0">
        <div class="row">
          <div class="col-6">
            <h3 class="h5 text-dark">Infrastructure / Cluster / <?php echo $data_to_send['cluster_name']; ?></h3>
            <div class="h6 text-grey-400">
              <span class="text-dark">Owner: <a href="">Convesio Inc</a></span>
            </div>
          </div>
          <div class="col-6 text-end">
            <h5 class="text-grey-400">
              <?php if($data_to_send['whatjob']=="deploy"){
                echo "Deployment in progress ...";
              }elseif($data_to_send['whatjob'] == "destroy"){
                echo "Destroy in progress ...";
              }elseif($data_to_send['whatjob'] == "scale"){
                echo "Scaling in progress ...";
              } ?>
              
            </h5>
          </div>
        </div>
      </div>      

      <div class="container-fluid p-4">

        <div class="row">
          <div class="col-4">

            <div class="card p-2 m-0 pt-4  ">
                <div class="card-body">
                  <div class="cluster-profile-image text-center">
                    <img class="img-thumbnail w-25" src="https://object-storage-ca-ymq-1.vexxhost.net/swift/v1/6e4619c416ff4bd19e1c087f27a43eea/www-assets-prod/Uploads/openstack-vert3.jpg" alt="">
                  </div>
                  <h4 class="cluster-name h4 text-center mt-3">
                    <?php echo $data_to_send['cluster_name']; ?>
                  </h4>
                  <div class="status-job text-center mt-4">

                    <?php if($data_to_send['whatjob']=="deploy"){

                        ?>
                        <span class="badge jobstats-btn fs-5 bg-primary text-primary bg-opacity-25">
                          <i class="ri-checkbox-blank-circle-fill fs-6" aria-label="ri-checkbox-blank-circle-fill"></i>
                            Deploying
                            
                        </span>
                        
                        <?php


                      }elseif($data_to_send['whatjob'] == "destroy"){
                        ?>
                        <span class="badge jobstats-btn fs-5 bg-primary text-primary bg-opacity-25">
                          <i class="ri-checkbox-blank-circle-fill fs-6" aria-label="ri-checkbox-blank-circle-fill"></i>
                            Destroying
                            
                        </span>
                        <?php
                      }elseif($data_to_send['whatjob'] == "scale"){
                        ?>
                        
                        <span class="badge jobstats-btn fs-5 bg-primary text-primary bg-opacity-25">
                          <i class="ri-checkbox-blank-circle-fill fs-6" aria-label="ri-checkbox-blank-circle-fill"></i>
                            Scalling
                            
                        </span>
                        
                        
                        <?php
                      } ?>

                    
                  </div>

                  <div class="api-endpoint-container">
                    <div class="api-endpoint">
                      <label for="api-endpoint-label fw-bold text-gray" class="form-label">Api Endpoint</label>
                      <div class="container text-gray fs-5 bg-gray-300 p-3 text-center">
                        In Progress ...
                      </div>
                    </div>
                  </div>


                  <div class="rest-section mt-4">

                    <div class="node-version pt-2 pb-2">
                      <div class="row">
                        <div class="col-6 fs-5">Node Version</div>
                        <div class="col-6 fw-bold text-end fs-4">0.1.0</div>
                      </div>
                    </div>

                    <div class="running-time pt-2 pb-2">
                      <div class="row">
                        <div class="col-6 fs-5">Running Time</div>
                        <div class="col-6 fw-bold text-end fs-4">--</div>
                      </div>
                    </div>

                    <div class="location pt-2 pb-2">
                      <div class="row">
                        <div class="col-6 fs-5">Location</div>
                        <div class="col-6 fw-bold text-end fs-4">United States</div>
                      </div>
                    </div>

                    <div class="last-update pt-2 pb-2">
                      <div class="row">
                        <div class="col-6 fs-5">Last Update</div>
                        <div class="col-6 fw-bold text-end fs-4">--</div>
                      </div>
                    </div>
                  </div>

                  <div class="d-grid gap-2 mt-4">
                    <button class="btn btn-outline-secondary pt-2 pb-2 fs-4" type="button">Delete Node</button>
                  </div>
                </div>
            </div>

          </div>
          <div class="col-8">

              <div class="card p-2 m-0">
                <div class="card-body">
                
                  <div class="progress-container mb-3">
                    <div class="progress">
                      <div class="progress-bar progress-bar-striped progress-bar-animated " id="progress-bar" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                  </div>
                  <div class="job-log-container mt-4 ">
                    <div class="cluster-job-log pt-3 pb-2 bg-dark p-3" style="min-height: 50vh;max-height: 60vh;overflow: scroll;">
                      <?php foreach($job_data as $data){ ?>
                        <h6 class='h6 text-white font-monospace'><?php echo $data; ?></h6>
                      <?php } ?>
                    </div>
                  </div>
                </div>
              </div>

          </div>
        </div>

      </div>

    </div>
    <script>

        function completeProgressBar(data) {
            if(data == "passed"){
              progressBar.addClass("bg-success");
              progressBar.removeClass("bg-primary");
              progressBar.css('width', '100%');

            }else{
              progressBar.css('width', '100%');
              progressBar.addClass("bg-danger");
              progressBar.removeClass("bg-primary");
            }
            $('.cluster-job-log').scrollTop($('.cluster-job-log')[0].scrollHeight);
        }

      
    <?php if($data_to_send['status'] == 0){?>
      let progress = 0;
        let progressBar = $("#progress-bar");

        function checkStatus(statusInterval) {
            $.ajax({
                url: '/api/job/status?id='+<?php echo $clusterjobID; ?>, // Replace with your actual endpoint
                method: 'GET',
                success: function(response) {
                    if (response.status == 'complete') {
                        clearInterval(statusInterval);
                        completeProgressBar("passed");

                        <?php if($data_to_send['whatjob']=="deploy"){
                          ?>alert("Cluster Deployed Successfully.");<?php
                        }elseif($data_to_send['whatjob'] == "destroy"){
                          ?>alert("Cluster Destroy Successfully.");<?php
                        }elseif($data_to_send['whatjob'] == "scale"){
                          ?>alert("Cluster Scalled Successfully.");<?php
                        } ?>
                        
                        
                        location.reload();
                    } else if (response.status == 'running') {
                        updateProgressBar();
                    } else if (response.status == 'failed') {
                      clearInterval(statusInterval);
                      completeProgressBar("failed");
                      alert("Cluster Deployed Failed. Please try again");
                      location.reload();
                    }
                    $('.cluster-job-log').scrollTop($('.cluster-job-log')[0].scrollHeight);
                }
            });
        }

        function updateProgressBar() {
            if (progress < 60) {
                progress += 1;
            } else {
                let remainingProgress = 100 - progress;
                progress += remainingProgress / 2;
            }

            progressBar.css('width', progress + '%');
        }
        

        let statusInterval = setInterval(checkStatus, 30000);
        let progressInterval = setInterval(updateProgressBar, 60000);

        checkStatus(statusInterval);

    <?php }elseif($data_to_send['status'] == 1){?>
      let progressBar = $("#progress-bar");
      completeProgressBar("passed");
    <?php }elseif($data_to_send['status'] == 2){ ?>
      let progressBar = $("#progress-bar");
      completeProgressBar("failed");
    <?php }else{ ?>
      let progressBar = $("#progress-bar");
      completeProgressBar("failed");
    <?php } ?>


    </script>
@endsection
