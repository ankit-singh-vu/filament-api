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

            <div class="text-center">
                <div class="card w-40 p-3 text-center m-auto rounded-3">
                    <!-- <img src="../assets/img/img2.jpg" class="card-img-top" alt="..."> -->
                    <div class="card-body">
                        <h5 class="card-title text-dark">Comming soon ...</h5>
                    </div>
                </div>
            </div>


           
</div>
@endsection
