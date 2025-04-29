<!-- resources/views/layouts/sidebar.blade.php -->

<div class="d-flex flex-column bg-white border-end position-fixed" style="width: 250px; height: 100vh; top: 0; left: 0; z-index: 1000; box-shadow: 2px 0 5px rgba(0,0,0,0.1);">
    <!-- Logo -->
    <div class="text-center p-3 border-bottom">
        <img src="{{ asset('MedellinLogo.png') }}" alt="Logo" class="img-fluid" style="height: 60px;">
    </div>

    <!-- Navigation -->
    <ul class="nav flex-column mt-3 px-2">
        <!-- Dashboard -->
        <li class="nav-item mb-2">
            <a class="nav-link d-flex align-items-center rounded" href="{{ route('admin.dashboard') }}">
                <i class="bi bi-speedometer2 me-2"></i> Dashboard
            </a>
        </li>

        <!-- Users -->
        <li class="nav-item mb-2">
            <a class="nav-link d-flex justify-content-between align-items-center rounded" data-bs-toggle="collapse"
                href="#usersSubmenu" role="button">
                <span><i class="bi bi-people-fill me-2"></i> Users</span>
                <i class="bi bi-chevron-down small"></i>
            </a>
            <div class="collapse ps-4" id="usersSubmenu">
                <a href="{{ route('admin.students.index') }}" class="nav-link py-2">Students</a>
                <a href="{{ route('admin.teachers.index') }}" class="nav-link py-2">Teachers</a>
            </div>
        </li>

        <!-- Subjects -->
        <li class="nav-item mb-2">
            <a class="nav-link d-flex align-items-center rounded" href="{{ route('admin.subjects.index') }}">
                <i class="bi bi-book-fill me-2"></i> Subjects
            </a>
        </li>

        <!-- Profile -->
        <li class="nav-item mb-2">
            <a class="nav-link d-flex align-items-center rounded" href="{{ route('admin.profile') }}">
                <i class="bi bi-person-fill me-2"></i> My Profile
            </a>
        </li>
    </ul>

    <!-- Bottom User Info and Logout -->
    <div class="mt-auto p-3 border-top">
        <div class="d-flex align-items-center justify-content-between">
            <a href="{{ route('admin.profile') }}" class="d-flex align-items-center text-decoration-none text-dark">
                <img src="{{ Auth::user()->profile_image ? asset('storage/' . Auth::user()->profile_image) : asset('images/user.png') }}" 
                     class="rounded-circle me-2" style="height: 32px; width: 32px; object-fit: cover;" alt="User">
                <div>
                    <div class="fw-bold">{{ Auth::user()->name }}</div>
                    <small class="text-muted">{{ ucfirst(Auth::user()->role) }}</small>
                </div>
            </a>
            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-link text-danger p-0">
                    <i class="bi bi-box-arrow-right fs-5"></i>
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
.nav-link:hover, .nav-link.active {
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