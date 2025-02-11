@extends('admin/layouts/master')
@section('content')
<div class="main main-app p-3 p-lg-4">
            <style>
                .center-screen {
                    margin-top: 120px;
                    margin: auto;
                }

                .emptytext {
                    text-align: center;
                }

                .head-box {
                    display: flex;
                    justify-content: space-between;
                    width: 100%;
                    padding: 20px;
                }

                .left,
                .right {
                    display: flex;
                    gap: 10px;
                }

                th,
                td {
                    padding: 8px;
                    text-align: center;
                }

                .expandable-content {
                    display: none;
                }

                .tbltwo {
                    background-color: #ebebeb;
                }

                .table-one thead th:not(:first-child),
                .table-one tbody td:not(:first-child) {
                    text-align: center;
                }

                .icon-text-container {
                    background-color: #CFE8FF;
                    border-radius: 20px;
                    display: inline-flex;
                    align-items: center;
                    width: 111px;
                    height: 32px;
                }

                .container-text {
                    margin-left: 5px;
                    /* Adds space between the icon and text */
                    color: #22588A;
                    /* Optional: Changes text color for better visibility */
                    margin-top: 13px;
                }
            </style>

            @if( count($applications_data) == 0 )
            <div class="text-center">
                <div class="card w-40 p-3 text-center m-auto rounded-3">
                    <!-- <img src="../assets/img/img2.jpg" class="card-img-top" alt="..."> -->
                    <div class="card-body">
                        <h5 class="card-title text-dark">No application has not yet been deployed in any of our clusters. Login as a client and deploy an application. Once an application is deployed, it will appear in the list over here.</h5>
                    </div>
                </div>
            </div>
            @else



            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h3>Application Manager</h3>
                </div>

            </div>
            <div class="col-md-12 center-screen">
                <div class="card card-one">
                    <div class="card-header border-0">
                        <div class="head-box align-items-center">
                            <div class="left">
                                <h5 class="m-0">
                                    <span style="color:#9e9e9e">Applications: <?php echo count($applications_data); ?></span>
                                </h5>
                                <h5 class="m-0">
                                    <span style="color:#9e9e9e">Containers: <?php echo count($applications_data)*2; ?></span>
                                </h5>
                            </div>
                            <div class="right">
                                <select class="filter_cl" name="cluster_filter[]" id="cluster_filter" style="width:100%;" multiple="multiple">
                                    <option value="1">Option 1</option>
                                    <option value="2">Option 2</option>
                                    <option value="3">Option 3</option>
                                </select>
                                <select class="filter_cu" name="customer_filter[]" id="customer_filter" style="width:100%;" multiple="multiple">
                                    <option>option 1</option>
                                    <option>option 2</option>
                                </select>
                            </div>
                        </div>
                    </div><!-- card-header -->
                    <div class="card-body">
                        <div class="container-fluid emptytext">
                            <div class="table-responsive">
                                <table class="table table-one">
                                    <tbody>
                                        @foreach($applications_data as $application)
                                        <tr class="expandable-row">
                                            <td>{{ $application->uuid }}</td>
                                            <td>{{ $application->services }}</td>
                                            <td>{{ $application->domain }}</td>
                                            <td>{{ $application->image_names }}</td>
                                            <td>
                                                <div class="icon-text-container">
                                                    <i class="fa fa-arrow-down" style="margin-left: 5px;"></i>
                                                    <i class="fa fa-bars"></i>
                                                    <p class="container-text"> 2 Services</p>
                                                </div>
                                            </td>
                                            <td><i class="fa fa-pencil"></td>
                                        </tr>
                                        <tr class="expandable-content">
                                            <td colspan="6">
                                                <table class="table tbltwo">
                                                    <thead>
                                                        <tr>
                                                            <th>Container id</th>
                                                            <th>Container name</th>
                                                            <th>created at</th>
                                                            <th>status</th>
                                                            <th>Test</th>
                                                            <th>Test</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>2rsgshy45gna</td>
                                                            <td>mysql</td>
                                                            <td>26/06/2024</td>
                                                            <td>Active</td>
                                                            <td>26/06/2024</td>
                                                            <td>Active</td>
                                                        </tr>
                                                        <tr>
                                                            <td>3hdjsdj45ajh</td>
                                                            <td>wordpress</td>
                                                            <td>26/06/2024</td>
                                                            <td>Active</td>
                                                            <td>26/06/2024</td>
                                                            <td>Active</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>



                        </div>
                    </div><!-- card-body -->
                </div><!-- card -->
            </div>
            @endif
            <script>
               
                document.querySelectorAll('.expandable-row').forEach((row, index) => {
                    row.addEventListener('click', () => {
                        const content = row.nextElementSibling;
                        if (content && content.classList.contains('expandable-content')) {
                            content.style.display = content.style.display === 'table-row' ? 'none' : 'table-row';
                        }
                    });
                });
            </script>
</div>
@endsection
