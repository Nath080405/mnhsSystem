@extends('layouts.studentApp')

@section('content')
<div class="container-fluid py-3">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1" style="color: #0d6efd;">School Events, Annoucements & Updates</h2>
            <p class="text-muted mb-0 small">View and track school events and activities</p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="card shadow-lg border-0">
        <div class="card-body p-4">
            @if($recentEvents->count() > 0 || $oldEvents->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th style="color: #0d6efd;">Event Title</th>
                                <th style="color: #0d6efd;">Status</th>
                                <th style="color: #0d6efd;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentEvents as $event)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle me-2">
                                                <i class="bi bi-calendar-event" style="color: #0d6efd;"></i>
                                            </div>
                                            <div>
                                                <div class="fw-medium">
                                                    {{ $event->title }}
                                                    <span class="badge bg-danger ms-2">Recent</span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $event->status === 'Upcoming' ? 'success' : ($event->status === 'Completed' ? 'info' : 'danger') }}">
                                            <i class="bi bi-{{ $event->status === 'Upcoming' ? 'clock' : ($event->status === 'Completed' ? 'check-circle' : 'x-circle') }} me-1"></i>
                                            {{ $event->status }}
                                        </span>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#eventModal{{ $event->event_id }}">
                                            <i class="bi bi-eye me-1"></i> View Details
                                        </button>
                                    </td>
                                </tr>

                                <!-- Event Modal -->
                                <div class="modal fade" id="eventModal{{ $event->event_id }}" tabindex="-1" aria-labelledby="eventModalLabel{{ $event->event_id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="modal-section">
                                                    <div class="section-icon">
                                                        <i class="bi bi-card-text"></i>
                                                    </div>
                                                    <h6 class="section-title">Description</h6>
                                                    <p class="section-content">{{ $event->description }}</p>
                                                </div>

                                                <div class="modal-section">
                                                    <div class="section-icon">
                                                        <i class="bi bi-calendar2-week"></i>
                                                    </div>
                                                    <h6 class="section-title">Schedule</h6>
                                                    <div class="section-content">
                                                        <div class="info-item">
                                                            <i class="bi bi-calendar-date"></i>
                                                            <span>{{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}</span>
                                                        </div>
                                                        <div class="info-item">
                                                            <i class="bi bi-clock"></i>
                                                            <span>{{ \Carbon\Carbon::parse($event->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($event->end_time)->format('h:i A') }}</span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="modal-section">
                                                    <div class="section-icon">
                                                        <i class="bi bi-geo-alt"></i>
                                                    </div>
                                                    <h6 class="section-title">Location</h6>
                                                    <div class="info-item">
                                                        <i class="bi bi-pin-map"></i>
                                                        <span class="section-content">{{ $event->location }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            @foreach($oldEvents as $event)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle me-2">
                                                <i class="bi bi-calendar-event" style="color: #0d6efd;"></i>
                                            </div>
                                            <div>
                                                <div class="fw-medium">{{ $event->title }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $event->status === 'Upcoming' ? 'success' : ($event->status === 'Completed' ? 'info' : 'danger') }}">
                                            <i class="bi bi-{{ $event->status === 'Upcoming' ? 'clock' : ($event->status === 'Completed' ? 'check-circle' : 'x-circle') }} me-1"></i>
                                            {{ $event->status }}
                                        </span>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#eventModal{{ $event->event_id }}">
                                            <i class="bi bi-eye me-1"></i> View Details
                                        </button>
                                    </td>
                                </tr>

                                <!-- Event Modal -->
                                <div class="modal fade" id="eventModal{{ $event->event_id }}" tabindex="-1" aria-labelledby="eventModalLabel{{ $event->event_id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="modal-section">
                                                    <div class="section-icon">
                                                        <i class="bi bi-card-text"></i>
                                                    </div>
                                                    <h6 class="section-title">Description</h6>
                                                    <p class="section-content">{{ $event->description }}</p>
                                                </div>

                                                <div class="modal-section">
                                                    <div class="section-icon">
                                                        <i class="bi bi-calendar2-week"></i>
                                                    </div>
                                                    <h6 class="section-title">Schedule</h6>
                                                    <div class="section-content">
                                                        <div class="info-item">
                                                            <i class="bi bi-calendar-date"></i>
                                                            <span>{{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}</span>
                                                        </div>
                                                        <div class="info-item">
                                                            <i class="bi bi-clock"></i>
                                                            <span>{{ \Carbon\Carbon::parse($event->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($event->end_time)->format('h:i A') }}</span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="modal-section">
                                                    <div class="section-icon">
                                                        <i class="bi bi-geo-alt"></i>
                                                    </div>
                                                    <h6 class="section-title">Location</h6>
                                                    <div class="info-item">
                                                        <i class="bi bi-pin-map"></i>
                                                        <span class="section-content">{{ $event->location }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center text-muted py-5">
                    <i class="bi bi-calendar-x" style="font-size: 3rem;"></i>
                    <p class="mt-2 mb-0">No events found.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.card {
    border-radius: 1rem;
}
.badge {
    padding: 0.5rem 1rem;
    font-weight: 500;
}
.avatar-sm {
    width: 2.5rem;
    height: 2.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
}
.table > :not(caption) > * > * {
    padding: 1rem;
}
.table-hover tbody tr:hover {
    background-color: rgba(111, 78, 242, 0.05);
}
.modal-content {
    border-radius: 2rem;
    border: none;
    box-shadow: 0 15px 35px rgba(0,0,0,0.1);
    overflow: hidden;
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
}
.modal-header {
    border-bottom: none;
    padding: 1.5rem 1.5rem 0.5rem;
    background: linear-gradient(45deg, #0d6efd 0%, #0a58ca 100%);
    min-height: 1rem;
}
.modal-body {
    padding: 1.5rem;
    background: #fff;
}
.modal-footer {
    border-top: none;
    padding: 1rem 1.5rem 1.5rem;
    background: linear-gradient(45deg, #6f4ef2 0%, #5a3fd9 100%);
}
.modal-section {
    background: #fff;
    border-radius: 1.5rem;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}
.modal-section:nth-child(1) {
    border: 3px solid #0d6efd;
}
.modal-section:nth-child(2) {
    border: 3px solid #0dcaf0;
}
.modal-section:nth-child(3) {
    border: 3px solid #198754;
}
.modal-section::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transform: rotate(45deg);
    animation: shine 3s infinite;
}
.modal-section:hover {
    transform: translateY(-5px) rotate(1deg);
}
.modal-section:nth-child(1):hover {
    box-shadow: 0 10px 20px rgba(13, 110, 253, 0.2);
}
.modal-section:nth-child(2):hover {
    box-shadow: 0 10px 20px rgba(13, 202, 240, 0.2);
}
.modal-section:nth-child(3):hover {
    box-shadow: 0 10px 20px rgba(25, 135, 84, 0.2);
}
.section-icon {
    width: 4rem;
    height: 4rem;
    border-radius: 1.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1rem;
    transition: all 0.3s ease;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}
.modal-section:nth-child(1) .section-icon {
    background: linear-gradient(45deg, #0d6efd 0%, #0a58ca 100%);
}
.modal-section:nth-child(2) .section-icon {
    background: linear-gradient(45deg, #0dcaf0 0%, #0aa2c0 100%);
}
.modal-section:nth-child(3) .section-icon {
    background: linear-gradient(45deg, #198754 0%, #146c43 100%);
}
.section-icon i {
    font-size: 1.8rem;
    color: white;
}
.section-icon:hover {
    transform: scale(1.1) rotate(5deg);
}
.section-title {
    font-size: 1.3rem;
    font-weight: 700;
    margin-bottom: 1rem;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
}
.modal-section:nth-child(1) .section-title {
    color: #0d6efd;
}
.modal-section:nth-child(2) .section-title {
    color: #0dcaf0;
}
.modal-section:nth-child(3) .section-title {
    color: #198754;
}
.section-content {
    color: #666;
    font-size: 1rem;
    line-height: 1.6;
    font-family: 'Comic Sans MS', cursive, sans-serif;
}
.info-item {
    display: flex;
    align-items: center;
    margin-bottom: 1rem;
    padding: 1rem;
    border-radius: 1rem;
    transition: all 0.3s ease;
    background: #fff;
}
.modal-section:nth-child(1) .info-item {
    border: 2px dashed #0d6efd;
}
.modal-section:nth-child(2) .info-item {
    border: 2px dashed #0dcaf0;
}
.modal-section:nth-child(3) .info-item {
    border: 2px dashed #198754;
}
.info-item:hover {
    transform: scale(1.02);
    border-style: solid;
}
.modal-section:nth-child(1) .info-item:hover {
    background: #f0f7ff;
}
.modal-section:nth-child(2) .info-item:hover {
    background: #f0faff;
}
.modal-section:nth-child(3) .info-item:hover {
    background: #f0fff4;
}
.info-item i {
    width: 3rem;
    height: 3rem;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 1rem;
    margin-right: 1rem;
    font-size: 1.3rem;
    color: white;
}
.modal-section:nth-child(1) .info-item i {
    background: linear-gradient(45deg, #0d6efd 0%, #0a58ca 100%);
}
.modal-section:nth-child(2) .info-item i {
    background: linear-gradient(45deg, #0dcaf0 0%, #0aa2c0 100%);
}
.modal-section:nth-child(3) .info-item i {
    background: linear-gradient(45deg, #198754 0%, #146c43 100%);
}
.btn-close {
    padding: 0.8rem;
    margin: -0.5rem -0.5rem -0.5rem auto;
    border-radius: 1rem;
    transition: all 0.3s ease;
    background: white;
    opacity: 1;
    filter: none;
    width: 1.5rem;
    height: 1.5rem;
    position: relative;
}
.btn-close::before {
    content: '×';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 1.5rem;
    color: #0d6efd;
    font-weight: bold;
}
.btn-close:hover {
    transform: rotate(90deg);
    background: #0d6efd;
}
.btn-close:hover::before {
    color: white;
}
.btn-light {
    padding: 0.8rem 2rem;
    border-radius: 1.5rem;
    font-weight: 600;
    transition: all 0.3s ease;
    background: white;
    color: #6f4ef2;
    border: 3px solid #6f4ef2;
    font-family: 'Comic Sans MS', cursive, sans-serif;
    font-size: 1.1rem;
}
.btn-light:hover {
    transform: translateY(-3px) scale(1.05);
    box-shadow: 0 8px 15px rgba(111, 78, 242, 0.3);
    background: #6f4ef2;
    color: white;
}
@keyframes shine {
    0% {
        transform: translateX(-100%) rotate(45deg);
    }
    100% {
        transform: translateX(100%) rotate(45deg);
    }
}
</style>
@endsection

