@extends('core::layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="card">
    <h1>Welcome to Nokuex Staff Panel</h1>
    <p>Hello, <strong>{{ Auth::guard('staff')->user()->name }}</strong>! 
       @if(Auth::guard('staff')->user()->department())
       You are logged in as <strong>{{ Auth::guard('staff')->user()->role->name }}</strong> in the 
       <strong>{{ \Modules\Core\Entities\Role::getDepartments()[Auth::guard('staff')->user()->department()] ?? 'Unknown' }}</strong> department.
       @endif
    </p>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-value">{{ $stats['total_staff'] }}</div>
        <div class="stat-label">Total Staff</div>
    </div>
    
    <div class="stat-card success">
        <div class="stat-value">{{ $stats['total_roles'] }}</div>
        <div class="stat-label">Total Roles</div>
    </div>
    
    <div class="stat-card warning">
        <div class="stat-value">{{ $stats['unread_notifications'] }}</div>
        <div class="stat-label">Unread Notifications</div>
    </div>
    
    <div class="stat-card info">
        <div class="stat-value">{{ $stats['active_chats'] }}</div>
        <div class="stat-label">Active Chats</div>
    </div>
</div>

@if(Auth::guard('staff')->user()->department() === \Modules\Core\Entities\Role::DEPARTMENT_ADMIN)
<div class="card">
    <h2>System Overview</h2>
    
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-top: 2rem;">
        <div>
            <h3 style="margin-bottom: 1rem; color: var(--primary-color);">Recent Staff</h3>
            @foreach($recent_staff as $staff)
            <div style="padding: 0.75rem; border-bottom: 1px solid #eee; display: flex; align-items: center; gap: 0.75rem;">
                <div style="width: 40px; height: 40px; border-radius: 50%; background: var(--secondary-color); color: white; display: flex; align-items: center; justify-content: center; font-weight: 600;">
                    {{ strtoupper(substr($staff->name, 0, 1)) }}
                </div>
                <div>
                    <div style="font-weight: 600;">{{ $staff->name }}</div>
                    <div style="font-size: 0.8rem; color: #666;">{{ $staff->role->name }}</div>
                </div>
                <div style="margin-left: auto; font-size: 0.8rem; color: #999;">
                    {{ $staff->created_at->diffForHumans() }}
                </div>
            </div>
            @endforeach
            <a href="{{ route('core.staff.index') }}" class="btn btn-primary" style="margin-top: 1rem; width: 100%; justify-content: center;">
                View All Staff
            </a>
        </div>
        
        <div>
            <h3 style="margin-bottom: 1rem; color: var(--primary-color);">Recent Notifications</h3>
            @foreach($recent_notifications as $notification)
            <div style="padding: 0.75rem; border-bottom: 1px solid #eee;">
                <div style="display: flex; justify-content: between; align-items: start; gap: 0.5rem;">
                    <span class="badge badge-{{ $notification->type == 'success' ? 'success' : ($notification->type == 'warning' ? 'warning' : ($notification->type == 'error' ? 'danger' : 'info')) }}" style="flex-shrink: 0;">
                        {{ ucfirst($notification->type) }}
                    </span>
                    <div style="flex: 1;">
                        <div style="font-weight: 600;">{{ $notification->title }}</div>
                        <div style="font-size: 0.8rem; color: #666;">to {{ $notification->staff->name }}</div>
                    </div>
                </div>
                <div style="margin-top: 0.5rem; font-size: 0.9rem;">
                    {{ Str::limit($notification->message, 100) }}
                </div>
                <div style="margin-top: 0.5rem; font-size: 0.8rem; color: #999;">
                    {{ $notification->created_at->diffForHumans() }}
                </div>
            </div>
            @endforeach
            <a href="{{ route('core.notification.index') }}" class="btn btn-primary" style="margin-top: 1rem; width: 100%; justify-content: center;">
                View All Notifications
            </a>
        </div>
    </div>
</div>

<div class="card">
    <h3 style="margin-bottom: 1rem; color: var(--primary-color);">Quick Actions</h3>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
        <a href="{{ route('core.staff.create') }}" class="btn btn-primary" style="justify-content: center;">
            <i>➕</i> Add Staff
        </a>
        <a href="{{ route('core.role.create') }}" class="btn btn-success" style="justify-content: center;">
            <i>🔐</i> Create Role
        </a>
        <a href="{{ route('core.notification.create') }}" class="btn btn-warning" style="justify-content: center;">
            <i>🔔</i> Send Notification
        </a>
        <a href="{{ route('chat.index') }}" class="btn btn-info" style="justify-content: center; background: #17a2b8;">
            <i>💬</i> Open Chat
        </a>
    </div>
</div>
@else
<div class="card">
    <h2>Department Access</h2>
    <p>You are currently viewing the system dashboard. For department-specific features, please use the sidebar navigation to access your department modules.</p>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-top: 2rem;">
        @can('customercare.dashboard.view')
        <a href="{{ route('customercare.dashboard') }}" class="btn btn-primary" style="justify-content: center;">
            <i>🎯</i> Customer Care Dashboard
        </a>
        @endcan
        
        @can('sales.dashboard.view')
        <a href="{{ route('sales.dashboard') }}" class="btn btn-success" style="justify-content: center;">
            <i>💰</i> Sales Dashboard
        </a>
        @endcan
        
        @can('finance.dashboard.view')
        <a href="{{ route('finance.dashboard') }}" class="btn btn-warning" style="justify-content: center;">
            <i>💳</i> Finance Dashboard
        </a>
        @endcan
        
        @can('compliance.dashboard.view')
        <a href="{{ route('compliance.dashboard') }}" class="btn btn-info" style="justify-content: center;">
            <i>🛡️</i> Compliance Dashboard
        </a>
        @endcan
    </div>
</div>
@endif
@endsection