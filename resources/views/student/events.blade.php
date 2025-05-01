@extends('layouts.studentApp')

@section('content')
<div class="container-fluid" style="background-color: #f2f0f1; min-height: 100vh;">
    <div class="row">
        <!-- Main Content -->
        <div class="col-md-9 col-lg-12 ms-auto">
            <div class="p-4">
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2" style="border-color: black !important;">
                    <div>
                        <p class="mb-1 text-black-50" style="font-size: 0.9rem;">Overview</p>
                        <h3 class="mb-0 fw-bold text-black">School Events</h3>
                    </div>
                </div>

                <!-- Notifications Section -->
                <div class="notification-section" style="background-color: #ffffff;">
                    <div class="tab-header d-flex justify-content-between align-items-center">
                        <div>
                            <button class="active" data-filter="unread">Unread</button>
                            <button data-filter="urgent">Urgent</button>
                            <button data-filter="latest">Latest</button>
                        </div>
                        <button class="btn btn-dark btn-sm" id="markAllRead">+ Mark All Read</button>
                    </div>

                    <div id="notifications-container">
                        <!-- Initial notifications -->
                        <div class="notification-card urgent">
                            <strong>Mid-term Examination Schedule</strong>
                            <p class="mb-1">Examination schedules for all departments have been finalized.</p>
                            <small class="notification-date">April 25, 2025 • 9:30 AM • <span class="notification-tag tag-academic">Academic</span></small>
                        </div>

                        <div class="notification-card">
                            <strong>Scholarship Deadline</strong>
                            <p class="mb-1">Applications for the Henderson Scholarship close soon.</p>
                            <small class="notification-date">April 12, 2025 • 11:20 AM • <span class="notification-tag tag-academic">Academic</span></small>
                        </div>
                    </div>

                    <div class="more-btn" id="loadMore">Load more notifications ↓</div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.notification-section {
    border-radius: 10px;
    padding: 20px;
    margin-top: 20px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.tab-header {
    border-bottom: 1px solid #ccc;
    margin-bottom: 20px;
}

.tab-header button {
    background: none;
    border: none;
    padding: 10px 15px;
    font-weight: 600;
    cursor: pointer;
    color: #333;
}

.tab-header .active {
    color: #2ecc71;
    border-bottom: 2px solid #2ecc71;
}

.notification-card {
    background: #fff;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 15px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    position: relative;
}

.notification-card.urgent {
    border-left: 4px solid red;
    background-color: #fef2f2;
}

.notification-tag {
    font-size: 12px;
    padding: 2px 8px;
    border-radius: 12px;
    font-weight: 500;
    color: white;
}

.tag-academic {
    background-color: #0d6efd;
}

.tag-event {
    background-color: #198754;
}

.tag-admin {
    background-color: #d63384;
}

.tag-extra {
    background-color: #ffc107;
    color: #000;
}

.notification-date {
    font-size: 13px;
    color: #888;
}

.more-btn {
    color: #27ae60;
    text-align: center;
    display: block;
    margin-top: 20px;
    cursor: pointer;
    font-weight: 500;
    transition: color 0.3s ease;
}

.more-btn:hover {
    color: #219a52;
}
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const notificationsContainer = document.getElementById('notifications-container');
    const loadMoreBtn = document.getElementById('loadMore');
    const filterButtons = document.querySelectorAll('.tab-header button');
    const markAllReadBtn = document.getElementById('markAllRead');
    
    let currentPage = 1;
    let currentFilter = 'unread';
    
    // Sample additional notifications (replace with actual data from your backend)
    const additionalNotifications = {
        unread: [
            {
                title: 'Science Fair Registration',
                description: 'Register now for the annual science fair.',
                date: 'April 10, 2025 • 2:00 PM',
                type: 'event',
                urgent: false
            },
            {
                title: 'Math Competition',
                description: 'Annual math competition registration is now open.',
                date: 'April 8, 2025 • 9:00 AM',
                type: 'academic',
                urgent: false
            }
        ],
        urgent: [
            {
                title: 'Emergency School Closure',
                description: 'School will be closed tomorrow due to maintenance.',
                date: 'April 20, 2025 • 8:00 AM',
                type: 'admin',
                urgent: true
            },
            {
                title: 'Important Parent Meeting',
                description: 'Mandatory parent-teacher meeting this Friday.',
                date: 'April 18, 2025 • 2:00 PM',
                type: 'admin',
                urgent: true
            }
        ],
        latest: [
            {
                title: 'Sports Day Announcement',
                description: 'Annual sports day will be held next month.',
                date: 'April 15, 2025 • 10:00 AM',
                type: 'event',
                urgent: false
            },
            {
                title: 'New Library Hours',
                description: 'Library will now be open until 6 PM.',
                date: 'April 10, 2025 • 9:00 AM',
                type: 'admin',
                urgent: false
            }
        ]
    };

    function createNotificationCard(notification) {
        return `
            <div class="notification-card ${notification.urgent ? 'urgent' : ''}">
                <strong>${notification.title}</strong>
                <p class="mb-1">${notification.description}</p>
                <small class="notification-date">${notification.date} • <span class="notification-tag tag-${notification.type}">${notification.type}</span></small>
            </div>
        `;
    }

    function loadMoreNotifications() {
        const notifications = additionalNotifications[currentFilter] || [];
        const startIndex = (currentPage - 1) * 2;
        const endIndex = startIndex + 2;
        const pageNotifications = notifications.slice(startIndex, endIndex);
        
        pageNotifications.forEach(notification => {
            notificationsContainer.innerHTML += createNotificationCard(notification);
        });
        
        currentPage++;
        
        // Hide load more button if no more notifications
        if (endIndex >= notifications.length) {
            loadMoreBtn.style.display = 'none';
        }
    }

    // Filter button click handler
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            currentFilter = this.dataset.filter;
            currentPage = 1;
            
            // Clear existing notifications
            notificationsContainer.innerHTML = '';
            
            // Load initial notifications for the selected filter
            loadMoreNotifications();
            
            // Show load more button
            loadMoreBtn.style.display = 'block';
        });
    });

    // Load more button click handler
    loadMoreBtn.addEventListener('click', loadMoreNotifications);

    // Mark all as read button click handler
    markAllReadBtn.addEventListener('click', function() {
        // Add your mark all as read logic here
        console.log('Marking all as read');
    });
});
</script>
@endpush
@endsection
