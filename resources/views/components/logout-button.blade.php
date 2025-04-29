@props(['class' => ''])

<form action="{{ route('logout') }}" method="POST" class="d-inline">
    @csrf
    <button type="submit" class="btn btn-outline-danger shadow-sm {{ $class }}">
        <i class="bi bi-box-arrow-right me-2"></i>Logout
    </button>
</form> 