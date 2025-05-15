@extends('layouts.studentApp')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-body">
            <h3 class="mb-3">My Profile</h3>
            <ul class="list-unstyled">
                <li><strong>Name:</strong> {{ $user->name }}</li>
                <li><strong>Email:</strong> {{ $user->email }}</li>
                <li><strong>Role:</strong> {{ ucfirst($user->role) }}</li>
            </ul>
        </div>
    </div>
</div>
@endsection 