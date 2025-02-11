<div class="sidebar sidebar-dark">
      <div class="sidebar-header">
        <!-- <a href="/" class="sidebar-logo">dashbyte</a> -->
        <div>
            <a href="/dashboard" class="header-logo mb-0">
            <img width="140" height="30" src="https://convesio.com/wp-content/uploads/2022/05/convesio-logo-mob-1.png" class="attachment-full size-full wp-image-52319" alt="convesio-logo-mob ()">
            </a>
            <h5 class="text-white">Administrator</h5>
        </div>
      </div><!-- sidebar-header -->
      <div id="sidebarMenu" class="sidebar-body">
        <div class="nav-group show">
          <a href="#" class="nav-label">Dashboard</a>
          <ul class="nav nav-sidebar">
              <li class="nav-item">
                <a href="/dashboard" class="nav-link dashboard"><i class="ri-pie-chart-2-line"></i> <span>Overview</span></a>
              </li>
              <li class="nav-item">
                <a href="/applications" class="nav-link applications"><i class="ri-calendar-todo-line"></i> <span>Applications</span></a>
              </li>
              <li class="nav-item">
                <a href="{{route('partners')}}" class="nav-link partners"><i class="ri-shopping-bag-3-line"></i> <span>Partners</span></a>
              </li>
              <li class="nav-item">
                <a href="/infrastructure" class="nav-link infrastructure"><i class="ri-bar-chart-2-fill"></i> <span>Infrastructure</span></a>
              </li>
              <li class="nav-item">
                <a href="/customers" class="nav-link customers"><i class="ri-coin-line"></i> <span>Customers</span></a>
              </li>
              <li class="nav-item">
                <a href="/monitoring" class="nav-link monitoring"><i class="ri-service-line"></i> <span>Monitoring</span></a>
              </li>
              <li class="nav-item">
                <a href="/app-catalog" class="nav-link app-catalog"><i class="ri-hard-drive-2-line"></i> <span>App Catalog</span></a>
              </li>
              <li class="nav-item">
                <a href="/settings" class="nav-link settings"><i class="ri-suitcase-2-line"></i> <span>Settings</span></a>
              </li>
          </ul>
        </div><!-- nav-group -->
        
      </div><!-- sidebar-body -->
     <!-- sidebar-footer -->
    </div><!-- sidebar -->
    <script>
      $(document).ready(function(){
        
        var url = window.location.href;
        if (url.includes('infrastructure')) {
          var lastPart ="infrastructure";
        }else{
          var lastPart = url.substring(url.lastIndexOf('/') + 1);
        }
        
        $("."+lastPart).addClass("active");

      });
    </script>