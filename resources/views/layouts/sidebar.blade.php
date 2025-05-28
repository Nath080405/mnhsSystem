<!-- resources/views/layouts/sidebar.blade.php -->

<div class="materio-sidebar d-flex flex-column align-items-start pt-3 px-3">
    <!-- Logo -->
    <div class="w-100 d-flex align-items-center mb-2 logo-section">
        <img src="{{ asset('MedellinLogo.png') }}" alt="Logo" class="materio-logo me-3">
        <span class="sidebar-appname">MNHS Student Information Portal</span>
    </div>

    <!-- Main Navigation -->
    <div class="w-100 mt-1">
        <div class="sidebar-section-title">MAIN</div>
        <ul class="nav flex-column materio-nav">
            <li class="nav-item mb-1">
                <a class="nav-link d-flex align-items-center materio-link @if(request()->routeIs('admin.dashboard')) active @endif" href="{{ route('admin.dashboard') }}">
                    <i class="bi bi-speedometer2 me-3"></i> Dashboard
                </a>
            </li>
            <li class="nav-item mb-1">
                <a class="nav-link d-flex align-items-center materio-link @if(request()->routeIs('admin.students.index') || request()->routeIs('admin.teachers.index')) active @endif" data-bs-toggle="collapse" href="#usersSubmenu" role="button">
                    <i class="bi bi-people-fill me-3"></i> Users
                    <i class="bi bi-chevron-down ms-auto small"></i>
                </a>
                <div class="collapse ps-4" id="usersSubmenu">
                    <a href="{{ route('admin.students.index') }}" class="nav-link materio-sublink">Students</a>
                    <a href="{{ route('admin.teachers.index') }}" class="nav-link materio-sublink">Teachers</a>
                </div>
            </li>
            <li class="nav-item mb-1">
                <a class="nav-link d-flex align-items-center materio-link @if(request()->routeIs('admin.subjects.*')) active @endif" data-bs-toggle="collapse" href="#subjectsSubmenu" role="button">
                    <i class="bi bi-book-fill me-3"></i> Subjects
                    <i class="bi bi-chevron-down ms-auto small"></i>
                </a>
                <div class="collapse ps-4" id="subjectsSubmenu">
                    <a href="{{ route('admin.subjects.index') }}" class="nav-link materio-sublink">Subject List</a>
                    <a href="{{ route('admin.subjects.plan') }}" class="nav-link materio-sublink">Subject Plan</a>
                </div>
            </li>
            <li class="nav-item mb-1">
                <a class="nav-link d-flex align-items-center materio-link @if(request()->routeIs('admin.sections.index')) active @endif" href="{{ route('admin.sections.index') }}">
                    <i class="bi bi-building me-3"></i> Sections
                </a>
            </li>
            <li class="nav-item mb-1">
                <a class="nav-link d-flex align-items-center materio-link @if(request()->routeIs('admin.events.index')) active @endif" href="{{ route('admin.events.index') }}">
                    <i class="bi bi-calendar-event me-3"></i> Events
                </a>
            </li>
        </ul>
    </div>
</div>

<style>
.materio-sidebar {
    width: 260px;
    min-height: 100vh;
    background: #fff;
    border-radius: 0 2rem 2rem 0;
    box-shadow: 2px 0 24px rgba(80, 80, 160, 0.10);
    position: fixed;
    left: 0;
    top: 0;
    z-index: 1040;
    transition: box-shadow 0.2s;
    color: #5A5A89;
}
.logo-section {
    margin-bottom: 1.2rem;
    padding: 0.5rem 0 0.5rem 0.5rem;
    justify-content: flex-start;
    background: none;
    border-radius: 1.5rem 1.5rem 0 0;
}
.materio-logo {
    height: 72px;
    width: 72px;
    object-fit: contain;
    border-radius: 16px;
    background: #f5f6fa;
    box-shadow: 0 2px 8px rgba(80,80,160,0.06);
    padding: 8px;
}
.sidebar-appname {
    font-family: 'Poppins', 'Segoe UI', Arial, sans-serif;
    font-size: 1rem;
    font-weight: 600;
    color: #4B4B8F;
    letter-spacing: 0.04em;
    text-shadow: 0 2px 8px rgba(80,80,160,0.06);
}
.sidebar-section-title {
    font-size: 0.75rem;
    font-weight: 700;
    color: #b0b3c6;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    margin-bottom: 0.5rem;
    margin-top: 0.2rem;
    padding-left: 0.5rem;
}
.materio-nav {
    width: 100%;
}
.materio-link {
    color: #5A5A89;
    font-weight: 500;
    border-radius: 2rem;
    padding: 0.65rem 1.25rem;
    transition: background 0.2s, color 0.2s;
    font-size: 1.05rem;
}
.materio-link.active, .materio-link:hover {
    background: linear-gradient(90deg, #e3e6ff 0%, #f5f6fa 100%);
    color: #6f4ef2;
    font-weight: 600;
}
.materio-sublink {
    color: #a0a3b6;
    font-size: 0.98rem;
    margin-left: 1.5rem;
    border-radius: 1rem;
    transition: background 0.2s, color 0.2s;
    padding: 0.45rem 1rem;
}
.materio-sublink:hover, .materio-sublink.active {
    background: #f5f6fa;
    color: #6f4ef2;
}
@media (max-width: 991px) {
    .materio-sidebar {
        width: 70px;
        border-radius: 0 1rem 1rem 0;
        padding: 0.5rem 0.25rem;
    }
    .sidebar-appname, .sidebar-section-title {
        display: none;
    }
    .logo-section img {
        height: 40px !important;
        width: 40px !important;
    }
}
</style>