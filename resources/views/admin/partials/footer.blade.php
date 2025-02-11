<div class="main-footer mt-5">
        <span>&copy; 2019-2024 Convesio Inc. All rights reserved.</span>
        <!-- <span>Created by: <a href="http://themepixels.me" target="_blank">Themepixels</a></span> -->
      </div><!-- main-footer -->
    </div><!-- main -->


    <script src="{{secure_asset('assets/lib/jquery/jquery.min.js')}}"></script>
    <script src="{{secure_asset('assets/lib/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{secure_asset('assets/lib/perfect-scrollbar/perfect-scrollbar.min.js')}}"></script>
    <script src="{{secure_asset('assets/lib/jqvmap/jquery.vmap.min.js')}}"></script>
    <script src="{{secure_asset('assets/lib/jqvmap/maps/jquery.vmap.world.js')}}"></script>
    <script src="{{secure_asset('assets/lib/apexcharts/apexcharts.min.js')}}"></script>

    <script src="{{secure_asset('assets/js/script.js')}}"></script>
    <script src="{{secure_asset('assets/js/db.data.js')}}"></script>
    <script src="{{secure_asset('assets/js/db.analytics.js')}}"></script>
    <script src="{{secure_asset('assets/lib/select2/js/select2.min.js')}}"></script>


    <!-- <script src="{{secure_asset('assets/lib/apexcharts/apexcharts.min.js')}}"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- <p >© 2019-2024 Convesio Inc. All rights reserved.</p> -->

    <script>
       $(document).ready(function() {

$('.filter_cl').select2({
    placeholder: "Filter by Cluster"
});
$('.filter_cu').select2({
    placeholder: "Filter by Customer"
});
});
    </script>
  