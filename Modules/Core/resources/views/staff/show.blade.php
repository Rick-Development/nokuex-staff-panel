@extends('core::layouts.app')

@section('content')
<div class="card">
    <h2>Staff Details</h2>
    
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
        <div>
            <h3>Basic Information</h3>
            <p><strong>Name:</strong> {{ $staff->name }}</p>
            <p><strong>Email:</strong> {{ $staff->email }}</p>
            <p><strong>Phone:</strong> {{ $staff->phone ?? 'N/A' }}</p>
            <p><strong>Role:</strong> {{ $staff->role->name }}</p>
            <p><strong>Status:</strong> {{ $staff->is_active ? 'Active' : 'Inactive' }}</p>
            <p><strong>Joined:</strong> {{ $staff->created_at->format('M d, Y') }}</p>
        </div>
        
        <div>
            <h3>Activity</h3>
            <p><strong>Last Login:</strong> {{ $staff->last_login_at ? $staff->last_login_at->format('M d, Y H:i') : 'Never' }}</p>
            <p><strong>Audit Logs:</strong> {{ $staff->auditLogs->count() }} entries</p>
            <p><strong>Notifications:</strong> {{ $staff->notifications->count() }} total</p>
        </div>
    </div>
    
    <div style="margin-top: 2rem;">
        <a href="{{ route('core.staff.edit', $staff->id) }}" class="btn btn-warning">Edit</a>
        <a href="{{ route('core.staff.index') }}" class="btn btn-primary">Back to List</a>
    </div>
</div>
@endsection