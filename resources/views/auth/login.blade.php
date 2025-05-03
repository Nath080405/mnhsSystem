@extends('layouts.login')

@section('content')
<div class="container">
    <div class="row justify-content-center min-vh-100 align-items-center">
        <div class="col-md-4">
            <div class="card shadow-lg border-0">
                <div class="card-body p-3">
                    <!-- Logo -->
                    <div class="text-center mb-3">
                        <img src="{{ asset('MedellinLogo.png') }}" alt="Logo" class="img-fluid mb-2" style="height: 60px;">
                        <h2 class="fw-bold text-primary mb-1" style="font-size: 1.5rem;">Welcome!</h2>
                        <p class="text-muted small">Please sign in to your account</p>
                    </div>

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show py-2" role="alert">
                            <i class="bi bi-exclamation-circle-fill me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="username" class="form-label text-muted small">School ID</label>
                            <div class="input-group shadow-sm">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="bi bi-envelope text-muted"></i>
                                </span>
                                <input type="text" class="form-control border-start-0 @error('username') is-invalid @enderror" 
                                       id="username" name="username" value="{{ old('username') }}" required autofocus
                                       placeholder="Enter your email">
                            </div>
                            @error('username')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label text-muted small">Password</label>
                            <div class="input-group shadow-sm">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="bi bi-lock text-muted"></i>
                                </span>
                                <input type="password" class="form-control border-start-0 @error('password') is-invalid @enderror" 
                                       id="password" name="password" required
                                       placeholder="Enter your password">
                            </div>
                            @error('password')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                <label class="form-check-label text-muted small" for="remember">
                                    Remember me
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2 mb-2 shadow-sm">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border-radius: 6px;
    border: none;
    background: white;
    max-width: 360px;
    margin: 0 auto;
}
.shadow-lg {
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12) !important;
}
.card-body {
    padding: 1.5rem !important;
}
.input-group-text {
    border-right: none;
    background-color: white;
    color: #6c757d;
    padding: 0.4rem 0.6rem;
    font-size: 0.875rem;
}
.form-control {
    border-left: none;
    padding: 0.4rem 0.6rem;
    background-color: white;
    font-size: 0.875rem;
    height: 36px;
}
.form-control:focus {
    box-shadow: none;
    border-color: #dee2e6;
    background-color: white;
}
.input-group:focus-within {
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
    border-radius: 0.375rem;
}
.input-group:focus-within .input-group-text,
.input-group:focus-within .form-control {
    border-color: #86b7fe;
}
.btn-primary {
    background-color: #4267B2;
    border-color: #4267B2;
    font-weight: 500;
    padding: 0.4rem 0.75rem;
    border-radius: 4px;
    font-size: 0.875rem;
    height: 36px;
}
.btn-primary:hover {
    background-color: #385499;
    border-color: #385499;
}
.form-label {
    color: #495057;
    font-size: 0.813rem;
    margin-bottom: 0.25rem;
}
.text-muted {
    color: #6c757d !important;
}
.form-check-input:checked {
    background-color: #4267B2;
    border-color: #4267B2;
}
.alert {
    border-radius: 4px;
    font-size: 0.813rem;
    padding: 0.5rem 0.75rem;
}
.mb-3 {
    margin-bottom: 0.75rem !important;
}
.mb-4 {
    margin-bottom: 1rem !important;
}
img.img-fluid {
    height: 50px !important;
}
h4.fw-bold {
    font-size: 1.25rem;
}
.small {
    font-size: 0.813rem;
}
.form-check-label {
    font-size: 0.813rem;
}
</style>
@endsection 