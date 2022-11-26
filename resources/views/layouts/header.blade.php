<header id="header" class="header fixed-top d-flex align-items-center">
  <div class="d-flex align-items-center justify-content-between">
    <a href="index.html" class="logo d-flex align-items-center">
      <img src="assets/img/logo.png" alt="" />
      <span class="d-none d-lg-block project-name"></span>
    </a>
    <i class="bi bi-list toggle-sidebar-btn d-none d-md-block"></i>
  </div>
  <!-- End Logo -->

  <div class="search-bar">
    <form
      class="search-form d-flex align-items-center rounded-pill"
      method="POST"
      action="#"
    >
      <input
        type="text"
        name="query"
        placeholder="Search"
        title="Enter search keyword"
        class="rounded-pill"
      />
      <button type="submit" title="Search">
        <i class="bi bi-search"></i>
      </button>
    </form>
  </div>
  <!-- End Search Bar -->

  <nav class="header-nav ms-auto">
    <ul class="d-flex align-items-center">
      <li class="nav-item d-block d-lg-none">
        <a class="nav-link nav-icon search-bar-toggle" href="#">
          <i class="bi bi-search"></i>
        </a>
      </li>
      <!-- End Search Icon-->

      <li class="nav-item me-3">
        <a href="{{ route('formBuilder') }}" class="btn btn-outline-primary rounded-pill d-flex"
          ><i class="bi bi-basket"></i>
          <span class="ms-1 d-none d-md-block">Form Builder</span></a
        >
      </li>

      <li class="nav-item dropdown">
        <a
          class="nav-link nav-icon nav-link-expand"
          id="btnFullscreen"
          href="javascript:void(0)"
        >
          <i class="bi bi-fullscreen"></i>
        </a>
      </li>
      <!-- End Notification Nav -->

      <li class="nav-item dropdown">
        <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
          <i class="bi bi-bell"></i>
          <span class="badge bg-primary badge-number">3</span> </a
        ><!-- End Messages Icon -->

        <ul
          class="dropdown-menu dropdown-menu-end dropdown-menu-arrow messages"
        >
          <li class="dropdown-header">
            You have 3 new messages
            <a href="#"
              ><span class="badge rounded-pill bg-primary p-2 ms-2"
                >View all</span
              ></a
            >
          </li>
          <li>
            <hr class="dropdown-divider" />
          </li>

          <li class="message-item">
            <a href="#">
              <img
                src="assets/img/messages-1.jpg"
                alt=""
                class="rounded-circle"
              />
              <div>
                <h4>Maria Hudson</h4>
                <p>
                  Velit asperiores et ducimus soluta repudiandae labore
                  officia est ut...
                </p>
                <p>4 hrs. ago</p>
              </div>
            </a>
          </li>
          <li>
            <hr class="dropdown-divider" />
          </li>

          <li class="message-item">
            <a href="#">
              <img
                src="assets/img/messages-2.jpg"
                alt=""
                class="rounded-circle"
              />
              <div>
                <h4>Anna Nelson</h4>
                <p>
                  Velit asperiores et ducimus soluta repudiandae labore
                  officia est ut...
                </p>
                <p>6 hrs. ago</p>
              </div>
            </a>
          </li>
          <li>
            <hr class="dropdown-divider" />
          </li>

          <li class="message-item">
            <a href="#">
              <img
                src="assets/img/messages-3.jpg"
                alt=""
                class="rounded-circle"
              />
              <div>
                <h4>David Muldon</h4>
                <p>
                  Velit asperiores et ducimus soluta repudiandae labore
                  officia est ut...
                </p>
                <p>8 hrs. ago</p>
              </div>
            </a>
          </li>
          <li>
            <hr class="dropdown-divider" />
          </li>

          <li class="dropdown-footer">
            <a href="#">Show all messages</a>
          </li>
        </ul>
        <!-- End Messages Dropdown Items -->
      </li>
      <!-- End Messages Nav -->

      <li class="nav-item dropdown pe-3">
        <a
          class="nav-link nav-profile d-flex align-items-center pe-0"
          href="#"
          data-bs-toggle="dropdown"
        >
          <span class="d-none d-md-block dropdown-toggle ps-2"
            >Super Admin</span
          >
          <span class="text-muted">|</span>
          <img
            src="assets/img/profile-img.jpg"
            alt="Profile"
            class="rounded-circle"
          /> </a
        ><!-- End Profile Iamge Icon -->

        <ul
          class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile"
        >
          <li class="dropdown-header">
            <h6>Kelly O.</h6>
            <span>Super Admin</span>
          </li>
          <li>
            <hr class="dropdown-divider" />
          </li>

          <li>
            <a
              class="dropdown-item d-flex align-items-center"
              href="users-profile.html"
            >
              <i class="bi bi-person"></i>
              <span>My Profile</span>
            </a>
          </li>
          <li>
            <hr class="dropdown-divider" />
          </li>

          <li>
            <a
              class="dropdown-item d-flex align-items-center"
              href="users-profile.html"
            >
              <i class="bi bi-gear"></i>
              <span>Account Settings</span>
            </a>
          </li>
          <li>
            <hr class="dropdown-divider" />
          </li>

          <li>
            <a
              class="dropdown-item d-flex align-items-center"
              href="pages-faq.html"
            >
              <i class="bi bi-question-circle"></i>
              <span>Need Help?</span>
            </a>
          </li>
          <li>
            <hr class="dropdown-divider" />
          </li>

          <li>
            <a class="dropdown-item d-flex align-items-center" href="#">
              <i class="bi bi-box-arrow-right"></i>
              <span>Sign Out</span>
            </a>
          </li>
        </ul>
        <!-- End Profile Dropdown Items -->
      </li>
      <!-- End Profile Nav -->

      <li></li>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </ul>
  </nav>
  <!-- End Icons Navigation -->
</header>