<div class="d-flex flex-column border-end position-fixed sidebar-custom"
    style="width: 250px; height: 100vh; top: 0; left: 0; z-index: 1000; box-shadow: 2px 0 5px rgba(0,0,0,0.1);">

    <!-- Logo -->
    <div class="text-center p-3 border-bottom">
        <img src="{{ asset('MedellinLogo.png') }}" alt="Logo" class="img-fluid" style="height: 60px;">
    </div>

    <!-- Navigation -->
    <ul class="nav flex-column mt-3 px-2">
        <!-- Dashboard -->
        <li class="nav-item mb-2">
            <a class="nav-link d-flex align-items-center rounded" href="{{ route('teachers.dashboard') }}">
                <i class="bi bi-house-door me-2"></i> Dashboard
            </a>
        </li>

        <li class="nav-item mb-2">
            <a class="nav-link d-flex justify-content-between align-items-center rounded" data-bs-toggle="collapse"
                href="#usersSubmenu" role="button">
                <span><i class="bi bi-people-fill me-2"></i> Students</span>
                <i class="bi bi-chevron-down small"></i>
            </a>
            <div class="collapse ps-4" id="usersSubmenu">
                <a href="{{ route('teachers.student.index') }}" class="nav-link py-2">My students</a>
                <a href="{{ route('teachers.student.grade.index') }}" class="nav-link py-2">Grades of students</a>
            </div>
        </li>

        <li class="nav-item mb-2">
        <a class="nav-link d-flex align-items-center rounded" href="{{ route('teachers.subject.index') }}">
         <i class="bi bi-calendar-event me-2"></i> Subjects
        </a>
        </li>
        
        <li class="nav-item mb-2">
        <a class="nav-link d-flex align-items-center rounded" href="{{ route('teachers.event.index') }}">
                <i class="bi bi-calendar-week me-2"></i> Events
        </a>
        </li>
    </ul>

<!-- Bottom User Info and Logout -->
<div class="mt-auto p-3 border-top">
    <div class="d-flex align-items-center gap-2">
        <!-- User Image -->
        <img src="{{ Auth::user()->profile_picture ?? asset('images/mae.png') }}"
             alt="User" class="rounded-circle border"
             style="height: 40px; width: 40px; object-fit: cover;">

        <!-- User Info -->
        <div class="flex-grow-1">
            <div class="fw-semibold text-dark" style="font-size: 0.95rem;">
                {{ Auth::user()->name }}
            </div>
            <div class="text-muted" style="font-size: 0.8rem;">
                {{ ucfirst(Auth::user()->role) }}
            </div>
        </div>

        <!-- Logout Button -->
        <form action="{{ route('logout') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-sm btn-light border text-danger" title="Logout">
                <i class="bi bi-box-arrow-right"></i>
            </button>
        </form>
    </div>
</div>
</div>

<style>
    .nav-link {
        color: #495057;
        padding: 0.5rem 1rem;
    }

    .nav-link:hover,
    .nav-link.active {
        background-color: #e9ecef;
        color: #0d6efd;
        font-weight: 500;
    }

    .dropdown-menu {
        min-width: 200px;
        border: none;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }

    .dropdown-item {
        padding: 0.5rem 1rem;
    }

    .dropdown-item:hover {
        background-color: #f8f9fa;
        color: #0d6efd;
    }

    .dropdown-item i {
        font-size: 1.1rem;
    }

    .badge {
        font-size: 0.6rem;
        padding: 0.25em 0.4em;
    }
</style>