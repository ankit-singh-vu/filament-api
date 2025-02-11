<style>
  #cAvatar {
    border: 1px solid #b5b1b1;
    border-radius: 50%;
    height: 80px;
    width: 80px;
    padding: 5px;
  }

  #cAvatar img {
    object-fit: contain;
    width: 100%;
    height: 100%;
  }

  .tag-btn {
    color: #094f81;
    border: 2px solid #e2e2e2;
    border-radius: 7px;
    margin-right: 8px;
    font-weight: 600;
  }



  [data-tab-info] {
    display: none;
  }

  .active[data-tab-info] {
    display: block;
  }

  .tab-content {
    margin-top: 1.5rem;
    padding: 1rem;
    font-size: 20px;
    font-weight: bold;
    color: rgb(0, 0, 0);
  }

  .tabs {
    border-bottom: 1px solid #0000002d;
    font-size: 17px;
    display: flex;
    justify-content: space-around;
    margin: 0;
  }

  .tabs span {
    padding: 10px;
    border: 1px solid rgb(255, 255, 255);
    width: 50%;
    text-align: center;
  }

  .tabs span:hover {
    cursor: pointer;
    color: black;
    border-bottom: 2px solid #506fd9;
  }

  .tabs span.active {
    border-bottom: 2px solid #506fd9;
  }

  .send-nudgebox {
    padding: 6px 10px;
    background: #FEF6E9;
    border-radius: 4px;
    gap: 5px;
  }

  .send-nudgebox i {
    font-size: 16px;
  }

  .stats-overview div {
    width: 33.33%;
    border-right: 1px solid #e2e5ec;
    padding-left: 30px;
  }

  .teambox-wrap {
    padding-bottom: 10px;
  }

  .teambox {
    margin-bottom: 20px;
  }

  .teambox .email-item {
    padding: 0;
  }

  .teambox .email-item .email-item-body h6 {
    font-size: 10px;
  }

  .teambox .teambox-inner .email-item:hover {
    background-color: transparent;
  }

  .teambox .teambox-inner {
    padding-right: 30px;
    font-size: 12px;
  }

  .teambox .teambox-inner .number {
    font-size: 12px;
    padding: 4px 6px;
    color: #094f81;
    border: 2px solid #e2e2e2;
    border-radius: 7px;
    font-weight: 600;
  }

  .comp-dtls-wrap .comp-dtls-box {
    display: flex;
    margin-bottom: 20px;
  }
</style>
<div class="card-header">
  <h6 class="card-title">View Catalog Details</h6>
  <nav class="nav nav-icon nav-icon-sm ms-auto">
    <!-- <a href="" class="nav-link"><i class="ri-refresh-line"></i></a> -->
    <!-- <a href="" class="nav-link"><i class="ri-more-2-fill"></i></a> -->
  </nav>
</div><!-- card-header -->

<div class="card-body p-0">
  <div class="d-sm-flex p-2 p-sm-4 p-md-2 p-xl-4">
    <div id="cAvatar" class="mb-3 mb-sm-0">
      <!-- <div class="avatar avatar-xl"><img src="{{$catalogitem->imagesrc}}" style="object-fit: contain;" alt=""></div> -->
      <img src="{{$catalogitem->imagesrc}}" class="rounded float-end w-200" alt="...">
    </div>
    <div class="flex-fill ps-3">

      <div class="d-flex align-items-center tag-box justify-content-between mb-2">
        <h5 id="cName" class="mb-0 text-dark fw-semibold">{{$catalogitem->name}}</h5>
        <button type="button" class="btn btn-outline-primary btn-sm">Edit</button>
      </div>
      <h6>{{ $catalogitem->description }}</h6>
      <p class="mb-2" style="font-size: 11px;">www.wordpress.com</p>
      <p style="font-size: 11px;">info@wordpress.com</p>
      <div class="d-flex tag-box">
        <a href="" class="btn btn-outline-primary btn-sm tag-btn">wordpress</a>
        <a href="" class="btn btn-outline-primary btn-sm tag-btn">Convesio</a>
        <a href="" class="btn btn-outline-primary btn-sm tag-btn">WP</a>
      </div>
    </div>
  </div>
  <hr class="m-0">
  <div class="pt-2">
    <div class="tabs">
      <span data-tab-value="#overview" class="fw-semibold">Overview</span>
      <span data-tab-value="#release" class="fw-semibold">Release</span>
    </div>

    <div class="tab-content">
      <div class="tabs__tab active" id="overview" data-tab-info>
        <p class="fs-sm text-secondary mb-0">ALERTS</p>
        <hr class="mt-2">
        <div class="send-nudgebox d-flex">
          <div>
            <i class="mt-2 ri-error-warning-fill text-warning"></i>
          </div>
          <div style="display: grid;">
            <label class="fw-medium fs-sm text-dark mb-0">This Company has not engaged in last 60 days</label>
            <a href="#" class="fw-semibold fs-sm text-primary d-flex align-items-center">Send Nudge <i class="ri-arrow-right-line"></i></a>
          </div>
        </div>
        <p class="fs-sm text-secondary mb-0 mt-4">STATS OVERVIEW</p>
        <hr class="mt-2">
        <div class="stats-overview d-flex justify-content-between">
          <div class="p-0">
            <h4 class="mb-0 text-dark fw-semibold">250k</h4>
            <p class="fs-xs text-secondary mb-0">FUNDING TO DATE</p>
          </div>
          <div class="">
            <h4 class="mb-0 text-dark fw-semibold">68</h4>
            <p class="fs-xs text-secondary mb-0">FUNDING TO DATE</p>
          </div>
          <div class="border-0">
            <h4 class="mb-0 text-dark fw-semibold">82</h4>
            <p class="fs-xs text-secondary mb-0">FUNDING TO DATE</p>
          </div>
        </div>
        <p class="fs-sm text-secondary mb-0 mt-4">TEAM</p>
        <hr class="mt-2">
        <div class="teambox-wrap">
          <div class="teambox d-flex align-items-center">
            <div class="teambox-inner p-0" style="width: 40px;">
              <span class="number">1st</span>
            </div>
            <div class="teambox-inner w-40">
              <div class="email-item">
                <a href="" class="avatar ms-3"><img src="https://organicthemes.com/demo/profile/files/2018/05/profile-pic.jpg" alt=""></a>
                <div class="email-item-body">
                  <div class="d-flex align-items-center mb-1">
                    <span class="email-item-sender text-dark fw-semibold">Patricia Anderson</span>
                  </div>
                  <h6 class="email-item-subject fs-xs text-secondary fw-semibold mb-0">FOUNDER</h6>
                </div><!-- email-item-body -->
              </div>
            </div>
            <div class="teambox-inner fs-xs text-secondary fw-semibold">
              ankit@convesio.com
            </div>
          </div>
          <div class="teambox d-flex align-items-center">
            <div class="teambox-inner p-0" style="width: 40px;">
              <span class="number">2nd</span>
            </div>
            <div class="teambox-inner w-40">
              <div class="email-item">
                <a href="" class="avatar ms-3"><img src="https://res.cloudinary.com/demo/image/facebook/65646572251.jpg" alt=""></a>
                <div class="email-item-body">
                  <div class="d-flex align-items-center mb-1">
                    <span class="email-item-sender text-dark fw-semibold">John Patric</span>
                  </div>
                  <h6 class="email-item-subject fs-xs text-secondary fw-semibold mb-0">FOUNDER</h6>
                </div><!-- email-item-body -->
              </div>
            </div>
            <div class="teambox-inner fs-xs text-secondary fw-semibold">
              kunal@convesio.com
            </div>
          </div>
          <div class="teambox d-flex align-items-center">
            <div class="teambox-inner p-0" style="width: 40px;">
              <span class="number">3rd</span>
            </div>
            <div class="teambox-inner w-40">
              <div class="email-item">
                <a href="" class="avatar ms-3"><img src="https://fdopportunities.com/wp-content/uploads/2019/12/fdo-bsherman-480x480.jpg" alt=""></a>
                <div class="email-item-body">
                  <div class="d-flex align-items-center mb-1">
                    <span class="email-item-sender text-dark fw-semibold">Dave Peterson</span>
                  </div>
                  <h6 class="email-item-subject fs-xs text-secondary fw-semibold mb-0">COO & CO-FOUNDER</h6>
                </div><!-- email-item-body -->
              </div>
            </div>
            <div class="teambox-inner fs-xs text-secondary fw-semibold">
              manajit@convesio.com
            </div>
          </div>
        </div>
        <p class="fs-sm text-secondary mb-0 mt-4">COMPANY DETAILS</p>
        <hr class="mt-2">
        <div class="comp-dtls-wrap">
          <div class="comp-dtls-box">
            <div class="w-30 fs-xs text-dark fw-semibold">Address</div>
            <div class="fs-xs text-secondary fw-semibold">RADIUS -90 -450 carrall street vancober BC V4f 6B3</div>
          </div>
          <div class="comp-dtls-box">
            <div class="w-30 fs-xs text-dark fw-semibold">Phone Number</div>
            <div class="fs-xs text-secondary fw-semibold">+1 1234567890</div>
          </div>
          <div class="comp-dtls-box">
            <div class="w-30 fs-xs text-dark fw-semibold">Year Established</div>
            <div class="fs-xs text-secondary fw-semibold">2012 (3 Years Old)</div>
          </div>
        </div>
        <p class="fs-sm text-secondary mb-0 mt-4">SOCIAL DETAILS</p>
        <hr class="mt-2">
        <div class="comp-dtls-wrap">
          <div class="comp-dtls-box">
            <div class="w-30 fs-xs text-dark fw-semibold">Test 1</div>
            <div class="fs-xs text-secondary fw-semibold">Lorem ipsum dolor, sit amet consectetur adipisicing elit</div>
          </div>
          <div class="comp-dtls-box">
            <div class="w-30 fs-xs text-dark fw-semibold">Test 3</div>
            <div class="fs-xs text-secondary fw-semibold">Lorem ipsum dolor, sit amet consectetur adipisicing elit</div>
          </div>
          <div class="comp-dtls-box">
            <div class="w-30 fs-xs text-dark fw-semibold">Test 3</div>
            <div class="fs-xs text-secondary fw-semibold">Lorem ipsum dolor, sit amet consectetur adipisicing elit</div>
          </div>
        </div>
      </div>
      <div class="tabs__tab" id="release" data-tab-info>
        <p>Release details of the application.</p>
      </div>

    </div>

  </div>


</div>
<div class="card-footer d-flex justify-content-center">
  <!-- <a href="" class="fs-sm">Manage Customers</a> -->
</div><!-- card-footer -->

<script type="text/javascript">
  // function to get each tab details
  const tabs = document.querySelectorAll('[data-tab-value]')
  const tabInfos = document.querySelectorAll('[data-tab-info]')

  tabs.forEach(tab => {
    tab.addEventListener('click', () => {
      const target = document
        .querySelector(tab.dataset.tabValue);
      tabInfos.forEach(tabInfo => {
        tabInfo.classList.remove('active')
      })
      target.classList.add('active');
    })
  })
</script>