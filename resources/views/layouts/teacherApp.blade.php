<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Teacher Sidebar Layout</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body {
        min-height: 100vh;
        background-color: #f8f9fa;
    }
    .main-content {
        margin-left: 250px;
        padding: 1.5rem;
        min-height: 100vh;
    }
    .container-fluid {
        max-width: 1400px;
        margin: 0 auto;
    }
    .nav-link {
        color: #000;
    }
    .nav-link:hover, .nav-link.active {
        background-color: #f1f1f1;
        font-weight: bold;
    }
    .topbar {
        position: fixed;
        top: 24px;
        left: 270px;
        right: 32px;
        z-index: 1050;
        height: 64px;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        background: #fff;
        border-radius: 1.5rem;
        box-shadow: 0 4px 24px rgba(80,80,160,0.10);
        padding: 0 2rem 0 2rem;
        width: auto;
        min-width: 320px;
    }
    .main-content {
        margin-top: 96px;
    }
    .topbar-profile {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    .topbar-profile img {
        height: 40px;
        width: 40px;
        object-fit: cover;
        border-radius: 50%;
        border: 2px solid #ff6aab;
    }
    .topbar-profile .fw-bold {
        font-size: 1rem;
    }
    .topbar-profile small {
        color: #888;
        font-size: 0.85rem;
    }
    .dropdown-menu {
        min-width: 180px;
        border-radius: 1rem;
        border: none;
        margin-top: 0.5rem;
    }
    .dropdown-item:active, .dropdown-item:focus {
        background: #f1eaff;
        color: #6f4ef2;
    }
    @media (max-width: 991px) {
        .topbar {
            left: 80px;
            right: 8px;
            padding: 0 1rem;
        }
        .main-content {
            margin-top: 80px;
        }
    }
  </style>
</head>
<body>

  @include('layouts.teacherSidebar')

  <!-- Topbar -->
  <div class="topbar">
      <div class="dropdown">
          <a href="#" class="d-flex align-items-center text-decoration-none text-dark topbar-profile dropdown-toggle" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
              <img src="{{ Auth::user()->profile_picture ?? asset('images/mae.png') }}" alt="User">
              <div class="ms-2">
                  <div class="fw-bold">{{ Auth::user()->name }}</div>
                  <small>{{ ucfirst(Auth::user()->role) }}</small>
              </div>
          </a>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
              <li>
                  <a class="dropdown-item d-flex align-items-center" href="{{ route('teachers.profile') }}">
                      <i class="bi bi-person me-2"></i> My Profile
                  </a>
              </li>
              <li><hr class="dropdown-divider"></li>
              <li>
                  <form action="{{ route('logout') }}" method="POST" class="d-inline">
                      @csrf
                      <button type="submit" class="dropdown-item d-flex align-items-center text-danger">
                          <i class="bi bi-box-arrow-right me-2"></i> Logout
                      </button>
                  </form>
              </li>
          </ul>
      </div>
  </div>

  <div class="main-content">
      <div class="container-fluid">
          @yield('content')
      </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
