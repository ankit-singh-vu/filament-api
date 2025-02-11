  <div class="main-footer mt-5" style="position: fixed;bottom: 0;">
        <span>&copy; 2019-2024 Convesio Inc. All rights reserved.</span>
        <!-- <span>Created by: <a href="http://themepixels.me" target="_blank">Themepixels</a></span> -->
      </div><!-- main-footer -->
    </div><!-- main -->
    
    <script src="{{secure_asset('assets/js/script.js')}}"></script>
    <script src="{{secure_asset('assets/js/db.data.js')}}"></script>
    <script src="{{secure_asset('assets/js/db.analytics.js')}}"></script>
    <script>
    $(".nav>li").each(function() {
      var navItem = $(this);
      if (navItem.find("a").attr("href") == location.pathname) {
        navItem.find("a").addClass("active");
      }
    });
    </script>
    <!-- <p >© 2019-2024 Convesio Inc. All rights reserved.</p> -->
    